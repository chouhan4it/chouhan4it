<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	  
	   parent::__construct();
	    #$this->load->library('parser');
	    #$this->load->library('PHPMailer/phpmailer');
	    if(!$this->isMemberLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}

	
	public function contactsupport(){
		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$enquiry_id = ($form_data['enquiry_id'])? $form_data['enquiry_id']:_d($segment['enquiry_id']);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		if($form_data['logaTicket']!='' && $this->input->post()!=''){
			$query_id = $form_data['query_id'];
			$enquiry_from = 'M';
			$from_id = $this->session->userdata('mem_id');
			$subject = $form_data['subject'];
			$enquiry_detail = $form_data['enquiry_detail'];
			$enquiry_sts = 'O';
			$enquiry_date =  $reply_date =  getLocalTime();
			$ticket_no = UniqueId("TICKET_NO");
			$data = array("enquiry_from"=>$enquiry_from,
				"from_id"=>$from_id,
				"type"=>$model->getQueryType($query_id),
				"query_id"=>$query_id,
				"subject"=>$subject,
				"enquiry_detail"=>$enquiry_detail,
				"enquiry_sts"=>$enquiry_sts,
				"enquiry_date"=>$enquiry_date,
				"reply_date"=>$reply_date,
				"ticket_no"=>$ticket_no
			);
			if($enquiry_id>0){
				$this->SqlModel->updateRecord("tbl_support",$data,array("enquiry_id"=>$enquiry_id));
			}else{
				$enquiry_id = $this->SqlModel->insertRecord("tbl_support",$data);
				$data_reply = array("member_id"=>$from_id,
					"enquiry_id"=>$enquiry_id,
					"enquiry_reply"=>$enquiry_detail,
					"enquiry_date"=>$enquiry_date,
					"reply_date"=>$reply_date
				);
				$parent_id = $this->SqlModel->insertRecord("tbl_support_rply",$data_reply);
				
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
			}			
			set_message("success","You have  successfully raised a ticket");
			redirect_member("support","contactsupport","");
		}
		if($action_request=="CLOSE"){
			if($enquiry_id>0){
				$data = array("enquiry_sts"=>"C");
				$this->SqlModel->updateRecord("tbl_support",$data,array("enquiry_id"=>$enquiry_id));
				set_message("success","You have  successfully closed a ticket");
				redirect_member("support","contactsupport","");
			}
		}
		
		$this->load->view(MEMBER_FOLDER.'/support/contactsupport');
	}
	
	public function conversation(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$today_date = InsertDate(getLocalTime());
		$member_id = $this->session->userdata('mem_id');
		$enquiry_id = $model->setgetEnquiry($member_id,$today_date);
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
				$this->SqlModel->updateRecord("tbl_support",array("enquiry_sts"=>"O","reply_date"=>$reply_date),array("enquiry_id"=>$enquiry_id));
				$fldvPath = "";
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
				
				redirect_member("support","conversation",array("enquiry_id"=>_e($enquiry_id)));
			}
			
		}
		
		$this->load->view(MEMBER_FOLDER.'/support/conversation');
	}
	
	
	
	public function faq(){
		$this->load->view(MEMBER_FOLDER.'/support/faq');
	}
	
}
