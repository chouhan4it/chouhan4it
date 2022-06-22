<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Olddata extends CI_Controller {
	 public function __construct() {
        parent::__construct();
    }
	
	
	public function memberdata(){
		$model = new OperationModel();
		$today_date = getLocalTime();
		$QR_SEL = "SELECT a.* 
				   FROM table134 AS a 
				   WHERE  a.COL1 NOT IN(SELECT user_id FROM tbl_members) 
				   ORDER BY RAND()";
		$RS_SEL  = $this->SqlModel->runQuery($QR_SEL);
		
		foreach($RS_SEL as $AR_SEL):
			
			$left_right = $AR_SEL['COL3'];
			$AR_SPR = $model->getSponsorId($AR_SEL['COL6']);
			$sponsor_id = $AR_SPR['sponsor_id'];
			#$AR_GET = $model->getSponsorSpill($sponsor_id,$left_right);
			$AR_SPL = $model->getSponsorId($AR_SEL['COL5']);
			$spil_id = $AR_SPL['sponsor_id'];
			
			$Ctrl +=  $model->CheckOpenPlace($spil_id,$left_right);
			$Ctrl +=  ($sponsor_id>0)? 0:1;
			$Ctrl +=  ($spil_id>0)? 0:1;
			
			
			if($Ctrl==0){
				$data_mem = array("user_id"=>FCrtRplc($AR_SEL['COL1']),
					"user_name"=>FCrtRplc($AR_SEL['COL1']),
					"first_name"=>getTool(FCrtRplc($AR_SEL['COL2']),''),
					"full_name"=>getTool(FCrtRplc($AR_SEL['COL2']),''),
					"sponsor_id"=>$sponsor_id,
					"spil_id"=>$spil_id,
					"left_right"=>$left_right,
					"user_password"=>FCrtRplc($AR_SEL['COL4']),
					"member_email"=>getTool(FCrtRplc($AR_SEL['COL7']),''),
					"member_mobile"=>getTool(FCrtRplc($AR_SEL['COL8']),''),
					
					"pan_no"=>getTool(FCrtRplc($AR_SEL['COL10']),''),
					"aadhar_no"=>getTool(FCrtRplc($AR_SEL['COL11']),''),
					"bank_name"=>getTool(FCrtRplc($AR_SEL['COL12']),''),
					"branch"=>getTool(FCrtRplc($AR_SEL['COL13']),''),
					"bank_acct_holder"=>getTool(FCrtRplc($AR_SEL['COL14']),''),
					"account_number"=>getTool(FCrtRplc($AR_SEL['COL15']),''),
					"ifc_code"=>getTool(FCrtRplc($AR_SEL['COL17']),''),
					"date_join"=>$today_date
				);	
				
				
				$member_id = $this->SqlModel->insertRecord("tbl_members",$data_mem);
				$tree_data = array("member_id"=>$member_id,
					"sponsor_id"=>$sponsor_id,
					"spil_id"=>$spil_id,
					"nlevel"=>0,
					"left_right"=>$left_right,
					"nleft"=>0,
					"nright"=>0,
					"date_join"=>$today_date
				);
				$this->SqlModel->insertRecord("tbl_mem_tree",$tree_data);
				$model->updateTree($spil_id,$member_id);
			}
			unset($Ctrl,$member_id,$spil_id,$AR_GET,$sponsor_id,$AR_SPR,$left_right,$data_mem);
		endforeach;
	}
	
	
}
?>