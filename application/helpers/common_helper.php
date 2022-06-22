<?php
#Fixed Code Starts-------------------------------------c
function PrintR($StrArr){
	echo "<pre>";print_r($StrArr);echo "</pre>";
}
function FCrtRplc($StrVal){
	return str_replace("'","\"",trim($StrVal));
}
function FCrtAdd($StrVal){
	return str_replace("\"","'",trim($StrVal));
}
function StringReplace($StrVal, $RFrom, $RTo){
	return str_replace($RFrom,$RTo,$StrVal);
}
function StripString($strString, $StrType){
	if(strlen($strString)>1){
		return substr($strString,0,strlen($strString)-strlen($StrType));
	}else{
		return $strString;
	}
}
function null_val($variable){
	if(is_numeric($variable)){
		return ($variable>0)? $variable:0;
	}else{
		return ($variable!='')? $variable:"NULL";
	}
}
function getSwitch($variable){
	if(is_numeric($variable)){
		return ($variable>0)? 1:0;
	}else{
		return ($variable=="on")? 1:0;
	}
}
function getPrimaryKey($table_name){
	$db = new SqlModel();
	$QR_SET = "SHOW KEYS FROM ".$table_name." WHERE key_name ='PRIMARY'";
	$AR_SET = $db->runQuery($QR_SET,true);
	return $AR_SET['Column_name'];
}
function get_input($type,$name,$id,$class,$value,$placeholder='',$checked=false,$readonly=false,$data_table='',$data_field='',$data_id=''){
	switch($type):
		case "number":
		case "email":
		case "text":
			$input = '<input type="text" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="'.$value.'" data_table="'.$data_table.'"
					   data_field="'.$data_field.'" data_id="'.$data_id.'" readonly="'.$readonly.'" placeholder="'.$placeholder.'">';
		break;
		case "file";
			$input = '<input type="file" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="'.$value.'" data_table="'.$data_table.'"
					   data_field="'.$data_field.'" data_id="'.$data_id.'" readonly="'.$readonly.'" placeholder="'.$placeholder.'">';
		break;
		case "radio":
		case "checkbox";
			$input = '<input type="checkbox" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="'.$value.'" data_table="'.$data_table.'"
					   data_field="'.$data_field.'" data_id="'.$data_id.'" checked="'.$checked.'" readonly="'.$readonly.'" placeholder="'.$placeholder.'">';
		break;
	endswitch;
	return $input;
}
function match_number($from_number,$to_number,$till){
	if($from_number>=0 && $to_number>=0){
		$first_number_floor =  floor($from_number);
		$first_decimal_number = $from_number-$first_number_floor;
		$match_first_number = substr($first_decimal_number,0,$till);
		
		$second_number_floor =  floor($to_number);
		$second_decimal_number = $to_number-$second_number_floor;
		$match_second_number = substr($second_decimal_number,0,$till);
		if($first_number_floor>=$second_number_floor && $match_first_number==$match_second_number){
			return 1;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
}

function masking($value,$number){
    $mask_number =  str_repeat("*", strlen($value)-$number) . substr($value, -$number);
    return $mask_number;
}
function getToolValue($variable){
	if($variable!=''){
		return $variable;
	}else{
		return "NULL";
	}
}
function getTool($key, $default_value = false){
	if (!isset($key) || empty($key))
		return $default_value;

	$ret = (isset($key) ? $key : (isset($key) ? $key : $default_value));

	if (is_string($ret))
		return stripslashes(urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret))));

	return $ret;
}
function getPercent($marks,$total){
	if($total>=$marks){
		return (100-($marks/$total*100));
	}else{
		return 0;
	}
}



