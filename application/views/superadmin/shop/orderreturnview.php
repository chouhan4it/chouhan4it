<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);
$order_id = _d($segment['order_id']);
$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.current_address,
			 tm.city_name, tm.state_name, tm.country_name, tm.pin_code, tm.member_mobile,
			 tad.current_address AS order_address, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name,
			 tad.country_code AS ship_country_code,	 tos.name AS order_state, tc.coupon_no AS coupon_no, tc.coupon_val AS coupon_val
			 FROM tbl_orders_return AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 LEFT JOIN tbl_coupon AS tc ON ord.order_id=tc.use_order_id
			 WHERE ord.order_id>0  AND ord.order_id='".$order_id."'
			 GROUP BY ord.order_id";
$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,true);
$AR_FRAN = $model->getFranchiseeDetail($AR_ORDER['franchisee_id']);
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
          <h1> Order <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;Return View </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <div class="row">
              <div class="col-sm-10 col-sm-offset-1"> <?php echo get_message(); ?>
                <div class="widget-box transparent">
                  <div  class="print_area">
                    <div class="widget-header widget-header-large">
                      <h3 class="widget-title grey lighter"> <i class="ace-icon fa fa-leaf green"></i> Return Invoice </h3>
                      <div class="widget-toolbar no-border invoice-info"> <span class="invoice-info-label">Order No:</span> <span class="red">#<?php echo $AR_ORDER['order_no']; ?></span> <br />
                        <span class="invoice-info-label">Date:</span> <span class="blue"><?php echo getDateFormat($AR_ORDER['date_add'],"d D M Y h:i"); ?></span> </div>
                      <div class="widget-toolbar hidden-480"> <a href="javascript:void(0)" id="PrintImg"> <i class="ace-icon fa fa-print"></i> </a> </div>
                    </div>
                    <div class="widget-body">
                      <div class="widget-main padding-24">
                        <div class="row"> 
                          
                          <!-- /.col -->
                          <div class="col-sm-12">
                            <table width="100%" border="0">
                              <tr>
                                <td colspan="2" align="center"><h3 class="green">RETURN  INVOICE </h3></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="center">Branch- <strong><?php echo ucfirst(strtolower($AR_FRAN['name'])); ?></strong></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="center"><strong><?php echo ucfirst(strtolower($AR_FRAN['address'])); ?></strong></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="center"><?php echo ucfirst(strtolower($AR_FRAN['tin_no'])); ?></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="center">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2" align="center">Branch of : <strong><?php echo WEBSITE; ?></strong><br>
                                  <?php echo ucfirst(strtolower($model->getValue("POSTAL_ADDRESS"))); ?></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="center">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2" align="center">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="50%" align="center">USER ID : <span class="red"><?php echo $AR_ORDER['user_id']; ?></span></td>
                                <td width="50%" align="center">ORDER NO : <span class="red"><?php echo $AR_ORDER['order_no']; ?></span></td>
                              </tr>
                              <tr>
                                <td align="center">NAME: <span class="blue"><?php echo $AR_ORDER['full_name']; ?></span></td>
                                <td align="center">DATE: <span class="blue"><?php echo getDateFormat($AR_ORDER['date_add'],"d D M Y"); ?></span></td>
                              </tr>
                              <tr>
                                <td align="center">MOB.NO : <span class="green"><?php echo $AR_ORDER['member_mobile']; ?></span></td>
                                <td align="center">TIME : <span class="green"><?php echo getDateFormat($AR_ORDER['date_add'],"h:i:s"); ?></span></td>
                              </tr>
                              <tr>
                                <td align="center">ADD : <?php echo ($AR_ORDER['order_address'])? $AR_ORDER['order_address']:$AR_ORDER['current_address']; ?>, <?php echo ($AR_ORDER['ship_city_name'])? $AR_ORDER['ship_city_name']:$AR_ORDER['city_name']; ?>,<?php echo ($AR_ORDER['ship_state_name'])? $AR_ORDER['ship_state_name']:$AR_ORDER['state_name']; ?>,<?php echo ($AR_ORDER['ship_pin_code'])? $AR_ORDER['ship_pin_code']:$AR_ORDER['pin_code']; ?></td>
                                <td align="center">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td align="center">&nbsp;</td>
                              </tr>
                            </table>
                          </div>
                          <!-- /.col --> 
                        </div>
                        <!-- /.row -->
                        <div class="space"></div>
                        <div>
                          <table class="table table-striped table-bordered">
                            <thead>
                              <tr>
                                <th class="center">Srl #</th>
                                <th>Img</th>
                                <th>Code</th>
                                <th>Pack</th>
                                <th >Item Detail </th>
                                <th >Batch No </th>
                                <th >Mrp</th>
                                <th > Price</th>
                                <th >Qty</th>
                                <th>Net Amount</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
							$QR_ORD_DT = "SELECT todr.* FROM tbl_order_detail_return AS todr WHERE todr.order_return_id='".$AR_ORDER['order_return_id']."'
							 ORDER BY todr.order_return_detail_id ASC";
							$RS_ORD_DT = $this->SqlModel->runQuery($QR_ORD_DT);
							$Ctrl=1;
							foreach($RS_ORD_DT as $AR_ORD_DT):
							
								$total_selling =($AR_ORD_DT['post_selling']*$AR_ORD_DT['post_qty']);
								$total_selling_mrp =($AR_ORD_DT['post_selling_mrp']*$AR_ORD_DT['post_qty']);					
								
								$order_total +=$total_selling;
								$order_total_mrp +=$total_selling_mrp;
								$shipping_total = 0;
						?>
                              <tr>
                                <td class="center"><?php echo $Ctrl; ?></td>
                                <td><img src="<?php echo $model->getFileSrc($AR_ORD_DT['post_image_id']); ?>" width="40" class="img-responsive"></td>
                                <td><span class="hidden-xs"><?php echo ($AR_ORD_DT['post_code']); ?></span></td>
                                <td><span class="hidden-xs"><?php echo $AR_ORD_DT['post_attribute']; ?></span></td>
                                <td ><a target="_blank" href="<?php echo generateSeoUrl("product","detail",array("post_id"=>($AR_ORD_DT['post_id']))); ?>"><?php echo ($AR_ORD_DT['post_title']); ?></a></td>
                                <td ><span class="hidden-xs"><?php echo $AR_ORD_DT['batch_no']; ?> </span></td>
                                <td ><span class="hidden-xs"><?php echo number_format($AR_ORD_DT['post_selling_mrp'],2); ?></span></td>
                                <td ><span class="hidden-xs"><?php echo number_format($AR_ORD_DT['post_selling'],2); ?></span></td>
                                <td ><?php echo $AR_ORD_DT['post_qty']; ?></td>
                                <td align="right"><?php echo number_format($total_selling,2); ?></td>
                              </tr>
                              <?php $Ctrl++; 
						  endforeach;  
						  $order_amount_payable = $order_total;
						  ?>
                              <tr>
                                <td colspan="9" align="right" class=""><strong>Sub Total</strong></td>
                                <td align="right"><span class="hidden-480"><?php echo number_format($order_total,2); ?></span></td>
                              </tr>
                              <tr>
                                <td colspan="9" align="right" class=""><strong>Shipping </strong></td>
                                <td align="right"><span class="hidden-480"><?php echo number_format($shipping_total,2); ?></span></td>
                              </tr>
                              <tr>
                                <td colspan="9" align="right" class=""><strong>Total</strong></td>
                                <td align="right"><strong><?php echo number_format($order_total+$shipping_total,2); ?></strong></td>
                              </tr>
                              <tr>
                                <td colspan="10" align="right" class=""><table width="40%" border="1" cellpadding="4" cellspacing="4"  style="border-collapse:collapse;" >
                                    <tr>
                                      <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td width="48%" align="right">MRP AMT </td>
                                      <td width="52%" align="right"><strong><?php echo number_format($order_total_mrp,2); ?></strong></td>
                                    </tr>
                                    <tr>
                                      <td align="right">TOTAL. AMT </td>
                                      <td align="right"><strong><?php echo number_format($order_total,2); ?></strong></td>
                                    </tr>
                                    <tr>
                                      <td align="right">TOTAL DISC </td>
                                      <td align="right"><strong><?php echo number_format($order_total_mrp-$order_total,2); ?></strong></td>
                                    </tr>
                                    <tr>
                                      <td align="right">NET AMOUNT REFUND</td>
                                      <td align="right"><strong><?php echo number_format($order_amount_payable,2); ?></strong></td>
                                    </tr>
                                  </table></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="hr hr8 hr-double hr-dotted"></div>
                        <div class="row">
                          <div class="col-sm-7"><?php echo $AR_ORDER['order_message']; ?> </div>
                          <div class="col-sm-5 align-right">
                            <h4> Sub Total : <span class="red"><?php echo number_format($order_total,2); ?></span> </h4>
                            <div class="clearfix">&nbsp;</div>
                            <h4> Shipping Charge : <span class="red"><?php echo number_format($shipping_total,2); ?></span> </h4>
                            <div class="clearfix">&nbsp;</div>
                            <h4> Net Total : <span class="red"><?php echo number_format($order_total+$shipping_total,2); ?></span> </h4>
                          </div>
                        </div>
                        <div class="space-6"></div>
                        <div class="well"> Thank you for choosing <?php echo WEBSITE; ?> products.
                          We believe you will be satisfied by our services. </div>
                        <div class="hr hr8 hr-double hr-dotted"></div>
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
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD hh:mm'
		});
	});
</script> 
<script type="text/javascript">
	$(function(){$( "#PrintImg" ).click(function(){$( ".print_area" ).print(); return( false );});});
</script>
</body>
</html>
