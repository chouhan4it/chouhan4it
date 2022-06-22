<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	
	$member_id = $this->session->userdata('mem_id');
	$AR_DT = $model->getMember($member_id);
	
	$wallet_id = $model->getWallet(WALLET1);
	
	if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
		$from_date = InsertDate($_REQUEST['from_date']);
		$to_date = InsertDate($_REQUEST['to_date']);
		$StrWhr .=" AND DATE(tft.date_time) BETWEEN '$from_date' AND '$to_date'";
		$SrchQ .="&from_date=$from_date&to_date=$to_date";
	}
	
	$QR_PAGES="SELECT tft.* 
			   FROM tbl_fund_transfer AS tft 
			   WHERE tft.to_member_id='".$member_id."' AND tft.wallet_id='".$wallet_id."'
			   AND tft.trns_for LIKE 'DPT' AND tft.draw_type LIKE 'ONLINE' 
			   $StrWhr 
			   ORDER BY tft.trns_date DESC";
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
        <h4 class="page-title">Deposit  History</h4>
        <p class="text-muted page-title-alt">Your Deposit  Transaction</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
        <?php get_message(); ?>
        <div class="card-box">
          <div class="row">
            <div class="col-md-4">
              <form method="post" action="<?php echo generateMemberForm("financial","deposit",""); ?>" enctype="multipart/form-data" name="form-page" id="form-page">
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-3 col-sm-3 date-picker" name="from_date" id="id-date-picker-1" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-3 col-sm-3  date-picker" name="to_date" id="id-date-picker-1" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <input type="hidden" name="wallet_id" id="wallet_id" value="<?php echo $wallet_id; ?>">
                <input class="btn btn-sm btn-primary m-t-n-xs" name="searchRequest" value="Search" type="submit">
                <input type="button" class="btn btn-sm btn-danger m-t-n-xs" name="reset" id="reset" value="Reset" onClick="window.location.href='?'">
                <a href="javascript:void(0)" class="btn btn-sm btn-info" data-toggle="modal" data-target="#deposit-box">New Deposit</a>
              </form>
            </div>
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="table-responsive">
            <table class="table table-actions-bar">
              <thead>
                <tr role="row">
                  <th>Date</th>
                  <th >Amount</th>
                  <th>Details</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
				<?php 
                if($PageVal['TotalRecords'] > 0){
                $Ctrl=1;
                foreach($PageVal['ResultSet'] as $AR_DT):
                ?>
                <tr class="odd" role="row">
                  <td ><?php echo getDateFormat($AR_DT['date_time'],"d M Y h:i"); ?></td>
                  <td><?php echo CURRENCY; ?> &nbsp; <?php echo $AR_DT['initial_amount']; ?></td>
                  <td><?php echo $AR_DT['trns_remark']; ?></td>
                  <td><?php echo DisplayText("DEPOSIT_".$AR_DT['trns_status']); ?></td>
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
<div id="deposit-box" class="modal fade" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Deposit Money</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <form method="post"   action="<?php echo generateMemberForm("payment","processpayment",""); ?>" name="form-page" id="form-page">
               
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-12">Amount (<?php echo CURRENCY; ?>)</label>
                  <div class="col-md-12">
                    <input name="deposit_amount" value="" placeholder="Amount" class="form-control input-xlarge form-half validate[required]" type="text"  id="deposit_amount">
                    <p><small>Minimum deposit <?php echo CURRENCY; ?> 10</small></p>
                  </div>
                </div>
              </div>
              <input type="hidden" name="wallet_id" id="wallet_id" value="<?php echo $wallet_id; ?>">
              <input class="btn  btn-primary m-t-n-xs" name="depositMoney" value="Deposit" type="submit">
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
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
