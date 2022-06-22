<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);
$waybill = ($segment['waybill']);

$AR_CRCR = $model->getCourierDetail($waybill);

$AR_TRACK = json_decode($AR_CRCR['track_status'],true);
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
       <a href="<?php echo generateSeoUrlMember("order","trackorder",""); ?>" > <i class="ace-icon fa fa-arrow-left"></i>  Back</a>&nbsp;&nbsp;
         <a href="javascript:void(0)" id="PrintImg"> <i class="ace-icon fa fa-print"></i> Print</a>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card-box">
          
          <div class="clearfix">&nbsp;</div>
          <div class="table-responsive">
            <table width="100%" border="0" class="table">
                                <tr>
                                  <td align="left"><strong>AWB NO</strong></td>
                                  <td align="left"><?php echo $AR_TRACK['awb_no']; ?></td>
                                </tr>
                                <tr>
                                  <td align="left"><strong>LOGISTICS</strong></td>
                                  <td align="left"><?php echo $AR_TRACK['logistic']; ?></td>
                                </tr>
                                <tr>
                                  <td align="left"><strong>CURRENT STATUS</strong></td>
                                  <td align="left"><?php echo $AR_TRACK['current_status']; ?></td>
                                </tr>
                                <tr>
                                  <td width="50%" align="left"><strong>RETURN DETAIL</strong></td>
                                  <td width="50%" align="left"><?php echo getTool($AR_TRACK['return_tracking_no'],'N/A'); ?></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="top"><strong>DETAIL</strong></td>
                                  <td align="left">
                                  	<?php 
									foreach($AR_TRACK['scan_details'] as $key=>$value):
										$scan_html .= '<p>'.$AR_TRACK['scan_details'][$key]['remark']." on ".$AR_TRACK['scan_details'][$key]['scan_date_time'].'</p>';
									endforeach;
									echo $scan_html;
									 ?>
                                  </td>
                                </tr>
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
<script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/jquery/jquery.print.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
	});
	$(function(){$( "#PrintImg" ).click(function(){$( ".print_area" ).print(); return( false );});});
</script>
</html>
