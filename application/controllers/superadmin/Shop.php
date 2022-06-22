<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}

	public function pendinginvoices(){
		$this->load->view(ADMIN_FOLDER.'/shop/pendinginvoices',$data);	
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
					
					$franchisee_id = FCrtRplc($form_data['franchisee_id']);
					$franchisee_name = $model->getFranchisee($franchisee_id);
										
					$member_id = _d($form_data['member_id']);
					$address_id = $model->getMemberAddressId($member_id);
					$order_no = $model->franchiseOrderNo($franchisee_id);
					
					$total_paid = $total_paid_real = FCrtRplc($form_data['net_payable']);
					
					$order_message = "ORDER NO :".$order_no." &nbsp;Order By: ".$franchisee_name;
					$order_date = InsertDate($form_data['order_date']);			
					
					$payment_type = FCrtRplc($form_data['payment_type']);
					$payment = getTool($form_data['payment'],$payment_type);
					$cheque_no = FCrtRplc($form_data['cheque_no']);
					$cheque_date = InsertDate($form_data['cheque_date']);
					$bank_name = FCrtRplc($form_data['bank_name']);
					$bank_branch = FCrtRplc($form_data['bank_branch']);
					$trns_remark = FCrtRplc($form_data['trns_remark']);
					
					$wallet_amount = FCrtRplc($form_data['wallet_amount']);
								
					$post_id_all = array_filter($form_data['post_id']);
					
					$total_products = count($post_id_all);
					$filter_products = count(array_filter($form_data['post_id']));
					if($total_products==$filter_products){
						if($franchisee_id>0){
							if($order_no!='' && $member_id>0){
								$order_data = array("order_no"=>$order_no,
									"member_id"=>$member_id,
									"address_id"=>getTool($address_id,0),
									"franchisee_id"=>$franchisee_id,
									"lang_id"=>LANG_ID,
									"id_order_state"=>2,
									"payment"=>$payment,
									"order_message"=>$order_message,
									"total_paid"=>$total_paid,
									"total_paid_real"=>$total_paid_real,
									"total_products"=>$total_products,
									"date_add"=>$order_date,
									"stock_sts"=>1,
									"payment_type"=>$payment_type,
									"cheque_no"=>getTool($cheque_no,''),
									"cheque_date"=>getTool($cheque_date,''),
									"bank_name"=>getTool($bank_name,''),
									"bank_branch"=>getTool($bank_branch,''),
									"trns_remark"=>getTool($trns_remark,''),
									"date_upd"=>$order_date
									
								);
								$order_id = $this->SqlModel->insertRecord("tbl_orders",$order_data);
								if($order_id>0){									
									$Ctrl=1;
									foreach($post_id_all as  $key=>$post_id):
									
										$AR_POST = $model->getPostDetail($post_id);
										$post_title = $AR_POST['post_title'];
										$post_desc = $AR_POST['post_desc'];
										$post_pv = $AR_POST['post_pv'];
										$post_bv = $AR_POST['post_bv'];
										$tax_age = $AR_POST['tax_age'];
										$post_hsn = $AR_POST['post_hsn'];
										
										$batch_no = FCrtRplc($form_data['post_batch'][$key]);
										$post_attribute_id = FCrtRplc($form_data['post_attribute_id'][$key]);
										$post_qty = FCrtRplc($form_data['post_qty'][$key]);
										$available_qty = FCrtRplc($form_data['available_qty'][$key]);
										
										
										if($post_attribute_id>0){
											$post_attribute = $model->getAttributeOfCombination($post_attribute_id);
											$AR_ATTR = $model->getPostAttributeDetail($post_attribute_id);
											$post_pv = $AR_ATTR['post_attribute_pv'];
										}
										
										
										$post_mrp = ($form_data['post_mrp'][$key]);
										$post_price = ($form_data['post_price'][$key]);							
										$post_amount = ( $post_price * $post_qty );
									
										$post_tax = getPostGst($post_price,$tax_age);
										
										$data_trns = array("order_id"=>$order_id,
											"post_id"=>$post_id,
											"post_attribute_id"=>getTool($post_attribute_id,0),
											"post_attribute"=>getTool($post_attribute,''),
												
											"post_pv"=>getTool($post_pv,0),
											"post_bv"=>getTool($post_bv,0),
											
											"post_tax"=>getTool($post_tax,0),
											"tax_age"=>getTool($tax_age,0),
											"post_hsn"=>getTool($post_hsn,''),
											
											"post_title"=>getTool($post_title,''),
											"post_desc"=>getTool($post_desc,''),
											
											"original_post_price"=>getTool($post_mrp,0),
											"post_qty"=>$post_qty,
											"post_price"=>$post_price,
											"net_amount"=>$post_amount,
											"batch_no"=>getTool($batch_no,''),
											"date_time"=>$order_date
										);
										if($post_id>0){
											$trans_no = UniqueId("STOCK_TRNS_NO");
											$this->SqlModel->insertRecord("tbl_order_detail",$data_trns);
											$model->InsertStockLedger($franchisee_id,$trans_no,"Dr","SAL",$post_id,$post_attribute_id,$post_price,$tax_age,$post_qty,
											$post_amount,$order_no,$order_date,$batch_no,$post_mrp,$post_pv);
											
										}
										
										unset($AR_POST,$post_id,$post_pv,$post_bv,$batch_no,$post_qty,$available_qty,$post_price,$post_amount,$data,$offer_id_set);
									$Ctrl++;
									endforeach;

									
									$AR_TOTAL = $model->getTotalOrderCalc($order_id);
									$total_paid = $AR_TOTAL['total_paid'];
									$cash_amount = $total_paid - $wallet_amount;
									
									$data_order = array("total_pv"=>$AR_TOTAL['total_pv'],
												"total_bv"=>$AR_TOTAL['total_bv'],
												"total_products"=>$AR_TOTAL['total_products'],
												"total_paid"=>$AR_TOTAL['total_paid'],
												"total_paid_real"=>$AR_TOTAL['total_paid_real'],
												"stock_sts"=>1
									);	
									$this->SqlModel->updateRecord("tbl_orders",$data_order,array("order_id"=>$order_id));							
									
									set_message("success","Your order placed successfully");
									redirect_page("shop","orderview",array("order_id"=>_e($order_id)));
								}else{
									set_message("warning","Unable to place order, please try again");
									redirect_page("shop","placeorder",array());
								}
							}else{
								set_message("warning","Unable to place order, please check all filed");
								redirect_page("shop","placeorder",array());
							}
						}else{
							set_message("warning","Unable to place order, please logout and login again");
							redirect_page("shop","placeorder",array());
						}
					}else{
						set_message("warning","Same product is not allowed , please enter unique product");
						redirect_page("shop","placeorder",array());
					}
				}
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/shop/placeorder',$data);
	}
	
	public function xplaceorder(){
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
					
					$franchisee_id = $model->getDefaultFranchisee();
					$franchisee_name = $model->getFranchisee($franchisee_id);
										
					$member_id = _d($form_data['member_id']);
					$address_id = $model->getMemberAddressId($member_id);
					
					$reference_no =  UniqueId("REFER_NO");
					
					$total_paid = $total_paid_real = FCrtRplc($form_data['net_payable']);
					
					$order_message = "ORDER NO :".$order_no." &nbsp;ORDER BY: ".$franchisee_name;
					$order_date = InsertDate($form_data['order_date']);			
					
					$payment_type = FCrtRplc($form_data['payment_type']);
					$payment = getTool($form_data['payment'],$payment_type);
					$cheque_no = FCrtRplc($form_data['cheque_no']);
					$cheque_date = InsertDate($form_data['cheque_date']);
					$bank_name = FCrtRplc($form_data['bank_name']);
					$bank_branch = FCrtRplc($form_data['bank_branch']);
					$trns_remark = FCrtRplc($form_data['trns_remark']);
					
					$wallet_amount = FCrtRplc($form_data['wallet_amount']);
								
					$post_id_all = array_filter($form_data['post_id']);
					
					$total_products = count($post_id_all);
					$filter_products = count(array_filter($form_data['post_id']));
					if($total_products==$filter_products){
						if($franchisee_id>0){
							if($reference_no!=''){
								if($member_id>0){									
									$Ctrl=1;
									foreach($post_id_all as  $key=>$post_id):
										$order_no = $model->getOrderNo();
										$AR_POST = $model->getPostDetail($post_id);
										$post_title = $AR_POST['post_title'];
										$franchisee_id = $AR_POST['franchisee_id'];
										$post_desc = $AR_POST['post_desc'];
										$post_pv = $AR_POST['post_pv'];
										$post_bv = $AR_POST['post_bv'];
										$tax_age = $AR_POST['tax_age'];
										$post_hsn = $AR_POST['post_hsn'];
										
										$batch_no = FCrtRplc($form_data['post_batch'][$key]);
										$post_attribute_id = FCrtRplc($form_data['post_attribute_id'][$key]);
										$post_qty = FCrtRplc($form_data['post_qty'][$key]);
										$available_qty = FCrtRplc($form_data['available_qty'][$key]);
										
										$post_attribute = $model->getAttributeOfCombination($post_attribute_id);
										
										$post_mrp = ($form_data['post_mrp'][$key]);
										$post_price = ($form_data['post_price'][$key]);							
										$post_amount = ( $post_price * $post_qty );
									
										$post_tax = getPostGst($post_price,$tax_age);
										
										$order_data = array("order_no"=>$order_no,
											"member_id"=>$member_id,
											"reference_no"=>$reference_no,
											"address_id"=>getTool($address_id,0),
											"franchisee_id"=>$franchisee_id,
											"lang_id"=>LANG_ID,
											"id_order_state"=>2,
											"payment"=>$payment,
											"order_message"=>$order_message,
											"total_paid"=>$post_amount,
											"total_paid_real"=>$post_mrp,
											"total_products"=>$post_qty,
											"date_add"=>$order_date,
											"stock_sts"=>1,
											"payment_type"=>$payment_type,
											"cheque_no"=>getTool($cheque_no,''),
											"cheque_date"=>getTool($cheque_date,''),
											"bank_name"=>getTool($bank_name,''),
											"bank_branch"=>getTool($bank_branch,''),
											"trns_remark"=>getTool($trns_remark,''),
											"date_upd"=>$order_date
										);
										$order_id = $this->SqlModel->insertRecord("tbl_orders",$order_data);
								
										if($order_id>0){
											$data_trns = array("order_id"=>$order_id,
												"post_id"=>$post_id,
												"franchisee_id"=>getTool($franchisee_id,0),
												"post_attribute_id"=>getTool($post_attribute_id,0),
												"post_attribute"=>getTool($post_attribute,''),
													
												"post_pv"=>getTool($post_pv,0),
												"post_bv"=>getTool($post_bv,0),
												
												"post_tax"=>getTool($post_tax,0),
												"tax_age"=>getTool($tax_age,0),
												"post_hsn"=>getTool($post_hsn,''),
												
												"post_title"=>getTool($post_title,''),
												"post_desc"=>getTool($post_desc,''),
												
												"original_post_price"=>getTool($post_mrp,0),
												"post_qty"=>$post_qty,
												"post_price"=>$post_price,
												"net_amount"=>$post_amount,
												"batch_no"=>getTool($batch_no,''),
												"date_time"=>$order_date
											);
											if($post_id>0){
												$trans_no = UniqueId("STOCK_TRNS_NO");
												$this->SqlModel->insertRecord("tbl_order_detail",$data_trns);
												$model->InsertStockLedger($franchisee_id,$trans_no,"Dr","SAL",$post_id,$post_attribute_id,$post_price,$tax_age,$post_qty,
												$post_amount,$order_no,$order_date,$batch_no,$post_mrp,$post_pv);

												
											}
										}
										
										unset($AR_POST,$post_id,$post_pv,$post_bv,$batch_no,$post_qty,$available_qty,$post_price,$post_amount,$data,$offer_id_set);
									$Ctrl++;
									endforeach;
									set_message("success","Your order placed successfully");
									redirect_page("shop","orderlist","");
								}else{
									set_message("warning","Unable to place order, please try again");
									redirect_page("shop","placeorder",array());
								}
							}else{
								set_message("warning","Unable to place order, please check all filed");
								redirect_page("shop","placeorder",array());
							}
						}else{
							set_message("warning","Unable to place order, please logout and login again");
							redirect_page("shop","placeorder",array());
						}
					}else{
						set_message("warning","Same product is not allowed , please enter unique product");
						redirect_page("shop","placeorder",array());
					}
				}
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/shop/placeorder',$data);
	}
	
	public function postproduct(){
		$model = new OperationModel();
		$today_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$post_id = ($form_data['post_id'])? _d($form_data['post_id']):_d($segment['post_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitPostSave']==1 && $this->input->post()!=''){
					$post_title = FCrtRplc($form_data['post_title']);
					$post_code = FCrtRplc($form_data['post_code']);
					$post_ref = FCrtRplc($form_data['post_ref']);
					$post_tags = FCrtRplc($form_data['post_tags']);
					
					$franchisee_id = getTool($form_data['franchisee_id'],$model->getDefaultFranchisee());
					$lang_id = FCrtRplc($form_data['lang_id']);
					$category_id = $form_data['category_id'];
					$is_product = $form_data['is_product'];
					
					$post_qty_limit = FCrtRplc($form_data['post_qty_limit']);
					
					$post_desc = FCrtRplc($form_data['post_desc']);
					$short_desc = FCrtRplc($form_data['short_desc']);
					$post_mrp = FCrtRplc($form_data['post_mrp']);
					$post_dp_price = FCrtRplc($form_data['post_dp_price']);
					$post_pv = FCrtRplc($form_data['post_pv']);
					$post_bv = FCrtRplc($form_data['post_bv']);
					$post_discount = FCrtRplc($form_data['post_discount']);
					$post_price = FCrtRplc($form_data['post_price']);
					
					$post_width = FCrtRplc($form_data['post_width']);
					$post_height = FCrtRplc($form_data['post_height']);
					$post_depth = FCrtRplc($form_data['post_depth']);
					$post_weight = FCrtRplc($form_data['post_weight']);
					
					$range_offer = FCrtRplc($form_data['range_offer']);
					$post_date = InsertDateTime($form_data['post_date']);
					
					$post_hsn = FCrtRplc($form_data['post_hsn']);
					$post_slug = gen_slug($form_data['post_title']);
					
					$data_add = array("meta_title"=>$post_title,
								"meta_key"=>$post_tags,
								"is_product"=>getTool($is_product,0),
								"post_code"=>$post_code,
								"post_ref"=>getTool($post_ref,''),
								
								"post_width"=>getTool($post_width,0),
								"post_height"=>getTool($post_height,0),
								"post_depth"=>getTool($post_depth,0),
								"post_weight"=>getTool($post_weight,0),
								
								"franchisee_id"=>$franchisee_id,
								"post_qty_limit"=>getTool($post_qty_limit,1),
								"post_date"=>getTool($post_date,$today_date),
								"update_date"=>$today_date
					);
							
					if($model->checkCount("tbl_post","post_id",$post_id)>0){
						
						$this->SqlModel->updateRecord("tbl_post",$data_add,array("post_id"=>$post_id));
						$model->addUpdateLang($post_id,$lang_id,$form_data);
						$model->setPostCategory($post_id,$category_id);
						$model->uploadPostFile($_FILES,array("post_id"=>$post_id),"");
						set_message("success","Successfully updated product  detail");
						redirect_page("shop","productlist",array("post_id"=>_e($post_id)));
					}else{
						if($model->checkRefCodeExist($post_ref)==0){
							if($model->checkPostCodeExist($post_code)==0){
								$post_id = $this->SqlModel->insertRecord("tbl_post",$data_add);
								$model->addUpdateLang($post_id,$lang_id,$form_data);
								$model->setPostCategory($post_id,$category_id);					
								$model->uploadPostFile($_FILES,array("post_id"=>$post_id),"");
								set_message("success","Successfully added new  product detail");
								redirect_page("shop","productlist",array("post_id"=>_e($post_id)));
							}else{
								set_message("warning","This product code  is already exist");
								redirect_page("shop","postproduct","");
							}
						}else{
							set_message("warning","This product ref code  is already exist");
							redirect_page("shop","postproduct","");
						}
					}		
				}
			break;
			case "STATUS":
				if($post_id>0){
					$post_sts = ($segment['post_sts']=="0")? "1":"0";
					$data = array("post_sts"=>$post_sts);
					$this->SqlModel->updateRecord("tbl_post",$data,array("post_id"=>$post_id));
					set_message("success","Status change successfully");
					redirect_page("shop","productlist",array()); exit;
				}
			break;
			case "DELETE":
				if($post_id>0){
					if($model->checkCount("tbl_order_detail","post_id",$post_id)==0){
						$QR_ATTR = "DELETE tbl_post_attribute,tbl_post_attribute_combination, tbl_post_attribute_image 
									FROM tbl_post_attribute
									INNER JOIN tbl_post_attribute_combination ON tbl_post_attribute_combination.post_attribute_id = tbl_post_attribute.post_attribute_id 
									INNER JOIN tbl_post_attribute_image ON tbl_post_attribute_image.post_attribute_id=tbl_post_attribute.post_attribute_id 
									WHERE tbl_post_attribute.post_id = '".$post_id."'";
						$this->db->query($QR_ATTR);		
						
						$QR_IMG	= "SELECT tpf.* FROM tbl_post_file AS tpf WHERE tpf.post_id='".$post_id."'";
						$RS_IMG = $this->SqlModel->runQuery($QR_IMG); 
						foreach($RS_IMG as $AR_IMG){
							$field_id = $AR_IMG['field_id'];
							$final_location = "upload/post/".$AR_IMG['file_name'];
							$final_location_thumb = "upload/post/thumb/".$AR_IMG['file_name_thumb'];
							if($AR_IMG['file_name']!="") { @chmod($final_location,0777);	@unlink($final_location); }
							if($AR_IMG['file_name_thumb']!="") { @chmod($final_location_thumb,0777);	@unlink($final_location_thumb); }	
							$this->SqlModel->deleteRecord("tbl_post_file",array("field_id"=>$field_id));
						}
						$this->SqlModel->deleteRecord("tbl_post",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_post_lang",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_post_review",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_post_view",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_post_category",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_pin_post",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_subscription_post",array("post_id"=>$post_id));
						set_message("success","Product deleted  successfully");
						redirect_page("shop","productlist",array()); exit;
					}else{
						set_message("warning","Cannot delete this product");
						redirect_page("shop","productlist",array()); exit;
					}
				}
			break;
		}
		$data['ROW']=$model->getPostDetail($post_id);
		$this->load->view(ADMIN_FOLDER.'/shop/postproduct',$data);
	}
	
	public function productlist(){
		$this->load->view(ADMIN_FOLDER.'/shop/productlist',$data);	
	}
	
	
	public function postattribute(){
		$model = new OperationModel();
		$today_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$post_attribute_id = (_d($form_data['post_attribute_id']))? _d($form_data['post_attribute_id']):_d($segment['post_attribute_id']);
		$post_id = (_d($form_data['post_id']))? _d($form_data['post_id']):_d($segment['post_id']);
		#$data['ROW']=$model->getPostDetail($post_id);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submit-post-attribute']!='' && $this->input->post()!=''){
					$attribute_combination_list = $form_data['attribute_combination_list'];
					$post_attribute_code = FCrtRplc($form_data['post_attribute_code']);
					$field_id = $form_data['field_id'];
					$post_attribute_detail = FCrtRplc($form_data['post_attribute_detail']);
		
					$post_attribute_mrp = FCrtRplc($form_data['post_attribute_mrp']);
					$post_attribute_tax = $form_data['post_attribute_tax'];
					$post_attribute_discount = $form_data['post_attribute_discount'];
					$post_attribute_price = FCrtRplc($form_data['post_attribute_price']);
					
					$post_selling_mrp = FCrtRplc($form_data['post_selling_mrp']);
					$post_cmsn = FCrtRplc($form_data['post_cmsn']);
					$post_selling = FCrtRplc($form_data['post_selling']);
					
					$post_attribute_pv = FCrtRplc($form_data['post_attribute_pv']);
					
					$data_set = array("post_id"=>$post_id,
								"post_attribute_code"=>$post_attribute_code,
								"post_attribute_detail"=>getTool($post_attribute_detail,''),
								
								"post_selling_mrp"=>getTool($post_selling_mrp,0),
								"post_attribute_mrp"=>getTool($post_attribute_mrp,0),
								"post_attribute_discount"=>getTool($post_attribute_discount,0),
								"post_cmsn"=>getTool($post_cmsn,0),
								"post_selling"=>getTool($post_selling,0),
								"post_attribute_price"=>getTool($post_attribute_price,0),
								
								"post_attribute_pv"=>getTool($post_attribute_pv,0),
								"post_attribute_tax"=>getTool($post_attribute_tax,0),
								"update_date"=>$today_date
					);
					
					if($model->checkCount("tbl_post_attribute","post_attribute_id",$post_attribute_id)>0){
						$this->SqlModel->updateRecord("tbl_post_attribute",$data_set,array("post_attribute_id"=>$post_attribute_id));
						$model->setPostCombination($form_data,$post_attribute_id);
						$model->setAttributeImage($form_data,$post_attribute_id);
						set_message("success","Successfully updated product  detail");
						redirect_page("shop","postattributelist",array("post_id"=>_e($post_id)));
					}else{
						if($model->checkRefCodeExist($post_attribute_code)==0){
								$post_attribute_id = $this->SqlModel->insertRecord("tbl_post_attribute",$data_set);
								$model->setPostCombination($form_data,$post_attribute_id);					
								$model->setAttributeImage($form_data,$post_attribute_id);
								set_message("success","Successfully added new  product detail");
								redirect_page("shop","postattributelist",array("post_id"=>_e($post_id)));
						}else{
							set_message("warning","This Attribute ref code  is already exist");
							redirect_page("shop","postattribute",array("post_id"=>_e($post_id)));
						}
					}		
				}
			break;
			case "STATUS":
				if($post_attribute_id>0){
					$post_attribute_sts = ($segment['post_attribute_sts']=="0")? "1":"0";
					$data = array("post_attribute_sts"=>$post_attribute_sts);
					$this->SqlModel->updateRecord("tbl_post_attribute",$data,array("post_attribute_id"=>$post_attribute_id));
					set_message("success","Status change successfully");
					redirect_page("shop","postattributelist",array("post_id"=>_e($post_id)));
				}
			break;
			case "DELETE":
				if($post_attribute_id>0){
					$data = array("delete_sts"=>0,"update_date"=>$today_date);
					$this->SqlModel->updateRecord("tbl_post_attribute",$data,array("post_attribute_id"=>$post_attribute_id));
					set_message("success","Product deleted  successfully");
					redirect_page("shop","postattributelist",array("post_id"=>_e($post_id)));
				}
			break;
			case "DUPLICATE":
				$QR_SEL ="SELECT * FROM tbl_post_attribute WHERE post_attribute_id='$post_attribute_id'";
				$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
				if($AR_SEL['post_attribute_id']>0){
					
					$post_id = $AR_SEL['post_id'];
					$post_attribute_code = UniqueId("REF_CODE");
					$post_attribute_detail = $AR_SEL['post_attribute_detail'];
					$post_selling_mrp = $AR_SEL['post_selling_mrp'];
					$post_attribute_mrp = $AR_SEL['post_attribute_mrp'];
					$post_attribute_discount = $AR_SEL['post_attribute_discount'];
					$post_cmsn = $AR_SEL['post_cmsn'];
					$post_selling = $AR_SEL['post_selling'];
					$post_attribute_price = $AR_SEL['post_attribute_price'];
					$post_attribute_tax = $AR_SEL['post_attribute_tax'];
					
					$data_set = array("post_id"=>$post_id,
								"post_attribute_code"=>$post_attribute_code,
								"post_attribute_detail"=>getTool($post_attribute_detail,''),
								
								"post_selling_mrp"=>getTool($post_selling_mrp,0),
								"post_attribute_mrp"=>getTool($post_attribute_mrp,0),
								"post_attribute_discount"=>getTool($post_attribute_discount,0),
								"post_cmsn"=>getTool($post_cmsn,0),
								"post_selling"=>getTool($post_selling,0),
								"post_attribute_price"=>getTool($post_attribute_price,0),
								
								"post_attribute_tax"=>getTool($post_attribute_tax,0),
								"update_date"=>$today_date
					);
					
					$post_attribute_id_new = $this->SqlModel->insertRecord("tbl_post_attribute",$data_set);
					
					$QR_SET = "SELECT * FROM tbl_post_attribute_combination WHERE post_attribute_id='".$post_attribute_id."'";
					$RS_SET  = $this->SqlModel->runQuery($QR_SET);
					foreach($RS_SET as $AR_SET):	
						$data_com = array("attribute_id"=>$AR_SET['attribute_id'],
										"post_attribute_id"=>$post_attribute_id_new
									);
						$this->SqlModel->insertRecord("tbl_post_attribute_combination",$data_com);
					endforeach;
					
					$QR_IMG = "SELECT * FROM tbl_post_attribute_image WHERE post_attribute_id='".$post_attribute_id."'";
					$RS_IMG  = $this->SqlModel->runQuery($QR_IMG);
					foreach($RS_IMG as $AR_IMG):	
						$data_img = array("field_id"=>$AR_IMG['field_id'],
										"post_attribute_id"=>$post_attribute_id_new
									);
						$this->SqlModel->insertRecord("tbl_post_attribute_image",$data_img);
					endforeach;
					
					set_message("success","Product combination duplicated  successfully");
					redirect_page("shop","postattributelist",array("post_id"=>_e($post_id)));
				}
			break;
			case "EDIT":
				$QR_SEL ="SELECT * FROM tbl_post_attribute WHERE post_attribute_id='$post_attribute_id'";
				$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
				$data['ROW'] = $AR_SEL;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/shop/postattribute',$data);
	}
	
	public function postattributelist(){
		$this->load->view(ADMIN_FOLDER.'/shop/postattributelist',$data);	
	}
	
	public function postphoto(){	
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$field_id = ($form_data['field_id'])? $form_data['field_id']:_d($segment['field_id']);


		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitTour']==1 && $this->input->post()!=''){
					$post_id = _d($form_data['post_id']);
					$model->uploadPostFile($_FILES,array("post_id"=>$post_id),"");
					set_message("success","You have successfully uploaded a new photo");
					redirect_page("shop","postphoto",array("post_id"=>_e($post_id)));					
						
				}
			break;
			case "DELETE":
				$AR_FILE = SelectTable("tbl_post_file","*","field_id='".$field_id."'");
				
				if($field_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->updateRecord("tbl_post_file",$data,array("field_id"=>$field_id));
					set_message("success","You have successfully deleted photo");	
				}
				redirect_page("shop","postphoto",array("post_id"=>_e($AR_FILE['post_id']))); exit;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/shop/postphoto',$data);
	}
	
	public function category(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$category_id = ($form_data['category_id'])? _d($form_data['category_id']):_d($segment['category_id']);
		$parent_id_get = ($form_data['parent_id'])? _d($form_data['parent_id']):_d($segment['parent_id']);
		$parent_id = ($parent_id_get>0)? $parent_id_get:0;
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitCategory']==1 && $this->input->post()!=''){
					$category_name = FCrtRplc($form_data['category_name']);
					$category_short = FCrtRplc($form_data['category_short']);
					$category_detail = FCrtRplc($form_data['category_detail']);
					$category_slug = gen_slug($category_name);
					
					$option_value = FCrtRplc($form_data['option_value']);
										
					$data = array("category_name"=>$category_name,
						"category_slug"=>$category_slug,
						"parent_id"=>$parent_id,
						"category_short"=>$category_short,
						"category_detail"=>$category_detail,
						"parent_id"=>($parent_id>0)? $parent_id:0,
						"update_date"=>$today_date,
					);
					if($model->checkCount("tbl_category","category_id",$category_id)>0){
						$this->SqlModel->updateRecord("tbl_category",$data,array("category_id"=>$category_id));
						$model->uploadCategoryImg($_FILES,array("category_id"=>$category_id),"");
						$model->setCategoryOption($category_id,array("Font Icon"=>$option_value));
						set_message("success","You have successfully updated a Concierge Services");
						redirect_page("shop","categorylist",array("parent_id"=>_e($parent_id)));								
					}else{
						$category_id = $this->SqlModel->insertRecord("tbl_category",$data,array("category_id"=>$category_id));
						$model->uploadCategoryImg($_FILES,array("category_id"=>$category_id),"");
						$model->setCategoryOption($category_id,array("Font Icon"=>$option_value));
						set_message("success","You have successfully added a new Concierge Services");
						redirect_page("shop","categorylist",array("parent_id"=>_e($parent_id)));					
					}
				}
			break;
			case "STATUS":
				$category_sts = FCrtRplc($segment['category_sts']);
				$new_sts = ($category_sts==0)? 1:0;
				$data = array("category_sts"=>$new_sts);
				$this->SqlModel->updateRecord("tbl_category",$data,array("category_id"=>$category_id));
				set_message("success","You have successfully changed a status");
				redirect_page("shop","categorylist",array());	
			break;
			case "DELETE":
				if($category_id>0){
					/*$QR_SEL = "SELECT tpc.* FROM tbl_post_category AS tpc 
							   WHERE tpc.category_id='".$category_id."'";
					$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
					foreach($RS_SEL as $AR_SEL):
						$post_id = $AR_SEL['post_id'];
						$QR_ATTR = "DELETE tbl_post_attribute,tbl_post_attribute_combination, tbl_post_attribute_image 
									FROM tbl_post_attribute
									INNER JOIN tbl_post_attribute_combination ON tbl_post_attribute_combination.post_attribute_id = tbl_post_attribute.post_attribute_id 
									INNER JOIN tbl_post_attribute_image ON tbl_post_attribute_image.post_attribute_id=tbl_post_attribute.post_attribute_id 
									WHERE tbl_post_attribute.post_id = '".$post_id."'";
						$this->db->query($QR_ATTR);		
						
						$QR_IMG	= "SELECT tpf.* FROM tbl_post_file AS tpf WHERE tpf.post_id='".$post_id."'";
						$RS_IMG = $this->SqlModel->runQuery($QR_IMG); 
						foreach($RS_IMG as $AR_IMG){
							$field_id = $AR_IMG['field_id'];
							$final_location = "upload/post/".$AR_IMG['file_name'];
							$final_location_thumb = "upload/post/thumb/".$AR_IMG['file_name_thumb'];
							if($AR_IMG['file_name']!="") { @chmod($final_location,0777);	@unlink($final_location); }
							if($AR_IMG['file_name_thumb']!="") { @chmod($final_location_thumb,0777);	@unlink($final_location_thumb); }	
							$this->SqlModel->deleteRecord("tbl_post_file",array("field_id"=>$field_id));
						}
						$this->SqlModel->deleteRecord("tbl_post",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_post_lang",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_post_review",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_post_view",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_post_category",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_pin_post",array("post_id"=>$post_id));
						$this->SqlModel->deleteRecord("tbl_subscription_post",array("post_id"=>$post_id));
						unset($post_id);
					endforeach;
					$AR_CAT = $model->getCategoryDetail($category_id);
					$final_location_cat = "upload/category/".$AR_CAT['category_img'];
					if($AR_CAT['category_img']!="") { @chmod($final_location_cat,0777);	@unlink($final_location_cat); }
					$this->SqlModel->deleteRecord("tbl_category_option",array("category_id"=>$category_id));
					$this->SqlModel->deleteRecord("tbl_category",array("category_id"=>$category_id));*/
					$this->SqlModel->updateRecord("tbl_category",array("delete_sts"=>0),array("category_id"=>$category_id));
					set_message("success","Successfully delete a category detail");
				}else{
					set_message("warning","Failed , unable to delete category detail");
				}
				redirect_page("shop","categorylist",array());	
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_category WHERE category_id='".$category_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				
				$AR_ADD = $model->getCategoryOption($category_id);
				$data['ADD'] = $AR_ADD;
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/shop/category',$data);	
	}
	
	public function categorylist(){
	
		$this->load->view(ADMIN_FOLDER.'/shop/categorylist',$data);	
	}
	
	public function orderlist(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = (_d($form_data['order_id']))? _d($form_data['order_id']):_d($segment['order_id']);
		switch($action_request):
			case "DELETE":
				if($this->session->userdata('oprt_id')<=2){
					$AR_ORDR = $model->getOrderMaster($order_id);
					if($AR_ORDR['order_id']>0){
						$Q_Used = "SELECT COUNT(coupon_id) AS iCnt FROM tbl_coupon WHERE order_id='$AR_ORDR[order_id]' AND use_status='Y'";
						$AR_Used = $this->SqlModel->runQuery($Q_Used,true);
						if($AR_Used['iCnt']>0){
							set_message("warning","Can Not Delete this Invoice because FPV generated due to this Invoice is already being used by PC");
						}else{
						$this->SqlModel->deleteRecord("tbl_order_detail",array("order_id"=>$AR_ORDR['order_id']));
						$this->SqlModel->deleteRecord("tbl_stock_ledger",array("ref_no"=>$AR_ORDR['order_no'],"trans_type"=>'Dr'));
						$this->SqlModel->deleteRecord("tbl_mem_point",array("point_ref"=>$AR_ORDR['order_no'],"point_sub_type"=>'ORD'));
						$this->SqlModel->deleteRecord("tbl_orders",array("order_id"=>$AR_ORDR['order_id']));
						$Q_Fpv = "SELECT * FROM tbl_coupon WHERE use_order_id='$AR_ORDR[order_id]'";
						$AR_Fpv = $this->SqlModel->runQuery($Q_Fpv,true);
						if($AR_Fpv['use_order_id']==$AR_ORDR['order_id']){
							$expires_on = AddToDate($AR_Fpv['expires_on'],'+5 Day');
							$fpvdata = array("expires_on"=>$expires_on,
											 "use_status"=>'N',
											 "use_inv_no"=>'',
											 "use_order_id"=>0,
											 "use_member_id"=>0,
											 "use_date"=>'0000-00-00',
											 "order_pv"=>0,
											 "ready_to_use"=>'Y'
							);
							$this->SqlModel->updateRecord("tbl_coupon",$fpvdata,array("coupon_id"=>$AR_Fpv['coupon_id']));
						}
						$Q_Assigned = "SELECT coupon_id FROM tbl_coupon WHERE order_id='$AR_ORDR[order_id]' AND use_status='N' ORDER BY coupon_id ASC";
						$RS_Assigned = $this->SqlModel->runQuery($Q_Assigned);
						foreach($RS_Assigned as $AR_Assigned):
							$this->SqlModel->deleteRecord("tbl_coupon",array("coupon_id"=>$AR_Assigned['coupon_id']));
						endforeach;
						set_message("success","Successfully delete a order");
						}
					}else{
						set_message("warning","Unable to delete this order no");
					}
				}else{
					set_message("warning","Unable to delete this order no");
				}
				redirect_page("shop","orderlist",array());	
			break;
		endswitch;
		$this->load->view(ADMIN_FOLDER.'/shop/orderlist',$data);	
	}
	
	public function invoiceview(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = (_d($form_data['order_id'])>0)? _d($form_data['order_id']):_d($segment['order_id']);
		$AR_ORDR = $model->getOrderMaster($order_id);
		
		$this->load->view(ADMIN_FOLDER.'/shop/invoiceview',$data);
	}
	
	
	public function invoicedetail(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
				
		$this->load->view(ADMIN_FOLDER.'/shop/invoicedetail',$data);
	}
	
	
	
	
	public function sendtocourier(){
		if(isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}

		$model = new OperationModel();
		$CONFIG_COURIER = $model->getValue("CONFIG_COURIER");
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = getLocalTime();
		$order_id = (_d($form_data['order_id'])>0)? _d($form_data['order_id']):_d($segment['order_id']);
		
		if($order_id>0){
			$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.member_email, tm.user_id AS user_id ,tm.city_name, tm.state_name,
			 tad.full_name AS ship_full_name, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name,
			 tad.country_code AS ship_country_code, tad.current_address AS ship_current_address, tad.pin_code AS ship_pin_code, 
			 tad.mobile_number AS ship_mobile_number,  tad.order_type, 
			 tos.name AS order_state,
			 tod.post_title, tod.post_attribute, tod.post_desc, tod.post_qty AS order_qty, tod.post_price AS item_price, tod.batch_no,
			 tod.post_width, tod.post_height, tod.post_depth, tod.post_weight,
			 tf.name AS seller_name, tf.address AS seller_addres, tf.gst_no AS seller_gst_no, tf.tin_no AS seller_tin_no,
			 tf.warehouse_id
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 LEFT JOIN tbl_order_detail AS tod ON tod.order_id=ord.order_id
			 LEFT JOIN tbl_franchisee AS tf ON tf.franchisee_id=ord.franchisee_id
			 WHERE ord.order_id>0 AND ord.invoice_number!='' AND ord.order_id='".$order_id."'
			 $StrWhr
			 GROUP BY ord.order_id
			 ORDER BY ord.order_id DESC";
			$AR_DT = $this->SqlModel->runQuery($QR_ORDER,true);
			
			$waybill = '';
			$OrderType = ($AR_DT['payment']=="COD")? "COD":"PrePaid";
			$order_date = InsertDate($AR_DT['date_add']);
			$OrderNo = $AR_DT['order_no'];
			$SubOrderNo = $AR_DT['reference_no'];
			$PaymentStatus = $OrderType;
			
			$CustomerName = $AR_DT['full_name'];
			$CustomerAddress = $AR_DT['ship_current_address'];
			$CustomerAddress2 = '';
			$CustomerAddress3 = '';
			$CustomerCity = $AR_DT['ship_city_name'];
			$CustomerState = $AR_DT['ship_state_name'];
			$ZipCode = $AR_DT['ship_pin_code'];
			$CustomerMobileNo = $AR_DT['ship_mobile_number'];
			$CustomerPhoneNo = '';
			$CustomerEmail = $AR_DT['member_email'];
			
			$ProductMRP = $AR_DT['total_paid_real'];
			$ProductGroup = $AR_DT['post_title'];
			$ProductDesc = $AR_DT['post_attribute'];
			$Cod_Payment = $AR_DT['total_paid'];
			$OctroiMRP = 0;
			
			$VolWeight = $AR_DT['post_weight'];
			$PhyWeight = $AR_DT['post_weight'];
			$ShipLength = $AR_DT['post_depth'];
			$ShipWidth = $AR_DT['post_width'];
			$ShipHeight = $AR_DT['post_height'];
			$AirWayBillNO = '';
			$ServiceType = '';
			
			$Quantity = $AR_DT['total_products'];
			$CSTNumber = '';
			$TINNumber = '';
			$CGST = 0;
			$SGST = 0;
			$IGST = 0;
			
			$invoiceno = $AR_DT['invoice_number'];
			$invoicedate = $AR_DT['invoice_date'];
			$Product_SKU = $AR_DT['batch_no'];
			$Status = '';
			$OrderStatus = $AR_DT['order_state'];
			$Error = '';
			$return_address_id = $AR_DT['warehouse_id'];
			
			$pickup_address_id = $AR_DT['warehouse_id'];
			$access_token = ITHINK_ACCESS;
			$secret_key = ITHINK_KEY;
			
			$logistics = $CONFIG_COURIER;
			
			$shipments = array("shipments"=>array(
				array("waybill"=>$waybill,
					"order"=>$OrderNo,
					"sub_order"=>$SubOrderNo,
					"order_date"=>$order_date,
					"total_amount"=>$ProductMRP,
					"name"=>$CustomerName,
					"add"=>$CustomerAddress,
					"add2"=>$CustomerAddress2,
					"add3"=>$CustomerAddress3,
					"pin"=>$ZipCode,
					"city"=>$CustomerCity,
					"state"=>$CustomerState,
					"country"=>"india",
					"phone"=>$CustomerMobileNo,
					"email"=>$CustomerEmail,
					
					"products"=>$ProductGroup,
					"products_desc"=>getTool($ProductDesc,$ProductGroup),
					"quantity"=>$Quantity,
					"shipment_length"=>getTool($ShipLength,''),
					"shipment_width"=>getTool($ShipWidth,''),
					"shipment_height"=>getTool($ShipHeight,''),
					"weight"=>getTool($VolWeight,''),
					"cod_amount"=>$Cod_Payment,
					"payment_mode"=>$OrderType,
					
					"seller_tin"=>$TINNumber,
					"seller_cst"=>$CSTNumber,
					"return_address_id"=>$return_address_id,
					"product_sku"=>$Product_SKU,
					"extra_parameters"=>array("return_reason"=>'',
						"encryptedShipmentID"=>""),
				
				)
			),
			"pickup_address_id"=>$pickup_address_id,
			"access_token"=>$access_token,
			"secret_key"=>$secret_key,
			"logistics"=>$logistics,
			"s_type"=>"standard",
			"order_type"=>""
			); 
			
			$data = array("data"=>$shipments);
			
			$request_param =  json_encode($data,true);
					
			
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL             => "https://manage.ithinklogistics.com/api_v2/order/add.json",
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
			#PrintR($request_param);
			#PrintR($response);exit;
			if ($err){
				set_message("warning","Error #:" . $err);
				redirect_page("shop","invoicereport","");
			}else{
				
				
				$status_code = $response_array['status_code'];
				$message = $response_array['message'];
				if($model->checkCount("tbl_api_courier","order_id",$order_id)==0){
					$status = $response_array['data']['1']['status'];
					$remark = $response_array['data']['1']['remark'];
					$waybill = $response_array['data']['1']['waybill'];
					$refnum = $response_array['data']['1']['refnum'];
					$payment = $response_array['data']['1']['payment'];
					$cod_amount = $response_array['data']['1']['cod_amount'];
					
					
					$data_set = array("order_id"=>$order_id,
						"access_token"=>$access_token,
						"secret_key"=>$secret_key,
						"pickup_address_id"=>$pickup_address_id,
						"request_json"=>getTool($request_param,''),
						"response_json"=>getTool($response,''),
						"status"=>getTool($status,''),
						"remark"=>getTool($remark,''),
						"waybill"=>getTool($waybill,''),
						"refnum"=>getTool($refnum,''),
						"payment"=>getTool($payment,''),
						"cod_amount"=>getTool($cod_amount,'0')
					);
					if($model->checkCountPro("tbl_api_courier","refnum='".$refnum."'")==0){
						if($status=="success"){
							$this->SqlModel->insertRecord("tbl_api_courier",$data_set);
							set_message("success","Order request to courier has been process successfully with waybill no:".$waybill);
							redirect_page("shop","trackcourier",array("waybill"=>($waybill)));
						}else{
							set_message("warning","Remark #:" . $remark);
							redirect_page("shop","invoicereport","");
						}
					}else{
						set_message("success",$remark);
						redirect_page("shop","invoicereport","");
					}
				}else{
					set_message("warning","This order is already process for courier, please check status of courier");
					redirect_page("shop","invoicereport","");
				}
			}

				
		}
		
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
			redirect_page("shop","invoicereport","");
		}else{
			$status_code = $response_array['status_code'];
			
			$this->SqlModel->updateRecord("tbl_api_courier",array("track_status"=>$track_status),array("courier_api_id"=>$courier_api_id));
			redirect_page("shop","trackdetail",array("waybill"=>($waybill)));
			
		}
		
	}
	
	public function trackdetail(){
		$this->load->view(ADMIN_FOLDER.'/shop/trackdetail',$data);	
	}
	
	
	public function generateinvoice(){
		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = getLocalTime();
	
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = (_d($form_data['order_id'])>0)? _d($form_data['order_id']):_d($segment['order_id']);
		$AR_ORDR = $model->getOrderMaster($order_id);
		$AR_MEM = $model->getMember($AR_ORDR['member_id']);
		
		if($form_data['generateInvoice']==1 && $this->input->post()!=''){
			
			$invoice_number = $model->getInvoiceNo(1);
			
			if($order_id>0 && $invoice_number!=''){
				$data_invoice = array("invoice_number"=>$invoice_number,
					"invoice_date"=>$today_date
				);
				
				$model->checkStockQty($AR_ORDR);
				$model->checkBatchNoProduct($AR_ORDR);
				$model->updateStockQty($AR_ORDR);
				
				$this->SqlModel->updateRecord("tbl_orders",$data_invoice,array("order_id"=>$order_id));				
				$model->sendInvoiceSMS($AR_MEM['mobile_number'],$AR_ORDR['total_paid'],$AR_ORDR['total_pv']);
				
				#$model->setReferralOrder($AR_ORDR['member_id'],$order_id);
				$model->point_transaction($AR_ORDR['member_id'],"Cr","ORD",$AR_ORDR['total_pv'],$AR_ORDR['total_bv'],
				$AR_ORDR['total_paid'],$AR_ORDR['total_paid_real'],$AR_ORDR['order_no'],$today_date);

				set_message("success","Successfully generated invoice no of order ".$AR_ORDR['order_no']."");
				redirect_page("shop","orderview",array("order_id"=>_e($order_id)));
			}else{
				set_message("warning","Unable to update order status");
				redirect_page("shop","orderview",array("order_id"=>_e($order_id)));
			}
		}
		$this->load->view(ADMIN_FOLDER.'/shop/orderview',$data);
	}	
	
	public function orderview(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = ($form_data['order_id'])? _d($form_data['order_id']):_d($segment['order_id']);
		$AR_ORDR = $model->getOrderMaster($order_id);
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
					#$model->point_transaction($AR_ORDR['member_id'],"Cr","ORD",$AR_ORDR['total_pv'],$AR_ORDR['total_bv'],$AR_ORDR['order_no'],$today_date);
					#$model->updateStockQty($AR_ORDR);
					
				}
				
				set_message("success","Order status changed successfully");
				redirect_page("shop","orderview",array("order_id"=>_e($order_id)));
			}else{
				set_message("warning","Unable to update order status");
				redirect_page("shop","orderview",array("order_id"=>_e($order_id)));
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
				redirect_page("shop","orderview",array("order_id"=>_e($order_id)));
			}else{
				set_message("warning","Unable to update order status");
				redirect_page("shop","orderview",array("order_id"=>_e($order_id)));
			}
		}
		$this->load->view(ADMIN_FOLDER.'/shop/orderview',$data);	
	}
		
	public function productreport(){
		$this->load->view(ADMIN_FOLDER.'/shop/productreport',$data);	
	}
	
	public function productreportdetail(){
		
		$this->load->view(ADMIN_FOLDER.'/shop/productreportdetail',$data);	
	}
	
	public function postoffer(){
		$model = new OperationModel();
		$today_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$offer_id = ($form_data['offer_id'])? _d($form_data['offer_id']):_d($segment['offer_id']);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitOfferSave']==1 && $this->input->post()!=''){
					$post_id = FCrtRplc($form_data['post_id']);
					
					$offer_title = FCrtRplc($form_data['offer_title']);
					$offer_code = FCrtRplc($form_data['offer_code']);
					$offer_min_price = FCrtRplc($form_data['offer_min_price']);
					#$offer_max_price = FCrtRplc($form_data['offer_max_price']);
					
					$offer_min_pv = FCrtRplc($form_data['offer_min_pv']);
					$offer_max_pv = FCrtRplc($form_data['offer_max_pv']);
		
					$offer_expiry = FCrtRplc($form_data['offer_expiry']);
					$offer_desc = $form_data['offer_desc'];
					$offer_terms = $form_data['offer_terms'];
					
					$offer_repeat = FCrtRplc($form_data['offer_repeat']);
					$offer_module = FCrtRplc($form_data['offer_module']);
					$offer_type = FCrtRplc($form_data['offer_type']);
					$offer_multiple = FCrtRplc($form_data['offer_multiple']);
					
					$franchisee_id = FCrtRplc($form_data['franchisee_id']);
					
					if($offer_type=="NEW"){
						$member_join_from = InsertDate($form_data['member_join_from']);
						$member_join_to = InsertDate($form_data['member_join_to']);
						$period_register = FCrtRplc($form_data['period_register']);
					}else{
						$member_join_from = InsertDate($model->getFirstDate());
						$member_join_to = InsertDate($form_data['member_join_before']);
						$period_register = 0;
					}
					
					$offer_pv = FCrtRplc($form_data['offer_pv']);
					$offer_bv = FCrtRplc($form_data['offer_bv']);
					$offer_price = FCrtRplc($form_data['offer_price']);
					
					$data_offer = array("offer_title"=>$offer_title,
								"offer_code"=>$offer_code,
								"offer_min_price"=>($offer_min_price)? $offer_min_price:0,
								"offer_max_price"=>($offer_max_price)? $offer_max_price:0,
								"offer_min_pv"=>($offer_min_pv>0)? $offer_min_pv:0,
								"offer_max_pv"=>($offer_max_pv>0)? $offer_max_pv:0,
								
								"offer_expiry"=>$offer_expiry,
								"offer_desc"=>($offer_desc)? $offer_desc:'',
								
								"offer_module"=>$offer_module,
								"offer_type"=>$offer_type,
								"member_join_from"=>$member_join_from,
								"member_join_to"=>$member_join_to,
								"period_register"=>($period_register>0)? $period_register:0,
								"offer_multiple"=>($offer_multiple>0)? $offer_multiple:0,
								
								"franchisee_id"=>$franchisee_id,
								"offer_terms"=>$offer_terms,
								"offer_pv"=>($offer_pv)? $offer_pv:0,
								"offer_bv"=>($offer_bv)? $offer_bv:0,
								"offer_price"=>($offer_price)? $offer_price:0
					);

							
					if($model->checkCount("tbl_offer","offer_id",$offer_id)>0){
						$this->SqlModel->updateRecord("tbl_offer",$data_offer,array("offer_id"=>$offer_id));
						$model->setOfferPost($offer_id,array("post_id"=>$post_id));
						$model->uploadOfferFile($_FILES,array("offer_id"=>$offer_id),"");
						set_message("success","Successfully updated offer detail");
						redirect_page("shop","offerlist",array("offer_id"=>_e($offer_id)));
					}else{
						if($model->checkOfferCodeExist($offer_code)==0){
							
							$offer_id = $this->SqlModel->insertRecord("tbl_offer",$data_offer);
							$model->setOfferPost($offer_id,array("post_id"=>$post_id));
							$model->uploadOfferFile($_FILES,array("offer_id"=>$offer_id),"");
							
							set_message("success","Successfully added new  offer detail");
							redirect_page("shop","offerlist",array("offer_id"=>_e($offer_id)));
						}else{
							set_message("warning","This offer code  is already exist");
							redirect_page("shop","postoffer","");
						}
						
					}		
				}
			break;
			case "STATUS":
				if($offer_id>0){
					$offer_sts = ($segment['offer_sts']=="0")? "1":"0";
					$data = array("offer_sts"=>$offer_sts);
					$this->SqlModel->updateRecord("tbl_offer",$data,array("offer_id"=>$offer_id));
					set_message("success","Status change successfully");
					redirect_page("shop","offerlist",array()); exit;
				}
			break;
			case "DELETE":
				if($offer_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->updateRecord("tbl_offer",$data,array("offer_id"=>$offer_id));
					set_message("success","Offer deleted  successfully");
					redirect_page("shop","offerlist",array()); exit;
				}
			break;
			case "COPY":
				if($offer_id>0){
					$AR_OFF = $model->getOfferDetail($offer_id);
					$source_path = "upload/offer/".$AR_OFF['offer_image'];
					$sufix = $model->checkOfferCodeExist($AR_OFF['offer_code'])+1;
					$offer_code = $AR_OFF['offer_code'].$sufix;
					
					$data_offer = array("offer_title"=>$AR_OFF['offer_title'],
								"offer_code"=>$offer_code,
								"offer_min_price"=>($AR_OFF['offer_min_price'])? $AR_OFF['offer_min_price']:0,
								"offer_max_price"=>($AR_OFF['offer_max_price'])? $AR_OFF['offer_max_price']:0,
								"offer_min_pv"=>($AR_OFF['offer_min_pv']>0)? $AR_OFF['offer_min_pv']:0,
								"offer_max_pv"=>($AR_OFF['offer_max_pv']>0)? $AR_OFF['offer_max_pv']:0,
								
								"offer_expiry"=>$AR_OFF['offer_expiry'],
								"offer_desc"=>($AR_OFF['offer_desc'])? $AR_OFF['offer_desc']:'',
								
								"offer_module"=>$AR_OFF['offer_module'],
								"offer_type"=>$AR_OFF['offer_type'],
								"member_join_from"=>$AR_OFF['member_join_from'],
								"member_join_to"=>$AR_OFF['member_join_to'],
								"period_register"=>($AR_OFF['period_register']>0)? $AR_OFF['period_register']:0,
								"offer_multiple"=>($AR_OFF['offer_multiple']>0)? $AR_OFF['offer_multiple']:0,
								
								"franchisee_id"=>$AR_OFF['franchisee_id'],
								"offer_terms"=>$AR_OFF['offer_terms'],
								"offer_pv"=>($AR_OFF['offer_pv'])? $AR_OFF['offer_pv']:0,
								"offer_bv"=>($AR_OFF['offer_bv'])? $AR_OFF['offer_bv']:0,
								"offer_price"=>($AR_OFF['offer_price'])? $AR_OFF['offer_price']:0
					);
					$new_offer_id = $this->SqlModel->insertRecord("tbl_offer",$data_offer);
					
					$new_file = $model->copyImg($source_path,"");
					$this->SqlModel->updateRecord("tbl_offer",array("offer_image"=>$new_file),array("offer_id"=>$new_offer_id));

					$QR_POST = "SELECT * FROM tbl_offer_product WHERE offer_id='".$offer_id."'";
					$RS_POST = $this->SqlModel->runQuery($QR_POST);
					foreach($RS_POST as $AR_POST):
						$post_id = $AR_POST['post_id'];
						if($post_id>0 && $new_offer_id>0){
							$this->SqlModel->insertRecord("tbl_offer_product",array("post_id"=>$post_id,"offer_id"=>$new_offer_id));
						}
					endforeach;
					
					set_message("success","Successfully copy your selected offer");
					redirect_page("shop","offerlist","");
					
				}
			break;
		}
		$QR_CHECK = "SELECT tof.* FROM tbl_offer AS tof WHERE tof.offer_id='".$offer_id."'";		
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		
		$data['ROW']=$fetchRow;
		$this->load->view(ADMIN_FOLDER.'/shop/postoffer',$data);
	}
	
	public function postmultipleoffer(){
		$model = new OperationModel();
		$today_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		switch($action_request){
			case "MULTIPLE_OFFERS":
				if($form_data['submitOfferSave']==1 && $this->input->post()!=''){
					$QR_POST = "SELECT post_id, post_code, meta_title FROM tbl_post WHERE delete_sts='1' ORDER BY post_id ASC";
					$RS_POST = $this->SqlModel->runQuery($QR_POST);
					foreach($RS_POST as $AR_POST):
						$offer_title = FCrtRplc($form_data['offer_title']);
						$offer_code = FCrtRplc($form_data['offer_code']);
						$offer_min_price = FCrtRplc($form_data['offer_min_price']);
						
						$offer_min_pv = FCrtRplc($form_data['offer_min_pv']);
						$offer_max_pv = FCrtRplc($form_data['offer_max_pv']);
			
						$offer_expiry = FCrtRplc($form_data['offer_expiry']);
						$offer_desc = $form_data['offer_desc'];
						$offer_terms = $form_data['offer_terms'];
						
						$offer_repeat = FCrtRplc($form_data['offer_repeat']);
						$offer_module = FCrtRplc($form_data['offer_module']);
						$offer_type = FCrtRplc($form_data['offer_type']);
						$offer_multiple = FCrtRplc($form_data['offer_multiple']);
						
						$franchisee_id = FCrtRplc($form_data['franchisee_id']);
						
						if($offer_type=="NEW"){
							$member_join_from = InsertDate($form_data['member_join_from']);
							$member_join_to = InsertDate($form_data['member_join_to']);
							$period_register = FCrtRplc($form_data['period_register']);
						}else{
							$member_join_from = InsertDate($model->getFirstDate());
							$member_join_to = InsertDate($form_data['member_join_before']);
							$period_register = 0;
						}
						
						$offer_pv = FCrtRplc($form_data['offer_pv']);
						$offer_bv = FCrtRplc($form_data['offer_bv']);
						$offer_price = FCrtRplc($form_data['offer_price']);
					
						$offer_title = $AR_POST['meta_title'].' '.$offer_title;
						$offer_code = $AR_POST['post_code'].''.$offer_code;
						
						$data_offer = array("offer_title"=>$offer_title,
									"offer_code"=>$offer_code,
									"offer_min_price"=>($offer_min_price)? $offer_min_price:0,
									"offer_max_price"=>($offer_max_price)? $offer_max_price:0,
									"offer_min_pv"=>($offer_min_pv>0)? $offer_min_pv:0,
									"offer_max_pv"=>($offer_max_pv>0)? $offer_max_pv:0,
									
									"offer_expiry"=>$offer_expiry,
									"offer_desc"=>($offer_desc)? $offer_desc:'',
									
									"offer_module"=>$offer_module,
									"offer_type"=>$offer_type,
									"member_join_from"=>$member_join_from,
									"member_join_to"=>$member_join_to,
									"period_register"=>($period_register>0)? $period_register:0,
									"offer_multiple"=>($offer_multiple>0)? $offer_multiple:0,
									
									"franchisee_id"=>$franchisee_id,
									"offer_terms"=>$offer_terms,
									"offer_pv"=>($offer_pv)? $offer_pv:0,
									"offer_bv"=>($offer_bv)? $offer_bv:0,
									"offer_price"=>($offer_price)? $offer_price:0
						);
						$offer_id = $this->SqlModel->insertRecord("tbl_offer",$data_offer);
						$model->setOfferPost($offer_id,array("post_id"=>$AR_POST['post_id']));
						unset($offer_title,$offer_code,$data_offer,$offer_id);
					endforeach;
					set_message("success","Successfully Created");
					redirect_page("shop","offerlist","");
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/shop/postmultipleoffer',$data);
	}

	public function offerlist(){
		$this->load->view(ADMIN_FOLDER.'/shop/offerlist',$data);	
	}
	
	public function reportoffer(){
		$this->load->view(ADMIN_FOLDER.'/shop/reportoffer',$data);	
	}

	public function viewofferreport(){
		$this->load->view(ADMIN_FOLDER.'/shop/viewofferreport',$data);	
	}
	
	public function review(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$review_id = (_d($form_data['review_id']))? _d($form_data['review_id']):_d($segment['review_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitReview']==1 && $this->input->post()!=''){
					$post_id = FCrtRplc($form_data['post_id']);
					$review_by = FCrtRplc($form_data['review_by']);
					$email_id = FCrtRplc($form_data['email_id']);
					$review_dtls = FCrtRplc($form_data['review_dtls']);
					
					$data = array("review_by"=>$review_by,
						"post_id"=>$post_id,
						"email_id"=>$email_id,
						"review_dtls"=>$review_dtls
					);
					if($model->checkCount("tbl_post_review","review_id",$review_id)>0){
						$this->SqlModel->updateRecord("tbl_post_review",$data,array("review_id"=>$review_id));
						set_message("success","You have successfully updated a product review");
						redirect_page("shop","productreview",array("review_id"=>_e($review_id),"action_request"=>"EDIT"));							
					}else{
						$review_id = $this->SqlModel->insertRecord("tbl_post_review",$data,array("review_id"=>$review_id));
						
						set_message("success","You have successfully added a new product review");
						redirect_page("shop","productreview",array());					
					}
				}
			break;
			case "STATUS":
				$review_sts = FCrtRplc($segment['review_sts']);
				$new_sts = ($review_sts==0)? 1:0;
				$data = array("review_sts"=>$new_sts);
				$this->SqlModel->updateRecord("tbl_post_review",$data,array("review_id"=>$review_id));
				set_message("success","You have successfully changed a status");
				redirect_page("shop","productreview",array());	
			break;
			case "DELETE":
				if($review_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->deleteRecord("tbl_post_review",array("review_id"=>$review_id));
					set_message("success","Successfully delete a Product Review");
				}else{
					set_message("warning","Failed , unable to delete product review");
				}
				redirect_page("shop","productreview",array());	
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_post_review WHERE review_id='".$review_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/shop/review',$data);	
	}
	
	public function productreview(){
		$this->load->view(ADMIN_FOLDER.'/shop/productreview',$data);	
	}
	
	public function taxsummary(){
		$this->load->view(ADMIN_FOLDER.'/shop/taxsummary',$data);
	}

	// new report created on 19 aug 2017 (by abhay) on client request
	public function invoicereport(){
		$this->load->view(ADMIN_FOLDER.'/shop/invoicereport',$data);
	}

	// new report created on 29 sep 2017 (by abhay) on client request
	
	
	public function carrier(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$carrier_id = (_d($form_data['carrier_id'])>0)? _d($form_data['carrier_id']):_d($segment['carrier_id']);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitCarrier']==1 && $this->input->post()!=''){
					$carrier_name = FCrtRplc($form_data['carrier_name']);
					$carrier_free = FCrtRplc($form_data['carrier_free']);
					$carrier_flat_charge = FCrtRplc($form_data['carrier_flat_charge']);
					
					$data = array("carrier_name"=>$carrier_name,
						"carrier_free"=>$carrier_free,
						"carrier_flat_charge"=>getTool($carrier_flat_charge,'')
					);
					
					if($model->checkCount("tbl_carrier","carrier_id",$carrier_id)>0){
						$this->SqlModel->updateRecord("tbl_carrier",$data,array("carrier_id"=>$carrier_id));
						$model->updateCarrierCharge($carrier_id,$form_data);
						
						$model->uploadCarrierImage($_FILES,array("carrier_id"=>$carrier_id),"");
						set_message("success","You have successfully updated a carrier detail");
						redirect_page("shop","carrierlist","");							
					}else{
						$carrier_id = $this->SqlModel->insertRecord("tbl_carrier",$data);
						$model->updateCarrierCharge($carrier_id,$form_data);
						$model->uploadCarrierImage($_FILES,array("carrier_id"=>$carrier_id),"");
						set_message("success","You have successfully added a carrier detail");
						redirect_page("shop","carrierlist",array());					
					}
				}
			break;
			case "STATUS":
				$carrier_sts = FCrtRplc($segment['carrier_sts']);
				$new_sts = ($carrier_sts==0)? 1:0;
				$data = array("carrier_sts"=>$new_sts);
				$this->SqlModel->updateRecord("tbl_carrier",$data,array("carrier_id"=>$carrier_id));
				set_message("success","You have successfully changed a status");
				redirect_page("shop","carrierlist",array());	
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_carrier WHERE carrier_id='".$carrier_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/shop/carrier',$data);	
	}
	
	public function carrierlist(){
		$this->load->view(ADMIN_FOLDER.'/shop/carrierlist',$data);
	}
	
	public function invoicepackagelist(){
		$this->load->view(ADMIN_FOLDER.'/shop/invoicepackagelist',$data);
	}
	
	public function invoicepackagedetail(){
		$this->load->view(ADMIN_FOLDER.'/shop/invoicepackagedetail',$data);
	}
	
	public function orderpayment(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = getTool($form_data['action_request'],$segment['action_request']);
		$order_no =  getTool($form_data['order_no'],$segment['order_no']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submit-payment']==1 && $this->input->post()!=''){
					$order_no = FCrtRplc($form_data['order_no']);
					$order_amount = FCrtRplc($form_data['order_amount']);
					$transaction_no = FCrtRplc($form_data['transaction_no']);
					$payment_type = FCrtRplc($form_data['payment_type']);
					$pay_status = FCrtRplc($form_data['pay_status']);					
					$AR_ORDR = $model->getOrderMasterDetail($order_no);
					$order_id = $AR_ORDR['order_id'];
					$franchisee_id = $AR_ORDR['franchisee_id'];
					
					if($order_id>0){
						if($order_amount>0){
							$data_set = array("order_id"=>$order_id,
								"franchisee_id"=>$franchisee_id,
								"transaction_no"=>getTool($transaction_no,''),
								"order_amount"=>getTool($order_amount,0),
								"payment_type"=>getTool($payment_type,''),
								"pay_status"=>$pay_status
							);
							if($model->checkCount("tbl_franchisee_payment","order_id",$order_id)>0){
								$this->SqlModel->updateRecord("tbl_franchisee_payment",$data_set,array("order_id"=>$order_id));
								$model->uploadFranchiseReceipt($_FILES,array("order_id"=>$order_id),"");
								set_message("success","You have successfully updated  order payment");
								redirect_page("report","orderpayment","");	
							}else{
								$this->SqlModel->insertRecord("tbl_franchisee_payment",$data_set);
								$model->uploadFranchiseReceipt($_FILES,array("order_id"=>$order_id),"");
								set_message("success","You have successfully updated  order payment");
								redirect_page("report","orderpayment","");	
							}
						}else{
							set_message("warning","Invalid order amount");
							redirect_page("shop","orderpayment","");	
						}
					}else{
						set_message("warning","Invalid order no");
						redirect_page("shop","orderpayment","");	
					}
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/shop/orderpayment',$data);	
	}
	
	public function orderreturnlist(){
		$this->load->view(ADMIN_FOLDER.'/shop/orderreturnlist',$data);	
	}
	
	public function orderreturnview(){
		$this->load->view(ADMIN_FOLDER.'/shop/orderreturnview',$data);	
	}
	
}
