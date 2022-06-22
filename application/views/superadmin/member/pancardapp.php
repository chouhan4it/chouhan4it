<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['fullname']!=''){
	$fullname = FCrtRplc($_REQUEST['fullname']);
	$StrWhr .=" AND ( tpr.first_name LIKE '%$fullname%' OR tpr.last_name LIKE '%$fullname%' )";
	$SrchQ .="&fullname=$fullname";
}

$QR_PAGES="SELECT tpr.*	 FROM tbl_pan_register AS tpr	 WHERE tpr.pan_app_id>0   $StrWhr ORDER BY tpr.pan_app_id DESC";
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
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
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
		$(".open_pan_modal").on('click',function(){
			var pan_app_id = $(this).attr("alt");
			if(pan_app_id!=''){
				$("#pan_app_id").val(pan_app_id);
				$('#update-pan-card').modal('show');
				return false;
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
          <h1> Pancard Application <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; List </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a href="<?php echo generateSeoUrlAdmin("member","addmember",array("")); ?>" title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-collection buttons-colvis btn btn-white btn-primary btn-bold"><span><i class="fa fa-plus bigger-110 blue"></i> <span class="hidden">Show/hide columns</span></span></a> <a  class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold open_modal"><span><i class="fa fa-search bigger-110 pink"></i> <span class="hidden">Search</span></span></a> 
                      <!-- <a aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-excel buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Export to Excel</span></span>
                    </a> --> 
                      <a  href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div style="min-height:500px;">
                  <table id="" class="table">
                    <thead>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr class="<?php echo ($AR_DT['block_sts']=="Y")? "text-danger":""; ?>">
                        <td rowspan="3" class="center"><label class="pos-rel"> <?php echo $Ctrl; ?> <span class="lbl"></span> </label></td>
                        <td>Full Name : </td>
                        <td><a href="javascript:void(0)"><?php echo $AR_DT['first_name']." ".$AR_DT['last_name']; ?></a></td>
                        <td>City : </td>
                        <td><?php echo ($AR_DT['city_name']!='')? $AR_DT['city_name']:""; ?></td>
                        <td>Status : </td>
                        <td><?php echo ($AR_DT['pan_sts'])? $AR_DT['pan_sts']:"Fresh"; ?> <br>
                          <strong>on</strong> <?php echo ($AR_DT['pan_sts'])? getDateFormat($AR_DT['pan_sts_date'],"d D M, Y h:i"):getDateFormat($AR_DT['date_time'],"d D M, Y h:i"); ?></td>
                        <td rowspan="3"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Status <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                              <li> <a  onClick="return confirm('Make sure , want to change pancard status?')"  href="<?php echo generateSeoUrlAdmin("member","pancardapp",array("pan_app_id"=>_e($AR_DT['pan_app_id']),"pan_sts"=>_e("Applied for Pancard"))); ?>">Applied for Pancard</a> </li>
                              <li> <a   onClick="return confirm('Make sure , want to change pancard status?')"  href="<?php echo generateSeoUrlAdmin("member","pancardapp",array("pan_app_id"=>_e($AR_DT['pan_app_id']),"pan_sts"=>_e("Dispatch"))); ?>">Dispatch</a> </li>
                              <li> <a   onClick="return confirm('Make sure , want to change pancard status?')"  href="<?php echo generateSeoUrlAdmin("member","pancardapp",array("pan_app_id"=>_e($AR_DT['pan_app_id']),"pan_sts"=>_e("Delivered"))); ?>">Delivered</a> </li>
                              <li> <a   onClick="return confirm('Make sure , want to change pancard status?')"  href="<?php echo generateSeoUrlAdmin("member","pancardapp",array("pan_app_id"=>_e($AR_DT['pan_app_id']),"pan_sts"=>_e("Rejected"))); ?>">Rejected</a> </li>
                            </ul>
                          </div>
                          
                          <!--<div class="clearfix">&nbsp;</div>
						
					  	<div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                          <ul class="dropdown-menu dropdown-default">
						  	
						
							
							<li> <a class="open_pan_modal"  alt="<?php echo _e($AR_DT['pan_app_id']); ?>" href="javascript:void(0)">Update Pan No</a> </li>
						
                          </ul>
                        </div>--></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['block_sts']=="Y")? "text-danger":""; ?>">
                        <td>Mobile : </td>
                        <td><?php echo $AR_DT['member_mobile']; ?></td>
                        <td>Email Address : </td>
                        <td><?php echo $AR_DT['member_email']; ?></td>
                        <td>PinCode : </td>
                        <td><?php echo $AR_DT['pin_code']; ?></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['block_sts']=="Y")? "text-danger":""; ?>">
                        <td>Gender : </td>
                        <td><?php echo DisplayText("GENDER_".$AR_DT['gender']); ?></td>
                        <td>Lamdmark : </td>
                        <td><?php echo $AR_DT['current_address']." ".$AR_DT['city_name']." ".$AR_DT['state_name']; ?></td>
                        <td>Date of Applied : </td>
                        <td><?php echo DisplayDate($AR_DT['date_time']); ?></td>
                      </tr>
                      <tr>
                        <td colspan="8" class="center"><hr class="divider">
                          </hr></td>
                      </tr>
                      <?php $Ctrl++; endforeach; }else{ ?>
                      <tr>
                        <td colspan="8" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
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
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("member","pancardapp","");  ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Name   :</label>
              <div class="col-sm-7">
                <input id="form-field-1" placeholder="Name / Email" name="fullname"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['fullname']; ?>">
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
  <div id="update-pan-card" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="smaller lighter blue no-margin">Update</h3>
        </div>
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("member","pancardapp","");  ?>" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Pan No   :</label>
              <div class="col-sm-7">
                <input id="form-field-1" placeholder="Pan No" name="pan_no"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['pan_no']; ?>" maxlength="10">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Upload Pancard   :</label>
              <div class="col-sm-7">
                <input id="file_passport" name="file_passport" class="form-control imageFormat" type="file">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="pan_app_id" id="pan_app_id" value="">
            <button type="submit" name="updatePancard" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check"></i> Update </button>
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
<script type="text/javascript" src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
		 $(".imageFormat").on('change',function(){
			$in=$(this);
			 var fileUpload = $in[0];
				var FileType = ImageFile($in.val());
				if(FileType==0){
					alert('Please select a correct file format(.png/.jpg/.jpeg/.gif) !!!');
					$in.val("");
				}
		});
	});
</script>
</body>
</html>
