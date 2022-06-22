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
			case "MEMBER":
				if(trim($_GET['srch']) != ""){
					$srch = str_replace("'", "''", $_GET['srch']);
					$srch = preg_quote($srch);
					$Q_MEM="SELECT member_id, user_id, CONCAT_WS(' ',first_name,last_name) AS full_name
					FROM tbl_members 
					WHERE (member_email LIKE '$srch%'	OR first_name LIKE '$srch%' OR last_name LIKE '%$srch%' OR user_id LIKE '%$srch%')
					AND join_type='M'
					ORDER BY full_name LIMIT 0,5";
					$RS_MEM = $this->SqlModel->runQuery($Q_MEM);
					foreach($RS_MEM as $AR_MEM):
					$member_id = _e($AR_MEM['member_id']);
					$user_id = $AR_MEM['user_id'];
					$full_name = $AR_MEM['full_name'];
						echo "<li onselect=\"this.setText('$full_name').setValue('$member_id')\"> <span>$user_id</span> $full_name</li>";
					endforeach;
				}
			break;
			case "MEMBER_DOWNLINE":
				if(trim($_GET['srch']) != ""){
					$srch = str_replace("'", "''", $_GET['srch']);
					$srch = preg_quote($srch);
					$session_member_id = $this->session->userdata('mem_id');
					
					$Q_USER="SELECT node.member_id, node.user_id, CONCAT_WS(' ',node.first_name, node.last_name) AS full_name 
							FROM tbl_members AS node 
							INNER JOIN tbl_mem_tree AS nodetree ON node.member_id=nodetree.member_id,
							tbl_members AS parent INNER JOIN tbl_mem_tree AS parenttree ON parent.member_id=parenttree.member_id
							WHERE nodetree.nleft BETWEEN parenttree.nleft AND parenttree.nright AND parenttree.member_id='".$session_member_id."'
							AND nodetree.member_id!='".$session_member_id."'  AND 
							(node.user_id LIKE '$srch%' OR node.first_name LIKE '$srch%' OR node.last_name LIKE '$srch%')  
							ORDER BY node.user_id LIMIT 0,5";
					$RS_USER = $this->SqlModel->runQuery($Q_USER);
					foreach($RS_USER as $AR_USER):
					$member_id = _e($AR_USER['member_id']);
					$user_id = $AR_USER['user_id'];
					$full_name = $AR_USER['full_name'];
						echo "<li onselect=\"this.setText('$user_id').setValue('$member_id')\"> <span>$user_id</span> $full_name</li>";
					endforeach;
				}
			break;
		}
		
		
	}
	
	

	
	
}
