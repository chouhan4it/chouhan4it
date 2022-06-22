<?php 
$model = new OperationModel();
defined('BASEPATH') OR exit('No direct script access allowed');
$member_id = $this->session->userdata('mem_id');
$NFT_STS = $model->getNeftStatus($member_id);
 ?>
<!DOCTYPE html>
<html>
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
        <h4 class="page-title">Bank Details</h4>
        <p class="text-muted page-title-alt">You can updated your neft request bank details</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <!-- /primary heading -->
          <div class="portlet-heading">
            <h3 class="lighter block green">Please fill all details</h3>
            <div class="clearfix"></div>
          </div>
          <div class="portlet-body" >
            <div class="row">
				
				
              <div class="col-lg-8">
			  <?php echo get_message(); ?>
			  	<?php if($ROW['bank_name']!="" && $NFT_STS['neft_sts']!="Y"){ ?>
					<div class="alert alert-danger">Your bank details is pending for verification !</div>
				<?php }elseif($NFT_STS['neft_sts']=="Y"){ ?>
					<div class="alert alert-success">Your bank details status is approved on <?php echo $NFT_STS['neft_sts_date']; ?> !</div>
				<?php } ?>
                <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateMemberForm("account","bankdetail",""); ?>" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">User Id: </label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="user_id" id="user_id" class="form-control input-xlarge validate[required]" type="text" placeholder="Consultant Id" value="<?php echo $ROW['user_id']; ?>" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Member Name as per Bank:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="bank_acct_holder" id="bank_acct_holder"  class="form-control input-xlarge validate[required]" type="text" placeholder="Consultant Name as per Bank" value="<?php echo $ROW['full_name']; ?>"  readonly="true">
                      </div>
                    </div>
                  </div>
				  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Bank Name:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="bank_name" id="bank_name" class="form-control input-xlarge validate[required]" type="text" placeholder="Bank Name" value="<?php echo $ROW['bank_name']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Account No:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="account_number" id="account_number" class="form-control input-xlarge validate[required]" type="text" placeholder="Account No" value="<?php echo $ROW['account_number']; ?>">
                      </div>
                    </div>
                  </div>
                  
                 
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Branch :</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="branch" id="branch" class="form-control input-xlarge" type="text" placeholder="Branch" value="<?php echo $ROW['branch']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">IFC Code:</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input name="ifc_code" id="ifc_code" class="form-control input-xlarge" type="text" placeholder="IFC Code" value="<?php echo $ROW['ifc_code']; ?>">
                      </div>
                    </div>
                  </div>
                 <h3 class="lighter block green">Upload Cancel Cheque</h3>
				 <div class="space-2"></div>
                  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Cancel Cheque / Passbook :</label>
                    <div class="col-xs-12 col-sm-9">
                      <div class="clearfix">
                        <input type="file" name="cancel_cheque" id="cancel_cheque">
                      </div>
                    </div>
                  </div>
				  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password"></label>
                    <div class="col-xs-12 col-sm-3">
                      <div class="clearfix">
                        <img src="<?php echo $model->getCancelCheque($ROW['member_id']); ?>" class="img-thumbnail" width="200" height="80">
                      </div>
                    </div>
                  </div>
                  <div class="clearfix form-action">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                      <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id']; ?>">
                      <button type="submit" name="submitMemberSave" value="1" class="btn btn-info" <?php if($ROW['bank_name']!=""){ echo "disabled=disabled";}?>> <i class="ace-icon fa fa-check bigger-110"></i> Update </button>  &nbsp;&nbsp;<button onClick="window.location.href='<?php echo MEMBER_PATH; ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
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
