<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalTime();
$franchisee_id = $this->session->userdata('fran_id');

$QR_SHOPPE = "SELECT franchisee_id, name, city FROM tbl_franchisee WHERE franchisee_id='$franchisee_id'";
$AR_SHOPPE = $this->SqlModel->runQuery($QR_SHOPPE,true);

if($_REQUEST['period']!=''){
	switch($_REQUEST['period']){
		case 1:
			$from_date = '2017-04-01';
			$to_date = '2018-03-31';
		break;
		case 2:
			$from_date = '2018-04-01';
			$to_date = '2019-03-31';
		break;
	}
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
        <h1> Sales <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Cash in Hand Annual </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-12">
              <div class="clearfix">
                <div class="row">
                  <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("report","cashinhandannual",""); ?>" method="post">
                    <div class="col-md-2">
                     <div class="input-group">
                        <select name="period" id="period" class="form-control" style="width:200px;">
                                <option value="">--------Financial Year--------</option>
                                <option value="1" <?php if($_REQUEST[period]==1) echo "selected=selected";?>>April 2017 - March 2018</option>
                                <option value="2" <?php if($_REQUEST[period]==2) echo "selected=selected";?>>April 2018 - March 2019</option>
                              </select></div>
                    </div>
					<div class="col-md-3">
						<button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
          				<button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?franchisee_id=<?php echo $franchisee_id;?>'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
					</div>
                  </form>
                </div>
              </div>
            </div>
			<hr>
            <div class="clearfix">
			<div class="col-xs-12">
            <?php if($_REQUEST['period']!=''){?>
              <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
                <tr class="">
                    <td align="left"><strong>MONTH</strong></td>
                    <td align="right"><strong>GROSS SALE (=)</strong></td>
                    <td align="right"><strong>FPV SALE (-)</strong></td>
                    <td align="right"><strong>NET SALE (=)</strong></td>
                    <td align="right"><strong>EXCESS + TAX COLLECTED (+)</strong></td>
                    <td align="right"><strong>CASH IN HAND (=)</strong></td>
                </tr>
				<?php
                for($i=0; $i<12; $i++){
				$start = $from_date;
				$date = AddToDate($start, $i.' Month');
				$Q_Date = "SELECT DATE_ADD(DATE_ADD(LAST_DAY('$date'), INTERVAL 1 DAY), INTERVAL - 1 MONTH) AS first_day, 
						   LAST_DAY('$date') AS last_day, 
						   MONTHNAME('$date') AS month_name, 
						   YEAR('$date') AS month_year";
				$AR_Date = $this->SqlModel->runQuery($Q_Date,true);
				$Q_GROSS = "SELECT IFNULL(SUM(ord.total_paid),0) AS total_paid 
							FROM  tbl_orders AS ord	
							WHERE ord.franchisee_id='$franchisee_id' 
							AND DATE(ord.invoice_date) BETWEEN '$AR_Date[first_day]' AND '$AR_Date[last_day]'  
							AND ord.invoice_number!=''";
				$AR_GROSS = $this->SqlModel->runQuery($Q_GROSS,true); 
				$Q_FPV = "SELECT IFNULL(SUM(ord.total_paid),0) AS total_paid 
							FROM  tbl_orders AS ord	
							WHERE ord.franchisee_id='$franchisee_id' 
							AND DATE(ord.invoice_date) BETWEEN '$AR_Date[first_day]' AND '$AR_Date[last_day]'  
							AND ord.total_pv='0' AND ord.invoice_number!=''";
				$AR_FPV = $this->SqlModel->runQuery($Q_FPV,true); 
				$Q_EXTRA = "SELECT ord.*, fpv.coupon_val    
							FROM  tbl_order_detail AS ord 
							LEFT JOIN tbl_orders AS prnt ON prnt.order_id=ord.order_id 
							LEFT JOIN tbl_coupon AS fpv ON fpv.use_order_id=ord.order_id 	
							WHERE prnt.franchisee_id='$franchisee_id' 
							AND DATE(prnt.invoice_date) BETWEEN '$AR_Date[first_day]' AND '$AR_Date[last_day]'   
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
                    <td align="left"><?php echo $AR_Date[month_name].' '.$AR_Date[month_year];?></td>
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
            <?php }?>
			  </div>
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