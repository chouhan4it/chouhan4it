<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {
	
	public function __construct(){
	   parent::__construct();
	   $this->load->library('parser');
	   $this->load->view('captcha/securimage');
	   $this->load->view('mailer/phpmailer');
	}
	
	
	

	
	
	public function payuform(){
		$this->checkMemberLogin();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$cart_price = $model->getCartTotal();
		
		if($cart_price==0 || $cart_price='' && $member_id>0){
			redirect(BASE_PATH);
		}
		
		$this->load->view('payuform',$data);
	}
	
	public function success(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		

		if($form_data['mihpayid']!='' && $this->input->post()!=''){
			if (isset($form_data["additionalCharges"])) {
				$additionalCharges=$form_data["additionalCharges"];
				$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
			}else{	  
				$retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;		
			}
			$hash = hash("sha512", $retHashSeq);
			if ($hash != $posted_hash) {
				$trans_message = "Invalid Transaction. Please try again";
			}else{
			   $trans_message = "Transaction successfull";			
			}         
		
			$member_id = FCrtRplc($form_data['member_id']);
			$mihpayid = FCrtRplc($form_data['mihpayid']);
			$mode = FCrtRplc($form_data['mode']);
			$status = FCrtRplc($form_data['status']);
			$unmappedstatus = FCrtRplc($form_data['unmappedstatus']);
			$key = FCrtRplc($form_data['key']);
			$txnid = FCrtRplc($form_data['txnid']);
			$amount = FCrtRplc($form_data['amount']);
			$addedon = FCrtRplc($form_data['addedon']);
			$productinfo = ($form_data['productinfo']);
			$firstname = FCrtRplc($form_data['firstname']);
			$lastname = FCrtRplc($form_data['lastname']);
			$address1 = FCrtRplc($form_data['address1']);
			$address2 = FCrtRplc($form_data['address2']);
			$city = FCrtRplc($form_data['city']);
			$state = FCrtRplc($form_data['state']);
			$country = FCrtRplc($form_data['country']);
			$zipcode = FCrtRplc($form_data['zipcode']);
			$email = FCrtRplc($form_data['email']);
			$phone = FCrtRplc($form_data['phone']);
			
			$hash = FCrtRplc($form_data['hash']);
			$field1 = FCrtRplc($form_data['field1']);
			$field2 = FCrtRplc($form_data['field2']);
			$field3 = FCrtRplc($form_data['field3']);
			$field4 = FCrtRplc($form_data['field4']);
			$field5 = FCrtRplc($form_data['field5']);
			$field6 = FCrtRplc($form_data['field6']);
			$field7 = FCrtRplc($form_data['field7']);
			$field8 = FCrtRplc($form_data['field8']);
			$field9 = FCrtRplc($form_data['field9']);
			$field10 = FCrtRplc($form_data['field10']);
			
			$PG_TYPE = FCrtRplc($form_data['PG_TYPE']);
			$encryptedPaymentId = FCrtRplc($form_data['encryptedPaymentId']);
			$bank_ref_num = FCrtRplc($form_data['bank_ref_num']);
			$bankcode = FCrtRplc($form_data['bankcode']);
			$error = FCrtRplc($form_data['error']);
			$error_Message = FCrtRplc($form_data['error_Message']);
			$name_on_card = FCrtRplc($form_data['name_on_card']);
			$cardnum = FCrtRplc($form_data['cardnum']);
			$cardhash = FCrtRplc($form_data['cardhash']);
			$amount_split = FCrtRplc($form_data['amount_split']);
			
			$payuMoneyId = FCrtRplc($form_data['payuMoneyId']);
			$discount = FCrtRplc($form_data['discount']);
			$net_amount_debit = FCrtRplc($form_data['net_amount_debit']);
			
			$productinfo_array = array_unique(array_filter(explode("-",$productinfo)));
			
			$reference_no = $productinfo_array[0];
			$member_id = $productinfo_array[1];
			$cart_total = $productinfo_array[2];
			$shipping = $productinfo_array[3];
			$net_total = $productinfo_array[4];
			$cart_session = $productinfo_array[5];
			$address_id = $productinfo_array[6];
			$coupon_mstr_id = $productinfo_array[7];
			


			$data = array("mihpayid"=>getToolValue($mihpayid),
				"mode"=>getToolValue($mode),
				"status"=>getToolValue($status),
				"unmappedstatus"=>getToolValue($unmappedstatus),
				"keyval"=>getToolValue($key),
				"txnid"=>getToolValue($txnid),
				"addedon"=>getToolValue($addedon),
				"productinfo"=>getToolValue($productinfo),
				"firstname"=>getToolValue($firstname),
				"lastname"=>getToolValue($lastname),
				"address"=>getToolValue($address1." ".$address2),
				"city"=>getToolValue($city),
				"state"=>getToolValue($state),
				"country"=>getToolValue($country),
				"zipcode"=>getToolValue($zipcode),
				"email"=>getToolValue($email),
				"phone"=>getToolValue($phone),
				

				
				"hash"=>getToolValue($hash),
				"field1"=>getToolValue($field1),
				"field2"=>getToolValue($field2),
				"field3"=>getToolValue($field3),
				"field4"=>getToolValue($field4),
				"field5"=>getToolValue($field5),
				"field6"=>getToolValue($field6),
				"field7"=>getToolValue($field7),
				"field8"=>getToolValue($field8),
				"field9"=>getToolValue($field9),
				
				"PG_TYPE"=>getToolValue($PG_TYPE),
				"encryptedPaymentId"=>getToolValue($encryptedPaymentId),
				"bank_ref_num"=>getToolValue($bank_ref_num),
				"bankcode"=>getToolValue($bankcode),
				"error"=>getToolValue($error),
				"error_Message"=>getToolValue($error_Message),
				"name_on_card"=>getToolValue($name_on_card),
				"cardnum"=>getToolValue($cardnum),
				"cardhash"=>getToolValue($cardhash),
				"amount_split"=>getToolValue($amount_split),
				"payuMoneyId"=>getToolValue($payuMoneyId),
				
				"trans_message"=>$trans_message,
				"discount"=>getToolValue($discount),
				"pmt_type"=>"ORD",
				"net_amount_debit"=>getToolValue($net_amount_debit)				
			);
			$this->SqlModel->updateRecord("tbl_pmt_history",$data,array("reference_no"=>$reference_no));
			
			if($status=="success"){
				$cart_price = $total_cart  = $model->getCartTotal($cart_session);
				$order_amount = ($cart_total + $shipping_charge)-$coupon_discount;
				$AR_SHIP = $model->getShippingDetail($address_id);
				
				$QR_CART = "SELECT tc.*, tp.franchisee_id,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, 
							tpl.post_mrp, tpl.post_hsn, tpl.tax_age, tpl.post_discount, tpl.short_desc, tpl.post_tax, tpl.post_price, 
							tpl.post_pv, tpl.post_bv,  tpl.update_date 
							FROM tbl_cart AS tc
							LEFT JOIN tbl_post AS tp ON tp.post_id=tc.post_id 
							LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
							LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
							LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tag_id,tpl.post_tags)
							LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
							LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
							WHERE tp.delete_sts>0  AND tc.cart_session='".$cart_session."'
							GROUP BY tc.cart_id  
							ORDER BY tc.cart_id ASC";
							$AR_CART = $this->SqlModel->runQuery($QR_CART);
								foreach($AR_CART as $AR_DT):
									
									$post_id = FCrtRplc($AR_DT['post_id']);
									$franchisee_id = FCrtRplc($AR_DT['franchisee_id']);
									$cart_mrp = FCrtRplc($AR_DT['cart_mrp']);	
									$cart_shipping = FCrtRplc($AR_DT['cart_shipping']);	
									$cart_image_id = FCrtRplc($AR_DT['cart_image_id']);			
									
									$post_hsn = FCrtRplc($AR_DT['post_hsn']);
									$tax_age = FCrtRplc($AR_DT['tax_age']);
									
									
									$post_selling = FCrtRplc($AR_DT['cart_selling']);
									$post_cmsn = FCrtRplc($AR_DT['cart_cmsn']);
									
									$post_pv = FCrtRplc($AR_DT['cart_pv']);
									$post_bv = FCrtRplc($AR_DT['cart_bv']);
									$post_qty = FCrtRplc($AR_DT['cart_qty']);
									$post_price = FCrtRplc($AR_DT['cart_price']);
									
									$shipping_charge =( $cart_shipping * $post_qty );
									$post_amount = ( $post_price * $post_qty ); 
									
									$total_cart =  ( $post_price * $post_qty );
									$total_paid = ( $total_cart + $shipping_charge ) - $coupon_discount;
									$total_paid_real = ( ( $cart_mrp * $post_qty ) + $shipping_charge ) - $coupon_discount;
									
									$order_no = $model->getOrderNo();
									$order_data = array("order_no"=>$order_no,
										"member_id"=>$member_id,
										"franchisee_id"=>$franchisee_id,
										"store_id"=>getTool($store_id,0),
										"address_id"=>$address_id,
										"lang_id"=>LANG_ID,
										"id_order_state"=>2,
										"payment"=>"PAYU",
										"order_message"=>getTool($order_message,''),
										
										"total_cart"=>$total_cart,
										"shipping_charge"=>getTool($shipping_charge,0),
										
										"total_paid"=>$total_paid,
										"total_paid_real"=>$total_paid_real,
										
										"total_products"=>$post_qty,
										"total_pv"=>getTool($cart_pv,0),
										"total_bv"=>getTool($cart_bv,0),
										
										"reference_no"=>$reference_no,
										"date_add"=>$current_date,
										"date_upd"=>$current_date
									);
									$order_id = $this->SqlModel->insertRecord("tbl_orders",$order_data);
									
									if($order_id>0){
										$data_dt = array("order_id"=>$order_id,
											"franchisee_id"=>getTool($franchisee_id,0),
											"post_id"=>$post_id,
											"post_image_id"=>getTool($cart_image_id,0),
											"single_pv"=>getTool($single_pv,0),
											"post_pv"=>getTool($post_pv,0),
											"post_bv"=>getTool($post_bv,0),
											"post_title"=>$AR_DT['cart_title'],
											"post_code"=>getTool($AR_DT['cart_code'],''),
											"post_desc"=>getTool($AR_DT['cart_desc'],''),
											
											"original_post_price"=>$AR_DT['cart_mrp'],
											"post_selling"=>getTool($post_selling,0),
											"post_cmsn"=>getTool($post_cmsn,0),
											"post_price"=>$post_price,
											"post_shipping"=>getTool($cart_shipping,0),
											
											"post_tax"=>getTool($AR_DT['cart_tax'],0),
											"tax_age"=>getTool($tax_age,0),
											"post_hsn"=>getTool($post_hsn,0),
											"post_qty"=>$post_qty,
											"net_amount"=>$post_amount,
											"date_time"=>$current_date
										);
										$this->SqlModel->insertRecord("tbl_order_detail",$data_dt);
										$this->SqlModel->deleteRecord("tbl_cart",array("cart_id"=>$AR_DT['cart_id']));
									}
									unset($order_id,$data_dt);
								endforeach;
			
						$trns_remark = "ORDER PURCHASE [".$reference_no."]";
						$trans_no = UniqueId("TRNS_NO");
						$model->sendOrderConfirmation($AR_MEM['mobile_number'],$reference_no,$order_amount);
							
						$this->session->unset_userdata('reference_no');
					set_message("success","Thank you for placing an order, you will get confirmation shortly");
					redirect_member("order","orderlist","");
			}
			
		}
		
		
	}
	
	public function failed(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');

		if($form_data['mihpayid']!='' && $this->input->post()!=''){
			if (isset($form_data["additionalCharges"])) {
				$additionalCharges=$form_data["additionalCharges"];
				$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
			}else{	  
				$retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;		
			}
			$hash = hash("sha512", $retHashSeq);
			if ($hash != $posted_hash) {
				$trans_message = "Invalid Transaction. Please try again";
			}else{
			   $trans_message = "Transaction successfull";			
			}         
		
			$member_id = FCrtRplc($form_data['member_id']);
			$mihpayid = FCrtRplc($form_data['mihpayid']);
			$mode = FCrtRplc($form_data['mode']);
			$status = FCrtRplc($form_data['status']);
			$unmappedstatus = FCrtRplc($form_data['unmappedstatus']);
			$key = FCrtRplc($form_data['key']);
			$txnid = FCrtRplc($form_data['txnid']);
			$amount = FCrtRplc($form_data['amount']);
			$addedon = FCrtRplc($form_data['addedon']);
			$productinfo = ($form_data['productinfo']);
			$firstname = FCrtRplc($form_data['firstname']);
			$lastname = FCrtRplc($form_data['lastname']);
			$address1 = FCrtRplc($form_data['address1']);
			$address2 = FCrtRplc($form_data['address2']);
			$city = FCrtRplc($form_data['city']);
			$state = FCrtRplc($form_data['state']);
			$country = FCrtRplc($form_data['country']);
			$zipcode = FCrtRplc($form_data['zipcode']);
			$email = FCrtRplc($form_data['email']);
			$phone = FCrtRplc($form_data['phone']);
			
			$hash = FCrtRplc($form_data['hash']);
			$field1 = FCrtRplc($form_data['field1']);
			$field2 = FCrtRplc($form_data['field2']);
			$field3 = FCrtRplc($form_data['field3']);
			$field4 = FCrtRplc($form_data['field4']);
			$field5 = FCrtRplc($form_data['field5']);
			$field6 = FCrtRplc($form_data['field6']);
			$field7 = FCrtRplc($form_data['field7']);
			$field8 = FCrtRplc($form_data['field8']);
			$field9 = FCrtRplc($form_data['field9']);
			$field10 = FCrtRplc($form_data['field10']);
			
			$PG_TYPE = FCrtRplc($form_data['PG_TYPE']);
			$encryptedPaymentId = FCrtRplc($form_data['encryptedPaymentId']);
			$bank_ref_num = FCrtRplc($form_data['bank_ref_num']);
			$bankcode = FCrtRplc($form_data['bankcode']);
			$error = FCrtRplc($form_data['error']);
			$error_Message = FCrtRplc($form_data['error_Message']);
			$name_on_card = FCrtRplc($form_data['name_on_card']);
			$cardnum = FCrtRplc($form_data['cardnum']);
			$cardhash = FCrtRplc($form_data['cardhash']);
			$amount_split = FCrtRplc($form_data['amount_split']);
			
			$payuMoneyId = FCrtRplc($form_data['payuMoneyId']);
			$trans_message = FCrtRplc($form_data['trans_message']);
			$discount = FCrtRplc($form_data['discount']);
			$net_amount_debit = FCrtRplc($form_data['net_amount_debit']);
			
			$productinfo_array = array_unique(array_filter(explode("-",$productinfo)));
			
			$reference_no = $productinfo_array[0];
			$member_id = $productinfo_array[1];
			$cart_total = $productinfo_array[2];
			$shipping = $productinfo_array[3];
			$net_total = $productinfo_array[4];
			$cart_session = $productinfo_array[5];
			$address_id = $productinfo_array[6];
			$coupon_mstr_id = $productinfo_array[7];

			$data = array("mihpayid"=>getToolValue($mihpayid),
				"mode"=>getToolValue($mode),
				"status"=>getToolValue($status),
				"unmappedstatus"=>getToolValue($unmappedstatus),
				"keyval"=>getToolValue($key),
				"txnid"=>getToolValue($txnid),
				"addedon"=>getToolValue($addedon),
				"productinfo"=>getToolValue($productinfo),
				"firstname"=>getToolValue($firstname),
				"lastname"=>getToolValue($lastname),
				"address"=>getToolValue($address1." ".$address2),
				"city"=>getToolValue($city),
				"state"=>getToolValue($state),
				"country"=>getToolValue($country),
				"zipcode"=>getToolValue($zipcode),
				"email"=>getToolValue($email),
				"phone"=>getToolValue($phone),
				

				
				"hash"=>getToolValue($hash),
				"field1"=>getToolValue($field1),
				"field2"=>getToolValue($field2),
				"field3"=>getToolValue($field3),
				"field4"=>getToolValue($field4),
				"field5"=>getToolValue($field5),
				"field6"=>getToolValue($field6),
				"field7"=>getToolValue($field7),
				"field8"=>getToolValue($field8),
				"field9"=>getToolValue($field9),
				
				"PG_TYPE"=>getToolValue($PG_TYPE),
				"encryptedPaymentId"=>getToolValue($encryptedPaymentId),
				"bank_ref_num"=>getToolValue($bank_ref_num),
				"bankcode"=>getToolValue($bankcode),
				"error"=>getToolValue($error),
				"error_Message"=>getToolValue($error_Message),
				"name_on_card"=>getToolValue($name_on_card),
				"cardnum"=>getToolValue($cardnum),
				"cardhash"=>getToolValue($cardhash),
				"amount_split"=>getToolValue($amount_split),
				"payuMoneyId"=>getToolValue($payuMoneyId),
				
				"trans_message"=>$trans_message,
				"discount"=>getToolValue($discount),
				"pmt_type"=>"ORD",
				"net_amount_debit"=>getToolValue($net_amount_debit)				
			);
			$this->SqlModel->updateRecord("tbl_pmt_history",$data,array("reference_no"=>$reference_no));
			redirect_front("order","cancel",array("reference_no"=>$reference_no));
		}
		
	}
	
	public function cancel(){
		$this->load->view('cancel',$data);
	}
	
	

}
?>