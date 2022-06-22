<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(1);
$post_id = getTool($segment['post_id'],0);
$member_id = $this->session->userdata('mem_id');
$AR_DT = $model->getPostDetail($post_id);
$stock_sts = $AR_DT['stock_sts'];
$model->postView($post_id,$member_id);
$review_ctrl = $model->getReviewCtrl($post_id);

$QR_PAGES= "SELECT tpf.* 
			FROM tbl_post_file AS tpf 
			WHERE tpf.delete_sts>0 AND tpf.post_id='".$post_id."'
		    ORDER BY tpf.cover_sts DESC";
$AR_IMG = $this->SqlModel->runQuery($QR_PAGES);

$IMG_SRC_FULL = $model->getDefaultPhoto($AR_DT['post_id'],1); 

$AR_STOCK = $model->getStockBalance($AR_DT['post_id'],0,$AR_DT['franchisee_id'],"","");

$off_ratio = getPercent($AR_DT['post_price'],$AR_DT['post_mrp']);
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
<!--<![endif]-->

<!-- Mirrored from opencart.dostguru.com/FD01/fruitino_04/index.php?route=product/product&path=20&product_id=30&sort=p.price&order=ASC by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Dec 2021 08:56:24 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<!-- /Added by HTTrack -->
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<?php $this->load->view('layout/svgsymbol'); ?>
<body>
<?php $this->load->view('layout/header'); ?>
<div id="product-product" class="container">
  <ul class="breadcrumb">
    <li><a href="<?php echo BASE_PATH; ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo generateSeoUrl("product","catalog",array("category_id"=>$AR_DT['category_id'])); ?>/<?php echo $AR_DT['category_slug']; ?>"><?php echo $AR_DT['category_name']; ?></a></li>
  </ul>
  <div class="row">
    <div id="content" class="col-xs-12">
      <?php get_message(); ?>
      <div class="productbg">
        <div class="row">
          <div class="col-sm-6 col-lg-5 col-md-6 col-xs-12 proimg sticky">
            <ul class="thumbnails">
              <li><a class="thumbnail" href="<?php echo $IMG_SRC_FULL; ?>" title="Canon EOS 5D"> <img data-zoom-image="<?php echo $IMG_SRC_FULL; ?>" src="<?php echo $IMG_SRC_FULL; ?>" id="zoom_03" class="img-responsive center-block" alt="<?php echo $AR_DT['post_title']; ?>"> </a> </li>
              <div class="row">
                <li id="gallery_01" class="owl-carousel">
                  <?php 
                if(count($AR_IMG)>0){
                $x=1;
                foreach($AR_IMG as $IMG_S): 
					$IMG_THUMB = $model->getFileSrc($IMG_S['field_id'],0);
					$IMG_FULL = $model->getFileSrc($IMG_S['field_id'],1);
                ?>
                  <a data-zoom-image="<?php echo $IMG_FULL; ?>" data-image="<?php echo $IMG_THUMB; ?>"  href="<?php echo $IMG_FULL; ?>" class="col-xs-12"> <img src="<?php echo $IMG_THUMB; ?>" class="img-responsive center-block" alt="<?php echo $AR_DT['post_title']; ?>"> </a>
                  <?php 
                 $x++;
                  endforeach;
                }
                ?>
                </li>
              </div>
            </ul>
          </div>
          <div class="col-lg-7 col-md-6 col-xs-12 col-sm-6 pro-content">
            <h1><?php echo $AR_DT['post_title']; ?></h1>
            <hr class="producthr">
            <ul class="list-unstyled">
              <li><span class="text-decor">Category:</span><a href="javascript:void()0" class="textdeb"><?php echo $AR_DT['category_name']; ?></a></li>
              <li><span class="text-decor">Product Code:</span> <?php echo $AR_DT['post_code']; ?></li>
              <li><span class="text-decor">Reward Points:</span> 200</li>
              <li><span class="text-decor">Availability:</span> <?php echo ($AR_STOCK['net_balance']>0 && $stock_sts>0)? "In Stock":"Out of Stock"; ?></li>
              <hr class="producthr">
            </ul>
            <ul class="list-unstyled">
              <ul class="list-inline">
                <li class="text-decor-bold">
                  <h2><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_DT['post_price'],2); ?></h2>
                </li>
                <li><span class="price-old"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_DT['post_mrp'],2); ?></span></li>
              </ul>
              <!--             <li>Ex Tax: $80.00</li>
             -->
            </ul>
            <form method="post" action="<?php echo generateForm("product","cart",""); ?>" name="form-detail" id="form-detail">
              <div id="product">
                <?php
			  	$QR_ATTR = "SELECT tag.* FROM tbl_attribute_group AS tag 
                WHERE tag.attribute_group_id IN(SELECT attribute_group_id FROM tbl_attribute 
                    WHERE attribute_id IN(SELECT attribute_id FROM tbl_post_attribute_combination 
                        WHERE post_attribute_id IN(SELECT post_attribute_id FROM tbl_post_attribute WHERE post_id='".$AR_DT['post_id']."' 
						AND delete_sts>0 AND post_attribute_sts>0)))";
                $RS_ATTR = $this->SqlModel->runQuery($QR_ATTR);
                $row_ctrl = count($RS_ATTR);
				if($row_ctrl>0){
			   ?>
                <hr class="producthr">
                <h3>Available Options</h3>
                <div class="form-group required ">
                  <?php 
                
                foreach($RS_ATTR as $AR_ATTR):
                $post_attribute_id = $AR_ATTR['post_attribute_id'];
                $attribute_group_id = $AR_ATTR['attribute_group_id'];
                $attribute_group_name = $AR_ATTR['attribute_group_name'];
                ?>
                  <label class="control-label text-decorop" for="input-option226"><?php echo ucfirst(strtolower($attribute_group_name)); ?> </label>
                  <select name="name[]" id="group_id<?php echo $attribute_group_id; ?>" data-id="<?php echo $attribute_group_id; ?>" class="form-control">
                    <option value="">---Select <?php echo ucfirst(strtolower($attribute_group_name)); ?>----</option>
                    <?php
                    
                    $QR_SEL = "SELECT ta.* FROM tbl_attribute AS ta 
						WHERE ta.attribute_id IN(SELECT attribute_id FROM tbl_post_attribute_combination 
						WHERE post_attribute_id IN(SELECT post_attribute_id FROM tbl_post_attribute WHERE post_id='".$post_id."' AND delete_sts>0 AND post_attribute_sts>0))
						AND ta.attribute_group_id='".$attribute_group_id."'
						GROUP BY ta.attribute_id";
                    $RS_SEL = $this->SqlModel->runQuery($QR_SEL);
                    foreach($RS_SEL as $AR_SEL):
                    ?>
                    <option value="<?php echo $AR_SEL['attribute_id']; ?>"><?php echo $AR_SEL['attribute_name']; ?></option>
                    <?php 
                    endforeach;
                    ?>
                  </select>
                  <?php endforeach; ?>
                </div>
                <?php } ?>
                <hr class="producthr">
                <!-- Quantity option -->
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-3 col-md-3 col-xs-3 col-lg-2 op-box qtlabel">
                      <label class="control-label text-decorop" for="input-quantity">Qty :</label>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9 col-lg-10 op-box qty-plus-minus">
                      <button type="button" class="form-control pull-left btn-number btnminus" data-type="minus" data-field="post_qty"> <span class="glyphicon glyphicon-minus"></span> </button>
                      <input id="post_qty" type="text" name="post_qty" value="1" size="2" min="1" max="99" class="form-control input-number pull-left" />
                      <input type="hidden" name="post_attribute_id" id="post_attribute_id" value="0">
                      <button type="button" class="form-control pull-left btn-number btnplus" data-type="plus" data-field="post_qty"> <span class="glyphicon glyphicon-plus"></span> </button>
                    </div>
                  </div>
                  <hr class="producthr">
                  <button type="button" id="button-cart"  class="btn pcrt add-to-cart add_to_cart btn-primary" post_id="<?php echo $AR_DT['post_id']; ?>">Add to Cart</button>
                  <button type="button" data-toggle="tooltip" title="Add to Wish List" class="btn pcrt btn-primary" onclick="wishlist.add('30');"><svg width="18px" height="17px">
                  <use xlink:href="#heart" />
                  </svg></button>
                  <button type="button" data-toggle="tooltip" title="Compare this Product" class="btn pcrt  btn-primary" onclick="compare.add('30');" ><svg width="18px" height="17px">
                  <use xlink:href="#compare"/>
                  </svg></button>
                  <hr class="producthr">
                </div>
              </div>
            </form>
            <div class="rating">
              <li> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> </li>
              <li class="proreview"> <a id="ratecount" href="#" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">0 reviews</a> </li>
              <li> <a id="ratep" href="#rt" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">Write a review</a> </li>
              <hr class="producthr">
              <!-- AddToAny BEGIN -->
              <div class="a2a_kit a2a_kit_size_32 a2a_default_style"> <a class="a2a_button_facebook"></a> <a class="a2a_button_twitter"></a> <a class="a2a_button_google_plus"></a> <a class="a2a_button_pinterest"></a> <a class="a2a_button_linkedin"></a> <a class="a2a_dd" href="https://www.addtoany.com/share"></a> </div>
              
              <!-- AddToAny END --> 
            </div>
          </div>
        </div>
        <div class="product-tab">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-description" data-toggle="tab">Description</a></li>
            <li><a href="#tab-review" data-toggle="tab">Reviews (0)</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-description">
              <p> <?php echo $AR_DT['post_desc']; ?></p>
            </div>
            <div class="tab-pane" id="tab-review">
              <?php
       
				$QR_RVW = "SELECT tpr.* FROM tbl_post_review AS tpr WHERE tpr.post_id='".$AR_DT['post_id']."'";
				$RS_RVW = $this->SqlModel->runQuery($QR_RVW);
  
                foreach($RS_RVW as $AR_RVW):
                ?>
              <div class="reviews_comment_box">
                <div class="comment_thmb"> <img src="<?php echo THEME_PATH; ?>assets/img/blog/comment2.jpg" alt=""> </div>
                <div class="comment_text">
                  <div class="reviews_meta">
                    <div class="product_rating">
                      <ul>
                        <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                        <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                        <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                        <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                        <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                      </ul>
                    </div>
                    <p><strong><?php echo $AR_RVW['review_by']; ?> </strong>- <?php echo getDateFormat($AR_RVW['date_time'],"M d, Y"); ?></p>
                    <span><?php echo masking($AR_RVW['email_id'],15); ?> :- <br>
                    <?php echo $AR_RVW['review_dtls']; ?></span> </div>
                </div>
              </div>
              <?php endforeach; ?>
              <form class="" name="form-review" id="form-review" action="<?php echo generateForm("product","detail",""); ?>">
                <div id="review"></div>
                <h2 class="co-heading">Write a review</h2>
                <div class="form-group required row">
                  <div class="col-sm-12">
                    <label class="control-label" for="review_by">Your Name</label>
                    <input type="text" name="review_by" value="" id="review_by" class="form-control" />
                  </div>
                </div>
                <div class="form-group required row">
                  <div class="col-sm-12">
                    <label class="control-label" for="review_by">Your Email</label>
                    <input type="text" name="email_id" value="" id="email_id" class="form-control" />
                  </div>
                </div>
                <div class="form-group required row">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-review">Your Review</label>
                    <textarea name="review_dtls" rows="5" id="review_dtls" class="form-control"></textarea>
                    <!-- <div class="help-block"><span class="text-danger">Note:</span> HTML is not translated!</div> --> 
                  </div>
                </div>
                <div class="form-group required">
                  <div class="radi">
                    <label class="control-label" for="input-review">Rating</label>
                    <div class="form-rating">
                      <div class="form-rating-container pull-left">
                        <input id="rating-5" type="radio" name="rating" value="5" />
                        <label class="fa fa-stack pull-right" for="rating-5"> <i class="fa fa-star fa-stack-1x"></i> <i class="fa fa-star-o fa-stack-1x"></i> </label>
                        <input id="rating-4" type="radio" name="rating" value="4" />
                        <label class="fa fa-stack pull-right" for="rating-4"> <i class="fa fa-star fa-stack-1x"></i> <i class="fa fa-star-o fa-stack-1x"></i> </label>
                        <input id="rating-3" type="radio" name="rating" value="3" />
                        <label class="fa fa-stack pull-right" for="rating-3"> <i class="fa fa-star fa-stack-1x"></i> <i class="fa fa-star-o fa-stack-1x"></i> </label>
                        <input id="rating-2" type="radio" name="rating" value="2" />
                        <label class="fa fa-stack pull-right" for="rating-2"> <i class="fa fa-star fa-stack-1x"></i> <i class="fa fa-star-o fa-stack-1x"></i> </label>
                        <input id="rating-1" type="radio" name="rating" value="1" />
                        <label class="fa fa-stack pull-right" for="rating-1"> <i class="fa fa-star fa-stack-1x"></i> <i class="fa fa-star-o fa-stack-1x"></i> </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="buttons clearfix">
                  <div class="pull-right">
                    <input type="hidden" name="review_post_id" id="review_post_id" value="<?php echo _e($AR_DT['post_id']);  ?>">
                    <button type="submit" name="submit-review" id="submit-review" value="1" class="btn btn-primary">Submit</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- relatedproduct --> 
      </div>
    </div>
  </div>
