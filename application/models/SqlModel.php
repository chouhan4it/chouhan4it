<?php

class SqlModel extends CI_Model {

    private $table;

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }


   

    /**
     * Use this login method if you want to use ROLE Access in your application
     */
    public function CMSRoleLogin($table, $column = '', $where = array()) {
		//print_r( $where );
		if (is_array($where) && count($where) > 0) {
            $sql_query = '*';

            // Load role if defined!
            $this->config->load('site', FALSE, TRUE);
            $roles = $this->config->item('user_roles');

            $sql_query .= ', ' . MySQLCaseGenerator($roles, $column, 'currentRole');

            $this->db->select($sql_query, false)->from($table)->where($where);
            return $this->db->get()->row_array();
        }
        return false;
    }

   
   

    /*     * **************************** Start General Functions ********************** */

    //insert function 
    function insertRecord($table, $colums) {
        if ($this->db->insert($table, $colums))
            return $this->db->insert_id();
        else
            return false;
    }

    //update function
    function updateRecord($table, $colums, $condition) {
        if ($this->db->update($table, $colums, $condition))
            return true;
        else
            return false;
    }

    // delete function 
    function deleteRecord($table, $condition) {
        if ($this->db->delete($table, $condition)) {
            //echo $this->db->last_query();
            return true;
        } else {
            return false;
        }
    }
	function getQuery($sql){
		$fetchRow = $this->db->query($sql);
		return $fetchRow;
	}
    function runQuery($sql, $flag = '') {
		//echo $sql;
		$this->result = $this->db->query($sql);
		if ($flag)
            return $this->result->row_array();
        else
            return $this->result->result_array();
    }
	
	function queryCount($sql, $flag = '') {
		//echo $sql;
		$this->result = $this->db->query($sql);
		
            return $this->result->num_rows();
    }

    public function insertRec($table, $data) {

        $q = $this->db->insert($table, $data);
        if (!$q) {
            // if query returns null
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
            return false;
        } else {
            return $this->db->insert_id();
        }
    }

    public function updateRec($table, $data, $where) {
        $this->db->where($where);
        $q = $this->db->update($table, $data);
        if (!$q) {
            // if query returns null
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
            return "false";
        }
        return "true";
    }

    public function getSingleRecord($table, $where) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where);
        $count = $this->db->count_all_results();
        if ($count == "1") {
            $query = $this->db->get_where($table, $where);
            $data = $query->row_array();
            return $data;
        } else {
            return null;
        }
    }

    public function getSingleField($col, $table, $where) {
        $this->db->select($col);
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        $data = $query->row_array();
        if (isset($data[$col])) {
            return $data[$col];
        } else {
            return null;
        }
    }

   

    public function batchInsert($table = '', $data = array()) {
        if (empty($table) || empty($data)) {
            return false;
        }
        if ($this->db->insert_batch($table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function batchUpdate($table = '', $data = array(), $cols = '') {
        if (empty($table) || empty($data) || empty($cols)) {
            return false;
        }
        if ($this->db->update_batch($table, $data, $cols)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkCookie() {
        if (isset($_COOKIE['dstacklog']) && $_COOKIE['dstacklog'] != "") {
            $id = $this->encrypt->decode($_COOKIE['dstacklog']);
            $user_data = $this->getUserDataById($id);
            if (!empty($user_data)) {
                $this->session->set_userdata('user_id', $user_data['id']);
                $this->session->set_userdata('fb_uid', $user_data['fb_uid']);
                $this->session->set_userdata('email', $user_data['email']);
                $this->session->set_userdata('full_name', $user_data['full_name']);
                $this->session->set_userdata('role', $user_data['user_role']);
                $this->session->set_userdata('auth', '1');
                redirect(base_url() . 'home', 'location');
            } else {
                return;
            }
        } else {
            return;
        }
    }

    public function truncate($table = "") {
        if ($table == "") {
            return;
        }
        if ($this->db->truncate($table)) {
            return true;
        } else {
            return false;
        }
    }

    public function seostring($rstring = "", $suffix = '.html') {
        $string = preg_replace('/\%/', ' percentage', $rstring);
        $string = preg_replace('/\@/', ' at ', $string);
        $string = preg_replace('/\&/', ' and ', $string);
        $string = preg_replace('/\s[\s]+/', '-', $string);    // Strip off multiple spaces
        $string = preg_replace('/[\s\W]+/', '-', $string);    // Strip off spaces and non-alpha-numeric
        $string = preg_replace('/^[\-]+/', '', $string); // Strip off the starting hyphens
        $string = preg_replace('/[\-]+$/', '', $string); // // Strip off the ending hyphens
        $string = strtolower($string);
        $string = str_replace(" ", "-", $string) . $suffix;
        return $string;
    }

    public function convertImage($img = "", $path = 'images/temp') {
        $img = trim($img);
        $ext = ".jpg";
        if (strpos($img, 'image/png')) {
            $ext = ".png";
        }

        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = $path . '/' . uniqid() . $ext;
        $success = @file_put_contents($file, $data);
        if ($success) {
            return str_replace($path . '/', '', $file);
        } else {
            return false;
        }
    }

	function update_all_videos_links($sql = '') {
		$query = $this->db->query($sql);
		return $query;
	}
	
	function defaultCurrency(){
		$QR_CONFIG = "SELECT value FROM tbl_configuration WHERE name LIKE 'DEFAULT_CURRENCY'";
		$RS_CONFIG = $this->db->query($QR_CONFIG);
		$AR_CONFIG = $RS_CONFIG->row_array();
		return ($AR_CONFIG['value'])? $AR_CONFIG['value']:"$";
	}
	
	function checkCronJob(){
		$url_deposite = generateSeoUrl("cronjob","depositbitcoin","");
		get_web_page($url_deposite);
	}
	


}

?>