<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);
$order_id = _d($segment['order_id']);
#$model->getFreeCouponToUser($order_id);
$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.current_address,
			 tm.city_name, tm.state_name, tm.country_name, tm.pin_code, tm.member_mobile,
			 tad.current_address AS order_address, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name, tad.country_code AS 
			 ship_country_code, tad.pin_code AS ship_pin_code, tos.name AS order_state, tc.coupon_no AS coupon_no, tc.coupon_val AS coupon_val
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 LEFT JOIN tbl_coupon AS tc ON ord.order_id=tc.use_order_id
			 WHERE ord.order_id>0  AND ord.order_id='".$order_id."'
			 GROUP BY ord.order_id";
$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,true);
$AR_FRAN = $model->getFranchiseeDetail($AR_ORDER['franchisee_id']);

$QR_GST = "SELECT SUM(tod.post_pv*tod.post_qty) AS order_pv, SUM(tod.original_post_price*tod.post_qty) AS  order_mrp,
		   SUM(tod.post_price*tod.post_qty) AS order_rcp,
		   tod.post_tax, tod.tax_age, tod.post_qty, tod.net_amount
		   FROM tbl_order_detail AS tod 
		   WHERE tod.order_id='".$AR_ORDER['order_id']."' 
		   GROUP BY tod.tax_age 
		   ORDER BY tod.order_detail_id ASC"; 
$RS_GST = $this->SqlModel->runQuery($QR_GST);
foreach($RS_GST as $AR_GST):
	$post_tax_gst = $AR_GST['tax_age'];
	$order_tax_devide_gst = $post_tax_gst/2;
	$order_rcp_gst = ( $AR_GST['order_rcp']  /  ( ($post_tax_gst/100)+1 ) );								
	$order_tax_calc_gst = ($order_rcp_gst*$order_tax_devide_gst)/100;
	$sum_order_rcp_gst +=$order_rcp_gst;
	$sum_order_tax_calc_gst +=$order_tax_calc_gst;
