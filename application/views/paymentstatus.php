<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$current_date = getLocalTime();
$today_date = InsertDate($current_date);
$segment = $this->uri->uri_to_assoc(1);

$reference_no = $segment['reference_no'];

$AR_ORDER = $model->getPaymentDetail($reference_no);
$member_id = $AR_ORDER['member_id'];


$AR_MEM = $model->getMember($member_id);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>

<body>

<!--header area start--> 

<!--offcanvas menu area start-->
<?php $this->load->view('layout/header'); ?>

<!--offcanvas menu area end--> 

<!--header area end--> 

<!--breadcrumbs area start-->
<div class="breadcrumbs_area">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="breadcrumb_content">
          <ul>
            <li><a href="<?php echo BASE_PATH; ?>">home</a></li>
            <li>Payment Status</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<!--breadcrumbs area end--> 

<!-- customer login start -->
<div class="container mt-30">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <?php get_message(); ?>
        <table width="100%" border="1" class="table">
          <tr>
            <td><strong>User</strong></td>
            <td><?php echo $AR_MEM['full_name']; ?>[<?php echo $AR_MEM['user_id']; ?>]</td>
          </tr>
          <tr>
            <td>Order No</td>
            <td><?php echo $AR_ORDER['reference_no']; ?></td>
          </tr>
          <tr>
            <td>Order Amount</td>
            <td><?php echo $AR_ORDER['trns_currency']; ?>&nbsp;<?php echo number_format($AR_ORDER['order_amount'],2); ?></td>
          </tr>
          
          <tr>
            <td>Bank Name</td>
            <td><?php echo $AR_ORDER['bank_name']; ?></td>
          </tr>
          <tr>
            <td>Reference No</td>
            <td><?php echo $AR_ORDER['bank_ref_no']; ?></td>
          </tr>
          <tr>
            <td>Transaction No</td>
            <td><?php echo $AR_ORDER['trns_id']; ?></td>
          </tr>
          <tr>
            <td>Payment Mode</td>
            <td><?php echo $AR_ORDER['payment_mode']; ?></td>
          </tr>
          <tr>
            <td>Payment Status</td>
            <td><div class="text <?php echo (strtolower($AR_ORDER['payment_sts'])=='success')? "text-success":"text-danger"; ?>">
                <?php  ?>
                <?php echo $AR_ORDER['payment_sts']; ?>-<small> <?php echo $AR_ORDER['response_msg']; ?></small></div></td>
          </tr>
          <?php if(strtolower($AR_ORDER['payment_sts'])!='success'){  ?>
          <tr>
            <td colspan="2" align="center"><div class="alert alert-danger"> It seem your transaction is failed, <a href="<?php echo generateSeoUrl("product","payment",""); ?>">click here</a> to try again</div></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>

<!-- customer login end --> 

<!--footer area start-->
<?php $this->load->view('layout/footer'); ?>
<!--footer area end-->
<?php $this->load->view('layout/footerjs'); ?>
<!-- Plugins JS --> 
<script src="<?php echo THEME_PATH; ?>assets/js/plugins.js"></script> 
<!-- Main JS --> 
<script src="<?php echo THEME_PATH; ?>assets/js/main.js"></script>
</body>
<script type="text/javascript" language="javascript" src="<?php echo BASE_PATH; ?>jquery/jquery.print.js"></script>
<script type="text/javascript">
$(function(){
	$("#print-now").click(function(){$( "#welcome-letter" ).print(); return( false );});
});
</script>
</html>