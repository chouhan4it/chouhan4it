<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isMemberLoggedIn()){
			 redirect(BASE_PATH);		
		}
	}
	
	public function jsonhandler(){	
		$model = new OperationModel();
		$switch_type  = $this->input->get('switch_type');
		switch($switch_type){
			case "UPDATE_CART":
				$cart_id = $this->input->get('cart_id');
				$cart_qty = ceil($this->input->get('cart_qty'));
				
				$QR_CART = "SELECT tc.* FROM  tbl_cart AS tc WHERE tc.cart_id='".$cart_id."'";
				$AR_CART = $this->SqlModel->runQuery($QR_CART,true);
				
				$cart_price = $AR_CART['cart_price'];
				$cart_total = $cart_price*$cart_qty;
				$data = array("cart_qty"=>$cart_qty,"cart_total"=>$cart_total);
				
				$this->SqlModel->updateRecord("tbl_cart",$data,array("cart_id"=>$cart_id));
				
			
				$AR_RT['ErrorMsg']="success";
				$AR_RT['cart_qty']=$cart_qty;
				echo json_encode($AR_RT);
			break;
		}
		
		
	}
	
	

	
	
}
