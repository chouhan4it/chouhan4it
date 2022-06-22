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
        <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Update Member</small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php  get_message(); ?>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo ADMIN_PATH."member/updatemember"; ?>" method="post">
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
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Email Address:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="email_address" id="email_address" class="col-xs-12 col-sm-5 validate[required,custom[email]]" type="text" placeholder="email adrress" value="<?php echo $ROW['member_email']; ?>">
                </div>
              </div>
            </div>
            <div class="space-4"></div>
            <h3 class="lighter block green">Address Settings</h3>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Address : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <textarea name="current_address" class="col-xs-12 col-sm-4 validate[required]" id="current_address" placeholder="Your current address"><?php echo $ROW['current_address']; ?></textarea>
                </div>
              </div>
            </div>
			 <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Country:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <select class="col-xs-12 col-sm-4 validate[required] getStateList" id="country_code" name="country_code" >
				  	<option value="">-----select country-----</option>
                  	<?php echo DisplayCombo($ROW['country_code'],"COUNTRY"); ?>
                 </select>
                </div>
              </div>
            </div>
			 <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">State:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
					  <select class="col-xs-12 col-sm-4 validate[required] getCityList" id="state_name" name="state_name" >
					  	<option value="">----select state----</option>
						<?php if($ROW['state_name']!=''){  DisplayCombo($ROW['state_name'],"STATE"); } ?>
					 </select>
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">City:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                   <select class="col-xs-12 col-sm-4 validate[required]" id="city_name" name="city_name" >
				   		<option value="">----select city----</option>
						<?php if($ROW['city_name']!=''){  DisplayCombo($ROW['city_name'],"CITY"); } ?>
					 </select>
                </div>
              </div>
            </div>
           
           
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Postal Code:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="pin_code" id="pin_code"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Your Pin Code" value="<?php echo $ROW['pin_code']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Phone:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="member_mobile" id="member_mobile"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Your Phone No" value="<?php echo $ROW['member_mobile']; ?>">
                </div>
              </div>
            </div>
            <h3 class="lighter block green">Login Info</h3>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Username : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="user_name" id="user_name"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Username" value="<?php echo $ROW['user_name']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Password:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="user_password" id="user_password"  class="col-xs-12 col-sm-4 validate[required]" type="password" placeholder="Password" value="<?php echo $ROW['user_password']; ?>">
                </div>
              </div>
            </div>
            <h3 class="lighter block green">Payment settings</h3>
            
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Bitcoin Address:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="bitcoin_address" id="bitcoin_address"  class="col-xs-12 col-sm-4" type="text" placeholder="Account ID" value="<?php echo $ROW['bitcoin_address']; ?>">
                </div>
              </div>
            </div>
            <div class="clearfix form-action">
              <div class="col-md-offset-3 col-md-9">
                <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id']; ?>">
                <button type="submit" name="submitMemberSave" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Update </button>
     
                <button onClick="window.location.href='<?php echo ADMIN_PATH."setting/processors"; ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
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
		$(".getStateList").on('blur',getStateList);
		$(".getCityList").on('blur',getCityList);
		function getStateList(){
			var country_code = $("#country_code").val();
			var URL_STATE = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=STATE_LIST&country_code="+country_code;
			$("#state_name").load(URL_STATE);
		}
		function getCityList(){
			var state_name = $("#state_name").val();
			var URL_CITY = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=CITY_LIST&state_name="+state_name;
			$("#city_name").load(URL_CITY);
		}
	});
</script>
</body>
</html>
