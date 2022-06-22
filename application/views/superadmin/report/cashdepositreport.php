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



if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = FCrtRplc($_REQUEST['date_from']);
	$date_to = FCrtRplc($_REQUEST['date_to']);
	$StrWhr .=" AND ( DATE(tft.date_time) BETWEEN '".InsertDate($date_from)."' AND '".InsertDate($date_to)."')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}


$QR_PAGES = "SELECT tft.* , tmt.user_name AS user_id , CONCAT_WS(' ',tmt.first_name,tmt.last_name) AS full_name,
			tmf.user_id AS from_user_id , CONCAT_WS(' ',tmf.first_name,tmf.last_name) AS from_full_name
			FROM tbl_fund_transfer AS tft 
			LEFT JOIN tbl_members AS tmt ON tmt.member_id=tft.to_member_id
			LEFT JOIN tbl_members AS tmf ON tmf.member_id=tft.from_member_id
			WHERE tft.transfer_id>0 AND tft.to_member_id>0 
			AND tft.trns_for LIKE 'DPT'
			$StrWhr 
			ORDER BY tft.transfer_id DESC";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
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
          <h1> Report <small> <i class="ace-icon fa fa-angle-double-right"></i> CASh DEPOSIT HISTORY</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-4">
            <div class="clearfix">
              <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("report","cashdepositreport",""); ?>">
                <b>USER ID</b>
                <div class="form-group">
                  <input name="user_id" type="text" class="form-control col-xs-12 col-sm-6" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id"  />
                  <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                </div>
                <div class="clearfix">&nbsp;</div>
                <b>TRANSACTION NO</b>
                <div class="form-group">
                  <input name="trans_no" type="text" class="form-control" id="trans_no" value="<?php echo $_REQUEST['trans_no']; ?>" placeholder="TRANSACTION NO"  />
                </div>
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
                <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                <a href="<?php echo generateSeoUrlAdmin("report","cashdepositreport",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
              </form>
            </div>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr class="">
                <td align="left"><strong>SR NO</strong></td>
                <td align="right"><strong>USER ID</strong></td>
                <td align="right"><strong>USER NAME</strong></td>
                <td align="right"><strong>AMOUNT</strong></td>
                <td align="right"><strong>DEPOSIT MODE</strong></td>
                <td align="right"><strong>DEPOSIT DATE</strong></td>
                <td align="right"><strong>TRANSACTION NO</strong></td>
                <td align="right"><strong>RECEIPT</strong></td>
                <td align="right"><strong>APPROVED DATE</strong></td>
                <td align="right"><strong>APPROVAL NO</strong></td>
                <td align="right"><strong>STATUS</strong></td>
                <td align="right"><strong>REJECT  REASON</strong></td>
              </tr>
              <?php 
                    if($PageVal['TotalRecords'] > 0){
                    $Ctrl = $PageVal['RecordStart']+1;
                    foreach($PageVal['ResultSet'] as $AR_DT):
					
					
               ?>
              <tr>
                <td><?php echo $Ctrl; ?></td>
                <td align="right"><?php echo $AR_DT['user_id']; ?></td>
                <td align="right"><?php echo $AR_DT['full_name']; ?></td>
                <td align="right"><?php echo number_format($AR_DT['initial_amount'],2); ?></td>
                <td align="right"><?php echo $AR_DT['payment_type']; ?></td>
                <td align="right"><?php echo DisplayDate($AR_DT['date_time']); ?></td>
                <td align="right"><?php echo $AR_DT['trans_no']; ?></td>
                <td align="right"><a class="text text-success open_receipt"  payment_type="<?php echo $AR_DT['payment_type']; ?>" trans_no="<?php echo $AR_DT['trans_no']; ?>" trns_amount="<?php echo $AR_DT['trns_amount']; ?>" trns_remark="<?php echo $AR_DT['trns_remark']; ?>" trns_date="<?php echo $AR_DT['trns_date']; ?>" href="javascript:void(0)" ref="<?php echo $model->getTransactionReceipt($AR_DT['transfer_id']); ?>">VIEW</a></td>
                <td align="right"><?php echo DisplayDate($AR_DT['status_up_date']); ?></td>
                <td align="right">N/A</td>
                <td align="right"><?php echo DisplayText("DEPOSIT_".$AR_DT['trns_status']); ?></td>
                <td align="right"><?php echo $AR_DT['trns_detail']; ?></td>
              </tr>
              <?php 
			  $Ctrl++;
			  endforeach;
			  ?>
              <?php
       			 }else{
       		  ?>
              <tr>
                <td colspan="14" align="center" class="errMsg">No record found</td>
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
<div id="receipt-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="smaller lighter blue no-margin">Receipt Details</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <form class="form-horizontal"  name="form-page" id="form-page" >
        <div class="modal-body">
          <div class="form-group">
            <div class="col-md-12"> <img src="" id="image_receipt" class="img-responsive"> </div>
          </div>
          <div class="form-group">
            <div class="col-md-12"> Transaction Mode : <span id="div_payment_type"></span> </div>
            <div class="col-md-12"> Transaction No : <span id="div_trans_no"></span> </div>
            <div class="col-md-12"> Transaction Date : <span id="div_trns_date"></span> </div>
            <div class="col-md-12"> Transaction Amount : <span id="div_trns_amount"></span> </div>
            <div class="col-md-12"> Transaction Detail : <span id="div_trns_remark"></span> </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-danger pull-right" data-dismiss="modal"> <i class="ace-icon fa fa-times"></i> Close </button>
          <input type="hidden" name="transfer_id" id="transfer_id" value="">
        </div>
      </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
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
		
		$(".open_receipt").on('click',function(){
			var ref = $(this).attr("ref");
			var div_payment_type = $(this).attr("payment_type");
			var div_trns_amount = $(this).attr("trns_amount");
			var div_trans_no = $(this).attr("trans_no");
			var div_trns_remark = $(this).attr("trns_remark");
			var div_trns_date = $(this).attr("trns_date");
			if(ref!=''){
				$("#image_receipt").attr('src',ref);
				$("#div_payment_type").text(div_payment_type);
				$("#div_trns_amount").text(div_trns_amount);
				$("#div_trans_no").text(div_trans_no);
				$("#div_trns_remark").text(div_trns_remark);
				$("#div_trns_date").text(div_trns_date);
				$('#receipt-modal').modal('show');
				return false;
			}
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