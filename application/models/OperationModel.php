<?php

class OperationModel extends CI_Model {

    private $table;

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	function checkMemberLogin($member_id){
		$route_class =   $this->router->fetch_class();
		$route_method =   $this->router->fetch_method();
		if($member_id=='' || $member_id==0){
			$this->session->set_userdata('route_class',$route_class);
			$this->session->set_userdata('route_method',$route_method);
			redirect_front("account","login","");
		}
	}
	
	function checkRegistration(){
		$CONFIG_REGISTER = $this->getValue("CONFIG_REGISTER");
		if($CONFIG_REGISTER=="Y"){
			set_message("warning","Currently our server is down, service will resume soon.");
			redirect_front("account","register","");
		}
	}
	
	function checkLogin(){
		$CONFIG_LOGIN = $this->getValue("CONFIG_LOGIN");
		if($CONFIG_LOGIN=="Y"){
			set_message("warning","Currently our server is down, service will resume soon.");
			redirect_front("account","login","");
		}
	}
	
	function getMemberShopTill($member_id,$total_paid){
        $today_date = InsertDate(getLocalTime());
        $AR_DATE = getMonthDates($today_date);
        $from_date = InsertDate($AR_DATE['flddFDate']);
        $to_date = InsertDate($AR_DATE['flddTDate']);
        $QR_ORDR = "SELECT SUM(total_paid) AS total_purchase
                    FROM tbl_orders
                    WHERE member_id='".$member_id."' AND  stock_sts>0
                    AND DATE(date_add) BETWEEN '".$from_date."' AND '".$to_date."'";
        $AR_ORDR = $this->SqlModel->runQuery($QR_ORDR,true);
        return $AR_ORDR['total_purchase']+$total_paid;
    }
	


	

	
	function getQueryType($query_id){
		$QR_SELECT = "SELECT query_name FROM tbl_support_type WHERE query_id='$query_id'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['query_name'];
	}
	
	function getPinType($type_id){
		$QR_SELECT = "SELECT * FROM tbl_pintype WHERE type_id = '".$type_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function getAttachSrc($reply_id){
		$Q_CHK ="SELECT file_attach FROM tbl_support_rply WHERE reply_id='".$reply_id."';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		if($AR_CHK['file_attach']!=''){
			return BASE_PATH."upload/chat/".$AR_CHK['file_attach'];
		}else{
			return "javascript:void(0)";
		}
	}
	
	function getDefaultPlan(){
		$QR_SELECT = "SELECT type_id FROM tbl_pintype WHERE 1 ORDER BY type_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['type_id'];
	}
	
	function getDefaultLang(){
		$QR_SELECT = "SELECT lang_id FROM tbl_lang WHERE 1 ORDER BY lang_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['lang_id'];
	}
	
	function checkCmsnDaily($subcription_id,$member_id,$start_date,$end_date){
		$QR_SELECT = "SELECT COUNT(daily_id) AS row_ctrl FROM tbl_cmsn_daily 
					  WHERE subcription_id = '".$subcription_id."' 
					  AND DATE(start_date)='".$start_date."' AND DATE(end_date)='".$end_date."'
					  AND member_id='".$member_id."'";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT['row_ctrl'];
	}
	
	
	
	
	function getPostingCount($subcription_id,$member_id,$cmsn_date){
		$QR_SEL = "SELECT COUNT(daily_id) AS row_ctrl FROM tbl_cmsn_daily 
				  WHERE subcription_id='".$subcription_id."' AND member_id='".$member_id."' 
				  AND DATE(cmsn_date)<='".$cmsn_date."'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL['row_ctrl']+1;
	}
	
	function getPostTags($tag_id){
		$tags = ($tag_id!='')? implode(",",array_unique(array_filter(explode(",",$tag_id)))):0;
		$QR_SELECT = "SELECT tag_id AS id , tag_name AS name  FROM tbl_tags WHERE  FIND_IN_SET(tag_id,'$tags')";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->result_array();
		return $AR_SELECT;
	}
	
	
	function getPinPost($type_id){
		$QR_SELECT = "SELECT tp.post_id AS id , tpl.post_title AS name  
					  FROM tbl_post AS tp 
					  LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
					  WHERE tp.post_id IN (SELECT post_id FROM tbl_pin_post WHERE type_id='$type_id')
					  GROUP BY tp.post_id
					  ORDER BY tpl.post_title ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->result_array();
		return $AR_SELECT;
	}
	
	public function setPostCombination($form_data,$post_attribute_id){
		$attribute_combination_list = array_unique(array_filter($form_data['attribute_combination_list']));
		$this->SqlModel->deleteRecord("tbl_post_attribute_combination",array("post_attribute_id"=>$post_attribute_id));
		foreach($attribute_combination_list as $attribute_id):	
			$this->SqlModel->insertRecord("tbl_post_attribute_combination",array("attribute_id"=>$attribute_id,"post_attribute_id"=>$post_attribute_id));
		endforeach;
	}
	
	function setAttributeImage($form_data,$post_attribute_id){
		$field_id_array = $form_data['field_id'];
		$this->SqlModel->deleteRecord("tbl_post_attribute_image",array("post_attribute_id"=>$post_attribute_id));
		foreach($field_id_array as $key=>$field_id):
			$this->SqlModel->insertRecord("tbl_post_attribute_image",array("post_attribute_id"=>$post_attribute_id,"field_id"=>$field_id));
		endforeach;
	}
	
	function getPostAttributeDetail($post_attribute_id){
		$QR_SEL = "SELECT tpa.*, tpf.field_id  FROM tbl_post_attribute AS tpa
				  LEFT JOIN tbl_post_attribute_image AS tpai ON tpai.post_attribute_id=tpa.post_attribute_id
				  LEFT JOIN tbl_post_file AS tpf ON tpf.field_id=tpai.field_id
				  WHERE tpa.post_attribute_id='$post_attribute_id'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL;
	}
	
	function getAttrGroupDetail($attribute_group_id){
		$QR_SEL = "SELECT tag.*  FROM tbl_attribute_group AS tag
				  WHERE tag.attribute_group_id='$attribute_group_id'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL;
	}
	
	function getAttributeOfCombination($post_attribute_id){
		$QR_SEL = "SELECT GROUP_CONCAT(CONCAT_WS('-',tag.attribute_group_name,ta.attribute_name))  AS post_attribute
				   FROM tbl_post_attribute_combination AS tpac 
				   LEFT JOIN tbl_attribute AS ta ON ta.attribute_id=tpac.attribute_id
				   LEFT JOIN tbl_attribute_group AS tag ON tag.attribute_group_id=ta.attribute_group_id
				   WHERE tpac.post_attribute_id='$post_attribute_id'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL['post_attribute'];
	}

	function getCityCovered($franchisee_id){
		$QR_LIST = "SELECT city_list FROM tbl_franchisee WHERE franchisee_id='$franchisee_id'";
		$AR_LIST = $this->SqlModel->runQuery($QR_LIST,true);
		$QR_SELECT = "SELECT * FROM tbl_city WHERE city_id IN ($AR_LIST[city_list])";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->result_array();
		return $AR_SELECT;
	}
	
	
	function setPinPost($type_id,$AR_VAL){
		$post_id_array = array_unique(array_filter(explode(",",$AR_VAL['post_id'])));
		$this->SqlModel->deleteRecord("tbl_pin_post",array("type_id"=>$type_id));
		foreach($post_id_array as $key=>$post_id):	
			if($post_id>0 && $type_id>0){
				$this->SqlModel->insertRecord("tbl_pin_post",array("post_id"=>$post_id,"type_id"=>$type_id));
			}
		endforeach;
	}
	
	function getNewsMemberAccess($news_id){
		$QR_SELECT = "SELECT tna.member_id AS id , CONCAT_WS(' ',first_name,user_id) AS name  FROM tbl_new_access AS tna
					 LEFT JOIN tbl_members AS tm ON tm.member_id=tna.member_id
					 WHERE tna.news_id ='".$news_id."'  AND tna.member_id>0
					ORDER BY tna.news_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->result_array();
		return $AR_SELECT;
	}
	
	function getRewardAchivers(){
		$QR_SELECT = "SELECT trma.member_id AS id , CONCAT_WS(' ',tm.first_name,tm.user_id) AS name  
					 FROM tbl_reward_manual_achiver AS trma
					 LEFT JOIN tbl_members AS tm ON tm.member_id=trma.member_id
					 WHERE  trma.member_id>0
					 ORDER BY trma.member_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->result_array();
		return $AR_SELECT;
	}
	
	function getMemberNews($member_id){
		$QR_ANCE = "SELECT tn.* FROM tbl_news AS tn
				LEFT JOIN tbl_new_access AS tna ON tna.news_id=tn.news_id
				WHERE tn.news_sts>0 AND tn.isDelete>0 AND 
				tn.news_type LIKE 'PRIVATE' AND tna.member_id='".$member_id."'";
		$AR_ANCE = $this->SqlModel->runQuery($QR_ANCE,true);
		if($AR_ANCE['news_id']==''){
			$QR_ANCE ="SELECT tn.* FROM tbl_news AS tn WHERE tn.news_type LIKE 'PRIVATE' 
					  AND tn.checkallmember>0 AND tn.news_sts>0 AND tn.isDelete>0
    			      ORDER BY tn.news_id DESC LIMIT 1";
			$AR_ANCE = $this->SqlModel->runQuery($QR_ANCE,true);
		}
		return $AR_ANCE;
	}
	
	function getAllMember(){
		$QR_MEM = "SELECT tm.member_id FROM tbl_members AS tm WHERE tm.block_sts='N' AND tm.delete_sts>0";
		$RS_MEM = $this->SqlModel->runQuery($QR_MEM);
		foreach($RS_MEM as $AR_MEM):
			$AR_RT[] = $AR_MEM['member_id'];
		endforeach;
		return $AR_RT;
	}
	
	function updateNewsAccess($news_id,$AR_VALS){
		if($news_id!=''){
			if($AR_VALS['checkallmember']>0){
				$this->SqlModel->updateRecord("tbl_news",array("checkallmember"=>1),array("news_id"=>$news_id));
			}elseif($AR_VALS['member_id']!='' && $_FILES['upload_member']['name']==''){
				$member_explode = array_unique(array_filter(explode(",",$AR_VALS['member_id'])));
				foreach($member_explode as $member_id):
					$this->SqlModel->insertRecord("tbl_new_access",array("member_id"=>$member_id,"news_id"=>$news_id));
				endforeach;
			}elseif($_FILES['upload_member']['error']==0){
				
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read($_FILES['upload_member']['tmp_name']);
					for($i=2; $i <= $data->sheets[0]['numRows']; $i++):
						if($data->sheets[0]['cells'][$i][1] <> ''){
							$user_id =trim(str_replace("'","\'", $data->sheets[0]['cells'][$i][1]));
							$member_id = $this->getMemberId($user_id);
							if($member_id>0){
								$this->SqlModel->insertRecord("tbl_new_access",array("member_id"=>$member_id,"news_id"=>$news_id));
							}
						}
					endfor;
					$this->uploadExcelFile($_FILES,array("news_id"=>$news_id),"");
					
			}
		}
	}	
	
	function uploadCommissionOther(){
		if($_FILES['upload_file']['error']==0){
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read($_FILES['upload_file']['tmp_name']);
					for($i=2; $i <= $data->sheets[0]['numRows']; $i++):
						if($data->sheets[0]['cells'][$i][1] <> ''){
							$process_id =trim(str_replace("'","\'", $data->sheets[0]['cells'][$i][1]));
							$user_id =trim(str_replace("'","\'", $data->sheets[0]['cells'][$i][2]));
							$total_amount =trim(str_replace("'","\'", $data->sheets[0]['cells'][$i][3]));
							$trns_remark =trim(str_replace("'","\'", $data->sheets[0]['cells'][$i][4]));
							$member_id = $this->getMemberId($user_id);
							if($member_id>0 && $process_id>0 && $total_amount>0){
								$data_excel = array("member_id"=>$member_id,
									"process_id"=>$process_id,
									"trns_remark"=>($trns_remark!='')? $trns_remark:'',
									"total_amount"=>$total_amount
								);
								$this->SqlModel->insertRecord("tbl_cmsn_other",$data_excel);
							}
						}
					endfor;
					$this->uploadCommisionExcelFile($_FILES,"","");
					
		}
	}	

	function uploadCommisionExcelFile($FILES,$AR_DT,$fldvPath){
		$fldvFileName = $FILES['upload_file']['name'];
		$fldvFileError = $FILES['upload_file']['error'];
		$fldvFileTemp = $FILES['upload_file']['tmp_name'];
		if($fldvFileError=="0"){
			$ext = explode(".",$fldvFileName);
			$fExtn = strtolower(end($ext));
			$fldvUniqueNo = UniqueId("UNIQUE_NO");
			$excel_file = "CMSN_OTH_".$fldvUniqueNo."_". str_replace(" ","","FILE".".".$fExtn);
			$target_path = $fldvPath."upload/other/".$excel_file;
			if(move_uploaded_file($fldvFileTemp, $target_path)){ }	
		}
		
	}
	
	function uploadFranchiseReceipt($FILES,$AR_DT,$fldvPath){

		$order_id = $AR_DT['order_id'];
		$fldvFileName = $FILES['receipt_file']['name'];
		$fldvFileError = $FILES['receipt_file']['error'];
		$fldvFileTemp = $FILES['receipt_file']['tmp_name'];
		if($fldvFileError=="0" && $order_id>0){
			$ext = explode(".",$fldvFileName);
			$fExtn = strtolower(end($ext));
			$fldvUniqueNo = UniqueId("UNIQUE_NO");
			$receipt_file = $fldvUniqueNo."_RCPT_". str_replace(" ","",$order_id.".".$fExtn);
			$target_path = $fldvPath."upload/receipt/".$receipt_file;
			
			$AR_X_FILE = SelectTable("tbl_franchisee_payment","receipt_file","order_id='$order_id'");
			$final_location = $fldvPath."upload/receipt/".$AR_X_FILE['receipt_file'];
			if($AR_X_FILE['receipt_file']!="") { @chmod($final_location,0777);	@unlink($final_location); }
			
			if(move_uploaded_file($fldvFileTemp, $target_path)){
				$data_file = array("receipt_file"=>$receipt_file);
				$this->SqlModel->updateRecord("tbl_franchisee_payment",$data_file,array("order_id"=>$order_id));						
			}	
		}
		
	}
	
	function getFranchiseReceipt($order_id){
		if($order_id>0){
			$QR_FILE = "SELECT tfp.receipt_file
						FROM tbl_franchisee_payment AS tfp 
						WHERE tfp.order_id='".$order_id."'";
			$AR_FILE = $this->SqlModel->runQuery($QR_FILE,true);
			$SRC_PATH = BASE_PATH."upload/receipt/".$AR_FILE['receipt_file'];
			if($AR_FILE['receipt_file']=="") { 
				$SRC_PATH = "javascript:void(0)";
			}
			return $SRC_PATH;
		}
	}
	

	
	function uploadExcelFile($FILES,$AR_DT,$fldvPath){
		$news_id = $AR_DT['news_id'];
		$fldvFileName = $FILES['upload_member']['name'];
		$fldvFileError = $FILES['upload_member']['error'];
		$fldvFileTemp = $FILES['upload_member']['tmp_name'];
		if($fldvFileError=="0" && $news_id>0){
			$ext = explode(".",$fldvFileName);
			$fExtn = strtolower(end($ext));
			$fldvUniqueNo = UniqueId("UNIQUE_NO");
			$excel_file = $fldvUniqueNo.rand(100,999)."_". str_replace(" ","",$news_id.".".$fExtn);
			$target_path = $fldvPath."upload/excel/".$excel_file;
			if(move_uploaded_file($fldvFileTemp, $target_path)){
				$data_file = array("excel_file"=>$excel_file);
				$this->SqlModel->updateRecord("tbl_news",$data_file,array("news_id"=>$news_id));						
			}	
		}
		
	}
	
	
	function uploadCarrierImage($FILES,$AR_DT,$fldvPath){
		$carrier_id = $AR_DT['carrier_id'];
		$file_name = $FILES['carrier_logo']['name'];
		$file_error = $FILES['carrier_logo']['error'];
		$file_temp = $FILES['carrier_logo']['tmp_name'];
		if($file_error=="0" && $carrier_id>0){
			$ext = explode(".",$file_name);
			$file_extn = strtolower(end($ext));
			$unique_no = UniqueId("UNIQUE_NO");
			$carrier_logo = $unique_no.rand(100,999)."_". str_replace(" ","",$carrier_id.".".$file_extn);
			$target_path = $fldvPath."upload/carrier/".$carrier_logo;
			if(move_uploaded_file($file_temp, $target_path)){
				$data_file = array("carrier_logo"=>$carrier_logo);
				if(resizeImage($target_path,$target_path,"300","200","99"))  {
					$this->SqlModel->updateRecord("tbl_carrier",$data_file,array("carrier_id"=>$carrier_id));						
			}	}
		}
		
	}
	
	
	function uploadNewsImage($FILES,$AR_DT,$fldvPath){
		$news_id = $AR_DT['news_id'];
		$file_name = $FILES['news_img']['name'];
		$file_error = $FILES['news_img']['error'];
		$file_temp = $FILES['news_img']['tmp_name'];
		if($file_error=="0" && $news_id>0){
			$ext = explode(".",$file_name);
			$file_extn = strtolower(end($ext));
			$unique_no = UniqueId("UNIQUE_NO");
			$news_img = $unique_no.rand(100,999)."_". str_replace(" ","",$news_id.".".$file_extn);
			$target_path = $fldvPath."upload/news/".$news_img;
			if(move_uploaded_file($file_temp, $target_path)){
				$data_file = array("news_img"=>$news_img);
				if(resizeImage($target_path,$target_path,"300","200","99"))  {
					$this->SqlModel->updateRecord("tbl_news",$data_file,array("news_id"=>$news_id));						
			}	}
		}
		
	}
	
	function getNewsImage($news_id){
		$IMG_SRC = BASE_PATH."setupimages/no-image-available.png";
		if($news_id>0){
			$QR_NEWS = "SELECT tn.news_img
						FROM tbl_news AS tn 
						WHERE tn.news_id='".$news_id."'";
			$AR_NEWS = $this->SqlModel->runQuery($QR_NEWS,true);
			if($AR_NEWS['news_img']!="") { 
				$IMG_SRC = BASE_PATH."upload/news/".$AR_NEWS['news_img'];
			}
		}
		return $IMG_SRC;
	}
	
	function getDefaultWarehouse(){
		$QR_SELECT = "SELECT warehouse_id FROM  tbl_warehouse WHERE 1 ORDER BY warehouse_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['warehouse_id'];
	}
	
	function getDefaultFranchisee(){
		$QR_SELECT = "SELECT franchisee_id FROM tbl_franchisee WHERE 1 ORDER BY franchisee_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['franchisee_id'];
	}
	
	function getDefaultLangLabel(){
		$lang_id = $this->session->userdata('lang_id');
	
		$QR_SELECT = "SELECT name FROM tbl_lang WHERE lang_id='".$lang_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return ($AR_SELECT['name'])? $AR_SELECT['name']:"LANGUAGES";
	}
	
	function getCMS($id_cms){
		$QR_SELECT = "SELECT * FROM tbl_cms WHERE id_cms = '".$id_cms."' AND active>0";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	
	function getWallet($wallet_name){
		$QR_SELECT = "SELECT wallet_id FROM tbl_wallet WHERE wallet_name LIKE '$wallet_name'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['wallet_id'];
	}
	
	function getDefaultWallet(){
		$QR_SELECT = "SELECT wallet_id FROM tbl_wallet WHERE 1 ORDER BY wallet_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['wallet_id'];
	}
	
	function getCompanyId(){
		$QR_SELECT = "SELECT user_id FROM tbl_members WHERE 1 ORDER BY member_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['user_id'];
	}
	
	function getFirstId(){
		$QR_SELECT = "SELECT member_id FROM tbl_members WHERE 1 ORDER BY member_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['member_id'];
	}
	
	function getFirstDate(){
		$QR_SELECT = "SELECT DATE(date_join) AS date_join  FROM tbl_members WHERE 1 ORDER BY member_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['date_join'];
	}
	
	function getStockFirstDate(){
		$QR_SELECT = "SELECT DATE(trans_date) AS trans_date  FROM tbl_stock_ledger WHERE 1 ORDER BY trans_date ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['trans_date'];
	}
	
	function getStockCompanyFirstDate(){
		$QR_SELECT = "SELECT DATE(trans_date) AS trans_date  FROM tbl_warehouse_trns WHERE 1 ORDER BY trans_date ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['trans_date'];
	}
	
	function getCountryName($country_code){
		$QR_SELECT = "SELECT country_name FROM tbl_country WHERE country_code='$country_code'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['country_name'];
	}
	
	function checkPanCard($pan_no){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM tbl_members WHERE pan_no LIKE '$pan_no'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}

	function checkUserExist($user_name){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM tbl_operator WHERE user_name LIKE '$user_name'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}

	function getOprtrDetail($oprt_id){
		$QR_GET = "SELECT * FROM tbl_operator WHERE oprt_id = '".$oprt_id."'";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET;
	}
	
	function checkEmailExist($member_email){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM tbl_members WHERE member_email LIKE '".$member_email."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function checkCount($table_name,$field,$primary_id){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM $table_name WHERE $field = '$primary_id'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function checkCountPro($table_name,$where_clause){
		$QR_SEL = "SELECT COUNT(*) AS ctrl_count FROM $table_name WHERE  $where_clause";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL['ctrl_count'];
	}
	
	function checkProtectCount($table_name,$field,$primary_id){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM $table_name WHERE $field = '$primary_id' AND $field!=''";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function checkMemberUsernameExist($user_name,$member_id=''){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM tbl_members WHERE user_name LIKE '$user_name'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		if($member_id>0){	
			return 0;
		}else{
			return $AR_SELECT['ctrl_count'];
		}
	}
	
	function checkPinKey($member_id,$pin_id){
		$Q_COUNT = "SELECT COUNT(*) AS fldiCtrl 
					FROM tbl_pinsdetails 
					WHERE member_id='".$member_id."' 
					AND pin_id='".$pin_id."'
					AND pin_sts='N' 
					AND block_sts='N'";
		$R_COUNT = $this->db->query($Q_COUNT);
		$A_COUNT  = $R_COUNT->row_array();
		return $A_COUNT['fldiCtrl'];
	}
	
	function getPinDetail($pin_no,$pin_key){
		$QR_SELECT = "SELECT * FROM tbl_pinsdetails WHERE pin_no = '".$pin_no."' AND pin_key = '".$pin_key."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function updatePinDetail($pin_id,$use_member_id){
		$today_date = InsertDate(getLocalTime());
		if($pin_id>0 && $use_member_id>0){
			$data = array("use_member_id"=>$use_member_id,
				"used_date"=>$today_date,
				"pin_sts"=>"Y"
			);
			$this->SqlModel->updateRecord("tbl_pinsdetails",$data,array("pin_id"=>$pin_id));
		}
	}
	
	
	function deleteTable($table_name,$where_condition){
		if ($this->db->delete($table_name, $where_condition)) {
            return true;
        } else {
            return false;
        }
	}
	
	function getGroupType($group_id){
		$QR_SELECT = "SELECT group_type FROM tbl_oprtr_grp WHERE group_id = '$group_id'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['group_type'];
	}
	
	function getValue($fldvFiled){
		$QR_CONFIG="SELECT value FROM tbl_configuration WHERE name='$fldvFiled' LIMIT 1";
		$RS_CONFIG = $this->db->query($QR_CONFIG);
		$AR_CONFIG = $RS_CONFIG->row_array();
		if(is_numeric($AR_CONFIG['value'])){
			$returnVar = ($AR_CONFIG['value']==NULL)? "0":$AR_CONFIG['value'];	
		}else{
			$returnVar = ($AR_CONFIG['value']==NULL)? "":$AR_CONFIG['value'];
		}
		return $returnVar;
	}
	
	function getAll($fldvFiled){	
		$QR_CONFIG="SELECT * FROM tbl_configuration WHERE name='$fldvFiled' LIMIT 1";
		$RS_CONFIG = $this->db->query($QR_CONFIG);
		$AR_CONFIG = $RS_CONFIG->row_array();
		return $AR_CONFIG;
	}
	
	function updateConfig($fldvFields,$whereClause){
		$this->db->query("UPDATE tbl_configuration SET $fldvFields WHERE $whereClause");
	}
	
