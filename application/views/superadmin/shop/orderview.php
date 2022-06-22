<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);
$order_id = _d($segment['order_id']);
$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.current_address,
			 tm.city_name, tm.state_name, tm.country_name, tm.pin_code, tm.member_mobile,
			 tad.current_address AS ship_current_address, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name, 
			 tad.pin_code AS ship_pin_code, tad.country_code AS ship_country_code,	 tos.name AS order_state
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0  AND ord.order_id='".$order_id."'
			 GROUP BY ord.order_id";
$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,true);
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
          <h1> Order <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; View </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <div class="space-6"></div>
            <div class="row">
              <div class="col-sm-10 col-sm-offset-1"> <?php echo get_message(); ?>
                <div class="widget-box transparent">
                  <div  class="print_area">
                    <div class="widget-header widget-header-large">
                      <h3 class="widget-title grey lighter"> <i class="ace-icon fa fa-leaf green"></i> Customer Invoice </h3>
                      <div class="widget-toolbar no-border invoice-info"> <span class="invoice-info-label">Invoice:</span> <span class="red">#<?php echo $AR_ORDER['order_no']; ?></span> <br />
                        <span class="invoice-info-label">Date:</span> <span class="blue"><?php echo getDateFormat($AR_ORDER['date_add'],"d D M Y h:i"); ?></span> </div>
                      <div class="widget-toolbar hidden-480"> <a href="javascript:void(0)" id="PrintImg"> <i class="ace-icon fa fa-print"></i> </a> </div>
                    </div>
                    <div class="widget-body">
                      <div class="widget-main padding-24">
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="row">
                              <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right"> <b>Billing Address</b> </div>
                            </div>
                            <div>
                              <ul class="list-unstyled spaced">
                                <li> <i class="ace-icon fa fa-caret-right green"></i><?php echo $AR_ORDER['full_name']; ?> &nbsp;[<?php echo $AR_ORDER['user_id']; ?>]</li>
                                <li> <i class="ace-icon fa fa-caret-right blue"></i><?php echo getTool($AR_ORDER['ship_current_address'],$AR_ORDER['current_address']); ?>-<?php echo getTool($AR_ORDER['ship_pin_code'],$AR_ORDER['pin_code']); ?></li>
                                <li> <i class="ace-icon fa fa-caret-right blue"></i><?php echo getTool($AR_ORDER['ship_city_name'],$AR_ORDER['city_name']); ?>, <?php echo getTool($AR_ORDER['ship_state_name'],$AR_ORDER['state_name']); ?> </li>
                                <li> <i class="ace-icon fa fa-caret-right blue"></i> Phone: <b class="red"><?php echo $AR_ORDER['mobile_number']; ?></b> </li>
                                <li> <i class="ace-icon fa fa-caret-right blue"></i> <?php echo $AR_ORDER['payment']; ?> </li>
                              </ul>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-sm-6">
                            <div class="row">
                              <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right"> <b>Customer Info</b> </div>
                            </div>
                            <div>
                              <ul class="list-unstyled  spaced">
                                <li> <i class="ace-icon fa fa-caret-right green"></i><?php echo $AR_ORDER['full_name']; ?> &nbsp;[<?php echo $AR_ORDER['user_id']; ?>]</li>
                                <li> <i class="ace-icon fa fa-caret-right green"></i><?php echo getTool($AR_ORDER['ship_current_address'],$AR_ORDER['current_address']); ?> </li>
                                <li> <i class="ace-icon fa fa-caret-right green"></i><?php echo getTool($AR_ORDER['ship_city_name'],$AR_ORDER['city_name']); ?> , <?php echo getTool($AR_ORDER['ship_state_name'],$AR_ORDER['state_name']); ?></li>
                                <li> <i class="ace-icon fa fa-caret-right green"></i><?php echo getTool($AR_ORDER['ship_pin_code'],$AR_ORDER['pin_code']); ?> </li>
                                <li class="divider"></li>
                                <li> <i class="ace-icon fa fa-caret-right green"></i> <?php echo $AR_ORDER['member_mobile']; ?> </li>
                              </ul>
                            </div>
                          </div>
                          <!-- /.col --> 
                        </div>
                        <!-- /.row -->
                        <div class="space"></div>
                        <div>
                          <table class="table table-striped table-bordered">
                            <thead>
                              <tr>
                                <th class="center">#</th>
                                <th>Picture</th>
                                <th class="hidden-xs">Product</th>
                                <th class="hidden-480">Detail</th>
                                <th class="hidden-480">Unit</th>
                                <th class="hidden-480">Qty</th>
                                <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
							$QR_ORD_DT = "SELECT tod.* FROM tbl_order_detail AS tod WHERE tod.order_id='".$AR_ORDER['order_id']."' ORDER BY tod.order_detail_id ASC";
							$RS_ORD_DT = $this->SqlModel->runQuery($QR_ORD_DT);
							$Ctrl=1;
							foreach($RS_ORD_DT as $AR_ORD_DT):
							$order_total +=$AR_ORD_DT['net_amount'];
						?>
                              <tr>
                                <td class="center"><?php echo $Ctrl; ?></td>
                                <td><img src="<?php echo $model->getDefaultPhoto($AR_ORD_DT['post_id']); ?>" width="60px" height="60px" class="img-responsive"></td>
                                <td class="hidden-xs"><?php echo setWord($AR_ORD_DT['post_title'],30); ?></td>
                                <td class="hidden-480"><span class="hidden-xs"><?php echo $AR_ORD_DT['post_attribute']; ?></span></td>
                                <td class="hidden-480"><?php echo $AR_ORD_DT['post_price']; ?></td>
                                <td class="hidden-480"><?php echo $AR_ORD_DT['post_qty']; ?></td>
                                <td><?php echo number_format($AR_ORD_DT['net_amount'],2); ?></td>
                              </tr>
                              <?php $Ctrl++; endforeach;  ?>
                            </tbody>
                          </table>
                        </div>
                        <div class="hr hr8 hr-double hr-dotted"></div>
                        <div class="row">
                          <div class="col-sm-5 pull-right">
                            <h4 class="pull-right"> Total amount : <span class="red"><?php echo number_format($order_total,2); ?></span> </h4>
                          </div>
                          <div class="col-sm-7 pull-left"><?php echo $AR_ORDER['order_message']; ?> </div>
                        </div>
                        <?php if($AR_ORDER['coupon_no']!='' and $AR_ORDER['coupon_val']!=''){?>
                        <div class="row">
                          <div class="col-sm-5 pull-right">
                            <h4 class="pull-right"> FPV NO : <span class="red"><?php echo $AR_ORDER['coupon_no']; ?></span>, VALUE : <span class="red"><?php echo $AR_ORDER['coupon_val']; ?></span> </h4>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-5 pull-right">
                            <h4 class="pull-right"> Net Payable : <span class="red"><?php echo number_format($order_total-$AR_ORDER['coupon_val']); ?></span> </h4>
                          </div>
                        </div>
                        <?php }?>
                        <div class="space-6"></div>
                        <div class="well"> Thank you for choosing <?php echo $model->getValue("CONFIG_COMPANY_NAME"); ?> products.
                          We believe you will be satisfied by our services. </div>
                        <div class="hr hr8 hr-double hr-dotted"></div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pull-left">
                      <h5>Order Status : </h5>
                      <form method="post" name="form-history" id="form-history" action="<?php echo generateAdminForm("shop","orderview",array("order_id"=>_e($AR_ORDER['order_id']))); ?>">
                        <select name="id_order_state" id="id_order_state">
                          <option value="">-----select status-----</option>
                          <?php DisplayCombo($_REQUEST['id_order_state'],"ORDER_STATE"); ?>
                        </select>
                        <input type="hidden" name="order_id" id="order_id" value="<?php echo _e($AR_ORDER['order_id']); ?>">
                        <button type="submit" class="btn btn-sm btn-success" name="submitFormHistory"  value="1"> <i class="ace-icon fa fa-check"></i> Update </button>
                      </form>
                      <table width="100%" class="table" style="border-collapse:collapse;">
                        <thead>
                          <tr>
                            <td>Srl No</td>
                            <td>Order Status</td>
                            <td>Date</td>
                          </tr>
                        </thead>
                        <?php 
							$QR_HIS = "SELECT toh.*, tos.name FROM tbl_order_history AS toh 
									 LEFT JOIN tbl_order_state AS tos ON tos.id_order_state=toh.id_order_state
									 WHERE toh.order_id='".$AR_ORDER['order_id']."' ORDER BY toh.id_order_history DESC";
							$RS_HIS = $this->SqlModel->runQuery($QR_HIS);
							$Ctrl=1;
							foreach($RS_HIS as $AR_HIS):
							?>
                        <tr>
                          <td><?php echo $Ctrl; ?></td>
                          <td><?php echo $AR_HIS['name']; ?></td>
                          <td><?php echo getDateFormat($AR_HIS['date_add'],"d D M Y h:i");?></td>
                        </tr>
                        <?php $Ctrl++; endforeach;  ?>
                      </table>
                    </div>
                    <div class="col-md-6">
                      <h5>Order Shipping : </h5>
                      <form method="post" name="form-ship" id="form-ship" action="<?php echo generateAdminForm("shop","orderview",array("order_id"=>_e($AR_ORDER['order_id']))); ?>">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <div class="clearfix">
                              <textarea name="order_ship" class="col-xs-10 col-sm-12" id="order_ship" placeholder="Shipping Details"><?php echo $AR_ORDER['order_ship']; ?></textarea>
                            </div>
                          </div>
                        </div>
                        <div class="space-2">&nbsp;</div>
                        <div class="form-group">
                          <h5>Shipping Date : </h5>
                          <div class="col-sm-12">
                            <div class="input-group">
                              <input class="form-control col-xs-12 col-sm-12  validate[required] date-picker" name="ship_date" id="id-date-picker-1" value="<?php echo $AR_ORDER['ship_date']; ?>" type="text"  />
                              <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span> </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="clearfix"><br>
                              <button type="submit" name="submitShipDetail" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Update Shipping </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                 <?php if($AR_ORDER['invoice_number']==''){ ?>
                  		<div class="row">
                            <div class="col-md-12 center-block">
                                <form method="post" name="form-history" id="form-history" action="<?php echo generateAdminForm("shop","generateinvoice",array("order_id"=>_e($AR_ORDER['order_id']))); ?>" onSubmit="return confirm('Make sure, want to generate invoice of order  no : <?php echo $AR_ORDER['order_no']; ?>')">
                            <button type="submit" class="btn  btn-info" name="generateInvoice"  value="1"> <i class="ace-icon fa fa-check"></i> Generate Invoice </button>
                          </form>
                            </div>
                		 </div>
                 	 <?php } ?>
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
