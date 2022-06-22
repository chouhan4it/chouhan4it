<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	public function editmemberneft(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = _d($form_data['member_id']);
		$AR_MEM = $model->getMember($member_id);
		
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			if($member_id>0){
				$model->uploadCancelCheque(array("member_id"=>$member_id),$_FILES,"");
				set_message("success","Successfully updated NEFT Request Detail");
				redirect_page("member","editmemberneft",array("member_id"=>_e($member_id)));
			}else{
				set_message("warning","Unable to upload, please try again");
				redirect_page("member","editmemberneft",array("member_id"=>_e($member_id)));
			}		
		}
		$this->load->view(ADMIN_FOLDER.'/member/editmemberneft',$data);
	}

	
	public function idcard(){
		$this->load->view(ADMIN_FOLDER.'/member/idcard',$data);
	}

	public function cadreupgradehistory(){
		$this->load->view(ADMIN_FOLDER.'/member/cadreupgradehistory',$data);
	}

	public function idcardback(){
		$this->load->view(ADMIN_FOLDER.'/member/idcardback',$data);
	}

	


	public function assignvoucher(){
		$this->load->view(ADMIN_FOLDER.'/member/assignvoucher',$data);
	}

	

	public function profilelist(){
		$this->load->view(ADMIN_FOLDER.'/member/profilelist',$data);
	}
	
	public function customlist(){
		$this->load->view(ADMIN_FOLDER.'/member/customlist',$data);
	}
	
	public function incompletelist(){
		$this->load->view(ADMIN_FOLDER.'/member/incompletelist',$data);
	}
	
	public function pancardapp(){
		$model = new OperationModel();
		$today_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$pan_app_id = ($form_data['pan_app_id'])? _d($form_data['pan_app_id']):_d($segment['pan_app_id']);
		$pan_sts = _d($segment['pan_sts']);
		$oprt_id  = $this->session->userdata('oprt_id');
		$id_type = "PAN CARD";
		$document_id = $pan_no =  FCrtRplc($form_data['pan_no']);
		if($pan_app_id>0 && $pan_sts!=''){
			$data = array("pan_sts"=>$pan_sts,
				"pan_sts_date"=>$today_date
			);
			$data_his = array("pan_app_id"=>$pan_app_id,
				"pan_sts"=>$pan_sts,
				"pan_sts_date"=>$today_date,
				"oprt_id"=>$oprt_id
			);
			$this->SqlModel->updateRecord("tbl_pan_register",$data,array("pan_app_id"=>$pan_app_id));
			$this->SqlModel->insertRecord("tbl_pan_history",$data_his);
			set_message("success","You have successfully updated pancard status");
			redirect_page("member","pancardapp","");
			
		}
		if($form_data['updatePancard']==1 && $this->input->post()!=''){
			$member_id = $model->getMemberIdPanRegister($pan_app_id);
			
			if($member_id>0){
				if($_FILES['file_passport']['error']=="0"){
						$ext = explode(".",$_FILES['file_passport']["name"]);
						$fExtn = strtolower(end($ext));
						$fldvUniqueNo = UniqueId("UNIQUE_NO");
						$file_passport = $fldvUniqueNo."_id_proof_". str_replace(" ","",$member_id.".".$fExtn);
						$target_path = $fldvPath."upload/kyc/".$file_passport;
						
						$AR_MEM = SelectTable("tbl_mem_kyc","file_passport","member_id='$member_id'");
						$final_location = $fldvPath."upload/kyc/".$AR_MEM['file_passport'];
						$fldvImageArr= @getimagesize($final_location);
						if ($fldvImageArr['mime']!="") { @chmod($final_location,0777);	@unlink($final_location); }
							if(move_uploaded_file($_FILES['file_passport']['tmp_name'], $target_path)){
								$kyc_id = $model->getKycId($member_id);
								$data = array("member_id"=>$member_id,
									"id_type"=>($id_type!='')? $id_type:" ",
									"document_id"=>($document_id)? $document_id:" ",
									"approved_sts"=>0,
									"file_passport"=>$file_passport
								);
								
								if($id_type=="PAN CARD"){
									$new_name = ($document_id)? $document_id:$fldvUniqueNo;
									$file_name = $new_name."_pan_". str_replace(" ","",$member_id.".".$fExtn);
									copyImg($file_name,$target_path,"pancard");
									$pan_data = array("member_id"=>$member_id,
										"pan_file"=>$file_name,
										"pan_no"=>($document_id)? $document_id:" ",
										"approve_sts"=>0,
									);
									if($model->checkCount("tbl_mem_pancard","member_id",$member_id)>0){
										$this->SqlModel->updateRecord("tbl_mem_pancard",$pan_data,array("member_id"=>$member_id));
									}else{
										$this->SqlModel->insertRecord("tbl_mem_pancard",$pan_data);
									}	
								}
								if($kyc_id>0){
									$this->SqlModel->updateRecord("tbl_mem_kyc",$data,array("kyc_id"=>$kyc_id));
								}else{
									$this->SqlModel->insertRecord("tbl_mem_kyc",$data);
								}
								$this->SqlModel->updateRecord("tbl_pan_register",array("pan_no"=>$document_id),array("pan_app_id"=>$pan_app_id));
								$this->SqlModel->updateRecord("tbl_members",array("pan_no"=>$document_id),array("member_id"=>$member_id));
								set_message("success","Pancard updated successfull");
								redirect_page("member","pancardapp",array());
							}
					}
				}
			}
		
		$this->load->view(ADMIN_FOLDER.'/member/pancardapp',$data);
	}
	
	public function agentlist(){
		$this->load->view(ADMIN_FOLDER.'/member/agentlist',$data);
	}
	
	public function changesponsor(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = _d($form_data['member_id']);
		$new_sponsor_id = FCrtRplc($form_data['new_sponsor_id']);
		if($form_data['submitSponsorMember']==1 && $this->input->post()!=''){
			$AR_GET = $model->getSponsorId($new_sponsor_id);
			$sponsor_id  = $AR_GET['sponsor_id'];
			if($member_id>0){
				if($sponsor_id>0){
					if($member_id>$sponsor_id){
						$this->SqlModel->updateRecord("tbl_members",array("sponsor_id"=>$sponsor_id),array("member_id"=>$member_id));
						$this->SqlModel->updateRecord("tbl_mem_tree",array("sponsor_id"=>$sponsor_id),array("member_id"=>$member_id));
						set_message("success","Sponsor updated successfully");
						redirect_page("member","changesponsor",array("member_id"=>_e($member_id)));
					}else{
						set_message("warning","Downline member cannot be sponsor");
						redirect_page("member","changesponsor",array("member_id"=>_e($member_id)));
					}
				}else{
					set_message("warning","Invalid sponsor Id");
					redirect_page("member","changesponsor",array("member_id"=>_e($member_id)));
				}
			}else{
				set_message("success","Unable to process request, please try again");
				redirect_page("member","changesponsor",array("member_id"=>_e($member_id)));
			}
		}
		if($form_data['submitValidMember']==1 && $this->input->post()!=''){
			$member_id = _d($form_data['member_id']);
			if($member_id>0){
				$AR_MEM = $model->getMember($member_id);
				$data['ROW'] = $AR_MEM;
			}else{
				set_message("warning","Invalid member Id");
				redirect_page("member","changesponsor","");
			}
		}
		$this->load->view(ADMIN_FOLDER.'/member/changesponsor',$data);
	}
	
	public function chainreferesh(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$this->load->view(ADMIN_FOLDER.'/member/chainreferesh',$data);
	}
	
	
	public function kyc(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$kyc_id = ($form_data['kyc_id'])? $form_data['kyc_id']:_d($segment['kyc_id']);
		$member_id =  _d($segment['member_id']);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		switch($action_request){
			case "KYC_ADD":
				if($kyc_id>0){
					$approved_date = getLocalTime();
					$approved_sts = ($segment['approved_sts']);
					$this->SqlModel->updateRecord("tbl_mem_kyc",array("approved_sts"=>$approved_sts,"approved_date"=>$approved_date),
					array("kyc_id"=>$kyc_id));
					set_message("success","Successfully updated  member kyc");
					redirect_page("member","kycaddress","");
				}
			break;
			case "KYC_ID":
				if($kyc_id>0){
					$approved_date = getLocalTime();
					$approved_sts_id = ($segment['approved_sts_id']);
					$this->SqlModel->updateRecord("tbl_mem_kyc",array("approved_sts_id"=>$approved_sts_id,"approved_date_id"=>$approved_date)
					,array("kyc_id"=>$kyc_id));
					set_message("success","Successfully updated  member kyc");
					redirect_page("member","kycid","");
				}
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/member/kyc',$data);
	}
	
	
	
	public function updatemember(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = InsertDate(getLocalTime());
		$member_id = ($form_data['member_id'])? $form_data['member_id']:_d($segment['member_id']);
		
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			$title = FCrtRplc($form_data['title']);
			$first_name = FCrtRplc($form_data['first_name']);
			$last_name = FCrtRplc($form_data['last_name']);
			$full_name = $first_name." ".$last_name;
			$member_email = FCrtRplc($form_data['email_address']);
			$gender = FCrtRplc($form_data['gender']);
			
			$flddDOB_D = FCrtRplc($form_data['flddDOB_D']);
			$flddDOB_M = FCrtRplc($form_data['flddDOB_M']);
			$flddDOB_Y = FCrtRplc($form_data['flddDOB_Y']);
			
			$date_of_birth = InsertDate($flddDOB_Y."-".$flddDOB_M."-".$flddDOB_D);
			
			$user_name = FCrtRplc($form_data['user_name']);
			$user_password = FCrtRplc($form_data['user_password']);
			
			$nominal_name = FCrtRplc($form_data['nominal_name']);
			$nominal_relation = FCrtRplc($form_data['nominal_relation']);
			$nominal_mobile = FCrtRplc($form_data['nominal_mobile']);
			$nominal_dob = InsertDate($form_data['nominal_dob']);
			
			$pan_no = FCrtRplc($form_data['pan_no']);
			$bank_acct_holder = FCrtRplc($form_data['bank_acct_holder']);
			$bank_name = FCrtRplc($form_data['bank_name']);
			$account_number = FCrtRplc($form_data['account_number']);
			$branch = FCrtRplc($form_data['branch']);
			$ifc_code = FCrtRplc($form_data['ifc_code']);
			
			$current_address = FCrtRplc($form_data['current_address']);
			$land_mark = FCrtRplc($form_data['land_mark']);
			$city_name = FCrtRplc($form_data['city_name']);
			$state_name = FCrtRplc($form_data['state_name']);
			$country_name = FCrtRplc($form_data['country_name']);
			$country_code = FCrtRplc($form_data['country_code']);
			$pin_code = FCrtRplc($form_data['pin_code']);
			$member_mobile = FCrtRplc($form_data['member_mobile']);
		
			$processor_id = FCrtRplc($form_data['processor_id']);
			$pif_amount = FCrtRplc($form_data['pif_amount']);
			
			$data = array("title"=>$title,
				"first_name"=>$first_name,
				"last_name"=>$last_name,
				"full_name"=>$full_name,
				"title"=>$title,
				"user_id"=>$user_name,
				"user_name"=>$user_name,
				"user_password"=>$user_password,
				"member_email"=>$member_email,
				"member_mobile"=>$member_mobile,
				"date_of_birth"=>$date_of_birth,
				"gender"=>$gender,
				"current_address"=>$current_address,
				"city_name"=>($city_name)? $city_name:" ",
				"state_name"=>($state_name)? $state_name:" ",
				"land_mark"=>($land_mark)? $land_mark:" ",
				"pin_code"=>($pin_code)? $pin_code:" ",
				"nominal_name"=>($nominal_name)? $nominal_name:" ",
				"nominal_relation"=>($nominal_relation)? $nominal_relation:" ",
				"nominal_mobile"=>($nominal_mobile)? $nominal_mobile:" ",
				"nominal_dob"=>($nominal_dob)? $nominal_dob:" ",
				"pan_no"=>($pan_no)? $pan_no:" ",
				"pan_status"=>($pan_status)? $pan_status:"Y",
				"bank_acct_holder"=>($bank_acct_holder)? $bank_acct_holder:" ",
				"bank_name"=>($bank_name)? $bank_name:" ",
				"account_number"=>($account_number)? $account_number:" ",
				"branch"=>($branch)? $branch:" ",
				"ifc_code"=>($ifc_code)? $ifc_code:" "
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
		if($form_data['submitValidMember']==1 && $this->input->post()!=''){
			$member_id = $model->getMemberId($form_data['member_user_id']);
			$oprt_id = $this->session->userdata('oprt_id');
			if($member_id>0){
				if($member_id==1 && $oprt_id>2){
					set_message("warning","Invalid User Id");
					redirect_page("member","updatemember","");
				}else{
					$AR_MEM = $model->getMember($member_id);
					$data['ROW'] = $AR_MEM;
				}
			}else{
				set_message("warning","Invalid User Id");
				redirect_page("member","updatemember","");
			}
		}
		$this->load->view(ADMIN_FOLDER.'/member/updatemember',$data);
	}
	
	public function addmember(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $segment['member_id'];
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$NO_ACCOUNT_MOBILE = $model->getValue("NO_ACCOUNT_MOBILE");
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			$first_name = FCrtRplc($form_data['first_name']);
			$last_name = FCrtRplc($form_data['last_name']);
			$full_name = $first_name." ".$last_name;
			$email_address = FCrtRplc($form_data['email_address']);
			$member_mobile = FCrtRplc($form_data['member_mobile']);
			
			$flddDOB_Y = FCrtRplc($form_data['flddDOB_Y']);
			$flddDOB_M = FCrtRplc($form_data['flddDOB_M']);
			$flddDOB_D = FCrtRplc($form_data['flddDOB_D']);
			
			$left_right = FCrtRplc($form_data['left_right']);

			$sponsor_id = $spil_id = $model->getMemberId($form_data['spr_user_id']);
			
			$flddDOB = $flddDOB_Y."-".$flddDOB_M."-".$flddDOB_D;
			$date_of_birth = InsertDate($flddDOB);
			$user_id = $user_name = $model->generateUserId();
			
			if($sponsor_id>0){
				if($model->checkCount("tbl_members","user_id",$user_id)==0){
					$Ctrl +=  ($sponsor_id>0)? 0:1;
					$data = array("first_name"=>$first_name,
						"last_name"=>$last_name,
						"full_name"=>getTool($full_name,''),
						"user_id"=>$user_id,
						"user_name"=>$user_name,
						"member_email"=>$email_address,
						"member_mobile"=>$member_mobile,
						
						"sponsor_id"=>getTool($sponsor_id,0),
						"spil_id"=>getTool($spil_id,0),
						"left_right"=>getTool($left_right,''),
						
						"date_join"=>$current_date,
						"date_of_birth"=>$date_of_birth,
						"pan_status"=>"N",
						"status"=>"Y",
						"last_login"=>$current_date,
						"login_ip"=>$_SERVER['REMOTE_ADDR'],
						"block_sts"=>"N",
						"sms_sts"=>"N",
						"upgrade_date"=>$current_date
						
					);		
					if($Ctrl==0){
						$member_id = $this->SqlModel->insertRecord("tbl_members",$data);
						
						$tree_data = array("member_id"=>$member_id,
							"sponsor_id"=>$sponsor_id,
							"spil_id"=>$spil_id,
							"nlevel"=>0,
							"left_right"=>getTool($left_right,''),
							"nleft"=>0,
							"nright"=>0,
							"date_join"=>$current_date
						);
						$this->SqlModel->insertRecord("tbl_mem_tree",$tree_data);
						$model->updateTree($spil_id,$member_id);
						
						
						#$model->welcomeMemberSms($member_mobile,$full_name,$user_id,"********");
						
						set_message("success","Please enter login info of member");
						redirect_page("member","addmembertwo",array("member_id"=>_e($member_id)));
					}else{
						set_message("warning","Failed , unable to process your request , place not available");
						redirect_page("member","addmember",array());
					}
				}else{
					set_message("warning","Unable to process your request, please try again");
				}
			}else{
				set_message("warning","Invalid sponsor id");
			}
		}
		$QR_CHECK = "SELECT tm.* FROM tbl_members AS tm WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		$this->load->view(ADMIN_FOLDER.'/member/addmember',$data);
	}
	
	public  function addmembertwo(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);

		$member_id = ($form_data['member_id']>0)? $form_data['member_id']:_d($segment['member_id']);
		$model->checkMemberId($member_id);
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			
			$user_name = FCrtRplc($form_data['user_name']);
			$user_password = FCrtRplc($form_data['user_password']);
			$member_id = FCrtRplc($form_data['member_id']);
			if($model->checkMemberUsernameExist($user_name,$member_id)==0){
				$data = array("user_password"=>$user_password);		
				$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
				
				set_message("success","Successfully executed record, please proceed for address setting");
				redirect_page("member","addmembertwo",array("member_id"=>_e($member_id)));
			}else{
				set_message("warning","This username is already register with us, please try another");
				redirect_page("member","addmembertwo",array("member_id"=>_e($member_id)));
			}
		}
		
		$QR_CHECK = "SELECT tm.* FROM tbl_members AS tm WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);

		$data['ROW']=$fetchRow;
		$this->load->view(ADMIN_FOLDER.'/member/addmember-two',$data);
	}
	
	
	public function addmemberthree(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);

		$member_id = ($form_data['member_id']>0)? $form_data['member_id']:_d($segment['member_id']);
		$model->checkMemberId($member_id);
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			$current_address = FCrtRplc($form_data['current_address']);
			$country_code = FCrtRplc($form_data['country_code']);
			$city_name = FCrtRplc($form_data['city_name']);
			$state_name = FCrtRplc($form_data['state_name']);
			$country_name = FCrtRplc($form_data['country_name']);
			$pin_code = FCrtRplc($form_data['pin_code']);
			$member_mobile = FCrtRplc($form_data['member_mobile']);
			$member_id = FCrtRplc($form_data['member_id']);

			$data = array("current_address"=>$current_address,
				"country_code"=>$country_code,				
				"city_name"=>$city_name,				
				"state_name"=>$state_name,				
				"country_name"=>$country_name,				
				"pin_code"=>$pin_code,				
				"member_mobile"=>$member_mobile,							
			);				
			$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
			set_message("success","Successfully executed record, please proceed for payment setting");
			redirect_page("member","addmemberthree",array("member_id"=>_e($member_id)));
			
		}
		
		$QR_CHECK = "SELECT tm.* FROM tbl_members AS tm WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		
		$this->load->view(ADMIN_FOLDER.'/member/addmember-three',$data);
	}
	
	public function addmemberfour(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = ($form_data['member_id']>0)? $form_data['member_id']:_d($segment['member_id']);
		$model->checkMemberId($member_id);
		
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
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
						$this->SqlModel->updateRecord("tbl_members",array("photo"=>$photo),array("member_id"=>$member_id));
						set_message("success","Successfully updated profile avatar");
						redirect_page("member","addmemberfour",array("member_id"=>_e($member_id)));
						
					}
			}
		}
		
		$QR_CHECK = "SELECT tm.* FROM tbl_members AS tm WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		
		$this->load->view(ADMIN_FOLDER.'/member/addmember-four',$data);
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
			case "PAN_STS":
				if($member_id>0){
					$pan_sts = ($segment['pan_sts']=="0")? "1":"0";
					$data = array("pan_sts"=>$pan_sts);
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
		
		$fetchRow = $model->getMember($member_id);
		$data['ROW']=$fetchRow;
		
		$this->load->view(ADMIN_FOLDER.'/member/profile',$data);
	}
	
	public function jumpcadre(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = ($form_data['member_id']>0)? $form_data['member_id']:_d($segment['member_id']);
		$model->checkMemberId($member_id);
		$fetchRow = $model->getMember($member_id);
		$data['ROW']=$fetchRow;
		$this->load->view(ADMIN_FOLDER.'/member/jumpcadre',$data);
	}
	
	public function tree(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$this->load->view(ADMIN_FOLDER.'/member/tree',$data);
	}
	
	public function treeauto(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$this->load->view(ADMIN_FOLDER.'/member/treeauto',$data);
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
		$oprt_id  = $this->session->userdata('oprt_id');
		if($form_data['submitLoginMember']==1 && $this->input->post()!=''){
			$member_user_id = FCrtRplc($form_data['member_user_id']);
			if($member_user_id!=''){
				$Q_MEM = "SELECT * FROM tbl_members WHERE ( user_name='".$member_user_id."' OR member_email='".$member_user_id."' ) 
				AND delete_sts>0";
				$fetchRow = $this->SqlModel->runQuery($Q_MEM,true);
				if($fetchRow['member_id']>0){
					if($fetchRow['member_id']==1 && $oprt_id>2){
						set_message("warning","Invalid member username");
						redirect_page("member","accesspanel",array()); 
					}else{
						$this->session->set_userdata('mem_id',$fetchRow['member_id']);
						$this->session->set_userdata('user_id',$fetchRow['user_id']);
						if($oprt_id<=2){$this->session->set_userdata('temp_oprtid',1);}
						redirect(MEMBER_PATH);
					}
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
			$Q_MEM = "SELECT * FROM tbl_members WHERE user_id='$user_id' AND delete_sts>0";
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
				$QR_PAGE ="SELECT * FROM tbl_support_type WHERE query_id='$query_id'";
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
			$old_rank = $model->getRankName($AR_MEM['rank_id']);
			
			$rank_id = FCrtRplc($form_data['rank_id']);
			$new_rank = $model->getRankName($rank_id);
			if($member_id>0 && $rank_id>0){	
				if($rank_id>$AR_MEM['rank_id']){
					$this->SqlModel->updateRecord("tbl_members",array("rank_id"=>$rank_id,"upgrade_date"=>$today_date),
					array("member_id"=>$member_id));
					$model->upgradeHistory($member_id,$AR_MEM['rank_id'],$rank_id);
					set_message("success","Successfully upgrade a ".$AR_MEM['full_name']."&nbsp;[".$AR_MEM['user_id']."]&nbsp;".$old_rank." 
					to ".$new_rank." ");
					redirect_page("member","upgrademember",array());
				}else{
					set_message("warning","Member cannot be upgrade to old rank");
					redirect_page("member","upgrademember",array());
				}
			}else{
				set_message("warning","Member not found , please select valid member");
				redirect_page("member","upgrademember",array());
			}
		}
		
		$this->load->view(ADMIN_FOLDER.'/member/upgrademember',$data);
	}
	
	public function membertopup(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$wallet_id = $model->getDefaultWallet();
		
		if($form_data['submitTopup']=='1' && $this->input->post()!=''){
			$type_id = FcrtRplc($form_data['type_id']);
			$payment_type = FcrtRplc($form_data['payment_type']);
			$member_id = _d($form_data['member_id']);
			
			$AR_PACK = $model->getPinType($type_id);
			$pin_price = $AR_PACK['pin_price'];
			$type_id = $AR_PACK['type_id'];
			$no_month = $AR_PACK['no_month'];
			
			$cash_back_bonus = $AR_PACK['cash_back_bonus'];
			$fast_track_bonus = $AR_PACK['fast_track_bonus'];
			
			
			$LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
			$order_no = UniqueId("ORDER_NO");
			$trns_remark = "PACKAGE UPGRADE[".$order_no."]";
			
			$date_expire = InsertDate(AddToDate($today_date,"+ $no_month Month"));
			
			if($member_id>0){
				if($type_id>0){
					$data_sub = array("order_no"=>$order_no,
						"member_id"=>$member_id,
						"type_id"=>$type_id,
						"pin_name"=>$AR_PACK['pin_name'],
						"pin_price"=>$AR_PACK['pin_price'],
						
						"prod_pv"=>getTool($AR_PACK['prod_pv'],0),
						"net_amount"=>getTool($pin_price,0),
						
						"cash_back_bonus"=>getTool($cash_back_bonus,0),
						"fast_track_bonus"=>getTool($fast_track_bonus,0),						
						
						"no_month"=>getTool($no_month,0),
						"payment_type"=>$payment_type,
						"date_from"=>$current_date,
						"date_expire"=>$date_expire
					);
					
					switch($payment_type):
						case "WALLET-A":
							$member_id_wallet = $model->getMemberId($form_data['user_id_wallet']);
							$LDGR = $model->getCurrentBalance($member_id_wallet,$wallet_id,"","");
							if($LDGR['net_balance']>=$pin_price && $LDGR['net_balance']>0){
						
								$subcription_id = $this->SqlModel->insertRecord("tbl_subscription",$data_sub);
								$model->setSubscriptionPost($subcription_id,$type_id);
								$AR_SEND['trns_for']="UPGRADE";
								$AR_SEND['trans_ref_no']=$order_no;
								$model->wallet_transaction($wallet_id,$member_id_wallet,"Dr",$pin_price,$trns_remark,$today_date,$order_no,$AR_SEND);
																
								$this->SqlModel->updateRecord("tbl_members",array("subcription_id"=>$subcription_id),array("member_id"=>$member_id));
								#$model->setReferralIncome($member_id,$subcription_id);
								set_message("success","You have successfully upgraded your package");
								redirect_page("member","membertopup",array());
							}else{
								set_message("warning","It seem user  don't have enough credit to upgrade this package");
								redirect_page("member","membertopup",array());
							}
						break;
						case "EPIN-A":
							$pin_no = FCrtRplc($form_data['pin_no']);
							$pin_key = FCrtRplc($form_data['pin_key']);
							$AR_PIN = $model->getPinDetail($pin_no,$pin_key);
							if($pin_no!='' && $pin_key!=''){
								if($AR_PIN['block_sts']=="N"){
									if($AR_PIN['pin_sts']=="N" && $AR_PIN['use_member_id']==0){
										$subcription_id = $this->SqlModel->insertRecord("tbl_subscription",$data_sub);
										$model->setSubscriptionPost($subcription_id,$type_id);
										$model->updatePinDetail($AR_PIN['pin_id'],$member_id);
										#$model->setReferralIncome($member_id,$subcription_id);
										set_message("success","You have successfully upgraded your package");
										redirect_page("member","membertopup",array());
									}else{
										set_message("warning","This pin is already used by other member");
										redirect_page("member","membertopup",array());
									}
								}else{
									set_message("warning","Sorry this pin is blocked, please contact our support team");
									redirect_page("member","membertopup",array());
								}
							}else{
								set_message("warning","Please enter pin-no & pin-key details");
								redirect_page("member","membertopup",array());
							}
						break;
						case "ONLINE":
							set_message("warning","Currently Online transaction is not available");
							redirect_page("member","membertopup",array());
						break;
						default:
							set_message("warning","Unable to proceed this request , please try after some time");
							redirect_page("member","membertopup",array());
						break;
					endswitch;
					
					
				}else{
					set_message("warning","Invalid package, please select  valid package");
					redirect_page("member","membertopup",array());
				}
			}else{
				set_message("warning","Invalid User Id, please enter valid");
				redirect_page("member","membertopup",array());
			}
		}
		
		$this->load->view(ADMIN_FOLDER.'/member/membertopup',$data);
	}
	
	public function upgradememberrc(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = InsertDate(getLocalTime());
		
		if($form_data['submitUpgradeRC']=='1' && $this->input->post()!=''){
			$member_id = _d($form_data['member_id']);
			$AR_MEM = $model->getMember($member_id);
			
			$rank_id = FCrtRplc($form_data['rank_id']);
			if($member_id>0 && $rank_id>0){	
				$new_user_id  = StringReplace($AR_MEM['user_id'],"RC","PC");
				$this->SqlModel->updateRecord("tbl_members",array("rank_id"=>$rank_id,
							"upgrade_date"=>$today_date,
							"user_id"=>$new_user_id,"
							user_name"=>$new_user_id),
							array("member_id"=>$member_id));
							
				$model->sendUpgradeMemberSMS($member_id);
				$model->upgradeHistory($member_id,$AR_MEM['rank_id'],$rank_id);
				set_message("success","Successfully upgrade a User Id[".$AR_MEM['user_id']."] to User Id[".$new_user_id."]");
				redirect_page("member","upgradememberrc",array());
			}else{
				set_message("warning","RC Id  not found , please select valid RC Id");
				redirect_page("member","upgradememberrc",array());
			}
		}
		
		$this->load->view(ADMIN_FOLDER.'/member/upgradememberrc',$data);		
	}
	
	public function upgradememberpc(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = InsertDate(getLocalTime());
		
		if($form_data['submitUpgradePC']=='1' && $this->input->post()!=''){
			$member_id = _d($form_data['member_id']);
			$AR_MEM = $model->getMember($member_id);
			
			$rank_id = FCrtRplc($form_data['rank_id']);
			if($member_id>0 && $rank_id>=0){	
				$Ctrl += $model->checkMemberLevel($member_id);
				if($Ctrl==0){
					$new_user_id  = StringReplace($AR_MEM['user_id'],"PC","RC");
					$this->SqlModel->updateRecord("tbl_members",array("rank_id"=>$rank_id,"user_id"=>$new_user_id,"user_name"=>$new_user_id),array("member_id"=>$member_id));
					
					$model->upgradeHistory($member_id,$AR_MEM['rank_id'],$rank_id);
					set_message("success","Successfully downgrade a User Id[".$AR_MEM['user_id']."] to User Id[".$new_user_id."]");
					redirect_page("member","upgradememberpc",array());
			  }else{
			  	set_message("warning","This User member cannot be downgrade to RC becuase of  downline member");
				redirect_page("member","upgradememberpc",array());
			  }
			}else{
				set_message("warning","BA Id  not found , please select valid User Id");
				redirect_page("member","upgradememberpc",array());
			}
		}
		
		$this->load->view(ADMIN_FOLDER.'/member/upgradememberpc',$data);	
	}
	
	public function cmsndifferential(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		$this->load->view(ADMIN_FOLDER.'/member/cmsndifferential',$data);
	}
	
	
	public function pancard(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$pan_id = ($form_data['pan_id'])? _d($form_data['pan_id']):_d($segment['pan_id']);
		
		switch($action_request){
			case "DELETE":
				if($pan_id>0){
					$AR_PAN = SelectTable("tbl_mem_pancard","pan_file","pan_id='$pan_id'");
					$final_location = $fldvPath."upload/pancard/".$AR_PAN['pan_file'];
					$fldvImageArr= @getimagesize($final_location);
					if ($fldvImageArr['mime']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					$this->SqlModel->deleteRecord("tbl_mem_pancard",array("pan_id"=>$pan_id));

					set_message("success","You have successfully deleted your pancard");
				}else{
					set_message("warning","Unable to delete pancard, please try again");
				}
				redirect_page("member","pancard",array());
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/member/pancard',$data);
	}
	
	public function document(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$document_id = ($form_data['document_id'])? _d($form_data['document_id']):_d($segment['document_id']);
		
		switch($action_request){
			case "DELETE":
				if($document_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->updateRecord("tbl_mem_doc",$data,array("document_id"=>$document_id));
					set_message("success","You have successfully deleted your document");
				}else{
					set_message("warning","Unable to delete document, please try again");
				}
				redirect_page("member","document",array());
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/member/document',$data);
	}
	public function memberdelete(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		if($form_data['submitDeleteMember']=='1' && $this->input->post()!=''){
			$member_user_id = FCrtRplc($form_data['member_user_id']);
			$member_to_delete = $model->getMemberId($member_user_id);
			if($member_to_delete>0 && $member_to_delete>1){
				$AR_MEM = $model->getMember($member_to_delete);
				$AR_MEM['delete_member'] = true;
			}else{
				set_message("warning","This user cannot delete, please contact your administrator");
				redirect_page("member","memberdelete",array());
			}
		}
		
		if($form_data['deleteConfirmMember']=='1' && $this->input->post()!=''){
			$member_id = FCrtRplc(_d($form_data['member_id']));
			$direct_count = $model->BinaryCount($member_id,"DirectCount");
			$total_count = $model->BinaryCount($member_id,"TotalCount");
			
			if($member_id>0){
				if($direct_count==0 && $total_count==0){
					$model->deleteMemberFromTree($member_id);
					set_message("success","User deleted successfully");
					redirect_page("member","memberdelete",array());
				}else{
					set_message("warning","This user cannot be  delete, it has a donwline user");
					redirect_page("member","memberdelete",array());
				}
			}else{
				set_message("warning","Unbale to delete this user, please try again");
				redirect_page("member","memberdelete",array());
			}
		}
		
		$data['ROW'] = $AR_MEM;
		$this->load->view(ADMIN_FOLDER.'/member/memberdelete',$data);
	}
	
	
	public function form16(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		if($form_data['uploadForm16']=='1' && $this->input->post()!=''){
			#$member_user_id = FCrtRplc($form_data['user_id']);
			#$member_id = $model->getMemberId($member_user_id);
			#$AR_MEM = $model->getMember($member_id);
			
			$fldvFileName= $_FILES['form_file']['name'];
			$fldvFileTemp = $_FILES['form_file']['tmp_name'];
			$fldvFileType = $_FILES['form_file']['type'];
			$fldvFileError = $_FILES['form_file']['error'];
			
			$ext = explode(".",$fldvFileName);
			$pan_no = $ext['0'];
			$fExtn = strtolower(end($ext));
			$member_id = $model->getMemberIdPanCard($pan_no);
			if($pan_no!='' && strlen($pan_no)==10){
				if($fExtn=="pdf"){
					$path = $fldvPath."upload/form16/";
					$flddDOB_M =  FCrtRplc($form_data['flddDOB_M']);
					$flddDOB_Y =  FCrtRplc($form_data['flddDOB_Y']);
					$year_path = $path.$flddDOB_Y;
					if (!file_exists($year_path)) {	mkdir($year_path, 0777, true); }
					$month_path = $year_path."/".$flddDOB_M;
					if (!file_exists($month_path)) {mkdir($month_path, 0777, true);	}
					$final_path = $month_path;
					
					if($fldvFileError=="0"){
						
						$form_file = str_replace(" ","",$pan_no.".".$fExtn);
						$target_path = $final_path."/".$form_file;
						$form_path = BASE_PATH.$target_path;
						if(move_uploaded_file($fldvFileTemp, $target_path)){
							$data_file = array("form_file"=>$form_file,
								"pan_no"=>$pan_no,
								"form_year"=>$flddDOB_Y,
								"form_month"=>$flddDOB_M,
								"member_id"=>($member_id>0)? $member_id:0,
								"form_path"=>$form_path
							);
							$this->SqlModel->insertRecord("tbl_form16",$data_file);	
							set_message("success","Successfully uploaded member form 16");
							redirect_page("member","form16",array());					
						}	
					}
				}else{
					set_message("warning","Please upload only pdf file e.g: 'ABCDEFG789.pdf'");
					redirect_page("member","form16",array());	
				}
			}else{
				set_message("warning","Invalid pancard , please upload valid pancard");
				redirect_page("member","form16",array());
			}
		}
		$this->load->view(ADMIN_FOLDER.'/member/form16',$data);
	}
	
	public function form16list(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$this->load->view(ADMIN_FOLDER.'/member/form16list',$data);
	}
	
	public function contactus(){
		$this->load->view(ADMIN_FOLDER.'/member/contactus',$data);
	}
	
	public function memberneft(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = getLocalTime();
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$member_id = (_d($form_data['member_id']))? _d($form_data['member_id']):_d($segment['member_id']);
		
		switch($action_request){
			case "NEFT":
			if($member_id>0){
			$neft_sts = FCrtRplc($segment['neft_sts']);
			$this->SqlModel->insertRecord("tbl_mem_neft_sts",array("neft_sts"=>$neft_sts,"member_id"=>$member_id,"neft_sts_date"=>$today_date));
			set_message("success","You have successfully updated member NEFT Status");
			}else{
			set_message("warning","Unable to update status, please try again");
			}
			redirect_page("member","memberneft",array());
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/member/memberneft',$data);
	}
	
	public function upliner(){
		$this->load->view(ADMIN_FOLDER.'/member/upliner',$data);
	}
	
	public function downlineincome(){
		$this->load->view(ADMIN_FOLDER.'/member/downlineincome',$data);
	}
	
	public function downlinelevelincome(){
		$this->load->view(ADMIN_FOLDER.'/member/downlinelevelincome',$data);
	}
	
	public function directincome(){
		$this->load->view(ADMIN_FOLDER.'/member/directincome',$data);
	}
	
	public function kycid(){
		$this->load->view(ADMIN_FOLDER.'/member/kycid',$data);
	}
	
	public function kycaddress(){
		$this->load->view(ADMIN_FOLDER.'/member/kycaddress',$data);
	}
	
	public function upgradememberrank(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = getLocalTime();
		if($form_data['submitUpgradeRank']=='1' && $this->input->post()!=''){
			$password = FCrtRplc($form_data['password']);
			$oprt_id  = $this->session->userdata('oprt_id');
			$QR_CHECK = "SELECT COUNT(top.oprt_id) AS row_ctrl FROM tbl_operator AS top WHERE top.oprt_id='".$oprt_id."' 
						AND top.password='".$password."'";
			$AR_CHECK = $this->SqlModel->runQuery($QR_CHECK,true);
			if($AR_CHECK['row_ctrl']>0){
					$QR_MEM = "SELECT tm.* ,tree.nleft, tree.nright,  tr.rank_cmsn, tr.month_target, tr.tm_count, tr.rank_name
								FROM tbl_members AS tm 
								LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
								LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
								WHERE  tm.delete_sts>0 AND tm.rank_id>0
								ORDER BY tm.rank_id ASC";
					$RS_MEM  = $this->SqlModel->runQuery($QR_MEM);
					foreach($RS_MEM as $AR_MEM):
						$member_id = $AR_MEM['member_id'];
						$nleft = $AR_MEM['nleft'];
						$nright = $AR_MEM['nright'];
						$rank_id = $AR_MEM['rank_id'];
						$model->upgradeMemberRank($member_id,$nleft,$nright,$rank_id);
					endforeach;
					set_message("success","All member upgraded successfully");
					redirect_page("member","upgradememberrank",array());
			}else{
				set_message("warning","Invalid password, please try again");
				redirect_page("member","upgradememberrank",array());
			}
		}
		$this->load->view(ADMIN_FOLDER.'/member/upgradememberrank',$data);
	}
	
	
	public function subscription(){
		$this->load->view(ADMIN_FOLDER.'/member/subscription',$data);
	}
	
	public function membercollection(){
		$this->load->view(ADMIN_FOLDER.'/member/membercollection',$data);
	}
	
	public function reportcollection(){
		$this->load->view(ADMIN_FOLDER.'/member/reportcollection',$data);
	}
	
	
	
	public function royaltyachiver(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = getLocalTime();
		
		$member_id = _d($segment['member_id']);
		$royalty_id = _d($segment['royalty_id']);
		
		
		switch($segment['action_request']){
			case "DELETE":
				if($member_id>0 && $royalty_id>0){
					
					$this->SqlModel->deleteRecord("tbl_mem_royalty",array("member_id"=>$member_id,"royalty_id"=>$royalty_id));
					set_message("success","Deleted  successfully");
					redirect_page("member","royaltyachiver",array()); exit;
				}
			break;			
		}
		$this->load->view(ADMIN_FOLDER.'/member/royaltyachiver',$data);
	}
	
}
?>