function gen_slug($text){
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  #$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, '-');
  $text = preg_replace('~-+~', '-', $text);
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function implode_val($filed_array,$string){
	if(is_array($filed_array)){
		return implode($string,array_unique(array_filter($filed_array)));
	}else{
		return implode($string,array_filter(array_unique(implode($string,$filed_array))));
	}
}

function explode_val($filed_value,$string){
	if(!is_array($filed_value)){
		return array_unique(array_filter(explode($string,$filed_value)));
	}else{
		return array_unique(array_filter($filed_value));
	}
}
function panel_name(){
	return ucwords(strtolower(WEBSITE));
}
function title_name(){
	return panel_name();
}
function web_name(){
	return WEBSITE;
}
function word_cleanup ($str)
{
    $pattern = "/<(\w+)>(\s|&nbsp;)*<\/\1>/";
    $str = preg_replace($pattern, '', $str);
    return mb_convert_encoding($str, 'HTML-ENTITIES', 'UTF-8');
}
function getSubString($strString, $IntLng){
	return substr($strString,0,$IntLng);
}
function ForEachArr($StrArr){
	foreach($StrArr as $Key => $Val){
		global $$Key;
		$$Key = FCrtRplc($Val);
	}
}
function OneDcmlPoint($fldiAmount){
	return round($fldiAmount,1);
}
function DisplayDate($DateStr){
	if($DateStr<>"" and $DateStr <> "0000-00-00"){
		return date("d-m-Y", strtotime($DateStr));
	}
}
function DisplayTime($TimeStr){
	if($TimeStr<>"" and $TimeStr <> "0000-00-00"){
		return date("h:i A", strtotime($TimeStr));
	}
}
function InsertDateTime($DateStr){
	if($DateStr<>"" and $DateStr <> "0000-00-00 00:00:00"){
		return date("Y-m-d h:i:s", strtotime($DateStr));
	}
}
function InsertDate($DateStr){
	if($DateStr<>"" and $DateStr <> "0000-00-00"){
		return date("Y-m-d", strtotime($DateStr));
	}
}
function getDateFormat($StrDate, $StrFormat){
	if($StrDate<>"" and $StrDate<>"N/A" and $StrDate <> "0000-00-00"){
		return date($StrFormat,strtotime($StrDate));
	}elseif($StrDate=="N/A"){
		return "N/A" ;
	}
}
function getAccountYear($year){
	$fldiYear = ($year>0)? $year:date("Y");
	$AR_RT['flddFDate'] = InsertDate("01-04-".$fldiYear);
	$AR_RT['flddTDate'] = InsertDate("31-03-".($fldiYear+1));
	return $AR_RT;
}
function AddTime($flddDate, $StrAdd){
	return date("Y-m-d G:i:s",strtotime(date("Y-m-d G:i:s", strtotime($flddDate)) . " $StrAdd"));
}
function getLocalTime(){
	$db = new SqlModel();
	$model = new OperationModel();
	$CONFIG_TIME = $model->getValue("CONFIG_TIME");
	$time = ($CONFIG_TIME>0)? $CONFIG_TIME:0;
	$result = $db->runQuery("SELECT DATE_SUB(NOW(),INTERVAL $time HOUR) AS fldiTime;",true);
	return $result['fldiTime'];
	
}
function getTimeStamp(){
	$db = new SqlModel();
	$result = $db->runQuery("SELECT DATE_SUB(NOW(),INTERVAL 0 HOUR) AS fldiTime;",true);
	return $result['fldiTime'];
	
}
function getLocalDate(){
	$db = new SqlModel();
	$model = new OperationModel();
	$CONFIG_TIME = $model->getValue("CONFIG_TIME");
	$time = ($CONFIG_TIME>0)? $CONFIG_TIME:0;
	$result = $db->runQuery("SELECT DATE(NOW()-INTERVAL $time HOUR) AS fldiTime;",true);
	return $result['fldiTime'];
	
}
function getTime(){
	#$AR_Time = mysql_fetch_assoc(mysql_query("SELECT CURTIME() AS fldiTime;"));
	$fldiTime = date('G:i:s');
	return AddTime($fldiTime,"+0 Hour");
}
function crntTime(){
	$db = new SqlModel();
	$AR_Time = $db->runQuery("SELECT CURTIME() AS fldiTime;",true);
	return $AR_Time['fldiTime'];
}
function TimeIsBetweenTwoTimes($from, $till, $input) {
    $f = DateTime::createFromFormat('H:i:s', $from);
    $t = DateTime::createFromFormat('H:i:s', $till);
    $i = DateTime::createFromFormat('H:i:s', $input);
    if ($f > $t) $t->modify('+1 day');
	return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
}
function printDate($flddDate){
	return date("d F Y h:i:s A", $flddDate);
}
function AddToDate($flddDate, $StrAdd){
	return date("Y-m-d",strtotime(date("Y-m-d", strtotime($flddDate)) . " $StrAdd"));
}
function AddToDateSQL($flddDate, $StrAdd){
	$Q_DATE = "SELECT DATE_ADD('$flddDate', INTERVAL $StrAdd) AS flddDate";
	$AR_DATE = ExecQ($Q_DATE,1);
	return $AR_DATE[flddDate];
}
function dayBetween($flddFDate, $flddTDate){
	$db = new SqlModel();
	$Q_DATE = "SELECT DATEDIFF('$flddFDate','$flddTDate') AS flddDay";
	$AR_DATE = $db->runQuery($Q_DATE,true);
	return $AR_DATE['flddDay'];
}
function integerVal($fldiInteger){
	if(FCrtRplc($fldiInteger)>0){
		return FCrtRplc($fldiInteger);
	}else{
		return "0";
	}
}
function varcharVal($fldvVachar){
	if(FCrtRplc($fldvVachar)!=""){
		return FCrtRplc($fldvVachar);
	}else{
		return "Null";
	}
}
function dateDiff($dformat, $FromDate, $ToDate){
	$date_parts1=explode($dformat, $FromDate);
	$date_parts2=explode($dformat, $ToDate);
	$start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
	$end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
	return $end_date - $start_date;
}

function dayDiff($flddFDate,$flddTDate){
	if(strtotime($flddFDate)<=strtotime($flddTDate)){
		$date1=date_create($flddFDate);
		$date2=date_create($flddTDate);
		$diff=date_diff($date1,$date2);
		return $diff->format("%a");
	}else{
		return 0;
	}
}
function MonthDiff($flddFDate, $flddTDate){
	$flddDate1 = strtotime($flddFDate);
	$flddDate2 = strtotime($flddTDate);
	$fldiYear1 = date('Y', $flddDate1);
	$fldiYear2 = date('Y', $flddDate2);
	$fldiMonth1 = date('n', $flddDate1);
	$fldiMonth2 = date('n', $flddDate2);
	$fldiMonths = ($fldiYear2 - $fldiYear1) * 12 + ($fldiMonth2 - $fldiMonth1);
	return $fldiMonths;
}
function YearDiff($flddFDate, $flddTDate){
	$diff = abs(strtotime($flddFDate) - strtotime($flddTDate));
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	return $years;
}
function MinuteDiff($flddFDate,$flddTDate){
	$to_time = strtotime(InsertDateTime($flddFDate));
	$from_time = strtotime(InsertDateTime($flddTDate));
	return round(abs($to_time - $from_time) / 60,2);
}
function HoursDiff($flddFDate,$flddTDate){
	$to_time = strtotime(InsertDateTime($flddFDate));
	$from_time = strtotime(InsertDateTime($flddTDate));
	return round(abs($to_time - $from_time) / 3600,2);
}
function getMonthDays($flddDate){
	$db = new SqlModel();
	$Q_DTQRY = " SELECT DAY(LAST_DAY('$flddDate')) AS fldiDays";
	$AR_DTQRY = $db->runQuery($Q_DTQRY,true);
	return $AR_DTQRY['fldiDays'];
} 
function getMonth($Date){
	$db = new SqlModel();
	$strQ_Fetch = "SELECT MONTH('$Date') AS Month";
	$Arr_Fetch = $db->runQuery($strQ_Fetch,true);
	return $Arr_Fetch['Month']; 
}
function getYear($Date){
	$db = new SqlModel();
	$strQ_Fetch = "SELECT YEAR('$Date') AS Year";
	$Arr_Fetch = $db->runQuery($strQ_Fetch,true);
	return $Arr_Fetch['Year']; 
}
function getMonthDates($flddDate){
	$fldiMDays = getMonthDays($flddDate);
	$fldiMonth = getMonth($flddDate);
	$fldiYear = getYear($flddDate);
	$AR_MD[flddFDate] = InsertDate("01"."-".$fldiMonth."-".$fldiYear);
	$AR_MD[flddTDate] = InsertDate($fldiMDays."-".$fldiMonth."-".$fldiYear);
	return $AR_MD;
}
function getYearDate($flddDate){
	$fldiYear = getYear($flddDate);
	$fldiLYear = $fldiYear+1;
	$AR_MD['flddFDate'] = InsertDate("01-04-".$fldiYear);
	$AR_MD['flddTDate'] = InsertDate("31-03-".$fldiLYear);
	return $AR_MD;
}
function WriteToFile($ErrorMsg){
	$myFile =  "MySqlErroFile.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, $ErrorMsg);
	fclose($fh);
}
function ExecQ($Query, $ReturnType){
	$RS_Query = mysql_query($Query);
	if(mysql_errno()){
		WriteToFile(date('Y-m-d H:i:s')."	MySQL error ".mysql_errno().": ".mysql_error()." When executing: $Query\n".$_SERVER['PHP_SELF']."\n");
		echo "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$Query\n<br>";
		exit;
	}
	switch($ReturnType){
		case 0:
			return $ReturnType;
		break;
		case 1:
			return mysql_fetch_assoc($RS_Query);
		break;
		case 2:
			return $RS_Query;
		break;
	}
}


function NumberFormat($Number){
	if(($Number/10)%10 != 1){
		switch($Number% 10 ){
			case 1: return $Number.'<sup>st</sup>';break;
			case 2: return $Number.'<sup>nd</sup>';break;
			case 3: return $Number.'<sup>rd</sup>';break;
		}
	}
	return $Number.'<sup>th</sup>';
}
function NumberFormat_Txt($Number){
	if(($Number/10)%10 != 1){
		switch($Number% 10 ){
			case 1: return $Number.'st';break;
			case 2: return $Number.'nd';break;
			case 3: return $Number.'rd';break;
		}
	}
	return $Number.'th';
}


#Fixed Code Ends-----------------------------------
function DisplayCombo($SlctVal, $CmbType){
	$db = new SqlModel();
	switch($CmbType){
		case "EXPRNC":
			for($Ctrl=0; $Ctrl < 51; $Ctrl++){
				$CMBTXT = $Ctrl==0?"0": $Ctrl."";
				echo "<option value='".$Ctrl."'"; if($SlctVal == $Ctrl){echo 'selected="selected"';}
				echo ">".$CMBTXT."</option>";
			}
		break;
		case "DAY":
			for($Ctrl=1; $Ctrl < 32; $Ctrl++){
				echo "<option value='".$Ctrl."'"; if($SlctVal == $Ctrl){echo "selected";}
				echo ">".$Ctrl."</option>";
			}
		break;
		case "MONTH":
			$QR_SELECT = "SELECT * FROM tbl_months WHERE month_id>0 ORDER BY month_id ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['month_id']."'";if($SlctVal == $rowSet['month_id']){echo "selected";}
				echo ">".$rowSet['month']."</option>";
			endforeach;
		break;
		case "YEAR":
			$Curr_Yr = date("Y");
			for($Ctrl=$Curr_Yr; $Ctrl >= 1930; $Ctrl--){
				echo "<option value='".$Ctrl."'"; if($SlctVal == $Ctrl){echo "selected";}
				echo ">".$Ctrl."</option>";
			}
		break;
		case "YEARNEWPC":
			$Curr_Yr = date("Y");
			for($Ctrl=($Curr_Yr-18); $Ctrl>=1930; $Ctrl--){
				echo "<option value='".$Ctrl."'"; if($SlctVal == $Ctrl){echo "selected";}
				echo ">".$Ctrl."</option>";
			}
		break;
		case "PAST_5_YEAR":
			$Curr_Yr = date("Y");
			$SlctVal = ($SlctVal=='')? $Curr_Yr:$Curr_Yr;
			for($Ctrl=$Curr_Yr; $Ctrl >= ($Curr_Yr-5); $Ctrl--){
				echo "<option value='".$Ctrl."'"; if($SlctVal == $Ctrl){echo "selected";}
				echo ">".$Ctrl."</option>";
			}
		break;
		case "PROCESS":
			$QR_SELECT = "SELECT * FROM tbl_process WHERE process_id>0 AND  process_sts='Y' ORDER BY process_id DESC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['process_id']."'";if($SlctVal == $rowSet['process_id']){echo "selected";}
				echo ">Cycle No  ".$rowSet['process_id']." : [  ".DisplayDate($rowSet['start_date'])."&nbsp;&nbsp;To&nbsp;&nbsp;".DisplayDate($rowSet['end_date'])." ]</option>";
			endforeach;
		break;
		case "BINARY_PROCESS":
			$QR_SELECT = "SELECT * FROM tbl_process_binary WHERE process_id>1 AND  process_sts='Y' ORDER BY process_id DESC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['process_id']."'";if($SlctVal == $rowSet['process_id']){echo "selected";}
				echo ">Cycle No  ".$rowSet['process_id']." : [  ".DisplayDate($rowSet['start_date'])."&nbsp;&nbsp;To&nbsp;&nbsp;".DisplayDate($rowSet['end_date'])." ]</option>";
			endforeach;
		break;
		case "PROCESS_MEM":
			$QR_SELECT = "SELECT *, MONTHNAME(start_date) AS fldvMonth, YEAR(start_date) AS fldiYear FROM tbl_process WHERE process_id>1 AND  
						  process_sts='Y' ORDER BY process_id DESC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['process_id']."'";if($SlctVal == $rowSet['process_id']){echo "selected";}
				echo ">".$rowSet['fldvMonth']." ".$rowSet['fldiYear']."</option>";
			endforeach;
		break;
		case "PROCESS_ALL":
			$QR_SELECT = "SELECT * FROM tbl_process WHERE 1 ORDER BY process_id DESC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['process_id']."'";if($SlctVal == $rowSet['process_id']){echo "selected";}
				echo ">Cycle No  ".$rowSet['process_id']." : [  ".DisplayDate($rowSet['start_date'])."&nbsp;&nbsp;To&nbsp;&nbsp;".DisplayDate($rowSet['end_date'])." ]</option>";
			endforeach;
		break;
		case "PACKAGE":
		case "PIN_TYPE":
			$QR_SELECT = "SELECT * FROM tbl_pintype WHERE isDelete>0 ORDER BY type_id ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['type_id']."'";if($SlctVal == $rowSet['type_id']){echo "selected";}
				echo ">".$rowSet['pin_name']."</option>";
			endforeach;
		break;
		case "RANK":
			$QR_SELECT = "SELECT * FROM tbl_rank WHERE rank_sts>0 ORDER BY rank_id ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['rank_id']."'";if($SlctVal == $rowSet['rank_id']){echo "selected";}
				echo ">".$rowSet['rank_name']."</option>";
			endforeach;
		break;
		case "BANK":
			$QR_SELECT = "SELECT * FROM tbl_banks WHERE 1 ORDER BY bank_id ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['bank_id']."'";if($SlctVal == $rowSet['bank_id']){echo "selected";}
				echo ">".$rowSet['bank_name']."</option>";
			endforeach;
		break;
		case "POINT_TYPE":
			$QR_SELECT = "SELECT DISTINCT point_sub_type FROM tbl_mem_point WHERE 1 ORDER BY point_id ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['point_sub_type']."'";if($SlctVal == $rowSet['point_sub_type']){echo "selected";}
				echo ">".$rowSet['point_sub_type']."</option>";
			endforeach;
		break;
		case "CITY_LIST":	
			$Q_PTYPE = "SELECT * FROM tbl_city WHERE country_code LIKE 'IND' AND state_name!='' 
						GROUP BY state_name  
						ORDER BY state_name ASC;";
			$rowSets = $db->runQuery($Q_PTYPE);
			foreach($rowSets as $rowSet):
				echo "<optgroup label='".$rowSet['state_name']."'>";
				$Q_CMB = "SELECT city_id, city_name FROM tbl_city WHERE state_code='".$rowSet['state_code']."' ORDER BY city_name ASC;";
				$rowSubs = $db->runQuery($Q_CMB);
				foreach($rowSubs as $rowSub):
					echo "<option value='".$rowSub['city_name']."'"; if($SlctVal == $rowSub['city_name']){echo "selected";}
					echo ">&nbsp;&nbsp;".$rowSub['city_name']."</option>";
				endforeach;
				echo "</option>";
			endforeach;
		break;
		case "CITY":
			$Q_PTYPE = "SELECT * FROM tbl_city WHERE country_code LIKE 'IND' AND state_code!='' 
						GROUP BY state_code  
						ORDER BY state_name ASC;";
			$rowSets = $db->runQuery($Q_PTYPE);
			foreach($rowSets as $rowSet):
				echo "<optgroup label='".$rowSet['state_name']."'>";
				$Q_CMB = "SELECT city_id, city_name 
				FROM tbl_city 
				WHERE state_code='".$rowSet['state_code']."'  AND city_name!=''
				GROUP BY city_name   
				ORDER BY city_name ASC;";
				$rowSubs = $db->runQuery($Q_CMB);
				foreach($rowSubs as $rowSub):
					echo "<option value='".$rowSub['city_id']."'"; if($SlctVal == $rowSub['city_id']){echo "selected";}
					echo ">&nbsp;&nbsp;".$rowSub['city_name']."</option>";
				endforeach;
				echo "</option>";
			endforeach;
		break;
		case "STATE":
			$QR_SELECT = "SELECT state_name FROM tbl_city WHERE country_code LIKE 'IND' AND state_name!='' AND isDelete>0
			GROUP BY state_name 
			ORDER BY state_name ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['state_name']."'";if($SlctVal == $rowSet['state_name']){echo "selected";}
				echo ">".$rowSet['state_name']."</option>";
			endforeach;
		break;
		case "COUNTRY":
			$QR_SELECT = "SELECT * FROM tbl_country WHERE  country_code LIKE 'IND' AND  country_code IS NOT NULL ORDER BY country_name ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['country_code']."'";if($SlctVal == $rowSet['country_code']){echo "selected";}
				echo ">".$rowSet['country_name']."</option>";
			endforeach;
		break;
		case "WALLET":
			$QR_SELECT = "SELECT * FROM tbl_wallet WHERE 1 ORDER BY wallet_name ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['wallet_id']."'";if($SlctVal == $rowSet['wallet_id']){echo "selected";}
				echo ">".$rowSet['wallet_name']."</option>";
			endforeach;
		break;
		case "FONT_AWSOME":	
			$QR_SELECT = "SELECT * FROM tbl_font_awsome_icon ORDER BY icon_name ASC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['icon_id']."'";if($SlctVal == $rowSet['icon_id']){echo "selected";}
				echo ">".$rowSet['icon_name']."</option>";
			endforeach;
		break;
		case "MAIN_MENU":
			$QR_SELECT = "SELECT * FROM tbl_sys_menu_main ORDER BY order_id ASC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['ptype_id']."'";if($SlctVal == $rowSet['ptype_id']){echo "selected";}
				echo ">".$rowSet['type_name']."</option>";
			endforeach;
		break;
		case "USRGRP":
			$QR_SELECT = "SELECT * FROM tbl_oprtr_grp ORDER BY group_id ASC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['group_id']."'";if($SlctVal == $rowSet['group_id']){echo "selected";}
				echo ">".$rowSet['group_name']."</option>";
			endforeach;
		break;
		case "ATTRIBUTE_GRP":
			$QR_SELECT = "SELECT * FROM tbl_attribute_group WHERE delete_sts>0 AND attribute_group_sts>0 ORDER BY attribute_group_id ASC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['attribute_group_id']."'";if($SlctVal == $rowSet['attribute_group_id']){echo "selected";}
				echo ">".$rowSet['attribute_group_name']."</option>";
			endforeach;
		break;
		case "ATTRIBUTE":
			$QR_SELECT = "SELECT * FROM tbl_attribute WHERE attribute_sts>0 AND delete_sts>0 ORDER BY attribute_id ASC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['attribute_id']."'";if($SlctVal == $rowSet['attribute_id']){echo "selected";}
				echo ">".$rowSet['attribute_name']."</option>";
			endforeach;
		break;
		case "ORDER_STATE":
			$QR_SELECT = "SELECT * FROM tbl_order_state ORDER BY id_order_state ASC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['id_order_state']."'";if($SlctVal == $rowSet['id_order_state']){echo "selected";}
				echo ">".$rowSet['name']."</option>";
			endforeach;
		break;
		case "ORDER_STATE_RETURN": 
			$QR_SELECT = "SELECT * FROM tbl_order_state WHERE id_order_state IN(10,11) ORDER BY id_order_state ASC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['id_order_state']."'";if($SlctVal == $rowSet['id_order_state']){echo "selected";}
				echo ">".$rowSet['name']."</option>";
			endforeach;
		break;
		case "EMAIL_TEMPLATE":
			$QR_SELECT = "SELECT DISTINCT type FROM tbl_mail_send WHERE 1 ORDER BY type ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['type']."'";if($SlctVal == $rowSet['type']){echo "selected";}
				echo ">".$rowSet['type']."</option>";
			endforeach;
		break;
		case "FRANCHISEE_TYPE":
			$QR_SELECT = "SELECT * FROM tbl_setup_franchisee ORDER BY fran_setup_id DESC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['fran_setup_id']."'";if($SlctVal == $rowSet['fran_setup_id']){echo "selected";}
				echo ">".$rowSet['franchisee_type']."</option>";
			endforeach;
		break;
		case "STORE_LOCATOR":
			$QR_SELECT = "SELECT * FROM tbl_franchisee WHERE 1 ORDER BY user_name ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['franchisee_id']."'";if($SlctVal == $rowSet['franchisee_id']){echo "selected";}
				echo ">".$rowSet['user_name']."</small></option>";
			endforeach;
		break;
		case "DELIVERY_SLOT":
			$QR_SELECT = "SELECT * FROM tbl_delivery_slot WHERE 1 ORDER BY delivery_slot_id ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['delivery_slot_id']."'";if($SlctVal == $rowSet['delivery_slot_id']){echo "selected";}
				echo ">".$rowSet['delivery_slot']."</small></option>";
			endforeach;
		break;
		case "SHORTING":
			echo "<option value=''"; if($SlctVal == ''){echo "selected";} echo ">Default sorting</option>";
			echo "<option value='nameasc'"; if($SlctVal == 'nameasc'){echo "selected";} echo ">Name, A to Z</option>";
			echo "<option value='namedesc'"; if($SlctVal == 'namedesc'){echo "selected";} echo ">Name, Z to A</option>";
			echo "<option value='priceasc'"; if($SlctVal == 'priceasc'){echo "selected";} echo ">Price, low to high</option>";
			echo "<option value='pricedesc'"; if($SlctVal == 'pricedesc'){echo "selected";} echo ">Price, high to low</option>";
		break;
		case "PAGE_RECORD":
			echo "<option value='15'"; if($SlctVal == '15'){echo "selected";} echo ">15 records</option>";
			echo "<option value='25'"; if($SlctVal == '25'){echo "selected";} echo ">25 records</option>";
			echo "<option value='50'"; if($SlctVal == '50'){echo "selected";} echo ">50 records</option>";
			echo "<option value='100'";if($SlctVal == '100'){echo "selected";} echo ">100 records</option>";
		break;
		case "SPECIAL_INCOME":
			echo "<option value='1'"; if($SlctVal == '1'){echo "selected";} echo ">SILVER</option>";
			echo "<option value='2'"; if($SlctVal == '2'){echo "selected";} echo ">GOLD</option>";
			echo "<option value='3'"; if($SlctVal == '3'){echo "selected";} echo ">PLATINUM</option>";
		break;
		case "YN":
			echo "<option value='1'"; if($SlctVal == '1'){echo "selected";} echo ">Yes</option>";
			echo "<option value='0'"; if($SlctVal == '0'){echo "selected";} echo ">No</option>";
		break;
		case "JOIN_TYPE":
			echo "<option value='M'"; if($SlctVal == 'M'){echo "selected";} echo ">Member</option>";
			echo "<option value='A'"; if($SlctVal == 'A'){echo "selected";} echo ">Agent</option>";
		break;
		case "YESNOFLAG":
			echo "<option value='N'"; if($SlctVal == 'N'){echo "selected";} echo ">SET NO</option>";
			echo "<option value='Y'"; if($SlctVal == 'Y'){echo "selected";} echo ">SET YES</option>";
		break;
		case "ACT_INACT":
			echo "<option value='N'"; if($SlctVal == 'N'){echo "selected";} echo ">In Active</option>";
			echo "<option value='Y'"; if($SlctVal == 'Y'){echo "selected";} echo ">Active</option>";
		break;
		case "NOTICE":
			echo "<option value='Na'"; if($SlctVal == 'Na'){echo "selected";} echo ">Any Time</option>";
			echo "<option value='7days'"; if($SlctVal == '7days'){echo "selected";} echo ">7 Days</option>";
			echo "<option value='15days'"; if($SlctVal == '15days'){echo "selected";} echo ">15 Days</option>";
			echo "<option value='1month'"; if($SlctVal == '1month'){echo "selected";} echo ">1 Month</option>";
			echo "<option value='3months'"; if($SlctVal == '3months'){echo "selected";} echo ">3 Months</option>";
		break;
		case "TMPLT":
			echo "<option value='01'"; if($SlctVal == '01'){echo "selected";} echo ">Menu on Left</option>";
			echo "<option value='02'"; if($SlctVal == '02'){echo "selected";} echo ">Menu on Top</option>";
		break;
		case "LOGSTS":
			echo "<option value='N'"; if($SlctVal == 'N'){echo "selected";} echo ">NOT TRACED</option>";
			echo "<option value='F'"; if($SlctVal == 'F'){echo "selected";} echo ">FAILED LOGIN</option>";
			echo "<option value='S'"; if($SlctVal == 'S'){echo "selected";} echo ">SUCCESS LOGIN</option>";
		break;
		case "METHOD":
			echo "<option value='BITCOIN'"; if($SlctVal == 'BITCOIN'){echo "selected";} echo ">Bitcoin</option>";
			echo "<option value='PERFECT'"; if($SlctVal == 'PERFECT'){echo "selected";} echo ">Perfect Money</option>";
			echo "<option value='BANKWIRE'"; if($SlctVal == 'BANKWIRE'){echo "selected";} echo "> Bank Wire</option>";
			echo "<option value='EWALLET'"; if($SlctVal == 'EWALLET'){echo "selected";} echo ">E-Wallet</option>";
		break;
		case "CAPPING_TYPE":
			echo "<option value='Y'"; if($SlctVal == 'Y'){echo "selected";} echo ">Year</option>";
			echo "<option value='M'"; if($SlctVal == 'M'){echo "selected";} echo ">Month</option>";
			echo "<option value='W'"; if($SlctVal == 'W'){echo "selected";} echo ">Week</option>";
			echo "<option value='D'"; if($SlctVal == 'D'){echo "selected";} echo ">Days</option>";
		break;
		case "ID_TYPE":
			echo "<option value='PAN CARD'"; if($SlctVal == 'PAN CARD'){echo "selected";} echo ">PAN Card</option>";
			echo "<option value='VOTER ID'"; if($SlctVal == 'VOTER ID'){echo "selected";} echo ">Voter Id</option>";
			echo "<option value='DRIVING LICENSE'"; if($SlctVal == 'DRIVING LICENSE'){echo "selected";} echo ">Driving License</option>";
			echo "<option value='PASSPORT'"; if($SlctVal == 'PASSPORT'){echo "selected";} echo ">Passport</option>";
			echo "<option value='ADDHAR CARD'"; if($SlctVal == 'ADDHAR CARD'){echo "selected";} echo ">Addhar Card</option>";
		break;
		case "ADDRESS_TYPE":
			echo "<option value='VOTER ID'"; if($SlctVal == 'VOTER ID'){echo "selected";} echo ">Voter Id</option>";
			echo "<option value='DRIVING LICENSE'"; if($SlctVal == 'DRIVING LICENSE'){echo "selected";} echo ">Driving License</option>";
			echo "<option value='PASSPORT'"; if($SlctVal == 'PASSPORT'){echo "selected";} echo ">Passport</option>";
			echo "<option value='ADDHAR CARD'"; if($SlctVal == 'ADDHAR CARD'){echo "selected";} echo ">Addhar Card</option>";
			echo "<option value='RATION CARD'"; if($SlctVal == 'VOTER CARD'){echo "selected";} echo ">Ration Card</option>";
			echo "<option value='TELEPHONE BILL'"; if($SlctVal == 'TELEPHONE BILL'){echo "selected";} echo ">Telephone Bill</option>";
			echo "<option value='ELECTRICTY BILL'"; if($SlctVal == 'ELECTRICTY BILL'){echo "selected";} echo ">Electricity Bill</option>";
			echo "<option value='GAS CONNECTION BILL'"; if($SlctVal == 'GAS CONNECTION BILL'){echo "selected";} echo ">Gas Connection Bill</option>";
			echo "<option value='BANK STATEMENT'"; if($SlctVal == 'BANK STATEMENT'){echo "selected";} echo ">Bank Statement/Passbook</option>";
		break;
		case "ORDER_ADDRESS";
			echo "<option value='HOME'"; if($SlctVal == 'HOME'){echo "selected";} echo ">Home</option>";
			echo "<option value='OFFICE'"; if($SlctVal == 'OFFICE'){echo "selected";} echo ">Office</option>";
			echo "<option value='OTHER'"; if($SlctVal == 'OTHER'){echo "selected";} echo ">Other</option>";
		break;
		case "TITLE":
			echo "<option value='Mr'"; if($SlctVal == 'Mr'){echo "selected";} echo ">Mr</option>";
			echo "<option value='Ms'"; if($SlctVal == 'Ms'){echo "selected";} echo ">Ms</option>";
			echo "<option value='Mrs'"; if($SlctVal == 'Mrs'){echo "selected";} echo ">Mrs</option>";
			echo "<option value='Md'"; if($SlctVal == 'Md'){echo "selected";} echo ">Md</option>";
			echo "<option value='M/S'"; if($SlctVal == 'M/S'){echo "selected";} echo ">M/S</option>";
			echo "<option value='Dr'"; if($SlctVal == 'Dr'){echo "selected";} echo ">Dr</option>";
		break;
		case "PMTTYPE":
			echo "<option value='CS'"; if($SlctVal == 'CS'){echo "selected";} echo ">CASH</option>";
			echo "<option value='CQ'"; if($SlctVal == 'CQ'){echo "selected";} echo ">CHEQUE</option>";
			echo "<option value='DD'"; if($SlctVal == 'DD'){echo "selected";} echo ">BANK DD</option>";
		break;
		case "BUSINESS_TYPE":
			echo "<option value='INV'"; if($SlctVal == 'INV'){echo "selected";} echo ">Individual</option>";
			echo "<option value='PTS'"; if($SlctVal == 'PTS'){echo "selected";} echo ">Properitorship</option>";
			echo "<option value='PPS'"; if($SlctVal == 'PPS'){echo "selected";} echo ">Partnership</option>";
			echo "<option value='COM'"; if($SlctVal == 'COM'){echo "selected";} echo ">Company</option>";
		break;
		case "FPVSTATUS":
			echo "<option value='N'"; if($SlctVal == 'N'){echo "selected";} echo ">Unused</option>";
			echo "<option value='Y'"; if($SlctVal == 'Y'){echo "selected";} echo ">Used</option>";
			echo "<option value='X'"; if($SlctVal == 'X'){echo "selected";} echo ">Expired</option>";
		break;
		
		case "DATE_TIME":
			$today_date = InsertDate(getLocalTime());
			$QR_SELECT = "SELECT DISTINCT date_time FROM tbl_daily_return WHERE  DATE(date_time)!='".$today_date."' ORDER BY date_time ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			echo '<option value="'.$today_date.'">Date : &nbsp; '.getDateFormat($today_date,"d D m Y").'</option>';
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['date_time']."'";if($SlctVal == $rowSet['date_time']){echo "selected";}
				echo ">Date : &nbsp; ".getDateFormat($rowSet['date_time'],"d D m Y")."</option>";
			endforeach;
		break;
		case "MOBILE_OPERATOR":
			$QR_SEL = "SELECT * FROM tbl_recharge_operator WHERE operator_type='MOB' ORDER BY operator_name ASC"; 
			$RS_SEL = $db->runQuery($QR_SEL);
			foreach($RS_SEL as $AR_SEL):
				echo "<option value='".$AR_SEL['operator_id']."'";if($SlctVal == $AR_SEL['operator_id']){echo "selected";}
				echo ">".$AR_SEL['operator_name']."</option>";
			endforeach;
		break;
		case "DTH_OPERATOR":
			$QR_SEL = "SELECT * FROM tbl_recharge_operator WHERE operator_type='DTH' ORDER BY operator_name ASC"; 
			$RS_SEL = $db->runQuery($QR_SEL);
			foreach($RS_SEL as $AR_SEL):
				echo "<option value='".$AR_SEL['operator_id']."'";if($SlctVal == $AR_SEL['operator_id']){echo "selected";}
				echo ">".$AR_SEL['operator_name']."</option>";
			endforeach;
		break;
		case "DCD_OPERATOR":
			$QR_SEL = "SELECT * FROM tbl_recharge_operator WHERE operator_type='DCD' ORDER BY operator_name ASC"; 
			$RS_SEL = $db->runQuery($QR_SEL);
			foreach($RS_SEL as $AR_SEL):
				echo "<option value='".$AR_SEL['operator_id']."'";if($SlctVal == $AR_SEL['operator_id']){echo "selected";}
				echo ">".$AR_SEL['operator_name']."</option>";
			endforeach;
		break;
		case "POP_OPERATOR":
			$QR_SEL = "SELECT * FROM tbl_recharge_operator WHERE operator_type='POP' ORDER BY operator_name ASC"; 
			$RS_SEL = $db->runQuery($QR_SEL);
			foreach($RS_SEL as $AR_SEL):
				echo "<option value='".$AR_SEL['operator_id']."'";if($SlctVal == $AR_SEL['operator_id']){echo "selected";}
				echo ">".$AR_SEL['operator_name']."</option>";
			endforeach;
		break;
		case "CATEGORY":
			$category_id_array = explode(",",$SlctVal);
			$QR_SELECT = "SELECT * FROM tbl_category WHERE category_sts>0  AND delete_sts>0 
						  ORDER BY category_id ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				if(in_array($rowSet['category_id'],$category_id_array)==TRUE){ $selected="selected=selected"; }else{ $selected=""; }
				echo '<option value="'.$rowSet['category_id'].'" '.$selected.'>'.$rowSet['category_name'].'</option>';
			endforeach;
		break;
		case "CATEGORY_ALL":
			$category_id_array = explode(",",$SlctVal);
			$QR_SELECT = "SELECT * FROM tbl_category WHERE category_sts>0  AND delete_sts>0 
						  ORDER BY category_id ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				if(in_array($rowSet['category_id'],$category_id_array)==TRUE){ $selected="selected=selected"; }else{ $selected=""; }
				echo '<option value="'.$rowSet['category_id'].'" '.$selected.'>'.$rowSet['category_name'].'</option>';
			endforeach;
		break;
		case "POST":
			$QR_SELECT = "SELECT tp.post_id, tpl.post_title FROM tbl_post AS tp
						 LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
						 WHERE tp.delete_sts>0 ORDER BY tpl.post_title ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['post_id']."'";if($SlctVal == $rowSet['post_id']){echo "selected";}
				echo ">".$rowSet['post_title']."</option>";
			endforeach;
		break;
		case "SUPPORT_QUERY":
			$QR_SELECT = "SELECT * FROM tbl_support_type WHERE 1 ORDER BY query_name ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['query_id']."'";if($SlctVal == $rowSet['query_id']){echo "selected";}
				echo ">".$rowSet['query_name']."</option>";
			endforeach;
		break;
		case "FRANCHISEE":
			$QR_SELECT = "SELECT * FROM tbl_franchisee WHERE 1 ORDER BY user_name ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['franchisee_id']."'";if($SlctVal == $rowSet['franchisee_id']){echo "selected";}
				echo ">".$rowSet['user_name']."</option>";
			endforeach;
		break;
		case "SUPPLIER":
			$QR_SELECT = "SELECT * FROM tbl_supplier WHERE  supplier_sts>0 AND delete_sts>0 ORDER BY supplier_name ASC"; 
			$rowSets = $db->SqlModel->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo '<option value="'.$rowSet['supplier_id'].'"';if($SlctVal == $rowSet['supplier_id']){echo "selected";}
				echo '>'.$rowSet['supplier_name'].'</option>';
			endforeach;
		break;
		case "RETURN_CHARGE":
			$QR_SELECT = "SELECT * FROM tbl_return_setup WHERE  return_sts>0 AND delete_sts>0 ORDER BY return_id ASC"; 
			$rowSets = $db->SqlModel->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo '<option value="'.$rowSet['return_id'].'"';if($SlctVal == $rowSet['return_id']){echo "selected";}
				echo '>'.$rowSet['return_name'].'&nbsp;('.$rowSet['return_rate'].'%)</option>';
			endforeach;
		break;
		case "MEMBERS":
			$QR_SELECT = "SELECT tm.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS mem_full_name 
						  FROM tbl_members AS tm WHERE tm.delete_sts>0
						  ORDER BY tm.member_id ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['member_id']."'";if($SlctVal == $rowSet['member_id']){echo "selected";}
				echo ">".$rowSet['mem_full_name']."[".$rowSet['user_id']."]"."</option>";
			endforeach;
		break;
		case "MEMBER_FORM16":
			$QR_SELECT = "SELECT tf.*, tm.user_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS mem_full_name 
						  FROM tbl_form16 AS tf
						  LEFT JOIN tbl_members AS tm ON tm.member_id=tf.member_id
						  WHERE tf.form_id>0
						  GROUP BY tf.member_id
						  ORDER BY tf.form_year ASC, tf.form_month ASC"; 
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['member_id']."'";if($SlctVal == $rowSet['member_id']){echo "selected";}
				echo ">".$rowSet['mem_full_name']."[".$rowSet['user_id']."]"."</option>";
			endforeach;
		break;
		case "PAYMENT_STS":
			$QR_SELECT = "SELECT DISTINCT payment_sts FROM ".prefix."tbl_online_payment WHERE payment_sts!='' ORDER BY payment_sts ASC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['payment_sts']."'";if($SlctVal == $rowSet['payment_sts']){echo "selected";}
				echo ">".$rowSet['payment_sts']."</option>";
			endforeach;
		break;
		case "ROYALTY_LIST":
			$QR_SELECT = "SELECT royalty_id, royalty_name FROM ".prefix."tbl_setup_royalty WHERE royalty_sts>0 ORDER BY royalty_id ASC;";
			$rowSets = $db->runQuery($QR_SELECT);
			foreach($rowSets as $rowSet):
				echo "<option value='".$rowSet['royalty_id']."'";if($SlctVal == $rowSet['royalty_id']){echo "selected";}
				echo ">".$rowSet['royalty_name']."</option>";
			endforeach;
		break;
	}
}


function DisplayComboLowerRank($select_value,$rank_id){
	$db = new SqlModel();
	$QR_SELECT = "SELECT * FROM tbl_rank WHERE 
				 rank_sts>0 
				 ORDER BY rank_id ASC"; 
	$rowSets = $db->runQuery($QR_SELECT);
	foreach($rowSets as $rowSet):
		echo "<option value='".$rowSet['rank_id']."'";if($select_value == $rowSet['rank_id']){echo "selected";}
		echo ">".$rowSet['rank_name']."</option>";
	endforeach;
}

function InsertVal($fldvField){
	$val = FCrtRplc($fldvField);
	if(!empty($val)){
		if(is_numeric($val)){
			return $val;
		}else{
			if(!is_numeric($val)){
				return $val;
			}else{
			 	return $val;
			}
		}
	}else{
		return 'NULL';
	}
}
function getUnusedPin($member_id){
	$db = new SqlModel();
	$QR_SELECT = "SELECT tpd.*, tpt.pin_name 
				 FROM tbl_pinsdetails AS tpd 
				 LEFT JOIN tbl_pintype AS tpt ON tpt.type_id=tpd.type_id
				 WHERE tpd.member_id='".$member_id."' AND tpd.pin_sts='N'  
				 AND tpd.to_member_id='0'
				 ORDER BY tpd.pin_id ASC"; 
	$rowSets = $db->runQuery($QR_SELECT);
	foreach($rowSets as $rowSet):
		echo "<option value='".$rowSet['pin_id']."'";
		echo ">".$rowSet['pin_key']." @ ".$rowSet['pin_name']."</option>";
	endforeach;
}
function UniqueId($Type){
	$db =  new SqlModel();
	switch($Type){
		case "UNIQUE_NO":
			srand((double)microtime()*1000000);
			$data .= "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghigklmnopqrstuvwxyz";
			for($i = 0; $i < 25; $i++){
				$fldvNumber .= substr($data, (rand()%(strlen($data))), 1);
			}
			if($fldvNumber!=""){
				return $fldvNumber;
			}else{ return UniqueId("UNIQUE_NO"); }
		break;
		case "REF_CODE":
			srand((double)microtime()*1000000);
			$data .= "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			for($i = 0; $i < 8; $i++){
				$generate_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$post_ref = "PD".$generate_no;
			$Q_CHK ="SELECT COUNT(*) AS row_ctrl FROM tbl_post WHERE post_ref='$post_ref';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['row_ctrl']==0){
				return $post_ref;
			}else{
				return UniqueId("REF_CODE");
			}
			
			if($unique_no!=""){
				return $unique_no;
			}else{ return UniqueId("REF_CODE"); }
		break;
		case "COUPON_NO":
			$data = "0123456789";
			for($i = 0; $i < 8; $i++){
				$coupon_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_coupon WHERE coupon_no='$coupon_no';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $coupon_no;
			}else{
				return UniqueId("COUPON_NO");
			}
		break;
		case "TICKET_NO":
			$data = "123456789";
			for($i = 0; $i < 7; $i++){
				$ticket_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_support WHERE ticket_no='$ticket_no';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $ticket_no;
			}else{
				return UniqueId("TICKET_NO");
			}
		break;
		case "SMS_OTP":
			$data = "123456789";
			for($i = 0; $i <= 6; $i++){
				$sms_otp .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_sms_otp WHERE sms_otp='$sms_otp';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $sms_otp;
			}else{
				return UniqueId("SMS_OTP");
			}
		break;
		case "DRAW_OTP":
			$data = "123456789";
			for($i = 0; $i <= 6; $i++){
				$sms_otp .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_sms_otp WHERE sms_otp='$sms_otp';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $sms_otp;
			}else{
				return UniqueId("DRAW_OTP");
			}
		break;
		case "SMS_TMP":
			$data = "123456789";
			for($i = 0; $i <= 6; $i++){
				$sms_otp .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_tmp_register WHERE fldvMCode='$sms_otp';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $sms_otp;
			}else{
				return UniqueId("SMS_TMP");
			}
		break;
		case "GET_PWD":
			$data = "123456789";
			for($i = 0; $i <= 6; $i++){
				$sms_otp .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_members WHERE otp_no='$sms_otp';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $sms_otp;
			}else{
				return UniqueId("GET_PWD");
			}
		break;
		case "OTP_FPV":
			$data = "123456789";
			for($i = 0; $i <= 6; $i++){
				$sms_otp .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_coupon WHERE otp_order='$sms_otp';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $sms_otp;
			}else{
				return UniqueId("OTP_FPV");
			}
		break;
		case "TRNS_NO":
			$data = "123456789";
			for($i = 0; $i < 7; $i++){
				$unique_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_wallet_trns WHERE trans_ref_no='$unique_no';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $unique_no;
			}else{
				return UniqueId("TRNS_NO");
			}
		break;
		case "WARE_TRNS_NO":
			$data = "123456789";
			for($i = 0; $i < 7; $i++){
				$unique_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_warehouse_trns WHERE trans_no='$unique_no';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $unique_no;
			}else{
				return UniqueId("WARE_TRNS_NO");
			}
		break;
		case "GEN_TRNS":
			$data = "123456789";
			for($i = 0; $i < 7; $i++){
				$point_ref .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_mem_point WHERE point_ref='$point_ref';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $point_ref;
			}else{
				return UniqueId("GEN_TRNS");
			}
		break;
		case "REFER_NO":
			$data = "1234567890";
			for($i = 0; $i < 11; $i++){
				$reference_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(order_id) AS row_ctrl FROM tbl_orders WHERE reference_no='$reference_no';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['row_ctrl']==0){
				return $reference_no;
			}else{
				return UniqueId("REFER_NO");
			}
		break;
		case "ORDER_NO":
			$data = "123456789";
			for($i = 0; $i < 7; $i++){
				$order_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_subscription WHERE order_no='$order_no';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $order_no;
			}else{
				return UniqueId("ORDER_NO");
			}
		break;
		case "PRODUCT_ORDER_NO":
			$data = "123456789";
			for($i = 0; $i < 7; $i++){
				$order_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_orders WHERE order_no='$order_no';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $order_no;
			}else{
				return UniqueId("PRODUCT_ORDER_NO");
			}
		break;
		case "STOCK_TRNS_NO":
			$data = "123456789";
			for($i = 0; $i < 7; $i++){
				$trans_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_stock_ledger WHERE trans_no='".$trans_no."';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $trans_no;
			}else{
				return UniqueId("STOCK_TRNS_NO");
			}
		break;
		case "STOCK_ORDER_NO":
			$data = "123456789";
			for($i = 0; $i < 7; $i++){
				$ref_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_stock_ledger WHERE ref_no='".$ref_no."';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $ref_no;
			}else{
				return UniqueId("STOCK_ORDER_NO");
			}
		break;
		case "WH_ORDER_NO":
			$data = "123456789";
			for($i = 0; $i < 7; $i++){
				$ref_no .= substr($data, (rand()%(strlen($data))), 1);
			}
			$Q_CHK ="SELECT COUNT(*) AS fldiCtrl FROM tbl_stock_ledger WHERE ref_no='".$ref_no."';";
			$AR_CHK = $db->runQuery($Q_CHK,true);
			if($AR_CHK['fldiCtrl']==0){
				return $ref_no;
			}else{
				return UniqueId("STOCK_ORDER_NO");
			}
		break;
	}
}

