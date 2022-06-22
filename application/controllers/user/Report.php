<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	    if(!$this->isMemberLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}
	 
	
	
	
	public function selfcollection(){
		$this->load->view(MEMBER_FOLDER.'/report/selfcollection',$data);
	}
	
	public function groupcollection(){
		$this->load->view(MEMBER_FOLDER.'/report/groupcollection',$data);
	}
	
	

	public function binaryincome(){
		$this->load->view(MEMBER_FOLDER.'/report/binaryincome',$data);
	}
	
	public function binaryincomerepurchase(){
		$this->load->view(MEMBER_FOLDER.'/report/binaryincomerepurchase',$data);
	}
	
	
	public function levelincome(){
		$this->load->view(MEMBER_FOLDER.'/report/levelincome',$data);
	}
	
	
	public function levelincomebinary(){
		$this->load->view(MEMBER_FOLDER.'/report/levelincomebinary',$data);
	}
	
	public function rewardincome(){				
		$this->load->view(MEMBER_FOLDER.'/report/rewardincome',$data);
	}
	
		
	public function downlineincome(){
		$this->load->view(MEMBER_FOLDER.'/report/downlineincome',$data);
	}
	
	public function directincome(){
		$this->load->view(MEMBER_FOLDER.'/report/directincome',$data);
	}
	
	public function royaltyincome(){
		$this->load->view(MEMBER_FOLDER.'/report/royaltyincome',$data);
	}
	
	public function royaltyincome2(){
		$this->load->view(MEMBER_FOLDER.'/report/royaltyincome2',$data);
	}
	
	public function ewallet(){
		$this->load->view(MEMBER_FOLDER.'/report/ewallet',$data);
	}
	
	public function order(){
		$this->load->view(MEMBER_FOLDER.'/report/order',$data);
	}
	
	public function commission(){
		$this->load->view(MEMBER_FOLDER.'/report/commission',$data);
	}
	
}
?>
