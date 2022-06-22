<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$direct_count = $model->BinaryCount($ROW['member_id'],"DirectCount");
$total_count = $model->BinaryCount($ROW['member_id'],"TotalCount");
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
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<?php auto_complete(); ?>
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
.pointer {
	cursor: pointer;
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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Update Member </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?php if($ROW['member_id']==true){ ?>
            <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("member","updatemember",""); ?>" enctype="multipart/form-data"
		   method="post">
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Title : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <select name="title" id="title" class="col-xs-12 col-sm-4  validate[required]">
                      <option value="">Select Title</option>
                      <?php DisplayCombo($ROW['title'], "TITLE");?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">First name : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="first_name" id="first_name" class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="First name" value="<?php echo $ROW['first_name']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Last Name:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="last_name" id="last_name"  class="col-xs-12 col-sm-5" type="text" placeholder="Last name" value="<?php echo $ROW['last_name']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Email Address:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="email_address" id="email_address" class="col-xs-12 col-sm-5 validate[custom[email]]" type="text" placeholder="email adrress" value="<?php echo $ROW['member_email']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Pan No:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="pan_no" id="pan_no" class="col-xs-12 col-sm-5" type="text" placeholder="Pancard No" value="<?php echo $ROW['pan_no']; ?>">
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
              <div class="space-2"></div>
              <div class="form-group">
                <?php
			$dob = explode('-',$ROW['date_of_birth']);
			$dob_year = $dob[0];
			$dob_month = $dob[1];
			$dob_day = $dob[2];
			?>
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Date of Birth:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <select name="flddDOB_D" id="flddDOB_D" class="cmnfld" style="width:70px;">
                      <option value="">Day</option>
                      <?php DisplayCombo($dob_day, "DAY");?>
                    </select>
                    <select name="flddDOB_M" id="flddDOB_M" class="cmnfld" style="width:100px;">
                      <option value="">Month</option>
                      <?php DisplayCombo($dob_month, "MONTH");?>
                    </select>
                    <select name="flddDOB_Y" id="flddDOB_Y" class="cmnfld" style="width:75px;">
                      <option value="">Year</option>
                      <?php DisplayCombo($dob_year, "YEAR");?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <h3 class="lighter block green">Address Settings</h3>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Address : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <textarea name="current_address" class="col-xs-12 col-sm-4 validate[required]" id="current_address" placeholder="Your current address"><?php echo $ROW['current_address']; ?></textarea>
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Landmark:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="land_mark" id="land_mark" class="col-xs-12 col-sm-5" type="text" placeholder="Landmark" value="<?php echo $ROW['land_mark']; ?>">
                  </div>
                </div>
              </div>
              <!-- <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right">Country:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <select class="col-xs-12 col-sm-4 validate[required] getStateList" id="country_code" name="country_code" >
                    <option value="">-----select country-----</option>
                    <?php echo DisplayCombo($ROW['country_code'],"COUNTRY"); ?>
                  </select>
                </div>
              </div>
            </div>-->
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">State:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                  	<input name="state_name" id="state_name"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="State" value="<?php echo $ROW['state_name']; ?>">
                   
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">City:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                  	<input name="city_name" id="city_name"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="City Name" value="<?php echo $ROW['city_name']; ?>">
                   
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Postal Code:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="pin_code" id="pin_code"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Your Pin Code" value="<?php echo $ROW['pin_code']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Phone:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="member_mobile" id="member_mobile"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Your Phone No" value="<?php echo $ROW['member_mobile']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <h3 class="lighter block green">Bank Detail</h3>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Name on Bank Account : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="bank_acct_holder" id="bank_acct_holder"  class="col-xs-12 col-sm-4" type="text" placeholder="Name on Bank Account" value="<?php echo $ROW['bank_acct_holder']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Bak Name:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="bank_name" id="bank_name"  class="col-xs-12 col-sm-4" type="text" placeholder="Bak Name" value="<?php echo $ROW['bank_name']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Bank Account No:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="account_number" id="account_number"  class="col-xs-12 col-sm-4" type="text" placeholder="Bank Account No" value="<?php echo $ROW['account_number']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Branch Name:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="branch" id="branch"  class="col-xs-12 col-sm-4" type="text" placeholder="Branch Name" value="<?php echo $ROW['branch']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">IFS Code:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="ifc_code" id="ifc_code"  class="col-xs-12 col-sm-4" type="text" placeholder="IFC Code" value="<?php echo $ROW['ifc_code']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <h3 class="lighter block green">Nominee Detail</h3>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Nominee Name:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="nominal_name" id="nominal_name"  class="col-xs-12 col-sm-4" type="text" placeholder="Nominee Name" value="<?php echo $ROW['nominal_name']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Nominee Relation:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="nominal_relation" id="nominal_relation"  class="col-xs-12 col-sm-4" type="text" placeholder="Nominee Relation" value="<?php echo $ROW['nominal_relation']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Nominee Mobile:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="nominal_mobile" id="nominal_mobile"  class="col-xs-12 col-sm-4" type="text" placeholder="Nominee Mobile" value="<?php echo $ROW['nominal_mobile']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Nominee DOB:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="nominal_dob" id="nominal_dob"  class="col-xs-12 col-sm-4" type="text" placeholder="Nominee DOB" value="<?php echo $ROW['nominal_dob']; ?>">
                  </div>
                </div>
              </div>
              <h3 class="lighter block green">Login Info</h3>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Username : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="user_name" id="user_name"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Username" value="<?php echo $ROW['user_name']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Password:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="user_password" id="user_password"  class="col-xs-12 col-sm-4 validate[required]" type="password" placeholder="Password" value="<?php echo $ROW['user_password']; ?>">
                  </div>
                </div>
              </div>
              <h3 class="lighter block green">Profile Picture</h3>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right">Profile:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input type="file" name="avatar_name" class="col-xs-12 col-sm-4" id="avatar_name" value="">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right"></label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix"> <img src="<?php echo getMemberImage($ROW['member_id']); ?>" style="width: 50px;height: 50px;"> </div>
                </div>
              </div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id']; ?>">
                  <button type="submit" name="submitMemberSave" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Update </button>
                  <button onClick="window.location.href='<?php echo ADMIN_PATH."member/updatemember"; ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                </div>
              </div>
            </form>
            <?php }else{ ?>
            <form class="form-horizontal" name="form-page" id="form-page" action="<?php echo generateAdminForm("member","updatemember",""); ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> User Id : </label>
                <div class="col-sm-9">
                  <input id="member_user_id" placeholder="User Id" name="member_user_id"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $user_id; ?>">
                  <input name="member_id" type="hidden" id="member_id" value="" />
                </div>
              </div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <button type="submit" name="submitValidMember" value="1" class="btn btn-danger"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                </div>
              </div>
            </form>
            <?php } ?>
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
		$(".getStateList").on('blur',getStateList);
		$(".getCityList").on('blur',getCityList);
		function getStateList(){
			var country_code = $("#country_code").val();
			var URL_STATE = encodeURI("<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=STATE_LIST&country_code="+country_code);
			$("#state_name").load(URL_STATE);
		}
		function getCityList(){
			var state_name = $("#state_name").val();
			if(state_name!=''){
			var URL_CITY = encodeURI("<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=CITY_LIST&state_name="+state_name);
			$("#city_name").load(URL_CITY);
			}
		}
	});
</script>
<script type="text/javascript" language="javascript">
	new Autocomplete("member_user_id", function(){
		this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
		if(this.isModified) this.setValue("");
		if(this.value.length < 1 && this.isNotClick ) return;
		return "<?php echo BASE_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
	});
</script>
</body>
</html>
