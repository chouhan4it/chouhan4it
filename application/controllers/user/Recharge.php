<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recharge extends MY_Controller {
	
	
	
	public function form(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = InsertDate(getLocalTime());
		$member_id = $this->session->userdata('mem_id');
		$client_id = "8808881856";
		
		$wallet_id_1 = $model->getWallet(WALLET1);
		
		$AR_LDGR = $model->getCurrentBalance($member_id,$wallet_id_1,"","");
		
		$net_balance = $AR_LDGR['net_balance'];
		
		if($form_data['submit-mobile-recharge']!='' && $this->input->post()!=''){
			$mobile_number = FCrtRplc($form_data['mobile_number']);
			$operator_id = FCrtRplc($form_data['operator_id']);
			$recharge_amount = FCrtRplc($form_data['recharge_amount']);
			$recharge_type = FCrtRplc($form_data['recharge_type']);
			
			if(strlen($mobile_number)==10){
				if($operator_id!=''){
					if($recharge_amount>0){
					  	if($net_balance>=$recharge_amount){
						
							$number = $mobile_number;
							$amount = $recharge_amount;
							$provider_id = $operator_id;
							$client_id = time();
							$api_token = ""; 
							$ch = curl_init();
							$url = "https://www.pay2all.in/web-api/paynow?api_token=$api_token&number=$number&provider_id=$provider_id&amount=$amount&client_id=$client_id";
							$response = file_get_contents($url);
							$AR_RTN = json_decode($response,true);
							
							
							$pay_id = $AR_RTN['payid'];
							$operator_ref = $AR_RTN['operator_ref'];
							$status = $AR_RTN['status'];
							$message = $AR_RTN['message'];
							$txstatus_desc = $AR_RTN['txstatus_desc'];
							
							$trans_no = UniqueId("TRNS_NO");
							
							$data_set = array("member_id"=>$member_id,
								"pay_id"=>getTool($pay_id,''),
								"trans_no"=>$trans_no,
								"operator_ref"=>getTool($operator_ref,''),
								"status"=>getTool($status,''),
								"message"=>getTool($message,''),
								"txstatus_desc"=>getTool($txstatus_desc,''),
								"mobile_number"=>getTool($mobile_number,''),
								"operator_id"=>getTool($operator_id,''),
								"amount"=>getTool($amount,0),
								"recharge_type"=>$recharge_type,
								"response"=>getTool($response,'')
							);
							
							$this->SqlModel->insertRecord("tbl_recharge_history",$data_set);
							if($AR_RTN['status']!="failure"){
								$trns_remark = "MOBILE RECHARGE [".$operator_ref."]";
								$model->wallet_transaction($wallet_id_1,$member_id,"Dr",$amount,$trns_remark,$today_date,$trans_no,
									array("trns_for"=>"MOB","trans_ref_no"=>$trans_no));
								set_message("success","Recharge process successfully, waiting for operator confirmation");
								redirect_member("recharge","form","");
							}else{
								set_message("warning","Unable to process your request, please try again");
								redirect_member("recharge","form","");
							}
						}else{
							set_message("warning","It seem that you have low balance to recharge");
							redirect_member("recharge","form","");
						}						
					}else{
						set_message("warning","Please enter valid amount");
						redirect_member("recharge","form","");
					}
				}else{
					set_message("warning","Please select mobile operator");
					redirect_member("recharge","form","");
				}
			}else{
				set_message("warning","Invalid mobile, please enter valid mobile number");
				redirect_member("recharge","form","");
			}
		}
		
		if($form_data['submit-dth-recharge']!='' && $this->input->post()!=''){
			$card_number = FCrtRplc($form_data['card_number']);
			$operator_id = FCrtRplc($form_data['operator_id']);
			$recharge_amount = FCrtRplc($form_data['recharge_amount']);
			$recharge_type = FCrtRplc($form_data['recharge_type']);
			
			if(strlen($card_number)>0){
				if($operator_id!=''){
					if($recharge_amount>0){
					  	if($net_balance>=$recharge_amount){
						
							$number = $card_number;
							$amount = $recharge_amount;
							$provider_id = $operator_id;
							$client_id = time();
							$api_token = ""; 
							$ch = curl_init();
							$url = "https://www.pay2all.in/web-api/paynow?api_token=$api_token&number=$number&provider_id=$provider_id&amount=$amount&client_id=$client_id";
							
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$response = curl_exec($ch);
							curl_close($ch);
							$AR_RTN = json_decode($response,true);
							
							$pay_id = $AR_RTN['payid'];
							$operator_ref = $AR_RTN['operator_ref'];
							$status = $AR_RTN['status'];
							$message = $AR_RTN['message'];
							$txstatus_desc = $AR_RTN['txstatus_desc'];
							
							$trans_no = UniqueId("TRNS_NO");
							
							$data_set = array("member_id"=>$member_id,
								"pay_id"=>getTool($pay_id,''),
								"trans_no"=>$trans_no,
								"operator_ref"=>getTool($operator_ref,''),
								"status"=>getTool($status,''),
								"message"=>getTool($message,''),
								"txstatus_desc"=>getTool($txstatus_desc,''),
								"mobile_number"=>getTool($mobile_number,''),
								"operator_id"=>getTool($operator_id,''),
								"amount"=>getTool($amount,0),
								"recharge_type"=>$recharge_type,
								"response"=>getTool($response,'')
							);
							$this->SqlModel->insertRecord("tbl_recharge_history",$data_set);
								
							if($AR_RTN['status']!="failure"){
								$trns_remark = "DTH RECHARGE [".$operator_ref."]";
								$model->wallet_transaction($wallet_id_1,$member_id,"Dr",$amount,$trns_remark,$today_date,$trans_no,
									array("trns_for"=>"DTH","trans_ref_no"=>$trans_no));
								set_message("success","Recharge process successfully, waiting for operator confirmation");
								redirect_member("recharge","form","");
							}else{
								set_message("warning","Unable to process your request, please try again");
								redirect_member("recharge","form","");
							}
						}else{
							set_message("warning","It seem that you have low balance to recharge");
							redirect_member("recharge","form","");
						}						
					}else{
						set_message("warning","Please enter valid amount");
						redirect_member("recharge","form","");
					}
				}else{
					set_message("warning","Please select dth operator");
					redirect_member("recharge","form","");
				}
			}else{
				set_message("warning","Invalid mobile, please enter valid card number");
				redirect_member("recharge","form","");
			}
		}
		
		if($form_data['submit-dcd-recharge']!='' && $this->input->post()!=''){
			$card_number = FCrtRplc($form_data['card_number']);
			$operator_id = FCrtRplc($form_data['operator_id']);
			$recharge_amount = FCrtRplc($form_data['recharge_amount']);
			$recharge_type = FCrtRplc($form_data['recharge_type']);
			
			if(strlen($card_number)>0){
				if($operator_id!=''){
					if($recharge_amount>0){
					  	if($net_balance>=$recharge_amount){
						
							$number = $card_number;
							$amount = $recharge_amount;
							$provider_id = $operator_id;
							$client_id = time();
							$api_token = ""; 
							$ch = curl_init();
							$url = "https://www.pay2all.in/web-api/paynow?api_token=$api_token&number=$number&provider_id=$provider_id&amount=$amount&client_id=$client_id";
							
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$response = curl_exec($ch);
							curl_close($ch);
							$AR_RTN = json_decode($response,true);
							
							$pay_id = $AR_RTN['payid'];
							$operator_ref = $AR_RTN['operator_ref'];
							$status = $AR_RTN['status'];
							$message = $AR_RTN['message'];
							$txstatus_desc = $AR_RTN['txstatus_desc'];
							
							$trans_no = UniqueId("TRNS_NO");
							
							$data_set = array("member_id"=>$member_id,
								"pay_id"=>getTool($pay_id,''),
								"trans_no"=>$trans_no,
								"operator_ref"=>getTool($operator_ref,''),
								"status"=>getTool($status,''),
								"message"=>getTool($message,''),
								"txstatus_desc"=>getTool($txstatus_desc,''),
								"mobile_number"=>getTool($mobile_number,''),
								"operator_id"=>getTool($operator_id,''),
								"amount"=>getTool($amount,0),
								"recharge_type"=>$recharge_type,
								"response"=>getTool($response,'')
							);
							
							$this->SqlModel->insertRecord("tbl_recharge_history",$data_set);
								
							if($AR_RTN['status']!="failure"){
								$trns_remark = "DCD RECHARGE [".$operator_ref."]";
								$model->wallet_transaction($wallet_id_1,$member_id,"Dr",$amount,$trns_remark,$today_date,$trans_no,
									array("trns_for"=>"DCD","trans_ref_no"=>$trans_no));
								set_message("success","Recharge process successfully, waiting for operator confirmation");
								redirect_member("recharge","form","");
							}else{
								set_message("warning","Unable to process your request, please try again");
								redirect_member("recharge","form","");
							}
						}else{
							set_message("warning","It seem that you have low balance to recharge");
							redirect_member("recharge","form","");
						}						
					}else{
						set_message("warning","Please enter valid amount");
						redirect_member("recharge","form","");
					}
				}else{
					set_message("warning","Please select datacard operator");
					redirect_member("recharge","form","");
				}
			}else{
				set_message("warning","Invalid customer number, please enter valid customer number");
				redirect_member("recharge","form","");
			}
		}
		
		if($form_data['submit-pop-recharge']!='' && $this->input->post()!=''){
			$mobile_number = FCrtRplc($form_data['mobile_number']);
			$operator_id = FCrtRplc($form_data['operator_id']);
			$recharge_amount = FCrtRplc($form_data['recharge_amount']);
			$recharge_type = FCrtRplc($form_data['recharge_type']);
			
			if(strlen($mobile_number)==10){
				if($operator_id!=''){
					if($recharge_amount>0){
					  	if($net_balance>=$recharge_amount){
						
							$number = $mobile_number;
							$amount = $recharge_amount;
							$provider_id = $operator_id;
							$client_id = time();
							$api_token = ""; 
							$ch = curl_init();
							$url = "https://www.pay2all.in/web-api/paynow?api_token=$api_token&number=$number&provider_id=$provider_id&amount=$amount&client_id=$client_id";
							$response = file_get_contents($url);
							$AR_RTN = json_decode($response,true);
							
							
							$pay_id = $AR_RTN['payid'];
							$operator_ref = $AR_RTN['operator_ref'];
							$status = $AR_RTN['status'];
							$message = $AR_RTN['message'];
							$txstatus_desc = $AR_RTN['txstatus_desc'];
							
							$trans_no = UniqueId("TRNS_NO");
							
							$data_set = array("member_id"=>$member_id,
								"pay_id"=>getTool($pay_id,''),
								"trans_no"=>$trans_no,
								"operator_ref"=>getTool($operator_ref,''),
								"status"=>getTool($status,''),
								"message"=>getTool($message,''),
								"txstatus_desc"=>getTool($txstatus_desc,''),
								"mobile_number"=>getTool($mobile_number,''),
								"operator_id"=>getTool($operator_id,''),
								"amount"=>getTool($amount,0),
								"recharge_type"=>$recharge_type,
								"response"=>getTool($response,'')
							);
							
							$this->SqlModel->insertRecord("tbl_recharge_history",$data_set);
							if($AR_RTN['status']!="failure"){
								$trns_remark = "POP RECHARGE [".$operator_ref."]";
								$model->wallet_transaction($wallet_id_1,$member_id,"Dr",$amount,$trns_remark,$today_date,$trans_no,
									array("trns_for"=>"POP","trans_ref_no"=>$trans_no));
								set_message("success","Recharge process successfully, waiting for operator confirmation");
								redirect_member("recharge","form","");
							}else{
								set_message("warning","Unable to process your request, please try again");
								redirect_member("recharge","form","");
							}
						}else{
							set_message("warning","It seem that you have low balance to recharge");
							redirect_member("recharge","form","");
						}						
					}else{
						set_message("warning","Please enter valid amount");
						redirect_member("recharge","form","");
					}
				}else{
					set_message("warning","Please select mobile operator");
					redirect_member("recharge","form","");
				}
			}else{
				set_message("warning","Invalid postpaid number, please enter valid postpaid number");
				redirect_member("recharge","form","");
			}
		}
		
		$this->load->view(MEMBER_FOLDER.'/recharge/form',$data);
	}
	
	public function history(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(MEMBER_FOLDER.'/recharge/history',$data);
	}
	
	
	public function moneytransferlist(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(MEMBER_FOLDER.'/recharge/moneytransferlist',$data);
	}

	
}
