<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);
$stock_id = ($form_data['stock_id'])? $form_data['stock_id']:_d($segment['stock_id']);
$Q_Master = "SELECT SUM(post_mrp*post_qty) AS total_mrp, SUM(post_price*post_qty) AS total_price, 
			 SUM(post_pv*post_qty) AS total_pv ,  order_no , order_no, order_date
			 FROM tbl_stock_master 
			 WHERE stock_id='$stock_id' ORDER BY stock_id ASC LIMIT 1";
$AR_Master = $this->SqlModel->runQuery($Q_Master,true);
$QR_MSTR =  "SELECT tsm.*, tf.name AS franchisee_name_from, ttf.name AS franchisee_name_to
			FROM tbl_stock_master AS tsm 
			LEFT JOIN tbl_franchisee AS tf ON tf.franchisee_id = tsm.franchisee_id_from
			LEFT JOIN tbl_franchisee AS ttf ON ttf.franchisee_id = tsm.franchisee_id_to
			WHERE  tsm.stock_id>0 AND tsm.order_no='".$AR_Master['order_no']."'
			ORDER BY tsm.stock_id DESC";
$AR_MSTR = $this->SqlModel->runQuery($QR_MSTR,true);
$AR_FRAN_FROM = $model->getFranchiseeDetail(getTool($AR_MSTR['franchisee_id_from'],$model->getDefaultFranchisee()));
$AR_FRAN_TO = $model->getFranchiseeDetail($AR_MSTR['franchisee_id_to']);

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
                                        <td><strong><?php echo strtoupper($AR_FRAN_FROM['name']); ?></strong></td>
                                      </tr>
                                      <tr>
                                        <td><?php echo wordwrap($AR_FRAN_FROM['address'],30,"<br>\n");  ?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>GSTIN/UIN:</strong> <?php echo strtoupper($AR_FRAN_FROM['gst_no']); ?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>E-Mail:</strong> <?php echo ($AR_FRAN_FROM['email']); ?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Phone:</strong> <?php echo ($AR_FRAN_FROM['mobile']); ?></td>
                                      </tr>
                                    </table></td>
                                  <td align="right"><table width="100%" border="0">
                                      <tr>
                                        <td><table width="100%" border="0">
                                            <tr>
                                              <td width="47%"><strong>Invoice No : </strong> <?php echo $AR_Master['order_no']; ?></td>
                                              <td width="53%" rowspan="6"><img src="<?php echo LOGO; ?>" width="150"></td>
                                            </tr>
                                            <tr>
                                              <td><strong>Date  :</strong> <?php echo getDateFormat($AR_Master['order_date'],"D d M Y"); ?></td>
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td><strong>Supplier's Ref.:</strong></td>
                                            </tr>
                                            <tr>
                                              <td><strong><?php echo strtoupper($AR_FRAN_FROM['franchisee_type']); ?></strong></td>
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
                                  <td align="left"><?php echo strtoupper($AR_FRAN_TO['name']); ?></td>
                                  <td align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">Mobile:  <?php echo ($AR_FRAN_FROM['mobile']); ?></td>
                                  <td align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="50%" align="left">Email: <span class="green"> <?php echo ($AR_FRAN_FROM['email']); ?></span></td>
                                  <td width="50%" align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">Address : <?php echo wordwrap($AR_FRAN_TO['address'],30,"<br>\n");  ?></td>
                                  <td align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left"><strong>GSTIN : <?php echo strtoupper($AR_FRAN_FROM['gst_no']); ?></strong></td>
                                  <td align="center">&nbsp;</td>
                                </tr>
                              </table>
                            </div>
                            <div class="hr hr8 hr-double hr-dotted"></div>
                            <div>
                              <table width="100%" border="0">
                                <tr>
                                  <td colspan="2" align="center"><table class="table" width="100%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #EBEBEB;">
                          <tr>
                            <td width="10%" align="center" class="cmntext"><strong>SRL # </strong></td>
                            <td width="18%" align="left" class="cmntext"><strong>PRODUCT</strong></td>
                            <td width="10%" align="left" class="cmntext"><strong>MRP</strong></td>
                            <td align="center" class="cmntext"><strong>PRICE</strong></td>
                            <td align="center" class="cmntext"><strong>PV</strong></td>
                            <td align="center" class="cmntext"><strong>QTY</strong></td>
                            <td align="center" class="cmntext"><strong>TOTAL</strong></td>
                          </tr>
                          <?php
						$Ctrl=1;
						$Q_STOCK = "SELECT tsm.*, tpl.post_title, tpl.post_price AS product_price FROM tbl_stock_master AS tsm 
						LEFT JOIN tbl_post AS tp ON tp.post_id=tsm.post_id
						LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
						WHERE tsm.order_no='".$AR_Master[order_no]."' ORDER BY tsm.post_qty ASC";
						$RS_STOCK = $this->SqlModel->runQuery($Q_STOCK);
						foreach($RS_STOCK as $AR_STOCK){
						$net_pay_amount +=$AR_STOCK['post_amount'];
						?>
                          <tr class="<?php echo ($AR_STOCK['stock_sts']=="R")? "text-danger":""; ?>">
                            <td align="center" class="cmntext"><?php echo $Ctrl;?></td>
                            <td align="left" class="cmntext"><?php echo $AR_STOCK['post_title'];?></td>
                            <td align="left" class="cmntext"><?php echo number_format($AR_STOCK['product_price']);?></td>
                            <td width="12%" align="center" class="cmntext"><?php echo number_format($AR_STOCK['post_price']);?></td>
                            <td width="12%" align="center" class="cmntext"><?php echo $AR_STOCK['post_pv'];?></td>
                            <td width="19%" align="center" class="cmntext"><?php echo $AR_STOCK['post_qty'];?></td>
                            <td width="19%" align="center" class="cmntext"><strong><?php echo number_format($AR_STOCK['post_amount']);?></strong></td>
                          </tr>
                          <?php 
						$Ctrl++;
						} 
						?>
                          <tr>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="center" class="cmntext"><strong>TOTAL</strong></td>
                            <td align="center" class="">&nbsp;<strong><?php echo number_format($net_pay_amount,2);?></strong></td>
                          </tr>
                          <tr>
                            <td colspan="7" align="right" class="cmntext"><strong>(Rupees <?php echo convert_number($net_pay_amount);?> Only)</strong></td>
                          </tr>
                          <tr>
                            <td colspan="7" align="right" class="cmntext">
                            	  <table width="100%" border="1" style="border-collapse:collapse;">
                                <tr>
                                  <td width="45%" rowspan="2" align="center"><strong>HSN/SAC</strong></td>
                                  <td width="13%" align="center"><strong>Taxable Value</strong></td>
                                  <td colspan="2" align="center"><strong>CGST</strong></td>
                                  <td colspan="2" align="center"><strong>SGST</strong></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td width="11%" align="center">Rate</td>
                                  <td width="10%" align="center">Amount</td>
                                  <td width="11%" align="center">Rate</td>
                                  <td width="10%" align="center">Amount</td>
                                </tr>
                                <?php 
							  	$QR_ORD_TAX = "SELECT SUM(tsl.post_pv*tsl.trans_qty) AS order_pv, SUM(tsl.post_mrp*tsl.trans_qty) AS  order_mrp  ,
								SUM(tsl.trans_amount*tsl.trans_qty) AS order_price,
								tsl.tax_age, tsl.trans_qty, tsl.net_amount
								FROM tbl_stock_ledger AS tsl 
								WHERE  tsl.ref_no='".$AR_Master['order_no']."'
								GROUP BY tsl.tax_age 
								ORDER BY tsl.trans_id ASC"; 
							  	$RS_ORD_TAX = $this->SqlModel->runQuery($QR_ORD_TAX);
								
								foreach($RS_ORD_TAX as $AR_ORD_TAX):
								
									$post_tax = $AR_ORD_TAX['tax_age'];
									$order_tax_devide = $post_tax/2;					
									$order_price = ( $AR_ORD_TAX['order_price']  /  ( ($post_tax/100)+1 ) );								
									
									$order_tax_calc = ($order_price*$order_tax_devide)/100;
									
									$sum_order_price +=$order_price;
									$sum_order_tax_calc +=$order_tax_calc;
							  ?>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td align="center"><?php echo number_format($order_price,2); ?></td>
                                  <td align="center"><?php echo number_format($order_tax_devide,2); ?> %</td>
                                  <td align="center"><?php echo number_format($order_tax_calc,2); ?></td>
                                  <td align="center"><?php echo number_format($order_tax_devide,2); ?> %</td>
                                  <td align="center"><?php echo number_format($order_tax_calc,2); ?></td>
                                </tr>
                                <?php 
							 	endforeach;
								$gst_order_tax_calc = $sum_order_tax_calc*2;
								
							  ?>
                                <tr>
                                  <td align="right"><strong>Total</strong> :</td>
                                  <td align="center"><strong><?php echo number_format($sum_order_price,2); ?></strong></td>
                                  <td align="center">&nbsp;</td>
                                  <td align="center"><strong><?php echo number_format($sum_order_tax_calc,2); ?></strong></td>
                                  <td align="center">&nbsp;</td>
                                  <td align="center"><strong><?php echo number_format($sum_order_tax_calc,2); ?></strong></td>
                                </tr>
                                <tr>
                                  <td colspan="6">Tax Amount (In words): <u><?php echo convert_number($gst_order_tax_calc); ?> Only /-</u></td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                      </table></td>
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
                                <td align="right">For <strong><?php echo strtoupper($AR_FRAN_FROM['name']); ?></td>
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
