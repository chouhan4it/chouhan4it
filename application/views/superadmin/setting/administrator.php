<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$CONFIG_TIME = $model->getValue("CONFIG_TIME");
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
          <h1> Business <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Settings Administrator </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo generateAdminForm("setting","administrator","");  ?>" method="post">
            
              <div class="space-4"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="CONFIG_REGISTER">Registration:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input type="radio" name="CONFIG_REGISTER" id="CONFIG_REGISTER" <?php echo checkRadio($model->getValue("CONFIG_REGISTER"),"Y"); ?> value="Y">
                    Block &nbsp;&nbsp;
                    <input type="radio" name="CONFIG_REGISTER" id="CONFIG_REGISTER" <?php echo checkRadio($model->getValue("CONFIG_REGISTER"),"N"); ?> value="N">
                    Un-Block </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="CONFIG_LOGIN">Login:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input type="radio" name="CONFIG_LOGIN" id="CONFIG_LOGIN" <?php echo checkRadio($model->getValue("CONFIG_LOGIN"),"Y"); ?> value="Y">
                    Block &nbsp;&nbsp;
                    <input type="radio" name="CONFIG_LOGIN" id="CONFIG_LOGIN" <?php echo checkRadio($model->getValue("CONFIG_LOGIN"),"N"); ?> value="N">
                    Un-Block </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Order Return Days: </label>
                <div class="col-sm-9">
                  <input type="text"  name="CONFIG_ORDER_RETURN" id="CONFIG_ORDER_RETURN" placeholder="No of days" value="<?php echo getTool($model->getValue("CONFIG_ORDER_RETURN"),0); ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Back Date (In Hours)  : </label>
                <div class="col-sm-3">
                  <input type="number" class="validate[required,custom[integer]]" name="CONFIG_TIME" id="CONFIG_TIME" value="<?php echo ($CONFIG_TIME>0)? $CONFIG_TIME:0; ?>">
                </div>
                <div class="col-sm-3"><strong>Current Time : </strong><?php echo getLocalTime(); ?></div>
                <div class="col-sm-2">&nbsp;</div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">No of account can register with single mobile number  : </label>
                <div class="col-sm-9">
                  <input type="text" class="col-md-5 validate[required,custom[integer]]" name="NO_ACCOUNT_MOBILE" id="NO_ACCOUNT_MOBILE" value="<?php echo $model->getValue("NO_ACCOUNT_MOBILE"); ?>" maxlength="3" placeholder="e.g 1,2,3">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Mobile verification time in (Minute)  : </label>
                <div class="col-sm-9">
                  <input type="text" class="col-md-5 validate[required,custom[integer]]" name="VERIFY_TIME" id="VERIFY_TIME" value="<?php echo $model->getValue("VERIFY_TIME"); ?>" maxlength="2" placeholder="Minute">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Postal Address  : </label>
                <div class="col-sm-9">
                  <textarea name="POSTAL_ADDRESS" class="col-md-5" id="POSTAL_ADDRESS" placeholder="Company Address"><?php echo $model->getValue("POSTAL_ADDRESS"); ?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Phone No  : </label>
                <div class="col-sm-9">
                  <input type="text" class="col-md-5 validate[required]" name="MOBILE_NO" id="MOBILE_NO" value="<?php echo $model->getValue("MOBILE_NO"); ?>"  placeholder="Phone No">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Email  : </label>
                <div class="col-sm-9">
                  <input type="text" class="col-md-5" name="EMAIL_ADDRESS" id="EMAIL_ADDRESS" value="<?php echo $model->getValue("EMAIL_ADDRESS"); ?>"  placeholder="Email Address">
                </div>
              </div>
             
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">TDS Charges  : </label>
                <div class="col-sm-9">
                  <input type="text"  name="CONFIG_TDS" id="CONFIG_TDS" placeholder="(%)" value="<?php echo $model->getValue("CONFIG_TDS"); ?>">
                  (%) </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Admin Charges  : </label>
                <div class="col-sm-9">
                  <input type="text"  name="CONFIG_ADMIN_CHARGE" id="CONFIG_ADMIN_CHARGE" placeholder="(%)" value="<?php echo $model->getValue("CONFIG_ADMIN_CHARGE"); ?>">
                  (%) </div>
              </div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-6">
                  <button type="submit" name="submitAdministrator" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check bigger-110"></i> Save Changes </button>
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
	});
</script>
</body>
</html>