</div>

<!--for product quantity plus minus--> 
<script type="text/javascript">
    //plugin bootstrap minus and plus
    $(document).ready(function() {
		$('.btn-number').click(function(e){
			e.preventDefault();
			var fieldName = $(this).attr('data-field');
			var type = $(this).attr('data-type');
			var input = $("input[name='" + fieldName + "']");
			var currentVal = parseInt(input.val());
			if (!isNaN(currentVal)) {
			if (type == 'minus') {
			 var minValue = parseInt(input.attr('min'));
			if (!minValue) minValue = 1;
			if (currentVal > minValue) {
			 input.val(currentVal - 1).change();
			}
			if (parseInt(input.val()) == minValue) {
			  $(this).attr('disabled', true);
			}
		
			} else if (type == 'plus') {
				var maxValue = parseInt(input.attr('max'));
			if (!maxValue) maxValue = 999;
			if (currentVal < maxValue) {
			 input.val(currentVal + 1).change();
			}
				if (parseInt(input.val()) == maxValue) {
				 $(this).attr('disabled', true);
				}
		
			}
			} else {
			 input.val(0);
			}
		});
		$('.input-number').focusin(function(){
		  $(this).data('oldValue', $(this).val());
		});
		$('.input-number').change(function() {
	
			var minValue = parseInt($(this).attr('min'));
			var maxValue = parseInt($(this).attr('max'));
			if (!minValue) minValue = 1;
			if (!maxValue) maxValue = 999;
			var valueCurrent = parseInt($(this).val());
			var name = $(this).attr('name');
			if (valueCurrent >= minValue) {
				$(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
			} else {
				alert('Sorry, the minimum value was reached');
				$(this).val($(this).data('oldValue'));
			}
			if (valueCurrent <= maxValue) {
				$(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
			} else {
				alert('Sorry, the maximum value was reached');
				$(this).val($(this).data('oldValue'));
			}
		});
		$(".input-number").keydown(function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== - 1 ||
			// Allow: Ctrl+A
					(e.keyCode == 65 && e.ctrlKey === true) ||
					// Allow: home, end, left, right
							(e.keyCode >= 35 && e.keyCode <= 39)) {
			// let it happen, don't do anything
				return;
			}
		// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
    });
</script> 
<script type="text/javascript"><!--

$(document).ready(function() {
  $('.thumbnails').magnificPopup({
    type:'image',
    delegate: 'a',
    gallery: {
      enabled: true
    }
  });
});
//--></script> 
<!-- related --> 
<script type="text/javascript">
    $(document).ready(function() {
		 $("#related").owlCarousel({
			itemsCustom : [
			[0, 1],
			[320, 2],
			[600, 3],
			[992, 4],
			[1200, 4],
			[1410, 5]
			],
		  // autoPlay: 1000,
		  navigationText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		  navigation : true,
		  pagination:false
		});
    });
</script> 
<!-- related over --> 
<!-- zoom product start --> 
<!-- zoom product start --> 
<script>
     if (jQuery(window).width() >= 1200){
        //initiate the plugin and pass the id of the div containing gallery images
            $("#zoom_03").elevateZoom({gallery:'gallery_01', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: ''});
        //pass the images to Fancybox
            $("#zoom_03").bind("click", function (e) {
            var ez = $('#zoom_03').data('elevateZoom');
            $.fancybox(ez.getGalleryList());
            return false;
            });
    }
</script> 
<!--ZOOM END--> 

<!--slider for product--> 
<script type="text/javascript"><!--
$('#gallery_01').owlCarousel({
  itemsCustom : [
        [0, 2],
        [320, 3],
        [600, 5],
        [768, 4],
        [992, 4],
        [1200, 4],
        [1410, 4]
        ],
   autoPlay: 1000,
  autoPlay: true,
  navigation: false,
  navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
  pagination: false
});
--></script> 
<!--over slider for product-->
<script>
	$(function(){
		$(".product_attribute").on('change',function(){
			var post_id = "<?php echo $AR_DT['post_id']; ?>";
			var attribute_id = $(this).val();
			var attribute_group_id = $(this).attr("data-id");
			update_attr_value(post_id,attribute_group_id,attribute_id);
			var URL_LOAD = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>?switch_type=ATTRIBUTE&post_id="+post_id;
			$.getJSON(URL_LOAD,$("#form-detail").serialize(),function(json_return){
				if(json_return){
					if(json_return.post_attribute_id>0){
						$("#error_box").hide();
						$("#submit_buton").show();
						$("#post_attribute_id").val(json_return.post_attribute_id);
						update_attr_price(json_return.post_attribute_id,post_id);
						
					}
				}else{
					//$("#submit_buton").hide();
					$("#error_box").show();
					$("#post_attribute_id").val('');
					$("#error_box").html('<div class="alert alert-danger">This combination does not exist for this product. Please select another combination.</div>');
					
					
				}
			});
		});
		
		function update_attr_value(post_id,attribute_group_id_select,attribute_id){
			if(post_id>0 && attribute_group_id_select>0  && attribute_id>0){
				var product_attribute = $(".product_attribute");
				var attribute_group_id_last = $( ".product_attribute" ).last().attr("data-id");
				product_attribute.each(function(index){
					var attribute_group_id = $(this).attr("data-id");
					if(attribute_group_id_select!=attribute_group_id_last){
						if(attribute_group_id>0 && attribute_group_id!=attribute_group_id_select){
							var URL_VAL = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>?switch_type=ATTR_VALUE&post_id="+post_id+"&attribute_group_id="+attribute_group_id+"&attribute_id="+attribute_id;
							$("#group_id"+attribute_group_id).load(URL_VAL);
						}
					}
				});
				
			}
			
		}
		
		$(".check-pincode").on('click',function(){
			var pin_code = $("#pin_code").val();
			if(pin_code!=''){
				var URL_LOAD = "<?php echo BASE_PATH; ?>json/jsonhandler";
				var data_send = {
					switch_type : "CHECK_PINCODE",
					pin_code : pin_code
				}
				$.getJSON(URL_LOAD,data_send,function(json_eval){
					if(json_eval){
						if(json_eval.pincode_id>0){
							$("#pincode_msg").html("<div class='alert alert-success'>Shipping available in "+json_eval.city_name+".</div>");
						}else{
							$("#pincode_msg").html("<div class='alert alert-danger'>Shipping not  available in this pincode "+pin_code+".</div>");
						}
					}
				});
			}
		});
		
		function update_attr_price(post_attribute_id,post_id){
			if(post_attribute_id>0){
				var URL_PRICE_ATTR = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
				var data_send = {
					switch_type : "ATTR_PRICE",
					post_attribute_id: post_attribute_id,
					post_id : post_id
				}
				$.getJSON(URL_PRICE_ATTR,function(json_attr){
					if(json_attr){
						if(json_attr.post_attribute_id>0){
							$("#price_lable_box").text(parseFloat(json_attr.post_attribute_price).toFixed(2));
							$("#post_attribute_id").val(json_attr.post_attribute_id);
						}else{
							$("#price_lable_box").text(parseFloat(json_attr.post_attribute_price).toFixed(2));
							$("#post_attribute_id").val(json_attr.post_attribute_id);
						}
					}
				});
			}
		}
	
	
		$(".add_to_cart").on('click',function(e){
			e.preventDefault();
			var post_id = $(this).attr("post_id");
			
			var post_qty = parseInt($("#post_qty").val());
			var post_attribute_id = parseInt($("#post_attribute_id").val());
			var total_qty = parseInt(post_qty);
			if(total_qty>=0){
				process_cart(post_id,post_attribute_id,total_qty);
			}
			
		});
		
		function process_cart(post_id,post_attribute_id,total_qty){
			var row_ctrl = "<?php echo $row_ctrl; ?>";
			if(row_ctrl>0){
				if(post_attribute_id=='' || post_attribute_id==0){
					alert("Please select product entities?");
					return false;
				}
			}
			
			if(post_id>0){
				var data = {
					switch_type : "CART",
					post_id : post_id,
					post_attribute_id : post_attribute_id,
					total_qty : total_qty
				};
				var URL_CART = "<?php echo BASE_PATH; ?>json/jsonhandler";
				$.getJSON(URL_CART,data,function(JsonEval){	
					if(JsonEval.status>0){
						window.location.href='<?php echo generateSeoUrl("product","cart",""); ?>';
					}else{
						alert("Unable to add product into cart, please check quantity");
					}
				});
			}
		}
	});
	
	
</script>
<?php $this->load->view('layout/footer'); ?>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
</body>
<!-- Mirrored from opencart.dostguru.com/FD01/fruitino_04/index.php?route=product/product&path=20&product_id=30&sort=p.price&order=ASC by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Dec 2021 08:56:24 GMT -->
</html>
