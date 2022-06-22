<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(2);
$order_id = _d($segment['order_id']);

$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.current_address,
			 tm.city_name, tm.state_name, tm.country_name, tm.pin_code, tm.member_mobile,
			 tad.current_address AS order_address, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name, tad.pin_code AS ship_pin_code,
			 tad.mobile_number AS ship_mobile_number,  tos.name AS order_state
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0  AND ord.order_id='".$order_id."'
			 GROUP BY ord.order_id";
$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,true);
$AR_STORE = $model->getFranchiseeDetail($AR_ORDER['franchisee_id']);
?>
<!DOCTYPE html>
<html>
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
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
        
        <ol class="breadcrumb">
          <li> <a href="javascript:void(0)">Order</a> </li>
          <li class="active"> Cancel </li>
        </ol>
      </div>
    </div>
    <!-- Page-Title -->
    <!-- SECTION FILTER
                ================================================== -->
    <div class="row">
      <div class="col-sm-12">
        <?php echo get_message(); ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
			  	<div class="row">
                
                <form action="<?php echo generateMemberForm("order","cancel",array("order_id"=>_e($AR_ORDER['order_id']))); ?>" method="post" name="form-valid" id="form-valid" enctype="multipart/form-data">
				<div class="col-md-5">
                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Order Detail</b></h5>
				 <div class="form-group m-b-20 address-filed">
                  <label>Return Option </label>
                  <select class="form-control validate[required]" name="id_order_state" id="id_order_state" >
                  	<option value="">----select-----</option>
                    <?php echo  DisplayCombo($_REQUEST['id_order_state'],"ORDER_STATE_RETURN"); ?>
                  </select>
                 
                </div>
				 <div class="form-group m-b-20 address-filed">
                  <label>Order No </label>
                  <input value="<?php echo $AR_ORDER['order_no']; ?>" class="form-control validate[required]" placeholder="Order No" name="order_no" id="order_no" type="text" readonly>
                </div>
                 <div class="form-group m-b-20 address-filed">
                  <label>Seller Name  </label>
                  <input value="<?php echo $AR_STORE['store']; ?>" class="form-control validate[required]" placeholder="Seller" name="store" id="store" type="text" readonly>
                </div>
                <div class="form-group m-b-20 address-filed">
                  <label>Reason </label>
                  <textarea class="form-control validate[required,minSize[10]]" name="cancel_reason" id="cancel_reason" placeholder="Please let us know any reason"><?php echo $_REQUEST['cancel_reason']; ?></textarea>
                </div>
                 
				<div class="form-group m-b-20">
                   <button type="submit" name="submit-cancel" value="1" class="btn w-sm btn-default waves-effect waves-light">  <i class="fa fa-times"></i> Submit </button>
                   <a  href="<?php echo generateSeoUrlMember("order","orderlist",""); ?>" class="btn w-sm btn-danger">  <i class="fa fa-arrow-left"></i> Back </a>
                </div>
              </div>
               </form>
			  </div>
			  </div>
            </div>
          </div>
          
       
      </div>
    </div>
    <!-- End row -->
    <!-- Footer -->
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
    <!-- End Footer -->
  </div>
  <!-- end container -->
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
	
	});
</script>
</html>
