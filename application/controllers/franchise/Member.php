<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(FRANCHISE_PATH);		
		}
	}
	
	
	
	
	public function agentlist(){
		$this->load->view(ADMIN_FOLDER.'/member/agentlist',$data);
	}
	
	public function kyc(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$kyc_id = ($form_data['kyc_id'])? $form_data['kyc_id']:_d($segment['kyc_id']);
		$member_id =  _d($segment['member_id']);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		switch($action_request){
			case "KYC":
				if($kyc_id>0){
					$approved_date = getLocalTime();
					$approved_sts = ($segment['approved_sts']>0)? 0:1;
					$this->SqlModel->updateRecord("tbl_mem_kyc",array("approved_sts"=>$approved_sts,"approved_date"=>$approved_date),array("kyc_id"=>$kyc_id));
					set_message("success","Successfully updated  member kyc");
					redirect_page("member","kyc",array("member_id"=>_e($member_id)));
				}
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/member/kyc',$data);
	}
	
	
	
	public function updatemember(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = ($form_data['member_id'])? $form_data['member_id']:_d($segment['member_id']);
		
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			$first_name = FCrtRplc($form_data['first_name']);
			$last_name = FCrtRplc($form_data['last_name']);
			$email_address = FCrtRplc($form_data['email_address']);
			$gender = FCrtRplc($form_data['gender']);
			
			$user_name = FCrtRplc($form_data['user_name']);
			$user_password = FCrtRplc($form_data['user_password']);
			
			$flddDOB_D = FCrtRplc($form_data['flddDOB_D']);
			$flddDOB_M = FCrtRplc($form_data['flddDOB_M']);
			$flddDOB_Y = FCrtRplc($form_data['flddDOB_Y']);
			
			$flddDOB = $flddDOB_Y."-".$flddDOB_M."-".$flddDOB_D;
			$date_of_birth = InsertDate($flddDOB);
			
			$current_address = FCrtRplc($form_data['current_address']);
			$city_name = FCrtRplc($form_data['city_name']);
			$state_name = FCrtRplc($form_data['state_name']);
			$country_name = FCrtRplc($form_data['country_name']);
			$country_code = FCrtRplc($form_data['country_code']);
			$pin_code = FCrtRplc($form_data['pin_code']);
			$member_mobile = FCrtRplc($form_data['member_mobile']);
		
			$processor_id = FCrtRplc($form_data['processor_id']);
			$pif_amount = FCrtRplc($form_data['pif_amount']);
			
			$data = array("first_name"=>$first_name,
				"last_name"=>$last_name,
				"member_email"=>$email_address,
				"current_address"=>$current_address,
				"city_name"=>$city_name,
				"state_name"=>$state_name,
				"country_name"=>$model->getCountryName($country_code),
				"country_code"=>$country_code,
				"pin_code"=>$pin_code,
				"member_mobile"=>$member_mobile,
				"upgrade_date"=>InsertDate(getLocalTime()),
				"user_name"=>$user_name,
				"user_password"=>$user_password,
				"date_of_birth"=>$date_of_birth
				
			);		
			if($member_id>0){
				$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
				$model->uploadProfileAvtar($_FILES,array("member_id"=>$member_id),"");
				set_message("success","Successfully updated member  detail");
				redirect_page("member","updatemember",array("member_id"=>_e($member_id)));
			}else{
				set_message("warning","Unable to update, please try again");
				redirect_page("member","profilelist",array("member_id"=>_e($member_id)));
			}		
		}
		$QR_CHECK = "SELECT tm.* FROM  tbl_members AS tm WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		$this->load->view(ADMIN_FOLDER.'/member/updatemember',$data);
	}
	
	public function newmember(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $segment['member_id'];
		$today_date = InsertDate(getLocalTime());
		$franchisee_id = $this->session->userdata('fran_id');
		$NO_ACCOUNT_MOBILE = $model->getValue("NO_ACCOUNT_MOBILE");
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			$first_name = FCrtRplc($form_data['first_name']);
			$last_name = FCrtRplc($form_data['last_name']);
			$email_address = FCrtRplc($form_data['email_address']);
			$member_mobile = FCrtRplc($form_data['member_mobile']);
			$member_company = FCrtRplc($form_data['member_company']);
			$country_code = FCrtRplc($form_data['country_code']);
			
			$user_name = FCrtRplc($form_data['user_name']);
			$user_password = FCrtRplc($form_data['user_password']);
			
			$flddDOB_Y = FCrtRplc($form_data['flddDOB_Y']);
			$flddDOB_M = FCrtRplc($form_data['flddDOB_M']);
			$flddDOB_D = FCrtRplc($form_data['flddDOB_D']);
			
			$left_right = FCrtRplc($form_data['left_right']);
			$sponsor_user_id = ($form_data['sponsor_user_id']);
			$AR_GET = $model->getSponsorId($sponsor_user_id);
			if($sponsor_user_id!='' && $AR_GET['sponsor_id']>0){
	
				$sponsor_id = $spil_id = ($AR_GET['sponsor_id']!='')? $AR_GET['sponsor_id']:$model->getMemberId($sponsor_user_id);
				
				$flddDOB = $flddDOB_Y."-".$flddDOB_M."-".$flddDOB_D;
				$date_of_birth = InsertDate($flddDOB);
				$user_id = $user_name = $model->generateUserId();
				if($model->checkCount("tbl_members","member_mobile",$member_mobile)<$NO_ACCOUNT_MOBILE){
					$Ctrl += ($AR_GET['sponsor_id']>0)? 0:1;
					$data = array("first_name"=>$first_name,
						"last_name"=>$last_name,
						"user_id"=>$user_id,
						"user_name"=>$user_name,
						"user_password"=>$user_password,
						"member_email"=>$email_address,
						"member_mobile"=>$member_mobile,
						"sponsor_id"=>($sponsor_id>0)? $sponsor_id:0,
						"date_join"=>getLocalTime(),
						"date_of_birth"=>$date_of_birth,
						"pan_status"=>"N",
						"status"=>"Y",
						"last_login"=>getLocalTime(),
						"login_ip"=>$_SERVER['REMOTE_ADDR'],
						"franchisee_id"=>$franchisee_id,
						"rank_id"=>1,
						"block_sts"=>"N",
						"sms_sts"=>"N",
						"upgrade_date"=>getLocalTime()
					);		
					if($Ctrl==0){
						$member_id = $this->SqlModel->insertRecord("tbl_members",$data);
							$tree_data = array("member_id"=>$member_id,
								"sponsor_id"=>$sponsor_id,
								"spil_id"=>$spil_id,
								"nlevel"=>0,
								"left_right"=>'',
								"nleft"=>0,
								"nright"=>0,
								"date_join"=>$today_date
							);
						$this->SqlModel->insertRecord("tbl_mem_tree",$tree_data);
						$model->updateTree($sponsor_id,$member_id);
							
						set_message("success","Please enter login info of member");
						redirect_franchise("member","profilelist",array("member_id"=>_e($member_id)));
					}else{
						set_message("warning","Failed , unable to process your request , please try again");
						redirect_franchise("member","profilelist",array());
					}
				}else{
					set_message("warning","This email is already register with us");
				}
			}else{
				set_message("warning","Invalid sponsor Id, please enter valid");
			}
		}
		$QR_CHECK = "SELECT tm.* FROM  tbl_members AS tm WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		$this->load->view(FRANCHISE_FOLDER.'/member/newmember',$data);
	}
	
	public function profilelist(){
		$this->load->view(FRANCHISE_FOLDER.'/member/profilelist',$data);
	}
	
	
	public function profile(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];

		$member_id = ($form_data['member_id']>0)? $form_data['member_id']:_d($segment['member_id']);
		
		$model->checkMemberId($member_id);
		
		
		if($form_data['submitMemberSavePassword']==1 && $this->input->post()!=''){
			$old_password = FCrtRplc($form_data['old_password']);
			$user_password = FCrtRplc($form_data['user_password']);
			$confirm_user_password = FCrtRplc($form_data['confirm_user_password']);	
			if($old_password!=$user_password){
				if($model->checkOldPassword($member_id,$old_password)>0){
					$data = array("user_password"=>$user_password);
					$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
					set_message("success","Password changed successfully");
					redirect_page("member","profile",array("member_id"=>_e($member_id))); 
				}else{
					set_message("warning","Invalid old password");
					redirect_page("member","profile",array("member_id"=>_e($member_id))); 
				}
			}else{
				set_message("warning","New password must be different form old-password");
				redirect_page("member","profile",array("member_id"=>_e($member_id))); 
			}
		}
		
		switch($action_request){
			case "BLOCK_UNBLOCK":
				if($member_id>0){
					$block_sts = ($segment['block_sts']=="N")? "Y":"N";
					$data = array("block_sts"=>$block_sts);
					$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
					set_message("success","Status change successfully");
					redirect_page("member","profilelist",array()); exit;
				}
			break;
			case "EMAIL_STS":
				if($member_id>0){
					$email_sts = ($segment['email_sts']=="N")? "Y":"N";
					$data = array("email_sts"=>$email_sts);
					$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
					set_message("success","Email status changed  successfully");
					redirect_page("member","profilelist",array()); exit;
				}
			break;
			case "STATUS":
				if($member_id>0){
					$status = ($segment['status']=="N")? "Y":"N";
					$data = array("status"=>$status);
					$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
					set_message("success","Status change successfully");
					redirect_page("member","profilelist",array()); exit;
				}
			break;
			
		}
		
		$QR_CHECK = "SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name, tmsp.user_id AS spsr_user_id
		 FROM  tbl_members AS tm	
		 LEFT JOIN  tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
		 WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		
		$this->load->view(ADMIN_FOLDER.'/member/profile',$data);
	}
	
	public function tree(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$this->load->view(ADMIN_FOLDER.'/member/tree',$data);
	}
	
	public function treegenealogy(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$this->load->view(ADMIN_FOLDER.'/member/treegenealogy',$data);
	}
	
	public function genealogy(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$this->load->view(ADMIN_FOLDER.'/member/genealogy',$data);
	}
	
	public function level(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$this->load->view(ADMIN_FOLDER.'/member/level',$data);
	}
	
	public function direct(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$this->load->view(ADMIN_FOLDER.'/member/direct',$data);
	}
	
	public function accesspanel(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		if($form_data['submitLoginMember']==1 && $this->input->post()!=''){
			$member_user_id = FCrtRplc($form_data['member_user_id']);
			if($member_user_id!=''){
				$Q_MEM = "SELECT * FROM  tbl_members WHERE ( user_name='".$member_user_id."' OR member_email='".$member_user_id."' ) 
				AND delete_sts>0";
				$fetchRow = $this->SqlModel->runQuery($Q_MEM,true);
				if($fetchRow['member_id']>0){
					$this->session->set_userdata('mem_id',$fetchRow['member_id']);
					$this->session->set_userdata('user_id',$fetchRow['user_id']);
					redirect(BASE_PATH."user");
				}else{
					set_message("warning","Invalid member username");
					redirect_page("member","accesspanel",array()); 
				}
			}else{
				set_message("warning","Please enter valid member username");
				redirect_page("member","accesspanel",array()); 
			}
		}
		$this->load->view(ADMIN_FOLDER.'/member/accesspanel',$data);
	}
	
	public function directaccesspanel(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$user_id = FCrtRplc($segment['user_id']);
		if($user_id!=''){
			$Q_MEM = "SELECT * FROM  tbl_members WHERE user_id='$user_id' AND delete_sts>0";
			$fetchRow = $this->SqlModel->runQuery($Q_MEM,true);
			if($fetchRow['member_id']>0){
				$this->session->set_userdata('mem_id',$fetchRow['member_id']);
				$this->session->set_userdata('user_id',$fetchRow['user_id']);
				redirect(BASE_PATH."user");
			}else{
				set_message("warning","Invalid member id");
				redirect_page("member","accesspanel",array()); 
			}
		}else{
			set_message("warning","Please enter valid member id");
			redirect_page("member","accesspanel",array()); 
		}
		
	}
	
	public function deletemember(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$member_id = ($form_data['member_id']>0)? $form_data['member_id']:$segment['member_id'];
		if($member_id>0){
			$data = array("delete_sts"=>0);
			$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
			set_message("success","Member deleted successfully");
			redirect_page("member","profilelist",array()); 
		}else{
			set_message("warning","Unable to delete member");
			redirect_page("member","profilelist",array()); 
		}
	}
	
	public function membersupport(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$enquiry_id = ($form_data['enquiry_id'])? $form_data['enquiry_id']:_d($segment['enquiry_id']);
		
		if($action_request=="CLOSE"){
			if($enquiry_id>0){
				$data = array("enquiry_sts"=>"C");
				$this->SqlModel->updateRecord("tbl_support",$data,array("enquiry_id"=>$enquiry_id));
				set_message("success","You have  successfully closed a ticket");
				redirect_page("member","membersupport","");
			}
		}
		$this->load->view(ADMIN_FOLDER.'/member/membersupport',$data);
	}
	
	public function queryadd(){
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$query_id = ($form_data['query_id'])? $form_data['query_id']:$segment['query_id'];
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitQuery']==1 && $this->input->post()!=''){
					$query_name = FCrtRplc($form_data['query_name']);					
					$data = array("query_name"=>$query_name);
					
					$fldiCount = SelectTableWithOption("tbl_support_type","COUNT(*)","query_id='$query_id'");
					if($fldiCount>0){
						$this->SqlModel->updateRecord("tbl_support_type",$data,array("query_id"=>$query_id));
						set_message("success","You have successfully updated a query details");
						redirect_page("member","queryadd",array("query_id"=>$query_id,"action_request"=>"EDIT"));							
					}else{
						$this->SqlModel->insertRecord("tbl_support_type",$data,array("query_id"=>$query_id));
						set_message("success","You have successfully added a new query type");
						redirect_page("member","querytype",array());					
					}
				}
			break;
			case "DELETE":
				if($query_id>0){
					$data = array("isDelete"=>0);
					$this->SqlModel->updateRecord("tbl_support_type",$data,array("query_id"=>$query_id));
					set_message("success","You have successfully deleted a query type");
				}else{
					set_message("warning","Unable to delete city, please try again");
				}
				redirect_page("member","querytype",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM  tbl_support_type WHERE query_id='$query_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/member/queryadd',$data);	
	}
	
	public function querytype(){
	
		$this->load->view(ADMIN_FOLDER.'/member/querytype',$data);
	}
	
	public function conversation(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$enquiry_id = ($form_data['enquiry_id'])? $form_data['enquiry_id']:_d($segment['enquiry_id']);
		$member_id = 0;
		if($form_data['chatSubmit']=='1' && $this->input->post()!=''){
			$enquiry_reply = FCrtRplc($form_data['enquiry_reply']);
			$reply_date = $enquiry_date = getLocalTime();
			$data = array("member_id"=>$member_id,
				"enquiry_id"=>$enquiry_id,
				"enquiry_reply"=>$enquiry_reply,
				"enquiry_date"=>$enquiry_date,
				"reply_date"=>$reply_date
			);
			if($enquiry_id>0){
				$parent_id = $this->SqlModel->insertRecord("tbl_support_rply",$data);
				$this->SqlModel->updateRecord("tbl_support",array("enquiry_sts"=>"H","reply_date"=>$reply_date),array("enquiry_id"=>$enquiry_id));
				
				foreach($_FILES['file_attach']['tmp_name'] as $key=>$fldvValue):
					$fldvFileName= $_FILES['file_attach']['name'][$key];
					$fldvFileTemp = $_FILES['file_attach']['tmp_name'][$key];
					$fldvFileType = $_FILES['file_attach']['type'][$key];
					$fldvFileError = $_FILES['file_attach']['error'][$key];
					if($fldvFileError=="0" && $parent_id>0){
						$ext = explode(".",$fldvFileName);
						$fExtn = strtolower(end($ext));
						$fldvUniqueNo = UniqueId("UNIQUE_NO");
						$file_attach = $fldvUniqueNo.rand(100,999)."_". str_replace(" ","",rand(100,999).".".$fExtn);
						$target_path = $fldvPath."upload/chat/".$file_attach;
						
						if(move_uploaded_file($fldvFileTemp, $target_path)){
							$data_file = array("file_attach"=>$file_attach,
								"file_type"=>$fldvFileType,
								"parent_id"=>$parent_id,
								"enquiry_date"=>$enquiry_date,
								"reply_date"=>$reply_date
						     );
							$this->SqlModel->insertRecord("tbl_support_rply",$data_file);						
						}	
					}
				endforeach;
				
				redirect_page("member","conversation",array("enquiry_id"=>_e($enquiry_id)));
			}
			
		}
		$this->load->view(ADMIN_FOLDER.'/member/conversation',$data);
	}
	
	public function upgrademember(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = InsertDate(getLocalTime());
		
		if($form_data['submitUpgrade']=='1' && $this->input->post()!=''){
			$member_id = _d($form_data['member_id']);
			$AR_MEM = $model->getMember($member_id);
			
			$rank_id = FCrtRplc($form_data['rank_id']);
			
			if($member_id>0 && $rank_id>0){	
				$this->SqlModel->updateRecord("tbl_members",array("rank_id"=>$rank_id),array("member_id"=>$member_id));
				set_message("success","Successfully upgrade a member");
				redirect_page("member","upgrademember",array());
			}else{
				set_message("warning","Member not found , please select valid member");
				redirect_page("member","upgrademember",array());
			}
		}
		
		$this->load->view(ADMIN_FOLDER.'/member/upgrademember',$data);
	}
	
	public function cmsndifferential(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(ADMIN_FOLDER.'/member/cmsndifferential',$data);
	}
	
	
}
