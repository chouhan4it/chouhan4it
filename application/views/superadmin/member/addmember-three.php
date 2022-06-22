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
        <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  New Member </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <div class="col-xs-12"> <?php echo get_message(); ?>
          <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
              <h4 class="widget-title lighter"><?php echo ($ROW['member_id'])? "Update Profile":"Add Member"; ?></h4>
              <div class="widget-toolbar">&nbsp;</div>
            </div>
            <div class="widget-body">
              <div class="widget-main">
                <div class="no-steps-container" id="fuelux-wizard-container">
                  <div>
                    <ul style="margin-left: 0" class="steps">
                      <li data-step="1" class="active"> <span class="step">1</span> <span class="title">Main Settings</span> </li>
                      <li data-step="2" class="active"> <span class="step">2</span> <span class="title">Login Info</span> </li>
                      <li data-step="3" class="active"> <span class="step">3</span> <span class="title">Address Settings</span> </li>
                      <li data-step="4"> <span class="step">4</span> <span class="title">Profile Pic</span> </li>
                    </ul>
                  </div>
                  <hr>
                  <form  name="form-valid" id="form-valid" method="post" class="form-horizontal" action="<?php echo generateAdminForm("member","addmemberthree",array("member_id"=>_e($ROW['member_id'])));  ?>">
                    <div class="step-content pos-rel">
                      <div class="step-pane active" data-step="1">
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
                        <div class="space-2"></div>
                      </div>
                    </div>
                    <hr>
                    <div class="wizard-actions">
                      <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id'];  ?>">
                      <button name="submitMemberSave" type="submit"  value="1" class="btn btn-success"> <i class="ace-icon fa fa-floppy-o"></i> Save </button>
                      <button type="button"  <?php if($ROW['city_name']=="" || $ROW['current_address']==""){ echo 'disabled="disabled"'; } ?> 
				  onClick="window.location.href='<?php echo generateSeoUrlAdmin("member","addmemberfour",array("member_id"=>_e($ROW['member_id']))); ?>'"   
				  name="submitMemberNext" class="btn btn-info btn-next"> Next <i class="ace-icon fa fa-arrow-right icon-on-right"></i> </button>
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
		$(".getStateList").on('blur',getStateList);
		$(".getCityList").on('blur',getCityList);
		function getStateList(){
			var country_code = encodeURI($("#country_code").val());
			var URL_STATE = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=STATE_LIST&country_code="+country_code;
			$("#state_name").load(URL_STATE);
		}
		function getCityList(){
			var state_name = encodeURI($("#state_name").val());
			var URL_CITY = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=CITY_LIST&state_name="+state_name;
			$("#city_name").load(URL_CITY);
		}
	});
</script>
</body>
</html>
