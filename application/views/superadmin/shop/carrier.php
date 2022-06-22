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
          <h1>Carrier <small> <i class="ace-icon fa fa-angle-double-right"></i>&nbsp; Add / Update </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  enctype="multipart/form-data" name="form-page" id="form-page" action="<?php echo generateAdminForm("shop","carrier",""); ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="carrier_name"> Carrier Name </label>
                <div class="col-sm-9">
                  <input id="carrier_name" placeholder="Carrier Name" name="carrier_name"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['carrier_name']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="carrier_logo"> Carrier Logo </label>
                <div class="col-sm-9">
                  <input id="carrier_logo" placeholder="Carrier Logo" name="carrier_logo"  class="col-xs-10 col-sm-5" type="file" >
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="carrier_flat_charge"> Flat Charge </label>
                <div class="col-sm-9">
                  <input id="carrier_flat_charge" placeholder="Flat Charge" name="carrier_flat_charge"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['carrier_flat_charge']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="carrier_free"> Free Shipping </label>
                <div class="col-sm-9">
                  <select name="carrier_free" id="carrier_free" class="col-xs-10 col-sm-5 validate[required]">
                    <option value="">-----select----</option>
                    <?php echo DisplayCombo($ROW['carrier_free'],"YN"); ?>
                  </select>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group" id="ship-option" style="display:none;">
                <label class="col-sm-3 control-label no-padding-right" > Range </label>
                <div class="col-sm-9">
                  <div class="clearfix">
                    <div class="row" style="overflow-y:scroll; height:300px;" >
                      <?php 
					
                    $QR_PIN = "SELECT DISTINCT tp.city_name AS area_name FROM tbl_pincode AS tp WHERE tp.pincode_id>0 ORDER BY tp.city_name ASC";
                    $RS_PIN = $this->SqlModel->runQuery($QR_PIN);
                   	
                    ?>
                      <?php  foreach($RS_PIN as $AR_PIN): ?>
                      <div class="col-md-3" align="center"> <?php echo $AR_PIN['area_name']; ?> </div>
                      <div class="col-md-3" align="center">
                        <input type="text" name="carrier_charge[<?php echo $AR_PIN['area_name']; ?>]" id="charge<?php echo $AR_PIN['area_name']; ?>"  value="0" >
                      </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <input type="hidden" name="carrier_id" id="carrier_id" value="<?php echo _e($ROW['carrier_id']); ?>">
                  <button type="submit" name="submitCarrier" id="submitCarrier" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button onClick="window.location.href='<?php echo generateSeoUrlAdmin("shop","carrierlist",""); ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
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
		$("#form-page").validationEngine();
		$("#carrier_free").on('change',function(){
			var carrier_free = parseInt($("#carrier_free").val());
			if(carrier_free==0){
				$("#ship-option").slideDown(600);
			}else{
				$("#ship-option").slideUp(600);
			}
		});
	});
</script>
</body>
</html>
