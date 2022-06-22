<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$gst_invoice_date = "2017-07-01";
$franchisee_id = $this->session->userdata('fran_id');
$StrWhr .=" AND (ord.franchisee_id='".$franchisee_id."')";

if($_REQUEST['user_id']!=''){
	$user_id = FCrtRplc($_REQUEST['user_id']);
	$StrWhr .=" AND ( tm.user_name = '%$user_id%' OR tm.user_id = '$user_id')";
	$SrchQ .="&user_id=$user_id";
}
if($_REQUEST['name']!=''){
	$name = FCrtRplc($_REQUEST['name']);
	$StrWhr .=" AND ( tm.first_name LIKE '%$name%' OR tm.last_name LIKE '%$name%')";
	$SrchQ .="&name=$name";
}
if($_REQUEST['invoice_number']!=''){
	$invoice_number = FCrtRplc($_REQUEST['invoice_number']);
	$StrWhr .=" AND (ord.invoice_number = '$invoice_number')";
	$SrchQ .="&invoice_number=$invoice_number";
}
if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND (DATE(ord.date_add) BETWEEN '$date_from' AND '$date_to')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$QR_FRAN = "SELECT coup.*, 
			ord.order_id, DATE(ord.invoice_date) AS invoice_date, ord.invoice_number, ord.order_no, fra.user_name, 
			CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id, tm.city_name, ord.total_products, ord.total_paid, 
			ord.total_bv, ord.total_pv, DATE(ord.date_add) AS date_add 
			FROM tbl_coupon AS coup
			LEFT JOIN tbl_orders AS ord ON ord.invoice_number=coup.use_inv_no
			LEFT JOIN tbl_members AS tm ON tm.member_id=coup.use_member_id
			LEFT JOIN tbl_franchisee AS fra ON fra.franchisee_id=ord.franchisee_id
			WHERE coup.use_inv_no!='' AND ord.order_id>0  AND (ord.invoice_number!='0' AND ord.invoice_number!='')
			$StrWhr
			GROUP BY ord.order_id
			ORDER BY ord.order_id DESC";
$PageVal = DisplayPages($QR_FRAN, 100, $Page, $SrchQ);

ExportQuery($QR_FRAN,array("d_month"=>$d_month,"d_year"=>$d_year));
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
    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!--[if lte IE 8]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        $(function(){
            $(".open_modal").on('click',function(){
                $('#search-modal').modal('show');
                return false;
            });
        });
    </script>
