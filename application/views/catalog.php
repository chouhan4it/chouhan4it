<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$page_record = getTool($_REQUEST['page_record'],40);
$current_record = getTool($Page*$page_record,0);

$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(1);
$OrderBy = "ORDER BY tp.display_order ASC";
$category_id = getTool($segment['category_id'],$_REQUEST['category_id']);


$tag_id = $segment['tag_id'];
if($tag_id>0){
	$StrWhr .=" AND FIND_IN_SET('".$tag_id."',post_tags)";
	$SrchQ .="&tag_id=".$tag_id."";
}

if($category_id>0){
	$AR_CAT = $model->getCategoryDetail($category_id);
	$StrWhr .=" AND tpc.category_id='".$category_id."'";
	$SrchQ .="&category_id=".$category_id."";
}

if($_REQUEST['q']!=''){
	$q = FCrtRplc($_REQUEST['q']);
	$StrWhr .=" AND ( tpl.post_title LIKE '%".$q."%' OR tpl.short_desc LIKE '%".$q."%' OR tpl.post_desc LIKE '%".$q."%' )";
	$SrchQ .="&q=".$q."";
}

if($_REQUEST['shorting']!=''){
	$shorting = FCrtRplc($_REQUEST['shorting']);
	switch($shorting):
		case "nameasc":
			$OrderBy = " ORDER BY tpl.post_title ASC";
		break;
		case "namedesc":
			$OrderBy = " ORDER BY tpl.post_title DESC";
		break;
		case "priceasc":
			$OrderBy = " ORDER BY tpl.post_price ASC";
		break;
		case "pricedesc":
			$OrderBy = " ORDER BY tpl.post_price DESC";
		break;
	endswitch;
}


$QR_PAGES = "SELECT tp.*,  tpl.lang_id, tpl.post_size, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_pv,  tpl.update_date , tpl.post_slug, GROUP_CONCAT(tc.category_name) AS category_name
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_category AS tc ON tc.category_id=tpc.category_id 
			WHERE tp.delete_sts>0 AND tp.post_sts>0   $StrWhr 
			GROUP BY tp.post_id  
			$OrderBy";
$PageVal = DisplayPages($QR_PAGES, $page_record, $Page, $SrchQ);
$show_record = ($PageVal['TotalRecords']>$current_record)? $current_record:$PageVal['TotalRecords'];
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
<div id="product-manufacturer" class="container cleft manucom">
  <ul class="breadcrumb">
    <li><a href="<?php echo BASE_PATH; ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="javascript:void(0)">Category</a></li>
  </ul>
  <div class="row">
    <div id="content" class="col-sm-12">
      <h1 class="heading">Find Your Favorite Brand</h1>
      <div class="appres"></div>
      <form  action="" method="get">
      <div class="row cate-border">
        <div class="col-md-2 col-sm-3 col-xs-4 lgrid">
          <div class="btn-group-sm">
            <button type="button" id="list-view" class="btn listgridbtn" data-toggle="tooltip" title="List"> <svg width="20px" height="20px">
            <use xlink:href="#clist"></use>
            </svg> </button>
            <button type="button" id="grid-view" class="btn listgridbtn" data-toggle="tooltip" title="Grid"> <svg width="18px" height="18px">
            <use xlink:href="#cgrid"></use>
            </svg> </button>
          </div>
        </div>
        <div class="col-lg-4 col-md-5 col-sm-6 col-xs-8 hidden-md hidden-sm ct"> <!-- <a href="index6431.html?route=product/compare" id="compare-total" class="btn btn-link">Product Compare (0)</a>  --></div>
        <div class="col-lg-3 col-md-5 col-xs-4 col-sm-5 catesort">
          <div class="input-group input-group-sm select-input">
            <label class="input-group-addon" for="input-sort">Sort By:</label>
            <select id="input-sort" class="form-control" name="shorting" onchange="this.form.submit()">
            
              <?php echo DisplayCombo($_REQUEST['shorting'],"SHORTING"); ?> 
            </select>
          </div>
        </div>
        <div class="col-lg-3 col-md-5 col-xs-4 col-sm-4 catesort">
          <div class="input-group input-group-sm select-input">
            <label class="input-group-addon" for="input-limit">Show:</label>
            <select id="input-limit" name="page_record" class="form-control" onchange="this.form.submit()">
            <?php echo DisplayCombo($_REQUEST['page_record'],"PAGE_RECORD"); ?> 
            </select>
          </div>
        </div>
      </div>
      </form>
      <div class="row cpagerow rless">
		<?php 
        if($PageVal['TotalRecords'] > 0){
        $Ctrl=1;
        foreach($PageVal['ResultSet'] as $AR_DT):
        $IMG_SRC = $model->getDefaultPhoto($AR_DT['post_id'],0); 
         $off_ratio = getPercent($AR_DT['post_price'],$AR_DT['post_mrp']);
        ?>
        <div class="product-layout product-list col-xs-12 cless">
          <div class="product-thumb transition">
            <div class="image"><a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_DT['post_id'])); ?>/<?php echo $AR_DT['post_slug']; ?>"><img src="<?php echo $IMG_SRC; ?>" alt="<?php echo $AR_DT['post_title']; ?>" title="<?php echo $AR_DT['post_title']; ?>" class="img-responsive center-block" /></a> 
              <!-- Webiarch Images Start --> 
              <a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_DT['post_id'])); ?>/<?php echo $AR_DT['post_slug']; ?>"><img src="<?php echo $IMG_SRC; ?>" class="img-responsive second-img" alt="<?php echo $AR_DT['post_title']; ?>"/></a> 
              <!-- Webiarch Images End --> 
             <?php if($off_ratio>0){ ?> <span class="sale"><?php echo number_format($off_ratio,2); ?>%</span> <?php } ?></div>
            <div class="caption text-center">
              <div class="rating"> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> </div>
              <div class="opbtn">
                <h4  class="protitle"><a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_DT['post_id'])); ?>/<?php echo $AR_DT['post_slug']; ?>"><?php echo $AR_DT['post_title']; ?></a></h4>
                <p class="catlist-des"><?php echo $AR_DT['short_desc']; ?></p>
                <div class="price"> <span class="price-new"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_DT['post_price'],2); ?></span> <span class="price-old"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($AR_DT['post_mrp'],2); ?></span> </div>
              </div>
              <div class="button-group">
                
                <button type="button" data-toggle="tooltip" title="Add to Cart" class="cartb"><svg>
                <use xlink:href="#pcart"></use>
                </svg><span></span></button>
               
                <div data-toggle="tooltip" title="Quickview"  class="bquickv"></div>
              </div>
            </div>
          </div>
        </div>
        
		<?php 
        if($Ctrl>=5){ echo '</div><div class="row cpagerow rless">'; $Ctrl=0;  }
        $Ctrl++; 
        endforeach; 
        }else{
        echo '<div class="text-danger" align="center">No product found</div>'; 
        } ?>
       
        
        
      </div>
      <div class="row pagi">
        <div class="col-sm-6 col-xs-6 text-left"></div>
        <div class="col-sm-6 col-xs-6 text-right tot">Showing <?php echo $PageVal['RecordStart']+1; ?> to <?php echo  count($PageVal['ResultSet']); ?> of <?php echo $PageVal['TotalRecords']; ?> product</div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
</body>
</html>
