<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['wallet_id']!=''){
	$wallet_id = FCrtRplc($_REQUEST['wallet_id']);
	$StrWhr .=" AND tft.wallet_id='$wallet_id'";
	$SrchQ .="&wallet_id=$wallet_id";
}
if($_REQUEST['member_id']!=''){
	$member_id = _d($_REQUEST['member_id']);
	$StrWhr .=" AND tft.to_member_id='".$member_id."'";
	$SrchQ .="&user_id=$user_id";
}
if($_REQUEST['trns_type']!=''){
	$trns_type = FcrtRplc($_REQUEST['trns_type']);
	$StrWhr .=" AND tft.trns_type='".$trns_type."'";
	$SrchQ .="&trns_type=$trns_type";
}

$QR_PAGES = "SELECT tft.* , tmt.user_name AS user_id , CONCAT_WS(' ',tmt.first_name,tmt.last_name) AS full_name,
			tmf.user_id AS from_user_id , CONCAT_WS(' ',tmf.first_name,tmf.last_name) AS from_full_name
			FROM tbl_fund_transfer AS tft 
			LEFT JOIN tbl_members AS tmt ON tmt.member_id=tft.to_member_id
			LEFT JOIN tbl_members AS tmf ON tmf.member_id=tft.from_member_id
			WHERE tft.transfer_id>0 AND tft.to_member_id>0 $StrWhr ORDER BY tft.transfer_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
ExportQuery($QR_PAGES);

$net_deposite = $model->getWalletTrns("Cr",$StrWhr);
$net_payout = $model->getWalletTrns("Dr",$StrWhr);
$net_balance = $net_deposite-$net_payout;

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
<?php auto_complete(); ?>
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
          <h1> Financial <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;User Transaction </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12" style="min-height:500px;">
                <div class="row">
                  <div class="col-md-4">
                    <form action="<?php echo generateAdminForm("financial","viewtransactions",""); ?>" method="post" name="form-search" id="form-search">
                      <b>User Id </b>
                      <div class="form-group">
                        <div class="clearfix">
                          <input id="form-field-1" placeholder="User Id" name="user_id"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['user_id']; ?>">
                          <input type="hidden" name="member_id" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>">
                        </div>
                      </div>
                      <b>Transaction Type </b>
                      <div class="form-group">
                        <div class="clearfix">
                          <input type="radio" name="trns_type" id="trns_type"  value="Cr" class="validate[required]">
                          Deposit &nbsp;&nbsp;
                          <input type="radio" name="trns_type" id="trns_type"  value="Dr" class="validate[required]">
                          Withdrawal </div>
                      </div>
                      <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                      <a href="javascript:void(0)" onClick="window.location.href='<?php echo generateSeoUrlAdmin("financial","viewtransactions",""); ?>'" class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
                    </form>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 infobox-container">
                    <div class="infobox infobox-orange2">
                      <div class="infobox-chart"> <span class="sparkline" data-values="196,128,202,177,154,94,100,170,224">
                        <canvas height="34" width="44" style="display: inline-block; width: 44px; height: 34px; vertical-align: top;"></canvas>
                        </span> </div>
                      <div class="infobox-data"> <span class="infobox-data-number"><?php echo number_format($net_deposite); ?></span>
                        <div class="infobox-content">Total Wallet</div>
                      </div>
                    </div>
                    <div class="infobox infobox-blue2">
                      <div class="infobox-chart"> <span class="sparkline" data-values="196,128,202,177,154,94,100,170,224">
                        <canvas height="34" width="44" style="display: inline-block; width: 44px; height: 34px; vertical-align: top;"></canvas>
                        </span> </div>
                      <div class="infobox-data"> <span class="infobox-data-number"><?php echo number_format($net_payout); ?></span>
                        <div class="infobox-content">Wallet Used</div>
                      </div>
                    </div>
                    <div class="infobox infobox-red2">
                      <div class="infobox-chart"> <span class="sparkline" data-values="196,128,202,177,154,94,100,170,224">
                        <canvas height="34" width="44" style="display: inline-block; width: 44px; height: 34px; vertical-align: top;"></canvas>
                        </span> </div>
                      <div class="infobox-data"> <span class="infobox-data-number"><?php echo number_format($net_balance); ?></span>
                        <div class="infobox-content">Wallet Unused</div>
                      </div>
                    </div>
                  </div>
                  <div class="vspace-12-sm"></div>
                  <!-- /.col --> 
                </div>
                <div class="clearfix">&nbsp;</div>
                <div>
                  <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th  class="center"> <label class="pos-rel"> Date <span class="lbl"></span> </label>
                        </th>
                        <th>Amount</th>
                        <th >Trans Type </th>
                        <th >From User </th>
                        <th> To User </th>
                        <th>Description</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr>
                        <td class="center"><label class="pos-rel"> <?php echo $AR_DT['trns_date']; ?> <span class="lbl"></span> </label></td>
                        <td><?php echo $AR_DT['trns_amount']; ?> &nbsp;&nbsp;</td>
                        <td><?php echo $AR_DT['trns_for']; ?></td>
                        <td><?php echo $AR_DT['from_full_name']; ?> &nbsp; [<?php echo $AR_DT['from_user_id']; ?>]</td>
                        <td><?php echo $AR_DT['full_name']; ?> &nbsp; [<?php echo $AR_DT['user_id']; ?>]</td>
                        <td><?php echo $AR_DT['trns_remark']; ?></td>
                      </tr>
                      <?php $Ctrl++; endforeach; }else{ ?>
                      <tr>
                        <td colspan="6" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No transaction found</td>
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
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=AGENT";
});
</script>
</body>
</html>
