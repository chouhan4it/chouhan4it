<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalTime();

if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND ( DATE(led.order_date) BETWEEN '$date_from' AND '$date_to')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}
if($_REQUEST['order_no']!=''){
	$order_no = FCrtRplc($_REQUEST['order_no']);
	$StrWhr .=" AND led.ref_no='".$order_no."'";
	$SrchQ .="&order_no=$order_no";
}

$QR_PAGES = "SELECT DISTINCT(led.ref_no) AS ref_no, trans_id, trans_date, tf.name 
			 FROM tbl_stock_ledger AS led 
			 LEFT JOIN tbl_franchisee AS tf ON tf.franchisee_id = led.franchisee_id
			 WHERE led.ref_no LIKE '%RSTK%' $StrWhr 
			 GROUP BY led.ref_no
			 ORDER BY led.trans_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
ExportQuery($QR_PAGES);
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
<?php #auto_complete();  ?>
</head>
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
          <h1> Stock<small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Shoppe To Company Transaction </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="row">
                    <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("stock","shoppetocompanytxns",""); ?>" method="post">
                      <div class="col-md-4">
                        <input id="order_no" placeholder="Order No" name="order_no"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['order_no']; ?>">
                      </div>
                      <div class="col-md-2">
                        <div class="input-group">
                          <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="" placeholder="Date From" type="text"  />
                          <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                      </div>
                      <div class="col-md-2">
                        <div class="input-group">
                          <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="" placeholder="Date To" type="text"  />
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
              <div class="clearfix">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th width="22" class="center"> <label class="pos-rel"> ID <span class="lbl"></span> </label>
                      </th>
                      <th width="201">Order No</th>
                      <th width="117">From Branch</th>
                      <th width="117">To Branch</th>
                      <th width="117">Status</th>
                      <th width="117">Date</th>
                      <th width="89">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                    <tr>
                      <td class="center"><label class="pos-rel"> <?php echo $AR_DT['trans_id']; ?> <span class="lbl"></span> </label></td>
                      <td><?php echo $AR_DT['ref_no']; ?></td>
                      <td><?php echo $AR_DT['name']; ?></td>
                      <td><?php echo "COMPANY"; ?></td>
                      <td><?php echo "Approved";?></td>
                      <td><?php echo DisplayDate($AR_DT['trans_date']); ?></td>
                      <td><div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                          <ul class="dropdown-menu dropdown-default">
                            <li><a href="<?php echo generateSeoUrlAdmin("stock","returnstockdetail",array("order_no"=>_e($AR_DT['ref_no']))); ?>">View</a></li>
                          </ul>
                        </div></td>
                    </tr>
                    <?php $Ctrl++; endforeach; }else{ ?>
                    <tr>
                      <td colspan="7" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
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
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
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
<script type="text/javascript" language="javascript">
	new Autocomplete("post_name", function(){
	this.setValue = function( id ) {document.getElementsByName("post_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=PRODUCT";
	});
</script>
</body>
</html>