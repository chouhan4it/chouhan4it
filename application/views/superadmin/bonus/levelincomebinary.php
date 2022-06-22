<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}


if(_d($_REQUEST['member_id'])>0){
	$member_id = _d($_REQUEST['member_id']);
	$StrWhr .=" AND tcd.member_id='".$member_id."'";
	$SrchQ .="&member_id=$member_id";
}
if($_REQUEST['process_id']!=''){
	$process_id = FCrtRplc($_REQUEST['process_id']);
	$StrWhr .=" AND tclbml.process_id='$process_id'";
	$SrchQ .="&process_id=$process_id";
}

$QR_PAGES= "SELECT tclbml.*, tmf.user_id, CONCAT_WS(' ',tmf.first_name,tmf.last_name) AS full_name
			FROM tbl_cmsn_lvl_benefit_mstr_lvl AS tclbml 
			LEFT JOIN tbl_members AS tmf ON tclbml.member_id=tmf.member_id
			WHERE  tclbml.process_id>0
			 $StrWhr 
			ORDER BY tclbml.process_id DESC";
$PageVal = DisplayPages($QR_PAGES, 200, $Page, $SrchQ);

$QR_EXPORT = "SELECT CONCAT_WS(' ',tm.first_name,tm.last_name) AS FROM_MEMBER, tm.user_id AS FROM_USER_ID,
			tclbml.date_time AS DATE_TIME,
			tclbml.total_income AS TOTAL_INCOME,  
			tclbml.admin_charge AS ADMIN_CHARGE,  
			tclbml.tds_charge AS TDS_CHARGE,  
			tclbml.net_income AS NET_INCOME, 
			tclbml.process_id AS PROCESS_NO,
			tm.ifc_code , 
			tm.branch, 
			CONCAT_WS(':','AC',tm.account_number) AS account_no, 
			tm.bank_name 
			FROM tbl_cmsn_lvl_benefit_mstr_lvl AS tclbml 
			LEFT JOIN tbl_members AS tm ON tclbml.member_id=tm.member_id
			WHERE 1 $StrWhr 
			ORDER BY tclbml.process_id DESC";
ExportQuery($QR_EXPORT);
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
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<?php auto_complete(); ?>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
<style type="text/css">
.danger_alert {
	background-color: #f2dede;
	border-color: #ebccd1;
	color: #a94442;
}
.success_alert {
	background-color: #dff0d8;
	border-color: #d6e9c6;
	color: #3c763d;
}
.pointer {
	cursor: pointer;
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
          <h1> Matching <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Sponsor Bonus </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-md-4">
            <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("bonus","levelincomebinary",""); ?>">
              <b>User Id </b>
              <div class="form-group">
                <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id" style="width:200px;" />
                <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
              </div>
              <b>Process No </b>
                      <div class="form-group">
                        <div class="clearfix">
                          <select class="col-xs-12 col-sm-6 validate[required]" id="process_id" name="process_id" >
                            <option value="">----select cycle----</option>
                            <?php  DisplayCombo($_REQUEST['process_id'],"BINARY_PROCESS");  ?>
                          </select>
                        </div>
                      </div>
              <div class="clearfix">&nbsp;</div>
              <input class="btn btn-primary btn-sm m-t-n-xs" value=" Search " type="submit">
              <a href="javascript:void(0)" onClick="window.location.href='?'" class="btn btn-danger btn-sm m-t-n-xs" value=" Reset ">Back</a>
              <a href="<?php echo generateSeoUrlAdmin("excel","levelincome","") ?>" class="btn btn-warning btn-sm m-t-n-xs" value=" Download ">Download</a>
            </form>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12">
            <table id="no-more-tables" class="table table-striped table-bordered table-hover">
              <thead>
                <tr role="row">
                  <th  class="sorting">Sr. No </th>
                  <th  class="sorting">Member Id </th>
                  <th  class="sorting">Member Name </th>
                  <th  class="sorting">Cycle No</th>
                  <th  class="sorting">Level  Income </th>
                  <th  class="sorting">Tds Charge</th>
                  <th  class="sorting">Admin Charge </th>
                  <th  class="sorting">Net Income</th>
                  <th  class="sorting">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?php 
						if($PageVal['TotalRecords'] > 0){
						$Ctrl=$PageVal['RecordStart']+1;
							foreach($PageVal['ResultSet'] as $AR_DT):
						?>
                <tr class="odd" role="row">
                  <td><?php echo $Ctrl; ?></td>
                  <td><?php echo $AR_DT['user_id']; ?></td>
                  <td><?php echo $AR_DT['full_name']; ?></td>
                  <td>Cycle No <?php echo $AR_DT['process_id']; ?></td>
                  <td><?php echo $AR_DT['total_income'];?></td>
                  <td><?php echo $AR_DT['tds_charge'];?></td>
                  <td><?php echo $AR_DT['admin_charge']; ?></td>
                  <td><?php echo $AR_DT['net_income']; ?></td>
                  <td><a class="label label-info modal-level" 
                            member_id="<?php echo $AR_DT['member_id']; ?>" process_id="<?php echo $AR_DT['process_id']; ?>"
                            href="javascript:void(0)">View</a></td>
                </tr>
                <?php $Ctrl++; endforeach;
						}
						 ?>
              </tbody>
            </table>
            <ul class="pagination">
              <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
            </ul>
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
  <div class="modal" id="modal-level-detail"  aria-hidden="true">
    <div class="modal-dialog" style="width:800px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">Sponsor Income Detail</h4>
        </div>
        <div class="modal-body" >
          <div class="login-box" >
            <div id="row">
              <div class="input-box frontForms">
                <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <div class="load-level"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
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
	$(function(){
		$(".modal-level").on('click',function(){
			var process_id = $(this).attr("process_id");
			var member_id = $(this).attr("member_id");
			
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			$.post(URL_GET,{switch_type:"SPONSOR_LEVEL_INCOME",process_id:process_id,member_id:member_id},function(JsonEval){
				$(".load-level").html(JsonEval);
				$("#modal-level-detail").modal({
					backdrop: 'static',
					keyboard: false
				});
			});
			
		});
	});
</script>
</body>
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</html>