</head>
<body class="no-skin">
    <div class="main-content">
        <div class="main-content-inner">
        <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
        <div class="page-content">
        <div class="page-header">
            <h1> FPV <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Invoice List</small> </h1>
        </div>
        <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
        <div class="row">
        <div class="col-xs-12">
        <div class="clearfix">
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("order","fpvinvoicelist",""); ?>" method="post">
        <div class="row">
        <div class="col-md-2"><input id="user_id" placeholder="User Id" name="user_id"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['user_id']; ?>"></div>
        <div class="col-md-2"><input id="name" placeholder="Member Name" name="name"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['name']; ?>"></div>
        <div class="col-md-2"><input id="invoice_number" placeholder="Invoice No" name="invoice_number"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['invoice_number']; ?>"></div>
        <div class="col-md-2"><div class="input-group"><input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="Date From" type="text"  /><span class="input-group-addon"> <i class="fa fa-calendar"></i></span></div></div>
        <div class="col-md-2"><div class="input-group"><input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  /><span class="input-group-addon"> <i class="fa fa-calendar"></i></span></div></div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
        <div class="col-md-12">
        <button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
        <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
        <button type="button" class="btn btn-sm btn-info" onClick="window.location.href='<?php echo generateSeoUrlAdmin("excel","fpvinvoicereport",""); ?>'"> <i class="ace-icon fa fa-download"></i> Download </button>
        </div>
        </div>
        </form>
        </div>
        <div class="row">&nbsp;</div>
        </div>
        <hr>
        <div class="col-xs-12">
        <div>
        <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
        <tr class="">
        <td align="left"><strong>SR NO</strong></td>
        <td align="right"><strong>INVOICE DATE</strong></td>
        <td align="left"><strong>INVOICE NO.</strong></td>
        <td align="left"><strong>ORDER NO.</strong></td>
        <td align="right"><strong>ORDER DATE</strong></td>
        <td align="left"><strong>Member Name</strong></td>
        <td align="right"><strong>User Id</strong></td>
        <td align="left"><strong>CITY</strong></td>
        <td align="right"><strong>QTY</strong></td>
        <td align="right"><strong>AMOUNT</strong></td>
        <td align="right"><strong>FPV VALUE</strong></td>
		<td align="right"><strong>DIFF. AMOUNT</strong></td>
		<td align="right"><strong>TAX AMOUNT</strong></td>
		<td align="right"><strong>TOTAL COLLECTED</strong></td>
        <td align="right"><strong>BV</strong></td>
        <td align="right"><strong>PV</strong></td>
        </tr>
        <?php 
        if($PageVal['TotalRecords'] > 0){
        $Ctrl = $PageVal['RecordStart']+1;
        foreach($PageVal['ResultSet'] as $AR_DT):
        $invoice_type = (strtotime(InsertDate($AR_DT['invoice_date']))>strtotime("2017-06-30"))? "invoicedetail":"invoiceview";
		$QR_GST = "SELECT SUM(tod.post_pv*tod.post_qty) AS order_pv, SUM(tod.original_post_price*tod.post_qty) AS  order_mrp,
					   SUM(tod.post_price*tod.post_qty) AS order_rcp,
					   tod.post_tax, tod.tax_age, tod.post_qty, tod.net_amount
					   FROM tbl_order_detail AS tod 
					   WHERE tod.order_id='".$AR_DT['order_id']."' 
					   GROUP BY tod.tax_age 
					   ORDER BY tod.order_detail_id ASC"; 
		$RS_GST = $this->SqlModel->runQuery($QR_GST);
		foreach($RS_GST as $AR_GST):
			$post_tax_gst = $AR_GST['tax_age'];
			$order_tax_devide_gst = $post_tax_gst/2;
			$order_rcp_gst = ( $AR_GST['order_rcp']  /  ( ($post_tax_gst/100)+1 ) );								
			$order_tax_calc_gst = ($order_rcp_gst*$order_tax_devide_gst)/100;
			$sum_order_rcp_gst +=$order_rcp_gst;
			$sum_order_tax_calc_gst +=$order_tax_calc_gst;
		endforeach;
        ?>
        <tr>
        <td><?php echo $Ctrl;?></td>
        <td align="right"><?php echo DisplayDate($AR_DT['invoice_date']); ?></td>
        <td align="left"><a href="<?php echo generateSeoUrlFranchise("order",$invoice_type,array("order_id"=>_e($AR_DT['order_id']))); ?>"><?php echo $AR_DT['invoice_number']; ?></a></td>
        <td align="left"><?php echo $AR_DT['order_no']; ?></td>
        <td align="right"><?php echo DisplayDate($AR_DT['date_add']); ?></td>
        <td align="left"><?php echo $AR_DT['full_name']; ?></td>
        <td align="right"><?php echo $AR_DT['user_id']; ?></td>
        <td align="left"><?php echo $AR_DT['city_name']; ?></td>
        <td align="right"><?php echo $AR_DT['total_products']; ?></td>
        <td align="right"><?php echo number_format($AR_DT['total_paid'],2); ?></td>
        <td align="right"><?php echo $AR_DT['coupon_val']; ?></td>
		<td align="right"><?php echo (($AR_DT['total_paid']-$AR_DT['coupon_val'])<0)? 0:($AR_DT['total_paid']-$AR_DT['coupon_val']); ?></td>
		<td align="right"><?php if($AR_DT['order_id']>4309) echo round($sum_order_tax_calc_gst,2)*2; else echo "0"; ?></td>
		<td align="right">
		<?php 
		if($AR_DT['order_id']>4309){ 
			echo (($AR_DT['total_paid']-$AR_DT['coupon_val'])<0)? round($sum_order_tax_calc_gst*2):round(($AR_DT['total_paid']-$AR_DT['coupon_val'])+($sum_order_tax_calc_gst*2));
		}else{ 
			echo round(($AR_DT['total_paid']-$AR_DT['coupon_val'])+($sum_order_tax_calc_gst*2));
		} 
		?></td>
        <td align="right"><?php echo $AR_DT['total_bv']; ?></td>
        <td align="right"><?php echo $AR_DT['total_pv']; ?></td>
        </tr>
        <?php
        $Ctrl++;
        endforeach;
        ?>
        <?php 
        }else{
        ?>
        <tr>
        <td colspan="11" align="center" class="errMsg">No Record Found</td>
        </tr>
        <?php } ?>
        </table>
        <div class="row">
        <div class="col-xs-6">
        <div aria-live="polite" role="status" id="dynamic-table_info" class="dataTables_info"> Showing <?php echo $PageVal['RecordStart']+1; ?> to <?php echo  count($PageVal['ResultSet']); ?> of <?php echo $PageVal['TotalRecords']; ?> entries </div>
        </div>
        <div class="col-xs-6">
        <div id="dynamic-table_paginate" class="dataTables_paginate paging_simple_numbers">
        <ul class="pagination">
        <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
        </ul>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
        </div>
        <!-- /.page-content -->
        </div>
    </div>
	<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
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
</html>