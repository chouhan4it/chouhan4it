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
        <p class="text-muted page-title-alt">Your updated profile</p>
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
                
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">First name : </label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                       <?php echo $ROW['first_name']; ?>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Last Name:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                       <?php echo $ROW['last_name']; ?>
                      </div>
                    </div>
                  </div>
                   <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Email Address:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <?php echo $ROW['member_email']; ?>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Gender:</label>
                    <div class="col-xs-12 col-sm-9">
						<?php echo ($ROW['gender']=="M")? "Male":"Female"; ?>
                     
                    </div>
                  </div>
				  <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Joining Date:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <?php echo getDateFormat($ROW['date_join'],"D d M Y h:i:s"); ?>
                      </div>
                    </div>
                  </div>
				  <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Date of Birth:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <?php echo $ROW['date_of_birth']; ?>
                      </div>
                    </div>
                  </div>
                   <div class="clearfix">&nbsp;</div>
                  <h3 class="lighter block green">Address</h3>
				  <smal>Your contact details</smal>
				   <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Address : </label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <?php echo $ROW['current_address']; ?>
                      </div>
                    </div>
                  </div>
                   <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Landmark:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <?php echo $ROW['land_mark']; ?>
                      </div>
                    </div>
                  </div>
                   <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">State:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <?php echo $ROW['state_name']; ?>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">City:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                      <?php echo $ROW['city_name']; ?>
                      </div>
                    </div>
                  </div>
                   <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Postal Code:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                       <?php echo $ROW['pin_code']; ?>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Phone:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                       <?php echo $ROW['member_mobile']; ?>
                      </div>
                    </div>
                  </div>
                  
              
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
</html>
