<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}
	
	
	public function datewiseclosingstock(){
		$this->load->view(FRANCHISE_FOLDER.'/stock/datewiseclosingstock',$data);
	}

	public function franchiseelist(){
		$this->load->view(FRANCHISE_FOLDER.'/franchisee/franchiseelist',$data);
	}
	
	public function stockentry(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
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
					
					$order_no = FCrtRplc($form_data['order_no']);
					$narration = "From franchisee: ".$franchisee_name_from." to Franchisee: ".$franchisee_name_to;
					$order_date = InsertDate($form_data['order_date']);			
					
					$stock_aprd_date = getLocalTime();
					
					$post_id_all = array_filter($form_data['post_id']);
					foreach($post_id_all as  $key=>$post_id):
						$AR_PD = $model->getPostDetail($post_id);
						$batch_no = $model->getDefaultBatch($post_id);
						$trans_no = UniqueId("TRNS_NO");
						$post_pv = $AR_PD['post_pv'];
						$post_mrp = $AR_PD['post_mrp'];
						$tax_age = $AR_PD['tax_age'];
						$post_attribute_id = FCrtRplc($form_data['post_attribute_id'][$key]);
						$post_qty = FCrtRplc($form_data['post_qty'][$key]);
						$post_price = FCrtRplc($form_data['post_price'][$key]);
						$post_dp_price = FCrtRplc($form_data['post_dp_price'][$key]);
						$post_amount = ($post_dp_price*$post_qty);
						
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
							"order_date"=>$order_date,
							"stock_sts"=>"C",
							"stock_aprd_date"=>$stock_aprd_date,
							"narration"=>$narration
						);
						if($post_id>0){
							$this->SqlModel->insertRecord("tbl_stock_master",$data);
							$model->InsertStockLedger($franchisee_id_to,$trans_no,"Cr","TRFS",$post_id,$post_attribute_id,$post_dp_price,$tax_age,$post_qty,$post_amount,
									$order_no,$order_date,$batch_no);
						}
					endforeach;
					set_message("success","Successfully added a new stock");
					redirect_franchise("stock","stockdetail",array("order_no"=>_e($order_no)));
				}else{
					set_message("warning","Unable to add stock , please try again");
					redirect_franchise("stock","stockentry",array());
				}
			break;
		}
		
		$this->load->view(FRANCHISE_FOLDER.'/stock/stockentry',$data);
	}
	
	public function stocksummary(){
		$this->load->view(FRANCHISE_FOLDER.'/stock/stocksummary',$data);
	}
	
	public function stocktransaction(){
		$this->load->view(FRANCHISE_FOLDER.'/stock/stocktransaction',$data);
	}
	
	public function stockdetail(){
		$this->load->view(FRANCHISE_FOLDER.'/stock/stockdetail',$data);
	}
	
	public function stocktransfer(){
		$this->load->view(FRANCHISE_FOLDER.'/stock/stocktransfer',$data);
	}
	
	public function stockreport(){
		$this->load->view(FRANCHISE_FOLDER.'/stock/stockreport',$data);
	}
	
	public function stockreportmonthwise(){
		$this->load->view(FRANCHISE_FOLDER.'/stock/stockreportmonthwise',$data);
	}
	
 	
	
	
		
	
}
