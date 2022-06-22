<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['fullname']!=''){
	$fullname = FCrtRplc($_REQUEST['fullname']);
	$StrWhr .=" AND ( tmr.fldvFName LIKE '%$fullname%' OR tm.fldvLName LIKE '%$fullname%' OR tm.fldvEmail LIKE '%$fullname%' )";
	$SrchQ .="&fullname=$fullname";
}
if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	
	$StrWhr .=" AND ( DATE(tmr.flddDate) BETWEEN '$date_from' AND '$date_to' )";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$QR_PAGES="SELECT tmr.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_name AS spsr_user_id 
		 FROM tbl_tmp_register AS tmr	
		 LEFT JOIN tbl_members AS tmsp  ON tmsp.member_id=tmr.fldiSpmId
		 WHERE tmr.fldvMVerify='0' AND tmr.fldiRegId NOT IN(SELECT reg_id FROM tbl_members) 
		 $StrWhr 
		 ORDER BY tmr.fldiRegId DESC";
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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; In-complete registration </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a  href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div class="row">
                  <div class="col-md-6">
                    <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("member","incompletelist",""); ?>" method="post">
                      <div class="modal-body">
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Name / Email Address  :</label>
                          <div class="col-sm-7">
                            <input id="form-field-1" placeholder="Name / Email" name="fullname"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['fullname']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Date From  :</label>
                          <div class="col-sm-4">
                            <div class="input-group">
                              <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="Date From" type="text"  />
                              <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Date To  :</label>
                          <div class="col-sm-4">
                            <div class="input-group">
                              <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                              <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> </label>
                          <div class="col-sm-9">
                            <button type="submit" class="btn btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
                            <button type="button" class="btn btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
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
                        <td width="22" rowspan="3" class="center"><label class="pos-rel"> <?php echo $Ctrl; ?> <span class="lbl"></span> </label></td>
                        <td width="112">Full Name : </td>
                        <td width="148"><a href="javascript:void(0)"><?php echo $AR_DT['fldvFName']." ".$AR_DT['fldvLName']; ?></a></td>
                        <td width="126">Sponsor ID : </td>
                        <td width="120"><?php echo ($AR_DT['spsr_user_id']!='')? $AR_DT['spsr_user_id']:"Admin"; ?></td>
                        <td width="140">Date of Joining : </td>
                        <td width="164"><?php echo DisplayDate($AR_DT['flddDate']); ?></td>
                        <td width="90" rowspan="3"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                              <li> <a target="_blank" href="<?php echo generateSeoUrl("account","mobileverify",array("fldiRegId"=>_e($AR_DT['fldiRegId']),"approved"=>_e("admin"))); ?>">Verify It</a> </li>
                              <li> <a href="<?php echo generateSeoUrlAdmin("member","incompletelist",array("member_id"=>_e($AR_DT['member_id']))); ?>">Delete</a> </li>
                            </ul>
                          </div></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['block_sts']=="Y")? "text-danger":""; ?>">
                        <td>Mobile : </td>
                        <td><?php echo $AR_DT['fldvMobile']; ?></td>
                        <td>Email Address : </td>
                        <td><?php echo $AR_DT['fldvEmail']; ?></td>
                        <td>Pancard : </td>
                        <td valign="top"><?php echo $AR_DT['fldvPanNO']; ?></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['fldvMCode']=="Y")? "text-danger":""; ?>">
                        <td>Verify Code: </td>
                        <td><?php echo $AR_DT['fldvMCode']; ?></td>
                        <td>City / State : </td>
                        <td><?php echo $AR_DT['fldvCity']; ?>/<?php echo $AR_DT['fldvState']; ?></td>
                        <td>&nbsp;</td>
                        <td valign="top">&nbsp;</td>
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
			todayHighlight: true
		});
	});
</script>
</body>
</html>
