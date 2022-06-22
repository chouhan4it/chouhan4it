<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operation extends MY_Controller {

	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
		
		#$this->load->view('excel/reader');
	}

	public function deletecaching(){
		$this->db->cache_delete_all();
		set_message("success","caching deleted successfully");
		
		redirect_page("homepage","","");
	}
	
	public function blank(){
		$oprt_id  = $this->session->userdata('oprt_id');
		$sel_query = $this->db->query("SELECT * FROM tbl_operator WHERE oprt_id='$oprt_id'");
		$fetchRow = $sel_query->row_array();
		$data['fetchRow']=$fetchRow;
		$this->load->view(ADMIN_FOLDER.'/operation/blank',$data);
	}
	
	public function banner(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$banner_id = (_d($form_data['banner_id'])>0)? _d($form_data['banner_id']):_d($segment['banner_id']);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitBanner']==1 && $this->input->post()!=''){
					$banner_name = FCrtRplc($form_data['banner_name']);
					$banner_detail = FCrtRplc($form_data['banner_detail']);
					$banner_link = FCrtRplc($form_data['banner_link']);
					$data = array("banner_name"=>$banner_name,
							"banner_detail"=>getTool($banner_detail,''),
							"banner_link"=>$banner_link,
							"banner_id"=>$banner_id
					);
					if($model->checkCount("tbl_banner","banner_id",$banner_id)>0){
						$this->SqlModel->updateRecord("tbl_banner",$data,array("banner_id"=>$banner_id));
						$model->uploadBannerImg($_FILES,array("banner_id"=>$banner_id),"");
						set_message("success","You have successfully updated a banner");
						redirect_page("operation","banner",array());
					}else{
						
						$banner_id = $this->SqlModel->insertRecord("tbl_banner",$data);
						$model->uploadBannerImg($_FILES,array("banner_id"=>$banner_id),"");
						set_message("success","You have successfully added a record");
						redirect_page("operation","banner",array());					
					}
					
					
				}
			break;
			case "DELETE":
				if($banner_id>0){
					$SQL_QRY = "SELECT * FROM tbl_banner WHERE banner_id='".$banner_id."'";
					$AR_FILE = $this->SqlModel->runQuery($SQL_QRY,true);
					$final_location = $fldvPath."upload/banner/".$AR_FILE['banner_file'];
					$fldvImageArr= @getimagesize($final_location);
					if ($fldvImageArr['mime']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					$this->SqlModel->deleteRecord("tbl_banner",array("banner_id"=>$banner_id));
					set_message("success","You have successfully deleted record");
					redirect_page("operation","banner",array());		
				}else{
					set_message("warning","Can't delete, please try again");
					redirect_page("operation","banner",array());	
				}
			break;
			case "STATUS":
				if($banner_id>0){
					$status = ($segment['status']=="0")? "1":"0";
					$data = array("status"=>$status);
					$this->SqlModel->updateRecord("tbl_banner",$data,array("banner_id"=>$banner_id));
					set_message("success","Status change successfully");
					redirect_page("operation","banner",array()); exit;
				}
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_sys_menu_main WHERE ptype_id='$ptype_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/banner',$data);
	}
	
	public function configuration(){
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$model = new OperationModel();
		if($form_data['configSetting']==1 && $this->input->post()!=''){
			$CONFIG_COMPANY_NAME = FCrtRplc($form_data['CONFIG_COMPANY_NAME']);
			$CONFIG_WEBSITE = FCrtRplc($form_data['CONFIG_WEBSITE']);
			$CONFIG_TDS = FCrtRplc($form_data['CONFIG_TDS']);
			$CONFIG_SERVICE = FCrtRplc($form_data['CONFIG_SERVICE']);
			$CONFIG_MEM_LOGIN_STS = FCrtRplc($form_data['CONFIG_MEM_LOGIN_STS']);
			$CONFIG_SMS_STS = FCrtRplc($form_data['CONFIG_SMS_STS']);
			$model->setConfig("CONFIG_COMPANY_NAME",$CONFIG_COMPANY_NAME);
			$model->setConfig("CONFIG_WEBSITE",$CONFIG_WEBSITE);
			$model->setConfig("CONFIG_TDS",$CONFIG_TDS);
			$model->setConfig("CONFIG_SERVICE",$CONFIG_SERVICE);
			$model->setConfig("CONFIG_MEM_LOGIN_STS",getSwitch($CONFIG_MEM_LOGIN_STS));
			$model->setConfig("CONFIG_SMS_STS",getSwitch($CONFIG_SMS_STS));
			set_message("success","You have successfully updated a configuration setting");
			redirect_page("operation","configuration",array());
		}
		$this->load->view(ADMIN_FOLDER.'/operation/configuration',$data);
	}
	
	public function pagesadd(){
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$ptype_id = ($form_data['ptype_id'])? $form_data['ptype_id']:$segment['ptype_id'];
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitMenu']==1 && $this->input->post()!=''){
					$icon_id = FCrtRplc($form_data['icon_id']);
					$type_name = FCrtRplc($form_data['type_name']);
					$order_id = FCrtRplc($form_data['order_id']);
					$fldiCount = SelectTableWithOption("tbl_sys_menu_main","COUNT(*)","ptype_id='$ptype_id'");
					if($fldiCount>0){
						UpdateTable("tbl_sys_menu_main","type_name='$type_name', icon_id='$icon_id', order_id='$order_id'",
						"ptype_id='$ptype_id'");
						set_message("success","You have successfully updated a record");
						redirect_page("operation","pagestype",array());		
					}else{
						$ptype_id = InsertTable("tbl_sys_menu_main","type_name='$type_name', icon_id='$icon_id', order_id='$order_id'");	
						set_message("success","You have successfully added a record");
						redirect_page("operation","pagestype",array());					
					}
					
					
				}
			break;
			case "DELETE":
				$fldiCount = SelectTableWithOption("tbl_sys_menu_sub","COUNT(*)","ptype_id='$ptype_id'");
				if($fldiCount==0){
					DeleteTableRow("tbl_sys_menu_main","ptype_id='$ptype_id'");
					DeleteTableRow("tbl_sys_menu_sub","ptype_id='$ptype_id'");
					$QR_DELETE = "DELETE FROM tbl_sys_menu_acs WHERE page_id IN(SELECT page_id FROM tbl_sys_menu_sub WHERE ptype_id='".$ptype_id."')";
					$this->db->query($QR_DELETE);
					set_message("success","You have successfully deleted record");
				}else{
					set_message("warning","Can't delete, menu asigned to submenus");
				}
				redirect_page("operation","pagestype",array()); exit;
			break;
			case "POSITION":
				$order_id = $form_data['order_id'];
				$ptype_id = $form_data['ptype_id'];
				foreach($ptype_id as $Key => $Val){
					$StrQ_Updt = "UPDATE tbl_sys_menu_main SET order_id='".$form_data['order_id'][$Key]."' WHERE 
					ptype_id='".$form_data['ptype_id'][$Key]."'";
					$this->db->query($StrQ_Updt);
				}
				set_message("success","You have successfully updated display order");
				redirect_page("operation","pagestype",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_sys_menu_main WHERE ptype_id='$ptype_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/pagesadd',$data);	
	}
	
	public function pagestype(){
		$this->load->view(ADMIN_FOLDER.'/operation/pagestype',$data);	
	}
	
	public function submenuadd(){
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$page_id = ($form_data['page_id'])? $form_data['page_id']:$segment['page_id'];
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitMenu']==1 && $this->input->post()!=''){
					$ptype_id = FCrtRplc($form_data['ptype_id']);
					$page_title = FCrtRplc($form_data['page_title']);
					$page_name = FCrtRplc($form_data['page_name']);
					$order_id = FCrtRplc($form_data['order_id']);
					
					$fldiCount = SelectTableWithOption("tbl_sys_menu_sub","COUNT(*)","page_id='$page_id'");
					if($fldiCount>0){
						UpdateTable("tbl_sys_menu_sub","ptype_id='$ptype_id', page_title='$page_title', page_name='$page_name', order_id='$order_id'",
						"page_id='$page_id'");
						set_message("success","You have successfully updated a record");
						redirect_page("operation","subpage",array());							
					}else{
						$page_id = InsertTable("tbl_sys_menu_sub","ptype_id='$ptype_id', page_title='$page_title', page_name='$page_name', 
						order_id='$order_id'");	
						set_message("success","You have successfully added a record");
						redirect_page("operation","subpage",array());					
					}
				}
			break;
			case "DELETE":
				$fldiCount = SelectTableWithOption("tbl_sys_menu_acs","COUNT(*)","page_id='$page_id'");
				if($fldiCount!=''){
					DeleteTableRow("tbl_sys_menu_sub","page_id='$page_id'");
					set_message("success","You have successfully deleted record");
				}else{
					set_message("warning","Can't delete, menu asigned to users");
				}
				redirect_page("operation","subpage",array()); exit;
			break;
			case "POSITION":
				$order_id = $form_data['order_id'];
				$page_id = $form_data['page_id'];
				foreach($page_id as $Key => $Val){
					$QR_UP = "UPDATE tbl_sys_menu_sub SET order_id='".$form_data['order_id'][$Key]."' WHERE 
					page_id='".$form_data['page_id'][$Key]."'";
					$this->db->query($QR_UP);
				}
				set_message("success","You have successfully updated display order");
				redirect_page("operation","subpage",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_sys_menu_sub WHERE page_id='$page_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/submenuadd',$data);	
	}
	
	public function subpage(){
		$this->load->view(ADMIN_FOLDER.'/operation/subpage',$data);	
	}
	
	
	public function cityadd(){
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$city_id = ($form_data['city_id'])? $form_data['city_id']:$segment['city_id'];
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitMenu']==1 && $this->input->post()!=''){
					$city_id = FCrtRplc($form_data['city_id']);
					$country_code = FCrtRplc($form_data['country_code']);
					$city_name = FCrtRplc($form_data['city_name']);
					$state_name = FCrtRplc($form_data['state_name']);
					
					$data = array("city_name"=>$city_name,
						"state_name"=>$state_name,
						"country_code"=>$country_code,
					);
					
					$fldiCount = SelectTableWithOption("tbl_city","COUNT(*)","city_id='$city_id'");
					if($fldiCount>0){
						$this->SqlModel->updateRecord("tbl_city",$data,array("city_id"=>$city_id));
						set_message("success","You have successfully updated a city details");
						redirect_page("operation","cityadd",array("city_id"=>$city_id,"action_request"=>"EDIT"));							
					}else{
						$this->SqlModel->insertRecord("tbl_city",$data,array("city_id"=>$city_id));
						set_message("success","You have successfully added a city");
						redirect_page("operation","citylist",array());					
					}
				}
			break;
			case "DELETE":
				if($city_id>0){
					$data = array("isDelete"=>0);
					$this->SqlModel->updateRecord("tbl_city",$data,array("city_id"=>$city_id));
					set_message("success","You have successfully deleted a city");
				}else{
					set_message("warning","Unable to delete city, please try again");
				}
				redirect_page("operation","citylist",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_city WHERE city_id='$city_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/cityadd',$data);	
	}
	
	public function citylist(){
		$this->load->view(ADMIN_FOLDER.'/operation/citylist',$data);	
	}

	public function pccntcitywise(){
		$this->load->view(ADMIN_FOLDER.'/operation/pccntcitywise',$data);	
	}

	public function pccntstatewise(){
		$this->load->view(ADMIN_FOLDER.'/operation/pccntstatewise',$data);	
	}
	
	public function systempermission(){
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		if($form_data['submitForm']==1 && $this->input->post()!=''){
			$group_id = FCrtRplc($form_data['group_id']);
			$StrQ_Dlt = "DELETE FROM tbl_sys_menu_acs WHERE group_id='$group_id' AND page_id NOT IN(1,2);";
			$this->db->query($StrQ_Dlt);
			foreach($form_data['fldvTList'] as $Key => $Value){
				$StrPart .= "('$group_id','$Value'), ";
			}
			if($StrPart!=""){
				$StrPart = StripString($StrPart, ", ");
				$StrQ_Insert = "INSERT INTO tbl_sys_menu_acs(group_id, page_id) VALUES $StrPart;";
				$this->db->query($StrQ_Insert);
				set_message("success","Access Permission for the selected group has been updated.");
			}else{
				set_message("warning"," Unable to allow permission for selected group.");
			}
			redirect_page("operation","systempermission",array()); exit;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/systempermission',$data);	
	}
	
	
	
	public function operatoradd(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$oprt_id = ($form_data['oprt_id'])? $form_data['oprt_id']:$segment['oprt_id'];
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitOperator']==1 && $this->input->post()!=''){
					$name = FCrtRplc($form_data['name']);
					$user_name = FCrtRplc($form_data['user_name']);
					$password = FCrtRplc($form_data['password']);
					$email_address = FCrtRplc($form_data['email_address']);
					$mobile = FCrtRplc($form_data['mobile']);
					$group_id = FCrtRplc($form_data['group_id']);
					$type = $model->getGroupType($group_id);
					$data = array("group_id"=>$group_id,
						"branch_id"=>($branch_id)? $branch_id:0,
						"name"=>$name,
						"company_name"=>null_val($company_name),
						"user_name"=>$user_name,
						"password"=>$password,
						"email_address"=>$email_address,
						"mobile"=>$mobile,
						"department"=>null_val($department),
						"last_log"=>getLocalTime(),
						"type"=>($type)? $type:"OA",
						"temp_id"=>"01"
					);
					if($model->checkCount("tbl_operator","oprt_id",$oprt_id)>0){
						$this->SqlModel->updateRecord("tbl_operator",$data,array("oprt_id"=>$oprt_id));
						set_message("success","You have successfully updated a operator details");
						redirect_page("operation","operatoradd",array("oprt_id"=>$oprt_id,"action_request"=>"EDIT"));					
					}else{
						if($model->checkUserExist($user_name)==0){
							$this->SqlModel->insertRecord("tbl_operator",$data);
							set_message("success","You have successfully added a operator detail");
						}else{
							set_message("warning","This username is already exist");
						}
						redirect_page("operation","operator",array());					
					}
				}
			break;
			case "DELETE":
				if($oprt_id>0){
					$model->deleteTable("tbl_operator",array("oprt_id"=>$oprt_id));
					set_message("success","You have successfully deleted operator");
				}else{
					set_message("warning","Failed , unable to delete operator");
				}
				redirect_page("operation","operator",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_operator WHERE oprt_id='$oprt_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/operatoradd',$data);
	}
	
	public function operator(){
		$this->load->view(ADMIN_FOLDER.'/operation/operator',$data);	
	}
	
	
	public function cms(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$id_cms = ($form_data['id_cms'])? $form_data['id_cms']:$segment['id_cms'];
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitCMS']==1 && $this->input->post()!=''){
					$cms_title = FCrtRplc($form_data['cms_title']);
					$meta_title = FCrtRplc($form_data['meta_title']);
					$meta_description = FCrtRplc($form_data['meta_description']);
					$meta_keywords = FCrtRplc($form_data['meta_keywords']);
					$content = FCrtRplc($form_data['content']);
					$link_rewrite = FCrtRplc($form_data['link_rewrite']);
					$index = ($form_data['index']=="on")? 1:0;
					$active = ($form_data['active']=="on")? 1:0;
					$id_parent = FCrtRplc($form_data['id_parent']);
					$data = array("cms_title"=>$cms_title,
						"id_parent"=>$id_parent,
						"meta_title"=>$meta_title,
						"meta_description"=>$meta_description,
						"meta_keywords"=>$meta_keywords,
						"content"=>$content,
						"link_rewrite"=>$link_rewrite,
						"index"=>$index,
						"active"=>$active,
						"date_time"=>getLocalTime()
					);
					if($model->checkCount("tbl_cms","id_cms",$id_cms)>0){
						$this->SqlModel->updateRecord("tbl_cms",$data,array("id_cms"=>$id_cms));
						set_message("success","You have successfully updated a cms details");
						redirect_page("operation","cms",array("id_cms"=>$id_cms,"action_request"=>"EDIT"));					
					}else{
						$this->SqlModel->insertRecord("tbl_cms",$data);
						set_message("success","You have successfully added a cms detail");
						redirect_page("operation","cmslist",array());					
					}
				}
			break;
			case "DELETE":
				if($id_cms>0){
					$model->deleteTable("tbl_cms",array("id_cms"=>$id_cms));
					set_message("success","You have successfully deleted cms details");
				}else{
					set_message("warning","Failed , unable to delete cms");
				}
				redirect_page("operation","cmslist",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_cms WHERE id_cms='$id_cms'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/cms',$data);	
	}
	
	public function cmslist(){
		
		$this->load->view(ADMIN_FOLDER.'/operation/cmslist',$data);	
	}
	
	public function testimonial(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$testimonial_id = (_d($form_data['testimonial_id'])>0)? _d($form_data['testimonial_id']):_d($segment['testimonial_id']);
		switch($action_request){
			case "DELETE":
				if($testimonial_id>0){
					$model->deleteTable("tbl_testimonial",array("testimonial_id"=>$testimonial_id));
					set_message("success","You have successfully deleted testimonial");
				}else{
					set_message("warning","Failed , unable to delete testimonial");
				}
				redirect_page("operation","testimonial",array()); exit;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/testimonial',$data);	
	}
	
	
	public function oprtlog(){
		$this->load->view(ADMIN_FOLDER.'/operation/oprtlog',$data);	
	}
	
	public function template(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$option_id = ($form_data['option_id'])? $form_data['option_id']:$segment['option_id'];
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitTemplate']==1 && $this->input->post()!=''){
					$option_value = FCrtRplc($form_data['option_value']);
					$data = array("option_value"=>$option_value);
					if($model->checkCount("tbl_mail_template","option_id",$option_id)>0){
						$this->SqlModel->updateRecord("tbl_mail_template",$data,array("option_id"=>$option_id));
						set_message("success","You have successfully updated a mail template details");
						redirect_page("operation","template",array("option_id"=>$option_id,"action_request"=>"EDIT"));					
					}else{
						set_message("warning","Unable to update, please try  again");
						redirect_page("operation","mailtemplate",array());					
					}
				}
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_mail_template WHERE option_id='$option_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/template',$data);	
	}
	public function mailtemplate(){
		$this->load->view(ADMIN_FOLDER.'/operation/mailtemplate',$data);	
	}
	
	public function tag(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$tag_id = ($form_data['tag_id'])? _d($form_data['tag_id']):_d($segment['tag_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitTag']==1 && $this->input->post()!=''){
					$tag_name = FCrtRplc($form_data['tag_name']);
					
					$data = array("tag_name"=>$tag_name);
					
					if($model->checkCount("tbl_tags","tag_id",$tag_id)>0){
						$this->SqlModel->updateRecord("tbl_tags",$data,array("tag_id"=>$tag_id));
						set_message("success","You have successfully updated a tag");
						redirect_page("operation","taglist",array("tag_id"=>_e($tag_id),"action_request"=>"EDIT"));							
					}else{
						$this->SqlModel->insertRecord("tbl_tags",$data,array("tag_id"=>$tag_id));
						set_message("success","You have successfully added a new tag");
						redirect_page("operation","taglist",array());					
					}
				}
			break;
			case "STATUS":
				$tag_sts = FCrtRplc($segment['tag_sts']);
				$new_sts = ($tag_sts==0)? 1:0;
				$data = array("tag_sts"=>$new_sts);
				$this->SqlModel->updateRecord("tbl_tags",$data,array("tag_id"=>$tag_id));
				set_message("success","You have successfully changed a status");
				redirect_page("operation","taglist",array());	
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_tags WHERE tag_id='".$tag_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/tag',$data);	
	}
	
	public function tagsearch(){
		$q = $this->input->get('q');
		$QR_TAGS = "SELECT  tag_id AS id, tag_name AS name FROM tbl_tags 
				WHERE tag_sts>0 AND delete_sts>0 AND tag_name LIKE '%$q%' 
				ORDER BY tag_name ASC LIMIT 5";
		$AR_OBJ = $this->SqlModel->runQuery($QR_TAGS);
		$json_response = json_encode($AR_OBJ);
		
		if($_GET["callback"]) {
    		$json_response = $_GET["callback"] . "(" . $json_response . ")";
		}

	 	echo $json_response;
		
	}
	
	public function productsearch(){
		$q = $this->input->get('q');
		$QR_TAGS = "SELECT tp.post_id AS id , CONCAT_WS('@',tpl.post_title,tpl.post_size) AS name
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			WHERE tp.delete_sts>0  AND  tpl.post_title LIKE '%$q%' AND tp.is_product='0'
			GROUP BY tp.post_id  
			ORDER BY tp.post_id DESC LIMIT 5";
		$AR_OBJ = $this->SqlModel->runQuery($QR_TAGS);
		$json_response = json_encode($AR_OBJ);
		
		if($_GET["callback"]) {
    		$json_response = $_GET["callback"] . "(" . $json_response . ")";
		}

	 	echo $json_response;
		
	}

	public function citysearch(){
		$q = $this->input->get('q');
		$QR_TAGS = "SELECT *
					FROM tbl_city 
					WHERE country_code='IND' AND city_name LIKE '%$q%'
					ORDER BY city_name DESC LIMIT 5";
		$AR_OBJ = $this->SqlModel->runQuery($QR_TAGS);
		$json_response = json_encode($AR_OBJ);
		
		if($_GET["callback"]) {
    		$json_response = $_GET["callback"] . "(" . $json_response . ")";
		}

	 	echo $json_response;		
	}
	
	public function membersearch(){
		$q = $this->input->get('q');
		$QR_TAGS = "SELECT  tm.member_id AS id, CONCAT_WS(' ',tm.first_name,tm.last_name,tm.user_id)  AS name 
				 FROM tbl_members AS tm
				WHERE  tm.delete_sts>0 
				AND ( tm.first_name LIKE '%$q%' OR tm.user_id LIKE '%$q%' OR tm.member_email LIKE '%$q%' )
				ORDER BY tm.user_id ASC LIMIT 5";
		$AR_OBJ = $this->SqlModel->runQuery($QR_TAGS);
		$json_response = json_encode($AR_OBJ);
		
		if($_GET["callback"]) {
    		$json_response = $_GET["callback"] . "(" . $json_response . ")";
		}

	 	echo $json_response;
		
	}
	
	public function taglist(){
		$this->load->view(ADMIN_FOLDER.'/operation/taglist',$data);	
	}
	
	public function news(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$news_id = (_d($form_data['news_id'])>0)? _d($form_data['news_id']):_d($segment['news_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitNEWS']==1 && $this->input->post()!=''){
					$news_title = FCrtRplc($form_data['news_title']);
					$news_detail = FCrtRplc($form_data['news_detail']);
					$news_date = InsertDate($form_data['news_date']);
					$news_sts = ($form_data['news_sts']=="on")? 1:0;
					$data = array("news_title"=>$news_title,
						"news_detail"=>$news_detail,
						"news_date"=>$news_date,
						"news_sts"=>$news_sts,
						"news_type"=>"PUBLIC"
					);
					if($model->checkCount("tbl_news","news_id",$news_id)>0){
						$this->SqlModel->updateRecord("tbl_news",$data,array("news_id"=>$news_id));
						$model->uploadNewsImage($_FILES,array("news_id"=>$news_id),"");
						set_message("success","You have successfully updated a news details");
						redirect_page("operation","newlist",array());					
					}else{
						$news_id = $this->SqlModel->insertRecord("`tbl_news",$data);
						$model->uploadNewsImage($_FILES,array("news_id"=>$news_id),"");
						set_message("success","You have successfully added a news detail");
						redirect_page("operation","newlist",array());					
					}
				}
			break;
			case "DELETE":
				if($news_id>0){
					$this->SqlModel->updateRecord("`tbl_news",array("isDelete"=>0),array("news_id"=>$news_id));
					set_message("success","You have successfully deleted news details");
				}else{
					set_message("warning","Failed , unable to delete news");
				}
				redirect_page("operation","newlist",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_news WHERE news_id='$news_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/news',$data);	
	}
	
	public function newstomember(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$news_id = (_d($form_data['news_id'])>0)? _d($form_data['news_id']):_d($segment['news_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitAnnouncement']==1 && $this->input->post()!=''){
					$news_title = FCrtRplc($form_data['news_title']);
					$news_detail = FCrtRplc($form_data['news_detail']);
					$news_date = InsertDate($form_data['news_date']);
					$news_sts = ($form_data['news_sts']=="on")? 1:0;
					$data = array("news_title"=>$news_title,
						"news_detail"=>$news_detail,
						"news_date"=>$news_date,
						"news_sts"=>$news_sts,
						"checkallmember"=>0,
						"news_type"=>"PRIVATE"
					);
					$this->SqlModel->deleteRecord("tbl_new_access",array("news_id"=>$news_id));
					if($model->checkCount("tbl_news","news_id",$news_id)>0){
						$this->SqlModel->updateRecord("tbl_news",$data,array("news_id"=>$news_id));
						$model->updateNewsAccess($news_id,$form_data);
						set_message("success","You have successfully updated a news details");
						redirect_page("operation","newlistprivate",array());					
					}else{
						$news_id = $this->SqlModel->insertRecord("tbl_news",$data);
						$model->updateNewsAccess($news_id,$form_data);
						set_message("success","You have successfully added a news detail");
						redirect_page("operation","newlistprivate",array());					
					}
				}
			break;
			case "DELETE":
				if($news_id>0){
					$this->SqlModel->updateRecord("`tbl_news",array("isDelete"=>0),array("news_id"=>$news_id));
					set_message("success","You have successfully deleted news details");
				}else{
					set_message("warning","Failed , unable to delete news");
				}
				redirect_page("operation","newlistprivate",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_news WHERE news_id='$news_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
	
		$this->load->view(ADMIN_FOLDER.'/operation/newstomember',$data);	
	}
	
	public function newlistprivate(){
		$this->load->view(ADMIN_FOLDER.'/operation/newlistprivate',$data);	
	}
	
	public function newlist(){
		$this->load->view(ADMIN_FOLDER.'/operation/newlist',$data);	
	}
	
	public function albumlist(){
		$this->load->view(ADMIN_FOLDER.'/operation/albumlist',$data);	
	}
	
	public function album(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$gallery_id = ($form_data['gallery_id'])? _d($form_data['gallery_id']):_d($segment['gallery_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitAlbumSave']==1 && $this->input->post()!=''){
					$gallery_name = FCrtRplc($form_data['gallery_name']);
					$gallery_detail = FCrtRplc($form_data['gallery_detail']);
					$cover_image = InsertDate($form_data['cover_image']);
					$date_time = InsertDate($form_data['date_time']);

					$data = array("gallery_name"=>$gallery_name,
						"gallery_detail"=>$gallery_detail,
						"date_time"=>$date_time
					);
					if($model->checkCount("tbl_gallery","gallery_id",$gallery_id)>0){
						$this->SqlModel->updateRecord("tbl_gallery",$data,array("gallery_id"=>$gallery_id));
						$model->uploadCoverImage($_FILES,array("gallery_id"=>$gallery_id),"");
						set_message("success","You have successfully updated a galley details");
						redirect_page("operation","albumlist",array());					
					}else{
						$gallery_id = $this->SqlModel->insertRecord("tbl_gallery",$data);
						$model->uploadCoverImage($_FILES,array("gallery_id"=>$gallery_id),"");
						set_message("success","You have successfully added a galley detail");
						redirect_page("operation","albumlist",array());					
					}
				}
			break;
			case "DELETE":
				if($gallery_id>0){
					$this->SqlModel->updateRecord("`tbl_gallery",array("delete_sts"=>0),array("gallery_id"=>$gallery_id));
					set_message("success","You have successfully deleted news details");
				}else{
					set_message("warning","Failed , unable to delete news");
				}
				redirect_page("operation","albumlist",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_gallery WHERE gallery_id='$gallery_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/album',$data);	
	}
	
	
	public function supplier(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$today_date = getLocalTime();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$supplier_id = (_d($form_data['supplier_id'])>0)? _d($form_data['supplier_id']):_d($segment['supplier_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitSupplier']==1 && $this->input->post()!=''){
					$supplier_id = FCrtRplc($form_data['supplier_id']);
					$supplier_code = FCrtRplc(strtoupper($form_data['supplier_code']));
					$supplier_name = FCrtRplc($form_data['supplier_name']);
					$supplier_address = FCrtRplc($form_data['supplier_address']);
					
					$data = array("supplier_name"=>$supplier_name,
						"supplier_code"=>$supplier_code,
						"supplier_address"=>$supplier_address,
						"join_date"=>$today_date
					);
					
					
					if($model->checkCount("tbl_supplier","supplier_id",$supplier_id)>0){
						$this->SqlModel->updateRecord("tbl_supplier",$data,array("supplier_id"=>$supplier_id));
						set_message("success","You have successfully updated a supplier details");
						redirect_page("operation","supplier",array("supplier_id"=>$supplier_id,"action_request"=>"EDIT"));							
					}else{
						$this->SqlModel->insertRecord("tbl_supplier",$data,array("supplier_id"=>$supplier_id));
						set_message("success","You have successfully added a supplier");
						redirect_page("operation","supplierlist",array());					
					}
				}
			break;
			case "DELETE":
				if($supplier_id>0){
					$data = array("delete_sts"=>0);
					$this->SqlModel->updateRecord("tbl_supplier",$data,array("supplier_id"=>$supplier_id));
					set_message("success","You have successfully deleted a supplier");
				}else{
					set_message("warning","Unable to delete supplier, please try again");
				}
				redirect_page("operation","supplierlist",array()); exit;
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_supplier WHERE supplier_id='$supplier_id'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/supplier',$data);	
	}
	
	public function supplierlist(){
		$this->load->view(ADMIN_FOLDER.'/operation/supplierlist',$data);	
	}
	
	
	public function smscustom(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$today_date = getLocalTime();
		$segment = $this->uri->uri_to_assoc(2);
		
		if($form_data['submit-sms']!='' && $this->input->post()!=''){
			
			$message = FCrtRplc($form_data['message']);
			
			$user_id = FCrtRplc($form_data['user_id']);
			if($user_id!=''){
				$StrWhr .=" AND tm.user_id='$user_id'";
			}
			
			$spr_user_id = FCrtRplc($form_data['spr_user_id']);
			if($spr_user_id!=''){
				
				$member_id = $model->getMemberId($spr_user_id);
				$AR_SPR = $model->getMember($member_id);
				$nleft = $AR_SPR['nleft'];
				$nright = $AR_SPR['nright'];
				
				$StrWhr .=" AND tree.nleft BETWEEN '$nleft' AND '$nright'";
			}
			
			
			$state_name = FCrtRplc($form_data['state_name']);
			if($state_name!=''){
				$StrWhr .=" AND tm.state_name='$state_name'";
			}
			
			$city_name = FCrtRplc($form_data['city_name']);
			if($city_name!=''){
				$StrWhr .=" AND tm.city_name='$city_name'";
			}
			
			if($StrWhr==''){
				$StrWhr .=" AND tm.member_id='0'";
			}
			
			$QR_SEL = "SELECT tm.*
					 FROM tbl_members AS tm	
					 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					 WHERE tm.delete_sts>0     $StrWhr 
					 GROUP BY tm.member_id
					 ORDER BY tm.member_id ASC";
			$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
			
			$Ctrl = 0;
			foreach($RS_SEL as $AR_SEL):
				$member_id = $AR_SEL['member_id'];
				$member_mobile = $AR_SEL['member_mobile'];
				if($member_id>0){
					$AR_PARAM['member_id'] = $member_id;
					$AR_PARAM['sms_type'] = "MANU";
					Send_Single_SMS($member_mobile,$message,$AR_PARAM);
					$Ctrl++;
				}
				unset($member_id,$member_mobile);
				
			endforeach;
			if($Ctrl>0){
				set_message("success","You have successfully send sms to all search member");
				redirect_page("operation","smscustom",array());					
			}else{
				set_message("warning","Unable to send SMS , No member found on your search");
				redirect_page("operation","smscustom",array());	
			}
		}
		
		$this->load->view(ADMIN_FOLDER.'/operation/smscustom',$data);	
	}
	
	
	public function attribute(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$attribute_id = (_d($form_data['attribute_id'])>0)? _d($form_data['attribute_id']):_d($segment['attribute_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitTag']==1 && $this->input->post()!=''){
					$attribute_group_id = FCrtRplc($form_data['attribute_group_id']);
					$attribute_name = FCrtRplc($form_data['attribute_name']);
					
					$data_set = array("attribute_group_id"=>$attribute_group_id,
						"attribute_name"=>$attribute_name);
					
					if($model->checkCount("tbl_attribute","attribute_id",$attribute_id)>0){
						$this->SqlModel->updateRecord("tbl_attribute",$data_set,array("attribute_id"=>$attribute_id));
						set_message("success","You have successfully updated a attribute");
						redirect_page("operation","attributelist",array("attribute_id"=>_e($attribute_id),"action_request"=>"EDIT"));							
					}else{
						if($model->checkCountPro("tbl_attribute","attribute_group_id='$attribute_group_id' AND attribute_name='$attribute_name'")==0){
							$this->SqlModel->insertRecord("tbl_attribute",$data_set,array("attribute_id"=>$attribute_id));
							set_message("success","You have successfully added a new attribute");
							redirect_page("operation","attributelist",array());					
						}else{
							set_message("warning","This attribute is already exist");
							redirect_page("operation","attributelist",array());					
						}
					}
				}
			break;
			case "STATUS":
				$attribute_sts = FCrtRplc($segment['attribute_sts']);
				$new_sts = ($attribute_sts==0)? 1:0;
				$data = array("attribute_sts"=>$new_sts);
				$this->SqlModel->updateRecord("tbl_attribute",$data,array("attribute_id"=>$attribute_id));
				set_message("success","You have successfully changed a status");
				redirect_page("operation","attributelist",array());	
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_attribute WHERE attribute_id='".$attribute_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/attribute',$data);	
	}
	
	public function attributelist(){
		$this->load->view(ADMIN_FOLDER.'/operation/attributelist',$data);	
	}
	
	
	public function attributegroup(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$attribute_group_id = (_d($form_data['attribute_group_id'])>0)? _d($form_data['attribute_group_id']):_d($segment['attribute_group_id']);
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submit-attribute-group']==1 && $this->input->post()!=''){
					$attribute_group_name = FCrtRplc($form_data['attribute_group_name']);
					
					$data_set = array("attribute_group_name"=>$attribute_group_name);
					
					if($model->checkCount("tbl_attribute_group","attribute_group_id",$attribute_group_id)>0){
						$this->SqlModel->updateRecord("tbl_attribute_group",$data_set,array("attribute_group_id"=>$attribute_group_id));
						set_message("success","You have successfully updated a group attribute");
						redirect_page("operation","attributegrouplist",array("attribute_group_id"=>_e($attribute_group_id),"action_request"=>"EDIT"));							
					}else{
						$this->SqlModel->insertRecord("tbl_attribute_group",$data_set,array("attribute_group_id"=>$attribute_group_id));
						set_message("success","You have successfully added a group attribute");
						redirect_page("operation","attributegrouplist",array());					
					}
				}
			break;
			case "STATUS":
				$attribute_group_sts = FCrtRplc($segment['attribute_group_sts']);
				$new_sts = ($attribute_group_sts==0)? 1:0;
				$data = array("attribute_group_sts"=>$new_sts);
				$this->SqlModel->updateRecord("tbl_attribute_group",$data,array("attribute_group_id"=>$attribute_group_id));
				set_message("success","You have successfully changed a status");
				redirect_page("operation","attributegrouplist",array());	
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM tbl_attribute_group WHERE attribute_group_id='".$attribute_group_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/operation/attributegroup',$data);	
	}
	
	public function attributegrouplist(){
		$this->load->view(ADMIN_FOLDER.'/operation/attributegrouplist',$data);	
	}
	
	public function pricelist(){
		$this->load->view(ADMIN_FOLDER.'/operation/pricelist',$data);	
	}
	
	public function levelsetup(){
		$this->load->view(ADMIN_FOLDER.'/operation/levelsetup',$data);	
	}
	
	public function timeslot(){
		$this->load->view(ADMIN_FOLDER.'/operation/timeslot',$data);	
	}
	
}
