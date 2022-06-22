<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$fran_id = $this->session->userdata('fran_id');
$AR_FRAN = $model->getFranchiseeDetail($fran_id);
#PrintR($AR_FRAN);
$segment = $this->uri->uri_to_assoc(2);
$order_id = _d($segment['order_id']);
$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.current_address,
			 tm.city_name, tm.state_name, tm.country_name, tm.pin_code, tm.member_mobile,
			 tad.current_address AS order_address, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name, tad.country_code AS ship_country_code,
			 tad.pin_code AS ship_pin_code, tos.name AS order_state
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
			<?php echo get_message(); ?>
          <!-- PAGE CONTENT BEGINS -->
          <div class="space-6"></div>
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1"> 
			 <div  class="print_area">
              <div class="widget-box transparent">
                
                <div class="widget-body">
                  <div class="widget-main padding-24">
                    <div class="row">
                      <div class="col-md-12">
						<table width="100%" border="0">
						<tr>
						  <td colspan="2" align="center"><h3 class="green">ORDER VIEW </h3></td>
						  </tr>
						<tr>
							<td colspan="2" align="center">Franchisee- <strong><?php echo ucfirst(strtolower($AR_FRAN['name'])); ?></strong> </td>
							</tr>
						<tr>
							<td colspan="2" align="center"><strong><?php echo ucfirst(strtolower($AR_FRAN['address'])); ?></strong> </td>
							</tr>
						<tr>
							<td colspan="2" align="center"><?php echo ucfirst(strtolower($AR_FRAN['tin_no'])); ?></td>
							</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
							</tr>
						<tr>
						  <td colspan="2" align="center">Franchisee of : <strong><?php echo WEBSITE; ?></strong><br>
						  	<?php echo ucfirst(strtolower($model->getValue("POSTAL_ADDRESS"))); ?>						  	</td>
						  </tr>
						<tr>
						  <td colspan="2" align="center">&nbsp;</td>
						  </tr>
						<tr>
						  <td colspan="2" align="center">&nbsp;</td>
						  </tr>
						<tr>
						  <td width="50%" align="center">USER ID: <span class="red"><?php echo $AR_ORDER['user_id']; ?></span></td>
						  <td width="50%" align="center">ORDER NO : <span class="red"><?php echo $AR_ORDER['order_no']; ?></span></td>
						</tr>
						<tr>
						  <td align="center">NAME:   <span class="blue"><?php echo $AR_ORDER['full_name']; ?></span></td>
						  <td align="center">DATE: <span class="blue"><?php echo getDateFormat($AR_ORDER['date_add'],"d D M Y"); ?></span></td>
						  </tr>
						<tr>
						  <td align="center">MOB.NO : <span class="green"><?php echo $AR_ORDER['member_mobile']; ?></span></td>
						  <td align="center">TIME :  <span class="green"><?php echo getDateFormat($AR_ORDER['date_add'],"h:i:s"); ?></span></td>
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
  
                    </div>
                    <div>
                      <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th class="center">Srl #</th>
                            <th>P.Code</th>
                            <th class="hidden-xs">Item Detail </th>
                            <th class="hidden-480">Batch No </th>
                            <th class="hidden-480">Category</th>
                            <th class="hidden-480">Mrp</th>
                            <th align="right" class="hidden-480">Amt</th>
                            <th align="right" class="hidden-480">Qty</th>
                            <th align="right">Net Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
							$QR_ORD_DT = "SELECT tod.* FROM tbl_order_detail AS tod WHERE tod.order_id='".$AR_ORDER['order_id']."' ORDER BY tod.order_detail_id ASC";
							$RS_ORD_DT = $this->SqlModel->runQuery($QR_ORD_DT);
							$Ctrl=1;
							foreach($RS_ORD_DT as $AR_ORD_DT):
								$AR_POST = $model->getPostDetail($AR_ORD_DT['post_id']);
							
								$order_pv +=($AR_ORD_DT['post_pv']*$AR_ORD_DT['post_qty']);
								$order_mrp +=($AR_ORD_DT['original_post_price']*$AR_ORD_DT['post_qty']);
								$order_tax +=($AR_ORD_DT['post_tax']*$AR_ORD_DT['post_qty']);
								
								$order_qty +=$AR_ORD_DT['post_qty'];
								$order_total +=$AR_ORD_DT['net_amount'];
								
								$shipping_total +=($AR_ORD_DT['post_shipping']*$AR_ORD_DT['post_qty']);
								
								$vendor_total +=($AR_ORD_DT['post_price']*$AR_ORD_DT['post_qty'])+$shipping_total;
						?>
                          <tr>
                            <td class="center"><?php echo $Ctrl; ?></td>
                            <td><span class="hidden-xs"><?php echo $AR_POST['post_code']; ?></span></td>
                            <td class="hidden-xs"><?php echo $AR_ORD_DT['post_title']; ?><br><small><?php echo $AR_ORD_DT['post_attribute']; ?></small> </td>
                            <td class="hidden-480"><span class="hidden-xs"><?php echo $AR_ORD_DT['batch_no']; ?> </span></td>
                            <td class="hidden-480"><span class="hidden-xs"><?php echo $AR_POST['category_name']; ?></span></td>
                            <td class="hidden-480"><span class="hidden-xs"><?php echo $AR_POST['post_mrp']; ?></span></td>
                            <td align="right" class="hidden-480"><span class="hidden-xs"><?php echo $AR_POST['post_price']; ?></span></td>
                            <td align="right" class="hidden-480"><?php echo $AR_ORD_DT['post_qty']; ?></td>
                            <td align="right"><?php echo number_format($AR_ORD_DT['net_amount'],2); ?></td>
                          </tr>
                         
                          <?php $Ctrl++; endforeach;  ?>
						   <tr>
						     <td colspan="5" class="center">&nbsp;</td>
						     <td colspan="3" align="right" class="hidden-480"><strong>Sub Total : </strong></td>
						     <td align="right" class="hidden-480"><?php echo number_format($order_total,2); ?></td>
					        </tr>
						    <tr>
                              <td colspan="5" class="center">&nbsp;</td>
                              <td colspan="3" align="right" class="hidden-480"><strong>Shipping </strong></td>
                              <td align="right"><span class="hidden-480"><?php echo number_format($shipping_total,2); ?></span></td>
                            </tr>
						   <tr>
                            <td colspan="5" class="center">&nbsp;</td>
                            <td colspan="3" align="right" class="hidden-480"><strong>Total :</strong></td>
                            <td align="right" class="hidden-480"><strong><?php echo number_format($order_total+$shipping_total,2); ?></strong></td>
                            </tr>
						   <tr>
						     <td colspan="4" align="right" class="right">&nbsp;							 </td>
					         <td colspan="5" align="right" class="right"><table width="40%" border="1" cellpadding="4" cellspacing="4" class="table table-striped table-bordered" style="border-collapse:collapse;" >
                                  <tr>
                                    <td colspan="2">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td width="48%" align="right">TAX AMT </td>
                                    <td width="52%" align="right"><strong><?php echo number_format($order_tax,2); ?></strong></td>
                                  </tr>
                                  <tr>
                                    <td align="right">MRP AMT </td>
                                    <td align="right"><strong><?php echo number_format($order_mrp,2); ?></strong></td>
                                  </tr>
                                  <tr>
                                    <td align="right">TOTAL DISC </td>
                                    <td align="right"><strong>-<?php echo number_format($order_mrp-$order_total,2); ?></strong></td>
                                  </tr>
                                  <tr>
                                    <td align="right">SHIPPING</td>
                                    <td align="right"><strong>+<?php echo number_format($shipping_total,2); ?></strong></td>
                                  </tr>
                                  <tr>
                                    <td align="right">NET AMOUNT RECEIVED</td>
                                    <td align="right"><strong><?php echo number_format($vendor_total,2); ?></strong></td>
                                  </tr>
                                </table></td>
				            </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="hr hr8 hr-double hr-dotted"></div>
                    <div class="row">
    
                      <div class="col-sm-7 pull-left"><?php echo $AR_ORDER['order_message']; ?> </div>
                    </div>
                    <div class="space-6"></div>
                    <div class="well"> Thank you for choosing <?php echo $model->getValue("CONFIG_COMPANY_NAME"); ?> products.
                      We believe you will be satisfied by our services. </div>
                    <div class="hr hr8 hr-double hr-dotted"></div>
                  </div>
                </div>
              </div>
			 </div>
              <div class="row">
                <div class="col-md-12 center">
                  <form method="post" name="form-history" id="form-history" action="<?php echo generateFranchiseForm("order","generateinvoice",array("order_id"=>_e($AR_ORDER['order_id']))); ?>" onSubmit="return confirm('Make sure, want to generate invoice of order  no : <?php echo $AR_ORDER['order_no']; ?>')">
                    <button type="submit" class="btn btn-sm btn-success" name="generateInvoice"  value="1"> <i class="ace-icon fa fa-check"></i> Generate Invoice </button>
                  </form>
                  
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
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
