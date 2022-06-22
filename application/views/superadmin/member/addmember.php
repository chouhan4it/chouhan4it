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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;New Member</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <?php get_message(); ?>
            <div class="widget-box">
              <div class="widget-header widget-header-blue widget-header-flat">
                <h4 class="widget-title lighter"><?php echo ($ROW['member_id'])? "Update Profile":"Add Member"; ?></h4>
                <div class="widget-toolbar">&nbsp;</div>
              </div>
              <div class="widget-body">
                <div class="widget-main">
                  <form  name="form-valid" id="form-valid" method="post" class="form-horizontal" action="<?php echo generateAdminForm("member","addmember","");  ?>">
                    <div class="no-steps-container" id="fuelux-wizard-container">
                      <div>
                        <ul style="margin-left: 0" class="steps">
                          <li data-step="1" class="active"> <span class="step">1</span> <span class="title">Main Settings</span> </li>
                          <li data-step="2"> <span class="step">2</span> <span class="title">Login Info</span> </li>
                          <li data-step="3"> <span class="step">3</span> <span class="title">Address Settings</span> </li>
                          <li data-step="4"> <span class="step">4</span> <span class="title">Profile Pic</span> </li>
                        </ul>
                      </div>
                      <hr>
                      <div class="step-content pos-rel">
                        <div class="step-pane active" data-step="1">
                          <h3 class="lighter block green">Enter the following information</h3>
                          <div class="hr hr-dotted"></div>
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
                                <input name="email_address" id="email_address" class="col-xs-12 col-sm-5" type="text" placeholder="Email address" value="<?php echo $ROW['email_address']; ?>">
                              </div>
                            </div>
                          </div>
                          <div class="space-2"></div>
                          <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Mobile No:</label>
                            <div class="col-xs-12 col-sm-9">
                              <div class="clearfix">
                                <input name="member_mobile" id="member_mobile" class="col-xs-12 col-sm-5 validate[required,custom[integer],minSize[10]]" type="text" placeholder="Mobile No" value="<?php echo $ROW['member_mobile']; ?>" maxlength="10">
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
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Date of Birth:</label>
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
                                  <?php DisplayCombo($_REQUEST['flddDOB_Y'], "YEARNEWPC");?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <h3 class="lighter block green">Sponsor</h3>
                          <div class="hr hr-dotted"></div>
                          <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Sponsor ID :</label>
                            <div class="col-xs-12 col-sm-9">
                              <div class="clearfix">
                                <input id="spr_user_id" name="spr_user_id" class="col-xs-12 col-sm-5" type="text" value="" placeholder="Sponsor ID">
                              </div>
                            </div>
                          </div>
                         
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="wizard-actions">
                      <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id'];  ?>">
                      <button name="submitMemberSave" type="submit"  value="1" class="btn btn-success"> <i class="ace-icon fa fa-floppy-o"></i> Save </button>
                      <button type="button" name="submitMemberNext"
				        onClick="window.location.href='<?php echo generateSeoUrlAdmin("member","addmembertwo",array("member_id"=>$ROW['member_id'])); ?>'"  
					    value="1" class="btn btn-info btn-next"> Next <i class="ace-icon fa fa-arrow-right icon-on-right"></i> </button>
                    </div>
                  </form>
                </div>
                <!-- /.widget-main --> 
              </div>
              <!-- /.widget-body --> 
            </div>
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
		<?php if($ROW['member_id']==""){ ?>
			//$(".checkSponsor").on('blur click',checkSponsor);
			//$("#submitMemberSave").attr('disabled',true);
			function checkSponsor(){
				var sponsor_user_id = $("#sponsor_user_id").val();
				var left_right = $('input[name=left_right]:checked').val();
				if(sponsor_user_id!='' && (left_right=='L' || left_right=='R')){
					var url_check_spr = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=CHECK_SPR&sponsor_user_id="+sponsor_user_id+"&left_right="+left_right;
					$.getJSON(url_check_spr,function(jsonReturn){	
						if(jsonReturn.spil_id>0){
							$("#spil_id").val(jsonReturn.spil_id);
							$("#submitMemberSave").attr('disabled',false);
							$("#error_box").removeClass('danger_alert');
							$("#error_box").addClass('success_alert');
							$("#error_box").text("Sponsor validated , please proceed further"); 
						}else{
							$("#error_box").removeClass('success_alert');
							$("#error_box").addClass('danger_alert');
						}
					});
				}
			}
		<?php } ?>
	});
</script>
</body>
</html>
