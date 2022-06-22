<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalTime();
$franchisee_id = $this->session->userdata('fran_id');
$StrWhr .=" AND tsl.franchisee_id='".$franchisee_id."'";
if($_REQUEST['post_title']!=''){
	$post_title = FCrtRplc($_REQUEST['post_title']);
	$StrWhr .=" AND ( tpl.post_title LIKE '%$post_title%' OR tpl.post_desc LIKE '%$post_title%' )";
	$SrchQ .="&post_title=$post_title";
}
$QR_PAGES= "SELECT tsl.post_id, tsl.post_attribute_id, tpl.post_title, tpl.post_price
			FROM tbl_stock_ledger AS tsl
			LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tsl.post_id
			WHERE 1
		    $StrWhr
			GROUP BY tsl.post_id, tsl.post_attribute_id
			$ORDER_BY";
$AR_DTS  = $this->SqlModel->runQuery($QR_PAGES);

$monday = InsertDate($flddToday);
$tuesday = InsertDate(AddToDate($flddToday,"-1 Day"));
$wednesday = InsertDate(AddToDate($flddToday,"-2 Day"));
$thursday = InsertDate(AddToDate($flddToday,"-3 Day"));
$friday = InsertDate(AddToDate($flddToday,"-4 Day"));
$saturday = InsertDate(AddToDate($flddToday,"-5 Day"));
$sunday = InsertDate(AddToDate($flddToday,"-6 Day"));
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
        <h1> Weekly Sales <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Report </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-12">
              <div class="clearfix">
                <div class="row">
                  <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("report","salesreport",""); ?>" method="post">
                    <div class="col-md-4">
                      <input id="post_title" placeholder="Product Name" name="post_title"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['post_title']; ?>">
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
            <div class="clearfix">
              <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="201">&nbsp;</th>
                    <th width="117"><?php echo getDateFormat($sunday,"D (d-m-Y)"); ?></th>
                    <th width="117"><?php echo getDateFormat($saturday,"D (d-m-Y)"); ?></th>
                    <th width="117"><?php echo getDateFormat($friday,"D (d-m-Y)"); ?></th>
                    <th width="117"><?php echo getDateFormat($thursday,"D (d-m-Y)"); ?></th>
                    <th width="117"><?php echo getDateFormat($wednesday,"D (d-m-Y)"); ?></th>
                    <th width="117"><?php echo getDateFormat($tuesday,"D (d-m-Y)"); ?></th>
                    <th width="117"><?php echo getDateFormat($monday,"D (d-m-Y)"); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
			 	 	if(count($AR_DTS) > 0){
				  		$Ctrl=1;
						foreach($AR_DTS as $AR_DT):
						$monday_all = $model->getSalesReport($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$monday,$monday);
						$tuesday_all = $model->getSalesReport($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$tuesday,$tuesday);
						$wednesday_all = $model->getSalesReport($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$wednesday,$wednesday);
						$thursday_all = $model->getSalesReport($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$thursday,$thursday);
						$friday_all = $model->getSalesReport($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$friday,$friday);
						$saturday_all = $model->getSalesReport($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$saturday,$saturday);
						$sunday_all = $model->getSalesReport($AR_DT['post_id'],$AR_DT['post_attribute_id'],$franchisee_id,$sunday,$sunday);
			       ?>
                  <tr>
                    <td><?php echo $AR_DT['post_title']; ?></td>
                    <td><?php echo number_format($sunday_all); ?></td>
                    <td><?php echo number_format($saturday_all); ?></td>
                    <td><?php echo number_format($friday_all); ?></td>
                    <td><?php echo number_format($thursday_all); ?></td>
                    <td><?php echo number_format($wednesday_all); ?></td>
                    <td><?php echo number_format($tuesday_all); ?></td>
                    <td><?php echo number_format($monday_all); ?></td>
                  </tr>
                  <?php 
				  		$Ctrl++; endforeach;
				  	 }else{ 
				   ?>
                  <tr>
                    <td colspan="8" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
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
