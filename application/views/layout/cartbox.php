<?php
$cart_count = $this->OperationModel->getCartCount();
$cart_total = 	$this->OperationModel->getCartTotal();
$shipping_charge = $this->OperationModel->getShippingCharge();
 ?>
<div class="col-md-4 col-sm-12 col-xs-12 cartCosting">
        <div class="cartHolder">
          <div class="row">
            <div class="col-6 itemsCount alignLeft">
              <h5> <?php echo $cart_count; ?> Items </h5>
            </div>
            <div class="col-6 itemsCount alignRight">
              <p> <i class="fa fa-inr" aria-hidden="true"></i>  <?php echo number_format($cart_total,2); ?> </p>
            </div>
            <div class="col-6 prdShipping alignLeft">
              <h5> Shipping </h5>
            </div>
            <div class="col-6 prdShipping alignRight">
              <p> <i class="fa fa-inr" aria-hidden="true"></i>  <?php echo number_format($shipping_charge,2); ?> </p>
            </div>
            <div class="col-6 prdShipping alignLeft">
              <h5> Discount </h5>
            </div>
            <div class="col-6 prdShipping alignRight">
              <p> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format(0,2); ?> </p>
            </div>
            <div class="hrline"></div>
            <div class="col-6 prdTotal alignLeft">
              <h5> Total (tax incl) </h5>
            </div>
            <div class="col-6 prdTotal alignRight">
              <p> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($cart_total+$shipping_charge,2); ?></p>
            </div>
            <div class="buttoncheckout">
              <center>
                <a href="<?php echo generateSeoUrl("product","shipping",""); ?>" id="paymentPage" class="btn btn-info">Checkout</a>
              </center>
            </div>
          </div>
        </div>
      </div>