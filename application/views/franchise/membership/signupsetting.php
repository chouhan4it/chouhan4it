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
    <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Sign-up <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Setting </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php  get_message(); ?>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo ADMIN_PATH."membership/signupsetting"; ?>" method="post">
		  	<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Allow New Member Registration :  </label>
              <div class="col-sm-9">
				<label>
				<input name="CONFIG_NEW_MEMBER_STS"  class="ace ace-switch ace-switch-4" <?php if($model->getValue("CONFIG_NEW_MEMBER_STS")>0){ echo 'checked="checked"';} ?>  type="checkbox">
				<span class="lbl"></span>
				</label>
              </div>
            </div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Sponsor is required   :  </label>
              <div class="col-sm-9">
				<select name="CONFIG_SPONSOR_ID" id="CONFIG_SPONSOR_ID" class="col-xs-4 col-sm-4 validate[required]">
					<?php echo DisplayCombo($model->getValue("CONFIG_SPONSOR_ID"),"YN"); ?>
				</select>
				<small class="text-danger">&nbsp; No (If not Sponsor filled use company account as sponsor)</small>
              </div>
            </div>
		    <!--<?php
				$QR_PACK = "SELECT tp.* FROM  tbl_package AS tp WHERE tp.delete_sts>0";
				$RS_PACK = $this->db->query($QR_PACK);
				$AR_PACK = $RS_PACK->result_array();
				foreach($AR_PACK as $AR_DT):
			 ?>
            
			 <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  <?php  echo $AR_DT['package_name']; ?> : $ </label>
              <div class="col-sm-9">
               <input id="form-field-1" placeholder="Package price" name="package_price[]"  class="col-xs-4 col-sm-4 validate[required]" 
			  type="text" 	value="<?php echo $AR_DT['package_price']; ?>">
			  <input type="hidden" name="package_id[]" id="" value="<?php echo $AR_DT['package_id']; ?>">
              </div>
            </div>
	 		<?php endforeach; ?>-->
            
            <div class="space-4"></div>
	
            
            <div class="space-4"></div>
            <div class="clearfix form-action">
              <div class="col-md-offset-3 col-md-6">
                <button type="submit" name="submitPackagePrice" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check bigger-110"></i> Save Changes  </button>
?? ?? ??                 </div>
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
		$("#form-valid").validationEngine();
	});
</script>
</body>
</html>
