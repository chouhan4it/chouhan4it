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
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Enroll <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  New PC </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php  get_message(); ?>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo generateFranchiseForm("member","newmember",""); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Title : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <select class="col-xs-12 col-sm-4 validate[required]" id="title" name="title" >
                    <option value="">Title</option>
                    <?php echo DisplayCombo($ROW['title'],"TITLE"); ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Sponsor Id : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="sponsor_user_id" id="first_name" class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="Sponsor Id" value="<?php echo $ROW['sponsor_user_id']; ?>">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">First name : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="first_name" id="first_name" class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="First name" value="<?php echo $ROW['first_name']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Last Name:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="last_name" id="last_name"  class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="Last name" value="<?php echo $ROW['last_name']; ?>">
                </div>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Phone No : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="member_mobile" type="text" class="col-xs-12 col-sm-4 validate[required,custom[integer]]" maxlength="10" id="member_mobile" value="<?php echo $ROW['member_mobile']; ?>" placeholder="Phone No">
                </div>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Date  Of Birth : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <select name="flddDOB_D" id="flddDOB_D" class="cmnfld" style="width:70px;">
                    <option value="">Day</option>
                    <?php DisplayCombo($_REQUEST['flddDOB_D'], "DAY");?>
                  </select>
                  <select name="flddDOB_M" id="flddDOB_M" class="cmnfld" style="width:100px;">
                    <option value="">Month</option>
                    <?php DisplayCombo($_REQUEST['flddDOB_M'], "MONTH");?>
                  </select>
                  <select name="flddDOB_Y" id="flddDOB_Y" class="cmnfld" style="width:75px;">
                    <option value="">Year</option>
                    <?php DisplayCombo($_REQUEST['flddDOB_Y'], "YEAR");?>
                  </select>
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Gender:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input type="radio" name="gender" id="gender" <?php echo checkRadio($ROW['gender'],"M",true); ?> value="M">
                  Male &nbsp;&nbsp;
                  <input type="radio" name="gender" id="gender" <?php echo checkRadio($ROW['gender'],"F"); ?> value="F">
                  Female </div>
              </div>
            </div>
            <h3 class="lighter block green">Login Info</h3>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Email Address:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="email_address" id="email_address" class="col-xs-12 col-sm-4" type="text" placeholder="Email address" value="<?php echo $ROW['member_email']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Password : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="user_password" id="user_password"  class="col-xs-12 col-sm-4 validate[required]" type="password" placeholder="Password" value="<?php echo $ROW['user_password']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Confirm Password : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="user_cf_password" id="user_cf_password"  class="col-xs-12 col-sm-4 validate[required,equals[user_password]]" type="password" placeholder="Confirm Password" value="<?php echo $ROW['user_cf_password']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Logo : </label>
              <div class="col-xs-8 col-sm-8">
                <div class="clearfix">
                  <input name="avatar_name" id="avatar_name"  class="col-xs-12 col-sm-4 validate[required]" type="file" placeholder="Upload Logo" value="<?php echo $ROW['avatar_name']; ?>">
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="clear">&nbsp;</div>
                    <img src="<?php echo getMemberImage($ROW['member_id']); ?>" style="width: 50px;height: 50px;"> </div>
                </div>
              </div>
            </div>
            <div class="clearfix form-action">
              <div class="col-md-offset-3 col-md-9">
                <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id']; ?>">
                <button type="submit" name="submitMemberSave" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
     
                <button onClick="window.location.href='<?php echo generateSeoUrlAdmin("member","profilelist","");  ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
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
