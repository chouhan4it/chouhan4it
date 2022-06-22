<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['user_id']!=''){
	$member_id = _d($_REQUEST['member_id']);	
	$StrWhr .=" AND tcd.member_id = '".$member_id."'";
	$SrchQ .="&member_id=$member_id";
}

if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = FCrtRplc($_REQUEST['from_date']);
	$to_date = FCrtRplc($_REQUEST['to_date']);
	$StrWhr .=" AND ( DATE(tcd.date_time) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."')";
	$SrchQ .="&from_date=$from_date&to_date=$to_date";
}

$QR_MEM = "SELECT tcd.*,  CONCAT_WS(' ',tm.first_name,tm.last_name) AS  full_name, tm.user_id,
		   CONCAT_WS(' ',tmf.first_name,tmf.last_name) AS  from_full_name,   tmf.user_id AS from_user_id
		   FROM  tbl_cmsn_direct AS tcd	
		  
		   LEFT JOIN  tbl_members AS tm  ON tm.member_id=tcd.member_id
		   LEFT JOIN  tbl_members AS tmf  ON tmf.member_id=tcd.from_member_id
		   WHERE 1
		   $StrWhr 
		   ORDER BY tcd.direct_id DESC";
$PageVal = DisplayPages($QR_MEM, 100, $Page, $SrchQ);

$QR_EXPORT = "SELECT CONCAT_WS(' ',tm.first_name,tm.last_name) AS FROM_MEMBER, tm.user_id AS FROM_USER_ID,
			tmt.user_id AS TO_USER_ID, CONCAT_WS(' ',tmt.first_name,tmt.last_name) AS TO_MEMBER, 
			tcd.date_time AS DATE_TIME,
			tcd.total_income AS TOTAL_INCOME,  
			tcd.admin_charge AS ADMIN_CHARGE,  
			tcd.tds_charge AS TDS_CHARGE,  
			tcd.net_income AS NET_INCOME, 
			tcd.date_time AS DATE_TIME ,
			tm.ifc_code , 
			tm.branch, 
			CONCAT_WS(':','AC',tm.account_number) AS account_no, 
			tm.bank_name 
			FROM tbl_cmsn_direct AS tcd 
			LEFT JOIN tbl_members AS tm ON tcd.from_member_id=tm.member_id
			LEFT JOIN tbl_members AS tmt ON tmt.member_id=tm.member_id
			WHERE 1 $StrWhr ORDER BY tcd.direct_id DESC";
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
          <h1> Bonus <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Direct Income </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-md-4">
            <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("bonus","directincome",""); ?>">
              <b>User Id </b>
              <div class="form-group">
                <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id" style="width:200px;" />
                <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
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
              <input class="btn btn-primary btn-sm m-t-n-xs" value=" Search " type="submit">
              <a href="javascript:void(0)" onClick="window.location.href='?'" class="btn btn-danger btn-sm m-t-n-xs" value=" Reset ">Back</a>
              <a href="<?php echo generateSeoUrlAdmin("excel","directincome","") ?>" class="btn btn-warning btn-sm m-t-n-xs" value=" Download ">Download</a>
            </form>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr role="row">
                <td><strong>Srl # </strong></td>
                <td><strong>User</strong></td>
                <td><strong>From User</strong></td>
                <td><strong>Date</strong></td>
                <td ><strong> Package Amount</strong></td>
                <td ><strong>Total Income</strong></td>
                <td><strong>Admin Charge</strong></td>
                <td><strong>Tds Charge</strong></td>
                <td><strong>Net Income</strong></td>
              </tr>
              <?php 
                        if($PageVal['TotalRecords'] > 0){
                        $Ctrl = $PageVal['RecordStart']+1;
                        foreach($PageVal['ResultSet'] as $AR_DT):
                        ?>
              <tr class="" style="cursor:pointer">
                <td><?php echo $Ctrl;?></td>
                <td><?php echo ($AR_DT['full_name']);?>&nbsp; [
                  <?php  echo $AR_DT['user_id']; ?>
                  ]</td>
                <td><?php echo ($AR_DT['from_full_name']);?>&nbsp; [
                  <?php  echo $AR_DT['from_user_id']; ?>
                  ]</td>
                <td>&nbsp;<?php echo DisplayDate($AR_DT['date_time']);?></td>
                <td><?php echo number_format($AR_DT['total_collection'],2);?></td>
                <td><?php echo number_format($AR_DT['total_income'],2);?></td>
                <td><?php echo number_format($AR_DT['admin_charge'],2);?></td>
                <td><?php echo number_format($AR_DT['tds_charge'],2);?></td>
                <td><?php echo number_format($AR_DT['net_income'],2);?></td>
              </tr>
              <?php 
						$Ctrl++;
						endforeach; ?>
              <?php }else{?>
              <tr>
                <td colspan="9" align="center" class="errMsg">No record found</td>
              </tr>
              <?php } ?>
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
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</html>
