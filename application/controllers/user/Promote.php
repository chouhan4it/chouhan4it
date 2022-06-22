<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promote extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	  
	   parent::__construct();
	    #$this->load->library('parser');
	    #$this->load->library('PHPMailer/phpmailer');
	    if(!$this->isMemberLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}

	
	public function tellafriend(){
		
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		if($form_data['sendToFriend']!='' && $this->input->post()!=''){
			$mail_name_array = $form_data['mail_name'];
			$mail_email_array = $form_data['mail_email'];
			$email_body = $form_data['email_body'];
			$email_subject = $form_data['email_subject'];
			foreach($mail_name_array as $key=>$value):
				$mail_name = $mail_name_array[$key];
				$mail_email = $mail_email_array[$key];
				$AR_RT['mail_name'] = $mail_name;
				$AR_RT['mail_email'] = $mail_email;
				$AR_RT['email_subject'] = $email_subject;
				$AR_RT['email_body'] = $email_body;
				Send_Mail($AR_RT,"TELL_FRIEND");
			endforeach;
			set_message("success","Message successfully send to all member");
			redirect_member("promote","tellafriend","");
		}
		
		$this->load->view(MEMBER_FOLDER.'/promote/tellafriend');
	}
	
	
	
}
