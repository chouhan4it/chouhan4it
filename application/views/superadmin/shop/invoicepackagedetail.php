<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(2);

$subcription_id = _d($segment['subcription_id']);

$AR_SUB = $model->getSubscription($subcription_id);

$member_id = $AR_SUB['member_id'];
$AR_MEM = $model->getMember($member_id);

$AR_PACK = $model->getPinType($AR_SUB['type_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title><?php echo title_name(); ?></title>
<meta name="description" content="Static &amp; Dynamic Tables" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/fonts.googleapis.com.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
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
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
<![endif]-->
<style type="text/css">
tr > td {
	font-size: 11px;
}
</style>
</head>
<body class="no-skin">
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Invoice Package <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; View </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12"> <?php echo get_message(); ?> 
            <!-- PAGE CONTENT BEGINS -->
            <div class="space-6"></div>
            <div class="row">
              <div class="col-sm-10 col-sm-offset-1">
                <div  class="print_area">
                  <div class="widget-box transparent">
                    <div class="widget-body">
                      <div class="widget-main padding-24">
                        <div class="row">
                          <div class="col-md-12"> <a href="<?php echo generateSeoUrlAdmin("shop","invoicepackagelist",""); ?>"  > <i class="ace-icon fa fa-arrow-left"></i> </a> <a href="javascript:void(0)" id="PrintImg"> <i class="ace-icon fa fa-print"></i> </a> <a target="_blank" href="<?php echo generateSeoUrlAdmin("htmltopdf","packageinvoice",array("subcription_id"=>_e($AR_SUB['subcription_id']))); ?>" > <i class="ace-icon fa fa-file-pdf-o"></i> </a>
                            <div>
                              <table width="100%" border="0" >
                                <tr>
                                  <td colspan="2" align="left"   ><img src="<?php echo LOGO; ?>" width="200" alt="<?php echo WEBSITE; ?>"></td>
                                </tr>
                                <tr>
                                  <td   ><strong>&nbsp;&nbsp;&nbsp;Invoice  No :-</strong> <?php echo $AR_SUB['order_no']; ?></td>
                                  <td ><strong>Date :-</strong> <?php echo DisplayDate($AR_SUB['date_from']); ?></td>
                                </tr>
                                <tr>
                                  <td   ><strong>&nbsp;&nbsp;Member Name :-</strong></td>
                                  <td ><?php echo $AR_MEM['full_name']; ?></td>
                                </tr>
                                <tr>
                                  <td valign="top"  ><strong>&nbsp;&nbsp;Member Code :-</strong></td>
                                  <td ><?php echo $AR_MEM['user_id']; ?></td>
                                </tr>
                                <tr>
                                  <td ><strong>&nbsp;&nbsp;Received With thanks from :-</strong></td>
                                  <td ><?php echo getTool($AR_SUB['net_amount'],"NA"); ?> /-</td>
                                </tr>
                                <tr>
                                  <td ><strong>&nbsp;&nbsp;Package Type :-</strong></td>
                                  <td ><?php echo $AR_PACK['pin_name']; ?></td>
                                </tr>
                                <tr>
                                  <td ><strong>&nbsp;&nbsp;Amount Paid :-</strong></td>
                                  <td ><?php echo number_format($AR_SUB['net_amount'],2); ?></td>
                                </tr>
                                <tr>
                                  <td ><strong>&nbsp;&nbsp;Amount Paid in Word :-</strong></td>
                                  <td ><?php echo convert_number($AR_SUB['net_amount']); ?> Only /-</td>
                                </tr>
                                <tr>
                                  <td ><strong>&nbsp;&nbsp;Date of Joining :-</strong></td>
                                  <td valign="top"><?php echo $AR_MEM['date_join']; ?></td>
                                </tr>
                                <tr>
                                  <td ><strong>&nbsp;&nbsp;Distributor Address :-</strong></td>
                                  <td ><?php echo $AR_MEM['current_address']." ".$AR_MEM['state_name']." ".$AR_MEM['city_name']." Pincode : ".$AR_MEM['pin_code']; ?></td>
                                </tr>
                                <tr>
                                  <td >&nbsp;</td>
                                  <td >&nbsp;</td>
                                </tr>
                              </table>
                            </div>
                            <div class="hr hr8 hr-double hr-dotted"></div>
                          </div>
                        </div>
                        <div></div>
                        <div class="row">
                          <div class="col-sm-9">This is a Computer Generated Invoice</div>
                          <div class="col-sm-3 pull-right">&nbsp;</div>
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
    </div>
  </div>
</div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script> 
<script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/jquery/jquery.print.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){$( "#PrintImg" ).click(function(){$( ".print_area" ).print(); return( false );});});
</script>
</body>
</html>
