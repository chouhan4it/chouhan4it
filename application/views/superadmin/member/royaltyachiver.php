<?php defined('BASEPATH') OR exit('No direct script access allowed');
	$model = new OperationModel();
	$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
	
	
	if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
		$from_date = InsertDate($_REQUEST['from_date']);
		$to_date = InsertDate($_REQUEST['to_date']);
		$StrWhr .=" AND DATE(tmr.date_time) BETWEEN '$from_date' AND '$to_date'";
		$SrchQ .="&from_date=$from_date&to_date=$to_date";
	}
	
	
	if(_d($_REQUEST['member_id'])>0){
		$member_id = _d($_REQUEST['member_id']);
		$StrWhr .=" AND tmr.member_id='".$member_id."'";
		$SrchQ .="&member_id=$member_id";
	}
	
	if($_REQUEST['royalty_id']>0){
		$royalty_id = FCrtRplc($_REQUEST['royalty_id']);
		$StrWhr .=" AND tmr.royalty_id='".$royalty_id."'";
		$SrchQ .="&royalty_id=$royalty_id";
	}

	
	$QR_PAGES= "SELECT tmr.*, tsr.royalty_name, tm.user_id, tm.first_name, tm.last_name
				FROM ".prefix."tbl_mem_royalty AS tmr 
				LEFT JOIN tbl_setup_royalty AS tsr ON tsr.royalty_id=tmr.royalty_id
			    LEFT JOIN tbl_members AS tm ON tm.member_id=tmr.member_id
			    WHERE 1 $StrWhr ORDER BY tmr.date_time DESC";
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
<style type="text/css">
.table-wrapper {
	background: #eeeded;
	border-radius: 10px;
	width: 100%;
	height: auto;
	padding: 10px;
	box-sizing: border-box;
	border: 1px solid #CCC;
	margin: 30px 0;
}
table {
	background-color: #f9f9f9;
	border-collapse: collapse;
	color: black;
	margin: 0px !important;
	width: 100%;
	text-align: left
}
table tr:nth-child(odd) {
	background-color: #fefbf0;
}
table tr th {
/*background-color: #E0CEF2;
		border-color: #B1A3BF;*/
}
table tr, table tr td {
/*line-height: 40px !important;*/
}
table tr th, table tr td {
	border: 1px solid #aaa;
}
 @media only screen and (max-width:992px) {
/* Force table to not be like tables anymore */
#no-more-tables table, #no-more-tables thead, #no-more-tables tbody, #no-more-tables th, #no-more-tables td, #no-more-tables tr {
	display: block;
}
/* Hide table headers (but not display: none;, for accessibility)  */
#no-more-tables thead tr {
	position: absolute;
	top: -9999px;
	left: -9999px;
}
/* Behave like a "row" */
#no-more-tables td:not(:first-child) {
	position: relative;
	padding-left: 52%;
	text-align: left;
	border-collapse: collapse;
	border-bottom: 0px;
}
#no-more-tables td:first-child {
	text-align: center;
	border-collapse: collapse;
	border-bottom: 0px;/*background-color: #359AF2;
		color: white;
		font-weight: bold*/
}
#no-more-tables td:last-child {
	border: 1px solid #aaa;
}
#no-more-tables tr {
	margin-bottom: 10px;
}
/* Now like a table header */
#no-more-tables td:not(:first-child):before {
	position: absolute;
	left: 0;
	top: 0;
	height: 100%;
	width: 48%;
	padding-left: 2%;
	/*background-color: #f2f7fc;*/
	border-right: 1px solid #aaa;
	white-space: wrap;
	text-align: left;
	font-weight: bold;
	content: attr(data-title);
}
}
</style>
</head>
<body class="no-skin">
<?= $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?= $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;Roaylty Achivers </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-4" >
                
                <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("member","royaltyachiver",""); ?>">
              <b>User Id </b>
              <div class="form-group">
                <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id" style="width:200px;" />
                <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
              </div>
              <b>Royalty Type </b>
              <div class="form-group">
              	<select name="royalty_id" class="form-control" id="royalty_id" >
                	<option  value="">---select---</option>
                    <?php echo DisplayCombo($_REQUEST['royalty_id'],"ROYALTY_LIST"); ?>
                </select>
               
              </div>
              <b>From Date </b>
              <div class="form-group">
                <div class="input-group">
                                        <input class="form-control validate[required] date-picker col-md-3" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
              </div>
              <b>To Date</b>
              <div class="form-group">
                <div class="input-group">
                                        <input class="form-control  validate[required] date-picker" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                                        <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span> </div>
              </div>
              <div class="clearfix">&nbsp;</div>
              <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
              <a href="javascript:void(0)" onClick="window.location.href='?'" class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
            </form>
				</div>
                </div>
                <div class="clearfix">&nbsp;</div>
        			<div class="row">     
                    <div class="col-xs-12">          
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                
                  <table id="no-more-tables" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr role="row">
                        <th  class="">Sr No. </th>
                        <th  class=""> Date</th>
                        <th  class="">Member Id</th>
                        <th  class="">Member Name</th>
                        <th  class="">Royalty</th>
                        <th  class="">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
						if($PageVal['TotalRecords'] > 0){
						$Ctrl=$PageVal['RecordStart']+1;
							foreach($PageVal['ResultSet'] as $AR_DT):
						?>
                      <tr class="odd" role="row">
                        <td class=""><?php echo $Ctrl; ?></td>
                        <td><?php echo DisplayDate($AR_DT['date_time']); ?></td>
                        <td><?php echo $AR_DT['user_id']; ?></td>
                        <td><?php echo $AR_DT['first_name']; ?> <?php echo $AR_DT['last_name']; ?></td>
                        <td><?php echo $AR_DT['royalty_name']; ?></td>
                        <td><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                             
                              <li> <a onClick="return confirm('Make sure , you want to delete this achivers from <?php echo $AR_DT['royalty_name']; ?>?')" href="<?php echo generateSeoUrlAdmin("member","royaltyachiver",array("member_id"=>_e($AR_DT['member_id']),"action_request"=>"DELETE","royalty_id"=>_e($AR_DT['royalty_id']))); ?>">Delete</a> </li>
                              
                            </ul>
                          </div></td>
                      </tr>
                      <?php $Ctrl++; endforeach;
						}
						 ?>
                    </tbody>
                  </table>
                
              </div>
            </div>
            <div class="clearfix">&nbsp;</div>
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
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
  <?= $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
  <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse"> <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i> </a> </div>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); auto_complete(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		});
		
	});
</script>
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</body>
</html>
