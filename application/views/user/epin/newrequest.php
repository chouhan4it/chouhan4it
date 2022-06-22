<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);

$request_id = _d($segment['request_id']);
$sms_otp = $model->getOpt($request_id);
$member_id = $this->session->userdata('mem_id');
$wallet_id_1 = $model->getWallet(WALLET1);

$AR_MEM = $model->getMember($member_id);


$AD_LDGR = $model->getCurrentBalance($member_id,$wallet_id_1,"","");
 ?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
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
        <h4 class="page-title">E-Pin Purchase</h4>
        <p class="text-muted page-title-alt">You can purchase new E-pin </p>
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
          <div class="portlet-body" >
          <?php echo get_message(); ?>
          	  <?php if($request_id='' || $request_id==0){ ?>
           	 <div class="row">
              <div class="col-lg-6"> 
                <form action="<?php  echo  generateMemberForm("epin","newrequest"); ?>" id="form-valid" name="form-valid" method="post">
                            <div class="form-group">
                              <label>Pin Type  :</label>
                              <select  name="type_id" id="type_id" class="col-md-12 form-control validate[required] getPinPrice">
                                <option value="">Select Pin</option>
                                <?php echo DisplayCombo($ROW['type_id'],"PACKAGE"); ?>
                              </select>
                            </div>
                          <div class="clearfix">&nbsp;</div>
                            <div class="form-group">
                              <label>Pin Price/Unit :</label>
                              <input id="pin_price" readonly placeholder="Pin Price"  name="pin_price"  class="form-control validate[required]" type="text" value="<?php echo $ROW['pin_price']; ?>">
                            </div>
                           
                            <div class="form-group">
                              <label>No Of Pin :</label>
                              <input id="no_pin" placeholder="" name="no_pin" maxlength="3"  class="form-control validate[required,custom[integer]] getCalculate" type="text" value="<?php echo $ROW['no_pin']; ?>">
                            </div>
                            
                            <div class="form-group">
                              <label>Net Amount :</label>
                              <input name="net_amount" type="text" class="form-control validate[required]" id="net_amount" value="<?php echo $ROW['net_amount']; ?>" readonly>
                            </div>
                           
                            <div class="form-group">
                              <label >Note:</label>
                              <textarea name="payment_sts" class="form-control validate[required]" id="form-field-1"><?php echo $ROW['payment_sts']; ?></textarea>
                            </div>
                            
                            
                            <div class="form-group">
                            <div class="radio">
                             
                              <input id="EWALLET" name="payment_type" value="EWALLET"  class="validate[required] select-wallet" 
                              type="radio">
                              <label for="EWALLET">Wallet</label>
                              <p><small>You can pay through your wallet, your available balance <?php echo number_format($AD_LDGR['net_balance'],2); ?></small></p>
                               &nbsp;&nbsp;
                               &nbsp;&nbsp;
                               <!--<input id="ONLINE" name="payment_type" value="ONLINE"  class="validate[required] select-wallet" 
                              type="radio" disabled>
                              <label for="ONLINE"> Online <small>(Powered by PayTm)</small></label>
                               &nbsp;&nbsp;-->
                            </div>
                          </div>
                            <div class="form-group option-wallet">
                              <label>Wallet :</label>
                                <select name="wallet_id" class="form-control validate[required] checkBalance" id="wallet_id">
                                <option value="">----select wallet----</option>
                                <?php DisplayCombo($ROW['wallet_id'],"WALLET"); ?>
                                </select>
                            </div>
                          <div class="clearfix">&nbsp;</div>
                            <div class="form-group option-wallet">
                              <label>Transaction Password :</label>
                              <input name="trns_password" type="password" class="form-control validate[required]" id="trns_password" 
                    value="">
                            </div>
                            
                            <div class="form-group">
                              
                              <input type="hidden" name="submitPinRequest" id="submitPinRequest" value="1" />
                              <input name="buttonRequest" value="Submit" class="btn  btn-primary" id="buttonRequest" type="submit">
                            </div>
                          </form>
              </div>
            </div>
             <?php  }else{ ?>
              <div class="row">
                  <div class="col-lg-6"> 
                  	<form action="<?php echo  generateMemberForm("epin","newrequest",array("request_id"=>$segment['request_id'])); ?>" id="otpForm" name="otpForm" method="post">
                            <div class="form-group">
                              <label for="transaction_password">Enter OTP :</label>
                               <div class="clear">&nbsp;</div>
                               <input type="password" name="sms_otp" id="sms_otp" class="form-control validate[required,minSize[6],custom[integer]]" maxlength="7"/>
                              <div class="clear">&nbsp;</div>
                            </div>
                            <div class="form-group">
                           
                              <input type="hidden" name="request_id" id="request_id" value="<?php echo $segment['request_id']; ?>" />
                               <input type="submit" name="verifyOTP" value="Confirm" class="btn btn-primary btn-submit" id="verifyOTP"/>
                      &nbsp;&nbsp;
                      <input type="button" name="button_close" value="Cancel" class="btn btn-danger btn-submit"   onclick="window.location.href='<?php echo generateSeoUrlMember("epin","newrequest",""); ?>'" id="button_close"/>
                          
                            </div>
                          </form>
                  </div>
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
<?php jquery_validation(); auto_complete(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
	
		$(".getPinPrice").on('blur',getPinPrice);
		$(".getCalculate").on('blur',getCalculate);
		
		function getPinPrice(){
			var type_id = $("#type_id").val();
			var URL_LOAD = "<?php echo BASE_PATH; ?>json/jsonhandler";
			
			$("#pin_price").val(0);
			$("#net_amount").val(0);
			var data = {
				switch_type : "PIN_TYPE",
				type_id : type_id
				
			}
			$.getJSON(URL_LOAD,data,function(json_eval){
				if(json_eval){
					if(json_eval.type_id>0){
						var net_price = parseInt(json_eval.net_price);
						$("#pin_price").val(net_price);
						
					}else{
						$("#no_pin").val(0);
						$("#pin_price").val(0);
					}
					
				}else{
					$("#no_pin").val(0);
					$("#pin_price").val(0);
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
		
		$(".option-wallet").hide();
		$(".select-wallet").on('click',function(){
			var payment_type = $(this).val();
			if(payment_type=="EWALLET"){
				$(".option-wallet").slideDown(600);
			}else{
				$(".option-wallet").slideUp(600);
			}
			return true;
		});
	});
</script>
</html>
