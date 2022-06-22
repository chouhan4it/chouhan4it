<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membership extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	
	public function addplan(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$package_id = ($form_data['package_id'])? $form_data['package_id']:$segment['package_id'];
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitPackage']==1 && $this->input->post()!=''){
					$package_name = FCrtRplc($form_data['package_name']);
					$package_price = FCrtRplc($form_data['package_price']);
					$package_ccl = FCrtRplc($form_data['package_ccl']);
					$package_credit = $package_price;
					$monthly_rent = FCrtRplc($form_data['monthly_rent']);
					$yearly_rent = FCrtRplc($form_data['yearly_rent']);
					$int_ccl_month = FCrtRplc($form_data['int_ccl_month']);
					$max_ccl = FCrtRplc($form_data['max_ccl']);
					$max_transaction = FCrtRplc($form_data['max_transaction']);
					$max_limit_receive = FCrtRplc($form_data['max_limit_receive']);
					$max_trns_day = FCrtRplc($form_data['max_trns_day']);
					$package_type = FCrtRplc($form_data['package_type']);
					
					$max_agent = FCrtRplc($form_data['max_agent']);
					$max_member = FCrtRplc($form_data['max_member']);
					
					$data = array("package_name"=>$package_name,
						"package_price"=>$package_price,
						"package_credit"=>$package_credit,
						"package_ccl"=>$package_ccl,
						"monthly_rent"=>$monthly_rent,
						"yearly_rent"=>$yearly_rent,
						"int_ccl_month"=>$int_ccl_month,
						"max_ccl"=>$max_ccl,
						"max_transaction"=>$max_transaction,
						"max_limit_receive"=>$max_limit_receive,
						"max_trns_day"=>$max_trns_day,
						"package_type"=>$package_type,
						"max_agent"=>($max_agent>0)? $max_agent:0,
						"max_member"=>($max_member>0)? $max_member:0,
						"date_update"=>getLocalTime(),
						"status"=>1,
						"delete_sts"=>1
					);
					if($model->checkCount("tbl_package","package_id",$package_id)>0){
						$this->SqlModel->updateRecord("tbl_package",$data,array("package_id"=>$package_id));
						set_message("success","You have successfully updated a plan details");
						redirect_page("membership","addplan",array("package_id"=>$package_id,"action_request"=>"EDIT"));					
					}else{
						if($model->checkCount("tbl_package","package_name",$package_name)==0){
							$this->SqlModel->insertRecord("tbl_package",$data);
							set_message("success","Successfully added new plan");
							redirect_page("membership","addplan",array());
						}else{
							set_message("warning","Plan name already used, please enter unique plan name");
							redirect_page("membership","addplan",array());					
						}
					}
				}
			break;
			case "STATUS":
				if($package_id>0){
					$status = ($segment['status']=="0")? "1":"0";
					$data = array("status"=>$status);
					$this->SqlModel->updateRecord("tbl_package",$data,array("package_id"=>$package_id));
					set_message("success","Status change successfully");
					redirect_page("membership","viewplan",array()); exit;
				}
			break;
			case "DELETE":
				if($model->checkCount("tbl_members","package_id",$package_id)==0){
					$this->db->query("UPDATE tbl_package  SET delete_sts='0' WHERE package_id='$package_id'");
					set_message("success","You have successfully deleted record");
				}else{
					set_message("warning","Can't delete, this package is assign to member");
				}
				redirect_page("membership","viewplan",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_package WHERE package_id='$package_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/membership/addplan',$data);
	}
	
	public function viewplan(){
		$this->load->view(ADMIN_FOLDER.'/membership/viewplan',$data);
	}
	
	public function manage()
	{
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$package_id = ($form_data['package_id'])? $form_data['package_id']:$segment['package_id'];
		if($form_data['submitPackage']==1 && $this->input->post()!=''){
			$package_id_array = $form_data['package_id'];
			$package_name_array = $form_data['package_name'];
			foreach($package_id_array as $key=>$package_id):	
				$package_name = $package_name_array[$key];
				$this->db->query("UPDATE tbl_package SET package_name='$package_name' WHERE package_id='".$package_id."'");
			endforeach;
			set_message("success","Successfully updated changes");
			redirect_page("membership","manage",array());
		}
		
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitNewPackage']==1 && $this->input->post()!=''){
					$package_name = FCrtRplc($form_data['package_name']);
					$data = array("package_name"=>$package_name,
					"date_update"=>getLocalTime(),
					"status"=>1,
					"delete_sts"=>1,
					"package_price"=>0
					);
					if($model->checkCount("tbl_package","package_name",$package_name)==0){
						$this->SqlModel->insertRecord("tbl_package",$data);
						set_message("success","Successfully added new membership package");
						redirect_page("membership","manage",array());
					}else{
						set_message("success","Package name already used, please enter unique package name");
						redirect_page("membership","manage",array());					
					}
				}
			break;
			case "DELETE":
				if($model->checkCount("tbl_members","package_id",$package_id)==0){
					$this->db->query("UPDATE tbl_package  SET delete_sts='0' WHERE package_id='$package_id'");
					set_message("success","You have successfully deleted record");
				}else{
					set_message("warning","Can't delete, this package is assign to member");
				}
				redirect_page("membership","manage",array()); exit;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/membership/manage',$data);
		
	}
	
	public function signupsetting(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		if($form_data['submitPackagePrice']==1 && $this->input->post()!=''){
			$model->setConfig("CONFIG_NEW_MEMBER_STS",getSwitch($form_data['CONFIG_NEW_MEMBER_STS']));
			$model->setConfig("CONFIG_SPONSOR_ID",$form_data['CONFIG_SPONSOR_ID']);
			
			$package_id_array = $form_data['package_id'];
			$package_price_array = $form_data['package_price'];
			foreach($package_id_array as $key=>$package_id):	
				$package_price = $package_price_array[$key];
				$this->db->query("UPDATE tbl_package SET package_price='$package_price' WHERE package_id='".$package_id."'");
			endforeach;
			set_message("success","Successfully updated changes");
			redirect_page("membership","signupsetting",array());
		}
		
		$this->load->view(ADMIN_FOLDER.'/membership/signupsetting',$data);
	}
	
	public function referralsetup(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		if($form_data['submitReferral']==1 && $this->input->post()!=''){
			$model->deleteReferralSetup();
			$referral_array = $form_data['referral'];
			foreach($referral_array as $key=>$field_val):
				$form_field_explode  =  explode("_",$key);
				$field_first = $form_field_explode['0'];
				$field_second = $form_field_explode['1'];
				$package_id_from = $model->getPackageId($field_first);
				$package_id_to = $model->getPackageId($field_second);
				$cmsn_amount = $field_val;
				$data = array("package_id_from"=>$package_id_from,
					"package_id_to"=>$package_id_to,
					"cmsn_amount"=>$cmsn_amount
				);
				$this->SqlModel->insertRecord("tbl_setup_mem_referal_cmsn",$data);
			endforeach;
			set_message("success","Successfully updated changes");
			redirect_page("membership","referralsetup",array());
		}
		$this->load->view(ADMIN_FOLDER.'/membership/referralsetup',$data);
	}
	
	public function creditsetup(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		if($form_data['submitCredit']==1 && $this->input->post()!=''){			
			$package_id_array = $form_data['package_id'];
			$package_credit_array = $form_data['package_credit'];
			foreach($package_id_array as $key=>$package_id):	
				$package_credit = $package_credit_array[$key];
				$this->db->query("UPDATE tbl_package SET package_credit='$package_credit' WHERE package_id='".$package_id."'");
			endforeach;
			set_message("success","Successfully updated changes");
			redirect_page("membership","creditsetup",array());
		}
		$this->load->view(ADMIN_FOLDER.'/membership/creditsetup',$data);
	}
	
	

	
	
}
