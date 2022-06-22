<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$today_date = InsertDate(getLocalTime());	
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	
	$member_id = $this->session->userdata('mem_id');
	
	if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
		$from_date = InsertDate($_REQUEST['from_date']);
		$to_date = InsertDate($_REQUEST['to_date']);
		$StrWhr .=" AND DATE(tcr.date_from) BETWEEN '$from_date' AND '$to_date'";
		$SrchQ .="&from_date=$from_date&to_date=$to_date";
	}
		
	$QR_PAGES= "SELECT tcr.*, tm.user_id, tm.full_name,
			    tm.ifc_code , tm.branch, CONCAT_WS(':','AC',tm.account_number) AS account_no, tm.bank_name
				FROM ".prefix."tbl_cmsn_royalty AS tcr 
			    LEFT JOIN tbl_members AS tm ON tm.member_id=tcr.member_id
			    WHERE tcr.member_id='".$member_id."'  $StrWhr ORDER BY tcr.royalty_cmsn_id DESC";
	$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
	
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
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
        <h4 class="page-title">Royalty  Income</h4>
        <p class="text-muted page-title-alt">Your Royalty2 Income</p>
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
                      <form id="form-search" name="form-search" method="get" action="<?php echo generateMemberForm("report","royaltyincome",""); ?>">
                        <b>Date </b>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                          <div class="input-group">
                            <input class="form-control col-xs-3 col-sm-3  date-picker" name="from_date" id="id-date-picker-1" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                            <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group">
                            <input class="form-control col-xs-3 col-sm-3 date-picker" name="to_date" id="id-date-picker-1" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                            <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                        <a href="<?php echo generateMemberForm("report","royaltyincome",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
                      </form>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
                        <thead>
                      <tr role="row">
                        <th  class="">Sr No. </th>
                        <th  class="">From Date</th>
                        <th  class="">To Date</th>
                        <th  class="">Royalty</th>
                        <th  class="">Royalty Achivers</th>
                        <th  class="">Amount</th>
                        <th  class="">Admin Charge</th>
                        <th  class="">Tds</th>
                        <th  class="">Net Amount </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
						if($PageVal['TotalRecords'] > 0){
						$Ctrl=$PageVal['RecordStart']+1;
							foreach($PageVal['ResultSet'] as $AR_DT):
						?>
                      <tr class="odd" role="row">
                        <td class=""><?php echo $Ctrl; ?></td>
                        <td class=""><?php echo DisplayDate($AR_DT['date_from']); ?></td>
                        <td><?php echo DisplayDate($AR_DT['date_end']); ?></td>
                        <td><?php echo number_format($AR_DT['royalty_total']); ?></td>
                        <td><?php echo $AR_DT['royalty_achiver']; ?> </td>
                        <td><?php echo number_format($AR_DT['total_income'],2); ?></td>
                        <td><?php echo number_format($AR_DT['admin_charge'],2); ?></td>
                        <td><?php echo number_format($AR_DT['tds_charge'],2); ?></td>
                        <td><?php echo number_format($AR_DT['net_income'],2); ?></td>
                      </tr>
                      <?php $Ctrl++; endforeach;
						}
						 ?>
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
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
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
