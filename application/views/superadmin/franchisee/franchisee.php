<?php 
 defined('BASEPATH') OR exit('No direct script access allowed');
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
          <h1> Branch <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Add / Update</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo generateAdminForm("franchisee","franchisee",""); ?>" method="post">
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="gst_no"> Branch GST No. :</label>
                <div class="col-sm-9">
                  <input id="gst_no" placeholder="GST NO" name="gst_no"  class="col-xs-9 col-sm-6 validate[required]" type="text" value="<?php echo $ROW['gst_no']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="fran_setup_id"> Type :</label>
                <div class="col-sm-9">
                  <select name="fran_setup_id" id="fran_setup_id" class="col-xs-9 col-sm-6 validate[required]">
                    <option value="">-----select----</option>
                    <?php echo DisplayCombo($ROW['fran_setup_id'],"FRANCHISEE_TYPE"); ?>
                  </select>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="store"> Shop Name :</label>
                <div class="col-sm-9">
                  <input id="store" placeholder="" name="store"  class="col-xs-9 col-sm-6 validate[required]" type="text" value="<?php echo $ROW['store']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="name"> Name :</label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="" name="name"  class="col-xs-9 col-sm-6 validate[required]" type="text" value="<?php echo $ROW['name']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="email"> Email :</label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="" name="email"  class="col-xs-9 col-sm-6 validate[required,custom[email]]" type="text" value="<?php echo $ROW['email']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="mobile"> Mobile :</label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="" name="mobile"  class="col-xs-9 col-sm-6 validate[required,custom[integer]]" maxlength="10"
				 type="text" value="<?php echo $ROW['mobile']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="city"> Address :</label>
                <div class="col-sm-9">
                  <textarea name="address" class="col-xs-9 col-sm-6 validate[required]" id="form-field-1" placeholder=""><?php echo $ROW['address']; ?></textarea>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="state"> State :</label>
                <div class="col-sm-9">
                   <input id="state" placeholder="State" name="state"  class="col-xs-9 col-sm-6 validate[required]" type="text" 
			   value="<?php echo $ROW['state']; ?>"  >
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="city"> City :</label>
                <div class="col-sm-9">
                   <input id="city" placeholder="City" name="city"  class="col-xs-9 col-sm-6 validate[required]" type="text" 
			   value="<?php echo $ROW['city']; ?>"  >
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="pincode"> Zip Code:</label>
                <div class="col-sm-9">
                  <input id="pincode" placeholder="Zip Code" name="pincode"  class="col-xs-9 col-sm-6 validate[required,custom[integer]]" maxlength="6" type="text" value="<?php echo $ROW['pincode']; ?>" >
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Stop Invoicing :</label>
                <div class="col-sm-9">
                  <select name="stop_invoice" id="stop_invoice" class="col-xs-9 col-sm-6 validate[required]">
                    <option value="">Select Option</option>
                    <?php DisplayCombo($ROW['stop_invoice'], "YESNOFLAG"); ?>
                  </select>
                </div>
              </div>
              <h4>Login Info</h4>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Username :</label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="" name="user_name"  class="col-xs-9 col-sm-6 validate[required]" type="text" value="<?php echo $ROW['user_name']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Password :</label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="" name="password"  class="col-xs-9 col-sm-6 validate[required]" type="text" value="<?php echo $ROW['password']; ?>">
                </div>
              </div>
              <div class="clearfix space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <input type="hidden" name="franchisee_id" id="franchisee_id" value="<?php echo _e($ROW['franchisee_id']); ?>">
                  <button type="submit" name="submitFranchisee" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button onClick="window.location.href='<?php echo generateSeoUrlAdmin("franchisee","franchisee","");  ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
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
		$("#form-valid").validationEngine();
	});
</script>
</body>
</html>
