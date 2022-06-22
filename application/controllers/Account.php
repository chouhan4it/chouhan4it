<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   $this->load->library('parser');
	   $this->load->view('captcha/securimage');
	   $this->load->view('mailer/phpmailer');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	 
	
	public function welcome(){
		$AR_DT['page_title']="Welcome to ".WEBSITE;
		$data['ROW'] = $AR_DT;
		$this->load->view('welcome',$data);
	}
	
	public function welcomefranchisee(){
		$AR_DT['page_title']="Welcome to ".WEBSITE;
		$data['ROW'] = $AR_DT;
		$this->load->view('welcomefranchisee',$data);
	}
	
	
	
	public function paymentsubscription(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$current_date = getLocalTime();
		$this->load->view('paymentsubscription',$data);
	}
	
	
	public function register(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$login_ip = $_SERVER['REMOTE_ADDR'];
		$cart_count = $model->getCartCount();
		$img = new Securimage();
		
		$NO_ACCOUNT_MOBILE = $model->getValue("NO_ACCOUNT_MOBILE");
		if($form_data['submit-register']!='' && $this->input->post()!=''){
			$valid = $img->check($form_data["captcha_code"]);
			$valid = true;
  			if($form_data["captcha_code"]!='') {
				
				$model->checkRegistration();
				
				$first_name = FCrtRplc($form_data['first_name']);
				$last_name = FCrtRplc($form_data['last_name']);
				$full_name = $first_name." ".$last_name;
				$member_email = FCrtRplc($form_data['member_email']);
				$user_password = FCrtRplc($form_data['user_password']);
				
				$gender = FCrtRplc($form_data['gender']);
				$member_mobile = FCrtRplc($form_data['member_mobile']);
				$current_address = FCrtRplc($form_data['current_address']);
				$date_of_birth = InsertDate($form_data['date_of_birth']);
				
				$pan_no = FCrtRplc($form_data['pan_no']);
				$aadhar_no = FCrtRplc($form_data['aadhar_no']);
				
				$bank_name = FCrtRplc($form_data['bank_name']);
				$account_number = FCrtRplc($form_data['account_number']);
				$branch = FCrtRplc($form_data['branch']);
				$ifc_code = FCrtRplc($form_data['ifc_code']);
				
				$nominal_name = FCrtRplc($form_data['nominal_name']);
				$nominal_relation = FCrtRplc($form_data['nominal_relation']);
				
				$pin_code = FCrtRplc($form_data['pin_code']);
				$land_mark = FCrtRplc($form_data['land_mark']);
				
				$city_name = FCrtRplc($form_data['city_name']);
				$state_name = FCrtRplc($form_data['state_name']);
				
				$left_right = FCrtRplc($form_data['left_right']);
				$spr_user_id  = FCrtRplc($form_data['spr_user_id']);
				#$spil_user_id  = FCrtRplc($form_data['spil_user_id']);
				

				$sponsor_id =  $spil_id = ($spr_user_id='')? $model->getMemberId($spr_user_id):$model->getFirstId();
				#$AR_GET = $model->getSponsorSpill($sponsor_id,$left_right);
				#$spil_id = $AR_GET['spil_id'];				
				
				$user_id = $user_name = $model->generateUserId();
				if($sponsor_id>0){
					if($model->checkCount("tbl_members","user_id",$user_id)==0){
						$data = array("first_name"=>$first_name,
								"last_name"=>$last_name,
								"full_name"=>$full_name,
								"user_id"=>$user_id,
								"user_name"=>$user_name,
								"user_password"=>$user_password,
								"member_email"=>$member_email,
								
								"sponsor_id"=>$sponsor_id,
								"spil_id"=>$spil_id,
								"left_right"=>getTool($left_right,''),
								
								"date_join"=>$current_date,
								"date_of_birth"=>getTool($date_of_birth,"0000-00-00"),
								"gender"=>getTool($gender,'M'),
								"member_mobile"=>$member_mobile,
								"pan_no"=>getTool($pan_no,''),
								"aadhar_no"=>getTool($aadhar_no,''),
								
								"current_address"=>getTool($current_address,''),
								"land_mark"=>getTool($land_mark,''),
								"pin_code"=>getTool($pin_code,''),
								"city_name"=>getTool($city_name,''),
								"state_name"=>getTool($state_name,''),
								
								"bank_name"=>getTool($bank_name,''),
								"account_number"=>getTool($account_number,''),
								"branch"=>getTool($branch,''),
								"ifc_code"=>getTool($ifc_code,''),
								
								"nominal_name"=>getTool($nominal_name,''),
								"nominal_relation"=>getTool($nominal_relation,''),
								
								"pan_status"=>"N",
								"status"=>"Y",
								"last_login"=>$current_date,
								"login_ip"=>$login_ip,
								"block_sts"=>"N",
								"sms_sts"=>"N",
								"upgrade_date"=>$current_date
							);	
						if($model->checkCount("tbl_members","member_mobile",$member_mobile)==0){
							$this->session->unset_userdata("send_sms");
							$member_id = $this->SqlModel->insertRecord("tbl_members",$data);
							/********* Tree **************/
							$tree_data = array("member_id"=>$member_id,
								"sponsor_id"=>$sponsor_id,
								"spil_id"=>$spil_id,
								"nlevel"=>0,
								"left_right"=>getTool($left_right,''),
								"nleft"=>0,
								"nright"=>0,
								"date_join"=>$current_date
							);
							$this->SqlModel->insertRecord("tbl_mem_tree",$tree_data);
							$model->updateTree($spil_id,$member_id);
							/***************** End Tree ****************/
															
							$this->session->set_userdata('mem_id',$member_id);
							$this->session->set_userdata('user_id',$user_name);
								
							$model->welcomeMemberSms($member_mobile,$full_name,$user_id,$user_password);
							$AR_MAIL['member_id']=$member_id;
							Send_Mail($AR_MAIL,"EMAIL_VERIFY");
								
							if($cart_count>0){
								redirect_front("product","cart","");
							}else{
								$route_class =  $this->session->userdata('route_class');
								$route_method =  $this->session->userdata('route_method');
								
								if($route_class!='' && $route_method!=''){
									redirect_front($route_class,$route_method,"");
								}else{	
									redirect_front("account","welcome",array("member_id"=>_e($member_id)));
								}
							}	
						}else{
							set_message("warning","This mobile number is already register with us");
						}
					}else{
						set_message("warning","unable to process , please try again");
					}
				}else{
					set_message("warning","Invalid Referral Id");
				}
			}else{
				set_message("warning","Invalid security code, please try again");
			}
		}

		$AR_DT['page_title']="Register with us";
		$AR_DT['page_name']="Register with us";
		$data['ROW'] = $AR_DT;
		$this->load->view('register',$data);
	}
	
	
	public function sellerregister(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$login_ip = $_SERVER['REMOTE_ADDR'];
		
		$img = new Securimage();
		
		if($form_data['submit-seller-register']!='' && $this->input->post()!=''){
			$valid = $img->check($_POST["captcha_code"]);
  			if($valid == true) {
								
					$fran_setup_id = $model->getDefaultFranchiseSetupId();
					$referral_id = $model->getDefaultFranchisee();
					$name = FCrtRplc($form_data['name']);
					$email = FCrtRplc($form_data['email']);
					$mobile = FCrtRplc($form_data['mobile']);
							
					$gst_no = FCrtRplc($form_data['gst_no']);
					$tin_no = FCrtRplc($form_data['tin_no']);
					
					$store = FCrtRplc($form_data['store']);

					$address_1 = FCrtRplc($form_data['address_1']);
					$address_2 = FCrtRplc($form_data['address_2']);
					$address = $address_1." ".$address_2;
					
					$pincode = FCrtRplc($form_data['pincode']);
					$city = FCrtRplc($form_data['city']);
					$state = FCrtRplc($form_data['state']);
					$user_name = FCrtRplc($form_data['user_name']);
					$password = FCrtRplc($form_data['password']);
					
					if($model->checkCount("tbl_franchisee","email",$email)==0){
						if($model->checkCount("tbl_franchisee","user_name",$user_name)==0){
							$data = array("fran_setup_id"=>$fran_setup_id,
								"referral_id"=>$referral_id,
								"store"=>$store,
								"name"=>$name,
								"email"=>$email,
								"mobile"=>$mobile,
								"address"=>$address,
								"pincode"=>$pincode,
								"city"=>$city,
								"state"=>$state,
								"tin_no"=>getTool($tin_no,''),
								"gst_no"=>getTool($gst_no,''),
								"is_status"=>0,
								"user_name"=>$user_name,
								"password"=>$password
							);
							$franchisee_id = $this->SqlModel->insertRecord("tbl_franchisee",$data);
							$model->addUpdateFranchiseeBank($franchisee_id,$form_data);
							
							$AR_MAIL['franchisee_id']=$franchisee_id;
							Send_Mail($AR_MAIL,"EMAIL_FRAN_VERIFY");
							set_message("success","Welcome to family of ".WEBSITE.", after scrutinizing  data, we will send your login credentials");
							redirect_front("account","welcomefranchisee",array("franchisee_id"=>_e($franchisee_id)));
						}else{
							set_message("warning","This username already register with us , please recover your password");
						}
					}else{
						set_message("warning","This email id  already register with us , please recover your password");
					}
					
			}else{
				set_message("warning","Invalid security code, please try again");
			}
		}
		
		$QR_CMSN = "SELECT * FROM  tbl_cms WHERE active>0 AND id_cms='2'";
		$AR_CMSN = $this->SqlModel->runQuery($QR_CMSN,true);
		$data['ROW'] = $AR_CMSN;
		
		
		$AR_DT['page_title']="Seller Register";
		$AR_DT['page_name']="Seller Register";
		$AR_DT['page_controller']="account";
		$data['ROW'] = $AR_DT;
		$this->load->view('register-seller',$data);
	}
	
	function login(){
		$AR_DT['page_title']="Sign In";
		$AR_DT['page_name']="Login";
		$AR_DT['page_controller']="account";
		$data['ROW'] = $AR_DT;
		$this->load->view('login',$data);
	}
	
	public function paymentstatus(){
		$AR_META['page_title']="Payment Status";
		$AR_META['page_content']="";
		$AR_META['page_breadcrumb']="Paytm Payment Status";
		$data['META'] = $AR_META;
		$this->load->view('paymentstatus',$data);
	}
	
	
	
	public function loginhandler(){
		$model = new OperationModel();
		$img = new Securimage();
		$form_data = $this->input->post();
		$cart_count = $model->getCartCount();
		if($form_data['submit-login']!='' && $this->input->post()!=''){
			$model->checkLogin();
			
			$valid = true; #$img->check($form_data["captcha_code"]);
  			if($valid == true) {
				$user_name = $form_data['user_name_login'];
				$user_password = $form_data['user_password_login'];
				$page_name = "login";
				$log_sts = "F";
				$browser = getBrowser();
				$operate_system = $browser['name'];
				$web_browser = $browser['browser'];
				$browser_version = $browser['version'];
				$oprt_ip = FCrtRplc($_SERVER['REMOTE_ADDR']);
				$login_time = getLocalTime();
				$logout_time = getLocalTime();
				set_message("warning","Invalid username & password");
				
				if($user_name!='' && $user_password!=''){
					
					$sel_query = $this->db->query("SELECT * FROM tbl_members 
					WHERE ( user_name='".$user_name."' OR user_id = '".$user_name."' OR member_email ='".$user_name."' ) 
					AND user_password='".$user_password."'");
					$fetchRow = $sel_query->row_array();
					
					if($fetchRow['member_id']>0){
						set_message("success","Welcome to member pabel of ".WEBSITE."");
						$this->session->set_userdata('mem_id',$fetchRow['member_id']);
						$this->session->set_userdata('user_id',$fetchRow['user_name']);
						$this->session->set_userdata('last_log',$fetchRow['last_login']);
						
						$this->session->unset_userdata('fldcType');
						$this->session->unset_userdata('fldvMessage');
						
						$log_sts = "S";
						$page_name = "dashboard";
						
						$member_id = $fetchRow['member_id'];
						$post_data = array("member_id"=>($member_id>0)? $member_id:0,
							"user_name"=>$user_name,
							"user_password"=>$user_password,
							"member_ip"=>$_SERVER['REMOTE_ADDR'],
							"operate_system"=>$operate_system,
							"browser"=>$web_browser,
							"browser_version"=>$browser_version,
							"log_sts"=>$log_sts,
							"login_time"=>$login_time,
							"logout_time"=>$logout_time
						);
						$login_id = $this->SqlModel->insertRecord("tbl_mem_logs",$post_data);
						$this->session->set_userdata('login_id',$login_id);
						
						$route_class =  $this->session->userdata('route_class');
						$route_method =  $this->session->userdata('route_method');
						if($cart_count>0){
							redirect_front("product","cart","");
						}else{					
							#exit("You have successfully login into system, member dashboard page is under construction.");
							redirect(MEMBER_PATH);
						}
					}else{
						set_message("warning","Invalid username & password");
						redirect_front("account","login",array()); 
					}
				}else{
					set_message("warning","Invalid username & password");
					redirect_front("account","login",array()); 
				}
			}else{
				set_message("warning","Invalid security code, please try again");
				redirect_front("account","login",array()); 
			}
		}else{
			set_message("warning","Invalid username & password");
			redirect_front("account","login",array()); 
		}
	}
	
	function sellerlogin(){
		$AR_DT['page_title']="Seller Login";
		$AR_DT['page_name']="Seller Login";
		$AR_DT['page_controller']="account";
		$data['ROW'] = $AR_DT;
		$this->load->view('login-seller',$data);
	}
	
	public function sellerloginhandler(){
		$config = new SqlModel();
		$form_data = $this->input->post();
		if($form_data['submit-login-seller']==1 && $this->input->post()!=''){
			$user_name = $form_data['user_name'];
			$user_password = $form_data['user_password'];
			$page_name = "login";
			$log_sts = "F";
			$browser = getBrowser();
			$operate_system = $browser['name'];
			$web_browser = $browser['browser'];
			$browser_version = $browser['version'];
			$oprt_ip = FCrtRplc($_SERVER['REMOTE_ADDR']);
			$login_time = getLocalTime();
			$logout_time = getLocalTime();
			set_message("warning","Invalid username & password");
			$path_url = generateSeoUrl("account","sellerlogin","");
			if($user_name!='' && $user_password!=''){
				$sel_query = $this->db->query("SELECT * FROM  tbl_franchisee WHERE user_name='$user_name' AND password='$user_password'");
				$fetchRow = $sel_query->row_array();
				
				if($fetchRow['franchisee_id']>0){
					if($fetchRow['is_status']>0){
						$this->session->set_userdata('fran_id',$fetchRow['franchisee_id']);
						$this->session->set_userdata('fran_user',$fetchRow['user_name']);
						$this->session->set_userdata('fran_last_log',$fetchRow['last_login']);
						set_message("success","Welcome ".$fetchRow['name'].", to franchisee control panel of ".WEBSITE);
						$log_sts = "S";
						$path_url = FRANCHISE_PATH;
					}else{
						$fldiOprtrId = 0;
						set_message("warning","Unable to login , please contact our customer support team");
						$path_url = generateSeoUrl("account","sellerlogin","");
						$log_sts = "F";
					}
				}else{
					$fldiOprtrId = 0;
					set_message("warning","Invalid username & password");
					$path_url = generateSeoUrl("account","sellerlogin","");
					$log_sts = "F";
				}
			}
			
			$franchisee_id = $fetchRow['franchisee_id'];
				$post_data = array("franchisee_id"=>getTool($franchisee_id,0),
				"user_name"=>$user_name,
				"user_password"=>$user_password,
				"oprt_ip"=>$oprt_ip,
				"operate_system"=>$operate_system,
				"browser"=>$web_browser,
				"browser_version"=>$browser_version,
				"log_sts"=>$log_sts,
				"login_time"=>$login_time,
				"logout_time"=>$logout_time
			);
			$login_id = $config->insertRecord("tbl_franchise_logs",$post_data);
			$this->session->set_userdata('login_id',$login_id);
			
			redirect($path_url);
		}
	}
	
	public function loginajax(){
		$user_name_login = $this->input->get("user_name_login");
		$user_password_login = $this->input->get("user_password_login");
		if($user_name_login!='' && $user_password_login!=''){
			$user_name = $user_name_login;
			$user_password = $user_password_login;
			$page_name = "login";
			$log_sts = "F";
			$browser = getBrowser();
			$operate_system = $browser['name'];
			$web_browser = $browser['browser'];
			$browser_version = $browser['version'];
			$oprt_ip = FCrtRplc($_SERVER['REMOTE_ADDR']);
			$login_time = getLocalTime();
			$logout_time = getLocalTime();
			$AR_RT['ErrorDtl']="Invalid username & password";
			$AR_RT['ErrorMsg']="invalid";
			if($user_name!='' && $user_password!=''){
				$sel_query = $this->db->query("SELECT * FROM tbl_members 
				WHERE ( user_name='".$user_name."' OR user_id = '".$user_name."' OR member_email ='".$user_name."' ) 
				AND user_password='".$user_password."' $StrWhr ");
				$fetchRow = $sel_query->row_array();
				if($fetchRow['member_id']>0){
					set_message("success","Welcome to member pabel of ".WEBSITE."");
					$this->session->set_userdata('mem_id',$fetchRow['member_id']);
					$this->session->set_userdata('user_id',$fetchRow['user_name']);
					$this->session->set_userdata('last_log',$fetchRow['last_login']);
					
					$this->session->unset_userdata('fldcType');
	    			$this->session->unset_userdata('fldvMessage');
					
					$log_sts = "S";
					$page_name = "dashboard";
					
					$member_id = $fetchRow['member_id'];
					$post_data = array("member_id"=>($member_id>0)? $member_id:0,
						"user_name"=>$user_name,
						"user_password"=>$user_password,
						"member_ip"=>$_SERVER['REMOTE_ADDR'],
						"operate_system"=>$operate_system,
						"browser"=>$web_browser,
						"browser_version"=>$browser_version,
						"log_sts"=>$log_sts,
						"login_time"=>$login_time,
						"logout_time"=>$logout_time
					);
					$login_id = $this->SqlModel->insertRecord("tbl_mem_logs",$post_data);
					$this->session->set_userdata('login_id',$login_id);
					$AR_RT['error_msg']="";
					$AR_RT['status']="1";
				}else{
					$AR_RT['error_msg']="Invalid username & password";
					$AR_RT['status']="0";
				}
			}else{
				$AR_RT['error_msg']="Invalid username & password";
				$AR_RT['status']="0";
			}
		}else{
			$AR_RT['error_msg']="Invalid username & password";
			$AR_RT['status']="0";
		}
		echo json_encode($AR_RT);
	}

	public function forgotpassword(){
		$model = new OperationModel();
		$img = new Securimage();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		if($form_data['submit-forgot']!='' && $this->input->post()!=''){
			$user_name = FCrtRplc($form_data['user_name']);
			$valid = $img->check($form_data["captcha_code"]);
  			if($valid == true) {
				$Q_MEM = "SELECT * FROM tbl_members WHERE 
				( member_email = '".$user_name."' OR user_id = '".$user_name."' OR user_name='".$user_name."' )   
				AND delete_sts>0 AND member_email!=''";
				$fetchRow = $this->SqlModel->runQuery($Q_MEM,true);
				$member_id = $fetchRow['member_id'];
				if($member_id>0){
					$message ="Hi ".$fetchRow['user_id'].", Your username : ".$user_name." and password: ".$fetchRow['user_password'].", info : ".WEBSITE."";
					Send_Single_SMS($fetchRow['member_mobile'],$message);
					Send_Mail(array("member_id"=>$member_id),"FORGOT_PASSWORD");
					set_message("success","Please check your mobile inbox , we have forward  your  password");
					redirect_front("account","forgotpassword",array());
					
				}else{
					set_message("warning","Email not found, please enter valid email address");
					redirect_front("account","forgotpassword",array()); 
				}
			}else{
				set_message("warning","Invalid security code, please try again");
				redirect_front("account","forgotpassword",array()); 
			}
		}
		
		$AR_DT['page_title']="Recover Password";
		$AR_DT['page_name']="Recover Password";
		$AR_DT['page_controller']="account";
		$data['ROW'] = $AR_DT;
		
		$this->load->view('forgotpassword',$data);
	}
	

	
	public function getpassword(){
		$model = new OperationModel();
		$today_date = getLocalTime();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = (_d($form_data['member_id'])>0)? _d($form_data['member_id']):_d($segment['member_id']);
		$verify_code = FCrtRplc($form_data['verify_code']);
		$user_password = FCrtRplc($form_data['user_password']);
		$QR_MEM = "SELECT * FROM tbl_members WHERE member_id='$member_id' AND otp_no='$verify_code'";
		$AR_MEM = $this->SqlModel->runQuery($QR_MEM,true);
		
		if($form_data['resetPassword']==1 && $this->input->post()!=''){
			if($AR_MEM['member_id']>0){
				$sms_otp = $model->sendNewPass($AR_MEM['member_mobile'],$AR_MEM['first_name'],$AR_MEM['user_id'],$user_password);
				$this->SqlModel->updateRecord("tbl_members",array("user_password"=>$user_password),array("member_id"=>$member_id));
				set_message("success","Password updated successfully!!!");
				redirect_front("account","forgotpassword","");
			}else{
				set_message("warning","Invalid OTP. Please try again!!!");
				redirect_front("account","getpassword",array("member_id"=>_e($member_id)));
			}
		}
		$this->load->view('getpassword',$data);
	}
	
	public function resetpassword(){
		$this->load->view('reset-password',$data);
	}
	
	public function emailverify(){
		$this->load->view('emailverify',$data);
	}
	
	public function emailverifyfranchisee(){
		$this->load->view('emailverifyfranchisee',$data);
	}
	
	public function email(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(1);
		$member_id  = $segment['member_id'];
		$franchisee_id  = $segment['franchisee_id'];
		$order_id = $segment['order_id'];
		
		$option_name = $segment['option_name'];
		$rowSingle = $model->getMember($member_id);
		if($member_id>0){
			$full_name = $rowSingle['first_name']." ".$rowSingle['last_name'];
			$email = $rowSingle['member_email'];
			$phone = $rowSingle['member_mobile'];
			$email_verify = generateSeoUrl("account","emailverify",array("email"=>_e($rowSingle['member_id'])));
		}
		
		if($order_id>0){
			$AR_ORDR = $model->getOrderMaster($order_id);
			$franchisee_id = $AR_ORDR['franchisee_id'];
			$rowSingle = $model->getMember($AR_ORDR['member_id']);
			$full_name = $rowSingle['first_name']." ".$rowSingle['last_name'];
			$email = $rowSingle['member_email'];
			$phone = $rowSingle['member_mobile'];
			
			$order_no = $AR_ORDR['order_no'];
			$invoice_number = $AR_ORDR['invoice_number'];
			if($invoice_number!=''){
				#getHttpContent(generateSeoUrlAdmin("pdf","invoice",array("order_id"=>$order_id)));
			}
		}
		
		if($franchisee_id>0){
			$AR_FRAN = $model->getFranchiseeDetail($franchisee_id);
			$full_name = $AR_FRAN['name'];
			$email = $AR_FRAN['email'];
			$phone = $AR_FRAN['mobile'];
			$email_verify = generateSeoUrl("account","emailverifyfranchisee",array("email"=>_e($AR_FRAN['franchisee_id'])));
		}
		
		
		$data['order_no'] = $order_no;
		$data['invoice_number'] = $invoice_number;
		
		$data['option_name'] = $option_name;
        $data['company_name'] = WEBSITE;
		$data['name'] = $full_name;
		$data['phone'] = $phone;
		$data['email'] = getTool($email,'N/A');
		$data['website_url'] = BASE_PATH;
		$data['login_url'] = generateSeoUrl("user","login","");
		$data['password'] = $rowSingle['user_password'];
		$data['username'] = getTool($rowSingle['user_id'],$rowSingle['user_name']);
		$data['email_verify'] = $email_verify;
		$this->parser->parse('template/email_template', $data);
	}

}