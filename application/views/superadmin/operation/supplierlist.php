<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}


if($_REQUEST['supplier_name']!=''){
	$supplier_name = FCrtRplc($_REQUEST['supplier_name']);
	$StrWhr .=" AND ( ts.supplier_name LIKE '%$supplier_name%' OR ts.supplier_code LIKE '%$supplier_name%' )";
	$SrchQ .="&supplier_name=$supplier_name&page=$Page";
}

$QR_PAGES="SELECT ts.* FROM tbl_supplier AS ts WHERE ts.delete_sts>0	$StrWhr  ORDER BY ts.supplier_id DESC";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
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
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
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
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Supplier <small> <i class="ace-icon fa fa-angle-double-right"></i>List </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a href="<?php echo generateSeoUrlAdmin("operation","supplier",""); ?>" title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-collection buttons-colvis btn btn-white btn-primary btn-bold"><span><i class="fa fa-plus bigger-110 blue"></i> <span class="hidden">Show/hide columns</span></span></a> <a  class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold open_modal"><span><i class="fa fa-search bigger-110 pink"></i> <span class="hidden">Search</span></span></a> 
                      <!-- <a aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-excel buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Export to Excel</span></span>
                    </a> --> 
                      <a  href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a aria-controls="dynamic-table" href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div>
                  <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="center"> <label class="pos-rel"> Srl No <span class="lbl"></span> </label>
                        </th>
                        <th> Name </th>
                        <th >Code</th>
                        <th >Address</th>
                        <th>Join date </th>
                        <th ></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr>
                        <td class="center"><label class="pos-rel"> <?php echo $AR_DT['supplier_id']; ?> <span class="lbl"></span> </label></td>
                        <td><?php echo $AR_DT['supplier_name']; ?></td>
                        <td><?php echo $AR_DT['supplier_code']; ?></td>
                        <td><?php echo $AR_DT['supplier_address']; ?></td>
                        <td><?php echo DisplayDate($AR_DT['join_date']); ?></td>
                        <td><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                              <li> <a href="<?php echo generateSeoUrlAdmin("operation","supplier",array("supplier_id"=>_e($AR_DT['supplier_id']),"action_request"=>"EDIT")); ?>">Edit</a> </li>
                              <li> <a onClick="return confirm('Make sure want to delete this record?')" href="<?php echo generateSeoUrlAdmin("operation","supplier",array("supplier_id"=>_e($AR_DT['supplier_id']),"action_request"=>"DELETE")); ?>">Delete</a> </li>
                            </ul>
                          </div></td>
                      </tr>
                      <?php $Ctrl++; endforeach; }else{ ?>
                      <?php } ?>
                      <tr>
                        <td colspan="6" class="text text-danger">No supplier found</td>
                      </tr>
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
  <div id="search-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="smaller lighter blue no-margin">Search</h3>
        </div>
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("operation","supplierlist","");  ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Supplier Name / Code </label>
              <div class="col-sm-6">
                <input id="form-field-1" placeholder="Supplier Name" name="supplier_name"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['supplier_name']; ?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Submit </button>
            <button type="button" onClick="window.location.href='?'" class="btn btn-sm btn-warning"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
            <button type="button" class="btn btn-sm btn-danger pull-right" data-dismiss="modal"> <i class="ace-icon fa fa-times"></i> Close </button>
          </div>
        </form>
      </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
</body>
</html>
