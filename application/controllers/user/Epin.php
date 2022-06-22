<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Epin extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isMemberLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 

	
	public function usedpin(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(MEMBER_FOLDER.'/epin/usedpin',$data);
	}
	
	public function unusedpin(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(MEMBER_FOLDER.'/epin/unusedpin',$data);
	}
	
	public function pinrequest(){		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(MEMBER_FOLDER.'/epin/pinrequest',$data);
	}
	
	
	public function newrequest(){		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$today_date = getLocalTime();
		
		$AR_MEM = $model->getMember($member_id);
		
		$trns_password = FCrtRplc($form_data['trns_password']);	
		
		$payment_type = FCrtRplc($form_data['payment_type']);	
		$type_id = FCrtRplc($form_data['type_id']);	
		$no_pin = FCrtRplc($form_data['no_pin']);	
		$payment_sts = FCrtRplc($form_data['payment_sts']);	
		
		$AR_PACK = $model->getPinType($type_id);
		$pin_price = $AR_PACK['pin_price'];
		$net_price = $AR_PACK['net_price'];
		
		$net_amount = $net_price*$no_pin;
		
		
		$wallet_id = FCrtRplc($form_data['wallet_id']);	
		$LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
		
		if($form_data['submitPinRequest']==1 && $this->input->post()!=''){			
			switch($payment_type):
				case "ONLINE":
					$AR_SEND['deposit_amount']=$net_amount;
					$AR_SEND['type_id']=$type_id;
					$AR_SEND['no_pin']=$no_pin;
					$AR_SEND['user_note']=$payment_sts;
					$data['POST']=$AR_SEND;
					
					set_message("warning","Currently Online Transaction is Not Available");
					redirect_member("epin","newrequest","");
					#$this->load->view("paytm/lib/config_paytm");
					#$this->load->view("paytm/lib/encdec_paytm");
					#$this->load->view(MEMBER_FOLDER.'/payment/onlinebuypin',$data);
					
				break;
				case "EWALLET":
					if($LDGR['net_balance']>=$net_amount && $LDGR['net_balance']>0 && $net_amount>0){
						if($model->checkTrnsPassword($member_id,$trns_password)>0){
							$new_value = json_encode($form_data);
							$sms_otp = $model->sendEpinRequestSMS($AR_MEM['mobile_number']);
								$data = array("member_id"=>$member_id,
									"new_value"=>$new_value,
									"sms_otp"=>$sms_otp,
									"sms_type"=>"EPIN",
									"mobile_number"=>$AR_MEM['mobile_number']
								);
							$request_id = $this->SqlModel->insertRecord("tbl_sms_otp",$data);
							set_message("success","Please verify OTP from your registered mobile number");
							redirect_member("epin","newrequest",array("request_id"=>_e($request_id))); 
						}else{
							set_message("warning","Invalid transaction password, please try again");
							redirect_member("epin","newrequest","");
						}
					}else{
						set_message("warning","It seem  you have low balance to buy E-pin");
						redirect_member("epin","newrequest","");
					}
				break;
			endswitch;
			
		}
		if($form_data['verifyOTP']!='' && $this->input->post()!=''){
				$request_id = _d($form_data['request_id']);
				$sms_otp = FCrtRplc($form_data['sms_otp']);
				$AR_TYPE = $model->verifySMSOTP($request_id,$sms_otp);
				
				if($AR_TYPE['request_id']>0){
			
					$NEW_VAL = json_decode($AR_TYPE['new_value'],true);
					$net_amount = FCrtRplc($NEW_VAL['net_amount']);
					
					$wallet_type = FCrtRplc($NEW_VAL['wallet_type']);
					
					$wallet_id = FCrtRplc($NEW_VAL['wallet_id']);
					$LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
					$narration = "Pin purchase through cash wallet";
					
					if($LDGR['net_balance']>=$net_amount && $LDGR['net_balance']>0 && $net_amount>0){
						
						$paid_by = FCrtRplc($NEW_VAL['paid_by']);
						$type_id = FCrtRplc($NEW_VAL['type_id']);
						$no_pin = FCrtRplc($NEW_VAL['no_pin']);
						$bank_id = FCrtRplc($NEW_VAL['bank_id']);
						$payment_date = InsertDate($today_date);
						$payment_sts = FCrtRplc($NEW_VAL['payment_sts']);
						$pin_price = FCrtRplc($NEW_VAL['pin_price']);
						
						$request_date = $trns_date = InsertDate($today_date);
						$trans_ref_no = UniqueId("TRNS_NO");
						$AR_PACK =  $model->getPinType($type_id);
						
					
						$data_request = array("type_id"=>$type_id,
							"trans_no"=>$trans_ref_no,
							"no_pin"=>$no_pin,
							"net_amount"=>$net_amount,
							"member_id"=>$member_id,
							
							"bank_id"=>($bank_id>0)? $bank_id:0,
							"payment_date"=>InsertDate($today_date),
							"payment_sts"=>getTool($narration,''),
							"ip_address"=>$_SERVER['REMOTE_ADDR'],
							"request_date"=>$today_date,
							"assign_sts"=>"Y"
						);
						$data_mstr = array("type_id"=>$type_id,
							"no_pin"=>$no_pin,
							"prod_pv"=>$AR_PACK['prod_pv'],
							"pin_price"=>$pin_price,
							"trans_no"=>$trans_ref_no,
							
							"net_amount"=>$net_amount,
							"member_id"=>$member_id,
							"bank_id"=>$bank_id,
							"payment_date"=>$payment_date,
							"payment_sts"=>getTool($payment_sts,''),
							"ip_address"=>$_SERVER['REMOTE_ADDR'],
							"generate_by"=>0
						);
						if($no_pin>0){
							$payment_sts = "PIN PURCHASE [".$AR_MEM['user_id']."]";
							$request_id = $this->SqlModel->insertRecord("tbl_pin_request",$data_request);
							$mstr_id = $this->SqlModel->insertRecord("tbl_pinsmaster",$data_mstr);
							$model->generatePinDetail($mstr_id);
							
							$model->wallet_transaction($wallet_id,$member_id,"Dr",$net_amount,$payment_sts,$trns_date,$trans_ref_no,
									array("trns_for"=>"PIP","trans_ref_no"=>$trans_ref_no));
							
							$this->SqlModel->updateRecord("tbl_sms_otp",array("otp_sts"=>"1"),array("request_id"=>$AR_TYPE['request_id']));
							set_message("success","You have successfully generated your e-pin");
							redirect_member("epin","pinrequest",array("request_id"=>_e($request_id)));
						}else{
							set_message("warning","Unable to send E-pin request");
							redirect_member("epin","newrequest","");
						}			
					}else{
						set_message("warning","It seem  you have low balance to send E-pin request");
						redirect_member("epin","newrequest","");
					}
				}else{
					set_message("warning","Invalid opt  code, please enter valid otp");
					redirect_member("epin","newrequest",array("request_id"=>_e($request_id)));
				}
		}
		
		$AR_ATTR['page_title'] = "E-Pin New Request";
		$data['AR_ATTR'] = $AR_ATTR;
		$this->load->view(MEMBER_FOLDER.'/epin/newrequest',$data);
	}
	
	public function pinsend(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$AR_ATTR['page_title'] = "E-Pin Send";
		$data['AR_ATTR'] = $AR_ATTR;
		$this->load->view(MEMBER_FOLDER.'/epin/pinsend',$data);
	}
	
	public function pinreceive(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$AR_ATTR['page_title'] = "E-Pin Received";
		$data['AR_ATTR'] = $AR_ATTR;
		$this->load->view(MEMBER_FOLDER.'/epin/pinreceive',$data);
	}
	
	public function transferepin(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$today_date = getLocalTime();
		
		$AR_MEM = $model->getMember($member_id);
		$type_id = FCrtRplc($form_data['type_id']);

		$user_id = FCrtRplc($form_data['user_id']);
		$trns_password = FCrtRplc($form_data['trns_password']);	
		$to_member_id = $model->getMemberId($user_id);
		
		
		if($form_data['submitPinTransfer']!='' && $this->input->post()!=''){
			if($model->checkTrnsPassword($member_id,$trns_password)>0){
				if($to_member_id>0 && $member_id!=$to_member_id){
					if(count($form_data['pin_id'])>0){
						$new_value = json_encode($form_data);
						$sms_otp = $model->sendEpinTransferSMS($AR_MEM['mobile_number']);
							$data = array("member_id"=>$member_id,
								"new_value"=>$new_value,
								"sms_otp"=>$sms_otp,
								"sms_type"=>"EPIN_TRF",
								"mobile_number"=>$AR_MEM['mobile_number']
							);
						$request_id = $this->SqlModel->insertRecord(prefix."tbl_sms_otp",$data);
						set_message("success","Please verify OTP from your registered mobile number");
						redirect_member("epin","transferepin",array("request_id"=>_e($request_id))); 
					}else{
						set_message("warning","Invalid e-pin key,  please enter valid key");
						redirect_member("epin","transferepin","");	
					}
			  }else{
			  	set_message("warning","Invalid  User Id,  please enter valid User Id");
				redirect_member("epin","transferepin","");	
			  }
			}else{
				set_message("warning","Invalid transaction password, please try again");
				redirect_member("epin","transferepin","");
			}
			
		}
		if($form_data['verifyOTP']!='' && $this->input->post()!=''){
				$request_id = _d($form_data['request_id']);
				$sms_otp = FCrtRplc($form_data['sms_otp']);
				$AR_TYPE = $model->verifySMSOTP($request_id,$sms_otp);
				if($AR_TYPE['request_id']>0){
					$NEW_VAL = json_decode($AR_TYPE['new_value'],true);
					if($NEW_VAL['user_id']!='' && $NEW_VAL['pin_id']>0){
						
						$user_id = FCrtRplc($NEW_VAL['user_id']);
						$pin_id_array = array_filter(array_unique($NEW_VAL['pin_id']));
						$to_member_id = $model->getMemberId($user_id);
						
						if($to_member_id>0 && $member_id>0){
							$i=0;
							foreach($pin_id_array as $pin_id):
							$data = array("from_member_id"=>$member_id,
										"to_member_id"=>$to_member_id,
										"member_id"=>$to_member_id,
										"transfer_date"=>$today_date);
							$this->SqlModel->updateRecord(prefix."tbl_pinsdetails",$data,array("pin_id"=>$pin_id,"member_id"=>$member_id));
							$i++;
							endforeach;
							if($i>0){
								set_message("success","Your pin transfer successfully");
								redirect_member("epin","transferepin","");
							}else{
								set_message("warning","Unable to find your selected pin");
								redirect_member("epin","transferepin","");
							}
						}else{
							set_message("warning","It seem User Id is inavlid , please try again");
							redirect_member("epin","transferepin","");
						}
						
				    }else{
					set_message("warning","It seem User Id is inavlid , please try again");
					redirect_member("epin","transferepin",array("request_id"=>_e($request_id)));
				   }
				}else{
					set_message("warning","Invalid opt  code, please enter valid otp");
					redirect_member("epin","transferepin",array("request_id"=>_e($request_id)));
				}
		}
		$AR_ATTR['page_title'] = "Transfer E-Pin";
		$data['AR_ATTR'] = $AR_ATTR;
		$this->load->view(MEMBER_FOLDER.'/epin/transferepin',$data);
	}
	
	public function pintransfer(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$AR_ATTR['page_title'] = "E-Pin Transfer";
		$data['AR_ATTR'] = $AR_ATTR;
		$this->load->view(MEMBER_FOLDER.'/epin/pintransfer',$data);
	}
	
	
	
	
	
}
