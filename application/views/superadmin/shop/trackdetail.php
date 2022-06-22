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
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/fonts.googleapis.com.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
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
<script type="text/javascript">
	$(function(){
		$(".open_modal").on('click',function(){
			$('#search-modal').modal('show');
			return false;
		});
	});
</script>
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
          <h1> Tracking <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Detail </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12"> <?php echo get_message(); ?> 
            <!-- PAGE CONTENT BEGINS -->
            <div class="space-6"></div>
            <div class="row">
              <div class="col-sm-10 col-sm-offset-1">
               <a href="<?php echo generateSeoUrlAdmin("shop","invoicereport",""); ?>" > <i class="ace-icon fa fa-arrow-left"></i>  Back</a>&nbsp;&nbsp;
               <a href="javascript:void(0)" id="PrintImg"> <i class="ace-icon fa fa-print"></i> Print</a>
                <div  class="print_area">
                  <div class="widget-box transparent">
                    <div class="widget-body">
                      <div class="widget-main padding-24">
                        <div class="row">
                          <div class="col-md-12">
                            
                            <table width="100%" border="0"  class="table">
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
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
<script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/jquery/jquery.print.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){$( "#PrintImg" ).click(function(){$( ".print_area" ).print(); return( false );});});
</script>
</body>
</html>
