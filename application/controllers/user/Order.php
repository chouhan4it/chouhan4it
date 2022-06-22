<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	    if(!$this->isMemberLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}
	
	
	public function shoppingtype(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		if($form_data['submitShoppingType']==1 && $this->input->post()!=''){
			$order_method = FCrtRplc($form_data['order_method']);
			$order_to = FCrtRplc($form_data['order_to']);
			$billing_type = FCrtRplc($form_data['billing_type']);
			
			$this->session->set_userdata('order_method',$order_method);
			$this->session->set_userdata('order_to',$order_to);
			$this->session->set_userdata('billing_type',$billing_type);
			switch($order_method){
				case "GRAPH":
					redirect_member("order","shopgraph","");
				break;
				case "TAB":	
					redirect_member("order","shoptab","");
				break;
			}
		}
		$this->load->view(MEMBER_FOLDER.'/order/shoppingtype',$data);
	}
	
	public function shopgraph(){
		$this->session->set_userdata('order_method',"GRAPH");
		$this->session->set_userdata('order_to',"self_order");
		$this->load->view(MEMBER_FOLDER.'/order/shopgraph',$data);
	}

	
	public function shoptab(){
		$this->session->set_userdata('order_method',"TAB");
		$this->session->set_userdata('order_to',"self_order");
		$this->load->view(MEMBER_FOLDER.'/order/shoptab',$data);
	}
	
	public function cartsave(){
		$member_id = $this->session->userdata('mem_id');
		$cart_session = $this->session->userdata('session_id');
		$model = new OperationModel();
		if($member_id>0){
			$this->SqlModel->updateRecord("tbl_cart",array("save_by"=>$member_id),array("cart_session"=>$cart_session));
			set_message("success","Your cart save successfully");
			$page_redirect = $model->getShopType();
			redirect($page_redirect);
	
		}
	}
	
	public function savecart(){
		$member_id = $this->session->userdata('mem_id');
		$cart_session = $this->session->userdata('session_id');
		$model = new OperationModel();
		if($member_id>0){
			$this->SqlModel->updateRecord("tbl_cart",array("cart_session"=>$cart_session),array("save_by"=>$member_id));
			redirect_member("order","cart","");
		}
	}
	
	public function orderlist(){
		$this->load->view(MEMBER_FOLDER.'/order/orderlist',$data);
	}

	public function cancel(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$today_date = getLocalTime();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$order_id = _d($segment['order_id']);
		$id_order_state = $form_data['id_order_state'];
		
		if($form_data['submit-cancel']==1 && $this->input->post()!=''){
			$cancel_reason = $form_data['cancel_reason'];
			if($order_id>0){
				$AR_ORDER = $model->getOrderMaster($order_id);
				$order_no = $AR_ORDER['order_no'];
				$member_id = $AR_ORDER['member_id'];
				$franchisee_id = $AR_ORDER['franchisee_id'];
				$total_paid = $AR_ORDER['total_paid'];
				
				$AR_MEM = $model->getMember($member_id,"");
				$mobile_number = $AR_MEM['mobile_number'];
				
				$data_set = array("order_id"=>$order_id,	
					"franchisee_id"=>$franchisee_id,
					"order_no"=>$order_no,
					"cancel_reason"=>getTool($cancel_reason,'')
				);
				$this->SqlModel->insertRecord("tbl_order_cancel",$data_set);
				$data_state = array("order_id"=>$order_id,
					"id_order_state"=>$id_order_state,
					"date_add"=>$today_date
				);
				$this->SqlModel->insertRecord("tbl_order_history",$data_state);
				$this->SqlModel->updateRecord("tbl_orders",array("id_order_state"=>$id_order_state),array("order_id"=>$order_id));
				$model->sendOrderCancel($mobile_number,$order_no,$total_paid);
				$cancel_msg = 'Order No '.$order_no.' has cancel successfully, you will get notfication shortly';
				set_message("success","".$cancel_msg);
				redirect_member("order","orderlist","");			
			}
		}
		$this->load->view(MEMBER_FOLDER.'/order/cancel',$data);
	}
	
	public function trackorder(){
		$this->load->view(MEMBER_FOLDER.'/order/trackorder',$data);
	}
	
		
	public function orderview(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = ($form_data['order_id'])? _d($form_data['order_id']):_d($segment['order_id']);
		
		$this->load->view(MEMBER_FOLDER.'/order/orderview',$data);
	}
	
	public function product(){
		$this->load->view(MEMBER_FOLDER.'/order/product',$data);
	}
	
	public function incproduct(){
		$this->load->view(MEMBER_FOLDER.'/order/incproduct',$data);
	}
	
	public function cart(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$post_id = ($form_data['post_id'])? _d($form_data['post_id']):_d($segment['post_id']);
		$AR_DT = $model->getPostDetail($post_id);
		$cart_session = $this->session->userdata('session_id');
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$cart_id = ($form_data['cart_id'])? _d($form_data['cart_id']):_d($segment['cart_id']);
		switch($action_request){
			case "DELETE":
				if($cart_id>0){
					$this->SqlModel->deleteRecord("tbl_cart",array("cart_id"=>$cart_id));
					redirect_member("order","cart","");
				}
			break;
			default:
				if($post_id>0){
					$QR_COUNT = "SELECT COUNT(tc.cart_id) AS row_ctrl
								FROM tbl_cart AS tc 
								WHERE tc.post_id='".$post_id."' 
								AND tc.cart_session='".$cart_session."'";
					$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
					$cart_ctrl = $AR_COUNT['row_ctrl'];
					if($cart_ctrl==0){
						$cart_title = $AR_DT['post_title'];
						$cart_desc = $AR_DT['post_desc'];
						$cart_price = $AR_DT['post_price'];
						$cart_pv = $AR_DT['post_pv'];
						$cart_bv = $model->getValue("CONFIG_PV");
						$cart_total = $cart_price*1;
						
						$data = array("post_id"=>$post_id,
							"cart_title"=>$cart_title,
							"cart_desc"=>$cart_desc,
							"cart_price"=>$cart_price,
							"cart_pv"=>$cart_pv,
							"cart_bv"=>$cart_bv,
							"cart_total"=>$cart_total,
							"cart_session"=>$cart_session,
							"date_up"=>$today_date
						);
						$cart_id = $this->SqlModel->insertRecord("tbl_cart",$data);
						redirect_member("order","cart","");
					}
				}				
			break;
		}
		$this->load->view(MEMBER_FOLDER.'/order/cart',$data);
	}
	
	public function shipping(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$cart_price = $model->getCartTotal();
		
		if($cart_price==0 || $cart_price=''){
			redirect_member("order","productlist","");
		}
		
		
		if($form_data['submit-shipping']==1 && $this->input->post()!=''){
			
			$first_name = FCrtRplc($form_data['first_name']);
			$last_name = FCrtRplc($form_data['last_name']);
			$full_name = $first_name." ".$last_name;
			$current_address = FCrtRplc($form_data['current_address']);
			$city_name = FCrtRplc($form_data['city_name']);
			$state_name = FCrtRplc($form_data['state_name']);
			$country_code = FCrtRplc($form_data['country_code']);
			$pin_code = FCrtRplc($form_data['pin_code']);
			$mobile_number = FCrtRplc($form_data['mobile_number']);
			$adress_type = FCrtRplc($form_data['adress_type']);
			
			$email_address = FCrtRplc($form_data['email_address']);
			
			$data_set = array("member_id"=>$member_id,	
				"first_name"=>$first_name,
				"last_name"=>getTool($last_name,''),
				"full_name"=>getTool($full_name,''),
				
				"country_code"=>getTool($country_code,'IND'),
				"city_name"=>getTool($city_name,''),
				"state_name"=>getTool($state_name,''),
				"pin_code"=>getTool($pin_code,''),
				"current_address"=>getTool($current_address,''),
				
				"email_address"=>getTool($email_address,''),
				"mobile_number"=>getTool($mobile_number,''),
				"adress_type"=>$adress_type
			);
			$this->SqlModel->insertRecord("tbl_address",$data_set);
			
			redirect_member("order","shipping","");
						
		}
		
		if($form_data['submit-address']!='' && $this->input->post()!=''){
			$address_id = FCrtRplc($form_data['address_id']);
			if($address_id>0){
				$this->session->set_userdata('address_id',$address_id);
				redirect_member("order","payment","");
			}else{
				set_message("warning","Please  select delivery address");
				redirect_member("order","shipping","");
			}
		}
		
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$address_id = (_d($form_data['address_id'])>0)? _d($form_data['address_id']):_d($segment['address_id']);
		switch($action_request):
			case "DELETE":
				if($address_id>0){
					$data_up = array("delete_sts"=>0);
					if($model->checkCount("tbl_orders","address_id",$address_id)==0){
						$this->SqlModel->deleteRecord("tbl_address",array("address_id"=>$address_id));	
					}else{
						$this->SqlModel->updateRecord("tbl_address",$data_up,array("address_id"=>$address_id));
					}
					redirect_member("order","shipping","");
				}
			break;
		endswitch;
		
		$this->load->view(MEMBER_FOLDER.'/order/shipping',$data);
	}
	
	
	public function payment(){
		$today_date = getLocalTime();
		$order_date = InsertDate($today_date);
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$member_id = $this->session->userdata('mem_id');
		$cart_session = $this->session->userdata('session_id');
		$address_id = $this->session->userdata('address_id');
		
		$wallet_id = $model->getWallet(WALLET1);
		
		$reference_no = UniqueId("REFER_NO");
		
		$AR_MEM = $model->getMember($member_id);
		$AR_LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
				
		$franchisee_id = $model->getDefaultFranchisee();
		$cart_price = $total_paid = $model->getCartTotal();
		$total_paid_real = $model->getCartTotalMrp();
		$cart_pv =    $model->getCartTotalPv();
		$cart_bv =    $model->getCartTotalBv();
		$cart_count = $model->getCartCount();
		
		$shipping_charge = $model->getShippingCharge();
		$payment_type = FCrtRplc($form_data['payment_type']);
		
		
		if($cart_price==0 || $cart_price==''){
			redirect_member("order","shoptab","");
		}
		
		if($address_id==0 || $address_id==''){
			redirect_member("order","shipping","");
		}
		
		if($form_data['submit-payment']!='' && $this->input->post()!=''){
			
			$order_message = FCrtRplc($form_data['order_message']);
			
				if($member_id>0){
						switch($payment_type):
							case "ONLINE":
								set_message("warning","current online payment is not available");
								redirect_member("order","payment","");
								#redirect_front("product","paymentgateway",""); exit;
							break;
							case "WALLET":
								if( $AR_LDGR['net_balance']>=$total_paid  && $total_paid > 0 ){
									$payment = "EWP";
									$process_order = "active";
								}else{
									$process_order = "block";
									set_message("warning","It seem that you have less balance to make an order");
									redirect_member("order","payment","");
								}
							break;
							case "COD":
								$payment = "COD";
								$process_order = "active";
							break;
						endswitch;
						
						if( $process_order == "active" ){
								
								$QR_CART = "SELECT tc.*, tp.franchisee_id, tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, 
											tpl.post_mrp, tpl.post_hsn, tpl.tax_age, tpl.post_discount, tpl.short_desc, tpl.post_tax, tpl.post_price, 
											tpl.post_pv, tpl.post_bv,  tpl.update_date 
											FROM  tbl_cart AS tc
											LEFT JOIN  tbl_post AS tp ON tp.post_id=tc.post_id 
											LEFT JOIN  tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
											LEFT JOIN  tbl_post_category AS tpc ON tpc.post_id=tp.post_id
											LEFT JOIN  tbl_tags AS tt ON  FIND_IN_SET(tag_id,tpl.post_tags)
											LEFT JOIN  tbl_post_view AS tpv ON tpv.post_id=tp.post_id
											LEFT JOIN  tbl_post_review AS tpr ON tpr.post_id=tp.post_id
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
										$cart_attribute_id = FCrtRplc($AR_DT['cart_attribute_id']);	
										$post_attribute = $model->getAttributeOfCombination($cart_attribute_id);		
										
										$post_hsn = FCrtRplc($AR_DT['post_hsn']);
										$tax_age = FCrtRplc($AR_DT['tax_age']);
										
										$post_pv = FCrtRplc($AR_DT['cart_pv']);
										$post_bv = FCrtRplc($AR_DT['cart_bv']);
										$post_qty = FCrtRplc($AR_DT['cart_qty']);
										$post_selling = FCrtRplc($AR_DT['cart_selling']);
										$post_cmsn = FCrtRplc($AR_DT['cart_cmsn']);
										$post_price = FCrtRplc($AR_DT['cart_price']);
										$post_amount = ($post_price*$post_qty);
										
										$post_width = FCrtRplc($AR_DT['cart_width']);	
										$post_height = FCrtRplc($AR_DT['cart_height']);	
										$post_depth = FCrtRplc($AR_DT['cart_depth']);	
										$post_weight = FCrtRplc($AR_DT['cart_weight']);
										
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
											"id_order_state"=>8,
											"payment"=>$payment,
											"order_message"=>getTool($order_message,''),
											
											"total_cart"=>$total_cart,
											"shipping_charge"=>getTool($shipping_charge,0),
											
											
											"total_paid"=>$total_paid,
											"total_paid_real"=>$total_paid_real,
											
											"total_products"=>$post_qty,
											"total_pv"=>getTool($post_pv,0),
											"total_bv"=>getTool($post_bv,0),
											
											"reference_no"=>$reference_no,
											"date_add"=>$today_date,
											"date_upd"=>$today_date
										);
										$order_id = $this->SqlModel->insertRecord("tbl_orders",$order_data);
										if($order_id>0){
											$data = array("order_id"=>$order_id,
												"franchisee_id"=>getTool($franchisee_id,0),
												"post_id"=>$post_id,
												"post_image_id"=>getTool($cart_image_id,0),
												"post_attribute_id"=>getTool($cart_attribute_id,0),
												"post_attribute"=>getTool($post_attribute,''),
												"single_pv"=>getTool($post_pv,0),
												"post_pv"=>getTool($post_pv,0),
												"post_bv"=>getTool($post_bv,0),
												"post_title"=>$AR_DT['cart_title'],
												"post_code"=>getTool($AR_DT['cart_code'],''),
												"post_desc"=>getTool($AR_DT['cart_desc'],''),
												
												"post_width"=>getTool($post_width,0),
												"post_height"=>getTool($post_height,0),
												"post_depth"=>getTool($post_depth,0),
												"post_weight"=>getTool($post_weight,0),
												
												"post_tax"=>$AR_DT['cart_tax'],
												"tax_age"=>getTool($tax_age,0),
												"post_hsn"=>getTool($post_hsn,0),
												
												"original_post_price"=>$AR_DT['cart_mrp'],
												"post_selling"=>getTool($post_selling,0),
												"post_cmsn"=>getTool($post_cmsn,0),
												"post_price"=>$post_price,
												
												"post_qty"=>$post_qty,
												"net_amount"=>$post_amount,
												"date_time"=>$today_date
											);
											$this->SqlModel->insertRecord("tbl_order_detail",$data);
											$this->SqlModel->deleteRecord("tbl_cart",array("cart_id"=>$AR_DT['cart_id']));
											
											$trns_remark = "ORDER PURCHASE [".$order_no."]";
											if($payment_type=="WALLET"){
												$model->wallet_transaction($wallet_id,$member_id,"Dr",$total_paid,$trns_remark,$today_date,"",
												array("trns_for"=>"ORDER","trans_ref_no"=>$order_no));
											}
											$model->sendOrderConfirmation($AR_MEM['mobile_number'],$order_no,$total_paid);
										}
									endforeach;																		
									set_message("success","Thank you for placing an order, you will get confirmation shortly");
									redirect_member("order","orderview",array("order_id"=>_e($order_id)));
									
						}
											
			  	}
		}
		
		$AR_META['page_title']="Payment";
		$AR_META['page_content']="";
		$AR_META['page_breadcrumb']="Payment";
		$data['META'] = $AR_META;
		$this->load->view(MEMBER_FOLDER.'/order/payment',$data);
	}

	
	public function orderdelivered(){
		$this->load->view(MEMBER_FOLDER.'/order/orderdelivered',$data);
	}
	
	
	public function orderreturn(){
		$this->load->view(MEMBER_FOLDER.'/order/orderreturn',$data);
	}
	
	public function productlist(){
		$this->load->view(MEMBER_FOLDER.'/order/productlist',$data);
	}
	
	
	public function trackcourier(){
		if(isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}
		
		$access_token = ITHINK_ACCESS;
		$secret_key = ITHINK_KEY;
		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = getLocalTime();
		$waybill = ($segment['waybill']);
		
		
		$AR_CRCR = $model->getCourierDetail($waybill);
		$courier_api_id = $AR_CRCR['courier_api_id'];
		$order_id = $AR_CRCR['order_id'];
		
		$data = array("data"=>array("awb_number_list"=>$waybill,
			"access_token"=>$access_token,
			"secret_key"=>$secret_key));
		
		$request_param =  json_encode($data,true);
				
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL             => "https://manage.ithinklogistics.com/api_v2/order/track.json",
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_ENCODING        => "",
			CURLOPT_MAXREDIRS       => 10,
			CURLOPT_TIMEOUT         => 30,
			CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST   => "POST",
			CURLOPT_POSTFIELDS      => $request_param,
			CURLOPT_HTTPHEADER      => array(
			"cache-control: no-cache",
			"content-type: application/json"
		  ),
		));
		
		$response = curl_exec($curl);
		$err      = curl_error($curl);
		curl_close($curl);
		$response_array = json_decode($response,true);
		$track_status = json_encode($response_array['data'][$waybill],true);	
		
		if ($err){
			set_message("warning","Error #:" . $err);
			redirect_member("order","trackorder","");
		}else{
			$status_code = $response_array['status_code'];
			
			$this->SqlModel->updateRecord("tbl_api_courier",array("track_status"=>$track_status),array("courier_api_id"=>$courier_api_id));
			redirect_member("order","trackdetail",array("waybill"=>($waybill)));
			
		}
		
	}
	
	public function trackdetail(){
		$this->load->view(MEMBER_FOLDER.'/order/trackdetail',$data);	
	}
	
	
			
	
}
