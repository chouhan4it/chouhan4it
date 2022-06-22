<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(2);
$post_id = (_d($form_data['post_id'])>0)? _d($form_data['post_id']):_d($segment['post_id']);
$AR_POST = $model->getPostDetail($post_id);
$post_attribute_code = UniqueId("REF_CODE");
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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>public/jquery_token/token-input.css" />

<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>public/jquery_token/jquery.tokeninput.js"></script>
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
<body class="no-skin" style="min-height:2000px;">
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Product Attribute <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Add / Update</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo generateAdminForm("shop","postattribute",""); ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Group Attribute : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <select name="attribute_group_id" class="form-control attribute_group" id="attribute_group_id">
                      <option value="">---select group attribute---</option>
                      <?php echo DisplayCombo($ROW['attribute_group_id'],"ATTRIBUTE_GRP"); ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email"> Attribute : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <select name="attribute_id" class="form-control attribute_value" id="attribute_id">
                      <option value="">---select attribute---</option>
                      <?php echo DisplayCombo($ROW['attribute_id'],"ATTRIBUTE"); ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="product_att_list"> Attribute : </label>
                <div class="col-xs-12 col-sm-6"> <a href="javascript:void(0)" class="btn btn-sm btn-primary" id="add_attr"><i class="fa fa-plus"></i> Add</a> &nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-danger" id="del_attr"><i class="fa fa-times"></i> Delete</a>
                  <div class="space-2"></div>
                  <div class="clearfix">
                    <select id="product_att_list" name="attribute_combination_list[]" class="form-control "  multiple="multiple" >
                      <?php DisplayMultipleCombo("","PRODUCT_COMBO_ATTR",$ROW['post_attribute_id']); ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Product Attribute Code:</label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_attribute_code" id="post_attribute_code"  class="col-xs-12 col-sm-5 validate[required]" type="text" placeholder="Code  of Atrribute" value="<?php echo getTool($ROW['post_attribute_code'],$post_attribute_code); ?>" <?php echo ($ROW['post_attribute_code'])? "readonly='readonly'":""; ?>>
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Product Photo: </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <div class="row" style="overflow-y:scroll; height:200px;" >
                      <?php 
					
                    $QR_IMAGE = "SELECT * FROM tbl_post_file WHERE post_id='$post_id' ORDER BY field_id ASC";
                    $RS_IMAGE = $this->SqlModel->runQuery($QR_IMAGE);
                   	 foreach($RS_IMAGE as $AR_IMAGE):
                    ?>
                      <div class="col-md-3" align="center"> <img src="<?php echo $model->getFileSrc($AR_IMAGE['field_id']); ?>"  class="img-responsive">
                        <input type="checkbox" name="field_id[]" id="field_id<?php echo $AR_IMAGE['field_id']; ?>" 
                         value="<?php echo $AR_IMAGE['field_id']; ?>" <?php if($model->checkCountPro("tbl_post_attribute_image","post_attribute_id='".$ROW['post_attribute_id']."' AND field_id='".$AR_IMAGE['field_id']."'")>0){ echo 'checked="checked"'; }else{ echo ""; } ?> >
                      </div>
                      <?php endforeach; ?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="space-4"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Short Desc &nbsp;: </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <textarea name="post_attribute_detail" class="col-xs-12 col-sm-8" id="post_attribute_detail" placeholder="Short Desc"><?php echo $ROW['post_attribute_detail']; ?></textarea>
                  </div>
                </div>
              </div>
              <h3 class="lighter block green">Attribute Statics</h3>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_mrp">Mrp Price : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_attribute_mrp" id="post_attribute_mrp"  class="col-xs-12 col-sm-4 cal_product_price validate[required]" type="text" placeholder="Product MRP" value="<?php echo $ROW['post_attribute_mrp']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="tax_age">Tax ( % ) : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_attribute_tax" id="post_attribute_tax"  class="col-xs-12 col-sm-4 cal_product_price validate[required]" type="text" placeholder="Product Tax" value="<?php echo $ROW['post_attribute_tax']; ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_discount">Discount Price : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_attribute_discount" id="post_attribute_discount"  class="col-xs-12 col-sm-4 cal_product_price" type="text" placeholder="Discount" value="<?php echo getTool($ROW['post_attribute_discount'],0); ?>">
                  </div>
                </div>
              </div>
              <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_price">Product  Price : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_attribute_price" id="post_attribute_price"  class="col-xs-12 col-sm-4 cal_product_price validate[required]" type="text" placeholder="Product Price" value="<?php echo $ROW['post_attribute_price']; ?>">
                  </div>
                </div>
              </div>
			
				 <div class="space-2"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="post_attribute_pv">Product  BV : </label>
                <div class="col-xs-12 col-sm-9">
                  <div class="clearfix">
                    <input name="post_attribute_pv" id="post_attribute_pv"  class="col-xs-12 col-sm-4 cal_product_price validate[required]" type="text" placeholder="Product BV" value="<?php echo $ROW['post_attribute_pv']; ?>">
                  </div>
                </div>
              </div>
			<div class="space-2"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                  <input type="hidden" name="post_id" id="post_id" value="<?php echo _e($AR_POST['post_id']); ?>">
                  <input type="hidden" name="post_attribute_id" id="post_attribute_id" value="<?php echo _e($ROW['post_attribute_id']); ?>">
                  <button type="submit" name="submit-post-attribute" id="submit-post-attribute" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
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
		$("#form-valid").validationEngine(
			{onValidationComplete: function(form, valid){
				$("#product_att_list > option").each(function(){
					$('#product_att_list option').attr("selected", "selected");
				});
				return true;
        	}}
		);
		$(".cal_product_price").on('blur change',function(){
			var post_attribute_mrp = $("#post_attribute_mrp").val();
			var post_attribute_discount = $("#post_attribute_discount").val();
			if(post_attribute_mrp>0){
				var post_attribute_discount = parseFloat(post_attribute_discount);
				var post_attribute_price = parseFloat(post_attribute_mrp-post_attribute_discount);
				$("#post_attribute_price").val(post_attribute_price);
			}else{
				$("#post_attribute_price").val(0);
			}
		});
		
		$(".attribute_group").on('blur',populate_attr);
		$(".attribute_group").on('change',populate_attr);
		
		function populate_attr(){
			var attribute_group_id = $(this).val();
			$(".attribute_value").attr('disabled',true);
			var URL_ATTR = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=ATTR_VALUE&attribute_group_id="+attribute_group_id;
			$(".attribute_value").load(URL_ATTR);
			$(".attribute_value").attr('disabled',false);
		}
		
		$("#add_attr").on('click',function(){
			var attribute_group_id = $("#attribute_group_id").val();
			var attribute_id = $("#attribute_id").val();
			var select_arr = []; 
			$('#product_att_list option').each(function() {
				select_arr.push($(this).val());
			});
			var option_ctrl = $('select#product_att_list option').length;
			var URL_COMB = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=ADD_ATTR_SELECT&attribute_group_id="+attribute_group_id+"&attribute_id="+attribute_id+"&select_arr="+select_arr;
			$.getJSON(URL_COMB,function(jsonCombo){
				if(jsonCombo.attribute_id>0){
					switch(jsonCombo.error_msg){
						case "success":
							if(option_ctrl<=1){
								$("#product_att_list").append($('<option>', {value:jsonCombo.attribute_id, text: jsonCombo.file_name}));
							}else{
								alert("You can add only two combination  on this attribute.");	
							}
						break;
						case "already":
							alert("You can only add one combination per attribute type.");
						break;
					}
				}
			});
		});
		
		$("#del_attr").on('click',function(){
			$("#product_att_list > option:selected").each(function(){
				$(this).remove();
			});
		});
		
	});
</script>
</body>
</html>
