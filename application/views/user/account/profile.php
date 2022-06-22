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
        <h4 class="page-title">Profile</h4>
        <p class="text-muted page-title-alt">You can updated your profile details</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <!-- /primary heading -->
          <div class="portlet-heading">
            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Profile : </label>
            <div class="clearfix"></div>
          </div>
          <div class="portlet-body" >
            <div class="row">
				<?php echo get_message(); ?>
              <div class="col-lg-8">
                <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateMemberForm("account","profile",""); ?>" method="post">
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">First name : </label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="first_name" id="first_name" class="form-control input-xlarge validate[required]" type="text" placeholder="First name" value="<?php echo $ROW['first_name']; ?>" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Last Name:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="last_name" id="last_name"  class="form-control input-xlarge validate[required]" type="text" placeholder="Last name" value="<?php echo $ROW['last_name']; ?>" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Email Address:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="email_address" id="email_address" class="form-control input-xlarge validate[required,custom[email]]" type="text" placeholder="email adrress" value="<?php echo $ROW['member_email']; ?>" >
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
                  <div class="space-4"></div>
                  <h3 class="lighter block green">Address</h3>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Address : </label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <textarea name="current_address" class="form-control input-xlarge validate[required]" id="current_address" placeholder="Your current address"><?php echo $ROW['current_address']; ?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Landmark:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="land_mark" id="land_mark" class="form-control input-xlarge" type="text" placeholder="Landmark" value="<?php echo $ROW['land_mark']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">State:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="state_name" id="state_name" class="form-control input-xlarge" type="text" placeholder="State" value="<?php echo $ROW['state_name']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">City:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                       <input name="city_name" id="city_name" class="form-control input-xlarge" type="text" placeholder="City" value="<?php echo $ROW['city_name']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Postal Code:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="pin_code" id="pin_code"  class="form-control input-xlarge validate[required,custom[integer]]" type="text" placeholder="Your Pin Code" value="<?php echo $ROW['pin_code']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Phone:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="member_mobile" id="member_mobile"  class="form-control input-xlarge validate[required,custom[integer]]" maxlength="20" type="text" placeholder="Your Phone No" value="<?php echo $ROW['member_mobile']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="clearfix form-action">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                      <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id']; ?>">
                      <button type="submit" name="submitMemberSave" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Update </button>  &nbsp;&nbsp;<button onClick="window.location.href='<?php echo MEMBER_PATH; ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                    </div>
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
		$("#form-page").validationEngine({
				'custom_error_messages': {
					'#pin_code': {
						'custom[integer]': {
							'message': "Not a valid postal code ."
						}
					}
					,'#member_mobile': {
						'custom[integer]': {
							'message': "Not a valid phone no."
						}
					}
					
				}
			});
		
	});
</script>
</html>
