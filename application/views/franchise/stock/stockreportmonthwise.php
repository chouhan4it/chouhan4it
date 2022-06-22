<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalTime();
$franchisee_id = $this->session->userdata('fran_id');
$StrWhr .=" AND tsl.franchisee_id='".$franchisee_id."'";

if($_REQUEST['d_month']>0){
		$d_month = FCrtRplc($_REQUEST['d_month']);
		$d_year = FCrtRplc($_REQUEST['d_year']);
		$d_year = ($d_year>0)? $d_year:date("Y");
		$start_date = $d_year."-".$d_month."-01";
		$PERIOD = getMonthDates($start_date);
		$from_date = $PERIOD['flddFDate'];
		$to_date = $PERIOD['flddTDate'];
		$SrchQ .="&d_month=$d_month&d_year=$d_year";
}
if($_REQUEST['d_month']==''){
	$d_month = date("m");
	$d_year = FCrtRplc($_REQUEST['d_year']);
	$d_year = ($d_year>0)? $d_year:date("Y");
	$start_date = $d_year."-".$d_month."-01"; 
	$PERIOD = getMonthDates($start_date);
	$from_date = $PERIOD['flddFDate'];
	$to_date = $PERIOD['flddTDate'];
}


if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);
}
$SrchQ .="&date_from=$date_from&date_to=$date_to";


if($_REQUEST['by']!='' && $_REQUEST['order']!=''){
	$by = FCrtRplc($_REQUEST['by']);
	$order = strtoupper($_REQUEST['order']);
	$ORDER_BY = "ORDER BY ".$by." ".$order;
}else{
	$ORDER_BY = "ORDER BY tsl.post_id ASC";
}

$QR_PAGES = "SELECT tsl.post_id, tsl.post_attribute_id, tpl.post_title
			 FROM  tbl_stock_ledger AS tsl
			 LEFT JOIN  tbl_post_lang AS tpl ON tpl.post_id=tsl.post_id
			 WHERE tsl.post_id>0
			 $StrWhr
			 GROUP BY tsl.post_id, tsl.post_attribute_id
			 $ORDER_BY";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
ExportQuery($QR_PAGES,array("date_from"=>$date_from,"date_to"=>$date_to,"franchisee_id"=>$franchisee_id));
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
        <h1> Stock<small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Report Month wise </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-12">
              <div class="clearfix">
                <div class="row">
                  <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("stock","stockreportmonthwise",""); ?>" method="post">
                    <div class="col-md-3">
                      <select name="d_month" class="form-control"  id="d_month">
                  <option value="">---month---</option>
                  <?php echo DisplayCombo($d_month,"MONTH"); ?>
                </select>
                     
                    </div>
                    <div class="col-md-3">
                     <select name="d_year" class="form-control" id="d_year">
                  <option value="0">---select year---</option>
                  <?php echo DisplayCombo($_REQUEST['d_year'],"PAST_5_YEAR"); ?>
                </select>
                    </div>
                    
					<div class="col-md-3">
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
			<table id="dynamic-table" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="22" class="center"> <label class="pos-rel"> Srl # <span class="lbl"></span> </label>                    </th>
                    <th width="201">Product Name  &nbsp; <a href="<?php echo generateSeoUrlAdmin("stock","stockreport",""); ?>?by=tpl.post_title&order=<?php echo ($_REQUEST['order']=="asc")? "desc":"asc"; ?>"> <i class="fa fa-fw fa-sort"></i></a></th>
                    <th width="117">Stock Opening </th>
                    <th width="117">Stock In &nbsp; <!--<a href="<?php echo generateSeoUrlAdmin("stock","stockreport",""); ?>?by=total_credits&order=<?php echo ($_REQUEST['order']=="asc")? "desc":"asc"; ?>"> <i class="fa fa-fw fa-sort"></i></a>--></th>
                    <th width="117">Stock Out &nbsp; <!--<a href="<?php echo generateSeoUrlAdmin("stock","stockreport",""); ?>?by=total_debits&order=<?php echo ($_REQUEST['order']=="asc")? "desc":"asc"; ?>"> <i class="fa fa-fw fa-sort"></i></a>--></th>
                    <th width="117">Stock Balance  &nbsp; <!--<a href="<?php echo generateSeoUrlAdmin("stock","stockreport",""); ?>?by=balance&order=<?php echo ($_REQUEST['order']=="asc")? "desc":"asc"; ?>"> <i class="fa fa-fw fa-sort"></i></a>--></th>
                    <th width="117">Amt</th>
                    <th width="117">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						$AR_OPEN = $model->getStockOpening($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$from_date);
						$AR_STOCK = $model->getStockBalance($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$from_date,$to_date);
						
						
						$net_balance = $AR_OPEN['net_balance']+$AR_STOCK['net_balance'];
						$total_price = $net_balance*$AR_DT['post_price'];
						$sum_balance +=$net_balance;
					
						$sum_total_credits += $AR_STOCK['total_qty_cr'];
						$sum_total_debits += $AR_STOCK['total_qty_dr'];
						$sum_total_price += $total_price;
			       ?>
                  <tr class="<?php echo ($AR_STOCK['net_balance']==0)? "text-danger":""; ?>">
                    <td class="center"><label class="pos-rel"> <?php echo $Ctrl; ?> <span class="lbl"></span> </label></td>
                    <td><?php echo $AR_DT['post_title']; ?></td>
                    <td align="right"><?php echo number_format($AR_OPEN['net_balance']); ?></td>
                    <td align="right"><?php echo number_format($AR_STOCK['total_qty_cr']); ?></td>
                    <td align="right"><?php echo number_format($AR_STOCK['total_qty_dr']); ?></td>
                    <td align="right"><?php echo number_format($net_balance); ?></td>
                    <td align="right"><?php echo number_format($AR_DT['post_price']); ?></td>
                    <td align="right"><strong><?php echo number_format($total_price); ?></strong></td>
                  </tr>
                 
                  <?php  $Ctrl++; endforeach; ?>
				   <tr class="">
                    <td class="center">&nbsp;</td>
                    <td><strong>Total : </strong></td>
                    <td align="right">&nbsp;</td>
                    <td align="right"><strong><?php echo number_format($sum_total_credits); ?></strong></td>
                    <td align="right"><strong><?php echo number_format($sum_total_debits); ?></strong></td>
                    <td align="right"><strong><?php echo number_format($sum_balance); ?></strong></td>
                    <td align="right">&nbsp;</td>
                    <td align="right"><strong><?php echo number_format($sum_total_price); ?></strong></td>
                  </tr>
				  <?php  }else{ ?>
                  <tr>
                    <td colspan="8" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                  </tr>
                  <?php } ?>
                </tbody>
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
