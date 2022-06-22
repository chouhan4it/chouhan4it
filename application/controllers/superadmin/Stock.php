<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	public function franchiseelist(){
		$this->load->view(ADMIN_FOLDER.'/franchisee/franchiseelist',$data);
	}

	public function shoppetocompanytxns(){
		$this->load->view(ADMIN_FOLDER.'/stock/shoppetocompanytxns',$data);
	}

	public function returnstockdetail(){
		$this->load->view(ADMIN_FOLDER.'/stock/returnstockdetail',$data);
	}
	
	public function stockentrycompany(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$stock_id = ($form_data['stock_id'])? $form_data['stock_id']:_d($segment['stock_id']);
		$today_date = InsertDate(getLocalTime());
		
		$oprt_id  = $this->session->userdata('oprt_id');
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitStock']==1 && $this->input->post()!=''){
				
					$order_no = $model->warehouseOrderNo();
					$narration = "WAREHOUSE[".$today_date."]"."[".$order_no."]";
					$order_date = InsertDate($form_data['order_date']);			
					
					$bill_no = FCrtRplc($form_data['bill_no']);			
					$bill_date = InsertDate($form_data['bill_date']);			
					
									
					$post_id_all = array_filter($form_data['post_id']);
					$warehouse_id = $model->getDefaultWarehouse();
					
						if(count($post_id_all)>0){
							$data = array("order_no"=>$order_no,
								"product_count"=>count($post_id_all),
								"order_date"=>$today_date,
								"sub_type"=>"CMP",
								"batch_no"=>$batch_no,
								"oprt_id"=>$oprt_id
							);
							
							foreach($post_id_all as  $key=>$post_id):
								$AR_PD = $model->getPostDetail($post_id);
								$tax_age = $AR_PD['tax_age'];
								$trans_no = UniqueId("TRNS_NO");
								
								$post_attribute_id = FCrtRplc($form_data['post_attribute_id'][$key]);
								
								$supplier_id = FCrtRplc($form_data['supplier_id'][$key]);
								$batch_no = FCrtRplc($form_data['batch_no'][$key]);
								
								$mfg_date = InsertDate($form_data['mfg_date'][$key]);
								$exp_date = InsertDate($form_data['exp_date'][$key]);
								
								$name = FCrtRplc($form_data['post_title'][$key]);
								$price = FCrtRplc($form_data['post_price'][$key]);
								$qty = FCrtRplc($form_data['post_qty'][$key]);
								$total = FCrtRplc($form_data['post_sum_price'][$key]);
								$sum_total +=$total;
								if($post_id>0 && $batch_no!=''){
									$wh_trns_id = $model->WarehouseTransaction($warehouse_id,$post_id,$post_attribute_id,$supplier_id,"Cr","CMP",$name,$price,$tax_age,
									$qty,$total,$mfg_date,$exp_date,$order_no,$batch_no,$order_date);
									$this->SqlModel->updateRecord("tbl_warehouse_trns",array("bill_no"=>$bill_no,"bill_date"=>$bill_date),
									array("wh_trns_id"=>$wh_trns_id));
									
								}
							endforeach;
						}
						set_message("success","Successfully added a new stock");
						redirect_page("stock","stocktransactioncompany",array("warehouse_id"=>_e($warehouse_id)));
				}else{
					set_message("warning","Unable to add stock , please try again");
					redirect_page("stock","stockentrycompany",array());
				}
			break;
			case "DELETE":
				$ref_no = (_d($form_data['ref_no'])>0)? _d($form_data['ref_no']):_d($segment['ref_no']);
				if($ref_no!=''){
					$QR_WHR = "SELECT twt.* FROM tbl_warehouse_trns AS twt WHERE twt.ref_no LIKE '".$ref_no."' ORDER BY twt.wh_trns_id ASC";
					$RS_WHR = $this->SqlModel->runQuery($QR_WHR);
					foreach($RS_WHR as $AR_WHR):
						$Ctrl +=$model->checkCount("tbl_stock_master","batch_no",$AR_WHR['batch_no']);
					endforeach;
					if($Ctrl==0){
						$this->SqlModel->deleteRecord("tbl_warehouse_trns",array("ref_no"=>$ref_no));
						set_message("success","Successfully delete stock");
					    redirect_page("stock","stocktransactioncompany",array()); 
					}
					set_message("warning","Unable to delete batch no is forward to franchisee");
					redirect_page("stock","stocktransactioncompany",array()); 
				}
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/stock/stockentrycompany',$data);
	}
	
	public function stockentry(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$oprt_id  = $this->session->userdata('oprt_id');
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$stock_id = ($form_data['stock_id'])? $form_data['stock_id']:_d($segment['stock_id']);
		$today_date = InsertDate(getLocalTime());
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitStock']==1 && $this->input->post()!=''){
					
					$franchisee_id_from = FCrtRplc($form_data['franchisee_id_from']);
					$franchisee_id_to = FCrtRplc($form_data['franchisee_id_to']);
					$franchisee_name_from = $model->getFranchisee($franchisee_id_from);
					$franchisee_name_to = $model->getFranchisee($franchisee_id_to);
					$warehouse_id = $model->getDefaultWarehouse();
					
					$payment_type = FCrtRplc($form_data['payment_type']);
					$cheque_no = FCrtRplc($form_data['cheque_no']);
					$cheque_date = InsertDate($form_data['cheque_date']);
					$bank_name = FCrtRplc($form_data['bank_name']);
					$bank_branch = FCrtRplc($form_data['bank_branch']);
					$trns_remark = FCrtRplc($form_data['trns_remark']);
								
					
					$order_no = $model->stockOrderNo();
					$narration = "From franchisee: ".$franchisee_name_from." to Franchisee: ".$franchisee_name_to;
					$order_date = InsertDate($form_data['order_date']);			
					
					$stock_aprd_date = getLocalTime();
					
					$post_id_all = array_filter($form_data['post_id']);
					
						if(count($post_id_all)>0){
							$model->checkQtyInvoice($form_data);
							foreach($post_id_all as  $key=>$post_id):
								$AR_PD = $model->getPostDetail($post_id);
								$trans_no = UniqueId("TRNS_NO");
								$post_pv = $AR_PD['post_pv'];
								$post_mrp = $AR_PD['post_mrp'];
								$tax_age = $AR_PD['tax_age'];
								$post_attribute_id =  FCrtRplc($form_data['post_attribute_id'][$key]);
								$available_qty =  FCrtRplc($form_data['available_qty'][$key]);
								$post_qty = FCrtRplc($form_data['post_qty'][$key]);
								$post_price = FCrtRplc($form_data['post_price'][$key]);
								$post_dp_price = FCrtRplc($form_data['post_dp_price'][$key]);
								$post_amount = ($post_dp_price*$post_qty);
								$batch_no = $model->getBatchNoProduct($post_id);
								
								$data = array("franchisee_id_from"=>$franchisee_id_from,
									"franchisee_id_to"=>$franchisee_id_to,
									"post_id"=>$post_id,
									"post_attribute_id"=>getTool($post_attribute_id,0),
									"post_mrp"=>$post_mrp,
									"post_pv"=>$post_pv,
									"post_price"=>$post_dp_price,
									"post_qty"=>$post_qty,
									"post_amount"=>$post_amount,
									"order_no"=>$order_no,
									"batch_no"=>$batch_no,
									"order_date"=>$order_date,
									"stock_sts"=>"C",
									"supplier_id"=>0,
									"stock_aprd_date"=>$stock_aprd_date,
									"payment_type"=>$payment_type,
									"cheque_no"=>($cheque_no)? $cheque_no:'',
									"cheque_date"=>($cheque_date)? $cheque_date:'',
									"bank_name"=>($bank_name)? $bank_name:'',
									"bank_branch"=>($bank_branch)? $bank_branch:'',
									"trns_remark"=>($trns_remark)? $trns_remark:'',
									"narration"=>$narration
								);
								
								if($post_id>0 && $available_qty>=$post_qty){
									$stock_id = $this->SqlModel->insertRecord("tbl_stock_master",$data);
									$model->InsertStockLedger($franchisee_id_to,$trans_no,"Cr","TRF",$post_id,$post_attribute_id,$post_dp_price,$tax_age,$post_qty,$post_amount,
									$order_no,$order_date,$batch_no);
									
									$model->WarehouseTransaction($warehouse_id,$post_id,$post_attribute_id,0,"Dr","TRF",$AR_PD['post_title'],$post_dp_price,$tax_age,$post_qty,
									$post_amount,"","",$order_no,$batch_no,$order_date);
									
								}
							endforeach;
							set_message("success","Successfully added a new stock");
							redirect_page("stock","stockdetail",array("stock_id"=>_e($stock_id)));
						}
					
				}else{
					set_message("warning","Unable to add stock , please try again");
					redirect_page("stock","stockentry",array());
				}
			break;
			case "STATUS":
				$order_date =  InsertDate(getLocalTime());
				$stock_sts = $segment['stock_sts'];
				$order_no = ($form_data['order_no'])? $form_data['order_no']:_d($segment['order_no']);
				if($order_no>0){
					$Q_STOCK = "SELECT tsm.*, tpl.post_title, tpl.tax_age, tpl.post_price AS product_price FROM tbl_stock_master AS tsm 
						LEFT JOIN tbl_post AS tp ON tp.post_id=tsm.post_id
						LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
						WHERE tsm.order_no='".$order_no."'";
					$RS_STOCK = $this->SqlModel->runQuery($Q_STOCK);
					foreach($RS_STOCK as $AR_STOCK){
						$stock_id = $AR_STOCK['stock_id'];
						$tax_age = $AR_STOCK['tax_age'];
						$trans_no = UniqueId("TRNS_NO");
						$franchisee_id = $AR_STOCK['franchisee_id_to'];
						$post_id =  $AR_STOCK['post_id'];
						$post_attribute_id =  $AR_STOCK['post_attribute_id'];
						$trans_amount = $AR_STOCK['post_price'];
						$trans_qty = $AR_STOCK['post_qty'];
						$net_amount = $AR_STOCK['post_amount'];
						$ref_no = $AR_STOCK['order_no'];
						if($stock_sts=="C"){ 
							$model->InsertStockLedger($franchisee_id,$trans_no,"Cr","TRF",$post_id,$post_attribute_id,$trans_amount,$tax_age,$trans_qty,$net_amount,$ref_no,$order_date);
							$data = array("stock_sts"=>$stock_sts,"stock_aprd_date"=>$order_date);
					 	}
						$this->SqlModel->updateRecord("tbl_stock_master",$data,array("stock_id"=>$stock_id));
					}
					set_message("success","Status change successfully");
					redirect_page("stock","stocktransaction",array()); exit;
				}else{
					set_message("warning","Unable to update status");
					redirect_page("stock","stocktransaction",array()); exit;
				}
			break;
			case "DELETE":
				$order_no = (_d($form_data['order_no'])>0)? _d($form_data['order_no']):_d($segment['order_no']);
				if($order_no!=''){
					$QR_SEL = "SELECT tsm.* FROM tbl_stock_master AS tsm WHERE tsm.order_no LIKE '".$order_no."' ORDER BY tsm.stock_id ASC";
					$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
					foreach($RS_SEL as $AR_SEL):
						$Ctrl +=$model->checkProtectCount("tbl_order_detail","batch_no",$AR_SEL['batch_no']);
					endforeach;
					
					if($Ctrl==0){
						$QR_MSTR = "SELECT tsm.* FROM tbl_stock_master AS tsm WHERE tsm.order_no='".$order_no."'";
						$AR_MSTR = $this->SqlModel->runQuery($QR_MSTR,true);
						$order_no = $ref_no = $AR_MSTR['order_no'];
						$this->SqlModel->deleteRecord("tbl_stock_master",array("order_no"=>$order_no));
						$this->SqlModel->deleteRecord("tbl_stock_ledger",array("ref_no"=>$ref_no));
						set_message("success","Successfully delete stock");
						redirect_page("stock","stocktransaction",array());
					}else{
						set_message("success","Unable to delete stock, batch is used");
						redirect_page("stock","stocktransaction",array());
					}
				}
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/stock/stockentry',$data);
	}

	public function shoppetocompany(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$oprt_id  = $this->session->userdata('oprt_id');
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$stock_id = ($form_data['stock_id'])? $form_data['stock_id']:_d($segment['stock_id']);
		$today_date = InsertDate(getLocalTime());
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitStock']==1 && $this->input->post()!=''){
					
					$franchisee_id_from = FCrtRplc($form_data['franchisee_id_to']);
					$franchisee_id_to = FCrtRplc($form_data['franchisee_id_from']);
					$franchisee_name_from = $model->getFranchisee($franchisee_id_from);
					$franchisee_name_to = $model->getFranchisee($franchisee_id_to);
					$warehouse_id = $model->getDefaultWarehouse();
					
					$payment_type = FCrtRplc($form_data['payment_type']);
					$cheque_no = FCrtRplc($form_data['cheque_no']);
					$cheque_date = InsertDate($form_data['cheque_date']);
					$bank_name = FCrtRplc($form_data['bank_name']);
					$bank_branch = FCrtRplc($form_data['bank_branch']);
					$trns_remark = FCrtRplc($form_data['trns_remark']);
								
					
					$order_no = $model->stockrevOrderNo();
					$narration = "From franchisee: ".$franchisee_name_from." to Company warehouse";
					$order_date = InsertDate($form_data['order_date']);			
					
					$stock_aprd_date = getLocalTime();
					
					$post_id_all = array_unique(array_filter($form_data['post_id']));
					
						if(count($post_id_all)>0){
							$model->checkQtyInvoice($form_data);
							foreach($post_id_all as  $key=>$post_id):
								$AR_PD = $model->getPostDetail($post_id);
								$trans_no = UniqueId("TRNS_NO");
								$post_pv = $AR_PD['post_pv'];
								$post_mrp = $AR_PD['post_mrp'];
								$tax_age = $AR_PD['tax_age'];
								$post_attribute_id =  FCrtRplc($form_data['post_attribute_id'][$key]);
								$available_qty =  FCrtRplc($form_data['available_qty'][$key]);
								$post_qty = FCrtRplc($form_data['post_qty'][$key]);
								$post_price = FCrtRplc($form_data['post_price'][$key]);
								$post_dp_price = FCrtRplc($form_data['post_dp_price'][$key]);
								$post_amount = ($post_dp_price*$post_qty);
								$batch_no = $model->getBatchNoProduct($post_id);
								
								$data = array("franchisee_id_from"=>$franchisee_id_from,
									"franchisee_id_to"=>$franchisee_id_to,
									"post_id"=>$post_id,
									"post_mrp"=>$post_mrp,
									"post_pv"=>$post_pv,
									"post_price"=>$post_dp_price,
									"post_qty"=>$post_qty,
									"post_amount"=>$post_amount,
									"order_no"=>$order_no,
									"batch_no"=>$batch_no,
									"order_date"=>$order_date,
									"stock_sts"=>"C",
									"supplier_id"=>0,
									"stock_aprd_date"=>$stock_aprd_date,
									"payment_type"=>$payment_type,
									"cheque_no"=>($cheque_no)? $cheque_no:'',
									"cheque_date"=>($cheque_date)? $cheque_date:'',
									"bank_name"=>($bank_name)? $bank_name:'',
									"bank_branch"=>($bank_branch)? $bank_branch:'',
									"trns_remark"=>($trns_remark)? $trns_remark:'',
									"narration"=>$narration
								);
								
								if($post_id>0 && $available_qty>=$post_qty){
									//$this->SqlModel->insertRecord("tbl_stock_master",$data);
									$model->InsertStockLedger($franchisee_id_from,$trans_no,"Dr","TRF",$post_id,$post_attribute_id,$post_dp_price,$tax_age,$post_qty,$post_amount,
									$order_no,$order_date,$batch_no);
									$model->WarehouseTransaction($warehouse_id,$post_id,$post_attribute_id,0,"Cr","TRF",$AR_PD['post_title'],$post_dp_price,$tax_age,$post_qty,
									$post_amount,"","",$order_no,$batch_no,$order_date);
									
								}
							endforeach;
							set_message("success","Successfully transferred to warehouse");
							redirect_page("stock","shoppetocompany",array());
						}
					
				}else{
					set_message("warning","Unable to process request, please try again");
					redirect_page("stock","shoppetocompany",array());
				}
			break;
		}

		$this->load->view(ADMIN_FOLDER.'/stock/shoppetocompany',$data);
	}
	
	public function demoentry(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$oprt_id  = $this->session->userdata('oprt_id');
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$stock_id = ($form_data['stock_id'])? $form_data['stock_id']:_d($segment['stock_id']);
		$today_date = InsertDate(getLocalTime());
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitDemoStock']==1 && $this->input->post()!=''){
					
					$name_to = FCrtRplc($form_data['name_to']);
					$warehouse_id = $model->getDefaultWarehouse();
					
					$payment_type = FCrtRplc($form_data['payment_type']);
					$cheque_no = FCrtRplc($form_data['cheque_no']);
					$cheque_date = InsertDate($form_data['cheque_date']);
					$bank_name = FCrtRplc($form_data['bank_name']);
					$bank_branch = FCrtRplc($form_data['bank_branch']);
					$trns_remark = FCrtRplc($form_data['trns_remark']);
								
					
					$order_no = $model->demoOrderNo();
					$narration = "DEMO ".$name_to." [".$order_no."]";
					$order_date = InsertDate($form_data['order_date']);			
					
					$stock_aprd_date = getLocalTime();
					
					$post_id_all = array_unique(array_filter($form_data['post_id']));
					
						if(count($post_id_all)>0){
							$model->checkQtyInvoice($form_data);
							foreach($post_id_all as  $key=>$post_id):
								$AR_PD = $model->getPostDetail($post_id);
								$trans_no = UniqueId("TRNS_NO");
								$post_pv = $AR_PD['post_pv'];
								$post_mrp = $AR_PD['post_price'];
								$post_tax = $AR_PD['post_tax'];
								$tax_age = $AR_PD['tax_age'];
								$available_qty =  FCrtRplc($form_data['available_qty'][$key]);
								$post_qty = FCrtRplc($form_data['post_qty'][$key]);
								$post_price = FCrtRplc($form_data['post_price'][$key]);
								$post_dp_price = FCrtRplc($form_data['post_dp_price'][$key]);
								$post_amount = ($post_dp_price*$post_qty);
								$batch_no = $model->getBatchNoProduct($post_id);
								
								$data = array("name_to"=>$name_to,
									"post_id"=>$post_id,
									"post_mrp"=>$post_mrp,
									"post_pv"=>$post_pv,
									"post_price"=>$post_dp_price,
									"post_tax"=>$post_tax,
									"post_qty"=>$post_qty,
									"post_amount"=>$post_amount,
									"order_no"=>$order_no,
									"batch_no"=>$batch_no,
									"order_date"=>$order_date,
									"stock_sts"=>"C",
									"supplier_id"=>0,
									"stock_aprd_date"=>$stock_aprd_date,
									"payment_type"=>$payment_type,
									"cheque_no"=>($cheque_no)? $cheque_no:'',
									"cheque_date"=>($cheque_date)? $cheque_date:'',
									"bank_name"=>($bank_name)? $bank_name:'',
									"bank_branch"=>($bank_branch)? $bank_branch:'',
									"trns_remark"=>($trns_remark)? $trns_remark:'',
									"narration"=>$narration
								);
								if($post_id>0 && $available_qty>=$post_qty){
									$this->SqlModel->insertRecord("tbl_demo_stock",$data);
									$model->WarehouseTransaction($warehouse_id,$post_id,0,"Dr","TRF",$AR_PD['post_title'],$post_dp_price,$tax_age,$post_qty,
									$post_amount,"","",$order_no,$batch_no,$order_date);
									
								}
							endforeach;
							set_message("success","Successfully added a new stock");
							redirect_page("stock","demoview",array("order_no"=>_e($order_no)));
						}
					
				}else{
					set_message("warning","Unable to add stock , please try again");
					redirect_page("stock","demoview",array());
				}
			break;
			
			case "DELETE":
				$order_no = (_d($form_data['order_no'])>0)? _d($form_data['order_no']):_d($segment['order_no']);
				if($order_no!=''){
					//$QR_SEL = "SELECT tsm.* FROM tbl_demo_stock AS tds WHERE tds.order_no LIKE '".$order_no."' ORDER BY tds.demo_id ASC";
					$QR_SEL = "SELECT tds.* FROM tbl_demo_stock AS tds WHERE tds.order_no LIKE '".$order_no."' ORDER BY tds.demo_id ASC";
					$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
					
					$this->SqlModel->deleteRecord("tbl_demo_stock",array("order_no"=>$AR_SEL['order_no']));
					set_message("success","Successfully delete demo stock");
					redirect_page("stock","demotransaction",array());
				}
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/stock/demoentry',$data);
	}
	
	public function demotransaction(){
		$this->load->view(ADMIN_FOLDER.'/stock/demotransaction',$data);
	}
	
	public function stocktransaction(){
		$this->load->view(ADMIN_FOLDER.'/stock/stocktransaction',$data);
	}
	
	public function stocktransactioncompany(){
		$this->load->view(ADMIN_FOLDER.'/stock/stocktransactioncompany',$data);
	}
	
	public function stockdetail(){
		$this->load->view(ADMIN_FOLDER.'/stock/stockdetail',$data);
	}
	
	public function demoview(){
		$this->load->view(ADMIN_FOLDER.'/stock/demoview',$data);
	}
	
	public function stockdetailcompany(){
		$this->load->view(ADMIN_FOLDER.'/stock/stockdetailcompany',$data);
	}
	
	public function stocktransfer(){
		$this->load->view(ADMIN_FOLDER.'/stock/stocktransfer',$data);
	}
	
	public function stockreport(){
		$this->load->view(ADMIN_FOLDER.'/stock/stockreport',$data);
	}
	
	public function stockreportcompany(){
		$this->load->view(ADMIN_FOLDER.'/stock/stockreportcompany',$data);
	}
	
 	public function accesspanel(){
		die("Page is under construction");
		exit;
	}
	
	/*
	public function shoppetocompany(){
		$this->load->view(ADMIN_FOLDER.'/stock/shoppetocompany',$data);
	}
	*/
	
	public function stockreverse(){
		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$order_no = (_d($form_data['order_no']))? _d($form_data['order_no']):_d($segment['order_no']);
		$today_date = $date_upd = getLocalTime();
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitOrderUpdate']==1 && $this->input->post()!=''){
					
					$post_id_all = array_unique(array_filter($form_data['post_id']));
					$total_products = count($post_id_all);
					if($order_no!=''){
						if($total_products>0){
							$Ctrl=0;
							foreach($post_id_all as  $key=>$post_id):
								$AR_POST = $model->getStockMaster($order_no,$post_id);
								$post_id= $AR_POST['post_id'];
								$post_pv = $AR_POST['post_pv'];
								$post_qty = FCrtRplc($form_data['post_qty'][$key]);
								if($post_qty>0 && $post_id>0){
									$post_price = FCrtRplc($AR_POST['post_price']);
									$post_amount = ($post_price*$post_qty);
									
									$data = array("post_qty"=>$post_qty,
										"post_amount"=>$post_amount
									);
									$this->SqlModel->updateRecord("tbl_stock_master",$data,array("order_no"=>$order_no,"post_id"=>$post_id));
									
 									$data_trns = array("trans_qty"=>$post_qty,
										"net_amount"=>$post_amount
									);
									$this->SqlModel->updateRecord("tbl_stock_ledger",$data_trns,array("ref_no"=>$order_no,"post_id"=>$post_id));
									
									$data_ware = array("qty"=>$post_qty,
										"total"=>$post_amount
									);
									$this->SqlModel->updateRecord("tbl_warehouse_trns",$data_ware,array("ref_no"=>$order_no,"post_id"=>$post_id));
									$Ctrl++;
								}
							endforeach;
							if($Ctrl>0){
								set_message("success","Your order returned successfully");
								redirect_page("stock","stockreverse",array("order_no"=>_e($order_no)));
							}else{
								set_message("warning","Unable to update order, please enter valid qty");
								redirect_page("stock","stockreverse",array("order_no"=>_e($order_no)));
							}
						}else{
							set_message("warning","Unable to update order, please try again");
							redirect_page("stock","stockreverse",array("order_no"=>_e($order_no)));
						}
					}else{
						set_message("warning","Unable to update order, please try again");
						redirect_page("stock","stockreverse",array("order_no"=>_e($order_no)));
					}
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/stock/stockreverse',$data);
	}
	
}
?>