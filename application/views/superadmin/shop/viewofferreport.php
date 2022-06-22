<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$gst_invoice_date = "2017-07-01";

if($_REQUEST['franchisee_id']>0){
	$franchisee_id = FCrtRplc($_REQUEST['franchisee_id']);
	$SrchQ .="&franchisee_id=$franchisee_id";
}
if($_REQUEST['offer_id']>0){
	$offer_id = FCrtRplc($_REQUEST['offer_id']);
	$SrchQ .="&offer_id=$offer_id";
}
if($_REQUEST['invoice_number']!=''){
	$invoice_number = FCrtRplc($_REQUEST['invoice_number']);
	$StrWhr .=" AND A.invoice_number = '$invoice_number'";
	$SrchQ .="&invoice_number=$invoice_number";
}
if($_REQUEST['user_id']!=''){
	$user_id = FCrtRplc($_REQUEST['user_id']);
	$member_id = $model->getMemberId($user_id);
	$StrWhr .=" AND ( A.member_id = '$member_id' )";
	$SrchQ .="&user_id=$user_id";
}
if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND DATE(A.date_add) BETWEEN '$date_from' AND '$date_to'";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$QR_FRAN = "SELECT DISTINCT(A.order_id), A.invoice_number, A.total_paid, A.invoice_date, C.user_id, UCASE(CONCAT_WS(' ',C.first_name,C.last_name)) AS 
			full_name FROM tbl_orders AS A, tbl_order_detail AS B, tbl_members AS C WHERE A.franchisee_id='$franchisee_id' AND A.order_id=B.order_id AND 
			B.offer_id='$offer_id'  AND A.member_id=C.member_id $StrWhr";
$PageVal = DisplayPages($QR_FRAN, 100, $Page, $SrchQ);

ExportQuery($QR_FRAN ,array("d_month"=>$d_month,"d_year"=>$d_year));

$QR_OFFER = "SELECT A.post_id, A.post_mrp, A.post_discount, A.post_price, C.offer_module, C.offer_title, C.offer_price FROM tbl_post_lang AS A, 
			tbl_offer_product AS B, tbl_offer AS C WHERE A.post_id = B.post_id AND B.offer_id = '$offer_id' AND B.offer_id = C.offer_id";
