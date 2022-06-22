<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
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
          <h1>Order <small> <i class="ace-icon fa fa-angle-double-right"></i>&nbsp; Payment </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo generateAdminForm("shop","orderpayment",""); ?>" method="post" enctype="multipart/form-data">
              
              
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="order_no">Order No </label>
                <div class="col-sm-9">
                  <input id="order_no" placeholder="Order No" name="order_no"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $_REQUEST['order_no']; ?>">
                </div>
              </div>
                <div class="space-4"></div>
               <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="order_no">Order Amount </label>
                <div class="col-sm-9">
                  <input id="order_amount" placeholder="Order Amount" name="order_amount"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $_REQUEST['order_amount']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="transaction_no">Transaction No </label>
                <div class="col-sm-9">
                  <input id="transaction_no" placeholder="Transaction No" name="transaction_no"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $_REQUEST['transaction_no']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="payment_type">Payment Detail </label>
                <div class="col-sm-9">
                  <textarea name="payment_type" class="col-xs-10 col-sm-5 validate[required]" id="payment_type" placeholder="Payment Detail"><?php echo $_REQUEST['payment_type']; ?></textarea>
                </div>
              </div>
               <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="transaction_no">Payment Receipt </label>
                <div class="col-sm-9">
                
                  <input id="receipt_file"  name="receipt_file"  class="col-xs-10 col-sm-5" type="file" >
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="pay_status">Payment Status </label>
                <div class="col-sm-9">
                  <select  class="col-xs-10 col-sm-5 validate[required]" name="pay_status" id="pay_status">
                  	<option value="">----select-----</option>
                    <?php echo DisplayCombo($_REQUEST['pay_status'],"YESNOFLAG"); ?>
                  </select>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <button type="submit" name="submit-payment" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button onClick="window.location.href='<?php echo generateSeoUrlAdmin("report","orderpayment",""); ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                </div>
              </div>
            </form>
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$("#order_no").on('blur',function(){
			var order_no = $("#order_no").val();
			var URL_LOAD = "<?php echo generateSeoUrlAdmin("json","jsonhandler"); ?>";
			var data = {
				switch_type : "ORDER_AMOUNT",
				order_no : order_no
			}
			$.getJSON(URL_LOAD,data,function(json_val){
				if(json_val){
					if(json_val.error_sts>0){
						$("#order_amount").val(json_val.total_amount);
					}
				}
			});
		});
	});
</script>
</body>
</html>
