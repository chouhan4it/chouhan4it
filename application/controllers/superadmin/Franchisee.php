<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchisee extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	    if(!$this->isAdminLoggedIn()){
			 redirect(ADMIN_FOLDER);		
		}
	}
	
	public function datewiseinoutstock(){
		$this->load->view(ADMIN_FOLDER.'/franchisee/datewiseinoutstock',$data);
	}

	public function datewiseclosingstock(){
		$this->load->view(ADMIN_FOLDER.'/franchisee/datewiseclosingstock',$data);
	}

	public function shoppelist(){
		$this->load->view(ADMIN_FOLDER.'/franchisee/shoppelist',$data);
	}

	public function shoppereport(){
		$this->load->view(ADMIN_FOLDER.'/franchisee/shoppereport',$data);
	}

	public function franchiseelist(){
		$this->load->view(ADMIN_FOLDER.'/franchisee/franchiseelist',$data);
	}
	
	public function retailcommission(){
		$this->load->view(ADMIN_FOLDER.'/franchisee/retailcommission',$data);
	}
	
	public function franchisee(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$franchisee_id = (_d($form_data['franchisee_id'])>0)? _d($form_data['franchisee_id']):_d($segment['franchisee_id']);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submitFranchisee']==1 && $this->input->post()!=''){
					$fran_setup_id = FCrtRplc($form_data['fran_setup_id']);
					
					$name = FCrtRplc($form_data['name']);
					$store = FCrtRplc($form_data['store']);
					$email = FCrtRplc($form_data['email']);
					
					$gst_no = FCrtRplc($form_data['gst_no']);
					$tin_no = FCrtRplc($form_data['tin_no']);
					$mobile = FCrtRplc($form_data['mobile']);
					$address = FCrtRplc($form_data['address']);
					$pincode = FCrtRplc($form_data['pincode']);
					$city = FCrtRplc($form_data['city']);
					$state = FCrtRplc($form_data['state']);
					$user_name = FCrtRplc($form_data['user_name']);
					$password = FCrtRplc($form_data['password']);
					$stop_invoice = FCrtRplc($form_data['stop_invoice']);
	
	
					if($model->checkCount("tbl_franchisee","franchisee_id",$franchisee_id)>0){
						$data = array("fran_setup_id"=>$fran_setup_id,
							"store"=>getTool($store,''),
							"name"=>$name,
							"email"=>$email,
							"mobile"=>$mobile,
							"address"=>$address,
							"pincode"=>$pincode,
							"city"=>getTool($city,''),
							"state"=>getTool($state,''),
							"tin_no"=>getTool($tin_no,''),
							"gst_no"=>getTool($gst_no,''),
							"user_name"=>$user_name,
							"password"=>$password,
							"stop_invoice"=>$stop_invoice
						);
						$this->SqlModel->updateRecord("tbl_franchisee",$data,array("franchisee_id"=>$franchisee_id));
						set_message("success","You have successfully updated a setup details");
						redirect_page("franchisee","franchiseelist",array());					
					}else{
						$data = array("fran_setup_id"=>$fran_setup_id,
							"store"=>getTool($store,''),
							"name"=>$name,
							"email"=>$email,
							"mobile"=>$mobile,
							"address"=>$address,
							"pincode"=>$pincode,
							"city"=>getTool($city,''),
							"state"=>getTool($state,''),
							"tin_no"=>getTool($tin_no,''),
							"gst_no"=>getTool($gst_no,''),
							"user_name"=>$user_name,
							"password"=>$password,
							"stop_invoice"=>$stop_invoice
						);
						$this->SqlModel->insertRecord("tbl_franchisee",$data);
							set_message("success","Successfully added a new franchisee");
							redirect_page("franchisee","franchiseelist",array());
					}
				}
			break;
			case "STATUS":
				if($franchisee_id>0){
					$is_status = ($segment['is_status']=="0")? "1":"0";
					$data = array("is_status"=>$is_status);
					$this->SqlModel->updateRecord("tbl_franchisee",$data,array("franchisee_id"=>$franchisee_id));
					set_message("success","Status change successfully");
					redirect_page("franchisee","franchiseelist",array()); exit;
				}
			break;
			case "EDIT":
				$QR_PAGE ="SELECT * FROM  tbl_franchisee WHERE franchisee_id='".$franchisee_id."'";
				$SEL_QUERY = $this->db->query($QR_PAGE);
				$AR_PAGE = $SEL_QUERY->row_array();
				$data['ROW'] = $AR_PAGE;
			break;
		}
		
		$this->load->view(ADMIN_FOLDER.'/franchisee/franchisee',$data);
	}
	
	
	public function addtocourier(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$franchisee_id = (_d($form_data['franchisee_id'])>0)? _d($form_data['franchisee_id']):_d($segment['franchisee_id']);
		
		switch($action_request){
			case "ADD_UPDATE":
				if($form_data['submit-warehouse']==1 && $this->input->post()!=''){
					$AR_FRAN = $model->getFranchiseeDetail($franchisee_id);
					$company_name = $AR_FRAN['store'];
					$address1 = $AR_FRAN['address'];
					$address2 = '';
					$mobile = $AR_FRAN['mobile'];
					$pincode = $AR_FRAN['pincode'];
					
					$country_id = $form_data['country_id'];
					$state_id = $form_data['state_id'];
					$city_id = $form_data['city_id'];
					
					$access_token = ITHINK_ACCESS;
					$secret_key = ITHINK_KEY;
					
					$data_set = array("company_name"=>$company_name,
						"address1"=>$address1,
						"address2"=>$address2,
						"mobile"=>$mobile,
						"pincode"=>$pincode,
						"city_id"=>$city_id,
						"state_id"=>$state_id,
						"country_id"=>$country_id,
						"access_token"=>$access_token,
						"secret_key"=>$secret_key
					);
					$data = array("data"=>$data_set);
					$request_param =  json_encode($data,true);
										
					$curl = curl_init();
					curl_setopt_array($curl, array(
							CURLOPT_URL             => "https://manage.ithinklogistics.com/api_v2/warehouse/add.json",
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
						set_message("warning","Error #:" . $err);
						redirect_page("franchisee","addtocourier",array("franchisee_id"=>_e($franchisee_id)));
					}else{
						$status_code = $response_array['status_code'];
						$status = $response_array['status'];
						$warehouse_id = $response_array['warehouse_id'];
						if($model->checkCount("tbl_franchisee","warehouse_id",$warehouse_id)==0){
							$data_up = array("warehouse_id"=>$warehouse_id);
							$this->SqlModel->updateRecord("tbl_franchisee",$data_up,array("franchisee_id"=>$franchisee_id));
							set_message("success","Branch successfully added to ithink logistic wharehouse, please  contact  ithinklogistic team for approval");
							redirect_page("franchisee","franchiseelist",array());					
						}
					}
				}
			break;
		}
		$this->load->view(ADMIN_FOLDER.'/franchisee/addtocourier',$data);
	}
	
	
 	public function accesspanel(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$franchisee_id = _d($segment['franchisee_id']);
		if($franchisee_id>0){
			$Q_MEM = "SELECT * FROM  tbl_franchisee WHERE franchisee_id='$franchisee_id' AND is_delete>0";
			$fetchRow = $this->SqlModel->runQuery($Q_MEM,true);
			if($fetchRow['franchisee_id']>0){
				$this->session->set_userdata('fran_id',$fetchRow['franchisee_id']);
				$this->session->set_userdata('fran_user',$fetchRow['user_name']);
				redirect(FRANCHISE_PATH);
			}else{
				set_message("warning","Invalid franchisee id");
				redirect_page("franchisee","franchiseelist",array()); 
			}
		}else{
			set_message("warning","Please enter valid franchisee id");
			redirect_page("franchisee","franchiseelist",array()); 
		}
	}
	
	
	public function reportcollection(){
		$this->load->view(ADMIN_FOLDER.'/franchisee/reportcollection',$data);
	}	
	
	
	
}
?>