<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$today_date = InsertDate(getLocalTime());
	$member_id = $this->session->userdata('mem_id');
	$user_id = $this->session->userdata('user_id');
	$AR_MEM = $model->getMember($member_id);	
	
	$order_count = $model->getOrderCount($member_id);
	$no_order_bv = $model->getOrderCount();
	
	$downline_count = $model->BinaryCount($member_id,"TotalCount");
	$direct_count = $model->BinaryCount($member_id,"DirectCount");
	
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container">
    <div class="wrapper-page">
      <div class="ex-page-content text-center">
        <div class="text-error"> <span class="text-primary">4</span><i class="ti-face-sad text-pink"></i><span class="text-info">3</span> </div>
        <h2>Under-construction! Page is under construction</h2>
        <br>
        <p class="text-muted"> We are working on this page , we make it live soon..... </p>
        <p class="text-muted"> Use the navigation above or the button below to get back and track. </p>
        <br>
        <a class="btn btn-default waves-effect waves-light" href="<?php echo MEMBER_PATH; ?>"> Return Home</a> </div>
    </div>
    
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
    
  </div>
  <!-- end container -->
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
</html>
