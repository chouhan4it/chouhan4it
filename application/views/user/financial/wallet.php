<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$wallet_id = ($_REQUEST['wallet_id']>0)? $_REQUEST['wallet_id']:$model->getWallet(WALLET1);

if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);	
	$StrWhr .=" AND DATE(twt.trns_date) BETWEEN '".$from_date."' AND '".$to_date."'";
	$SrchQ  = "&from_date=$from_date&to_date=$to_date";
}

$QR_PAGES = "SELECT twt.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id 
			 FROM tbl_wallet_trns AS twt
			 LEFT JOIN tbl_members AS tm ON tm.member_id=twt.member_id
			 WHERE twt.member_id='".$member_id."' AND twt.wallet_id='$wallet_id'
			 $StrWhr
			 GROUP BY twt.wallet_trns_id
			 ORDER BY twt.wallet_trns_id ASC";
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
        <h4 class="page-title">e-Wallet Statement</h4>
        <p class="text-muted page-title-alt">Your e-Wallet transaction</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
      <?php get_message(); ?>
        <div class="card-box">
          <div class="row">
            <div class="col-md-3">
              <form method="post" action="<?php echo generateMemberForm("financial","wallet",""); ?>" enctype="multipart/form-data" name="form-page" id="form-page">
			  	<div class="form-group">
                 	<select name="wallet_id" class="form-control col-xs-3 col-sm-3 validate[required]" id="wallet_id">
						<?php echo DisplayCombo($wallet_id,"WALLET"); ?>
					</select>
                </div>
				<div class="clearfix">&nbsp;</div>
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
                <input class="btn btn-sm btn-primary m-t-n-xs" name="searchRequest" value="Search" type="submit">
                <input type="button" class="btn btn-sm btn-danger m-t-n-xs" name="reset" id="reset" value="Reset" onClick="window.location.href='?'">
              </form>
            </div>
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="table-responsive">
            <table class="table table-actions-bar">
              <thead>
                <tr>
                  <th>Srl  No </th>
                  <th>Date</th>
                  <th>Trns  No </th>
                  <th>Trns Detail </th>
                  <th>Credit </th>
                  <th>Debit </th>
                  <th style="min-width: 80px;">Balance</th>
                </tr>
              </thead>
              <tbody>
                <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
							switch($AR_DT['trns_type']):
								case "Cr":
									$dr_amount = 0;
									$cr_amount = $AR_DT['trns_amount'];
									$credit_amt +=$AR_DT['trns_amount'];
									$net_balalnce = $credit_amt-$debit_amt;
								break;
								case "Dr":
									$cr_amount = 0;
									$dr_amount = $AR_DT['trns_amount'];
									$debit_amt +=$AR_DT['trns_amount'];
									$net_balalnce =$credit_amt-$debit_amt;
								break;
							endswitch;
			       ?>
                <tr>
                  <td><?php echo $Ctrl; ?> </td>
                  <td><?php echo DisplayDate($AR_DT['trns_date']); ?></td>
                  <td><?php echo $AR_DT['trans_no']; ?></td>
                  <td><?php echo $AR_DT['trns_remark']; ?></td>
                  <td><?php echo number_format($cr_amount); ?></td>
                  <td><?php echo number_format($dr_amount); ?></td>
                  <td><strong><?php echo number_format($net_balalnce); ?></strong></td>
                </tr>
                <?php $Ctrl++; endforeach; }else{ ?>
                <tr>
                  <td colspan="7" class="text-danger">No transaction found </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
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
