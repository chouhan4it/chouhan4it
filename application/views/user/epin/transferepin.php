<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);
$request_id = _d($segment['request_id']);
$member_id = $this->session->userdata('mem_id');

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
.scrollcss{
	width:400px;
	height:300px;
	overflow-y:scroll;
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
        <h4 class="page-title">E-Pin Send</h4>
        <p class="text-muted page-title-alt">You can transfer E-Pin to other user</p>
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
             <?php if($request_id=='' || $request_id==0){ ?>
              <div class="row"> 
                <div class="col-md-6">
                  <form action="<?php  echo  generateMemberForm("epin","transferepin"); ?>" id="form-valid" name="form-valid" method="post">
                            <div class="clear">&nbsp;</div>
                            <div class="form-group">
                            	
                              <label for="" class="col-md-12 control-label">Pin   :</label>
                              <div class="col-md-12">
                              	<div class="scrollcss">
                              <?php 
									$QR_PIN = "SELECT tpd.*, tpt.pin_name 
									FROM tbl_pinsdetails AS tpd 
									LEFT JOIN tbl_pintype AS tpt ON tpt.type_id=tpd.type_id
									WHERE tpd.member_id='".$member_id."' AND tpd.pin_sts='N'  
									AND tpd.to_member_id='0'
									ORDER BY tpd.pin_id ASC"; 
									$RS_PIN = $this->SqlModel->runQuery($QR_PIN);
									foreach($RS_PIN as $AR_PIN):
										echo '<input type="checkbox" name="pin_id[]" value="'.$AR_PIN["pin_id"].'">&nbsp;'.$AR_PIN['pin_key']." @ ".$AR_PIN['pin_name']."<br>";
									endforeach;
							  ?>
                              </div>

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
                              <label for="new_again_password" class="col-md-12 control-label">Transaction Password :</label>
                               <div class="col-md-12">
                              <input name="trns_password" type="password" class="form-control validate[required]" id="trns_password" 
						      value="">
                              </div>
                            </div>
                            <div class="clear">&nbsp;</div>
                            <div class="form-group">
                             <div class="col-md-12">
                              <input name="submitPinTransfer" value="Submit" class="btn  btn-primary" id="submitPinTransfer" type="submit">
                              </div>
                            </div>
                          </form>
                </div>
              </div>
              <?php  }else{ ?>
              <div class="row">
                <div class="col-md-6">
                  <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                          <form action="<?php echo  generateMemberForm("epin","transferepin",array("request_id"=>$segment['request_id'])); ?>" id="otpForm" name="otpForm" method="post" autocomplete="on">
                            <div class="form-group">
                              <label for="sms_otp">Enter OTP</label>
                               <input type="password" name="sms_otp" id="sms_otp" placeholder="Check your registered  mobile number" value="" class="form-control validate[required,minSize[6],custom[integer]]" maxlength="8"/>
                            </div>
                            <div class="form-group">
                            
                              <input type="hidden" name="request_id" id="request_id" value="<?php echo $segment['request_id']; ?>" />
                              <input type="submit" name="verifyOTP" value="Verify OTP" class="btn btn-primary btn-submit" id="updateOTP"/>
                               &nbsp;&nbsp;
                      <input type="button" name="button_close" onclick="window.location.href='<?php echo generateSeoUrl("epin","transferepin",""); ?>'" value="Cancel" class="btn btn-danger btn-submit"  id="button_close"/>
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
<?php jquery_validation(); auto_complete(); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		$(".getPinValue").on('blur',getPinValue);
		function getPinValue(){
			var pin_id = $("#pin_id").val();
			var URL_LOAD = "<?php echo BASE_PATH; ?>json/jsonhandler?switch_type=PIN_VALUE&pin_id="+pin_id;
			$.getJSON(URL_LOAD,function(jsonReturn){
				if(jsonReturn.pin_id>0){
					$("#pin_price").val(jsonReturn.pin_price);
				}
			});
		}
		
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
