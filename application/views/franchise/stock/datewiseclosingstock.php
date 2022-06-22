<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = InsertDate(getLocalTime());
$franchisee_id = $this->session->userdata('fran_id');

if($_REQUEST['d_month']>0){
	$d_month = FCrtRplc($_REQUEST['d_month']);
	$d_year = FCrtRplc($_REQUEST['d_year']);
	$d_year = ($d_year>0)? $d_year:date("Y");
	$start_date = $d_year."-".$d_month."-01";
	$PERIOD = getMonthDates($start_date);
	$from_date = $PERIOD['flddFDate'];
	$to_date = ( strtotime($flddToday) >= strtotime($PERIOD['flddTDate']) )? $PERIOD['flddTDate']:$flddToday;
	$SrchQ .="&d_month=$d_month&d_year=$d_year";
}
/*
if($_REQUEST['d_month']==''){
	$d_month = date("m");
	$d_year = FCrtRplc($_REQUEST['d_year']);
	$d_year = ($d_year>0)? $d_year:date("Y");
	$start_date = $d_year."-".$d_month."-01"; 
	$PERIOD = getMonthDates($start_date);
	$from_date = $PERIOD['flddFDate'];
	$to_date = $PERIOD['flddTDate'];
}
*/
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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>public/jquery_token/token-input.css" />
<!--[if lte IE 9]>
	<link rel="stylesheet" href="assets/css/ace-ie.min.css" />
<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>public/jquery_token/jquery.tokeninput.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
<![endif]-->
</head>
</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(FRANCHISE_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Stock<small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Date Wise Closing Stock </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-12">
              <div class="clearfix">
                <div class="row">
                  <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("stock","datewiseclosingstock",""); ?>" method="post">
                    <div class="col-md-2">
                      <select name="d_month" class="form-control"  id="d_month">
                  <option value="">---month---</option>
                  <?php echo DisplayCombo($d_month,"MONTH"); ?>
                </select>
                     
                    </div>
                    <div class="col-md-2">
                     <select name="d_year" class="form-control" id="d_year">
                  <option value="0">---select year---</option>
                  <?php echo DisplayCombo($_REQUEST['d_year'],"PAST_5_YEAR"); ?>
                </select>
                    </div>
                    
					<div class="col-md-8">
						<button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
          				<button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
						<a  class="btn btn-sm btn-info" href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>"> <i class="fa fa-file-excel-o"></i> Excel </a>
					</div>
                  </form>
                </div>
              </div>
            </div>
			<hr>
            <div class="clearfix">
            <?php if($franchisee_id!='' and $from_date!='' and $to_date!=''){?>
			<table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
        <tr class="">
            <td align="center"><strong>SR NO</strong></td>
            <td align="center"><strong>DATE</strong></td>
            <td align="center"><strong>STOCK NOS.</strong></td>
            <td align="center"><strong>STOCK VALUE</strong></td>
        </tr>
        <?php
		$date_last = AddToDate($from_date,"-1 Day");
		$num_days = dayDiff($from_date,$to_date)+1;
        for($i=1; $i<=$num_days; $i++){
		$for_date = AddToDate($date_last,"+$i Day");
		$stock_nos = $model->DateWiseClosingStock($franchisee_id,$for_date);
		$stock_val = $model->DateWiseStockValue($franchisee_id,$for_date);
		$QR_DIR = "SELECT tsl.post_id, tsl.post_attribute_id, tpl.post_title
					FROM  tbl_stock_ledger AS tsl
					LEFT JOIN  tbl_post_lang AS tpl ON tpl.post_id=tsl.post_id
					WHERE tsl.post_id>0
					GROUP BY tsl.post_id, tsl.post_attribute_id";
		$RS_DIR = $this->SqlModel->runQuery($QR_DIR);
		foreach($RS_DIR as $AR_DIR):
			$AR_OPEN = $model->getStockOpening($AR_DIR['post_id'],$AR_DIR['post_attribute_id'],$franchisee_id,$for_date);
			$AR_STOCK = $model->getStockBalance($AR_DIR['post_id'],$AR_DIR['post_attribute_id'],$franchisee_id,$for_date,$for_date);
			$net_balance = $AR_OPEN['net_balance']+$AR_STOCK['net_balance'];
			$total_price = $net_balance*$AR_DIR['post_price'];
			$sum_total_price += $total_price;
			unset($net_balance,$total_price);
		endforeach;
		$net_sum_total_price +=$sum_total_price;
        ?>
        <tr>
            <td align="center"><?php echo $i;?></td>
            <td align="center"><?php echo DisplayDate($for_date);?></td>
            <td align="center"><?php echo $stock_nos;?></td>
            <td align="center"><?php echo $sum_total_price;?></td>
        </tr>
       
        <?php 
		unset($for_date,$stock_nos,$stock_val,$sum_total_price);
		}
		?>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right"><strong>Total :</strong></td>
          <td align="center"><?php echo number_format($net_sum_total_price,2); ?></td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right"><strong>Average:</strong></td>
          <td align="center"><?php echo number_format($net_sum_total_price/$num_days,2); ?></td>
        </tr>
        </table>
       <?php }?>       
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		});
	});
</script>
</body>
</html>