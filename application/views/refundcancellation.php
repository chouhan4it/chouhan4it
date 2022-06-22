<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	$model = new OperationModel();
	$AR_CMS = $model->getCMS(5);	
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
<!--<![endif]-->

<!-- Mirrored from opencart.dostguru.com/FD01/fruitino_04/index.php?route=account/login by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Dec 2021 08:33:10 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<!-- /Added by HTTrack -->
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<?php $this->load->view('layout/svgsymbol'); ?>
<body>
<?php $this->load->view('layout/header'); ?>
<div id="information-information" class="container">
  <ul class="breadcrumb">
    <li><a href="<?php echo BASE_PATH; ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="javascript:void(0)">Refund & Cancellation </a></li>
  </ul>
  <div class="row">
    <div id="content" class="col-sm-12">
    	<div class="clearfix">&nbsp;</div>
      <div class="col-lg-12 col-md-12"> 
      		<?php echo $AR_CMS['content']; ?>
			
      </div>
      
    </div>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
</body>
</html>
