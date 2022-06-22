<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(1);
$AR_NEWS = $model->getMemberNews("");

$QR_PAGES = "SELECT tp.*,  tpl.lang_id, tpl.post_size, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_pv,  tpl.update_date , tpl.post_slug, GROUP_CONCAT(tpc.category_id) AS category_id , 
			GROUP_CONCAT(tc.category_name) AS category_name
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_category AS tc ON tc.category_id=tpc.category_id 
			WHERE tp.delete_sts>0  AND tp.post_sts>0  AND tpc.category_id!='12'
      $StrWhr 
			GROUP BY tp.post_id  
			ORDER BY tp.update_date DESC";
$PageVal = DisplayPages($QR_PAGES, 60, $Page, $SrchQ);
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
<!--<![endif]-->

<!-- Mirrored from opencart.dostguru.com/FD01/fruitino_04/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Dec 2021 08:31:13 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<!-- /Added by HTTrack -->
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<?php $this->load->view('layout/svgsymbol'); ?>
<body>
<?php $this->load->view('layout/header'); ?>
<div id="common-home" class="container-fluid">
  <div class="row">
    <div id="content" class="col-xs-12">
      <?php $this->load->view('layout/slider'); ?>
      
<script type="text/javascript">
$(document).ready(function() {
    $("#owl-testi").owlCarousel({
    itemsCustom : [
    [0, 1]
    ],
      autoPlay: false,
      navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
      navigation : false,
      pagination:true
    });
    });

  </script>
      
      <div class="homecategory container next-prevb">
      <h1 class="heading text-center"><span>Popular Categories</span></h1>
        <div id="featured_category" >
          <div class="row rless">

          <?php 
          $QR_TAB_CAT = "SELECT tc.*, COUNT(tpc.post_id) AS prod_ctrl
            FROM tbl_category  AS tc  
            LEFT JOIN tbl_post_category AS tpc ON tpc.category_id=tc.category_id
            WHERE tc.category_sts>0 AND tc.delete_sts>0  AND tc.parent_id='0' AND tc.display_order>0
            GROUP BY tc.category_id 
            ORDER BY tc.display_order ASC LIMIT 8";
          $RS_TAB_CAT =  $this->SqlModel->runQuery($QR_TAB_CAT);
          foreach($RS_TAB_CAT as $AR_TAB_CAT): 
            $prod_ctrl = $model->checkCountPro("tbl_post_category","category_id='$AR_TAB_CAT[category_id]'");
          ?>
          <div class="block-cat-wr col-md-2 col-xs-6 cless text-center" >
            <div class="categorybr">
            
            <a href="<?php echo generateSeoUrl("product","catalog",array("category_id"=>$AR_TAB_CAT['category_id'])); ?>/<?php echo $AR_TAB_CAT['category_slug']; ?>">
              <img src="<?php echo $model->getCategoryImgSrc($AR_TAB_CAT['category_id']); ?>"  class="img-responsive center-block" alt="<?php echo ucfirst(strtolower($AR_TAB_CAT['category_name'])); ?>">
            </a>
            
            </div>
            <h5><span><a href="<?php echo generateSeoUrl("product","catalog",array("category_id"=>$AR_TAB_CAT['category_id'])); ?>/<?php echo $AR_TAB_CAT['category_slug']; ?>"><?php echo ucfirst(strtolower($AR_TAB_CAT['category_name'])); ?></a></span></h5>
            <h6><a href="<?php echo generateSeoUrl("product","catalog",array("category_id"=>$AR_TAB_CAT['category_id'])); ?>/<?php echo $AR_TAB_CAT['category_slug']; ?>">view all <i class="fa fa-long-arrow-right"></i></a></h6>
          </div>
          <?php endforeach; ?>
          
          </div>
        </div>
      </div>
    
      
      <div class="clearfix">&nbsp;</div> 
      <div class="clearfix">&nbsp;</div> 
      <h1 class="heading text-center"><span>Our Products</span></h1>
      <div class="row cpagerow rless">
        <?php 
        if($PageVal['TotalRecords'] > 0){
        $prod_ctrl=1;
        foreach($PageVal['ResultSet'] as $AR_DT):
        $IMG_SRC_FULL = $model->getDefaultPhoto($AR_DT['post_id'],0); 
        $off_ratio = getPercent($AR_DT['post_price'],$AR_DT['post_mrp']);
        ?>
        <div class="product-layout product-grid col-lg-2 col-md-4 col-sm-6 col-xs-12">
          <div class="product-thumb transition">
            <div class="image"><a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_DT['post_id'])); ?>/<?php echo $AR_DT['post_slug']; ?>"><img src="<?php echo $IMG_SRC_FULL; ?>" alt="<?php echo $AR_DT['post_title']; ?>" title="<?php echo $AR_DT['post_title']; ?>" class="img-responsive center-block"></a> 
              <!-- Webiarch Images Start --> 
              <a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_DT['post_id'])); ?>/<?php echo $AR_DT['post_slug']; ?>"><img src="<?php echo $IMG_SRC_FULL; ?>" class="img-responsive second-img" alt="<?php echo $AR_DT['post_title']; ?>"></a> 
              <!-- Webiarch Images End --> 
              <?php if($off_ratio>0){ ?><span class="sale"><?php echo number_format($off_ratio); ?>%</span> <?php } ?> </div>
            <div class="caption text-center">
              <div class="rating"> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> </div>
              <div class="opbtn">
                <h4 class="protitle"><a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_DT['post_id'])); ?>/<?php echo $AR_DT['post_slug']; ?>"><?php echo $AR_DT['post_title']; ?></a></h4>
                <div class="price"> <span class="price-new"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_DT['post_price'],2); ?></span> <span class="price-old"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_DT['post_mrp'],2); ?></span> </div>
              </div>
              <div class="button-group">
                <button type="button" data-toggle="tooltip" onClick="window.location.href='<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_DT['post_id'])); ?>/<?php echo $AR_DT['post_slug']; ?>'" title="" class="cartb" data-original-title="Add to Cart"><svg>
                <use xlink:href="#pcart"></use>
                </svg><span></span></button>
                <div data-toggle="tooltip" title="" class="bquickv" onClick="window.location.href='<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_VIEW['post_id'])); ?>/<?php echo $AR_VIEW['post_slug']; ?>'" data-original-title="Quickview"></div>
              </div>
            </div>
          </div>
        </div>
        <?php 
        if($prod_ctrl>=6){ echo '</div><div class="row cpagerow rless">'; $prod_ctrl=0; }
        $prod_ctrl++; 
        endforeach; 
        }else{
        echo '<div class="text-danger" align="center">No product found</div>'; 
        } ?>
      </div>
           
            
  
      <div class="container spepro next-prevb">
        <h1 class="heading text-center"><span>Recent View</span></h1>
        <div class="row rless">
          <div id="special" class="owl-theme owl-carousel">
            <?php 
			$QR_VIEW = "SELECT tp.*,  tpl.lang_id, tpl.post_size, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_pv,  tpl.update_date , tpl.post_slug, GROUP_CONCAT(tpc.category_id) AS category_id , 
			GROUP_CONCAT(tc.category_name) AS category_name
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_category AS tc ON tc.category_id=tpc.category_id 
			WHERE tp.delete_sts>0  AND tp.post_sts>0  
			AND tp.post_id IN(SELECT post_id FROM tbl_post_view)
			GROUP BY tp.post_id  
			ORDER BY tp.update_date DESC";
			$RS_VIEW = $this->SqlModel->runQuery($QR_VIEW);
            if(count($RS_VIEW) > 0){
            $prod_ctrl=1;
            foreach($RS_VIEW as $AR_VIEW):
            $IMG_SRC_VIEW = $model->getDefaultPhoto($AR_VIEW['post_id'],0); 
            $off_ratio = getPercent($AR_VIEW['post_price'],$AR_VIEW['post_mrp']);
            ?>
            <div class="product-layout col-xs-12 cless">
              <div class="product-thumb transition">
                <div class="image"><a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_VIEW['post_id'])); ?>/<?php echo $AR_VIEW['post_slug']; ?>"><img src="<?php echo $IMG_SRC_VIEW; ?>" alt="<?php echo $AR_VIEW['post_title']; ?>" title="<?php echo $AR_VIEW['post_title']; ?>" class="img-responsive center-block" /></a> <a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_VIEW['post_id'])); ?>/<?php echo $AR_VIEW['post_slug']; ?>"><img src="<?php echo $IMG_SRC_VIEW; ?>" class="img-responsive second-img" alt="<?php echo $AR_VIEW['post_title']; ?>"/></a>
				<?php if($off_ratio>0){ ?> <span class="sale"><?php echo number_format($off_ratio); ?>%</span><?php } ?> </div>
                <div class="caption text-center">
                  <div class="rating"> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> </div>
                  <div class="opbtn">
                    <h4  class="protitle"><a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_VIEW['post_id'])); ?>/<?php echo $AR_VIEW['post_slug']; ?>"><?php echo $AR_VIEW['post_title']; ?></a></h4>
                    <div class="price"> <span class="price-new"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_VIEW['post_price'],2); ?></span> <span class="price-old"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_VIEW['post_mrp'],2); ?></span> </div>
                  </div>
                  <div class="button-group">
                    <button type="button" data-toggle="tooltip" onClick="window.location.href='<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_VIEW['post_id'])); ?>/<?php echo $AR_VIEW['post_slug']; ?>'" title="Add to Cart" class="cartb"><svg>
                    <use xlink:href="#pcart"></use>
                    </svg><span></span></button>
                    <div data-toggle="tooltip" title="Quickview" onClick="window.location.href='<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_VIEW['post_id'])); ?>/<?php echo $AR_VIEW['post_slug']; ?>'" class="bquickv"></div>
                  </div>
                </div>
              </div>
            </div>
            <?php 
           
            $prod_ctrl++; 
            endforeach; 
            }else{
            echo '<div class="text-danger" align="center">No product found</div>'; 
            } ?>
          </div>
        </div>
      </div>
      <script type="text/javascript">
    $(document).ready(function() {
    $("#special").owlCarousel({
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
      
      
    </div>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
<a href="#" id="scroll" title="Scroll to Top" style="display: none;"> <i class="fa fa-angle-up"></i> </a> 

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
</body>
<!-- Mirrored from opencart.dostguru.com/FD01/fruitino_04/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Dec 2021 08:32:55 GMT -->
</html>
