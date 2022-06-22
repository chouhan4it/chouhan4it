<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$direct_count = $model->BinaryCount($ROW['member_id'],"DirectCount");
$total_count = $model->BinaryCount($ROW['member_id'],"TotalCount");
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
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<?php auto_complete(); ?>
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
.pointer {
	cursor: pointer;
}
</style>
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
          <h1> Delete <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Member </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?php if($ROW['delete_member']==true){ ?>
            <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("member","memberdelete",""); ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3"> Full Name : </label>
                <div class="col-sm-9"> <strong><?php echo $ROW['full_name']; ?>[<?php echo $ROW['user_id']; ?>]</strong> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3"> No of Direct Member : </label>
                <div class="col-sm-9"> <strong><?php echo ($direct_count>0)? $direct_count:"No Directs"; ?></strong><?php echo ($direct_count>0)? "<small class='text-danger'> &nbsp; ( All this member will delete? )</small>&nbsp;&nbsp;<a target='_blank' href='".generateSeoUrlAdmin("member","direct","")."?member_id="._e($ROW['member_id'])."'>View</a>":""; ?> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3"> No of Downline Member : </label>
                <div class="col-sm-9"> <strong><?php echo ($total_count>0)? $total_count:"No Downlines"; ?></strong><?php echo ($total_count>0)? "<small class='text-danger'> &nbsp; ( All this member will delete? )</small>&nbsp;&nbsp;<a target='_blank' href='".generateSeoUrlAdmin("member","level","")."?member_id="._e($ROW['member_id'])."'>View</a>":""; ?> </div>
              </div>
              <?php if($direct_count==0 && $total_count==0){ ?>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="member_id" id="member_id" value="<?php echo _e($ROW['member_id']); ?>">
                  <button type="submit" name="deleteConfirmMember" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check"></i> Confirm </button>
                  <button type="button" onClick="window.location.href='<?php echo generateSeoUrlAdmin("member","memberdelete","");  ?>'" name="cancelButton" value="1" class="btn btn-danger"> <i class="ace-icon fa fa-times"></i> Cancel </button>
                </div>
              </div>
              <?php }else{ ?>
              <div class="alert alert-block alert-danger bg-white"><i class='fa fa-times red'></i> This user cannot be deleted , it has a donwline user</div>
              <a href="<?php echo generateSeoUrlAdmin("member","memberdelete","");  ?>">Click here to delete other user</a>
              <?php } ?>
            </form>
            <?php }else{ ?>
            <form class="form-horizontal" name="form-page" id="form-page" action="<?php echo generateAdminForm("member","memberdelete",""); ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> User Id : </label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="User Id" name="member_user_id"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $user_id; ?>">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <button type="submit" name="submitDeleteMember" value="1" class="btn btn-danger"> <i class="ace-icon fa fa-check bigger-110"></i> Delete </button>
                </div>
              </div>
            </form>
            <?php } ?>
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
	});
</script>
</body>
</html>
