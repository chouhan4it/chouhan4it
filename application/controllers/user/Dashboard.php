<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isMemberLoggedIn()){
			 redirect_front("account","login","");		
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
	 
	public function index(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$QR_CHECK = "SELECT tm.*, CONCAT_WS('',tm.mobile_code,tm.member_mobile) AS mobile_number, tmsp.first_name AS spsr_first_name, 
		tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,	tree.nlevel, tree.left_right, tree.nleft, tree.nright,
		tree.date_join, ts.* FROM tbl_members AS tm	
		 LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
		 LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id
		 LEFT JOIN tbl_subscription AS ts ON (tm.member_id=ts.member_id)
		 WHERE tm.member_id='".$member_id."' 
		 ORDER BY tm.member_id ASC";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true); 
		
		if($form_data['submitMemberSaveTrnsPassword']==1 && $this->input->post()!=''){
			$old_password = FCrtRplc($form_data['current_tr_password']);
			$trns_password = FCrtRplc($form_data['new_tr_password']);
			$confirm_trns_password = FCrtRplc($form_data['new_tr_password_again']);	
			if($old_password!=$trns_password){
				if($model->checkTrnsPassword($member_id,$old_password)>0){
					$sms_otp = $model->sendTransactionSMS($fetchRow['mobile_number']);
					$data = array("member_id"=>$member_id,
						"new_value"=>$trns_password,
						"sms_otp"=>$sms_otp,
						"sms_type"=>"TRNS",
						"mobile_number"=>$fetchRow['mobile_number']
					);
					$request_id = $this->SqlModel->insertRecord("tbl_sms_otp",$data);
					set_message("success","Please verify otp from your registered mobile number");
					redirect_member("dashboard","verifyotp",array("request_id"=>_e($request_id))); 
				}else{
					set_message("warning","Invalid transaction password");
					redirect_member("dashboard","",""); 
				}
			}else{
				set_message("warning","New password must be different form old-password");
				redirect_member("dashboard","",""); 
			}
		}		
		$data['ROW']=$fetchRow;
		$this->load->view(MEMBER_FOLDER.'/dashboard',$data);
	}
	
	public function verifyotp(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$member_id = $this->session->userdata('mem_id');
		$request_id = ($form_data['request_id'])? _d($form_data['request_id']):_d($segment['request_id']);
		
		if($form_data['updateOTP']!='' && $this->input->post()!=''){
			$sms_otp = FCrtRplc($form_data['sms_otp']);
			$AR_TYPE = $model->verifySMSOTP($request_id,$sms_otp);
			
			$new_value = $AR_TYPE['new_value'];
			switch($AR_TYPE['sms_type']){
				case "TRNS":
					if($AR_TYPE['request_id']>0 && $AR_TYPE['mobile_number']!=''){
						$data = array("trns_password"=>$new_value);
						$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
						set_message("success","Transaction password updated successfully");
						redirect(MEMBER_PATH);
					}else{
						static_message("warning","Invalid OTP , please try again");
						redirect_member("dashboard","verifyotp",array("request_id"=>_e($request_id),"error"=>"failed"));
					}
				break;
				case "EMAIL":
					if($AR_TYPE['request_id']>0 && $AR_TYPE['mobile_number']!=''){
						$data = array("member_email"=>$new_value);
						$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
						set_message("success","You have successfully changed email address");
						redirect(MEMBER_PATH);
					}else{
						static_message("warning","Invalid OTP , please try again");
						redirect_member("dashboard","verifyotp",array("request_id"=>_e($request_id),"error"=>"failed"));
					}
				break;
				default:
					static_message("warning","Invalid OTP , please try again");
					redirect_member("dashboard","verifyotp",array("request_id"=>_e($request_id),"error"=>"failed"));
				break;
			}
		}
		$this->load->view(MEMBER_FOLDER.'/dashboard',$data);
	}
	
	public function logout(){
		
		$this->session->unset_userdata('mem_id');
		 $this->session->unset_userdata('user_id');
		 
		 set_message("success","You have successfully logout");
		 redirect(MEMBER_PATH);
	}
	
	
}
