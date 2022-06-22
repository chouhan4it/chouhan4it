<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
          <h1>Generate <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  E-Pin Branch</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("epin","generatefran",""); ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> E-Pin Name :</label>
                <div class="col-sm-9">
                  <select  name="type_id" id="type_id" class="col-xs-5 col-sm-5 validate[required] getPinPrice">
                    <option value="">Select Pin</option>
                    <?php echo DisplayCombo($ROW['type_id'],"PIN_TYPE"); ?>
                  </select>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">E-Pin Price </label>
                <div class="col-sm-9">
                  <input id="pin_price" readonly placeholder="Pin Price"  name="pin_price"  class="col-xs-5 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['pin_price']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">No Of E-Pin :</label>
                <div class="col-sm-9">
                  <input id="no_pin" placeholder="" name="no_pin" maxlength="3"  class="col-xs-5 col-sm-5 validate[required,custom[integer]] getCalculate" type="text" value="<?php echo $ROW['no_pin']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Net Amount :</label>
                <div class="col-sm-9">
                  <input name="net_amount" type="text" class="col-xs-5 col-sm-5 validate[required]" id="net_amount" value="<?php echo $ROW['net_amount']; ?>" readonly>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Branch :</label>
                <div class="col-sm-9">
                  <input name="store" type="text" class="col-xs-5 col-sm-5 validate[required]" id="store" value="<?php echo $ROW['store']; ?>" placeholder="">
                  <input type="hidden" name="franchisee_id" id="franchisee_id">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Bank Name :</label>
                <div class="col-sm-9">
                  <select  name="bank_id" id="bank_id" class="col-xs-5 col-sm-5 validate[required]">
                    <option value="">Select Pin</option>
                    <?php echo DisplayCombo($ROW['bank_id'],"BANK"); ?>
                  </select>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date  :</label>
                <div class="col-sm-3">
                  <div class="input-group">
                    <input class="form-control col-xs-6 col-sm-3  validate[required] date-picker" name="payment_date" id="id-date-picker-1" value="<?php echo $ROW['payment_date']; ?>" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span> </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Payment Details :</label>
                <div class="col-sm-9">
                  <textarea name="payment_sts" class="col-xs-6 col-sm-6 validate[required]" id="form-field-1"><?php echo $ROW['payment_sts']; ?></textarea>
                </div>
              </div>
              <div class="clearfix space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <input type="hidden" name="mstr_id" id="mstr_id" value="<?php echo $ROW['mstr_id']; ?>">
                  <button type="submit" name="generateEpin" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button  class="btn btn-danger" type="button" onClick="window.location.href='<?php echo generateSeoUrlAdmin("epin","pingenerate",array("")); ?>'"> <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel </button>
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
<?php jquery_validation(); auto_complete(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		$(".getPinPrice").on('blur',getPinPrice);
		$(".getCalculate").on('blur',getCalculate);
		function getPinPrice(){
			var type_id = $("#type_id").val();
			var URL_LOAD = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=PIN_TYPE&type_id="+type_id;
			$.getJSON(URL_LOAD,function(jsonReturn){
				if(jsonReturn.type_id>0){
					$("#pin_price").val(jsonReturn.net_price);
					$("#no_pin").val('');
				}
			});
		}
		
		function getCalculate(){
			var no_pin = $("#no_pin").val();
			var pin_price = $("#pin_price").val();
			if(no_pin>0 && pin_price>0){
				var net_amount = pin_price*no_pin;
				$("#net_amount").val(net_amount);
			}
		}
	});
</script> 
<script type="text/javascript" language="javascript">
new Autocomplete("store", function(){
	this.setValue = function( id ) {document.getElementsByName("franchisee_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=FRANCHISEE";
});
</script>
</body>
</html>
