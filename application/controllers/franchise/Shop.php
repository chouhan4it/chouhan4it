<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	     if(!$this->isFranchiseLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}


	
	public function postproduct(){
		$model = new OperationModel();
		$today_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$franchisee_id = $this->session->userdata('fran_id');
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$post_id = ($form_data['post_id'])? _d($form_data['post_id']):_d($segment['post_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitPostSave']==1 && $this->input->post()!=''){
					$post_title = FCrtRplc($form_data['post_title']);
					$post_code = FCrtRplc($form_data['post_code']);
					$post_ref = FCrtRplc($form_data['post_ref']);
					$post_tags = FCrtRplc($form_data['post_tags']);
		
					$lang_id = FCrtRplc($form_data['lang_id']);
					$category_id = $form_data['category_id'];
					$is_product = getTool($form_data['is_product'],0);
					$post_qty_limit = FCrtRplc($form_data['post_qty_limit']);
					
					$post_desc = FCrtRplc($form_data['post_desc']);
					$short_desc = FCrtRplc($form_data['short_desc']);
					$post_mrp = FCrtRplc($form_data['post_mrp']);
					$post_dp_price = FCrtRplc($form_data['post_dp_price']);
					$post_pv = FCrtRplc($form_data['post_pv']);
					$post_bv = FCrtRplc($form_data['post_bv']);
					$post_discount = FCrtRplc($form_data['post_discount']);
					$post_price = FCrtRplc($form_data['post_price']);
					
					$post_sts = FCrtRplc($form_data['post_sts']);
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
								"franchisee_id"=>$franchisee_id,
								"post_ref"=>getTool($post_ref,''),
								
								"post_width"=>getTool($post_width,0),
								"post_height"=>getTool($post_height,0),
								"post_depth"=>getTool($post_depth,0),
								"post_weight"=>getTool($post_weight,0),
								
								"post_sts"=>getTool($post_sts,0),
								"post_qty_limit"=>getTool($post_qty_limit,1),
								"post_date"=>getTool($post_date,$today_date),
								"update_date"=>$today_date
					);
					
					if($franchisee_id>0){
						if($model->checkCount("tbl_post","post_id",$post_id)>0){
							
							$this->SqlModel->updateRecord("tbl_post",$data_add,array("post_id"=>$post_id));
							$model->addUpdateLang($post_id,$lang_id,$form_data);
							$model->setPostCategory($post_id,$category_id);
							$model->uploadPostFile($_FILES,array("post_id"=>$post_id),"");
							set_message("success","Successfully updated product  detail");
							redirect_franchise("shop","productlist",array("post_id"=>_e($post_id)));
						}else{
							if($model->checkRefCodeExist($post_ref)==0){
								if($model->checkPostCodeExist($post_code)==0){
									$post_id = $this->SqlModel->insertRecord("tbl_post",$data_add);
									$model->addUpdateLang($post_id,$lang_id,$form_data);
									$model->setPostCategory($post_id,$category_id);					
									$model->uploadPostFile($_FILES,array("post_id"=>$post_id),"");
									set_message("success","Successfully added new  product detail");
									redirect_franchise("shop","productlist",array("post_id"=>_e($post_id)));
								}else{
									set_message("warning","This product code  is already exist");
									redirect_franchise("shop","postproduct","");
								}
							}else{
								set_message("warning","This product ref code  is already exist");
								redirect_franchise("shop","postproduct","");
							}
						}
					}else{
						set_message("warning","Something went wrong, please login and try again");
						redirect_franchise("shop","postproduct","");
					}
				}
			break;
			case "STATUS":
				if($post_id>0){
					$post_sts = ($segment['post_sts']=="0")? "1":"0";
					$data = array("post_sts"=>$post_sts);
					$this->SqlModel->updateRecord("tbl_post",$data,array("post_id"=>$post_id));
					set_message("success","Status change successfully");
					redirect_franchise("shop","productlist",array()); exit;
				}
			break;
			case "STOCK_STS":
				if($post_id>0){
					$stock_sts = ($segment['stock_sts']=="0")? "1":"0";
					$data = array("stock_sts"=>$stock_sts);
					$this->SqlModel->updateRecord("tbl_post",$data,array("post_id"=>$post_id));
					set_message("success","Stock Status change successfully");
					redirect_franchise("shop","productlist",array()); exit;
				}
			break;
			case "DELETE":
				if($post_id>0){
					$data = array("delete_sts"=>0,"update_date"=>$today_date);
					$this->SqlModel->updateRecord("tbl_post",$data,array("post_id"=>$post_id));
					set_message("success","Product deleted  successfully");
					redirect_franchise("shop","productlist",array()); exit;
				}
			break;
		}
		$data['ROW']=$model->getPostDetail($post_id);
		$this->load->view(FRANCHISE_FOLDER.'/shop/postproduct',$data);
	}
	
	public function productlist(){
		$this->load->view(FRANCHISE_FOLDER.'/shop/productlist',$data);	
	}
	
	
	public function postattribute(){
		$model = new OperationModel();
		$today_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$post_attribute_id = (_d($form_data['post_attribute_id']))? _d($form_data['post_attribute_id']):_d($segment['post_attribute_id']);
		$post_id = (_d($form_data['post_id'])>0)? _d($form_data['post_id']):_d($segment['post_id']);
		#$data['ROW']=$model->getPostDetail($post_id);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submit-post-attribute']!='' && $this->input->post()!=''){
					$attribute_combination_list = $form_data['attribute_combination_list'];
					$post_attribute_code = FCrtRplc($form_data['post_attribute_code']);
					$field_id = $form_data['field_id'];
					$post_attribute_detail = FCrtRplc($form_data['post_attribute_detail']);
					
					
					$post_selling_mrp = FCrtRplc($form_data['post_selling_mrp']);
					$post_attribute_tax = $form_data['post_attribute_tax'];
					$post_attribute_discount = $form_data['post_attribute_discount'];
					$post_cmsn = $form_data['post_cmsn'];
					$post_attribute_mrp = FCrtRplc($form_data['post_attribute_mrp']);
					
					$post_selling = FCrtRplc($form_data['post_selling']);
					$post_attribute_price = FCrtRplc($form_data['post_attribute_price']);
					
					$data_set = array("post_id"=>$post_id,
								"post_attribute_code"=>$post_attribute_code,
								
								"post_selling_mrp"=>$post_selling_mrp,
								"post_attribute_tax"=>getTool($post_attribute_tax,0),
								"post_attribute_discount"=>getTool($post_attribute_discount,0),
								"post_cmsn"=>getTool($post_cmsn,0),
								"post_attribute_mrp"=>getTool($post_attribute_mrp,0),
								"post_attribute_price"=>getTool($post_attribute_price,0),
								"post_selling"=>getTool($post_selling,0),
								
								"post_attribute_detail"=>getTool($post_attribute_detail,''),
								"update_date"=>$today_date
					);
					
					if($model->checkCount("tbl_post_attribute","post_attribute_id",$post_attribute_id)>0){
						$this->SqlModel->updateRecord("tbl_post_attribute",$data_set,array("post_attribute_id"=>$post_attribute_id));
						$model->setPostCombination($form_data,$post_attribute_id);
						$model->setAttributeImage($form_data,$post_attribute_id);
						set_message("success","Successfully updated product  detail");
						redirect_franchise("shop","postattributelist",array("post_id"=>_e($post_id)));
					}else{
						if($model->checkRefCodeExist($post_attribute_code)==0){
								$post_attribute_id = $this->SqlModel->insertRecord("tbl_post_attribute",$data_set);
								$model->setPostCombination($form_data,$post_attribute_id);					
								$model->setAttributeImage($form_data,$post_attribute_id);
								set_message("success","Successfully added new  product detail");
								redirect_franchise("shop","postattributelist",array("post_id"=>_e($post_id)));
						}else{
							set_message("warning","This Attribute ref code  is already exist");
							redirect_franchise("shop","postattribute",array("post_id"=>_e($post_id)));
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
					redirect_franchise("shop","postattributelist",array("post_id"=>_e($post_id)));
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
			case "DELETE":
				if($post_attribute_id>0){
					$data = array("delete_sts"=>0,"update_date"=>$today_date);
					$this->SqlModel->updateRecord("tbl_post_attribute",$data,array("post_attribute_id"=>$post_attribute_id));
					set_message("success","Product deleted  successfully");
					redirect_franchise("shop","postattributelist",array("post_id"=>_e($post_id)));
				}
			break;
			case "EDIT":
				$QR_SEL ="SELECT * FROM tbl_post_attribute WHERE post_attribute_id='$post_attribute_id'";
				$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
				$data['ROW'] = $AR_SEL;
			break;
		}
		$this->load->view(FRANCHISE_FOLDER.'/shop/postattribute',$data);
	}
	
	public function postattributelist(){
		$this->load->view(FRANCHISE_FOLDER.'/shop/postattributelist',$data);	
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
					redirect_franchise("shop","postphoto",array("post_id"=>_e($post_id)));					
						
				}
			break;
			case "DELETE":
				$AR_FILE = SelectTable("tbl_post_file","*","field_id='".$field_id."'");
				
				if($field_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->updateRecord("tbl_post_file",$data,array("field_id"=>$field_id));
					set_message("success","You have successfully deleted photo");	
				}
				redirect_franchise("shop","postphoto",array("post_id"=>_e($AR_FILE['post_id']))); exit;
			break;
		}
		$this->load->view(FRANCHISE_FOLDER.'/shop/postphoto',$data);
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
						redirect_franchise("shop","categorylist",array("parent_id"=>_e($parent_id)));								
					}else{
						$category_id = $this->SqlModel->insertRecord("tbl_category",$data,array("category_id"=>$category_id));
						$model->uploadCategoryImg($_FILES,array("category_id"=>$category_id),"");
						$model->setCategoryOption($category_id,array("Font Icon"=>$option_value));
						set_message("success","You have successfully added a new Concierge Services");
						redirect_franchise("shop","categorylist",array("parent_id"=>_e($parent_id)));					
					}
				}
			break;
			case "STATUS":
				$category_sts = FCrtRplc($segment['category_sts']);
				$new_sts = ($category_sts==0)? 1:0;
				$data = array("category_sts"=>$new_sts);
				$this->SqlModel->updateRecord("tbl_category",$data,array("category_id"=>$category_id));
				set_message("success","You have successfully changed a status");
				redirect_franchise("shop","categorylist",array());	
			break;
			case "DELETE":
				if($category_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->updateRecord("tbl_category",$data,array("category_id"=>$category_id));
					set_message("success","Successfully delete a Concierge Services");
				}else{
					set_message("warning","Failed , unable to delete Concierge Services");
				}
				redirect_franchise("shop","categorylist",array());	
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
		$this->load->view(FRANCHISE_FOLDER.'/shop/category',$data);	
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
				redirect_franchise("shop","orderlist",array());	
			break;
		endswitch;
		$this->load->view(FRANCHISE_FOLDER.'/shop/orderlist',$data);	
	}
	
	public function invoiceview(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_id = (_d($form_data['order_id'])>0)? _d($form_data['order_id']):_d($segment['order_id']);
		$AR_ORDR = $model->getOrderMaster($order_id);
		
		$this->load->view(FRANCHISE_FOLDER.'/shop/invoiceview',$data);
	}
	
	
	public function invoicedetail(){
		$today_date = getLocalTime();
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
				
		$this->load->view(FRANCHISE_FOLDER.'/shop/invoicedetail',$data);
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
				redirect_franchise("shop","orderview",array("order_id"=>_e($order_id)));
			}else{
				set_message("warning","Unable to update order status");
				redirect_franchise("shop","orderview",array("order_id"=>_e($order_id)));
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
				redirect_franchise("shop","orderview",array("order_id"=>_e($order_id)));
			}else{
				set_message("warning","Unable to update order status");
				redirect_franchise("shop","orderview",array("order_id"=>_e($order_id)));
			}
		}
		$this->load->view(FRANCHISE_FOLDER.'/shop/orderview',$data);	
	}
		
	public function productreport(){
		$this->load->view(FRANCHISE_FOLDER.'/shop/productreport',$data);	
	}
	
	public function productreportdetail(){
		
		$this->load->view(FRANCHISE_FOLDER.'/shop/productreportdetail',$data);	
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
						redirect_franchise("shop","offerlist",array("offer_id"=>_e($offer_id)));
					}else{
						if($model->checkOfferCodeExist($offer_code)==0){
							
							$offer_id = $this->SqlModel->insertRecord("tbl_offer",$data_offer);
							$model->setOfferPost($offer_id,array("post_id"=>$post_id));
							$model->uploadOfferFile($_FILES,array("offer_id"=>$offer_id),"");
							
							set_message("success","Successfully added new  offer detail");
							redirect_franchise("shop","offerlist",array("offer_id"=>_e($offer_id)));
						}else{
							set_message("warning","This offer code  is already exist");
							redirect_franchise("shop","postoffer","");
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
					redirect_franchise("shop","offerlist",array()); exit;
				}
			break;
			case "DELETE":
				if($offer_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->updateRecord("tbl_offer",$data,array("offer_id"=>$offer_id));
					set_message("success","Offer deleted  successfully");
					redirect_franchise("shop","offerlist",array()); exit;
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
					redirect_franchise("shop","offerlist","");
					
				}
			break;
		}
		$QR_CHECK = "SELECT tof.* FROM tbl_offer AS tof WHERE tof.offer_id='".$offer_id."'";		
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		
		$data['ROW']=$fetchRow;
		$this->load->view(FRANCHISE_FOLDER.'/shop/postoffer',$data);
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
					redirect_franchise("shop","offerlist","");
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/shop/postmultipleoffer',$data);
	}

	public function offerlist(){
		$this->load->view(FRANCHISE_FOLDER.'/shop/offerlist',$data);	
	}
	
	public function reportoffer(){
		$this->load->view(FRANCHISE_FOLDER.'/shop/reportoffer',$data);	
	}

	public function viewofferreport(){
		$this->load->view(FRANCHISE_FOLDER.'/shop/viewofferreport',$data);	
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
						redirect_franchise("shop","productreview",array("review_id"=>_e($review_id),"action_request"=>"EDIT"));							
					}else{
						$review_id = $this->SqlModel->insertRecord("tbl_post_review",$data,array("review_id"=>$review_id));
						
						set_message("success","You have successfully added a new product review");
						redirect_franchise("shop","productreview",array());					
					}
				}
			break;
			case "STATUS":
				$review_sts = FCrtRplc($segment['review_sts']);
				$new_sts = ($review_sts==0)? 1:0;
				$data = array("review_sts"=>$new_sts);
				$this->SqlModel->updateRecord("tbl_post_review",$data,array("review_id"=>$review_id));
				set_message("success","You have successfully changed a status");
				redirect_franchise("shop","productreview",array());	
			break;
			case "DELETE":
				if($review_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->deleteRecord("tbl_post_review",array("review_id"=>$review_id));
					set_message("success","Successfully delete a Product Review");
				}else{
					set_message("warning","Failed , unable to delete product review");
				}
				redirect_franchise("shop","productreview",array());	
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_post_review WHERE review_id='".$review_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(FRANCHISE_FOLDER.'/shop/review',$data);	
	}
	
	public function productreview(){
		$this->load->view(FRANCHISE_FOLDER.'/shop/productreview',$data);	
	}
	
	public function taxsummary(){
		$this->load->view(FRANCHISE_FOLDER.'/shop/taxsummary',$data);
	}

	// new report created on 19 aug 2017 (by abhay) on client request
	public function invoicereport(){
		$this->load->view(FRANCHISE_FOLDER.'/shop/invoicereport',$data);
	}

	
}
