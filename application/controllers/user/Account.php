<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   $this->load->view('mailer/phpmailer');
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
	 
	public function profile(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$AR_MEM = $model->getMember($member_id);

	
		
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			$first_name = FCrtRplc($form_data['first_name']);
			$last_name = FCrtRplc($form_data['last_name']);
			$email_address = FCrtRplc($form_data['email_address']);
			$member_mobile = FCrtRplc($form_data['member_mobile']);
			
			$current_address = FCrtRplc($form_data['current_address']);
			$land_mark = FCrtRplc($form_data['land_mark']);
			$state_name = FCrtRplc($form_data['state_name']);
			$city_name = FCrtRplc($form_data['city_name']);
			$pin_code = FCrtRplc($form_data['pin_code']);

			$date_of_birth = InsertDate($form_data['date_of_birth']);
					
			
			$data = array("first_name"=>$first_name,
				"last_name"=>$last_name,
				"member_email"=>$email_address,
				"member_mobile"=>$member_mobile,
				
				"land_mark"=>$land_mark,
				"city_name"=>$city_name,
				"state_name"=>$state_name,
				"pin_code"=>$pin_code,
				"date_of_birth"=>$date_of_birth,
				"current_address"=>$current_address
				
			);		
			if($member_id>0){
				$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
				set_message("success","Successfully updated member  detail");
				redirect_member("account","profile",array());
			}else{
				set_message("warning","Unable to update, please try again");
				redirect_member("account","profile",array());
			}		
		}
	
		$data['ROW']=$AR_MEM;
		$this->load->view(MEMBER_FOLDER.'/account/profile',$data);
	}
	
	public function bankdetail(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$AR_MEM = $model->getMember($member_id);

	
		
		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			$bank_acct_holder = FCrtRplc($form_data['bank_acct_holder']);
			
			
			$bank_name = FCrtRplc($form_data['bank_name']);
			$account_number = FCrtRplc($form_data['account_number']);
			$branch = FCrtRplc($form_data['branch']);
			$ifc_code = FCrtRplc($form_data['ifc_code']);				
			
			$data = array("bank_acct_holder"=>$bank_acct_holder,
				"bank_name"=>$bank_name,
				"account_number"=>$account_number,
				"branch"=>$branch,
				"ifc_code"=>$ifc_code			
			);		
			if($member_id>0){
				$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
				$this->SqlModel->deleteRecord("tbl_mem_neft_sts",array("member_id"=>$member_id));
				$model->uploadCancelCheque(array("member_id"=>$member_id),$_FILES,"");
				set_message("success","Successfully updated bank Detail");
				redirect_member("account","bankdetail",array());
			}else{
				set_message("warning","Unable to update, please try again");
				redirect_member("account","bankdetail",array());
			}		
		}
	
		$data['ROW']=$AR_MEM;
		$this->load->view(MEMBER_FOLDER.'/account/bankdetail',$data);
	}
	
	public function profiledetail(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$AR_MEM = $model->getMember($member_id);
		$data['ROW']=$AR_MEM;
		$this->load->view(MEMBER_FOLDER.'/account/profiledetail',$data);
	}
	
	public function avtar(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		
		if($form_data['updateProfileAvatar']==1 && $this->input->post()!=''){
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
						redirect_member("account","avtar",array());
						
					}
			}
		}
		
		$this->load->view(MEMBER_FOLDER.'/account/avtar',$data);
	}
	
	public function changepassword(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		
		if($form_data['submitMemberSavePassword']==1 && $this->input->post()!=''){
			$old_password = FCrtRplc($form_data['old_password']);
			$user_password = FCrtRplc($form_data['user_password']);
			$confirm_user_password = FCrtRplc($form_data['confirm_user_password']);	
			if($old_password!=$user_password){
				if($model->checkOldPassword($member_id,$old_password)>0){
					$data = array("user_password"=>$user_password);
					$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
					set_message("success","Password changed successfully");
					redirect_member("account","changepassword",""); 
				}else{
					set_message("warning","Invalid old password");
					redirect_member("account","changepassword",""); 
				}
			}else{
				set_message("warning","New password must be different form old-password");
				redirect_member("account","changepassword",""); 
			}
		}
		
		$QR_CHECK = "SELECT tm.* FROM tbl_members AS tm WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		$this->load->view(MEMBER_FOLDER.'/account/changepassword',$data);
	}
	
	public function changetrnspassword(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		
		if($form_data['submit-trns-password']!='' && $this->input->post()!=''){
			$old_password = FCrtRplc($form_data['old_password']);
			$user_password = FCrtRplc($form_data['user_password']);
			$confirm_user_password = FCrtRplc($form_data['confirm_user_password']);	
			if($old_password!=$user_password){
				if($model->checkTrnsPassword($member_id,$old_password)>0){
					$data = array("trns_password"=>$user_password);
					$this->SqlModel->updateRecord("tbl_members",$data,array("member_id"=>$member_id));
					set_message("success","Password changed successfully");
					redirect_member("account","changetrnspassword",""); 
				}else{
					set_message("warning","Invalid old password");
					redirect_member("account","changetrnspassword",""); 
				}
			}else{
				set_message("warning","New password must be different form old-password");
				redirect_member("account","changetrnspassword",""); 
			}
		}
		
		$data['ROW']=$model->getMember($member_id);
		$this->load->view(MEMBER_FOLDER.'/account/changetrnspassword',$data);
	}
	
	public function userlogs(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$this->load->view(MEMBER_FOLDER.'/account/userlogs',$data);
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
				redirect_member("account","document",array());
			break;
		}
		
		if($form_data['saveDocument']==1 && $this->input->post()!=''){
			$fldvPath = "";
			
			if($_FILES['file_name']['error']=="0"){
				$file_type = $_FILES['file_name']['type'];
				$file_size = $_FILES['file_name']['size'];
				$ext = explode(".",$_FILES['file_name']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$file_name = $fldvUniqueNo.rand(100,999)."_". str_replace(" ","",rand(100,999).".".$fExtn);
				$target_path = $fldvPath."upload/document/".$file_name;
				$AR_MEM = SelectTable("tbl_mem_doc","file_name","member_id='$member_id'");

				$final_location = $fldvPath."upload/document/".$AR_MEM['file_name'];
				$fldvImageArr= @getimagesize($final_location);
				if ($fldvImageArr['mime']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(move_uploaded_file($_FILES['file_name']['tmp_name'], $target_path)){
						$data = array("member_id"=>$member_id,
							"file_name"=>$file_name,
							"file_type"=>$file_type,
							"file_size"=>$file_size
						);
						$this->SqlModel->insertRecord("tbl_mem_doc",$data);
					}
			}
			
			set_message("success","Your document saved successfully");
			redirect_member("account","document",array());
				
		}
		
		$QR_CHECK = "SELECT tm.* FROM tbl_members AS tm WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		$this->load->view(MEMBER_FOLDER.'/account/document',$data);
	}
	
	public function kyc(){
		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$id_type = FCrtRplc($form_data['id_type']);
		$add_type = FCrtRplc($form_data['add_type']);
		
		$document_id = FCrtRplc($form_data['document_id']);
		$document_add = FCrtRplc($form_data['document_add']);
		if($form_data['saveKycForm']==1 && $this->input->post()!=''){
			$fldvPath = "";
			
			if($_FILES['file_photo']['error']=="0"){
				$ext = explode(".",$_FILES['file_photo']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$file_photo = $fldvUniqueNo."_photo_". str_replace(" ","",$member_id.".".$fExtn);
				$target_path = $fldvPath."upload/kyc/".$file_photo;
				
				$AR_MEM = SelectTable("tbl_mem_kyc","file_photo","member_id='$member_id'");
				$final_location = $fldvPath."upload/kyc/".$AR_MEM['file_name'];
				$fldvImageArr= @getimagesize($final_location);
				if ($fldvImageArr['mime']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(move_uploaded_file($_FILES['file_photo']['tmp_name'], $target_path)){
						$kyc_id = $model->getKycId($member_id);
						$data = array("member_id"=>$member_id,
							"file_photo"=>$file_photo
						);
						if($kyc_id>0){
							$this->SqlModel->updateRecord("tbl_mem_kyc",$data,array("kyc_id"=>$kyc_id));
						}else{
							$this->SqlModel->insertRecord("tbl_mem_kyc",$data);
						}
						set_message("success","KYC photo uploaded successfull");
						redirect_member("account","kyc",array());
					}
			}
			
			if($_FILES['file_address']['error']=="0"){
				$ext = explode(".",$_FILES['file_address']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$file_address = $fldvUniqueNo."_address_". str_replace(" ","",$member_id.".".$fExtn);
				$target_path = $fldvPath."upload/kyc/".$file_address;
				
				$AR_MEM = SelectTable("tbl_mem_kyc","file_address","member_id='$member_id'");
				$final_location = $fldvPath."upload/kyc/".$AR_MEM['file_address'];
				$fldvImageArr= @getimagesize($final_location);
				if ($fldvImageArr['mime']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(move_uploaded_file($_FILES['file_address']['tmp_name'], $target_path)){
						$kyc_id = $model->getKycId($member_id);
						$data = array("member_id"=>$member_id,
							"add_type"=>($add_type!='')? $add_type:" ",
							"document_add"=>($document_add)? $document_add:" ",
							"file_address"=>$file_address
						);
						if($kyc_id>0){
							$this->SqlModel->updateRecord("tbl_mem_kyc",$data,array("kyc_id"=>$kyc_id));
						}else{
							$this->SqlModel->insertRecord("tbl_mem_kyc",$data);
						}
						set_message("success","KYC Adress Proof uploaded successfull");
						redirect_member("account","kyc",array());
					}
			}
			
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
							"file_passport"=>$file_passport
						);
						if($id_type=="PAN CARD"){
							$new_name = ($document_id)? $document_id:$fldvUniqueNo;
							$file_name = $new_name."_pan_". str_replace(" ","",$member_id.".".$fExtn);
							copyImg($file_name,$target_path,"pancard");
							$pan_data = array("member_id"=>$member_id,
								"pan_file"=>$file_name,
								"pan_no"=>($document_id)? $document_id:" ",
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
						set_message("success","KYC Photo Id Proof uploaded successfull");
						redirect_member("account","kyc",array());
					}
			}
			
			if($_FILES['kyc_form']['error']=="0"){
				$ext = explode(".",$_FILES['kyc_form']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$kyc_form = $fldvUniqueNo."_form_". str_replace(" ","",$member_id.".".$fExtn);
				$target_path = $fldvPath."upload/kyc/".$kyc_form;
				
				$AR_MEM = SelectTable("tbl_mem_kyc","kyc_form","member_id='$member_id'");
				$final_location = $fldvPath."upload/kyc/".$AR_MEM['kyc_form'];
				$fldvImageArr= @getimagesize($final_location);
				if ($fldvImageArr['mime']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(move_uploaded_file($_FILES['kyc_form']['tmp_name'], $target_path)){
						$kyc_id = $model->getKycId($member_id);
						$data = array("member_id"=>$member_id,
							"kyc_form"=>$kyc_form
						);
						if($kyc_id>0){
							$this->SqlModel->updateRecord("tbl_mem_kyc",$data,array("kyc_id"=>$kyc_id));
						}else{
							$this->SqlModel->insertRecord("tbl_mem_kyc",$data);
						}
						set_message("success","KYC form uploaded successfull");
						redirect_member("account","kyc",array());
					}
			}
			
		}
			
		$fetchRow = $model->getMember($member_id);
		$data['ROW']=$fetchRow;
		$this->load->view(MEMBER_FOLDER.'/account/kyc',$data);
	}

	public function pancard(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$pan_no = FCrtRplc($form_data['pan_no']);
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
				redirect_member("account","pancard",array());
			break;
		}
		
		if($form_data['savePancard']==1 && $this->input->post()!=''){
			$fldvPath = "";
			
			if($_FILES['pan_file']['error']=="0"){
				$file_type = $_FILES['pan_file']['type'];
				$file_size = $_FILES['pan_file']['size'];
				$ext = explode(".",$_FILES['pan_file']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$new_name = ($pan_no)? $pan_no:$fldvUniqueNo;
				$pan_file = $new_name."_pan_". str_replace(" ","",$member_id.".".$fExtn);
				$target_path = $fldvPath."upload/pancard/".$pan_file;
				$AR_MEM = SelectTable("tbl_mem_pancard","pan_file","member_id='$member_id'");

				$final_location = $fldvPath."upload/pancard/".$AR_MEM['pan_file'];
				$fldvImageArr= @getimagesize($final_location);
				if ($fldvImageArr['mime']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(move_uploaded_file($_FILES['pan_file']['tmp_name'], $target_path)){
						$data = array("member_id"=>$member_id,
							"pan_file"=>$pan_file,
							"pan_no"=>$pan_no
						);
						$this->SqlModel->deleteRecord("tbl_mem_pancard",array("member_id"=>$member_id));
						$this->SqlModel->insertRecord("tbl_mem_pancard",$data);
					}
			}
			
			set_message("success","Your pancard saved successfully");
			redirect_member("account","pancard",array());
				
		}
		
		$this->load->view(MEMBER_FOLDER.'/account/pancard',$data);
	}
	
	public function copykyc(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		
		if($form_data['submitKycCopy']!='' && $this->input->post()!=''){
			$user_id_ref = FCrtRplc($form_data['user_id_ref']);
			$user_password_ref = FCrtRplc($form_data['user_password_ref']);
			$QR_CHECK = "SELECT tm.* FROM tbl_members AS tm WHERE 
			( tm.user_id LIKE '$user_id_ref' OR tm.member_email LIKE '$user_id_ref' OR tm.user_name LIKE '$user_id_ref' ) 
			AND tm.user_password='$user_password_ref' AND tm.delete_sts>0";
			$AR_RT = $this->SqlModel->runQuery($QR_CHECK,true);
			
			
			$member_id_ref = $AR_RT['member_id'];
			if($member_id_ref>0){
				$QR_KYC = "SELECT COUNT(tmk.kyc_id) AS fldiCtrl FROM tbl_mem_kyc AS tmk WHERE tmk.member_id='".$member_id."' AND tmk.approved_sts=0";
				$AR_KYC = $this->SqlModel->runQuery($QR_KYC,true);
				if($AR_KYC['fldiCtrl']==0){
					$QR_NEW_KYC = "SELECT tmk.* FROM tbl_mem_kyc AS tmk WHERE tmk.member_id='".$member_id."'";
					$RS_OLD_KYC = $this->SqlModel->runQuery($QR_NEW_KYC);
					if(count($RS_OLD_KYC)>0){
						foreach($RS_OLD_KYC as $AR_NEW_KYC):
							$data = array("member_id"=>$member_id_ref,
								"file_name"=>$AR_NEW_KYC['file_name'],
								"file_type"=>$AR_NEW_KYC['file_type'],
								"file_name"=>$AR_NEW_KYC['file_name'],
								"approved_date"=>$AR_NEW_KYC['approved_date'],
								"approved_sts"=>$AR_NEW_KYC['approved_sts']
							);
							$this->SqlModel->insertRecord("tbl_mem_kyc",$data);
						endforeach;
						set_message("success","We have successfully add kyc to this User Id : ".$user_id_ref."");
						redirect_member("account","kyc","");
					}else{
						set_message("warning","Unable to process your request, please try again");
						redirect_member("account","kyc","");	
					}	
				}else{
					set_message("warning","it seem , your KYC is in pending stage");
					redirect_member("account","kyc","");	
				}
			}else{
				set_message("warning","Invalid login credentials");
				redirect_member("account","kyc","");
			}
		}
	}
	
	
	
	public function accountsetting(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		if($form_data['saveConfig']==1 && $this->input->post()!=''){
			
			$model->setConfigMember("EMAIL_FROM_COMPANY",FCrtRplc($form_data['EMAIL_FROM_COMPANY']));
			$model->setConfigMember("EMAIL_FROM_UPLINE",FCrtRplc($form_data['EMAIL_FROM_UPLINE']));
			$model->setConfigMember("LOG_IP",FCrtRplc($form_data['LOG_IP']));
			$model->setConfigMember("NOTIFY_CHANGES",FCrtRplc($form_data['NOTIFY_CHANGES']));
			$model->setConfigMember("DISPLAY_NAME",FCrtRplc($form_data['DISPLAY_NAME']));
			$model->setConfigMember("DISPLAY_EMAIL",FCrtRplc($form_data['DISPLAY_EMAIL']));
			set_message("success","Successfully updated changes");
			redirect_member("account","accountsetting","");
		}
		$this->load->view(MEMBER_FOLDER.'/account/accountsetting',$data);
	}
	
	
	
	
	public function transactionpassword(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$AR_MEM = $model->getMember($member_id);
		
		if($form_data['submitMemberSavePassword']==1 && $this->input->post()!=''){
			$old_password = FCrtRplc($form_data['old_password']);
			$trns_password = FCrtRplc($form_data['trns_password']);
			$confirm_trns_password = FCrtRplc($form_data['confirm_trns_password']);	
			if($old_password!=$trns_password){
				if($model->checkTrnsPassword($member_id,$old_password)>0){
					$sms_otp = $model->sendTransactionSMS($AR_MEM['mobile_number']);
					$data = array("member_id"=>$member_id,
						"new_password"=>$trns_password,
						"sms_otp"=>$sms_otp,
						"sms_type"=>"TRNS",
						"mobile_number"=>$AR_MEM['mobile_number']
					);
					$request_id = $this->SqlModel->insertRecord("tbl_sms_otp",$data);
					set_message("success","Please verify otp from your registered mobile number");
					redirect_member("dashboard","verifyotp",array("request_id"=>_e($request_id))); 
				}else{
					set_message("warning","Invalid old password");
					redirect_member("account","transactionpassword",""); 
				}
			}else{
				set_message("warning","New password must be different form old-password");
				redirect_member("account","transactionpassword",""); 
			}
		}
		
		$QR_CHECK = "SELECT tm.* FROM tbl_members AS tm WHERE tm.member_id='$member_id'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		$this->load->view(MEMBER_FOLDER.'/account/transactionpassword',$data);
	}
	
	
	
	public function news(){
		$this->load->view(MEMBER_FOLDER.'/account/news',$data);
	}
	
	public function newsdetails(){
		$this->load->view(MEMBER_FOLDER.'/account/newsdetails',$data);
	}
	
	public function faq(){
		$this->load->view(MEMBER_FOLDER.'/account/faq',$data);
	}
	
	public function upgradepackage(){
		$this->load->view(MEMBER_FOLDER.'/account/upgradepackage',$data);
	}
	
	public function paymentpackage(){
		$this->load->view(MEMBER_FOLDER.'/account/paymentpackage',$data);
	}
	
	public function welcomeletter(){
		$this->load->view(MEMBER_FOLDER.'/account/welcomeletter',$data);
	}
	
	public function welcomeletterpdf(){	
	$model = new OperationModel();
	$member_id = $this->session->userdata('mem_id');
	$AR_MEM = $model->getMember($member_id);
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$output .='<div class="modal-dialog">';
			
			$output .='<div class="modal-content">';
			$output .='<div class="modal-header">';
				
				$output .='<img src="'.LOGO.'" alt="'.WEBSITE.'"> </div>';
			    $output .='<div class="modal-body" style="margin-bottom:0px; padding-bottom:2px;">';
				$output .='<p class="cmntext" style="line-height:30px; text-align:justify"> Dear <span class="smalltxt" style="line-height:30px; text-align:justify">'.$AR_MEM['full_name'].'</span>,<br />';
				$output .='Your application dated '.DisplayDate($AR_MEM['date_join']).' is received.  After scrutinizing the same you are found to be competent person.';
				$output .='Given below are the ID No. along with other  details for accessing your account &amp; any related information at our  Official';
				$output .='website: <u><em>www.'.DOMAIN.'</em></u>. We suggest you to change your password immediately  &amp; if any problem relating to login occurs,  &amp;';
				$output .='you need any assistance  please do not hesitate to contact us at our Email Id: <u><em>support@'.DOMAIN.'</em></u>. <br />';
				$output .='Last but not least you are a very important pillar of our Network marketing  System, it is very important that who so ever works will be rewarded with';
				$output .='maximum returns, and it is very necessary for all customer including you to work  hard to promote our products and earn maximum income and assured payouts. </p>';
				$output .='<div class="clearfix">&nbsp;</div>';
				$output .='<label><strong>Your login credential :</strong></label>';
				$output .='<br>';
				$output .='<strong>Username : </strong> &nbsp;'.$AR_MEM['user_id'].'<br>';
				$output .='<strong>Password :</strong> &nbsp;'.$AR_MEM['user_password'].'<br>';
				$output .='<strong>Transaction Password :</strong> &nbsp; '.$AR_MEM['trns_password'].'<br>';
				$output .='<h5 class="btm"> The '.WEBSITE.' Team, <br />';
				$output .='<strong> Best Regards </strong> </h5>';
		$output .='</div>';
		$output .='<div class="modal-footer"></div>';
		$output .='</div></div>';
		$this->load->view('pdflib/mpdf');
		$mpdf=new mPDF('c'); 
		#$mpdf->showImageErrors = true;
		$css_path = BASE_PATH."public/css/webcustom.css";
		$stylesheet = file_get_contents($css_path);
		$html = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($output);
		$mpdf->Output();
		exit;
	}
	
	
	public function comming_soon(){
		$this->load->view(MEMBER_FOLDER.'/comming_soon',$data);
	}
	

	public function download(){
		$this->load->view(MEMBER_FOLDER.'/account/download',$data);
	}
	
	public function newjoining(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$today_date = InsertDate(getLocalTime());

		if($form_data['submitMemberSave']==1 && $this->input->post()!=''){
			$first_name = FCrtRplc($form_data['first_name']);
			$last_name = FCrtRplc($form_data['last_name']);
			$member_email = FCrtRplc($form_data['member_email']);
			$member_mobile = FCrtRplc($form_data['member_mobile']);
			$gender = FCrtRplc($form_data['gender']);
			
			$current_address = FCrtRplc($form_data['current_address']);
			$state_name = FCrtRplc($form_data['state_name']);
			$city_name = FCrtRplc($form_data['city_name']);
			$pin_code = FCrtRplc($form_data['pin_code']);
			
			$date_of_birth = InsertDate($form_data['date_of_birth']);
			$user_password = FCrtRplc($form_data['user_password']);

			
			$sponsor_user_id = ($form_data['sponsor_user_id']!='')? FCrtRplc($form_data['sponsor_user_id']):$model->getCompanyId();
			
			$AR_GET = $model->getSponsorId($sponsor_user_id);

			$sponsor_id = $spil_id = ($AR_GET['sponsor_id']!='')? $AR_GET['sponsor_id']:$model->getMemberId($sponsor_user_id);
				
				
				if($model->checkCount("tbl_members","member_mobile",$member_mobile)==0){
					$Ctrl += ($AR_GET['sponsor_id']>0)? 0:1;
					$data = array("first_name"=>$first_name,
						"last_name"=>$last_name,
						"user_id"=>$member_mobile,
						"user_name"=>$member_mobile,
						"member_email"=>$member_email,
						"member_mobile"=>$member_mobile,
						"gender"=>$gender,
						"current_address"=>$current_address,
						"state_name"=>$state_name,
						"city_name"=>$city_name,
						"pin_code"=>$pin_code,
						"sponsor_id"=>($sponsor_id>0)? $sponsor_id:0,
						"date_join"=>getLocalTime(),
						"date_of_birth"=>$date_of_birth,
						"user_password"=>$user_password,
						"pan_status"=>"N",
						"status"=>"Y",
						"last_login"=>getLocalTime(),
						"login_ip"=>$_SERVER['REMOTE_ADDR'],
						"block_sts"=>"N",
						"sms_sts"=>"N",
						"upgrade_date"=>getLocalTime()
						
					);		
					if($Ctrl==0){
							$member_id = $this->SqlModel->insertRecord("tbl_members",$data);
							  /********* Tree **************/
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
							/***************** End Tree ****************/
							set_message("success","Please enter login info of member");
							redirect_member("account","welcomeletter",array("member_id"=>_e($member_id)));
					}else{
							set_message("warning","Failed , unable to process your request , please try again");
							redirect_member("account","profile",array());
					}
				}else{
					set_message("warning","This mobile no is  already register with us");
					redirect_member("account","profile",array());
				}
		}
		$QR_CHECK = "SELECT tm.* FROM tbl_members AS tm WHERE tm.member_id='".$member_id."'";
		$fetchRow = $this->SqlModel->runQuery($QR_CHECK,true);
		$data['ROW']=$fetchRow;
		$this->load->view(MEMBER_FOLDER.'/account/newjoining',$data);
	}	
	
	
	public function businessplan(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$today_date = InsertDate(getLocalTime());
		
		$this->load->view(MEMBER_FOLDER.'/account/businessplan',$data);
	}
	
	public function form16(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$today_date = InsertDate(getLocalTime());
		
		$this->load->view(MEMBER_FOLDER.'/account/form16',$data);
	}
	
	public function testimonial(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$AR_MEM = $model->getMember($member_id);
		if($form_data['submit-testimonail']!='' && $this->input->post()!=''){
			$testimonial_by = $AR_MEM['full_name'];
			$testimonial_detail = FCrtRplc($form_data['testimonial_detail']);
			$data = array("testimonial_by"=>$testimonial_by,
				"member_id"=>$member_id,
				"testimonial_sts"=>0,
				"testimonial_detail"=>$testimonial_detail);
			if($testimonial_by!='' && $testimonial_detail!=''){
				$this->SqlModel->insertRecord("tbl_testimonial",$data);
			}
			set_message("success","Your have successfully posted a testimonial");
			redirect_member("account","testimonial","");
		}
		$this->load->view(MEMBER_FOLDER.'/account/testimonial',$data);
	}
	
	
	public function topup(){
		$model = new OperationModel();
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$form_data = $this->input->post();
		
		$segment = $this->uri->uri_to_assoc(2);
		$wallet_id = $model->getDefaultWallet();
		
		
		$type_id = FcrtRplc($form_data['type_id']);
		$pin_price = FcrtRplc($form_data['pin_price']);
		$payment_type = FcrtRplc($form_data['payment_type']);
		$member_id = $model->getMemberId($form_data['user_id']);
		$AR_MEM = $model->getMember($member_id);
		$sponsor_id = $model->getSponsor($member_id);
		
		$AR_PACK = $model->getPinType($type_id);
		$pin_price = $AR_PACK['pin_price'];
		$gst_price = $AR_PACK['gst_price'];
		$net_price = $AR_PACK['net_price'];
		$type_id = $AR_PACK['type_id'];
		$pin_type = $AR_PACK['pin_type'];
		$LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
		$order_no = UniqueId("ORDER_NO");
		$trns_remark = "PACKAGE UPGRADE[".$order_no."]";
		
		if($form_data['submitTopup']!='' && $this->input->post()!=''){
			if($member_id>0){
				if($model->checkCountPro("tbl_subscription","type_id='".$type_id."' AND member_id='".$member_id."'")==0){
					$data_sub = array("order_no"=>$order_no,
						"member_id"=>$member_id,
						"type_id"=>$type_id,
						"pin_name"=>$AR_PACK['pin_name'],
						"pin_price"=>getTool($pin_price,0),
						"gst_price"=>getTool($gst_price,0),
						"net_amount"=>getTool($net_price,0),
						
						"direct_bonus"=>getTool($AR_PACK['direct_bonus'],0),
						
						"prod_pv"=>getTool($AR_PACK['prod_pv'],0),
						"pin_type"=>getTool($pin_type,'DFT'),
						"payment_type"=>$payment_type,
						"date_from"=>$current_date
					);
					switch($payment_type):
						case "WALLET":
							
							$trns_password = FcrtRplc($form_data['trns_password']);
							if($model->checkTrnsPassword($this->session->userdata("mem_id"),$trns_password)>0){
								if($LDGR['net_balance']>=$net_price && $LDGR['net_balance']>0){
									$subcription_id = $this->SqlModel->insertRecord("tbl_subscription",$data_sub);									
									$model->setSubscriptionPost($subcription_id,$type_id);
									$AR_SEND['trns_for']="UPGRADE";
									$AR_SEND['trans_ref_no']=$order_no;
									$model->wallet_transaction($wallet_id,$member_id,"Dr",$net_price,$trns_remark,$today_date,$order_no,$AR_SEND);
																	
									$this->SqlModel->updateRecord("tbl_members",array("subcription_id"=>$subcription_id,"upgrade_date"=>$current_date),array("member_id"=>$member_id));
									$model->setReferralIncome($member_id,$subcription_id);
									$model->point_transaction($member_id,"Cr","PKG",$AR_PACK['prod_pv'],0,$pin_price,$pin_price,$order_no,$today_date);
									set_message("success","You have successfully upgraded your package");
									redirect_member("account","topup",'');
								}else{
									set_message("warning","You don't have enough credit to upgrade this package");
									redirect_member("account","topup","");
								}
							}else{
								set_message("warning","Invalid password, please try again");
								redirect_member("account","topup","");
							}
						break;
						case "EPIN":
							$pin_no = FCrtRplc($form_data['pin_no']);
							$pin_key = FCrtRplc($form_data['pin_key']);
							$AR_PIN = $model->getPinDetail($pin_no,$pin_key);
							if($pin_no!='' && $pin_key!=''){
								if($AR_PIN['block_sts']=="N"){
									if($AR_PIN['pin_sts']=="N" && $AR_PIN['use_member_id']==0){
										$subcription_id = $this->SqlModel->insertRecord("tbl_subscription",$data_sub);
										$model->setSubscriptionPost($subcription_id,$type_id);
										$model->updatePinDetail($AR_PIN['pin_id'],$member_id);
										
										$this->SqlModel->updateRecord("tbl_members",array("subcription_id"=>$subcription_id,"upgrade_date"=>$current_date),array("member_id"=>$member_id));
									
										$model->setReferralIncome($member_id,$subcription_id);
										$model->point_transaction($member_id,"Cr","PKG",$AR_PACK['prod_pv'],0,$pin_price,$pin_price,$order_no,$today_date);
										set_message("success","You have successfully upgraded your package");
										redirect_member("account","topup","");
									}else{
										set_message("warning","This pin is already used by other member");
										redirect_member("account","topup","");
									}
								}else{
									set_message("warning","Sorry this pin is blocked, please contact our support team");
									redirect_member("account","topup","");
								}
							}else{
								set_message("warning","Please enter pin-no & pin-key details");
								redirect_member("account","topup","");
							}
						break;
						case "ONLINE":
							set_message("warning","Currently Online transaction is not available");
							redirect_member("account","topup","");
						break;
						default:
							set_message("warning","Unable to proceed this request , please try after some time");
							redirect_member("account","topup","");
						break;
					endswitch;
				}else{
					set_message("warning","You have already subcribe for this package");
					redirect_member("account","topup","");	
				}
			}else{
				set_message("warning","Invalid User Id, please enter valid");
				redirect_member("account","topup","");
			}
		}
		
		$this->load->view(MEMBER_FOLDER.'/account/topup',$data);
	}
	
}
