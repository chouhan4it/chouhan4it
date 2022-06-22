<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel extends MY_Controller {

	public function pagestype(){		
		$output = "";
		$QR_SELECT = "SELECT A.* FROM tbl_sys_menu_main AS A WHERE 1 ORDER BY A.ptype_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="PAGES_TYPE_EXCEL_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	public function submenu(){		
		$output = "";
		$QR_SELECT = "SELECT A.* FROM tbl_sys_menu_sub AS A WHERE 1 ORDER BY A.page_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="SUB_PAGES_EXCEL_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	public function operator(){		
		$output = "";
		$QR_SELECT = "SELECT top.*, tog.group_name FROM tbl_operator AS top 
		   LEFT JOIN tbl_oprtr_grp AS tog ON top.fldiGrpId=tog.group_id  
		   WHERE 1 $StrWhr ORDER BY top.fldiOprtrId ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="OPERATOR_EXCEL_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	public function cms(){
		$output = "";
		$QR_SELECT = "SELECT cms.* FROM tbl_cms AS cms   WHERE 1 $StrWhr ORDER BY cms.id_cms ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="CMS_EXCEL_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function member(){
		$output = "";
		$QR_SELECT = "SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,
		 tree.nlevel,  tree.nleft, tree.nright, tree.date_join FROM tbl_members AS tm	
		 LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
		 LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id
		 WHERE 1 ORDER BY tm.member_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="MEMBER_LIST_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function advert(){
		$output = "";
		$QR_SELECT = "SELECT ta.* FROM tbl_advert AS ta WHERE ta.isDelete>0 $StrWhr ORDER BY ta.advert_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="ADVERT_LIST_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function oprtlog(){	
		$output = "";
		$QR_SELECT = "SELECT tol.* FROM tbl_oprtr_logs AS tol WHERE 1 $StrWhr ORDER BY tol.login_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="OPERATOR_LOG_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function processor(){
		$output = "";
		$QR_SELECT = "SELECT tpp.* FROM tbl_payment_processor AS tpp WHERE 1 $StrWhr ORDER BY tpp.processor_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="PAYMENT_PROCESSOR_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function plan(){
		$output = "";
		$QR_SELECT = "SELECT tp.* FROM tbl_package AS tp	 WHERE tp.delete_sts>0  AND tp.package_id>0 $StrWhr ORDER BY tp.package_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="PLAN_DETAIL_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function citylist(){
		$output = "";
		$QR_SELECT = "SELECT tc.* FROM tbl_city AS tc   WHERE tc.isDelete>0 $StrWhr ORDER BY tc.country_code ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="CITY_LIST_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}


	
	
	function support(){
		$output = "";
		$QR_SELECT = "SELECT ts.*, tm.first_name, tm.last_name FROM tbl_support AS ts 
			LEFT JOIN tbl_members AS tm ON ts.from_id=tm.member_id WHERE 1 $StrWhr ORDER BY ts.enquiry_id DESC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="CONTACT_SUPPORT_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function faq(){
		$output = "";
		$QR_SELECT = "SELECT tf.* FROM tbl_faq AS tf   WHERE tf.faq_delete>0 $StrWhr ORDER BY tf.faq_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="FAQ_LIST_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function pintype(){
		$output = "";
		$QR_SELECT = "SELECT tpy.* FROM tbl_pintype AS tpy WHERE tpy.isDelete>0 $StrWhr ORDER BY tpy.type_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="EPIN_TYPE_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function pinmaster(){
		$output = "";
		$QR_SELECT = "SELECT tpm.*, tpy.pin_name, tpy.pin_letter, tm.user_id FROM tbl_pinsmaster AS tpm 
			LEFT  JOIN tbl_pintype AS tpy ON tpm.type_id=tpy.type_id
			LEFT JOIN tbl_members AS tm ON tpm.member_id=tm.member_id
			 WHERE 1 $StrWhr ORDER BY tpm.mstr_id DESC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="EPIN_MASTER_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	public function binaryincome(){		
		$output = "";
		$member_id = $this->session->userdata('mem_id');
		$QR_SELECT = ImportQuery(); 
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SRL NO</td>";
			$output .="<td>USER ID</td>";
			$output .="<td>FULL NAME</td>";
			$output .="<td>LEFT COLL</td>";
			$output .="<td>RIGHT COLL</td>";
			$output .="<td>MATCHING</td>";
			$output .="<td>LEFT CARRY</td>";
			$output .="<td>RIGHT CARRY</td>";
			$output .="<td>INCOME</td>";
			$output .="<td>TDS</td>";
			$output .="<td>ADMIN</td>";
			$output .="<td>NET INCOME</td>";
			
			$output .="<td>BANK NAME</td>";
			$output .="<td>ACCOUNT NO</td>";
			$output .="<td>IFSC CODE</td>";
			$output .="<td>BRANCH</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
		
		$output .="<tr>";
			$output .="<td>".$i."</td>";
			$output .="<td>".$row['user_id']."</td>";
			$output .="<td>".$row['full_name']."</td>";
			$output .="<td>".number_format($row['newLft'])."</td>";
			$output .="<td>".number_format($row['newRgt'])."</td>";
			$output .="<td>".number_format($row['pair_match'])."</td>";
			$output .="<td>".number_format($row['leftCrf'])."</td>";
			$output .="<td>".number_format($row['rightCrf'])."</td>";
			$output .="<td>".number_format($row['amount'],2)."</td>";
			$output .="<td>".number_format($row['tds'],2)."</td>";
			$output .="<td>".number_format($row['admin_charge'],2)."</td>";
			$output .="<td>".number_format($row['net_cmsn'],2)."</td>";
			
			$output .="<td>".$row['bank_name']."</td>";
			$output .="<td>".$row['account_no']."</td>";
			$output .="<td>".$row['ifc_code']."</td>";
			$output .="<td>".$row['branch']."</td>";
		$output .="</tr>";
		$i++;
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= "BINARY_INCOME_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	public function levelincome(){
		$output = "";
		$member_id = $this->session->userdata('mem_id');
		$QR_SELECT = ImportQuery(); 
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SRL NO</td>";
			$output .="<td>USER ID</td>";
			$output .="<td>FULL NAME</td>";
			$output .="<td>DATE</td>";
			$output .="<td>INCOME</td>";
			$output .="<td>TDS</td>";
			$output .="<td>ADMIN</td>";
			$output .="<td>NET INCOME</td>";
			
			$output .="<td>BANK NAME</td>";
			$output .="<td>ACCOUNT NO</td>";
			$output .="<td>IFSC CODE</td>";
			$output .="<td>BRANCH</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
		
		$output .="<tr>";
			$output .="<td>".$i."</td>";
			$output .="<td>".$row['FROM_USER_ID']."</td>";
			$output .="<td>".$row['FROM_MEMBER']."</td>";
			$output .="<td>". DisplayDate($row['DATE_TIME'])."</td>";
			$output .="<td>".number_format($row['TOTAL_INCOME'],2)."</td>";
			$output .="<td>".number_format($row['TDS_CHARGE'],2)."</td>";
			$output .="<td>".number_format($row['ADMIN_CHARGE'],2)."</td>";
			$output .="<td>".number_format($row['NET_INCOME'],2)."</td>";
			
			$output .="<td>".$row['bank_name']."</td>";
			$output .="<td>".$row['account_no']."</td>";
			$output .="<td>".$row['ifc_code']."</td>";
			$output .="<td>".$row['branch']."</td>";
		$output .="</tr>";
		$i++;
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= "LEVEL_INCOME_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	public function directincome(){		
		$output = "";
		$member_id = $this->session->userdata('mem_id');
		$QR_SELECT = ImportQuery(); 
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SRL NO</td>";
			$output .="<td>USER ID</td>";
			$output .="<td>FULL NAME</td>";
			$output .="<td>FROM USER ID</td>";
			$output .="<td>FROM FULL NAME</td>";
			$output .="<td>DATE</td>";
			$output .="<td>INCOME</td>";
			$output .="<td>TDS</td>";
			$output .="<td>ADMIN</td>";
			$output .="<td>NET INCOME</td>";
			
			$output .="<td>BANK NAME</td>";
			$output .="<td>ACCOUNT NO</td>";
			$output .="<td>IFSC CODE</td>";
			$output .="<td>BRANCH</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
		
		$output .="<tr>";
			$output .="<td>".$i."</td>";
			$output .="<td>".$row['TO_USER_ID']."</td>";
			$output .="<td>".$row['TO_MEMBER']."</td>";
			$output .="<td>".$row['FROM_USER_ID']."</td>";
			$output .="<td>".$row['FROM_MEMBER']."</td>";
			$output .="<td>". DisplayDate($row['DATE_TIME'])."</td>";
			$output .="<td>".number_format($row['TOTAL_INCOME'],2)."</td>";
			$output .="<td>".number_format($row['TDS_CHARGE'],2)."</td>";
			$output .="<td>".number_format($row['ADMIN_CHARGE'],2)."</td>";
			$output .="<td>".number_format($row['NET_INCOME'],2)."</td>";
			
			$output .="<td>".$row['bank_name']."</td>";
			$output .="<td>".$row['account_no']."</td>";
			$output .="<td>".$row['ifc_code']."</td>";
			$output .="<td>".$row['branch']."</td>";
		$output .="</tr>";
		$i++;
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= "DIRECT_INCOME_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	
	public function royaltyincome(){
		$output = "";
		$member_id = $this->session->userdata('mem_id');
		$QR_SELECT = ImportQuery(); 
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SRL NO</td>";
			$output .="<td>FROM DATE</td>";
			$output .="<td>TO DATE</td>";
			$output .="<td>USER ID</td>";
			$output .="<td>FULL NAME</td>";
			$output .="<td>ROYALTY</td>";
			$output .="<td>COMPANY BSNS</td>";
			$output .="<td>ROYALTY CMSN</td>";
			$output .="<td>TOTAL ACHIVERS</td>";
			$output .="<td>INCOME</td>";
			$output .="<td>TDS</td>";
			$output .="<td>ADMIN</td>";
			$output .="<td>NET INCOME</td>";
			
			$output .="<td>BANK NAME</td>";
			$output .="<td>ACCOUNT NO</td>";
			$output .="<td>IFSC CODE</td>";
			$output .="<td>BRANCH</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $AR_DT):
		
		$output .="<tr>";
			$output .="<td>".$i."</td>";
			$output .="<td>".DisplayDate($AR_DT['date_from'])."</td>";
			$output .="<td>".DisplayDate($AR_DT['date_end'])."</td>";
			$output .="<td>".$AR_DT['user_id']."</td>";
			$output .="<td>".$AR_DT['full_name']."</td>";
			$output .="<td>".$AR_DT['royalty_name']."</td>";
			$output .="<td>".number_format($AR_DT['royalty_count'],2)."</td>";
			$output .="<td>".$AR_DT['royalty_cmsn']."</td>";
			$output .="<td>".$AR_DT['royalty_achiver']."</td>";
			$output .="<td>".number_format($AR_DT['total_income'],2)."</td>";
			$output .="<td>".number_format($AR_DT['admin_charge'],2)."</td>";
			$output .="<td>".number_format($AR_DT['tds_charge'],2)."</td>";
			$output .="<td>".number_format($AR_DT['net_income'],2)."</td>";
			
			$output .="<td>".$AR_DT['bank_name']."</td>";
			$output .="<td>".$AR_DT['account_no']."</td>";
			$output .="<td>".$AR_DT['ifc_code']."</td>";
			$output .="<td>".$AR_DT['branch']."</td>";
		$output .="</tr>";
		$i++;
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= "ROYALTY_INCOME_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	function contactus(){
		$output = "";
		$QR_SELECT = "SELECT tc.* FROM tbl_contacts AS tc   WHERE 1 $StrWhr ORDER BY tc.contact_id ASC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="CONTACT_US_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	
	function albumlist(){	
		
		$first_date = InsertDate($model->getStartOrderDate());
		$output = "";
		$QR_SELECT = "SELECT tg.* FROM tbl_gallery AS tg   WHERE tg.delete_sts>0 $StrWhr ORDER BY tg.gallery_id DESC";
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		for ($i = 0; $i < count($columns_total); $i++) {
			$heading = $columns_total[$i];
			$output .= '"'.$heading.'",';
		}
			$output .="\n";
				
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .='"'.$row[$columns_total[$i]].'",';
			}
			$output .="\n";
		endforeach;
		$FileName="ALBUM_LIST_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function levelview(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		if($PARAM['d_month']>0){
			$d_month = FCrtRplc($PARAM['d_month']);
			$d_year = FCrtRplc($PARAM['d_year']);
			$d_year = ($d_year>0)? $d_year:date("Y");
			$start_date = $d_year."-".$d_month."-01";
			$PERIOD = getMonthDates($start_date);
			$from_date = $PERIOD['flddFDate'];
			$to_date = $PERIOD['flddTDate'];
		}
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>Srl No</td>";
			$output .="<td>User Id</td>";
			$output .="<td>Full Name</td>";
			$output .="<td>Introducer ID</td>";
			$output .="<td>Rank</td>";
			$output .="<td>Register Date</td>";
			$output .="<td>Email</td>";
			$output .="<td>Mobile</td>";
			$output .="<td>City</td>";
			$output .="<td>State</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
		
		$output .="<tr>";
			$output .="<td>".$i."</td>";
			$output .="<td>".$row['user_id']."</td>";
			$output .="<td>".$row['first_name']." ".$row['last_name']."</td>";
			$output .="<td>".$row['spsr_user_id']."</td>";
			$output .="<td>".$row['rank_name']."</td>";
			$output .="<td>". DisplayDate($row['date_join'])."</td>";
			$output .="<td>".$row['member_email']."</td>";
			$output .="<td>".$row['member_mobile']."</td>";
			$output .="<td>".$row['city_name']."</td>";
			$output .="<td>".$row['state_name']."</td>";
			
			$output .="<td>".$cadre."</td>";
		$output .="</tr>";
		$i++;
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function downlineincome(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		if($PARAM['d_month']>0){
			$d_month = FCrtRplc($PARAM['d_month']);
			$d_year = FCrtRplc($PARAM['d_year']);
			$d_year = ($d_year>0)? $d_year:date("Y");
			$start_date = $d_year."-".$d_month."-01";
			$PERIOD = getMonthDates($start_date);
			$from_date = $PERIOD['flddFDate'];
			$to_date = $PERIOD['flddTDate'];
		}
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>Srl No</td>";
			$output .="<td>User Id</td>";
			$output .="<td>Full Name</td>";
			$output .="<td>Introducer ID</td>";
			$output .="<td>Register Date</td>";
			$output .="<td>City</td>";
			$output .="<td>State</td>";
			$output .="<td>Self PV ".getDateFormat($from_date,"F")." month</td>";
			$output .="<td>Self BV ".getDateFormat($from_date,"F")." month</td>";
			$output .="<td>Group PV ".getDateFormat($from_date,"F")." month</td>";
			$output .="<td>Group BV ".getDateFormat($from_date,"F")." month</td>";
			$output .="<td>Self accumulated PV till ".getDateFormat($from_date,"F-Y")."  month</td>";
			$output .="<td>Self accumulated BV till ".getDateFormat($from_date,"F-Y")."  month</td>";
			$output .="<td>Group accumulated PV till ".getDateFormat($from_date,"F-Y")."  month</td>";
			$output .="<td>Group accumulated BV till ".getDateFormat($from_date,"F-Y")."  month</td>";
			$output .="<td>Rank</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
		
		$AR_SELF = $model->getSumSelfCollection($row['member_id'],$from_date,$to_date);
		$AR_GROUP = $model->getSumGroupCollection($row['member_id'],$row['nleft'],$row['nright'],$from_date,$to_date);
		
		
		$TILL_SELF = $model->getSumSelfCollection($row['member_id'],$first_date,$to_date);
		$TILL_GROUP = $model->getSumGroupCollection($row['member_id'],$row['nleft'],$row['nright'],$first_date,$to_date);
		
		$NEW_RANK = $model->getUpgradeRank($row['rank_id'],$TILL_SELF['total_pv']+$TILL_GROUP['total_pv']);
		$cadre = ($NEW_RANK['rank_name'])? $NEW_RANK['rank_name']:"N/A";
		
		$output .="<tr>";
			$output .="<td>".$i."</td>";
			$output .="<td>".$row['user_id']."</td>";
			$output .="<td>".$row['full_name']."</td>";
			$output .="<td>".$row['spsr_user_id']."</td>";
			$output .="<td>". DisplayDate($row['date_join'])."</td>";
			$output .="<td>".$row['city_name']."</td>";
			$output .="<td>".$row['state_name']."</td>";
			$output .="<td>".OneDcmlPoint($AR_SELF['total_pv'])."</td>";
			$output .="<td>".OneDcmlPoint($AR_SELF['total_bv'])."</td>";
			$output .="<td>".OneDcmlPoint($AR_GROUP['total_pv'])."</td>";
			$output .="<td>".OneDcmlPoint($AR_GROUP['total_bv'])."</td>";
			$output .="<td>".OneDcmlPoint($TILL_SELF['total_pv'])."</td>";
			$output .="<td>".OneDcmlPoint($TILL_SELF['total_bv'])."</td>";
			$output .="<td>".OneDcmlPoint($TILL_GROUP['total_pv'])."</td>";
			$output .="<td>".OneDcmlPoint($TILL_GROUP['total_bv'])."</td>";
			$output .="<td>".$cadre."</td>";
		$output .="</tr>";
		$i++;
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function membercollection(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$from_date = InsertDate($PARAM['from_date']);
		$to_date = InsertDate($PARAM['to_date']);
		$amount = FCrtRplc($PARAM['amount']);
		$carry_forward = FCrtRplc($PARAM['carry_forward']);
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>Srl No</td>";
			$output .="<td>User Id</td>";
			$output .="<td>Full Name</td>";
			$output .="<td>Mobile No</td>";
			$output .="<td>Introducer ID</td>";
			$output .="<td>Register Date</td>";
			$output .="<td>City</td>";
			$output .="<td>State</td>";
			$output .="<td>Rank</td>";
			$output .="<td>Self PV</td>";
			$output .="<td>Group PV</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
	  		 $AR_SELF = $model->getSumSelfCollection($row['member_id'],$from_date,$to_date);
			 $AR_ALL = $model->getDirectMemberQualify($row['member_id'],$from_date,$to_date);

			$net_total_pv += ceil($AR_SELF['total_pv']);
			foreach($AR_ALL['G_PV'] as $key=>$value):
				$group_pv = ceil($value);
				$net_total_pv += ($group_pv>=$amount) ? 0:$group_pv;
			endforeach;
			
			if($net_total_pv>=$amount){		
				 $total_pv  = ($total_pv-$amount);
				$output .="<tr>";
					$output .="<td>".$i."</td>";
					$output .="<td>".$row['user_id']."</td>";
					$output .="<td>".$row['full_name']."</td>";
					$output .="<td>".$row['member_mobile']."</td>";
					$output .="<td>".$row['spsr_user_id']."</td>";
					$output .="<td>". DisplayDate($row['date_join'])."</td>";
					$output .="<td>".$row['city_name']."</td>";
					$output .="<td>".$row['state_name']."</td>";
					$output .="<td>".$row['rank_name']."</td>";
					$output .="<td>".OneDcmlPoint($AR_SELF['total_pv'])."</td>";
					$output .="<td>".OneDcmlPoint(array_sum($AR_ALL['G_PV']))."</td>";
					
				$output .="</tr>";
				$i++;
			 }
		unset($AR_SELF,$AR_ALL,$net_total_pv);
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function downlinelevelincome(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$from_date = InsertDate($PARAM['from_date']);
		$to_date = InsertDate($PARAM['to_date']);
		$order_by = FCrtRplc($PARAM['order_by']);

		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td><strong>Srl No</strong></td>";
			$output .="<td><strong>User Id</strong></td>";
			$output .="<td><strong>Full Name</strong></td>";
			$output .="<td><strong>Rank</strong></td>";
			$output .="<td><strong>Introducer ID</strong></td>";
			$output .="<td><strong>City</strong></td>";
			$output .="<td colspan='3' align='center'><strong>STATICS</strong></td>";
		$output .="</tr>";
		$output .="<tr>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td align='center'>
			<table width='100%'>
				<tr>
					<td colspan='2' align='center'><strong>SELF</strong></td>
				</tr>
				<tr>
					<td align='center'><strong>BV</strong></td>
					<td align='center'><strong>BV</strong></td>
				</tr>
			</table></td>";
			$output .="<td align='center'>
			<table width='100%'>
				<tr>
					<td colspan='2' align='center'><strong>G1 COUNT</strong></td>
					<td colspan='2' align='center'><strong>G2 COUNT</strong></td>
					<td colspan='2' align='center'><strong>G3 COUNT</strong></td>
					<td colspan='2' align='center'><strong>G4 COUNT</strong></td>
					<td colspan='2' align='center'><strong>G5 COUNT &amp; ABOVE</strong></td>
				</tr>
				<tr>
					<td align='center'><strong>PV</strong></td>
					<td align='center'><strong>BV</strong></td>
					<td align='center'><strong>PV</strong></td>
					<td align='center'><strong>BV</strong></td>
					<td align='center'><strong>PV</strong></td>
					<td align='center'><strong>BV</strong></td>
					<td align='center'><strong>PV</strong></td>
					<td align='center'><strong>BV</strong></td>
					<td align='center'><strong>PV</strong></td>
					<td align='center'><strong>BV</strong></td>
				</tr>
			</table></td>";
			$output .="<td align='center'>
			<table width='100%'>
				<tr>
					<td colspan='2' align='center'><strong>TOTAL</strong></td>
				</tr>
				<tr>
					<td align='center'><strong>PV</strong></td>
					<td align='center'><strong>BV</strong></td>
				</tr>
			</table></td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
		
			$AR_IMG = $model->getCurrentImg($row['member_id']);
			$AR_SELF = $model->getSumSelfCollection($row['member_id'],$from_date,$to_date);
			$AR_ALL = $model->getDirectMemberCollection($row['member_id'],$from_date,$to_date,$order_by);
			
			
			$net_total_pv = $AR_SELF['total_pv']+$AR_ALL['G_ALL_PV'];
			$net_total_bv = $AR_SELF['total_bv']+$AR_ALL['G_ALL_BV'];
			if($net_total_pv>0){
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['rank_name']."</td>";
				$output .="<td>".$row['spsr_user_id']."</td>";
				$output .="<td>".$row['city_name']."</td>";
				$output .='<td align="left" valign="middle" class="cmntext"><table width="100%">
                  <tr>
                    <td align="center">'.OneDcmlPoint($AR_SELF['total_pv']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_SELF['total_bv']).'</td>
                  </tr>
                </table></td>
              <td align="left" valign="middle" class="cmntext"><table width="100%">
                  <tr>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G1_PV']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G1_BV']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G2_PV']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G2_BV']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G3_PV']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G3_BV']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G4_PV']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G4_BV']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G5_PV']).'</td>
                    <td align="center">'.OneDcmlPoint($AR_ALL['G5_BV']).'</td>
                  </tr>
              </table></td>
              <td align="left" valign="middle" class="cmntext"><table width="100%">
                  <tr>
                    <td align="center">'.OneDcmlPoint($net_total_pv).'</td>
                    <td align="center">'.OneDcmlPoint($net_total_bv).'</td>
                  </tr>
                </table></td>';
				
			$output .="</tr>";
			$i++;
			unset($AR_SELF,$GROUP1,$GROUP2,$GROUP3,$GROUP4,$GROUP5);
		 }
		
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	
	function deliveryexcel(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td><strong>Waybill</strong></td>";
			$output .="<td><strong>Reference No</strong></td>";
			$output .="<td><strong>Consignee Name</strong></td>";
			$output .="<td><strong>City</strong></td>";
			$output .="<td><strong>State</strong></td>";
			$output .="<td><strong>Country</strong></td>";
			$output .="<td><strong>Address</strong></td>";
			$output .="<td><strong>Pincode</strong></td>";
			$output .="<td><strong>Phone</strong></td>";
			$output .="<td><strong>Mobile</strong></td>";
			$output .="<td><strong>Weight</strong></td>";
			
			$output .="<td><strong>Payment</strong></td>";
			$output .="<td><strong>Package Amount</strong></td>";
			$output .="<td><strong>Code Amount</strong></td>";
			$output .="<td><strong>Product to be Shipped</strong></td>";
			$output .="<td><strong>Return Address</strong></td>";
			$output .="<td><strong>Return Pin</strong></td>";
			$output .="<td><strong>Seller Name</strong></td>";
			$output .="<td><strong>Seller Address</strong></td>";
			$output .="<td><strong>Seller CST No</strong></td>";
			$output .="<td><strong>Seller TIN</strong></td>";
			
			$output .="<td><strong>Invoice No</strong></td>";
			$output .="<td><strong>Invoice Date</strong></td>";
			$output .="<td><strong>Quantity</strong></td>";
			$output .="<td><strong>Commodity Value</strong></td>";
			$output .="<td><strong>Tax Value</strong></td>";
			$output .="<td><strong>Category of Goods</strong></td>";
			$output .="<td><strong>Seller_GST_TIN</strong></td>";
			
			$output .="<td><strong>HSN_Code</strong></td>";
			$output .="<td><strong>Return Reason</strong></td>";
			$output .="<td><strong>Vendor Pickup Location</strong></td>";
			$output .="<td><strong>EWBN</strong></td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			
			$cod_amount = ($row['payment']=="COD")? $row['total_paid']:0;
			
			$output .="<tr>";
				$output .="<td>&nbsp;</td>";
				$output .="<td>".$row['order_no']."</td>";
				$output .="<td>".$row['ship_full_name']."</td>";
				$output .="<td>".$row['ship_city_name']."</td>";
				$output .="<td>".$row['ship_state_name']."</td>";
				$output .="<td>".$row['ship_country_code']."</td>";
				$output .="<td>".$row['ship_current_address']."</td>";
				$output .="<td>".$row['ship_pin_code']."</td>";
				$output .="<td>&nbsp;</td>";
				$output .="<td>".$row['ship_mobile_number']."</td>";
				$output .="<td>&nbsp;</td>";
				
				$output .="<td>".$row['payment']."</td>";
				$output .="<td>".$row['total_paid']."</td>";
				$output .="<td>".$cod_amount."</td>";
				$output .="<td>".$row['post_title']."</td>";
				$output .="<td>".$row['ship_current_address']."</td>";
				$output .="<td>".$row['ship_pin_code']."</td>";
				$output .="<td>".$row['seller_name']."</td>";
				$output .="<td>".$row['seller_addres']."</td>";
				$output .="<td>&nbsp;</td>";
				$output .="<td>".$row['seller_tin_no']."</td>";
				
				$output .="<td>".$row['invoice_number']."</td>";
				$output .="<td>".$row['invoice_date']."</td>";
				$output .="<td>".$row['order_qty']."</td>";
				$output .="<td>".$row['item_price']."</td>";
				$output .="<td>0</td>";
				$output .="<td>N/A</td>";
				$output .="<td>".$row['seller_gst_no']."</td>";
				
				$output .="<td>&nbsp;</td>";
				$output .="<td>&nbsp;</td>";
				$output .="<td>".$row['store']."</td>";
				$output .="<td>&nbsp;</td>";
				
				
			$output .="</tr>";
			$i++;
			unset($AR_ALL);
		
		
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= "DELIVER_EXCEL_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function reportcollection(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$from_date = InsertDate($PARAM['from_date']);
		$to_date = InsertDate($PARAM['to_date']);
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>Srl No</td>";
			$output .="<td>Full Name</td>";
			$output .="<td>User Id</td>";			
			$output .="<td>Rank</td>";
			$output .= '<td  align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td colspan="3" align="center"><strong> PV </strong></td>
                  </tr>
                  <tr>
                    <td align="center">SELF</td>
                    <td align="center">GROUP</td>
                    <td align="center">TOTAL</td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td colspan="3" align="center"><strong> BV </strong></td>
                  </tr>
                  <tr>
                    <td align="center">SELF</td>
                    <td align="center">GROUP</td>
                    <td align="center">TOTAL</td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td colspan="3" align="center"><strong> RCP </strong></td>
                  </tr>
                  <tr>
                    <td align="center">SELF</td>
                    <td align="center">GROUP</td>
                    <td align="center">TOTAL</td>
                  </tr>
                </table></td>
              <td align="left"><strong>City</strong></td>
              <td align="left"><strong>State</strong></td>
              <td align="left"><strong>Mobile</strong></td>';
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
		
			$AR_SELF = $model->getSumSelfCollection($row['member_id'],$from_date,$to_date);
			$AR_GROUP = $model->getSumGroupCollection($row['member_id'],$row['nleft'],$row['nright'],$from_date,$to_date);
			
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['rank_name']."</td>";
				$output .='<td align="left"><table width="100%" border="0">
                  <tr>
                    <td>'.number_format($AR_SELF['total_pv'],2).'</td>
                    <td>'.number_format($AR_GROUP['total_pv'],2).'</td>
                    <td>'.number_format($AR_SELF['total_pv']+$AR_GROUP['total_pv'],2).'</td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="0">
                  <tr>
                    <td>'.number_format($AR_SELF['total_bv'],2).'</td>
                    <td>'.number_format($AR_GROUP['total_bv'],2).'</td>
                    <td>'.number_format($AR_SELF['total_bv']+$AR_GROUP['total_bv'],2).'</td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="0">
                <tr>
                  <td>'.number_format($AR_SELF['total_rcp']).'</td>
                  <td>'.number_format($AR_GROUP['total_rcp']).'</td>
                  <td>'.number_format($AR_SELF['total_rcp']+$AR_GROUP['total_rcp']).'</td>
                </tr>
              </table></td>
              <td align="left" valign="middle" class="cmntext">'.($row['city_name']).'</td>
              <td align="left" valign="middle" class="cmntext">'.($row['state_name']).'</td>
              <td align="left" valign="middle" class="cmntext">'.($row['member_mobile']).'</td>';
				
			$output .="</tr>";
			$i++;
			unset($AR_SELF,$AR_GROUP);
		
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	
	
	function incentivereward(){
		$model = new OperationModel();
		$today_date = InsertDate(getLocalTime());
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$from_date = InsertDate($PARAM['from_date']);
		$to_date = InsertDate($PARAM['to_date']);
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>Srl No</td>";
			$output .="<td>Full Name</td>";
			$output .="<td>User Id</td>";			
			$output .="<td>Register Date</td>";
			$output .="<td>Qualify Status</td>";
			$output .="<td>Qualification Start Date</td>";
			$output .= '<td  align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td colspan="5" align="center"><strong> Android Mobile </strong></td>
                  </tr>
                  <tr>
                    <td align="center">COMPLETED</td>
                    <td align="center">PENDING</td>
                    <td align="center">DAYS LEFT</td>
                    <td align="center">END DATE </td>
                    <td align="center">STATUS</td>
                  </tr>
                </table></td>
                <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                <tr>
                  <td colspan="5" align="center"><strong> Bangkok / Pattaya Trip </strong></td>
                </tr>
                <tr>
                  <td align="center">COMPLETED</td>
                  <td align="center">PENDING</td>
                  <td align="center">DAYS LEFT</td>
                  <td align="center">END DATE </td>
                  <td align="center">STATUS</td>
                </tr>
              </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                <tr>
                  <td colspan="5" align="center"><strong> Alto Car </strong></td>
                </tr>
                <tr>
                  <td align="center">COMPLETED</td>
                  <td align="center">PENDING</td>
                  <td align="center">DAYS LEFT</td>
                  <td align="center">END DATE </td>
                  <td align="center">STATUS</td>
                </tr>
              </table></td>
			  <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                <tr>
                  <td colspan="5" align="center"><strong> Duster Car </strong></td>
                </tr>
                <tr>
                  <td align="center">COMPLETED</td>
                  <td align="center">PENDING</td>
                  <td align="center">DAYS LEFT</td>
                  <td align="center">END DATE </td>
                  <td align="center">STATUS</td>
                </tr>
              </table></td>';
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		
		$android_initial = $model->getRewardtarget(1);
		$bangkok_initial =   $model->getRewardtarget(2);
		$alto_initial =   $model->getRewardtarget(3);
		$duster_initial =   $model->getRewardtarget(4);
		
		$year_end = InsertDate(date("Y")."-12-31");
		foreach($fetchRows as $AR_DT):
		
			$member_id = FCrtRplc($AR_DT['member_id']);
			
			$reward_ctrl = $model->checkRewardCriteria($member_id);
			
			$date_join = InsertDate($AR_DT['date_join']);
			$date_manual = "2017-05-28";
			if(strtotime( $date_join ) <= strtotime( $date_manual )){
				$date_join = $date_manual;
			}
			$android_end = InsertDate(AddToDate($date_join,"+ 1 Month"));
			$date_android_end = strtotime($android_end >= strtotime($year_end))? $year_end:$android_end;
			
			$bangkok_end = InsertDate(AddToDate($date_join,"+ 2 Month"));
			$date_bangkok_end = strtotime($bangkok_end >= strtotime($year_end))? $year_end:$bangkok_end;
			
			$alto_end = InsertDate(AddToDate($date_join,"+ 4 Month"));
			$date_alto_end = strtotime($alto_end >= strtotime($year_end))? $year_end:$alto_end;
			
			$duster_end = InsertDate(AddToDate($date_join,"+ 6 Month"));
			$date_duster_end = strtotime($duster_end >= strtotime($year_end))? $year_end:$duster_end;
			
			$day_android_diff = dayDiff($today_date,$date_android_end);
			$day_bangkok_diff = dayDiff($today_date,$date_bangkok_end);
			$day_alto_diff = dayDiff($today_date,$date_alto_end);
			$day_duster_diff = dayDiff($today_date,$date_duster_end);
			
			
			
			$android_target += $android_initial;
			$bangkok_target += $bangkok_initial;
			$alto_target += $alto_initial;
			$duster_target += $duster_initial;
			
			
			$android_achive = $model->getRewardAchive($member_id,$android_target,$date_join,$date_android_end);
			$android_complete = $android_achive;
			$android_value = ($android_complete>0)? $android_complete:0;
			$android_pending  = ( ($android_target-$android_value)>0 )? $android_target-$android_value:0;
			$android_expiry = (strtotime($date_android_end)>strtotime($today_date))? "RUNNING":"NOTACHIEVED";
			$android_sts = ($android_pending==0)? "ACHIEVED":$android_expiry;
			
			$bangkok_target += ($android_pending==0)? ($android_target):0;
			
			$bangkok_achive = $model->getRewardAchive($member_id,$bangkok_target,$date_join,$date_bangkok_end);
			$android_carry = (strtotime($date_android_end)>strtotime($today_date))? 0:$bangkok_achive;
			$bangkok_complete = ($android_pending<=0)? ($bangkok_achive-$android_target):$android_carry;
			$bangkok_value = ($bangkok_complete>0)? $bangkok_complete:0;
			$bangkok_pending  = ( ($bangkok_initial-$bangkok_value)>0 )? $bangkok_initial-$bangkok_value:0;
			$bangkok_expiry = (strtotime($date_bangkok_end)>strtotime($today_date))? "RUNNING":"NOTACHIEVED";
			$bangkok_sts = ($bangkok_pending==0)? "ACHIEVED":$bangkok_expiry;
			
			$alto_target += ($bangkok_pending==0)? ($android_target)+($bangkok_target):0;
			
			$alto_achive = $model->getRewardAchive($member_id,$alto_target,$date_join,$date_alto_end);
			$bangkok_carry = (strtotime($date_bangkok_end)>strtotime($today_date))? 0:$alto_achive;
			$alto_complete = ($bangkok_pending<=0)? $alto_achive-($android_target+$bangkok_target):$bangkok_carry;
			$alto_value = ($alto_complete>0)? $alto_complete:0;
			$alto_pending  = ( ($alto_initial-$alto_value)>0 )? $alto_initial-$alto_value:0;
			$alto_expiry = (strtotime($date_alto_end)>strtotime($today_date))? "RUNNING":"NOTACHIEVED";
			$alto_sts = ($alto_pending==0)? "ACHIEVED":$alto_expiry;
			
			$duster_target +=  ($alto_pending==0)? ($android_target)+($bangkok_target)+($alto_target):0;
			
			$duster_achive = $model->getRewardAchive($member_id,$duster_target,$date_join,$date_duster_end);
			$alto_carry = (strtotime($date_alto_end)>strtotime($today_date))? 0:$duster_achive;
			$duster_complete = ($alto_pending<=0)? $duster_achive-($android_target+$bangkok_target+$alto_target):$alto_carry;
			$duster_value = ($duster_complete>0)? $duster_complete:0;
			$duster_pending  = ( ($duster_initial-$duster_value)>0 )? $duster_initial-$duster_value:0;
			$duster_expiry = (strtotime($date_duster_end)>strtotime($today_date))? "RUNNING":"NOTACHIEVED";
			$duster_sts = ($duster_pending==0)? "ACHIEVED":$duster_expiry;
			
			$reward_sts = ($reward_ctrl>0)? "Yes":"No";
			
			$android_comleted = ($android_complete>0)? $android_complete:0;
			$bangkok_completed = ($bangkok_complete>0)? $bangkok_complete:0;
			$alto_completed = ($alto_complete>0 && $bangkok_pending==0)? $alto_complete:0;
			$duster_completed = ($duster_complete>0 && $alto_pending==0)? $duster_complete:0;
	
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$AR_DT['full_name']."</td>";
				$output .="<td>".$AR_DT['user_id']."</td>";
				$output .="<td>".DisplayDate($AR_DT['date_join'])."</td>";
				$output .="<td>".$reward_sts."</td>";
				$output .="<td>". DisplayDate($date_join)."</td>";
				$output .='<td align="left"><table width="100%" border="0">
                  <tr>
                    <td>'.OneDcmlPoint($android_comleted).'</td>
                    <td>'.OneDcmlPoint($android_pending).'</td>
                    <td>'.$day_android_diff.'</td>
					<td>'.DisplayDate($date_android_end).'</td>
                    <td>'.$android_sts.'</td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="0">
                  <tr>
                    <td>'.OneDcmlPoint($bangkok_completed).'</td>
                    <td>'.OneDcmlPoint($bangkok_pending).'</td>
                    <td>'.$day_bangkok_diff.'</td>
					<td>'.DisplayDate($date_bangkok_end).'</td>
                    <td>'.$bangkok_sts.'</td>
                  </tr>
                </table></td>
                <td align="left"><table width="100%" border="0">
                  <tr>
                    <td>'.OneDcmlPoint($alto_completed).'</td>
                    <td>'.OneDcmlPoint($alto_pending).'</td>
                    <td>'.$day_alto_diff.'</td>
					<td>'.DisplayDate($date_alto_end).'</td>
                    <td>'.$alto_sts.'</td>
                  </tr>
                </table></td>
				<td align="left"><table width="100%" border="0">
                  <tr>
                    <td>'.OneDcmlPoint($duster_completed).'</td>
                    <td>'.OneDcmlPoint($duster_pending).'</td>
                    <td>'.$day_duster_diff.'</td>
					<td>'.DisplayDate($date_duster_end).'</td>
                    <td>'.$duster_sts.'</td>
                  </tr>
                </table></td>';
				
			$output .="</tr>";
			$i++;
			unset($android_carry,$bangkok_carry,$alto_carry,$android_comleted,$bangkok_completed,$alto_completed,$duster_completed);
			unset($android_target,$bangkok_target,$alto_target,$duster_target,$android_achive,$bangkok_achive,$alto_achive,$duster_achive);
			unset($android_complete,$android_pending,$day_android_diff,$android_sts,$bangkok_complete,$bangkok_pending,$day_bangkok_diff,$bangkok_sts);
			unset($alto_complete,$alto_pending,$day_alto_diff,$alto_sts,$duster_complete,$duster_pending,$day_duster_diff,$duster_sts);
		
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function stockreport(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$date_from = InsertDate($PARAM['date_from']);
		$date_to = InsertDate($PARAM['date_to']);
		$franchisee_id = FCrtRplc($PARAM['franchisee_id']);
		
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>Srl No</td>";
			$output .="<td>Product Name</td>";
			$output .="<td>Stock Opening</td>";
			$output .="<td>Stock In </td>";
			$output .="<td>Stock Out </td>";
			$output .="<td>Stock Balance </td>";
			$output .="<td>AP</td>";
			$output .="<td>Total</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			
				$AR_OPEN = $model->getStockOpening($row['post_id'],$row['post_attribute_id'],$franchisee_id,$date_from);
				
				$AR_STOCK = $model->getStockBalance($row['post_id'],$row['post_attribute_id'],$franchisee_id,$date_from,$date_to);
				
				$net_balance = $AR_OPEN['net_balance']+$AR_STOCK['net_balance'];
				$total_price = $net_balance*$row['post_price'];
				$sum_balance +=$net_balance;
				
				$sum_total_credits += $AR_STOCK['total_qty_cr'];
				$sum_total_debits += $AR_STOCK['total_qty_dr'];
				$sum_total_price += $total_price;
			
			
				$output .="<tr>";
					$output .="<td>".$i."</td>";
					$output .="<td>".$row['post_title']."</td>";
					$output .="<td>".number_format($AR_OPEN['net_balance'],2)."</td>";
					$output .="<td>".number_format($AR_STOCK['total_qty_cr'],2)."</td>";
					$output .="<td>".number_format($AR_STOCK['total_qty_dr'],2)."</td>";
					$output .="<td>".number_format($net_balance,2)."</td>";
					$output .="<td>".number_format($row['post_price'],2)."</td>";
					$output .="<td>".number_format($total_price,2)."</td>";
				$output .="</tr>";
				$i++;
			
		unset($AR_SELF,$AR_ALL,$net_total_pv);
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function stockreportcompany(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$date_from = InsertDate($PARAM['date_from']);
		$date_to = InsertDate($PARAM['date_to']);
		$franchisee_id = FCrtRplc($PARAM['franchisee_id']);
		
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>Srl No</td>";
			$output .="<td>Product Name</td>";
			$output .="<td>Stock Opening</td>";
			$output .="<td>Stock In </td>";
			$output .="<td>Stock Out </td>";
			$output .="<td>Stock Balance </td>";
			$output .="<td>AP</td>";
			$output .="<td>Total</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
				
				$AR_OPEN = $model->getCompanyOpeningStock($row['post_id'],$franchisee_id,$date_from);
				$AR_STOCK = $model->getCompanyStock($row['post_id'],$supplier_id,$date_from,$date_to);
				
				$balance = $AR_OPEN['balance']+$AR_STOCK['balance'];
				$total_price = $balance*$row['post_price'];
				$sum_balance += $balance;
				$sum_total_credits += $AR_STOCK['total_credits'];
				$sum_total_debits += $AR_STOCK['total_debits'];
				$sum_total_price += $total_price;
				
			
				$output .="<tr>";
					$output .="<td>".$i."</td>";
					$output .="<td>".$row['post_title']."</td>";
					$output .="<td>".number_format($AR_OPEN['balance'],2)."</td>";
					$output .="<td>".number_format($AR_STOCK['total_credits'],2)."</td>";
					$output .="<td>".number_format($AR_STOCK['total_debits'],2)."</td>";
					$output .="<td>".number_format($balance,2)."</td>";
					$output .="<td>".number_format($row['post_price'],2)."</td>";
					$output .="<td>".number_format($total_price,2)."</td>";
				$output .="</tr>";
				$i++;
			
		unset($AR_SELF,$AR_ALL,$net_total_pv);
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function cmsnmaster(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$date_from = InsertDate($PARAM['date_from']);
		$date_to = InsertDate($PARAM['date_to']);
		$franchisee_id = FCrtRplc($PARAM['franchisee_id']);
		
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>Cycle No</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>Mobile No</td>";			
			$output .="<td>Cadre </td>";
			$output .="<td>Diff Income </td>";
			$output .="<td>Sr. Additional </td>";
			$output .="<td>Sr. Leadership</td>";
			$output .="<td>Car Budget</td>";
			$output .="<td>House  Budget</td>";
			$output .="<td>Total Income</td>";
			$output .="<td>Tds</td>";
			$output .="<td>Processing</td>";
			$output .="<td>Charity</td>";
			$output .="<td>Net Income</td>";
			$output .="<td>Carry Forward</td>";
			$output .="<td>Total</td>";
			$output .="<td>Pancard</td>";
			$output .="<td>Bank Name</td>";
			$output .="<td>Branch</td>";
			$output .="<td>AC NO</td>";
			$output .="<td>IFC Code</td>";
			$output .="<td>EFT_STS</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
				$carry_forward = $model->getLessPayout($row['member_id'],$row['process_id']);
				
				$net_payout = $row['net_cmsn']+$carry_forward;
				$output .="<tr>";
					$output .="<td>".$i."</td>";
					$output .="<td>".$row['full_name']."</td>";
					$output .="<td>".$row['user_id']."</td>";
					$output .="<td>".$row['mobile_number']."</td>";				
					$output .="<td>".$row['rank_name']."</td>";
					$output .="<td>".number_format($row['cadre_diffrential'])."</td>";
					$output .="<td>".number_format($row['sr_additional'])."</td>";
					$output .="<td>".number_format($row['sr_leadership'])."</td>";
					$output .="<td>".number_format($row['car_budget'])."</td>";
					$output .="<td>".number_format($row['house_budget'])."</td>";
					$output .="<td>".number_format($row['total_cmsn'])."</td>";
					$output .="<td>".number_format($row['tds'])."</td>";
					$output .="<td>".number_format($row['processing'])."</td>";
					$output .="<td>".number_format($row['charity_charge'])."</td>";
					$output .="<td>".number_format($row['net_cmsn'])."</td>";
					$output .="<td>".number_format($carry_forward)."</td>";
					$output .="<td>".number_format($net_payout)."</td>";
					$output .="<td>".$row['pan_card']."</td>";
					$output .="<td>".$row['bank_name']."</td>";
					$output .="<td>".$row['branch']."</td>";
					$output .="<td>".$row['account_number']."</td>";
					$output .="<td>".$row['ifc_code']."</td>";
					$output .="<td>".$row['neft_sts']."</td>";
				$output .="</tr>";
				$i++;
			
		
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function cmsnstatement(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$date_from = InsertDate($PARAM['date_from']);
		$date_to = InsertDate($PARAM['date_to']);
		$franchisee_id = FCrtRplc($PARAM['franchisee_id']);
		
		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>CYCLE NO</td>"; 
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>CITY</td>";
			$output .="<td>STATE</td>";
			$output .="<td>Rank</td>";
			$output .="<td>MOBILE</td>";			
			$output .="<td>PAN NO</td>";			
			$output .="<td>CADRE DIFF. INCOME (+) </td>";
			$output .="<td>SR.CADRE ADDL. BONUS (+)</td>";
			$output .="<td>LEADERSHIP BONUS (+)</td>";
			$output .="<td>BIKE  BUDGET (+)</td>";
			$output .="<td>CAR BUDGET (+)</td>";
			$output .="<td>HOUSE BUDGET (+)</td>";
			$output .="<td>OTHER TAXABLE(IF ANY) (+)</td>";
			$output .="<td>ROYALTY (+)</td>";
			$output .="<td>SPECIAL INCOME (+)</td>";
			$output .="<td>TOTAL</td>";
			$output .="<td>ACCUMULATED LEADERSHIP BONUS (-)</td>";
			$output .="<td>ACCUMULATED CAR BUDGET (-)</td>";
			$output .="<td>ACCUMULATED HOUSE BUDGET (-)</td>";
			$output .="<td>GROSS INCOME LESS</td>";
			$output .="<td>B/F AMT TILL (+)</td>";
			$output .="<td>ADD LEADERSHIP BONUS MODE WISE (+)</td>";
			$output .="<td>TOTAL</td>";
			$output .="<td>C/F AMT</td>";
			$output .="<td>TDS (-)</td>";
			$output .="<td>PROCESSING (-)</td>";
			$output .="<td>CHARITY (-)</td>";
			$output .="<td>NET PAYABLE</td>";
			$output .="<td>NET ACCUMULATED</td>";
			$output .="<td>BANK NAME</td>";
			$output .="<td>BRANCH</td>";
			$output .="<td>AC NO</td>";
			$output .="<td>IFC Code</td>";
			$output .="<td>NEFT_STS</td>";
			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
				
				$tds_charge = ( $row['tds'] / $row['total_cmsn'] * 100 );
				$cadre_diffrential = $row['cadre_diffrential'];
				$sr_additional = $row['sr_additional'];
				$sr_leadership = $model->getLeadershipBonus($row['member_id'],$row['process_id']);
				$bike_budget = $model->getSumBudgetPoint($row['member_id'],$row['process_id'],"BB");
				$car_budget = $model->getCarBudgetTotalPoint($row['member_id'],$row['process_id']);
				$house_budget = $model->getSumBudgetPoint($row['member_id'],$row['process_id'],"HB");
				$cmsn_other = $model->getCmsnOther($row['member_id'],$row['process_id']);
				$royalty_cmsn =  $model->getRoyaltyCommission($row['member_id'],$row['start_date'],$row['end_date']);
				$special_cmsn = $model->getSpecialCommission($row['member_id'],$row['start_date'],$row['end_date']);
				$total_cmsn = $cadre_diffrential + $sr_additional + $sr_leadership + $bike_budget + $car_budget 
							    + $house_budget + $cmsn_other + $royalty_cmsn + $special_cmsn;
				
				$accumulated_leadership =  $sr_leadership;
				$accumultaed_car_budget = $car_budget;
				$accumulated_housebudget = $house_budget;
				$total_accumulated = $accumulated_leadership + $accumultaed_car_budget + $accumulated_housebudget;
				
				$total_gross_cmsn = ( $total_cmsn - $total_accumulated );
				$add_bf_amount_till  = $model->getCarryForwardPayout($row['member_id'],$row['process_id']);
				$add_leadership_bonus_modewise = $model->getSeniorModeWise($row['member_id'],$row['mode_wise'],$row['start_date'],$row['end_date']);
				
				$net_total =  $total_gross_cmsn + $add_bf_amount_till + $add_leadership_bonus_modewise;
				$carry_forward = $model->setCarryForwardPayout($net_total,$row['neft_sts'],$tds_charge);
				$calc_total = ($carry_forward==0)? $net_total:0;
				$tds  = $calc_total * $tds_charge / 100;
				$processing = $calc_total * 3 / 100;
				$charity = $calc_total * 3 / 100;
				$net_payable = $calc_total - ($tds + $processing + $charity);
				$net_accumulated = $total_accumulated;
				
				$output .="<tr>";
					$output .="<td>".$i."</td>";
					$output .="<td>".$row['full_name']."</td>";
					$output .="<td>".$row['user_id']."</td>";
					$output .="<td>".$row['city_name']."</td>";
					$output .="<td>".$row['state_name']."</td>";
					$output .="<td>".$row['rank_name']."</td>";
					$output .="<td>".$row['mobile_number']."</td>";		
					$output .="<td>".$row['pan_card']."</td>";		
					$output .="<td>".number_format($cadre_diffrential)."</td>";
					$output .="<td>".number_format($sr_additional)."</td>";
					$output .="<td>".number_format($sr_leadership)."</td>";
					$output .="<td>".number_format($bike_budget)."</td>";
					$output .="<td>".number_format($car_budget)."</td>";
					$output .="<td>".number_format($house_budget)."</td>";
					$output .="<td>".number_format($cmsn_other)."</td>";
					$output .="<td>".number_format($royalty_cmsn)."</td>";
					$output .="<td>".number_format($special_cmsn)."</td>";
					$output .="<td>".number_format($total_cmsn)."</td>";
					$output .="<td>".number_format($accumulated_leadership)."</td>";
					$output .="<td>".number_format($accumultaed_car_budget)."</td>";
					$output .="<td>".number_format($accumulated_housebudget)."</td>";
					$output .="<td>".number_format($total_gross_cmsn)."</td>";
					$output .="<td>".number_format($add_bf_amount_till)."</td>";
					$output .="<td>".number_format($add_leadership_bonus_modewise)."</td>";
					$output .="<td>".number_format($net_total)."</td>";
					$output .="<td>".number_format($carry_forward)."</td>";
					$output .="<td>".number_format($tds)."</td>";
					$output .="<td>".number_format($processing)."</td>";
					$output .="<td>".number_format($charity)."</td>";
					$output .="<td>".number_format($net_payable)."</td>";
					$output .="<td>".number_format($net_accumulated)."</td>";
					$output .="<td>".$row['bank_name']."</td>";
					$output .="<td>".$row['branch']."</td>";
					$output .="<td>".$row['account_number']."</td>";
					$output .="<td>".$row['ifc_code']."</td>";
					$output .="<td>".$row['neft_sts']."</td>";
				$output .="</tr>";
				$i++;
			
		
		endforeach;
		$output .='</table>';
		
		
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function shoppecollection(){
		$model = new OperationModel();
		$month_ini = new DateTime("first day of last month");
		
		$last_month_date =  $month_ini->format('Y-m-d');
		$today_date = getLocalTime();
		$yester_date = InsertDate(AddToDate($today_date,"-1 Day"));
		
		$C_MONTH = getMonthDates($today_date);
		$X_MONTH = getMonthDates($last_month_date);

		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SRL NO</td>";
			$output .="<td>SHOPPE NAME</td>";
			$output .="<td>CITY</td>";
			$output .="<td>BA IN CITY</td>";			
			$output .="<td>YESTERDAY SALE</td>";
			$output .="<td>TODAY SALE</td>";
			$output .="<td>LAST MONTH SALE </td>";
			$output .="<td>CURRENT MONTH SALE </td>";
			$output .="<td>STOCK NOS.</td>";
			$output .="<td>STOCK VALUE</td>";			
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
				$yester_order = $model->getSumOfFranchiseOrder($row['franchisee_id'],$yester_date,$yester_date);
				$today_order = $model->getSumOfFranchiseOrder($row['franchisee_id'],$today_date,$today_date);
				$current_month_order = $model->getSumOfFranchiseOrder($row['franchisee_id'],$C_MONTH['flddFDate'],$C_MONTH['flddTDate']);
				$last_month_order = $model->getSumOfFranchiseOrder($row['franchisee_id'],$X_MONTH['flddFDate'],$X_MONTH['flddTDate']);
				$AR_STOCK = $model->getTotalStockBalance($row['franchisee_id'],"","");
				$pc_in_city = $model->getCountCityMember($row['city']);
				
				$total_yester_order +=$yester_order;
				$total_today_order +=$today_order;
				$total_current_month_order +=$current_month_order;
				$total_last_month_order +=$last_month_order;
				$total_pc_in_city +=$pc_in_city;
				
				$total_net_qty += $AR_STOCK['net_qty'];
				$total_net_rcp += $AR_STOCK['net_rcp'];
				
				$output .="<tr>";
					$output .="<td>".$i."</td>";
					$output .="<td>".$row['name']."</td>";
					$output .="<td>".$row['city']."</td>";
					$output .="<td>".$pc_in_city."</td>";				
					$output .="<td>".number_format($yester_order,2)."</td>";
					$output .="<td>".number_format($today_order,2)."</td>";
					$output .="<td>".number_format($last_month_order,2)."</td>";
					$output .="<td>".number_format($current_month_order,2)."</td>";
					$output .="<td>".number_format($AR_STOCK['net_qty'])."</td>";
					$output .="<td>".number_format($AR_STOCK['net_rcp'])."</td>";
				$output .="</tr>";
				$i++;
			
				unset($AR_STOCK,$pc_in_city,$yester_order,$today_order,$last_month_order,$current_month_order);
		endforeach;
		
		$output .='<tr>';
           $output .='<td colspan="3" align="right"><strong>Total</strong></td>';
           $output .='<td align="right"><strong>'.number_format($total_pc_in_city).'</strong></td>';
           $output .='<td align="right"><strong>'.number_format($total_yester_order,2).'</strong></td>';
           $output .='<td align="right" ><strong>'.number_format($total_today_order,2).'</strong></td>';
           $output .='<td align="right" ><strong>'.number_format($total_last_month_order,2).'</strong></td>';
           $output .='<td align="right"><strong>'.number_format($total_current_month_order,2).'</strong></td>';
           $output .='<td align="right"><strong>'.number_format($total_net_qty).'</strong></td>';
           $output .='<td align="right"><strong>'.number_format($total_net_rcp,2).'</strong></td>';
       $output .='</tr>';
		
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function taxsummary(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SRL NO</td>";
			$output .="<td>INVOICE NO</td>";
			$output .="<td>NAME</td>";
			$output .="<td>User Id</td>";
			$output .="<td>MOBILE NO</td>";			
			$output .="<td>DATE</td>";
			$output .="<td>INVOICE TOTAL</td>";
			$output .='<td colspan="3" align="center"><table width="100%" border="0">
                <tr>
                  <td colspan="3" align="center">12%</td>
                  </tr>
                <tr>
                  <td align="center">TAXABLE</td>
				  <td align="center">CGST 6%</td>
                  <td align="center">CGST 6%</td>
                </tr>
              </table></td>';
			$output .='<td colspan="3" align="center"><table width="100%" border="0">
                <tr>
                  <td colspan="3" align="center">18%</td>
                  </tr>
                <tr>
                  <td align="center">TAXABLE</td>
				  <td align="center">CGST 9%</td>
                  <td align="center">CGST 9%</td>
                </tr>
              </table></td>';
			$output .='<td colspan="3" align="center"><table width="100%" border="0">
                <tr>
                  <td colspan="3" align="center">28%</td>
                  </tr>
                <tr>
                  <td align="center">TAXABLE</td>
				  <td align="center">CGST 14%</td>
                  <td align="center">CGST 14%</td>
                </tr>
              </table></td>';
			$output .="<td>TAXABLE TOTAL</td>";  
			$output .="<td>TOTAL TAX</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			$AR_GST = $model->getOrderGst($row['order_id']);
			$order_12_tax = $AR_GST['12']['order_tax_devide'];
			$order_18_tax = $AR_GST['18']['order_tax_devide'];
			$order_28_tax = $AR_GST['28']['order_tax_devide'];
			$order_gst_tax = $order_12_tax+$order_18_tax+$order_28_tax;
			$order_total_tax = $order_gst_tax*2;
			$QR_ORD_TAX = "SELECT SUM(tod.post_pv*tod.post_qty) AS order_pv, SUM(tod.original_post_price*tod.post_qty) AS order_mrp, 
						   SUM(tod.post_price*tod.post_qty) AS order_rcp,
						   tod.post_tax, tod.tax_age, tod.post_qty, tod.net_amount
						   FROM tbl_order_detail AS tod 
						   WHERE tod.order_id='".$row['order_id']."' 
						   GROUP BY tod.tax_age 
						   ORDER BY tod.order_detail_id ASC"; 
			$RS_ORD_TAX = $this->SqlModel->runQuery($QR_ORD_TAX);
			foreach($RS_ORD_TAX as $AR_ORD_TAX):
				$post_tax = $AR_ORD_TAX['tax_age'];
				$order_tax_devide = $post_tax/2;
				$order_rcp = ( $AR_ORD_TAX['order_rcp'] / ( ($post_tax/100)+1 ) );								
				$order_tax_calc = ($order_rcp*$order_tax_devide)/100;
				$sum_order_rcp +=$order_rcp;
				$sum_order_tax_calc +=$order_tax_calc;
				switch($AR_ORD_TAX['tax_age']){
					case 12:
						$taxable12 = $order_rcp;
					break;
					case 18:
						$taxable18 = $order_rcp;
					break;
					case 28:
						$taxable28 = $order_rcp;
					break;
				}
			endforeach;
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['invoice_number']."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['member_mobile']."</td>";				
				$output .="<td>".DisplayDate($row['invoice_date'])."</td>";
				$output .="<td>".number_format($row['total_paid'],2)."</td>";
				$output .="<td>".number_format($taxable12,2)."</td>";
				$output .="<td>".number_format($order_12_tax,2)."</td>";
				$output .="<td>".number_format($order_12_tax,2)."</td>";
				$output .="<td>".number_format($taxable18,2)."</td>";
				$output .="<td>".number_format($order_18_tax,2)."</td>";
				$output .="<td>".number_format($order_18_tax,2)."</td>";
				$output .="<td>".number_format($taxable28,2)."</td>";
				$output .="<td>".number_format($order_28_tax,2)."</td>";
				$output .="<td>".number_format($order_28_tax,2)."</td>";
				$output .="<td>".number_format($sum_order_rcp,2)."</td>";
				$output .="<td>".number_format($order_total_tax,2)."</td>";
			$output .="</tr>";
			$i++;
		
			unset($AR_GST,$order_12_tax,$order_28_tax,$order_gst_tax,$order_total_tax,$sum_order_rcp,$taxable12,$taxable18,$taxable28);
		endforeach;
		
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");

		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}

	function invoicereport(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>OrderType</td>";
			$output .="<td>OrderNo</td>";
			$output .="<td>SubOrderNo</td>";
			$output .="<td>PaymentStatus</td>";
			$output .="<td>CustomerName</td>";
			$output .="<td>CustomerAddress</td>";
			$output .="<td>CustomerAddress2</td>";
			$output .="<td>CustomerAddress3</td>";
			$output .="<td>CustomerCity</td>";
			$output .="<td>CustomerState</td>";			
			$output .="<td>ZipCode</td>";
			$output .="<td>CustomerMobileNo</td>";
			$output .="<td>CustomerPhoneNo</td>";		
			$output .="<td>CustomerEmail</td>";
			
			$output .="<td>ProductMRP</td>";
			$output .="<td>ProductGroup</td>";
			$output .="<td>ProductDesc</td>";
			$output .="<td>Cod Payment</td>";
			$output .="<td>OctroiMRP</td>";
			
			$output .="<td>VolWeight(GMS)</td>";
			$output .="<td>PhyWeight(GMS)</td>";
			$output .="<td>ShipLength (CM)</td>";
			$output .="<td>ShipWidth(CM)</td>";
			$output .="<td>ShipHeight(CM)</td>";
			$output .="<td>AirWayBillNO</td>";
			
			$output .="<td>ServiceType</td>";
			$output .="<td>Quantity</td>";
			$output .="<td>CSTNumber</td>";
			$output .="<td>TINNumber</td>";
			$output .="<td>CGST</td>";
			$output .="<td>SGST</td>";
			$output .="<td>IGST</td>";
			$output .="<td>invoiceno</td>";
			$output .="<td>invoicedate</td>";
			$output .="<td>Product SKU</td>";
			$output .="<td>Status</td>";
			$output .="<td>OrderStatus</td>";
			$output .="<td>Error</td>";
		$output .="</tr>";
		$RS_SET = $sql->result_array();
		$i=1;
		foreach($RS_SET as $AR_DT):
			$OrderType = ($AR_DT['payment']=="COD")? "COD":"PrePaid";
			$OrderNo = $AR_DT['order_no'];
			$SubOrderNo = $AR_DT['reference_no'];
			$PaymentStatus = $OrderType;
			
			$CustomerName = $AR_DT['full_name'];
			$CustomerAddress = $AR_DT['ship_current_address'];
			$CustomerAddress2 = '';
			$CustomerAddress3 = '';
			$CustomerCity = $AR_DT['ship_city_name'];
			$CustomerState = $AR_DT['ship_state_name'];
			$ZipCode = $AR_DT['ship_pin_code'];
			$CustomerMobileNo = $AR_DT['ship_mobile_number'];
			$CustomerPhoneNo = '';
			$CustomerEmail = $AR_DT['member_email'];
			
			$ProductMRP = $AR_DT['total_paid_real'];
			$ProductGroup = $AR_DT['post_title'];
			$ProductDesc = $AR_DT['post_desc'];
			$Cod_Payment = $AR_DT['total_paid'];
			$OctroiMRP = 0;
			
			$VolWeight = $AR_DT['post_weight'];
			$PhyWeight = $AR_DT['post_weight'];
			$ShipLength = $AR_DT['post_depth'];
			$ShipWidth = $AR_DT['post_width'];
			$ShipHeight = $AR_DT['post_height'];
			$AirWayBillNO = '';
			$ServiceType = '';
			
			$Quantity = $AR_DT['total_products'];
			$CSTNumber = '';
			$TINNumber = '';
			$CGST = 0;
			$SGST = 0;
			$IGST = 0;
			
			$invoiceno = $AR_DT['invoice_number'];
			$invoicedate = $AR_DT['invoice_date'];
			$Product_SKU = '';
			$Status = '';
			$OrderStatus = $AR_DT['order_state'];
			$Error = '';
			
			
			$output .="<tr>";
				$output .="<td>".$OrderType."</td>";
				$output .="<td>".$OrderNo."</td>";
				$output .="<td>".$SubOrderNo."</td>";
				$output .="<td>".$PaymentStatus."</td>";
				
				$output .="<td>".$CustomerName."</td>";
				$output .="<td>".$CustomerAddress."</td>";
				$output .="<td>".$CustomerAddress2."</td>";
				$output .="<td>".$CustomerAddress3."</td>";
				$output .="<td>".$CustomerCity."</td>";
				$output .="<td>".$CustomerState."</td>";	
				$output .="<td>".$ZipCode."</td>";	
				$output .="<td>".$CustomerMobileNo."</td>";
				$output .="<td>".$CustomerPhoneNo."</td>";
				$output .="<td>".$CustomerEmail."</td>";
				
				$output .="<td>".$ProductMRP."</td>";
				$output .="<td>".$ProductGroup."</td>";
				$output .="<td>".$ProductDesc."</td>";
				$output .="<td>".$Cod_Payment."</td>";
				$output .="<td>".$OctroiMRP."</td>";
				
				$output .="<td>".$VolWeight."</td>";
				$output .="<td>".$PhyWeight."</td>";
				$output .="<td>".$ShipLength."</td>";
				$output .="<td>".$ShipWidth."</td>";
				$output .="<td>".$ShipHeight."</td>";
				$output .="<td>".$AirWayBillNO."</td>";
				$output .="<td>".$ServiceType."</td>";
				
				$output .="<td>".$Quantity."</td>";
				$output .="<td>".$CSTNumber."</td>";
				$output .="<td>".$TINNumber."</td>";
				$output .="<td>".$CGST."</td>";
				$output .="<td>".$SGST."</td>";
				$output .="<td>".$IGST."</td>";
				
				$output .="<td>".$invoiceno."</td>";
				$output .="<td>".$invoicedate."</td>";
				$output .="<td>".$Product_SKU."</td>";
				$output .="<td>".$Status."</td>";
				$output .="<td>".$OrderStatus."</td>";
				$output .="<td>".$Error."</td>";
			$output .="</tr>";
			$i++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= "ITHINK_LOGISTIC_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}

	function pendinginvoices(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>DATE</td>";
			$output .="<td>ORDER NO</td>";
			$output .="<td>SHOPPE</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>CITY</td>";			
			$output .="<td>QTY</td>";
			$output .="<td>AMOUNT</td>";
			$output .="<td>TOTAL BV</td>";		
			$output .="<td>TOTAL PV</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".DisplayDate($row['date_add'])."</td>";
				$output .="<td>".$row['order_no']."</td>";
				$output .="<td>".$row['user_name']."</td>";
				$output .="<td>".strtoupper($row['full_name'])."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['city_name']."</td>";	
				$output .="<td>".$row['post_qty']."</td>";	
				$output .="<td>".number_format($row['total_paid'],2)."</td>";
				$output .="<td>".$row['total_bv']."</td>";
				$output .="<td>".$row['total_pv']."</td>";
			$output .="</tr>";
			$i++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}

	function totalpvreport(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>Rank</td>";
			$output .="<td>SELF PV</td>";
			$output .="<td>GROUP PV</td>";
			$output .="<td>TOTAL PV</td>";
			$output .="<td>CITY</td>";
			$output .="<td>STATE</td>";
			$output .="<td>MOBILE</td>";
			$output .="<td>TILL DATE</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['rank_name']."</td>";
				$output .="<td>".$row['self_pv']."</td>";
				$output .="<td>".$row['group_pv']."</td>";	
				$output .="<td>".$row['total_pv']."</td>";	
				$output .="<td>".$row['city_name']."</td>";
				$output .="<td>".$row['state_name']."</td>";
				$output .="<td>".$row['member_mobile']."</td>";
				$output .="<td>".DisplayDate($row['till_date'])."</td>";
			$output .="</tr>";
			$i++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}

	function cadrejumpachievers(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>CADRE ACHIEVED</td>";
			$output .="<td>END DATE</td>";
			$output .="<td>ACHIEVED ON</td>";
			$output .="<td>CITY</td>";
			$output .="<td>STATE</td>";			
			$output .="<td>MOBILE</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['rank_name']."</td>";
				$output .="<td>".DisplayDate($row['end_date'])."</td>";
				$output .="<td>".DisplayDate($row['rank_date'])."</td>";
				$output .="<td>".$row['city_name']."</td>";
				$output .="<td>".$row['state_name']."</td>";
				$output .="<td>".$row['member_mobile']."</td>";
			$output .="</tr>";
			$i++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}

	function fpvinvoicereport(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>INVOICE DATE</td>";
			$output .="<td>INVOICE NO</td>";
			$output .="<td>ORDER NO</td>";
			$output .="<td>ORDER DATE</td>";
			$output .="<td>SHOPPE</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>CITY</td>";			
			$output .="<td>QTY</td>";
			$output .="<td>AMOUNT</td>";
			$output .="<td>FPV VALUE</td>";
			$output .="<td>DIFF. AMOUNT</td>";
			$output .="<td>TAX AMOUNT</td>";
			$output .="<td>TOTAL COLLECTED</td>";
			/*
			$output .="<td>TOTAL BV</td>";		
			$output .="<td>TOTAL PV</td>";
			*/
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			//$fldiNet = (($row['total_paid']-$row['coupon_val'])<0)? 0:($row['total_paid']-$row['coupon_val']);
			$QR_GST = "SELECT SUM(tod.post_pv*tod.post_qty) AS order_pv, SUM(tod.original_post_price*tod.post_qty) AS order_mrp,
						   SUM(tod.post_price*tod.post_qty) AS order_rcp,
						   tod.post_tax, tod.tax_age, tod.post_qty, tod.net_amount
						   FROM tbl_order_detail AS tod 
						   WHERE tod.order_id='".$row['order_id']."' 
						   GROUP BY tod.tax_age 
						   ORDER BY tod.order_detail_id ASC"; 
			$RS_GST = $this->SqlModel->runQuery($QR_GST);
			foreach($RS_GST as $AR_GST):
				/*
				$AR_GST = $model->getOrderGst($row['order_id']);
				$order_12_tax = $AR_GST['12']['order_tax_devide'];
				$order_28_tax = $AR_GST['28']['order_tax_devide'];
				$order_gst_tax = $order_12_tax+$order_28_tax;
				$order_total_tax = $order_gst_tax*2;				
				*/
				$post_tax_gst = $AR_GST['tax_age'];
				$order_tax_devide_gst = $post_tax_gst/2;
				$order_rcp_gst = ( $AR_GST['order_rcp']  /  ( ($post_tax_gst/100)+1 ) );								
				$order_tax_calc_gst = ($order_rcp_gst*$order_tax_devide_gst)/100;
				$sum_order_rcp_gst +=$order_rcp_gst;
				$sum_order_tax_calc_gst +=$order_tax_calc_gst;
			endforeach;
			if($row['order_id']>4309){
		$fldiTax = round($sum_order_tax_calc_gst,2)*2; 
		if(($row['total_paid']-$row['coupon_val'])<0){ 
		$fldiNet = round($sum_order_tax_calc_gst*2);}else{$fldiNet = round(($row['total_paid']-$row['coupon_val'])+($sum_order_tax_calc_gst*2));}
			}else{ 
		$fldiTax = 0;
		$fldiNet = round(($row['total_paid']-$row['coupon_val'])+($order_gst_tax*2));
			}
			if(($row['total_paid']-$row['coupon_val'])<0) $fldiDiff=0; else $fldiDiff = ($row['total_paid']-$row['coupon_val']);
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".DisplayDate($row['invoice_date'])."</td>";
				$output .="<td>".$row['invoice_number']."</td>";
				$output .="<td>".$row['order_no']."</td>";
				$output .="<td>".DisplayDate($row['date_add'])."</td>";
				$output .="<td>".$row['user_name']."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['city_name']."</td>";	
				$output .="<td>".$row['total_products']."</td>";	
				$output .="<td>".number_format($row['total_paid'],2)."</td>";
				$output .="<td>".$row['coupon_val']."</td>";
				$output .="<td>".$fldiDiff."</td>";
				$output .="<td>".$fldiTax."</td>";
				$output .="<td>".$fldiNet."</td>";
				/*
				$output .="<td>".$row['total_bv']."</td>";
				$output .="<td>".$row['total_pv']."</td>";
				*/
			$output .="</tr>";
			$i++;
			unset($post_tax_gst,$order_tax_devide_gst,$order_rcp_gst,$order_tax_calc_gst,$sum_order_rcp_gst,$sum_order_tax_calc_gst,$fldiTax,$fldiNet,$fldiDiff);
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}

	function jumpcadrereport(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$today_date = InsertDate(getLocalTime());
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>User Id</td>";
			$output .="<td>User Name</td>";
			$output .="<td>Mobile</td>";
			$output .="<td>Start Date</td>";
			$output .="<td>From Rank</td>";
			$output .="<td>To Rank</td>";
			$output .="<td>End Date</td>";
			$output .="<td>Days Left</td>";			
			$output .="<td>PV TARGET STATUS</td>";
			$output .="<td>Total PV</td>";
			$output .="<td>Considered G1 PV</td>";		
			$output .="<td>Considered G2 & Above PV (Incl. Self)</td>";
			$output .="<td>Balance Target PV</td>";
			$output .="<td>GROUPWISE STATUS</td>";
			$output .="<td>Contest Status</td>";
			$output .="<td>&nbsp;</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $AR_DT):
		$date_join = InsertDate($AR_DT['date_join']);
		$date_manual = "2017-10-15";
		if(strtotime($date_join) <= strtotime($date_manual)){
			$date_join = InsertDate($date_manual);
			$QR_CADRE = "SELECT A.rank_id, B.rank_name FROM tbl_mem_15oct AS A, tbl_rank AS B WHERE A.member_id='$AR_DT[member_id]' AND 
						 A.rank_id=B.rank_id";
			$AR_CADRE = $this->SqlModel->runQuery($QR_CADRE,true);
			$rank_id = $AR_CADRE['rank_id'];
			$rank_name = $AR_CADRE['rank_name'];
		}else{
			$rank_id = 1;
			$rank_name = 'Consultant @ 6%';
		}
		$QR_HIS = "SELECT DATEDIFF(CURDATE(),'$date_join') AS num_days";
		$AR_HIS = $this->SqlModel->runQuery($QR_HIS,true);
		$slab = floor($AR_HIS['num_days']/20);
		$days_start = 20*($slab+0);
		$days_total = 20*($slab+1);
		$from_rank = ($rank_id+floor($AR_HIS['num_days']/20));
		$QR_RANK = "SELECT * FROM tbl_rank AS tr WHERE tr.rank_id >='".$from_rank."' AND  tr.rank_id<='5' ORDER BY tr.rank_id ASC 
		LIMIT 1";
		$RS_RANK = $this->SqlModel->runQuery($QR_RANK);
		foreach($RS_RANK as $AR_RANK):
			/*
			$new_rank = $model->getRankName($AR_RANK['rank_id']+1);
			$QR_NRANK = "SELECT month_target FROM tbl_rank WHERE rank_id = $AR_RANK[rank_id]+1";
			$AR_NRANK = $this->SqlModel->runQuery($QR_NRANK,true);
			$max_collection = round($AR_NRANK['month_target']/2);
			$date_end = InsertDate(AddToDate($date_join,"+ $days_total Day"));
			$day_diff = dayDiff($today_date,$date_end);
			$QR_SELF = "SELECT 
						tm.member_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id, tm.rank_id, tree.nlevel, 
						tree.nleft, tree.nright, biz.self_pv, biz.group_pv, biz.total_pv, tr.rank_name FROM tbl_members AS tm	
						LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
						LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
						LEFT JOIN tbl_mem_biz AS biz ON biz.member_id=tm.member_id
						WHERE tm.member_id='$AR_DT[member_id]'";
			$AR_SELF = $this->SqlModel->runQuery($QR_SELF,true);
			$QR_GONE = "SELECT 
						tm.member_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id, tm.rank_id, tree.nlevel, 
						tree.nleft, tree.nright, biz.self_pv, biz.group_pv, biz.total_pv, tr.rank_name FROM tbl_members AS tm	
						LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
						LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
						LEFT JOIN tbl_mem_biz AS biz ON biz.member_id=tm.member_id
						WHERE tm.sponsor_id='$AR_DT[member_id]' AND biz.total_pv>'0' ORDER BY biz.total_pv DESC LIMIT 1";
			$AR_GONE = $this->SqlModel->runQuery($QR_GONE,true);
			if($AR_GONE['total_pv']>=$max_collection) $CONS_GONE=$max_collection; else $CONS_GONE=$AR_GONE['total_pv'];
			$QR_GTWO = "SELECT SUM(total_pv) AS total_pv FROM tbl_mem_biz WHERE sponsor_id='$AR_DT[member_id]' AND total_pv>'0' AND 
						member_id!='$AR_GONE[member_id]' GROUP BY sponsor_id";
			$AR_GTWO = $this->SqlModel->runQuery($QR_GTWO,true);
			if($AR_GTWO['total_pv']>=$max_collection) $CONS_GTWO=$max_collection; else $CONS_GTWO=$AR_GTWO['total_pv'];
			if(($AR_GONE['total_pv']+$AR_GTWO['total_pv']+$AR_SELF['self_pv'])>=$AR_NRANK['month_target']) 
				$cadre_status = "DONE"; else $cadre_status = "RUNNING";
			if(($CONS_GONE+$CONS_GTWO+$AR_SELF['self_pv'])>=$AR_NRANK['month_target']) 
				$qualify_status = "DONE"; else $qualify_status = "RUNNING";
			$contest_status = ($cadre_status=="DONE" && $qualify_status=="DONE")? "ACHIEVED":"RUNNING";
			*/
			$start_date = InsertDate(AddToDate($date_join,"+ $days_start Day"));
			$end_date = InsertDate(AddToDate($date_join,"+ $days_total Day")); 
			$new_rank = $model->getRankName($AR_RANK['rank_id']+1);
			$QR_NRANK = "SELECT month_target FROM tbl_rank WHERE rank_id = $AR_RANK[rank_id]+1";
			$AR_NRANK = $this->SqlModel->runQuery($QR_NRANK,true);
			$max_collection = round($AR_NRANK['month_target']/2);
			$date_end = InsertDate(AddToDate($date_join,"+ $days_total Day"));
			$day_diff = dayDiff($today_date,$date_end);
			$QR_SELF = "SELECT 
						tm.member_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id, tm.rank_id, tree.nlevel, 
						tree.nleft, tree.nright, biz.self_pv, biz.group_pv, biz.total_pv, tr.rank_name FROM tbl_members AS tm	
						LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
						LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
						LEFT JOIN tbl_mem_biz AS biz ON biz.member_id=tm.member_id
						WHERE tm.member_id='$AR_DT[member_id]'";
			$AR_SELF = $this->SqlModel->runQuery($QR_SELF,true);
			$QR_GONE = "SELECT 
						tm.member_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id, tm.rank_id, tree.nlevel, 
						tree.nleft, tree.nright, biz.self_pv, biz.group_pv, biz.total_pv, tr.rank_name FROM tbl_members AS tm	
						LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
						LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
						LEFT JOIN tbl_mem_biz AS biz ON biz.member_id=tm.member_id
						WHERE tm.sponsor_id='$AR_DT[member_id]' AND biz.total_pv>'0' ORDER BY biz.total_pv DESC LIMIT 1";
			$AR_GONE = $this->SqlModel->runQuery($QR_GONE,true);
			if($AR_GONE['total_pv']>=$max_collection) $CONS_GONE=$max_collection; else $CONS_GONE=$AR_GONE['total_pv'];
			$QR_GTWO = "SELECT SUM(total_pv) AS total_pv FROM tbl_mem_biz WHERE sponsor_id='$AR_DT[member_id]' AND total_pv>'0' AND 
						member_id!='$AR_GONE[member_id]' GROUP BY sponsor_id";
			$AR_GTWO = $this->SqlModel->runQuery($QR_GTWO,true);
			if($AR_GTWO['total_pv']>=$max_collection) $CONS_GTWO=$max_collection; else $CONS_GTWO=$AR_GTWO['total_pv'];
			if(($AR_GONE['total_pv']+$AR_GTWO['total_pv']+$AR_SELF['self_pv'])>=$AR_NRANK['month_target']) 
				$cadre_status = "DONE"; else $cadre_status = "RUNNING";
			if(($CONS_GONE+$CONS_GTWO+$AR_SELF['self_pv'])>=$AR_NRANK['month_target']) 
				$qualify_status = "DONE"; else $qualify_status = "RUNNING";
			$contest_status = ($cadre_status=="DONE" && $qualify_status=="DONE")? "ACHIEVED":"RUNNING";
			if(($AR_GTWO['total_pv']+$AR_SELF['self_pv'])>=($AR_NRANK['month_target']-$max_collection))  
			$CONS_G2 = (($AR_NRANK['month_target']-$max_collection)); else $CONS_G2 = (($AR_GTWO['total_pv']+$AR_SELF['self_pv']));
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$AR_DT['user_id']."</td>";
				$output .="<td>".strtoupper($AR_DT['full_name'])."</td>";
				$output .="<td>".$AR_DT['member_mobile']."</td>";
				$output .="<td>".DisplayDate($start_date)."</td>";
				$output .="<td>".$AR_RANK['rank_name']."</td>";
				$output .="<td>".$new_rank."</td>";
				$output .="<td>".DisplayDate($end_date)."</td>";
				$output .="<td>".$day_diff."</td>";	
				$output .="<td>".$cadre_status."</td>";	
				$output .="<td>".($AR_GONE['total_pv']+$AR_GTWO['total_pv']+$AR_SELF['self_pv'])."</td>";
				$output .="<td>".($CONS_GONE)."</td>";
				$output .="<td>".($CONS_GTWO+$AR_SELF['self_pv'])."</td>";
				$output .="<td>".($AR_NRANK['month_target']-($CONS_GONE+$CONS_GTWO+$AR_SELF['self_pv']))."</td>";
				$output .="<td>".$qualify_status."</td>";	
				$output .="<td>".$contest_status."</td>";	
				$output .="<td>&nbsp;</td>";	
			$output .="</tr>";
			$i++;
			$days_total += 20;
			endforeach;
			unset($CONS_GONE,$CONS_GTWO,$date_end,$cadre_status,$qualify_status,$contest_status,$CONS_G2,$show_rank,$days_total,$day_diff);
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}

	function assignvoucher(){
		$model = new OperationModel();
		
		$flddToday = InsertDate(getLocalTime());
		$flddExpire = AddToDate($flddToday, '-1 Day');
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td colspan='5'>ASSIGNED TO</td>";
			$output .="<td>FPV NO</td>";
			$output .="<td>FPV VALUE</td>";			
			$output .="<td>INVOICE NO</td>";
			$output .="<td>INVOICE DATE</td>";
			$output .="<td>AP</td>";
			$output .="<td colspan='3'>PURCHASED BY</td>";		
			$output .="<td>ISSUED ON</td>";
			$output .="<td>EXPIRY</td>";
			$output .="<td>DAYS LEFT</td>";
			$output .="<td>STATUS</td>";
		$output .="</tr>";
		$output .="<tr>";
			$output .="<td></td>";
			$output .="<td>User Id</td>";
			$output .="<td>User Name</td>";
			$output .="<td>Rank</td>";
			$output .="<td>CITY</td>";
			$output .="<td>MOBILE</td>";
			$output .="<td></td>";
			$output .="<td></td>";			
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td>User Id</td>";		
			$output .="<td>User Name</td>";
			$output .="<td>CITY</td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
		if($row['current_status']!='Unused') $days_left=0; else $days_left=dayDiff($flddExpire,$row['expires_on']);
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['rank_name']."</td>";	
				$output .="<td>".$row['city_name']."</td>";
				$output .="<td>".$row['member_mobile']."</td>";	
				$output .="<td>".$row['coupon_no']."</td>";
				$output .="<td>".$row['coupon_val']."</td>";
				$output .="<td>".$row['invoice_no']."</td>";
				$output .="<td>".DisplayDate($model->getInvoiceDate($row['invoice_no']))."</td>";
				$output .="<td>".$row['order_rcp']."</td>";
				$output .="<td>".$row['from_id']."</td>";
				$output .="<td>".$row['from_name']."</td>";
				$output .="<td>".$row['from_city']."</td>";	
				$output .="<td>".DisplayDate($row['assigned_on'])."</td>";
				$output .="<td>".DisplayDate($row['expires_on'])."</td>";
				$output .="<td>".$days_left."</td>";
				$output .="<td>".$row['current_status']."</td>";
			$output .="</tr>";
			$i++;
		unset($days_left);
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}

	function manualfpvreport(){
		$model = new OperationModel();
		
		$today_date = InsertDate(getLocalTime());
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td colspan='5'>ASSIGNED TO</td>";
			$output .="<td>FPV NO</td>";
			$output .="<td>FPV VALUE</td>";			
			$output .="<td>ASSIGNED BY</td>";		
			$output .="<td>ISSUED ON</td>";
			$output .="<td>EXPIRY</td>";
			$output .="<td>DAYS LEFT</td>";
			$output .="<td>STATUS</td>";
		$output .="</tr>";
		$output .="<tr>";
			$output .="<td></td>";
			$output .="<td>User Id</td>";
			$output .="<td>User Name</td>";
			$output .="<td>Rank</td>";
			$output .="<td>CITY</td>";
			$output .="<td>MOBILE</td>";
			$output .="<td></td>";
			$output .="<td></td>";			
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['rank_name']."</td>";	
				$output .="<td>".$row['city_name']."</td>";
				$output .="<td>".$row['member_mobile']."</td>";	
				$output .="<td>".$row['coupon_no']."</td>";
				$output .="<td>".$row['coupon_val']."</td>";
				$output .="<td>".$row['user_name']."</td>";
				$output .="<td>".DisplayDate($row['assigned_on'])."</td>";
				$output .="<td>".DisplayDate($row['expires_on'])."</td>";
				$output .="<td>".dayDiff($today_date,$row['expires_on'])."</td>";
				$output .="<td>".$row['current_status']."</td>";
			$output .="</tr>";
			$i++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function offerinvoices(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>INVOICE NO</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>DATE</td>";
			$output .="<td>INVOICE TOTAL</td>";			
			$output .="<td>NOS</td>";
			$output .="<td>VALUE</td>";
			$output .="<td>AMOUNT COLLECTED</td>";		
			$output .="<td>OFFER VALUE</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		$QR_OFFER = "SELECT A.post_id, A.post_mrp, A.post_discount, A.post_price, C.offer_module, C.offer_title, C.offer_price FROM tbl_post_lang AS A, 
		tbl_offer_product AS B, tbl_offer AS C WHERE A.post_id = B.post_id AND B.offer_id = '$_REQUEST[offer_id]' AND B.offer_id = C.offer_id";
		$AR_OFFER = $this->SqlModel->runQuery($QR_OFFER,true);
		foreach($fetchRows as $AR_DT):
			$QRY_CNT ="SELECT SUM(post_qty) AS icnt FROM tbl_order_detail WHERE order_id='$AR_DT[order_id]' AND offer_id='0'";
			$RS_CNT = $this->db->query($QRY_CNT);
			$AR_CNT = $RS_CNT->row_array();
			$QRY_VAL ="SELECT A.post_qty, B.post_price, SUM(A.post_qty*B.post_price) AS itotal FROM tbl_order_detail AS A, tbl_post_lang AS B WHERE 
				A.order_id='$AR_DT[order_id]' AND A.offer_id='0' AND A.post_id=B.post_id";
			$RS_VAL = $this->db->query($QRY_VAL);
			$AR_VAL = $RS_VAL->row_array();
			if($AR_OFFER['offer_module']=='OPOF'){
				$pos = strpos($AR_OFFER['offer_title'],'50 50 MRP OFFER');
				if($pos===false){
				$amount_collected = ($AR_OFFER['post_discount']+1)*$AR_CNT['icnt'];
				}else{
				$amount_collected = ($AR_OFFER['post_discount']*$AR_CNT['icnt']);	
				}
			}elseif($AR_OFFER['offer_module']=='FPOF'){
				$amount_collected = ($AR_OFFER['offer_price']*$AR_CNT['icnt']);
			}else{
				$amount_collected = $AR_CNT['icnt']*1;
			}
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$AR_DT['invoice_number']."</td>";
				$output .="<td>".$AR_DT['user_id']."</td>";
				$output .="<td>".$AR_DT['full_name']."</td>";
				$output .="<td>".DisplayDate($AR_DT['invoice_date'])."</td>";
				$output .="<td>".number_format($AR_DT['total_paid'],2)."</td>";
				$output .="<td>".$AR_CNT['icnt']."</td>";
				$output .="<td>".$AR_VAL['itotal']."</td>";	
				$output .="<td>".$amount_collected."</td>";	
				$output .="<td>".($AR_VAL['itotal']-$amount_collected)."</td>";
			$output .="</tr>";
			$i++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}	

	function viewofferreport(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		

		$from_date = InsertDate($segment['date_from']);
		$to_date = InsertDate($segment['date_to']);
		$new_all = FCrtRplc($segment['new_all']);
		switch($new_all){
			case 0:
				$SrchW = "BETWEEN '$from_date' AND '$to_date'";
			break;
			case 1:
				$SrchW = "<='$to_date'";
			break;
		}
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>INVOICE NO</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>DATE</td>";
			$output .="<td>INVOICE TOTAL</td>";			
			$output .="<td>NOS</td>";
			$output .="<td>VALUE</td>";
			$output .="<td>AMOUNT COLLECTED</td>";		
			$output .="<td>OFFER VALUE</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		$QR_OFFER = "SELECT A.post_id, A.post_mrp, A.post_discount, A.post_price, C.offer_module, C.offer_title, C.offer_price FROM tbl_post_lang AS A, 
		tbl_offer_product AS B, tbl_offer AS C WHERE A.post_id = B.post_id AND B.offer_id = '$_REQUEST[offer_id]' AND B.offer_id = C.offer_id";
		$AR_OFFER = $this->SqlModel->runQuery($QR_OFFER,true);
		foreach($fetchRows as $AR_DT):
			$QRY_CNT ="SELECT SUM(post_qty) AS icnt FROM tbl_order_detail WHERE order_id='$AR_DT[order_id]' AND offer_id='0'";
			$RS_CNT = $this->db->query($QRY_CNT);
			$AR_CNT = $RS_CNT->row_array();
			$QRY_VAL ="SELECT A.post_qty, B.post_price, SUM(A.post_qty*B.post_price) AS itotal FROM tbl_order_detail AS A, tbl_post_lang AS B WHERE 
				A.order_id='$AR_DT[order_id]' AND A.offer_id='0' AND A.post_id=B.post_id";
			$RS_VAL = $this->db->query($QRY_VAL);
			$AR_VAL = $RS_VAL->row_array();
			if($AR_OFFER['offer_module']=='OPOF'){
				$pos = strpos($AR_OFFER['offer_title'],'50 50 MRP OFFER');
				if($pos===false){
				$amount_collected = ($AR_OFFER['post_discount']+1)*$AR_CNT['icnt'];
				}else{
				$amount_collected = ($AR_OFFER['post_discount']*$AR_CNT['icnt']);	
				}
			}elseif($AR_OFFER['offer_module']=='FPOF'){
				$amount_collected = ($AR_OFFER['offer_price']*$AR_CNT['icnt']);
			}else{
				$amount_collected = $AR_CNT['icnt']*1;
			}
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$AR_DT['invoice_number']."</td>";
				$output .="<td>".$AR_DT['user_id']."</td>";
				$output .="<td>".$AR_DT['full_name']."</td>";
				$output .="<td>".DisplayDate($AR_DT['invoice_date'])."</td>";
				$output .="<td>".number_format($AR_DT['total_paid'],2)."</td>";
				$output .="<td>".$AR_CNT['icnt']."</td>";
				$output .="<td>".$AR_VAL['itotal']."</td>";	
				$output .="<td>".$amount_collected."</td>";	
				$output .="<td>".($AR_VAL['itotal']-$amount_collected)."</td>";
			$output .="</tr>";
			$i++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}	

	function sixtypvreport(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>CITY</td>";
			$output .="<td>STATE</td>";			
			$output .="<td>MOBILE</td>";
			$output .="<td>Rank</td>";
			$output .="<td colspan=3>2017</td>";
			$output .="<td colspan=8>2018</td>";
		$output .="</tr>";
		$output .="<tr>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";			
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td>OCT</td>";
			$output .="<td>NOV</td>";
			$output .="<td>DEC</td>";
			$output .="<td>JAN</td>";
			$output .="<td>FEB</td>";
			$output .="<td>MAR</td>";
			$output .="<td>APR</td>";
			$output .="<td>MAY</td>";
			$output .="<td>JUN</td>";
			$output .="<td>JUL</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $AR_DT):
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$AR_DT['user_id']."</td>";
				$output .="<td>".$AR_DT['full_name']."</td>";
				$output .="<td>".$AR_DT['city_name']."</td>";
				$output .="<td>".$AR_DT['state_name']."</td>";
				$output .="<td>".$AR_DT['member_mobile']."</td>";
				$output .="<td>".$AR_DT['rank_name']."</td>";
				for($j=10; $j<=12; $j++){
				$QR_PV = "SELECT IFNULL(SUM(total_pv),0) AS total_pv FROM tbl_orders WHERE member_id='$AR_DT[member_id]' AND total_pv>='60' AND 
					  	  MONTH(invoice_date)='$j' AND YEAR(invoice_date)='2017' AND DATE(invoice_date)>='2017-10-15' ORDER BY order_id ASC LIMIT 1";
				$AR_PV = $this->SqlModel->runQuery($QR_PV,true);
				$output .="<td>".$AR_PV['total_pv']."</td>";
				}
				for($k=1; $k<=date("n"); $k++){
				$QR_PV_18 = "SELECT IFNULL(SUM(total_pv),0) AS total_pv FROM tbl_orders WHERE member_id='$AR_DT[member_id]' AND total_pv>='60' AND 
					  	     MONTH(invoice_date)='$k' AND YEAR(invoice_date)='2018' AND DATE(invoice_date)>='2017-10-15' ORDER BY order_id ASC LIMIT 1";
				$AR_PV_18 = $this->SqlModel->runQuery($QR_PV_18,true);
				$output .="<td>".$AR_PV_18['total_pv']."</td>";
				}
			$output .="</tr>";
		$i++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}	

	function sixtypvteamreport(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";		
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>CITY</td>";
			$output .="<td>STATE</td>";			
			$output .="<td>MOBILE</td>";
			$output .="<td>Rank</td>";
			$output .="<td colspan=6>2017</td>";
			$output .="<td colspan=10>2018</td>";
		$output .="</tr>";
		$output .="<tr>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";			
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td colspan=2>OCT</td>";
			$output .="<td colspan=2>NOV</td>";
			$output .="<td colspan=2>DEC</td>";
			$output .="<td colspan=2>JAN</td>";
			$output .="<td colspan=2>FEB</td>";
			$output .="<td colspan=2>MAR</td>";
			$output .="<td colspan=2>APR</td>";
			$output .="<td colspan=2>MAY</td>";
			$output .="<td colspan=2>JUN</td>";
			$output .="<td colspan=2>JUL</td>";
		$output .="</tr>";
		$output .="<tr>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";			
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
			$output .="<td>G1</td>";
			$output .="<td>G2+</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$j=1;
		foreach($fetchRows as $AR_DT):
			$output .="<tr>";
				$output .="<td>".$j."</td>";
				$output .="<td>".$AR_DT['user_id']."</td>";
				$output .="<td>".$AR_DT['full_name']."</td>";
				$output .="<td>".$AR_DT['city_name']."</td>";
				$output .="<td>".$AR_DT['state_name']."</td>";
				$output .="<td>".$AR_DT['member_mobile']."</td>";
				$output .="<td>".$AR_DT['rank_name']."</td>";
				for($i=10; $i<=12; $i++){
				$QR_TOTAL = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS iCnt 
							 FROM tbl_orders AS ord 
							 LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
							 WHERE ord.total_pv>='60' AND MONTH(ord.invoice_date)='$i' 
							 AND YEAR(ord.invoice_date)='2017' AND DATE(ord.invoice_date)>='2017-10-15' 
							 AND tm.nleft BETWEEN '$AR_DT[nleft]' AND '$AR_DT[nright]'";
				$AR_TOTAL = $this->SqlModel->runQuery($QR_TOTAL,true);
				$QR_DIRECT = "SELECT A.member_id, B.nleft, B.nright 
							  FROM tbl_members AS A 
							  LEFT JOIN tbl_mem_tree AS B ON A.member_id=B.member_id 
							  WHERE A.sponsor_id='$AR_DT[member_id]' 
							  ORDER BY A.member_id ASC";
				$RS_DIRECT = $this->SqlModel->runQuery($QR_DIRECT);
				$member_id = 0;
				$count = 0;
				foreach($RS_DIRECT as $AR_DIRECT):
					$QR_COUNT = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS iCnt 
								 FROM tbl_orders AS ord 
								 LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
								 WHERE ord.total_pv>='60' AND MONTH(ord.invoice_date)='$i' 
								 AND YEAR(ord.invoice_date)='2017' AND DATE(ord.invoice_date)>='2017-10-15' 
								 AND tm.nleft BETWEEN '$AR_DIRECT[nleft]' AND '$AR_DIRECT[nright]'";
					$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
					if($AR_COUNT['iCnt']>$count){
						$member_id = $AR_DIRECT['member_id'];
						$count = $AR_COUNT['iCnt'];
					}
				endforeach;
				$output .="<td>".$count."</td>";
				$output .="<td>".($AR_TOTAL['iCnt']-$count)."</td>";
				unset($member_id, $count);
				}
				for($k=1; $k<=date("n"); $k++){
				$QR_TOTAL_18 = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS iCnt 
							    FROM tbl_orders AS ord 
							    LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
							    WHERE ord.total_pv>='60' AND MONTH(ord.invoice_date)='$k' 
								AND YEAR(ord.invoice_date)='2018' AND DATE(ord.invoice_date)>='2017-10-15' 
							    AND tm.nleft BETWEEN '$AR_DT[nleft]' AND '$AR_DT[nright]'";
				$AR_TOTAL_18 = $this->SqlModel->runQuery($QR_TOTAL_18,true);
				$QR_DIRECT_18 = "SELECT A.member_id, B.nleft, B.nright 
							     FROM tbl_members AS A 
							     LEFT JOIN tbl_mem_tree AS B ON A.member_id=B.member_id 
							     WHERE A.sponsor_id='$AR_DT[member_id]' 
							     ORDER BY A.member_id ASC";
				$RS_DIRECT_18 = $this->SqlModel->runQuery($QR_DIRECT_18);
				$member_id_18 = 0;
				$count_18 = 0;
				foreach($RS_DIRECT_18 as $AR_DIRECT_18):
					$QR_COUNT_18 = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS iCnt 
								    FROM tbl_orders AS ord 
								    LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
								    WHERE ord.total_pv>='60' AND MONTH(ord.invoice_date)='$k' 
									AND YEAR(ord.invoice_date)='2018' AND DATE(ord.invoice_date)>='2017-10-15' 
								    AND tm.nleft BETWEEN '$AR_DIRECT_18[nleft]' AND '$AR_DIRECT_18[nright]'";
					$AR_COUNT_18 = $this->SqlModel->runQuery($QR_COUNT_18,true);
					if($AR_COUNT_18['iCnt']>$count_18){
						$member_id_18 = $AR_DIRECT_18['member_id'];
						$count_18 = $AR_COUNT_18['iCnt'];
					}
				endforeach;
				$output .="<td>".$count_18."</td>";
				$output .="<td>".($AR_TOTAL_18['iCnt']-$count_18)."</td>";
				unset($member_id_18, $count_18);
				}
			$output .="</tr>";
		$j++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function sixtypvspecialreport(){
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');

		$from_date = InsertDate($PARAM['date_from']);
		$to_date = InsertDate($PARAM['date_to']);	
		$new_all = FCrtRplc($PARAM['new_all']);
				
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>User Name</td>";
			$output .="<td>User Id</td>";
			$output .="<td>CITY</td>";
			$output .="<td>STATE</td>";			
			$output .="<td>MOBILE</td>";
			$output .="<td>Rank</td>";
			$output .="<td colspan=2>DIRECT</td>";
			$output .="<td colspan=2>G1</td>";
			$output .="<td colspan=2>G2+</td>";
		$output .="</tr>";
		$output .="<tr>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td></td>";			
			$output .="<td></td>";
			$output .="<td></td>";
			$output .="<td>ENROLMENTS</td>";
			$output .="<td>60 PV</td>";
			$output .="<td>ENROLMENTS</td>";
			$output .="<td>60 PV</td>";
			$output .="<td>ENROLMENTS</td>";
			$output .="<td>60 PV</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $AR_DT):
		
			$SrchW = "BETWEEN '$from_date' AND '$to_date'";
			
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$AR_DT['user_id']."</td>";
				$output .="<td>".$AR_DT['full_name']."</td>";
				$output .="<td>".$AR_DT['city_name']."</td>";
				$output .="<td>".$AR_DT['state_name']."</td>";
				$output .="<td>".$AR_DT['member_mobile']."</td>";
				$output .="<td>".$AR_DT['rank_name']."</td>";
				
				//DIRECT
				$QR_DIRECT = "SELECT COUNT(tm.member_id) AS DirectCount   
							  FROM tbl_members AS tm 
							  WHERE tm.sponsor_id='$AR_DT[member_id]' 
							  AND DATE(tm.date_join) $SrchW";
				$AR_DIRECT = $this->SqlModel->runQuery($QR_DIRECT,true);
				
				switch($new_all){
					case 0:
						$QR_COUNT = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS DirectOrder 
									 FROM tbl_orders AS ord 
									 LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
									 WHERE ord.total_pv>='60' 
									 AND DATE(ord.invoice_date) $SrchW 
									 AND tm.sponsor_id='$AR_DT[member_id]' AND DATE(tm.date_join) $SrchW";
					break;
					case 1:
						$QR_COUNT = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS DirectOrder 
									 FROM tbl_orders AS ord 
									 LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
									 WHERE ord.total_pv>='60' 
									 AND DATE(ord.invoice_date) $SrchW 
									 AND tm.sponsor_id='$AR_DT[member_id]'";
					break;
				}
				$AR_COUNT = $this->SqlModel->runQuery($QR_COUNT,true);
				
				//GROUP ONE
				$QR_MYREF = "SELECT A.member_id, B.nleft, B.nright 
							 FROM tbl_members AS A 
							 LEFT JOIN tbl_mem_tree AS B ON A.member_id=B.member_id 
							 WHERE A.sponsor_id='$AR_DT[member_id]' 
							 ORDER BY A.member_id ASC";
				$RS_MYREF = $this->SqlModel->runQuery($QR_MYREF);
				$G1PCID = 0;
				$G1COUNT = 0;
				foreach($RS_MYREF as $AR_MYREF):
					switch($new_all){
						case 0:	
							$QR_REFCOUNT = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS iCnt 
											FROM tbl_orders AS ord 
											LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
											WHERE ord.total_pv>='60' 
											AND DATE(ord.invoice_date) $SrchW 
											AND tm.nleft BETWEEN '$AR_MYREF[nleft]' AND '$AR_MYREF[nright]' 
											AND DATE(tm.date_join) $SrchW";
						break;
						case 1:
							$QR_REFCOUNT = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS iCnt 
											FROM tbl_orders AS ord 
											LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
											WHERE ord.total_pv>='60' 
											AND DATE(ord.invoice_date) $SrchW 
											AND tm.nleft BETWEEN '$AR_MYREF[nleft]' AND '$AR_MYREF[nright]'";
						break;
					}
					$AR_REFCOUNT = $this->SqlModel->runQuery($QR_REFCOUNT,true);
					$i = $AR_REFCOUNT['iCnt'];
					if($i>$G1COUNT){
						$G1PCID = $AR_MYREF['member_id'];
						$G1COUNT = $i;	
					}
					unset($i);
				endforeach;
				$QR_G1REF = "SELECT COUNT(tm.member_id) AS iCnt   
							 FROM tbl_mem_tree AS tm 
							 WHERE tm.nleft 
							 BETWEEN (SELECT nleft FROM tbl_mem_tree WHERE member_id='$G1PCID') 
							 AND (SELECT nright FROM tbl_mem_tree WHERE 
							 member_id='$G1PCID') 
							 AND tm.date_join $SrchW";
				$AR_G1REF = $this->SqlModel->runQuery($QR_G1REF,true);
				
				//Group 2+
				$QR_TMEM = "SELECT COUNT(tm.member_id) AS TotalMember 
							FROM tbl_mem_tree AS tm 
							WHERE tm.nleft BETWEEN '$AR_DT[dleft]' AND '$AR_DT[nright]' AND DATE(tm.date_join) $SrchW   
							AND tm.member_id NOT IN (SELECT member_id FROM tbl_mem_tree WHERE nleft BETWEEN (SELECT nleft FROM tbl_mem_tree WHERE 
							member_id='$G1PCID') AND (SELECT nright FROM tbl_mem_tree WHERE member_id='$G1PCID') AND DATE(date_join) $SrchW)";
				$AR_TMEM = $this->SqlModel->runQuery($QR_TMEM,true);
				
				switch($new_all){
					case 0:
						$QR_TCNT = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS TotalOrder  
									FROM tbl_orders AS ord 
									LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
									WHERE ord.total_pv>='60' 
									AND DATE(ord.invoice_date) $SrchW  
									AND tm.nleft BETWEEN '$AR_DT[dleft]' AND '$AR_DT[nright]' 
									AND DATE(tm.date_join) $SrchW 
									AND tm.member_id NOT IN (SELECT member_id FROM tbl_mem_tree WHERE nleft BETWEEN (SELECT nleft FROM tbl_mem_tree WHERE 
									member_id='$G1PCID') AND (SELECT nright FROM tbl_mem_tree WHERE member_id='$G1PCID') AND DATE(date_join) $SrchW)";
					break;
					case 1:
						$QR_TCNT = "SELECT IFNULL(COUNT(DISTINCT ord.member_id),0) AS TotalOrder  
									FROM tbl_orders AS ord 
									LEFT JOIN tbl_mem_tree AS tm ON tm.member_id=ord.member_id 
									WHERE ord.total_pv>='60' 
									AND DATE(ord.invoice_date) $SrchW  
									AND tm.nleft BETWEEN '$AR_DT[dleft]' AND '$AR_DT[nright]' 
									AND tm.member_id NOT IN (SELECT member_id FROM tbl_mem_tree WHERE nleft BETWEEN (SELECT nleft FROM tbl_mem_tree WHERE 
									member_id='$G1PCID') AND (SELECT nright FROM tbl_mem_tree WHERE member_id='$G1PCID'))";
					break;
				}
				$AR_TCNT = $this->SqlModel->runQuery($QR_TCNT,true);
							
				$output .="<td>".$AR_DIRECT['DirectCount']."</td>";
				$output .="<td>".$AR_COUNT['DirectOrder']."</td>";
				$output .="<td>".$AR_G1REF['iCnt']."</td>";
				$output .="<td>".$G1COUNT."</td>";
				$output .="<td>".$AR_TMEM['TotalMember']."</td>";
				$output .="<td>".$AR_TCNT['TotalOrder']."</td>";
			$output .="</tr>";
			
		$i++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	function royaltyqualifiers(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>User Id</td>";
			$output .="<td>User Name</td>";
			$output .="<td>PAN NO</td>";
			$output .="<td>INT. User Id</td>";
			$output .="<td>INT. User Name</td>";
			$output .="<td>CITY</td>";			
			$output .="<td>STATE</td>";
			$output .="<td>MOBILE</td>";
			$output .="<td>SELF 60PV</td>";
			$output .="<td>DIRECT 60PV</td>";
			$output .="<td>MONTH</td>";
			$output .="<td>ACHIEVED ON</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			switch($row['month_no']){
				case 1:
					$royalty_month = 'ENTRY 1ST';
				break;
				default:
					$royalty_month = 'REPEAT '.$row['month_no'].'ND';
				break;
			}
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['pan_no']."</td>";
				$output .="<td>".$row['sp_user_id']."</td>";
				$output .="<td>".$row['sp_full_name']."</td>";
				$output .="<td>".$row['city_name']."</td>";				
				$output .="<td>".$row['state_name']."</td>";
				$output .="<td>".$row['member_mobile']."</td>";
				$output .="<td>".$row['self_target']."</td>";
				$output .="<td>".$row['ref_target']."</td>";
				$output .="<td>".$royalty_month."</td>";
				$output .="<td>".DisplayDate($row['qualify_date'])."</td>";
			$output .="</tr>";
			$i++;
			unset($royalty_month);
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}	

	function gsthsnreport(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');

		$date_from = InsertDate($PARAM['date_from']);
		$date_to = InsertDate($PARAM['date_to']);	
		$franchisee_id = $PARAM['franchisee_id'];	
		if($franchisee_id>0){
			$StrWhr .= "AND (mstr.franchisee_id='$franchisee_id')";
		}
				
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>HSN CODE</td>";
			$output .="<td>ITEM DESCRIPTION</td>";
			$output .="<td>GST SLAB</td>";
			$output .="<td>QTY (PCS)</td>";
			$output .="<td>TAXABLE VALUE</td>";
			$output .="<td colspan=3>TAX AMOUNT</td>";			
			$output .="<td>TOTAL VALUE</td>";
		$output .="</tr>";
		$output .="<tr>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>IGST</td>";
			$output .="<td>SGST</td>";
			$output .="<td>CGST</td>";
			$output .="<td>&nbsp;</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			$Q_Qty_Ofr = "SELECT IFNULL(SUM(ord.post_qty),0) AS post_qty_sold 
						  FROM tbl_order_detail AS ord 
						  LEFT JOIN tbl_orders AS mstr ON mstr.order_id=ord.order_id 
						  WHERE ord.post_id='$row[post_id]' 
						  AND ord.in_offer='Y' 
						  AND DATE(mstr.invoice_date) BETWEEN '$date_from' AND '$date_to' 
						  $StrWhr";
			$AR_Qty_Ofr = $this->SqlModel->runQuery($Q_Qty_Ofr,true);
	
			$Q_Qty_Nfr = "SELECT IFNULL(SUM(ord.post_qty),0) AS post_qty_sold 
						  FROM tbl_order_detail AS ord 
						  LEFT JOIN tbl_orders AS mstr ON mstr.order_id=ord.order_id 
						  WHERE ord.post_id='$row[post_id]' 
						  AND ord.in_offer='N' 
						  AND DATE(mstr.invoice_date) BETWEEN '$date_from' AND '$date_to' 
						  $StrWhr";
			$AR_Qty_Nfr = $this->SqlModel->runQuery($Q_Qty_Nfr,true);

			$offer_value = ($AR_Qty_Ofr['post_qty_sold']*($row['post_bv']/(1+($row['tax_age']/100))));
			$no_offer_value = ($AR_Qty_Nfr['post_qty_sold']*$row['post_tax']);
			$taxable_value = ($offer_value+$no_offer_value); 
			$sgst_value = ($taxable_value*($row['tax_age']/2)/100);
			$cgst_value = ($taxable_value*($row['tax_age']/2)/100);
			$total_value = ($taxable_value+$sgst_value+$cgst_value);
			
			$total_qty += ($AR_Qty_Ofr['post_qty_sold']+$AR_Qty_Nfr['post_qty_sold']);
			$total_tax += $taxable_value;
			$total_sgst += $sgst_value;
			$total_cgst += $cgst_value;
			$gross_value += $total_value;
			
			if($taxable_value>0){
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['post_hsn']."</td>";
				$output .="<td>".$row['post_title']."</td>";
				$output .="<td>".$row['tax_age']."%</td>";
				$output .="<td>".($AR_Qty_Ofr['post_qty_sold']+$AR_Qty_Nfr['post_qty_sold'])."</td>";
				$output .="<td>".round($taxable_value,2)."</td>";
				$output .="<td>0</td>";				
				$output .="<td>".round($sgst_value,2)."</td>";
				$output .="<td>".round($cgst_value,2)."</td>";
				$output .="<td>".round($total_value,2)."</td>";
			$output .="</tr>";
			}
			$i++;
			unset($taxable_value,$sgst_value,$cgst_value,$total_value);
		endforeach;
		$output .="<tr>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>&nbsp;</td>";
			$output .="<td>".$total_qty."</td>";
			$output .="<td>".round($total_tax,2)."</td>";
			$output .="<td>0</td>";				
			$output .="<td>".round($total_sgst,2)."</td>";
			$output .="<td>".round($total_cgst,2)."</td>";
			$output .="<td>".round($gross_value,2)."</td>";
		$output .="</tr>";
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}	

	function specialincomequalifiers(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>User Id</td>";
			$output .="<td>User Name</td>";
			$output .="<td>PAN NO</td>";
			$output .="<td>CITY</td>";			
			$output .="<td>STATE</td>";
			$output .="<td>MOBILE</td>";
			$output .="<td>TOTAL</td>";
			$output .="<td>CONSIDERED</td>";
			$output .="<td>ACHIEVEMENT</td>";
			$output .="<td>ACHIEVED ON</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$i=1;
		foreach($fetchRows as $row):
			switch($row['month_no']){
				case 1:
					$royalty_month = 'ENTRY 1ST';
				break;
				default:
					$royalty_month = 'REPEAT '.$AR_DT['month_no'].'ND';
				break;
			}
			$output .="<tr>";
				$output .="<td>".$i."</td>";
				$output .="<td>".$row['user_id']."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['pan_no']."</td>";
				$output .="<td>".$row['city_name']."</td>";				
				$output .="<td>".$row['state_name']."</td>";
				$output .="<td>".$row['member_mobile']."</td>";
				$output .="<td>".$row['actual_cnt']."</td>";
				$output .="<td>".$row['total_cnt']."</td>";
				$output .="<td>".$row['rank_name']."</td>";
				$output .="<td>".DisplayDate($row['qualify_date'])."</td>";
			$output .="</tr>";
			$i++;
			unset($royalty_month);
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}		

	function specialincomeracingreport(){
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$PARAM = $this->session->userdata('PARAM');
		
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$output .='<table>';
		$output .="<tr>";
			$output .="<td colspan=6>&nbsp;</td>";
			$output .="<td colspan=2 align=center>ROYALTY WINNERS COUNT</td>";
			$output .="<td colspan=5>&nbsp;</td>";
		$output .="</tr>";
		$output .="<tr>";
			$output .="<td>SR NO</td>";
			$output .="<td>User Id</td>";
			$output .="<td>User Name</td>";
			$output .="<td>CITY</td>";
			$output .="<td>STATE</td>";
			$output .="<td>MOBILE</td>";
			$output .="<td>TOTAL</td>";
			$output .="<td>CONSIDERED</td>";
			$output .="<td>ACHIEVEMENT</td>";
			$output .="<td>PENDING</td>";
			$output .="<td>DAYS LEFT</td>";
			$output .="<td>END DATE</td>";
			$output .="<td>STATUS</td>";
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		$Ctrl=1;
		foreach($fetchRows as $AR_DT):
		if(strtotime($AR_DT['date_join'])<=strtotime("2018-02-01")){ $start_date = "2018-02-01"; }else{ $start_date = $AR_DT['date_join'];}
		for($i=1; $i<=3; $i++){
			switch($i){
				case 1:
					$position = 'SILVER';
					$end_date = AddToDate($start_date,"+60 Day");
					$total_req = 10;
					$single_line = 5;
					$Q_Qualify = "SELECT qualify_date FROM tbl_mem_special WHERE member_id='$member_id' AND my_rank='1'";
					$AR_Qualify = $this->SqlModel->runQuery($Q_Qualify,true);
				break;
				case 2:
					$position = 'GOLD';
					$end_date = AddToDate($start_date,"+120 Day");
					$total_req = 60;
					$single_line = 30;
					$Q_Qualify = "SELECT qualify_date FROM tbl_mem_special WHERE member_id='$member_id' AND my_rank='2'";
					$AR_Qualify = $this->SqlModel->runQuery($Q_Qualify,true);
				break;
				case 3:
					$position = 'PLATINUM';
					$end_date = AddToDate($start_date,"+180 Day");
					$total_req = 300;
					$single_line = 150;
					$Q_Qualify = "SELECT qualify_date FROM tbl_mem_special WHERE member_id='$member_id' AND my_rank='3'";
					$AR_Qualify = $this->SqlModel->runQuery($Q_Qualify,true);
				break;
			}
			if($AR_Qualify['qualify_date']!=''){ $qualify_date = $AR_Qualify['qualify_date']; }else{ $qualify_date = "0000-00-00";}
			$end_date = ($end_date<='2018-07-31')? $end_date:"2018-07-31";
			$day_diff = dayDiff($today_date,$end_date);
			$Q_Direct = "SELECT tm.member_id, DATE(tm.date_join) AS date_join, 
						 tree.nleft, tree.nright 
						 FROM tbl_members AS tm 
						 LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id 
						 WHERE tm.sponsor_id='$AR_DT[member_id]' 
						 ORDER BY tm.member_id ASC";
			$RS_Direct = $this->SqlModel->runQuery($Q_Direct);
			foreach($RS_Direct as $AR_Direct):
				$Q_Count = "SELECT COUNT(rlty.member_id) AS iCnt 
							FROM tbl_mem_royalty AS rlty 
							LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=rlty.member_id 
							WHERE tree.nleft BETWEEN '$AR_Direct[nleft]' AND '$AR_Direct[nright]' AND 
							rlty.qualify_date BETWEEN '$start_date' AND '$end_date' 
							AND rlty.month_no='1'";
				$AR_Count = $this->SqlModel->runQuery($Q_Count,true);
				$rlty_count = $AR_Count['iCnt'];
				$actual_cnt += $rlty_count;
				if($rlty_count>$single_line) $rlty_count=$single_line;
				$total_count += $rlty_count;
				unset($rlty_count);
			endforeach;
			$pending = $total_req-$total_count;
			$pending = ($pending>0)? $pending:"0";
			$qualify_status = ($pending>0)? "RUNNING":"ACHIEVED";
			if($qualify_status!='ACHIEVED'){
			$output .="<tr>";
				$output .="<td>".$Ctrl."</td>";
				$output .="<td>".$AR_DT['user_id']."</td>";
				$output .="<td>".$AR_DT['full_name']."</td>";
				$output .="<td>".$AR_DT['city_name']."</td>";				
				$output .="<td>".$AR_DT['state_name']."</td>";
				$output .="<td>".$AR_DT['member_mobile']."</td>";
				$output .="<td>".$actual_cnt."</td>";
				$output .="<td>".$total_count."</td>";
				$output .="<td>".$position."</td>";
				$output .="<td>".$pending."</td>";
				$output .="<td>".$day_diff."</td>";
				$output .="<td>".DisplayDate($end_date)."</td>";
				$output .="<td>".$qualify_status."</td>";
			$output .="</tr>";
			}
			unset($total_count,$total_req,$single_line,$actual_cnt,$pending,$qualify_date,$qualify_status);
		}
		unset($i);
		$Ctrl++;
		endforeach;
		$output .='</table>';
		
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}		

}
?>