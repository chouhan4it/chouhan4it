<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
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
        <h1> Mail <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  gateway Settings </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php  get_message(); ?>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo ADMIN_PATH."system/mailing"; ?>" method="post">
		  	 <h3 class="lighter block green">FOR MASS EMAIL</h3>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">SMTP HOST   :  </label>
              <div class="col-sm-9">
				<input type="text" name="CONFIG_MASS_HOST" class="col-xs-10 col-sm-5 validate[required]"  id="CONFIG_MASS_HOST"  value="<?php echo $model->getValue("CONFIG_MASS_HOST"); ?>">
				
              </div>
            </div>
		    
            
            <div class="space-4"></div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> SMTP PORT   :  </label>
              <div class="col-sm-9">
				<input type="text" name="CONFIG_MASS_PORT" class="col-xs-10 col-sm-5 validate[required]"  id="CONFIG_MASS_PORT" value="<?php echo $model->getValue("CONFIG_MASS_PORT"); ?>"> 
              </div>
            </div>
			
			<div class="space-4"></div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">SMTP LOGIN :  </label>
              <div class="col-sm-9">
				<input type="text" name="CONFIG_MASS_LOGIN"  class="col-xs-10 col-sm-5 validate[required]"  id="CONFIG_MASS_LOGIN" value="<?php echo $model->getValue("CONFIG_MASS_LOGIN"); ?>"> 
              </div>
            </div>
			
			<div class="space-4"></div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">SMTP PASSWORD  :  </label>
              <div class="col-sm-9">
				<input type="text" name="CONFIG_MASS_PASSWORD" class="col-xs-10 col-sm-5 validate[required]"  id="CONFIG_MASS_PASSWORD" value="<?php echo $model->getValue("CONFIG_MASS_PASSWORD"); ?>"> 
              </div>
            </div>
			 <h3 class="lighter block green">FOR SYSTEM EMAILS <small>&nbsp;(system notification emails to users like signup email,withdrawal email etc...)</small></h3>
			
			<div class="space-4"></div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">SMTP HOST   :  </label>
              <div class="col-sm-9">
				<input type="text" name="CONFIG_SYSTEM_HOST" class="col-xs-10 col-sm-5 validate[required]"  id="CONFIG_SYSTEM_HOST"  value="<?php echo $model->getValue("CONFIG_SYSTEM_HOST"); ?>">
				
              </div>
            </div>
		    
            
            <div class="space-4"></div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> SMTP PORT   :  </label>
              <div class="col-sm-9">
				<input type="text" name="CONFIG_SYSTEM_PORT" class="col-xs-10 col-sm-5 validate[required]"  id="CONFIG_SYSTEM_PORT" value="<?php echo $model->getValue("CONFIG_SYSTEM_PORT"); ?>"> 
              </div>
            </div>
			
			<div class="space-4"></div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">SMTP LOGIN :  </label>
              <div class="col-sm-9">
				<input type="text" name="CONFIG_SYSTEM_LOGIN" class="col-xs-10 col-sm-5 validate[required]"  id="CONFIG_SYSTEM_LOGIN" value="<?php echo $model->getValue("CONFIG_SYSTEM_LOGIN"); ?>"> 
              </div>
            </div>
			
			<div class="space-4"></div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">SMTP PASSWORD  :  </label>
              <div class="col-sm-9">
				<input type="text" name="CONFIG_SYSTEM_PASSWORD" class="col-xs-10 col-sm-5 validate[required]"  id="CONFIG_SYSTEM_PASSWORD" value="<?php echo $model->getValue("CONFIG_SYSTEM_PASSWORD"); ?>"> 
              </div>
            </div>			
            <div class="space-4"></div>
            <div class="clearfix form-action">
              <div class="col-md-offset-3 col-md-6">
                <button type="submit" name="submitMailing" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check bigger-110"></i> Save Changes  </button>
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
