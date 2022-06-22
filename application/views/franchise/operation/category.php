<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
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
<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>ckeditor/ckeditor.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1>Concierge <small> <i class="ace-icon fa fa-angle-double-right"></i>&nbsp; Add / Update </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
	  	  <?php  get_message(); ?>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("operation","category",""); ?>" method="post" enctype="multipart/form-data"> 
		  <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">  Name  </label>
              <div class="col-sm-9">
                 <input id="form-field-1" placeholder="Name" name="category_name"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['category_name']; ?>">
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Short Desc</label>
              <div class="col-sm-9">
                <textarea name="category_short" class="col-xs-10 col-sm-5 validate[required]" id="form-field-1" placeholder="Short Description"><?php echo $ROW['category_short']; ?></textarea>
              </div>
            </div>
			<div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Details</label>
              <div class="col-sm-9">
                <textarea name="category_detail" class="col-xs-10 col-sm-5 validate[required]" id="category_detail" placeholder="Service details"><?php echo $ROW['category_detail']; ?></textarea>
				 <script type="text/javascript">
					CKEDITOR.replace( 'category_detail', {"toolbar":[["Format","Font","FontSize","-","TextColor","BGColor","-","Undo","Redo","-","Cut","Copy","Paste","-","NumberedList","BulletedList","-","SelectAll","-","Blockquote","Bold","Italic","Underline","-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","-","ShowBlocks","Maximize","Source"]],"language":"en"});
				
				 </script>
              </div>
            </div>
            
             <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Photo</label>
              <div class="col-sm-6">
                <input id="form-field-1" name="category_img"  class="col-xs-10 col-sm-5" type="file" >
			</div>
			  <div class="col-sm-3">
				<img src="<?php echo $model->getCategoryImgSrc($ROW['category_id']); ?>" class="img-thumbnail" height="60" width="80">
              </div>
            </div>
            
            <div class="space-4"></div>
            
            <div class="clearfix form-action">
              <div class="col-md-offset-3 col-md-9">
			  	<input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
				<input type="hidden" name="category_id" id="category_id" value="<?php echo _e($ROW['category_id']); ?>">
                <button type="submit" name="submitCategory" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                     
                <button onClick="window.location.href='<?php echo ADMIN_PATH."operation/categorylist"; ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
	});
</script>
</body>
</html>
