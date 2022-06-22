<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(1);
if ($this->session->userdata("referral") === false) {
	$this->session->set_userdata("referral",$segment['referral']);
}
$spr_user_id  = getTool($segment['referral'],$this->session->userdata("referral"));
$QR_SPR = "SELECT tm.* FROM ".prefix."tbl_members AS tm 
	   WHERE  tm.member_id IN(SELECT member_id FROM tbl_mem_tree) 
	   AND  (tm.user_id LIKE '".$spr_user_id."' OR tm.user_name LIKE '".$spr_user_id."')";
$AR_SPR = $this->SqlModel->runQuery($QR_SPR,true);
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
<!--<![endif]-->

<!-- Mirrored from opencart.dostguru.com/FD01/fruitino_04/index.php?route=account/login by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Dec 2021 08:33:10 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<!-- /Added by HTTrack -->
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<?php $this->load->view('layout/svgsymbol'); ?>
<body>
<?php $this->load->view('layout/header'); ?>
<div id="account-register" class="container acpage">
  <ul class="breadcrumb">
    <li><a href="<?php echo BASE_PATH; ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo generateSeoUrl("account","login",""); ?>">Account</a></li>
    <li><a href="<?php echo generateSeoUrl("account","register",""); ?>">Login</a></li>
  </ul>
  <div class="row">
    <div id="content" class="col-sm-8 col-md-9 col-xs-12 colright">
    	
      <div class="infobg">
        <h1>Register Account</h1>
        <p>If you already have an account with us, please login at the <a href="<?php echo generateSeoUrl("account","login",""); ?>">login page</a>.</p>
        <form action="<?php echo generateForm("account","register",""); ?>" method="post" enctype="multipart/form-data" name="form-register" id="form-register" class="form-horizontal">
        <?php get_message(); ?>
          <fieldset id="account">
            <legend>Your Personal Details</legend>
            <div class="form-group">
              <label class="col-sm-3 col-xs-12 control-label">Referral Id </label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" class="form-control spr_user_id" name="spr_user_id" id="spr_user_id" value="<?php echo getTool($AR_SPR['user_id'],$_REQUEST['spr_user_id']); ?>" placeholder="(Optional)" >
                <div class="col-md-12" id="error_msg" style="display:none;"></div>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-3 col-xs-12 control-label" for="first_name">First Name</label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" class="form-control validate[required]"  name="first_name" id="first_name" value="<?php echo $_REQUEST['first_name']; ?>" placeholder="First Name">
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-3 col-xs-12 control-label" for="last_name">Last Name</label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" class="form-control validate[required]"  name="last_name" id="last_name" value="<?php echo $_REQUEST['last_name']; ?>" placeholder="Last Name">
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-3 col-xs-12 control-label" for="input-email">E-Mail</label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" class="form-control validate[required,custom[email]]"  name="member_email" id="member_email" value="<?php echo $_REQUEST['member_email']; ?>"  placeholder="E-Mail">
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-3 col-xs-12 control-label" for="input-telephone">Mobile </label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" class="form-control validate[required,custom[integer]]"   name="member_mobile" id="member_mobile" value="<?php echo $_REQUEST['member_mobile']; ?>" placeholder="Mobile">
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Your Password</legend>
            <div class="form-group required">
              <label class="col-sm-3 col-xs-12 control-label" for="input-password">Password</label>
              <div class="col-sm-9 col-xs-12">
                <input type="password" class="form-control validate[required,minSize[6]]" name="user_password" id="user_password"  placeholder="Password" >
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-3 col-xs-12 control-label" for="input-confirm">Password Confirm</label>
              <div class="col-sm-9 col-xs-12">
                <input type="password" class="form-control validate[required,equals[user_password]]" name="user_crf_password" id="user_crf_password" 
                placeholder="Password Confirm" >
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Newsletter</legend>
            <div class="form-group">
              <label class="col-sm-3 col-xs-12 control-label">Subscribe</label>
              <div class="col-sm-9 col-xs-12">
                <label class="radio-inline">
                  <input type="radio" name="newsletter" value="Y" />
                  Yes</label>
                <label class="radio-inline">
                  <input type="radio" name="newsletter" value="N" checked="checked" />
                  No</label>
              </div>
            </div>
             <div class="form-group required">
              <label class="col-sm-3 col-xs-12 control-label" for="input-telephone">Security Code </label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" class="form-control unicase-form-control text-input" placeholder="Security Code"  name="captcha_code" id="captcha_code">
                <div class="clearfix">&nbsp;</div>
                <img class="img-responsive" src="<?php echo BASE_PATH."captcha/code"; ?>?sid=<?php echo md5(uniqid(time())); ?>" name="SecImg" id="SecImg" style="border-radius:5px; border:#AEAEAE 1px solid; width:100px;" onclick="document.getElementById('SecImg').src = '<?php echo BASE_PATH."captcha/code"; ?>?sid=' + Math.random(); return false">
              </div>
            </div>
          </fieldset>
          <div class="buttons">
            <div class="text-right acspace">I have read and agree to the <a href="<?php echo generateSeoUrl("web","privacypolicy",""); ?>" target="_blank" class=""><b>Privacy Policy</b></a>
              <input type="checkbox" name="agree" value="1" />
              &nbsp;
              <input type="submit" name="submit-register" value="Create an Account" class="btn btn-primary" />
            </div>
          </div>
        </form>
      </div>
    </div>
    <aside id="column-right" class="col-sm-4 col-md-3 col-xs-12 hidden-xs">
      <div class="list-group accolumn">
        <h3><svg class="" width="20px" height="20px">
          <use xlink:href="#acluser"></use>
          </svg>ACCOUNT SETTINGS</h3>
        <a href="<?php echo generateSeoUrl("account","login",""); ?>" class="list-group-item">Login</a> <a href="<?php echo generateSeoUrl("account","register",""); ?>" class="list-group-item">Register</a> <a href="javascript:void(0)" class="list-group-item">Forgotten Password</a> <a href="javascript:void(0)" class="list-group-item">My Account</a> </div>
    </aside>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
</body>
<?php jquery_validation(); ?>
<script type="text/javascript">
$("#form-register").validationEngine();
$(".spr_user_id").on('blur',function(){
	var spr_user_id = $(this).val();
	var URL_LOAD = "<?php echo BASE_PATH; ?>json/jsonhandler";
	if(spr_user_id!=''){
		$.getJSON(URL_LOAD,{switch_type:"CHECKUSR",user_name:spr_user_id},function(JsonEval){
			$("#error_msg").show();
			if(JsonEval){
				if(JsonEval.member_id>0){
					$("#error_msg").html("<div class='alert alert-success'>'"+JsonEval.full_name+"'  will be your sponsor member </div>");					
					return true;
				}else{
					$("#spr_user_id").val('');
					$("#error_msg").html("<div class='alert alert-danger'>'"+spr_user_id+"' Invalid sponsor member</div>");
					return false;
				}
			}else{
				$("#spr_user_id").val('');
				$("#error_msg").html("<div class='alert alert-danger'>'"+spr_user_id+"' Invalid sponsor member</div>");
				return false;
			}
		});
	}
});



$(".is_sponsor").on('click',function(){
	if($(this).prop("checked")==true){
		$(".option_sponsor").slideDown(600);
	}else{
		$(".option_sponsor").slideUp(600);
	}
});
$("#member_email").on('click',function(){
	$(this).attr('readonly',false);
});

</script>
</html>
