<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(BASE_PATH);
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function index(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$fran_id = $this->session->userdata('fran_id');
		

		
		$QR_FRAN = "SELECT tf.* FROM  tbl_franchisee AS tf WHERE tf.franchisee_id='".$fran_id."'";
		$AR_DT = $this->SqlModel->runQuery($QR_FRAN,true);
		$data['ROW'] = $AR_DT;
		$data['content'] = FRANCHISE_FOLDER."/homepage";
        $this->load->view(FRANCHISE_FOLDER."/layout/template", $data);
	}
	
	
	public function logout(){
		
		$this->session->unset_userdata('fran_id');
		 $this->session->unset_userdata('fran_user');
		 
		 set_message("success","You have successfully logout");
		 $path_url = generateSeoUrl("account","sellerlogin","");
		 redirect($path_url);
	}
	
	
}
