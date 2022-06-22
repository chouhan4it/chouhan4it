<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$gst_invoice_date = "2017-07-01";

if($_REQUEST['invoice_number']!=''){
	$invoice_number = FCrtRplc($_REQUEST['invoice_number']);
	$StrWhr .=" AND ( ord.invoice_number = '$invoice_number' )";
	$SrchQ .="&invoice_number=$invoice_number";
}
if($_REQUEST['franchisee_id']>0){
	$franchisee_id = FCrtRplc($_REQUEST['franchisee_id']);
	$StrWhr .=" AND ( ord.franchisee_id='".$franchisee_id."')";
	$SrchQ .="&franchisee_id=$franchisee_id";
}
if($_REQUEST['user_id']!=''){
	$user_id = FCrtRplc($_REQUEST['user_id']);
	$member_id = $model->getMemberId($user_id);
	$StrWhr .=" AND ( ord.member_id = '$member_id' )";
	$SrchQ .="&user_id=$user_id";
}
if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND ( DATE(ord.date_add) BETWEEN '$date_from' AND '$date_to')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$QR_FRAN = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.member_mobile, tm.city_name, tm.state_name,
			tad.current_address AS order_address, tad.order_type, tos.name AS order_state
			FROM tbl_orders AS ord
			LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			WHERE ord.order_id>0  AND ( ord.invoice_number!='0' AND ord.invoice_number!='' )
			AND DATE(ord.invoice_date)>='".$gst_invoice_date."'
			$StrWhr
			GROUP BY ord.order_id
			ORDER BY ord.order_id DESC";
$PageVal = DisplayPages( $QR_FRAN, 100, $Page, $SrchQ);

