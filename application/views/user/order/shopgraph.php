<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');



if(($_REQUEST['category_id'])>0){
	$category_id = ($_REQUEST['category_id']);
	$StrWhr .=" AND (tpc.category_id = '".$category_id."')";
	$SrchQ .="&category_id=$_REQUEST[category_id]";
}
if(($_REQUEST['post_title'])!=''){
	$post_title = ($_REQUEST['post_title']);
	$StrWhr .=" AND (tpl.post_title LIKE '%".$post_title."%' OR tpl.post_desc LIKE '%".$post_title."%')";
	$SrchQ .="&post_title=$_REQUEST[post_title]";
}

$QR_PAGES = "SELECT tp.*,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_pv,  tpl.post_bv, tpl.update_date , GROUP_CONCAT(tpc.category_id) AS category_id,
			GROUP_CONCAT(tt.tag_name) AS tags_name, COUNT(tpv.view_id) AS view_ctrl, COUNT(tpr.review_id) AS review_ctrl
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tag_id,tpl.post_tags)
			LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
			LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
			WHERE tp.delete_sts>0 AND tp.post_sts>0 
			$StrWhr 
			GROUP BY tp.post_id  
			ORDER BY tp.display_order ASC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>u-assets/plugins/magnific-popup/dist/magnific-popup.css"/>
<style type="text/css">
	.font-large{
		font-size:21px !important;
	}
</style>
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container">
    <!-- Page-Title -->
    <!-- Page-Title -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card-box">
           <?php #$this->load->view(MEMBER_FOLDER.'/inccart'); ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="portlet">
                <div class="portlet-heading bg-inverse">
                  <h3 class="portlet-title"> Products </h3>
                  <div class="portlet-widgets"> 
					  <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a> 
					  <span class="divider"></span> 
					  
				  </div>
                  <div class="clearfix"></div>
                </div>
                <div id="bg-inverse" class="panel-collapse collapse in">
                  <div class="portlet-body">
                    <div class="row m-t-10 m-b-10">
					<form action="<?php echo generateMemberForm("order","shopgraph",""); ?>" method="post" name="form-search" id="form-search">
						<div class="col-sm-6 col-lg-3">
                        <div class="h5 m-0"> 
                          <div class="btn-group vertical-middle" data-toggle="buttons">
                            	<select name="category_id" id="category_id" class="form-control"  style="width:250px;" > 
									<option value="">All Category</option>
									<?php DisplayCombo($category_id,"CATEGORY"); ?>
								</select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-lg-7">
                          <div class="form-group contact-search m-b-30">
                            <input type="text" name="post_title" id="post_title" class="form-control" placeholder="Search...">
                           
                          </div>
                      </div>
					   <div class="col-sm-6 col-lg-2">
                          <div class="form-group contact-search m-b-30">
                           		<button name="searchProduct" type="submit" class="btn  btn-success"  value="1">Search</button>
								<button type="button" class="btn  btn-danger" onClick="window.location.href='<?php echo generateSeoUrlMember("order","shopgraph",""); ?>'" >Reset</button>
                          </div>
                      </div>
                      </form>
                    </div>
					<div id="ajaxMessage"></div>
                    <div class="row port">
      <div class="portfolioContainer m-b-15">
	  	
	<?php 
	if($PageVal['TotalRecords'] > 0){
		$Ctrl=1;
		foreach($PageVal['ResultSet'] as $AR_DT):
	?>
		
        <div class="col-sm-6 col-lg-3 col-md-4 mobiles">
          <div class="product-list-box thumb"> <a href="javascript:void(0)" class="image-popup" title="<?php echo $AR_DT['post_title']; ?>"> <img src="<?php echo $model->getDefaultPhoto($AR_DT['post_id']); ?>" class="thumb-img" alt="work-thumbnail"> </a>
            <div class="product-action"> 
			<!--<a href="javascript:void(0)" class="btn btn-success btn-sm plus_qty" rel="<?php echo $AR_DT['post_id']; ?>"><i class="fa fa-plus"></i> Add to Cart</a>-->
			<input type="hidden" name="post_qty" id="post_qty<?php echo $AR_DT['post_id']; ?>" value="0">
			<a href="<?php echo generateSeoUrlMember("order","product",array("post_id"=>_e($AR_DT['post_id']))); ?>" class="btn btn-danger btn-sm"><i class="fa fa-eye"></i> Detail</a> </div>
            <div class="detail">
              <h4 class="m-t-0 m-b-5"><a href="#" class="text-dark"><?php echo $AR_DT['post_title']; ?></a> </h4>
              <div class="rating">
                <ul class="list-inline">
                  <li><a class="fa fa-star" href="#"></a></li>
                  <li><a class="fa fa-star" href="#"></a></li>
                  <li><a class="fa fa-star" href="#"></a></li>
                  <li><a class="fa fa-star" href="#"></a></li>
                  <li><a class="fa fa-star-o" href="#"></a></li>
                </ul>
              </div>
	
              <h5 class="m-0"><span class="text-custom font-large">Price : <?php echo number_format($AR_DT['post_price'],2); ?>
			  &nbsp;&nbsp;<small> <del><i class="fa fa-inr"></i> <?php echo number_format($AR_DT['post_mrp'],2); ?></del></small></span>   
			
            </div>
          </div>
        </div>
	<?php if($Ctrl>=4){ echo '</div> </div><div class="row port"><div class="portfolioContainer m-b-15">'; $Ctrl=0;  } $Ctrl++; endforeach; 
	}else{ ?>
	<div class="alert alert-danger">
		No product found
	</div>
	<?php  } ?>
      </div>
    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end col -->
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
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$(".plus_qty").on('click',function(){
			var post_id = $(this).attr("rel");
			var post_qty = parseInt($("#post_qty"+post_id).val());
			var total_qty = parseInt(post_qty+1);
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
