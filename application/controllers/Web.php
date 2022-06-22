<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends MY_Controller {
	
	public function __construct(){
	   parent::__construct();
	   $this->load->library('parser');
	   $this->load->view('captcha/securimage');
	   $this->load->view('mailer/phpmailer');
	   $this->OperationModel->addVisitor();
	}
	
	
	public function index(){
		$model = new OperationModel();
		
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		
		$data['ROW'] = $AR_DT;	
		$this->load->view('home',$data);
	}
	
	
	
	public function comingsoon(){
		$this->load->view('coming_soon');
	}
	
	public function pagenotfound(){
		$this->load->view('pagenotfound');
	}
	
	public function legal(){
		$AR_DT['page_title']="Legal";
		$data['ROW'] = $AR_DT;		
		$this->load->view('legal',$data);
	}
	
	public function refundcancellation(){
		$AR_DT['page_title']="Refund & Cancellation";
		$data['ROW'] = $AR_DT;		
		$this->load->view('refundcancellation',$data);
	}
	
	public function testimonial(){
		$this->load->view('testimonial');
	}
	
	public function privacypolicy(){
		$AR_DT['page_title']="Privacy Policy";
		$data['ROW'] = $AR_DT;		
		$this->load->view('privacypolicy',$data);
	}
	
	

	public function ourshoppe(){
		$this->load->view('ourshoppe');
	}
	
	public function aboutus(){
		$AR_DT['page_title']="About Us";
		$data['ROW'] = $AR_DT;		
		$this->load->view('aboutus',$data);
	}
	
	
	
	public function cms(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		
		$id_cms = _d($segment['id_cms']);
		
		$AR_DT = $model->getCms($id_cms);
		$AR_DT['page_title']="Term & Condition";
		$data['ROW'] = $AR_DT;		
		$this->load->view('cms',$data);
	}
	
	public function blog(){
		$this->load->view('blog');
	}
	
	public function contactus(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(1);
		$img = new Securimage();
		$visitor_ip = $_SERVER['REMOTE_ADDR'];
		
		if($form_data['submit-contact']!="" && $this->input->post()!=''){
			$valid = $img->check($_POST["captcha_code"]);
			$valid = true;
  			if($valid == true) {
				
				$member_id = FCrtRplc($form_data['member_id']);
				$first_name = FCrtRplc($form_data['first_name']);
				$last_name = FCrtRplc($form_data['last_name']);
				
				$name = $first_name."-".$last_name;
				$email = FCrtRplc($form_data['email']);
				$phone = FCrtRplc($form_data['phone']);
				$subject = FCrtRplc($form_data['subject']);
				$message = FCrtRplc($form_data['message']);
				if($name!='' && $email!=''){
					$data = array("member_id"=>getTool($member_id,0),
						"name"=>$name,
						"email"=>$email,
						"mobile"=>getTool($phone,''),
						"subject"=>$subject,
						"message"=>$message,
						"visitor_ip"=>$visitor_ip
					);
					$contact_id = $this->SqlModel->insertRecord("tbl_contacts",$data);
					if($contact_id>0){
						set_message("success","Thank you for contacting us, our customer support team contact you shortly");
						redirect_front("web","contactus",array());
					}else{
						set_message("warning","Unable to send your request , please try again");
						redirect_front("web","contactus",array());
					}
				}else{
					set_message("warning","Invalid details , Please enter valid details");
					redirect_front("web","contactus",array());
				}
			}else{
				set_message("warning","Invalid security code, please try again");
				redirect_front("web","contactus",array());
			}
		}
		$AR_DT['page_title']="Contact Us";
		$data['ROW'] = $AR_DT;
		$this->load->view('contactus',$data);
	}
	
	public function news(){
		$AR_DT['page_title']="News";
		$data['ROW'] = $AR_DT;
		$this->load->view('news',$data);
	}
	
	public function newsdetail(){
		$AR_DT['page_title']="News Detail";
		$data['ROW'] = $AR_DT;
		$this->load->view('newsdetail',$data);
	}
	
	public function businessplan(){
		$AR_DT['page_title']="Business Plan";
		$data['ROW'] = $AR_DT;
		$this->load->view('businessplan',$data);
	}
	
	public function termscondition(){
		$AR_DT['page_title']="Terms & Condition";
		$data['ROW'] = $AR_DT;
		$this->load->view('termscondition',$data);
	}
	
	public function gallery(){
		$AR_DT['page_title']="Gallery";
		$data['ROW'] = $AR_DT;
		$this->load->view('gallery',$data);
	}
	
	public function faq(){
		$AR_DT['page_title']="Frequently Ask Question";
		$data['ROW'] = $AR_DT;
		$this->load->view('faq',$data);
	}


	public function disclaimer(){
		$AR_DT['page_title']="Disclaimer";
		$data['ROW'] = $AR_DT;
		$this->load->view('disclaimer',$data);
	}

	public function socialmediapolicy(){
		$AR_DT['page_title']="Social Media Policy";
		$data['ROW'] = $AR_DT;
		$this->load->view('socialmediapolicy',$data);
	}

	public function grievanceredressal(){
		$AR_DT['page_title']="Grievance Redressal";
		$data['ROW'] = $AR_DT;
		$this->load->view('grievanceredressal',$data);
	}

	public function incomedisclaimer(){
		$AR_DT['page_title']="Income Disclaimer";
		$data['ROW'] = $AR_DT;
		$this->load->view('incomedisclaimer',$data);
	}

	public function banking(){
		$AR_DT['page_title']="Bank Details";
		$data['ROW'] = $AR_DT;
		$this->load->view('banking',$data);
	}
	
	
	function testmodule(){
		#$this->OperationModel->sendTransactionSMS("8655336008");
	}
	
	
	

}
?>