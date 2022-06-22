<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(FRANCHISE_PATH);		
		}
	}
	
	
	public function ledger(){
		$this->load->view(FRANCHISE_FOLDER.'/account/ledger',$data);
	}
	
	public function statement(){
		$this->load->view(FRANCHISE_FOLDER.'/account/statement',$data);
	}
	
	public function fundtransfer(){
	$model = new OperationModel();
	$form_data = $this->input->post();
	$today_date = getLocalTime();
	$segment = $this->uri->uri_to_assoc(2);
	$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		
		if($form_data['submitTransfer']==1 && $this->input->post()!=''){
			$from_franchisee_id = $this->session->userdata('fran_id');
			$send_franchisee = $model->getFranchiseeUsername($from_franchisee_id);
			$AR_BAL = $model->getBalanceFranchise($from_franchisee_id,"","");
			$current_balance = $AR_BAL['net_balance'];

			$received_franchisee = FCrtRplc($form_data['user_name']);
			$trns_amount = $initial_amount = FCrtRplc($form_data['trns_amount']);
			$trns_remark = FCrtRplc($form_data['trns_remark']);
			$trns_date = InsertDate($today_date);
			$trans_no = UniqueId("TRNS_NO");
			
			
			$to_franchisee_id = $model->getFranchiseeId($received_franchisee);
			if($to_franchisee_id>0){
				if($current_balance>$trns_amount){
					$trns_for_rcv = "Fund Transfer Received ".$received_franchisee;
					$trns_for_send = "Fund Transfer Send ".$send_franchisee;
					$data = array("trans_no"=>$trans_no,
						"from_franchisee_id"=>$from_franchisee_id,
						"to_franchisee_id"=>$to_franchisee_id,
						"initial_amount"=>$initial_amount,
						"trns_amount"=>$trns_amount,
						"trns_remark"=>$trns_remark,
						"trns_type"=>"Dr",
						"trns_for"=>$trns_for_rcv,
						"draw_type"=>"TRF",
						"trns_date"=>$trns_date
					);
					
					if(is_numeric($trns_amount) && $trns_amount>0){
						if($from_franchisee_id>0){
							if($model->checkCount("tbl_fund_transfer","transfer_id",$transfer_id)>0){
								$this->SqlModel->updateRecord("tbl_fund_transfer",$data,array("transfer_id"=>$transfer_id));
								set_message("success","You have successfully updated a  transaction detail");
								redirect_franchise("account","fundtransfer",array());					
							}else{
								$this->SqlModel->insertRecord("tbl_fund_transfer",$data);
								$model->wallet_transaction_franchisee($to_franchisee_id,"Cr",$trns_amount,$trns_remark,$trns_date,$trans_no,array("trns_for"=>$trns_for_rcv));
								$model->wallet_transaction_franchisee($from_franchisee_id,"Dr",$trns_amount,$trns_remark,$trns_date,$trans_no,array("trns_for"=>$trns_for_send));
								set_message("success","You have successfully added  a new  transaction");
								redirect_franchise("account","fundtransfer",array("error"=>"success"));					
							}
						}else{
							set_message("warning","Invalid Process, Please enter valid Id");		
							redirect_franchise("account","fundtransfer",array());
						}
					}else{
						set_message("warning","Invalid  amount");		
						redirect_franchise("account","fundtransfer",array());
					}
				}else{
					set_message("warning","Unable to transfer fund, please check your balance");		
					redirect_franchise("account","fundtransfer",array());
				}
			}else{
				set_message("warning","Invalid Franchise username");		
				redirect_franchise("account","fundtransfer",array());
			}
		}
		$this->load->view(FRANCHISE_FOLDER.'/account/fundtransfer',$data);
	}
	
	public function addmember(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = (_d($form_data['member_id'])>0)? _d($form_data['member_id']):_d($segment['member_id']);
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$NO_ACCOUNT_MOBILE = $model->getValue("NO_ACCOUNT_MOBILE");
		$login_ip = $_SERVER['REMOTE_ADDR'];
		if($form_data['submit-step-1']==1 && $this->input->post()!=''){
			$first_name = FCrtRplc($form_data['first_name']);
			$last_name = FCrtRplc($form_data['last_name']);
			$full_name = $first_name." ".$last_name;
			$email_address = FCrtRplc($form_data['email_address']);
			$member_mobile = FCrtRplc($form_data['member_mobile']);
			
			$user_password = FCrtRplc($form_data['user_password']);
			
			$pan_no = FCrtRplc($form_data['pan_no']);
			$aadhar_no = FCrtRplc($form_data['aadhar_no']);
			$date_join = InsertDate($today_date);
			
			$flddDOB_Y = FCrtRplc($form_data['flddDOB_Y']);
			$flddDOB_M = FCrtRplc($form_data['flddDOB_M']);
			$flddDOB_D = FCrtRplc($form_data['flddDOB_D']);
			$flddDOB = $flddDOB_Y."-".$flddDOB_M."-".$flddDOB_D;
			$date_of_birth = InsertDate($flddDOB);
			
			
			$user_id = $user_name = $model->generateUserId();
			
			if($member_id==''){
				if($model->checkCount("tbl_members","user_id",$user_id)==0){
						$Ctrl = 0;
						$data = array("first_name"=>$first_name,
							"last_name"=>$last_name,
							"full_name"=>getTool($full_name,''),
							"user_id"=>$user_id,
							"user_name"=>$user_name,
							"user_password"=>$user_password,
							"member_email"=>$email_address,
							"member_mobile"=>$member_mobile,
							
							"sponsor_id"=>getTool($sponsor_id,0),
							"spil_id"=>getTool($spil_id,0),
							"left_right"=>getTool($left_right,''),
							
							"date_join"=>$date_join,
							"date_of_birth"=>$date_of_birth,
							
							"pan_no"=>getTool($pan_no,''),
							"aadhar_no"=>getTool($aadhar_no,''),
							"pan_status"=>"N",
							"status"=>"Y",
							"last_login"=>$date_join,
							"login_ip"=>$login_ip,
							"block_sts"=>"N",
							"sms_sts"=>"N",
							"update_time"=>$current_date
							
						);		
						if($Ctrl==0){
							$member_id = $this->SqlModel->insertRecord("tbl_members",$data);	
							$model->welcomeMemberSms($member_mobile,$full_name,$user_id,"********");
							set_message("success","Successfully register an member");
							redirect_franchise("account","addmembertwo",array("member_id"=>_e($member_id)));
						}else{
							set_message("warning","Failed , unable to process your request , please check sponsor id");
							redirect_franchise("account","addmember",array());
						}
				}else{
					set_message("warning","Unable to register , please try again");
				}
			}elseif($member_id>0){
				$data = array("first_name"=>$first_name,
					"last_name"=>$last_name,
					"full_name"=>getTool($full_name,''),
					
					"member_email"=>$email_address,
					"member_mobile"=>$member_mobile,
									
					"date_of_birth"=>$date_of_birth,
					
					"pan_no"=>$pan_no,
					"pan_status"=>"N",
					"status"=>"Y",
					"last_login"=>$current_date,
					"login_ip"=>$_SERVER['REMOTE_ADDR'],
					"block_sts"=>"N",
					"sms_sts"=>"N",
					"update_time"=>$current_date
				
				);	
				
				$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));						
				set_message("success","Successfully updated  member details");
				redirect_franchise("account","addmembertwo",array("member_id"=>_e($member_id)));	
			}
			
		}
		
		$AR_MEM = $model->getMember($member_id,"DFT");
		$data['ROW']=$AR_MEM;
		$this->load->view(FRANCHISE_FOLDER.'/account/addmember',$data);
	}
	
	public  function addmembertwo(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
				
		$member_id = (_d($form_data['member_id'])>0)? _d($form_data['member_id']):_d($segment['member_id']);
		
		$model->checkMemberId($member_id);
		if($form_data['submit-step-2']==1 && $this->input->post()!=''){
			$type_id = FcrtRplc($form_data['type_id']);
			
			$pin_no = FCrtRplc($form_data['pin_no']);
			$pin_key = FCrtRplc($form_data['pin_key']);
			$AR_PIN = $model->getPinDetail($pin_no,$pin_key);
			
			$AR_PACK = $model->getPinType($type_id);
			$pin_name = $AR_PACK['pin_name'];
			$prod_pv = $AR_PACK['prod_pv'];
			$pin_price = $AR_PACK['pin_price'];
			$gst_price = $AR_PACK['gst_price'];
			$total_price = $AR_PACK['total_price'];
			$direct_bonus = $AR_PACK['direct_bonus'];
			$pin_type = $AR_PACK['pin_type'];
			
			$order_no = UniqueId("ORDER_NO");
			$trns_remark = "PACKAGE UPGRADE[".$order_no."]";
			
			if($pin_no!='' && $pin_key!=''){
				if($AR_PIN['block_sts']=="N"){
					if($AR_PIN['pin_sts']=="N" && $AR_PIN['use_member_id']==0){
						
						$left_right = FCrtRplc($form_data['left_right']);
						$sponsor_id = $spil_id =  $model->getMemberId($form_data['spr_user_id']);
												
						if($sponsor_id>0){
							$Ctrl +=  ($spil_id>0)? 0:1;
							if($Ctrl==0){
								$tree_data = array("member_id"=>$member_id,
									"sponsor_id"=>$sponsor_id,
									"spil_id"=>$spil_id,
									"left_right"=>getTool($left_right,''),
									"nlevel"=>0,
									"nleft"=>0,
									"nright"=>0,
									"date_join"=>$today_date
								);
								if($model->checkCount("tbl_mem_tree","member_id",$member_id)==0){
									$this->SqlModel->insertRecord("tbl_mem_tree",$tree_data);
									$model->updateTree($spil_id,$member_id);					
								}
										
								
								$data_sub = array("order_no"=>$order_no,
									"member_id"=>$member_id,
									"type_id"=>$type_id,
									"pin_name"=>getTool($pin_name,''),
									
									"pin_price"=>getTool($pin_price,0),
									"gst_price"=>getTool($gst_price,0),
									"total_price"=>getTool($total_price,0),
									
									"direct_bonus"=>getTool($direct_bonus,0),					
									"prod_pv"=>getTool($prod_pv,0),
									"net_amount"=>getTool($total_price,0),
									"pin_type"=>getTool($pin_type,'DFT'),
									"payment_type"=>"EPINF",
									"date_from"=>$current_date
								);
								
								$subcription_id = $this->SqlModel->insertRecord("tbl_subscription",$data_sub);
								$model->setSubscriptionPost($subcription_id,$type_id);
								$this->SqlModel->updateRecord("tbl_members",array("subcription_id"=>$subcription_id),array("member_id"=>$member_id));
								$model->updatePinDetail($AR_PIN['pin_id'],$member_id);
								$model->setReferralIncome($member_id,$subcription_id);
								$model->point_transaction($member_id,"Cr","PKG",$prod_pv,$prod_pv,$pin_price,$pin_price,$order_no,$current_date);
								set_message("success","You have successfully upgraded your package");
								redirect_franchise("account","addmemberthree",array("member_id"=>_e($member_id)));
							}else{
								set_message("warning","Unable to register , place is not available");
							}
						}else{
							set_message("warning","Invalid Sponsor Id");
						}
					}else{
						set_message("warning","This pin is already used by other member");
					}
				}else{
					set_message("warning","Sorry this pin is blocked, please contact our support team");
				}
			}else{
				set_message("warning","Please enter pin-no & pin-key details");
			}				
		}
		
		$AR_MEM = $model->getMember($member_id,"DFT");
		$data['ROW']=$AR_MEM;
		$this->load->view(FRANCHISE_FOLDER.'/account/addmember-two',$data);
	}
	
	
	public function addmemberthree(){
		$model = new OperationModel();
		$current_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);

		$member_id = (_d($form_data['member_id'])>0)? _d($form_data['member_id']):_d($segment['member_id']);
		$model->checkMemberId($member_id);
		if($form_data['submit-step-3']==1 && $this->input->post()!=''){
			$current_address = FCrtRplc($form_data['current_address']);
			$country_code = FCrtRplc($form_data['country_code']);
			$city_name = FCrtRplc($form_data['city_name']);
			$state_name = FCrtRplc($form_data['state_name']);
			$country_name = FCrtRplc($form_data['country_name']);
			$pin_code = FCrtRplc($form_data['pin_code']);
			$land_mark = FCrtRplc($form_data['land_mark']);

			$data = array("current_address"=>$current_address,
				"country_code"=>$country_code,				
				"city_name"=>$city_name,				
				"state_name"=>$state_name,				
				"country_name"=>$country_name,				
				"pin_code"=>$pin_code,				
				"land_mark"=>$land_mark,
				"update_time"=>$current_date							
			);				
			$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
			set_message("success","Successfully update  address detail");
			redirect_franchise("account","addmemberfour",array("member_id"=>_e($member_id)));
			
		}
		
		$AR_MEM = $model->getMember($member_id,"DFT");
		$data['ROW']=$AR_MEM;
		$this->load->view(FRANCHISE_FOLDER.'/account/addmember-three',$data);
	}
	
	public function addmemberfour(){
		$model = new OperationModel();
		$current_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = (_d($form_data['member_id'])>0)? _d($form_data['member_id']):_d($segment['member_id']);
		$model->checkMemberId($member_id);
		
		if($form_data['submit-step-4']==1 && $this->input->post()!=''){
			$fldvPath = "";
			if($_FILES['avatar_name']['error']=="0"){
				$ext = explode(".",$_FILES['avatar_name']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$photo = $fldvUniqueNo.rand(100,999)."_". str_replace(" ","",rand(100,999).".".$fExtn);
				$target_path = $fldvPath."upload/member/".$photo;
				
				$AR_MEM = SelectTable("tbl_members","photo","member_id='$member_id'");
				$final_location = $fldvPath."upload/member/".$AR_MEM['photo'];
				$fldvImageArr= @getimagesize($final_location);
				if ($fldvImageArr['mime']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(move_uploaded_file($_FILES['avatar_name']['tmp_name'], $target_path)){
						$this->SqlModel->updateRecord("tbl_members",array("photo"=>$photo,"update_time"=>$current_date),array("member_id"=>$member_id));
						set_message("success","Successfully updated all detail");
						redirect(FRANCHISE_PATH);
						
					}
			}
		}
		
		$AR_MEM = $model->getMember($member_id,"DFT");
		$data['ROW']=$AR_MEM;
		$this->load->view(FRANCHISE_FOLDER.'/account/addmember-four',$data);
	}
	
}
