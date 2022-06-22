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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/chosen.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
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
          <h1> Fund Managing Accounts<small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Add / Update</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo generateAdminForm("epin","addpin",""); ?>" enctype="multipart/form-data" method="post">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Plan Name :</label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="Plan Name" name="pin_name"  class="col-xs-9 col-sm-9 validate[required]" type="text" value="<?php echo $ROW['pin_name']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Plan Generate Letter <br>
                  <small>(Must be "2" Character) :</small></label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="Plan Letter" maxlength="2" name="pin_letter"  class="col-xs-9 col-sm-9 validate[required,minSize[2],maxSize[2]]" type="text" value="<?php echo $ROW['pin_letter']; ?>">
                </div>
              </div>
             <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Pin Price  (<?php echo CURRENCY; ?>) :</label>
                <div class="col-sm-9">
                  <input id="pin_price" placeholder="Pin Price" name="pin_price"  class="col-xs-9 col-sm-9 calc_price validate[required]" type="text" value="<?php echo $ROW['pin_price']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Gst Price  (<?php echo CURRENCY; ?>) :</label>
                <div class="col-sm-9">
                  <input id="gst_price" placeholder="Gst Price" name="gst_price"  class="col-xs-9 col-sm-9 calc_price validate[required]" type="text" value="<?php echo $ROW['gst_price']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Total Price  (<?php echo CURRENCY; ?>) :</label>
                <div class="col-sm-9">
                  <input id="net_price" placeholder="Total Price" name="net_price"  class="col-xs-9 col-sm-9 validate[required]" type="text" value="<?php echo $ROW['net_price']; ?>" readonly>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Direct Bonus  (%) :</label>
                <div class="col-sm-9">
                  <input id="direct_bonus" placeholder="Direct Bonus" name="direct_bonus"  class="col-xs-9 col-sm-9 validate[required]" type="text" value="<?php echo $ROW['direct_bonus']; ?>" >
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Point Value :</label>
                <div class="col-sm-9">
                  <input id="prod_pv" placeholder="Point Value" name="prod_pv"  class="col-xs-9 col-sm-9 validate[required]" type="text" value="<?php echo $ROW['prod_pv']; ?>" >
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Image :</label>
                <div class="col-sm-9">
                  <input name="pin_image[]" type="file" class="col-xs-12 col-sm-4" id="pin_image"  placeholder="Image" multiple>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Product :</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_id" id="demo-input-pre-populated" class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="tags" value="<?php echo $ROW['post_tags']; ?>">
                    <?php $AR_TAG = $model->getPinPost($ROW['type_id']); ?>
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
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Plan Details :</label>
                <div class="col-sm-9">
                  <textarea name="description" class="col-xs-9 col-sm-9 validate[required]" id="form-field-1" placeholder="Plan details"><?php echo $ROW['description']; ?></textarea>
                </div>
              </div>
              <div class="clearfix space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <input type="hidden" name="type_id" id="type_id" value="<?php echo $ROW['type_id']; ?>">
                  <button type="submit" name="submitEpin" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button  class="btn btn-danger" type="button" onClick="window.location.href='<?php echo generateSeoUrlAdmin("epin","pintype",array("")); ?>'"> <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel </button>
                  <button  class="btn btn-info" type="reset"> <i class="ace-icon fa fa-refresh bigger-110"></i> Reset </button>
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD hh:mm'
		});
		$(".calc_price").on('blur',function(){
			var pin_price = parseFloat($("#pin_price").val());
			var gst_price = parseFloat($("#gst_price").val());
			var net_price = pin_price + gst_price;
			$("#net_price").val(net_price);
		});
	});
</script>
</body>
</html>
