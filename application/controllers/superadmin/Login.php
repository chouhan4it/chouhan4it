<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	
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
	 
	public function index()
	{
		$this->load->view(ADMIN_FOLDER.'/login');
	}
	
	public function loginhandler(){
		$config = new SqlModel();
		$form_data = $this->input->post();
		if($form_data['loginSubmit']==1 && $this->input->post()!=''){
			$user_name = $form_data['user_name'];
			$user_password = $form_data['user_password'];
			$page_name = "login";
			$log_sts = "F";
			$browser = getBrowser();
			$operate_system = $browser['pattern'];
			$web_browser = $browser['browser'];
			$browser_version = $browser['version'];
			$oprt_ip = FCrtRplc($_SERVER['REMOTE_ADDR']);
			$login_time = getLocalTime();
			$logout_time = getLocalTime();
				
			set_message("warning","Invalid username & password");
			if($user_name!='' && $form_data!=''){
				$sel_query = $this->db->query("SELECT * FROM tbl_operator WHERE user_name='$user_name' AND password='$user_password'");
				$fetchRow = $sel_query->row_array();
				
				if($fetchRow['oprt_id']>0){
					$this->session->set_userdata('oprt_id',$fetchRow['oprt_id']);
					$this->session->set_userdata('group_id',$fetchRow['group_id']);
					$this->session->set_userdata('oprt_name',$fetchRow['name']);
					$this->session->set_userdata('oprt_type',$fetchRow['type']);
					$this->session->set_userdata('oprt_last_log',$fetchRow['last_log']);
					$oprt_id = $fetchRow['oprt_id'];
					set_message("success","Welcome ".$fetchRow['name'].", to control panel of ".panel_name());
					$log_sts = "S";
					$page_name = "homepage";
					
				}else{
					$fldiOprtrId = 0;
					set_message("warning","Invalid username & password");
					$page_name = "login";
					$log_sts = "F";
				}
			}
			$post_data = array("oprt_id"=>($oprt_id>0)? $oprt_id:0,
				"user_name"=>$user_name,
				"user_password"=>$user_password,
				"oprt_ip"=>$_SERVER['REMOTE_ADDR'],
				"operate_system"=>$operate_system,
				"browser"=>$web_browser,
				"browser_version"=>$browser_version,
				"log_sts"=>$log_sts,
				"login_time"=>$login_time,
				"logout_time"=>$logout_time
			);
			$login_id = $config->insertRecord("tbl_oprtr_logs",$post_data);
			$this->session->set_userdata('login_id',$login_id);
			redirect(ADMIN_PATH.$page_name);
		}
	}
	
	public function logouthandler(){
		 $login_id  = $this->session->userdata('login_id');
		 $group_id  = $this->session->userdata('group_id');
		 $oprt_id  = $this->session->userdata('oprt_id');
		 $oprt_name  = $this->session->userdata('oprt_name');
		 $oprt_type  = $this->session->userdata('oprt_type');
		 $oprt_last_log  = $this->session->userdata('oprt_last_log');
		 $logout_time = getLocalTime();
		 $data = array("logout_time"=>$logout_time);
		 $this->SqlModel->updateRecord("tbl_oprtr_logs",$data,array("login_id"=>$login_id));
		 
		 $this->session->unset_userdata('login_id');
		 $this->session->unset_userdata('group_id');
		 $this->session->unset_userdata('oprt_id');
		 $this->session->unset_userdata('oprt_name');
		 $this->session->unset_userdata('oprt_type');
		 $this->session->unset_userdata('oprt_last_log');
		 
		 set_message("success","You have successfully logout");
		 redirect(ADMIN_PATH);
	}
	
	
}
