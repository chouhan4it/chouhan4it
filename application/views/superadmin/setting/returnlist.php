<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}


$QR_PAGES="SELECT trs.* FROM tbl_return_setup AS trs WHERE  trs.return_id>0 $StrWhr ORDER BY trs.return_id ASC";
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
          <h1> Return <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Type </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a href="<?php echo generateSeoUrlAdmin("setting","addrank",""); ?>" title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-collection buttons-colvis btn btn-white btn-primary btn-bold"><span><i class="fa fa-plus bigger-110 blue"></i> <span class="hidden">Show/hide columns</span></span></a> 
                      <!-- <a aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-excel buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Export to Excel</span></span>
                    </a> --> 
                      <a  href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a 	 href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div>
                  <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th width="22" class="center"> Srl No
                          </label>
                        </th>
                        <th width="201">Name</th>
                        <th width="116">Rate % </th>
                        <th width="117">Status</th>
                        <th width="89">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr class="<?php echo ($AR_DT['return_sts']==0)? "text-danger":""; ?>">
                        <td class="center"><?php echo $Ctrl; ?></td>
                        <td><?php echo $AR_DT['return_name']; ?></td>
                        <td><?php echo $AR_DT['return_rate']; ?> % </td>
                        <td><?php echo ($AR_DT['return_sts']>0)? "Active":"In Active"; ?></td>
                        <td><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                              <li> <a href="<?php echo generateSeoUrlAdmin("setting","returntype",array("return_id"=>_e($AR_DT['return_id']),"action_request"=>"EDIT")); ?>">Edit</a> </li>
                              <li> <a onClick="return confirm('Make sure , you want to change this plan status?')" href="<?php echo generateSeoUrlAdmin("setting","returntype",array("return_id"=>_e($AR_DT['return_id']),"action_request"=>"STATUS","return_sts"=>$AR_DT['return_sts'])); ?>"><?php echo ($AR_DT['return_sts']=="0")? "In-Active":"Active"; ?></a> </li>
                            </ul>
                          </div></td>
                      </tr>
                      <?php $Ctrl++; endforeach; }else{ ?>
                      <tr>
                        <td colspan="5" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
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
</body>
</html>
