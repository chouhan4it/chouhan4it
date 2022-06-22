<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalTime();
$franchisee_id = $this->session->userdata('fran_id');

if($_REQUEST['post_title']!=''){
	$post_title = FCrtRplc($_REQUEST['post_title']);
	$StrWhr .=" AND ( tpl.post_title LIKE '%$post_title%' OR tpl.post_desc LIKE '%$post_title%' )";
	$SrchQ .="&post_title=$post_title";
}
$QR_PAGES= "SELECT tp.*,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price,  tpl.update_date , GROUP_CONCAT(tpc.category_id) AS category_id,
			GROUP_CONCAT(tt.tag_name) AS tags_name, COUNT(tpv.view_id) AS view_ctrl, COUNT(tpr.review_id) AS review_ctrl
			FROM  tbl_post AS tp
			LEFT JOIN  tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tag_id,tpl.post_tags)
			LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
			LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
			WHERE tp.delete_sts>0  AND tp.post_id IN(SELECT post_id FROM tbl_stock_ledger WHERE franchisee_id='".$franchisee_id."') 
			$StrWhr 
			GROUP BY tp.post_id  
			ORDER BY tp.post_id DESC";
$PageVal = DisplayPages($QR_PAGES, 1000, $Page, $SrchQ);
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
		line-height:2;
		padding:0 0 0 3px;
	}
.style1 {font-weight: bold}
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
      <!-- /.page-header -->
      <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-12">
              <div class="clearfix">
                <div class="row">
                  <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("stock","stocksummary",""); ?>" method="post">
                    
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
              <table border="1" style="border-collapse:collapse;" cellpadding="4" cellspacing="2" width="100%">
                <thead>
                  <tr>
                    <th width="22" class="center"> <label class="pos-rel"> ID <span class="lbl"></span> </label>                    </th>
                    <th width="201">Product Name </th>
                    <th width="117">Stock Purchase </th>
                    <th width="117">Stock Sale </th>
                    <th width="117">Stock Refund </th>
                    <th width="117">Aval Balance . </th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						$purchase = $model->getStockBalanceType($AR_DT['post_id'],"TRF",$franchisee_id,$_REQUEST['date_from'],$_REQUEST['date_to']);
						$sales = $model->getStockBalanceType($AR_DT['post_id'],"CF",$franchisee_id,$_REQUEST['date_from'],$_REQUEST['date_to']);
						$refund = $model->getStockBalanceType($AR_DT['post_id'],"REF",$franchisee_id,$_REQUEST['date_from'],$_REQUEST['date_to']);
						$net_available = ($purchase+$refund)-$sales;
						
						$toal_purchase +=$purchase;
						$toal_sales +=$sales;
						$toal_refund +=$refund;
						$toal_available +=$net_available;
			       ?>
                  <tr class="<?php echo ($AR_DT['stock_sts']=="R")? "text-danger":""; ?>">
                    <td class="center"><label class="pos-rel"> <?php echo $Ctrl; ?> <span class="lbl"></span> </label>                    </td>
                    <td><?php echo $AR_DT['post_title']; ?></td>
                    <td><?php echo number_format($purchase); ?></td>
                    <td><?php echo number_format($sales); ?></td>
                    <td><?php echo number_format($refund); ?></td>
                    <td><?php echo number_format($net_available); ?></td>
                  </tr>
                  
                  <?php $Ctrl++; endforeach; }else{ ?>
                  <tr>
                    <td colspan="6" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                  </tr>
                  <?php } ?>
				  <tr>
                    <td>&nbsp;</td>
                    <td><strong>Total Summary</strong></td>
                    <td><strong>
                    <?php echo  number_format($toal_purchase); ?>
                    </strong></td>
                    <td><strong>
                      <?php echo number_format($toal_sales); ?>
                    </strong></td>
                    <td><strong>
                      <?php  echo number_format($toal_refund); ?>
                    </strong></td>
                    <td><strong>
                      <?php echo number_format($toal_available); ?>
                    </strong></td>
                  </tr>
                </tbody>
              </table>
			  <div class="clearfix">&nbsp;</div>
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
