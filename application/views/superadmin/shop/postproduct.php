<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title><?php echo title_name(); ?></title>
<meta name="description" content="Static &amp; Dynamic Tables" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/fonts.googleapis.com.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-skins.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-rtl.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>jquery_token/token-input.css" />

<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>jquery_token/jquery.tokeninput.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>ckeditor/ckeditor.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
<style type="text/css">
.danger_alert {
	background-color: #f2dede;
	border-color: #ebccd1;
	color: #a94442;
}
.success_alert {
	background-color: #dff0d8;
	border-color: #d6e9c6;
	color: #3c763d;
}
</style>
</head>
<body class="no-skin" style="min-height:1500px;">
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Product <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Add / Update</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo generateAdminForm("shop","postproduct",""); ?>" method="post" enctype="multipart/form-data">
             
              
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="category_id">Category<br>
                  <small>(Use ctrl to select multiple)</small> : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <select name="category_id[]" id="category_id" class="col-xs-12 col-sm-5 validate[required]" multiple="multiple">
                      <?php echo DisplayCombo($ROW['category_id'],"CATEGORY_ALL"); ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_title">Product Name : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_title" id="post_title" class="col-xs-12 col-sm-8 validate[required]" type="text" placeholder="Name of product" value="<?php echo $ROW['post_title']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_code">Product Code:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_code" id="post_code"  class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="Code  of product" value="<?php echo $ROW['post_code']; ?>" <?php echo ($ROW['post_code'])? "readonly='readonly'":""; ?>>
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_tags">Tags :</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_tags" id="demo-input-pre-populated" class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="tags" value="<?php echo $ROW['post_tags']; ?>">
                    <?php $AR_TAG = $model->getPostTags($ROW['post_tags']); ?>
                    <script type="text/javascript">
					$(document).ready(function() {
						$("#demo-input-pre-populated").tokenInput("<?php echo generateSeoUrlAdmin("operation","tagsearch",""); ?>", {
							prePopulate: <?php echo json_encode($AR_TAG); ?>
						});
					});
					</script> 
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_image">Product Photo &nbsp;<br>
                  <small>(Use ctrl to select multiple photo)</small>: </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_image[]" type="file" class="col-xs-12 col-sm-4" id="post_image" value="<?php echo $ROW['post_image']; ?>" placeholder="Product Image" multiple>
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="short_desc">Short Desc &nbsp;: </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <textarea name="short_desc" class="col-xs-12 col-sm-8" id="short_desc" placeholder="Short Desc"><?php echo $ROW['short_desc']; ?></textarea>
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_desc">Description : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <textarea name="post_desc" class="col-xs-12 col-sm-12 validate[required]" id="post_desc" placeholder="Description"><?php echo $ROW['post_desc']; ?></textarea>
                    <script type="text/javascript">
					CKEDITOR.replace( 'post_desc', {"toolbar":[["Format","Font","FontSize","-","TextColor","BGColor","-","Undo","Redo","-","Cut","Copy","Paste","-","NumberedList","BulletedList","-","SelectAll","-","Blockquote","Bold","Italic","Underline","-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","-","ShowBlocks","Maximize","Source"]],"language":"en"});
				 </script> 
                  </div>
                </div>
              </div>
              <h3 class="lighter block green">Product Statics</h3>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_hsn">Product Weight (GMS) : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_weight" id="post_weight"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Product Weight (GMS) " value="<?php echo $ROW['post_weight']; ?>">
                  </div>
                </div>
              </div>
               <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_hsn">Product Height (CM) : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_height" id="post_height"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Product Height (CM)" value="<?php echo $ROW['post_height']; ?>">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_hsn">Product Width (CM): </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_width" id="post_width"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Product Width (CM)" value="<?php echo $ROW['post_width']; ?>">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_hsn">Product Depth (CM): </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_depth" id="post_depth"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="Product Depth (CM)" value="<?php echo $ROW['post_depth']; ?>">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_hsn">HSN/SAC : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_hsn" id="post_hsn"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="HSN / SAC" value="<?php echo $ROW['post_hsn']; ?>">
                    <input name="range_offer" id="range_offer"  class="col-xs-12 col-sm-4" type="hidden" placeholder="Offer range" value="0">
                  </div>
                </div>
              </div>
              <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_selling_mrp">Mrp Price : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="post_mrp" id="post_mrp"  class="col-xs-12 col-sm-4 cal_product_price validate[required]" type="text" placeholder="MRP Price" value="<?php echo $ROW['post_mrp']; ?>" >
                </div>
              </div>
            </div>
			<div class="space-2"></div>			
			<div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="tax_age">Tax ( % ) : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="tax_age" id="tax_age"  class="col-xs-12 col-sm-4 cal_product_price validate[required]" type="text" placeholder="Product Tax" value="<?php echo $ROW['tax_age']; ?>">
                </div>
              </div>
            </div>
			<div class="space-2"></div>			
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_discount">Discount Price : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="post_discount" id="post_discount"  class="col-xs-12 col-sm-4 cal_product_price" type="text" placeholder="Discount" value="<?php echo $ROW['post_discount']; ?>">
                </div>
              </div>
            </div>
            
			
           
			<div class="space-2"></div>			
			<div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_price">Product  Price : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="post_price" id="post_price"  class="col-xs-12 col-sm-4 cal_product_price validate[required]" type="text" placeholder="Product Price" value="<?php echo $ROW['post_price']; ?>" readonly>
                </div>
              </div>
            </div>	
            <div class="space-2"></div>			
			<div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_pv">Product  BV : </label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input name="post_pv" id="post_pv"  class="col-xs-12 col-sm-4 cal_product_price  validate[required]" type="text" placeholder="Product BV" value="<?php echo $ROW['post_pv']; ?>" >
                </div>
              </div>
            </div>					
            <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_shipping">Product  Shipping : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_shipping" id="post_shipping"  class="col-xs-12 col-sm-4 cal_product_price validate[required]" type="text" placeholder="Product Shipping" value="<?php echo $ROW['post_shipping']; ?>" >
                  </div>
                </div>
              </div>
              <h3 class="lighter block green">Product Security</h3>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_qty_limit">Shopping Qty  Limit : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_qty_limit" id="post_qty_limit"  class="col-xs-12 col-sm-4 validate[required,custom[integer]]" type="text" placeholder="E.g: 1 to 100" value="<?php echo $ROW['post_qty_limit']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <input name="post_bv" id="post_bv"  class="col-xs-12 col-sm-4" type="hidden" placeholder="Product Bv" value="1">
                  <input name="post_ref" id="post_ref"   type="hidden" value="<?php echo getTool($ROW['post_ref'],UniqueId("REF_CODE")); ?>">
                  <input type="hidden" name="lang_id" id="lang_id" value="<?php echo LANG_ID; ?>">
                  <input type="hidden" name="post_date" id="post_date" value="<?php echo ($ROW['post_date']); ?>">
                  <input type="hidden" name="post_id" id="post_id" value="<?php echo _e($ROW['post_id']); ?>">
                  <button type="submit" name="submitPostSave" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button onClick="window.location.href='<?php echo generateSeoUrlAdmin("shop","productlist","");  ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                </div>
              </div>
            </form>
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$(".cal_product_price").on('blur change',function(){
			var post_mrp = parseFloat($("#post_mrp").val());
			var post_discount = parseFloat($("#post_discount").val());
			if(post_discount>=0){
				var post_price = parseFloat(post_mrp) - parseFloat(post_discount);
				$("#post_price").val(post_price);			
			}else{
				$("#post_price").val(0);
			}
		});
		
	});
</script>
</body>
</html>
