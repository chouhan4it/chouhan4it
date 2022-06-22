<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$fran_id = $this->session->userdata('fran_id');
$AR_FRAN = $model->getFranchiseeDetail($fran_id);

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
<style type="text/css">
	tr > td {
		font-size:11px;
	}
</style>
</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Invoice <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; View </small> </h1>
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
					  <a href="javascript:void(0)" id="PrintImg"> <i class="ace-icon fa fa-print"></i> </a>
						<table width="100%" border="0">
						<tr>
							<td colspan="2" align="left"><strong>TIN NO</strong> : <?php echo ucfirst(strtolower($AR_FRAN['tin_no'])); ?></td>
							</tr>
						<tr>
						  <td colspan="2" align="center"><h3 class="green">VAT INVOICE </h3></td>
						  </tr>
						<tr>
							<td colspan="2" align="center"><?php echo strtoupper($AR_FRAN['franchisee_type']); ?> - <strong><?php echo strtoupper($AR_FRAN['name']); ?></strong> </td>
							</tr>
						<tr>
							<td colspan="2" align="center"><strong><?php echo $AR_FRAN['address']; ?></strong> </td>
							</tr>
						
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
							</tr>
						<tr>
						  <td colspan="2" align="center">
						  <img src="<?php echo LOGO; ?>">
						  <div class="clearfix">&nbsp;</div>						  
						  <?php echo strtoupper($AR_FRAN['franchisee_type']); ?> of : <strong><?php echo WEBSITE; ?></strong><br>
						  	<?php echo ucfirst(strtoupper($model->getValue("POSTAL_ADDRESS"))); ?>						  	</td>
						  </tr>
						<tr>
						  <td colspan="2" align="center">&nbsp;</td>
						  </tr>
						
						<tr>
						  <td width="50%" align="center">R.C / P.C. NO : <span class="red"><?php echo $AR_ORDER['user_id']; ?></span> <br>
						  NAME:   <span class="blue"><?php echo $AR_ORDER['full_name']; ?></span><br>
						  MOB.NO : <span class="green"><?php echo $AR_ORDER['member_mobile']; ?></span><br>
						  <!--ADD : <?php echo ($AR_ORDER['order_address'])? $AR_ORDER['order_address']:$AR_ORDER['current_address']; ?>, <?php echo ($AR_ORDER['ship_city_name'])? $AR_ORDER['ship_city_name']:$AR_ORDER['city_name']; ?>,<?php echo ($AR_ORDER['ship_state_name'])? $AR_ORDER['ship_state_name']:$AR_ORDER['state_name']; ?>,<?php echo ($AR_ORDER['ship_pin_code'])? $AR_ORDER['ship_pin_code']:$AR_ORDER['pin_code']; ?>				-->		    <div class="clearfix">&nbsp;</div></td>
						  <td width="50%" align="center">INVOICE NO : <span class="red"><?php echo $AR_ORDER['invoice_number']; ?></span><br>
						  DATE: <span class="blue"><?php echo getDateFormat($AR_ORDER['invoice_date'],"d D M Y"); ?></span><br>
						 <!-- TIME :  <span class="green"><?php echo getDateFormat($AR_ORDER['invoice_date'],"h:i:s"); ?></span>--></td>
						</tr>
						
						<tr>
						  <td align="center">&nbsp;</td>
						  <td align="center">&nbsp;</td>
						  </tr>
						</table>

                      </div>         
  
                    </div>
                    <div>
                      <table width="100%"  class="table">
                        <thead>
                          <tr>
                            <th width="8%" align="left" class="center">Srl #</th>
                            <th width="9%" align="left" class="center">Category</th>
                            <th width="9%" align="left" class="center">P.Code</th>
                            <th width="9%" align="left" class="center">HSN Code</th>
                            <th align="left" class="center">Item Detail </th>
                            <th width="10%" align="left" class="center">Pack Size </th>
                            <th width="10%" align="left" class="center">Batch No </th>
                            <th width="3%" align="center" class="center">PV</th>
                            <th width="5%" align="center" class="center">MRP</th>
                            <th width="5%" align="center" class="center">RCP</th>
                            <th width="4%" align="center" class="center">Qty</th>
                            <th width="8%" align="center">Amount</th>
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
                            <td align="left" class="center"><?php echo $Ctrl; ?></td>
                            <td align="left"><span class="hidden-480"><span class="hidden-xs"><?php echo $AR_POST['category_name']; ?></span></span></td>
                            <td align="left"><span class="hidden-xs"><?php echo $AR_POST['post_code']; ?></span></td>
                            <td align="left"><span class="hidden-xs"><?php echo $AR_POST['post_hsn']; ?></span></td>
                            <td align="left" class="hidden-xs"><?php echo setWord($AR_ORD_DT['post_title'],30); ?> </td>
                            <td align="left" class="hidden-480"><span class="hidden-xs"><?php echo $AR_POST['post_size']; ?></span></td>
                            <td align="left" class="hidden-480"><span class="hidden-xs"><?php echo $AR_ORD_DT['batch_no']; ?> </span></td>
                            <td align="right" class="hidden-480"><?php echo $AR_ORD_DT['post_pv']; ?></td>
                            <td align="right" class="hidden-480"><span class="hidden-xs"><?php echo $AR_ORD_DT['original_post_price']; ?></span></td>
                            <td align="right" class="hidden-480"><span class="hidden-xs"><?php echo $AR_ORD_DT['post_price']; ?></span></td>
                            <td align="right" class="hidden-480"><?php echo $AR_ORD_DT['post_qty']; ?></td>
                            <td align="right"><?php echo number_format($AR_ORD_DT['net_amount'],2); ?></td>
                          </tr>
                         
                          <?php $Ctrl++; endforeach;
							$base_amount =  $order_total-$order_tax;
							$vat_age = ($order_tax/$base_amount)*100;
						    ?>
						   <tr>
                            <td colspan="9" align="left" class="left"><strong>Amount in words :</strong> &nbsp;&nbsp; <u><?php echo convert_number($order_total); ?> only </u> /-</td>
                            <td align="right" class="center"><strong>Total</strong></td>
                            <td align="right" class="hidden-480"><strong><?php echo number_format($order_qty); ?></strong></td>
                            <td align="right"><strong><?php echo number_format($order_total,2); ?></strong></td>
                          </tr>
						 
						   <tr>
						     <td colspan="12" align="left" class="left">	
							 <div class="clearfix">&nbsp;</div>
							 	<table width="100%" border="1" style="border-collapse:collapse;" class="table">
                               <tr>
                                 <td colspan="4"><strong>VAT SUMMARY </strong></td>
                                 <td colspan="4"><strong> INVOICE SUMMARY </strong></td>
                                 </tr>
                               <tr>
                                 <td align="center"><strong>VAT (% AGE) </strong></td>
                                 <td align="center"><strong>BASE AMOUNT </strong></td>
                                 <td align="center"><strong>TAX AMT. </strong></td>
                                 <td align="center"><strong>TOTAL AMT. </strong></td>
                                 <td align="center"><strong>TOTAL PV </strong></td>
                                 <td align="center"><strong>MRP AMT </strong></td>
                                 <td align="center"><strong>R.C. AMT. </strong></td>
                                 <td align="center"><strong>R.C. DESC </strong></td>
                               </tr>
                               <tr>
                                 <td align="right"><strong><?php echo number_format($vat_age,2); ?> % </strong></td>
                                 <td align="right"><strong><?php echo number_format($base_amount,2); ?></strong></td>
                                 <td align="right"><strong><?php echo number_format($order_tax,2); ?></strong></td>
                                 <td align="right"><strong><?php echo number_format($order_total,2); ?></strong></td>
                                 <td align="right"><strong><?php echo number_format($order_pv,2); ?></strong></td>
                                 <td align="right"><strong><?php echo number_format($order_mrp,2); ?></strong></td>
                                 <td align="right"><strong><?php echo number_format($order_total,2); ?></strong></td>
                                 <td align="right"><strong><?php echo number_format($order_mrp-$order_total,2); ?></strong></td>
                               </tr>
                             </table>							  </td>
				            </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="hr hr8 hr-double hr-dotted"></div>
                    <div class="row">
    
                      <div class="col-sm-12 pull-left"><strong>Terms & Condition</strong> 
					  	<ul  style="list-style-type:decimal; font-size:8px;">	
							<li>GOODS ONCE SOLD WILL NOT BE TAKEN BACK AFTER 30 DAYS & IF SEAL BROCKEN</li>
							<li>SUBJECT TO PUNE JURISDICTION E. &amp; O.E</li>
						</ul>
					  </div>
                    </div>
                    <div class="hr hr8 hr-double hr-dotted"></div>
                    <div class="row">
    				  <div class="col-sm-9">&nbsp;</div>
                      <div class="col-sm-3 pull-right"><strong>For <?php echo ucfirst(strtolower($AR_FRAN['name'])); ?> </strong>
					   <div style="border-bottom: 1px solid #ccc;display: block;border-bottom: 1px solid #ccc;margin:0px">&nbsp;</div>
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
