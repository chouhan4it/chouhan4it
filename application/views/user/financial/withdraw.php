<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$wallet_id = $model->getWallet("Payout Wallet");

$NEFT_FEE = $model->getValue("NEFT_FEE");
if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);	
	$StrWhr .=" AND DATE(twt.trns_date) BETWEEN '".$from_date."' AND '".$to_date."'";
	$SrchQ  = "&from_date=$from_date&to_date=$to_date";
}

$LDGR = $model->getCurrentBalance($member_id,$wallet_id,$_REQUEST['from_date'],$_REQUEST['to_date']);
$StrWhr .=" AND tft.to_member_id='".$member_id."'";

$QR_PAGES="SELECT tft.* FROM tbl_fund_transfer AS tft WHERE tft.trns_for LIKE 'Withdrawal' $StrWhr ORDER BY tft.transfer_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container">
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Withdraw Request</h4>
        <p class="text-muted page-title-alt">Your NEFT withdraw request</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
	  
        <div class="card-box">
          <div class="row">
		  <?php echo get_message(); ?>
            <div class="col-md-6">
              <form method="post" action="<?php echo generateMemberForm("financial","withdraw",""); ?>" enctype="multipart/form-data" name="form-page" id="form-page">
                <h3>Available amount on your  wallet: <?php echo CURRENCY; ?> <?php echo $LDGR['net_balance']; ?> </h3>
                <?php if($NEFT_FEE>0){ ?>
                <p>NEFT Request Fees : <?php echo $NEFT_FEE; ?> (%)</p>
                <?php } ?>
                <div class="form-group">
                  <input type="text" placeholder="Amount" name="draw_amount" class="form-control input-xlarge form-half validate[required]" id="draw_amount" value="">
                </div>
                <input name="requestWithdraw" id="button" class="btn btn-sm btn-primary m-t-n-xs" value="Submit Request" type="submit">
              </form>
            </div>
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="table-responsive">
            <table class="table table-actions-bar">
              <thead>
                <tr role="row">
                  <th style="width: 65px;" colspan="1" rowspan="1"  tabindex="0" class="">Trns No </th>
                  <th  style="width: 168px;" colspan="1" rowspan="1"  tabindex="0" class="">Date</th>
                  <th  style="width: 436px;" colspan="1" rowspan="1"  tabindex="0" class="">Amount</th>
                  <th  style="width: 253px;" colspan="1" rowspan="1" tabindex="0" class="">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php 
								if($PageVal['TotalRecords'] > 0){
								$Ctrl=1;
									foreach($PageVal['ResultSet'] as $AR_DT):
								?>
                <tr class="odd" role="row">
                  <td class="sorting_1"><?php echo $AR_DT['trans_no']; ?></td>
                  <td><?php echo $AR_DT['date_time']; ?></td>
                  <td><?php echo $AR_DT['initial_amount']; ?></td>
                  <td><?php echo DisplayText("WITHDRAW_".$AR_DT['trns_status']); ?></td>
                </tr>
                <?php endforeach;
								}
								 ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- end col -->
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
	});
</script>
</html>
