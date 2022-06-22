<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ipnhandler extends MY_Controller {
	 public function __construct() {
        parent::__construct();
    }
	
	
	public function cashfreeorder(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$cart_session = $this->session->userdata('session_id');
		$secretkey = CASH_FREE_SECRET_KEY;
		
		$orderId = FCrtRplc($form_data['orderId']);
		$orderAmount = FCrtRplc($form_data['orderAmount']);
		$referenceId = FCrtRplc($form_data['referenceId']);
		$txStatus = FCrtRplc($form_data['txStatus']);
		$paymentMode = FCrtRplc($form_data['paymentMode']);
		$txMsg = FCrtRplc($form_data['txMsg']);
		$txTime = FCrtRplc($form_data['txTime']);
		$signature = FCrtRplc($form_data['signature']);
		
		$data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
		$hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
		$computedSignature = base64_encode($hash_hmac);
		
		$json_return = json_encode($form_data);
		
		$data_set = array("merchant_id"=>CASH_FREE_APP_ID,
			"bank_name"=>$paymentMode,
			"bank_ref_no"=>$referenceId,
			"trns_id"=>$referenceId,
			"payment_mode"=>$paymentMode,
			"trns_currency"=>'INR',
			"gate_way"=>$paymentMode,
			"order_amount"=>$orderAmount,
			"payment_sts"=>$txStatus,
			
			"response_code"=>$referenceId,
			"response_msg"=>$txMsg,
			"check_sum"=>$signature,
			"json_return"=>$json_return,
			"update_date"=>$current_date,
			"trns_date"=>$txTime
		);
		
		$this->SqlModel->updateRecord("tbl_online_payment",$data_set,array("reference_no"=>$orderId));
		
		$AR_ORDER = $model->getPaymentDetail($orderId);
		$reference_no = $AR_ORDER['reference_no'];
		$type_id = $AR_ORDER['type_id'];
		$address_id = $AR_ORDER['address_id'];
		
		$member_id = $AR_ORDER['member_id'];
		$AR_MEM = $model->getMember($member_id);
		
		if ($signature == $computedSignature) {
			$cart_price = $total_cart  = $model->getCartTotal();
			$order_amount = ($cart_total + $shipping_charge)-$coupon_discount;
			$AR_SHIP = $model->getShippingDetail($address_id);
			
			if($txStatus=="SUCCESS"){
			
				$QR_CART = "SELECT tc.*, tp.franchisee_id,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, 
								tpl.post_mrp, tpl.post_selling_mrp, tpl.post_hsn, tpl.tax_age, tpl.post_discount, tpl.short_desc, 
								tpl.post_tax, tpl.post_price, tpl.post_pv, tpl.post_bv,  tpl.update_date 
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
										$post_shipping = FCrtRplc($AR_DT['cart_shipping']);	
										$post_image_id = FCrtRplc($AR_DT['cart_image_id']);	
										$post_attribute_id = FCrtRplc($AR_DT['cart_attribute_id']);	
										$post_attribute = FCrtRplc($AR_DT['cart_attribute']);		
										
										$post_hsn = FCrtRplc($AR_DT['post_hsn']);
										$post_tax = FCrtRplc($AR_DT['cart_tax']);
										$tax_age = FCrtRplc($AR_DT['tax_age']);
										
										$post_width = FCrtRplc($AR_DT['cart_width']);	
										$post_height = FCrtRplc($AR_DT['cart_height']);	
										$post_depth = FCrtRplc($AR_DT['cart_depth']);	
										$post_weight = FCrtRplc($AR_DT['cart_weight']);	
										
										
										$post_selling_mrp = FCrtRplc($AR_DT['post_selling_mrp']);
										$post_selling = FCrtRplc($AR_DT['cart_selling']);
										$post_cmsn = FCrtRplc($AR_DT['cart_cmsn']);
										
										$post_pv = FCrtRplc($AR_DT['cart_pv']);
										$post_bv = FCrtRplc($AR_DT['cart_bv']);
										$post_qty = FCrtRplc($AR_DT['cart_qty']);
										$post_price = FCrtRplc($AR_DT['cart_price']);
										
										$shipping_charge =( $post_shipping * $post_qty );
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
											"payment"=>"CASHFREE",
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
												"post_image_id"=>getTool($post_image_id,0),
												"post_attribute_id"=>getTool($post_attribute_id,0),
												"post_attribute"=>getTool($post_attribute,''),
												"single_pv"=>getTool($single_pv,0),
												"post_pv"=>getTool($post_pv,0),
												"post_bv"=>getTool($post_bv,0),
												"post_title"=>$AR_DT['cart_title'],
												"post_code"=>getTool($AR_DT['cart_code'],''),
												"post_desc"=>getTool($AR_DT['cart_desc'],''),
												
												"post_width"=>getTool($post_width,0),
												"post_height"=>getTool($post_height,0),
												"post_depth"=>getTool($post_depth,0),
												"post_weight"=>getTool($post_weight,0),
												
												"original_post_price"=>getTool($cart_mrp,0),
												"post_selling_mrp"=>getTool($post_selling_mrp,0),
												"post_selling"=>getTool($post_selling,0),
												"post_cmsn"=>getTool($post_cmsn,0),
												"post_price"=>$post_price,
												"post_shipping"=>getTool($post_shipping,0),
												
												"post_tax"=>getTool($post_tax,0),
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
							#$trans_no = UniqueId("TRNS_NO");
							$model->sendOrderConfirmation($AR_MEM['mobile_number'],$reference_no,$order_amount);
								
							$this->session->unset_userdata('reference_no');
						set_message("success","Thank you for placing an order, you will get confirmation shortly");
						redirect_member("order","orderlist","");
			}else{
				$this->session->unset_userdata('reference_no');
				set_message("warning","Transaction failed, ".$txMsg);
				redirect_front("account","paymentstatus",array("reference_no"=>$reference_no));
			}
		}else{
			$this->session->unset_userdata('reference_no');
			set_message("warning","Transaction failed, ".$txMsg);
			redirect_front("account","paymentstatus",array("reference_no"=>$reference_no));
		}
	}
	
	
	public function cashfreedeposit(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$cart_session = $this->session->userdata('session_id');
		$secretkey = CASH_FREE_SECRET_KEY;
		
		$orderId = FCrtRplc($form_data['orderId']);
		$orderAmount = FCrtRplc($form_data['orderAmount']);
		$referenceId = FCrtRplc($form_data['referenceId']);
		$txStatus = FCrtRplc($form_data['txStatus']);
		$paymentMode = FCrtRplc($form_data['paymentMode']);
		$txMsg = FCrtRplc($form_data['txMsg']);
		$txTime = FCrtRplc($form_data['txTime']);
		$signature = FCrtRplc($form_data['signature']);
		
		$data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
		$hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
		$computedSignature = base64_encode($hash_hmac);
		
		$json_return = json_encode($form_data);
		
		
		
		$data_set = array("merchant_id"=>CASH_FREE_APP_ID,
			"bank_name"=>$paymentMode,
			"bank_ref_no"=>$referenceId,
			"trns_id"=>$referenceId,
			"payment_mode"=>$paymentMode,
			"trns_currency"=>'INR',
			"gate_way"=>$paymentMode,
			"order_amount"=>$orderAmount,
			"payment_sts"=>$txStatus,
			
			"response_code"=>$referenceId,
			"response_msg"=>$txMsg,
			"check_sum"=>$signature,
			"json_return"=>$json_return,
			"update_date"=>$current_date,
			"trns_date"=>$txTime
		);
		
		$this->SqlModel->updateRecord("tbl_online_payment",$data_set,array("reference_no"=>$orderId));
		
		$AR_ORDER = $model->getPaymentDetail($orderId);
		$order_no = $AR_ORDER['reference_no'];
		$type_id = $AR_ORDER['type_id'];
		$address_id = $AR_ORDER['address_id'];
		
		$member_id = $AR_ORDER['member_id'];
		$AR_MEM = $model->getMember($member_id);
		
		if ($signature == $computedSignature) {			
			if($txStatus=="SUCCESS"){
				$deposit_amount  = $AR_ORDER['order_amount'];
				$deposit_fee = 0;
				$trns_amount = $deposit_amount - $deposit_fee;
				$wallet_id  = $AR_ORDER['wallet_id'];
				
				$trns_remark  = "DEPOSIT BY[".$AR_MEM['user_id']."]";
				
				$fund_data = array("wallet_id"=>$wallet_id,
					"trans_no"=>$order_no,
					"from_member_id"=>0,
					"to_member_id"=>$member_id,
					"initial_amount"=>$deposit_amount,
					"deposit_fee"=>$deposit_fee,
					"trns_amount"=>$trns_amount,
					"trns_remark"=>$trns_remark,
					"trns_type"=>"Cr",
					"trns_for"=>'DPT',
					"trns_status"=>"C",
					"status_up_date"=>$current_date,
					"draw_type"=>'ONLINE',
					"trns_date"=>InsertDate($today_date)
				);
				
				$this->SqlModel->insertRecord("tbl_fund_transfer",$fund_data);
				$model->wallet_transaction($wallet_id,$member_id,"Cr",$trns_amount,$trns_remark,$today_date,$order_no,array("trans_ref_no"=>$order_no,"trns_for"=>"DEPOSIT"));
				$model->sendWalletSMS($AR_MEM['mobile_number'],$AR_MEM['full_name'],"Cr",$trns_amount);
				set_message("success","You have successfully credited your wallet");
				redirect_member("financial","deposit","");
			}else{
				$this->session->unset_userdata('reference_no');
				set_message("warning","Payment falied , please try again");
				redirect_member("financial","deposit","");
			}
		}else{
			$this->session->unset_userdata('reference_no');
			set_message("warning","Payment falied , please try again");
			redirect_member("financial","deposit","");
		}
	}
	
	public function cashfreefailed(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$cart_session = $this->session->userdata('session_id');
		$secretkey = CASH_FREE_SECRET_KEY;
		
		$orderId = FCrtRplc($form_data['orderId']);
		$orderAmount = FCrtRplc($form_data['orderAmount']);
		$referenceId = FCrtRplc($form_data['referenceId']);
		$txStatus = FCrtRplc($form_data['txStatus']);
		$paymentMode = FCrtRplc($form_data['paymentMode']);
		$txMsg = FCrtRplc($form_data['txMsg']);
		$txTime = FCrtRplc($form_data['txTime']);
		$signature = FCrtRplc($form_data['signature']);
		
		$data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
		$hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
		$computedSignature = base64_encode($hash_hmac);
		
		$json_return = json_encode($form_data);
		
		$data_set = array("merchant_id"=>CASH_FREE_APP_ID,
			"bank_name"=>$paymentMode,
			"bank_ref_no"=>$referenceId,
			"trns_id"=>$referenceId,
			"payment_mode"=>$paymentMode,
			"trns_currency"=>'INR',
			"gate_way"=>$paymentMode,
			"order_amount"=>$orderAmount,
			"payment_sts"=>$txStatus,
			
			"response_code"=>$referenceId,
			"response_msg"=>$txMsg,
			"check_sum"=>$signature,
			"json_return"=>$json_return,
			"update_date"=>$current_date,
			"trns_date"=>$txTime
		);
		
		$this->SqlModel->updateRecord("tbl_online_payment",$data_set,array("reference_no"=>$orderId));
		$this->session->unset_userdata('reference_no');
		set_message("warning","Transaction failed, ".$txMsg);
		redirect_front("account","paymentstatus",array("reference_no"=>$orderId));
	}
	
	
}
?>