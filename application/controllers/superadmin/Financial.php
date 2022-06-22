<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Financial extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	public function paymenthistory(){
		$this->load->view(ADMIN_FOLDER.'/financial/paymenthistory',$data);
	}
	
	public function addtransaction(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$today_date = getLocalTime();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$wallet_trns_id = ($form_data['wallet_trns_id'])? $form_data['wallet_trns_id']:$segment['wallet_trns_id'];
		$member_id_all =($form_data['member_id']);
		$member_id_array = array_unique(array_filter(explode(",",$member_id_all)));
		
		$NEFT_FEE = 0;
		$DEPOSITE_FEE = 0;
		$trns_date = InsertDate($form_data['trns_date']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitTransaction']==1 && $this->input->post()!=''){
					$wallet_id = ($form_data['wallet_id']>0)? $form_data['wallet_id']:0;
					$initial_amount = FCrtRplc($form_data['initial_amount']);
					$trns_remark = FCrtRplc($form_data['trns_remark']);
					$trns_type = FCrtRplc($form_data['trns_type']);
					$from_member_id = $model->getFirstId();
					
					
					$payment_type = FCrtRplc($form_data['payment_type']);
					$cheque_no = FCrtRplc($form_data['cheque_no']);
					$cheque_date = FCrtRplc($form_data['cheque_date']);
					$bank_name = FCrtRplc($form_data['bank_name']);
					$bank_branch = FCrtRplc($form_data['bank_branch']);
					
					if($trns_type=="Cr"){
						$trns_for = "DPT";
						$withdraw_fee = $initial_amount*$NEFT_FEE/100; 
						$total_charge = $withdraw_fee;
					}else{
						$trns_for = "WTD";
						$deposit_fee = $initial_amount*$DEPOSITE_FEE/100; 
						$total_charge = $deposit_fee;
					}
					$trns_amount = $initial_amount-$total_charge;	
					
					if(is_array($member_id_array) && $member_id_all!=''){
						if(is_numeric($trns_amount) && $trns_amount>0 && $trns_type!=''){
							$ctrl_count = 0;
								foreach($member_id_array as $key=>$member_id):
									if($member_id>0){
										$trans_ref_no = UniqueId("TRNS_NO");
											
										$data = array("wallet_id"=>$wallet_id,
											"trans_no"=>$trans_ref_no,
											"from_member_id"=>$from_member_id,
											"to_member_id"=>$member_id,
											"initial_amount"=>$initial_amount,
											"withdraw_fee"=>($withdraw_fee)? $withdraw_fee:0,
											"deposit_fee"=>($deposit_fee)? $deposit_fee:0,
											"trns_amount"=>$trns_amount,
											"trns_remark"=>$trns_remark,
											"trns_type"=>$trns_type,
											"trns_for"=>$trns_for,
											"trns_status"=>"C",
											"draw_type"=>'ADMIN',
											"payment_type"=>$payment_type,
											"cheque_no"=>($cheque_no)? $cheque_no:'',
											"cheque_date"=>($cheque_date)? $cheque_date:'',
											"bank_name"=>($bank_name)? $bank_name:'',
											"bank_branch"=>($bank_branch)? $bank_branch:'',
											"trns_date"=>$trns_date
										);
										$trans_no = UniqueId("TRNS_NO");
										$this->SqlModel->insertRecord("tbl_fund_transfer",$data);
										$model->wallet_transaction($wallet_id,$member_id,$trns_type,$trns_amount,$trns_remark,$trns_date,$trans_no,
										array("trns_for"=>$trns_for,"trans_ref_no"=>$trans_ref_no));
										$ctrl_count++;
									}
								endforeach;
							if($ctrl_count>0){
								set_message("success","You have successfully transfer fund to user");
								redirect_page("financial","addtransaction",array("error"=>"success"));		
							}else{
								set_message("warning","Invalid  member selection");		
								redirect_page("financial","addtransaction",array());
							}
						}else{
							set_message("warning","Invalid  amount");		
							redirect_page("financial","addtransaction",array());
						}
					}else{
						set_message("warning","Unable to process your request");		
						redirect_page("financial","addtransaction",array());
					}
				}
			break;
			case "DELETE":
				if($wallet_trns_id>0){
					$this->SqlModel->deleteRecord("tbl_fund_transfer",array("transfer_id"=>$transfer_id));
					set_message("success","You have successfully deleted record");	
				}
				redirect_page("financial","addtransaction",array()); exit;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/financial/addtransaction',$data);
	}
	
	
	public function viewtransactions(){
		$this->load->view(ADMIN_FOLDER.'/financial/viewtransactions',$data);
	}
	
	public function alltransaction(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$today_date = getLocalTime();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = (_d($form_data['action_request']))? _d($form_data['action_request']):_d($segment['action_request']);
		$transfer_id = (_d($form_data['transfer_id']))? _d($form_data['transfer_id']):_d($segment['transfer_id']);
		switch($action_request){
			case "DELETE":
				if($transfer_id>0){
					$QR_TRF = "SELECT tft.* FROM tbl_fund_transfer AS tft WHERE tft.transfer_id='".$transfer_id."'";
					$AR_TRF = $this->SqlModel->runQuery($QR_TRF,true);
					if($AR_TRF['trans_no']!=''){
						$trans_no = $AR_TRF['trans_no'];
						$this->SqlModel->deleteRecord("tbl_fund_transfer",array("transfer_id"=>$transfer_id));
						$this->SqlModel->deleteRecord("tbl_wallet_trns",array("trans_ref_no"=>$trans_no));
						set_message("success","You have successfully deleted record");	
						redirect_page("financial","alltransaction",array()); exit;
					}else{
						set_message("warning","Unable to  delete record");	
						redirect_page("financial","alltransaction",array()); exit;
					}
				}else{
					set_message("warning","Invalid action , please login again  to  delete record");	
					redirect_page("financial","alltransaction",array()); exit;
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/financial/alltransaction',$data);
	}
	

	
	public function viewmembertransactions(){
		$this->load->view(ADMIN_FOLDER.'/financial/viewmembertransactions',$data);
	}
	
	
	public function withdrawals(){
		$this->load->view(ADMIN_FOLDER.'/financial/withdrawals',$data);
	}
	
	public function transactions(){
		$this->load->view(ADMIN_FOLDER.'/financial/transactions',$data);
	}
	
	public function investincome(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(ADMIN_FOLDER.'/financial/investincome',$data);
	}
	
	function memberwallet(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = InsertDate(getLocalTime());
		$this->load->view(ADMIN_FOLDER.'/financial/memberwallet',$data);
	}
	
	function agentwallet(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = InsertDate(getLocalTime());
		$this->load->view(ADMIN_FOLDER.'/financial/agentwallet',$data);
	}
	
}
?>