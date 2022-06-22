<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(2);

if($_REQUEST['type_id']>0){
	$type_id = FCrtRplc($_REQUEST['type_id']);
	$StrWhr .=" AND tslc.type_id='$type_id'";
	$SrchQ .="&type_id=$type_id";
}
		
$QR_PAGES= "SELECT tslc.* , tp.pin_name
			FROM tbl_setup_level_cmsn AS tslc 
			LEFT JOIN tbl_pintype AS tp ON tslc.type_id=tp.type_id
			WHERE tp.isDelete>0 $StrWhr
		    ORDER BY tslc.type_id ASC,tslc.level_no ASC";
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
		$(".search_modal").on('click',function(){
			$('#search-modal').modal('show');
			return false;
		});
		
		$(".open_modal").on('click',function(){
			$('#form-modal').modal('show');
			return false;
		});
		
		$(".edit_modal").on('click',function(){
			var level_id = $(this).attr("data-id");
			if(level_id>0){
				var URL_LOAD = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
				var data_param = {
					switch_type : "LEVEL_SETUP",
					level_id : level_id
				};
				$.getJSON(URL_LOAD,data_param,function(json_eval){
					if(json_eval){
						if(json_eval.error_sts>0){
							$("#level_id").val(json_eval.primary_id);
							$("#type_id_form").val(json_eval.type_id);
							$("#level_no").val(json_eval.level_no);
							$("#level_cmsn").val(json_eval.level_cmsn);
							$('#form-modal').modal('show');
							return false;
						}
					}
				});
			}
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
          <h1> E-PIn <small> <i class="ace-icon fa fa-angle-double-right"></i> Level </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a  class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold open_modal"><span><i class="fa fa-plus bigger-110 blue bigger-110 pink"></i> <span class="hidden">Add</span></span></a><a  class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold search_modal"><span><i class="fa fa-search bigger-110 pink"></i> <span class="hidden">Search</span></span></a> <a  href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div>
                  <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="center"> Srl No </th>
                        <th >Package</th>
                        <th >Level No</th>
                        <th >Level Cmsn</th>
                        <th >Status</th>
                        <th >Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr>
                        <td class="center"><?php echo $Ctrl; ?></td>
                        <td><?php echo $AR_DT['pin_name']; ?></td>
                        <td>Level No <?php echo $AR_DT['level_no']; ?></td>
                        <td><?php echo $AR_DT['level_cmsn']; ?></td>
                        <td><?php echo ($AR_DT['level_sts']=="0")? "In-Active":"Active"; ?></td>
                        <td><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                              <li> <a class="edit_modal"  data-id="<?php echo $AR_DT['level_id']; ?>" href="javascript:void(0)">Edit</a> </li>
                              <li> <a onClick="return confirm('Make sure , you want to delete this document?')" href="<?php echo generateSeoUrlAdmin("epin","pinlevel",array("level_id"=>_e($AR_DT['level_id']),"action_request"=>"DELETE")); ?>">Delete</a> </li>
                              <li> <a onClick="return confirm('Make sure , you want to change this  status?')" href="<?php echo generateSeoUrlAdmin("epin","pinlevel",array("level_id"=>_e($AR_DT['level_id']),"action_request"=>"STATUS","level_sts"=>$AR_DT['level_sts'])); ?>"><?php echo ($AR_DT['level_sts']=="0")? "Active":"In-Active"; ?></a> </li>
                            </ul>
                          </div></td>
                      </tr>
                      <?php $Ctrl++; endforeach; }else{ ?>
                      <tr>
                        <td colspan="6" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
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
  <div id="search-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="smaller lighter blue no-margin">Search</h3>
        </div>
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("epin","pinlevel","");  ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Pin Type </label>
            <div class="col-sm-7">
                <select  name="type_id" id="type_id" class="form-control validate[required]">
                    <option value="">Select Pin</option>
                    <?php echo DisplayCombo($_REQUEST['type_id'],"PIN_TYPE"); ?>
                  </select>
            </div>
          </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
            <button type="button" class="btn btn-sm btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
            <button type="button" class="btn btn-sm btn-danger pull-right" data-dismiss="modal"> <i class="ace-icon fa fa-times"></i> Close </button>
          </div>
        </form>
      </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>
  <div id="form-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="smaller lighter blue no-margin">Level Setup</h3>
        </div>
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("epin","pinlevel","");  ?>" enctype="multipart/form-data" method="post">
          <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Pin Type </label>
            <div class="col-sm-7">
                <select  name="type_id" id="type_id_form" class="form-control validate[required]">
                    <option value="">Select Pin</option>
                    <?php echo DisplayCombo($ROW['type_id'],"PIN_TYPE"); ?>
                  </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Level No </label>
            <div class="col-sm-7">
              <input name="level_no" type="text" class="col-md-12" id="level_no" placeholder="Level NO" value="<?php echo $ROW['level_no']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Level Price </label>
            <div class="col-sm-7">
              <input type="text" class="col-md-12" placeholder="Level Price" name="level_cmsn" id="level_cmsn" value="<?php echo $ROW['level_cmsn']; ?>">
            </div>
          </div>
          
          <div class="modal-footer">
            <button type="submit" name="submit-level" id="submit-level" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Submit </button>
            &nbsp;
            <input type="hidden" name="action_request" id="action_request"  value="ADD_UPDATE">
            <input type="hidden" name="level_id" id="level_id" value="">
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
			format: 'yyyy-mm-dd',
			todayHighlight: true
		});
		
	});
</script>
</body>
</html>
