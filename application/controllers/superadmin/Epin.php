<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Epin extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	
	public function pintype(){	
		$this->load->view(ADMIN_FOLDER.'/epin/pintype',$data);
	}
	
	public function addpin(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$type_id = ($form_data['type_id'])? $form_data['type_id']:_d($segment['type_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitEpin']==1 && $this->input->post()!=''){
					$pin_name = FCrtRplc($form_data['pin_name']);
					$description = FCrtRplc($form_data['description']);
					$pin_letter = strtoupper($form_data['pin_letter']);
					
					$pin_price = FCrtRplc($form_data['pin_price']);
					$gst_price = FCrtRplc($form_data['gst_price']);
					$net_price = FCrtRplc($form_data['net_price']);
					
					$direct_bonus = FCrtRplc($form_data['direct_bonus']);
					$prod_pv = FCrtRplc($form_data['prod_pv']);
					
					$data = array("pin_name"=>$pin_name,
						"pin_price"=>getTool($pin_price,0),
						"gst_price"=>getTool($gst_price,0),
						"net_price"=>getTool($net_price,0),
						
						"direct_bonus"=>getTool($direct_bonus,0),
						"prod_pv"=>getTool($prod_pv,0),
						"pin_letter"=>$pin_letter,
						"description"=>$description
					);
					if($model->checkCount("tbl_pintype","type_id",$type_id)>0){
						$this->SqlModel->updateRecord("tbl_pintype",$data,array("type_id"=>$type_id));
						$model->setPinPost($type_id,$form_data);
						$model->uploadPinFile($_FILES,array("type_id"=>$type_id),"");
						set_message("success","You have successfully updated a  pin detail");
						redirect_page("epin","pintype",array(""));					
					}else{
						$type_id = $this->SqlModel->insertRecord("tbl_pintype",$data);
						$model->setPinPost($type_id,$form_data);
						$model->uploadPinFile($_FILES,array("type_id"=>$type_id),"");
						set_message("success","You have successfully added  a new  pin type");
						redirect_page("epin","pintype","");				
					}
				}
			break;
			case "DELETE":
				if($type_id>0){
					$data = array("isDelete"=>0);
					$this->SqlModel->updateRecord("tbl_pintype",$data,array("type_id"=>$type_id));
					set_message("success","You have successfully deleted record");	
				}
				redirect_page("epin","pintype",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_pintype WHERE type_id='$type_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/epin/addpin',$data);
	}
	
	public function pinimage(){	
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$pin_img_id = (_d($form_data['pin_img_id'])>0)? _d($form_data['pin_img_id']):_d($segment['pin_img_id']);


		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submit-image']==1 && $this->input->post()!=''){
					$type_id = _d($form_data['type_id']);
					$model->uploadPinFile($_FILES,array("type_id"=>$type_id),"");
					set_message("success","You have successfully uploaded a new image");
					redirect_page("epin","pinimage",array("type_id"=>_e($type_id)));					
						
				}
			break;
			case "DELETE":
				$AR_FILE = SelectTable("tbl_pin_image","*","pin_img_id='".$pin_img_id."'");
				
				if($pin_img_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->updateRecord("tbl_pin_image",$data,array("pin_img_id"=>$pin_img_id));
					set_message("success","You have successfully deleted image");	
				}
				redirect_page("epin","pinimage",array("type_id"=>_e($AR_FILE['type_id'])));
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/epin/pinimage',$data);
	}
	
	public function pingenerate(){	
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$mstr_id = ($form_data['mstr_id'])? $form_data['mstr_id']:_d($segment['mstr_id']);
		switch($action_request){
			case "ADD_UPDATE":
				
				if($form_data['generateEpin']==1 && $this->input->post()!=''){
					$type_id = FCrtRplc($form_data['type_id']);
					$no_pin = FCrtRplc($form_data['no_pin']);
					$member_id = _d($form_data['member_id']);
					$bank_id = FCrtRplc($form_data['bank_id']);
					$payment_date = InsertDate($form_data['payment_date']);
					$payment_sts = FCrtRplc($form_data['payment_sts']);
					$pin_price = FCrtRplc($form_data['pin_price']);
					$net_amount = FCrtRplc($form_data['net_amount']);
					
					$AR_PACK = $model->getPinType($type_id);
					
					$data = array("type_id"=>$type_id,
						"no_pin"=>$no_pin,
						"prod_pv"=>$AR_PACK['prod_pv'],
						"pin_price"=>$pin_price,
						"net_amount"=>$net_amount,
						"member_id"=>$member_id,
						"bank_id"=>$bank_id,
						"payment_date"=>$payment_date,
						"payment_sts"=>$payment_sts,
						"ip_address"=>$_SERVER['REMOTE_ADDR'],
						"generate_by"=>$this->session->userdata('oprt_name')
					);
					if($model->checkCount("tbl_pinsmaster","mstr_id",$mstr_id)==0){
						$mstr_id = $this->SqlModel->insertRecord("tbl_pinsmaster",$data);
						$model->generatePinDetail($mstr_id);
						set_message("success","You have successfully generated  a E-pin");
						redirect_page("epin","viewpin",array("mstr_id"=>_e($mstr_id)));			
					}
				}
			break;
			case "DELETE":
				if($mstr_id>0){
					if($model->checkCountPro("tbl_pinsdetails","mstr_id='$mstr_id' AND use_member_id>0")==0){
						$this->SqlModel->deleteRecord("tbl_pinsmaster",array("mstr_id"=>$mstr_id));
						$this->SqlModel->deleteRecord("tbl_pinsdetails",array("mstr_id"=>$mstr_id,"use_member_id"=>0));
						set_message("success","You have successfully deleted record");	
						redirect_page("epin","pingenerate",array()); 
					}else{
						set_message("warning","Unable to delete this pin , pin is already use by member");	
						redirect_page("epin","pingenerate",array()); 
					}
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/epin/pingenerate',$data);
	}
	
	
	public function generate(){
		$this->load->view(ADMIN_FOLDER.'/epin/generate',$data);
	}
	
	public function pingeneratefran(){	
		$this->load->view(ADMIN_FOLDER.'/epin/pingeneratefran',$data);
	}
	
	
	public function generatefran(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$mstr_id = (_d($form_data['mstr_id'])>0)? _d($form_data['mstr_id']):_d($segment['mstr_id']);
		switch($action_request){
			case "ADD_UPDATE":
				
				if($form_data['generateEpin']==1 && $this->input->post()!=''){
					$type_id = FCrtRplc($form_data['type_id']);
					$no_pin = FCrtRplc($form_data['no_pin']);
					$franchisee_id = FCrtRplc($form_data['franchisee_id']);
					$bank_id = FCrtRplc($form_data['bank_id']);
					$payment_date = InsertDate($form_data['payment_date']);
					$payment_sts = FCrtRplc($form_data['payment_sts']);
					$pin_price = FCrtRplc($form_data['pin_price']);
					$net_amount = FCrtRplc($form_data['net_amount']);
					
					$AR_PACK = $model->getPinType($type_id);
					
					$data = array("type_id"=>$type_id,
						"no_pin"=>$no_pin,
						"prod_pv"=>$AR_PACK['prod_pv'],
						"pin_price"=>$pin_price,
						"net_amount"=>$net_amount,
						"franchisee_id"=>$franchisee_id,
						"bank_id"=>$bank_id,
						"payment_date"=>$payment_date,
						"payment_sts"=>$payment_sts,
						"ip_address"=>$_SERVER['REMOTE_ADDR'],
						"generate_by"=>$this->session->userdata('oprt_name')
					);
					if($model->checkCount("tbl_pinsmaster","mstr_id",$mstr_id)==0){
						$mstr_id = $this->SqlModel->insertRecord("tbl_pinsmaster",$data);
						$model->generatePinDetail($mstr_id);
						set_message("success","You have successfully generated  a E-pin");
						redirect_page("epin","viewpinfran",array("mstr_id"=>_e($mstr_id)));			
					}
				}
			break;
			case "DELETE":
				if($mstr_id>0){
					if($model->checkCountPro("tbl_pinsdetails","mstr_id='$mstr_id' AND use_member_id>0")==0){
						$this->SqlModel->deleteRecord("tbl_pinsmaster",array("mstr_id"=>$mstr_id));
						$this->SqlModel->deleteRecord("tbl_pinsdetails",array("mstr_id"=>$mstr_id,"use_member_id"=>0));
						set_message("success","You have successfully deleted record");	
						redirect_page("epin","generatefran",array()); 
					}else{
						set_message("warning","Unable to delete this pin , pin is already use by member");	
						redirect_page("epin","generatefran",array()); 
					}
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/epin/generatefran',$data);
	}
	
	public function viewpinfran(){
		$this->load->view(ADMIN_FOLDER.'/epin/viewpinfran',$data);
	}
	
	
	public function viewpin(){
		$this->load->view(ADMIN_FOLDER.'/epin/viewpin',$data);
	}
	
	public function blockpin(){	
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$pin_id = ($form_data['pin_id'])? $form_data['pin_id']:_d($segment['pin_id']);
		switch($action_request){
			case "BLOCK":
				$block_sts = FCrtRplc($segment['block_sts']);
				if($pin_id>0){
					$this->SqlModel->updateRecord("tbl_pinsdetails",array("block_sts"=>$block_sts),array("pin_id"=>$pin_id));
					set_message("success","You have successfully updated pin status");	
					redirect_page("epin","blockpin",array()); exit;
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/epin/blockpin',$data);
	}
	
	public function usedpin(){	
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$pin_id = ($form_data['pin_id'])? $form_data['pin_id']:_d($segment['pin_id']);
		switch($action_request){
			case "BLOCK":
				$block_sts = FCrtRplc($segment['block_sts']);
				if($pin_id>0){
					$this->SqlModel->updateRecord("tbl_pinsdetails",array("block_sts"=>$block_sts),array("pin_id"=>$pin_id));
					set_message("success","You have successfully updated pin status");	
					redirect_page("epin","blockpin",array()); exit;
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/epin/usedpin',$data);
	}
	
	public function pinrequest(){
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$request_id = ($form_data['request_id'])? $form_data['request_id']:_d($segment['request_id']);
		switch($action_request){
			case "STS":
				$assign_sts = _d($segment['assign_sts']);
				if($request_id>0){
					$data = array("assign_sts"=>$assign_sts);
					$this->SqlModel->updateRecord("tbl_pin_request",$data,array("request_id"=>$request_id));
					set_message("success","You have successfully updated E-pin request status");	
					redirect_page("epin","pinrequest",array()); exit;
				}else{
					set_message("warning","Unable to update request , please try again");	
					redirect_page("epin","pinrequest",array()); exit;
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/epin/pinrequest',$data);
	}
	
	public function unusedpin(){	
		$this->load->view(ADMIN_FOLDER.'/epin/unusedpin',$data);
	}
	
	public function pintransfer(){	
		$this->load->view(ADMIN_FOLDER.'/epin/pintransfer',$data);
	}
	
	public function pinlevel(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$level_id = (_d($form_data['level_id'])>0)? _d($form_data['level_id']):_d($segment['level_id']);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submit-level']==1 && $this->input->post()!=''){
					$type_id = FCrtRplc($form_data['type_id']);
					$level_no = FCrtRplc($form_data['level_no']);
					$level_cmsn = FCrtRplc($form_data['level_cmsn']);
					$data = array("type_id"=>$type_id,
							"level_no"=>getTool($level_no,''),
							"level_cmsn"=>getTool($level_cmsn,0)
					);
					if($model->checkCount("tbl_setup_level_cmsn","level_id",$level_id)>0){
						$this->SqlModel->updateRecord("tbl_setup_level_cmsn",$data,array("level_id"=>$level_id));
						set_message("success","You have successfully updated a banner");
						redirect_page("epin","pinlevel",array());
					}else{
						$level_id = $this->SqlModel->insertRecord("tbl_setup_level_cmsn",$data);
						set_message("success","You have successfully added a record");
						redirect_page("epin","pinlevel",array());					
					}
					
					
				}
			break;
			case "DELETE":
				if($level_id>0){
					$this->SqlModel->deleteRecord("tbl_setup_level_cmsn",array("level_id"=>$level_id));
					set_message("success","You have successfully deleted record");
					redirect_page("epin","pinlevel",array());		
				}else{
					set_message("warning","Can't delete, please try again");
					redirect_page("epin","pinlevel",array());	
				}
			break;
			case "STATUS":
				if($level_id>0){
					$level_sts = ($segment['level_sts']=="0")? "1":"0";
					$data = array("level_sts"=>$level_sts);
					$this->SqlModel->updateRecord("tbl_setup_level_cmsn",$data,array("level_id"=>$level_id));
					set_message("success","Status change successfully");
					redirect_page("epin","pinlevel",array()); exit;
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/epin/pinlevel',$data);
	}
	
	
	
}
?>