<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(2);
$user_id = $segment['user_id'];
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
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<?php auto_complete(); ?>
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
.pointer {
	cursor: pointer;
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
          <h1> Membership <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Topup </small> </h1>
        </div>
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-8" style="min-height:500px;">
            <form class="form-horizontal"   name="form-valid" id="form-valid" action="<?php echo generateAdminForm("member","membertopup","");  ?>" method="post" >
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member username : </label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="Username" name="user_id"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $user_id; ?>">
                  <input type="hidden" name="member_id" id="member_id" >
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Package: </label>
                <div class="col-sm-9">
                  <select class="col-xs-10 col-sm-5 getPinPrice validate[required]" name="type_id" id="type_id">
                    <option value="" selected="selected">---select----</option>
                    <?php echo DisplayCombo($type_id,"PIN_TYPE"); ?>
                  </select>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Package Price: </label>
                <div class="col-sm-9">
                  <input name="pin_price" id="pin_price" class="form-control input-xlarge  validate[required]" type="text" placeholder="Package Price" value="" readonly>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Payment Option : </label>
                <div class="col-xs-3 col-sm-3">
                  <input type="radio" name="payment_type" id="payment_type" class="payment_option" value="WALLET-A">
                  E-wallet </div>
                <div class="col-xs-3 col-sm-3">
                  <input type="radio" name="payment_type" id="payment_type" class="payment_option" value="EPIN-A">
                  E-pin </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group option_wallet">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">User Id:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="user_id_wallet" id="user_id_wallet" class="form-control input-xlarge validate[required]" type="text"  value="" >
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group option_epin">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">E-pin No:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="pin_no" id="pin_no" class="form-control input-xlarge validate[required]" type="text"  value="" >
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group option_epin">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">E-pin Key:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="pin_key" id="pin_key" class="form-control input-xlarge validate[required]" type="text"  value="" >
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <button type="submit" name="submitTopup" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> TOPUP MEMBER </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>tiny/nicEdit.js" type="text/javascript"></script>
<?php jquery_validation(); auto_complete(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine(
		   {onValidationComplete: function(form, valid){
            if (valid) {
				return confirm('Are you sure you want to submit?');
            }
           }}
		);
	});
	$(function(){
		$(".option_epin").hide();
		$(".option_wallet").hide();
		$("#form-page").validationEngine();
		$(".other_id").on('click',function(){
			$("#user_id").attr("readonly",false);
			$("#user_id").val('');
		});
		$(".payment_option").on('click',function(){
			var payment_type = $(this).val();
			switch(payment_type){
				case "WALLET-A":
					$(".option_epin").slideUp(600);
					$(".option_wallet").slideDown(600);
				break;
				case "EPIN-A":
					$(".option_wallet").slideUp(600);
					$(".option_epin").slideDown(600);
				break;
				default:
					$(".option_epin").slideUp(600);
					$(".option_wallet").slideUp(600);
				break;
			}
			
		});
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
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEM_DESG";
});
</script>
</body>
</html>
