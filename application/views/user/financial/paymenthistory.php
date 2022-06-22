<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$wallet_id = $model->getWallet(WALLET1);
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	
	$member_id = $this->session->userdata('mem_id');
	
	if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
		$from_date = InsertDate($_REQUEST['from_date']);
		$to_date = InsertDate($_REQUEST['to_date']);
		$StrWhr .=" AND DATE(top.date_time) BETWEEN '$from_date' AND '$to_date'";
		$SrchQ .="&from_date=$from_date&to_date=$to_date";
	}
	
	$QR_PAGES="SELECT top.*
			FROM ".prefix."tbl_online_payment AS top 
			LEFT JOIN tbl_members AS tm ON tm.member_id=top.member_id
			WHERE (top.member_id='".$member_id."')
			$StrWhr 
			ORDER BY top.online_id DESC";
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
        <h4 class="page-title">Online Payment Transaction</h4>
        <p class="text-muted page-title-alt">Your Online Payment Transaction List</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
	  
        <div class="card-box">
          
          <div class="table-responsive">
            <table  id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                      <thead>
                        <tr role="row">
                          <th >Srl No </th>
                          <th >Bank </th>
                          <th >Ref No</th>
                          <th >Order</th>
                          <th >Transaction No </th>
                          <th>Date</th>
                          <th>Amount</th>
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
                          <td class=""><?php echo $Ctrl; ?></td>
                          <td class=""><?php echo $AR_DT['bank_name']; ?></td>
                          <td class=""><?php echo $AR_DT['bank_ref_no']; ?></td>
                          <td class=""><?php echo $AR_DT['order_no']; ?></td>
                          <td class=""><?php echo $AR_DT['trns_id']; ?></td>
                          <td class=""><?php echo getDateFormat($AR_DT['date_time'],"d M Y h:i"); ?></td>
                          <td><?php echo number_format($AR_DT['order_amount'],2); ?></td>
                          <td><?php echo ($AR_DT['payment_sts']); ?></td>
                        </tr>
						<?php 
                        $Ctrl++;
                        endforeach;
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
