<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$member_id = $this->session->userdata('mem_id');
$QR_PAN = "SELECT tmp.* FROM tbl_mem_pancard AS tmp WHERE tmp.member_id='".$member_id."' AND tmp.approve_sts='1' ORDER BY tmp.pan_id DESC LIMIT 1";
$AR_PAN = $this->SqlModel->runQuery($QR_PAN,true);
$QR_DOC = "SELECT tmp.* FROM tbl_mem_pancard AS tmp WHERE tmp.member_id='".$member_id."' ORDER BY tmp.pan_id DESC";
$AD_DOCS = $this->SqlModel->runQuery($QR_DOC);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
	<div class="container">
	<!-- Page-Title -->
	<div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">My Pancard</h4>
            <p class="text-muted page-title-alt">Your pan verifcation status</p>
        </div>
	</div>
    <!-- end row -->
	<div class="row">
		<!-- Transactions -->
		<div class="col-lg-12">
		<div class="portlet">
		<div class="portlet-body">
        <?php if($AR_PAN['approve_sts']<1){?>
		<div class="row">
			<?php echo get_message(); ?>
			<div class="col-lg-8">
                <form action="<?php  echo  generateMemberForm("account","pancard"); ?>" id="document" name="document" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="form-group">
	                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Browse Pancard : </label>
    	            <div class="col-xs-12 col-sm-9">
        	        	<div class="clearfix">
            	    	<input id="pan_file" name="pan_file" class="form-control validate[required]" type="file">
                		</div>
                	</div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="form-group">
                	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Pan No : </label>
                	<div class="col-xs-12 col-sm-9">
                		<div class="clearfix">
                		<input id="pan_no" name="pan_no" class="form-control validate[required]" maxlength="10" type="text">
                		</div>
                	</div>
                </div>
                <div class="space-2">&nbsp;</div>
                <div class="clearfix form-action">
                    <div class="col-md-offset-3 col-md-9">
                    <button type="submit" name="savePancard" value="1" class="btn btn-primary"> <i class="ace-icon fa fa-check bigger-110"></i> Save </button> <button onClick="window.location.href='<?php echo MEMBER_PATH; ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                    </div>
                </div>
                </form>
			</div>
		</div>
        <?php }?>
		<div class="clearfix">&nbsp;</div>
		<?php
        if(count($AD_DOCS)>0){
        ?>
        <div class="row">
            <div class="col-md-12">
            <div class="table-responsive">
	        <table class="table table-striped">
				<thead>
                    <tr>
                        <th>Pan No</th>
                        <th>PanCard</th>
                        <th>Verifcation</th>
                        <th>Date</th>
                    </tr>
        		</thead>
        		<tbody>
					<?php 
                    $Ctrl=1; 
					foreach($AD_DOCS as $AD_DOC): 
                    $pan_file = $model->getPanCard($AD_DOC['pan_id']);
                    ?>
                    <tr>
                        <td><?php echo strtoupper($AD_DOC['pan_no']); ?></td>
                        <td><a target="_blank" href="<?php echo $pan_file; ?>"><?php echo $pan_file; ?></a></td>
                        <td><?php echo ($AD_DOC['approve_sts']>0)? "Approved":"Pending"; ?></td>
                        <td><?php echo DisplayDate($AD_DOC['date_time']); ?></td>
                    </tr>
        			<?php 
					$Ctrl++; 
					endforeach; 
					?>
        		</tbody>
        	</table>
            </div>
            </div>
        </div>
		<?php }?>
        </div>
        </div>
        </div>
    </div>
	<?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
	</div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#document").validationEngine();
	});
</script>
</html>