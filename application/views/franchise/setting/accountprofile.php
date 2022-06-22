<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
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
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(FRANCHISE_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Bank <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Account </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php  get_message(); ?>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("setting","accountprofile",""); ?>" method="post">
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Account Holder Name:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input placeholder="Holder Name" name="bank_holder" id="bank_holder"  class="form-control input-xlarge validate[required]" type="text"   value="<?php echo $ROW['bank_holder']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Bank Name:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                 
                  <input  placeholder="Bank Name" name="bank_name" id="bank_name"  class="form-control input-xlarge validate[required]" type="text" value="<?php echo $ROW['bank_name']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Account Number:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input  placeholder="Account Number" name="bank_account" id="bank_account"  class="form-control input-xlarge validate[required]" type="text"  value="<?php echo $ROW['bank_account']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Bank IFC Code:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input placeholder="Bank IFC Code"  name="bank_ifc" id="bank_ifc"  class="form-control input-xlarge validate[required]" type="text"  value="<?php echo $ROW['bank_ifc']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Bank Branch:</label>
              <div class="col-xs-12 col-sm-9">
                <div class="clearfix">
                  <input placeholder="Bank Branch" name="bank_branch" id="bank_branch"  class="form-control input-xlarge" type="text"  value="<?php echo $ROW['bank_branch']; ?>">
                </div>
              </div>
            </div>
            <div class="space-2"></div>
            <div class="clearfix space-4"></div>
            <div class="clearfix form-action">
              <div class="col-md-offset-3 col-md-9">
                <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                <button type="submit" name="submit-bank" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Update </button>
     
               
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
