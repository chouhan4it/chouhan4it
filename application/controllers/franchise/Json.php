<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends MY_Controller {	 
	 
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    if(!$this->isFranchiseLoggedIn()){
			 redirect(FRANCHISE_FOLDER);		
		}
	}
	
	public function jsonhandler(){	
		$model = new OperationModel();
		$switch_type  = $this->input->get('switch_type');
		switch($switch_type){
			case "PRODUCT":
				#$franchisee_id = $this->session->userdata('fran_id');
				#$StrWhr .=" AND ( tp.franchisee_id = '$franchisee_id')";
				$q =  strtolower(($this->input->get('q'))? $this->input->get('q'):$this->input->post('q'));
				if($q!=''){
					$StrWhr .=" AND ( tpl.post_title LIKE '%$q%' )";
					$QR_OBJ = "SELECT tp.post_id , tpl.post_title
					FROM  tbl_post AS tp
					LEFT JOIN  tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
					LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
					LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tag_id,tpl.post_tags)
					LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
					LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
					WHERE tp.delete_sts>0 
					  $StrWhr 
					GROUP BY tp.post_id  
					ORDER BY tp.post_id DESC";
					$AR_RT = $this->SqlModel->runQuery($QR_OBJ);
					foreach($AR_RT as $AR_DT):
						echo str_replace('"','',$AR_DT['post_title'])."\n";
					endforeach;
				}
			break;
			case "PRODUCT_SINGLE":
				$franchisee_id = $this->session->userdata('fran_id');
				$q =  strtolower(($this->input->get('q'))? $this->input->get('q'):$this->input->post('q'));
				#$StrWhr .=" AND ( tp.franchisee_id = '$franchisee_id')";
				$StrWhr .=" AND ( tpl.post_title = '$q')";
				$QR_OBJ = "SELECT tp.post_id , tp.post_code, tp.post_ref, tp.post_qty_limit, tpl.post_title, tpl.post_price, 
				tpl.post_dp_price, tpl.post_size, tpl.post_tax, tpl.post_pv, tpl.post_mrp
				FROM  tbl_post AS tp
				LEFT JOIN  tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
				LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
				WHERE tp.delete_sts>0   $StrWhr 
				GROUP BY tp.post_id  
				ORDER BY tp.post_id DESC";
				$AR_RT = $this->SqlModel->runQuery($QR_OBJ,true);
				
				$batch_no = $model->getBatchNoFranchise($AR_RT['post_id'],$franchisee_id);
				$AR_RT['post_batch'] = $batch_no;
				echo json_encode($AR_RT,true);
			break;
			case "PRODUCT_AVL_QTY":
				
				$franchisee_id = $this->session->userdata('fran_id');
				$post_id =  ($this->input->get('post_id'))? $this->input->get('post_id'):$this->input->post('post_id');
				$AR_POST = $model->getPostDetail($post_id);
				$stock_sts = $AR_POST['stock_sts'];
				$post_attribute_id =  getTool(getTool($this->input->get('post_attribute_id'),$this->input->post('post_attribute_id')),0);
				$AR_STOCK = $this->OperationModel->getFranchiseStock($post_id,$post_attribute_id,$franchisee_id,"","","");
				$AR_RT['available_qty'] = ($AR_STOCK['balance']>0 && $stock_sts>0)? $AR_STOCK['balance']:0;
				echo json_encode($AR_RT,true);
			break;
			case "PRODUCT_STOCK":
				$AR_RT['post_qty']=0;
				$franchisee_id = $this->session->userdata('fran_id');
				$post_id = FCrtAdd($_REQUEST['post_id']);
				$post_qty = FCrtAdd($_REQUEST['post_qty']);
				$AR_LDGR = $model->getStockBalance($post_id,$franchisee_id,"","");
				if($AR_LDGR['net_balance']>=$post_qty){
					$AR_RT['post_qty']=1;
				}
				echo json_encode($AR_RT);
			break;
			case "RETURN_QTY":
				$AR_RT['post_qty']=0;
				$order_detail_id = $this->input->get('order_detail_id');
				$post_qty = $this->input->get('post_qty');
				$QR_DT = "SELECT tod.* FROM  tbl_order_detail AS tod WHERE order_detail_id='".$order_detail_id."'";
				$AR_DT = $this->SqlModel->runQuery($QR_DT,true);
				if($post_qty<=$AR_DT['post_qty']){	
					$AR_RT['post_qty']=$post_qty;
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
			case "CHECK_FPV_POST":
				$coupon_id = $this->input->get('coupon_id');
				$post_id = $this->input->get('post_id');
				
				$AR_RT = array();
				
				echo json_encode(array_merge($AR_SET,$AR_GET));
			break;

		}
		
		
	}
	
	

	
	
}
