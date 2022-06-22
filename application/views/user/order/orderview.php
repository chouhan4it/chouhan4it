<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);
$order_id = _d($segment['order_id']);
$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.current_address,
			 tm.city_name, tm.state_name, tm.country_name, tm.pin_code, tm.member_mobile,
			 tad.current_address AS order_address, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name, tad.pin_code AS ship_pin_code,
			 tad.mobile_number AS ship_mobile_number,  tos.name AS order_state, toc.cancel_id
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 LEFT JOIN tbl_order_cancel AS toc ON toc.order_id=ord.order_id
			 WHERE ord.order_id>0  AND ord.order_id='".$order_id."'
			 GROUP BY ord.order_id";
$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,true);
$AR_STORE = $model->getFranchiseeDetail($AR_ORDER['franchisee_id']);
$trns_remark = "Order N-".$AR_ORDER['order_no'];
$wallet_paid = $model->getTotalWalletPaidToOrder($AR_ORDER['member_id'],"ORDER",$trns_remark);

?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
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
        <h4 class="page-title">Order</h4>
        <p class="text-muted page-title-alt">Order Invoice</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <?php echo get_message(); ?>
      <div class="col-md-12">
        <div class="panel panel-default">
          <!-- <div class="panel-heading">
                                <h4>Invoice</h4>
                            </div> -->
          <div class="panel-body">
            <div class="clearfix">
              <div class="pull-left">
                <h4 class="text-right"><?php echo ucfirst(strtolower(WEBSITE)); ?></h4>
              </div>
              <div class="pull-right">
                <h4>Order No #  <?php echo $AR_ORDER['order_no']; ?><br>
                  <strong><?php echo getDateFormat($AR_ORDER['date_add'],"d D M Y h:i"); ?></strong> </h4>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-12">
                <div class="pull-left m-t-30">
                  <address>
                  <strong><?php echo $AR_ORDER['full_name']; ?> &nbsp;[<?php echo $AR_ORDER['user_id']; ?>]</strong><br>
                  <?php if($AR_ORDER['store_id']>0){ ?>
                  <?php echo $AR_STORE['name']; ?><br>
                  <?php echo $AR_STORE['address']; ?><br>
                  <abbr title="Phone">P:</abbr> <?php echo $AR_STORE['mobile']; ?>
                  <?php }else{ ?>
                  <?php echo getTool($AR_ORDER['order_address'],$AR_ORDER['current_address']); ?><br>
                  <?php echo getTool($AR_ORDER['ship_city_name'],$AR_ORDER['city_name']); ?>, <?php echo getTool($AR_ORDER['ship_state_name'],$AR_ORDER['state_name']); ?><br>
				  <?php echo getTool($AR_ORDER['ship_pin_code'],$AR_ORDER['pin_code']); ?><br>
                  <abbr title="Phone">P:</abbr> <?php echo getTool($AR_ORDER['ship_mobile_number'],$AR_ORDER['member_mobile']); ?>
                  <?php } ?>
                  
                  </address>
                </div>
                <div class="pull-right m-t-30">
                  <p><strong>Order Date: </strong> <?php echo getDateFormat($AR_ORDER['date_add'],"M d, Y"); ?></p>
                  <p class="m-t-10"><strong>Order Status: </strong> <span class="label label-pink"><?php echo $AR_ORDER['order_state']; ?></span></p>
                  <p class="m-t-10"><strong>Order ID: </strong> # <?php echo $AR_ORDER['order_no']; ?></p>
                </div>
              </div>
            </div>
            <div class="m-h-50"></div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table m-t-30">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>&nbsp;</th>
                        <th>Code</th>
                        <th>Item</th>
                        <th>Detail</th>
                        <th align="right">Qty</th>
                        <th align="right">Unit Cost</th>
                        <th align="right">Total</th>
                      </tr>
                    </thead>
                    <tbody>
						 <?php 
							$QR_ORD_DT = "SELECT tod.* FROM tbl_order_detail AS tod WHERE tod.order_id='".$AR_ORDER['order_id']."'
							ORDER BY tod.order_detail_id ASC";
							$RS_ORD_DT = $this->SqlModel->runQuery($QR_ORD_DT);
							$Ctrl=1;
							foreach($RS_ORD_DT as $AR_ORD_DT):
							$order_pv +=($AR_ORD_DT['post_pv']*$AR_ORD_DT['post_qty']);
							$order_total +=$AR_ORD_DT['net_amount'];
							
							$shipping_total +=($AR_ORD_DT['post_shipping']*$AR_ORD_DT['post_qty']);
						?>
                      <tr>
                        <td><?php echo $Ctrl; ?></td>
                        <td><img src="<?php echo $model->getFileSrc($AR_ORD_DT['post_image_id']); ?>" width="40" class="img-responsive"></td>
                        <td><?php echo ($AR_ORD_DT['post_code']); ?></td>
                        <td><a target="_blank" href="<?php echo generateSeoUrl("product","detail",array("post_id"=>($AR_ORD_DT['post_id']))); ?>"><?php echo ($AR_ORD_DT['post_title']); ?></a></td>
                        <td><?php echo $AR_ORD_DT['post_attribute']; ?></td>
                        <td align="right"><?php echo $AR_ORD_DT['post_qty']; ?></td>
                        <td align="right"><i class="fa fa-inr"></i>&nbsp;<?php echo $AR_ORD_DT['post_price']; ?></td>
                        <td align="right"><i class="fa fa-inr"></i>&nbsp; <?php echo number_format($AR_ORD_DT['net_amount'],2); ?></td>
                      </tr>
					   <?php $Ctrl++; endforeach;  ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row" style="border-radius: 0px;">
              <div class="col-md-3 col-md-offset-9">
                <p class="text-right"><b>Sub-total:</b> &nbsp; <strong><i class="fa fa-inr"></i>&nbsp;<?php echo number_format($order_total,2); ?></strong></p>
        <!--        <p class="text-right">Discout: 12.9%</p>
                <p class="text-right">VAT: 12.9%</p>-->
                 <hr>
                <p class="text-right">Shipping Charge: <i class="fa fa-inr"></i>&nbsp;<?php echo number_format($shipping_total,2); ?></p>
                <hr>
                <p class="text-right">Net Total: <i class="fa fa-inr"></i>&nbsp;<?php echo number_format($order_total+$shipping_total,2); ?></p>
				
                <!--<hr><p class="text-right">Net BV: <i class="fa fa-dot-circle-o"></i>&nbsp;<?php echo number_format($order_pv,2); ?></p>-->
               
                
                
              </div>
            </div>
          
            <hr>
             <p><strong>Shipping Date :</strong> <?php echo $AR_ORDER['ship_date']; ?></p>
            <p>	<strong>Shipping Detail :</strong> <?php echo getTool($AR_ORDER['order_ship'],"Shipping is under process..."); ?></p>
           
            <hr>
            <div class="hidden-print">
            <?php if($AR_ORDER['cancel_id']==''){ ?>
               <div class="pull-left"> <a href="<?php echo generateSeoUrlMember("order","cancel",array("order_id"=>_e($AR_ORDER['order_id']))); ?>" onClick="return confirm('Make sure want to cancel this order?')" class="text text-danger">Cancel Order ?</a>  </div>
              <?php } ?>
              <div class="pull-right"> <a href="javascript:void(0)" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></a>  </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
</html>
