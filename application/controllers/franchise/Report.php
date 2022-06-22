<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}
	
	public function gsthsnreport(){
		$this->load->view(FRANCHISE_FOLDER.'/report/gsthsnreport',$data);
	}

	public function cashinhanddaily(){
		$this->load->view(FRANCHISE_FOLDER.'/report/cashinhanddaily',$data);
	}

	public function cashinhandannual(){
		$this->load->view(FRANCHISE_FOLDER.'/report/cashinhandannual',$data);
	}

	public function offerinvoices(){
		$this->load->view(FRANCHISE_FOLDER.'/report/offerinvoices',$data);
	}

	public function offerreport(){
		$this->load->view(FRANCHISE_FOLDER.'/report/offerreport',$data);
	}

	public function collection(){
		$this->load->view(FRANCHISE_FOLDER.'/report/collection',$data);
	}
	
	public function collectiongraph(){
		$this->load->view(FRANCHISE_FOLDER.'/report/collectiongraph',$data);
	}
	
	public function salesreport(){
		$this->load->view(FRANCHISE_FOLDER.'/report/salesreport',$data);
	}
	
 	
	
	
		
	
}
