<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autocomplete extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	}
	
	public function listing(){	
		$switch_type  = $this->input->get('switch_type');
		switch($switch_type){
			case "FRANCHISEE":
				if(trim($_GET['srch']) != ""){
					$srch = str_replace("'", "''", $_GET['srch']);
					$srch = preg_quote($srch);
					$Q_POST = "SELECT tf.* FROM tbl_franchisee AS tf
							 WHERE  tf.is_status>0 AND tf.is_delete>0
							 AND  ( tf.user_name LIKE '%".$srch."%' OR tf.name LIKE '%".$srch."%' ) ";
					$RS_POST = $this->SqlModel->runQuery($Q_POST);
					foreach($RS_POST as $AR_POST):
					$franchisee_id = $AR_POST['franchisee_id'];
					$user_name = $AR_POST['user_name'];
					$name = $AR_POST['name'];
						echo "<li onselect=\"this.setText('$user_name').setValue('$franchisee_id')\"> $user_name<span>$name</span></li>";
					endforeach;
				}
			break;
		}
		
		
	}
	
	

	
	
}
