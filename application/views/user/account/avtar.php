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
        <h4 class="page-title">Profile Picture</h4>
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
                <form class="form-horizontal" enctype="multipart/form-data"  name="form-page" id="form-page" action="<?php echo generateMemberForm("account","avtar",""); ?>" method="post">
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Profile Picture : </label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                         <input name="avatar_name" value="" class="form-control" id="avatar_name" type="file">
                      </div>
                    </div>
                  </div>
				  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email"> </label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
					  
                        <img src="<?php echo getMemberImage($this->session->userdata('mem_id')); ?>" style="width: 50px;height: 50px;">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  
                  <div class="clearfix form-action">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                      <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id']; ?>">
                      <button type="submit" name="updateProfileAvatar" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Upload </button>  &nbsp;&nbsp;<button onClick="window.location.href='<?php echo MEMBER_PATH; ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
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
