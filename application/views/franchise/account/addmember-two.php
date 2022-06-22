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
                <form  name="form-valid" id="form-valid" method="post" class="form-horizontal" action="<?php echo generateFranchiseForm("account","addmembertwo",array("member_id"=>_e($ROW['member_id'])));  ?>">
                  <div class="no-steps-container" id="fuelux-wizard-container">
                    <div>
                      <ul style="margin-left: 0" class="steps">
                        <li data-step="1" class="active"> <span class="step">1</span> <span class="title">Main Settings</span> </li>
                        <li data-step="2" class="active"> <span class="step">2</span> <span class="title">Package Detail</span> </li>
                        <li data-step="3"> <span class="step">3</span> <span class="title">Address Settings</span> </li>
                        <li data-step="4"> <span class="step">4</span> <span class="title">Profile Pic</span> </li>
                      </ul>
                    </div>
                    <hr>
                    <div class="step-content pos-rel">
                      <div class="step-pane active" data-step="1">
                        <h3 class="lighter block green">Sponsor</h3>
                        <div class="hr hr-dotted"></div>
                        <div class="form-group">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="spr_user_id">Sponsor ID :</label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                              <input id="spr_user_id" name="spr_user_id" class="col-xs-12 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['spsr_user_name'];  ?>" placeholder="Sponsor ID">
                            </div>
                          </div>
                        </div>
                        <div class="space-2"></div>
                        
                        <h3 class="lighter block green">Package</h3>
                        <div class="hr hr-dotted"></div>
                        <div class="form-group">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Package :</label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                              <select name="type_id" id="type_id" class="form-control input-xlarge validate[required] getPinPrice">
                                <option value="">----select package----</option>
                                <?php echo DisplayCombo($type_id,"PIN_TYPE"); ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Package Price:</label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                              <input name="pin_price" id="pin_price" class="form-control input-xlarge validate[required]" type="text" placeholder="Package Price" value="" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group option_epin">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="pin_no">E-pin No:</label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                              <input name="pin_no" id="pin_no" class="form-control input-xlarge validate[required]" type="text"  value="" >
                            </div>
                          </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group option_epin">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="pin_key">E-pin Key:</label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                              <input name="pin_key" id="pin_key" class="form-control input-xlarge validate[required]" type="text"  value="" >
                            </div>
                          </div>
                        </div>
                        <div class="space-2"></div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="wizard-actions">
                    <input type="hidden" name="member_id" id="member_id" value="<?php echo _e($ROW['member_id']);  ?>">
                    <button type="button" name="button-back" 
                  onClick="window.location.href='<?php echo generateSeoUrlFranchise("account","addmember",array("member_id"=>_e($ROW['member_id']))); ?>'" 
                  class="btn btn-danger"> <i class="ace-icon fa fa-arrow-left"></i> Back </button>
                    <button type="submit" name="submit-step-2"  value="1" class="btn btn-info btn-next"> Submit <i class="ace-icon fa fa-arrow-right icon-on-right"></i> </button>
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
