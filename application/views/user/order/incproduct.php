<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(2);
$post_id = _d($segment['post_id']);
$member_id = $this->session->userdata('mem_id');
$AR_DT = $model->getPostDetail($post_id);
$model->postView($post_id,$member_id);
$post_qty =  $model->getCartQty($AR_DT['post_id']); 
?>
<!DOCTYPE html>
<html>
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>u-assets/plugins/magnific-popup/dist/magnific-popup.css"/>
</head>
<body>

<div class="wrapper" style="margin-top:0px;">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
                <div class="row">
                  <div class="col-sm-6 col-lg-3 col-md-4 mobiles">
                    <div class="product-list-box thumb"> <a href="<?php echo $model->getDefaultPhoto($AR_DT['post_id']); ?>" class="image-popup" title="<?php echo $AR_DT['post_title']; ?>"> <img src="<?php echo $model->getDefaultPhoto($AR_DT['post_id']); ?>" class="thumb-img" alt="work-thumbnail"> </a> </div>
                  </div>
                  <div class="col-md-6">
                    <h3 class="item_name"><?php echo $AR_DT['post_title']; ?></h3>
                    <div class="row">
                      <div class="col-md-12"> <?php echo $AR_DT['short_desc']; ?> </div>
                      <div class="clearfix">&nbsp;</div>
                      <div class="col-md-12">
                        <h5 class="m-0"><span class="text-custom"><i class="fa fa-inr"></i> <?php echo number_format($AR_DT['post_price'],2); ?></span> <span class="text-muted m-l-15"> Point value : <?php echo $AR_DT['post_pv']; ?></span></h5>
						<div class="rating">
							<ul class="list-inline">
								<li><a class="fa fa-star" href="#"></a></li>
								<li><a class="fa fa-star" href="#"></a></li>
								<li><a class="fa fa-star" href="#"></a></li>
								<li><a class="fa fa-star" href="#"></a></li>
								<li><a class="fa fa-star-o" href="#"></a></li>
							</ul>
						</div>
						<div class="">
							Quantity : <input style="width:50px;" type="text" name="post_qty" id="post_qty_inc" value="<?php echo ($post_qty>0)? $post_qty:1; ?>" maxlength="5">
						</div>
                        <div class="clearfix">&nbsp;</div>
                        <a type="button" rel="<?php echo $AR_DT['post_id']; ?>" href="javascript:void(0)" class="w3ls-cart btn btn-success plus_qty" ><i class="fa fa-cart-plus" aria-hidden="true"></i> Add to cart</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="row">
                  <div class="col-md-12"> <?php echo $AR_DT['post_desc']; ?> </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$(".plus_qty").on('click',function(){
			var post_id = $(this).attr("rel");
			var post_qty = parseInt($("#post_qty_inc").val());
			var total_qty = parseInt(post_qty);
			if(total_qty>=0){
				process_cart(post_id,total_qty);
				window.location.reload();
			}
			
		});
		$(".minus_qty").on('click',function(){
			var post_id = $(this).attr("rel");
			var post_qty = parseInt($("#post_qty"+post_id).val());
			var total_qty = parseInt(post_qty-1);
			if(total_qty>=0){
				process_cart(post_id,total_qty);
				window.location.reload();
			}
			
		});
		
		
		function process_cart(post_id,total_qty){
			if(post_id>0){
				var data = {
					switch_type : "CART",
					post_id : post_id,
					total_qty : total_qty
				};
				var URL_CART = "<?php echo BASE_PATH; ?>json/jsonhandler";
				$.getJSON(URL_CART,data,function(JsonEval){	
					if(JsonEval){
						if(JsonEval.ErrorMsg=="success"){
							$("#cart_total").text(JsonEval.cart_total);
							$("#cart_bv").text(JsonEval.cart_bv);
							$("#cart_count").text(JsonEval.cart_count);
							$("#post_qty"+post_id).val(total_qty);
							$("#checkout_button").attr("disabled",false);
						}
					}
				});
			}
		}
	});
</script>
</html>