function DisplayMultipleCombo($select_val,$switch,$where_id){
	$db = new SqlModel();
	switch($switch){
		case "PRODUCT_COMBO_ATTR":
			$QR_SEL = "SELECT tpca.post_attribute_id, tpca.attribute_id, tag.attribute_group_name, ta.attribute_name
					   FROM tbl_post_attribute_combination AS tpca
					   LEFT JOIN tbl_attribute AS ta ON ta.attribute_id=tpca.attribute_id
					   LEFT JOIN tbl_attribute_group AS tag ON tag.attribute_group_id=ta.attribute_group_id
					   LEFT JOIN tbl_post_attribute AS tpa ON tpa.post_attribute_id=tpca.post_attribute_id
					   WHERE tpa.post_attribute_id='".$where_id."'
					   GROUP BY tpca.attribute_id
					   ORDER BY ta.attribute_name ASC";
			$RS_SEL = $db->runQuery($QR_SEL);
			foreach($RS_SEL as $AR_SEL):
				echo "<option value='".$AR_SEL['attribute_id']."'";	if($select_val == $AR_SEL['attribute_id']){echo "selected";}
				echo ">".$AR_SEL['attribute_group_name']." : ".$AR_SEL['attribute_name']."</option>";
			endforeach;
		break;
	}
}


