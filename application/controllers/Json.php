<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   #$this->load->view('mailer/phpmailer');
	   
	}
	
	public function jsonhandler(){	
		$model = new OperationModel();
		$method_type  = $this->input->get('switch_type');
		$switch_type = ($method_type)? $method_type:$this->input->post('switch_type');
		
		switch($switch_type){
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
				$Q_SPR = "SELECT DISTINCT  tc.city_name 
				FROM tbl_city AS tc WHERE tc.state_name LIKE '%".$state_name."%' AND tc.city_name!=''
				GROUP BY city_name  
				ORDER BY city_name ASC";
				$A_SPRS = $this->SqlModel->runQuery($Q_SPR);
				echo "<option value=''>----select city----</option>";
				foreach($A_SPRS as $A_SPR):
					echo "<option value='".getTool($A_SPR['city_name'],'')."'>".$A_SPR['city_name']."</option>";
				endforeach;
				/*
				echo "<option value='Other'>Other</option>";
				option removed on client request dated 6 dec 2017 (abhay)
				*/
			break;
			case "PIN_TYPE":
				$type_id = $this->input->get('type_id');
				$QR_GET = "SELECT * FROM tbl_pintype WHERE type_id='".$type_id."'";
				$AR_SET = $this->SqlModel->runQuery($QR_GET,true);
				echo json_encode($AR_SET);
			break;
			case "MEM_FIELD_EXIST":
				$data_name = $this->input->get('data_name');
				$data_id = $this->input->get('data_id');
				$data_val = $this->input->get('data_val');
				
				$row_ctrl = 0;
				if($model->checkCount("tbl_members",$data_name,$data_val)>0){
					$row_ctrl = 1;
				}
				$AR_RT['row_ctrl'] = $row_ctrl;
				echo json_encode($AR_RT);
			break;
			case "PIN_VALUE":
				$pin_id = $this->input->get('pin_id');
				$QR_GET = "SELECT * FROM tbl_pinsdetails WHERE pin_id='".$pin_id."'";
				$AR_SET = $this->SqlModel->runQuery($QR_GET,true);
				echo json_encode($AR_SET);
			break;
			case "ATTR_DEFAULT":
				$AR_RT['error_sts'] = 0;
				$post_attribute_id = $this->input->get('post_attribute_id');
				$AR_ATTR = $model->getPostAttributeDetail($post_attribute_id);
				$post_id = $AR_ATTR['post_id'];
				if($post_attribute_id>0 && $post_id>0){
					$data_set = array("default_sts"=>1);
					$this->SqlModel->updateRecord("tbl_post_attribute",array("default_sts"=>0),array("post_id"=>$post_id));
					$this->SqlModel->updateRecord("tbl_post_attribute",$data_set,array("post_attribute_id"=>$post_attribute_id));
					$AR_RT['error_sts'] = 1;
				}
				echo json_encode($AR_RT);
			break;
			case "SEND_SMS_CODE":
				$flddDate = getLocalTime();
				$member_mobile  = $this->input->get('member_mobile');
				$member_name  = $this->input->get('member_name');
				$fldiRegId = $this->input->get('fldiRegId');
				$AR_RT['ErrorMsg']="FAILED";
				if($fldiRegId>0){
					$sms_number = $member_mobile;
					$sms_otp = $model->sendMobileVerifySMS($sms_number,$member_name);
					$this->SqlModel->updateRecord("tbl_tmp_register",
						array("fldvMobile"=>$member_mobile,"fldvMCode"=>$sms_otp,"flddDate"=>$flddDate),
						array("fldiRegId"=>$fldiRegId)
					);
					$AR_RT['ErrorMsg']="SUCCESS";
				}
				echo json_encode($AR_RT);
			break;
			case "POST_ATTR":
				$post_id = FCrtRplc($_REQUEST['post_id']);
				$select_val = '';
				$QR_SEL = "SELECT GROUP_CONCAT(CONCAT_WS('-',tag.attribute_group_name,ta.attribute_name))  AS post_attribute, tpac.post_attribute_id
				   FROM tbl_post_attribute_combination AS tpac 
				   LEFT JOIN tbl_attribute AS ta ON ta.attribute_id=tpac.attribute_id
				   LEFT JOIN tbl_attribute_group AS tag ON tag.attribute_group_id=ta.attribute_group_id
				   LEFT JOIN tbl_post_attribute AS tpa ON tpa.post_attribute_id=tpac.post_attribute_id
				   WHERE tpa.post_id='$post_id'
				   GROUP BY tpac.post_attribute_id
				   ORDER BY tpac.post_attribute_id ASC";
				$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
				if(count($RS_SEL)>0){
					echo '<option value="">select attribute</option>';
					foreach($RS_SEL as $AR_SEL):
						echo '<option value="'.$AR_SEL['post_attribute_id'].'"';if($select_val == $AR_SEL['post_attribute_id']){echo "selected";}
						echo '>'.$AR_SEL['post_attribute'].'</option>';
					endforeach;
				}else{
					echo '<option value="0"';
					echo '>None</option>';
				}
			break;
			
			case "ATTRIBUTE":
				$attribute_id_val = $_REQUEST['name'];
				$post_id = FCrtRplc($_REQUEST['post_id']);
				
				
				$attribute_id_implode = ($attribute_id_val!='')? implode_val($attribute_id_val,","):0;

				$array_ctrl = count($attribute_id_val);
				$QR_ATTR = "SELECT tpa.post_attribute_id FROM tbl_post_attribute AS tpa WHERE tpa.post_id='$post_id'";
				$RS_ATTR = $this->SqlModel->runQuery($QR_ATTR);
				
				$Ctrl=0;
				$AR_COMB['post_attribute_id'] = 0;
				foreach($RS_ATTR as $AR_ATTR):
					$QR_COMB = "SELECT tpac.attribute_id, tpac.post_attribute_id  
								FROM tbl_post_attribute_combination AS tpac 
								WHERE 
								tpac.post_attribute_id='".$AR_ATTR['post_attribute_id']."' AND tpac.attribute_id IN($attribute_id_implode)";
					$RS_COMB = $this->SqlModel->runQuery($QR_COMB);
					
					$row_ctrl = count($RS_COMB);
					if($array_ctrl==$row_ctrl){
						$AR_COMB['post_attribute_id'] = getTool($AR_ATTR['post_attribute_id'],0);
					}
				endforeach;
				echo json_encode($AR_COMB);
			break;
			case "ATTR_PRICE":
				$post_id = FCrtRplc($_REQUEST['post_id']);
				$post_attribute_id = FCrtRplc($_REQUEST['post_attribute_id']);
				$AT_RT = $model->getPostAttrPrice($post_attribute_id,$post_id);	
				echo json_encode($AT_RT);
			break;
			case "ATTR_VALUE":
				$post_id = FCrtRplc($_REQUEST['post_id']);
				$attribute_group_id = FCrtRplc($_REQUEST['attribute_group_id']);
				$attribute_id = FCrtRplc($_REQUEST['attribute_id']);
				
				$AR_GRP = $model->getAttrGroupDetail($attribute_group_id);
				$attribute_group_name = $AR_GRP['attribute_group_name'];
				
				$QR_SEL  = "SELECT ta.* FROM tbl_attribute AS ta 
						WHERE ta.attribute_id IN(SELECT attribute_id FROM tbl_post_attribute_combination 
							WHERE post_attribute_id IN(SELECT post_attribute_id FROM tbl_post_attribute WHERE post_id='".$post_id."')
							AND post_attribute_id IN(SELECT post_attribute_id FROM tbl_post_attribute_combination WHERE attribute_id='".$attribute_id."'))
						AND ta.attribute_group_id='".$attribute_group_id."'
						GROUP BY ta.attribute_id";
				$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
				echo "<option value=''>----Select ".$attribute_group_name."----</option>";
				foreach($RS_SEL as $AR_SEL):
					echo "<option value='".$AR_SEL['attribute_id']."'>".$AR_SEL['attribute_name']."</option>";
				endforeach;
				
			break;
			case "CHECK_PINCODE":
				$pin_code = FCrtRplc($_REQUEST['pin_code']);
				$QR_SEL = "SELECT tpc.* FROM tbl_pincode AS tpc WHERE tpc.pin_code='".$pin_code."'";
				$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
				if($AR_SEL['pincode_id']>0){
					$AR_RT = $AR_SEL;
				}else{
					$AR_RT['pincode_id'] = 0;
				}
				echo json_encode($AR_RT);
			break;
			case "CART":
				$today_date = getLocalTime();
				$cart_session = $this->session->userdata('session_id');
				$post_id  = $this->input->get('post_id');
				$post_attribute_id  = getTool($this->input->get('post_attribute_id'),0);
				$cart_qty  = getTool($this->input->get('total_qty'),0);
				$franchisee_id = $model->getDefaultFranchisee();
				$member_id = $this->session->userdata("mem_id");
				$first_pv = $model->getValue("CONFIG_FIRST_PV");
				
				$AR_SELF = $model->getSumSelfCollection($member_id,"","");
				$total_pv  = $AR_SELF['total_bal_pv'];
				
				$AR_RT['status'] = "0";
				$AR_RT['error_msg'] = "Unable to process product not found";
				
				if($post_id>0 && $cart_qty>0){
					$AR_DT = $model->getPostDetail($post_id);
					
					$AR_DT_FILE = $model->getPostFile($post_id);
					$cart_image_id = $AR_DT_FILE['field_id'];
					
					if($post_attribute_id>0){
						$AR_ATTR = $model->getPostAttributeDetail($post_attribute_id);
						$cart_image_id = $AR_ATTR['field_id'];
						$cart_attribute = $model->getAttributeOfCombination($post_attribute_id);
					}
										
					#$stock_ctrl = $model->checkAvailableQty($AR_DT['post_id'],$franchisee_id,$cart_qty);
					#$AR_STOCK = $model->getStockOpening($AR_DT['post_id'],$franchisee_id);
					
					$ctrl_post = 0;
					#$ctrl_post += (int) ($AR_DT['post_sts']>0)? 0:1;
					#$ctrl_post += (int) ($AR_DT['post_pv']>0)? 0:1;
					#$ctrl_post += (int) ($stock_ctrl>0)? 1:0;
					
					$cart_ctrl = $model->checkCountPro("tbl_cart","post_id='".$post_id."' AND cart_attribute_id='".$post_attribute_id."' AND cart_session='".$cart_session."'");
					if($ctrl_post==0){
						if($cart_ctrl==0){
							$category_id = $AR_DT['category_id'];
							$cart_title = $AR_DT['post_title'];
							$cart_code = $AR_DT['post_code'];
							$cart_desc = $AR_DT['post_desc'];
							$tax_age = $AR_DT['tax_age'];
							
							$cart_width = $AR_DT['post_width'];
							$cart_height = $AR_DT['post_height'];
							$cart_depth = $AR_DT['post_depth'];
							$cart_weight = $AR_DT['post_weight'];
							
							$cart_selling = getTool($AR_ATTR['post_selling'],$AR_DT['post_selling']);
							$cart_cmsn = getTool($AR_ATTR['post_cmsn'],$AR_DT['post_cmsn']);
							$cart_price = getTool($AR_ATTR['post_attribute_price'],$AR_DT['post_price']);
							$cart_mrp =  getTool($AR_ATTR['post_attribute_mrp'],$AR_DT['post_mrp']);
							
							$cart_value = ($total_pv>=$first_pv)? $cart_price:$cart_mrp;
							
							$cart_shipping = $AR_DT['post_shipping'];
							$cart_pv = $AR_DT['post_pv'];
							$cart_bv = $AR_DT['post_bv'];
							$cart_total = ($cart_value*$cart_qty);
							
							$cart_tax = getPostGst($cart_price,$tax_age);
							
							$data = array("post_id"=>$post_id,
								"cart_attribute_id"=>getTool($post_attribute_id,0),
								"cart_attribute"=>getTool($cart_attribute,''),
								"category_id"=>getTool($category_id,0),
								"cart_title"=>$cart_title,
								"cart_code"=>getTool($cart_code,''),
								"cart_desc"=>getTool($cart_desc,''),
								"cart_image_id"=>getTool($cart_image_id,''),
								
								"tax_age"=>getTool($tax_age,0),
								"cart_tax"=>getTool($cart_tax,0),
								
								"cart_mrp"=>getTool($cart_mrp,0),
								"cart_cmsn"=>getTool($cart_cmsn,0),
								"cart_selling"=>getTool($cart_selling,0),
								"cart_price"=>getTool($cart_value,0),
								
								"cart_qty"=>getTool($cart_qty,1),
								"cart_total"=>$cart_total,
								
								"cart_width"=>getTool($cart_width,0),
								"cart_height"=>getTool($cart_height,0),
								"cart_depth"=>getTool($cart_depth,0),
								"cart_weight"=>getTool($cart_weight,0),
								
								"cart_shipping"=>getTool($cart_shipping,0),
								"cart_pv"=>$cart_pv,
								"cart_bv"=>$cart_bv,
								
								"cart_session"=>$cart_session,
								"date_up"=>$today_date
							);
							$cart_id = $this->SqlModel->insertRecord("tbl_cart",$data);
							$AR_RT['status'] = "1";
							$AR_RT['error_msg'] = "Product successfully added to cart";
						}elseif($cart_ctrl>0){
						
							$QR_CART = "SELECT tc.* FROM  tbl_cart AS tc WHERE tc.post_id='".$post_id."' 
										AND tc.cart_session='".$cart_session."'";
							$AR_CART = $this->SqlModel->runQuery($QR_CART,true);
							
							$cart_price = $AR_CART['cart_price'];
							$cart_total = ( $cart_price * $cart_qty );
							$data = array("cart_qty"=>$cart_qty,"cart_total"=>$cart_total);
							
							$this->SqlModel->updateRecord("tbl_cart",$data,array("post_id"=>$post_id,"cart_attribute_id"=>$post_attribute_id,"cart_session"=>$cart_session));
							$AR_RT['status'] = "1";
							$AR_RT['error_msg'] = "Your cart successfully updated";
						}
					}else{
						$AR_RT['status'] = "0";
						$AR_RT['error_msg'] = "Unable to add product into cart, product is currenctly out of stock";
					}
				}else{
					$data_delete = array("post_id"=>$post_id,"cart_session"=>$cart_session);
					$this->SqlModel->deleteRecord("tbl_cart",$data_delete);
					$AR_RT['status'] = "1";
					$AR_RT['error_msg'] = "Your product successfully deleted";
				}
				
				
				$post_price = $model->getCartPrice($post_id);
				$AR_RT['post_price'] = ($post_price>0)? number_format($post_price,2):0;
				
				$cart_total_mrp = 	$model->getCartTotalMrp();		
				$cart_total = 	$model->getCartTotal();
				$cart_pv = $model->getCartTotalPv();
				$cart_bv = $model->getCartTotalBv();
				$cart_count = $model->getCartCount();
				
				$AR_RT['cart_total_mrp'] = ($cart_total_mrp>0)? number_format($cart_total_mrp,2):0;
				$AR_RT['cart_total'] = ($cart_total>0)? number_format($cart_total,2):0;
				$AR_RT['cart_pv'] = ($cart_pv>0)? number_format($cart_pv,2):0;
				$AR_RT['cart_bv'] = ($cart_bv>0)? number_format($cart_bv,2):0;
				$AR_RT['cart_count'] = ($cart_count>0)? $cart_count:0;
				
				echo json_encode($AR_RT);
			break;
			case "GET_BALANCE":
				$wallet_id = $model->getWallet(WALLET1);
				$member_id = _d($this->input->get('member_id'));
				$AR_LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
				echo json_encode($AR_LDGR);
			break;
			case "CHECKUSR":
				$user_name = $this->input->get('user_name');
				$QR_GET = "SELECT tm.member_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
				FROM tbl_members AS tm	
				WHERE tm.user_id='".$user_name."'";
				$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
				echo json_encode($AR_GET);
			break;
			case "CHECK_SPIL":
				$spr_user_name = $this->input->get('spr_user_name');
				$spil_user_name = $this->input->get('spil_user_name');
				
				$sponsor_id = $model->getMemberId($spr_user_name);
				$spil_id = $model->getMemberId($spil_user_name);
				
				$AR_SPR = $model->getMember($sponsor_id);
				$nleft = $AR_SPR['nleft'];
				$nright = $AR_SPR['nright'];
				
				$QR_GET = "SELECT tm.member_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name 
						   FROM tbl_members AS tm 
						   LEFT JOIN  tbl_mem_tree AS tree ON tree.member_id=tm.member_id
						   WHERE tree.member_id='".$spil_id."'
						   AND tree.nleft BETWEEN '".$nleft."' AND '".$nright."'";
				$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
				echo json_encode($AR_GET);
			break;
			
			case "CHECKFRAN":
				$user_name = $this->input->get('user_name');
				$QR_GET = "SELECT tf.franchisee_id, tf.name
				FROM tbl_franchisee AS tf	
				WHERE tf.user_name='".$user_name."'";
				$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
				echo json_encode($AR_GET);
			break;
			case "CHECK_BATCH":
				$q = $this->input->get('q');
				$QR_GET = "SELECT COUNT(twt.wh_trns_id) AS row_ctrl 
				FROM tbl_warehouse_trns AS twt	
				WHERE twt.batch_no='".$q."'";
				$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
				echo json_encode($AR_GET);
			break;
			case "ADDRESS":
				$member_id = $this->session->userdata('mem_id');
				$AR_ADD = $model->getAddress($member_id);
				echo json_encode($AR_ADD);
			break;
			case "TESTIMONIAL":
				$testimonial_id = $this->input->get('testimonial_id');
				$AR_SET = $this->SqlModel->runQuery("SELECT testimonial_sts FROM tbl_testimonial WHERE testimonial_id='$testimonial_id'",true);
				$testimonial_sts = $AR_SET['testimonial_sts'];
				$new_testimonial_sts = ($testimonial_sts>0)? 0:1;
				$data_up = array("testimonial_sts"=>$new_testimonial_sts);
				$this->SqlModel->updateRecord("tbl_testimonial",$data_up,array("testimonial_id"=>$testimonial_id));
				$AR_RT['ErrorMsg']="success";
				echo json_encode($AR_RT);
			break;
			case "COVER":
				$post_id = $this->input->get('post_id');
				$field_id = $this->input->get('field_id');
				$AR_RT['ErrorMsg']="failed";
				if($field_id>0 && $post_id>0){
					$this->SqlModel->updateRecord("tbl_post_file",array("cover_sts"=>"0"),array("post_id"=>$post_id));
					$this->SqlModel->updateRecord("tbl_post_file",array("cover_sts"=>"1"),array("field_id"=>$field_id));
					$AR_RT['ErrorMsg']="success";
				}
				echo json_encode($AR_RT);
			break;
			case "PIN_COVER":
				$type_id = $this->input->get('type_id');
				$pin_img_id = $this->input->get('pin_img_id');
				$AR_RT['ErrorMsg']="failed";
				if($pin_img_id>0 && $type_id>0){
					$this->SqlModel->updateRecord("tbl_pin_image",array("cover_sts"=>"0"),array("type_id"=>$type_id));
					$this->SqlModel->updateRecord("tbl_pin_image",array("cover_sts"=>"1"),array("pin_img_id"=>$pin_img_id));
					$AR_RT['ErrorMsg']="success";
				}
				echo json_encode($AR_RT);
			break;
			case "WALLET_BALANCE":
				$member_id = $this->session->userdata('mem_id');
				$wallet_id = $this->input->get('wallet_id');
				
				$AR_LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");
				if($AR_LDGR['net_balance']>0){
					$AR_RT = $AR_LDGR;
				}else{
					$AR_RT['net_balance']=0;
				}
				echo json_encode($AR_RT);
			break;
			case "CHECK_REPURCHASE":
				$member_id = _d($this->input->get('member_id'));
				$row_ctrl = $model->checkCountPro("tbl_mem_point","member_id='$member_id' AND point_sub_type LIKE 'ORD'");
				if($row_ctrl>0){
					$AR_RT['row_ctrl']=0;
				}else{
					$AR_RT['row_ctrl']=1;
				}
				 echo json_encode($AR_RT);
			break;
			case "PRICE_SLAB":
				$AR_RT['error_sts'] = 0;
				$post_selling_mrp = $this->input->get('post_selling_mrp');
				if($post_selling_mrp>0){
					$QR_SEL = "SELECT tspp.* 
							  FROM tbl_setup_product_price  AS tspp
							  WHERE tspp.product_price_ratio>0
							  AND tspp.product_price_from<='".$post_selling_mrp."' AND tspp.product_price_to>='".$post_selling_mrp."'
							  ORDER BY tspp.product_price_id DESC LIMIT 1";
					$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
					$product_price_ratio = $AR_SEL['product_price_ratio'];
					$post_cmsn = $post_selling_mrp*$product_price_ratio/100;	
					$AR_RT['post_cmsn'] = $post_cmsn;
					$AR_RT['error_sts'] = 1;
				}
				echo json_encode($AR_RT);
			break;
			case "BANNER_DTL":
				$AR_RT['error_sts'] = 0;
				$banner_id = $this->input->get('banner_id');
				if($banner_id>0){
					$QR_SEL = "SELECT tb.* 
							  FROM tbl_banner  AS tb
							  WHERE tb.banner_id='".$banner_id."'";
					$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
					$AR_SEL['primary_id'] = _e($AR_SEL['banner_id']);
					$AR_SEL['error_sts'] = 1;
					$AR_RT = $AR_SEL;
				}
				echo json_encode($AR_RT);
			break;
			case "LEVEL_SETUP":
				$AR_RT['error_sts'] = 0;
				$level_id = $this->input->get('level_id');
				if($level_id>0){
					$QR_SEL = "SELECT tslc.* 
							  FROM tbl_setup_level_cmsn  AS tslc
							  WHERE tslc.level_id='".$level_id."'";
					$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
					$AR_SEL['primary_id'] = _e($AR_SEL['level_id']);
					$AR_SEL['error_sts'] = 1;
					$AR_RT = $AR_SEL;
				}
				echo json_encode($AR_RT);
			break;
			case "LEVEL_INCOME":
				$member_id = $this->input->post('member_id');	
				$cmsn_date = $this->input->post('cmsn_date');	
			
				
				$QR_TRNS = "SELECT tclb.*, tm.user_id AS from_user_id, tm.first_name, tm.last_name
							FROM  tbl_cmsn_lvl_benefit AS tclb 
							LEFT JOIN tbl_members AS tm ON tm.member_id=tclb.from_member_id
							WHERE tclb.cmsn_date='".$cmsn_date."' AND tclb.member_id='".$member_id."' 
							ORDER BY tclb.level_no ASC";
				$RS_TRNS = $this->SqlModel->runQuery($QR_TRNS);
				
				
				$html .='<table class="table table-striped table-bordered table-hover">';
				$html .='<thead>
				  <tr role="row">
					<th  class="">Full Name</th>
					<th  class="">From User </th>
					<th  class="">Level No </th>
					<th  class="">Total Amount </th>
					<th  class="">Ratio</th>
					<th  class="">Net Amount</th>
					
				  </tr>
				</thead>';
				$html .='<tbody>';
				foreach($RS_TRNS as $AR_DT):
				$total_amount +=$AR_DT['level_cmsn'];
					   $html .='<tr >
                                <td class="">'.$AR_DT['first_name']." ".$AR_DT['last_name'].'</td>
                                <td >'.$AR_DT['from_user_id'].'</td>
								<td>'.number_format($AR_DT['level_no']).'</td>
								<td>'.number_format($AR_DT['net_amount'],2).'</td>
								<td>'.number_format($AR_DT['level_ratio']).' %</td>
                                <td>'.number_format($AR_DT['level_cmsn'],2).'</td>
                              </tr>';
				endforeach;
				$html .='<tr >
                                <td class="">&nbsp;</td>
								<td class="">&nbsp;</td>
								<td class="">&nbsp;</td>
								<td class="">&nbsp;</td>
                                <td class=""><strong>Total</strong></td>
                                <td><strong>'.number_format($total_amount,2).'</strong></td>
                              </tr>';
				$html .='</table>';
				echo $html;
			break;
			case "SPONSOR_LEVEL_INCOME":
				$member_id = $this->input->post('member_id');	
				$process_id = $this->input->post('process_id');	
				
				#$AR_TREE = $model->getMemberTreeField($member_id,"nlevel","DFT");
				#$nlevel = $AR_TREE['nlevel'];
				
				$QR_TRNS = "SELECT tclb.*, tm.user_id AS from_user_id, tm.first_name, tm.last_name
							FROM  tbl_cmsn_lvl_benefit_lvl AS tclb 
							LEFT JOIN tbl_members AS tm ON tm.member_id=tclb.from_member_id
							WHERE tclb.process_id='".$process_id."' AND tclb.member_id='".$member_id."' 
							ORDER BY tclb.level_no ASC";
				$RS_TRNS = $this->SqlModel->runQuery($QR_TRNS);
				
				
				$html .='<table class="table table-striped table-bordered table-hover">';
				$html .='<thead>
				  <tr role="row">
				  	<th  class="">Srl No</th>
					<th  class="">Full Name</th>
					<th  class="">From User </th>
					<th  class="">Total Amount</th>
					<th  class="">Ratio</th>
					<th  class="">Net Amount</th>
					
				  </tr>
				</thead>';
				$html .='<tbody>';
				$i=1;
				foreach($RS_TRNS as $AR_DT):
				$total_amount +=$AR_DT['level_cmsn'];
					   $html .='<tr >
					   			<td >'.$i.'</td>
                                <td class="">'.$AR_DT['first_name']." ".$AR_DT['last_name'].'</td>
                                <td >'.$AR_DT['from_user_id'].'</td>
                                <td>'.number_format($AR_DT['binary_income'],2).'</td>
								<td>'.number_format($AR_DT['level_ratio'],2).'</td>
								<td>'.number_format($AR_DT['level_cmsn'],2).'</td>
                              </tr>';
				$i++;
				endforeach;
				$html .='<tr >
							    <td class="">&nbsp;</td>
                                <td class="">&nbsp;</td>
								<td class="">&nbsp;</td>
								<td class="">&nbsp;</td>
                                <td class=""><strong>Total</strong></td>
                                <td><strong>'.number_format($total_amount,2).'</strong></td>
                              </tr>';
				$html .='</table>';
				echo $html;
			break;
			

		
		}
				
	}
		
}