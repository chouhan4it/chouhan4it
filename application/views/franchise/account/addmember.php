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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>jquery_token/token-input.css" />

<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>jquery_token/jquery.tokeninput.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>ckeditor/ckeditor.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>
<body class="no-skin" style="min-height:1400px;">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(FRANCHISE_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      
      <!-- /.page-header -->
      <div class="row">
          <div class="col-xs-12">
            <?php get_message(); ?>
            <div class="widget-box">
              
              <div class="widget-body">
                <div class="widget-main">
                  <form  name="form-valid" id="form-valid" method="post" class="form-horizontal" action="<?php echo generateFranchiseForm("account","addmember","");  ?>">
                    <div class="no-steps-container" id="fuelux-wizard-container">
                      <div>
                        <ul style="margin-left: 0" class="steps">
                          <li data-step="1" class="active"> <span class="step">1</span> <span class="title">Basic Detail</span> </li>
                          <li data-step="2"> <span class="step">2</span> <span class="title">Package Detail</span> </li>
                          <li data-step="3"> <span class="step">3</span> <span class="title">Address Settings</span> </li>
                          <li data-step="4"> <span class="step">4</span> <span class="title">Profile Pic</span> </li>
                        </ul>
                      </div>
                      <hr>
                      <div class="step-content pos-rel">
                        <div class="step-pane active" data-step="1">
                          <h3 class="lighter block green">Enter the following information</h3>
                          <div class="hr hr-dotted"></div>
                             
                          <div class="space-2"></div>
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
                                <input name="email_address" id="email_address" class="col-xs-12 col-sm-5" type="text" placeholder="Email address" value="<?php echo $ROW['member_email']; ?>">
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
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="pan_no">Pan No:</label>
                            <div class="col-xs-12 col-sm-9">
                              <div class="clearfix">
                                <input name="pan_no" id="pan_no" class="col-xs-12 col-sm-5 validate[required,minSize[10]] checkExistData" type="text" placeholder="Pan No" value="<?php echo $ROW['pan_no']; ?>" maxlength="10">
                              </div>
                            </div>
                          </div>
                          <div class="space-2"></div>
                          <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="aadhar_no">Aadhaar No:</label>
                            <div class="col-xs-12 col-sm-9">
                              <div class="clearfix">
                                <input name="aadhar_no" id="aadhar_no" class="col-xs-12 col-sm-5 validate[required,custom[integer]] checkExistData" type="text" placeholder="Aadhaar No" value="<?php echo $ROW['aadhar_no']; ?>" maxlength="14">
                              </div>
                            </div>
                          </div>
                          <div class="space-2"></div>
                          <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Gender:</label>
                            <div class="col-xs-12 col-sm-9">
                              <div class="clearfix">
                                <input type="radio" class="validate[required]" name="gender" id="gender" <?php echo checkRadio($ROW['gender'],"M",true); ?> value="M">
                                Male &nbsp;&nbsp;
                                <input type="radio" class="validate[required]" name="gender" id="gender" <?php echo checkRadio($ROW['gender'],"F"); ?> value="F">
                                Female </div>
                            </div>
                          </div>
                          <div class="space-2"></div>
                          <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Date of Birth:</label>
                            <div class="col-xs-12 col-sm-9">
                              <div class="clearfix">
                                <select name="flddDOB_D" id="flddDOB_D" class="validate[required]" style="width:70px;">
                                  <option value="">Day</option>
                                  <?php DisplayCombo($d_day, "DAY");?>
                                </select>
                                <select name="flddDOB_M" id="flddDOB_M" class="validate[required]" style="width:100px;">
                                  <option value="">Month</option>
                                  <?php DisplayCombo($d_month, "MONTH");?>
                                </select>
                                <select name="flddDOB_Y" id="flddDOB_Y" class="validate[required]" style="width:75px;">
                                  <option value="">Year</option>
                                  <?php DisplayCombo($d_year, "YEAR");?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <h3 class="lighter block green">Password</h3>
                          <div class="hr hr-dotted"></div>
                          
                          <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Password :</label>
                            <div class="col-xs-12 col-sm-9">
                              <div class="clearfix">
                                <input id="user_password" name="user_password" class="col-xs-12 col-sm-5 validate[required,minSize[6],maxSize[20]]" type="password" value="<?php echo $ROW['user_password'];  ?>" placeholder="Password">
                              </div>
                            </div>
                          </div>
                          <div class="space-2"></div>
                          <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Confirm Password :</label>
                            <div class="col-xs-12 col-sm-9">
                              <div class="clearfix">
                                <input id="user_crf_password" name="user_crf_password" class="col-xs-12 col-sm-5 validate[required,equals[user_password]]" type="password" value="<?php echo $ROW['user_password'];  ?>" placeholder="Confirm Password">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="wizard-actions">
                      <input type="hidden" name="member_id" id="member_id" value="<?php echo _e($ROW['member_id']);  ?>">
                      <button name="submit-step-1" type="submit"  value="1" class="btn btn-info">  Submit  <i class="ace-icon fa fa-arrow-right"></i></button>
                      
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		
		$(".checkExistData").on('change',function(){
				var data_name = $(this).attr('name');
				var data_id = $(this).attr('id');
				var data_val = $(this).val();
				var data = {
					switch_type : "MEM_FIELD_EXIST",
					data_name : data_name,
					data_id : data_id,
					data_val : data_val
				};
				var URL_LOAD = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
				$.getJSON(URL_LOAD,data,function(json_eval){
					if(json_eval){
						if(json_eval.row_ctrl>0){
							alert(data_val+" already exist, please enter unique");
							$("#"+data_id).val('');
							return true;
						}
					}
				});
		});
	});
</script>
</body>
</html>