ExportQuery($QR_FRAN ,array("d_month"=>$d_month,"d_year"=>$d_year));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title><?php echo title_name(); ?></title>
<meta name="description" content="Static &amp; Dynamic Tables" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/fonts.googleapis.com.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-skins.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-rtl.min.css" />
<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<?php auto_complete(); ?>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
<style type="text/css">
.danger_alert {
	background-color: #f2dede;
	border-color: #ebccd1;
	color: #a94442;
}
.success_alert {
	background-color: #dff0d8;
	border-color: #d6e9c6;
	color: #3c763d;
}
.pointer {
	cursor: pointer;
}
tr > td {
	font-size: 10px;
}
</style>
</head>
<body class="no-skin">
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Shoppe <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Tax Summary </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12">
            <div class="clearfix">
              <div class="row">
                <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("shop","taxsummary",""); ?>" method="post">
                  <div class="col-md-2">
                    <input id="invoice_number" placeholder="Invoice No" name="invoice_number"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['invoice_number']; ?>">
                  </div>
                  <div class="col-md-2">
                    <select name="franchisee_id"  id="franchisee_id" class="form-control">
                      <option value="">----Franchise----</option>
                      <?php DisplayCombo($_REQUEST['franchisee_id'],"FRANCHISEE");  ?>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <div class="input-group">
                      <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="Date From" type="text"  />
                      <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                  </div>
                  <div class="col-md-2">
                    <div class="input-group">
                      <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                      <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                  </div>
                  <div class="col-md-4">
                    <button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
                    <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                    <button type="button" class="btn btn-sm btn-info" onClick="window.location.href='<?php echo generateSeoUrlAdmin("excel","taxsummary",""); ?>'"> <i class="ace-icon fa fa-download"></i> Download </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <hr>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr class="">
                <td align="left"><strong>SR NO</strong></td>
                <td  align="left"><strong>INVOICE NO</strong></td>
                <td align="left"><strong>NAME</strong></td>
                <td  align="right"><strong>User Id</strong></td>
                <td align="right"><strong>DATE</strong></td>
                <td align="right"><strong>INVOICE TOTAL</strong></td>
                <td colspan="3" align="center"><strong>12%</strong></td>
                <td colspan="3" align="center"><strong>18%</strong></td>
                <td colspan="3" align="center"><strong>28%</strong></td>
                <td align="right"><strong>TAXABLE TOTAL</strong></td>
                <td align="right"><strong>TOTAL TAX</strong></td>
              </tr>
              <tr class="">
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="center"><strong>TAXABLE</strong></td>
                <td align="center"><strong>CGST 6%</strong></td>
                <td align="center"><strong>SGST 6%</strong></td>
                <td align="center"><strong>TAXABLE</strong></td>
                <td align="center"><strong>CGST 9%</strong></td>
                <td align="center"><strong>SGST 9%</strong></td>
                <td align="center"><strong>TAXABLE</strong></td>
                <td align="center"><strong>CGST 14%</strong></td>
                <td align="center"><strong>SGST 14%</strong></td>
                <td align="right">&nbsp;</td>
                <td align="right">&nbsp;</td>
              </tr>
              <?php 
			if($PageVal['TotalRecords'] > 0){
			$Ctrl = $PageVal['RecordStart']+1;
			foreach($PageVal['ResultSet'] as $AR_DT):
			$AR_GST = $model->getOrderGst($AR_DT['order_id']);
			$order_12_tax = $AR_GST['12']['order_tax_devide'];
			$order_18_tax = $AR_GST['18']['order_tax_devide'];
			$order_28_tax = $AR_GST['28']['order_tax_devide'];
			$order_gst_tax = $order_12_tax+$order_18_tax+$order_28_tax;
			$order_total_tax = $order_gst_tax*2;
			$invoice_type = (strtotime(InsertDate($AR_DT['invoice_date']))>strtotime("2017-06-30"))? "invoicedetail":"invoiceview";
			$QR_ORD_TAX = "SELECT SUM(tod.post_pv*tod.post_qty) AS order_pv, SUM(tod.original_post_price*tod.post_qty) AS order_mrp, 
						   SUM(tod.post_price*tod.post_qty) AS order_rcp,
						   tod.post_tax, tod.tax_age, tod.post_qty, tod.net_amount
						   FROM tbl_order_detail AS tod 
						   WHERE tod.order_id='".$AR_DT['order_id']."' 
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
			?>
              <tr>
                <td><?php echo $Ctrl;?></td>
                <td align="left"><a target="_blank" href="<?php echo generateSeoUrlAdmin("shop",$invoice_type,array("order_id"=>_e($AR_DT['order_id']))); ?>"><?php echo $AR_DT['invoice_number']; ?></a></td>
                <td align="left"><?php echo strtoupper($AR_DT['full_name']); ?></td>
                <td align="right"><?php echo $AR_DT['user_id']; ?></td>
                <td align="right"><?php echo DisplayDate($AR_DT['invoice_date']); ?></td>
                <td align="right"><?php echo number_format($AR_DT['total_paid'],2); ?></td>
                <td align="center" ><?php echo number_format($taxable12,2); ?></td>
                <td align="center" ><?php echo number_format($order_12_tax,2); ?></td>
                <td align="center"><?php echo number_format($order_12_tax,2); ?></td>
                <td align="center" ><?php echo number_format($taxable18,2); ?></td>
                <td align="center" ><?php echo number_format($order_18_tax,2); ?></td>
                <td align="center" ><?php echo number_format($order_18_tax,2); ?></td>
                <td align="center" ><?php echo number_format($taxable28,2); ?></td>
                <td align="center"><?php echo number_format($order_28_tax,2); ?></td>
                <td align="center"><?php echo number_format($order_28_tax,2) ?></td>
                <td align="right"><?php echo number_format($sum_order_rcp,2); ?></td>
                <td align="right"><strong><?php echo number_format($order_total_tax,2); ?></strong></td>
              </tr>
              <?php
			$Ctrl++;
			unset($AR_GST,$order_12_tax,$order_18_tax,$order_28_tax,$order_gst_tax,$order_total_tax,$sum_order_rcp,$taxable12,$taxable18,$taxable28);
			endforeach;
			?>
              <?php 
			}else{
			?>
              <tr>
                <td colspan="13" align="center" class="errMsg">No record found</td>
              </tr>
              <?php } ?>
            </table>
            <ul class="pagination">
              <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
            </ul>
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
	});
</script>
</body>
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</html>
