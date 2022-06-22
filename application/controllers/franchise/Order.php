<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}
	
	 private function _placeorder($form_data) {
        $this->form_validation->set_rules("member_id", "Member", "required");
        $this->form_validation->set_rules("order_no", "OrderNo", "required");
        $this->form_validation->set_rules("order_date", "OrderDate", "required");
		$this->form_validation->set_rules("net_pv", "PV", "required");
		$this->form_validation->set_rules("net_payable", "TotalAmount", "required");
        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                "Member" => form_error('member_id'),
                "OrderNo" => form_error('order_no'),
                "OrderDate" => form_error('order_date'),
				"PV" => form_error('net_pv'),
				"TotalAmount" => form_error('net_payable')
            );
            #$this->session->set_flashdata("error", $errors);
			$output .="<ul>";
			foreach($errors as $val):
				if($val!=''){
					$output .="<li>".$val."</li>";
				}
			endforeach;
			$output .="</ul>";
			
			set_message("warning",$output);
			redirect_franchise("order","placeorder",array());
        }
    }

	public function temporder(){
		$this->load->view(FRANCHISE_FOLDER.'/order/temporder',$data);
	}
	
	public function placeorder(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$stock_id = ($form_data['stock_id'])? $form_data['stock_id']:_d($segment['stock_id']);
		$today_date = $date_upd = getLocalTime();
		$current_date = InsertDate($today_date);
		$wallet_id = $model->getWallet(WALLET1);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitOrder']==1 && $this->input->post()!=''){
					#$this->_placeorder($form_data);
					$franchisee_id = $this->session->userdata('fran_id');
					$franchisee_name = $model->getFranchisee($franchisee_id);
					
					$net_pv = getTool($form_data['net_pv'],0);
					$member_id = _d($form_data['member_id']);
					$AR_MEM = $model->getMember($member_id);
					$reference_no = UniqueId("REFER_NO");
					$payment = FCrtRplc($form_data['payment']);
					$total_paid = $total_paid_real = FCrtRplc($form_data['net_payable']);
					
					$row_ctrl = $model->checkCountPro("tbl_mem_point","member_id='$member_id' AND point_sub_type LIKE 'ORD'");
					$address_id = $model->getMemberAddressId($member_id);
					$order_message = "REF NO :".$reference_no." &nbsp;ORDER BY : ".$franchisee_name;
					$order_date = InsertDate($form_data['order_date']);			
					
					$payment_type = FCrtRplc($form_data['payment_type']);
					$cheque_no = FCrtRplc($form_data['cheque_no']);
					$cheque_date = InsertDate($form_data['cheque_date']);
					$bank_name = FCrtRplc($form_data['bank_name']);
					$bank_branch = FCrtRplc($form_data['bank_branch']);
					$trns_remark = FCrtRplc($form_data['trns_remark']);
					
					$wallet_amount = FCrtRplc($form_data['wallet_amount']);
					$order_type = FCrtRplc($form_data['order_type']);
								
					$post_id_all = array_filter($form_data['post_id']);
					$total_products = count($post_id_all);
					$filter_products = count(array_filter($form_data['post_id']));
					if($total_products==$filter_products){
						
							if($member_id>0){
								
								if(count($post_id_all)>0){									
									$model->checkQtyFranchise($form_data);
									$Ctrl=1;
									foreach($post_id_all as  $key=>$post_id):
										
										$AR_POST = $model->getPostDetail($post_id);
										$AR_DT_FILE = $model->getPostFile($post_id);
										$post_image_id = $AR_DT_FILE['field_id'];
					
										#$franchisee_id = $AR_POST['franchisee_id'];
										$post_mrp = $AR_POST['post_mrp'];
										$post_pv = $AR_POST['post_pv'];
										$post_bv = $AR_POST['post_bv'];
										$tax_age = $AR_POST['tax_age'];
										
										$post_selling = $AR_POST['post_selling'];
										$post_cmsn = $AR_POST['post_cmsn'];
										$post_shipping = $AR_POST['post_shipping'];
										
										$post_width = FCrtRplc($AR_POST['post_width']);
										$post_height = FCrtRplc($AR_POST['post_height']);
										$post_depth = FCrtRplc($AR_POST['post_depth']);
										$post_weight = FCrtRplc($AR_POST['post_weight']);
										
										$post_attribute_id = FCrtRplc($form_data['post_attribute_id'][$key]);
										$batch_no = FCrtRplc($form_data['post_batch'][$key]);
										$post_qty = FCrtRplc($form_data['post_qty'][$key]);
										$available_qty = FCrtRplc($form_data['available_qty'][$key]);
										
										$post_attribute = $model->getAttributeOfCombination($post_attribute_id);
										
										$shipping_charge = ( $post_shipping * $post_qty );
																				
										$post_price = ($form_data['post_price'][$key]);
										$post_amount = ( $post_price * $post_qty );
										
										$total_cart =  ( $post_price * $post_qty );
										$total_paid = ( $total_cart + $shipping_charge ) - $coupon_discount;
										$total_paid_real = ( ( $post_mrp * $post_qty ) + $shipping_charge ) - $coupon_discount;
										
										$post_tax = getPostGst($post_price,$tax_age);
										
										$order_no = $model->getOrderNo();
										$order_data = array("order_no"=>$order_no,
											"reference_no"=>$reference_no,
											"member_id"=>$member_id,
											"franchisee_id"=>$franchisee_id,
											"store_id"=>getTool($store_id,0),
											"address_id"=>getTool($address_id,0),
											"lang_id"=>LANG_ID,
											"id_order_state"=>9,
											"payment"=>$payment_type,
											"order_message"=>$order_message,
											
											"total_cart"=>$post_qty,
											"shipping_charge"=>getTool($shipping_charge,0),
											
											"total_paid"=>$total_cart,
											"total_paid_real"=>$total_paid_real,
											
											"total_products"=>getTool($post_qty,0),
											"total_pv"=>getTool($post_pv,0),
											"total_bv"=>getTool($post_bv,0),
											
											"order_type"=>getTool($order_type,"F"),
											
											"reference_no"=>$reference_no,
											"date_add"=>$today_date,
											"date_upd"=>$today_date
										);
										$order_id = $this->SqlModel->insertRecord("tbl_orders",$order_data);
										
										if($order_id>0){
												$data_trns = array("order_id"=>$order_id,
													"franchisee_id"=>getTool($franchisee_id,0),
													"post_id"=>$post_id,
													"post_attribute_id"=>getTool($post_attribute_id,0),
													"post_attribute"=>getTool($post_attribute,''),
													"post_image_id"=>getTool($post_image_id,0),
													
													"post_pv"=>getTool($post_pv,0),
													"post_bv"=>getTool($post_bv,0),
													"post_title"=>$AR_POST['post_title'],
													"post_code"=>getTool($AR_POST['post_code'],''),
													"post_desc"=>getTool($AR_POST['post_desc'],''),
													
													"post_tax"=>getTool($post_tax,0),
													"tax_age"=>getTool($tax_age,0),
													"post_hsn"=>getTool($post_hsn,0),
													
													"post_width"=>getTool($post_width,0),
													"post_height"=>getTool($post_height,0),
													"post_depth"=>getTool($post_depth,0),
													"post_weight"=>getTool($post_weight,0),
													
													"original_post_price"=>$AR_POST['post_mrp'],
													"post_selling_mrp"=>getTool($AR_POST['post_selling_mrp'],0),
													"post_selling"=>getTool($post_selling,0),
													"post_cmsn"=>getTool($post_cmsn,0),
													"post_price"=>$post_price,
													"post_shipping"=>getTool($post_shipping,0),
													
													"post_qty"=>$post_qty,
													"net_amount"=>$post_amount,
													"batch_no"=>getTool($batch_no,''),
													"date_time"=>$today_date
												);
												if($post_id>0){
													$trans_no = UniqueId("STOCK_TRNS_NO");
													$this->SqlModel->insertRecord("tbl_order_detail",$data_trns);
													$model->InsertStockLedger($franchisee_id,$trans_no,"Dr","SAL",$post_id,$post_attribute_id,$post_price,$tax_age,$post_qty,
													$post_amount,$order_no,$order_date,$batch_no,$AR_POST['post_mrp'],$post_pv);
													
													
												}
										}										
										unset($AR_POST,$post_id,$post_pv,$post_bv,$batch_no,$post_qty,$available_qty,$post_price,$post_amount,$data,$offer_id_set);
									$Ctrl++;
									endforeach;
									$model->sendOrderConfirmation($AR_MEM['mobile_number'],$reference_no,$total_paid);
									set_message("success","Your order placed successfully");
									redirect_franchise("order","orderlist","");
								}else{
									set_message("warning","Unable to place order, please try again");
									redirect_franchise("order","placeorder",array());
								}
							}else{
								set_message("warning","Unable to place order, please check all filed");
								redirect_franchise("order","placeorder",array());
							}
					}else{
						set_message("warning","Same product is not allowed , please enter unique product");
						redirect_franchise("order","placeorder",array());
					}
				}
			break;
		}
		
		$this->load->view(FRANCHISE_FOLDER.'/order/placeorder',$data);
	}
	
	public function returnorder(){
		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = (_d($form_data['order_id']))? _d($form_data['order_id']):_d($segment['order_id']);
		$today_date = $date_upd = getLocalTime();
		$current_date = InsertDate($today_date);
		
		$wallet_id = $model->getWallet(WALLET1);
		$CONFIG_ORDER_RETURN = $model->getValue("CONFIG_ORDER_RETURN");
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitOrderReturn']==1 && $this->input->post()!=''){
					
					
					$franchisee_id = $this->session->userdata('fran_id');
					$franchisee_name = $model->getFranchisee($franchisee_id);
					$return_type = FCrtRplc($form_data['return_type']);
					$return_id = FCrtRplc($form_data['return_id']);
					
					
					$order_detail_id_all = array_filter($form_data['order_detail_id']);
					$total_products = count($order_detail_id_all);
					
					$AR_RATE = $model->getReturnCharge($return_id);
					$AR_ORDER = $model->getOrderMaster($order_id);
					
					$order_no = $AR_ORDER['order_no'];
					$day_diff = dayDiff($current_date,$AR_ORDER['date_add']);
					if($day_diff<=$CONFIG_ORDER_RETURN){ 
						if($order_id>0 && $franchisee_id>0){
							
							$order_data = array("order_no"=>$order_no,
								"order_id"=>$order_id,
								"member_id"=>$AR_ORDER['member_id'],
								"franchisee_id"=>$AR_ORDER['franchisee_id'],
								"lang_id"=>$AR_ORDER['lang_id'],
								"id_order_state"=>$AR_ORDER['id_order_state'],
								"payment"=>$AR_ORDER['payment'],
								"invoice_number"=>getTool($AR_ORDER['invoice_number'],''),
								"invoice_date"=>$AR_ORDER['invoice_date'],
								"order_message"=>$AR_ORDER['order_message'],
								
								"total_paid"=>$AR_ORDER['total_paid'],
								"total_paid_real"=>$AR_ORDER['total_paid_real'],
								"total_products"=>$AR_ORDER['total_products'],
								
								"return_type"=>$return_type,
								"return_name"=>($AR_RATE['return_name'])? $AR_RATE['return_name']:'',
								"return_rate"=>($AR_RATE['return_rate'])? $AR_RATE['return_rate']:'',
								"date_add"=>$today_date,
								"date_upd"=>$today_date
							);
							
							$order_return_id = $this->SqlModel->insertRecord("tbl_orders_return",$order_data);
							
							
							if($order_return_id>0){
								foreach($order_detail_id_all as  $key=>$order_detail_id):
									$AR_POST = $model->getOrderDetail($order_detail_id);
									$post_id= $AR_POST['post_id'];
									$post_pv = $AR_POST['post_pv'];
									$tax_age = $AR_POST['tax_age'];
									$batch_no = $AR_POST['batch_no'];
									$original_post_price = $AR_POST['original_post_price'];
									$post_selling = $AR_POST['post_selling'];
									$post_cmsn = $AR_POST['post_cmsn'];
									$post_attribute_id = $AR_POST['post_attribute_id'];
									$post_dp_price = $post_price = $AR_POST['post_price'];
									
									$post_width = $AR_POST['post_width'];
									$post_height = $AR_POST['post_height'];
									$post_depth = $AR_POST['post_depth'];
									$post_weight = $AR_POST['post_weight'];
									

									
									$post_attribute = $model->getAttributeOfCombination($post_attribute_id);
									$post_tax = getPostGst($post_price,$tax_age);
									$post_qty = FCrtRplc($form_data['post_qty'][$key]);
									if($post_qty>0 && $post_id>0){
										
										$post_amount = ($post_dp_price*$post_qty);
										$trans_no = UniqueId("STOCK_TRNS_NO");
										$data = array("order_return_id"=>$order_return_id,
											"post_id"=>$post_id,
											"post_attribute_id"=>getTool($post_attribute_id,0),
											"post_attribute"=>getTool($post_attribute,''),
											
											"post_title"=>$AR_POST['post_title'],
											"post_desc"=>$AR_POST['post_desc'],
											
											"original_post_price"=>getTool($original_post_price,0),
											"post_pv"=>getTool($post_pv,0),
											"post_selling"=>getTool($post_selling,0),
											"post_cmsn"=>getTool($post_cmsn,0),
											"post_width"=>getTool($post_width,0),
											"post_height"=>getTool($post_height,0),
											"post_depth"=>getTool($post_depth,0),
											"post_weight"=>getTool($post_weight,0),
											
											"batch_no"=>getTool($batch_no,''),
											"tax_age"=>getTool($tax_age,0),
											"post_tax"=>getTool($post_tax,0),
											"post_qty"=>$post_qty,
											"post_price"=>$post_price,
											"net_amount"=>$post_amount
										);
										
										$this->SqlModel->insertRecord("tbl_order_detail_return",$data);
										
									
										$model->InsertStockLedger($franchisee_id,$trans_no,"Cr","REF",$post_id,$post_attribute_id,$AR_POST['post_price'],$tax_age,$post_qty,
										$post_amount,$order_no,$today_date,$batch_no,$AR_POST['post_mrp'],$post_pv);
										
		
										$new_post_qty = $AR_POST['post_qty']-$post_qty;
										$new_net_amount = $AR_POST['net_amount']-$post_amount;
										$data_detail = array("post_qty"=>$new_post_qty,
											"net_amount"=>$new_net_amount
										);
										$this->SqlModel->updateRecord("tbl_order_detail",$data_detail,array("order_detail_id"=>$order_detail_id));	
									}
								endforeach;
								$AR_TOTAL = $model->getTotalOrderCalc($order_id);							
								$data_order = array("total_pv"=>$AR_TOTAL['total_pv'],
											"total_bv"=>$AR_TOTAL['total_bv'],
											"total_paid"=>$AR_TOTAL['total_paid'],
											"total_products"=>$AR_TOTAl['total_products'],
											"total_paid_real"=>$AR_TOTAL['total_paid_real']
								);
								$this->SqlModel->updateRecord("tbl_orders",$data_order,array("order_id"=>$order_id));
								
								$AR_RETURN_TOTAL = $model->getTotalOrderReturnCalc($order_return_id);
								$total_charge = ($AR_RETURN_TOTAL['total_paid']*$AR_RATE['return_rate'])/100;
								$return_amount = ($AR_RETURN_TOTAL['total_paid']-$total_charge);
									
								$data_return = array("total_pv"=>$AR_RETURN_TOTAL['total_pv'],
											"total_bv"=>$AR_RETURN_TOTAL['total_bv'],
											"total_products"=>$AR_RETURN_TOTAL['total_products'],
											"total_paid"=>$AR_RETURN_TOTAL['total_paid'],
											"total_charge"=>($total_charge>0)? $total_charge:0,
											"total_paid_real"=>$return_amount
								);
								$this->SqlModel->updateRecord("tbl_orders_return",$data_return,array("order_return_id"=>$order_return_id));
								
								if($AR_ORDER['payment']=="EWP"){
									$trans_no = UniqueId("TRNS_NO");
									$return_remark = "ORDER RETURN[".$AR_ORDER['order_no']."]";
									$model->wallet_transaction($wallet_id,$AR_ORDER['member_id'],"Cr",$return_amount,$return_remark,$today_date,$trans_no,array("trns_for"=>"RTNO","trans_ref_no"=>$trans_no));
								}
								
								
								$model->point_transaction($AR_ORDER['member_id'],"Dr","RTN",$AR_RETURN_TOTAL['total_pv'],$AR_RETURN_TOTAL['total_bv'],
								$AR_RETURN_TOTAL['total_paid'],$AR_RETURN_TOTAL['total_paid_real'],$AR_ORDER['order_no'],$today_date);
								
								
								
								set_message("success","Your order returned successfully");
								redirect_franchise("order","orderreturnview",array("order_return_id"=>_e($order_return_id)));
							}else{
								set_message("warning","Unable to retun order, please try again");
								redirect_franchise("order","returnorder",array("order_id"=>_e($order_id)));
							}
						}else{
							set_message("warning","Unable to return order as it pass order return policy days");
							redirect_franchise("order","returnorder",array("order_id"=>_e($order_id)));	
						}
					}else{
						set_message("warning","Unable to return order, please try again");
						redirect_franchise("order","returnorder",array("order_id"=>_e($order_id)));
					}
				}
			break;
		}
		
		$this->load->view(FRANCHISE_FOLDER.'/order/returnorder',$data);
	}
	
	public function orderlist(){
		$this->load->view(FRANCHISE_FOLDER.'/order/orderlist',$data);
	}
	
	public function invoicelist(){
		$this->load->view(FRANCHISE_FOLDER.'/order/invoicelist',$data);
	}

	public function fpvinvoicelist(){
		$this->load->view(FRANCHISE_FOLDER.'/order/fpvinvoicelist',$data);
	}
	
	public function luckydraw(){
		$this->load->view(FRANCHISE_FOLDER.'/order/luckydraw',$data);
	}
	
	public function orderreturnlist(){
		$this->load->view(FRANCHISE_FOLDER.'/order/orderreturnlist',$data);
	}
	
	public function orderview(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = (_d($form_data['order_id'])>0)? _d($form_data['order_id']):_d($segment['order_id']);
		$AR_ORDR = $model->getOrderMaster($order_id);
		$order_date = InsertDate($AR_ORDR['date_add']);
		if($form_data['submitFormHistory']==1 && $this->input->post()!=''){
			$id_order_state = $form_data['id_order_state'];
			$data = array("order_id"=>$order_id,
				"id_order_state"=>$id_order_state,
				"date_add"=>$today_date,
			);
			if($id_order_state>0 && $order_id>0){
				$this->SqlModel->insertRecord("tbl_order_history",$data);
				$this->SqlModel->updateRecord("tbl_orders",array("id_order_state"=>$id_order_state),array("order_id"=>$order_id));
				
				if( ( $id_order_state=="2" || $id_order_state=="4" || $id_order_state=="5" || $id_order_state=="9" || $id_order_state=="12" ) && ($id_order_state>0) ){
					#$model->point_transaction($AR_ORDR['member_id'],"Cr","ORD",$AR_ORDR['total_pv'],$AR_ORDR['total_bv'],$AR_ORDR['total_paid'],$AR_ORDR['total_paid_real'],$AR_ORDR['order_no'],$order_date);
					#$model->updateStockQty($AR_ORDR);
					
				}
				
				set_message("success","Order status changed successfully");
				redirect_franchise("order","orderview",array("order_id"=>_e($order_id)));
			}else{
				set_message("warning","Unable to update order status");
				redirect_franchise("order","orderview",array("order_id"=>_e($order_id)));
			}
			
		}
		if($form_data['submitShipDetail']==1 && $this->input->post()!=''){
			$order_ship = $form_data['order_ship'];
			$ship_date = InsertDateTime($form_data['ship_date']);
			if($order_id>0){
				$data = array("order_ship"=>$order_ship,
					"ship_date"=>$ship_date
				);
				$this->SqlModel->updateRecord("tbl_orders",$data,array("order_id"=>$order_id));
				set_message("success","Successfully updated shipping details");
				redirect_franchise("order","orderview",array("order_id"=>_e($order_id)));
			}else{
				set_message("warning","Unable to update order status");
				redirect_franchise("order","orderview",array("order_id"=>_e($order_id)));
			}
		}
		$this->load->view(FRANCHISE_FOLDER.'/order/orderview',$data);
	}
	
	public function invoiceview(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = (_d($form_data['order_id'])>0)? _d($form_data['order_id']):_d($segment['order_id']);
		$AR_ORDR = $model->getOrderMaster($order_id);
		
		$this->load->view(FRANCHISE_FOLDER.'/order/invoiceview',$data);
	}
	
	public function invoicedetail(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = (_d($form_data['order_id'])>0)? _d($form_data['order_id']):_d($segment['order_id']);
		$AR_ORDR = $model->getOrderMaster($order_id);
		
		$this->load->view(FRANCHISE_FOLDER.'/order/invoicedetail',$data);
	}
	
	public function generateinvoice(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		
		
		$CONFIG_MIN_PV_COUPON = $model->getValue("CONFIG_MIN_PV_COUPON");
		$CONFIG_COUPON_SELF = $model->getValue("CONFIG_COUPON_SELF");
		$CONFIG_COUPON_SPONSOR = $model->getValue("CONFIG_COUPON_SPONSOR");
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = (_d($form_data['order_id'])>0)? _d($form_data['order_id']):_d($segment['order_id']);
		$AR_ORDR = $model->getOrderMaster($order_id);
		$AR_MEM = $model->getMember($AR_ORDR['member_id']);
		
		$AR_PLAN = $model->getPinType($model->getDefaultPlan());
		$pin_price = $AR_PLAN['pin_price'];
		
		if($form_data['generateInvoice']==1 && $this->input->post()!=''){
			
			$AR_GST = $model->getOrderGst($order_id);
			$tax_amount = $AR_GST['tax_amount'];
			
			$invoice_number = $model->getInvoiceNo('');
			
			if($order_id>0 && $invoice_number!=''){
				$data = array("invoice_number"=>$invoice_number,
					"invoice_date"=>$today_date,
					"tax_amount"=>getTool($tax_amount,0)
				);
				$model->checkStockQty($AR_ORDR);
				$model->checkBatchNoProduct($AR_ORDR);
				$model->updateStockQty($AR_ORDR);
				
				$this->SqlModel->updateRecord("tbl_orders",$data,array("order_id"=>$order_id));
				$model->point_transaction($AR_ORDR['member_id'],"Cr","ORD",$AR_ORDR['total_pv'],$AR_ORDR['total_bv'],$AR_ORDR['total_paid'],$AR_ORDR['total_paid_real'],$AR_ORDR['order_no'],$today_date);
				
				$model->sendInvoiceSMS($AR_MEM['mobile_number'],$AR_ORDR['total_paid'],$AR_ORDR['total_pv']);
				
				$AR_SELF = $model->getSumSelfCollection($AR_ORDR['member_id'],"","");
				$total_collection = $AR_SELF['total_bal_vol'];
				if($total_collection>=$pin_price){
					$model->insertTree($AR_MEM['member_id'],$AR_MEM['sponsor_id'],$AR_MEM['left_right'],$today_date);
				}
								
				#$AR_MAIL['order_id']=$order_id;
				#Send_Mail($AR_MAIL,"ORDER_STATUS_FRAN");
				
				set_message("success","Successfully generated invoice no of order ".$AR_ORDR['order_no']."");
				redirect_franchise("order","invoicedetail",array("order_id"=>_e($order_id)));
			}else{
				set_message("warning","Unable to update order status");
				redirect_franchise("order","generateinvoice",array("order_id"=>_e($order_id)));
			}
		}
		$this->load->view(FRANCHISE_FOLDER.'/order/generateinvoice',$data);
	}
	
	public function orderreturnview(){
		$this->load->view(FRANCHISE_FOLDER.'/order/orderreturnview',$data);
	}
	
	public function orderlastinvoice(){
		$franchisee_id = $this->session->userdata('fran_id');
		$QR_ORDER = "SELECT toa.* 	
					FROM  tbl_orders  AS toa
					WHERE  toa.franchisee_id='".$franchisee_id."' AND toa.invoice_number!=''
					ORDER BY toa.order_id DESC LIMIT 1";
		$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,1);
		$order_id = $AR_ORDER['order_id'];
		if($order_id>0){
			redirect_franchise("order","invoicedetail",array("order_id"=>_e($order_id)));
		}else{
			$AR_RT['ErrorMsg'] = "Order not found";
			$data['PAGE'] = $AR_RT;
			$this->load->view(FRANCHISE_FOLDER.'/operation/message',$data);
		}
		
	}
	
	public function ordershipped(){
		$this->load->view(FRANCHISE_FOLDER.'/order/ordershipped',$data);
	}
	
	public function taxsummary(){
		$this->load->view(FRANCHISE_FOLDER.'/order/taxsummary',$data);
	}

	public function shipment(){
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
		
		$data = array("data"=>array("awb_numbers"=>$waybill,
			"per_page"=>4,
			"page_size"=>"A4",
			"access_token"=>$access_token,
			"secret_key"=>$secret_key));
		
		$request_param =  json_encode($data,true);
				
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL             => "https://manage.ithinklogistics.com/api_v2/shipping/label.json",
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
			redirect_franchise("order","invoicelist","");
		}else{
			$status_code = $response_array['status_code'];
			$shipment_label = $response_array['file_name'];
			
			$this->SqlModel->updateRecord("tbl_api_courier",array("shipment_label"=>$shipment_label),array("courier_api_id"=>$courier_api_id));
			redirect($shipment_label);
			
		}
		
	}
	
	public function manifest(){
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
		
		$data = array("data"=>array("awb_numbers"=>$waybill,
			"access_token"=>$access_token,
			"secret_key"=>$secret_key));
		
		$request_param =  json_encode($data,true);
				
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL             => "https://manage.ithinklogistics.com/api_v2/shipping/manifest.json",
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
			redirect_franchise("order","invoicelist","");
		}else{
			$status_code = $response_array['status_code'];
			$manifest_label = $response_array['file_name'];
			
			$this->SqlModel->updateRecord("tbl_api_courier",array("manifest_label"=>$manifest_label),array("courier_api_id"=>$courier_api_id));
			redirect($manifest_label);
			
		}
		
	}
	
			
}
?>
