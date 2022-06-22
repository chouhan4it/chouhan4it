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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>public/jquery_token/token-input.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
<!--[if lte IE 9]>
	<link rel="stylesheet" href="assets/css/ace-ie.min.css" />
<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>public/jquery_token/jquery.tokeninput.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>ckeditor/ckeditor.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="no-skin" style="min-height:2000px;">
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1>Offer <small><i class="ace-icon fa fa-angle-double-right"></i> Create Multiple Offers</small></h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo generateAdminForm("shop","postmultipleoffer",""); ?>" method="post" enctype="multipart/form-data">
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Offer Type : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <select name="offer_module" id="offer_module" class="form-control validate[required]" style="width:auto;">
                      <option value="">---select---</option>
                      <?php DisplayCombo('',"OFFER_MODULE"); ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">User Offer Repeat : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input type="radio" name="offer_repeat" id="offer_repeat" class="validate[required]" value="Y">
                    Yes  &nbsp;&nbsp;
                    <input type="radio" name="offer_repeat" id="offer_repeat" class="validate[required]" value="N">
                    No  &nbsp;&nbsp; </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Offer Name : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="offer_title" id="offer_title" class="col-xs-12 col-sm-8 validate[required]" type="text" placeholder="Name of Offer">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Offer  Code:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="offer_code" id="offer_code"  class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="Code of offer">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Offer Min Price:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="offer_min_price" id="offer_min_price"  class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="Offer Min Price">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Offer Min PV:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="offer_min_pv" id="offer_min_pv"  class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="Offer Min PV">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Franchise :</label>
                <div class="col-xs-12 col-sm-3">
                  <div class="clearfix">
                    <select name="franchisee_id" id="franchisee_id" class="form-control">
                      <option value="0">-----select-----</option>
                      <?php echo DisplayCombo('',"FRANCHISEE"); ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Multiplies :</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input type="radio" name="offer_multiple" id="offer_multiple" class="validate[required]" value="1">
                    Yes  &nbsp;&nbsp;
                    <input type="radio" name="offer_multiple" id="offer_multiple" class="validate[required]" value="0">
                    No  &nbsp;&nbsp; </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Member Type :</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input type="radio" name="offer_type" id="offer_type" class="offer_type validate[required]" value="NEW">
                    New Member &nbsp;&nbsp;
                    <input type="radio" name="offer_type" id="offer_type" class="offer_type validate[required]" value="OLD">
                    Old Member &nbsp;&nbsp; </div>
                </div>
              </div>
              <div class="new_member">
                <div class="space-2"></div>
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Member Join From <br>
                    Date :</label>
                  <div class="col-xs-4 col-sm-4">
                    <div class="clearfix">
                      <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="member_join_from" id="member_join_from" type="text"   />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
                  </div>
                </div>
                <div class="space-2"></div>
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">To Date :</label>
                  <div class="col-xs-4 col-sm-4">
                    <div class="clearfix">
                      <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="member_join_to" id="member_join_to" type="text" />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
                  </div>
                </div>
                <div class="space-2"></div>
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Period from Registration (Hours) :</label>
                  <div class="col-xs-4 col-sm-4">
                    <div class="clearfix">
                      <input name="period_register" id="period_register"  class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="Period from registration">
                    </div>
                  </div>
                </div>
              </div>
              <div class="old_member">
                <div class="space-2"></div>
                <div class="form-group">
                  <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Before Join Date <br>
                    Date :</label>
                  <div class="col-xs-4 col-sm-4">
                    <div class="clearfix">
                      <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="member_join_before" id="member_join_before" type="text"   />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Expiry Date :</label>
                <div class="col-xs-12 col-sm-4">
                  <div class="clearfix">
                    <div class="input-group">
                      <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="offer_expiry" id="offer_expiry" type="text"   />
                      <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                  </div>
                </div>
              </div>
              <?php /* 
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Products in offer :</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
					<input name="post_id" id="demo-input-pre-populated" class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="Products">
                    <?php $AR_TAG = $model->getOfferPost($ROW['offer_id']); ?>
					<script type="text/javascript">
					$(document).ready(function() {
						$("#demo-input-pre-populated").tokenInput("<?php echo generateSeoUrlAdmin("operation","productsearch",""); ?>", {
							prePopulate: <?php echo json_encode($AR_TAG); ?>
						});
					});
					</script>
                </div>
              </div>
            </div>
            
            <div class="space-4"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Offer  Photo &nbsp; <small>(Use ctrl to select multiple photo)</small>: </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="offer_image" type="file" class="col-xs-12 col-sm-4" id="offer_image" placeholder="Offer Image">
                </div>
              </div>
            </div>
			*/ ?>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Offer Description &nbsp;: </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <textarea name="offer_desc" class="col-xs-12 col-sm-8" id="offer_desc" placeholder="Offer Description" rows="5"></textarea>
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Offer Terms & Condition : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <textarea name="offer_terms" class="col-xs-12 col-sm-8 validate[required]" id="offer_terms" placeholder="Terms" rows="5"></textarea>
                  </div>
                </div>
              </div>
              <h3 class="lighter block green">Product Statics</h3>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Point Value : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="offer_pv" id="offer_pv"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Offer Pv">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Business Value : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="offer_bv" id="offer_bv"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Offer Bv">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Offer Price : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="offer_price" id="offer_price"  class="col-xs-12 col-sm-4 cal_product_price validate[required]" type="text" placeholder="Offer Price">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="MULTIPLE_OFFERS">
                  <button type="submit" name="submitOfferSave" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button onClick="window.location.href='<?php echo generateSeoUrlAdmin("shop","offerlist","");  ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                </div>
              </div>
            </form>
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
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		$(".cal_product_price").on('blur change',function(){
			var post_mrp = $("#post_mrp").val();
			var post_discount = $("#post_discount").val();
			if(post_mrp>0){
				var cal_product_discount = parseFloat(post_discount);
				var cal_product_price = parseFloat(post_mrp-cal_product_discount);
				$("#post_price").val(cal_product_price);
			}else{
				$("#post_price").val(0);
			}
		});
		$(".offer_type").on('click',function(){
			var offer_type = $(this).val();
			if(offer_type=="NEW"){
				$(".new_member").slideDown(600);
				$(".old_member").slideUp(600);
				return true;
			}

			if(offer_type=="OLD"){
				$(".new_member").slideUp(600);
				$(".old_member").slideDown(600);
				return true;
			}
		});
	});
</script>
</body>
</html>