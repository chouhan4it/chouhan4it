<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$month_ini = new DateTime("first day of last month");

$last_month_date =  $month_ini->format('Y-m-d');
$today_date = getLocalTime();
$yester_date = InsertDate(AddToDate($today_date,"-1 Day"));

$C_MONTH = getMonthDates($today_date);
$X_MONTH = getMonthDates($last_month_date);
$till_date = InsertDate(AddToDate($today_date,"-1 Month"));
//$till_date = '2018-03-16';

if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$QR_FRAN = "SELECT tf.*, tsf.franchisee_type 
		    FROM  tbl_franchisee AS tf	
		    LEFT JOIN  tbl_setup_franchisee AS tsf  ON tsf.fran_setup_id=tf.fran_setup_id 
		    WHERE tf.is_delete>0 AND tf.franchisee_id>0   $StrWhr ORDER BY tf.franchisee_id DESC";
$PageVal = DisplayPages( $QR_FRAN , 100 , $Page , $SrchQ);

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
          <h1> Branch <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Report </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-md-12">
            <div class="pull-right"> <a href="<?php echo generateSeoUrlAdmin("excel","shoppecollection",""); ?>"  class="m-t-n-xs" value=" Excel "> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a> </div>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr class="">
                <td align="left" style="font-size:14px;"><strong>SR NO</strong></td>
                <td align="left" style="font-size:14px;"><strong>BRANCH NAME</strong></td>
                <td align="left" style="font-size:14px;"><strong>CITY</strong></td>
                <td align="right" style="font-size:14px;"><strong>YESTERDAY SALE</strong></td>
                <td align="right" style="font-size:14px;"><strong>TODAY SALE</strong></td>
                <td align="right" style="font-size:14px;"><strong>LAST MONTH SALE</strong></td>
                <td align="right" style="font-size:14px;"><strong>LAST MONTH TILL DATE SALE</strong></td>
                <td align="right" style="font-size:14px;"><strong>CURRENT MONTH SALE</strong></td>
              </tr>
              <?php 
			if($PageVal['TotalRecords'] > 0){
			$Ctrl = $PageVal['RecordStart']+1;
			foreach($PageVal['ResultSet'] as $AR_DT):
				$yester_order = $model->getSumOfFranchiseOrder($AR_DT['franchisee_id'],$yester_date,$yester_date);
				$today_order = $model->getSumOfFranchiseOrder($AR_DT['franchisee_id'],$today_date,$today_date);
				$current_month_order = $model->getSumOfFranchiseOrder($AR_DT['franchisee_id'],$C_MONTH['flddFDate'],$C_MONTH['flddTDate']);
				$last_month_order = $model->getSumOfFranchiseOrder($AR_DT['franchisee_id'],$X_MONTH['flddFDate'],$X_MONTH['flddTDate']);
				$last_month_till_date_order = $model->getSumOfFranchiseOrder($AR_DT['franchisee_id'],$X_MONTH['flddFDate'],$till_date);
				$AR_STOCK = $model->getTotalStockBalance($AR_DT['franchisee_id'],"","");
				
				
				
				$total_yester_order +=$yester_order;
				$total_today_order +=$today_order;
				$total_current_month_order +=$current_month_order;
				$total_last_month_order +=$last_month_order;
				$total_last_month_till_date_order +=$last_month_till_date_order;
				$total_pc_in_city +=$pc_in_city;
				
				$total_net_qty += $AR_STOCK['net_qty'];
				$total_net_rcp += $AR_STOCK['net_rcp'];
			?>
              <tr>
                <td style="font-size:14px;"><?php echo $Ctrl;?></td>
                <td align="left" style="font-size:14px;"><?php echo $AR_DT['name']; ?></td>
                <td align="left" style="font-size:14px;"><?php echo $AR_DT['city']; ?></td>
                <td align="right" style="font-size:14px;"><?php echo number_format($yester_order,2); ?></td>
                <td align="right" style="font-size:14px;"><?php echo number_format($today_order,2); ?></td>
                <td align="right" style="font-size:14px;"><?php echo number_format($last_month_order,2); ?></td>
                <td align="right" style="font-size:14px;"><?php echo number_format($last_month_till_date_order,2); ?></td>
                <td align="right" style="font-size:14px;"><?php echo number_format($current_month_order,2); ?></td>
              </tr>
              <?php
			$Ctrl++;
			unset($AR_STOCK,$pc_in_city,$yester_order,$today_order,$last_month_order,$current_month_order);
			endforeach;
			?>
              <tr>
                <td colspan="3" align="right" style="font-size:14px;"><strong>Total</strong></td>
                <td align="right" style="font-size:14px;"><strong><?php echo number_format($total_yester_order,2); ?></strong></td>
                <td align="right" style="font-size:14px;"><strong><?php echo number_format($total_today_order,2); ?></strong></td>
                <td align="right" style="font-size:14px;"><strong><?php echo number_format($total_last_month_order,2); ?></strong></td>
                <td align="right" style="font-size:14px;"><strong><?php echo number_format($total_last_month_till_date_order,2); ?></strong></td>
                <td align="right" style="font-size:14px;"><strong><?php echo number_format($total_current_month_order,2); ?></strong></td>
              </tr>
              <?php 
			}else{
			?>
              <tr>
                <td colspan="8" align="center" class="errMsg">No record found</td>
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
