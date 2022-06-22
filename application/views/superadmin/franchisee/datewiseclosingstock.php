<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$flddToday = getLocalTime();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['franchisee_id']>0){
	$franchisee_id = FCrtRplc($_REQUEST['franchisee_id']);
}
if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
}
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
          <h1> Shoppe <small> <i class="ace-icon fa fa-angle-double-right"></i> Date Wise Closing Stock</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="clearfix">
              <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("franchisee","datewiseclosingstock",""); ?>" method="get">
                <div class="row">
                  <div class="col-md-2">
                    <select name="franchisee_id"  id="franchisee_id" class="form-control">
                      <option value="">---- Select Shoppe ----</option>
                      <?php DisplayCombo($_REQUEST['franchisee_id'],"FRANCHISEE");  ?>
                    </select>
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
                  </div>
                </div>
              </form>
            </div>
            <div class="row">&nbsp;</div>
          </div>
          <hr>
          <div class="col-xs-12">
            <?php if($franchisee_id!='' and $date_from!='' and $date_to!=''){?>
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr class="">
                <td align="center"><strong>SR NO</strong></td>
                <td align="center"><strong>DATE</strong></td>
                <td align="center"><strong>STOCK NOS.</strong></td>
                <td align="center"><strong>STOCK VALUE</strong></td>
              </tr>
              <?php
		$date_last = AddToDate($date_from,"-1 Day");
		$num_days = dayDiff($date_from,$date_to)+1;
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
		}?>
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
                <td align="center"><?php echo number_format($net_sum_total_price/$num_days,2); ?>;</td>
              </tr>
            </table>
            <?php }?>
          </div>
        </div>
      </div>
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
</html>