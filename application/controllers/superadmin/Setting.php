<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	
	public function group(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$group_id = (_d($form_data['group_id'])>0)? _d($form_data['group_id']):_d($segment['group_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitGroup']==1 && $this->input->post()!=''){
					$group_name = FCrtRplc($form_data['group_name']);
					
					$data = array("group_name"=>$group_name);
					if($model->checkCount("tbl_oprtr_grp","group_id",$group_id)>0){
						$this->SqlModel->updateRecord("tbl_oprtr_grp",$data,array("group_id"=>$group_id));
						set_message("success","You have successfully updated a  group detail");
						redirect_page("setting","grouplist",array("processor_id"=>$processor_id,"action_request"=>"EDIT"));					
					}else{
						$this->SqlModel->insertRecord("tbl_oprtr_grp",$data);
						set_message("success","You have successfully added  a new  group");
						redirect_page("setting","grouplist",array());					
					}
				}
			break;
			case "DELETE":
				if($group_id>0){
					if($model->checkCount("tbl_sys_menu_acs","group_id",$group_id)==0){
						$this->SqlModel->deleteRecord("tbl_oprtr_grp",array("group_id"=>$group_id));
						set_message("success","You have successfully deleted record");	
					}else{
						set_message("warning","Unable to delete group, group is assign to user");	
					}
					
				}
				redirect_page("setting","grouplist",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_oprtr_grp WHERE group_id='$group_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/setting/group',$data);
	}
	
	public function grouplist(){
		$this->load->view(ADMIN_FOLDER.'/setting/grouplist',$data);
	}	
	
	public function addprocessor(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$processor_id = ($form_data['processor_id'])? $form_data['processor_id']:$segment['processor_id'];
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitProcessor']==1 && $this->input->post()!=''){
					$account_id = FCrtRplc($form_data['account_id']);
					$fee_flat = FCrtRplc($form_data['fee_flat']);
					$fee_percent = FCrtRplc($form_data['fee_percent']);
					$withdraw_fee = FCrtRplc($form_data['withdraw_fee']);
					$deposit_fee = FCrtRplc($form_data['deposit_fee']);
					$payment_active	 = FCrtRplc($form_data['payment_active']);
					$withdraw_active = FCrtRplc($form_data['withdraw_active']);
					$data = array("account_id"=>$account_id,
						"fee_flat"=>$fee_flat,
						"fee_percent"=>$fee_percent,
						"deposit_fee"=>$deposit_fee,
						"withdraw_fee"=>$withdraw_fee,
						"payment_active"=>$payment_active,
						"withdraw_active"=>$withdraw_active
					);
					if($model->checkCount("tbl_payment_processor","processor_id",$processor_id)>0){
						$this->SqlModel->updateRecord("tbl_payment_processor",$data,array("processor_id"=>$processor_id));
						set_message("success","You have successfully updated a  processor detail");
						redirect_page("setting","addprocessor",array("processor_id"=>$processor_id,"action_request"=>"EDIT"));					
					}else{
						$this->SqlModel->insertRecord("tbl_payment_processor",$data);
						set_message("success","You have successfully added  a new  processor");
						redirect_page("setting","addprocessor",array());					
					}
				}
			break;
			case "DELETE":
				if($processor_id>0){
					$data = array("isDelete"=>0);
					$this->SqlModel->updateRecord("tbl_payment_processor",$data,array("processor_id"=>$processor_id));
					set_message("success","You have successfully deleted record");	
				}
				redirect_page("setting","processors",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_payment_processor WHERE processor_id='$processor_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/setting/addprocessor',$data);
	}
	
	public function processors(){
		$this->load->view(ADMIN_FOLDER.'/setting/processors',$data);
	}	
	
	
	public function administrator(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
			
		if($form_data['submitAdministrator']==1 && $this->input->post()!=''){
			
			$model->setConfig("CONFIG_REGISTER",FCrtRplc($form_data['CONFIG_REGISTER']));
			$model->setConfig("CONFIG_LOGIN",FCrtRplc($form_data['CONFIG_LOGIN']));
			
			
			$model->setConfig("CONFIG_TIME",FCrtRplc($form_data['CONFIG_TIME']));
			
			$model->setConfig("VERIFY_TIME",FCrtRplc($form_data['VERIFY_TIME']));
			$model->setConfig("POSTAL_ADDRESS",FCrtRplc($form_data['POSTAL_ADDRESS']));
			$model->setConfig("MOBILE_NO",FCrtRplc($form_data['MOBILE_NO']));
			$model->setConfig("FAX_NO",FCrtRplc($form_data['FAX_NO']));
			$model->setConfig("EMAIL_ADDRESS",FCrtRplc($form_data['EMAIL_ADDRESS']));
			
			$model->setConfig("NO_ACCOUNT_MOBILE",FCrtRplc($form_data['NO_ACCOUNT_MOBILE']));
			
			$model->setConfig("CONFIG_TDS",FCrtRplc($form_data['CONFIG_TDS']));
			$model->setConfig("CONFIG_ADMIN_CHARGE",FCrtRplc($form_data['CONFIG_ADMIN_CHARGE']));
			
			
			set_message("success","Successfully updated changes");
			redirect_page("setting","administrator",array());
		}
		
		$this->load->view(ADMIN_FOLDER.'/setting/administrator',$data);
	}
	
	public function faq(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$faq_id = ($form_data['faq_id'])? $form_data['faq_id']:_d($segment['faq_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitFAQ']==1 && $this->input->post()!=''){
					$faq_question = FCrtRplc($form_data['faq_question']);
					$faq_answer = FCrtRplc($form_data['faq_answer']);
					
					$data = array("faq_question"=>$faq_question,
						"faq_answer"=>$faq_answer,
						"faq_active"=>1
					);
					if($model->checkCount("tbl_faq","faq_id",$faq_id)>0){
						$this->SqlModel->updateRecord("tbl_faq",$data,array("faq_id"=>$faq_id));
						set_message("success","You have successfully updated a FAQ details");
						redirect_page("setting","faq",array("faq_id"=>_e($faq_id),"action_request"=>"EDIT"));					
					}else{
						$this->SqlModel->insertRecord("tbl_faq",$data);
						set_message("success","You have successfully added a FAQ detail");
						redirect_page("setting","faqlist",array());					
					}
				}
			break;
			case "DELETE":
				if($faq_id>0){
					$model->deleteTable("tbl_faq",array("faq_id"=>$faq_id));
					set_message("success","You have successfully deleted FAQ details");
				}else{
					set_message("warning","Failed , unable to delete FAQ");
				}
				redirect_page("setting","faqlist",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_faq WHERE faq_id='$faq_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/setting/faq',$data);	
	}
	
	public function faqlist(){
		$this->load->view(ADMIN_FOLDER.'/setting/faqlist',$data);	
	}
	
	public function dailyreturn(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
			
		if($form_data['submitDailyReturnForm']==1 && $this->input->post()!=''){
			$type_id_array = $form_data['type_id'];
			$daily_return_array = $form_data['daily_return'];
			$date_time = InsertDate($form_data['date_time']);
			foreach($type_id_array as $key=>$value):
				$type_id = $type_id_array[$key];
				$daily_return = $daily_return_array[$key];
				if($model->getDailyReturn($type_id,$date_time)>0){
					$this->SqlModel->updateRecord("tbl_daily_return",array("daily_return"=>$daily_return),
					array("type_id"=>$type_id,"date_time"=>$date_time));
				}else{
					$data = array("type_id"=>$type_id,
						"daily_return"=>$daily_return,
						"date_time"=>$date_time
					);
					$this->SqlModel->insertRecord("tbl_daily_return",$data);
				}
			endforeach;
			set_message("success","You have successfully set daily return of ".$date_time."");
			redirect_page("setting","dailyreturn",array());		
		}
		$this->load->view(ADMIN_FOLDER.'/setting/dailyreturn',$data);	
	}
	
	public function addrank(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$rank_id = (_d($form_data['rank_id'])>0)? _d($form_data['rank_id']):_d($segment['rank_id']);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitRank']==1 && $this->input->post()!=''){
					$rank_name = FCrtRplc($form_data['rank_name']);
					$rank_cmsn = FCrtRplc($form_data['rank_cmsn']);
					
					$data_set = array("rank_name"=>$rank_name,
						"rank_cmsn"=>$rank_cmsn);
					if($model->checkCount("tbl_rank","rank_id",$rank_id)>0){
						$this->SqlModel->updateRecord("tbl_rank",$data_set,array("rank_id"=>$rank_id));
						set_message("success","You have successfully updated a rank details");
						redirect_page("setting","viewrank","");					
					}else{
						if($model->checkCount("tbl_rank","rank_name",$rank_name)==0){
							$this->SqlModel->insertRecord("tbl_rank",$data_set);
							set_message("success","Successfully added new rank");
							redirect_page("setting","viewrank",array());
						}else{
							set_message("warning","please enter unique rank name");
							redirect_page("setting","addrank",array());					
						}
					}
				}
			break;
			case "STATUS":
				if($rank_id>0){
					$rank_sts = ($segment['rank_sts']=="0")? "1":"0";
					$data = array("rank_sts"=>$rank_sts);
					$this->SqlModel->updateRecord("tbl_rank",$data,array("rank_id"=>$rank_id));
					set_message("success","Status change successfully");
					redirect_page("setting","viewrank",array()); exit;
				}
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_rank WHERE rank_id='".$rank_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/setting/addrank',$data);
	}
	
	public function viewrank(){
		$this->load->view(ADMIN_FOLDER.'/setting/viewrank',$data);
	}
	
	
	public function returntype(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$return_id = (_d($form_data['return_id'])>0)? _d($form_data['return_id']):_d($segment['return_id']);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitReturn']==1 && $this->input->post()!=''){
					$return_name = FCrtRplc($form_data['return_name']);
					$return_rate = FCrtRplc($form_data['return_rate']);
					
					
					
					$data = array("return_name"=>$return_name,
						"return_rate"=>$return_rate
					);
					if($model->checkCount("tbl_return_setup","return_id",$return_id)>0){
						$this->SqlModel->updateRecord("tbl_return_setup",$data,array("return_id"=>$return_id));
						set_message("success","You have successfully updated a return details");
						redirect_page("setting","returnlist",array("return_id"=>$return_id,"action_request"=>"EDIT"));					
					}else{
						if($model->checkCount("tbl_return_setup","return_name",$return_name)==0){
							$this->SqlModel->insertRecord("tbl_return_setup",$data);
							set_message("success","Successfully added new return");
							redirect_page("setting","returnlist",array());
						}else{
							set_message("warning"," name already used, please enter unique return name");
							redirect_page("setting","returntype",array());					
						}
					}
				}
			break;
			case "STATUS":
				if($return_id>0){
					$return_sts = ($segment['return_sts']=="0")? "1":"0";
					$data = array("	return_sts"=>$return_sts);
									
					$this->SqlModel->updateRecord("tbl_return_setup",$data,array("return_id"=>$return_id));
					set_message("success","Status change successfully");
					redirect_page("setting","returnlist",array()); exit;
				}
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_return_setup WHERE return_id='".$return_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/setting/returntype',$data);
	}
	
	public function returnlist(){
		$this->load->view(ADMIN_FOLDER.'/setting/returnlist',$data);
	}
	
	public function addfranchiseesetup(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$fran_setup_id = ($form_data['fran_setup_id'])? $form_data['fran_setup_id']:_d($segment['fran_setup_id']);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitFranchisee']==1 && $this->input->post()!=''){
					$franchisee_type = FCrtRplc($form_data['franchisee_type']);
					$invest = FCrtRplc($form_data['invest']);
					$rotation = FCrtRplc($form_data['rotation']);
					
					$security_amt = FCrtRplc($form_data['security_amt']);
					$population = FCrtRplc($form_data['population']);
					$area_sqft = FCrtRplc($form_data['area_sqft']);
					$min_req_consult = FCrtRplc($form_data['min_req_consult']);
					$cmsn_own_product = FCrtRplc($form_data['cmsn_own_product']);
					$cmsn_tie_ups = FCrtRplc($form_data['cmsn_tie_ups']);
					$min_gurant_1year = FCrtRplc($form_data['min_gurant_1year']);
					$min_gurant_2year = FCrtRplc($form_data['min_gurant_2year']);
					$mg_eligibility = FCrtRplc($form_data['mg_eligibility']);
					$allowed_maxm = FCrtRplc($form_data['allowed_maxm']);
					
					$discount_ratio = FCrtRplc($form_data['discount_ratio']);
					
					$data = array("franchisee_type"=>$franchisee_type,
						"discount_ratio"=>$discount_ratio
					);
	
					if($model->checkCount("tbl_setup_franchisee","fran_setup_id",$fran_setup_id)>0){
						$this->SqlModel->updateRecord("tbl_setup_franchisee",$data,array("fran_setup_id"=>$fran_setup_id));
						set_message("success","You have successfully updated a setup details");
						redirect_page("setting","franchiseesetup",array());					
					}else{
						$this->SqlModel->insertRecord("tbl_setup_franchisee",$data);
							set_message("success","Successfully added a new franchisee");
							redirect_page("setting","franchiseesetup",array());
					}
				}
			break;
			case "STATUS":
				if($fran_setup_id>0){
					$is_status = ($segment['is_status']=="0")? "1":"0";
					$data = array("is_status"=>$is_status);
					$this->SqlModel->updateRecord("tbl_setup_franchisee",$data,array("fran_setup_id"=>$fran_setup_id));
					set_message("success","Status change successfully");
					redirect_page("setting","franchiseesetup",array()); exit;
				}
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_setup_franchisee WHERE fran_setup_id='".$fran_setup_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/setting/addfranchiseesetup',$data);
	}
	
	public function franchiseesetup(){
		$this->load->view(ADMIN_FOLDER.'/setting/franchiseesetup',$data);
	}
	
	public function singlecmsn(){
		$this->load->view(ADMIN_FOLDER.'/setting/singlecmsn',$data);
	}
	
	public function rewardachiversetup(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$news_id = (_d($form_data['news_id'])>0)? _d($form_data['news_id']):_d($segment['news_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitReward']==1 && $this->input->post()!=''){
					$this->db->query("DELETE FROM tbl_reward_manual_achiver WHERE member_id>0");
					$member_explode = array_unique(array_filter(explode(",",$form_data['member_id'])));
					foreach($member_explode as $member_id):
						$this->SqlModel->insertRecord("tbl_reward_manual_achiver",array("member_id"=>$member_id));
					endforeach;
					set_message("success","You have successfully added  new achiver");
					redirect_page("setting","rewardachiversetup",array());			
				}
			break;
		}
	
		$this->load->view(ADMIN_FOLDER.'/setting/rewardachiversetup',$data);	
	}
	
	
}
?>