<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	
	public function index()
	{
		$this->load->view(FRANCHISE_FOLDER.'/login');
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
			$operate_system = $browser['name'];
			$web_browser = $browser['browser'];
			$browser_version = $browser['version'];
			$oprt_ip = FCrtRplc($_SERVER['REMOTE_ADDR']);
			$login_time = getLocalTime();
			$logout_time = getLocalTime();
			set_message("warning","Invalid username & password");
			if($user_name!='' && $form_data!=''){
				$sel_query = $this->db->query("SELECT * FROM  tbl_franchisee WHERE user_name='$user_name' AND password='$user_password'");
				$fetchRow = $sel_query->row_array();
				
				if($fetchRow['franchisee_id']>0){
					$this->session->set_userdata('fran_id',$fetchRow['franchisee_id']);
					$this->session->set_userdata('fran_user',$fetchRow['user_name']);
					$this->session->set_userdata('fran_last_log',$fetchRow['last_login']);
					set_message("success","Welcome ".$fetchRow['name'].", to franchisee control panel of ".WEBSITE);
					$log_sts = "S";
					$page_name = "dashboard";
					
				}else{
					$fldiOprtrId = 0;
					set_message("warning","Invalid username & password");
					$page_name = "login";
					$log_sts = "F";
				}
			}
			$franchisee_id = $fetchRow['franchisee_id'];
			$post_data = array("franchisee_id"=>($franchisee_id>0)? $franchisee_id:0,
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
			$login_id = $config->insertRecord("tbl_franchise_logs",$post_data);
			$this->session->set_userdata('login_id',$login_id);
			redirect(FRANCHISE_PATH.$page_name);
		}
	}
	
	public function logouthandler(){
		 $login_id  = $this->session->userdata('login_id');
		 $fran_id  = $this->session->userdata('fran_id');
		 $fran_user  = $this->session->userdata('fran_user');
		 $fran_last_log  = $this->session->userdata('fran_last_log');
		 $logout_time = getLocalTime();
		 $data = array("logout_time"=>$logout_time);
		 $this->SqlModel->updateRecord("tbl_franchise_logs",$data,array("login_id"=>$login_id));
		 
		 $this->session->unset_userdata('login_id');
		 $this->session->unset_userdata('fran_id');
		 $this->session->unset_userdata('fran_user');
		 $this->session->unset_userdata('fran_last_log');
		 
		 set_message("success","You have successfully logout");
		 $path_url = generateSeoUrl("franchise","login","");
		 redirect($path_url);
	}
	
	

	
}
