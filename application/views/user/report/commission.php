<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$StrWhr .=" AND (ord.member_id='".$member_id."')";

$CONFIG_TDS = $model->getValue("CONFIG_TDS");
$CONFIG_FOUNDATION = $model->getValue("CONFIG_FOUNDATION");
$CONFIG_PROCESSING = $model->getValue("CONFIG_PROCESSING");
$CONFIG_REPURCHASE = $model->getValue("CONFIG_REPURCHASE");

if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);	
	$StrWhr .=" AND DATE(ord.date_add) BETWEEN '".$from_date."' AND '".$to_date."'";
	$SrchQ  = "&from_date=$from_date&to_date=$to_date";
}

$QR_PAGES = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id ,
			 tad.current_address AS order_address, tos.name AS order_state
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0 $StrWhr
			 GROUP BY ord.order_id
			 ORDER BY ord.order_id DESC";
$AR_PAGES = $this->SqlModel->runQuery($QR_PAGES);
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<table border="1" style="border-collapse:collapse;" cellpadding="5" cellspacing="2">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>ORDER NO</th>
                  <th>NO OF PRODUCT </th>
                  <th align="right">AMOUNT</th>
                  <th align="right">DATE </th>
                  <th align="right" style="min-width: 80px;">POINT VALUE</th>
                </tr>
              </thead>
              <tbody>
			  	  <?php 
			 	 	if(count($AR_PAGES) > 0){
				  		$Ctrl=1;
						foreach($AR_PAGES as $AR_DT):
						$net_total_pv +=$AR_DT['total_pv'];
			       ?>
                <tr>
                  <td><?php echo $Ctrl; ?> </td>
                  <td><?php echo $AR_DT['order_no']; ?></td>
                  <td><?php echo $AR_DT['total_products']; ?></td>
                  <td align="right"><?php echo number_format($AR_DT['total_paid_real']); ?></td>
                  <td align="right"><?php echo $AR_DT['date_add']; ?></td>
                  <td align="right"><?php echo $AR_DT['total_pv']; ?></td>
                </tr>
               
				 <?php 
				 	$Ctrl++; endforeach;  }
				 	$net_tds = ($net_total_pv*$CONFIG_TDS)/100;
					$net_foundation = ($net_total_pv*$CONFIG_FOUNDATION)/100;
					$net_processing = ($net_total_pv*$CONFIG_PROCESSING)/100;
					$net_repurchase = ($net_total_pv*$CONFIG_REPURCHASE)/100;
					
					$net_deduction = $net_tds+$net_foundation+$net_processing+$net_repurchase;
					$net_total_payment = $net_total_pv-$net_deduction;
				  ?>
              </tbody>
            </table>
</body>
</html>
