<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);

$request_id = _d($segment['request_id']);
$member_id = $this->session->userdata('mem_id');
$wallet_id = $this->OperationModel->getWallet(WALLET1);
$AR_MEM = $model->getMember($member_id);

?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
<style type="text/css">
.img-circle {
	border-radius: 50%;
}
.item-pic {
	width: 30px;
}
tr > td {
	font-size: 12px !important;
}
.scrollcss {
	width: 400px;
	height: 300px;
	overflow-y: scroll;
	border: 1px solid #E3E3E3;
	border-radius: 4px;
	color: #565656;
	padding: 7px 12px;
}
</style>
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container"> 
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Fund Transfer</h4>
        <p class="text-muted page-title-alt">You can transfer Fund Transfer to other member</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row"> 
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet"> 
          <!-- /primary heading -->
          <div class="portlet-heading">
            <h3 class="lighter block green">Please fill all details</h3>
            <div class="clearfix"></div>
          </div>
          <div class="portlet-body" > <?php echo get_message(); ?>
            <?php if($request_id=='' || $request_id==0){ ?>
            <div class="row">
              <div class="col-md-6">
               <div id="wallet_label"> </div>
                <form action="<?php  echo  generateMemberForm("financial","transferfund"); ?>" id="form-valid" name="form-valid" method="post">
                  <div class="clear">&nbsp;</div>
                  <div class="form-group">
                    <label for="" class="col-md-12 control-label">Wallet  Type    :</label>
                    <div class="col-md-12">
                      <select name="wallet_id" class="form-control validate[required] checkBalance" id="wallet_id">
                        <option value="">----select wallet----</option>
                        <?php DisplayCombo($ROW['wallet_id'],"WALLET"); ?>
                      </select>
                    </div>
                  </div>
                  <div class="clear">&nbsp;</div>
                  <div class="form-group">
                    <label for="" class="col-md-12 control-label">Member Id  :</label>
                    <div class="col-md-12">
                      <input id="user_id"  placeholder="Member Id"  name="user_id"  class="form-control check-username validate[required]" type="text" value="">
                    </div>
                    <div id="error_msg"></div>
                  </div>
                   <div class="clear">&nbsp;</div>
                  <div class="form-group">
                    <label for="" class="col-md-12 control-label">Amount  :</label>
                    <div class="col-md-12">
                      <input id="initial_amount"  placeholder="Amount"  name="initial_amount"  class="form-control validate[required,custom[integer]]" type="text" value="" maxlength="5">
                    </div>
                  </div>
                  <div class="clear">&nbsp;</div>
                  <div class="form-group">
                    <label for="new_again_password" class="col-md-12 control-label">Transaction Password :</label>
                    <div class="col-md-12">
                      <input name="trns_password" type="password" class="form-control validate[required]" id="trns_password" 
						      value="">
                    </div>
                  </div>
                  <div class="clear">&nbsp;</div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <input name="submitFundRequest" value="Submit" class="btn  btn-primary" id="submitFundRequest" type="submit">
                    </div>
                  </div>
                 
                </form>
              </div>
              <div class="col-md-6">
                  <h4>Notes:</h4>
                  <ul style="list-style-type:decimal;">
                    <li>Minimum fund transfer : <?php echo number_format($model->getValue("CONFIG_MIN_FUND_TRANSFER")); ?> </li>
                  </ul>
                </div>
            </div>
            <?php  }else{ ?>
            <div class="row">
              <div class="col-md-6">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                  <form action="<?php echo  generateMemberForm("financial","transferfund",array("request_id"=>$segment['request_id'])); ?>" id="otpForm" name="otpForm" method="post" autocomplete="on">
                    <div class="form-group">
                      <label for="sms_otp">Enter OTP</label>
                      <input type="password" name="sms_otp" id="sms_otp" placeholder="Check your registered  mobile number" value="" class="form-control validate[required,minSize[6],custom[integer]]" maxlength="8"/>
                    </div>
                    <div class="form-group">
                      <input type="hidden" name="request_id" id="request_id" value="<?php echo $segment['request_id']; ?>" />
                      <input type="submit" name="verifyOTP" value="Verify OTP" class="btn btn-primary btn-submit" id="updateOTP"/>
                      &nbsp;&nbsp;
                      <input type="button" name="button_close" onclick="window.location.href='<?php echo generateSeoUrl("financial","transferfund",""); ?>'" value="Cancel" class="btn btn-danger btn-submit"  id="button_close"/>
                    </div>
                  </form>
                </div>
              </div>
              <br>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation();  ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$(".checkBalance").on('change',function(){
			var wallet_id = $("#wallet_id").val();
			var URL_LOAD = "<?php echo BASE_PATH; ?>json/jsonhandler";
			$.getJSON(URL_LOAD,{switch_type:"WALLET_BALANCE",wallet_id:wallet_id},function(JsonEval){
				if(JsonEval){
					if(JsonEval.net_balance>=0){
						$("#wallet_label").html('<div class="alert alert-success">Available amount on your  wallet :  '+JsonEval.net_balance+'<div>');
					}
				}
			});
		});
		
		$(".check-username").on('blur',function(){
			var user_id = $(this).val();
			var URL_LOAD = "<?php echo BASE_PATH; ?>json/jsonhandler";
			$.getJSON(URL_LOAD,{switch_type:"CHECKUSR",user_name:user_id},function(JsonEval){
				$("#error_msg").show();
				if(JsonEval){
					if(JsonEval.member_id>0){
						$("#error_msg").html("<div class='alert alert-success'>"+JsonEval.full_name+"</div>");
						return true;
					}else{
						$("#user_id").val('');
						$("#error_msg").html("<div class='alert alert-danger'>'"+user_id+"' member not found</div>");
						return false;
					}
				}else{
					$("#user_id").val('');
					$("#error_msg").html("<div class='alert alert-danger'>'"+user_id+"' member not found</div>");
					return false;
				}
			});
		});
		
	});
</script>
</html>
