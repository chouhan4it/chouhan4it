<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends MY_Controller {

	
	public function report(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
		$Str_Param = $_SERVER['QUERY_STRING']."&fldiRand=$fldiRand";
		if($action_request!=''){
			switch($action_request){
				case "EWALLET":
					$URL_RPT = generateSeoUrlMember("report","ewallet","");
					$ch = curl_init();
					if (!$ch){die("Couldn't initialize a cURL handle");}
					$ret = curl_setopt($ch, CURLOPT_URL,$URL_RPT);
					curl_setopt ($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $Str_Param);
					$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$HTMLCONTENT = curl_exec($ch);
					curl_close($ch);
					$FileName = "e-wallet_statement_".getLocalTime();
				break;
				case "ORDER":
					$URL_RPT = generateSeoUrlMember("report","order","");
					$ch = curl_init();
					if (!$ch){die("Couldn't initialize a cURL handle");}
					$ret = curl_setopt($ch, CURLOPT_URL,$URL_RPT);
					curl_setopt ($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $Str_Param);
					$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$HTMLCONTENT = curl_exec($ch);
					curl_close($ch);
					$FileName = "order_statement_".getLocalTime();
				break;
				case "COMMISSION":
					$URL_RPT = generateSeoUrlMember("report","commission","");
					$ch = curl_init();
					if (!$ch){die("Couldn't initialize a cURL handle");}
					$ret = curl_setopt($ch, CURLOPT_URL,$URL_RPT);
					curl_setopt ($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $Str_Param);
					$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$HTMLCONTENT = curl_exec($ch);
					curl_close($ch);
					$FileName = "commission_statement_".getLocalTime();
				break;
			}
			header('Content-Description: File Transfer');
			header('Content-Type: application/msexcel');
			header('Content-Disposition: attachment; filename='.$FileName.".xls");
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			echo $HTMLCONTENT;
			exit;
		}
	}
	
	public function directincome(){		
		$output = "";
		$member_id = $this->session->userdata('mem_id');
		$QR_SELECT = "SELECT CONCAT_WS(' ',first_name,last_name) AS FROM_MEMBER,  tcd.process_id AS PROCESS_NO, tcd.total_collection AS TOTAL_COLLECTION, 
		             tcd.income_percent AS DIRECT_PERCENT, tcd.total_income AS TOTAL_INCOME,  tcd.net_income AS NET_INCOME, tcd.date_time AS DATE_TIME  
					 FROM tbl_cmsn_direct AS tcd 
					 LEFT JOIN tbl_members AS tm ON tm.member_id=tcd.from_member_id
					 WHERE tcd.member_id='".$member_id."' $StrWhr ORDER BY tcd.direct_id DESC";
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
		$FileName="DIRECT_INCOME_DOWNLOAD_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	
	public function allincome(){
		$output = "";
		$member_id = $this->session->userdata('mem_id');
		$QR_SELECT = "SELECT CONCAT_WS(' ',first_name,last_name) AS MEMBER_NAME,  tcm.process_id AS PROCESS_NO, tcm.binary_income AS BINARY_INCOME, 
		             tcm.direct_income AS DIRECT_INCOME, tcm.total_income AS TOTAL_INCOME, tcm.admin_charge AS ADMIN_CHARGE,  
					 tcm.net_income AS NET_INCOME, tcm.date_time AS DATE_TIME 	 FROM tbl_cmsn_mstr AS tcm 
					 LEFT JOIN tbl_members AS tm ON tm.member_id=tcm.member_id
					 WHERE tcm.member_id='".$member_id."' $StrWhr ORDER BY tcm.master_id DESC";
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
		$FileName="DIRECT_INCOME_DOWNLOAD_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".csv";
		header('Content-type: application/csv');
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
			$output .="<td>Self BV ".getDateFormat($from_date,"F")." month</td>";
			
			$output .="<td>Group BV ".getDateFormat($from_date,"F")." month</td>";
			
			$output .="<td>Self accumulated BV till ".getDateFormat($from_date,"F-Y")."  month</td>";
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
			$output .="<td>".number_format($AR_SELF['total_pv'])."</td>";
			$output .="<td>".number_format($AR_GROUP['total_pv'])."</td>";
			$output .="<td>".number_format($TILL_SELF['total_pv'])."</td>";
			$output .="<td>".number_format($TILL_GROUP['total_pv'])."</td>";
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
	
	
	
	function packageinvoice(){
		$segment = $this->uri->uri_to_assoc(2);
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$subcription_id = _d($segment['subcription_id']);
	
		$AR_SUB = $model->getSubscription($subcription_id);
	
		$member_id = $AR_SUB['member_id'];
		$AR_MEM = $model->getMember($member_id);
	
		$AR_PACK = $model->getPinType($AR_SUB['type_id']);
	
		$output = "";

		$output .='<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"  class="table">';
		   $output .='<tr>';
              $output .='<td colspan="2" align="left"   ><img src="'.LOGO.'" width="200" alt="'.WEBSITE.'"></td>';
           $output .='</tr>';
           $output .='<tr>';
		   	$output .='<td><strong>&nbsp;&nbsp;Invoice No :-</strong>'.$AR_SUB['order_no'].'</td>';
            $output .='<td><strong>Date :-</strong>'.DisplayDate($AR_SUB['date_from']).'</td>';
          $output .='</tr>';
          $output .='<tr>';
            $output .='<td><strong>&nbsp;&nbsp;Member Name :-</strong></td>';
            $output .='<td >'.$AR_MEM['full_name'].'</td>';
          $output .='</tr>';
          $output .='<tr>';
            $output .='<td valign="top"  ><strong>&nbsp;&nbsp;Member Code :-</strong></td>';
            $output .='<td >'.$AR_MEM['user_id'].'</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Received With thanks from :-</strong></td>';
             $output .='<td >'.getTool($AR_SUB['net_amount'],"NA").' /-</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Package Type :-</strong></td>';
             $output .='<td >'.$AR_PACK['pin_name'].'</td>';
          $output .='</tr>';
             $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Amount Paid :-</strong></td>';
             $output .='<td >'.number_format($AR_SUB['net_amount'],2).'</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Amount Paid in Word :-</strong></td>';
             $output .='<td >'.convert_number($AR_SUB['net_amount']).' Only /-</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Date of Joining :-</strong></td>';
              $output .='<td valign="top">'.$AR_MEM['date_join'].'</td>';
          $output .='</tr>';
          $output .='<tr>';
              $output .='<td ><strong>&nbsp;&nbsp;Distributor Address :-</strong></td>';
              $output .='<td >'.$AR_MEM['current_address']." ".$AR_MEM['state_name']." ".$AR_MEM['city_name']." Pincode : ".$AR_MEM['pin_code'].'</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td >&nbsp;</td>';
             $output .='<td >&nbsp;</td>';
         $output .='</tr>';
         $output .='</table>';
				
		$this->load->view('pdflib/mpdf');
		$mpdf=new mPDF('c'); 
		#$mpdf->showImageErrors = true;
		$stylesheet = "";
		$stylesheet .= file_get_contents("u-assets/css/bootstrap.min.css");
		$stylesheet .= file_get_contents("u-assets/css/core.css");
		$stylesheet .= file_get_contents("u-assets/css/components.css");
		$stylesheet .= file_get_contents("u-assets/css/pages.css");
		$stylesheet .= file_get_contents("u-assets/css/responsive.css");
		$html = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		exit;
	}
	
}
