<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isMemberLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}

	public function tempdash(){
		$this->load->view(MEMBER_FOLDER.'/tempdash',$data);
	}

	public function cmsnmaster(){
		$this->load->view(MEMBER_FOLDER.'/bonus/cmsnmaster',$data);
	}

	public function cmsnstatement(){
		$this->load->view(MEMBER_FOLDER.'/bonus/cmsnstatement',$data);
	}

	public function calccommission(){
		$this->load->view(MEMBER_FOLDER.'/bonus/calccommission',$data);
	}
	
	public function cmsngraph(){
		$this->load->view(MEMBER_FOLDER.'/bonus/cmsngraph',$data);
	}

	public function cmsndifferential(){
		$this->load->view(MEMBER_FOLDER.'/bonus/cmsndifferential',$data);
	}
	
	public function seniorcmsnadditional(){		
		$this->load->view(MEMBER_FOLDER.'/bonus/seniorcmsnadditional',$data);
	}
	
	
	public function seniorleadership(){		
		$this->load->view(MEMBER_FOLDER.'/bonus/seniorleadership',$data);
	}
	
	public function allincome(){
		
		
		$this->load->view(MEMBER_FOLDER.'/bonus/allincome',$data);
	}
	
	public function carbudget(){
		$this->load->view(MEMBER_FOLDER.'/bonus/carbudget',$data);
	}
	
	public function bikebudget(){
		$this->load->view(MEMBER_FOLDER.'/bonus/bikebudget',$data);
	}
	
	public function rewardstatics(){
		$this->load->view(MEMBER_FOLDER.'/bonus/rewardstatics',$data);
	}
	
	public function jumpcadre(){	
		$this->load->view(MEMBER_FOLDER.'/bonus/jumpcadre',$data);
	}
	
	
}
?>