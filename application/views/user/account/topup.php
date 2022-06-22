<?php 
$model = new OperationModel();
defined('BASEPATH') OR exit('No direct script access allowed');
$member_id = $this->session->userdata('mem_id');
$AR_MEM = $model->getMember($member_id);
$wallet_id = $model->getDefaultWallet();
$AR_LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
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
        <h4 class="page-title">Topup Account</h4>
        <p class="text-muted page-title-alt">You can topup your or other  account </p>
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
            <div class="row">
              <div class="col-lg-6"> <?php echo get_message(); ?>
                <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateMemberForm("account","topup",""); ?>" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="user_id">User Id : </label>
                    <div class="col-xs-6 col-sm-6">
                      <input name="user_id" id="user_id" class="form-control input-xlarge validate[required] user_id" type="text" placeholder="User Id" value="<?php echo $AR_MEM['user_id']; ?>" readonly>
                    </div>
                    <div class="col-xs-3 col-sm-3"><a href="javascript:void(0)" class="other_id">Other Id ?</a></div>
                  </div>
                  <div class="col-xs-9 col-xs-offset-3"  id="error_msg" style="display:none;"></div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Package:</label>
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
                  <h3 class="lighter block green">Payment Option</h3>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Mode :</label>
                    <div class="col-xs-3 col-sm-3">
                      <input type="radio" name="payment_type" id="payment_type" class="payment_option" value="WALLET">
                      E-wallet </div>
                    <div class="col-xs-3 col-sm-3">
                      <input type="radio" name="payment_type" id="payment_type" class="payment_option" value="EPIN">
                      E-pin </div>
                    <div class="col-xs-3 col-sm-3">
                      <input type="radio" name="payment_type" id="payment_type" class="payment_option" value="ONLINE" disabled>
                      Online (Powered By Paytm) </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group option_wallet">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="trns_password">Transaction Password:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="trns_password" id="trns_password" class="form-control input-xlarge validate[required]" type="password"  value="" >
                      </div>
                      <p>Available balance in your wallet <?php echo number_format($AR_LDGR['net_balance'],0); ?></p>
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
                  <div class="clearfix form-action">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                      <button type="submit" name="submitTopup" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                      &nbsp;&nbsp;
                      <button onClick="window.location.href='<?php echo MEMBER_PATH; ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
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
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$(".option_epin").hide();
		$(".option_wallet").hide();
		$("#form-page").validationEngine();
		$(".other_id").on('click',function(){
			$("#user_id").attr("readonly",false);
			$("#user_id").val('');
		});
		$(".user_id").on('blur',function(){
			var user_id = $(this).val();
			var URL_LOAD = "<?php echo BASE_PATH; ?>json/jsonhandler";
			if(user_id!=''){
				$.getJSON(URL_LOAD,{switch_type:"CHECKUSR",user_name:user_id},function(JsonEval){
					$("#error_msg").show();
					if(JsonEval){
						if(JsonEval.member_id>0){
							$("#error_msg").html("<div class='alert alert-success'>'"+JsonEval.full_name+"'  member validated !</div>");					
							return true;
						}else{
							$("#user_id").val('');
							$("#error_msg").html("<div class='alert alert-danger'>'"+user_id+"' Invalid  member</div>");
							return false;
						}
					}else{
						$("#user_id").val('');
						$("#error_msg").html("<div class='alert alert-danger'>'"+user_id+"' Invalid  member</div>");
						return false;
					}
				});
			}
		});

		$(".payment_option").on('click',function(){
			var payment_type = $(this).val();
			switch(payment_type){
				case "WALLET":
					$(".option_epin").slideUp(600);
					$(".option_wallet").slideDown(600);
				break;
				case "EPIN":
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
					$("#pin_price").val(jsonReturn.net_price);
				}
			});
		}
	});
</script>
</html>
