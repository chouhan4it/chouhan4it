<?php 
$model = new OperationModel();
$cart_total_mrp = $model->getCartTotalMrp();		
$cart_total = $model->getCartTotal();
$cart_pv = $model->getCartTotalPv();
$cart_bv = $model->getCartTotalBv();
$cart_count = $model->getCartCount();
 ?>
<div class="row">
            <div class="col-lg-6">&nbsp;</div>
            <div class="col-lg-6">
              <div class="portlet">
                <div class="portlet-heading bg-danger">
                  <h3 class="portlet-title"> Your cart </h3>
                  <div class="portlet-widgets"> </div>
                  <div class="clearfix"></div>
                </div>
                <div id="bg-danger" class="panel-collapse collapse in">
                  <div class="portlet-body">
                    <table class="table table-actions-bar" width="100%">
                      <thead>
                        <tr>
                          <th>  Cart </th>
                          <th>&nbsp;</th>
                          <th>Count</th>
                          <th>MRP</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Item</td>
                          <td>&nbsp;</td>
                          <td><span id="cart_count"><?php echo ($cart_count>0)? $cart_count:0; ?></span></td>
                          <td><?php echo CURRENCY; ?><span id="cart_total_mrp"><?php echo ($cart_total_mrp>0)? $cart_total_mrp:0; ?></span></td>
                          <td><?php echo CURRENCY; ?><span id="cart_total"><?php echo ($cart_total>0)? number_format($cart_total,2):0; ?></span></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="4">
						  
						  <a <?php echo ($cart_total==0)? "disabled='disabled'":""; ?> href="<?php echo generateSeoUrlMember("order","shipping",""); ?>" class="btn btn-purple pull-right col-md-offset-1" id="checkout_button"> <span>Proceed to Checkout <i class="fa fa-arrow-right"></i></span></a>
						  <a onclick="return confirm('Make sure, you want to save this cart')" <?php echo ($cart_total==0)? "disabled='disabled'":""; ?> href="<?php echo generateSeoUrlMember("order","cartsave",""); ?>" class="btn btn-danger pull-right" id="checkout_button"> <span>Save <i class="fa fa-floppy-o"></i></span></a></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>