<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['process_id']>0){
	$process_id  = $_REQUEST['process_id'];
	$StrWhr .=" AND tcb.process_id='".$process_id."'";
	$SrchQ .="&process_id=$process_id";
}else{ set_message("warning","unable to load matching repurchase detail"); redirect_page("bonus","binaryincomerepurchase",""); }

$QR_PAGES="SELECT tcb.*, tm.user_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tp.start_date, tp.end_date,
		 tm.ifc_code , tm.branch, CONCAT_WS(':','AC',tm.account_number) AS account_no, tm.bank_name
		 FROM tbl_cmsn_binary_repur AS tcb 
		 LEFT JOIN tbl_process_binary AS tp ON tp.process_id=tcb.process_id
		 LEFT JOIN tbl_members AS tm ON tm.member_id=tcb.member_id
		 WHERE tcb.amount>0 $StrWhr ORDER BY tcb.binary_id DESC";
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
          <h1> Bonus <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;Matching Repurchase Income </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12" style="min-height:500px;">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a  href="<?php echo generateSeoUrlAdmin("excel","binaryincomerepurchase",""); ?>?process_id=<?php echo $process_id; ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr role="row">
                      <th  class="sorting_desc">Week Date </th>
                      <th  class="sorting">Member </th>
                      <th  class="sorting">Full Name</th>
                      <th  class="sorting">Left<br />
                        Collection </th>
                      <th  class="sorting">Right <br />
                        Collection </th>
                      <th  class="sorting">Matching</th>
                      <th class="sorting"><span class="sorting" style="width: 526px;">Left <br />
                        Carry Forward </span></th>
                      <th  class="sorting">Right <br />
                        Carry Forward </th>
                      <th  class="sorting">Ratio %</th>
                      <th  class="sorting">Net Income </th>
                      <th  class="sorting">Tds</th>
                      <th  class="sorting">Admin</th>
                      <th  class="sorting">Net Payout</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					if($PageVal['TotalRecords'] > 0){
					$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						
					?>
                    <tr class="odd" role="row">
                      <td class="sorting_1"><?php echo DisplayDate($AR_DT['start_date']); ?> - To - <?php echo DisplayDate($AR_DT['end_date']); ?></td>
                      <td><?php echo $AR_DT['user_id']; ?></td>
                      <td><?php echo $AR_DT['full_name']; ?></td>
                      <td><?php echo $AR_DT['newLft']; ?></td>
                      <td><?php echo $AR_DT['newRgt']; ?></td>
                      <td><?php echo $AR_DT['pair_match']; ?></td>
                      <td><?php echo $AR_DT['leftCrf']; ?></td>
                      <td><?php echo $AR_DT['rightCrf']; ?></td>
                      <td><?php echo $AR_DT['binary_rate']; ?>  %</td>
                      <td><?php echo number_format($AR_DT['amount']); ?></td>
                      <td><?php echo number_format($AR_DT['tds']); ?></td>
                      <td><?php echo number_format($AR_DT['admin_charge']); ?></td>
                      <td><?php echo number_format($AR_DT['net_cmsn']); ?></td>
                    </tr>
                    <?php endforeach;
								}
								 ?>
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
</body>
</html>
