<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/chosen.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
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
<style type="text/css">
.danger_alert {
	background-color: #f2dede;
	border-color: #ebccd1;
	color: #a94442;
}
.success_alert {
	background-color: #dff0d8;
	border-color: #d6e9c6;
	color: #3c763d;
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
          <h1> Plan <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Add / Update</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo generateAdminForm("membership","addplan",""); ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Plan Type :</label>
                <div class="col-sm-9">
                  <select name="package_type" id="form-field-1"  class="col-xs-6 col-sm-3 validate[required] package_type">
                    <option value="">-----select plan-----</option>
                    <?php DisplayCombo($ROW['package_type'],"JOIN_TYPE"); ?>
                  </select>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Plan Name :</label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="" name="package_name"  class="col-xs-9 col-sm-6 validate[required]" type="text" value="<?php echo $ROW['package_name']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Plan Chrage :</label>
                <div class="col-sm-9">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input id="form-field-1" placeholder="" name="package_price"  class="col-xs-6 col-sm-3 validate[required,custom[integer]]"  type="text" value="<?php echo $ROW['package_price']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> CCL :</label>
                <div class="col-sm-3">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input class="form-control col-xs-6 col-sm-3  validate[required,custom[integer]]" name="package_ccl" id="form-field-1" value="<?php echo $ROW['package_ccl']; ?>" type="text"  />
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Monthely rent/ charge :</label>
                <div class="col-sm-3">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input class="form-control col-xs-6 col-sm-3  validate[required,custom[integer]]" name="monthly_rent" id="form-field-1" value="<?php echo $ROW['monthly_rent']; ?>" type="text"  />
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Yearly rent/ charge :</label>
                <div class="col-sm-3">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input class="form-control col-xs-6 col-sm-3  validate[required,custom[integer]]" name="yearly_rent" id="form-field-1" value="<?php echo $ROW['yearly_rent']; ?>" type="text"  />
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Intrest on ccl per month  :</label>
                <div class="col-sm-9">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                    <input name="int_ccl_month" type="text" class="col-xs-6 col-sm-3 validate[required]" id="form-field-1" value="<?php echo $ROW['int_ccl_month']; ?>" placeholder="Intrest on ccl per month">
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Maximam ccl:</label>
                <div class="col-sm-9">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input name="max_ccl" type="text" class="col-xs-6 col-sm-3 validate[required]"  id="form-field-1" value="<?php echo $ROW['max_ccl']; ?>" placeholder="Maximam ccl">
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Amount receive Maximum per transaction:</label>
                <div class="col-sm-9">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input name="max_transaction" type="text" class="col-xs-6 col-sm-3 validate[required]" id="form-field-1" value="<?php echo $ROW['max_transaction']; ?>" placeholder="Amount receive maxium per transaction">
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Maximum limit a dey receiving:</label>
                <div class="col-sm-9">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input name="max_limit_receive" type="text" class="col-xs-6 col-sm-3 validate[required]" id="form-field-1" value="<?php echo $ROW['max_limit_receive']; ?>" placeholder="Maximum limit a dey receiving">
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Maximum transaction  a day:</label>
                <div class="col-sm-9">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input name="max_trns_day" type="text" class="col-xs-6 col-sm-3 validate[required]" id="form-field-1" value="<?php echo $ROW['max_trns_day']; ?>" placeholder="Maximum transaction  a day">
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group agent-section">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Maximum Agent:</label>
                <div class="col-sm-9">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input name="max_agent" type="text" class="col-xs-6 col-sm-3 validate[required]" id="form-field-1" value="<?php echo $ROW['max_agent']; ?>" placeholder="Maximum Agent">
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group agent-section">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Maximum Member:</label>
                <div class="col-sm-9">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input name="max_member" type="text" class="col-xs-6 col-sm-3 validate[required]" id="form-field-1" value="<?php echo $ROW['max_member']; ?>" placeholder="Maximum Agent">
                  </div>
                </div>
              </div>
              <div class="clearfix space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <input type="hidden" name="package_id" id="package_id" value="<?php echo $ROW['package_id']; ?>">
                  <button type="submit" name="submitPackage" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button onClick="window.location.href='<?php echo generateSeoUrlAdmin("membership","viewplan","");  ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$(".agent-section").hide();
		$("#form-valid").validationEngine();
		$(".package_type").on('change blur',function(){
			var package_type = $(this).val();
			if(package_type=="A"){
				$(".agent-section").slideDown(600);
			}
			if(package_type=="M"){
				$(".agent-section").slideUp(600);
			}
		});
	});
</script>
</body>
</html>
