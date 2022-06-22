<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class setting extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isFranchiseLoggedIn()){
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
	 
	public function profile(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$franchisee_id = $this->session->userdata('fran_id');
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitFranchisee']==1 && $this->input->post()!=''){
					$name = FCrtRplc($form_data['name']);
					$email = FCrtRplc($form_data['email']);
					
					$mobile = FCrtRplc($form_data['mobile']);
					$address = FCrtRplc($form_data['address']);
					$pincode = FCrtRplc($form_data['pincode']);
					$city = FCrtRplc($form_data['city']);
						
					$data = array("name"=>$name,
						"email"=>$email,
						"mobile"=>$mobile,
						"address"=>$address,
						"pincode"=>$pincode,
						"city"=>$city
					);
	
					if($model->checkCount("tbl_franchisee","franchisee_id",$franchisee_id)>0){
						$this->SqlModel->updateRecord("tbl_franchisee",$data,array("franchisee_id"=>$franchisee_id));
						set_message("success","You have successfully updated your profile details");
						redirect_franchise("setting","profile",array());					
					}else{
						set_message("warning","Unable to update your profile details");
						redirect_franchise("setting","profile",array());
					}
				}
			break;
		}
		
		$QR_FRAN = "SELECT tf.* FROM  tbl_franchisee AS tf WHERE tf.franchisee_id='".$franchisee_id."'";
		$AR_DT = $this->SqlModel->runQuery($QR_FRAN,true);
		$data['ROW'] = $AR_DT;
        $this->load->view(FRANCHISE_FOLDER."/setting/profile", $data);
	}
	
	public function accountprofile(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$franchisee_id = $this->session->userdata('fran_id');
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submit-bank']==1 && $this->input->post()!=''){
					$bank_holder = FCrtRplc($form_data['bank_holder']);
					$bank_name = FCrtRplc($form_data['bank_name']);
					
					$bank_account = FCrtRplc($form_data['bank_account']);
					$bank_ifc = FCrtRplc($form_data['bank_ifc']);
					$bank_branch = FCrtRplc($form_data['bank_branch']);
					
					$data_set = array("franchisee_id"=>$franchisee_id,
						"bank_holder"=>$bank_holder,
						"bank_name"=>$bank_name,
						"bank_account"=>$bank_account,
						"bank_ifc"=>$bank_ifc,
						"bank_branch"=>$bank_branch
					);
	
					if($model->checkCount("tbl_franchisee_bank","franchisee_id",$franchisee_id)>0){
						$this->SqlModel->updateRecord("tbl_franchisee_bank",$data_set,array("franchisee_id"=>$franchisee_id));
						set_message("success","You have successfully updated your bank account details");
						redirect_franchise("setting","accountprofile",array());					
					}else{
						$this->SqlModel->insertRecord("tbl_franchisee_bank",$data_set);
						set_message("success","You have successfully updated your bank account details");
						redirect_franchise("setting","accountprofile",array());
					}
				}
			break;
		}
		
		$AR_DT = $model->getFranchiseeDetail($franchisee_id);
		$data['ROW'] = $AR_DT;
        $this->load->view(FRANCHISE_FOLDER."/setting/accountprofile", $data);
	}
	
	public function password(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$franchisee_id = $this->session->userdata('fran_id');
		
		
		if($form_data['submitPassword']==1 && $this->input->post()!=''){
			$old_password = FCrtRplc($form_data['old_password']);
			$user_password = FCrtRplc($form_data['user_password']);
			$confirm_user_password = FCrtRplc($form_data['confirm_user_password']);	
			if($old_password!=$user_password){
				if($model->checkOldPasswordFranchise($franchisee_id,$old_password)>0){
					$data = array("password"=>$user_password);
					$this->SqlModel->updateRecord("tbl_franchisee",$data,array("franchisee_id"=>$franchisee_id));
					set_message("success","Password changed successfully");
					redirect_franchise("setting","password",""); 
				}else{
					set_message("warning","Invalid old password");
					redirect_franchise("setting","password",""); 
				}
			}else{
				set_message("warning","New password must be different form old-password");
				redirect_franchise("setting","password",""); 
			}
		}
		
		$QR_FRAN = "SELECT tf.* FROM  tbl_franchisee AS tf WHERE tf.franchisee_id='".$franchisee_id."'";
		$AR_DT = $this->SqlModel->runQuery($QR_FRAN,true);
		$data['ROW'] = $AR_DT;
		$this->load->view(FRANCHISE_FOLDER."/setting/password", $data);
	}

	
}
