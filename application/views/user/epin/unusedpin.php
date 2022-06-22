<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$member_id = $this->session->userdata('mem_id');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}


if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);
	$StrWhr .=" AND DATE(tpd.date_time) BETWEEN '".$from_date."' AND '".$to_date."'";
	$SrchQ .="&from_date=$from_date&to_date=$to_date";
}
if($_REQUEST['type_id']>0){
	$type_id = FCrtRplc($_REQUEST['type_id']);
	$StrWhr .=" AND tpd.type_id='".$type_id."'";
	$SrchQ .="&type_id=$type_id";
}
if($_REQUEST['pin_no']>0){
	$pin_no = FCrtRplc($_REQUEST['pin_no']);
	$StrWhr .=" AND ( tpd.pin_no LIKE '%$pin_no%' OR tpd.pin_key LIKE '%$pin_no%' )";
	$SrchQ .="&pin_no=$pin_no";
}
$QR_PAGES= "SELECT tpd.*, tm.user_id, tm.first_name, tm.last_name, tpy.pin_name, tpd.pin_price 
			FROM tbl_pinsdetails AS tpd 
			LEFT JOIN tbl_pinsmaster AS tpm ON tpd.mstr_id=tpm.mstr_id
			LEFT JOIN tbl_members AS tm ON tpd.member_id=tm.member_id
			LEFT JOIN tbl_pintype AS tpy ON tpd.type_id=tpy.type_id WHERE tpd.pin_sts='N' 
			AND tpd.member_id>0 AND ( tpd.member_id='".$member_id."'  ) $StrWhr 
			GROUP BY tpd.pin_id
			ORDER BY tpd.pin_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
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
<style type="text/css">
.img-circle {
	border-radius: 50%;
}
.item-pic {
	width: 30px;
}
tr > td {
	font-size: 12px !important;
}
</style>
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
        <h4 class="page-title">My Un-Used Pin</h4>
        <p class="text-muted page-title-alt">Your Un-Used Pin</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row"> 
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <div class="portlet-body" >
            <div class="row"> <?php echo get_message(); ?>
              <div class="col-lg-12">
                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="">
                  <div class="row">
                    <div class="col-md-12">
                      <form id="form-search" name="form-search" method="get" action="<?php echo generateMemberForm("epin","unusedpin",""); ?>">
                        <b>Package </b>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                          <select name="type_id" id="type_id" class="form-control input-xlarge">
                            <option value="">----select package----</option>
                            <?php echo DisplayCombo($_REQUEST['type_id'],"PIN_TYPE"); ?>
                          </select>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <b>Date from </b>
                        <div class="clearfix">&nbsp;</div>
                        <div class="input-group">
                          <input class="form-control  validate[required] date-picker" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                          <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                        <b>To</b>
                        <div class="input-group">
                          <input class="form-control  validate[required] date-picker" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                          <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                        <div class="clearfix">&nbsp;</div>
                        <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                        <a   href="<?php  echo generateSeoUrlMember("epin","transferepin",array()); ?>"  class="btn btn-success">Transfer Pin</a>
                        <a href="<?php echo generateMemberForm("epin","unusedpin",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
                      </form>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-condensed">
                        <thead>
                          <tr>
                            <th>Srl # </th>
                            <th>Transfer Date </th>
                            <th>Used Date </th>
                            <th>Used By </th>
                            <th>E-Pin Type </th>
                            <th>E-Pin Number </th>
                            <th>E-Pin Key </th>
                            <th>E-Pin Value </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
					if($PageVal['TotalRecords'] > 0){
					$Ctrl=1;
					foreach($PageVal['ResultSet'] as $AR_DT):
					?>
                          <tr>
                            <td><?php echo $Ctrl; ?></td>
                            <td><?php echo DisplayDate($AR_DT['date_time']); ?></td>
                            <td><?php echo ($AR_DT['used_date']=="0000-00-00")? "N/A":DisplayDate($AR_DT['used_date']); ?></td>
                            <td><?php echo $AR_DT['use_first_name']."(".$AR_DT['use_user_id'].")"; ?></td>
                            <td><?php echo $AR_DT['pin_name']; ?></td>
                            <td><?php echo highlightWords($AR_DT['pin_no'],$_REQUEST['pin_no']); ?></td>
                            <td><?php echo highlightWords($AR_DT['pin_key'],$_REQUEST['pin_no']); ?></td>
                            <td><?php echo $AR_DT['pin_price']; ?></td>
                          </tr>
                          <?php $Ctrl++; endforeach; }else{ ?>
                          <tr>
                            <td colspan="8" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-6">
                      <div aria-live="polite" role="status" id="dynamic-table_info" class="dataTables_info"> Showing <?php echo $PageVal['RecordStart']+1; ?> to <?php echo  count($PageVal['ResultSet']); ?> of <?php echo $PageVal['TotalRecords']; ?> entries </div>
                    </div>
                    <div class="col-xs-6">
                      <div id="dynamic-table_paginate" class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                          <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); auto_complete(); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
	});
</script>
</html>
