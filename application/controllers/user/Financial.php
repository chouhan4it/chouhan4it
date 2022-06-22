<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Financial extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isMemberLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}
	
	public function subscription(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(MEMBER_FOLDER.'/financial/subscription',$data);
	}
	
	
	public function deposit(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(MEMBER_FOLDER.'/financial/deposit',$data);
	}
	
	public function wallet(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(MEMBER_FOLDER.'/financial/wallet',$data);
	}
	
	public function transferfund(){		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$member_id = $this->session->userdata('mem_id');
		$wallet_id = FCrtRplc($form_data['wallet_id']);
		
		$CONFIG_MIN_FUND_TRANSFER = $model->getValue("CONFIG_MIN_FUND_TRANSFER");
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$AR_MEM = $model->getMember($member_id);
		$trns_password = FCrtRplc($form_data['trns_password']);
		
		
		
		if($form_data['submitFundRequest']!='' && $this->input->post()!=''){
				
				$LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
				
				$to_member_id = $model->getMemberId($form_data['user_id']);
							
				
				$initial_amount = FCrtRplc($form_data['initial_amount']);
				$trns_remark = FCrtRplc($form_data['trns_remark']);
				$trns_type = FCrtRplc($form_data['trns_type']);
				$trns_date = InsertDate($today_date);
				
				$deposit_fee = 0;
				$withdraw_fee = 0;				
				$process_fee = 0;
				$admin_charge = 0;
				
				$total_charge = ($admin_charge+$withdraw_fee+$deposit_fee+$process_fee);
				$trns_amount = ($initial_amount-$total_charge);
				
				if($to_member_id>0 && $wallet_id>0){
				if($initial_amount>=$CONFIG_MIN_FUND_TRANSFER){
					if($LDGR['net_balance']>=$trns_amount && $LDGR['net_balance']>0 && $trns_amount>0){
						if($model->checkTrnsPassword($member_id,$trns_password)>0){
							if($to_member_id!=$member_id){
								$AR_MAP['wallet_id'] = $wallet_id;
								$AR_MAP['from_member_id'] = $member_id;
								$AR_MAP['to_member_id'] = $to_member_id;
								$AR_MAP['initial_amount'] = $initial_amount;
								$AR_MAP['deposit_fee'] = $deposit_fee;
								$AR_MAP['withdraw_fee'] = $withdraw_fee;
								$AR_MAP['process_fee'] = $process_fee;
								$AR_MAP['admin_charge'] = $admin_charge;
								$AR_MAP['trns_amount'] = $trns_amount;
								
								$AR_MAP['trns_remark'] = $trns_remark;
								$AR_MAP['trns_status'] = "C";
								$AR_MAP['status_up_date'] = $today_date;
								$AR_MAP['trns_for'] = "TRF";
								$AR_MAP['draw_type'] = "TRANSFER";
							
								$new_value = json_encode($AR_MAP);
								$sms_otp = $model->sendFundtransferRequestSMS($AR_MEM['mobile_number'],$initial_amount);
									$data = array("member_id"=>$member_id,
										"new_value"=>$new_value,
										"sms_otp"=>$sms_otp,
										"sms_type"=>"FUNDTRANSFER",
										"mobile_number"=>$AR_MEM['mobile_number']
									);
								$request_id = $this->SqlModel->insertRecord("tbl_sms_otp",$data);
								set_message("success","Please verify OTP from your registered email address");
								redirect_member("financial","transferfund",array("request_id"=>_e($request_id))); 
							}else{
								set_message("warning","You cannot send fund to your own id");
								redirect_member("financial","transferfund","");
							}
						}else{
							set_message("warning","Invalid transaction password, please try again");
							redirect_member("financial","transferfund","");
						}
					}else{
						set_message("warning","It seem  you have low balance to transfer fund");
						redirect_member("financial","transferfund","");
					}
				}else{
					set_message("warning","Enter the amount over and above  ".$CONFIG_MIN_FUND_TRANSFER." ".CURRENCY);
					redirect_member("financial","transferfund","");
				}
			}else{
				set_message("warning","Invalid details , please enter valid");
				redirect_member("financial","transferfund","");
			}
		}
		if($form_data['verifyOTP']!='' && $this->input->post()!=''){
				$request_id = _d($form_data['request_id']);
				$sms_otp = FCrtRplc($form_data['sms_otp']);
				$AR_TYPE = $model->verifySMSOTP($request_id,$sms_otp);
				
				$NEW_VAL = json_decode($AR_TYPE['new_value'],true);
				
				
				$wallet_id = FCrtRplc($NEW_VAL['wallet_id']);
				$trns_amount = FCrtRplc($NEW_VAL['trns_amount']);
				$from_member_id = FCrtRplc($NEW_VAL['from_member_id']);
				$from_user_id = $model->getMemberUserId($from_member_id);
				
				$to_member_id = FCrtRplc($NEW_VAL['to_member_id']);
				$to_user_id = $model->getMemberUserId($to_member_id);
				
				$initial_amount = FCrtRplc($NEW_VAL['initial_amount']);
				
				$withdraw_fee = FCrtRplc($NEW_VAL['withdraw_fee']);
				$process_fee = FCrtRplc($NEW_VAL['process_fee']);
				$admin_charge = FCrtRplc($NEW_VAL['admin_charge']);
				
				$trns_remark_to = $trns_remark = "FUND TRANSFER TO [".$to_user_id."]";
				$trns_remark_from = "FUND RECEIVED FROM[".$from_user_id."]";
				$trns_status = FCrtRplc($NEW_VAL['trns_status']);
				$status_up_date = ($NEW_VAL['status_up_date']);
				
				$trns_for = ($NEW_VAL['trns_for']);
				$draw_type = ($NEW_VAL['draw_type']);
				
				
				$trans_no = UniqueId("TRNS_NO");
				
				$LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
				
				if($AR_TYPE['request_id']>0){
					if($LDGR['net_balance']>=$trns_amount && $LDGR['net_balance']>0 && $trns_amount>0){
						
						$data = array("wallet_id"=>($wallet_id>0)? $wallet_id:1,
							"trans_no"=>$trans_no,
							"from_member_id"=>$from_member_id,
							"to_member_id"=>$to_member_id,
							"initial_amount"=>$initial_amount,
							"withdraw_fee"=>($withdraw_fee)? $withdraw_fee:0,
							"deposit_fee"=>($deposite_fee)? $deposite_fee:0,

							"trns_amount"=>$trns_amount,
							"trns_remark"=>$trns_remark,
							"trns_type"=>"Tr",
							"trns_for"=>$trns_for,
							"trns_status"=>"C",
							"draw_type"=>$draw_type,
							"trns_date"=>$today_date
						);
						
						if(is_numeric($trns_amount) && $trns_amount>0){
							if($member_id>0){
									$this->SqlModel->insertRecord(prefix."tbl_fund_transfer",$data);
									
									$model->wallet_transaction($wallet_id,$from_member_id,"Dr",$trns_amount,$trns_remark_to,$today_date,$trans_no,
									array("trns_for"=>"FTR","trans_ref_no"=>$trans_no));
									
									$model->wallet_transaction($wallet_id,$to_member_id,"Cr",$trns_amount,$trns_remark_from,$today_date,$trans_no,
									array("trns_for"=>"FTR","trans_ref_no"=>$trans_no));
									
									$this->SqlModel->updateRecord(prefix."tbl_sms_otp",array("otp_sts"=>"1"),array("request_id"=>$AR_TYPE['request_id']));
									#Send_Mail(array("member_id"=>$from_member_id,"amount"=>$trns_amount),"FUND_SENDER");
									#Send_Mail(array("member_id"=>$to_member_id,"amount"=>$trns_amount),"FUND_RECIVER");
									set_message("success","Your transaction processed successfull");
									
									redirect_member("financial","wallet",array("error"=>"success"));		
							}else{
								set_message("warning","Invalid member id");		
								redirect_member("financial","transferfund",array("request_id"=>_e($request_id)));
							}
						}else{
							set_message("warning","Invalid  amount");		
							redirect_member("financial","transferfund",array("request_id"=>_e($request_id)));
						}
					}else{
						set_message("warning","It seem  you have low balance to transfer your fund");
						redirect_member("financial","transferfund",array("request_id"=>_e($request_id)));
					}
			}else{
				set_message("warning","Invalid OTP, please enter valid OTP");
				redirect_member("financial","transferfund",array("request_id"=>_e($request_id)));
			}
		}
		
		
		$AR_ATTR['page_title'] = "Transfer fund";
		$data['AR_ATTR'] = $AR_ATTR;
		$this->load->view(MEMBER_FOLDER.'/financial/transferfund',$data);
	}
	
	public function withdraw(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$AR_MEM = $model->getMember($member_id);
		$draw_amount = FCrtRplc($form_data['draw_amount']);
		$wallet_id = $model->getWallet("Payout Wallet");
		$LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
		
		$NEFT_FEE = $model->getValue("NEFT_FEE");
		$DEPOSITE_FEE = $model->getValue("DEPOSITE_FEE");
		$CONFIG_WITHDRAWL = "MANUAL";
		
		
		if($form_data['requestWithdraw']!='' && $this->input->post()!=''){
			
			if($member_id>0 && is_numeric($draw_amount)){
				
				if($draw_amount>0){
					
					$WITDRAW_FEE = $draw_amount*$NEFT_FEE/100; 
					
					
					$CONFIG_ADMIN_CHARGE_PERCENT =  ($draw_amount*$CONFIG_ADMIN_CHARGE/100); 
					$total_charge = ($WITDRAW_FEE);
					
					$trns_amount = ($draw_amount-$total_charge);
					$trns_date = InsertDate(getLocalTime());
					$trans_no = UniqueId("TRNS_NO");
					if($trns_amount>0){
						$trns_remark = "Withdrawal  Request from".$AR_MEM['user_id'];
						if($draw_amount<=$LDGR['net_balance']){
							if($CONFIG_WITHDRAWL=="MANUAL"){
								$data = array("to_member_id"=>$member_id,
									"from_member_id"=>$model->getFirstId(),
									"trans_no"=>$trans_no,
									"wallet_id"=>$wallet_id,
									"initial_amount"=>$draw_amount,
									"withdraw_fee"=>($WITDRAW_FEE)? $WITDRAW_FEE:0,
									"deposit_fee"=>($DEPOSITE_FEE)? $DEPOSITE_FEE:0,
									"trns_amount"=>$trns_amount,
									"trns_status"=>"P",
									"trns_type"=>"Dr",
									"trns_date"=>$trns_date,
									"trns_for"=>"WTD",
									"draw_type"=>"MANUAL",
									"trns_remark"=>$trns_remark
								);
								$withdraw_id = $this->SqlModel->insertRecord("tbl_fund_transfer",$data);
								$model->wallet_transaction($wallet_id,$member_id,"Dr",$draw_amount,$trns_remark,$trns_date,$trans_no,
								array("trns_for"=>"WITHDRAW"));
								set_message("success","You have successfully request for withdrawal $StrMsg");
								redirect_member("financial","withdraw",array("error"=>"success"));
							}else{
								set_message("warning","Unable to process your request , please try again");
								redirect_member("financial","withdraw","");	
							}	
						}else{
							set_message("warning","Invalid amount, please check your balance");
							redirect_member("financial","withdraw","");	
						}
					}else{
						set_message("warning","Invalid amount");
						redirect_member("financial","withdraw","");	
					}
				}else{
					set_message("warning","Minimum withdrawal is ".$CONFIG_MIN_WITHDRAWL."");
					redirect_member("financial","withdraw","");	
				}
			}else{
				set_message("warning","Invalid amount");
				redirect_member("financial","withdraw","");	
			}
		}
		
		$this->load->view(MEMBER_FOLDER.'/financial/withdraw',$data);
	}
	
	
	public function paymenthistory(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(MEMBER_FOLDER.'/financial/paymenthistory',$data);
	}
	
}
?>