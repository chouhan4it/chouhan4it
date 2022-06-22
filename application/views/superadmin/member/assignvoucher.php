<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['member_id']!=''){
	$member_id = _d(FCrtRplc($_REQUEST['member_id']));
	$StrWhr .=" AND A.assigned_to='$member_id'";
	$SrchQ .= "&member_id="._e($member_id)."";
}
if($_REQUEST['status']!=''){
	$status = FCrtRplc($_REQUEST['status']);
	$StrWhr .=" AND A.use_status='$status'";
	$SrchQ .= "&status=".$_REQUEST[status]."";
}
if($_REQUEST['invoice_number']!=''){
	$StrWhr .=" AND A.invoice_no='$_REQUEST[invoice_number]'";
	$SrchQ .= "&invoice_number=".$_REQUEST[invoice_number]."";
}
if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND (A.assigned_on BETWEEN '$date_from' AND '$date_to')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}
if($_REQUEST['date_expires']!=''){
	$date_expires = InsertDate($_REQUEST['date_expires']);
	$StrWhr .=" AND (A.expires_on = '$date_expires')";
	$SrchQ .="&date_expires=$date_expires";
}

$QR_UPDATE = "UPDATE tbl_coupon AS A, tbl_orders AS B SET A.use_inv_no=B.invoice_number WHERE A.use_inv_no='' AND A.use_status='Y' AND 
			  A.use_order_id=B.order_id";
$this->db->query($QR_UPDATE);

$flddToday = InsertDate(getLocalTime());
$flddExpire = AddToDate($flddToday, '-1 Day');
$QR_EXPIRE = "UPDATE tbl_coupon SET use_status='X' WHERE use_status='N' AND expires_on<='$flddExpire'";
$this->db->query($QR_EXPIRE);

/*
$today_date = getLocalTime();
$expiry_date = AddToDate($today_date, '1 Month');
$date_start = "2017-10-15";
$date_end = "2017-10-25";
$min_pv = 60;
$QR_ORDER = "SELECT A.*, B.sponsor_id FROM tbl_orders AS A, tbl_members AS B WHERE 1 AND A.invoice_number!='' AND DATE(A.invoice_date) BETWEEN 
			 '$date_start' AND '$date_end' AND A.total_pv>='$min_pv' AND A.order_id NOT IN (SELECT order_id FROM tbl_coupon) AND A.member_id=B.member_id 
			 ORDER BY A.order_id DESC";
$RS_ORDER = $this->SqlModel->runQuery($QR_ORDER);
foreach($RS_ORDER as $AR_ORDER):
	$QR_CHECK = "SELECT COUNT(*) AS MyCount FROM tbl_coupon WHERE assigned_to='$AR_ORDER[member_id]' AND from_id='$AR_ORDER[member_id]' AND 
				 MONTH(assigned_on)=MONTH(CURDATE())";
	$AR_CHECK = $this->SqlModel->runQuery($QR_CHECK,true);
	if($AR_CHECK['MyCount']==0){
	# To self
	$coupon_no = UniqueId("COUPON_NO");
	$coupon_val = 600;
	$this->SqlModel->insertRecord("tbl_coupon",array("coupon_no"=>$coupon_no,"coupon_val"=>$coupon_val,"assigned_to"=>$AR_ORDER[member_id],"from_id"=>$AR_ORDER[member_id],"invoice_no"=>$AR_ORDER[invoice_number],"order_id"=>$AR_ORDER[order_id],"order_rcp"=>$AR_ORDER[total_paid],"assigned_on"=>$today_date,"expires_on"=>$expiry_date,"use_status"=>"N","order_pv"=>$AR_ORDER[total_pv],"ready_to_use"=>"Y"));
	unset($coupon_no,$coupon_val);
	# To the sponsor 
	$coupon_no = UniqueId("COUPON_NO");
	$coupon_val = 300;
	$this->SqlModel->insertRecord("tbl_coupon",array("coupon_no"=>$coupon_no,"coupon_val"=>$coupon_val,"assigned_to"=>$AR_ORDER[sponsor_id],"from_id"=>$AR_ORDER[member_id],"invoice_no"=>$AR_ORDER[invoice_number],"order_id"=>$AR_ORDER[order_id],"order_rcp"=>$AR_ORDER[total_paid],"assigned_on"=>$today_date,"expires_on"=>$expiry_date,"use_status"=>"N","order_pv"=>$AR_ORDER[total_pv],"ready_to_use"=>"Y"));
	unset($coupon_no,$coupon_val);
	}
endforeach;
*/

