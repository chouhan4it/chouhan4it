<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Epin extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(FRANCHISE_PATH);		
		}
	}
			

	
	public function usedpin(){	
		$this->load->view(FRANCHISE_FOLDER.'/epin/usedpin',$data);
	}
		
	public function unusedpin(){	
		$this->load->view(FRANCHISE_FOLDER.'/epin/unusedpin',$data);
	}
	
	
	
}
?>