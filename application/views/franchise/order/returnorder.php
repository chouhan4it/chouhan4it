<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$current_date = InsertDate($today_date);
$segment = $this->uri->uri_to_assoc(2);
$order_id = _d($segment['order_id']);
$CONFIG_ORDER_RETURN = $model->getValue("CONFIG_ORDER_RETURN");
$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.current_address,
			 tm.city_name, tm.state_name, tm.country_name, tm.pin_code, tm.member_mobile,
			 tad.current_address AS order_address, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name, tad.country_code AS ship_country_code,
			 tos.name AS order_state
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0  AND ord.order_id='".$order_id."'
			 GROUP BY ord.order_id";
$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,true);

$day_diff = dayDiff($current_date,$AR_ORDER['date_add']);
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
<script src="<?php echo BASE_PATH; ?>jquery/jquery-1.8.2.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/jquery/jquery.livequery.js"></script>
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<script src="<?php echo BASE_PATH; ?>jquery/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>jquery/jquery.autocomplete.css" />
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
<?= $this->load->view(FRANCHISE_FOLDER.'/order/orderreturnjs'); ?>
</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(FRANCHISE_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Return <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Order </small> </h1>
      </div>
      <!-- /.page-header -->
	
      <div class="row">
        <?php  get_message(); ?>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
           <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("order","returnorder",""); ?>" method="post" onSubmit="return ValidateForm()">
		   	<div class="row">
				<div class="col-xs-12">
                <div class="clearfix">
                  <div class="row">
                    <div class="col-md-4">
					<ul style="list-style-type:none;">
						<li>Order No : <strong><?php echo $AR_ORDER['order_no']; ?></strong></li>
						<li>Customer Name : <strong><?php echo $AR_ORDER['full_name']; ?> &nbsp;[<?php echo $AR_ORDER['user_id']; ?>]</strong></li>
                     	<li>Order Date : <strong><?php echo getDateFormat($AR_ORDER['date_add'],"d D M Y h:i"); ?></strong></li>
					 </ul>
                    </div>
                  </div>
                </div>
              </div>
			</div>
              <hr>
            <div class="row">
              <div class="clearfix">
                <table width="100%" class="table">
                  <tr>
                    <td colspan="5"><table class="table table-striped table-bordered table-hover" id="tblProdDtls">
                        <thead>
                          <tr>
                            <th width="201">&nbsp;</th>
                            <th width="201">Item </th>
                            <th width="117">Unit Price </th>
                            <th width="117">Qty</th>
                            <th width="117">Return Qty </th>
                            <th width="117">Total Amount </th>
                          </tr>
                        </thead>
                        <tbody>
                         <?php 
							$QR_ORD_DT = "SELECT tod.* FROM tbl_order_detail AS tod WHERE tod.order_id='".$AR_ORDER['order_id']."' ORDER BY tod.order_detail_id ASC";
							$RS_ORD_DT = $this->SqlModel->runQuery($QR_ORD_DT);
							$Li=1;
							foreach($RS_ORD_DT as $AR_ORD_DT):
							
								$order_pv +=($AR_ORD_DT['post_pv']*$AR_ORD_DT['post_qty']);
								$order_total +=$AR_ORD_DT['net_amount'];
						?>
                          <tr>
                            <td><img src="<?php echo $model->getDefaultPhoto($AR_ORD_DT['post_id']); ?>" width="35px" height="35px" class="img-responsive"></td>
                            <td>
								
								<?php echo setWord($AR_ORD_DT['post_title'],30); ?>
                              <input name="post_id[]" type="hidden" class="post_id_class" value="<?php echo $AR_ORD_DT['post_id']; ?>"  id="post_id<?php echo $Li; ?>" ref="<?php echo $Li; ?>" />                       
							    <input name="order_detail_id[]" type="hidden"  value="<?php echo $AR_ORD_DT['order_detail_id']; ?>"  id="order_detail_id<?php echo $Li; ?>" ref="<?php echo $Li; ?>" />							       </td>
                            <td><?php echo $AR_ORD_DT['post_price']; ?>
							<input id="post_price<?php echo $Li; ?>" name="post_pd_price[]"  type="hidden" ref="<?php echo $Li; ?>" value="<?php echo $AR_ORD_DT['post_price']; ?>">
							<input name="post_pv[]" type="hidden"   id="post_pv<?php echo $Li?>" ref="<?php echo $Li?>"  value="<?php echo $AR_ORD_DT['post_pv']; ?>"/>							</td>
                            <td><?php echo $AR_ORD_DT['post_qty']; ?></td>
                            <td><input name="post_qty[]" type="text" class="col-xs-6 col-sm-6 validate[required,custom[integer]] CalcTotalReturn" id="post_qty<?php echo $Li; ?>" ref="<?php echo $Li; ?>" value="0" />                            </td>
                            <td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                <input id="post_sum_price<?php echo $Li; ?>" placeholder="Total Price" name="post_sum_price[]"  class="col-xs-12 col-sm-12"  type="text" ref="<?php echo $Li; ?>" readonly>
								<input id="post_sum_pv<?php echo $Li?>"  name="post_sum_pv[]"   type="hidden" ref="<?php echo $Li?>">
                              </div></td>
                          </tr>
                         <?php $Li++; endforeach;  ?>
                        </tbody>
                      </table></td>
                  </tr>
				  <tr>
				  	<td colspan="5">
						<div class="row">
							<div class="col-md-6">
    		                      <div class="pull-right"> Return Type : </div>
	                        </div>
							<div class="col-md-5">
								 <div class="pull-right">
								<input type="radio" name="return_type" id="return_type"  value="OPEN" class="validate[required]"> Opened &nbsp;&nbsp;
								<input type="radio" name="return_type" id="return_type" value="SEAL" class="validate[required]"> Sealed &nbsp;&nbsp;
								</div>
							</div>
						</div>
					</td>
				  </tr>
				   <tr>
				  	<td colspan="5">
						<div class="row">
							<div class="col-md-6">
    		                      <div class="pull-right"> Return Charge : </div>
	                        </div>
							<div class="col-md-5">
								 <div class="pull-right">
									<select name="return_id" id="return_id" class="form-control validate[required]">
										<option value="">---- select return ----</option>
										<?php echo DisplayCombo($return_id,"RETURN_CHARGE"); ?>
									</select>
								</div>
							</div>
						</div>
					</td>
				  </tr>
                  <tr>
                    <td colspan="5"><div class="row">
                        <div class="col-md-6">
                          <div class="pull-right"> Total Return Amount : </div>
                        </div>
                        <div class="col-md-5">
                          <div class="pull-right">
                            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                              <input id="net_payable" placeholder="Total Amount" name="net_payable"  class="col-xs-12 col-sm-12 CalcTotal"  type="text">
                            </div>
                          </div>
                        </div>
						<div class="col-md-1"> <img src="<?php echo BASE_PATH; ?>setupimages/refresh2.png" alt="Refresh" align="absmiddle" id="NetCalcTotal"  style="cursor:pointer" /> </div>
                      </div></td>
                  </tr>
				  
				  <tr>
                    <td colspan="5"><div class="row">
                        <div class="col-md-6">
                          <div class="pull-right"> Return Detail : </div>
                        </div>
                        <div class="col-md-5">
                          <div class="pull-right">
                             <textarea name="payment" class="col-xs-12 col-sm-12" id="payment" placeholder="Order Return Detail"></textarea>
                          </div>
                        </div>
                         <div class="col-md-1">&nbsp;</div>
                      </div></td>
                  </tr>
                </table>
                <div class="row">
                  <div class="col-md-12">
                    <div class="pull-right">	
					  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                      <input   type="hidden" id="net_pv"  name="net_pv"  >
					  <input type="hidden" name="order_id" id="order_id" value="<?php echo _e($AR_ORDER['order_id']); ?>">
                      <input type="hidden" name="franchisee_id" id="franchisee_id" value="<?php echo $this->session->userdata('fran_id'); ?>">
					  <?php if($day_diff<=$CONFIG_ORDER_RETURN){ ?>
                      <button type="submit" name="submitOrderReturn" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Submit </button>
                      <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
					  <?php }else{ ?>
					  	<div class="alert alert-warning">Unable to return order as it pass order return policy days (<?php echo $CONFIG_ORDER_RETURN; ?>)</div>
					  <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.page-content -->
  </div>
</div>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<?php jquery_validation(); auto_complete(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
	});
</script>
</body>
</html>
