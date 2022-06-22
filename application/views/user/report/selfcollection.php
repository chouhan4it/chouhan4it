<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$StrWhr .=" AND (tmp.member_id='".$member_id."')";


if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);	
	$StrWhr .=" AND DATE(tmp.date_time) BETWEEN '".$from_date."' AND '".$to_date."'";
	$SrchQ  = "&from_date=$from_date&to_date=$to_date";
}
if($_REQUEST['point_sub_type']!=''){
	$point_sub_type = FCrtRplc($_REQUEST['point_sub_type']);
	$StrWhr .=" AND tmp.point_sub_type='".$point_sub_type."'";
	$SrchQ  = "&point_sub_type=$point_sub_type";
}

$QR_PAGES = "SELECT tmp.*
			 FROM tbl_mem_point AS tmp
			 WHERE tmp.point_id>0 $StrWhr
			 GROUP BY tmp.point_id
			 ORDER BY tmp.point_id DESC";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container"> 
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Self Collection</h4>
        <p class="text-muted page-title-alt">Your Self Collection</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card-box">
          <div class="row">
            <div class="col-md-3">
              <form method="post" action="<?php echo generateMemberForm("report","selfcollection",""); ?>" enctype="multipart/form-data" name="form-page" id="form-page">
              	<div class="form-group">
                 	<select name="point_sub_type" class="form-control col-xs-3 col-sm-3" id="point_sub_type">
                    	<option value="">----select----</option>
						<?php echo DisplayCombo($_REQUEST['point_sub_type'],"POINT_TYPE"); ?>
					</select>
                </div>
				<div class="clearfix">&nbsp;</div>
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-3 col-sm-3  validate[required] date-picker" name="from_date" id="id-date-picker-1" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-3 col-sm-3  validate[required] date-picker" name="to_date" id="id-date-picker-1" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <input class="btn btn-sm btn-primary m-t-n-xs" name="searchRequest" value="Search" type="submit">
                <input type="button" class="btn btn-sm btn-danger m-t-n-xs" name="reset" id="reset" value="Reset" onClick="window.location.href='?'">
              </form>
            </div>
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="table-responsive">
            <table class="table table-actions-bar">
              <thead>
                <tr>
                  <th>Srl #</th>
                  <th>Trans. No </th>
                  <th>Type</th>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>B.V</th>
                </tr>
              </thead>
              <tbody>
                <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						$net_total_pv +=$AR_DT['point_pv'];
						$net_total_vol +=$AR_DT['point_vol'];
			       ?>
                <tr>
                  <td><?php echo $Ctrl; ?></td>
                  <td><?php echo $AR_DT['point_ref']; ?></td>
                  <td><?php echo $AR_DT['point_sub_type']; ?></td>
                  <td><?php echo $AR_DT['date_time']; ?></td>
                  <td><?php echo number_format($AR_DT['point_vol']); ?></td>
                  <td><?php echo number_format($AR_DT['point_pv']); ?></td>
                </tr>
                <?php $Ctrl++; endforeach; }else{ ?>
                <?php } ?>
                <tr>
                  <td colspan="4" align="right"><strong>B.V</strong></td>
                  <td align="center"><?php echo number_format($net_total_vol); ?></td>
                  <td><strong><?php echo number_format($net_total_pv); ?></strong></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="col-xs-4">
              <div aria-live="polite" role="status" id="dynamic-table_info" class="dataTables_info"> Showing <?php echo $PageVal['RecordStart']+1; ?> to <?php echo  count($PageVal['ResultSet']); ?> of <?php echo $PageVal['TotalRecords']; ?> entries </div>
            </div>
            <div class="col-xs-8">
              <div id="dynamic-table_paginate" class="dataTables_paginate paging_simple_numbers">
                <ul class="pagination">
                  <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end col --> 
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
	});
</script>
</html>
