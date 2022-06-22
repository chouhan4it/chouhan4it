<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}


if($_REQUEST['process_id']!=''){
	$process_id = FCrtRplc($_REQUEST['process_id']);
	$StrWhr .=" AND tp.process_id='$process_id'";
	$SrchQ .="&process_id=$process_id";
}

$QR_PAGES="SELECT tp.*, SUM(tcb.net_cmsn) AS sum_net_cmsn
		 FROM tbl_process AS tp 
		 LEFT JOIN tbl_cmsn_binary_repur AS tcb ON tcb.process_id=tp.process_id
		 WHERE tp.process_sts='Y' 
		 $StrWhr 
		 GROUP BY tp.process_id ORDER BY tp.process_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);	

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
          <h1> CMSN  <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Binary  Income </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a  href="<?php echo generateSeoUrlAdmin("excel","cmsnmaster",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <div class="clearfix">
                  <div class="col-md-6">
                    <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("bonus","binaryincomerepurchase",""); ?>">
                      <b>Process Week </b>
                      <div class="form-group">
                        <div class="clearfix">
                          <select class="col-xs-12 col-sm-6 validate[required]" id="process_id" name="process_id" >
                            <option value="">----select week----</option>
                            <?php  DisplayCombo($_REQUEST['process_id'],"PROCESS");  ?>
                          </select>
                        </div>
                      </div>
                      <input class="btn btn-primary btn-sm m-t-n-xs" value=" Search " type="submit">
                      <a href="<?php echo generateSeoUrlAdmin("member","binaryincomerepurchase",""); ?>"  class="btn btn-danger btn-sm m-t-n-xs" value=" Reset ">Reset</a>
                    </form>
                  </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <table id="no-more-tables" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr role="row">
                      <th  class="">Srl No </th>
                      <th  class="">Date</th>
                      <th  class="">Process Week </th>
                      <th  class="">Total Sum </th>
                      <th  class="">Report </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					if($PageVal['TotalRecords'] > 0){
					$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
					?>
                    <tr class="odd" role="row">
                      <td data-title="Srl No" class=""><?php echo $Ctrl; ?></td>
                      <td data-title="Date" class=""><?php echo DisplayDate($AR_DT['end_date']); ?></td>
                      <td data-title="Process Week" class=""><?php echo DisplayDate($AR_DT['start_date']); ?> - To - <?php echo DisplayDate($AR_DT['end_date']); ?></td>
                      <td data-title="Total Sum"><?php echo number_format($AR_DT['sum_net_cmsn'],2); ?></td>
                      <td data-title="Report"><a href="<?php echo generateSeoUrlAdmin("bonus","binaryincomerepurchaselist",""); ?>?process_id=<?php echo $AR_DT['process_id']; ?>">View</a></td>
                    </tr>
                    <?php $Ctrl++; 
				  	endforeach;
					}else{ ?>
                    <tr class="odd" role="row">
                      <td colspan="5" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                    </tr>
                    <?php } ?>
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
		
		$(".modal-diffrential").on('click',function(){
			var master_id = $(this).attr("master_id");
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			$.post(URL_GET,{switch_type:"DIFFRENTIAL",master_id:master_id},function(JsonEval){
				$(".load-diffrential").html(JsonEval);
				$("#modal-diffrential-detail").modal({
					backdrop: 'static',
					keyboard: false
				});
			});
			
		});
		
		$(".payStatus").on('click',function(){
			var member_id = $(this).attr("member_id");
			var process_id = $(this).attr("process_id");
			var URL_LOAD = "<?php echo ADMIN_PATH."json/jsonhandler"; ?>";
			alert(URL_LOAD);
			var data = {
				switch_type : "PAYSTS",
				member_id : member_id,
				process_id : process_id
			}
			$.getJSON(URL_LOAD,data,function(JsonEval){});
		});
		
	});
</script>
</body>
</html>