function getPostGst($tax_amount,$tax_ratio){
	$total_amount_tax = ( $tax_amount  /  ( ($tax_ratio/100)+1 ) );	
	$total_tax_devide = $tax_ratio/2;
	$total_tax_calc = ($total_amount_tax*$total_tax_devide)/100;
	$sum_total_tax_calc = $total_tax_calc*2;
	return $sum_total_tax_calc;
}
	
function stockStatus($stock_sts){
	switch($stock_sts){
		case "C":
			return '<span class="text-success"><i class="fa fa-check" aria-hidden="true"></i> Approved</span>';
		break;
		case "R":
			return '<span class="text-danger"><i class="fa fa-times" aria-hidden="true"></i> Rejected</span>';
		break;
		default:
			return '<span class="text-info"><i class="fa fa-adjust" aria-hidden="true"></i>  Pending</span>';
		break;
	}
}

function just_clean($string){
	$specialCharacters = array('#' => '','$' => '','%' => '','&' => '','@' => '','.' => '','?' => '','+' => '','=' => '','?' => '','\'' => '','/' => '',);
	while (list($character, $replacement) = each($specialCharacters)) {
		$string = str_replace($character, '-' . $replacement . '-', $string);
	}
	$string = strtr($string,"??????? ??????????????????????????????????????????????","AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn");
	$string = preg_replace('/[^a-zA-Z0-9_]/', ' ', $string);
	$string = preg_replace('/^[-]+/', '', $string);
	$string = preg_replace('/[-]+$/', '', $string);
	$string = preg_replace('/[-]{2,}/', ' ', $string);
	return $string;
}
function RemoveEnter($StrString){
	return trim( preg_replace( '/\s+/', ' ', $StrString));  
	//return nl2br($StrString, false);
}



