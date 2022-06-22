<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();

if($_REQUEST['user_id']!=''){
	$member_id = $model->getMemberId($_REQUEST['user_id']);
	$StrWhr .=" AND tm.member_id='$member_id'";
	$SrchQ .="&member_id=$member_id";
}

$QR_PAGES = "SELECT tmp.*, tm.user_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.date_of_birth
			FROM tbl_mem_pancard AS tmp 
			LEFT JOIN tbl_members AS tm ON tmp.member_id=tm.member_id
			WHERE 1 $StrWhr ORDER BY tmp.pan_id DESC";
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
		
		$(".updateStatus").on('click',function(){
			var pan_id = $(this).attr("pan_id");
			var URL_LOAD = "<?php echo generateAdminForm("json","jsonhandler",""); ?>?switch_type=PANCARD&pan_id="+pan_id;
			$.getJSON(URL_LOAD,function(JVal){});
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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Pancard </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a  class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold open_modal"><span><i class="fa fa-search bigger-110 pink"></i> <span class="hidden">Search</span></span></a> 
                      <!-- <a aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-excel buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Export to Excel</span></span>
                    </a> --> 
                      <a  href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div>
                  <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th width="22" class="center"> <label class="pos-rel"> ID <span class="lbl"></span> </label>
                        </th>
                        <th width="150" align="center">Member</th>
                        <th width="157" align="center">User Id </th>
                        <th width="157" align="center">Date of Birth </th>
                        <th width="157" align="center">Pan No </th>
                        <th width="157" align="center">File</th>
                        <th width="157" align="center">Date</th>
                        <th width="101" align="center">Approved</th>
                        <th width="101" align="center">&nbsp;</th>
                      </tr>
                    </thead>
                    <form method="post" autocomplete="off" action="<?php echo generateAdminForm("operation","pancard","");  ?>">
                      <tbody>
                        <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						$pan_file = $model->getPanCard($AR_DT['pan_id']);
					
			       ?>
                        <tr>
                          <td class="center"><label class="pos-rel"> <?php echo $AR_DT['pan_id']; ?> <span class="lbl"></span> </label></td>
                          <td align="left"><a href="javascript:void(0)"><?php echo $AR_DT['full_name']; ?></a></td>
                          <td align="left"><a href="javascript:void(0)"><?php echo $AR_DT['user_id']; ?></a></td>
                          <td align="left"><input type="text" name="date_of_birth" id="date_of_birth" class="enableText updateDOB" readonly value="<?php echo DisplayDate($AR_DT['date_of_birth']); ?>" member_id="<?php echo $AR_DT['member_id']; ?>" style="width:100px;"></td>
                          <td align="left"><input type="text" name="pan_no" id="pan_no" class="enableText updatePancard" readonly value="<?php echo ($AR_DT['pan_no']); ?>" member_id="<?php echo $AR_DT['member_id']; ?>" approve_sts="<?php echo $AR_DT['approve_sts']; ?>" style="width:110px;"></td>
                          <td align="left"><a target="_blank" href="<?php echo $pan_file; ?>"><?php echo $pan_file; ?></a></td>
                          <td align="left"><?php echo DisplayDate($AR_DT['date_time']); ?></td>
                          <td align="center"><label>
                              <input name="switch-field-1"   pan_id="<?php echo $AR_DT['pan_id']; ?>"  class="ace ace-switch ace-switch-6 updateStatus" <?php if($AR_DT['approve_sts']>0){ echo 'checked="checked"';} ?> type="checkbox">
                              <span class="lbl"></span> </label></td>
                          <td align="center">&nbsp;&nbsp; <a onClick="return confirm('Make sure, want to delete this document?')" 
								href="<?php echo generateSeoUrlAdmin("member","pancard",array("pan_id"=>_e($AR_DT['pan_id']),"action_request"=>"DELETE")); ?>"><i class="fa fa-trash"></i></a></td>
                        </tr>
                        <?php $Ctrl++; endforeach; ?>
                        <?php  }else{ ?>
                        <tr>
                          <td colspan="9" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </form>
                  </table>
                  <div class="row">
                    <div class="col-xs-6">
                      <div aria-live="polite" role="status" id="dynamic-table_info" class="dataTables_info"> Showing <?php echo $PageVal['RecordStart']+1; ?> to <?php echo  count($PageVal['ResultSet']); ?> of <?php echo $PageVal['TotalRecords']; ?> operator </div>
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
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("member","pancard",""); ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Member </label>
              <div class="col-sm-6">
                <input name="user_id" id="user_id"  class="col-xs-12 col-sm-4" type="text" placeholder="User Name" value="">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
            <button type="button" class="btn btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
            <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"> <i class="ace-icon fa fa-times"></i> Close </button>
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
<script type="text/javascript">
	$(function(){
		$(".enableText").on('dblclick',function(){
			$(this).attr("readonly",false);
		});
		$(".enableText").on('blur',function(){
			$(this).attr("readonly",true);
		});
		$(".updateDOB").on('change',function(){
			var member_id = $(this).attr("member_id");
			var date_of_birth = $(this).val();
			if(member_id>0){
				var URL_LOAD = "<?php echo generateSeoUrlAdmin("json","jsonhandler",""); ?>";
				$.getJSON(URL_LOAD,{switch_type:"SAVE_DOB",member_id:member_id,date_of_birth:date_of_birth},function(JsonEval){
					if(JsonEval){
						alert("Date of birth  updated successfully");
						return true;
					}
				});
			}
		});
		$(".updatePancard").on('change',function(){
			var member_id = $(this).attr("member_id");
			var approve_sts = $(this).attr("approve_sts");
			var pan_no = $(this).val();
			if(member_id>0){
				if(approve_sts>0){
					var URL_LOAD = "<?php echo generateSeoUrlAdmin("json","jsonhandler",""); ?>";
					$.getJSON(URL_LOAD,{switch_type:"SAVE_PANCARD",member_id:member_id,pan_no:pan_no},function(JsonEval){
						if(JsonEval){
							alert("Pan No updated successfully");
							return true;
						}
					});
				}else{
					alert("Unable to update pancard, please approved first");
					return false;
				}
			}
		});
	});
</script>
</html>