endforeach;

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
<style type="text/css">
tr > td {
	font-size: 11px;
}
</style>
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
          <h1> Invoice <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; View </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12"> <?php echo get_message(); ?> 
            <!-- PAGE CONTENT BEGINS -->
            <div class="space-6"></div>
            <div class="row">
              <div class="col-sm-10 col-sm-offset-1">
                <div  class="print_area">
                  <div class="widget-box transparent">
                    <div class="widget-body">
                      <div class="widget-main padding-24">
                        <div class="row">
                          <div class="col-md-12"> <a href="javascript:void(0)" id="PrintImg"> <i class="ace-icon fa fa-print"></i> </a>
                            <div>
                              <table width="100%" border="0">
                                <tr>
                                  <td colspan="2" align="center"><h3 class="green">TAX INVOICE/RETAIL INVOICE</h3></td>
                                </tr>
                                <tr>
                                  <td align="left"><table width="100%" border="0">
                                      <tr>
                                        <td><strong><?php echo strtoupper($AR_FRAN['name']); ?></strong></td>
                                      </tr>
                                      <tr>
                                        <td><?php echo wordwrap($AR_FRAN['address'],30,"<br>\n");  ?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>GSTIN/UIN:</strong> <?php echo strtoupper($AR_FRAN['gst_no']); ?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>E-Mail:</strong> <?php echo ($AR_FRAN['email']); ?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Phone:</strong> <?php echo ($AR_FRAN['mobile']); ?></td>
                                      </tr>
                                    </table></td>
                                  <td align="right"><table width="100%" border="0">
                                      <tr>
                                        <td><table width="100%" border="0">
                                            <tr>
                                              <td width="47%"><strong>Invoice No : </strong> <?php echo $AR_ORDER['invoice_number']; ?></td>
                                              <td width="53%" rowspan="6"><img src="<?php echo LOGO; ?>" width="150"></td>
                                            </tr>
                                            <tr>
                                              <td><strong>Date  :</strong> <?php echo getDateFormat($AR_ORDER['invoice_date'],"D d M Y"); ?></td>
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td><strong>Supplier's Ref.:</strong></td>
                                            </tr>
                                            <tr>
                                              <td><strong><?php echo strtoupper($AR_FRAN['franchisee_type']); ?></strong></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                      <tr>
                                        <td align="center"><h5>Remarks (Discount Summary)</h5></td>
                                      </tr>
                                      <tr>
                                        <td><table width="100%" border="0">
                                            <tr>
                                              <td><strong>TOTAL PV</strong></td>
                                              <td><strong>MRP</strong></td>
                                              <td><strong>NET AMT</strong></td>
                                              <td><strong> DISCOUNT</strong></td>
                                            </tr>
                                          
                                            <tr>
                                              <td><?php echo number_format($AR_ORDER['total_pv'],2); ?></td>
                                              <td><?php echo ($AR_ORDER['total_paid_real']); ?></td>
                                              <td><?php echo ($AR_ORDER['total_paid']); ?></td>
                                              <td><?php echo ($AR_ORDER['total_paid_real']-$AR_ORDER['total_paid']); ?></td>
                                            </tr>
                                           
                                           
                                          </table></td>
                                      </tr>
                                      <tr>
                                        <td align="center"><strong>Other Reference(s)</strong></td>
                                      </tr>
                                    </table></td>
                                </tr>
                                <tr>
                                  <td align="left"><strong>Buyer:</strong></td>
                                  <td align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left"><?php echo getTool($AR_ORDER['ship_full_name'],$AR_ORDER['full_name']); ?></td>
                                  <td align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">Mobile: <?php echo getTool($AR_ORDER['ship_mobile_number'],$AR_ORDER['member_mobile']); ?></td>
                                  <td align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="50%" align="left">Email: <span class="green"><?php echo getTool($AR_ORDER['ship_email_address'],$AR_ORDER['member_email']); ?></span></td>
                                  <td width="50%" align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">Address : <?php echo $AR_ORDER['order_address'].",".$AR_ORDER['ship_land_mark'].",".$AR_ORDER['ship_city_name'].",".$AR_ORDER['ship_state_name'].",".$AR_ORDER['ship_pin_code']; ?></td>
                                  <td align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left"><strong>GSTIN : (Unregistered)</strong></td>
                                  <td align="center">&nbsp;</td>
                                </tr>
                              </table>
                            </div>
                            <div class="hr hr8 hr-double hr-dotted"></div>
                            <div>
                              <table width="100%" border="0">
                                <tr>
                                  <td colspan="2" align="center"><table width="100%"  class="table">
                                      <thead>
                                        <tr>
                                          <th width="6%" align="left">Sr No</th>
                                          <th width="7%" align="left">Code</th>
                                          <th width="7%" align="left">HSN</th>
                                          <th align="left">Item Detail</th>
                                          <th width="6%" align="left">Pack</th>
                                          <th width="6%" align="left">Batch</th>
                                          <th width="4%" class="center">PV</th>
                                          <th width="5%" class="center">MRP</th>
                                          <th width="5%" class="center">PRICE</th>
                                          <th width="4%" class="center">Qty</th>
                                          <th width="9%" class="center">Amount</th>
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
										?>
                                        <tr>
                                          <td align="left"><?php echo $Ctrl; ?></td>
                                          <td align="left"><?php echo $AR_POST['post_code']; ?></td>
                                          <td align="left"><?php echo $AR_POST['post_hsn']; ?></td>
                                          <td align="left"><?php echo setWord($AR_ORD_DT['post_title'],30); ?></td>
                                          <td align="left"><?php echo $AR_POST['post_size']; ?></td>
                                          <td align="left"><?php echo $AR_ORD_DT['batch_no']; ?></td>
                                          <td align="center"><?php echo $AR_ORD_DT['post_pv']; ?></td>
                                          <td align="center"><?php echo $AR_ORD_DT['original_post_price']; ?></td>
                                          <td align="center"><?php echo $AR_ORD_DT['post_price']; ?></td>
                                          <td align="center"><?php echo $AR_ORD_DT['post_qty']; ?></td>
                                          <td align="center"><?php echo number_format($AR_ORD_DT['net_amount'],2); ?></td>
                                        </tr>
                                        <?php $Ctrl++; endforeach;
									$base_amount =  $order_total-$order_tax;
									$vat_age = ($order_tax/$base_amount)*100;
									?>
                                        <tr>
                                          <td colspan="8" align="left" class="left"><strong>Amount in words :</strong> &nbsp;&nbsp; <u><?php echo convert_number($order_total); ?> only </u> /-</td>
                                          <td align="right" class="center"><strong>Total</strong></td>
                                          <td align="right" class="hidden-480"><strong><?php echo number_format($order_qty); ?></strong></td>
                                          <td align="right"><strong><?php echo number_format($order_total,2); ?></strong><br>
                                            <small style="font-size:8px;">E. & O.E</small></td>
                                        </tr>
                                        <tr>
                                          <td colspan="11" align="left" class="left"><div class="clearfix">&nbsp;</div></td>
                                        </tr>
                                      </tbody>
                                    </table></td>
                                </tr>
                              </table>
                            </div>
                            <div class="hr hr8 hr-double hr-dotted"></div>
                            <div>
                              <table width="100%" border="0">
                                <tr>
                                  <td width="45%" rowspan="2" align="center"><strong>HSN/SAC</strong></td>
                                  <td width="13%"><strong>Taxable Value</strong></td>
                                  <td colspan="2" align="center"><strong>CGST</strong></td>
                                  <td colspan="2" align="center"><strong>SGST</strong></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td width="11%">Rate</td>
                                  <td width="10%">Amount</td>
                                  <td width="11%">Rate</td>
                                  <td width="10%">Amount</td>
                                </tr>
                                <?php 
							  	$QR_ORD_TAX = "SELECT SUM(tod.post_pv*tod.post_qty) AS order_pv, SUM(tod.original_post_price*tod.post_qty) AS  order_mrp  ,
								SUM(tod.post_price*tod.post_qty) AS order_rcp,
								tod.post_tax, tod.tax_age, tod.post_qty, tod.net_amount
								FROM tbl_order_detail AS tod 
								WHERE tod.order_id='".$AR_ORDER['order_id']."' 
								GROUP BY tod.tax_age 
								ORDER BY tod.order_detail_id ASC"; 
							  	$RS_ORD_TAX = $this->SqlModel->runQuery($QR_ORD_TAX);
								foreach($RS_ORD_TAX as $AR_ORD_TAX):
								$post_tax = $AR_ORD_TAX['tax_age'];
								
								$order_tax_devide = $post_tax/2;
								#$tax_formula =  ( $AR_ORD_TAX['order_tax'] / 10 );
								
								$order_rcp = ( $AR_ORD_TAX['order_rcp']  /  ( ($post_tax/100)+1 ) );								
								
								$order_tax_calc = ($order_rcp*$order_tax_devide)/100;
								
								$sum_order_rcp +=$order_rcp;
								$sum_order_tax_calc +=$order_tax_calc;
							  ?>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td><?php echo number_format($order_rcp,2); ?></td>
                                  <td><?php echo number_format($order_tax_devide,2); ?> %</td>
                                  <td><?php echo number_format($order_tax_calc,2); ?></td>
                                  <td><?php echo number_format($order_tax_devide,2); ?> %</td>
                                  <td><?php echo number_format($order_tax_calc,2); ?></td>
                                </tr>
                                <?php 
							 	endforeach;
								$gst_order_tax_calc = $sum_order_tax_calc*2;							
								
							  ?>
                                <tr>
                                  <td align="right"><strong>Total</strong> :</td>
                                  <td align="left"><strong><?php echo number_format($sum_order_rcp,2); ?></strong></td>
                                  <td>&nbsp;</td>
                                  <td><strong><?php echo number_format($sum_order_tax_calc,2); ?></strong></td>
                                  <td>&nbsp;</td>
                                  <td><strong><?php echo number_format($sum_order_tax_calc,2); ?></strong></td>
                                </tr>
                                <tr>
                                  <td colspan="6">Tax Amount (In words): <u><?php echo convert_number(number_format($gst_order_tax_calc,2)); ?> Only /-</u></td>
                                </tr>
                              </table>
                            </div>
                          </div>
                        </div>
                        <div></div>
                        <div class="hr hr8 hr-double hr-dotted"></div>
                        <div class="row">
                          <div class="col-sm-12 pull-left"><strong>Terms & Condition</strong>
                            <ul  style="list-style-type:decimal; font-size:8px;">
                              <li>GOODS ONCE SOLD WILL NOT BE TAKEN BACK AFTER 30 DAYS & IF SEAL BROCKEN</li>
                              <li>SUBJECT TO ODISHA JURISDICTION ONLY</li>
                            </ul>
                          </div>
                        </div>
                        <div class="hr hr8 hr-double hr-dotted"></div>
                        <div class="row">
                          <div class="col-sm-12 pull-left"><strong>
                            <table width="100%" border="0">
                              <tr>
                                <td width="50%">.</td>
                                <td width="50%">&nbsp;</td>
                              </tr>
                              <tr>
                                <td><strong>Declaration:</strong></td>
                                <td align="right">For <strong><?php echo strtoupper($AR_FRAN['name']); ?></td>
                              </tr>
                              <tr>
                                <td rowspan="2">We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td align="right">Authorised Signatory</td>
                              </tr>
                            </table>
                          </div>
                        </div>
                        <div class="hr hr8 hr-double hr-dotted"></div>
                        <div class="row">
                          <div class="col-sm-9">This is a Computer Generated Invoice</div>
                          <div class="col-sm-3 pull-right">&nbsp;</div>
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
