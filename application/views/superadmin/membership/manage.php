<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Memberships <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Set Up </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo ADMIN_PATH."membership/manage"; ?>" method="post">
              <?php
				$QR_PACK = "SELECT tp.* FROM ".$prefix."tbl_package AS tp WHERE tp.delete_sts>0";
				$RS_PACK = $this->db->query($QR_PACK);
				$AR_PACK = $RS_PACK->result_array();
				foreach($AR_PACK as $AR_DT):
			 ?>
              <div class="form-group">
                <label class="col-md-offset-1 col-sm-4 control-label no-padding-right" for="form-field-1">
                  <input id="form-field-1" placeholder="Package Name" name="package_name[]"  class="col-xs-12 col-sm-12 validate[required]" 
			  type="text" 	value="<?php echo $AR_DT['package_name']; ?>">
                  <input type="hidden" name="package_id[]" id="" value="<?php echo $AR_DT['package_id']; ?>">
                </label>
                <label class="col-sm-1">
                  <button class="btn btn-xs btn-danger" onClick="if(confirm('Make sure want to delete this package')){ window.location.href='<?php echo generateSeoUrlAdmin("membership","manage",array("package_id"=>$AR_DT['package_id'],"action_request"=>"DELETE")); ?>'; }" type="button" style="margin-top:8px;">
                  <i class="fa fa-trash-o"></i> <span class="bold">Delete</span></label>
              </div>
              <?php endforeach; ?>
              <div class="space-4"></div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-1 col-md-6">
                  <button type="submit" name="submitPackage" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check bigger-110"></i> Save Changes </button>
                  <button type="button" name="newPackage" value="1" class="btn btn-info openModal"> <i class="ace-icon fa fa-plus bigger-110"></i> Add New Membership </button>
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
  <div id="form-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="smaller lighter blue no-margin">Add New Membership</h3>
        </div>
        <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo ADMIN_PATH."membership/manage"; ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Name </label>
              <div class="col-sm-6">
                <input id="form-field-1" placeholder="Package Name" name="package_name"  class="col-xs-10 col-sm-12 validate[required]" type="text" 
			  value="<?php echo $ROW['package_name']; ?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
            <input type="hidden" name="package_id" id="package_id"  value="<?php echo $ROW['package_id']; ?>">
            <button type="submit" name="submitNewPackage" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Submit </button>
            <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"> <i class="ace-icon fa fa-times"></i> Close </button>
          </div>
        </form>
      </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
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
		$(".openModal").on('click',function(){
			$('#form-modal').modal('show');
			return false;
		});
	});
</script>
</body>
</html>
