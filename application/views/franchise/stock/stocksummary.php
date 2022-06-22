<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalTime();
$franchisee_id = $this->session->userdata('fran_id');
$StrWhr .=" AND tsl.franchisee_id='".$franchisee_id."'";
if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
}
if($_REQUEST['month']!='' and $_REQUEST['year']!=''){
	$date_from = InsertDate($_REQUEST['year'].'-'.$_REQUEST['month'].'-01');
	$Q_Date = "SELECT DATE_ADD(DATE_ADD(LAST_DAY('$date_from'), INTERVAL 1 DAY), INTERVAL - 1 MONTH) AS first_day, LAST_DAY('$date_from') AS last_day";
	$AR_Date = $this->SqlModel->runQuery($Q_Date,true);
	$date_to = InsertDate($AR_Date['last_day']);
}
$QR_PAGES = "SELECT tsl.post_id, tsl.post_attribute_id, tpl.post_title
			 FROM  tbl_stock_ledger AS tsl
			 LEFT JOIN  tbl_post_lang AS tpl ON tpl.post_id=tsl.post_id
			 WHERE tsl.post_id>0
			 $StrWhr
			 GROUP BY tsl.post_id, tsl.post_attribute_id
			 $ORDER_BY";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
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
<style type="text/css">
tr > td {
	line-height: 2;
	padding: 0 0 0 3px;
}
.style1 {
	font-weight: bold;
}
</style>
</head>
<body class="no-skin">
<div class="main-content">
	<div class="main-content-inner">
    <?= $this->load->view(FRANCHISE_FOLDER.'/layout/breadcumb'); ?>
	<div class="page-content">
		<div class="page-header">
			<h1> Stock<small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Summary </small> </h1>
		</div>
		<div class="row">
        <?php get_message(); ?>
		<div class="col-xs-12">
			<div class="row">
			<div class="col-xs-12">
			<div class="clearfix">
			<div class="row">
<form class="form-horizontal" name="form-page" id="form-page" action="<?php echo generateFranchiseForm("stock","stocksummary",""); ?>" method="post">
    <div class="col-md-2">
        <div class="input-group">
        <select name="month" id="month" class="form-control">
                    <option value="">---- Select Month ----</option>
                    <?php DisplayCombo($_REQUEST[month], "MONTH");?>
                  </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
        <select name="year" id="year" class="form-control">
                    <option value="">---- Select Year ----</option>
                    <?php for($i=2017; $i<=date('Y'); $i++){ ?>
                    <option value="<?php echo $i;?>" <?php if($_REQUEST[year]==$i) echo "selected=selected";?>><?php echo $i;?></option>
                    <?php }?>
                  </select>
        </div>
    </div>
    <div class="col-md-3">
        <button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
        <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
    </div>
</form>
			</div>
			</div>
			</div>
			<hr>
            <div class="clearfix">&nbsp;</div>
            <?php 
			if($date_from!='' and $date_to!=''){
			$date_last = AddToDate($date_from,"-1 Day");
			$num_days = dayDiff($date_from,$date_to)+1;
			?>
            <table width="1800" border="0" cellpadding="5" cellspacing="1" class="table-striped table-bordered">
                <tr>
                    <td align="center" width="40" height="45"><strong>SR NO</strong></td>
                    <td align="center" width="280"><strong>PRODUCT</strong></td>
                    <td align="center" width="65"><strong>OPENING STOCK</strong></td>
                    <?php for($i=1; $i<=$num_days; $i++){?>
                    <td align="center" colspan="2"><strong><?php echo $i;?></strong></td>
                    <?php }?>
                    <td align="center" width="65"><strong>CLOSING STOCK</strong></td>
                </tr>
                <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <?php for($i=1; $i<=$num_days; $i++){?>
                    <td align="center" width="35" height="35"><strong>IN</strong></td>
                    <td align="center" width="35"><strong>OUT</strong></td>
                    <?php }?>
                    <td align="center">&nbsp;</td>
                </tr>
				<?php
                $Ctrl=1;
                foreach($PageVal['ResultSet'] as $AR_DT):
                $AR_OPEN = $model->getStockOpening($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$date_from);
                $TotalOpening += $AR_OPEN['net_balance'];
                ?>
                <tr>
                    <td align="center"><?php echo $Ctrl; ?></td>
                    <td align="left" style="padding:10px;"><?php echo $AR_DT['post_title']; ?></td>
                    <td align="center"><?php echo $AR_OPEN['net_balance'];?></td>
                    <?php 
                    for($i=1; $i<=$num_days; $i++){
                    $for_date = AddToDate($date_last,"+$i Day");
                    $AR_QTY = $model->getStockBalance($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$for_date,$for_date);
                    if($AR_QTY['total_qty_cr']>0) $fldiPlus=$AR_QTY['total_qty_cr']; else $fldiPlus=0;
                    if($AR_QTY['total_qty_dr']>0) $fldiMinus=$AR_QTY['total_qty_dr']; else $fldiMinus=0;
                    $fldiClosing += ($fldiPlus-$fldiMinus);
                    $TotalPlus += $fldiPlus;
                    $TotalMinus += $fldiMinus;
                    ?>
                    <td align="center"><?php if($fldiPlus>0) echo '<strong>'.$fldiPlus.'</strong>'; else echo $fldiPlus;?></td>
                    <td align="center"><?php if($fldiMinus>0) echo '<strong>'.$fldiMinus.'</strong>'; else echo $fldiMinus;?></td>
                    <?php 
                    unset($for_date,$fldiPlus,$fldiMinus);
                    }?>
                    <td align="center">
                        <?php 
                        echo ($AR_OPEN['net_balance']+$fldiClosing);
                        $TotalClosing += ($AR_OPEN['net_balance']+$fldiClosing);
                        ?>
                    </td>
                </tr>
				<?php
                unset($fldiClosing); 
                $Ctrl++;
                endforeach;
                ?>
                <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center" height="45"><strong><?php echo $TotalOpening;?></strong></td>
                    <?php for($i=1; $i<=$num_days; $i++){?>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <?php }?>
                    <td align="center"><strong><?php echo $TotalClosing;?></strong></td>
                </tr>
            </table>
			<?php }?>
			<div class="clearfix">&nbsp;</div>
			</div>
		</div>
		</div>
    </div>
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