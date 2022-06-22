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
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>u-assets/plugins/magnific-popup/dist/magnific-popup.css"/>
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
        
        <h4 class="page-title">Products</h4>
        <ol class="breadcrumb">
          <li> <a href="<?php echo MEMBER_PATH; ?>">Dashboard</a> </li>
          <li class=""> <a href="<?php echo $model->getShopType(); ?>">Products</a></li>
          <li class="active"> <?php echo $AR_DT['post_title']; ?> </li>
        </ol>
      </div>
    </div>
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
			    <?php  #$this->load->view(MEMBER_FOLDER.'/inccart'); ?>
				<hr>
				<div id="ajaxMessage"></div>
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
						<!--<div class="">
							Quantity : <input style="width:50px;" type="text" name="post_qty" id="post_qty<?php echo $AR_DT['post_id']; ?>" value="<?php echo ($post_qty>0)? $post_qty:1; ?>">
						</div>-->
                        <div class="clearfix">&nbsp;</div>
						<!--<a type="button" href="<?php echo $model->getShopType(); ?>" class="w3ls-cart btn btn-purple" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Continue Shopping</a>
                        <a type="button" rel="<?php echo $AR_DT['post_id']; ?>" href="javascript:void(0)" class="w3ls-cart btn btn-success plus_qty" ><i class="fa fa-cart-plus" aria-hidden="true"></i> Add to cart</a>-->
						
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
    <!-- Footer -->
     <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
    <!-- End Footer -->
  </div>
  <!-- end container -->
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>u-assets/plugins/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.image-popup').magnificPopup({
			type: 'image',
			closeOnContentClick: true,
			mainClass: 'mfp-fade',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			}
		});
	});
</script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$(".plus_qty").on('click',function(){
			var post_id = $(this).attr("rel");
			var post_qty = parseInt($("#post_qty"+post_id).val());
			var total_qty = parseInt(post_qty);
			if(total_qty>=0){
				process_cart(post_id,total_qty);
			}
			
		});
		$(".minus_qty").on('click',function(){
			var post_id = $(this).attr("rel");
			var post_qty = parseInt($("#post_qty"+post_id).val());
			var total_qty = parseInt(post_qty-1);
			if(total_qty>=0){
				process_cart(post_id,total_qty);
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
							$("#ajaxMessage").slideDown(600);
							$("#cart_total").text(JsonEval.cart_total);
							$("#cart_bv").text(JsonEval.cart_bv);
							$("#cart_count").text(JsonEval.cart_count);
							$("#post_qty"+post_id).val(total_qty);
							$("#checkout_button").attr("disabled",false);
							$("#ajaxMessage").html("<div class='alert alert-info bg-white'>"+JsonEval.ErrorDtl+"</div>");
							$("#ajaxMessage").slideUp(5000);
						}
					}
				});
			}
		}
	});
</script>
</html>