function Send_Single_SMS($fldvMobile, $SMS, $AR_DT=''){
	$CI =& get_instance();
	$fldiMemId = $AR_DT['member_id'];
	$fldcType = $AR_DT['sms_type'];
	
	if($_SERVER['HTTP_HOST'] != "localhost:81" && $_SERVER['HTTP_HOST'] != "localhost"){
		
		/*$sender = "P2PLFC";
		$mobiles = $fldvMobile;
		$message = urlencode($SMS);		
		
		$url = "http://login.bulksmsgateway.in/sendmessage.php?user=cbonline&password=Cbo@1007&mobile=".$mobiles."&sender=".$sender."&message=".$message."&type=3";
		$ch = curl_init();
		if (!$ch){die("Couldn't initialize a cURL handle");}
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$fldvResponse = trim(strip_tags(curl_exec($ch)));
		curl_close($ch);*/
		
		$Q_INSERT = "INSERT INTO tbl_sms_details SET fldvMobile='$fldvMobile', fldvSMS='$SMS', fldcStatus='Y', fldiMemId='$fldiMemId', 					
					 fldcType='$fldcType', fldvResponse='$fldvResponse'";
		$CI->db->query($Q_INSERT);
		curl_close($ch);
		
	}
}

function convert_number($number){ 
	//PrintR(func_get_args());
    if(($number < 0) || ($number > 99999999999)){ throw new Exception("Number is out of range");}
	$Cn = floor($number / 10000000);  /* Millions (giga) */ 
    $number -= $Cn * 10000000; 
    $Gn = floor($number / 100000);  /* Millions (giga) */ 
    $number -= $Gn * 100000; 
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;               /* Ones */ 

    $res = ""; 
	if ($Cn) 
    { 
        $res .= convert_number($Cn) . " Crore "; 
    } 
    
	if ($Gn) 
    { 
        $res .= convert_number($Gn) . " Lakh "; 
    } 

    if ($kn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand "; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
    } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 

        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
} 
function getHeightWidth($ImagesPath, $reqWidth, $reqHeight){
	$file=trim($ImagesPath);
	if(file_exists($file)) {
		$dimension=getimagesize($file);
		if($dimension){
			$width=$dimension[0];
			$height=$dimension[1];
			if($reqWidth!="" & $reqHeight!=""){
				if($width>$reqWidth) $newWidth=$reqWidth;						
				else $newWidth=$width;
				$newHeight=floor(($newWidth/$width)*$height);
				if($newHeight>$reqHeight) {			
					$newWidth=$newWidth*($reqHeight/$newHeight);
					$newHeight=$reqHeight;
				}
			}else{
				$newWidth=$width;
				$newHeight=$height;
			}
			
			$ARHW["width"]=$newWidth;
			$ARHW["height"]=$newHeight;
			return $ARHW;
		}
	}
}

