<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operation extends MY_Controller {

	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	public function blank(){
		$model = new OperationModel();
		$franchisee_id = $this->session->userdata('fran_id');
		
		$AR_DT = $model->getFranchiseeDetail($franchisee_id);
		$data['ROW'] = $AR_DT;
		$this->load->view(FRANCHISE_FOLDER.'/operation/blank',$data);
	}
	
	
	public function coming_soon(){
		$this->load->view(FRANCHISE_FOLDER.'/operation/comming_soon',$data);
	}

	
}
