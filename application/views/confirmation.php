<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(1);
$order_id = _d($segment['order_id']);
$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.current_address,
			 tm.city_name, tm.state_name, tm.country_name, tm.pin_code, tm.member_mobile,
			 tad.current_address AS order_address, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name, tad.pin_code AS ship_pin_code,
			 tad.mobile_number AS ship_mobile_number,  tos.name AS order_state
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0  AND ord.order_id='".$order_id."'
			 GROUP BY ord.order_id";
$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,true);
$address_id = $AR_ORDER['address_id'];
#$AR_STORE = $model->getFranchiseeDetail($AR_ORDER['store_id']);
$AR_SHIP = $model->getShippingDetail($address_id);
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<body>

<!-- Page Wrapper -->
<div id="wrap" class="layout-3"> 
  
  <!-- Top bar -->
   <?php $this->load->view('layout/topheader'); ?>
  
  <!-- Header -->
  <?php $this->load->view('layout/headerweb'); ?>
  
  <!-- Content -->
  <div id="content"> 
    
    <!-- Ship Process -->
    <div class="ship-process padding-top-30 padding-bottom-30">
      <div class="container">
        <ul class="row">
          
          <!-- Step 1 -->
          <li class="col-sm-3">
            <div class="media-left"> <i class="fa fa-check"></i> </div>
            <div class="media-body"> <span>Step 1</span>
              <h6>Shopping Cart</h6>
            </div>
          </li>
          
          <!-- Step 2 -->
          <li class="col-sm-3">
            <div class="media-left"> <i class="fa fa-check"></i> </div>
            <div class="media-body"> <span>Step 2</span>
              <h6>Payment Methods</h6>
            </div>
          </li>
          
          <!-- Step 3 -->
          <li class="col-sm-3">
            <div class="media-left"> <i class="fa fa-check"></i> </div>
            <div class="media-body"> <span>Step 3</span>
              <h6>Delivery Methods</h6>
            </div>
          </li>
          
          <!-- Step 4 -->
          <li class="col-sm-3 current">
            <div class="media-left"> <i class="fa fa-check"></i> </div>
            <div class="media-body"> <span>Step 4</span>
              <h6>Confirmation</h6>
            </div>
          </li>
        </ul>
      </div>
    </div>
    
    <!-- Payout Method -->
    <section class="padding-bottom-60">
      <div class="container"> 
      <?php get_message(); ?>
        <!-- Payout Method -->
        <div class="pay-method check-out"> 
          
          <!-- Shopping Cart -->
          <div class="heading">
            <h2>Shopping Cart</h2>
            <hr>
          </div>
          
          <!-- Check Item List -->
		<?php 
            $QR_ORD_DT = "SELECT tod.* FROM tbl_order_detail AS tod WHERE tod.order_id='".$AR_ORDER['order_id']."'
            ORDER BY tod.order_detail_id ASC";
            $RS_ORD_DT = $this->SqlModel->runQuery($QR_ORD_DT);
            $Ctrl=1;
            foreach($RS_ORD_DT as $AR_ORD_DT):
            $order_pv +=($AR_ORD_DT['post_pv']*$AR_ORD_DT['post_qty']);
            $order_total +=$AR_ORD_DT['net_amount'];
        ?>
          <ul class="row check-item">
            <li class="col-xs-6">
              <p><?php echo setWord($AR_ORD_DT['post_title'],30); ?></p>
            </li>
            <li class="col-xs-2 text-center">
              <p><i class="fa fa-inr" aria-hidden="true"></i><?php echo $AR_ORD_DT['post_price']; ?></p>
            </li>
            <li class="col-xs-2 text-center">
              <p><?php echo number_format($AR_ORD_DT['post_qty']); ?> Items</p>
            </li>
            <li class="col-xs-2 text-center">
              <p><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_ORD_DT['net_amount'],2); ?></p>
            </li>
          </ul>
          <?php $Ctrl++; endforeach;  ?>
          <!-- Check Item List -->
          
          
          <!-- Payment information -->
          <div class="heading margin-top-50">
            <h2>Payment information</h2>
            <hr>
          </div>
          
          <!-- Check Item List -->
          <ul class="row check-item">
            <li class="col-xs-6">
              <p>Payment Type : <?php echo $AR_ORDER['payment']; ?> <small>(<?php echo $AR_ORDER['order_state']; ?>)</small></p>
            </li>
            <li class="col-xs-6 text-center">
              <p>Payment Date:   <?php echo $AR_ORDER['date_add']; ?></p>
            </li>
          </ul>
          
          <!-- Delivery infomation -->
          <div class="heading margin-top-50">
            <h2>Delivery infomation</h2>
            <hr>
          </div>
          
          <!-- Information -->
          <ul class="row check-item infoma">
            <li class="col-sm-3">
              <h6>Name</h6>
              <span><?php echo $AR_SHIP['full_name']; ?></span> </li>
            <li class="col-sm-3">
              <h6>Phone</h6>
              <span><?php echo $AR_SHIP['mobile_number']; ?></span> </li>
            <li class="col-sm-3">
              <h6>Country</h6>
              <span><?php echo $AR_SHIP['country_code']; ?></span> </li>
            <li class="col-sm-3">
              <h6>Email</h6>
              <span><?php echo $AR_SHIP['email_address']; ?></span> </li>
            <li class="col-sm-3">
              <h6>City</h6>
              <span><?php echo $AR_SHIP['city_name']; ?></span> </li>
            <li class="col-sm-3">
              <h6>State</h6>
              <span><?php echo $AR_SHIP['state_name']; ?></span> </li>
            <li class="col-sm-3">
              <h6>Zipcode</h6>
              <span><?php echo $AR_SHIP['pin_code']; ?></span> </li>
            <li class="col-sm-3">
              <h6>Address</h6>
              <span><?php echo wordwrap($AR_SHIP['current_address'],25,"<br>\n");  ?></span> </li>
          </ul>
          
          <!-- Information -->
          <ul class="row check-item infoma exp">
            <li class="col-sm-6"> <span>Expert Delivery</span> </li>
            <li class="col-sm-3">
              <h6>4 - 5 business day</h6>
            </li>
            <li class="col-sm-3">
              <h6>Order will not deliver on public holiday</h6>
            </li>
          </ul>
          
          <!-- Totel Price -->
          <div class="totel-price">
            <h4><small> Total Price: </small> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($order_total,2); ?></h4>
          </div>
        </div>
        
        <!-- Button -->
        <!--<div class="pro-btn"> <a href="#." class="btn-round btn-light">Back to Delivery</a> <a href="#." class="btn-round">Proceed to Checkout</a> </div>-->
      </div>
    </section>
  
    
    <!-- Newslatter -->
    <?php $this->load->view('layout/newsletter'); ?>
  </div>
  <!-- End Content --> 
  
  <!-- Footer -->
  <?php $this->load->view('layout/footer'); ?>
  
</div>
<!-- End Page Wrapper --> 

<?php $this->load->view('layout/footerjs'); ?>
</body>

</html>