	function setConfig($fldvFields,$fldvValue){
		$date_upd = $date_upd = getLocalTime();
		if($this->checkCount("tbl_configuration","name",$fldvFields)>0){
			$this->db->query("UPDATE tbl_configuration SET value='$fldvValue', date_upd='$date_upd' WHERE name='$fldvFields'");
		}else{
			$this->db->query("INSERT INTO  tbl_configuration SET value='$fldvValue' , name='$fldvFields', date_add='$date_add', 
			date_upd='$date_upd'");
		}
	}
	
	function getConfig($wherclause){	
		$StrWhr .=($wherclause!="")? "AND $wherclause":"AND id_configuration<0";
		$QR_CONFIG="SELECT value FROM tbl_configuration WHERE id_configuration>0  $StrWhr  LIMIT 1";
		$RS_CONFIG = $this->db->query($QR_CONFIG);
		$AR_CONFIG = $RS_CONFIG->row_array();
		$returnVar = ($AR_CONFIG['value']==NULL)? "0":$AR_CONFIG['value'];
		return $returnVar;
	}
		
	function getRankName($rank_id){
		$QR_SELECT = "SELECT rank_name FROM tbl_rank WHERE rank_id = '$rank_id'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['rank_name'];
	}
	
	function getMemberOldRank($member_id,$rank_id){
		$QR_SEL = "SELECT from_rank_id FROM tbl_upgrade_history WHERE to_rank_id = '".$rank_id."' AND member_id='".$member_id."'";		
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL['from_rank_id'];
	}
	
	function deleteReferralSetup(){
		if ($this->db->delete("tbl_setup_mem_referal_cmsn",array("package_id_from > "=>"0"))) {
            return true;
        } else {
            return false;
        }
	}
	
	function getRefferalCmsn($package_id_from,$package_id_to){
		$QR_SELECT = "SELECT cmsn_amount FROM tbl_setup_mem_referal_cmsn WHERE package_id_from = '".$package_id_from."' 
		AND package_id_to = '".$package_id_to."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['cmsn_amount'];
	}
	
	function getMemberPlacement($member_id){
		$mem_id = $this->session->userdata("mem_id");
		$QR_SEL = "SELECT COUNT(A.member_id) AS row_ctrl 
				  FROM tbl_mem_tree AS A, tbl_mem_tree AS B
				  WHERE A.left_right='L' AND A.spil_id='".$mem_id."'
			  	  AND B.nleft BETWEEN A.nleft AND A.nright AND B.member_id='".$member_id."'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		$row_ctrl = $AR_SEL['row_ctrl'];
		return ($row_ctrl>0)? "L":"R";
	}

	
	function getMemberId($user_name){
		$QR_SELECT = "SELECT member_id FROM tbl_members WHERE  ( user_name LIKE '".$user_name."' OR user_id LIKE '".$user_name."' )";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['member_id'];
	}
	
	function getDefaultProcessor(){
		$QR_SELECT = "SELECT processor_id FROM tbl_payment_processor WHERE 1 ORDER BY processor_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['processor_id'];
	}
	function getProcessor($processor_id){
		$QR_SELECT = "SELECT * FROM tbl_payment_processor WHERE processor_id='$processor_id'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function getProcess($process_id){
		$StrWhr .= ($process_id>0)? " AND process_id='".$process_id."'":" AND process_sts='N' AND business_sts='N'";
		$QR_SELECT = "SELECT * FROM tbl_process WHERE 1 $StrWhr ORDER BY process_id DESC LIMIT 1";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	function getOldBinary($process_id,$member_id){
		$QR_BNRY = "SELECT tcb.* 
					FROM tbl_cmsn_binary AS tcb  
					WHERE tcb.member_id='".$member_id."' 
					AND tcb.process_id<'".$process_id."' 
					ORDER BY tcb.process_id DESC LIMIT 1";
		$AR_BNRY = $this->SqlModel->runQuery($QR_BNRY,true);
		return $AR_BNRY;
	}
	
	function getOldBinaryRepurcase($process_id,$member_id){
		$QR_BNRY = "SELECT tcbr.* 
					FROM tbl_cmsn_binary_repur AS tcbr  
					WHERE tcbr.member_id='".$member_id."' 
					AND tcbr.process_id<'".$process_id."' 
					ORDER BY tcbr.process_id DESC LIMIT 1";
		$AR_BNRY = $this->SqlModel->runQuery($QR_BNRY,true);
		return $AR_BNRY;
	}
	
	function getPendingProcess($process_id){
		$StrWhr .= ($process_id>0)? " AND process_id='".$process_id."'":" AND process_sts='N' AND business_sts='N'";
		$QR_SELECT = "SELECT * FROM tbl_process WHERE 1 $StrWhr ORDER BY process_id DESC LIMIT 1";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	function getPendingProcessBinary($process_id){
		$StrWhr .= ($process_id>0)? " AND process_id='".$process_id."'":" AND process_sts='N'";
		$QR_SELECT = "SELECT * FROM tbl_process_binary WHERE 1 $StrWhr ORDER BY process_id DESC LIMIT 1";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	
	function uploadCancelCheque($ARRAY,$FILES,$fldvPath){
		
		$fldvFileName= $FILES['cancel_cheque']['name'];
		$fldvFileTemp = $FILES['cancel_cheque']['tmp_name'];
		$fldvFileType = $FILES['cancel_cheque']['type'];
		$fldvFileError = $FILES['cancel_cheque']['error'];
		$member_id = $ARRAY['member_id'];
		if($fldvFileError=="0" && $member_id>0){
			$ext = explode(".",$fldvFileName);
			$fExtn = strtolower(end($ext));
			$fldvUniqueNo = UniqueId("UNIQUE_NO");
			$cancel_cheque = $fldvUniqueNo.rand(100,999)."_". str_replace(" ","",rand(100,999).".".$fExtn);
			$target_path = $fldvPath."upload/cheque/".$cancel_cheque;
			if(move_uploaded_file($fldvFileTemp, $target_path)){
				$data_file = array("cancel_cheque"=>$cancel_cheque);
				$this->SqlModel->updateRecord("tbl_members",$data_file,array("member_id"=>$member_id));						
			}	
		}
	}
	
	function getCancelCheque($member_id){
		if($member_id>0){
			$QR_FILE = "SELECT tm.cancel_cheque
						FROM tbl_members AS tm 
						WHERE tm.member_id='".$member_id."'";
			$AR_FILE = $this->SqlModel->runQuery($QR_FILE,true);
			$IMG_SRC = BASE_PATH."upload/cheque/".$AR_FILE['cancel_cheque'];
			#$fldvImageArr= @getimagesize($IMG_SRC);
			if($AR_FILE['cancel_cheque']=="") { 
				$IMG_SRC = BASE_PATH."setupimages/no-image-available.png";
			}
			return $IMG_SRC;
		}
	}
	
	function uploadBannerImg($FILES,$ARRAY,$fldvPath){
		
		$fldvFileName= $FILES['banner_image']['name'];
		$fldvFileTemp = $FILES['banner_image']['tmp_name'];
		$fldvFileType = $FILES['banner_image']['type'];
		$fldvFileError = $FILES['banner_image']['error'];
		$banner_id = $ARRAY['banner_id'];
		if($fldvFileError=="0" && $banner_id>0){
			$ext = explode(".",$fldvFileName);
			$fExtn = strtolower(end($ext));
			$fldvUniqueNo = UniqueId("UNIQUE_NO");
			$banner_file = $fldvUniqueNo.rand(100,999)."_". str_replace(" ","",rand(100,999).".".$fExtn);
			$target_path = $fldvPath."upload/banner/".$banner_file;
			if(move_uploaded_file($fldvFileTemp, $target_path)){
				$data_file = array("banner_file"=>$banner_file);
				$this->SqlModel->updateRecord("tbl_banner",$data_file,array("banner_id"=>$banner_id));						
			}	
		}
	}
	
	function getBannerImg($banner_id){
		if($banner_id>0){
			$QR_FILE = "SELECT tb.*
						FROM tbl_banner AS tb 
						WHERE tb.banner_id='".$banner_id."' AND tb.delete_sts>0
						ORDER BY tb.banner_id ASC LIMIT 1";
			$AR_FILE = $this->SqlModel->runQuery($QR_FILE,true);
			$IMG_SRC = BASE_PATH."upload/banner/".$AR_FILE['banner_file'];
			#$fldvImageArr= @getimagesize($IMG_SRC);
			if($AR_FILE['banner_file']=="") { 
				$IMG_SRC = BASE_PATH."setupimages/no-image-available.png";
			}
			return $IMG_SRC;
		}
	}
	
	
	
	function generateUserId(){
		$data = "1234567890";
		for($i = 0; $i < 7; $i++){
			$unique_no .= substr($data, (rand()%(strlen($data))), 1);
		}
		$user_id = "TK".$unique_no;
		$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_members WHERE user_id='".$user_id."';";
		$AR_CHK = $this->SqlModel->runQuery($Q_CHK,true);
		if($AR_CHK['fldiCtrl']==0){
			return $user_id;
		}else{
			return $this->generateUserId();
		}
	}
	
	function generatePassword(){
		$data = "1234567890";
		for($i = 0; $i < 5; $i++){
			$unique_no .= substr($data, (rand()%(strlen($data))), 1);
		}
		$user_id = $unique_no;
		$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_members WHERE user_id='".$user_id."';";
		$AR_CHK = $this->SqlModel->runQuery($Q_CHK,true);
		if($AR_CHK['fldiCtrl']==0){
			return $user_id;
		}else{
			return $this->generateUserId();
		}
	}
	
	function checkPrevMember($member_id){
		$Q_COUNT = "SELECT member_id FROM tbl_members WHERE member_id<'".$member_id."' ORDER BY member_id DESC LIMIT 1";
		$R_COUNT = $this->db->query($Q_COUNT);
		$A_COUNT  = $R_COUNT->row_array();
		return $A_COUNT['member_id'];
	}
	
	function checkNextMember($member_id){
		$Q_COUNT = "SELECT member_id FROM tbl_members WHERE member_id>'".$member_id."' ORDER BY member_id ASC LIMIT 1";
		$R_COUNT = $this->db->query($Q_COUNT);
		$A_COUNT  = $R_COUNT->row_array();
		return $A_COUNT['member_id'];
	}
	
	
	function checkMemberId($member_id){
		$Q_COUNT = "SELECT COUNT(*) AS fldiCtrl FROM tbl_members WHERE member_id='".$member_id."' ORDER BY member_id DESC LIMIT 1";
		$R_COUNT = $this->db->query($Q_COUNT);
		$A_COUNT  = $R_COUNT->row_array();
		if($A_COUNT['fldiCtrl']==0){
			set_message("warning","Direct access not allowed , unable to load");
			redirect_page("member","profilelist",array()); exit;
		}
	}
	
	function checkOldPassword($member_id,$user_password){
		$Q_COUNT = "SELECT COUNT(*) AS fldiCtrl FROM tbl_members WHERE member_id='".$member_id."' AND user_password='".$user_password."'
		 ORDER BY member_id DESC LIMIT 1";
		$R_COUNT = $this->db->query($Q_COUNT);
		$A_COUNT  = $R_COUNT->row_array();
		return $A_COUNT['fldiCtrl'];
	}
	
	function checkTrnsPassword($member_id,$trns_password){
		$Q_COUNT = "SELECT COUNT(*) AS fldiCtrl FROM tbl_members WHERE member_id='".$member_id."' AND trns_password='".$trns_password."'
		 ORDER BY member_id DESC LIMIT 1";
		$R_COUNT = $this->db->query($Q_COUNT);
		$A_COUNT  = $R_COUNT->row_array();
		return $A_COUNT['fldiCtrl'];
	}
	
	function ExtrmLftRgt($member_id, $left_right){
		$QR_GET  = "SELECT member_id FROM tbl_mem_tree WHERE spil_id='".$member_id."' AND left_right='".$left_right."';";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		if($AR_GET['member_id'] == ""){return $member_id;}
		else{return $this->ExtrmLftRgt($AR_GET['member_id'],$left_right);}
	}
	
	function CheckOpenPlace($spil_id, $left_right){
		$QR_GET = "SELECT COUNT(*) AS fldiCtrl FROM tbl_mem_tree WHERE spil_id='".$spil_id."' AND left_right='".$left_right."'; ";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET['fldiCtrl'];
	}
	
	function CheckOpenPlaceLvl($spil_id, $left_right){
		$QR_GET = "SELECT COUNT(*) AS fldiCtrl FROM tbl_mem_tree WHERE spil_id='".$spil_id."' AND left_right='".$left_right."'; ";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET['fldiCtrl'];
	}
	
	function insertTree($member_id,$sponsor_id,$left_right,$date_join){
		$AR_GET = $this->getOpenPlace($sponsor_id);
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
		
		if($this->checkCount("tbl_mem_tree","member_id",$member_id)==0 && $spil_id>0){
			$this->SqlModel->insertRecord("tbl_mem_tree",$tree_data);
			$this->updateTree($spil_id,$member_id);
			$this->SqlModel->updateRecord("tbl_members",array("spil_id"=>$spil_id),array("member_id"=>$member_id));
		}
	}
	
	function insertTreeLvl($member_id,$sponsor_id,$spil_id,$left_right,$date_join){
		$tree_data = array("member_id"=>$member_id,
			"sponsor_id"=>$sponsor_id,
			"spil_id"=>$spil_id,
			"left_right"=>getTool($left_right,''),
			"nlevel"=>0,
			"nleft"=>0,
			"nright"=>0,
			"date_join"=>$date_join
		);
		
		if($this->checkCount("tbl_mem_tree_lvl","member_id",$member_id)==0 && $sponsor_id>0){
			$this->SqlModel->insertRecord("tbl_mem_tree_lvl",$tree_data);
			$this->updateTreeLvl($sponsor_id,$member_id);
		}
	}
	
	function updateTree($sponsor_id,$member_id){
		$LockTbl = "LOCK TABLE tbl_mem_tree WRITE;";
		
		$SpLeft = "SELECT @myLeft := nleft FROM  tbl_mem_tree WHERE member_id='".$sponsor_id."';";
		$UpdtRight = "UPDATE  tbl_mem_tree SET nright = nright + 2 WHERE nright > @myLeft;";
		$UpdateLeft = "UPDATE  tbl_mem_tree SET nleft = nleft + 2 WHERE nleft > @myLeft;";
		$UpdateAll ="UPDATE  tbl_mem_tree SET nleft=@myLeft + 1, nright=@myLeft + 2 WHERE member_id='".$member_id."';";
		$UnLockTbl = "UNLOCK TABLES;";
		$this->db->query($LockTbl);
		$this->db->query($SpLeft);
		$this->db->query($UpdtRight);
		$this->db->query($UpdateLeft);
		$this->db->query($UpdateAll);
		$this->db->query($UnLockTbl);
		
		$Q_LVL ="SELECT COUNT(parent.member_id) AS nlevel FROM tbl_mem_tree AS node, tbl_mem_tree AS parent 
				WHERE node.nleft  BETWEEN parent.nleft AND parent.nright AND node.member_id='".$member_id."' 
				GROUP BY node.member_id ORDER BY node.nleft";
		$R_LVL = $this->db->query($Q_LVL);
		$AR_LVL = $R_LVL->row_array();


		$Q_UpLvl="UPDATE tbl_mem_tree SET nlevel='$AR_LVL[nlevel]' WHERE member_id='".$member_id."'";
		$this->db->query($Q_UpLvl);
	}
	
	function updateTreeLvl($sponsor_id,$member_id){
		$LockTbl = "LOCK TABLE tbl_mem_tree_lvl WRITE;";
		
		$SpLeft = "SELECT @myLeft := nleft FROM  tbl_mem_tree_lvl WHERE member_id='".$sponsor_id."';";
		$UpdtRight = "UPDATE  tbl_mem_tree_lvl SET nright = nright + 2 WHERE nright > @myLeft;";
		$UpdateLeft = "UPDATE  tbl_mem_tree_lvl SET nleft = nleft + 2 WHERE nleft > @myLeft;";
		$UpdateAll ="UPDATE  tbl_mem_tree_lvl SET nleft=@myLeft + 1, nright=@myLeft + 2 WHERE member_id='".$member_id."';";
		$UnLockTbl = "UNLOCK TABLES;";
		$this->db->query($LockTbl);
		$this->db->query($SpLeft);
		$this->db->query($UpdtRight);
		$this->db->query($UpdateLeft);
		$this->db->query($UpdateAll);
		$this->db->query($UnLockTbl);
		
		$Q_LVL ="SELECT COUNT(parent.member_id) AS nlevel FROM tbl_mem_tree_lvl AS node, tbl_mem_tree_lvl AS parent 
				WHERE node.nleft  BETWEEN parent.nleft AND parent.nright AND node.member_id='".$member_id."' 
				GROUP BY node.member_id ORDER BY node.nleft";
		$R_LVL = $this->db->query($Q_LVL);
		$AR_LVL = $R_LVL->row_array();


		$Q_UpLvl="UPDATE tbl_mem_tree_lvl SET nlevel='$AR_LVL[nlevel]' WHERE member_id='".$member_id."'";
		$this->db->query($Q_UpLvl);
	}
	
	function UpdateMemberTree($sponsor_id, $member_id,$date_join){
		$date_join = InsertDate($date_join);
		$LockTbl = "LOCK TABLE tbl_mem_tree WRITE;";
		$SpLeft = "SELECT @myLeft := nleft, @iLevel:=nlevel FROM tbl_mem_tree WHERE member_id='$sponsor_id';";
		$UpdtRight = "UPDATE tbl_mem_tree SET nright = nright + 2 WHERE nright > @myLeft;";
		$UpdateLeft = "UPDATE tbl_mem_tree SET nleft = nleft + 2 WHERE nleft > @myLeft;";
		$StrQ_Insert="INSERT INTO tbl_mem_tree SET member_id='$member_id', sponsor_id='$sponsor_id', spil_id='$sponsor_id',
					 nlevel=@iLevel+1, nleft=@myLeft+1, nright=@myLeft+2, date_join='$date_join';";
		$UnLockTbl = "UNLOCK TABLES;";
		$this->db->query($LockTbl);
		$this->db->query($SpLeft);
		$this->db->query($UpdtRight);
		$this->db->query($UpdateLeft);
		$this->db->query($StrQ_Insert);
		$this->db->query($UnLockTbl);
	}
	
	function deleteMemberFromTree($member_id){
		$Q_Lck = "LOCK TABLE tbl_mem_tree WRITE;";
		$Q_LR = "SELECT @myLeft := nleft, @myRight := nright, @myWidth := nright - nleft + 1 FROM tbl_mem_tree 
		WHERE member_id='".$member_id."';";
		$Q_DLT = "UPDATE tbl_mem_tree SET nleft=0, nright=0, nlevel=0 WHERE nleft BETWEEN @myLeft AND @myRight;";
		$Q_UDT1 = "UPDATE tbl_mem_tree SET nright = nright - @myWidth WHERE nright > @myRight;";
		$Q_UDT2 = "UPDATE tbl_mem_tree SET nleft = nleft - @myWidth WHERE nleft > @myRight;";
		$Q_Ulck = "UNLOCK TABLES;";
		$Q_TREE = "DELETE FROM  tbl_mem_tree WHERE member_id='".$member_id."'";
		$Q_MEM =  "DELETE FROM  tbl_members WHERE member_id='".$member_id."'";
		
		$this->db->query($Q_Lck);
		$this->db->query($Q_LR);
		$this->db->query($Q_DLT);
		$this->db->query($Q_UDT1);
		$this->db->query($Q_UDT2);
		$this->db->query($Q_Ulck);
		$this->db->query($Q_TREE);
		$this->db->query($Q_MEM);
		
	}
	
	
	function getCurrentImg($member_id){
		if($member_id>0){
		$AR_GV = $this->getMember($member_id);
			if($AR_GV['photo']=="" && $AR_GV['gender']=="M"){ 
				$fldvMemPhoto= BASE_PATH."setupimages/photo.jpg"; 
			}elseif($AR_GV['photo']=="" && $AR_GV['gender']=="F"){ 
				$fldvMemPhoto= BASE_PATH."setupimages/female.jpg"; 
			}elseif($AR_GV['photo']){
				$fldvMemPhoto = BASE_PATH."upload/member/".$AR_GV['photo']; 
			}else{
				$fldvMemPhoto= BASE_PATH."setupimages/photo.jpg"; 
			}
			
			$AR_RT['IMG_SRC']=$fldvMemPhoto;
			return $AR_RT;
		}
	}
	
	function getTempMember($fldiRegId){
		$QR_GET = "SELECT ttr.* FROM tbl_tmp_register AS ttr WHERE ttr.fldiRegId='".$fldiRegId."'";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET;
	}
	
	function getMember($member_id,$type=''){
		 $tree_table = getTree($type);
		 $QR_GET = "SELECT tm.*, tm.member_mobile AS mobile_number, 
		 CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tmsp.first_name AS spsr_first_name,
		 tmsp.last_name AS spsr_last_name,  tmsp.user_name AS spsr_user_name, tmsp.user_id AS spsr_user_id, 
		 CONCAT_WS('',tmsp.mobile_code,tmsp.member_mobile) AS spsr_mobile_number, 
		 tree.nlevel, tree.nleft, tree.nright, tr.rank_name
		 FROM tbl_members AS tm	
		 LEFT JOIN ".$tree_table." AS tree  ON tree.member_id=tm.member_id
		 LEFT JOIN tbl_members AS tmsp  ON tmsp.member_id=tree.sponsor_id
		 LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
		 WHERE tm.member_id='$member_id'";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET;
	}
	
	
	
	function getMemberRank($member_id){
		 $QR_GET = "SELECT tm.member_id, tr.rank_id,  tr.rank_name, tr.month_target, tr.rank_cmsn
		 FROM tbl_members AS tm	
		 LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
		 WHERE tm.member_id='".$member_id."'";
		 $AR_GET = $this->SqlModel->runQuery($QR_GET,true);
		 return $AR_GET;
	}
	
	function getMemberField($member_id,$colum_name){
		$QR_GET = "SELECT $colum_name 	 FROM tbl_members	 WHERE member_id='$member_id'";
		$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
		return $AR_GET;
	}
	
	function getMemberTreeField($member_id,$colum_name,$tree_type=''){
		$table_type = getTree($tree_type);
		$QR_GET = "SELECT $colum_name 	 FROM ".$table_type."	 WHERE member_id='$member_id'";
		$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
		return $AR_GET;
	}
	
	function getMemberIdPanCard($pan_no){
		$QR_GET = "SELECT  member_id	 FROM tbl_members	 WHERE pan_no='$pan_no'";
		$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
		return $AR_GET['member_id'];
	}
	
	
	
	
	function getMemberUserId($member_id){
		$QR_SELECT = "SELECT user_id FROM tbl_members WHERE member_id = '".$member_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['user_id'];
	}
	
	function getMemberRankId($member_id,$date_time){
		$QR_GET = "SELECT to_rank_id AS rank_id FROM tbl_mem_rank 
		 WHERE member_id='$member_id' AND DATE(date_time)<='".$date_time."'
		 ORDER BY history_id DESC LIMIT 1";
		$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
		return $AR_GET['rank_id'];
	}
	
	
	
	function BinaryCount($member_id, $switch){
		switch($switch){	
			
			case "TotalCount":
				$QR_SELECT = "SELECT nleft, nright FROM tbl_mem_tree WHERE member_id='".$member_id."';";
				$RS_SELECT = $this->db->query($QR_SELECT);
				$AR_SELECT = $RS_SELECT->row_array();
				$nleft = $AR_SELECT["nleft"];
				$nright = $AR_SELECT["nright"];
				
				$Q_CTRL= "SELECT COUNT(tm.member_id) AS fldiCtrl 
				 FROM tbl_members AS tm
				 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				 WHERE  tree.member_id!='".$member_id."' 
				 AND  tree.nleft BETWEEN '$nleft' AND '$nright'";
				$R_CTRL = $this->db->query($Q_CTRL);
				$A_CTRL = $R_CTRL->row_array();
				return $A_CTRL['fldiCtrl'];
			break;
			case "ActiveCount":
				$QR_SELECT = "SELECT nleft, nright FROM tbl_mem_tree WHERE member_id='".$member_id."';";
				$RS_SELECT = $this->db->query($QR_SELECT);
				$AR_SELECT = $RS_SELECT->row_array();
				$nleft = $AR_SELECT["nleft"];
				$nright = $AR_SELECT["nright"];
				
				$Q_CTRL= "SELECT COUNT(tm.member_id) AS fldiCtrl 
				 FROM tbl_members AS tm
				 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				 WHERE  tree.member_id!='".$member_id."' AND tree.member_id IN (SELECT member_id FROM tbl_subscription)
				 AND  tree.nleft BETWEEN '$nleft' AND '$nright'";
				$R_CTRL = $this->db->query($Q_CTRL);
				$A_CTRL = $R_CTRL->row_array();
				return $A_CTRL['fldiCtrl'];
			break;
			case "InActiveCount":
				$QR_SELECT = "SELECT nleft, nright FROM tbl_mem_tree WHERE member_id='".$member_id."';";
				$RS_SELECT = $this->db->query($QR_SELECT);
				$AR_SELECT = $RS_SELECT->row_array();
				$nleft = $AR_SELECT["nleft"];
				$nright = $AR_SELECT["nright"];
				
				$Q_CTRL= "SELECT COUNT(tm.member_id) AS fldiCtrl 
				 FROM tbl_members AS tm
				 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				 WHERE  tree.member_id!='".$member_id."' AND tree.member_id NOT IN (SELECT member_id FROM tbl_subscription)
				 AND  tree.nleft BETWEEN '$nleft' AND '$nright'";
				$R_CTRL = $this->db->query($Q_CTRL);
				$A_CTRL = $R_CTRL->row_array();
				return $A_CTRL['fldiCtrl'];
			break;
			case "DirectCount":
				 $Q_CTRL = "SELECT COUNT(tm.member_id) AS fldiCtrl 
				 FROM tbl_members AS tm
				 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				 WHERE tree.sponsor_id='".$member_id."'";
				$R_CTRL = $this->db->query($Q_CTRL);
				$A_CTRL = $R_CTRL->row_array();
				return $A_CTRL['fldiCtrl'];
			break;
			
		}
	}
	
	
	function getCronJob($cron_id){
		$StrWhr .=($cron_id>0)? " AND cron_id='".$cron_id."'":" AND  cron_sts='N'";
		$QR_SELECT = "SELECT * FROM tbl_cronjob WHERE 1 $StrWhr ORDER BY cron_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function getCronJobLast(){
		$QR_SELECT = "SELECT cron_id FROM tbl_cronjob WHERE  cron_id>0 ORDER BY cron_id DESC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['cron_id'];
	}
	
	
	
	function checkMemberLevel($member_id){
		return $this->BinaryCount($member_id,"TotalCount");
	}
	
	function getMemberTree($member_id){
		$AR_GV = $this->getMemberField($member_id,"gender");
		$AR_SHIP = $this->getCurrentMemberShipFormat($member_id);
		$AR_SELF = $this->getSumSelfCollection($member_id,"","","ORD");
		
		$self_pv = $AR_SELF['total_bal_pv'];
		$pin_letter = $AR_SHIP['pin_letter'];
		$paid_ctrl = $AR_SHIP['subcription_id'];
		
		if($paid_ctrl>0){
			if($AR_GV['gender']=='M'){
				$image = "green_m.png";
				$ImgSrc = BASE_PATH."setupimages/".$image; 
			}elseif($AR_GV['gender']=='F'){
				$image = "green_f.png";
				$ImgSrc = BASE_PATH."setupimages/".$image; 
			}else{
				$ImgSrc = BASE_PATH."setupimages/red_m.png"; 
			}
		}elseif($self_pv>=1000){
			if($AR_GV['gender']=='M'){
				$image = "yellow_m.png";
				$ImgSrc = BASE_PATH."setupimages/".$image; 
			}elseif($AR_GV['gender']=='F'){
				$image =  "yellow_f.png";
				$ImgSrc = BASE_PATH."setupimages/".$image; 
			}else{
				$ImgSrc = BASE_PATH."setupimages/red_m.png"; 
			}
		}else{
			if($AR_GV['gender']=='M'){
				$image = "red_m.png";
				$ImgSrc = BASE_PATH."setupimages/".$image; 
			}elseif($AR_GV['gender']=='F'){
				$image = "red_f.png";
				$ImgSrc = BASE_PATH."setupimages/".$image; 
			}else{
				$ImgSrc = BASE_PATH."setupimages/red_m.png"; 
			}
		}
		
		
		$CssCls= ($paid_ctrl>0)? "text_green":"text_danger";
		$AR_RT['IMG_PATH'] = $ImgSrc;
		$AR_RT['IMG_CLASS'] = $CssCls;
		return $AR_RT;
	}
	
	function getStartOrderDate(){
		$QR_GET = "SELECT date_add FROM tbl_orders WHERE 1 ORDER BY order_id ASC LIMIT 1";
		$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
		return $AR_GET['date_add'];
	}
	
	function getInvoiceDate($invoice_number){
		$QR_GET = "SELECT invoice_date FROM tbl_orders WHERE invoice_number='$invoice_number'";
		$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
		return $AR_GET['invoice_date'];
	}
	
	function getNameStatus($member_id){
		if($member_id != ""){
			$today_date = getLocalTime();
			$last_month_date = InsertDate(AddToDate(InsertDate($today_date),"-1 Month"));
			$AR_GV = $this->getMember($member_id);
			
			
			$start_date = InsertDate($this->getStartOrderDate());
			
			$C_MONTH = getMonthDates($today_date);
			$X_MONTH = getMonthDates($last_month_date);

			$total_count = $this->BinaryCount($member_id, "TotalCount");
			$direct_count = $this->BinaryCount($member_id, "DirectCount");
			
			
			$C_M_SELF = $this->getSumSelfCollection($member_id,$C_MONTH['flddFDate'],$C_MONTH['flddTDate']);
			$L_M_SELF = $this->getSumSelfCollection($member_id,$start_date,$X_MONTH['flddTDate']);
			
			$ACU_SELF_VOL = $C_M_SELF['total_bal_vol']+$L_M_SELF['total_bal_vol'];
			
			$C_M_GROUP = $this->getSumGroupCollection($member_id,$AR_GV['nleft'],$AR_GV['nright'],$C_MONTH['flddFDate'],$C_MONTH['flddTDate']);
			$L_M_GROUP = $this->getSumGroupCollection($member_id,$AR_GV['nleft'],$AR_GV['nright'],$start_date,$X_MONTH['flddTDate']);
			
			$ACU_GROUP_VOL = $C_M_GROUP['total_bal_vol']+$L_M_GROUP['total_bal_vol'];
			
			$L_M_TOTAL_VOL = $L_M_SELF['total_bal_vol']+$L_M_GROUP['total_bal_vol'];
			
			$C_M_TOTAL_VOL = $C_M_SELF['total_bal_vol']+$C_M_GROUP['total_bal_vol'];
			
			$TOTAL_VOL = $ACU_SELF_VOL+$ACU_GROUP_VOL;
					
		
			if($AR_GV['photo']=="" && $AR_GV['gender']=="M"){ 
				$fldvMemPhoto= BASE_PATH."setupimages/photo.jpg"; 
			}elseif($AR_GV['photo']=="" && $AR_GV['gender']=="F"){ 
				$fldvMemPhoto= BASE_PATH."setupimages/female.jpg"; 
			}elseif($AR_GV['photo']!=''){
				$fldvMemPhoto = BASE_PATH."upload/member/".$AR_GV['photo']; 
			}else{
				$fldvMemPhoto = BASE_PATH."setupimages/photo.jpg"; 
			}
			$member_label = ($AR_GV['rank_id']>0)? "User Id":"User Id";
			// rank update date n time on mouseover earlier showing $AR_GV['update_time'] changed to $AR_GV['date_time'] on 18 Aug 2017 (By abhay)
			$Message ="<table class=small_font width=100% border=0 cellspacing=3 cellpadding=1><tr><td width=177 valign=top>FULL NAME:</td><td width=438 valign=top>".$AR_GV['full_name']."</td><td width=684 rowspan=5 align=center valign=middle><img src=".$fldvMemPhoto." width=75 height=92 /></td></tr> <tr><td valign=top >User Id :</td><td valign=top >".$AR_GV['user_id']."</td></tr><tr><td valign=top >Rank:</td><td valign=top >".strtoupper($AR_GV['rank_name'])."</td></tr><tr><td valign=top >ACHIEVED ON:</td><td valign=top >".$AR_GV['date_time']."</td></tr><tr><td valign=top>REGISTER DATE:</td><td valign=top>".DisplayDate($AR_GV['date_join'])."</td></tr><tr><td valign=top>INTRO ID : </td><td valign=top>".$AR_GV['spsr_user_id']."</td></tr><tr><td valign=top>USER COUNT: </td><td colspan=2 valign=top><table width=100% border=1 style=border-collapse:collapse;><tr><td>DIRECT USER </td><td>GROUP USER </td></tr><tr><td>".$direct_count."</td><td>".$total_count."</td></tr></table></td> </tr><tr><td valign=top>SELF BSNS :</td><td colspan=2  valign=top><table width=100% border=1 style=border-collapse:collapse;><tr><td>TILL LAST MONTH </td><td>THIS MONTH </td> <td>ACCUMULATED</td>   </tr><tr><td class=text-red>".number_format($L_M_SELF['total_bal_vol'],2)."</td><td class=text-green>".number_format($C_M_SELF['total_bal_vol'],2)."</td><td class=text-orange>".number_format($ACU_SELF_VOL,2)."</td></tr></table></td></tr><tr><td valign=top>GROUP BSNS :</td><td colspan=2 valign=top><table width=100% border=1 style=border-collapse:collapse;><tr><td>TILL LAST MONTH </td><td>THIS MONTH </td><td>ACCUMULATED</td></tr><tr><td class=text-red>".number_format($L_M_GROUP['total_bal_vol'],2)."</td><td class=text-green>".number_format($C_M_GROUP['total_bal_vol'],2)."</td><td class=text-orange>".number_format($ACU_GROUP_VOL,2)."</td></tr></table></td></tr><tr><td valign=top>TOTAL BSNS :</td><td colspan=2 valign=top><table width=100% border=1 style=border-collapse:collapse;><tr><td>TILL LAST MONTH </td><td>THIS MONTH </td><td>ACCUMULATED</td></tr><tr><td class=text-red>".number_format($L_M_TOTAL_VOL,2)."</td><td class=text-green>".number_format($C_M_TOTAL_VOL,2)."</td><td class=text-orange>".number_format($TOTAL_PV,2)."</td></tr></table></td></tr></table>";
			$AR_IMG = $this->getMemberTree($member_id);
			$ImgSrc = $AR_IMG['IMG_PATH'];
			$CssCls = $AR_IMG['IMG_CLASS'];
			
			echo "<a href='javascript:void(0)'  class='".$AR_IMG_FLVL['IMG_CLASS']."' style='text-decoration:underline;' onMouseover=\"Tip('".$Message."', SHADOW, true, TITLE, 'Statistics', PADDING, 2)\" onclick='moveTree(\""._e($member_id)."\")' >
			 <img src='".$ImgSrc."' width='33' height='39'  border='0'><br>";
               echo $AR_GV['user_id'] . "<br>";
               echo $AR_GV['full_name']. "</a>";
		}else{
			echo '<div class=no-table><table border="0" cellpadding="0" cellspacing="0"><tr><td align="center">';
			if($fldiSpLId!=""){
				echo '<img src="'.BASE_PATH.'setupimages/add_member.gif" border=0>';
				
			}else{ echo '<img src="'.BASE_PATH.'setupimages/add_member.gif" border=0><br><span class="redlink"></span>'; }
			echo '</td></tr></table></div>';
		}
	}
	
	
	function getNameStatusLvl($member_id,$spil_id='',$left_right=''){
		if($member_id != ""){
			$month_ini = new DateTime("first day of last month");
			$today_date = getLocalTime();
			$last_month_date =  $month_ini->format('Y-m-d');
			$AR_GV = $this->getMember($member_id);
			
			
			$start_date = InsertDate($this->getStartOrderDate());
			
			$C_MONTH = getMonthDates($today_date);
			$X_MONTH = getMonthDates($last_month_date);

			$direct_count = $this->BinaryCount($member_id, "DirectCount");
			
			
			$total_count = $this->LevelCount($member_id,"TotalCount");
			
			$AR_SELF = $this->getSumSelfCollection($member_id,"","");
			$self_business = $AR_SELF['total_bal_pv'];
			
			$total_business = $this->LevelCount($member_id,"TotalBusiness");
			
			$total_package = $this->LevelCount($member_id,"TotalPackage");
			
				

			if($AR_GV['photo']=="" && $AR_GV['gender']=="M"){ 
				$fldvMemPhoto= BASE_PATH."setupimages/photo.jpg"; 
			}elseif($AR_GV['photo']=="" && $AR_GV['gender']=="F"){ 
				$fldvMemPhoto= BASE_PATH."setupimages/female.jpg"; 
			}elseif($AR_GV['photo']!=''){
				$fldvMemPhoto = BASE_PATH."upload/member/".$AR_GV['photo']; 
			}else{
				$fldvMemPhoto = BASE_PATH."setupimages/photo.jpg"; 
			}
			$member_label = "User Id";
			// rank update date n time on mouseover earlier showing $AR_GV['update_time'] changed to $AR_GV['date_time'] on 18 Aug 2017 (By abhay)
			$Message ="<table class=small_font width=100% border=0 cellspacing=3 cellpadding=1><tr><td width=177 valign=top>FULL NAME:</td><td width=438 valign=top>".$AR_GV['full_name']."</td><td width=684 rowspan=8 align=center valign=middle><img src=".$fldvMemPhoto." width=75 height=92 /></td></tr> <tr><td valign=top class=text-green>User Id :</td><td valign=top class=text-green>".$AR_GV['user_id']."</td></tr><tr><td valign=top class=text-green>RANK:</td><td valign=top class=text-green>".strtoupper($AR_GV['rank_name'])."</td></tr><tr><td valign=top class=text-green>PACKAGE:</td><td valign=top class=text-green>".$AR_GV['pin_name']."</td></tr><tr><td valign=top>REGISTER DATE:</td><td valign=top>".DisplayDate($AR_GV['date_join'])."</td></tr><tr><td valign=top>INTRO ID : </td><td valign=top>".$AR_GV['spsr_user_id']."</td></tr><tr><td valign=top>DIRECT</td><td valign=top>".$direct_count."</td></tr><tr><td valign=top>PBV</td><td valign=top>".number_format($self_business)." PV</td></tr><tr><td valign=top>MEMBERS :</td><td colspan=2  valign=top>".number_format($total_count)."</td></tr><tr><td valign=top>REPURCHASE :</td><td colspan=2 valign=top>".number_format($total_business)." BV</td></tr><tr><td valign=top>PACKAGE :</td><td colspan=2 valign=top>".number_format($total_package)."</td></tr></table>";
			$AR_IMG = $this->getMemberTree($member_id);
			$ImgSrc = $AR_IMG['IMG_PATH'];
			$CssCls = $AR_IMG['IMG_CLASS'];
			
			echo "<a href='javascript:void(0)'  class='".$AR_IMG_FLVL['IMG_CLASS']."' style='text-decoration:underline;' onMouseover=\"Tip('".$Message."', SHADOW, true, TITLE, 'Statistics', PADDING, 2)\" onclick='moveTree(\""._e($member_id)."\")' >
			 <img src='".$ImgSrc."' width='33' height='39'  border='0'><br>";
               echo $AR_GV['user_id'] . "<br>";
               echo $AR_GV['full_name']. "</a>";
		}else{
			echo '<table border="0" cellpadding="0" cellspacing="0"><tr><td align="center">';
			if($fldiSpLId!=""){
				echo '<img src="'.BASE_PATH.'setupimages/disabled-1.png" border=0>';
				
			}else{ echo '<img src="'.BASE_PATH.'setupimages/disabled-1.png" border=0><br><span class="redlink"></span>'; }
			echo '</td></tr></table>';
		}
	}
	
	function getVisitorCount($from_date,$to_date){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_GET = "SELECT COUNT(*) AS fldiCtrl FROM tbl_visitor WHERE 1 $StrWhr;";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET['fldiCtrl'];
	}
	
	function addVisitor(){
		$visitor_ip = $_SERVER['REMOTE_ADDR'];
		$date_time = InsertDate(getLocalTime());
		
		$QR_GET = "SELECT COUNT(*) AS fldiCtrl FROM tbl_visitor WHERE visitor_ip='".$visitor_ip."' AND DATE(date_time)='".$date_time."';";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		if($AR_GET['fldiCtrl']==0){	
			$data = array("visitor_ip"=>$visitor_ip);
			$this->SqlModel->insertRecord("tbl_visitor",$data);
		}

	}
	
	function getMemberCount($from_date,$to_date){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(date_join) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_GET = "SELECT COUNT(*) AS fldiCtrl FROM tbl_members WHERE delete_sts>0 $StrWhr;";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET['fldiCtrl'];
	}
	
	function getMemberLogin($from_date,$to_date){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(login_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_GET = "SELECT COUNT(member_id) AS fldiCtrl FROM tbl_mem_logs WHERE member_id>0 $StrWhr  ORDER BY login_id ASC";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET['fldiCtrl'];
	}
	

	function getMemberCountRank($rank_id){
		$QR_GET = "SELECT COUNT(*) AS fldiCtrl FROM tbl_members WHERE rank_id='$rank_id' $StrWhr;";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET['fldiCtrl'];
	}
	
	function getRank($rank_id){
		$QR_GET = "SELECT tr.* FROM tbl_rank AS tr WHERE tr.rank_id='".$rank_id."';";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET;
	}
	
	function getSponsorId($user_id){
		$QR_SELECT = "SELECT member_id AS sponsor_id FROM tbl_members WHERE  user_id = '".$user_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function getSponsor($member_id){
		$QR_SELECT = "SELECT sponsor_id FROM tbl_members WHERE  member_id = '".$member_id."'";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT['sponsor_id'];
	}
	
	
	function getSponsorSpill($member_id,$left_right){
		$Q_SPR = "SELECT ta.member_id FROM tbl_members AS ta WHERE ta.member_id='".$member_id."'";
		$R_SPR = $this->db->query($Q_SPR);
		$A_SPR = $R_SPR->row_array();
		if($A_SPR['member_id']>0 && ($left_right=='L' || $left_right=="R")){
			$spil_id = $this->ExtrmLftRgt($A_SPR['member_id'],$left_right);
			$AR_RT['spil_id'] = $spil_id;
			$AR_RT['sponsor_id'] = $A_SPR['member_id'];
			return $AR_RT;
			
		}
	}
	
	function display_downline_direct_member($id_parent,$selectval,$nlevel){
		$QR_LEVEL = "SELECT tm.* FROM  tbl_members AS tm LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id WHERE 
		tm.sponsor_id='".$id_parent."' AND tm.delete_sts>0";
		$RS_LEVEL = $this->db->query($QR_LEVEL);
		$AR_LEVELS = $RS_LEVEL->result_array();
		foreach($AR_LEVELS as $AR_LEVEL):
			$fldiPrntTotalCtrl = $this->BinaryCount($AR_LEVEL['member_id'], "TotalCount");
			$QR_CHILD = "SELECT tm.* FROM  tbl_members AS tm LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id WHERE 
			tm.sponsor_id='".$AR_LEVEL['member_id']."'  AND tm.delete_sts>0";
			$RS_CHILD = $this->db->query($QR_CHILD);
			$AR_CHILDS = $RS_CHILD->result_array();
			echo '<li ><a href="javascript:void(0)">'.$AR_LEVEL['first_name']." ".$AR_LEVEL['last_name'].'
			&nbsp;[&nbsp;'.$fldiPrntTotalCtrl.'&nbsp;]</a>';
			echo '<ul>';
						foreach($AR_CHILDS as $AR_CHILD):
							$fldiChildTotalCtrl = $this->BinaryCount($AR_CHILD['member_id'], "TotalCount");  
							echo '<li >&nbsp;&nbsp;<a href="javascript:void(0)">'.$AR_CHILD['first_name']." ".$AR_CHILD['last_name'].'
										&nbsp;[&nbsp;'.$fldiChildTotalCtrl.'&nbsp;]</a>';
							$this->display_downline_direct_member($AR_CHILD['member_id'],$selectval,$nlevel+1);
							echo '<li>';
							unset($fldiChildTotalCtrl);
						endforeach;
             echo  '</ul>';
			echo '<li>';
			unset($fldiPrntTotalCtrl);
		endforeach;
		
	}
	
	function display_downline_unilevel($id_parent,$selectval,$nlevel=''){
		$QR_LEVEL = "SELECT tm.* FROM  tbl_members AS tm LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id WHERE 
		tm.sponsor_id='".$id_parent."' AND tm.delete_sts>0";
		$RS_LEVEL = $this->db->query($QR_LEVEL);
		$AR_LEVELS = $RS_LEVEL->result_array();
		foreach($AR_LEVELS as $AR_LEVEL):
		
			$fldiPrntTotalCtrl = $this->BinaryCount($AR_LEVEL['member_id'], "TotalCount");
			
			$QR_CHILD = "SELECT ta.* FROM  tbl_members AS ta LEFT JOIN tbl_mem_tree AS tree ON ta.member_id=tree.member_id WHERE 
			ta.sponsor_id='".$AR_LEVEL['member_id']."' AND ta.delete_sts>0";
			$RS_CHILD = $this->db->query($QR_CHILD);
			$AR_CHILDS = $RS_CHILD->result_array();
			echo '<li ><a href="javascript:void(0)">'.$AR_LEVEL['first_name']." ".$AR_LEVEL['last_name'].'
			&nbsp;[&nbsp;'.$fldiPrntTotalCtrl.'&nbsp;]</a>';
			echo '<ul>';
						foreach($AR_CHILDS as $AR_CHILD):
						
							$fldiChildTotalCtrl = $this->BinaryCount($AR_CHILD['member_id'], "TotalCount");  
			
							echo '<li ><a href="javascript:void(0)">'.$AR_CHILD['first_name']." ".$AR_CHILD['last_name'].'
			&nbsp;[&nbsp;'.$fldiChildTotalCtrl.'&nbsp;]</a>';
							$this->display_downline_direct_agent($AR_CHILD['member_id'],$selectval,$nlevel+1);
							echo '<li>';
							unset($fldiChildTotalCtrl);
						endforeach;
             echo  '</ul>';
			echo '<li>';
			unset($fldiPrntTotalCtrl);
		endforeach;
		
	}
	
	function getWalletTrns($trns_type,$member_id='',$where_clause){
		if($trns_type!=''){
			$StrWhr .=($where_clause!='')? " AND $where_clause":"";
			$StrWhr .= ($member_id>0)? " AND member_id='".$member_id."'":"";
			$QR_WALLET = "SELECT SUM(trns_amount) AS total_amount  FROM tbl_wallet_trns WHERE trns_type='".$trns_type."' $StrWhr 
			ORDER BY wallet_trns_id DESC";
			$RS_WALLET = $this->db->query($QR_WALLET);
			$AR_WALLET = $RS_WALLET->row_array();
			return $AR_WALLET['total_amount'];
		}
	}
	
	function getValueConfig($fldvFiled){
		$member_id = $this->session->userdata('mem_id');
		$QR_CONFIG="SELECT value FROM tbl_mem_config WHERE name='".$fldvFiled."' AND member_id='".$member_id."'  LIMIT 1";
		$RS_CONFIG = $this->db->query($QR_CONFIG);
		$AR_CONFIG = $RS_CONFIG->row_array();
		if(is_numeric($AR_CONFIG['value'])){
			$returnVar = ($AR_CONFIG['value']==NULL)? "0":$AR_CONFIG['value'];	
		}else{
			$returnVar = ($AR_CONFIG['value']==NULL)? "":$AR_CONFIG['value'];
		}
		return $returnVar;
	}
	
	function getAllConfig($fldvFiled){	
		$member_id = $this->session->userdata('mem_id');
		$QR_CONFIG="SELECT * FROM tbl_mem_config WHERE name='".$fldvFiled."' AND member_id='".$member_id."' LIMIT 1";
		$RS_CONFIG = $this->db->query($QR_CONFIG);
		$AR_CONFIG = $RS_CONFIG->row_array();
		return $AR_CONFIG;
	}
	
	function updateConfigMember($fldvFields,$whereClause){
		$this->db->query("UPDATE tbl_mem_config SET $fldvFields WHERE $whereClause");
	}
	
	function setConfigMember($fldvFields,$fldvValue){
		$member_id = $this->session->userdata('mem_id');
		$date_upd = $date_upd = getLocalTime();
		$QR_CONFIG="SELECT COUNT(*) AS fldiCtrl FROM tbl_mem_config WHERE member_id='".$member_id."' AND name LIKE '$fldvFields'  LIMIT 1";
		$RS_CONFIG = $this->db->query($QR_CONFIG);
		$AR_CONFIG = $RS_CONFIG->row_array();
		if($AR_CONFIG['fldiCtrl']>0){
			$this->db->query("UPDATE tbl_mem_config SET value='$fldvValue', date_upd='$date_upd' WHERE name='$fldvFields' 
			AND member_id='".$member_id."'");
		}else{
			$this->db->query("INSERT INTO  tbl_mem_config SET value='$fldvValue' , member_id='".$member_id."', 
			name='$fldvFields', date_add='$date_add', 	date_upd='$date_upd'");
		}
	}
	
	function getConfigMember($wherclause){	
		$member_id = $this->session->userdata('mem_id');
		$StrWhr .=($wherclause!="")? "AND $wherclause":"AND id_config<0";
		$QR_CONFIG="SELECT value FROM tbl_mem_config WHERE member_id='".$member_id."'  $StrWhr  LIMIT 1";
		$RS_CONFIG = $this->db->query($QR_CONFIG);
		$AR_CONFIG = $RS_CONFIG->row_array();
		$returnVar = ($AR_CONFIG['value']==NULL)? "0":$AR_CONFIG['value'];
		return $returnVar;
	}
	
	function getAllMemberConfig($member_id){
		$QR_CONFIG="SELECT name,value FROM tbl_mem_config WHERE member_id='".$member_id."'  $StrWhr  LIMIT 1";
		$RS_CONFIG = $this->db->query($QR_CONFIG);
		$AR_CONFIGS = $RS_CONFIG->result_array();
		$AR_RT = array();
		foreach($AR_CONFIGS as $AR_CONFIG){
			$AR_RT[$AR_CONFIG['name']]=$AR_CONFIG['value'];
		}
		return $AR_RT;
	}
	
	
	
	function getCurrentBalance($member_id,$wallet_id,$from_date,$to_date){	
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(trns_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_TRNS_CR = "SELECT SUM(trns_amount) AS total_amount_cr  FROM tbl_wallet_trns WHERE trns_type LIKE 'Cr' AND  member_id='".$member_id."'
		AND wallet_id='".$wallet_id."' $StrWhr ORDER BY wallet_trns_id DESC";
		$AR_TRNS_CR = $this->SqlModel->runQuery($QR_TRNS_CR,true);
		$total_amount_cr = $AR_TRNS_CR['total_amount_cr'];
		
		$QR_TRNS_DR = "SELECT SUM(trns_amount) AS total_amount_dr  FROM tbl_wallet_trns WHERE trns_type LIKE 'Dr' AND  member_id='".$member_id."'
		AND wallet_id='".$wallet_id."'	$StrWhr ORDER BY wallet_trns_id DESC";
		$AR_TRNS_DR = $this->SqlModel->runQuery($QR_TRNS_DR,true);
		$total_amount_dr = $AR_TRNS_DR['total_amount_dr'];
		$net_balance = $total_amount_cr-$total_amount_dr;
		
		$AR_RT['total_amount_cr']=$total_amount_cr;
		$AR_RT['total_amount_dr']=$total_amount_dr;
		$AR_RT['net_balance']=$net_balance;
		return $AR_RT;
	}
	

	
	
	function getWalletBalance($member_id,$trns_for,$from_date,$to_date){	
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(trns_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_TRNS_CR = "SELECT SUM(trns_amount) AS total_amount_cr  FROM tbl_wallet_trns WHERE trns_type LIKE 'Cr' AND  member_id='".$member_id."'
		AND trns_for='".$trns_for."' $StrWhr ORDER BY wallet_trns_id DESC";
		$RS_TRNS_CR = $this->db->query($QR_TRNS_CR);
		$AR_TRNS_CR = $RS_TRNS_CR->row_array();
		$total_amount_cr = $AR_TRNS_CR['total_amount_cr'];
		
		$QR_TRNS_DR = "SELECT SUM(trns_amount) AS total_amount_dr  FROM tbl_wallet_trns WHERE trns_type LIKE 'Dr' AND  member_id='".$member_id."'
		AND trns_for='".$trns_for."'	$StrWhr ORDER BY wallet_trns_id DESC";
		$RS_TRNS_DR = $this->db->query($QR_TRNS_DR);
		$AR_TRNS_DR = $RS_TRNS_DR->row_array();
		$total_amount_dr = $AR_TRNS_DR['total_amount_dr'];
		
		$net_balance = $total_amount_cr-$total_amount_dr;
		
		$AR_RT['total_amount_cr']=$total_amount_cr;
		$AR_RT['total_amount_dr']=$total_amount_dr;
		$AR_RT['net_balance']=$net_balance;
		
		return $AR_RT;
	}
	
	function getPayoutWalletBalance($member_id,$wallet_id,$from_date,$to_date){	
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(twt.trns_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_SELECT = "SELECT 
			   SUM(COALESCE(CASE WHEN twt.trns_type = 'Dr' THEN twt.trns_amount END,0)) total_amount_cr,
			   SUM(COALESCE(CASE WHEN twt.trns_type = 'Cr' THEN twt.trns_amount END,0)) total_amount_dr,
			   SUM(COALESCE(CASE WHEN twt.trns_type = 'Cr' THEN twt.trns_amount END,0))
			   - SUM(COALESCE(CASE WHEN twt.trns_type = 'Dr' THEN twt.trns_amount END,0)) net_balance 
			   FROM tbl_wallet_trns AS twt 
			   WHERE twt.wallet_id='".$wallet_id."' AND twt.member_id='".$member_id."'
			   AND twt.trns_for NOT IN('DPT')
			   $StrWhr
			   ORDER BY twt.wallet_trns_id ASC";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	function getAllWalletBalance($wallet_id,$from_date,$to_date){	
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(twt.trns_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_SELECT = "SELECT 
			   SUM(COALESCE(CASE WHEN twt.trns_type = 'Dr' THEN twt.trns_amount END,0)) total_amount_cr,
			   SUM(COALESCE(CASE WHEN twt.trns_type = 'Cr' THEN twt.trns_amount END,0)) total_amount_dr,
			   SUM(COALESCE(CASE WHEN twt.trns_type = 'Cr' THEN twt.trns_amount END,0))
			   - SUM(COALESCE(CASE WHEN twt.trns_type = 'Dr' THEN twt.trns_amount END,0)) net_balance 
			   FROM tbl_wallet_trns AS twt 
			   WHERE twt.wallet_id='".$wallet_id."'
			   $StrWhr
			   ORDER BY twt.wallet_trns_id ASC";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	function getTotalWalletPaidToOrder($member_id,$trns_for,$trns_remark){	
		$QR_SEL = "SELECT IFNULL(SUM(trns_amount),0) AS  total_amount
			   FROM tbl_wallet_trns AS twt 
			   WHERE twt.member_id='".$member_id."' AND twt.trns_for='".$trns_for."' AND twt.trns_remark LIKE '".$trns_remark."'
			   $StrWhr
			   ORDER BY twt.wallet_trns_id ASC";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL['total_amount'];
	}
	
	function wallet_combo($member_id){
		$QR_SELECT = "SELECT * FROM tbl_wallet WHERE 1 ORDER BY wallet_name ASC"; 
		$rowSets = $this->SqlModel->runQuery($QR_SELECT);
		foreach($rowSets as $rowSet):
			$LDGR = $this->getCurrentBalance($member_id,$rowSet['wallet_id'],"","");
			echo "<option value='".$rowSet['wallet_id']."'";if($SlctVal == $rowSet['wallet_id']){echo "selected";}
			echo ">".$rowSet['wallet_name']."&nbsp;(".$LDGR['net_balance'].")"."</option>";
		endforeach;
	}
	
	function getDepositWallet($wallet_name){
		$QR_GET = "SELECT wallet_id FROM tbl_wallet AS tw WHERE tw.wallet_name LIKE '".$wallet_name."';";
		$RS_GET = $this->db->query($QR_GET);
		$AR_GET = $RS_GET->row_array();
		return $AR_GET['wallet_id'];
	}	
	
	function kycDocument($kyc_id,$file_name){
		$Q_CHK ="SELECT $file_name AS file_name FROM tbl_mem_kyc WHERE kyc_id='$kyc_id';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		if($AR_CHK['file_name']!=''){
			return BASE_PATH."upload/kyc/".$AR_CHK['file_name'];
		}else{
			return  BASE_PATH."setupimages/no-file-found.png";
		}
	}
	
	function getPanCard($pan_id){
		$Q_CHK ="SELECT pan_file AS file_name FROM tbl_mem_pancard WHERE pan_id='$pan_id';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		if($AR_CHK['file_name']!=''){
			return BASE_PATH."upload/pancard/".$AR_CHK['file_name'];
		}else{
			return  BASE_PATH."setupimages/no-file-found.png";
		}
	}
	
	function getKycDetail($member_id){
		if($member_id>0){
			$Q_CHK ="SELECT * FROM tbl_mem_kyc WHERE member_id='".$member_id."';";
			$R_CHK = $this->db->query($Q_CHK);
			$AR_CHK = $R_CHK->row_array();
			return $AR_CHK;
		}
	}
	
	function getKycId($member_id){
		if($member_id>0){
			$Q_CHK ="SELECT kyc_id FROM tbl_mem_kyc WHERE member_id='".$member_id."';";
			$R_CHK = $this->db->query($Q_CHK);
			$AR_CHK = $R_CHK->row_array();
			return $AR_CHK['kyc_id'];
		}
	}
	
	
	function documentLink($document_id){
		$Q_CHK ="SELECT file_name FROM tbl_mem_doc WHERE document_id='".$document_id."';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		
		if($AR_CHK['file_name']!=''){
			$document_link = BASE_PATH."upload/document/".$AR_CHK['file_name'];			
		}else{
			$document_link =  "javascript:void(0)";
		}
		return $document_link;
	}

	function profilePhoto($photo){
		if($photo!=''){ $photo_link = BASE_PATH."upload/member/".$photo; }else{ $photo_link =  "javascript:void(0)";}
		return $photo_link;
	}
	
	function getMemberWithdraw($member_id,$trns_status){
		if($trns_status!=''){
			$StrWhr .=" AND trns_status='$trns_status'";
		}
		$QR_TRNS = "SELECT SUM(initial_amount) AS draw_amount  
					FROM tbl_fund_transfer WHERE 	trns_for LIKE 'WTD' 
					AND  to_member_id='".$member_id."' 
					$StrWhr 
					ORDER BY transfer_id ASC";
		$RS_TRNS = $this->db->query($QR_TRNS);
		$AR_TRNS = $RS_TRNS->row_array();
		$draw_amount = $AR_TRNS['draw_amount'];
		return $draw_amount;
	}
	
	function getTotalWithdraw($from_date,$to_date){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tft.trns_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_TRNS = "SELECT SUM(initial_amount) AS draw_amount  
					FROM tbl_fund_transfer 
					WHERE 	trns_for LIKE 'WTD' 
					AND trns_status='C'
					$StrWhr 
					ORDER BY transfer_id ASC";
		$RS_TRNS = $this->db->query($QR_TRNS);
		$AR_TRNS = $RS_TRNS->row_array();
		$draw_amount = $AR_TRNS['draw_amount'];
		return $draw_amount;
	}
	
	function generatePinDetail($mstr_id){
		$transfer_by = $this->session->userdata('oprt_id');
		if($mstr_id>0){
			$QR_MSTR = "SELECT tpm.*, tpy.pin_name, tpy.pin_letter, tm.user_id 
			FROM tbl_pinsmaster AS tpm 
			LEFT  JOIN tbl_pintype AS tpy ON tpm.type_id=tpy.type_id
			LEFT JOIN tbl_members AS tm ON tpm.member_id=tm.member_id
			LEFT JOIN tbl_franchisee AS tf ON tf.franchisee_id=tpm.franchisee_id
			WHERE tpm.mstr_id='$mstr_id' ORDER BY tpm.mstr_id DESC";
			$RS_MSTR = $this->db->query($QR_MSTR);
			$AR_MSTR = $RS_MSTR->row_array();
			$member_id = $AR_MSTR['member_id'];
			$franchisee_id = $AR_MSTR['franchisee_id'];
			$type_id = $AR_MSTR['type_id'];
			$no_pin = $AR_MSTR['no_pin'];
			$pin_price = $AR_MSTR['pin_price'];
			$prod_pv = $AR_MSTR['prod_pv'];
			$pin_letter = getTool($AR_MSTR['pin_letter'],"ST");
			for($i=1; $i<=$no_pin; $i++):
				$data = array("type_id"=>$type_id,
					"mstr_id"=>$mstr_id,
					"pin_price"=>$pin_price,
					"prod_pv"=>getTool($prod_pv,0),
					"transfer_by"=>$transfer_by,
					"member_id"=>getTool($member_id,0),
					"franchisee_id"=>getTool($franchisee_id,0),
					"pin_no"=>$this->getPinNo(),
					"pin_key"=>$this->getPinKey($pin_letter)
				);
				$this->SqlModel->insertRecord("tbl_pinsdetails",$data);
			endfor;
		}
	}
	
	
	function getPinNo(){
		$data = "123456789";
		for($i = 0; $i < 7; $i++){
			$pin_no .= substr($data, (rand()%(strlen($data))), 1);
		}
		$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_pinsdetails WHERE pin_no='$pin_no';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		if($AR_CHK['fldiCtrl']==0){
			return $pin_no;
		}else{
			return $this->getPinNo();
		}
	}
	
	
	function getPinKey($pin_letter){
		$data = "123456789";
		for($i = 0; $i < 12; $i++){
			$pin_key_no .= substr($data, (rand()%(strlen($data))), 1);
		}
		$pin_key = $pin_letter.$pin_key_no;
		$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_pinsdetails WHERE pin_key='$pin_key';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		if($AR_CHK['fldiCtrl']==0){
			return $pin_key;
		}else{
			return $this->getPinKey($pin_letter);
		}
	}
	
	
	function getOpenPlace($member_id){
		$AR_SPR = $this->getMember($member_id);
		$QR_OPEN = "SELECT treea.member_id, treea.nlevel,  COUNT(treeb.member_id) AS row_ctrl 
					FROM tbl_mem_tree AS treea 
					LEFT  JOIN tbl_mem_tree AS treeb ON treeb.spil_id=treea.member_id 
					WHERE treea.nleft BETWEEN '$AR_SPR[nleft]' AND '$AR_SPR[nright]' 
					GROUP BY treea.member_id HAVING row_ctrl < 2 
					ORDER BY treea.nlevel ASC, treea.date_join ASC, row_ctrl ASC LIMIT 1;";
		$AR_DOWNL = $this->SqlModel->runQuery($QR_OPEN,true);
		$AR_RT['spil_id']=$AR_DOWNL['member_id'];
		return $AR_RT;
		
	}
	
	function sendTransactionSMS($mobile_number){
		$sms_otp = UniqueId("SMS_OTP");
		if(is_numeric($mobile_number)){
			$message = "Hello, ".$sms_otp." is your OTP for change of  transaction password, info: ".DOMAIN."";
			Send_Single_SMS($mobile_number,$message);
		}
		return $sms_otp;
	}
	
	function sendEpinRequestSMS($mobile_number){
		$sms_otp = UniqueId("SMS_OTP");
		if(is_numeric($mobile_number)){
			$message = "Hello, ".$sms_otp." is your OTP for E-pin purchase, info: ".WEBSITE."";
			Send_Single_SMS($mobile_number,$message);
		}
		return $sms_otp;
	}
	
	function sendFundtransferRequestSMS($mobile_number,$amount){
		$sms_otp = UniqueId("SMS_OTP");
		if(is_numeric($mobile_number)){
			$message = "Hello, ".$sms_otp." is your OTP for fund transfer of ".$amount.", info: ".WEBSITE."";
			Send_Single_SMS($mobile_number,$message);
		}
		return $sms_otp;
	}
	
	function sendUpgradeMemberSMS($member_id){
		$AR_MEM = $this->getMember($member_id);
		$mobile_number = $AR_MEM['mobile_number'];
		$sms_otp = UniqueId("SMS_OTP");
		if(is_numeric($mobile_number)){
			$message = "Hi ".$AR_MEM['first_name'].", CONGRATS for becoming a  CONSULTANT (BA) Your User No. is ".$AR_MEM['user_id']." & password is  ".$AR_MEM['user_password'].". Use this to Login at ".DOMAIN."";
			Send_Single_SMS($mobile_number,$message);
		}
		return $sms_otp;
	}
	
	function sendEpinTransferSMS($mobile_number){
		$sms_otp = UniqueId("SMS_OTP");
		if(is_numeric($mobile_number)){
			$message = "Hello, ".$sms_otp." is your OTP for E-pin transfer, info: ".DOMAIN."";
			Send_Single_SMS($mobile_number,$message);
		}
		return $sms_otp;
	}
	
	function upgradeRank($member_id,$from_rank_id,$to_rank_id,$pair_set,$pair_get){
		$data = array("member_id"=>$member_id,
			"from_rank_id"=>getTool($from_rank_id,0),
			"to_rank_id"=>getTool($to_rank_id,0),
			"pair_set"=>getTool($pair_set,0),
			"pair_get"=>getTool($pair_get,0)
		);
		
		if($member_id>0 && $to_rank_id>0){
			if($this->checkHistoryCount($member_id,$from_rank_id,$to_rank_id)==0){
				$this->SqlModel->insertRecord("tbl_mem_rank",$data);
				$this->SqlModel->updateRecord("tbl_members",array("rank_id"=>$to_rank_id),array("member_id"=>$member_id));
			}
		}
		
	}
	
	function checkHistoryCount($member_id,$from_rank_id,$to_rank_id){
		$QR_COUNT = "SELECT COUNT(history_id) AS row_count 
					FROM tbl_mem_rank WHERE member_id='".$member_id."' 
					AND from_rank_id='".$from_rank_id."' AND to_rank_id='".$to_rank_id."'";
		$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
		return $AR_COUNT['row_count'];
	}
	
	function checkCountUpgradeHistory($member_id,$rank_id){
		$QR_COUNT = "SELECT COUNT(tuh.history_id) AS row_ctrl FROM tbl_upgrade_history AS tuh 
					WHERE tuh.member_id='".$member_id."' AND tuh.to_rank_id='".$rank_id."'";
		$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
		return $AR_COUNT['row_ctrl'];
	}
	
	
	function sendOrderConfirmation($mobile_number,$reference_no,$order_amount){
		if(is_numeric($mobile_number)){
			$message = "Thank your plcaing an order!, Your Transaction no ".$reference_no." with total amount of ".$order_amount." placed successfully , info: ".DOMAIN." Thanx for your Order!!";
			$sms_response = Send_Single_SMS($mobile_number,$message,true);
		}
		return $sms_response;
	}
	
	function sendOrderCancel($mobile_number,$order_no,$order_amount){
		$today_date = InsertDate(getLocalTime());
		if(is_numeric($mobile_number)){
			$message = "Order No: ".$order_no." has cancel on ".$today_date.", ".$order_amount." has been refund to account after 5 or 7 working days , info: ".DOMAIN."";
			$sms_response = Send_Single_SMS($mobile_number,$message,true);
		}
		return $sms_response;
	}
	
	function sendEmailSMS($mobile_number){
		$sms_otp = UniqueId("SMS_OTP");
		if(is_numeric($mobile_number)){
			$message = "Hello, ".$sms_otp." is your OTP for change of  email address, info: ".DOMAIN."";
			Send_Single_SMS($mobile_number,$message);
		}
		return $sms_otp;
	}
	
	function verifySMSOTP($request_id,$sms_otp){
		$Q_CHK ="SELECT * FROM tbl_sms_otp WHERE request_id='".$request_id."' AND sms_otp='".$sms_otp."';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		return $AR_CHK;
	}
	
	function getOpt($request_id){
		$Q_CHK ="SELECT * FROM ".prefix."tbl_sms_otp WHERE request_id='".$request_id."';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		return $AR_CHK['sms_otp'];
	}
	
	function getSMSOTP($request_id){
		$Q_CHK ="SELECT * FROM tbl_sms_otp WHERE request_id='".$request_id."';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		return $AR_CHK;
	}
	
	function getMobileCode($country_code){
		$QR_SELECT = "SELECT phonecode FROM tbl_country WHERE country_code = '$country_code'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['phonecode'];
	}
	
	function getDailyReturn($type_id,$date_time){
		$QR_SELECT = "SELECT daily_return FROM tbl_daily_return WHERE type_id='".$type_id."' AND DATE(date_time)='".$date_time."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return ($AR_SELECT['daily_return']>0)? $AR_SELECT['daily_return']:0;
	}
	
	function getDailyReturnType($type_id){
		$QR_SELECT = "SELECT daily_return FROM tbl_daily_return WHERE type_id='".$type_id."'  ORDER BY daily_id DESC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return ($AR_SELECT['daily_return']>0)? $AR_SELECT['daily_return']:0;
	}
	
	function getFundTransfer($transfer_id){
		$QR_TRNS = "SELECT tft.* FROM tbl_fund_transfer AS tft  WHERE tft.transfer_id='".$transfer_id."'";
		$RS_TRNS = $this->db->query($QR_TRNS);
		$AR_TRNS = $RS_TRNS->row_array();
		return $AR_TRNS;
	}
	
	function wallet_transaction($wallet_id,$member_id,$trns_type,$trns_amount,$trns_remark,$trns_date,$trans_no='',$AR_VAL=''){
		$trans_no_new = UniqueId("TRNS_NO");
		$trns_for = $AR_VAL['trns_for'];
		$trans_ref_no = $AR_VAL['trans_ref_no'];
		$data = array("wallet_id"=>getTool($wallet_id,1),
			"trns_type"=>$trns_type,
			"member_id"=>getTool($member_id,0),
			"trns_amount"=>$trns_amount,
			"trns_remark"=>getTool($trns_remark,''),
			"trans_no"=>getTool($trans_no,$trans_no_new),
			"trans_ref_no"=>getTool($trans_ref_no,$trans_no_new),
			"trns_for"=>getTool($trns_for,'NA'),
			"trns_date"=>$trns_date
		);
		if($trns_amount>0 && $member_id>0 ){
			$this->SqlModel->insertRecord("tbl_wallet_trns",$data);
		}
	}
	
	
	
	function getPointCtrl($member_id){
		$QR_CTRL = "SELECT COUNT(tmp.point_id) AS row_ctrl 
					FROM tbl_mem_point  AS tmp
					WHERE  tmp.member_id='$member_id' AND tmp.point_type='Cr'
					AND tmp.point_sub_type LIKE 'ORD'";
		$AR_CTRL = $this->SqlModel->runQuery($QR_CTRL,1);
		return $AR_CTRL['row_ctrl'];
	}
		
	function point_transaction($member_id,$point_type,$point_sub_type,$point_pv,$point_bv,$point_vol,$point_rcp,$point_ref,$date_time){
		$point_ctrl = $this->getPointCtrl($member_id);
		if($member_id>0 && $point_vol>0){
			$data_set = array("member_id"=>$member_id,
				"point_type"=>$point_type,
				"point_sub_type"=>$point_sub_type,
				"point_pv"=>getTool($point_pv,0),
				"point_bv"=>getTool($point_bv,0),
				"point_vol"=>getTool($point_vol,0),
				"point_rcp"=>getTool($point_rcp,0),
				"point_ref"=>$point_ref,
				"point_ctrl"=>getTool($point_ctrl,1),
				"date_time"=>$date_time
			);	
			$this->SqlModel->insertRecord("tbl_mem_point",$data_set);
		}
	}
	
	function checkBatchNoProduct($AR_ORDR){
		$today_date = InsertDate(getLocalTime());
		$fran_id = $AR_ORDR['franchisee_id'];
		$franchisee_id = getTool($fran_id,$this->getDefaultFranchisee());
		$order_no = $AR_ORDR['order_no'];
		$order_id = $AR_ORDR['order_id'];
		if($AR_ORDR['order_id']>0 && $AR_ORDR['stock_sts']==0){
			$QR_TRNS = "SELECT tod.* FROM tbl_order_detail  AS tod 
			WHERE tod.order_id='".$order_id."' ORDER BY tod.order_detail_id ASC";
			$RS_TRNS = $this->SqlModel->runQuery($QR_TRNS);
			$Ctrl = 0;
			foreach($RS_TRNS as $AR_TRNS):
				$post_id = $AR_TRNS['post_id'];
				$batch_no = $this->getBatchNoFranchise($post_id,$franchisee_id);
				if( $batch_no=="" || $batch_no=="0" ){
					set_message("warning","Unable to generate your invoice , '".$AR_TRNS['post_title']."' product not available");
					redirect_page("order","orderview",array("order_id"=>_e($order_id)));
				}
			endforeach;
			
		}
	}
	
	function checkQtyFranchise($form_data){
		$post_id_all = array_unique(array_filter($form_data['post_id']));
		foreach($post_id_all as  $key=>$post_id):
			$post_qty = FCrtRplc($form_data['post_qty'][$key]);
			$available_qty = FCrtRplc($form_data['available_qty'][$key]);
			$post_title = FCrtRplc($form_data['post_title'][$key]);
			if($post_qty>$available_qty){
				set_message("warning","Unable to place order, please check available quantity of '".$post_title."'");
				redirect_franchise("order","placeorder",array("order_id"=>_e($order_id)));
			}
		endforeach;
	}
	
	
	function checkQtyInvoice($form_data){
		$post_id_all = array_filter($form_data['post_id']);
		foreach($post_id_all as  $key=>$post_id):
			$post_qty = FCrtRplc($form_data['post_qty'][$key]);
			$available_qty = FCrtRplc($form_data['available_qty'][$key]);
			$post_title = FCrtRplc($form_data['post_title'][$key]);
			if($post_qty>$available_qty){
				set_message("warning","Unable to place order, please check available quantity of '".$post_title."'");
				redirect_page("stock","stockentry",array("order_id"=>_e($order_id)));
			}
		endforeach;
	}
	
	function updateStockQty($AR_ORDR){
		$today_date = InsertDate(getLocalTime());
		$fran_id = $AR_ORDR['franchisee_id'];
		$franchisee_id = getTool($fran_id,$this->getDefaultFranchisee());
		$order_no = $AR_ORDR['order_no'];
		$order_id = $AR_ORDR['order_id'];
		$date_add = InsertDate($AR_ORDR['date_add']);
		if($AR_ORDR['order_id']>0 && $AR_ORDR['stock_sts']==0){
			$QR_TRNS = "SELECT tod.* FROM tbl_order_detail  AS tod 
			WHERE tod.order_id='".$order_id."' ORDER BY tod.order_detail_id ASC";
			$RS_TRNS = $this->SqlModel->runQuery($QR_TRNS);
			$Ctrl = 0;
			foreach($RS_TRNS as $AR_TRNS):
				$trans_no = UniqueId("STOCK_TRNS_NO");
				$order_detail_id = $AR_TRNS['order_detail_id'];
				$order_id = $AR_TRNS['order_id'];
				$post_id = $AR_TRNS['post_id'];
				$post_attribute_id = $AR_TRNS['post_attribute_id'];
				$post_price = $AR_TRNS['post_price'];
				$post_mrp = $AR_TRNS['original_post_price'];
				$post_qty = $AR_TRNS['post_qty'];
				$net_amount = $AR_TRNS['net_amount'];
				$post_pv = $AR_TRNS['post_pv'];
				$tax_age = $AR_TRNS['tax_age'];
				$batch_no = $this->getBatchNoFranchise($post_id,$franchisee_id);
			
				
				$this->InsertStockLedger($franchisee_id,$trans_no,"Dr","CF",$post_id,$post_attribute_id,$post_price,$tax_age,$post_qty,$net_amount,$order_no,$date_add,
				$batch_no,$post_mrp,$post_pv);
				
	
				$Ctrl++; 
				$this->SqlModel->updateRecord("tbl_order_detail",array("batch_no"=>$batch_no),array("order_detail_id"=>$order_detail_id));
				unset($trans_no,$order_id,$post_id,$post_qty,$net_amount,$AR_POST);
			endforeach;
			if($Ctrl>0){
				$this->SqlModel->updateRecord("tbl_orders",array("stock_sts"=>"1"),array("order_id"=>$AR_ORDR['order_id']));
			}
		}
	}
	
	function checkStockQty($AR_ORDR){
		
		$order_id = $AR_ORDR['order_id'];
		$fran_id = $AR_ORDR['franchisee_id'];
		if($AR_ORDR['stock_sts']==0){
		   $QR_TRNS = "SELECT tod.* FROM tbl_order_detail  AS tod 
			WHERE tod.order_id='".$order_id."' ORDER BY tod.order_detail_id ASC";
			$RS_TRNS = $this->SqlModel->runQuery($QR_TRNS);
			foreach($RS_TRNS as $AR_TRNS):
				 $Ctrl = $this->checkAvailableQty($AR_TRNS['post_id'],$fran_id,$AR_TRNS['post_qty']);
				 if($Ctrl>0){
				 	set_message("warning","Unable to generate your invoice , '".$AR_TRNS['post_title']."' product not available");
					redirect_page("shop","orderview",array("order_id"=>_e($order_id)));
				}
			endforeach;
		}
	}
	
	function checkAvailableQty($post_id,$franchisee_id,$qty){
		$QR_BAL = " SELECT tsl.post_id, 
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) total_qty_dr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0)) total_qty_cr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0))
		   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) net_balance 
		   FROM tbl_stock_ledger AS tsl 
		   WHERE tsl.post_id='".$post_id."' AND tsl.franchisee_id='".$franchisee_id."' AND tsl.stock_type LIKE 'NRML'
		   $StrWhr
		   GROUP BY tsl.post_id
		   ORDER BY tsl.trans_id DESC";
		$AR_BAL  = $this->SqlModel->runQuery($QR_BAL,true);	
		$available = $AR_BAL['net_balance'];
		return ($available>=$qty)? 0:1;
	}
	
	function InsertStockLedger($franchisee_id,$trans_no,$trans_type,$sub_type,$post_id,$post_attribute_id,$trans_amount,$tax_age,$trans_qty,$net_amount,$ref_no,$trans_date,
	$batch_no='',$stock_mrp='',$stock_pv=''){
		if($net_amount>=0){
			$AR_PD = $this->getPostDetail($post_id);
			$post_mrp = getTool($stock_mrp,$AR_PD['post_mrp']);
			$post_pv = getTool($stock_pv,$AR_PD['post_pv']);
			$data = array("franchisee_id"=>$franchisee_id,
				"trans_no"=>$trans_no,
				"trans_type"=>$trans_type,
				"sub_type"=>$sub_type,
				"post_id"=>$post_id,
				"post_attribute_id"=>getTool($post_attribute_id,0),
				"post_mrp"=>$post_mrp,
				"post_pv"=>$post_pv,
				"tax_age"=>$tax_age,
				"trans_amount"=>$trans_amount,
				"trans_qty"=>$trans_qty,
				"net_amount"=>$net_amount,
				"ref_no"=>$ref_no,
				"batch_no"=>($batch_no)? $batch_no:" ",
				"trans_date"=>$trans_date
			);
			$this->SqlModel->insertRecord("tbl_stock_ledger",$data);
		}
	}
	
	function WarehouseMaster($order_no,$product_count,$total_amount,$order_type,$sub_type,$oprt_id,$date_time,$batch_no=''){
		if($order_no!=''){
			$data = array("order_no"=>$order_no,
				"product_count"=>$product_count,
				"total_amount"=>($total_amount>0)? $total_amount:0,
				"order_type"=>$order_type,
				"sub_type"=>$sub_type,
				"oprt_id"=>$oprt_id,
				"batch_no"=>($batch_no)? $batch_no:" ",
				"date_time"=>$date_time
			);
			return $this->SqlModel->insertRecord("tbl_warehouse",$data);
		}
		
	}
	
	function WarehouseTransaction($warehouse_id,$post_id,$post_attribute_id,$supplier_id,$trans_type,$sub_type,$name,$price,$tax_age,$qty,$total,$mfg_date='',$exp_date='',$ref_no='',$batch_no,$trans_date=''){
		if($post_id>0  && $qty>0){
			$trans_no = UniqueId("WARE_TRNS_NO");
			$data = array("warehouse_id"=>$warehouse_id,
				"trans_no"=>$trans_no,
				"post_id"=>$post_id,
				"post_attribute_id"=>getTool($post_attribute_id,0),
				"supplier_id"=>$supplier_id,
				"trans_type"=>$trans_type,
				"sub_type"=>$sub_type,
				"name"=>getTool($name,''),
				"tax_age"=>getTool($tax_age,0),
				"price"=>$price,
				"qty"=>$qty,
				"total"=>$total,
				"mfg_date"=>($mfg_date)? $mfg_date:"0000-00-00",
				"exp_date"=>($exp_date)? $exp_date:"0000-00-00",
				"ref_no"=>$ref_no,
				"batch_no"=>($batch_no)? $batch_no:" ",
				"trans_date"=>$trans_date
			);
			$wh_trns_id = $this->SqlModel->insertRecord("tbl_warehouse_trns",$data);
			return $wh_trns_id;
		}
	}
	
	
	function getStockBalance($post_id,$post_attribute_id,$franchisee_id='',$from_date='',$to_date=''){	
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tsl.trans_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($franchisee_id>0){
			$StrWhr .=" AND tsl.franchisee_id='".$franchisee_id."'";
		}
		
		if($post_attribute_id>0){
			$StrWhr .=" AND tsl.post_attribute_id='".$post_attribute_id."'";
		}
		
		$QR_BAL = " SELECT tsl.post_id, tsl.post_attribute_id, tpl.post_title , tpl.post_price, 
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) total_qty_dr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0)) total_qty_cr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0))
		   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) net_balance 
		   FROM tbl_stock_ledger AS tsl 
		   LEFT JOIN tbl_post AS tp ON tp.post_id=tsl.post_id
		   LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
		   WHERE tsl.post_id='".$post_id."'  
		   $StrWhr
		   GROUP BY tsl.post_id
		   ORDER BY tsl.trans_id DESC";
		
		$AR_BAL  = $this->SqlModel->runQuery($QR_BAL,true);		
		return $AR_BAL;
	}

	function DateWiseClosingStock($franchisee_id,$for_date){	
		$QR_PRV = "SELECT 
				   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0))
				   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) net_balance 
				   FROM tbl_stock_ledger AS tsl 
				   WHERE tsl.franchisee_id='".$franchisee_id."' AND DATE(tsl.trans_date)<'".InsertDate($for_date)."'
				   GROUP BY tsl.franchisee_id";
		$AR_PRV = $this->SqlModel->runQuery($QR_PRV,true);	
		$QR_CUR = "SELECT 
				   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0))
				   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) net_balance 
				   FROM tbl_stock_ledger AS tsl 
				   WHERE tsl.franchisee_id='".$franchisee_id."' AND DATE(tsl.trans_date)='".InsertDate($for_date)."' 
				   GROUP BY tsl.franchisee_id";
		$AR_CUR = $this->SqlModel->runQuery($QR_CUR,true);
		$NT_BAL = ($AR_PRV['net_balance']+$AR_CUR['net_balance']); 
		return $NT_BAL;
	}

	function DateWiseStockValue($franchisee_id,$for_date){	
		$QR_PRV = "SELECT 
				   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.net_amount END,0))
				   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.net_amount END,0)) net_amount 
				   FROM tbl_stock_ledger AS tsl 
				   WHERE tsl.franchisee_id='".$franchisee_id."' AND DATE(tsl.trans_date)<'".InsertDate($for_date)."'
				   GROUP BY tsl.franchisee_id";
		$AR_PRV = $this->SqlModel->runQuery($QR_PRV,true);	
		$QR_CUR = "SELECT 
				   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.net_amount END,0))
				   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.net_amount END,0)) net_amount 
				   FROM tbl_stock_ledger AS tsl 
				   WHERE tsl.franchisee_id='".$franchisee_id."' AND DATE(tsl.trans_date)='".InsertDate($for_date)."' 
				   GROUP BY tsl.franchisee_id";
		$AR_CUR = $this->SqlModel->runQuery($QR_CUR,true);
		$NT_VAL = ($AR_PRV['net_amount']+$AR_CUR['net_amount']);
		return $NT_VAL;
	}
	
	function getStockOpening($post_id,$post_attribute_id,$franchisee_id='',$to_date=''){
		if($to_date!=''){
			$StrWhr .=" AND DATE(tsl.trans_date) < '".$to_date."'";
		}
		
		if($franchisee_id>0){
			$StrWhr .=" AND tsl.franchisee_id='".$franchisee_id."'";
		}		
		
		$QR_BAL = " SELECT tsl.post_id, tsl.post_attribute_id,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) total_qty_dr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0)) total_qty_cr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0))
		   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) net_balance 
		   FROM tbl_stock_ledger AS tsl 
		   LEFT JOIN tbl_post AS tp ON tp.post_id=tsl.post_id
		   WHERE tsl.post_id='".$post_id."' AND tsl.post_attribute_id='".$post_attribute_id."'
		   $StrWhr
		   GROUP BY tsl.post_id
		   ORDER BY tsl.trans_id DESC";
		$AR_BAL  = $this->SqlModel->runQuery($QR_BAL,true);	
		return $AR_BAL;
		
	}
	
	
	
	
	function getTotalStockBalance($franchisee_id,$from_date='',$to_date=''){	
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tsl.trans_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		
		$QR_BAL = " SELECT tsl.franchisee_id, 
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) total_qty_dr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tpl.post_price*tsl.trans_qty END,0)) total_rcp_dr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0)) total_qty_cr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tpl.post_price*tsl.trans_qty END,0)) total_rcp_cr,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0))
		   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) net_qty,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tpl.post_price*tsl.trans_qty END,0))
		   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tpl.post_price*tsl.trans_qty END,0)) net_rcp
		   FROM tbl_stock_ledger AS tsl 
		   LEFT JOIN tbl_post AS tp ON tp.post_id=tsl.post_id
		   LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
		   WHERE tsl.franchisee_id='".$franchisee_id."'
		   $StrWhr
		   ORDER BY tsl.trans_id DESC";
		
		$AR_BAL  = $this->SqlModel->runQuery($QR_BAL,true);		
		return $AR_BAL;
	}
	
	function getCompanyStock($post_id,$post_attribute_id,$supplier_id='',$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(twt.date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($supplier_id>0){
			$StrWhr .=" AND twt.supplier_id='".$supplier_id."'";
		}
		
		$QR_BAL = "SELECT twt.post_id, twt.post_attribute_id, 
					   SUM(COALESCE(CASE WHEN twt.trans_type = 'Dr' THEN twt.qty END,0)) total_debits,
					   SUM(COALESCE(CASE WHEN twt.trans_type = 'Cr' THEN twt.qty END,0)) total_credits,
					   SUM(COALESCE(CASE WHEN twt.trans_type = 'Cr' THEN twt.qty END,0))
					   - SUM(COALESCE(CASE WHEN twt.trans_type = 'Dr' THEN twt.qty END,0)) balance 
					   FROM tbl_warehouse_trns AS twt
					   WHERE twt.post_id='".$post_id."' AND twt.post_attribute_id='".$post_attribute_id."'
					   $StrWhr
					   GROUP BY twt.post_id
					   ORDER BY twt.wh_trns_id ASC";
		$AR_BAL  = $this->SqlModel->runQuery($QR_BAL,true);
		return $AR_BAL;
	}
	
	
	function getCompanyOpeningStock($post_id,$post_attribute_id,$supplier_id='',$to_date=''){
		if($to_date!=''){
			$StrWhr .=" AND DATE(twt.date_time) < '".InsertDate($to_date)."'";
		}else{
			$StrWhr .=" AND DATE(twt.date_time) < '".InsertDate(getLocalTime())."'";
		}
		if($supplier_id>0){
			$StrWhr .=" AND twt.supplier_id='".$supplier_id."'";
		}
		
		$QR_BAL = "SELECT twt.post_id, twt.post_attribute_id, 
					   SUM(COALESCE(CASE WHEN twt.trans_type = 'Dr' THEN twt.qty END,0)) total_debits,
					   SUM(COALESCE(CASE WHEN twt.trans_type = 'Cr' THEN twt.qty END,0)) total_credits,
					   SUM(COALESCE(CASE WHEN twt.trans_type = 'Cr' THEN twt.qty END,0))
					   - SUM(COALESCE(CASE WHEN twt.trans_type = 'Dr' THEN twt.qty END,0)) balance 
					   FROM tbl_warehouse_trns AS twt
					   WHERE twt.post_id='".$post_id."' AND twt.post_attribute_id='".$post_attribute_id."'
					   $StrWhr
					   GROUP BY twt.post_id
					   ORDER BY twt.wh_trns_id ASC";
		$AR_BAL  = $this->SqlModel->runQuery($QR_BAL,true);
		return $AR_BAL;
	}
	
	function getFranchiseStock($post_id,$post_attribute_id,$franchisee_id,$supplier_id='',$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tsl.trans_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($supplier_id>0){
			$StrWhr .=" AND tsl.supplier_id='".$supplier_id."'";
		}
		
		$QR_BAL  = $QR_MEM = "SELECT tsl.post_id, tsl.post_attribute_id,
					   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) total_debits,
					   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0)) total_credits,
					   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0))
					   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) balance 
					   FROM tbl_stock_ledger AS tsl
					   WHERE tsl.post_id='".$post_id."' AND tsl.post_attribute_id='".$post_attribute_id."'
					   AND tsl.franchisee_id='".$franchisee_id."'
					   $StrWhr
					   GROUP BY tsl.post_id
					   HAVING balance >= 0
					   ORDER BY tsl.trans_id ASC";
		$AR_BAL  = $this->SqlModel->runQuery($QR_BAL,true);
		return $AR_BAL;
	}
	
	
	
	function countCurrentMonthLeaderShip($month_wise,$mode_wise){
		$QR_SEL = "SELECT COUNT(month_wise) AS row_ctrl  
				   FROM tbl_setup_mode_wise WHERE month_wise='$month_wise' AND mode_wise='$mode_wise'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL['row_ctrl'];
	}
	
	function getDefaultBatch($post_id){
		$QR_SEL = "SELECT CONCAT_WS('',111,tp.post_code) AS batch_no FROM tbl_post AS tp WHERE tp.post_id='".$post_id."'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return getTool($AR_SEL['batch_no'],111001);
	}
	
	function getBatchNoProduct($post_id){
	   $QR_BALANCE = "SELECT twt.batch_no,
		   SUM(COALESCE(CASE WHEN twt.trans_type = 'Dr' THEN twt.qty END,0)) total_debits,
		   SUM(COALESCE(CASE WHEN twt.trans_type = 'Cr' THEN twt.qty END,0)) total_credits,
		   SUM(COALESCE(CASE WHEN twt.trans_type = 'Cr' THEN twt.qty END,0))
		   - SUM(COALESCE(CASE WHEN twt.trans_type = 'Dr' THEN twt.qty END,0)) balance 
		   FROM tbl_warehouse_trns AS twt
		   WHERE twt.post_id='".$post_id."'
		   $StrWhr
		   GROUP BY twt.batch_no
		   HAVING balance > 0
		   ORDER BY twt.wh_trns_id ASC LIMIT 1";
		$AR_BAL  = $this->SqlModel->runQuery($QR_BALANCE,true);	
		return $AR_BAL['batch_no'];
	}
	
	function getBatchNoFranchise($post_id,$franchisee_id){
	   $QR_BAL = "SELECT tsl.batch_no,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) total_debits,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0)) total_credits,
		   SUM(COALESCE(CASE WHEN tsl.trans_type = 'Cr' THEN tsl.trans_qty END,0))
		   - SUM(COALESCE(CASE WHEN tsl.trans_type = 'Dr' THEN tsl.trans_qty END,0)) balance 
		   FROM tbl_stock_ledger AS tsl
		   WHERE tsl.post_id='".$post_id."' AND tsl.franchisee_id='".$franchisee_id."' AND tsl.stock_type LIKE 'NRML'
		   $StrWhr
		   GROUP BY tsl.batch_no
		   HAVING balance > 0
		   ORDER BY tsl.trans_id ASC LIMIT 1";
		$AR_BAL  = $this->SqlModel->runQuery($QR_BAL,true);	
		return $AR_BAL['batch_no'];
	}
	
	function getStockBalanceType($post_id,$sub_type,$franchisee_id='',$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(trans_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($franchisee_id>0){
			$StrWhr .=" AND franchisee_id='".$franchisee_id."'";
		}
		$QR_LDGR  = "SELECT SUM(trans_qty) AS total_qty  
						FROM tbl_stock_ledger 
						WHERE  post_id='".$post_id."'
						$StrWhr 
						AND sub_type='".$sub_type."'
						ORDER BY trans_id DESC";
		$AR_LDGR = $this->SqlModel->runQuery($QR_LDGR,1);
		return $AR_LDGR['total_qty'];
	}
	
	function getSalesReport($post_id,$post_attribute_id,$franchisee_id='',$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tod.date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($franchisee_id>0){
			$StrWhr .=" AND toa.franchisee_id='".$franchisee_id."'";
		}
		$QR_SALE  = "SELECT SUM(tod.post_qty) AS total_qty  
						FROM tbl_order_detail  AS tod
						LEFT JOIN tbl_orders AS toa ON toa.order_id=tod.order_id
						WHERE  tod.post_id='".$post_id."' AND tod.post_attribute_id='".$post_attribute_id."'
						$StrWhr 
						ORDER BY tod.order_detail_id ASC";
		$AR_SALE = $this->SqlModel->runQuery($QR_SALE,true);
		$TOTAl_SALE = $AR_SALE['total_qty'];
		return $TOTAl_SALE;
	}
	
	function getTotalSale($from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(ord.invoice_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_SELECT = "SELECT SUM(ord.total_paid) AS total_paid
			 		  FROM tbl_orders AS ord
			 		  WHERE ord.order_id>0 AND ord.stock_sts>0 AND ord.invoice_number<>'' 
			 		  $StrWhr
			 		  ORDER BY ord.order_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['total_paid'];
	}
	
	function getTotalSubscription($from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(ts.date_from) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_SELECT = "SELECT SUM(ts.prod_pv) AS total_amount
			 		  FROM tbl_subscription AS ts
			 		  WHERE ts.subcription_id>0 
			 		  $StrWhr
			 		  ORDER BY ts.subcription_id ASC";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT['total_amount'];
	}
	
	function getSelfSubscription($member_id,$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(ts.date_from) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_SELECT = "SELECT SUM(ts.pin_price) AS total_amount
			 		  FROM tbl_subscription AS ts
			 		  WHERE ts.subcription_id>0  AND ts.member_id='$member_id'
			 		  $StrWhr
			 		  ORDER BY ts.subcription_id ASC";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT['total_amount'];
	}
	
	
	function getOrderNo(){
		$data = "123456789";
		for($i = 0; $i < 5; $i++){
			$number .= substr($data, (rand()%(strlen($data))), 1);
		}
		$order_no = "TKORD".$number;
		$Q_CHK ="SELECT COUNT(order_id) AS row_ctrl FROM tbl_orders WHERE order_no='$order_no';";
		$AR_CHK = $this->SqlModel->runQuery($Q_CHK,true);
		if($AR_CHK['row_ctrl']==0){
			return $order_no;
		}else{
			return $this->getOrderNo();
		}
	}
	


	function franchiseOrderNo($franchisee_id){
		$QR_ORDR = "SELECT CONCAT_WS( '', 'TK' , 'BRH' ) AS order_no
					FROM tbl_franchisee AS tf WHERE tf.franchisee_id='".$franchisee_id."' ORDER BY tf.franchisee_id DESC";
		$AR_ORDR = $this->SqlModel->runQuery($QR_ORDR,true);
		$order_no = $AR_ORDR['order_no'];
		
		$QR_CTRL = "SELECT LPAD(MAX(tod.order_id)+1,5,0) AS control_no FROM tbl_orders AS tod 
		WHERE  tod.franchisee_id='$franchisee_id' 
		ORDER BY tod.order_id DESC LIMIT 1";
		$AR_CTRL = $this->SqlModel->runQuery($QR_CTRL,true);
		$control_no = $AR_CTRL['control_no'];
		
		$invoice_no = ($control_no=='')? $order_no."00001":$order_no."".$control_no;
		return $invoice_no;
		
		
	}
	
	function warehouseOrderNo(){
		$QR_ORDR = "SELECT CONCAT_WS( '', 'TK' ,  'WHR') AS 	order_no
					FROM tbl_warehouse_trns AS twt";
		$AR_ORDR = $this->SqlModel->runQuery($QR_ORDR,true);
		$order_no = $AR_ORDR['order_no'];
		
		$QR_CTRL = "SELECT LPAD(MAX(twt.wh_trns_id)+1,5,0) AS control_no FROM tbl_warehouse_trns AS twt WHERE  
		1 ORDER BY twt.wh_trns_id DESC LIMIT 1";
		$AR_CTRL = $this->SqlModel->runQuery($QR_CTRL,true);
		$control_no = $AR_CTRL['control_no'];
		
		$invoice_no = ($AR_ORDR['order_no']=='')? $order_no."00001":$order_no."".$control_no;
		return $invoice_no;
		
		
	}
	
	function stockOrderNo(){
		$QR_ORDR = "SELECT CONCAT_WS( '', 'TK' ,  'STK') AS 	order_no
					FROM tbl_stock_master AS tsm";
		$AR_ORDR = $this->SqlModel->runQuery($QR_ORDR,true);
		$order_no = $AR_ORDR['order_no'];
		
		$QR_CTRL = "SELECT LPAD(MAX(tsm.stock_id)+1,5,0) AS control_no FROM tbl_stock_master AS tsm WHERE  
		1 ORDER BY tsm.stock_id DESC LIMIT 1";
		$AR_CTRL = $this->SqlModel->runQuery($QR_CTRL,true);
		$control_no = $AR_CTRL['control_no'];
		
		$invoice_no = ($AR_ORDR['order_no']=='')? $order_no."00001":$order_no."".$control_no;
		return $invoice_no;
		
		
	}

	function stockrevOrderNo(){
		$QR_CTRL = "SELECT LPAD(COUNT(*)+1,5,0) AS control_no FROM tbl_stock_ledger WHERE ref_no LIKE '%RSTK%'";
		$AR_CTRL = $this->SqlModel->runQuery($QR_CTRL,true);
		$control_no = $AR_CTRL['control_no'];
		$invoice_no = "ESRSTK".$control_no;
		return $invoice_no;
	}
	
	function demoOrderNo(){
		$QR_ORDR = "SELECT CONCAT_WS( '', 'TK' ,  'DMO') AS 	order_no
					FROM tbl_demo_stock AS tds";
		$AR_ORDR = $this->SqlModel->runQuery($QR_ORDR,true);
		$order_no = $AR_ORDR['order_no'];
		
		$QR_CTRL = "SELECT LPAD(MAX(tds.demo_id)+1,5,0) AS control_no FROM tbl_demo_stock AS tds WHERE  
		1 ORDER BY tds.demo_id DESC LIMIT 1";
		$AR_CTRL = $this->SqlModel->runQuery($QR_CTRL,true);
		$control_no = $AR_CTRL['control_no'];
		
		$invoice_no = ($AR_ORDR['order_no']=='')? $order_no."00001":$order_no."".$control_no;
		return $invoice_no;
		
		
	}
	
	function getInvoiceNo($ctrl=''){
		$increment = ($ctrl>0)? $ctrl:0;
		$order_no = "TKINV";
		
		$order_ctrl = $this->checkCountPro("tbl_orders","order_id>0 AND invoice_number!=''");
		
		$QR_CTRL = "SELECT LPAD(COUNT(tod.order_id)+$increment,5,0) AS control_no FROM tbl_orders AS tod WHERE tod.invoice_number!=''
		ORDER BY tod.order_id ASC";
		$AR_CTRL = $this->SqlModel->runQuery($QR_CTRL,true);
		$control_no = $AR_CTRL['control_no'];
		
		$invoice_no = ($order_ctrl=='0')? $order_no.""."00001":$order_no."".$control_no;
		if($this->checkCount("tbl_orders","invoice_number",$invoice_no)==0){
			return $invoice_no;
		}else{
			return $this->getInvoiceNo($increment+1);
		}
	}

	
	function getLastBinaryCmsn($member_id){
		$QR_SELECT = "SELECT SUM(amount)  AS amount FROM tbl_cmsn_binary WHERE member_id='".$member_id."' ORDER BY binary_id DESC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['amount'];
	}
	
	function getTotalBinaryCmsn($member_id){
		$QR_SELECT = "SELECT SUM(amount)  AS amount FROM tbl_cmsn_binary WHERE member_id='".$member_id."' ORDER BY binary_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['amount'];
	}
	
	
	function getTotalBinaryByDate($member_id,$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($member_id>0){
			$StrWhr .=" AND member_id='".$member_id."'";
		}
		
		$QR_SEL = "SELECT SUM(amount)  AS amount FROM tbl_cmsn_binary WHERE 1 $StrWhr ORDER BY binary_id ASC";
		$RS_SEL = $this->db->query($QR_SEL);
		$AR_SEL = $RS_SEL->row_array();
		return $AR_SEL['amount'];
	}
	
	function getTotalDirectByDate($member_id,$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($member_id>0){
			$StrWhr .=" AND member_id='".$member_id."'";
		}
		$QR_SEL = "SELECT SUM(total_income)  AS net_income FROM tbl_cmsn_direct WHERE 1 $StrWhr ORDER BY direct_id ASC";
		$RS_SEL = $this->db->query($QR_SEL);
		$AR_SEL = $RS_SEL->row_array();
		return $AR_SEL['net_income'];
	}
	
	function getTotalLevelByDate($member_id,$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(cmsn_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($member_id>0){
			$StrWhr .=" AND member_id='".$member_id."'";
		}
		$QR_SEL = "SELECT SUM(total_income)  AS net_income FROM tbl_cmsn_lvl_benefit_mstr WHERE 1 $StrWhr";
		$RS_SEL = $this->db->query($QR_SEL);
		$AR_SEL = $RS_SEL->row_array();
		return $AR_SEL['net_income'];
	}
	
	function getTotalDirectCmsn($member_id){
		$QR_SELECT = "SELECT SUM(net_income)  AS net_income FROM tbl_cmsn_direct WHERE member_id='".$member_id."' ORDER BY direct_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['net_income'];
	}
	
	
	function getTotalLevelCmsn($member_id){
		$QR_SELECT = "SELECT SUM(net_income)  AS net_income FROM tbl_cmsn_lvl_benefit_mstr WHERE member_id='".$member_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['net_income'];
	}
	
	function getTotalRoyalty($date_from,$date_end,$royalty_type){
		$QR_SELECT = "SELECT SUM(royalty_point)  AS  total_point 
					  FROM tbl_cmsn_royalty 
					  WHERE   DATE(date_from)='".$date_from."' AND DATE(date_end)='".$date_end."'
					  AND royalty_type='".$royalty_type."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['total_point'];
	}
	
	function getTotalRoyaltyCmsn($member_id,$royalty_type){
		$QR_SELECT = "SELECT SUM(royalty_point)  AS royalty_point FROM tbl_cmsn_royalty WHERE member_id='".$member_id."' AND royalty_type='$royalty_type'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['royalty_point'];
	}
	
	
	
	
	function setSubscriptionPost($subcription_id,$type_id){
		$QR_SEL = "SELECT post_id FROM tbl_pin_post WHERE type_id='$type_id' ORDER BY post_id";
		$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
		foreach($RS_SEL as $AR_SEL):
			$post_id = $AR_SEL['post_id'];
			if($post_id>0 && $subcription_id>0){
				$this->SqlModel->insertRecord("tbl_subscription_post",array("subcription_id"=>$subcription_id,"post_id"=>$post_id));
			}
		endforeach;
	}
	
	function getActivationDate($member_id){
		$QR_PLAN = "SELECT  ts.date_from
					FROM  tbl_subscription AS ts 
					WHERE  ts.member_id='".$member_id."'
					ORDER BY ts.subcription_id ASC LIMIT 1";
		$AR_PLAN = $this->SqlModel->runQuery($QR_PLAN,true);
		return $AR_PLAN['date_from'];
	}
	
	function getCurrentMemberShip($member_id){
		$QR_PLAN = "SELECT  ts.* , tp.pin_name, tp.pin_letter
					FROM  tbl_subscription AS ts 
					LEFT JOIN tbl_pintype AS tp ON tp.type_id=ts.type_id
					WHERE  ts.member_id='".$member_id."'";
		$AR_PLAN = $this->SqlModel->runQuery($QR_PLAN,true);
		return $AR_PLAN;
	}
	
	function getCurrentMemberShipFormat($member_id){
		$QR_PLAN = "SELECT  ts.subcription_id,  tp.pin_letter
					FROM  tbl_subscription AS ts 
					LEFT JOIN tbl_pintype AS tp ON tp.type_id=ts.type_id
					WHERE  ts.member_id='".$member_id."' ORDER BY ts.subcription_id DESC LIMIT 1";
		$AR_PLAN = $this->SqlModel->runQuery($QR_PLAN,true);
		return $AR_PLAN;
	}
	
	function getLastProcess(){
		$QR_SELECT = "SELECT * FROM tbl_process WHERE pair_sts='N' ORDER BY process_id DESC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	
	function getProductCount(){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM tbl_post WHERE delete_sts>0  ORDER BY post_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function getCategoryCount(){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM tbl_category WHERE 1 ORDER BY category_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function  getCountCityMember($city_name=''){
		$StrWhr .= ($city_name!='')? " AND LOWER(city_name) LIKE '".strtolower($city_name)."'":"";
		$QR_SELECT = "SELECT COUNT(member_id) AS ctrl_count 
					FROM tbl_members WHERE city_name!='' 
					$StrWhr
					ORDER BY member_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function getCountCityRegister(){
		$QR_SELECT = "SELECT COUNT(DISTINCT city_name) AS ctrl_count 
					FROM tbl_members WHERE city_name!='' 
					ORDER BY member_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function getCountStateRegister(){
		$QR_SELECT = "SELECT COUNT(DISTINCT state_name) AS ctrl_count 
					 FROM tbl_members WHERE state_name!='' 
					 ORDER BY member_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	
	function postView($post_id,$member_id){
		$server_ip = $_SERVER['REMOTE_ADDR'];
		if($post_id>0){
			$data = array("post_id"=>$post_id,
					"member_id"=>($member_id>0)? $member_id:0,
					"server_ip"=>$server_ip);
			$this->SqlModel->insertRecord("tbl_post_view",$data);
		}
	}
	
	
	
	function getPostDetail($post_id){
		if($post_id>0){
			$QR_SELECT = "SELECT tp.*,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.post_selling_mrp,
			tpl.post_price, tpl.post_shipping,  tpl.update_date , tpl.short_desc, tpl.post_hsn, tpl.post_pv, tpl.post_bv, tpl.post_size, tpl.post_tax, 
			tpl.tax_age, tpl.post_slug, tpl.post_selling, tpl.post_cmsn, tc.category_id, tc.category_name, tc.category_slug
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id 
			LEFT JOIN tbl_category AS tc ON tc.category_id=tpc.category_id 
			WHERE tp.post_id='".$post_id."'";
			$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
			return $AR_SELECT;
		}
	}
	
	function getPostAttrPrice($post_attribute_id,$post_id=''){
		if($post_id>0){
			$StrWhr .=" AND tpa.post_id='".$post_id."'";
		}		
		$QR_SEL = "SELECT tpa.* FROM tbl_post_attribute AS tpa WHERE tpa.post_attribute_id='".$post_attribute_id."' $StrWhr";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL;
	}

	
	function getReviewCtrl($post_id){
		$QR_SELECT = "SELECT COUNT(review_id) AS ctrl_count FROM tbl_post_review WHERE post_id = '$post_id'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function getCategoryDetail($category_id){
		if($category_id>0){
			$QR_SELECT = "SELECT tc.* 
					FROM tbl_category AS tc
					WHERE tc.category_id='".$category_id."'";
			$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
			return $AR_SELECT;
		}
	}
	
	function bootstrap_category($category_id){
		$category_id_array = array_filter(array_unique(explode(",",$category_id)));
		echo '<ul class="list-inline">';
		foreach($category_id_array as $key=>$category_id):
			$QR_QUERY = "SELECT category_name FROM tbl_category WHERE delete_sts>0 AND category_id='".$category_id."'";
			$AR_RT = $this->SqlModel->runQuery($QR_QUERY,true);
			echo '<li class="list-group-item-success">'.$AR_RT['category_name'].'</li>';
		endforeach;
		echo '</ul>';
	}
	
	
	
	function getFileSrc($field_id,$is_full=''){
		$fileSrc = "javascript:void(0)";
		if($field_id>0){
			$QR_FILE = "SELECT tpf.file_name,tpf.file_name_thumb,tpf.file_type,tpf.file_size 
						FROM tbl_post_file AS tpf 
						WHERE field_id='".$field_id."' AND tpf.delete_sts>0
						ORDER BY tpf.field_id ASC LIMIT 1";
			$AR_FILE = $this->SqlModel->runQuery($QR_FILE,true);
			
			$file_name = ($is_full>0)? $AR_FILE['file_name']:$AR_FILE['file_name_thumb'];
			if($is_full>0){
				$image_path = BASE_PATH."upload/post/".$file_name;
			}else{
				$image_path = BASE_PATH."upload/post/thumb/".$file_name;
			}
			
			if($file_name==""){ 
				$image_path = BASE_PATH."setupimages/no-image-available.png";
			}
			return $image_path;
		}else{
			return BASE_PATH."setupimages/no-image-available.png";
		}
	}
	
	function getPinFileSrc($pin_img_id,$is_full=''){
		$fileSrc = "javascript:void(0)";
		if($pin_img_id>0){
			$QR_FILE = "SELECT tpi.file_name,tpi.file_name_thumb,tpi.file_type,tpi.file_size 
						FROM tbl_pin_image AS tpi 
						WHERE tpi.pin_img_id='".$pin_img_id."' AND tpi.delete_sts>0
						ORDER BY tpi.pin_img_id ASC LIMIT 1";
			$AR_FILE = $this->SqlModel->runQuery($QR_FILE,true);
			
			$file_name = ($is_full>0)? $AR_FILE['file_name']:$AR_FILE['file_name_thumb'];
			if($is_full>0){
				$image_path = BASE_PATH."upload/pin/".$file_name;
			}else{
				$image_path = BASE_PATH."upload/pin/thumb/".$file_name;
			}
			
			if($file_name==""){ 
				$image_path = BASE_PATH."setupimages/no-image-available.png";
			}
			return $image_path;
		}else{
			return BASE_PATH."setupimages/no-image-available.png";
		}
	}
	
	function getDefaultPinImage($type_id,$is_full=''){
		$image_path = "javascript:void(0)";
		if($type_id>0){
			$QR_FILE = "SELECT tpi.file_name,tpi.file_name_thumb,tpi.file_type,tpi.file_size 
						FROM tbl_pin_image AS tpi 
						WHERE tpi.type_id='".$type_id."' AND tpi.delete_sts>0
						AND tpi.file_name!=''
						ORDER BY tpi.cover_sts DESC LIMIT 1";
			$AR_FILE = $this->SqlModel->runQuery($QR_FILE,true);
			$file_name = ($is_full>0)? $AR_FILE['file_name']:$AR_FILE['file_name_thumb'];
			
			if($is_full>0){
				$image_path = BASE_PATH."upload/pin/".$file_name;
			}else{
				$image_path = BASE_PATH."upload/pin/thumb/".$file_name;
			}
			
			if($file_name=="") { 
				$image_path = BASE_PATH."setupimages/no-image-available.png";
			}
		}
		return $image_path;
	}
	
	function getDefaultPhoto($post_id,$is_full=''){
		$image_path = "javascript:void(0)";
		if($post_id>0){
			$QR_FILE = "SELECT tpf.file_name,tpf.file_name_thumb,tpf.file_type,tpf.file_size 
						FROM tbl_post_file AS tpf 
						WHERE post_id='".$post_id."' AND delete_sts>0
						AND file_name!=''
						ORDER BY tpf.cover_sts DESC LIMIT 1";
			$AR_FILE = $this->SqlModel->runQuery($QR_FILE,true);
			$file_name = ($is_full>0)? $AR_FILE['file_name']:$AR_FILE['file_name_thumb'];
			
			if($is_full>0){
				$image_path = BASE_PATH."upload/post/".$file_name;
			}else{
				$image_path = BASE_PATH."upload/post/thumb/".$file_name;
			}
			
			if($file_name=="") { 
				$image_path = BASE_PATH."setupimages/no-image-available.png";
			}
		}
		return $image_path;
	}
	
	
	function getPostFile($post_id){
		$QR_FILE = "SELECT tpf.field_id,  tpf.file_name,tpf.file_name_thumb,tpf.file_type,tpf.file_size 
					FROM tbl_post_file AS tpf 
					WHERE post_id='".$post_id."' AND delete_sts>0
					AND file_name!=''
					ORDER BY tpf.cover_sts DESC LIMIT 1";
		$AR_FILE = $this->SqlModel->runQuery($QR_FILE,true);
		return $AR_FILE;
	}
	
	
	
	function getCategoryImgSrc($category_id){
			$QR_SEL = "SELECT tc.category_id, tc.category_img FROM tbl_category AS tc WHERE tc.category_id='".$category_id."'";
			$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
			$image_path = BASE_PATH."upload/category/".$AR_SEL['category_img'];
			#$fldvImageArr= @getimagesize($image_path);
			
			if($AR_SEL['category_img']=="") { 
				$image_path = BASE_PATH."setupimages/no-image-available.png";
			}
			return $image_path;
	}
	
	function setCategoryOption($category_id,$option_arr){
		if($category_id>0){
			if(count(array_filter(array_unique($option_arr)))>0){
				foreach($option_arr as $option_title=>$option_value):
					if($this->checkCountPro("tbl_category_option","category_id='$category_id' AND option_title='$option_title'")==0){
						$data_set = array("category_id"=>$category_id,
							"option_title"=>getTool($option_title,''),
							"option_value"=>getTool($option_value,'')
						);
						$this->SqlModel->insertRecord("tbl_category_option",$data_set);
					}else{
						$data_set = array("option_value"=>getTool($option_value,''));
						$this->SqlModel->updateRecord("tbl_category_option",$data_set,array("category_id"=>$category_id,"option_title"=>$option_title));
					}
				endforeach;
			}
		}
	}
	
	function getCategoryOption($category_id){
		$AR_RT = array();
		if($category_id>0){
			$QR_SEL = "SELECT tco.* FROM tbl_category_option AS tco WHERE tco.category_id='".$category_id."'";
			$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
			foreach($RS_SEL as $AR_SEL):
				$AR_RT[$AR_SEL['option_title']]=strip_tags($AR_SEL['option_value']);
			endforeach;
		}
		return $AR_RT;
	}
	
	function uploadProfileAvtar($FILE,$AR_DT,$fldvPath){
			$NewImageWidth      = "250"; 
			$NewImageHeight     = "50"; 
			$Quality        = 99; 
		
			$member_id = $AR_DT['member_id'];
			if($FILE['avatar_name']['error']=="0"){
				$ext = explode(".",$FILE['avatar_name']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$photo = $fldvUniqueNo."_POST"."_". str_replace(" ","",$member_id.".".$fExtn);
				$target_path = $fldvPath."upload/member/".$photo;
				
				$AR_MEM = SelectTable("tbl_members","photo","member_id='$member_id'");
				
				$final_location = $fldvPath."upload/member/".$AR_MEM['photo'];
				$fldvImageArr= @getimagesize($final_location);
				
				if ($AR_MEM['photo']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(resizeImage($FILE['avatar_name']['tmp_name'],$target_path,$NewImageWidth,$NewImageHeight,$Quality))  {
						$this->SqlModel->updateRecord("tbl_members",array("photo"=>$photo),array("member_id"=>$member_id));
					}
			}
	}
	
	function updateMemberPancard($member_id,$AR_DT,$FILE){
		$fldvPath = "";
		$pan_no  = $AR_DT['document_id'];
		$ext = explode(".",$FILE['file_passport']["name"]);
		$fExtn = strtolower(end($ext));
		$fldvUniqueNo = UniqueId("UNIQUE_NO");
		$file_name = ($pan_no)? $pan_no:$fldvUniqueNo;
		$pan_file = $file_name."_pan_". str_replace(" ","",$member_id.".".$fExtn);
		$target_path = $fldvPath."upload/kyc/".$pan_file;
		
		$AR_PAN = SelectTable("tbl_mem_pancard","pan_file","member_id='$member_id'");
		$final_location = $fldvPath."upload/kyc/".$AR_PAN['pan_file'];
		$fldvImageArr= @getimagesize($final_location);
		if ($AR_PAN['pan_file']!="") { @chmod($final_location,0777);	@unlink($final_location); }
		if(move_uploaded_file($FILE['file_passport']['tmp_name'], $target_path)){
			$data = array("member_id"=>$member_id,
				"pan_file"=>$pan_file,
				"pan_no"=>($pan_no)? $pan_no:" ",
				"approve_sts"=>0,
			);
			if($this->checkCount("tbl_mem_pancard","member_id",$member_id)>0){
				$this->SqlModel->updateRecord("tbl_mem_pancard",$data,array("member_id"=>$member_id));
			}else{
				$this->SqlModel->insertRecord("tbl_mem_pancard",$data);
			}
		}
		

	}
	
	function uploadCategoryImg($FILE,$AR_DT,$fldvPath){		
		$category_id = $AR_DT['category_id'];
			if($FILE['category_img']['error']=="0"){
				$ext = explode(".",$FILE['category_img']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$category_img = $fldvUniqueNo."_CAT"."_". str_replace(" ","",$category_id.".".$fExtn);
				$target_path = $fldvPath."upload/category/".$category_img;
				$target_path_thumb = $fldvPath."upload/category/thumb/".$category_img;
				
				$AR_CAT = SelectTable("tbl_category","category_img","category_id='$category_id'");
				$final_location = $fldvPath."upload/category/".$AR_CAT['category_img'];
				$fldvImageArr= @getimagesize($final_location);
				if ($AR_CAT['category_img']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(resizeImage($FILE['category_img']['tmp_name'],$target_path,"600","500","99"))  {
						$this->SqlModel->updateRecord("tbl_category",array("category_img"=>$category_img),array("category_id"=>$category_id));
						if(resizeImage($FILE['category_img']['tmp_name'],$target_path_thumb,"300","200","99"))  {
							$this->SqlModel->updateRecord("tbl_category",array("category_img_thumb"=>$category_img),array("category_id"=>$category_id));
						}
					}
			}
	}
	
	
	function uploadPostFile($FILES,$AR_DT,$upload_path){
		$post_id = $AR_DT['post_id'];
		foreach($FILES['post_image']['tmp_name'] as $key=>$upload_value):
			$upload_name = $FILES['post_image']['name'][$key];
			$upload_temp = $FILES['post_image']['tmp_name'][$key];
			$upload_type = $FILES['post_image']['type'][$key];
			$upload_error = $FILES['post_image']['error'][$key];
			$upload_size = $FILES['post_image']['size'][$key];
			if($upload_error=="0" && $post_id>0){
				$ext = explode(".",$upload_name);
				$fext = strtolower(end($ext));
				$unique_no = UniqueId("UNIQUE_NO");
				$rand_no = rand(100,999);
				$file_name = $unique_no.$rand_no."_". str_replace(" ","",$post_id.".".$fext);
				$file_name_thumb = $unique_no.$rand_no."_". str_replace(" ","",$post_id."-t.".$fext);
				
				$target_path = $upload_path."upload/post/".$file_name;
				$target_path_thumb = $upload_path."upload/post/thumb/".$file_name_thumb;
				
				if(move_uploaded_file($upload_temp, $target_path)){
					resizeImage($target_path,$target_path,"800","800","99");
					resizeImage($target_path,$target_path_thumb,"500","500","99");
					
					$data_file = array("post_id"=>$post_id,
						"file_name"=>$file_name,
						"file_name_thumb"=>$file_name_thumb,
						"file_type"=>$upload_type,
						"file_size"=>$upload_size
					 );
					 
					$this->SqlModel->insertRecord("tbl_post_file",$data_file);						
				}	
			}
		endforeach;	
	}
	
	function uploadPinFile($FILES,$AR_DT,$upload_path){
		$type_id = $AR_DT['type_id'];
		foreach($FILES['pin_image']['tmp_name'] as $key=>$upload_value):
			$upload_name = $FILES['pin_image']['name'][$key];
			$upload_temp = $FILES['pin_image']['tmp_name'][$key];
			$upload_type = $FILES['pin_image']['type'][$key];
			$upload_error = $FILES['pin_image']['error'][$key];
			$upload_size = $FILES['pin_image']['size'][$key];
			if($upload_error=="0" && $type_id>0){
				$ext = explode(".",$upload_name);
				$fext = strtolower(end($ext));
				$unique_no = UniqueId("UNIQUE_NO");
				$rand_no = rand(100,999);
				$file_name = $unique_no."_pin_". str_replace(" ","",$type_id.".".$fext);
				$file_name_thumb = $unique_no."_pin_". str_replace(" ","",$type_id."-t.".$fext);
				
				$target_path = $upload_path."upload/pin/".$file_name;
				$target_path_thumb = $upload_path."upload/pin/thumb/".$file_name_thumb;
				
				if(move_uploaded_file($upload_temp, $target_path)){
					resizeImage($target_path,$target_path,"800","600","99");
					resizeImage($target_path,$target_path_thumb,"250","200","99");
					
					$data_file = array("type_id"=>$type_id,
						"file_name"=>$file_name,
						"file_name_thumb"=>$file_name_thumb,
						"file_type"=>$upload_type,
						"file_size"=>$upload_size
					 );
					$this->SqlModel->insertRecord("tbl_pin_image",$data_file);						
				}	
			}
		endforeach;	
	}
	
	
	function copyImg($source_file,$fldvPath){
			$fldvUniqueNO = UniqueId("UNIQUE_NO");
			$save_file = "cfile_".$fldvUniqueNO.".jpg";
			$data = file_get_contents($source_file);
			$fileName = $fldvPath."upload/offer/".$save_file;
			$file = fopen($fileName, 'w+');
			fputs($file, $data);
			fclose($file);
			return $save_file;
	}
	
	function uploadCoverImage($FILE,$AR_DT,$fldvPath){
			$NewImageWidth      = "250"; 
			$NewImageHeight     = "50"; 
			$Quality        = 99; 
			
			$gallery_id = $AR_DT['gallery_id'];
			if($FILE['cover_image']['error']=="0"){
				$ext = explode(".",$FILE['cover_image']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$cover_image = $fldvUniqueNo."_POST"."_". str_replace(" ","",$gallery_id.".".$fExtn);
				$target_path = $fldvPath."upload/album/".$cover_image;
				
				$AR_GLY = SelectTable("tbl_gallery","cover_image","gallery_id='$gallery_id'");
				
				$final_location = $fldvPath."upload/album/".$AR_GLY['cover_image'];
				$fldvImageArr= @getimagesize($final_location);
				
				    if ($AR_GLY['cover_image']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(resizeImage($FILE['cover_image']['tmp_name'],$target_path,$NewImageWidth,$NewImageHeight,$Quality))  {
						$this->SqlModel->updateRecord("tbl_gallery",array("cover_image"=>$cover_image),array("gallery_id"=>$gallery_id));
					}
		  }

	}
	
	function getAlbumImg($gallery_id){
		if($gallery_id>0){
			$QR_FILE = "SELECT tg.*
						FROM tbl_gallery AS tg 
						WHERE tg.gallery_id='".$gallery_id."'";
			$AR_FILE = $this->SqlModel->runQuery($QR_FILE,true);
			$IMG_SRC = BASE_PATH."upload/album/".$AR_FILE['cover_image'];
			#$fldvImageArr= @getimagesize($IMG_SRC);
			if($AR_FILE['cover_image']=="") { 
				$IMG_SRC = BASE_PATH."setupimages/no-image-available.png";
			}
			return $IMG_SRC;
		}
	}
	
	function getPriceCmsn($post_selling_mrp,$post_discount){
		$post_cmsn_price = 0;
		if($post_selling_mrp>0){
			$QR_SEL = "SELECT tspp.* 
					  FROM tbl_setup_product_price  AS tspp
					  WHERE tspp.product_price_ratio>0
					  AND tspp.product_price_from<='".$post_selling_mrp."' AND tspp.product_price_to>='".$post_selling_mrp."'
					  ORDER BY tspp.product_price_id DESC LIMIT 1";
			$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
			$product_price_ratio = $AR_SEL['product_price_ratio'];
			$post_cmsn = $post_selling_mrp*$product_price_ratio/100;	
		}
		return $post_cmsn;
	}
	
	
	function addUpdateLang($post_id,$lang_id,$form_data){
		$today_date = getLocalTime();
		if($post_id>0 && $lang_id>0){
			$QR_CTRL  = "SELECT COUNT(*) AS fldiCtrl FROM tbl_post_lang WHERE post_id='".$post_id."' AND lang_id='".$lang_id."'";
			$AR_CTRL = $this->SqlModel->runQuery($QR_CTRL,true);
			
			
			$post_dp_price = FCrtRplc($form_data['post_dp_price']);
			$post_pv = FCrtRplc($form_data['post_pv']);
			$post_bv = FCrtRplc($form_data['post_bv']);
			
			$post_hsn = FCrtRplc($form_data['post_hsn']);
			
			$post_size = FCrtRplc($form_data['post_size']);
			$tax_age = FCrtRplc($form_data['tax_age']);
			$post_tax = ( $post_price * 100) / ( $tax_age+100 );
			
			$range_offer = FCrtRplc($form_data['range_offer']);
			$post_slug = gen_slug($form_data['post_title']);
			
			$post_mrp = FCrtRplc($form_data['post_mrp']);
			$post_discount = FCrtRplc($form_data['post_discount']);
			$post_price = ( $post_mrp - $post_discount );
			
			$post_shipping = FCrtRplc($form_data['post_shipping']);
			
			$data_lang = array("post_id"=>$post_id,
				"lang_id"=>$lang_id,
				"post_title"=>FCrtRplc($form_data['post_title']),
				"post_tags"=>FCrtRplc($form_data['post_tags']),
				"post_slug"=>$post_slug,
				"short_desc"=>FCrtRplc($form_data['short_desc']),
				"post_desc"=>FCrtRplc($form_data['post_desc']),
				"post_size"=>getTool($post_size,""),
				"post_hsn"=>getTool($post_hsn,""),
				"tax_age"=>getTool($tax_age,0),
				"post_tax"=>getTool($post_tax,0),
				
				"range_offer"=>getTool($range_offer,0),
				"post_dp_price"=>getTool($post_dp_price,0),
				"post_pv"=>getTool($post_pv,0),
				"post_bv"=>getTool($post_bv,0),
				
				"post_selling_mrp"=>getTool($post_selling_mrp,0),
				"post_mrp"=>getTool($post_mrp,0),
				"post_discount"=>getTool($post_discount,0),
				"post_selling"=>getTool($post_selling,0),
				"post_cmsn"=>getTool($post_cmsn,0),
				"post_price"=>getTool($post_price,0),
				"post_shipping"=>$post_shipping,
				"update_date"=>$today_date
			);
			
			if($AR_CTRL['fldiCtrl']>0){
				$this->SqlModel->updateRecord("tbl_post_lang",$data_lang,array("post_id"=>$post_id,"lang_id"=>$lang_id));
			}else{
				$this->SqlModel->insertRecord("tbl_post_lang",$data_lang);
			}
		}
	}
	
	function setPostCategory($post_id,$category_id_array){
		if(is_array($category_id_array)){
			$this->SqlModel->deleteRecord("tbl_post_category",array("post_id"=>$post_id));
			foreach($category_id_array as $key=>$category_id):
				$this->SqlModel->insertRecord("tbl_post_category",array("post_id"=>$post_id,"category_id"=>$category_id));
			endforeach;
		}
	}
	
	function uploadProfileAvtarAgent($FILE,$AR_DT,$fldvPath){
			$NewImageWidth      = "250"; 
			$NewImageHeight     = "50"; 
			$Quality        = 99; 
		
			$member_id = $AR_DT['member_id'];
			if($FILE['avatar_name']['error']=="0"){
				$ext = explode(".",$FILE['avatar_name']["name"]);
				$fExtn = strtolower(end($ext));
				$fldvUniqueNo = UniqueId("UNIQUE_NO");
				$photo = $fldvUniqueNo."_POST"."_". str_replace(" ","",$member_id.".".$fExtn);
				$target_path = $fldvPath."upload/agent/".$photo;
				
				$AR_GEN = SelectTable("tbl_members","photo","member_id='".$member_id."'");
				
				$final_location = $fldvPath."upload/agent/".$AR_GEN['photo'];
				$fldvImageArr= @getimagesize($final_location);
				
				if ($AR_GEN['photo']!="") { @chmod($final_location,0777);	@unlink($final_location); }
					if(resizeImage($FILE['avatar_name']['tmp_name'],$target_path,$NewImageWidth,$NewImageHeight,$Quality))  {
						$this->SqlModel->updateRecord("tbl_members",array("photo"=>$photo),array("member_id"=>$member_id));
					}
			}
	}
	
	function checkPostCodeExist($post_code){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM tbl_post WHERE post_code LIKE '".$post_code."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	
	function checkRefCodeExist($post_ref){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM tbl_post WHERE post_ref LIKE '".$post_ref."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function getFranchisee($franchisee_id){
		$QR_SELECT = "SELECT name FROM tbl_franchisee WHERE franchisee_id='".$franchisee_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['name'];
	}
	
	function getFranchisePostPrice($franchisee_id,$post_price,$post_pv){
		$AR_FRAN = $this->getFranchiseeDetail($franchisee_id);
		$discount_ratio = $AR_FRAN['discount_ratio'];
		
		$total_discount = $post_pv*$discount_ratio/100;
		return ($post_price>0)? $post_price-$total_discount:$post_price;
	}
	
	function getFranchiseDiffrentPostPrice($franchisee_id_from,$franchisee_id_to,$post_price,$post_pv){
		
		$AR_FROM = $this->getFranchiseeDetail($franchisee_id_from);
		$AR_TO  = $this->getFranchiseeDetail($franchisee_id_to);
		
		$discount_ratio_diff = $AR_FROM['discount_ratio']-$AR_TO['discount_ratio'];
		$total_discount = $post_pv*$discount_ratio_diff/100;
		
		return ($post_price>0)? $post_price-$total_discount:$post_price;
		
		
	}
	
	function getFranchiseeDetail($franchisee_id){
		$QR_SELECT = "SELECT tf.*, tsf.franchisee_type ,  tsf.discount_ratio, 
		tfb.bank_holder, tfb.bank_name, tfb.bank_account, tfb.bank_branch, tfb.bank_ifc, tfb.bank_doc
		FROM tbl_franchisee AS tf 
		LEFT JOIN tbl_setup_franchisee AS tsf ON tsf.fran_setup_id=tf.fran_setup_id
		LEFT JOIN tbl_franchisee_bank AS tfb ON tfb.franchisee_id=tf.franchisee_id
		WHERE tf.franchisee_id='".$franchisee_id."'";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	function getDefaultFranchiseSetupId(){
		$QR_SELECT = "SELECT fran_setup_id FROM tbl_setup_franchisee ORDER BY fran_setup_id ASC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['fran_setup_id'];
	}
	
	function getFranchiseeId($user_name){
		$QR_SELECT = "SELECT franchisee_id FROM tbl_franchisee WHERE user_name='".$user_name."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['franchisee_id'];
	}
	
	function getFranchiseeUsername($franchisee_id){
		$QR_SELECT = "SELECT user_name FROM tbl_franchisee WHERE franchisee_id='".$franchisee_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['user_name'];
	}
	
	
	function updateCarrierCharge($carrier_id,$form_data){
		if($carrier_id>0){
			$carrier_charge_array = array_filter($form_data['carrier_charge']);
			$this->Sqlmodel->deleteRecord("tbl_carrier_range",array("carrier_id"=>$carrier_id));
			foreach($carrier_charge_array as $key=>$carrier_charge):
				$city_name = $key;
				if($carrier_charge>0){
					 $QR_PIN = "SELECT tp.* FROM tbl_pincode AS tp WHERE tp.pincode_id>0 AND tp.city_name LIKE '$city_name' ORDER BY tp.city_name ASC";
                   	 $RS_PIN = $this->SqlModel->runQuery($QR_PIN);
					 foreach($RS_PIN as $AR_PIN):
					 		$data_set = array("carrier_id"=>$carrier_id,
								"pin_code"=>$pin_code,
								"carrier_charge"=>$carrier_charge
							);
							$this->Sqlmodel->insertRecord("tbl_carrier_range",$data_set);
					 endforeach;
				}
			endforeach;
		}
	}
	
	
	function getSelfCollection($member_id,$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tmp.date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_SELECT = "SELECT 
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_vol END,0)) total_dr_vol,
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_dr_pv,
			   
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_vol END,0)) total_cr_vol,
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0)) total_cr_pv,
			   
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_vol END,0))
			   - SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_vol END,0)) total_bal_vol ,
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0))
			   - SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_bal_pv 
			   FROM tbl_mem_point AS tmp 
			   WHERE tmp.member_id='".$member_id."'
			   $StrWhr
			   ORDER BY tmp.point_id ASC";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}

	function getGroupCollection($nleft,$nright,$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tmp.date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_SELECT = "SELECT 
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_vol END,0)) total_dr_vol,
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_dr_pv,
					 
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_vol END,0)) total_cr_vol,
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0)) total_cr_pv,
					 
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_vol END,0))
					 - SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_vol END,0)) total_bal_vol,
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0))
					 - SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_bal_pv
					 FROM tbl_mem_point AS tmp 
					 LEFT JOIN tbl_members AS tm ON tm.member_id = tmp.member_id
					 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					 WHERE  tmp.point_id>0
					 $StrWhr
					 AND tree.nleft BETWEEN '".$nleft."' AND '".$nright."'
					 ORDER BY tmp.point_id ASC";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	function getGroupRoyaltyCollection($nleft,$nright,$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tcd.date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		$QR_SELECT = "SELECT 
					 SUM(royalty_forward) AS total_point
					 FROM tbl_cmsn_direct AS tcd 
					 LEFT JOIN tbl_members AS tm ON tm.member_id = tcd.from_member_id
					 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					 WHERE  tcd.royalty_forward>0
					 $StrWhr
					 AND tree.nleft BETWEEN '".$nleft."' AND '".$nright."'
					 ORDER BY tcd.direct_id ASC";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	
	function getSumSelfCollection($member_id,$from_date='',$to_date='',$point_sub_type=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tmp.date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($point_sub_type!=""){
			$StrWhr .=" AND tmp.point_sub_type='$point_sub_type'";
		}
		$QR_SELECT = "SELECT 
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_vol END,0)) total_dr_vol,
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_dr_pv,
			   
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_vol END,0)) total_cr_vol,
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0)) total_cr_pv,
			   
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_vol END,0))
			   - SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_vol END,0)) total_bal_vol,
			   SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0))
			   - SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_bal_pv 
			   
			   FROM tbl_mem_point AS tmp 
			   WHERE tmp.member_id='".$member_id."'
			   $StrWhr
			   ORDER BY tmp.point_id ASC";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	
	
	
	function getSumGroupCollection($member_id,$nleft,$nright,$from_date='',$to_date='',$point_sub_type=''){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(tmp.date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		if($point_sub_type!=""){
			$StrWhr .=" AND tmp.point_sub_type='$point_sub_type'";
		}
		$QR_SELECT = "SELECT 
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_vol END,0)) total_dr_vol,
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_dr_pv,
					 
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_vol END,0)) total_cr_vol,
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0)) total_cr_pv,
					 
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_vol END,0))
					 - SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_vol END,0)) total_bal_vol,
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0))
					 - SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_bal_pv
					 
					 FROM tbl_mem_point AS tmp 
					 LEFT JOIN tbl_members AS tm ON tm.member_id = tmp.member_id
					 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					 WHERE  tmp.member_id!='".$member_id."'  
					 $StrWhr
					 AND tree.nleft BETWEEN '".$nleft."' AND '".$nright."' 
					 ORDER BY tmp.point_id ASC";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	
	
	
	
	function getNoOfOrder(){	
		$QR_SELECT = "SELECT COUNT(ord.order_id) AS ctrl_count
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0 AND ord.id_order_state NOT IN(6,7,8,13)
			 ORDER BY ord.order_id DESC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function getSumOfOrder(){
		$QR_SELECT = "SELECT SUM(ord.total_paid) AS total_paid
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0 AND ord.id_order_state NOT IN(6,7,8,13)
			 GROUP BY ord.order_id
			 ORDER BY ord.order_id DESC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['total_paid'];
	}
	
	function getLastOrder(){
		$QR_SELECT = "SELECT SUM(ord.total_paid) AS total_paid
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0 AND ord.id_order_state NOT IN(6,7,8,13) AND ord.invoice_number!=''
			 GROUP BY ord.order_id
			 ORDER BY ord.order_id DESC LIMIT 1";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['total_paid'];
	}
	
	function getSumOfFranchiseOrder($franchisee_id,$from_date='',$to_date=''){
		if($from_date!='' && $to_date!=''){
			/* commented on 31 jan 2018
			$StrWhr .=" AND DATE(ord.date_add) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
			*/
			$StrWhr .=" AND DATE(ord.invoice_date) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."' ";
		}
		$QR_SELECT = "SELECT SUM(ord.total_paid) AS total_paid , SUM(ord.total_paid_real) AS total_paid_real
					  FROM tbl_orders AS ord
					  LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
					  WHERE ord.order_id>0  AND ord.franchisee_id='".$franchisee_id."' AND ord.invoice_number!=''
					  $StrWhr
					  ORDER BY ord.order_id DESC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['total_paid'];
	}
	
	function getNoOfFranchiseOrder($franchisee_id){	
		$QR_SELECT = "SELECT COUNT(ord.order_id) AS ctrl_count
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0 AND ord.franchisee_id='".$franchisee_id."'
			 ORDER BY ord.order_id DESC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['ctrl_count'];
	}
	
	function getTotalOrderCalc($order_id){
		$QR_SEL = "SELECT SUM(tod.post_pv*tod.post_qty) AS total_pv, 
				 SUM(tod.post_price*tod.post_qty) AS total_paid, SUM(tod.original_post_price*tod.post_qty) AS  total_paid_real,
				 COUNT(tod.order_detail_id) AS total_products
				 FROM tbl_order_detail AS tod 
				 WHERE tod.order_id='".$order_id."'";
		$AR_RT = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_RT;
	}
	
	function getOrderQty($order_id){
		$QR_SEL = "SELECT SUM(tod.post_qty)  AS post_qty
				 FROM tbl_order_detail AS tod 
				 WHERE tod.order_id='".$order_id."'";
		$AR_RT = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_RT['post_qty'];
	}
	
	function getTotalOrderReturnCalc($order_return_id){
		$QR_SEL = "SELECT SUM(todr.post_pv*todr.post_qty) AS total_pv,
				 SUM(todr.post_price*todr.post_qty) AS total_paid, SUM(todr.original_post_price*todr.post_qty) AS  total_paid_real,
				 COUNT(todr.order_return_id) AS total_products
				 FROM tbl_order_detail_return AS todr 
				 WHERE todr.order_return_id='".$order_return_id."'";
		$AR_RT = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_RT;
	}
	
	function orderReturnCount($order_no){
		$QR_COUNT = "SELECT COUNT(tor.order_return_id) AS row_ctrl FROM tbl_orders_return AS tor WHERE tor.order_no='".$order_no."'";
		$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,1);
		return $AR_COUNT['row_ctrl'];
	}
	
	
	function getOrderCount($member_id){
		$QR_SELECT = "SELECT COUNT(order_id) AS order_ctrl FROM tbl_orders WHERE member_id='".$member_id."' AND id_order_state IN(4,5)";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['order_ctrl'];
	}
	
	function getOrderBv($member_id){
		$QR_SELECT = "SELECT SUM(total_pv) AS total_pv FROM tbl_orders WHERE member_id='".$member_id."' AND id_order_state IN(4,5)";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT['total_pv'];
	}
	
	function getOrderMaster($order_id){
		$QR_SELECT = "SELECT tot.* FROM tbl_orders AS tot WHERE tot.order_id='".$order_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function getOrderMasterDetail($order_no){
		$QR_SELECT = "SELECT tot.* FROM tbl_orders AS tot WHERE tot.order_no='".$order_no."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function getOrderReturnMaster($order_id){
		$QR_SELECT = "SELECT tor.* FROM tbl_orders_return AS tor WHERE tor.order_id='".$order_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function getCourierDetail($waybill){
		$QR_SELECT = "SELECT tac.* FROM tbl_api_courier AS tac WHERE tac.waybill='".$waybill."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function getStockMaster($order_no,$post_id){
		$QR_SELECT = "SELECT tsm.* FROM tbl_stock_master AS tsm WHERE tsm.order_no='".$order_no."' AND tsm.post_id='".$post_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function checkCouponValid($fpv_no,$member_id){
		$QR_GET = "SELECT tc.* 
				   FROM tbl_coupon AS tc 
				   WHERE tc.use_status='N' 
				   AND tc.coupon_no='".$fpv_no."' 	AND tc.assigned_to ='".$member_id."' 
				   AND tc.expires_on>=CURDATE()";
		$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
		return $AR_GET;
	}
	
	function updateCoupon($use_order_id,$coupon_id,$use_member_id){
		$today_date = InsertDate(getLocalTime());
		if($coupon_id>0 && $use_member_id>0){
			$data = array("use_status"=>"Y",
				"use_date"=>$today_date,
				"use_member_id"=>$use_member_id,
				"use_order_id"=>$use_order_id
			);
			$this->SqlModel->updateRecord("tbl_coupon",$data,array("coupon_id"=>$coupon_id));
		}
	}
	
	function checkMemberSms($fldiRegId,$sms_otp){
		$Q_CHK ="SELECT COUNT(fldiRegId) AS row_count FROM tbl_tmp_register WHERE 
			fldiRegId='".$fldiRegId."' AND fldvMCode='".$sms_otp."';";
		$R_CHK = $this->db->query($Q_CHK);
		$AR_CHK = $R_CHK->row_array();
		return $AR_CHK['row_count'];
	}
	
	function sendMobileVerifySMS($mobile_number,$name=''){
		$sms_otp = UniqueId("SMS_TMP");
		if(is_numeric($mobile_number)){
			$message = "Hi ".$name.", Your  phone verification code is ".$sms_otp." (valid for 15 minutes), Please use this OTP to verify your mobile";
			Send_Single_SMS($mobile_number,$message,true);
		}
		return $sms_otp;
	}
	
	function sendWalletSMS($mobile_number,$full_name,$trans_type,$amount){
		if(is_numeric($mobile_number)){
			$effect_type = ($trans_type=="Cr")? "Credit into":"Debit from";
			$message = "Hi ".$full_name.", ".$amount." is ".$effect_type."  your e-wallet, info: ".DOMAIN."";
			return Send_Single_SMS($mobile_number,$message);
		}
	}
	


	function sendNewPass($mobile_number,$name,$user_id,$password){
		if(is_numeric($mobile_number)){
			$message = "Hi ".$name.", You have successfully changed the password of your code ".$user_id.". plz use password ".$password." at ".DOMAIN.".";
			Send_Single_SMS($mobile_number,$message,true);
		}
		return $sms_otp;
	}
	
	function sendGetPassOtp($mobile_number,$name=''){
		$sms_otp = UniqueId("GET_PWD");
		if(is_numeric($mobile_number)){
			$message = "Hi ".$name.", You just requested to change your  password. Use OTP ".$sms_otp." (One Time Password) to reset your password. Do not share with anyone.";
			Send_Single_SMS($mobile_number,$message,true);
		}
		return $sms_otp;
	}

	function sendInvoiceSMS($mobile_number,$total_amount,$total_pv){
		$sms_otp = UniqueId("SMS_TMP");
		if(is_numeric($mobile_number)){
			$message = "Your order is billed with ".$total_amount." INR, info: ".DOMAIN." Thanx for shopping with ".WEBSITE.".";
			Send_Single_SMS($mobile_number,$message);
		}
		return $sms_otp;
	}

	
	function checkTempSms($fldiRegId){
		$QR_COUNT = "SELECT COUNT(fldiRegId) AS row_count 
					FROM tbl_tmp_register 
					WHERE fldiRegId='".$fldiRegId."' AND fldvMCode=''";
		$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
		return $AR_COUNT['row_count'];
	}
	
	function welcomeMemberSms($mobile_number,$name='',$user_id='',$password){
		$sms_otp = UniqueId("SMS_TMP");
		if(is_numeric($mobile_number)){
			$message = "Hi ".$name.", Welcome to ".WEBSITE."!! Your ID is ".$user_id." & password is: ".$password."  Enjoy shopping at Discounts!! by login at ".WEBSITE."";
			Send_Single_SMS($mobile_number,$message);
		}
		return $sms_otp;
	}
	
	function welcomeFranchiseSms($mobile_number,$name='',$user_id='',$password=''){
		$sms_otp = UniqueId("SMS_TMP");
		if(is_numeric($mobile_number)){
			$message = "Hi ".$name.", Welcome to ".WEBSITE."!! Your Usernamee is ".$user_id." & password is: ".$password."  , info : ".WEBSITE."";
			Send_Single_SMS($mobile_number,$message);
		}
		return $sms_otp;
	}
	
	
	function validateVerifcationTime($from_date,$to_date){
		$limit = $this->getValue("VERIFY_TIME");
		$current = MinuteDiff($from_date,$to_date);
		if($limit>=round($current)){
			return 1;
		}else{
			return 0;
		}
		
	}
	
	
	
	function getOrderDetail($order_detail_id){
		$QR_SELECT = "SELECT tod.* , tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount,
			  		 tpl.post_price,  tpl.update_date , tpl.post_pv
					  FROM tbl_order_detail AS tod
					  LEFT JOIN tbl_post AS tp ON tp.post_id=tod.post_id
					  LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
		              WHERE tod.order_detail_id='".$order_detail_id."'";
		$AR_SELECT = $this->SqlModel->runQuery($QR_SELECT,true);
		return $AR_SELECT;
	}
	
	function setNewsRead($news_id,$member_id){
		$QR_SELECT = "SELECT COUNT(*) AS ctrl_count FROM tbl_news_read WHERE news_id = '".$news_id."' AND member_id='".$member_id."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		if($AR_SELECT['ctrl_count']==0 && $news_id>0 && $member_id>0){
			$data = array("member_id"=>$member_id,
				"news_id"=>$news_id
			);
			$this->SqlModel->insertRecord("tbl_news_read",$data);
		}
	}
	
	function getAddress($member_id,$adress_type='BILL'){
		$QR_SEL = "SELECT ta.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
		FROM tbl_address AS ta 
		LEFT JOIN tbl_members AS tm ON tm.member_id=ta.member_id
		WHERE ta.member_id='".$member_id."'
		 AND ( ta.current_address!='' OR ta.pin_code!='' ) AND ta.adress_type LIKE '$adress_type' ORDER BY ta.address_id DESC";
		$AR_DT = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_DT;
	}
	
	function getMemberAddressId($member_id){
		$QR_SEL = "SELECT ta.address_id FROM tbl_address AS ta WHERE ta.member_id='".$member_id."' ORDER BY ta.address_id DESC";
		$AR_DT = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_DT['address_id'];
	}
	
	
	function getCartTotalMrp(){
		$cart_session = $this->session->userdata('session_id');
		$QR_SUM = "SELECT SUM(tc.cart_mrp*tc.cart_qty) AS cart_mrp_sum FROM tbl_cart AS tc WHERE tc.cart_session='".$cart_session."'";
		$AR_SUM = $this->SqlModel->runQuery($QR_SUM,true);
		return $AR_SUM['cart_mrp_sum'];
	}
	
	function getCartTotal(){
		$cart_session = $this->session->userdata('session_id');
		$QR_SUM = "SELECT SUM(tc.cart_price*tc.cart_qty) AS cart_sum FROM tbl_cart AS tc WHERE tc.cart_session='".$cart_session."'";
		$AR_SUM = $this->SqlModel->runQuery($QR_SUM,true);
		return $AR_SUM['cart_sum'];
	}
	
	function getShippingCharge(){
		$cart_session = $this->session->userdata('session_id');
		$QR_SUM = "SELECT SUM(tc.cart_shipping*tc.cart_qty) AS cart_sum FROM tbl_cart AS tc WHERE tc.cart_session='".$cart_session."'";
		$AR_SUM = $this->SqlModel->runQuery($QR_SUM,true);
		return $AR_SUM['cart_sum'];
	}
	
	function getCartTotalPv(){
		$cart_session = $this->session->userdata('session_id');
		$QR_SUM = "SELECT SUM(tc.cart_pv*tc.cart_qty) AS cart_pv FROM tbl_cart AS tc WHERE tc.cart_session='".$cart_session."'";
		$AR_SUM = $this->SqlModel->runQuery($QR_SUM,true);
		return $AR_SUM['cart_pv'];
	}
	
	function getCartTotalBv(){
		$cart_session = $this->session->userdata('session_id');
		$QR_BV = "SELECT SUM(tc.cart_bv*tc.cart_qty) AS cart_bv FROM tbl_cart AS tc 
				  WHERE tc.cart_session='".$cart_session."'";
		$AR_BV = $this->SqlModel->runQuery($QR_BV,true);
		return $AR_BV['cart_bv'];
	}
	
	function getCartCount(){
		$cart_session = $this->session->userdata('session_id');
		$QR_COUNT = "SELECT SUM(tc.cart_qty) AS row_ctrl FROM tbl_cart AS tc 
				  WHERE tc.cart_session='".$cart_session."'";
		$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
		return $AR_COUNT['row_ctrl'];
	}
	
	function getCartQty($post_id){
		$cart_session = $this->session->userdata('session_id');
		$QR_COUNT = "SELECT tc.cart_qty FROM tbl_cart AS tc   WHERE tc.post_id='".$post_id."' 
					AND  tc.cart_session='".$cart_session."'";
		$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
		$cart_qty = $AR_COUNT['cart_qty'];
		return ($cart_qty>0)? $cart_qty:0;
	}
	
	function getCartPrice($post_id){
		$cart_session = $this->session->userdata('session_id');
		$QR_COUNT = "SELECT (tc.cart_price*tc.cart_qty) AS cart_total FROM tbl_cart AS tc  
		 WHERE tc.post_id='".$post_id."' 	AND  tc.cart_session='".$cart_session."'";
		$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
		$cart_total = $AR_COUNT['cart_total'];
		return ($cart_total>0)? $cart_total:0;
	}
	
	function getChart($month,$year=''){
		$year = ($year!='')? $year:date("Y");
		$member_id = $this->session->userdata('mem_id');
		$QR_CMSN = "SELECT net_income 
				   FROM tbl_cmsn_mstr AS tcm
				   LEFT JOIN tbl_process AS tp ON tp.process_id=tcm.process_id
				   WHERE tcm.member_id='".$member_id."' 
				   AND MONTH(tp.start_date)='".$month."' AND YEAR(tp.start_date)='".$year."' 
				   ORDER BY  tcm.master_id ASC";
		$AR_CMSN = $this->SqlModel->runQuery($QR_CMSN,true);
		$net_income =  $AR_CMSN['net_income'];
		return ($net_income>0)? $net_income:0;
	}
	
	function getCollectionChart($month,$year=''){
		$year = ($year!='')? $year:date("Y");
		$franchisee_id = $this->session->userdata('fran_id');
		$QR_COLL = "SELECT SUM(toa.total_pv) AS total_pv 
					FROM tbl_orders AS toa 
					WHERE toa.franchisee_id='".$franchisee_id."'
					AND MONTH(toa.date_add)='".$month."' AND YEAR(toa.date_add)='".$year."' 
				   ORDER BY  toa.order_id ASC";
		$AR_COLL = $this->SqlModel->runQuery($QR_COLL,true);
		$total_pv =  $AR_COLL['total_pv'];
		return ($total_pv>0)? $total_pv:0;
	}
	

	function getShopType(){
		$order_method = $this->session->userdata('order_method');
		switch($order_method){
			case "GRAPH":
				$redirect = generateSeoUrlMember("order","shopgraph","");
			break;
			case "TAB":	
				$redirect = generateSeoUrlMember("order","shoptab","");
			break;
			default:
				$redirect = generateSeoUrl("product","catalog","");
			break;
		}
		return $redirect;
	}
	
	function getMemberIdPanRegister($pan_app_id){
		$QR_ID = "SELECT tm.member_id 
				 FROM tbl_members AS tm 
				 LEFT JOIN tbl_tmp_register AS ttr ON  ttr.fldiRegId=tm.reg_id
				 LEFT JOIN tbl_pan_register AS tpr ON tpr.reg_id=ttr.fldiRegId
				 WHERE tpr.pan_app_id='".$pan_app_id."'";
		$AR_ID = $this->SqlModel->runQuery($QR_ID,true);
		return $AR_ID['member_id'];
	}
	
	function getShippingDetail($address_id){
		$QR_SEL = "SELECT ta.* ,  CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
				  FROM tbl_address AS ta 
				  LEFT JOIN tbl_members AS tm ON tm.member_id=ta.member_id
				  WHERE ta.address_id='".$address_id."'
				  ORDER BY ta.address_id DESC";
		$AR_DT = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_DT;
	}
	
	function getBillingDetail($address_id){
		$QR_SEL = "SELECT ta.* ,  CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
				  FROM tbl_address AS ta 
				  LEFT JOIN tbl_members AS tm ON tm.member_id=ta.member_id
				  WHERE ta.member_id='".$member_id."' 
				  ORDER BY ta.address_id DESC";
		$AR_DT = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_DT;
	}
	
	function getCmsnMstr($master_id){
		$QR_MSTR = "SELECT tcm.* FROM tbl_cmsn_mstr AS tcm WHERE tcm.master_id='".$master_id."'";
		$AR_MSTR = $this->SqlModel->runQuery($QR_MSTR,true);
		return $AR_MSTR;
	}
	
	function getCmsnMstrSenior($master_id){
		$QR_MSTR = "SELECT tcm.* FROM tbl_cmsn_mstr_senior AS tcm WHERE tcm.master_id='".$master_id."'";
		$AR_MSTR = $this->SqlModel->runQuery($QR_MSTR,true);
		return $AR_MSTR;
	}
	

	
	function getPersonalCollection($member_id,$process_id=''){
		
		$StrWhr .=($process_id>0)? " AND tmb.process_id='".$process_id."'":"";
		if($member_id>0){
			$QR_COLL = "SELECT SUM(tmb.total_bv) AS total_bv, SUM(tmb.total_pv) AS total_pv
						FROM tbl_mem_business AS tmb 
						
						WHERE tmb.member_id='".$member_id."' 
						$StrWhr
						ORDER BY tmb.member_id";
			$AR_COLL = $this->SqlModel->runQuery($QR_COLL,true);
			return $AR_COLL;
		}
	}
	
	
	
	function getTeamCollection($member_id,$process_id=''){
		$StrWhr .=($process_id>0)? " AND tmb.process_id='".$process_id."'":"";
		if($member_id>0){
			$AR_SELECT = $this->getMember($member_id,"LVL");
			$nleft = $AR_SELECT["nleft"];
			$nright = $AR_SELECT["nright"];
				
			$Q_COLL = "SELECT SUM(tmb.total_bv) AS total_bv, SUM(tmb.total_pv) AS total_pv
			 FROM tbl_mem_business AS tmb
		     LEFT JOIN tbl_members AS tm ON tm.member_id=tmb.member_id
			 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
			 LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
			 WHERE  tmb.total_bv>0 AND tmb.member_id!='".$member_id."' $StrWhr
			 AND  tree.nleft BETWEEN '".$nleft."' AND '".$nright."'";
			
			$A_COLL = $this->SqlModel->runQuery($Q_COLL,true);
			return $A_COLL;		
		}
	}
	
	function getSelfTeamCollection($nleft,$nright,$process_id){
		$Q_COLL = "SELECT SUM(tmb.total_bv) AS total_bv, SUM(tmb.total_pv) AS total_pv
		 FROM tbl_mem_business AS tmb
		 LEFT JOIN tbl_members AS tm ON tm.member_id=tmb.member_id
		 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
		 LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
		 WHERE  tmb.total_bv>0 AND tmb.process_id='".$process_id."'
		 $StrWhr
		 AND  tree.nleft BETWEEN '".$nleft."' AND '".$nright."'";
		$A_COLL = $this->SqlModel->runQuery($Q_COLL,true);
		return $A_COLL;		
		
	}
	
	function getSelfGroupCollection($member_id,$nleft,$nright){
		$QR_SELECT = "SELECT SUM(tot.total_pv) AS total_pv, SUM(tot.total_bv) AS total_bv FROM tbl_orders AS tot 
					  LEFT JOIN tbl_members AS tm ON tm.member_id = tot.member_id
					  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					  WHERE tot.stock_sts>0 $StrDates
					  AND tree.nleft BETWEEN '".$nleft."' AND '".$nright."' ";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}

	function getSelfGroupCollectionFromToDate($member_id,$nleft,$nright,$date_from,$date_to){
		$QR_SELECT = "SELECT SUM(tot.total_pv) AS total_pv, SUM(tot.total_bv) AS total_bv FROM tbl_orders AS tot 
					  LEFT JOIN tbl_members AS tm ON tm.member_id = tot.member_id
					  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					  WHERE tot.stock_sts>0 $StrDates
					  AND tree.nleft BETWEEN '".$nleft."' AND '".$nright."' 
					  AND DATE(tot.date_add) BETWEEN '".InsertDate($date_from)."' AND '".InsertDate($date_to)."'";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}

	
	function getSelfSelfCollection($member_id,$date_from,$date_to){
		if($date_from!='' && $date_to!=''){
			$StrDates .=" AND DATE(tot.date_add) BETWEEN '".InsertDate($date_from)."' AND '".InsertDate($date_to)."'";
		}
		$QR_SELECT = "SELECT SUM(tot.total_pv) AS total_pv, SUM(tot.total_bv) AS total_bv, SUM(tot.total_paid) AS total_rcp
					 FROM tbl_orders AS tot 
					  LEFT JOIN tbl_members AS tm ON tm.member_id = tot.member_id
					  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					  WHERE  tot.stock_sts>0  AND tot.member_id='".$member_id."'
					  $StrDates ORDER BY tot.order_id ASC";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		return $AR_SELECT;
	}
	
	function getSelfBusiness($member_id,$process_id=''){
		$StrWhr .=($process_id>0)? " AND tmb.process_id='".$process_id."'":"";
		if($member_id>0){
			$AR_SELECT = $this->getMember($member_id);
			$nleft = $AR_SELECT["nleft"];
			$nright = $AR_SELECT["nright"];
				
			$Q_COLL = "SELECT SUM(tmb.total_bv) AS total_bv, SUM(tmb.total_pv) AS total_pv
			 FROM tbl_mem_business AS tmb
		     LEFT JOIN tbl_members AS tm ON tm.member_id=tmb.member_id
			 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
			 WHERE  tmb.total_bv>0 AND tmb.member_id='".$member_id."'  $StrWhr
			 AND  tree.nleft BETWEEN '".$nleft."' AND '".$nright."'";
			$A_COLL = $this->SqlModel->runQuery($Q_COLL,true);
			return $A_COLL;		
		}
	}
	function getGroupBusiness($member_id,$process_id=''){
		$StrWhr .=($process_id>0)? " AND tmb.process_id='".$process_id."'":"";
		if($member_id>0){
			$AR_SELECT = $this->getMember($member_id);
			$nleft = $AR_SELECT["nleft"];
			$nright = $AR_SELECT["nright"];
				
			$Q_COLL = "SELECT SUM(tmb.total_bv) AS group_bv, SUM(tmb.total_pv) AS group_pv
			 FROM tbl_mem_business AS tmb
		     LEFT JOIN tbl_members AS tm ON tm.member_id=tmb.member_id
			 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
			 WHERE  tmb.total_bv>0 AND tmb.member_id!='".$member_id."'  $StrWhr
			 AND  tree.nleft BETWEEN '".$nleft."' AND '".$nright."'";
			$A_COLL = $this->SqlModel->runQuery($Q_COLL,true);
			return $A_COLL;		
		}
	}
	
	function getCompanyBusiness($process_id){
		$StrWhr .=($process_id>0)? " AND tmb.process_id='".$process_id."'":"";	
		$Q_COLL = "SELECT SUM(tmb.total_bv) AS group_bv, SUM(tmb.total_pv) AS group_pv
		 FROM tbl_mem_business AS tmb
		 LEFT JOIN tbl_members AS tm ON tm.member_id=tmb.member_id
		 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
		 WHERE  tmb.total_bv>0 
		 $StrWhr
		 ORDER BY tmb.member_id ASC";
		$A_COLL = $this->SqlModel->runQuery($Q_COLL,true);
		return $A_COLL;		
	}
	
	function getOldCollectionProcess($member_id,$process_id){
		$AR_YEAR = getAccountYear(date("Y"));
		$QR_COLL = "SELECT SUM(total_bv) AS net_bv FROM tbl_cmsn_mstr WHERE 
					member_id='".$member_id."' AND process_id<'".$process_id."' 
					AND DATE(date_time) BETWEEN '".$AR_YEAR['flddFDate']."' AND '".$AR_YEAR['flddTDate']."'";
		$AR_COLL = $this->SqlModel->runQuery($QR_COLL,1);
		return $AR_COLL['net_bv'];
	}
	
	function getOldCollectionAll($member_id,$process_id,$cmsn_date){
		//$AR_YEAR = getAccountYear(getDateFormat($cmsn_date,"Y"));
		$QR_YEAR = "SELECT * FROM tbl_fin_year WHERE current_sts='Y'";
		$AR_YEAR = $this->SqlModel->runQuery($QR_YEAR,1);
		$QR_COLL = "SELECT SUM(total_cmsn) AS total_cmsn FROM tbl_cmsn_mstr_sum WHERE 
					member_id='".$member_id."' AND process_id<'".$process_id."'
					AND DATE(date_time) BETWEEN '".$AR_YEAR['from_date']."' AND '".$AR_YEAR['to_date']."'";
		$AR_COLL = $this->SqlModel->runQuery($QR_COLL,1);
		return $AR_COLL['total_cmsn'];
	}
	
	function getOldCollection($member_id){
		$AR_YEAR = getAccountYear(2016);
		$QR_COLL = "SELECT SUM(total_bv) AS net_bv FROM tbl_cmsn_mstr 
		WHERE member_id='".$member_id."'
		AND DATE(date_time) BETWEEN '".$AR_YEAR['flddFDate']."' AND '".$AR_YEAR['flddTDate']."'";
		$AR_COLL = $this->SqlModel->runQuery($QR_COLL,1);
		return $AR_COLL['net_bv'];
	}
	
	function setPaymentHistory($member_id,$reference_no,$amount,$AR_VAL=array()){
		if($member_id>0 && $amount>0){
			$data_set = array("member_id"=>$member_id,
				"reference_no"=>$reference_no,
				"amount"=>$amount
			);
			
			$trns_pay_id  = $this->SqlModel->insertRecord("tbl_pmt_history",$data_set);
		}
	}
	
	function setOnlinePayment($member_id,$reference_no,$order_amount,$type_id='',$trns_type='',$AR_VAL=array()){
		$order_message = $AR_VAL['order_message'];
		$address_id = $AR_VAL['address_id'];
		$cart_total = $AR_VAL['cart_total'];
		$shipping_charge = $AR_VAL['shipping_charge'];
		$coupon_discount = $AR_VAL['coupon_discount'];
		$coupon_mstr_id = $AR_VAL['coupon_mstr_id'];
		$wallet_id = $AR_VAL['wallet_id'];
		$no_pin = $AR_VAL['no_pin'];
		$user_note = $AR_VAL['user_note'];
		if($member_id>0 && $order_amount>0){
			$data_set = array("member_id"=>$member_id,
				"reference_no"=>$reference_no,
				"order_amount"=>$order_amount,
				"wallet_id"=>getTool($wallet_id,0),
				"address_id"=>getTool($address_id,0),
				"type_id"=>getTool($type_id,0),
				"no_pin"=>getTool($no_pin,0),
				"user_note"=>getTool($user_note,""),
				
				"coupon_mstr_id"=>getTool($coupon_mstr_id,0),				
				"order_message"=>getTool($order_message,''),
				"trns_type"=>$trns_type
			);
			
			$coinpay_id  = $this->SqlModel->insertRecord("tbl_online_payment",$data_set);
		}
	}
	
	function getOnlinePayment($online_id){
		$QR_SEL = "SELECT * FROM tbl_online_payment WHERE online_id='".$online_id."'";
		$AR_SEL  = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL;
	}
	
	function getPaymentDetail($reference_no){
		$QR_SEL = "SELECT * FROM tbl_online_payment WHERE reference_no='".$reference_no."' ORDER BY online_id DESC LIMIT 1";
		$AR_SEL  = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL;
	}
	
	
	function getCommissionMaster($member_id,$process_id){
		$QR_COLL = "SELECT * FROM tbl_cmsn_mstr WHERE member_id='".$member_id."' AND process_id='".$process_id."'";
		$AR_COLL = $this->SqlModel->runQuery($QR_COLL,1);
		return $AR_COLL;
	}
	
	function InsertDirectMemberBusiness($member_id,$rank_id,$process_id){
	
		$QR_DIR = "SELECT tm.member_id , tr.rank_id, tr.rank_cmsn, tr.month_target, tr.rank_name	
				  FROM tbl_members  AS tm
				  LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
				  WHERE tm.sponsor_id='".$member_id."'
				  AND tm.rank_id<='".$rank_id."'";
		$RS_DIR = $this->SqlModel->runQuery($QR_DIR);
		$AR_RANK = $this->getRank($rank_id);

		$new_cmsn = $AR_RANK['rank_cmsn'];
		foreach($RS_DIR as $AR_DIR):
			
			$from_member_id = FCrtRplc($AR_DIR['member_id']);
			$rank_cmsn = $AR_DIR['rank_cmsn'];
			
			$AR_SELF = $this->getPersonalCollection($from_member_id,$process_id);
			$self_pv = $AR_SELF['total_pv'];
			$self_bv = $AR_SELF['total_bv'];
			
			
			$AR_TEAM = $this->getTeamCollection($from_member_id,$process_id);
			
			$team_pv = $AR_TEAM['total_pv'];
			$team_bv = $AR_TEAM['total_bv'];
			
			$total_pv =  ( $self_pv + $team_pv );
			$total_bv = ( $self_bv + $team_bv );
						

			$from_rank_id = $AR_DIR['rank_id'];
			$old_cmsn = $AR_DIR['rank_cmsn'];
			$dif_cmsn = $new_cmsn - $old_cmsn;


			$net_pv = ( $total_pv * $dif_cmsn / 100 );
			$net_bv = ( $total_bv * $dif_cmsn / 100 );
			
			if($from_member_id>0 && $from_rank_id>0){
				$data = array("member_id"=>$member_id,	
					"rank_id"=>$rank_id,
					"process_id"=>$process_id,
					"from_member_id"=>$from_member_id,
					"from_rank_id"=>$from_rank_id,
					"self_pv"=>($self_pv>0)? $self_pv:0,
					"self_bv"=>($self_bv>0)? $self_bv:0,
					"group_pv"=>($team_pv>0)? $team_pv:0,
					"group_bv"=>($team_bv>0)? $team_bv:0,
					"total_pv"=>($total_pv>0)? $total_pv:0,
					"total_bv"=>($total_bv>0)? $total_bv:0,
					"new_cmsn"=>($new_cmsn>0)? $new_cmsn:0,
					"old_cmsn"=>$old_cmsn,
					"dif_cmsn"=>$dif_cmsn,
					"net_pv"=>$net_pv,
					"net_bv"=>$net_bv
				);
				$this->SqlModel->insertRecord("tbl_cmsn_trns",$data);
			}
			unset($net_collection,$from_rank_id,$rank_cmsn,$total_collection,$from_member_id);
		endforeach;

	}
	
	
	function getUpgradeRank($current_rank,$month_target){
		$QR_RANK = "SELECT tr.* FROM tbl_rank AS tr 
					WHERE tr.month_target<='".$month_target."' AND tr.rank_id>0 
					AND tr.month_target>0
					ORDER BY tr.rank_id DESC LIMIT 1";
		$AR_RANK = $this->SqlModel->runQuery($QR_RANK,true);
		return $AR_RANK;
	}
	
	function getLevelCount($member_id,$nlevel,$nleft,$nright,$from_date, $end_date){
		if($from_date!='' && $end_date!=''){
			$StrWhr .=" AND DATE(tree.date_join) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($end_date)."'";
		}elseif($end_date!=''){
			$StrWhr .=" AND DATE(tree.date_join) < '".InsertDate($end_date)."'";
		}
		$Q_CTRL = "SELECT COUNT(tree.member_id) AS row_count
				   FROM  tbl_mem_tree_lvl AS tree 
				   WHERE tree.nleft BETWEEN '$nleft' AND '$nright' 
				   AND tree.nlevel='$nlevel' 
				   AND tree.member_id IN(SELECT member_id FROM tbl_subscription )
				   $StrWhr";
		$A_CTRL = $this->SqlModel->runQuery($Q_CTRL,true);
		return $A_CTRL['row_count'];
	}
	
	
	function getMemberJoining($member_id,$left_right,$start_date,$end_date){
			if($start_date!='' && $end_date!=''){
				$StrWhr .= " AND DATE(ts.date_from) BETWEEN '".InsertDate($start_date)."' AND '".InsertDate($end_date)."'";
			}elseif($end_date!=''){
				$StrWhr .= " AND DATE(ts.date_from) <= '".InsertDate($end_date)."'";
			}
			
			if($left_right!=""){
				$QR_COL = "SELECT nleft, nright, nlevel, member_id FROM tbl_mem_tree WHERE spil_id='".$member_id."' AND left_right='".$left_right."';";
			}else{
				$QR_COL = "SELECT nleft, nright, nlevel, member_id FROM tbl_mem_tree WHERE member_id='".$member_id."';";
			}
			$AR_COL = $this->SqlModel->runQuery($QR_COL,true);
			$nleft = $AR_COL["nleft"];
			$nright = $AR_COL["nright"];
			
			$Q_CTRL = "SELECT COUNT(ts.subcription_id) AS row_ctrl
					  FROM tbl_subscription AS ts
					  LEFT JOIN tbl_members AS tm ON  tm.member_id=ts.member_id
					  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					  WHERE  tree.nleft BETWEEN '".$nleft."'  AND '".$nright."' 
					  $StrWhr  AND tm.delete_sts>0";
			$A_CTRL = $this->SqlModel->runQuery($Q_CTRL,true);
			return $A_CTRL['row_ctrl'];
			
	}
	
	function getPointCollection($member_id,$left_right,$start_date,$end_date,$point_sub_type=''){
			if($start_date!='' && $end_date!=''){
				$StrWhr .= " AND DATE(tmp.date_time) BETWEEN '".InsertDate($start_date)."' AND '".InsertDate($end_date)."'";
			}elseif($end_date!=''){
				$StrWhr .= " AND DATE(tmp.date_time) <= '".InsertDate($end_date)."'";
			}
			
			if($point_sub_type!=""){
				$StrWhr .= " AND tmp.point_sub_type = '".$point_sub_type."'";
			}
			
			if($left_right!=""){
				$QR_COL = "SELECT nleft, nright, nlevel, member_id FROM tbl_mem_tree WHERE spil_id='".$member_id."' AND left_right='".$left_right."';";
			}else{
				$QR_COL = "SELECT nleft, nright, nlevel, member_id FROM tbl_mem_tree WHERE member_id='".$member_id."';";
			}
			$AR_COL = $this->SqlModel->runQuery($QR_COL,true);
			$nleft = $AR_COL["nleft"];
			$nright = $AR_COL["nright"];
			
			$Q_CTRL = "SELECT 
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_dr_pv,
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0)) total_cr_pv,
					 
					 SUM(COALESCE(CASE WHEN tmp.point_type = 'Cr' THEN tmp.point_pv END,0))
					 - SUM(COALESCE(CASE WHEN tmp.point_type = 'Dr' THEN tmp.point_pv END,0)) total_bal_pv
					 
					 FROM tbl_mem_point AS tmp 
					 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tmp.member_id
					 WHERE  tmp.member_id!='".$member_id."'  
					 $StrWhr
					 AND tree.nleft BETWEEN '".$nleft."' AND '".$nright."' 
					 ORDER BY tmp.point_id ASC";
			$A_CTRL = $this->SqlModel->runQuery($Q_CTRL,true);
			return $A_CTRL['total_bal_pv'];
			
	}
	
	function getOrderCollection($member_id,$left_right,$start_date,$end_date,$order_type=''){
			if($start_date!='' && $end_date!=''){
				$StrWhr .= " AND DATE(tot.date_add) BETWEEN '".InsertDate($start_date)."' AND '".InsertDate($end_date)."'";
			}elseif($end_date!=''){
				$StrWhr .= " AND DATE(tot.date_add) <= '".InsertDate($end_date)."'";
			}
			
			if($order_type!=''){
				$StrWhr .= " AND tot.order_type = '".$order_type."'";
			}
			
			if($left_right!=""){
				$QR_COL = "SELECT nleft, nright, nlevel, member_id FROM tbl_mem_tree WHERE spil_id='".$member_id."' AND left_right='".$left_right."';";
			}else{
				$QR_COL = "SELECT nleft, nright, nlevel, member_id FROM tbl_mem_tree WHERE member_id='".$member_id."';";
			}
			$AR_COL = $this->SqlModel->runQuery($QR_COL,true);
			$nleft = $AR_COL["nleft"];
			$nright = $AR_COL["nright"];
			
			$Q_CTRL = "SELECT SUM(tot.total_pv) AS total_pv
					  FROM tbl_orders AS tot 
					  LEFT JOIN tbl_members AS tm ON tm.member_id = tot.member_id
					  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					  WHERE  tot.member_id!='".$member_id."'
					  AND tree.nleft BETWEEN '".$nleft."' AND '".$nright."'
					  $StrWhr";
			$A_CTRL = $this->SqlModel->runQuery($Q_CTRL,true);
			return $A_CTRL['total_pv'];
			
	}
	
	function getMemberJoiningLvl($member_id,$start_date,$end_date,$tree_type){
			$tree_table = getTree($tree_type);
			if($start_date!='' && $end_date!=''){
				$StrWhr .= " AND DATE(tree.date_join) BETWEEN '".InsertDate($start_date)."' AND '".InsertDate($end_date)."'";
			}elseif($end_date!=''){
				$StrWhr .= " AND DATE(tree.date_join) <= '".InsertDate($end_date)."'";
			}
			
			$QR_COL = "SELECT nleft, nright, member_id FROM ".$tree_table." WHERE member_id='".$member_id."';";
			$AR_COL = $this->SqlModel->runQuery($QR_COL,true);
			$nleft = $AR_COL["nleft"];
			$nright = $AR_COL["nright"];
			
			$Q_CTRL = "SELECT COUNT(tree.member_id) AS row_count
				   FROM  ".$tree_table." AS tree 
				   WHERE tree.nleft BETWEEN '$nleft' AND '$nright' 
				   AND tree.member_id IN(SELECT member_id FROM tbl_subscription )
				   $StrWhr";
			$A_CTRL = $this->SqlModel->runQuery($Q_CTRL,true);
			return $A_CTRL['row_count'];
			
	}
	
	
	
	function getDiffrentialBusiness($member_id,$process_id){
		$QR_MSTR  = "SELECT SUM(tct.net_pv) AS net_pv , 
					SUM(tct.net_bv) AS net_bv , 
					SUM(tct.total_pv) AS total_pv,
					SUM(tct.total_bv) AS total_bv
					FROM tbl_cmsn_trns AS tct 
					WHERE tct.member_id='".$member_id."' 
					AND tct.process_id='".$process_id."'";
		$AR_MSTR = $this->SqlModel->runQuery($QR_MSTR,true);
		return $AR_MSTR;
	}
	
	
	
	function getNeftStatus($member_id){
		$QR_STS = "SELECT tmns.* FROM tbl_mem_neft_sts AS tmns WHERE tmns.member_id='".$member_id."'";
		$AR_STS = $this->SqlModel->runQuery($QR_STS,true);
		return $AR_STS;
	}
	
	function getReturnCharge($return_id){
		$QR_SEL = "SELECT tmns.* FROM tbl_return_setup AS tmns WHERE tmns.return_id='".$return_id."'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL;
	}
	
	
	
	function getSelfTeamBusiness($member_id,$nleft,$nright,$process_id=''){
		$StrWhr .=($process_id>0)? " AND tmb.process_id='".$process_id."'":"";
		if($member_id>0){
			$Q_COLL = "SELECT SUM(tmb.total_bv) AS total_bv, SUM(tmb.total_pv) AS total_pv
			 FROM tbl_mem_business AS tmb
		     LEFT JOIN tbl_members AS tm ON tm.member_id=tmb.member_id
			 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
			 LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
			 WHERE  tmb.total_bv>0 
			 $StrWhr
			 AND  tree.nleft BETWEEN '".$nleft."' AND '".$nright."'";
			$A_COLL = $this->SqlModel->runQuery($Q_COLL,true);
			return $A_COLL;		
		}
	}
	
	function checkAllMemberNewsAccess($news_id){
		$QR_COUNT = "SELECT COUNT(tn.news_id) AS ctrl_count FROM 
		 AS tn WHERE tn.checkallmember>0 AND tn.news_id='".$news_id."'";
		$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
		return $AR_COUNT['ctrl_count'];
	}
	
	function checkMeberNewsAccess($news_id,$member_id){
		$QR_COUNT = "SELECT COUNT(tna.news_id) AS ctrl_count FROM tbl_new_access AS tna 
					WHERE tna.member_id='".$member_id."' AND tna.news_id='".$news_id."'";
		$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
		return $AR_COUNT['ctrl_count'];
	}
	
	
	function setgetEnquiry($from_id,$enquiry_date){
		$ticket_no = UniqueId("TICKET_NO");
		$QR_GET = "SELECT * FROM tbl_support WHERE from_id='".$from_id."'";
		$AR_GET = $this->SqlModel->runQuery($QR_GET,true);
		if($AR_GET['from_id']==""){
			$data = array("ticket_no"=>$ticket_no,"enquiry_from"=>"M","from_id"=>$from_id,"enquiry_sts"=>"O","enquiry_date"=>$enquiry_date);
			return $this->SqlModel->insertRecord("tbl_support",$data);
		}else{
			return $AR_GET['enquiry_id'];
		}
		
	}
	
	function process_cart($post_id,$cart_qty){
		$today_date = getLocalTime();
		$cart_session = $this->session->userdata('session_id');
		$AR_RT['ErrorMsg'] = "failed";
		$AR_RT['ErrorDtl'] = "Unable to process product not found";
		if($post_id>0 && $cart_qty>0){
			$AR_DT = $this->getPostDetail($post_id);
			$QR_COUNT = "SELECT COUNT(tc.cart_id) AS row_ctrl
						FROM tbl_cart AS tc 
						WHERE tc.post_id='".$post_id."' 
						AND tc.cart_session='".$cart_session."'";
			$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
			$cart_ctrl = $AR_COUNT['row_ctrl'];
			if($cart_ctrl==0){
				$category_id = $AR_DT['category_id'];
				$cart_title = $AR_DT['post_title'];
				$cart_desc = $AR_DT['post_desc'];
				$cart_price = $AR_DT['post_price'];
				
				$cart_mrp = $AR_DT['post_mrp'];
				$cart_pv = $AR_DT['post_pv'];
				$cart_bv = $AR_DT['post_bv'];
				$cart_total = ($cart_price*$cart_qty);
				
				$data = array("post_id"=>$post_id,
					"category_id"=>($category_id>0)? $category_id:0,
					"cart_title"=>$cart_title,
					"cart_desc"=>$cart_desc,
					"cart_mrp"=>$cart_mrp,
					"cart_price"=>$cart_price,
					"cart_qty"=>$cart_qty,
					"cart_pv"=>$cart_pv,
					"cart_bv"=>$cart_bv,
					"cart_total"=>$cart_total,
					"cart_session"=>$cart_session,
					"date_up"=>$today_date
				);
				$cart_id = $this->SqlModel->insertRecord("tbl_cart",$data);
				$AR_RT['ErrorMsg'] = "success";
				$AR_RT['ErrorDtl'] = "Product successfully added to cart";
			}elseif($cart_ctrl>0){
			
				$QR_CART = "SELECT tc.* FROM  tbl_cart AS tc WHERE tc.post_id='".$post_id."' 
							AND tc.cart_session='".$cart_session."'";
				$AR_CART = $this->SqlModel->runQuery($QR_CART,true);
				
				$cart_price = $AR_CART['cart_price'];
				$cart_total = $cart_price*$cart_qty;
				$data = array("cart_qty"=>$cart_qty,"cart_total"=>$cart_total);
				$this->SqlModel->updateRecord("tbl_cart",$data,array("post_id"=>$post_id,"cart_session"=>$cart_session));
				$AR_RT['ErrorMsg'] = "success";
				$AR_RT['ErrorDtl'] = "Your cart successfully updated";
			}
		}else{
			$data_delete = array("post_id"=>$post_id,"cart_session"=>$cart_session);
			$this->SqlModel->deleteRecord("tbl_cart",$data_delete);
			$AR_RT['ErrorMsg'] = "success";
			$AR_RT['ErrorDtl'] = "Your product successfully deleted";
		}
		
		
		$post_price = $this->getCartPrice($post_id);
		$AR_RT['post_price'] = ($post_price>0)? number_format($post_price,2):0;
		
		$cart_total_mrp = 	$this->getCartTotalMrp();		
		$cart_total = 	$this->getCartTotal();
		$cart_pv = $this->getCartTotalPv();
		$cart_bv = $this->getCartTotalBv();
		$cart_count = $this->getCartCount();
		
		$AR_RT['cart_total_mrp'] = ($cart_total_mrp>0)? number_format($cart_total_mrp,2):0;
		$AR_RT['cart_total'] = ($cart_total>0)? number_format($cart_total,2):0;
		$AR_RT['cart_pv'] = ($cart_pv>0)? number_format($cart_pv,2):0;
		$AR_RT['cart_bv'] = ($cart_bv>0)? number_format($cart_bv,2):0;
		$AR_RT['cart_count'] = ($cart_count>0)? $cart_count:0;
		
		return $AR_RT;
		
	}
	
	function getReferrerCountByRank($member_id,$rank_id,$from_date,$to_date){
		if($from_date!='' && $to_date!=''){
			$StrWhr .=" AND DATE(date_join) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."'";
		}
		
		$QR_SELECT = "SELECT nleft, nright FROM tbl_mem_tree WHERE member_id='$member_id';";
		$RS_SELECT = $this->db->query($QR_SELECT);
		$AR_SELECT = $RS_SELECT->row_array();
		$nleft = $AR_SELECT["nleft"];
		$nright = $AR_SELECT["nright"];
		
		$Q_CTRL = "SELECT COUNT(member_id) AS row_ctrl FROM tbl_mem_tree 
				   WHERE nleft BETWEEN '$nleft' AND '$nright' 
				   AND member_id IN(SELECT member_id FROM tbl_mem_rank WHERE to_rank_id='$rank_id')
				   $StrWhr";
		$R_CTRL = $this->db->query($Q_CTRL);
		$A_CTRL = $R_CTRL->row_array();
		return $A_CTRL['row_ctrl'];
	
	}
	
	
	
	function getDirectMemberCountByRank($member_id,$rank_id,$from_date,$to_date){
			$QR_DIR = "SELECT tm.member_id, tm.user_id, tm.rank_id, tree.nlevel, tree.nleft, tree.nright
				FROM tbl_members AS tm	
				LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				WHERE  tree.sponsor_id='".$member_id."' 
				GROUP BY tm.member_id";
			$RS_DIR = $this->SqlModel->runQuery($QR_DIR);
			$i=1;
			$AR_RT = array();
			foreach($RS_DIR as $AR_DIR):
				$rank_count = $this->getReferrerCountByRank($AR_DIR['member_id'],$rank_id,$from_date,$to_date);
				$AR_RT['rank_count_'.$i] = $rank_count;
			$i++;
			endforeach;
			return $AR_RT;
	}	
	
	function getDirectMemberCollection($member_id,$from_date,$to_date){
			$QR_DIR = "SELECT tm.member_id, tm.user_id, tm.rank_id, tree.nlevel, tree.nleft, tree.nright
				FROM tbl_members AS tm	
				LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				WHERE  tree.sponsor_id='".$member_id."' 
				GROUP BY tm.member_id
				ORDER BY tm.member_id ASC";
			$RS_DIR = $this->SqlModel->runQuery($QR_DIR);
			$i=1;
			$AR_RT = array();
			foreach($RS_DIR as $AR_DIR):
				$AR_GROUP = $this->getGroupCollection($AR_DIR['nleft'],$AR_DIR['nright'],$from_date,$to_date);
				$AR_RT['G_VAL_'.$i] = $AR_GROUP['total_bal_pv'];
			$i++;
			endforeach;
			return $AR_RT;
	}
	
	function getDirectMemberRoyaltyCollection($member_id,$from_date,$to_date){
			$QR_DIR = "SELECT tm.member_id, tm.user_id, tm.rank_id, tree.nlevel, tree.nleft, tree.nright
				FROM tbl_members AS tm	
				LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				WHERE  tree.sponsor_id='".$member_id."' 
				GROUP BY tm.member_id
				ORDER BY tm.member_id ASC";
			$RS_DIR = $this->SqlModel->runQuery($QR_DIR);
			$i=1;
			$AR_RT = array();
			foreach($RS_DIR as $AR_DIR):
				$AR_GROUP = $this->getGroupRoyaltyCollection($AR_DIR['nleft'],$AR_DIR['nright'],$from_date,$to_date);
				$AR_RT['G_VAL_'.$i] = $AR_GROUP['total_point'];
			$i++;
			endforeach;
			return $AR_RT;
	}
	
		
	function getLegPoint($rank_id){
		$QR_CB = "SELECT * FROM  tbl_rank_cb_point WHERE rank_id='".$rank_id."'";
		$AR_CB = $this->SqlModel->runQuery($QR_CB,true);
		return $AR_CB;
	}
	
	
	function getRoyaltyCommission($member_id,$from_date,$end_date){
		$QR_SEL = "SELECT SUM(net_income) AS total_amount FROM tbl_mem_royalty 
					WHERE member_id='$member_id' AND DATE(qualify_date) BETWEEN '$from_date' AND '$end_date'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return  $AR_SEL['total_amount'];
		
	}
	
	function getCurrentRoyalty($member_id){
		$QR_SEL = "SELECT tsr.*
				   FROM tbl_mem_royalty tmr
				   LEFT JOIN tbl_setup_royalty AS tsr ON tsr.royalty_id=tmr.royalty_id
				   WHERE tmr.member_id='$member_id'
				   ORDER BY tmr.royalty_id DESC LIMIT 1";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return  $AR_SEL;
		
	}
		
	
	function getOrderGst($order_id){
			$QR_TAX = "SELECT SUM(tod.post_price*tod.post_qty) AS order_amount, 
					   SUM(tod.original_post_price*tod.post_qty) AS  order_mrp, 
					   tod.tax_age
					   FROM tbl_order_detail AS tod 
					   WHERE tod.order_id='".$order_id."' 
					   GROUP BY tod.tax_age 
					   ORDER BY tod.order_detail_id ASC"; 
			$RS_TAX = $this->SqlModel->runQuery($QR_TAX);
			foreach($RS_TAX as $AR_TAX):
				$tax_age = $AR_TAX['tax_age'];
				$order_amount_tax = ( $AR_TAX['order_amount']  /  ( ($tax_age/100)+1 ) );	
				$order_tax_devide = $AR_TAX['tax_age']/2;
				$order_tax_calc = ($order_amount_tax*$order_tax_devide)/100;
				$sum_order_tax_calc +=$order_tax_calc;
			endforeach;
			$tax_amount = $sum_order_tax_calc*2;
			$AR_RT['tax_amount'] = $tax_amount;
			return $AR_RT;
	}

	
	
	
	function addUpdateFranchiseeBank($franchisee_id,$form_data){
		if($franchisee_id>0){
			
			$bank_holder = FCrtRplc($form_data['bank_holder']);
			$bank_name = FCrtRplc($form_data['bank_name']);
			$bank_account = FCrtRplc($form_data['bank_account']);
			$bank_branch = FCrtRplc($form_data['bank_branch']);
			$bank_ifc = FCrtRplc($form_data['bank_ifc']);
			
			$data_up = array("franchisee_id"=>$franchisee_id,
				"bank_holder"=>getTool($bank_holder,''),
				"bank_name"=>getTool($bank_name,''),
				"bank_account"=>getTool($bank_account,''),
				"bank_branch"=>getTool($bank_branch,''),
				"bank_ifc"=>getTool($bank_ifc,'')
			);			
			if($this->checkCountPro("tbl_franchisee_bank","franchisee_id='".$franchisee_id."'")==0){
				$this->SqlModel->insertRecord("tbl_franchisee_bank",$data_up);
			}else{
				$this->SqlModel->updateRecord("tbl_franchisee_bank",$data_up,array("franchisee_id"=>$franchisee_id));
			}
		}
	}
	
	function getLevelProcessCmsn($member_id,$cmsn_date){
		$QR_SEL =  "SELECT SUM(level_cmsn) AS total_amount  
					FROM tbl_cmsn_lvl_benefit 
					WHERE member_id='".$member_id."' AND DATE(cmsn_date)='".$cmsn_date."'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL['total_amount'];
	}
	
	function getTotalActivationCount($start_date='',$end_date=''){
		if($start_date!='' && $end_date!=''){
			$StrWhr .= " AND DATE(tmp.date_time) BETWEEN '".InsertDate($start_date)."' AND '".InsertDate($end_date)."'";
		}
		$QR_CTRL = "SELECT SUM(tmp.point_pv) AS total_value 
					FROM tbl_mem_point  AS tmp
					WHERE   tmp.point_type='Cr'
					$StrWhr";
		$AR_CTRL = $this->SqlModel->runQuery($QR_CTRL,1);
		return $AR_CTRL['total_value'];
	}
	
	function getBinaryLevelProcessCmsn($member_id,$process_id){
		$QR_SEL =  "SELECT SUM(level_cmsn) AS total_amount  
					FROM tbl_cmsn_lvl_benefit_lvl 
					WHERE member_id='".$member_id."' AND process_id='".$process_id."'";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		return $AR_SEL['total_amount'];
	}
	
	
	function getSubscription($subcription_id){
		$QR_SUB = "SELECT *  FROM ".prefix."tbl_subscription WHERE subcription_id='".$subcription_id."'";
		$AR_SUB = $this->SqlModel->runQuery($QR_SUB,true);
		return $AR_SUB;
	}
	
	function getCurrentRatio($member_id){
		$QR_RANK = "SELECT rank_cmsn AS rank_ratio 
					FROM tbl_rank 
					WHERE rank_id IN(SELECT rank_id FROM tbl_members WHERE member_id='$member_id')";
		$AR_RANK = $this->SqlModel->runQuery($QR_RANK,true);
		return $AR_RANK['rank_ratio'];
	}
	
	function getRoyaltyRatio($rank_id){
		$QR_RANK = "SELECT SUM(level_cmsn) AS royalty_ratio 
					FROM tbl_setup_level_cmsn 
					WHERE rank_id<='".$rank_id."'";
		$AR_RANK = $this->SqlModel->runQuery($QR_RANK,true);
		return $AR_RANK['royalty_ratio'];
	}
	
	function checkLevelRank($member_id,$from_member_id,$rank_id){
		$QR_SEL = "SELECT nleft, nright FROM tbl_mem_tree WHERE member_id='".$member_id."';";
		$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
		$nleft = $AR_SEL["nleft"];
		$nright = $AR_SEL["nright"];
		
		$Q_CTRL= "SELECT tm.member_id
				 FROM tbl_members AS tm
				 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				 WHERE  tree.member_id!='".$member_id."' AND tm.rank_id='".$rank_id."'
				 AND  tree.nleft BETWEEN '$nleft' AND '$nright'
				 ORDER BY tm.member_id DESC LIMIT 1";
		//PrintR($Q_CTRL);
		$A_CTRL = $this->SqlModel->runQuery($Q_CTRL,true);
		if($A_CTRL['member_id']==$from_member_id || $A_CTRL['member_id']==''){
			return 0;
		}else{
			return 1;
		}
	}
	
	function setReferralIncome($from_member_id,$subcription_id){
		$wallet_id = $this->getWallet(WALLET1);
		$today_date = InsertDate(getLocalTime());
		$trans_no = UniqueId("TRNS_NO");
		
		$CONFIG_TDS = getTool($this->getValue("CONFIG_TDS"),0);
		$CONFIG_ADMIN_CHARGE = getTool($this->getValue("CONFIG_ADMIN_CHARGE"),0);
		
		$AR_SUB = $this->getSubscription($subcription_id);
		
		$AR_MEM = $this->getMember($from_member_id);
		$user_id = $AR_MEM['user_id'];
		$sponsor_id = $AR_MEM['sponsor_id'];
		$trns_remark = "DIRECT REFERRAL FROM [".$user_id."]";
		
		
		$date_time = $AR_SUB['date_from'];
		$net_amount = $AR_SUB['prod_pv'];
		$income_percent = $AR_SUB['direct_bonus'];
		
		$total_income = ($net_amount * $income_percent)/100;
		$admin_charge = ($total_income * $CONFIG_ADMIN_CHARGE)/100;
		$tds_charge = ($total_income * $CONFIG_TDS)/100;
		$net_payout = $total_income - ($admin_charge + $tds_charge);
		
		$royalty_forward = $net_amount - $total_income;
		if($subcription_id>0 && $sponsor_id>0 && $total_income>0){
			
			$data_direct = array("subcription_id"=>$subcription_id,
				"member_id"=>$sponsor_id,
				"trans_no"=>$trans_no,
				"from_member_id"=>$from_member_id,
				"total_collection"=>getTool($net_amount,0),
				"income_percent"=>getTool($income_percent,0),
				"total_income"=>getTool($total_income,0),
				"royalty_forward"=>getTool($royalty_forward,0),
				
				"admin_charge"=>getTool($admin_charge,0),
				"tds_charge"=>getTool($tds_charge,0),
				"net_income"=>getTool($net_payout,0),
				"pay_status"=>'Y',
				"pay_sts_date"=>getTool($AR_SUB['date_time'],$today_date),
				"date_time"=>getTool($AR_SUB['date_time'],$today_date),
				"trns_remark"=>$trns_remark
			);

			if($this->checkCount("tbl_cmsn_direct","subcription_id",$subcription_id)==0){
				$this->SqlModel->insertRecord("tbl_cmsn_direct",$data_direct);
				$this->wallet_transaction($wallet_id,$sponsor_id,"Cr",$net_payout,$trns_remark,$today_date,$trans_no,array("trns_for"=>"DIR","trans_ref_no"=>$trans_no));
			}
			
		}
		
	}

}

?>