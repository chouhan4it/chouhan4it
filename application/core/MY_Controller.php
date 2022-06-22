<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	public $user_data;
    public function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
		 $this->load->helper('cookie');
		 
		 $this->db->query("SET SESSION sql_mode=''");
		 
        if ($_SERVER['SERVER_ADDR'] == "127.0.0.1") {
            #$this->output->enable_profiler(TRUE);
        }
		$HTTP_CF_VISITOR = json_decode($_SERVER['HTTP_CF_VISITOR'],true);
		$scheme = ($HTTP_CF_VISITOR['scheme']=="http")? "off":"on";
		if( $scheme!="on" && $_SERVER['HTTP_HOST']!='localhost' ){
			$redirect = 'https://' . $_SERVER['HTTP_HOST'].'/';
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $redirect);
			exit();
		}
		$currency = $this->SqlModel->defaultCurrency();
		define("CURRENCY",$currency);
    }
	
	
	
    protected function isAdminLoggedIn() {
        return $this->session->userdata('oprt_id');
    }

    protected function checkAdminLogin() {
        if ($this->isAdminLoggedIn() === false) {
            redirect(ADMIN_PATH);
        }
    }
	
	protected function isMemberLoggedIn() {
        return $this->session->userdata('mem_id');
    }
	
	protected function checkMemberLogin() {
        if ($this->isMemberLoggedIn() === false) {
            redirect(BASE_PATH);
        }
    }
	
	protected function isFranchiseLoggedIn() {
        return $this->session->userdata('fran_id');
    }

    protected function isAdmin() {
        return isset($this->user_data['admin']) && $this->user_data['admin'] === true;
    }

    protected function jhead() {
        header('Content-type: application/json');
    }

    protected function json($msg = '', $status = true) {
        $data = array();
        $data['status'] = ($status === true) ? 'true' : 'false';
        $message_key = ($status) ? 'data' : 'message';
        if ($msg != '')
            $data[$message_key] = $msg;
        echo json_encode($data);
        return;
    }

}