function DisplayMessage($fldvSwitch,$fldvMessage){
	echo '<script>$(function(){ setInterval(function(){$("#jsCallId").slideUp(600);}, 6000); });</script>';
	if($fldvSwitch){
		switch($fldvSwitch){
			case "success":
				$print="<div id='jsCallId' style='margin:3px;' class='success cmntext'>!! $fldvMessage !!</div>";
			break;
			case "warning":
				$print="<div id='jsCallId' style='margin:3px;' class='warning cmntext'>!! $fldvMessage !!</div>";
			break;
			case "failed":
				$print="<div id='jsCallId' style='margin:3px;' class='attention cmntext'>!! $fldvMessage !!</div>";
			break;
			
		}
		echo $print;
	}
}
function getWebPageName($PAGEURL){
	$this_page = basename($PAGEURL);
	if(strpos($this_page, "?") !== false) $this_page = reset(explode("?", $this_page));
	return $this_page;
}
function _e($string){
  $key = "MAL_979805"; //key to encrypt and decrypts.
  $result = '';
  $test = "";
   for($i=0; $i<strlen($string); $i++) {
     $char = substr($string, $i, 1);
     $keychar = substr($key, ($i % strlen($key))-1, 1);
     $char = chr(ord($char)+ord($keychar));

     $test[$char]= ord($char)+ord($keychar);
     $result.=$char;
   }

   return urlencode(base64_encode($result));
}
function _d($string){
	 $key = "MAL_979805"; //key to encrypt and decrypts.
    $result = '';
    $string = base64_decode(urldecode($string));
   for($i=0; $i<strlen($string); $i++) {
     $char = substr($string, $i, 1);
     $keychar = substr($key, ($i % strlen($key))-1, 1);
     $char = chr(ord($char)-ord($keychar));
     $result.=$char;
   }
   return $result;
}
function encode64($string){
	return base64_encode($string);
}
function decode64($string){
	return base64_decode($string);
}
function copyImg($file_name,$source_file,$folder_name,$path){
	$fldvUniqueNO = UniqueId("UNIQUE_NO");
	$save_file = $file_name;
	$data = file_get_contents($source_file);
	$fileName = $path."upload/".$folder_name."/".$save_file;
	$file = fopen($fileName, 'w+');
	fputs($file, $data);
	fclose($file);
	return $save_file;
}
function resizeImage($SrcImage,$DestImage, $MaxWidth,$MaxHeight,$Quality)
{
    list($iWidth,$iHeight,$type)    = getimagesize($SrcImage);
    $ImageScale             = min($MaxWidth/$iWidth, $MaxHeight/$iHeight);
    $NewWidth               = ceil($ImageScale*$iWidth);
    $NewHeight              = ceil($ImageScale*$iHeight);
    $NewCanves              = imagecreatetruecolor($NewWidth, $NewHeight);
	imagefilledrectangle($NewCanves, 0, 0, $NewWidth, $NewHeight, imagecolorallocate($NewCanves, 255, 255, 255));

		switch(strtolower(image_type_to_mime_type($type)))
		{
		case "image/jpeg":
			$NewImage = imagecreatefromjpeg($SrcImage);
		break;
		case "image/png":
			$NewImage = imagecreatefrompng($SrcImage);
		break;
		case "image/gif":
			$NewImage = imagecreatefromgif($SrcImage);
		break;
		default:
			return false;
		}

    // Resize Image
    if(imagecopyresampled($NewCanves, $NewImage,0, 0, 0, 0, $NewWidth, $NewHeight, $iWidth, $iHeight))
    {
        // copy file
        if(imagejpeg($NewCanves,$DestImage,$Quality))
        {
            imagedestroy($NewCanves);
            return true;
        }
    }
}

function SelectTableWithOption($table_name,$field_name,$where_clause){
	$SqlModel = new SqlModel();
    if($where_clause!=""){
		$where_condition="AND $where_clause";
	}
	$AR_SEL = $SqlModel->runQuery("SELECT $field_name FROM $table_name WHERE 1 $where_condition;",true);
	return $AR_SEL[$field_name];
}
function SelectTable($table_name,$field_name,$where_clause){
	$SqlModel = new SqlModel();
	if($where_clause!=""){
		$where_condition="AND $where_clause";
	}
	$QR_SELECT = "SELECT $field_name FROM $table_name WHERE 1 $where_condition;";
	$AR_SEL = $SqlModel->runQuery($QR_SELECT,true);
	return $AR_SEL;
}
function DeleteTableRow($table_name,$where_condition){
	$CI =& get_instance();
	if($where_condition!=""){
		$Del_Table="DELETE  FROM $table_name WHERE   $where_condition";
		$CI->db->query($Del_Table);
		return $CI->db->affected_rows();
	}
}
function UpdateTable($table_name,$field_name,$where_condition){
	$CI =& get_instance();
	if($where_condition!=""){
		$Up_Table="UPDATE $table_name SET $field_name WHERE $where_condition";
		$CI->db->query($Up_Table);
		return $CI->db->affected_rows();
	}
}
function InsertTable($table_name,$field_name){
	$CI =& get_instance();
	if($field_name!=""){
		$In_Table="INSERT INTO  $table_name SET $field_name";
		$CI->db->query($In_Table);
		return $CI->db->insert_id();
	}
}

function pop_loader($fldvPath){
	$fldvUrl = BASE_PATH;
	echo '<link rel="stylesheet" type="text/css" href="'.$fldvUrl.'popups/jquery_popupbox.css" />
	<script type="text/javascript" src="'.$fldvUrl.'popups/popups.js"></script>';
}
function javascript_alert($fldvMessage){
	echo '<script language="javascript" type="text/javascript">
	alert("'.$fldvMessage.'");
	</script>';
}

function jquery_validation(){
	echo '<link rel="stylesheet" type="text/css" href="'.BASE_PATH.'validator/validationEngine.jquery.css" />
	<script type="text/javascript" src="'.BASE_PATH.'validator/jquery.validationEngine.js"></script>
	<script type="text/javascript" src="'.BASE_PATH.'validator/jquery.validationEngine-en.js"></script>';
}
function auto_complete(){
	echo '<link rel="stylesheet" type="text/css" href="'.BASE_PATH.'autocomplete/autocomplete.css" />
	<script type="text/javascript" src="'.BASE_PATH.'autocomplete/autocomplete.js"></script>';
}
function jquery_file($fldvFile){
	$fldvUrl = BASE_PATH;
	if($fldvFile!=""){
		$fldvFileArr = explode(",",$fldvFile);
		foreach($fldvFileArr as $key=>$fldvNewFile){
			echo '<script type="text/javascript" src="'.$fldvUrl.$fldvNewFile.'"></script>';
		}
		
	}
}
function web_css($fldvFile){
	$fldvUrl =GetMISCCharges("fldvURL");
	if($fldvFile!=""){
		$fldvFileArr = explode(",",$fldvFile);
		foreach($fldvFileArr as $key=>$fldvNewFile){
			echo '<link rel="stylesheet" type="text/css" href="'.$fldvUrl.$fldvNewFile.'" />';
		}
		
	}
}
function jquery_open(){
	echo '<script type="text/javascript">';
}
function jquery_close(){	
	echo '</script>';
}
function page_redirect($fldvPath){
	if($fldvPath!=""){
		header("Location: $fldvPath");
	}else{
		header("Location: ?");
	}
}
function Number($table){
	$Q_Ctrl = "SELECT COUNT(fldiNumber) AS fldiCtrl FROM $table";
	$AR_Ctrl = ExecQ($Q_Ctrl,1);
	$fldiNumber = (100000 + 100000*$AR_Ctrl[fldiCtrl]);
	return $fldiNumber;
}

function currency($from_Currency,$to_Currency,$amount) {
	$amount = urlencode($amount);
	$from_Currency = urlencode($from_Currency);
	$to_Currency = urlencode($to_Currency);
	$url = "http://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";
	$ch = curl_init();
	$timeout = 0;
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$rawdata = curl_exec($ch);
	curl_close($ch);
	$data = explode('"', $rawdata);
	$data = explode(' ', $data['3']);
	$var = $data['0'];
	return round($var,3);
}

function highlightWords($content, $search){
    if(is_array($search)){
        foreach ( $search as $word ){   
		    if($word!="" and $word!="0" and $word!="on" and $word!="." and $word!=";" and $word!="name" and $word!="off" and $word!=$search[fldiJbSkrId] and $word!="IN"and $word!="yes" and $word!="AnyKey")
			{ 
			if(!is_numeric($word)){
				$neword=explode(",",$word);
				foreach($neword as $key=>$values){
           			 $content = str_ireplace(strtolower($values), '<span class="highlight_word">'.$values.'</span>', strtolower($content));
				 }
			 } 
			}
        }
    } else {
		if($search!="" and $search!="0"){
        	$content = str_ireplace(strtolower($search), '<span class="highlight_word">'.$search.'</span>', strtolower($content));
		}        
    }
    return $content;
} 

function DisplayStar($fldiRating){
	$fldvURL = GetMISCCharges("fldvURL");
	$fldvLink = $fldvURL."setupimages/star.png";
	
	switch($fldiRating){
		case "1":
			echo '<span style="float:right;"><img  src="'.$fldvLink.'"></span>';
		break;
		case "2":
			echo '<span style="float:right;"><img src="'.$fldvLink.'"> <img  src="'.$fldvLink.'"></span>';
		break;
		case "3":
			echo '<span style="float:right;"><img  src="'.$fldvLink.'"> <img  src="'.$fldvLink.'"> <img  src="'.$fldvLink.'"></span>';
		break;
		case "4":
			echo '<span style="float:right;"><img  src="'.$fldvLink.'"> <img  src="'.$fldvLink.'"> <img  src="'.$fldvLink.'"> <img " src="'.$fldvLink.'"></span>';
		break;
		case "5":
			echo '<span style="float:right;"><img src="'.$fldvLink.'"> <img  src="'.$fldvLink.'"> <img  src="'.$fldvLink.'"> <img  src="'.$fldvLink.'"> <img  src="'.$fldvLink.'"></span>';
		break;
	}
}
function DsplyCurrPrice($fldiPrice){
	if($fldiPrice){
		return "$&nbsp;".number_format($fldiPrice,2);
	}	
}
function CountWord($fldvName){	
	if($fldvName!=""){
		$fldiWord = strlen($fldvName);
		if($fldiWord<=60){
			return $fldvName;
		}else{
			return substr($fldvName,0,60)."...";
		}
	}
}
function setWord($fldvName,$fldvNumber){	
	if($fldvName!=""){
		$fldiWord = strlen($fldvName);
		if($fldiWord<=$fldvNumber){
			return $fldvName;
		}else{
			return substr($fldvName,0,$fldvNumber)."...";
		}
	}
}

