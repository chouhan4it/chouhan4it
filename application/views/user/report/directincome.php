<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$today_date = InsertDate(getLocalTime());	
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	
	$member_id = $this->session->userdata('mem_id');
	
	if($_REQUEST['order_no']!=''){
		$order_no = FCrtRplc($_REQUEST['order_no']);
		$StrWhr .=" AND ord.order_no LIKE '%".$order_no."%'";
		$SrchQ .="&order_no=$order_no";
	}
		
	$QR_MEM = "SELECT tcd.*, tm.full_name, tmf.full_name AS from_full_name, tmf.user_id AS from_user_id 
			   FROM  tbl_cmsn_direct AS tcd	
			   LEFT JOIN  tbl_members AS tm  ON tm.member_id=tcd.member_id
			   LEFT JOIN  tbl_members AS tmf  ON tmf.member_id=tcd.from_member_id
			   WHERE tcd.member_id='".$member_id."' 
			   $StrWhr 
			   ORDER BY tcd.direct_id DESC, tcd.date_time DESC";
	$PageVal = DisplayPages($QR_MEM, 100, $Page, $SrchQ);
	
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
.item-pic{
	width:30px;
}
tr > td {
	font-size:12px !important;
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
        <h4 class="page-title">Direct Referral Income</h4>
        <p class="text-muted page-title-alt">Your Direct Referral Income</p>
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
                      <form id="form-search" name="form-search" method="get" action="<?php echo generateMemberForm("report","directincome",""); ?>">
                        <b>Order No </b>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                          <input name="order_no" type="text" class="form-control" id="order_no" value="<?php echo $_REQUEST['order_no']; ?>" placeholder="Order No" style="width:200px;" />
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        
                        <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                        <a href="<?php echo generateMemberForm("report","directincome",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
                      </form>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
                        <tr role="row">
                          <td><strong>Srl # </strong></td>
                          <td><strong>From User</strong></td>
                          <td><strong>Date</strong></td>
                          <td ><strong>Order Amount</strong></td>
                          <td ><strong>Ratio (%)</strong></td>
                          <td ><strong>Total Income</strong></td>
                          <td><strong>Facility Charge</strong></td>
                          <td><strong>Tds Charge</strong></td>
                          <td><strong>Net Income</strong></td>
                        </tr>
						<?php 
                        if($PageVal['TotalRecords'] > 0){
                        $Ctrl = $PageVal['RecordStart']+1;
                        foreach($PageVal['ResultSet'] as $AR_DT):
                        ?>
                        <tr class="" style="cursor:pointer">
                          <td><?php echo $Ctrl;?></td>
                          <td><?php echo ($AR_DT['from_full_name']);?> &nbsp; [<?php echo $AR_DT['from_user_id']; ?>]</td>
                          <td>&nbsp;<?php echo DisplayDate($AR_DT['date_time']);?></td>
                          <td><?php echo number_format($AR_DT['total_collection'],2);?></td>
                          <td><?php echo number_format($AR_DT['income_percent']);?></td>
                          <td><?php echo number_format($AR_DT['total_income'],2);?></td>
                          <td><?php echo number_format($AR_DT['admin_charge'],2);?></td>
                          <td><?php echo number_format($AR_DT['tds_charge'],2);?></td>
                          <td><?php echo number_format($AR_DT['net_income'],2);?></td>
                        </tr>
                        <?php 
						$Ctrl++;
						endforeach; ?>
                        <?php }else{?>
                        <tr>
                          <td colspan="9" align="center" class="errMsg">No record found</td>
                        </tr>
                        <?php } ?>
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
