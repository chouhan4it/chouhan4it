<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$wallet_id = $model->getWallet("Re-Purchase E-Wallet");


if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);	
	$StrWhr .=" AND DATE(twt.trns_date) BETWEEN '".$from_date."' AND '".$to_date."'";
	$SrchQ  = "&from_date=$from_date&to_date=$to_date";
}

$QR_PAGES = "SELECT twt.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id 
			 FROM tbl_wallet_trns AS twt
			 LEFT JOIN tbl_members AS tm ON tm.member_id=twt.member_id
			 WHERE twt.member_id='".$member_id."' AND twt.wallet_id='".$wallet_id."'
			 $StrWhr
			 GROUP BY twt.trans_no
			 ORDER BY twt.wallet_trns_id ASC";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head></head>
<body>
<table border="1" style="border-collapse:collapse;" cellpadding="5" cellspacing="2">
  <thead>
    <tr>
      <th>Srl  No </th>
      <th>Date</th>
      <th>Trns  No </th>
      <th>Trns Detail </th>
      <th>Credit </th>
      <th>Debit </th>
      <th>Balance</th>
    </tr>
  </thead>
  <tbody>
    <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
							switch($AR_DT['trns_type']):
								case "Cr":
									$dr_amount = 0;
									$cr_amount = $AR_DT['trns_amount'];
									$credit_amt +=$AR_DT['trns_amount'];
									$net_balalnce = $credit_amt-$debit_amt;
								break;
								case "Dr":
									$cr_amount = 0;
									$dr_amount = $AR_DT['trns_amount'];
									$debit_amt +=$AR_DT['trns_amount'];
									$net_balalnce =$credit_amt-$debit_amt;
								break;
							endswitch;
			       ?>
    <tr>
      <td><?php echo $Ctrl; ?> </td>
      <td><?php echo DisplayDate($AR_DT['trns_date']); ?></td>
      <td><?php echo $AR_DT['trans_no']; ?></td>
      <td><?php echo $AR_DT['trns_remark']; ?></td>
      <td><?php echo number_format($cr_amount); ?></td>
      <td><?php echo number_format($dr_amount); ?></td>
      <td><?php echo number_format($net_balalnce); ?></td>
    </tr>
    <?php $Ctrl++; endforeach; }else{ ?>
    <tr>
      <td colspan="7" class="text-danger">No transaction found </td>
    </tr>
    <?php } ?>
  </tbody>
</table>
</body>
</html>