function checkRadio($fldvField,$fldvMatch,$fldvDefault=''){
	if( ($fldvField!="" && $fldvField==$fldvMatch) or ( $fldvDefault=="true" and $fldvField=="") ){
	 echo 'checked="checked"';
	}
}

function javascript_close(){
	echo '<script language="javascript" type="text/javascript">
	window.opener.location.reload();
	window.opener.focus();
	window.close();
	</script>';
}
function garbage_param(){
	$fldvSSS=session_id();
	$fldvRemote = $_SERVER[REMOTE_ADDR];
	$flddTime = $_SERVER[REQUEST_TIME];
	return "&fldvRemote=$fldvRemote&flddTime=$flddTime&fldvSSS=$fldvSSS";
}
function javascript_redirect($fldvPageName){
  if($fldvPageName!=""){
	echo '<script language="javascript" type="text/javascript">
		window.location.href="'.$fldvPageName.'";
	</script>';
  }	
}

function echoo($fldvFiled){	
	if(!is_numeric($fldvFiled)){
		echo ($fldvFiled!="")? $fldvFiled:"N/A";
	}else{
		echo ($fldvFiled>0)? $fldvFiled:"N/A";
	}	
}
function getSumOrder($fldvSwitch,$fldvFiled){
	switch($fldvSwitch){
		case "MEMBER":
			return SelectTableWithOption("tbl_order_food","SUM(fldiAmount)","fldiMemId='$fldvFiled' AND fldcAprSts='Y'");
		break;
	}
}
function DisplayCurrency($fldiPrice){
	return "$".number_format($fldiPrice,2);
}

function date_picker(){
	$fldvUrl =GetMISCCharges("fldvURL");
	echo '<link rel="stylesheet" type="text/css" href="'.$fldvUrl.'datepicker/date_input.css" />
	<script type="text/javascript" src="'.$fldvUrl.'datepicker/jquery.date_input.pack.js"></script>';
}

$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
function getOS() { 
    global $user_agent;
    $os_platform    =   "Unknown OS Platform";
    $os_array       =   array(
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}

function getBrowser(){
		$u_agent = isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT']:$_SERVER['HTTP_USER_AGENT'];
		$bname=$platform=$version=$ub="Unknown";
		$os_array = array('/windows nt 6.2/i'=>'Windows 8', '/windows nt 6.1/i'=>'Windows 7', '/windows nt 6.0/i'=>'Windows Vista', '/windows nt 5.2/i'=>'Windows Server 2003/XP x64',
					'/windows nt 5.1/i'=>'Windows XP', '/windows xp/i'=>'Windows XP','/windows nt 5.0/i'=>'Windows 2000','/windows me/i'=>'Windows ME','/win98/i'=>'Windows 98',
					'/win95/i'=>'Windows 95', '/win16/i'=>'Windows 3.11','/macintosh|mac os x/i'=>'Mac OS X','/mac_powerpc/i'=>'Mac OS 9','/linux/i'=>'Linux','/ubuntu/i'=>
					'Ubuntu', '/iphone/i'=>'iPhone','/ipod/i'=>'iPod','/ipad/i'=>'iPad','/android/i'=>'Android','/blackberry/i'=>'BlackBerry','/webos/i'=>'Mobile');
		foreach ($os_array as $regex => $value) { 
			if(preg_match($regex, $u_agent)){$platform=$value;}
		}  
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}elseif(preg_match('/Firefox/i',$u_agent)){
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}elseif(preg_match('/Chrome/i',$u_agent)){
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}elseif(preg_match('/Safari/i',$u_agent)){
			$bname = 'Apple Safari';
			$ub = "Safari";
		}elseif(preg_match('/Opera/i',$u_agent)){
			$bname = 'Opera';
			$ub = "Opera";
		}elseif(preg_match('/Netscape/i',$u_agent)){
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if(!preg_match_all($pattern, $u_agent, $matches)){
			// we have no matching number just continue
		}
		// see how many we have
		$i=count($matches['browser']);
		if ($i != 1){
			if(strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}else{
				$version= isset($matches['version'][1])?$matches['version'][1]:'';
			}
		}else{
			$version= $matches['version'][0];
		}
		// check if we have a number
		if($version==null || $version==""){
			$version="?";
		}
		return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'browser'   => $ub,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
		);
}
function static_message($type,$message){
	$CI = & get_instance();
	if($type!="" and $message!=""){		
	 	$CI->session->unset_userdata('type');
	    $CI->session->unset_userdata('message');

		$CI->session->set_userdata('type',$type);
		$CI->session->set_userdata('message',$message);
	}
}
function display_message(){
	$CI = & get_instance();
	echo '<script>$(function(){ setInterval(function(){$("#jsCallId").slideUp(600);}, 10000); });</script>';
	$message = $CI->session->userdata('message');
	$type = $CI->session->userdata('type');
	if($message!=''){
		switch($type){
			case "success":
				$print="<div class='alert alert-block alert-success' id='jsCallId'><i class='ace-icon fa fa-check green'></i>&nbsp;".$message."</div>";
			break;
			case "failed":
			case "warning":
			default:
				$print="<div class='alert alert-block alert-danger' id='jsCallId'><i class='ace-icon fa fa-times red'></i>&nbsp;".$message."</div>";
			break;	
			
		}
		$CI->session->unset_userdata('type');
	    $CI->session->unset_userdata('message');
		echo $print;
	}
}
function ExportQuery($SQLQUERY,$PARAM=''){
	$CI = & get_instance();
	$CI->session->unset_userdata('SQLQUERY');
	$CI->session->unset_userdata('PARAM');
	
	$CI->session->set_userdata('PARAM',$PARAM);
	$CI->session->set_userdata('SQLQUERY',$SQLQUERY);
}
function ImportQuery(){
	$CI = & get_instance();
	$SQLQUERY = $CI->session->userdata('SQLQUERY');
	#$CI->session->unset_userdata('SQLQUERY');
	return $SQLQUERY;
}
function set_message($fldcType,$fldvMessage){
	$CI = & get_instance();
	if($fldcType!="" and $fldvMessage!=""){		
	 	$CI->session->unset_userdata('fldcType');
	    $CI->session->unset_userdata('fldvMessage');

		$CI->session->set_userdata('fldcType',$fldcType);
		$CI->session->set_userdata('fldvMessage',$fldvMessage);
	}
}
function get_message(){
	$CI = & get_instance();
	echo '<script>$(function(){ setInterval(function(){$("#jsCallId").slideUp(600);}, 10000); });</script>';
	$fldvMessage = $CI->session->userdata('fldvMessage');
	$fldcType = $CI->session->userdata('fldcType');
	if($fldvMessage!=''){
		switch($fldcType){
			case "success":
				$print="<div class='alert alert-block alert-success' id='jsCallId'><i class='fa fa-check green'></i>&nbsp;".$fldvMessage."</div>";
			break;
			case "failed":
			case "warning":
			default:
				$print="<div class='alert alert-block alert-danger' id='jsCallId'><i class='fa fa-times red'></i>&nbsp;".$fldvMessage."</div>";
			break;	
			
		}
		$CI->session->unset_userdata('fldcType');
	    $CI->session->unset_userdata('fldvMessage');
		echo $print;
	}
}
function getTree($type=''){
	switch($type):
		case "DFT":
			return "tbl_mem_tree";
		break;
		case "LVL":
			return "tbl_mem_tree";
		break;
		default:
			return "tbl_mem_tree";
		break;
	endswitch;
}
function DisplayText($fldvField){
	switch($fldvField){
		case "MEM_L":
			return "Left";
		break;
		case "MEM_R":
			return "Right";
		break;
		case "JOIN_M":
			return "Member";
		break;
		case "JOIN_A":
			return "Agent";
		break;
		case "LOG_S":
			return "Success";
		break;
		case "LOG_F":
			return "Failed";
		break;
		case "TIME_Y":
			return "Year";
		break;
		case "TIME_M":
			return "Month";
		break;
		case "TIME_W":	
			return "Week";
		break;
		case "TIME_D":
			return "Days";
		break;
		case "GENDER_":
		case "GENDER_M":
			return "Male";
		break;
		case "GENDER_F":
			return "Female";
		break;
		case "TICKET_O":
			return "Customer Reply";
		break;
		case "TICKET_P":
			return "Ticket Open";
		break;
		case "TICKET_R":
			return "Admin Reply";
		break;
		case "TICKET_H":
			return "Admin Reply";
		break;
		case "TICKET_C":
			return "Close";
		break;
		case "LOG_N":
			return "N/A";
		break;
		case "WITHDRAW_P":
			return "Pending";
		break;
		case "WITHDRAW_C":
			return "Approved";
		break;
		case "WITHDRAW_R":
			return "Rejected";
		break;
		case "DEPOSIT_P":
			return "Pending";
		break;
		case "DEPOSIT_C":
			return "Approved";
		break;
		case "DEPOSIT_R":
			return "Rejected";
		break;
		case "INCOME_Y":
			return "Paid";
		break;
		case "PIN_N":
			return "Pending";
		break;
		case "PIN_Y":
			return "Approved";
		break;
		case "PIN_C":
			return "Rejected";
		break;
		case "N":
			return "Pending";
		break;
		case "TRNS_Cr":
			return "Credit";
		break;
		case "TRNS_Dr":
			return "Debit";
		break;
		case "OWNPRODUCT":
			return "Own Product";
		break;
		case "TIEPRODUCT":
			return "Tie Up Product";
		break;
		case "NA":
			return "N/A";
		break;
		
	}
}
function DisplayAttrCombination($post_attribute_id){
		$db = new SqlModel();
		$QR_SEL = "SELECT tpca.post_attribute_id, tpca.attribute_id, tag.attribute_group_name, ta.attribute_name
				   FROM tbl_post_attribute_combination AS tpca
				   LEFT JOIN tbl_attribute AS ta ON ta.attribute_id=tpca.attribute_id
				   LEFT JOIN tbl_attribute_group AS tag ON tag.attribute_group_id=ta.attribute_group_id
				   LEFT JOIN tbl_post_attribute AS tpa ON tpa.post_attribute_id=tpca.post_attribute_id
				   WHERE tpca.post_attribute_id='".$post_attribute_id."'
				   GROUP BY tpca.attribute_id
				   ORDER BY ta.attribute_name ASC";
		$RS_SEL = $db->runQuery($QR_SEL);
		echo '<ul class="holder" style="width:95%">';
		foreach($RS_SEL as $AR_SEL){
			echo '<li  class="bit-box" rel="103">'.$AR_SEL['attribute_group_name']."-".$AR_SEL['attribute_name'].'</li>';
		}
		echo '</ul>';
}
function DisplayMonth($fldvValue){
	if($fldvValue!=""){
		$fldvValueArr = array_filter(explode(",",$fldvValue));
		echo '<ul class="holder" style="width:95%">';
		foreach($fldvValueArr as $key=>$value){
			$monthName = date('F', mktime(0, 0, 0, $value, 10));
			echo '<li  class="bit-box" rel="103">'.$monthName.'</li>';
		}
		echo '</ul>';
		
	}
}
function setSession($fldvField,$fldvValue){
	return $_SESSION[$fldvField]=$fldvValue;
}
function getSession($fldvFiled){
	return $_SESSION[$fldvFiled];
}
function generateSeoUrl($controller,$action='',$params='') {
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$baseUrl = base_url();
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = BASE_PATH.$controllerName . "/" . $actionName . $paramString;
	return $generatedUrl;
}
function generateSeoUrlAdmin($controller,$action='',$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = ADMIN_PATH.$controllerName . "/" . $actionName . $paramString;
	return $generatedUrl;
}
function generateSeoUrlMember($controller,$action='',$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = MEMBER_PATH.$controllerName . "/" . $actionName . $paramString;
	return $generatedUrl;
}
function generateSeoUrlFranchise($controller,$action='',$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = FRANCHISE_PATH.$controllerName . "/" . $actionName . $paramString;
	return $generatedUrl;
}
function generateFranchiseForm($controller,$action='',$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = FRANCHISE_PATH.$controllerName . "/" . $actionName . $paramString;
	return $generatedUrl;	
}
function generateMemberForm($controller,$action='',$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = MEMBER_PATH.$controllerName . "/" . $actionName . $paramString;
	return $generatedUrl;	
}
function generateAdminForm($controller,$action='',$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = ADMIN_PATH.$controllerName . "/" . $actionName . $paramString;
	return $generatedUrl;	
}
function generateForm($controller,$action='',$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = BASE_PATH.$controllerName . "/" . $actionName . $paramString;
	return $generatedUrl;	
}
function redirect_page($controller,$action,$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = ADMIN_PATH.$controllerName . "/" . $actionName . $paramString;
	redirect($generatedUrl); exit;
}
function redirect_member($controller,$action,$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = MEMBER_PATH.$controllerName . "/" . $actionName . $paramString;
	redirect($generatedUrl); exit;
}

