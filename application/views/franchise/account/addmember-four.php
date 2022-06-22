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
                      <li data-step="2" class="active"> <span class="step">2</span> <span class="title">Package Detail</span> </li>
                      <li data-step="3" class="active"> <span class="step">3</span> <span class="title">Address Settings</span> </li>
                      <li data-step="4" class="active"> <span class="step">4</span> <span class="title">Profile Pic</span> </li>
                    </ul>
                  </div>
                  <hr>
                  <form  name="form-valid" id="form-valid" method="post" class="form-horizontal" action="<?php echo generateFranchiseForm("account","addmemberfour",array("member_id"=>_e($ROW['member_id'])));  ?>" enctype="multipart/form-data">
                    <div class="step-content pos-rel">
                      <div class="step-pane active" data-step="1">
                        <h3 class="lighter block green">Profile Picture</h3>
                        <div class="space-2"></div>
                        <div class="form-group">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="avatar_name">Upload:</label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                              <input type="file" name="avatar_name" class="col-xs-12 col-sm-4" id="avatar_name" value="">
                            </div>
                          </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="profile_pic"></label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix"> <img src="<?php echo getMemberImage($ROW['member_id']); ?>" style="width: 50px;height: 50px;"> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="wizard-actions">
                      <input type="hidden" name="member_id" id="member_id" value="<?php echo _e($ROW['member_id']);  ?>">
                      
                      <button type="button" 
				  onClick="window.location.href='<?php echo generateSeoUrlFranchise("member","addmemberthree",array("member_id"=>_e($ROW['member_id']))); ?>'"   
				  name="button-back" class="btn btn-danger"> <i class="ace-icon fa fa-arrow-left"></i> Back  </button>
                  <button name="submit-step-4" type="submit"  value="1" class="btn btn-success">  Finish <i class="ace-icon fa fa-indent icon-on-right"></i> </button>
                    </div>
                  </form>
                </div>
                <!-- /.widget-main --> 
              </div>
              <!-- /.widget-body --> 
            </div>
            <!-- PAGE CONTENT ENDS --> 
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
		$(".getPinPrice").on('blur',getPinPrice);
		
		function getPinPrice(){
			var type_id = $("#type_id").val();
			var URL_LOAD = "<?php echo BASE_PATH; ?>json/jsonhandler?switch_type=PIN_TYPE&type_id="+type_id;
			$.getJSON(URL_LOAD,function(jsonReturn){
				if(jsonReturn.type_id>0){
					$("#pin_price").val(jsonReturn.pin_price);
				}
			});
		}
	});
</script>
</body>
</html>
