<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
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
<?php auto_complete(); ?>
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
          <h1> Financial <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Add Transaction </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12" style="min-height:500px;"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("financial","addtransaction",""); ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Wallet Type : </label>
                <div class="col-sm-9">
                  <select name="wallet_id" class="col-xs-10 col-sm-5 validate[required]" id="wallet_id">
                    <?php DisplayCombo($ROW['wallet_id'],"WALLET"); ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Transaction Type : </label>
                <div class="col-sm-9">
                  <input type="radio" name="trns_type" id="trns_type"  value="Cr" class="validate[required]">
                  Credit &nbsp;&nbsp;
                  <input type="radio" name="trns_type" id="trns_type"  value="Dr" class="validate[required]">
                  Debit </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group" >
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">User Id : </label>
                <div class="col-sm-9">
                  <input id="demo-input-pre-populated" placeholder="User Id" name="member_id"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['member_user_id']; ?>">
                  <script type="text/javascript">
				$(document).ready(function() {
					$("#demo-input-pre-populated").tokenInput("<?php echo generateSeoUrlAdmin("operation","membersearch",""); ?>", {
						preventDuplicates: true
					});
				});
				</script> 
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Amount : </label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="Amount" name="initial_amount"  class="col-xs-10 col-sm-5 validate[required]" type="text" 
				value="<?php echo $ROW['initial_amount']; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Payment Type : </label>
                <div class="col-sm-9">
                  <select name="payment_type" class="col-xs-10 col-sm-5 validate[required]" id="payment_type">
                    <?php DisplayCombo($ROW['payment_type'],"PMTTYPE"); ?>
                  </select>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Chq/DD No. : </label>
                <div class="col-sm-9">
                  <input id="cheque_no" placeholder="Chq/DD No" name="cheque_no"  class="col-xs-10 col-sm-5 cheque_field" type="text" 
				value="<?php echo $ROW['cheque_no']; ?>" disabled="disabled">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Chq/DD Date : </label>
                <div class="col-sm-3">
                  <div class="input-group">
                    <input class="form-control col-xs-10 col-sm-12 date-picker cheque_field" name="cheque_date" id="cheque_date" value="" type="text"  disabled="disabled" />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Bank Name : </label>
                <div class="col-sm-9">
                  <input id="bank_name" placeholder="Bank Name " name="bank_name"  class="col-xs-10 col-sm-5 bank_field" type="text" 
				value="<?php echo $ROW['bank_name']; ?>" disabled="disabled">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Bank Branch : </label>
                <div class="col-sm-9">
                  <input id="bank_branch" placeholder="Bank Branch " name="bank_branch"  class="col-xs-10 col-sm-5 bank_field" type="text" 
				value="<?php echo $ROW['bank_branch']; ?>" disabled="disabled">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date : </label>
                <div class="col-sm-3">
                  <div class="input-group">
                    <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="trns_date" id="trns_date" value="" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Description : </label>
                <div class="col-sm-9">
                  <textarea name="trns_remark" class="col-xs-10 col-sm-5 validate[required]" id="form-field-1" placeholder="Remarks"><?php echo $ROW['trns_remark']; ?></textarea>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <input type="hidden" name="wallet_trns_id" id="wallet_trns_id" value="<?php echo $ROW['wallet_trns_id']; ?>">
                  <button type="submit" name="submitTransaction" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button onClick="window.location.href='<?php echo ADMIN_PATH."financial/addtransaction"; ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
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
		$("#form-page").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		
		$("#payment_type").on('change',function(){
			if($(this).val()=="DD"){
				$(".cheque_field").attr("disabled",true);
				$(".bank_field").attr("disabled",false);
			}else if($(this).val()=="CQ"){
				$(".cheque_field").attr("disabled",false);
				$(".bank_field").attr("disabled",true);
			}else{
				$(".cheque_field").attr("disabled",true);
				$(".bank_field").attr("disabled",true);
			}
		});
	});
</script> 
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</body>
</html>
