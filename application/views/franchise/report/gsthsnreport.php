<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$franchisee_id = $this->session->userdata('fran_id');
$StrWhr .= "AND (mstr.franchisee_id='$franchisee_id')";
$SrchQ .= "&franchisee_id=".$franchisee_id."";

if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$SrchQ .= "&date_from=$date_from&date_to=$date_to";
	
	$Q_Sales = "SELECT tpl.* FROM tbl_post_lang AS tpl
				LEFT JOIN tbl_post AS tp ON tp.post_id=tpl.post_id
				WHERE tpl.post_hsn!='' AND tp.franchisee_id='".$franchisee_id."'
				ORDER BY tpl.post_id ASC";
	$PageVal = DisplayPages($Q_Sales, 100, $Page, $SrchQ);
	
	ExportQuery($Q_Sales,array("franchisee_id"=>$franchisee_id,"date_from"=>$date_from,"date_to"=>$date_to));
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
    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!--[if lte IE 8]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        $(function(){
            $(".open_modal").on('click',function(){
                $('#search-modal').modal('show');
                return false;
            });
        });
    </script>
</head>
<body class="no-skin">
    <div class="main-content">
        <div class="main-content-inner">
        <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
        <div class="page-content">
        <div class="page-header">
            <h1> Sales <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; GST HSN Summary</small> </h1>
        </div>
        <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
        <div class="row">
        <div class="col-xs-12">
        <div class="clearfix">
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("report","gsthsnreport",""); ?>" method="post">
        <div class="row">
        <div class="col-md-2"><div class="input-group"><input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="Date From" type="text"  /><span class="input-group-addon"> <i class="fa fa-calendar"></i></span></div></div>
        <div class="col-md-2"><div class="input-group"><input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  /><span class="input-group-addon"> <i class="fa fa-calendar"></i></span></div></div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
        <div class="col-md-12">
        <button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
        <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
        <button type="button" class="btn btn-sm btn-info" onClick="window.location.href='<?php echo generateSeoUrlAdmin("excel","gsthsnreport",""); ?>'"> <i class="ace-icon fa fa-download"></i> Download </button>
        </div>
        </div>
        </form>
        </div>
        <div class="row">&nbsp;</div>
        </div>
        <hr>
        <div class="col-xs-12">
        <div>
        <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
        <tr class="">
            <td align="left"><strong>SR NO</strong></td>
            <td align="center"><strong>HSN CODE</strong></td>
            <td align="left"><strong>ITEM DESCRIPTION</strong></td>
            <td align="center"><strong>GST SLAB</strong></td>
            <td align="right"><strong>QTY (PCS)</strong></td>
            <td align="right"><strong>TAXABLE VALUE</strong></td>
            <td align="center" colspan="3"><strong>TAX AMOUNT</strong></td>
            <td align="right"><strong>TOTAL VALUE</strong></td>
        </tr>
        <tr class="">
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right"><strong>IGST</strong></td>
            <td align="right"><strong>SGST</strong></td>
            <td align="right"><strong>CGST</strong></td>
            <td align="right">&nbsp;</td>
        </tr>
        <?php 
        if($PageVal['TotalRecords'] > 0){
        $Ctrl = $PageVal['RecordStart']+1;
        foreach($PageVal['ResultSet'] as $AR_DT):
		
		$Q_Qty_Ofr = "SELECT IFNULL(SUM(ord.post_qty),0) AS post_qty_sold 
					  FROM tbl_order_detail AS ord 
					  LEFT JOIN tbl_orders AS mstr ON mstr.order_id=ord.order_id 
					  WHERE ord.post_id='$AR_DT[post_id]' 
					  AND ord.in_offer='Y' 
					  AND DATE(mstr.invoice_date) BETWEEN '$date_from' AND '$date_to' 
					  $StrWhr";
		$AR_Qty_Ofr = $this->SqlModel->runQuery($Q_Qty_Ofr,true);

		$Q_Qty_Nfr = "SELECT IFNULL(SUM(ord.post_qty),0) AS post_qty_sold 
					  FROM tbl_order_detail AS ord 
					  LEFT JOIN tbl_orders AS mstr ON mstr.order_id=ord.order_id 
					  WHERE ord.post_id='$AR_DT[post_id]' 
					  AND ord.in_offer='N' 
					  AND DATE(mstr.invoice_date) BETWEEN '$date_from' AND '$date_to' 
					  $StrWhr";
		$AR_Qty_Nfr = $this->SqlModel->runQuery($Q_Qty_Nfr,true);
		
		$offer_value = ($AR_Qty_Ofr['post_qty_sold']*($AR_DT['post_bv']/(1+($AR_DT['tax_age']/100))));
		$no_offer_value = ($AR_Qty_Nfr['post_qty_sold']*$AR_DT['post_tax']);
		$taxable_value = ($offer_value+$no_offer_value); 
		$sgst_value = ($taxable_value*($AR_DT['tax_age']/2)/100);
		$cgst_value = ($taxable_value*($AR_DT['tax_age']/2)/100);
		$total_value = ($taxable_value+$sgst_value+$cgst_value);
		
		$total_qty += ($AR_Qty_Ofr['post_qty_sold']+$AR_Qty_Nfr['post_qty_sold']);
		$total_tax += $taxable_value;
		$total_sgst += $sgst_value;
		$total_cgst += $cgst_value;
		$gross_value += $total_value;
		
		if($taxable_value>0){
        ?>
        <tr>
            <td><?php echo $Ctrl;?></td>
            <td align="center"><?php echo $AR_DT['post_hsn']; ?></td>
            <td align="left"><?php echo $AR_DT['post_title']; ?></td>
            <td align="center"><?php echo $AR_DT['tax_age']; ?>%</td>
            <td align="right"><?php echo ($AR_Qty_Ofr['post_qty_sold']+$AR_Qty_Nfr['post_qty_sold']); ?></td>
            <td align="right"><?php echo round($taxable_value,2); ?></td>
            <td align="right">0</td>
            <td align="right"><?php echo round($sgst_value,2); ?></td>
            <td align="right"><?php echo round($cgst_value,2); ?></td>
            <td align="right"><?php echo round($total_value,2); ?></td>
        </tr>
        <?php
		$Ctrl++;
		}
		unset($taxable_value,$sgst_value,$cgst_value,$total_value);
        endforeach;
        ?>
        <tr>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="right"><?php echo $total_qty; ?></td>
            <td align="right"><?php echo round($total_tax,2); ?></td>
            <td align="right">0</td>
            <td align="right"><?php echo round($total_sgst,2); ?></td>
            <td align="right"><?php echo round($total_cgst,2); ?></td>
            <td align="right"><?php echo round($gross_value,2); ?></td>
        </tr>
        <?php 
        }else{
        ?>
        <tr>
        	<td colspan="12" align="center" class="errMsg">No record found</td>
        </tr>
        <?php } ?>
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
    <script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
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