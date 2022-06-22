<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(2);
$member_id = $this->session->userdata('mem_id');
$cart_session = $this->session->userdata('session_id');

$shipping_charge = $model->getShippingCharge();

$QR_PAGES = "SELECT tc.*,  tpl.lang_id, tpl.post_slug, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_pv,  tpl.update_date 
			FROM tbl_cart AS tc
			LEFT JOIN tbl_post AS tp ON tp.post_id=tc.post_id 
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			WHERE tp.delete_sts>0  AND tc.cart_session='".$cart_session."'
			GROUP BY tc.cart_id  
			ORDER BY tc.cart_id ASC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
$cart_total = 	$this->OperationModel->getCartTotal();
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
<!--<![endif]-->

<!-- Mirrored from opencart.dostguru.com/FD01/fruitino_04/index.php?route=account/login by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Dec 2021 08:33:10 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<!-- /Added by HTTrack -->
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<?php $this->load->view('layout/svgsymbol'); ?>
<body>
<?php $this->load->view('layout/header'); ?>
<div id="checkout-cart" class="container">
  <div class="row">
    <div id="content" class="col-xs-12 acpage">
      <ul class="breadcrumb">
        <li><a href="<?php echo BASE_PATH; ?>"><i class="fa fa-home"></i></a></li>
        <li><a href="<?php echo generateSeoUrl("product","cart",""); ?>">Shopping Cart</a></li>
      </ul>
      <div class="infobg">
        <h1>Shopping Cart
          &nbsp;(<?php echo number_format($cart_total,2); ?>) </h1>
        <form action="https://opencart.dostguru.com/FD01/fruitino_04/index.php?route=checkout/cart/edit" method="post" enctype="multipart/form-data">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td class="text-center">Image</td>
                  <td class="text-left">Product Name</td>
                  <td class="text-left">Model</td>
                  <td class="text-left">Quantity</td>
                  <td class="text-right">Unit Price</td>
                  <td class="text-right">Total</td>
                </tr>
              </thead>
              <tbody>
				<?php  
                $Ctrl=1;
                if($PageVal['TotalRecords'] > 0){
                foreach($PageVal['ResultSet'] as $AR_DT):
                $net_cat_total +=$AR_DT['cart_total'];
                ?>
                <tr>
                  <td class="text-center"><a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_DT['post_id'])); ?>/<?php echo $AR_DT['post_slug']; ?>"><img src="<?php echo $model->getFileSrc($AR_DT['cart_image_id']); ?>" alt="<?php echo $AR_DT['cart_title']; ?>" title="<?php echo $AR_DT['cart_title']; ?>" width="100" class="img-thumbnail"></a></td>
                  <td class="text-left"><a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_DT['post_id'])); ?>/<?php echo $AR_DT['post_slug']; ?>"><?php echo $AR_DT['cart_title']; ?></a></td>
                  <td class="text-left"><?php echo $AR_DT['cart_title']; ?></td>
                  <td class="text-left"><div class="input-group btn-block" style="width: 200px;">
                  	<input class="form-control" name="post_qty" id="post_qty<?php echo $AR_DT['post_id']; ?>" rel="<?php echo $AR_DT['post_id']; ?>" attr_id="<?php echo $AR_DT['post_attribute_id']; ?>" min="1" max="100" value="<?php echo $AR_DT['cart_qty']; ?>" type="text">
                      <span class="input-group-btn cartpsp">
                      <button type="submit"  title="" class="btn btn-danger" ><i class="fa fa-refresh"></i></button>
                      <a  class="btn btn-danger" href="<?php echo generateSeoUrl("product","cart",array("cart_id"=>_e($AR_DT['cart_id']),"action_request"=>"DELETE")); ?>" onClick="return confirm('Make sure want to delete <?php echo $AR_DT['cart_title']; ?> item?')"><i class="fa fa-times-circle"></i></a>
                      </span></div></td>
                  <td class="text-right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_DT['cart_price'],2); ?></td>
                  <td class="text-right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_DT['cart_total'],2); ?></td>
                </tr>
				<?php 
                $Ctrl++; 
                endforeach;
                }else{
                ?>
                <tr>
                <td colspan="6" class="product_remove">No item in your cart</td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <h1>What would you like to do next?</h1>
        <p>Choose if you have a discount code or reward points you want to use or would like to estimate your delivery cost.</p>
        <div class="panel-group" id="accordion">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title"><a href="#collapse-coupon" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">Use Coupon Code <i class="fa fa-caret-down"></i></a></h4>
            </div>
            <div id="collapse-coupon" class="panel-collapse collapse">
              <div class="panel-body">
                <label class="col-sm-2 control-label" for="input-coupon">Enter your coupon here</label>
                <div class="input-group">
                  <input type="text" name="coupon" value="" placeholder="Enter your coupon here" id="input-coupon" class="form-control">
                  <span class="input-group-btn">
                  <input type="button" value="Apply Coupon" id="button-coupon" data-loading-text="Loading..." class="btn btn-primary">
                  </span></div>
                
              </div>
            </div>
          </div>
          
        </div>
        <br>
        <div class="row">
          <div class="col-lg-8 col-xs-12 col-md-6"></div>
          <div class="col-lg-4 col-xs-12 col-sm-12 col-md-6">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td class="text-right"><strong>Sub-Total:</strong></td>
                  <td class="text-right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($net_cat_total,2); ?></td>
                </tr>
                <tr>
                  <td class="text-right"><strong>Shipping:</strong></td>
                  <td class="text-right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($shipping_charge,2); ?></td>
                </tr>
                
                <tr>
                  <td class="text-right"><strong>Total:</strong></td>
                  <td class="text-right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($net_cat_total+$shipping_charge,2); ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo generateSeoUrl("product","catalog",""); ?>" class="btn btn-primary">Continue Shopping</a></div>
          <div class="pull-right"><a href="<?php echo generateSeoUrl("product","shipping",""); ?>" class="btn btn-primary">Checkout</a></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
</body>
<script type="text/javascript">
$(".update_cart").on('blur',function(){
	var post_id = $(this).attr("rel");
	var post_attribute_id = $(this).attr("attr_id");
	var post_qty = parseInt($("#post_qty"+post_id).val());
	var total_qty = parseInt(post_qty);
	if(total_qty>=0){
		process_cart(post_id,post_attribute_id,total_qty);
	}
	
});
		
function process_cart(post_id,post_attribute_id,total_qty){
	if(post_id>0){
		var data = {
			switch_type : "CART",
			post_attribute_id: post_attribute_id,
			post_id : post_id,
			total_qty : total_qty
		};
		var URL_CART = "<?php echo BASE_PATH; ?>json/jsonhandler";
		$.getJSON(URL_CART,data,function(JsonEval){	
			if(JsonEval.status>0){
				window.location.reload();
			}else{
				alert("Please enter valid quantity?");
			}
		});
	}
}
	
</script>
</html>
