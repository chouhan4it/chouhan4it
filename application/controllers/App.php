<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends MY_Controller {
	
	public function __construct(){
	   parent::__construct();
	   $this->load->view('captcha/securimage');
	}
	
	
	function register(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$current_date = getLocalTime();
		$today_date = InsertDate($current_date);
		$spr_user_id  = FCrtRplc($segment['register']);

		$img = new Securimage();
		
		if($form_data['submit-app-register']!='' && $this->input->post()!=''){
			$valid = $img->check($_POST["captcha_code"]);
  			if($valid == true) {
				$first_name = FCrtRplc($form_data['first_name']);
				$last_name = FCrtRplc($form_data['last_name']);
				$full_name = $first_name." ".$last_name;
				$member_email = FCrtRplc($form_data['member_email']);
				$user_password = FCrtRplc($form_data['user_password']);
				
				$gender = FCrtRplc($form_data['gender']);
				
				$country_code = "IND";
				$mobile_code =  $model->getMobileCode($country_code);
				$member_mobile = FCrtRplc($form_data['member_mobile']);
				
				$current_address = FCrtRplc($form_data['current_address']);
				$pin_code = FCrtRplc($form_data['pin_code']);
				$city_name = FCrtRplc($form_data['city_name']);
				$state_name = FCrtRplc($form_data['state_name']);
				
				$user_name = $user_id  = $model->generateUserId();
				

				if($first_name!='' && $member_email!=''){
					$sponsor_id = $spil_id = $model->getMemberId($form_data['spr_user_id']);
					
					if($sponsor_id>0 && $spil_id>0){
						if(strlen($first_name)>=3){
							if($model->checkCount("tbl_members","member_mobile",$member_mobile)==0){
								if($model->checkCount("tbl_members","member_email",$member_email)==0){
										$Ctrl += ($spil_id>0)? 0:1;
										$data = array("first_name"=>$first_name,
											"last_name"=>$last_name,
											"user_id"=>$user_id,
											"user_name"=>$user_name,
											"user_password"=>$user_password,
											"mobile_code"=>$mobile_code,
											"member_mobile"=>$member_mobile,
											"member_email"=>$member_email,
											
											"sponsor_id"=>$sponsor_id,
											"spil_id"=>$spil_id,
											"left_right"=>getTool($left_right,''),
											
											"gender"=>getTool($gender,"M"),
											"country_code"=>$country_code,
											"city_name"=>$city_name,
											"state_name"=>$state_name,
											"date_join"=>$current_date,
											"pan_status"=>"N",
											"status"=>"Y",
											"last_login"=>$current_date,
											"login_ip"=>$_SERVER['REMOTE_ADDR'],
											"block_sts"=>"N",
											"sms_sts"=>"N",
											"upgrade_date"=>$current_date
										);		
										if($Ctrl==0){
											if($model->checkSetupJoin($sponsor_id)==0){
												$member_id = $this->SqlModel->insertRecord("tbl_members",$data);
													$tree_data = array("member_id"=>$member_id,
														"sponsor_id"=>$sponsor_id,
														"spil_id"=>$spil_id,
														"nlevel"=>0,
														"left_right"=>getTool($left_right,''),
														"nleft"=>0,
														"nright"=>0,
														"date_join"=>$today_date
													);
													$this->SqlModel->insertRecord("tbl_mem_tree",$tree_data);
													$model->updateTree($spil_id,$member_id);
													
													$this->session->set_userdata('mem_id',$member_id);
													$this->session->set_userdata('user_id',$user_name);
													$AR_MAIL['member_id']=$member_id;
													Send_Mail($AR_MAIL,"EMAIL_VERIFY");
													set_message("success","Congratulation!, you have successfully register with us, Please Login");
													redirect_front("app","login","");
											}else{
												set_message("warning","Hi sponsor, please topup your account to join new user");
											}
										}else{
											set_message("warning","Failed , unable to process your request , please try again");
										}
								}else{
									set_message("warning","This email address is already used, please recover your password");
								}
							}else{
								set_message("warning","This mobile number is already used, please recover your password");
							}
						}else{
							set_message("warning","Invalid full name, Minimum 3 character is required");
						}
					}else{ set_message("warning","Invalid sponsor id, please eneter valid id"); }
				}else{
					set_message("warning","Failed , unable to process your request , please try again");
				}
			}else{
				set_message("warning","Invalid security code, please try again");
			}
		}
		
		$AR_META['page_title']= "Regitsration";
		$data['META'] = $AR_META;
		$this->load->view('app-register',$data);
    }	
	
	function login(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		
		$browser = getBrowser();
		$operate_system = $browser['name'];
		$web_browser = $browser['browser'];
		$browser_version = $browser['version'];
		$member_ip = FCrtRplc($_SERVER['REMOTE_ADDR']);
		$login_time = $logout_time = getLocalTime();
		
		if($form_data['submit-app-login']!='' && $this->input->post()!=''){
			$username_login = FCrtRplc($form_data['username_login']);
			$password_login  = FCrtRplc($form_data['password_login']);
			
			$Q_MEM = "SELECT * FROM tbl_members WHERE (user_id='".$username_login."' OR user_name='".$username_login."'
			OR member_email='".$username_login."') AND 	user_password='".$password_login."'	AND delete_sts>0";
			$AR_MEM = $this->SqlModel->runQuery($Q_MEM,true);
			if($AR_MEM['member_id']>0){				
				$login_from = "app";
				$log_sts = "S";
				$member_id = $AR_MEM['member_id'];
				$post_data = array("member_id"=>getTool($member_id,0),
					"user_name"=>$username_login,
					"user_password"=>$password_login,
					"member_ip"=>$member_ip,
					"operate_system"=>$operate_system,
					"browser"=>$web_browser,
					"browser_version"=>$browser_version,
					"log_sts"=>$log_sts,
					"login_time"=>$login_time,
					"logout_time"=>$logout_time
				);
				$login_id = $this->SqlModel->insertRecord("tbl_mem_logs",$post_data);
				
				$this->session->set_userdata('login_id',$login_id);
				$this->session->set_userdata('mem_id',$AR_MEM['member_id']);
				$this->session->set_userdata('user_id',$AR_MEM['user_id']);
				$this->session->set_userdata('login_from',$login_from);				
				
				redirect(MEMBER_PATH);
			}else{
				set_message("warning","Invalid username and password");
				redirect_front("app","login",array()); 
			}
		}
		
		$page_title = WEBSITE." App Login";
		$AR_META['page_title']= $page_title;
		$data['META'] = $AR_META;
		$this->load->view('app-login',$data);

    }	
		
}
?>