<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if(_d($_REQUEST['member_id'])>0){
	$member_id = _d($_REQUEST['member_id']);
	$StrWhr .=" AND tft.to_member_id='".$member_id."'";
	$SrchQ .="&member_id=$member_id";
}



if($_REQUEST['trans_no']!=''){
	$trans_no = FCrtRplc($_REQUEST['trans_no']);
	$StrWhr .=" AND tft.trans_no='$trans_no'";
	$SrchQ .="&trans_no=$trans_no";
}


if($_REQUEST['pan_no']!=''){
	$pan_no = FCrtRplc($_REQUEST['pan_no']);
	$StrWhr .=" AND tm.pan_no='$pan_no'";
	$SrchQ .="&pan_no=$pan_no";
}

if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = FCrtRplc($_REQUEST['date_from']);
	$date_to = FCrtRplc($_REQUEST['date_to']);
	$StrWhr .=" AND ( DATE(tft.trns_date) BETWEEN '".InsertDate($date_from)."' AND '".InsertDate($date_to)."')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}


if($_REQUEST['trns_date_from']!='' && $_REQUEST['trns_date_to']!=''){
	$trns_date_from = FCrtRplc($_REQUEST['trns_date_from']);
	$trns_date_to = FCrtRplc($_REQUEST['trns_date_to']);
	$StrWhr .=" AND ( DATE(tft.bank_trans_date) BETWEEN '".InsertDate($trns_date_from)."' AND '".InsertDate($trns_date_to)."')";
	$SrchQ .="&trns_date_from=$trns_date_from&trns_date_to=$trns_date_to";
}

$QR_PAGES="SELECT tft.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id , tm.pan_no
		   FROM tbl_fund_transfer AS tft 
		   LEFT JOIN tbl_members AS tm ON tft.to_member_id=tm.member_id 
		   WHERE tft.trns_for LIKE 'WTD' AND tft.trns_status IN('C')
		   $StrWhr 
		   ORDER BY tft.date_time DESC";
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
tr > td {
	font-size: 11px;
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
          <h1> Report <small> <i class="ace-icon fa fa-angle-double-right"></i>PAYMENT REPORT</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-4">
            <div class="clearfix">
              <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("report","paymentreport",""); ?>">
                <b>ORDER ID</b>
                <div class="form-group">
                 <input name="trans_no" type="text" class="form-control col-xs-12 col-sm-6" id="trans_no" value="<?php echo $_REQUEST['trans_no']; ?>" placeholder="Order Id"  />
                </div>
                <div class="clearfix">&nbsp;</div>
                 <b>PAN NO</b>
                <div class="form-group">
                 <input name="pan_no" type="text" class="form-control col-xs-12 col-sm-6" id="pan_no" value="<?php echo $_REQUEST['pan_no']; ?>" placeholder="Pan No"  />
                </div>
                <div class="clearfix">&nbsp;</div>
                <b>USER ID</b>
                <div class="form-group">
                  <input name="user_id" type="text" class="form-control col-xs-12 col-sm-6" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id"  />
                  <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                </div>
                <div class="clearfix">&nbsp;</div>
                <b>FROM DATE</b>
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-12 col-sm-6  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="FROM DATE" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <b>TO DATE</b>
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-12 col-sm-6  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                
                <b>TRANSACTION FROM DATE</b>
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-12 col-sm-6  validate[required] date-picker" name="trns_date_from" id="trns_date_from" value="<?php echo $_REQUEST['trns_date_from']; ?>" placeholder="FROM DATE" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <b>TRANSACTION TO DATE</b>
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-12 col-sm-6  validate[required] date-picker" name="trns_date_to" id="trns_date_to" value="<?php echo $_REQUEST['trns_date_to']; ?>" placeholder="Date To" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <input class="btn btn-sm btn-primary m-t-n-xs" value=" Search " type="submit">
                <a href="<?php echo generateSeoUrlAdmin("report","paymentreport",""); ?>"  class="btn btn-sm btn-danger m-t-n-xs" value=" Reset ">Reset</a>
              </form>
            </div>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              
                <tr>
                  <td><strong>S.NO </strong></td>
                  <td><strong>DATE</strong></td>
                  <td><strong>ORDER ID</strong></td>
                  <td><strong>USER ID</strong></td>
                  <td><strong>USER NAME</strong></td>
                  <td><strong>PAN </strong></td>
                  <td><strong>REQUESTED AMOUNT</strong></td>
                  <td><strong>TRANSACTION NO </strong></td>
                  <td><strong>TRANSACTION DATE</strong></td>
                  <td ><strong>ACCOUNT NO</strong></td>
                  <td ><strong>IFSC CODE</strong></td>
                  <td ><strong>BANK NAME</strong></td>
                  <td ><strong>ACCOUNT NAME</strong></td>
                </tr>
              
              
				<?php 
                if($PageVal['TotalRecords'] > 0){
                    $Ctrl=$PageVal['RecordStart']+1;
                    foreach($PageVal['ResultSet'] as $AR_DT):
                        
                ?>
                <tr>
                  <td><?php echo $Ctrl; ?> </td>
                  <td><?php echo DisplayDate($AR_DT['trns_date']); ?></td>
                  <td><?php echo $AR_DT['trans_no']; ?></td>
                  <td><?php echo $AR_DT['user_id']; ?></td>
                  <td><?php echo $AR_DT['full_name']; ?></td>
                  <td><?php echo $AR_DT['pan_no']; ?></td>
                  <td><?php echo number_format($AR_DT['initial_amount'],2); ?></td>
                  <td><?php echo $AR_DT['bank_trans_no']; ?></td>
                  <td><?php echo DisplayDate($AR_DT['bank_trans_date']); ?></td>
                  <td><?php echo $AR_DT['account_no']; ?></td>
                  <td><?php echo $AR_DT['ifsc_code']; ?></td>
                  <td><?php echo $AR_DT['bank_name']; ?></td>
                  <td><?php echo $AR_DT['account_name']; ?></td>
                </tr>
                <?php $Ctrl++; endforeach; }else{ ?>
                <tr>
                  <td colspan="13" class="text-danger">No transaction found </td>
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
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
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