function redirect_franchise($controller,$action,$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = FRANCHISE_PATH.$controllerName . "/" . $actionName . $paramString;
	redirect($generatedUrl); exit;
}
function redirect_front($controller,$action,$params=''){
	$controllerName = ($controller!="")? $controller:"index";		
	$actionName = ($action == '') ? "": $action;
	$paramString = '';
	foreach ($params as $key => $para) {
		$paramString .= "/" . $key . "/" . $para;
	}
	$generatedUrl = BASE_PATH.$controllerName . "/" . $actionName . $paramString;
	redirect($generatedUrl); exit;
}
function getMemberImage($member_id){
	$db = new SqlModel();
	$QR_MEM = "SELECT tm.member_id, tm.photo, tm.gender FROM tbl_members AS tm WHERE tm.member_id='$member_id'";
	$AR_MEM = $db->runQuery($QR_MEM,true);
	$image_path = BASE_PATH."upload/member/".$AR_MEM['photo'];
	$fldvImageArr= @getimagesize($image_path);
	switch($AR_MEM['gender']):
		case "F":
			if($fldvImageArr['mime']=="") { 
				$image_path = BASE_PATH."assets/images/avatars/avatar03.png";
			}			
		break;
		case "M":
		default:
			if($fldvImageArr['mime']=="") { 
				$image_path = BASE_PATH."assets/img/default_profile.png";
			}
		break;
	endswitch;
	return $image_path;
}
function getHttpContent($url){						
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);				
	$html .= curl_exec($curl);
	curl_close ($curl);
	return $html;
}

function Send_Mail($ARRAY,$fldvTemplate){
	$model = new OperationModel();
	if($_SERVER['HTTP_HOST']!=''){
		switch($fldvTemplate){
			case "INVITATION":
				$fldvEmail  = FCrtRplc($ARRAY['mail_email']);
				$fldvEmailArr = array_filter(array_unique(explode(",",$fldvEmail)));
				$fldvSubject  = FCrtRplc($ARRAY['email_subject']);
				$fldvMessage  = FCrtRplc($ARRAY['email_body']);
			break;
			case "REGISTER":
				$member_id  = FCrtRplc($ARRAY['member_id']);
				$fldvSubject  = WEBSITE." | Registration";
				$fldvEmailArr = array_filter(array_unique(explode(",",$fldvEmail)));
				$PARAM = generateSeoUrl("account","email",array("member_id"=>$member_id,"option_name"=>"welcome_template"));
			break;
			case "EMAIL_VERIFY":
				$member_id  = FCrtRplc($ARRAY['member_id']);
				$fldvSubject = WEBSITE." | Email verifcation";
				$AR_MEM = $model->getMember($member_id);
				$fldvEmail = $AR_MEM['member_email'];
				$fldvEmailArr = array_filter(array_unique(explode(",",$fldvEmail)));
				$PARAM = generateSeoUrl("account","email",array("member_id"=>$member_id,"option_name"=>"email_verify"));
			break;
			case "EMAIL_FRAN_VERIFY":
				$franchisee_id  = FCrtRplc($ARRAY['franchisee_id']);
				$fldvSubject = WEBSITE." | Vendor Email verifcation";
				$AR_FRAN = $model->getFranchiseeDetail($franchisee_id);
				$fldvEmail = $AR_FRAN['email'];
				$fldvEmailArr = array_filter(array_unique(explode(",",$fldvEmail)));
				$PARAM = generateSeoUrl("account","email",array("franchisee_id"=>$franchisee_id,"option_name"=>"email_verify_vendor"));
			break;
			case "ORDER_STATUS_FRAN":
				$order_id  = FCrtRplc($ARRAY['order_id']);
				$fldvSubject = "Welcome to ". WEBSITE;
				$AR_MEM = $model->getMember($member_id);
				$fldvEmail = $AR_MEM['member_email'];
				$fldvEmailArr = array_filter(array_unique(explode(",",$fldvEmail)));
				$PARAM = generateSeoUrl("account","email",array("order_id"=>$order_id,"option_name"=>"order_status_vendor"));
			break;
			case "WELCOME_MAIL":
				$member_id  = FCrtRplc($ARRAY['member_id']);
				$fldvSubject = "Welcome to ". WEBSITE;
				$AR_MEM = $model->getMember($member_id);
				$fldvEmail = $AR_MEM['member_email'];
				$fldvEmailArr = array_filter(array_unique(explode(",",$fldvEmail)));
				$PARAM = generateSeoUrl("account","email",array("member_id"=>$member_id,"option_name"=>"welcome_template"));
			break;
			case "FORGOT_PASSWORD":
				$member_id = FCrtRplc($ARRAY['member_id']);
				$AR_MEM = $model->getMember($member_id);
				$fldvEmail = $AR_MEM['member_email'];
				$fldvEmailArr = array_filter(array_unique(explode(",",$fldvEmail)));
				$fldvSubject  = WEBSITE." | Password Recovery";
				$PARAM = generateSeoUrl("account","email",array("member_id"=>$member_id,"option_name"=>"forgot_password"));
			break;
		}
		if($fldvEmail!=""){
			
			$fldvServerMail = $model->getValue("CONFIG_MASS_LOGIN");
			$fldvComName = $model->getValue("CONFIG_COMPANY_NAME");
			
			$mail   = new PHPMailer();
			$mail->IsSMTP();
			$mail->Host = $model->getValue("CONFIG_MASS_HOST");
			$mail->SMTPAuth = true;
			$mail->Username = $model->getValue("CONFIG_MASS_LOGIN");
			$mail->Password = $model->getValue("CONFIG_MASS_PASSWORD");
		

			$mail   = new PHPMailer();
			$body   = ($PARAM)? file_get_contents($PARAM):$fldvMessage;
			
			$mail->AddReplyTo($fldvServerMail,$fldvComName);
			$mail->SetFrom($fldvServerMail, $fldvComName);
			$mail->AddReplyTo($fldvServerMail,$fldvComName);	
			if($fldvAttachment==true){
				$mail->AddAttachment($fldiLink,$fileName);
			}
			$fldvEmail = '';
			foreach($fldvEmailArr as $fldvEmail){
				$mail->AddAddress($fldvEmail,$fldvSubject);
				$mail->Subject    = $fldvSubject;
				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
				$mail->MsgHTML($body);
				$mail->Send();
				$mail->ClearAddresses();
			}
		}	
	}
}
function DisplayICon($icon_id){
		return SelectTableWithOption("tbl_font_awsome_icon","icon_name","icon_id='$icon_id'");
}
function xlsBOF() {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
    return;
}

function xlsEOF() {
    echo pack("ss", 0x0A, 0x00);
    return;
}

function xlsWriteNumber($Row, $Col, $Value) {
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);
    return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
return;
} 
function validate($address){
       $decoded = decodeBase58($address);
 
       $d1 = hash("sha256", substr($decoded,0,21), true);
       $d2 = hash("sha256", $d1, true);
 
       if(substr_compare($decoded, $d2, 21, 4)){
               throw new \Exception("bad digest");
       }
       return true;
}
function get_web_page($url) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
        CURLOPT_ENCODING       => "",     // handle compressed
        CURLOPT_USERAGENT      => "test", // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
        CURLOPT_TIMEOUT        => 120,    // time-out on response
    ); 

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);

    $content  = curl_exec($ch);

    curl_close($ch);

    return $content;
}
function decodeBase58($input) {
       $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
 
       $out = array_fill(0, 25, 0);
       for($i=0;$i<strlen($input);$i++){
               if(($p=strpos($alphabet, $input[$i]))===false){
                       throw new \Exception("invalid character found");
               }
               $c = $p;
               for ($j = 25; $j--; ) {
                       $c += (int)(58 * $out[$j]);
                       $out[$j] = (int)($c % 256);
                       $c /= 256;
                       $c = (int)$c;
               }
               if($c != 0){
                   throw new \Exception("address too long");
               }
       }
 
       $result = "";
       foreach($out as $val){
               $result .= chr($val);
       }
 
       return $result;
}
function btc_encode($amount){
	if(is_numeric($amount)){
		return  ($amount*100000000);
	}
}
function btc_decode($amount){
	if(is_numeric($amount)){
		return  ($amount/100000000);
	}
}

function btc_val($amount,$decimal=''){
	if(is_numeric($amount)){
		return number_format($amount,($decimal)? $decimal:BTC);
	}else{	
		return 0;
	}
}
function cal_btc($amount){
	if(is_numeric($amount)){
		return number_format($amount,BTC,".",""); 
	}else{	
		return 0;
	}
}
?>