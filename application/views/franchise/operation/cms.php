<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$QR_PAGES="SELECT * FROM  tbl_sys_menu_main WHERE 1 ORDER BY order_id ASC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
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
<script type="text/javascript" src="<?php echo BASE_PATH;  ?>ckeditor/ckeditor.js"></script>
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
        <h1> CMS <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Add / Update </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
	  	  <?php  get_message(); ?>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo ADMIN_PATH."operation/cms"; ?>" method="post"> 
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Title </label>
              <div class="col-sm-9">
                <input id="form-field-1" placeholder="Title" name="cms_title"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['cms_title']; ?>">
              </div>
            </div>
           <div class="space-4"></div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta title </label>
              <div class="col-sm-9">
                <input id="form-field-1" placeholder="Meta title" name="meta_title"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['meta_title']; ?>">
              </div>
            </div>
            <div class="space-4"></div>			
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 
Meta description
 </label>
              <div class="col-sm-9">
                <input id="form-field-1" placeholder="Meta description" name="meta_description"  class="col-xs-12 col-sm-12" type="text" value="<?php echo $ROW['meta_description']; ?>">
              </div>
            </div>
            <div class="space-4"></div>			
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta keywords  </label>
              <div class="col-sm-9">
                <input id="form-field-1" placeholder="Meta keywords " name="meta_keywords"  class="col-xs-10 col-sm-5" type="text" value="<?php echo $ROW['meta_keywords']; ?>">
              </div>
            </div>
            <div class="space-4"></div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 
Friendly URL
 </label>
              <div class="col-sm-9">
                <input id="form-field-1" placeholder="Friendly URL" name="link_rewrite"  class="col-xs-10 col-sm-5" type="text" value="<?php echo $ROW['link_rewrite']; ?>">
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Page content  </label>
              <div class="col-sm-9">
                <textarea name="content" class="col-xs-10 col-sm-5 validate[required,custom[integer]]" id="ckeditor" placeholder="Content"><?php echo $ROW['content']; ?></textarea>
				 <script type="text/javascript">
				CKEDITOR.replace('ckeditor');
				</script>
              </div>
            </div>
            <div class="space-4"></div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Indexation by search engines </label>
              <div class="col-sm-3">
               <label>
							<input name="index" id_cms="<?php echo $ROW['index']; ?>" class="ace ace-switch ace-switch-4 checkStatus" <?php if($ROW['index']>0){ echo 'checked="checked"';} ?>  type="checkbox">
							<span class="lbl"></span>
							</label>
              </div>
            </div>
             <div class="space-4"></div>
			 <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Displayed </label>
              <div class="col-sm-3">
               <label>
							<input name="active" id_cms="<?php echo $ROW['active']; ?>" class="ace ace-switch ace-switch-4 checkStatus" <?php if($ROW['active']>0){ echo 'checked="checked"';} ?>  type="checkbox">
							<span class="lbl"></span>
							</label>
              </div>
            </div>
			
            <div class="clearfix form-action">
              <div class="col-md-offset-3 col-md-9">
			  	<input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
				<input type="hidden" name="id_cms" id="id_cms" value="<?php echo $ROW['id_cms']; ?>">
				<input type="hidden" name="id_parent" id="id_parent" value="0">
				
                <button type="submit" name="submitCMS" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                     
                <button onClick="window.location.href='<?php echo ADMIN_PATH."operation/cmslist"; ?>'"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
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
