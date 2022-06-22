<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	public function jsonhandler(){	
		$model = new OperationModel();
		$switch_type  = $this->input->get('switch_type');
		switch($switch_type){
			case "CMS":
				$id_cms = $this->input->get('id_cms');
				$AR_SET = $this->SqlModel->runQuery("SELECT active FROM tbl_cms WHERE id_cms='$id_cms'",true);
				$active = $AR_SET['active'];
				$new_active = ($active>0)? 0:1;
				$data_up = array("active"=>$new_active);
				$this->SqlModel->updateRecord("tbl_cms",$data_up,array("id_cms"=>$id_cms));
				$AR_RT['ErrorMsg']="success";
				echo json_encode($AR_RT);
			break;
			case "DOCUMENT":
				$document_id = $this->input->get('document_id');
				$AR_SET = $this->SqlModel->runQuery("SELECT active_sts FROM tbl_mem_doc WHERE document_id='".$document_id."'",true);
				$active_sts = $AR_SET['active_sts'];
				$new_active = ($active_sts>0)? 0:1;
				$data_up = array("active_sts"=>$new_active);
				$this->SqlModel->updateRecord("tbl_mem_doc",$data_up,array("document_id"=>$document_id));
				$AR_RT['ErrorMsg']="success";
				echo json_encode($AR_RT);
			break;
			case "DOCUMENT_ID":
				$kyc_id = $this->input->get('kyc_id');
				$document_id = $this->input->get('document_id');
				if($document_id!='' && $kyc_id!=''){
					$this->SqlModel->updateRecord("tbl_mem_kyc",array("document_id"=>$document_id),array("kyc_id"=>$kyc_id));
					$AR_RT['ErrorMsg']="success";
				}else{
					$AR_RT['ErrorMsg']="failed";
				}
				echo json_encode($AR_RT);
			break;
			case "DOCUMENT_ADD":
				$kyc_id = $this->input->get('kyc_id');
				$document_add = $this->input->get('document_add');
				if($document_add!='' && $kyc_id!=''){
					$this->SqlModel->updateRecord("tbl_mem_kyc",array("document_add"=>$document_add),array("kyc_id"=>$kyc_id));
					$AR_RT['ErrorMsg']="success";
				}else{
					$AR_RT['ErrorMsg']="failed";
				}
				echo json_encode($AR_RT);
			break;
			case "SAVE_DOB":
				$member_id = $this->input->get('member_id');
				$date_of_birth = InsertDate($this->input->get('date_of_birth'));
				if($date_of_birth!='' && $member_id>0){
					$this->SqlModel->updateRecord("tbl_members",array("date_of_birth"=>$date_of_birth),array("member_id"=>$member_id));
					$AR_RT['ErrorMsg']="success";
				}else{
					$AR_RT['ErrorMsg']="failed";
				}
				echo json_encode($AR_RT);
			break;
			case "SAVE_PANCARD":
				$member_id = $this->input->get('member_id');
				$pan_no = $this->input->get('pan_no');
				if($pan_no!='' && $member_id>0){
					$this->SqlModel->updateRecord("tbl_members",array("pan_no"=>$pan_no),array("member_id"=>$member_id));
					$this->SqlModel->updateRecord("tbl_mem_pancard",array("pan_no"=>$pan_no),array("member_id"=>$member_id));
					$AR_RT['ErrorMsg']="success";
				}else{
					$AR_RT['ErrorMsg']="failed";
				}
				echo json_encode($AR_RT);
			break;
			case "ADDRESS_APRVD":
				$approved_date = InsertDate(getLocalTime());
				$kyc_id = $this->input->get('kyc_id');
				$approved_sts = $this->input->get('approved_sts');
				if($approved_sts!='' && $kyc_id!=''){
					$this->SqlModel->updateRecord("tbl_mem_kyc",array("approved_sts"=>$approved_sts,"approved_date"=>$approved_date),array("kyc_id"=>$kyc_id));
					$AR_RT['ErrorMsg']="success";
				}else{
					$AR_RT['ErrorMsg']="failed";
				}
				echo json_encode($AR_RT);
			break;
			case "ID_APRVD":
				$approved_date_id = InsertDate(getLocalTime());
				$kyc_id = $this->input->get('kyc_id');
				$approved_sts_id = $this->input->get('approved_sts_id');
				if($approved_sts_id!='' && $kyc_id!=''){
					$this->SqlModel->updateRecord("tbl_mem_kyc",array("approved_sts_id"=>$approved_sts_id,"approved_date_id"=>$approved_date_id),array("kyc_id"=>$kyc_id));
					$AR_RT['ErrorMsg']="success";
				}else{
					$AR_RT['ErrorMsg']="failed";
				}
				echo json_encode($AR_RT);
			break;
			case "PANCARD":
				$pan_id = $this->input->get('pan_id');
				$AR_SET = $this->SqlModel->runQuery("SELECT pan_no, approve_sts, member_id FROM tbl_mem_pancard WHERE pan_id='".$pan_id."'",true);
				$approve_sts = $AR_SET['approve_sts'];
				$member_id = $AR_SET['member_id'];
				$pan_no = ($approve_sts==1)? '':$AR_SET['pan_no'];
				$new_active = ($approve_sts==1)? 0:1;
				
				$data_up = array("approve_sts"=>$new_active);
				$this->SqlModel->updateRecord("tbl_mem_pancard",$data_up,array("pan_id"=>$pan_id));
				$this->SqlModel->updateRecord("tbl_members",array("pan_no"=>$pan_no),array("member_id"=>$member_id));
				$AR_RT['ErrorMsg']="success";
				echo json_encode($AR_RT);
			break;
			case "FAQ":
				$faq_id = $this->input->get('faq_id');
				$AR_SET = $this->SqlModel->runQuery("SELECT faq_active FROM tbl_faq WHERE faq_id='$faq_id'",true);
				$faq_active = $AR_SET['faq_active'];
				$new_active = ($faq_active>0)? 0:1;
				$data_up = array("faq_active"=>$new_active);
				$this->SqlModel->updateRecord("tbl_faq",$data_up,array("faq_id"=>$faq_id));
				$AR_RT['ErrorMsg']="success";
				echo json_encode($AR_RT);
			break;
			case "CHECK_SPR":
				$sponsor_user_id = $this->input->get('sponsor_user_id');
				$left_right = $this->input->get('left_right');
				$Q_SPR = "SELECT tm.member_id FROM tbl_members AS tm WHERE tm.user_id='".$sponsor_user_id."'";
				$A_SPR = $this->SqlModel->runQuery($Q_SPR,true);
				if($A_SPR['member_id']>0 && ($left_right=='L' || $left_right=="R")){
					$spil_id = $model->ExtrmLftRgt($A_SPR['member_id'],$left_right);
					$AR_RT['spil_id'] = $spil_id;
					$AR_RT['sponsor_id'] = $A_SPR['member_id'];
					
				}
				echo json_encode($AR_RT);
			break;
			case "ATTR_VALUE":
				$attribute_group_id = FCrtRplc($_REQUEST['attribute_group_id']);
				$Q_SPR = "SELECT  attribute_id, attribute_name FROM tbl_attribute AS ta 
						  WHERE ta.attribute_group_id='".$attribute_group_id."' AND ta.attribute_sts>0 AND ta.delete_sts>0";
				$A_SPRS = $this->SqlModel->runQuery($Q_SPR);
				echo "<option value=''>----select attribute----</option>";
				foreach($A_SPRS as $A_SPR):
					echo "<option value='".$A_SPR['attribute_id']."'>".$A_SPR['attribute_name']."</option>";
				endforeach;
			break;
			case "ADD_ATTR_SELECT":
				global $db;
				$attribute_group_id = FCrtRplc($_REQUEST['attribute_group_id']);
				$attribute_id = FCrtRplc($_REQUEST['attribute_id']);
				
				$attribute_group_id_array =  array_unique(array_filter(explode(",",$_REQUEST['select_arr'])));
				
				foreach($attribute_group_id_array as $key=>$attribute_group_id_in):
					$Ctrl += $model->checkCountPro("tbl_attribute","attribute_id=$attribute_group_id_in AND attribute_group_id='$attribute_group_id'");
				endforeach;
				
				if($Ctrl==0){
					$attribute_group = SelectTableWithOption("tbl_attribute_group","attribute_group_name","attribute_group_id='$attribute_group_id'");
					$attribute_name = SelectTableWithOption("tbl_attribute","attribute_name","attribute_id='$attribute_id'");
					$AR_RT['file_name'] = $attribute_group." : ".$attribute_name;
					$AR_RT['error_msg']="success";	
				}else{
					$AR_RT['error_msg']="already";
				}
				
				$AR_RT['attribute_group_id'] = $attribute_group_id;
				$AR_RT['attribute_id'] = $attribute_id;
				echo json_encode($AR_RT);
			break;
			case "UPDATE_FIELD":
				$data_id = $this->input->get('data_id');
				$data_field = $this->input->get('data_field');
				$data_value = $this->input->get('data_value');
				$data_table = $this->input->get('data_table');
				
				$AR_RT['error_sts']="0";
				if($data_id!='' && $data_field!='' && $data_value!='' && $data_table!=''){
					$primary_key = getPrimaryKey($data_table);	
					$this->SqlModel->updateRecord($data_table,array($data_field=>$data_value),array($primary_key=>$data_id));
					$AR_RT['error_sts']="1";
				}
				echo json_encode($AR_RT);
			break;
			case "STATE_LIST":
				$country_code = FCrtRplc($_REQUEST['country_code']);
				$Q_SPR = "SELECT DISTINCT  tc.state_name FROM tbl_city AS tc WHERE tc.country_code='".$country_code."'";
				$A_SPRS = $this->SqlModel->runQuery($Q_SPR);
				echo "<option value=''>----select state----</option>";
				foreach($A_SPRS as $A_SPR):
					echo "<option value='".$A_SPR['state_name']."'>".$A_SPR['state_name']."</option>";
				endforeach;
				echo "<option value='Other'>Other</option>";
			break;
			case "CITY_LIST":
				$state_name = FCrtRplc($_REQUEST['state_name']);
				$Q_SPR = "SELECT DISTINCT  tc.city_name FROM tbl_city AS tc WHERE tc.state_name LIKE '".$state_name."'";
				$A_SPRS = $this->SqlModel->runQuery($Q_SPR);
				echo "<option value=''>----select city----</option>";
				foreach($A_SPRS as $A_SPR):
					echo "<option value='".$A_SPR['city_name']."'>".$A_SPR['city_name']."</option>";
				endforeach;
				echo "<option value='Other'>Other</option>";
			break;
			case "PIN_TYPE":
				$type_id = $this->input->get('type_id');
				$QR_GET = "SELECT * FROM tbl_pintype WHERE type_id='$type_id'";
				$AR_SET = $this->SqlModel->runQuery($QR_GET,true);
				echo json_encode($AR_SET);
			break;
			case "PRODUCT_STOCK":
				$q =  ($this->input->get('q'))? $this->input->get('q'):$this->input->post('q');
				$StrWhr .=" AND ( tpl.post_title LIKE '%$q%' OR tp.post_code LIKE '%$q%' )";
				$QR_OBJ = "SELECT tp.post_id AS id,  tpl.post_title AS name
				FROM tbl_post AS tp
				LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
				LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
				LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tag_id,tpl.post_tags)
				LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
				LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
				WHERE tp.delete_sts>0 AND tp.post_sts>0  $StrWhr 
				GROUP BY tp.post_id  
				ORDER BY tp.post_id DESC";
				$AR_RT = $this->SqlModel->runQuery($QR_OBJ);
				$json_response = json_encode($AR_RT);
				if($_GET["callback"]) {
				$json_response = $_GET["callback"] . "(" . $json_response . ")";
				}
				echo $json_response;
			break;
			case "PRODUCT":
				$q =  strtolower(($this->input->get('q'))? $this->input->get('q'):$this->input->post('q'));
				$StrWhr .=" AND ( LOWER(tpl.post_title) LIKE '%$q%' )";
				if($q!=''){
					$QR_OBJ = "SELECT tp.post_id , tpl.post_title
					FROM  tbl_post AS tp
					LEFT JOIN  tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
					WHERE tp.delete_sts>0   $StrWhr 
					ORDER BY tp.post_id DESC";
					$AR_RT = $this->SqlModel->runQuery($QR_OBJ);
					foreach($AR_RT as $AR_DT):
						echo str_replace('"','',$AR_DT['post_title'])."\n";
					endforeach;
				}
			break;
			case "PRODUCT_SINGLE":
				$q =  strtolower(($this->input->get('q'))? $this->input->get('q'):$this->input->post('q'));
				$StrWhr .=" AND ( LOWER(tpl.post_title) = '$q')";
				$QR_OBJ = "SELECT tp.post_id , tpl.post_title, tpl.post_mrp, tpl.post_price, tpl.post_pv
				FROM  tbl_post AS tp
				LEFT JOIN  tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
				WHERE tp.delete_sts>0   $StrWhr 
				ORDER BY tp.post_id DESC";
				$AR_RT = $this->SqlModel->runQuery($QR_OBJ,true);
				echo json_encode($AR_RT,true);
			break;
			case "PRODUCT_AVL_QTY":
				$post_id =  ($this->input->get('post_id'))? $this->input->get('post_id'):$this->input->post('post_id');
				$post_attribute_id =  ($this->input->get('post_attribute_id'))? $this->input->get('post_attribute_id'):$this->input->post('post_attribute_id');
				$AR_STOCK = $this->OperationModel->getCompanyStock($post_id,$post_attribute_id,$supplier_id,"","");
				$AR_RT['available_qty'] = ($AR_STOCK['balance']>0)? $AR_STOCK['balance']:0;
				echo json_encode($AR_RT,true);
			break;
			case "SHOPPE_TO_COMPANY":
				$franchisee_id = FCrtRplc($_REQUEST['franchise_id']);
				$q = ($this->input->get('q'))? $this->input->get('q'):$this->input->post('q');
				$StrWhr .=" AND ( tpl.post_title = '$q')";
				$QR_OBJ = "SELECT tp.post_id , tpl.post_title, tpl.post_price,  tpl.post_pv, tpl.post_dp_price
				FROM tbl_post AS tp
				LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
				LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
				LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tag_id,tpl.post_tags)
				LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
				LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
				WHERE tp.delete_sts>0   $StrWhr 
				GROUP BY tp.post_id  
				ORDER BY tp.post_id DESC";
				$AR_RT = $this->SqlModel->runQuery($QR_OBJ,true);
				$AR_STOCK = $this->OperationModel->getFranchiseStock($AR_RT['post_id'],$franchisee_id,$supplier_id,"","");
				$AR_RT['available_qty'] = ($AR_STOCK['balance']>0)? $AR_STOCK['balance']:0;
				echo json_encode($AR_RT,true);
			break;
			case "REFERESH_CHAIN":
				$Q_MEM = "SELECT member_id, sponsor_id, date_join, left_right 
						  FROM tbl_members AS A 
						  WHERE member_id NOT IN (SELECT member_id FROM tbl_mem_tree AS B) 
				          ORDER BY A.member_id ASC LIMIT 500";
				$RS_MEM = $this->SqlModel->runQuery($Q_MEM);
				$AR_RT['ErrorMsg']="PENDING";
				if(count($RS_MEM)>0){
					foreach($RS_MEM as $AR_MEM):
						$sponsor_id = $AR_MEM['sponsor_id'];
						$member_id = $AR_MEM['member_id'];
						$left_right = $AR_MEM['left_right'];
						$date_join = $AR_MEM['date_join'];
						if($model->checkCount("tbl_mem_tree","member_id",$sponsor_id)>0){
							$AR_GET = $model->getSponsorSpill($sponsor_id,$left_right);
							$spil_id = $AR_GET['spil_id'];
							
							$tree_data = array("member_id"=>$member_id,
								"sponsor_id"=>$sponsor_id,
								"spil_id"=>$spil_id,
								"left_right"=>getTool($left_right,''),
								"nlevel"=>0,
								"nleft"=>0,
								"nright"=>0,
								"date_join"=>$date_join
							);
							$this->SqlModel->insertRecord("tbl_mem_tree",$tree_data);
							$model->updateTree($spil_id,$member_id);
							$this->SqlModel->updateRecord("tbl_members",array("spil_id"=>$spil_id),array("member_id"=>$member_id));
						}
						
					endforeach;
				}else{
					$AR_RT['ErrorMsg']="DONE";
				}
				echo json_encode($AR_RT);
			break;
			case "PAYSTS":
				$member_id = $this->input->get('member_id');
				$process_id = $this->input->get('process_id');
				$AR_SET = $this->SqlModel->runQuery("SELECT pay_sts FROM tbl_cmsn_mstr_sum WHERE process_id='$process_id'
							AND member_id='$member_id'",true);
				$pay_sts = $AR_SET['pay_sts'];
				$new_sts = ($pay_sts=="Y")? "N":"Y";
				$data_up = array("pay_sts"=>$new_sts);
				$this->SqlModel->updateRecord("tbl_cmsn_mstr_sum",$data_up,array("process_id"=>$process_id,"member_id"=>$member_id));
				$AR_RT['ErrorMsg']="success";
				echo json_encode($AR_RT);
			break;
			case "ORDER_AMOUNT":
				$order_no = $this->input->get('order_no');
				$AR_ORDR = $model->getOrderMasterDetail($order_no);
				$order_id = $AR_ORDR['order_id'];
				$AR_RT['error_sts']=0;
				if($order_id>0){
					$QR_SEL = "SELECT SUM(tod.post_selling*tod.post_qty) AS total_amount
							   FROM  tbl_order_detail AS tod
							   WHERE tod.order_id='".$order_id."'";
					$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
					$total_amount = $AR_SEL['total_amount'];
						$AR_RT['total_amount'] = $total_amount;
						$AR_RT['error_sts']=1;
				}
				echo json_encode($AR_RT);
			break;
			case "ITHINK_STATE":
				$country_id = $this->input->get('country_id');
				$access_token = ITHINK_ACCESS;
				$secret_key = ITHINK_KEY;
			
				$data_set = array("country_id"=>$country_id,
					"access_token"=>$access_token,
					"secret_key"=>$secret_key
				);
				$data = array("data"=>$data_set);
				$request_param =  json_encode($data,true);
										
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL             => "https://manage.ithinklogistics.com/api_v2/state/get.json",
					CURLOPT_RETURNTRANSFER  => true,
					CURLOPT_ENCODING        => "",
					CURLOPT_MAXREDIRS       => 10,
					CURLOPT_TIMEOUT         => 30,
					CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST   => "POST",
					CURLOPT_POSTFIELDS      => $request_param,
					CURLOPT_HTTPHEADER      => array(
					"cache-control: no-cache",
					"content-type: application/json"
				  ),
				));
				
				$response = curl_exec($curl);
				$err      = curl_error($curl);
				curl_close($curl);
				$response_array = json_decode($response,true);
				$status = $response_array['status'];
				$option_array = $response_array['data'];
				if ($err){
					echo '<option value="">Other</option>';
				}else{
					foreach($option_array as $key=>$value):
						$id = $value['id'];
						$state_name = $value['state_name'];
						echo '<option value="'.$id.'">'.$state_name.'</option>';
					endforeach;
				}
			break;
			case "ITHINK_CITY":
				$state_id = $this->input->get('state_id');
				$access_token = ITHINK_ACCESS;
				$secret_key = ITHINK_KEY;
			
				$data_set = array("state_id"=>$state_id,
					"access_token"=>$access_token,
					"secret_key"=>$secret_key
				);
				$data = array("data"=>$data_set);
				$request_param =  json_encode($data,true);
										
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL             => "https://manage.ithinklogistics.com/api_v2/city/get.json",
					CURLOPT_RETURNTRANSFER  => true,
					CURLOPT_ENCODING        => "",
					CURLOPT_MAXREDIRS       => 10,
					CURLOPT_TIMEOUT         => 30,
					CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST   => "POST",
					CURLOPT_POSTFIELDS      => $request_param,
					CURLOPT_HTTPHEADER      => array(
					"cache-control: no-cache",
					"content-type: application/json"
				  ),
				));
				
				$response = curl_exec($curl);
				$err      = curl_error($curl);
				curl_close($curl);
				$response_array = json_decode($response,true);
				$status = $response_array['status'];
				$option_array = $response_array['data'];
				if ($err){
					echo '<option value="">Other</option>';
				}else{
					foreach($option_array as $key=>$value):
						$id = $value['id'];
						$city_name = $value['city_name'];
						echo '<option value="'.$id.'">'.$city_name.'</option>';
					endforeach;
				}
			break;
			
		}
		
		
	}
	
}
