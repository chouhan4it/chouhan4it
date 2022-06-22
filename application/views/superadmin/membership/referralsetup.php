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
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Referral <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Commission Setup </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-valid" id="form-valid" action="<?php echo ADMIN_PATH."membership/referralsetup"; ?>" method="post">
              <?php
				$QR_PACK = "SELECT tp.* FROM ".$prefix."tbl_package AS tp WHERE tp.delete_sts>0";
				$RS_PACK = $this->db->query($QR_PACK);
				$AR_PACK = $RS_PACK->result_array();
				$AR_PACK_TITLE = $RS_PACK->result_array();
				$AR_PACK_VAL  = $RS_PACK->result_array();
				foreach($AR_PACK as $AR_DT):
			 ?>
              <div class="form-group">
                <div class="col-md-12"> <b><?php echo $AR_DT['package_name']; ?></b> Fast Startup Settings (If this member type refers another member this is what they receive)<br>
                  <table class="basic" width="100%" cellspacing="0">
                    <tbody>
                      <tr>
                        <?php 
					  	foreach($AR_PACK_TITLE as $AR_TITLE):
					   ?>
                        <td align="center"><strong><?php echo $AR_TITLE['package_name'] ?></strong></td>
                        <?php endforeach; ?>
                      </tr>
                      <tr>
                        <?php foreach($AR_PACK_VAL as $AR_VAL): ?>
                        <td align="center"> $
                          <label for="textfield"></label>
                          <input name="referral[<?php echo $AR_DT['package_name']; ?>_<?php echo $AR_VAL['package_name']; ?>]" id="textfield" class="form-control" size="6" style="width: 100px;" value="<?php  echo  $model->getRefferalCmsn($AR_DT['package_id'],$AR_VAL['package_id']); ?>" type="text"></td>
                        <?php endforeach; ?>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <?php endforeach; ?>
              <div class="space-4"></div>
              <div class="space-4"></div>
              <div class="clearfix form-action">
                <div class="col-md-offset-3 col-md-6">
                  <button type="submit" name="submitReferral" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check bigger-110"></i> Save Changes </button>
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
	});
</script>
</body>
</html>
