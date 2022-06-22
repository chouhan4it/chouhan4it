<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(2);
$table_name = "tbl_category";
		
$parent_id_get = ($form_data['parent_id'])? _d($form_data['parent_id']):_d($segment['parent_id']);
$parent_id = ($parent_id_get>0)? $parent_id_get:0;

$AR_MAIN= $model->getCategoryDetail($parent_id);
$QR_PAGES= "SELECT tc.* FROM $table_name  AS tc  WHERE tc.parent_id='".$parent_id."' AND tc.delete_sts>0 $StrWhr ORDER BY tc.category_id DESC";
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
<style type="text/css">
.enableText{
	width:50px !important;
}
</style>
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
          <h1> Category<small> <i class="ace-icon fa fa-angle-double-right"></i> <?php echo ($AR_MAIN['category_name'])? $AR_MAIN['category_name']:"List"; ?> </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group">
                      <?php if($parent_id>0){ ?>
                      <a  class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold"  href="<?php echo generateSeoUrlAdmin("shop","categorylist",""); ?>"><span><i class="fa fa-arrow-left bigger-110 blue bigger-110 red"></i> <span class="hidden">Back</span></span></a>
                      <?php } ?>
                      <a href="<?php echo generateSeoUrlAdmin("shop","category",array("parent_id"=>_e($parent_id))); ?>" title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-collection buttons-colvis btn btn-white btn-primary btn-bold"><span><i class="fa fa-plus bigger-110 blue"></i> <span class="hidden">Show/hide columns</span></span></a> <a  href="<?php echo generateSeoUrlAdmin("export","excel",array("sql"=>encode64($QR_PAGES))); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a  href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div>
                  <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th >Id</th>
                        <th >Name</th>
                        <th >Img</th>
                        <th >Detail</th>
                        <th >Display Order </th>
                        <th >Status</th>
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
                        <td class="center"><label class="pos-rel"> <?php echo $AR_DT['category_id']; ?> <span class="lbl"></span> </label></td>
                        <td><a href="<?php echo   generateSeoUrlAdmin("shop","categorylist",array("parent_id"=>_e($AR_DT['category_id']))); ?>"><?php echo ucfirst(strtolower($AR_DT['category_name'])); ?></a></td>
                        <td><img src="<?php echo $model->getCategoryImgSrc($AR_DT['category_id']); ?>" class="img-thumbnail" height="30" width="50"></td>
                        <td><?php echo $AR_DT['category_short']; ?></td>
                        <td><?php echo get_input("text","display_order","display_order","enableText updateField",$AR_DT['display_order'],"Display Order","","",$table_name,"display_order",$AR_DT['category_id']);  ?></td>
                        <td><?php echo ($AR_DT['category_sts']=="1")? "Active":"In-Active"; ?></td>
                        <td><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                              <li> <a href="<?php echo generateSeoUrlAdmin("shop","category",array("category_id"=>_e($AR_DT['category_id']),"action_request"=>"EDIT")); ?>">Edit</a> </li>
                              <li> <a onClick="return confirm('Make sure , you want to change this category status?')" href="<?php echo generateSeoUrlAdmin("shop","category",array("category_id"=>_e($AR_DT['category_id']),"action_request"=>"STATUS","category_sts"=>$AR_DT['category_sts'])); ?>"><?php echo ($AR_DT['category_sts']=="0")? "Activate":"De-Activate"; ?></a> </li>
                              <li> <a onClick="return confirm('Make sure want to delete this record?')" href="<?php echo generateSeoUrlAdmin("shop","category",array("category_id"=>_e($AR_DT['category_id']),"action_request"=>"DELETE")); ?>">Delete</a> </li>
                            </ul>
                          </div></td>
                      </tr>
                      <?php $Ctrl++; endforeach; } ?>
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
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<script type="text/javascript">
$(function(){
	$(".enableText").on('dblclick',function(){
		$(this).attr("readonly",false);
	});
	$(".enableText").on('blur',function(){
		$(this).attr("readonly",true);
	});
	
	$(".updateField").on('change',function(){
		var data_id = $(this).attr("data_id");
		var data_field = $(this).attr("data_field");
		var data_table = $(this).attr("data_table");
		var data_value = $(this).val();
		var URL_LOAD = "<?php echo generateSeoUrlAdmin("json","jsonhandler",""); ?>";
		$.getJSON(URL_LOAD,{switch_type:"UPDATE_FIELD",data_id:data_id,data_field:data_field,data_value:data_value,data_table:data_table},function(JsonEval){
			if(JsonEval.error_sts>0){
				alert("Record updated successfully");
			}else{
				alert("Unable to update record");
			}
		});
	});
});
</script>
</body>
</html>