$AR_OFFER = $this->SqlModel->runQuery($QR_OFFER,true);
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
          <h1> Product Master <i class="ace-icon fa fa-angle-double-right"></i> Offers <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Order Wise Offer Report </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12">
            <div class="clearfix">
              <div class="row">
                <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("shop","viewofferreport",""); ?>?franchisee_id=<?php echo $franchisee_id; ?>&offer_id=<?php echo $offer_id; ?>" method="post">
                  <div class="col-md-2">
                    <input id="invoice_number" placeholder="Invoice No" name="invoice_number"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['invoice_number']; ?>">
                  </div>
                  <div class="col-md-2">
                    <select name="franchisee_id"  id="franchisee_id" class="form-control" disabled>
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
                    <button type="button" class="btn btn-sm btn-info" onClick="window.location.href='<?php echo generateSeoUrlAdmin("excel","viewofferreport",""); ?>?franchisee_id=<?php echo $franchisee_id;?>&offer_id=<?php echo $offer_id; ?>'"> <i class="ace-icon fa fa-download"></i> Download </button>
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
                <td align="left"><strong>INVOICE NO.</strong></td>
                <td align="left"><strong>NAME</strong></td>
                <td align="right"><strong>User Id</strong></td>
                <td align="right"><strong>DATE</strong></td>
                <td align="right"><strong>INVOICE TOTAL</strong></td>
                <td align="right"><strong>NOS</strong></td>
                <td align="right"><strong>VALUE</strong></td>
                <td align="right"><strong>AMOUNT COLLECTED</strong></td>
                <td align="right"><strong>OFFER VALUE</strong></td>
              </tr>
              <?php 
			if($PageVal['TotalRecords'] > 0){
			$Ctrl = $PageVal['RecordStart']+1;
			foreach($PageVal['ResultSet'] as $AR_DT):
			$invoice_type = (strtotime(InsertDate($AR_DT['invoice_date']))>strtotime("2017-06-30"))? "invoicedetail":"invoiceview";
			#$StrWhrOff .= " AND offer_id='$offer_id'";
			#SStrWhrMMM .=" AND A.offer_id='$offer_id'";
			
			$StrWhrOff .= " AND post_pv='0'";
			$StrWhrMMM .= " AND A.post_pv='0'";
			$QRY_CNT ="SELECT SUM(post_qty) AS icnt FROM tbl_order_detail WHERE order_id='$AR_DT[order_id]' $StrWhrOff ";
			$RS_CNT = $this->db->query($QRY_CNT);
			$AR_CNT = $RS_CNT->row_array();
			$QRY_VAL ="SELECT A.post_qty, B.post_price, SUM(A.post_qty*B.post_price) AS itotal,
					   SUM(A.post_qty*(B.post_mrp-B.post_price)) AS ncollect
					   FROM tbl_order_detail AS A, tbl_post_lang AS B WHERE 
						A.order_id='$AR_DT[order_id]' $StrWhrMMM AND A.post_id=B.post_id";
			$RS_VAL = $this->db->query($QRY_VAL);
			$AR_VAL = $RS_VAL->row_array();
			$amount_collected = $AR_VAL['ncollect'];
			$total_collected +=$amount_collected;
			/*if($AR_OFFER['offer_module']=='OPOF'){
				$pos = strpos($AR_OFFER['offer_title'],'50 50 MRP OFFER');
				if($pos===false){
				$amount_collected = ($AR_OFFER['post_discount']+1)*$AR_CNT['icnt'];
				}else{
				$amount_collected = ($AR_OFFER['post_discount']*$AR_CNT['icnt']);	
				}
			}elseif($AR_OFFER['offer_module']=='OPOF-T'){
				$amount_collected = ($AR_OFFER['post_discount']*$AR_CNT['icnt']);
			}elseif($AR_OFFER['offer_module']=='OPOF-U'){
				if($offer_id<159){
					$amount_collected = ($AR_OFFER['post_discount']*($AR_CNT['icnt']/2));
				}else{
					$amount_collected = ($AR_OFFER['post_discount']*$AR_CNT['icnt']);
				}
			}elseif($AR_OFFER['offer_module']=='FPOF'){
				$amount_collected = ($AR_OFFER['offer_price']*$AR_CNT['icnt']);
			}elseif($AR_OFFER['offer_module']=='FPOF-T'){
				$amount_collected = ($AR_OFFER['offer_price']*$AR_CNT['icnt']);
			}elseif($AR_OFFER['offer_module']=='ONDO'){
				$amount_collected = ($AR_OFFER['offer_price']*$AR_CNT['icnt']);
			}elseif($AR_DT['offer_module']=='MMMM'){
				
			}else{
				$amount_collected = $AR_CNT['icnt']*1;
			}*/
			/*
			$RS_VAL = $this->db->query($QRY_VAL);
			foreach($RS_VAL as $AR_VAL):
				$row_value = $row_value + $AR_VAL['itotal'];
			endforeach;
			*/
			?>
              <tr>
                <td><?php echo $Ctrl;?></td>
                <td align="left"><a target="_blank" href="<?php echo generateSeoUrlAdmin("shop",$invoice_type,array("order_id"=>_e($AR_DT['order_id']))); ?>"><?php echo $AR_DT['invoice_number']; ?></a></td>
                <td align="left"><?php echo $AR_DT['full_name']; ?></td>
                <td align="right"><?php echo $AR_DT['user_id']; ?></td>
                <td align="right"><?php echo DisplayDate($AR_DT['invoice_date']); ?></td>
                <td align="right"><?php echo number_format($AR_DT['total_paid'],2); ?></td>
                <td align="right"><?php echo $AR_CNT['icnt'];?></td>
                <td align="right"><?php echo $AR_VAL['itotal'];?></td>
                <td align="right"><?php echo $amount_collected;?></td>
                <td align="right"><?php echo $AR_VAL['itotal']-$amount_collected;?></td>
              </tr>
              <?php
			$Ctrl++;
			endforeach;
			?>
              <?php 
			}else{
			?>
              <tr>
                <td colspan="10" align="center" class="errMsg">No record found</td>
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