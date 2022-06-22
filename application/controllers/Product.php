<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {
	
	public function __construct(){
	   parent::__construct();
	   $this->load->library('parser');
	   $this->load->view('captcha/securimage');
	   $this->load->view('mailer/phpmailer');
	   $this->OperationModel->addVisitor();
	}
	
	
	public function productajax(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		
		if($form_data['search']!=''){
			$q = FCrtRplc($form_data['search']);
			$StrWhr .=" AND ( tpl.post_title LIKE '%".$q."%' OR tpl.short_desc LIKE '%".$q."%' OR tpl.post_desc LIKE '%".$q."%' )";
			$SrchQ .="&q=".$q."";
		}		


		$QR_PROD = "SELECT tp.*,  tpl.lang_id, tpl.post_size, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_pv,  tpl.update_date , tpl.post_slug, GROUP_CONCAT(tc.category_name) AS category_name
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_category AS tc ON tc.category_id=tpc.category_id 
			WHERE tp.delete_sts>0 AND tp.post_sts>0   $StrWhr 
			GROUP BY tp.post_id  
			ORDER BY tpl.post_title ASC LIMIT 5";
		$RS_PROD = $this->SqlModel->runQuery($QR_PROD);
		foreach($RS_PROD as $AR_PROD):
			$IMG_SRC_FULL = $model->getDefaultPhoto($AR_PROD['post_id']); 
			$detail_page =  generateSeoUrl("product","detail",array("post_id"=>$AR_PROD['post_id']))."/".$AR_PROD['post_slug'];
			$html .='<div class="show-search" align="left"   >
						<a href="'.$detail_page.'""><img src="'.$IMG_SRC_FULL.'" style="width:50px; height:50px; float:left; margin-right:6px;" /></a><a href="'.$detail_page.'" class="name">'.$AR_PROD['post_title'].'&nbsp;</a>
					</div>';
		endforeach;
        echo $html;
	}
	
	public function catalog(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$category_id = getTool($segment['category_id'],$_REQUEST['category_id']);
		$AR_CAT = $model->getCategoryDetail($category_id);
		if($form_data['submitRegister']==1 && $this->input->post()!=''){
			$fldvFName = FCrtRplc($form_data['fldvFName']);
			$fldvLName = FCrtRplc($form_data['fldvLName']);
			$fldvEmail = FCrtRplc($form_data['fldvEmail']);
			$fldvMobile = FCrtRplc($form_data['fldvMobile']);
			$fldvAddress = FCrtRplc($form_data['fldvAddress']);
			$fldvCity = FCrtRplc($form_data['fldvCity']);
			$fldvState = FCrtRplc($form_data['fldvState']);
			$fldvZip = FCrtRplc($form_data['fldvZip']);
			$fldvVstrIP = $_SERVER['REMOTE_ADDR'];
			
			$data = array("fldvFName"=>$fldvFName,
				"fldvLName"=>$fldvLName,
				"fldvEmail"=>$fldvEmail,
				"fldvMobile"=>$fldvMobile,
				"fldvAddress"=>($fldvAddress)? $fldvAddress:"NA",
				"fldvCity"=>$fldvCity,
				"fldvState"=>($fldvState)? $fldvState:"NA",
				"fldvZip"=>$fldvZip,
				"fldvVstrIP"=>$fldvVstrIP
			);
			$fldiRegId = $this->SqlModel->insertRecord("tbl_tmp_register",$data);
			if($fldiRegId>0){
				javascript_alert("Thank you for registration, please stay with us we will launch it soon...");
				javascript_redirect(BASE_PATH);
			}else{
				javascript_alert("Falied , please enter all valid fields");
				javascript_redirect(BASE_PATH);
			}
		}
		
		$AR_DATA['page_title']= ucfirst(strtolower($AR_CAT['category_name']));
		$data['ROW'] = $AR_DATA;
		$this->load->view('catalog',$data);
	}
	
	public function detail(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$img = new Securimage();
		$post_id = getTool($segment['post_id'],0);
		$AR_DT = $model->getPostDetail($post_id);
		
		$redirect_path = generateSeoUrl("product","detail",array("post_id"=>$post_id))."/".$AR_DT['post_slug'];
		
		if($form_data['submit-review']==1 && $this->input->post()!=''){
			$valid = $img->check($_POST["captcha_code"]);
			$valid = true;
  			if($valid == true) {
				$review_by = FCrtRplc($form_data['review_by']);
				$email_id = FCrtRplc($form_data['email_id']);
				$review_dtls = FCrtRplc($form_data['review_dtls']);
				$post_id = _d($form_data['review_post_id']);
				$server_ip = $_SERVER['REMOTE_ADDR'];
				if($post_id>0 && $review_by!=''){
					$data = array("post_id"=>$post_id,
						"review_by"=>$review_by,
						"email_id"=>$email_id,
						"review_dtls"=>$review_dtls,
						"server_ip"=>$server_ip
					);
					$this->SqlModel->insertRecord("tbl_post_review",$data);
					set_message("success","Your review posted successfully");
					redirect($redirect_path);
				}
			}else{
				set_message("warning","Invalid security code, please try again");
				redirect($redirect_path);
			}
		}
		$AR_DATA['page_title']= $AR_DT['post_title'];
		$data['ROW'] = $AR_DATA;
		$this->load->view('detail',$data);
	}
	
	public function cart(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$post_id = ($form_data['post_id'])? _d($form_data['post_id']):_d($segment['post_id']);
		$AR_DT = $model->getPostDetail($post_id);
		$cart_session = $this->session->userdata('session_id');
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$cart_id = (_d($form_data['cart_id'])>0)? _d($form_data['cart_id']):_d($segment['cart_id']);
		switch($action_request){
			case "DELETE":
				if($cart_id>0){
					$this->SqlModel->deleteRecord(prefix."tbl_cart",array("cart_id"=>$cart_id));
					redirect_front("product","cart","");
				}
			break;
		}
		$AR_META['page_title']="Your shopping cart";
		$AR_META['page_content']="";
		$AR_META['page_breadcrumb']="Shopping Cart";
		$data['ROW'] = $AR_META;
		$this->load->view('cart',$data);
	}
	
	
	public function shipping(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$member_id = $this->session->userdata('mem_id');
		$cart_price = $model->getCartTotal();
		$model->checkMemberLogin($member_id);
				
		if($cart_price==0 || $cart_price=''){
			redirect_front("product","cart","");
		}
		
		
		if($form_data['submit-shipping']!='' && $this->input->post()!=''){
			
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
			redirect_front("product","shipping","");
						
		}
		
		if($form_data['submit-address']!='' && $this->input->post()!=''){
			$address_id = FCrtRplc($form_data['address_id']);
			if($address_id>0){
				$this->session->set_userdata('address_id',$address_id);
				redirect_front("product","payment","");
			}else{
				set_message("warning","Please  select delivery address");
				redirect_front("product","shipping","");
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
					redirect_front("product","shipping","");
				}
			break;
		endswitch;
		
		$AR_META['page_title']="Shipping Address";
		$AR_META['page_content']="";
		$data['ROW'] = $AR_META;
		$this->load->view('shipping',$data);
	}
	
	
	public function payment(){
		$today_date = getLocalTime();
		$order_date = InsertDate($today_date);
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$member_id = $this->session->userdata('mem_id');
		$cart_session = $this->session->userdata('session_id');
		#$store_id = $this->session->userdata('store_id');
		$address_id = $this->session->userdata('address_id');
		
		$wallet_id = $model->getWallet(WALLET1);
		
		$coupon_mstr_id = FCrtRplc($form_data['coupon_mstr_id']);
		#$AR_CPN = $model->getCouponMaster($coupon_mstr_id);
		$coupon_discount = $AR_CPN['coupon_val'];
		
		$AR_MEM = $model->getMember($member_id);
		$AR_LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
				
		$franchisee_id = $model->getDefaultFranchisee();
		$cart_price = $total_cart  = $model->getCartTotal();
		#$total_paid_real = $model->getCartTotalMrp();
		$shipping_charge = $model->getShippingCharge();
		$order_amount = ($total_cart + $shipping_charge)-$coupon_discount;
		$cart_pv =    $model->getCartTotalPv();
		$cart_bv =    $model->getCartTotalBv();
		$cart_count = $model->getCartCount();
		
		$payment_type = FCrtRplc($form_data['payment_type']);
		
		
		if($cart_price==0 || $cart_price==''){
			redirect_front("product","catalog","");
		}
		
		if($address_id==0 || $address_id==''){
			redirect_front("product","shipping","");
		}
		
		if($form_data['submit-payment']!='' && $this->input->post()!=''){
			
			$order_message = FCrtRplc($form_data['order_message']);
				$reference_no = UniqueId("REFER_NO");
				if($reference_no!='' && $member_id>0){
						switch($payment_type):
							case "ONLINE":
								redirect_front("product","paymentgateway","");
							break;
							case "EWALLET":
								$id_order_state = "8";
								if( $AR_LDGR['net_balance']>=$total_paid  && $total_paid > 0 ){
									$payment = "EWP";
									$process_order = "active";
								}else{
									$process_order = "block";
									set_message("warning","It seem that you have less balance to make an order");
									redirect_front("product","payment","");
								}
							break;
							case "COD":
								$id_order_state = "7";
								$payment = "COD";
								$process_order = "active";
							break;
						endswitch;
						
						if( $process_order == "active" ){
								
									$QR_CART = "SELECT tc.*, tp.franchisee_id, tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, 
									tpl.post_mrp, tpl.post_selling_mrp, tpl.post_hsn, tpl.tax_age, tpl.post_discount, tpl.short_desc, tpl.post_tax, 
									tpl.post_price, tpl.post_pv, tpl.post_bv, tpl.update_date 
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
										
											$franchisee_id = FCrtRplc($AR_DT['franchisee_id']);
												
											$cart_mrp = FCrtRplc($AR_DT['cart_mrp']);	
											$post_shipping = FCrtRplc($AR_DT['cart_shipping']);			
											$post_image_id = FCrtRplc($AR_DT['cart_image_id']);	
											$post_attribute_id = FCrtRplc($AR_DT['cart_attribute_id']);	
											$post_attribute = FCrtRplc($AR_DT['cart_attribute']);	
														
											$post_id = FCrtRplc($AR_DT['post_id']);
											$post_hsn = FCrtRplc($AR_DT['post_hsn']);
											$post_tax = FCrtRplc($AR_DT['cart_tax']);
											$tax_age = FCrtRplc($AR_DT['tax_age']);
											
											$post_width = FCrtRplc($AR_DT['cart_width']);
											$post_height = FCrtRplc($AR_DT['cart_height']);
											$post_depth = FCrtRplc($AR_DT['cart_depth']);
											$post_weight = FCrtRplc($AR_DT['cart_weight']);
											
											$post_pv = FCrtRplc($AR_DT['cart_pv']);
											$post_bv = FCrtRplc($AR_DT['cart_bv']);
											$post_qty = FCrtRplc($AR_DT['cart_qty']);
																					
											
											$post_selling_mrp = FCrtRplc($AR_DT['post_selling_mrp']);
											$post_selling = FCrtRplc($AR_DT['cart_selling']);
											$post_cmsn = FCrtRplc($AR_DT['cart_cmsn']);
											$post_price = FCrtRplc($AR_DT['cart_price']);
											
											$shipping_charge = ( $post_shipping * $post_qty );
											$post_amount =  ( $post_price * $post_qty );
											
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
												"id_order_state"=>getTool($id_order_state,1),
												"payment"=>$payment,
												"order_message"=>$order_message,
												
												"total_cart"=>$post_qty,
												"shipping_charge"=>getTool($shipping_charge,0),
												"coupon_discount"=>getTool($coupon_discount,0),
												
												"total_paid"=>$total_cart,
												"total_paid_real"=>$total_paid_real,
												
												"total_products"=>getTool($post_qty,0),
												"total_pv"=>getTool($cart_pv,0),
												"total_bv"=>getTool($cart_bv,0),
												
												"reference_no"=>$reference_no,
												"date_add"=>$today_date,
												"date_upd"=>$today_date
											);
											$order_id = $this->SqlModel->insertRecord("tbl_orders",$order_data);
											
											if($order_id>0){
													$data = array("order_id"=>$order_id,
														"franchisee_id"=>getTool($franchisee_id,0),
														"post_id"=>$post_id,
														"post_image_id"=>getTool($post_image_id,0),
														"post_attribute_id"=>getTool($post_attribute_id,0),
														"post_attribute"=>getTool($post_attribute,''),
														"single_pv"=>getTool($post_pv,0),
														"post_pv"=>getTool($post_pv,0),
														"post_bv"=>getTool($post_bv,0),
														"post_title"=>$AR_DT['cart_title'],
														"post_code"=>getTool($AR_DT['cart_code'],''),
														"post_desc"=>getTool($AR_DT['cart_desc'],''),
														
														"post_tax"=>getTool($post_tax,0),
														"tax_age"=>getTool($tax_age,0),
														"post_hsn"=>getTool($post_hsn,0),
														
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
														
														"post_qty"=>$post_qty,
														"net_amount"=>$post_amount,
														"date_time"=>$today_date
													);
													$this->SqlModel->insertRecord("tbl_order_detail",$data);
													$this->SqlModel->deleteRecord("tbl_cart",array("cart_id"=>$AR_DT['cart_id']));
											}
											unset($order_id,$data_dt);
										endforeach;
									
							$trns_remark = "ORDER PURCHASE [".$order_no."]";
							$trans_no = UniqueId("TRNS_NO");
							$model->sendOrderConfirmation($AR_MEM['mobile_number'],$reference_no,$order_amount);
							if($payment_type=="EWALLET"){
								$model->wallet_transaction($wallet_id,"Dr",$member_id,$order_amount,$trns_remark,$today_date,$trans_no,1,"ORDER");
							}
							if($coupon_mstr_id>0){
								$model->coupon_transaction($coupon_mstr_id,$AR_CPN['coupon_code'],$AR_CPN['coupon_val'],$order_id,$AR_CPN['expire_date'],
								"Y",$member_id,$order_date,$cart_pv);
							}
							set_message("success","Thank you for placing an order, you will get confirmation shortly");
							redirect_member("order","orderlist","");
						}else{
							set_message("warning","It seem that you have less balance to make an order");
							redirect_front("product","payment","");
						}
			   }
		}
		
		$AR_META['page_title']="Payment";
		$AR_META['page_content']="";
		$AR_META['page_breadcrumb']="Payment";
		$data['ROW'] = $AR_META;
		$this->load->view('payment',$data);
	}
	
	public function paymentgateway(){
		$this->checkMemberLogin();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$cart_price = $model->getCartTotal();
		
		if($cart_price==0 || $cart_price='' && $member_id>0){
			redirect(BASE_PATH);
		}
		
		$this->load->view('paymentgateway',$data);
	}
	
	public function confirmation(){
		$this->checkMemberLogin();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$order_id = _d($segment['order_id']);
		$member_id = $this->session->userdata('mem_id');
		
		if($order_id==0 || $order_id=='' && $member_id>0){
			redirect(BASE_PATH);
		}
		
		$this->load->view('confirmation',$data);
	}

}
?>