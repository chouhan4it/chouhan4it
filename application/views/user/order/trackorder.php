<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');

if($_REQUEST['order_no']!=''){
	$order_no = FCrtRplc($_REQUEST['order_no']);
	$StrWhr .=" AND ( ord.order_no = '$order_no' )";
	$SrchQ .="&order_no=$order_no";
}

$QR_PAGES = "SELECT  ord.order_no, tac.waybill, tac.date_time, tac.track_status
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_api_courier AS tac ON tac.order_id=ord.order_id
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0 AND ord.member_id='".$member_id."' AND tac.waybill!=''
			 $StrWhr
			 GROUP BY ord.order_id
			 ORDER BY ord.order_id DESC";
$RS_PAGES = $this->SqlModel->runQuery($QR_PAGES);
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
        <h4 class="page-title"> Track Order</h4>
        <p class="text-muted page-title-alt">You can track you order</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
      <?php echo get_message(); ?>
        <div class="card-box">
          <div class="row">
            <div class="col-md-3">
              <form method="post" action="<?php echo generateMemberForm("order","trackorder",""); ?>" enctype="multipart/form-data" name="form-page" id="form-page">
                <div class="form-group">
                  <input class="form-control col-xs-3 col-sm-3  validate[required]" name="order_no" id="order_no" value="<?php echo $_REQUEST['order_no']; ?>" type="text"  />
                </div>
				<div class="clearfix">&nbsp;</div>
                <input class="btn btn-sm btn-primary m-t-n-xs" name="searchRequest" value="Track Order" type="submit">
                <input type="button" class="btn btn-sm btn-danger m-t-n-xs" name="reset" id="reset" value="Reset" onClick="window.location.href='?'">
              </form>
            </div>
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="table-responsive">
            <table class="table table-actions-bar">
              <thead>
                <tr>
                  <th>Order No </th>
                  <th>AWB Number </th>
                  <th>Status Date </th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php 
			 	 	if(count($RS_PAGES) > 0){
				  		$Ctrl=1;
						foreach($RS_PAGES as $AR_DT):
			       ?>
                <tr>
                  <td><?php echo $AR_DT['order_no']; ?></td>
                  <td><?php echo getTool($AR_DT['waybill'],'N/A'); ?></td>
                  <td><?php echo getDateFormat($AR_DT['date_time'],"d D M Y h:i");?></td>
                  <td><?php if($AR_DT['waybill']!=''){ ?><a href="<?php echo generateSeoUrlMember("order","trackcourier",array("waybill"=>($AR_DT['waybill']))); ?>" class="btn btn-success">Status</a><?php } ?></td>
                </tr>
                
                <?php 
				 		$Ctrl++; 
						endforeach;
				  }else{
				 	
				  ?>
				  <tr>
                  <td colspan="4" class="text text-danger">
				  	No order found
					</td>
                </tr>
				  <?php } ?>
              </tbody>
            </table>
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
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
	});
</script>
</html>
