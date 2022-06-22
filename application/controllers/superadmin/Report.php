<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	public function orderreport(){
		$this->load->view(ADMIN_FOLDER.'/report/order-report',$data);
	}
	
	public function cashdepositreport(){
		$this->load->view(ADMIN_FOLDER.'/report/cashdepositreport',$data);
	}
	
	public function gsthsnreport(){
		$this->load->view(ADMIN_FOLDER.'/report/gsthsnreport',$data);
	}
	
	public function ewalletreport(){
		$this->load->view(ADMIN_FOLDER.'/report/ewalletreport',$data);
	}
	
	public function withdrawreport(){
		$this->load->view(ADMIN_FOLDER.'/report/withdrawreport',$data);
	}
	
	public function paymentreport(){
		$this->load->view(ADMIN_FOLDER.'/report/paymentreport',$data);
	}
	
	public function directoutreport(){
		$this->load->view(ADMIN_FOLDER.'/report/directoutreport',$data);
	}
	
	public function subidreport(){
		$this->load->view(ADMIN_FOLDER.'/report/subidreport',$data);
	}
	
}
?>