$QR_LIST = "SELECT A.*, (CASE A.use_status WHEN 'N' THEN 'Unused' WHEN 'Y' THEN 'Used' ELSE 'Expired' END) AS current_status, B.user_id, 
			CONCAT_WS(' ',B.first_name,B.last_name) AS full_name, B.city_name, B.member_mobile, C.rank_name, D.user_id AS from_id, 
			CONCAT_WS(' ',D.first_name,D.last_name) AS from_name, D.city_name AS from_city FROM tbl_coupon AS A, tbl_members AS B, tbl_rank AS C, 
			tbl_members AS D WHERE 1 $StrWhr AND A.assigned_to=B.member_id AND B.rank_id=C.rank_id AND A.from_id=D.member_id ORDER BY A.coupon_id DESC";
$PageVal = DisplayPages($QR_LIST, 100, $Page, $SrchQ);
ExportQuery($QR_LIST,array("d_month"=>$d_month,"d_year"=>$d_year));
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
        <h1>Product Master <small> <i class="ace-icon fa fa-angle-double-right"></i> Free Product Vouchers</small></h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <div class="col-xs-12">
          <div class="clearfix">
            <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("member","assignvoucher",""); ?>" method="post">
              <div class="row">
                <div class="col-md-2">
                  <input id="user_id" placeholder="User Id" name="user_id"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['user_id']; ?>">
                  <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                </div>
                <div class="col-md-2">
                  <select name="status"  id="status" class="form-control">
                    <option value="">----Status----</option>
                    <?php DisplayCombo($_REQUEST['status'],"FPVSTATUS");  ?>
                  </select>
                </div>
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
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_expires" id="date_expires" value="<?php echo $_REQUEST['date_expires']; ?>" placeholder="Expiry Date" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span></div>
                </div>
              </div>
              <div class="row">&nbsp;</div>
              <div class="row">
                <div class="col-md-12">
                  <button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
                  <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                  <button type="button" class="btn btn-sm btn-info" onClick="window.location.href='<?php echo generateSeoUrlAdmin("excel","assignvoucher",""); ?>'"> <i class="ace-icon fa fa-download"></i> Download </button>
                </div>
              </div>
            </form>
          </div>
          <div class="row">&nbsp;</div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr class="">
                <td align="center"><strong>SR NO</strong></td>
                <td align="left"><strong>User Id</strong></td>
                <td align="left"><strong>User Name</strong></td>
                <td align="left"><strong>FPV NO</strong></td>
                <td align="right"><strong>FPV VALUE</strong></td>
                <td align="right"><strong>INVOICE NO</strong></td>
                <td align="right"><strong>INVOICE DATE</strong></td>
                <td align="right"><strong>PRICE</strong></td>
                <td align="center"><strong>ASSIGNED ON</strong></td>
                <td align="center"><strong>EXPIRES ON</strong></td>
                <td align="center"><strong>DAYS LEFT</strong></td>
                <td align="center"><strong>STATUS</strong></td>
              </tr>
              <?php
                $Ctrl = $PageVal['RecordStart']+1;
				foreach($PageVal['ResultSet'] as $AR_LIST):
				?>
              <tr>
                <td align="center"><?php echo $Ctrl;?></td>
                <td align="left"><?php echo $AR_LIST['user_id'];?></td>
                <td align="left"><?php echo strtoupper($AR_LIST['full_name']);?></td>
                <td align="left"><?php echo $AR_LIST['coupon_no'];?></td>
                <td align="right"><?php echo $AR_LIST['coupon_val'];?></td>
                <td align="right"><?php echo $AR_LIST['invoice_no'];?></td>
                <td align="right"><?php echo DisplayDate($model->getInvoiceDate($AR_LIST['invoice_no']));?></td>
                <td align="right"><?php echo $AR_LIST['order_rcp'];?></td>
                <td align="center"><?php echo DisplayDate($AR_LIST['assigned_on']);?></td>
                <td align="center"><?php echo DisplayDate($AR_LIST['expires_on']);?></td>
                <td align="center"><?php 
					if($AR_LIST['current_status']!='Unused') echo 0; else echo dayDiff($flddExpire,$AR_LIST['expires_on']);
					?></td>
                <td align="center"><?php echo $AR_LIST['current_status']; ?></td>
              </tr>
              <?php
                $Ctrl++;
				endforeach;
                ?>
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
</body>
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
$(function(){
	$("#form-valid").validationEngine();
	$('.date-picker').datetimepicker({
		format: 'YYYY-MM-DD'
	});
});
</script>
</html>