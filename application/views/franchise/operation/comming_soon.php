<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$no_of_order = $model->getNoOfOrder();
$total_order = $model->getSumOfOrder();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title><?php echo title_name(); ?></title>
<meta name="description" content="overview &amp; stats" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/fonts.googleapis.com.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-skins.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-rtl.min.css" />
<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
      <ul class="breadcrumb">
        <li> <i class="ace-icon fa fa-home home-icon"></i> <a href="#">Home</a> </li>
        <li class="active">Under-construction</li>
      </ul>
    </div>
    <div class="page-content">
      <div class="row">
        <div class="col-xs-12 col-sm-12">
          <div class="widget-box">
            <div class="widget-body">
              <div class="panel-body list">
                <div class="table-responsive project-list">
                  <div class="ex-page-content text-center">
                    <h2>Under-construction! Page is under construction</h2>
                    <br>
                    <p class="text-muted"> We are working on this page , we make it live soon..... </p>
                    <p class="text-muted"> Use the navigation above or the button below to get back and track. </p>
                    <br>
                    <a class="btn btn-default waves-effect waves-light" href="<?php echo FRANCHISE_PATH; ?>"> Return Home</a> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.span -->
      </div>
    </div>
  </div>
</div>
<!-- /.main-container -->
<!-- basic scripts -->
<!--[if !IE]> -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<!-- <![endif]-->
<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo BASE_PATH; ?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script>
<!-- page specific plugin scripts -->
<!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
		<![endif]-->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-ui.custom.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/jquery.easypiechart.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/jquery.sparkline.index.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/jquery.flot.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/jquery.flot.pie.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/jquery.flot.resize.min.js"></script>
<!-- ace scripts -->
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
</body>
</html>
