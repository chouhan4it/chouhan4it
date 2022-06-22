<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}
	
	
	public function retailcommission(){
		$this->load->view(FRANCHISE_FOLDER.'/bonus/retailcommission',$data);
	}
	
	
}
