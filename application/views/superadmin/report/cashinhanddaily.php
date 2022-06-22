<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['franchisee_id']!='' and $_REQUEST['month']!='' and $_REQUEST['year']!=''){
	$first = $_REQUEST['year'].'-'.$_REQUEST['month'].'-01';
	$Q_Date = "SELECT DATE_ADD(DATE_ADD(LAST_DAY('$first'), INTERVAL 1 DAY), INTERVAL - 1 MONTH) AS first_day, LAST_DAY('$first') AS last_day";
	$AR_Date = $this->SqlModel->runQuery($Q_Date,true);
	$franchisee_id = FCrtRplc($_REQUEST['franchisee_id']);
	$SrchQ .= "&franchisee_id=".$franchisee_id."";
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
        <h1>Report Master <small> <i class="ace-icon fa fa-angle-double-right"></i> Cash in Hand Daily</small></h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <div class="col-xs-12">
          <div class="clearfix">
            <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("report","cashinhanddaily",""); ?>" method="post">
              <div class="row">
                <div class="col-md-2">
                  <select name="franchisee_id"  id="franchisee_id" class="form-control">
                    <option value="">----Franchise----</option>
                    <?php DisplayCombo($_REQUEST['franchisee_id'],"FRANCHISEE");  ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <div class="input-group">
                    <select name="month" id="month" class="form-control">
                      <option value="">----Month----</option>
                      <?php DisplayCombo($_REQUEST[month], "MONTH");?>
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="input-group">
                    <select name="year" id="year" class="form-control">
                      <option value="">----Year----</option>
                      <?php for($i=2017; $i<=date('Y'); $i++){ ?>
                      <option value="<?php echo $i;?>" <?php if($_REQUEST[year]==$i) echo "selected=selected";?>><?php echo $i;?></option>
                      <?php }?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">&nbsp;</div>
              <div class="row">
                <div class="col-md-12">
                  <button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
                  <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                  <!--<button type="button" class="btn btn-sm btn-info" onClick="window.location.href='<?php echo generateSeoUrlAdmin("excel","cashinhanddaily",""); ?>'"> <i class="ace-icon fa fa-download"></i> Download </button>--> 
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
            <?php if($_REQUEST['franchisee_id']!=''){?>
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr class="">
                <td align="left"><strong>DATE</strong></td>
                <td align="right"><strong>GROSS SALE (=)</strong></td>
                <td align="right"><strong>FPV SALE (-)</strong></td>
                <td align="right"><strong>NET SALE (=)</strong></td>
                <td align="right"><strong>EXCESS + TAX COLLECTED (+)</strong></td>
                <td align="right"><strong>CASH IN HAND (=)</strong></td>
              </tr>
              <?php
				$start = $AR_Date['first_day'];
                for($i=0; $i<getMonthDays($start); $i++){
				$date = AddToDate($start, $i.' Day');
				$Q_GROSS = "SELECT IFNULL(SUM(ord.total_paid),0) AS total_paid 
							FROM tbl_orders AS ord	
							WHERE ord.franchisee_id='$franchisee_id' 
							AND DATE(ord.invoice_date)='$date' 
							AND ord.invoice_number!=''";
				$AR_GROSS = $this->SqlModel->runQuery($Q_GROSS,true); 
				$Q_FPV =  "SELECT IFNULL(SUM(ord.total_paid),0) AS total_paid 
						   FROM tbl_orders AS ord	
						   WHERE ord.franchisee_id='$franchisee_id' 
						   AND DATE(ord.invoice_date)='$date' 
						   AND ord.total_pv='0' AND ord.invoice_number!=''";
				$AR_FPV = $this->SqlModel->runQuery($Q_FPV,true); 
				$Q_EXTRA = "SELECT ord.*, fpv.coupon_val    
							FROM tbl_order_detail AS ord 
							LEFT JOIN tbl_orders AS prnt ON prnt.order_id=ord.order_id 
							LEFT JOIN tbl_coupon AS fpv ON fpv.use_order_id=ord.order_id 	
							WHERE prnt.franchisee_id='$franchisee_id' 
							AND DATE(prnt.invoice_date)='$date'  
							AND prnt.total_pv='0' AND prnt.invoice_number!=''";
				$RS_EXTRA = $this->SqlModel->runQuery($Q_EXTRA);
				foreach($RS_EXTRA as $AR_EXTRA):
					$tax_total += (($AR_EXTRA['post_tax']*$AR_EXTRA['post_qty'])*$AR_EXTRA['tax_age'])/100;
					if($AR_EXTRA['net_amount']>$AR_EXTRA['coupon_val']){
					$tax_total += ($AR_EXTRA['net_amount']-$AR_EXTRA['coupon_val']);
					}
				endforeach;
				$gross += $AR_GROSS['total_paid'];
				$fpv += $AR_FPV['total_paid'];
				$net += ($AR_GROSS['total_paid']-$AR_FPV['total_paid']);
				$excess += $tax_total;
				$inhand += (($AR_GROSS['total_paid']-$AR_FPV['total_paid'])+$tax_total);
				?>
              <tr>
                <td align="left"><?php echo DisplayDate($date);?></td>
                <td align="right"><?php echo $AR_GROSS['total_paid'];?></td>
                <td align="right"><?php echo $AR_FPV['total_paid'];?></td>
                <td align="right"><?php echo ($AR_GROSS['total_paid']-$AR_FPV['total_paid']);?></td>
                <td align="right"><?php echo round($tax_total,2);?></td>
                <td align="right"><?php echo round((($AR_GROSS['total_paid']-$AR_FPV['total_paid'])+$tax_total),2);?></td>
              </tr>
              <?php
				unset($tax_total, $date);
				}
				?>
              <tr style="font-weight:bold;">
                <td align="left">&nbsp;</td>
                <td align="right"><?php echo $gross;?></td>
                <td align="right"><?php echo $fpv;?></td>
                <td align="right"><?php echo $net;?></td>
                <td align="right"><?php echo round($excess,2);?></td>
                <td align="right"><?php echo round($inhand,2);?></td>
              </tr>
            </table>
            <ul class="pagination">
              <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
            </ul>
            <?php }?>
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
$(function(){
	$("#form-valid").validationEngine();
	$('.date-picker').datetimepicker({
		format: 'YYYY-MM-DD'
	});
});
</script>
</html>