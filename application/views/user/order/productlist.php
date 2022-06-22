<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');

if(_d($_REQUEST['category_id'])>0){
	$category_id = _d($_REQUEST['category_id']);
	$StrWhr .=" AND (tpc.category_id IN($category_id) )";
	$SrchQ .="&category_id=$_REQUEST[category_id]";
}


$QR_PAGES = "SELECT tp.*,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_pv,  tpl.update_date , GROUP_CONCAT(tpc.category_id) AS category_id,
			GROUP_CONCAT(tt.tag_name) AS tags_name, COUNT(tpv.view_id) AS view_ctrl, COUNT(tpr.review_id) AS review_ctrl
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tag_id,tpl.post_tags)
			LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
			LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
			WHERE tp.delete_sts>0   $StrWhr 
			GROUP BY tp.post_id  
			ORDER BY tp.post_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
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
        <div class="btn-group pull-right m-t-15">
			<a href="<?php echo generateSeoUrlMember("order","cart",""); ?>"  class="btn btn-default dropdown-toggle waves-effect waves-light">My Cart <span class="m-l-5"><i class="fa fa-shopping-cart"></i></span></a>
		</div>
        <h4 class="page-title">Products</h4>
        <ol class="breadcrumb">
          <li> <a href="<?php echo MEMBER_PATH; ?>">Dashboard</a> </li>
          <li class="active"> Products </li>
        </ol>
      </div>
    </div>
    <!-- Page-Title -->
    <!-- SECTION FILTER
                ================================================== -->
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 ">
        <div class="portfolioFilter"> 
			<a href="<?php echo generateSeoUrlMember("order","productlist",""); ?>"  class="<?php echo (_d($_REQUEST['category_id'])=='')? "current":""; ?>">All</a> 
			<?php
				$QR_CAT = "SELECT tc.* FROM tbl_category  AS tc  WHERE 1 AND tc.delete_sts>0  ORDER BY tc.category_id DESC";
				$AR_CATS =  $this->SqlModel->runQuery($QR_CAT);
				foreach($AR_CATS as $AR_CAT):
			 ?>
			<a class="<?php echo  (_d($_REQUEST['category_id'])==$AR_CAT['category_id'])? "current":""; ?>" href="<?php echo generateSeoUrlMember("order","productlist",""); ?>?category_id=<?php echo _e($AR_CAT['category_id']); ?>">
				<?php echo ucfirst(strtolower($AR_CAT['category_name'])); ?>
			</a>
			<?php endforeach; ?>
			</div>
      </div>
    </div>
    <div class="row port">
      <div class="portfolioContainer m-b-15">
	  	
	<?php 
	if($PageVal['TotalRecords'] > 0){
		$Ctrl=1;
		foreach($PageVal['ResultSet'] as $AR_DT):
	?>
		
        <div class="col-sm-6 col-lg-3 col-md-4 mobiles">
          <div class="product-list-box thumb"> <a href="<?php echo $model->getDefaultPhoto($AR_DT['post_id']); ?>" class="image-popup" title="<?php echo $AR_DT['post_title']; ?>"> <img src="<?php echo $model->getDefaultPhoto($AR_DT['post_id']); ?>" class="thumb-img" alt="work-thumbnail"> </a>
            <div class="product-action"> 
			<a href="<?php echo generateSeoUrlMember("order","cart",array("post_id"=>_e($AR_DT['post_id']))); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add to Cart</a>
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
              <h5 class="m-0"><span class="text-custom"><i class="fa fa-inr"></i> <?php echo number_format($AR_DT['post_price'],2); ?>
			  <small> <del><i class="fa fa-inr"></i> <?php echo number_format($AR_DT['post_mrp'],2); ?></del></small></span> <span class="text-muted m-l-15"> PV : <?php echo $AR_DT['post_pv']; ?></span></h5>
            </div>
          </div>
        </div>
	<?php $Ctrl++; endforeach; 
	}else{ ?>
	<div class="alert alert-danger">
		No product found
	</div>
	<?php  } ?>
      </div>
    </div>
    <!-- End row -->
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
</html>
