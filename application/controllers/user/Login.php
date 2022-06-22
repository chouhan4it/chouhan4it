<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	
	public function index()
	{
		$this->load->view('login');
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
				$sel_query = $this->db->query("SELECT * FROM tbl_members 
				WHERE ( user_name='$user_name' OR user_id = '$user_name' ) AND user_password='$user_password'");
				$fetchRow = $sel_query->row_array();
				if($fetchRow['member_id']>0){
					$this->session->set_userdata('mem_id',$fetchRow['member_id']);
					$this->session->set_userdata('user_id',$fetchRow['user_name']);
					$this->session->set_userdata('last_log',$fetchRow['last_login']);
					$log_sts = "S";
					$page_name = "dashboard";
					
				}else{
					$fldiOprtrId = 0;
					set_message("warning","Invalid username & password");
					$page_name = "login";
					$log_sts = "F";
				}
			}
			$member_id = $fetchRow['member_id'];
			$post_data = array("member_id"=>($member_id>0)? $member_id:0,
			"user_name"=>$user_name,
			"user_password"=>$user_password,
			"member_ip"=>$_SERVER['REMOTE_ADDR'],
			"operate_system"=>$operate_system,
			"browser"=>$web_browser,
			"browser_version"=>$browser_version,
			"log_sts"=>$log_sts,
			"login_time"=>$login_time,
			"logout_time"=>$logout_time
			);
			$login_id = $config->insertRecord("tbl_mem_logs",$post_data);
			$this->session->set_userdata('login_id',$login_id);
			redirect(MEMBER_PATH);
		}
	}
	
	public function logouthandler(){
		 $mem_id  = $this->session->userdata('mem_id');
		 $user_id  = $this->session->userdata('user_id');
		  $login_id  = $this->session->userdata('login_id');
		 $logout_time = getLocalTime();
		 $data = array("logout_time"=>$logout_time);
		 $this->SqlModel->updateRecord("tbl_mem_logs",$data,array("login_id"=>$login_id));
		 
		 $this->session->unset_userdata('mem_id');
		 $this->session->unset_userdata('user_id');
		 
		 set_message("success","You have successfully logout");
		 redirect_front("account","login","");
	}
	
	

	
}
