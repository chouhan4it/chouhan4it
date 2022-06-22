<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

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
if($_REQUEST['franchisee_id']>0){
	$franchisee_id = FCrtRplc($_REQUEST['franchisee_id']);
	$StrWhr .=" AND (ord.franchisee_id='".$franchisee_id."')";
	$SrchQ .="&franchisee_id=$franchisee_id";
}
if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND (DATE(ord.date_add) BETWEEN '$date_from' AND '$date_to')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$QR_PAGES = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.member_email, tm.user_id AS user_id ,tm.city_name, tm.state_name,
			 tad.full_name AS ship_full_name, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name,
			 tad.country_code AS ship_country_code, tad.current_address AS ship_current_address, tad.pin_code AS ship_pin_code, 
			 tad.mobile_number AS ship_mobile_number,  tad.order_type, 
			 tos.name AS order_state,
			 tod.post_title, tod.post_desc, tod.post_qty AS order_qty, tod.post_price AS item_price,
			 tf.name AS seller_name, tf.address AS seller_addres, tf.gst_no AS seller_gst_no, tf.tin_no AS seller_tin_no,
			 tac.waybill
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 LEFT JOIN tbl_order_detail AS tod ON tod.order_id=ord.order_id
			 LEFT JOIN tbl_franchisee AS tf ON tf.franchisee_id=ord.franchisee_id
			 LEFT JOIN tbl_api_courier AS tac ON tac.order_id=ord.order_id
			 WHERE ord.order_id>0 AND ord.invoice_number!=''
			 $StrWhr
			 GROUP BY ord.order_id
			 ORDER BY ord.order_id DESC";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);

ExportQuery($QR_PAGES ,array("d_month"=>$d_month,"d_year"=>$d_year));
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
          <h1> Order <small> <i class="ace-icon fa fa-angle-double-right"></i> Invoice List</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="clearfix">
              <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("shop","invoicereport",""); ?>" method="post">
                <div class="row">
                  <div class="col-md-2">
                    <input id="invoice_number" placeholder="Invoice No" name="invoice_number"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['invoice_number']; ?>">
                  </div>
                  <div class="col-md-2">
                    <div class="input-group">
                      <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="Date From" type="text"  />
                      <span class="input-group-addon"> <i class="fa fa-calendar"></i></span></div>
                  </div>
                  <div class="col-md-2">
                    <div class="input-group">
                      <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                      <span class="input-group-addon"> <i class="fa fa-calendar"></i></span></div>
                  </div>
                </div>
                <div class="row">&nbsp;</div>
                <div class="row">
                  <div class="col-md-12">
                    <button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
                    <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                    <button type="button" class="btn btn-sm btn-info" onClick="window.location.href='<?php echo generateSeoUrlAdmin("excel","invoicereport",""); ?>'"> <i class="ace-icon fa fa-download"></i> Bulk Download </button> 
                  </div>
                </div>
              </form>
            </div>
            <div class="row">&nbsp;</div>
          </div>
          <hr>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table  class="table">
             <thead>
              <tr class="">
                <td align="center"><strong>SR NO</strong></td>
                <td align="right"><strong>INVOICE DATE</strong></td>
                <td align="right"><strong>INVOICE NO.</strong></td>
                <td align="right"><strong>ORDER NO.</strong></td>
                <td align="right"><strong>ORDER DATE</strong></td>
                <td align="right"><strong>SHOPPE</strong></td>
                <td align="right"><strong>USER ID</strong></td>
                <td align="right"><strong>STATUS</strong></td>
                <td align="right"><strong>QTY</strong></td>
                <td align="right"><strong>B.V</strong></td>
                <td align="right"><strong>AMOUNT</strong></td>
                <td align="right">&nbsp;</td>
              </tr>
              </thead>
               <tbody>
              <?php 
        if($PageVal['TotalRecords'] > 0){
        $Ctrl = $PageVal['RecordStart']+1;
        foreach($PageVal['ResultSet'] as $AR_DT):
		$courier_ctrl = $model->checkCount("tbl_api_courier","order_id",$AR_DT['order_id']);
        ?>
              <tr class="<?php echo ($courier_ctrl>0)? 'text text-success':''; ?>">
                <td align="center"><?php echo $Ctrl;?></td>
                <td align="right"><?php echo DisplayDate($AR_DT['invoice_date']); ?></td>
                <td align="right"><?php echo $AR_DT['invoice_number']; ?></td>
                <td align="right"><?php echo $AR_DT['order_no']; ?></td>
                <td align="right"><?php echo DisplayDate($AR_DT['date_add']); ?></td>
                <td align="right"><?php echo $AR_DT['seller_name']; ?></td>
                <td align="right"><?php echo $AR_DT['user_id']; ?></td>
                <td align="right"><?php echo $AR_DT['order_state']; ?></td>
                <td align="right"><?php echo $AR_DT['total_products']; ?></td>
                <td align="right"><?php echo number_format($AR_DT['total_pv']); ?></td>
                <td align="right"><?php echo number_format($AR_DT['total_paid'],2); ?></td>
                <td align="right">
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                    <ul class="dropdown-menu dropdown-default">
                    	<li> <a  href="<?php echo generateSeoUrlAdmin("shop","orderview",array("order_id"=>_e($AR_DT['order_id']))); ?>">Branch Invoice</a> </li>
                       <li> <a  href="<?php echo generateSeoUrlAdmin("shop","invoicedetail",array("order_id"=>_e($AR_DT['order_id']))); ?>">Customer Invoice</a> </li>
                       	<?php if($courier_ctrl==0){ ?>
                        <li> <a  onClick="return confirm('Make sure, you want to send this order to courier?')" href="<?php echo generateSeoUrlAdmin("shop","sendtocourier",array("order_id"=>_e($AR_DT['order_id']))); ?>">Send to courier</a> </li>
                        <?php }else{ ?>
                        <li> <a  target="_blank"  href="<?php echo generateSeoUrlAdmin("shop","trackcourier",array("waybill"=>($AR_DT['waybill']))); ?>">Track Shipping</a> </li>
                        <?php } ?>
                    </ul>
                </div>
                </td>
              </tr>
              <?php
        $Ctrl++;
        endforeach;
        ?>
              
              <?php 
        }else{
        ?>
              <tr>
                <td colspan="12" align="center" class="errMsg">No record found</td>
              </tr>
              <?php } ?>
              </tbody>
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