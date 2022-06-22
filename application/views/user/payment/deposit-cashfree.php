<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	
								
	header("Pragma: no-cache");
	header("Cache-Control: no-cache");
	header("Expires: 0");


	$model = new OperationModel();
	$member_id = $this->session->userdata('mem_id');
	$AR_MEM = $model->getMember($member_id);
	$trns_type = "WALLET";
	$full_name = $AR_MEM['full_name'];
	$member_mobile = $AR_MEM['member_mobile'];
	$member_email = $AR_MEM['member_email'];
	
	$wallet_id = $POST['wallet_id'];
	$total_amount =  $POST['deposit_amount'];
	
	$return_url = generateSeoUrl("ipnhandler","cashfreedeposit","");
	$notify_url = generateSeoUrl("ipnhandler","cashfreefailed","");
	
	$AR_SET['wallet_id'] = $wallet_id;
	
	if(!$this->session->userdata('order_no')){
		$order_no = UniqueId("ORDER_NO");
		$model->setOnlinePayment($member_id,$order_no,$total_amount,"",$trns_type,$AR_SET);		
		$this->session->set_userdata('order_no',$order_no);
	}else{
		$order_no = $this->session->userdata('order_no');
	}
	
	$order_note = "WALLET DEPOSIT[".$order_no."]";
	

	
	if(!is_numeric($total_amount) && !isset($member_id)){
		echo "Unable to load please enter valid  amount"; exit; 
	}	
	
	
	$mode = "PROD"; //<------------ Change to TEST for test server, PROD for production
	
	$appId = CASH_FREE_APP_ID;
	$secretKey = CASH_FREE_SECRET_KEY;
	$postData = array( 
		"appId" => $appId, 
		"orderId" => $order_no, 
		"orderAmount" => $total_amount, 
		"orderCurrency" => 'INR', 
		"orderNote" => $order_note, 
		"customerName" => $full_name, 
		"customerPhone" => $member_mobile, 
		"customerEmail" => $member_email,
		"returnUrl" => $return_url, 
		"notifyUrl" => $notify_url
	);
	
	ksort($postData);
	$signatureData = "";
	foreach ($postData as $key => $value){
		$signatureData .= $key.$value;
	}
	$signature = hash_hmac('sha256', $signatureData, $secretKey,true);
	$signature = base64_encode($signature);

	if ($mode == "PROD") {
		$url = "https://www.cashfree.com/checkout/post/submit";
	} else {
		$url = "https://test.cashfree.com/billpay/checkout/post/submit";
	}
	
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CcAvenue Online Payment</title>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>jquery/jquery-1.11.1.js"></script>
</head>

<body>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <p>&nbsp;</p>
      <p class="errortxt" align="center">Please do not refresh this page, we are connecting to  payment gateway...., <br />
      </p>
       <div align="center"><img src="<?php echo BASE_PATH; ?>setupimages/ajax-loader.gif"/></div>
      <form action="<?php echo $url; ?>" name="frm1" method="post">
      <input type="hidden" name="signature" value='<?php echo $signature; ?>'/>
      <input type="hidden" name="orderNote" value='<?php echo $order_note; ?>'/>
      <input type="hidden" name="orderCurrency" value='INR'/>
      <input type="hidden" name="customerName" value='<?php echo $full_name; ?>'/>
      <input type="hidden" name="customerEmail" value='<?php echo $member_email; ?>'/>
      <input type="hidden" name="customerPhone" value='<?php echo $member_mobile; ?>'/>
      <input type="hidden" name="orderAmount" value='<?php echo $total_amount; ?>'/>
      <input type ="hidden" name="notifyUrl" value='<?php echo $notify_url; ?>'/>
      <input type ="hidden" name="returnUrl" value='<?php echo $return_url; ?>'/>
      <input type="hidden" name="appId" value='<?php echo $appId; ?>'/>
      <input type="hidden" name="orderId" value='<?php echo $order_no; ?>'/>
  </form>
    </div>
  </div>
</div>
</body>
<script language="javascript" type="text/javascript">
	document.frm1.submit();
</script>
<?php  exit; ?>
