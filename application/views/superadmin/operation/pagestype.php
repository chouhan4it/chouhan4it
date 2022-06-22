<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$QR_PAGES="SELECT tsmm.*, tfai.icon_name FROM tbl_sys_menu_main AS tsmm 
		   LEFT JOIN tbl_font_awsome_icon AS tfai ON tsmm.icon_id=tfai.icon_id 
		   WHERE 1 ORDER BY tsmm.order_id ASC";
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
          <h1> Main Menu <small> <i class="ace-icon fa fa-angle-double-right"></i>&nbsp; Pages </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a href="<?php echo generateSeoUrlAdmin("operation","pagesadd",array("ptype_id"=>$AR_DT['ptype_id'],"action_request"=>"ADD")); ?>" title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-collection buttons-colvis btn btn-white btn-primary btn-bold"><span><i class="fa fa-plus bigger-110 blue"></i> <span class="hidden">Show/hide columns</span></span></a> 
                      <!--<a title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-copy bigger-110 pink"></i> <span class="hidden">Copy to clipboard</span></span></a>
				   <a aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-excel buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Export to Excel</span></span>
                    </a> --> 
                      <a  href="<?php echo generateSeoUrlAdmin("excel","pagestype",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div>
                  <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th > Srl # </th>
                        <th >Main Menu </th>
                        <th >Icon</th>
                        <th >Display Order </th>
                        <th ></th>
                      </tr>
                    </thead>
                    <form method="post" autocomplete="off" action="<?php echo ADMIN_PATH."operation/pagesadd"; ?>">
                      <tbody>
                        <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                        <tr>
                          <td class="center"><?php echo $Ctrl; ?></td>
                          <td><a href="javascript:void(0)"><?php echo $AR_DT['type_name']; ?></a></td>
                          <td><?php echo $AR_DT['icon_name']; ?></td>
                          <td ><input type="hidden" name="ptype_id[]" value="<?php echo $AR_DT['ptype_id'];?>" />
                            <input name="order_id[]" type="text" class="cmnfld" id="order_id[]" size="3" value="<?php echo $AR_DT['order_id'];?>" style="text-align:center; width:60px;" onBlur="extractNumber(this,0,true);" onKeyPress="return blockNonNumbers(this, event, true, false);" onKeyDown="javascript: EnterKey();" onKeyUp="extractNumber(this,0,true);" maxlength="2"  tabindex="<?php echo $Ctrl; ?>" /></td>
                          <td><div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                              <ul class="dropdown-menu dropdown-default">
                                <li> <a href="<?php echo generateSeoUrlAdmin("operation","pagesadd",array("ptype_id"=>$AR_DT['ptype_id'],"action_request"=>"EDIT")); ?>">Edit</a> </li>
                                <li> <a onClick="return confirm('Make sure want to delete this record?')" href="<?php echo generateSeoUrlAdmin("operation","pagesadd",array("ptype_id"=>$AR_DT['ptype_id'],"action_request"=>"DELETE")); ?>">Delete</a> </li>
                              </ul>
                            </div></td>
                        </tr>
                        <?php $Ctrl++; endforeach; } ?>
                        <tr>
                          <td >&nbsp;</td>
                          <td >&nbsp;</td>
                          <td >&nbsp;</td>
                          <td ><input type="hidden" name="action_request" id="action_request" value="POSITION">
                            <button type="submit" class="btn btn-white btn-info btn-bold"> <i class="ace-icon fa fa-floppy-o bigger-120 blue"></i> Update</button></td>
                          <td>&nbsp;</td>
                        </tr>
                      </tbody>
                    </form>
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
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
</body>
</html>
