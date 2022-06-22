<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	
	public function mailing(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
			
		if($form_data['submitMailing']==1 && $this->input->post()!=''){
			$model->setConfig("CONFIG_MASS_HOST",FCrtRplc($form_data['CONFIG_MASS_HOST']));
			$model->setConfig("CONFIG_MASS_PORT",FCrtRplc($form_data['CONFIG_MASS_PORT']));
			$model->setConfig("CONFIG_MASS_LOGIN",FCrtRplc($form_data['CONFIG_MASS_LOGIN']));
			$model->setConfig("CONFIG_MASS_PASSWORD",FCrtRplc($form_data['CONFIG_MASS_PASSWORD']));
			
			$model->setConfig("CONFIG_SYSTEM_HOST",FCrtRplc($form_data['CONFIG_SYSTEM_HOST']));
			$model->setConfig("CONFIG_SYSTEM_PORT",FCrtRplc($form_data['CONFIG_SYSTEM_PORT']));
			$model->setConfig("CONFIG_SYSTEM_LOGIN",FCrtRplc($form_data['CONFIG_SYSTEM_LOGIN']));
			$model->setConfig("CONFIG_SYSTEM_PASSWORD",FCrtRplc($form_data['CONFIG_SYSTEM_PASSWORD']));
			
			set_message("success","Successfully updated changes");
			redirect_page("system","mailing",array());
		}
		
		$this->load->view(ADMIN_FOLDER.'/system/mailing',$data);
	}
	
}
?>