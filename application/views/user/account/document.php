<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$member_id = $this->session->userdata('mem_id');
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
        <h4 class="page-title">My Document</h4>
        <p class="text-muted page-title-alt">You can save your personal document</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <!-- /primary heading -->
		  
          <div class="portlet-body" >
            <div class="row">
			 <?php echo get_message(); ?>
              <div class="col-lg-8">
                <form action="<?php  echo  generateMemberForm("account","document"); ?>" id="document" name="document" method="post" enctype="multipart/form-data" autocomplete="off">
					
					 <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Browse Document : </label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                       <input  id="file_name" name="file_name"  class="form-control" type="file">
                      </div>
                    </div>
                  </div>
                  
                  <div class="space-2">&nbsp;</div>
                  <div class="clearfix form-action">
                    <div class="col-md-offset-3 col-md-9">
                      <button type="submit" name="saveDocument" value="1" class="btn btn-primary"> <i class="ace-icon fa fa-check bigger-110"></i> Save </button> <button onClick="window.location.href='<?php echo MEMBER_PATH; ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
			<div class="clearfix">&nbsp;</div>
			<?php
			$QR_DOC = "SELECT tmd.* FROM tbl_mem_doc AS tmd WHERE tmd.member_id='".$member_id."'  AND tmd.delete_sts>0
			ORDER BY tmd.document_id DESC LIMIT 10";
			$AD_DOCS = $this->SqlModel->runQuery($QR_DOC);
			if(count($AD_DOCS)>0){
			?>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                     
                      <tr>
                        <th>Srl #</th>
                        <th>Document</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php  $Ctrl=1; foreach($AD_DOCS as $AD_DOC): ?>
                      <tr>
                        <td><?php echo $Ctrl; ?></td>
                        <td><?php echo $AD_DOC['file_name']; ?></td>
                        <td><?php echo $AD_DOC['file_type']; ?></td>
                        <td><?php echo $AD_DOC['file_size']; ?></td>
                        <td><?php echo DisplayDate($AD_DOC['date_time']); ?></td>
                        <td>
							
								<a target="_blank" href="<?php echo  $model->documentLink($AD_DOC['document_id']); ?>"><i class="fa fa-download"></i></a>
							&nbsp;&nbsp;
								<a onclick="return confirm('Make sure, want to delete this document?')" 
								href="<?php echo generateSeoUrlMember("account","document",array("document_id"=>_e($AD_DOC['document_id']),"action_request"=>"DELETE")); ?>"><i class="fa fa-trash"></i></a>							
							
						</td>
                      </tr>
                      <?php $Ctrl++; endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              
            </div>
			<?php } ?>
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
		$("#form-page").validationEngine({
				'custom_error_messages': {
					'#pin_code': {
						'custom[integer]': {
							'message': "Not a valid postal code ."
						}
					}
					,'#member_mobile': {
						'custom[integer]': {
							'message': "Not a valid phone no."
						}
					}
					
				}
			});
		
	});
</script>
</html>
