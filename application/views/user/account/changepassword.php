<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container">
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Change Password</h4>
        <p class="text-muted page-title-alt">You can updated your login password</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <!-- /primary heading -->
          
          <div class="portlet-body" >
            <div class="row"> <?php echo get_message(); ?>
              <div class="col-lg-8">
                <form  name="form-valid" id="form-valid" method="post" class="form-horizontal" action="<?php echo MEMBER_PATH."account/changepassword"; ?>">
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">User-Id:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="user_id" id="user_id"  class="form-control input-xlarge validate[required,minSize[6]]" type="text" readonly="true" placeholder="" value="<?php echo $ROW['user_id']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">New - Password:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="old_password" id="old_password" class="form-control input-xlarge validate[required]" type="hidden" placeholder="current password" 
							 value="<?php echo $ROW['user_password']; ?>">
                        <input name="user_password" id="user_password"  class="form-control input-xlarge validate[required,minSize[6]]" type="password" placeholder="new password" value="">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Confirm - Password:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="confirm_user_password" id="confirm_user_password"  class="form-control input-xlarge validate[required,equals[user_password]]" type="password" placeholder="confirm password" value="">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <hr>
                  <div class="wizard-actions">
                    <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id'];  ?>">
                    <button name="submitMemberSavePassword" type="submit"  value="1" class="btn btn-success"> <i class="ace-icon fa fa-lock"></i> Update Password </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
	});
</script>
</html>
