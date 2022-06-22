<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$StrWhr .=" AND (ord.member_id='".$member_id."')";

if($_REQUEST['order_no']!=''){
	$order_no = FCrtRplc($_REQUEST['order_no']);
	$StrWhr .=" AND ( ord.order_no = '$order_no' )";
	$SrchQ .="&order_no=$order_no";
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
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head></head>
<body>
<table border="1" style="border-collapse:collapse;" cellpadding="5" cellspacing="2">
  <thead>
    <tr>
      <td class="center">ID</td>
      <td>Order No </td>
      <td>No of Product</td>
      <td>Total</td>
      <td>Payment </td>
      <td>Status</td>
      <td>Date</td>
      <td>&nbsp;</td>
    </tr>
  </thead>
  <tbody>
	<?php 
	if($PageVal['TotalRecords'] > 0){
		$Ctrl=1;
		foreach($PageVal['ResultSet'] as $AR_DT):
	?>
    <tr>
      <td width="38" class="center"><label class="pos-rel"> <?php echo $AR_DT['order_id']; ?> <span class="lbl"></span> </label>
      </td>
      <td width="143"><?php echo $AR_DT['order_no']; ?> </td>
      <td width="134"><?php echo $AR_DT['full_name']; ?> </td>
      <td width="158"><?php echo number_format($AR_DT['total_paid_real'],2); ?></td>
      <td width="164"><?php echo $AR_DT['payment']; ?></td>
      <td width="165"><?php echo $AR_DT['order_state']; ?></td>
      <td width="146"><?php echo $AR_DT['date_add']; ?></td>
      <td width="94"><a class="btn btn-primary" href="<?php echo generateSeoUrlMember("order","orderview",array("order_id"=>_e($AR_DT['order_id']))); ?>">View</a> </td>
    </tr>
    <?php $Ctrl++; endforeach; }else{ ?>
    <tr>
      <td colspan="8" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
    </tr>
    <?php } ?>
  </tbody>
</table>
</body>
</html>
