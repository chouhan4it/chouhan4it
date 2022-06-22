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
          <h1> Change <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Sponsor </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?php if($ROW['member_id']==true){ ?>
            <form class="form-horizontal"   name="form-page" id="form-page" action="<?php echo generateAdminForm("member","changesponsor",""); ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Full Name : </label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="Full Name" name="member_user_id"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['full_name']; ?>" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> User Id : </label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="User Id" name="member_user_id"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['user_id']; ?>" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Current Sponsor : </label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="Currenct Sponsor" name="member_user_id"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $ROW['spsr_first_name']; ?>[<?php echo $ROW['spsr_user_id']; ?>]" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> New Sponsor Id : </label>
                <div class="col-sm-9">
                  <input id="form-field-1" placeholder="New Sponsor Id" name="new_sponsor_id"  class="col-xs-10 col-sm-5 validate[required]" type="text" value="<?php echo $new_sponsor_id; ?>" maxlength="10">
                </div>
              </div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <input type="hidden" name="member_id" id="member_id" value="<?php echo _e($ROW['member_id']); ?>">
                  <button type="submit" name="submitSponsorMember" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Change Sponsor </button>
                  <button type="buton" name="buttonCancel" class="btn btn-danger"> <i class="ace-icon fa fa-times bigger-110"></i> Cancel </button>
                </div>
              </div>
            </form>
            <?php }else{ ?>
            <form class="form-horizontal" name="form-page" id="form-page" action="<?php echo generateAdminForm("member","changesponsor",""); ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> User Id : </label>
                <div class="col-sm-6">
                  <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $AR_MEM['user_id']; ?>" />
                  &nbsp;&nbsp;
                  <input name="member_id" type="hidden" id="member_id" value="" />
                </div>
              </div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-9">
                  <button type="submit" name="submitValidMember" value="1" class="btn btn-danger"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
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
		$("#form-page").validationEngine();
	});
</script> 
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo BASE_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</body>
</html>
