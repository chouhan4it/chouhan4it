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
					ORDER BY full_name LIMIT 0,5";
					$RS_MEM = $this->SqlModel->runQuery($Q_MEM);
					foreach($RS_MEM as $AR_MEM):
					$member_id = _e($AR_MEM['member_id']);
					$user_id = $AR_MEM['user_id'];
					$full_name = $AR_MEM['full_name'];
						echo "<li onselect=\"this.setText('$user_id').setValue('$member_id')\"> <span>$user_id</span> $full_name</li>";
					endforeach;
				}
			break;
			case "MEM_DESG":
				if(trim($_GET['srch']) != ""){
					$srch = str_replace("'", "''", $_GET['srch']);
					$srch = preg_quote($srch);
					$Q_MEM="SELECT tm.member_id, tm.user_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name,
					tr.rank_name
					FROM tbl_members  AS tm
					LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
					WHERE (tm.member_email LIKE '$srch%' OR tm.first_name LIKE '$srch%' OR tm.last_name LIKE '%$srch%' OR tm.user_id LIKE '%$srch%')
					ORDER BY tm.full_name LIMIT 0,5";
					$RS_MEM = $this->SqlModel->runQuery($Q_MEM);
					foreach($RS_MEM as $AR_MEM):
					$member_id = _e($AR_MEM['member_id']);
					$user_id = $AR_MEM['user_id'];
					$full_name = $AR_MEM['full_name'];
					$rank_name = getTool($AR_MEM['rank_name'],"Free");
						echo "<li onselect=\"this.setText('".$full_name."&nbsp;[".$rank_name."]').setValue('$member_id')\"> <span>$user_id</span> ".$full_name."&nbsp;[".$rank_name."]</li>";
					endforeach;
				}
			break;
			case "PAN_MEM":
				if(trim($_GET['srch']) != ""){
					$srch = str_replace("'", "''", $_GET['srch']);
					$srch = preg_quote($srch);
					$Q_MEM="SELECT member_id, user_id, CONCAT_WS(' ',first_name,last_name) AS full_name
					FROM tbl_members 
					WHERE (member_email LIKE '$srch%'	OR first_name LIKE '$srch%' OR last_name LIKE '%$srch%' OR user_id LIKE '%$srch%')
					AND pan_no!=''
					ORDER BY full_name LIMIT 0,5";
					$RS_MEM = $this->SqlModel->runQuery($Q_MEM);
					foreach($RS_MEM as $AR_MEM):
					$member_id = _e($AR_MEM['member_id']);
					$user_id = $AR_MEM['user_id'];
					$full_name = $AR_MEM['full_name'];
						echo "<li onselect=\"this.setText('$user_id').setValue('$member_id')\"> <span>$user_id</span> $full_name</li>";
					endforeach;
				}
			break;
			case "AGENT":
				if(trim($_GET['srch']) != ""){
					$srch = str_replace("'", "''", $_GET['srch']);
					$srch = preg_quote($srch);
					$Q_MEM="SELECT member_id, user_id, CONCAT_WS(' ',first_name,last_name) AS full_name
					FROM tbl_members 
					WHERE (member_email LIKE '$srch%' OR first_name LIKE '$srch%' OR last_name LIKE '%$srch%' OR user_id LIKE '%$srch%')
					ORDER BY first_name LIMIT 0,5";
					$RS_MEM = $this->SqlModel->runQuery($Q_MEM);
					foreach($RS_MEM as $AR_MEM):
					$member_id = _e($AR_MEM['member_id']);
					$user_id = $AR_MEM['user_id'];
					$full_name = $AR_MEM['full_name'];
						echo "<li onselect=\"this.setText('$full_name').setValue('$member_id')\"> <span>$user_id</span> $full_name</li>";
					endforeach;
				}				
			break;
			case "AGENT_DOWNLINE":
				if(trim($_GET['srch']) != ""){
					$srch = str_replace("'", "''", $_GET['srch']);
					$srch = preg_quote($srch);
					$session_agent_id = $_GET['agent_id'];
					$Q_USER="SELECT node.agent_id, node.user_id, node.first_name, node.last_name FROM tbl_agents AS node 
							INNER JOIN tbl_agents_tree AS nodetree ON node.agent_id=nodetree.agent_id,
							tbl_agents AS parent INNER JOIN tbl_agents_tree AS parenttree ON parent.agent_id=parenttree.agent_id
							WHERE nodetree.nleft BETWEEN parenttree.nleft AND parenttree.nright AND parenttree.agent_id='".$session_agent_id."'
							AND nodetree.agent_id!='".$session_agent_id."' AND nodetree.left_right!='' AND 
							(node.user_id LIKE '$srch%' OR node.first_name LIKE '$srch%' OR node.last_name LIKE '$srch%')  
							ORDER BY node.user_id LIMIT 0,5";
					$RS_USER = $this->SqlModel->runQuery($Q_USER);
					foreach($RS_USER as $AR_USER):
					$agent_id = _e($AR_USER['agent_id']);
					$user_id = $AR_USER['user_id'];
					$fldvFullName = $AR_MEM['first_name']." ".$AR_MEM['last_name'];
						echo "<li onselect=\"this.setText('$user_id').setValue('$agent_id')\"> <span>$user_id</span> $fldvFullName</li>";
					endforeach;
				}
			break;
			case "PRODUCT":
				if(trim($_GET['srch']) != ""){
					$srch = str_replace("'", "''", $_GET['srch']);
					$srch = preg_quote($srch);
					$Q_POST = "SELECT tp.*, tpl.post_title FROM tbl_post AS tp
							 LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
							 WHERE  tp.delete_sts>0 AND tp.post_sts>0
							 AND  ( tpl.post_title LIKE '%".$srch."%' OR tpl.post_desc LIKE '%".$srch."%' ) ";
					$RS_POST = $this->SqlModel->runQuery($Q_POST);
					foreach($RS_POST as $AR_POST):
					$post_id = $AR_POST['post_id'];
					$post_code = $AR_POST['post_code'];
					$post_title = $AR_POST['post_title'];
						echo "<li onselect=\"this.setText('$post_title').setValue('$post_id')\"> <span>$post_code</span>  $post_title</li>";
					endforeach;
				}
			break;
			
			case "FRANCHISEE":
				if(trim($_GET['srch']) != ""){
					$srch = str_replace("'", "''", $_GET['srch']);
					$srch = preg_quote($srch);
					$Q_POST = "SELECT tf.* FROM tbl_franchisee AS tf
							 WHERE  tf.is_status>0 AND tf.is_delete>0 
							 AND  ( tf.user_name LIKE '%".$srch."%' OR tf.name LIKE '%".$srch."%' OR tf.code LIKE '%".$srch."%' ) ";
					$RS_POST = $this->SqlModel->runQuery($Q_POST);
					foreach($RS_POST as $AR_POST):
					$franchisee_id = $AR_POST['franchisee_id'];
					$code = $AR_POST['code'];
					$name = $AR_POST['name'];
						echo "<li onselect=\"this.setText('$name').setValue('$franchisee_id')\"> $name<span>$code</span></li>";
					endforeach;
				}
			break;
		}
		
		
	}
	
	

	
	
}
