<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['fullname']!=''){
	$fullname = FCrtRplc($_REQUEST['fullname']);
	$StrWhr .=" AND ( tm.first_name LIKE '%$fullname%' OR tm.last_name LIKE '%$fullname%' OR tm.member_email LIKE '%$fullname%' OR tm.user_id LIKE '%$fullname%' )";
	$SrchQ .="&fullname=$fullname";
}
if($_REQUEST['d_o_b_day']>0){
	$d_o_b_day = FCrtRplc($_REQUEST['d_o_b_day']);
	$StrWhr .=" AND ( DAY(tm.date_of_birth) = '".$d_o_b_day."' )";
	$SrchQ .="&d_o_b_day=$d_o_b_day";
}

if($_REQUEST['d_o_b_month']>0){
	$d_o_b_month = FCrtRplc($_REQUEST['d_o_b_month']);
	$StrWhr .=" AND ( MONTH(tm.date_of_birth) = '".$d_o_b_month."' )";
	$SrchQ .="&d_o_b_month=$d_o_b_month";
}

if($_REQUEST['d_o_a_day']>0){
	$d_o_a_day = FCrtRplc($_REQUEST['d_o_a_day']);
	$StrWhr .=" AND ( DAY(tm.anniversary_date) = '".$d_o_a_day."' )";
	$SrchQ .="&d_o_a_day=$d_o_a_day";
}

if($_REQUEST['d_o_a_month']>0){
	$d_o_a_month = FCrtRplc($_REQUEST['d_o_a_month']);
	$StrWhr .=" AND ( MONTH(tm.anniversary_date) = '".$d_o_a_month."' )";
	$SrchQ .="&d_o_a_month=$d_o_a_month";
}

$QR_PAGES="SELECT tm.first_name, tm.last_name, tm.user_id, tm.city_name, tm.state_name, tm.date_of_birth, tm.anniversary_date, tm.date_join
		 FROM tbl_members AS tm	
		 LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
		 WHERE tm.delete_sts>0    $StrWhr ORDER BY tm.member_id ASC";
$PageVal = DisplayPages($QR_PAGES, 500, $Page, $SrchQ);


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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; DOB & Anniversary date list </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="row">
                  <div class="col-md-8">
                    <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("member","customlist",""); ?>" method="post">
                      <div class="modal-body">
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Name / Email Address  :</label>
                          <div class="col-sm-7">
                            <input id="form-field-1" placeholder="Name / Email" name="fullname"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['fullname']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Date of Birth  :</label>
                          <div class="col-sm-4">
                            <select name="d_o_b_day" class="form-control"  id="d_o_b_day">
                              <option value="">---day---</option>
                              <?php echo DisplayCombo($_REQUEST['d_o_b_day'],"DAY"); ?>
                            </select>
                          </div>
                          <div class="col-sm-4">
                            <select name="d_o_b_month" class="form-control" id="d_o_b_month">
                              <option value="0">---select month---</option>
                              <?php echo DisplayCombo($_REQUEST['d_o_b_month'],"MONTH"); ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Date of Anniversaries  :</label>
                          <div class="col-sm-4">
                            <select name="d_o_a_day" class="form-control"  id="d_o_a_day">
                              <option value="">---day---</option>
                              <?php echo DisplayCombo($_REQUEST['d_o_a_day'],"DAY"); ?>
                            </select>
                          </div>
                          <div class="col-sm-4">
                            <select name="d_o_a_month" class="form-control" id="d_o_a_month">
                              <option value="0">---select month---</option>
                              <?php echo DisplayCombo($_REQUEST['d_o_a_month'],"MONTH"); ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> </label>
                          <div class="col-sm-9">
                            <button type="submit" class="btn btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
                            <button type="button" class="btn btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                            <a href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" class="btn btn-danger"> <i class="ace-icon fa fa-database"></i> Excel</a> </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div style="min-height:500px;">
                  <table id="" class="table">
                    <thead>
                      <tr >
                        <td height="23">Srl No </td>
                        <td>Name</td>
                        <td>User Id </td>
                        <td>City </td>
                        <td>State</td>
                        <td>D.O.B</td>
                        <td>Aniversary Date </td>
                        <td>D.O.J</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						
			       ?>
                      <tr class="">
                        <td width="112"><?php echo $Ctrl; ?></td>
                        <td width="148"><?php echo $AR_DT['first_name']." ".$AR_DT['last_name']; ?></td>
                        <td width="126"><?php echo $AR_DT['user_id']; ?></td>
                        <td width="120"><?php echo $AR_DT['city_name']; ?></td>
                        <td width="140"><?php echo $AR_DT['state_name']; ?></td>
                        <td width="164"><?php echo ($AR_DT['date_of_birth']); ?></td>
                        <td width="164"><?php echo ($AR_DT['anniversary_date']); ?></td>
                        <td width="164"><?php echo ($AR_DT['date_join']); ?></td>
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
