<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   #$this->load->view('excel/reader');
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	public function royaltyincome(){
		$this->load->view(ADMIN_FOLDER.'/bonus/royaltyincome',$data);	
	}	
	
	public function royaltyincome2(){
		$this->load->view(ADMIN_FOLDER.'/bonus/royaltyincome2',$data);	
	}	
	
	public function directincome(){
		$this->load->view(ADMIN_FOLDER.'/bonus/directincome',$data);	
	}
	
	
	public function carbudget(){
		$this->load->view(ADMIN_FOLDER.'/bonus/carbudget',$data);	
	}
	
	public function bikebudget(){
		$this->load->view(ADMIN_FOLDER.'/bonus/bikebudget',$data);	
	}
	
	public function cmsnmaster(){
		$this->load->view(ADMIN_FOLDER.'/bonus/cmsnmaster',$data);	
	}
	
	
	
	public function binaryincome(){
		$this->load->view(ADMIN_FOLDER.'/bonus/binaryincome',$data);	
	}
	
	public function binaryincomelist(){
		$this->load->view(ADMIN_FOLDER.'/bonus/binaryincomelist',$data);	
	}
	
	public function binaryincomerepurchase(){
		$this->load->view(ADMIN_FOLDER.'/bonus/binaryincomerepurchase',$data);	
	}
	
	public function binaryincomerepurchaselist(){
		$this->load->view(ADMIN_FOLDER.'/bonus/binaryincomerepurchaselist',$data);	
	}
	
	public function levelincome(){
		$this->load->view(ADMIN_FOLDER.'/bonus/levelincome',$data);	
	}
	
	public function awardreward(){
		$this->load->view(ADMIN_FOLDER.'/bonus/awardreward',$data);	
	}
	
	public function levelincomebinary(){
		$this->load->view(ADMIN_FOLDER.'/bonus/levelincomebinary',$data);	
	}
	
	public function lifereturnincome(){
		$this->load->view(ADMIN_FOLDER.'/bonus/lifereturnincome',$data);	
	}
	
	
	public function cmsnother(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$cmsn_other_id = (_d($form_data['cmsn_other_id'])>0)? _d($form_data['cmsn_other_id']):_d($segment['cmsn_other_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitCommission']==1 && $this->input->post()!=''){
					$model->uploadCommissionOther();
					set_message("success","You have successfully uploaded commission");
					redirect_page("bonus","cmsnotherlist",array());					
				}
			break;
			case "DELETE":
				if($cmsn_other_id>0){
					$this->SqlModel->deleteRecord("tbl_cmsn_other",array("cmsn_other_id"=>$cmsn_other_id));
					set_message("success","You have successfully deleted commission other");
				}else{
					set_message("warning","Failed , unable to delete news");
				}
				redirect_page("bonus","cmsnotherlist",array()); exit;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/bonus/cmsnother',$data);	
	}
	
	public function cmsnotherlist(){
		$this->load->view(ADMIN_FOLDER.'/bonus/cmsnotherlist',$data);	
	}
	
}