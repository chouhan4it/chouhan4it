<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$member_id = $this->session->userdata('mem_id');
$AR_KYC = $model->getKycDetail($member_id);
$profile_photo = $model->kycDocument($AR_KYC['kyc_id'],"file_photo");
$file_src_add = $model->kycDocument($AR_KYC['kyc_id'],"file_address");
$file_src_id = $model->kycDocument($AR_KYC['kyc_id'],"file_passport");
$kyc_form_file = $model->kycDocument($AR_KYC['kyc_id'],"kyc_form");

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
        <h4 class="page-title">KYC</h4>
        <p class="text-muted page-title-alt">You can upload your document ot verify kyc</p>
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
			<?php if($AR_KYC['approved_sts']>0){ ?>
				<div class='alert alert-block alert-success bg-white'><i class='fa fa-check green'></i>Your Address proof  is  verfied on <?php echo $AR_KYC['neft_sts_date']; ?></div>
			<?php } ?>
			<?php if($AR_KYC['approved_sts_id']>0){ ?>
				<div class='alert alert-block alert-success bg-white'><i class='fa fa-check green'></i>Your Id proof  is  verfied on <?php echo $AR_KYC['approved_date_id']; ?></div>
			<?php } ?>
			<?php echo get_message(); ?>
              <div class="col-lg-12">
                
				<table width="100%" border="0" class="table table-striped text-left"> 
					<form action="<?php  echo  generateMemberForm("account","kyc"); ?>" id="form-photo" name="orm-photo" method="post" enctype="multipart/form-data" autocomplete="off">
					<tr>
					  <td><strong>Photo :</strong> </td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td><input id="file_photo" name="file_photo" class="form-control imageFormat" type="file"><br />
					  <img src="<?php echo $profile_photo; ?>" class="img-circle" width="60px" height="60px" />					  </td>
				      <td align="center"> <button type="submit" name="saveKycForm" value="1" class="btn btn-primary"> <i class="ace-icon fa fa-check bigger-110"></i> Upload </button></td>
					</tr>
					</form>
					<form action="<?php  echo  generateMemberForm("account","kyc"); ?>" id="form-add" name="form-add" method="post" enctype="multipart/form-data" autocomplete="off">
					<tr>
						<td width="18%"><strong>Address Proof :</strong> </td>
						<td width="17%"><select name="add_type" class="form-control" id="add_type" style="width:200px;">
                          <option value="">---- select -----</option>
                          <?php echo DisplayCombo($AR_KYC['add_type'],"ADDRESS_TYPE"); ?>
                      </select></td>
					  <td width="21%"><input type="text" name="document_add" id="document_add" class="form-control validate[required]" value="<?php echo $AR_KYC['document_add']; ?>" placeholder="document no" /></td>
						<td width="31%"><input id="file_address" name="file_address" class="form-control imageFormat" type="file"><br />
						<a target="_blank" href="<?php echo ($file_src_add!='')? $file_src_add:"javascript:void(0)"; ?>"><img src="<?php echo $file_src_add; ?>" class="img-thumbnail" width="60px" height="60px"/></a>					  </td>
				      <td width="13%" align="center"> <button type="submit" name="saveKycForm" value="1" class="btn btn-primary"> <i class="ace-icon fa fa-check bigger-110"></i> Upload </button></td>
					</tr>
					</form>
					<form action="<?php  echo  generateMemberForm("account","kyc"); ?>" id="form-id" name="form-id" method="post" enctype="multipart/form-data" autocomplete="off">
					<tr>
						<td><strong>Photo Identity Proof :</strong> </td>
						<td><select name="id_type" class="form-control" id="id_type" style="width:200px;" >
                          <option value="">---- select -----</option>
                          <?php echo DisplayCombo($AR_KYC['id_type'],"ID_TYPE"); ?>
                        </select></td>
						<td><input type="text" name="document_id" id="document_id" class="form-control validate[required]" value="<?php echo $AR_KYC['document_id']; ?>" placeholder="document no" /></td>
						<td><input id="file_passport" name="file_passport" class="form-control imageFormat" type="file"><br /><a target="_blank" href="<?php echo ($file_src_id!='')? $file_src_id:"javascript:void(0)"; ?>"><img src="<?php echo $file_src_id; ?>" class="img-thumbnail" width="60px" height="60px" /></a><br /></td>
					    <td align="center"> <button type="submit" name="saveKycForm" value="1" class="btn btn-primary"> <i class="ace-icon fa fa-check bigger-110"></i> Upload </button></td>
					</tr>
					</form>
					<form action="<?php  echo  generateMemberForm("account","kyc"); ?>" id="kyc-form" name="kyc-form" method="post" enctype="multipart/form-data" autocomplete="off">
					<!--<tr>
					  <td><strong>KYC Form</strong> : </td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td><input id="kyc_form" name="kyc_form" class="form-control imageFormat" type="file"><br />
					  <img src="<?php echo $kyc_form_file; ?>" class="img-thumbnail" width="60px" height="60px" />	</td>
					  <td align="center"><button type="submit" name="saveKycForm" value="1" class="btn btn-primary"> <i class="ace-icon fa fa-check bigger-110"></i> Upload </button></td>
					  </tr>-->
					</form>
				</table>

                  <div class="clearfix form-action">
                    <div class="col-md-12">
                     
                      <button onClick="window.location.href='<?php echo MEMBER_PATH; ?>'"  class="btn btn-danger pull-right" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row">
              <div class="col-md-12">
                <div class="panel panel-info ">
                  <div class="panel-heading">
                    <h3 class="panel-title text-white"> <i class="fa fa-info info"></i> KYC Upload Guidelines </h3>
                  </div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-12"> 
					  	<ul style="list-style-type:decimal;">
							<li>All Fields are mandatory</li>
							<li>Upload scan copy of KYC documents that are being submitted; Click on browse button to upload</li>
							<li>Documents being uploaded should be in any one of these formats only - .JPG .JPEG , .GIF , .BMP , .PNG , .TIFF and file size should not exceed 2000 KB/2 MB</li>
							<li>Only unique KYC documents will be accepted; please put exact document number (without any extra space) in field provided for your KYC request to be admissible</li>
							<li>KYC document must be in the name of main applicant; in case of address proof, the address in proof must match the address in <?php echo WEBSITE; ?> records (Please place separate request for change of address if address is different in records)</li>
							<li>For Address proof verification and acceptance by Company, Name of main applicant and address in the proof must appear in the document uploaded. Please see sample document for clarity. For sample <a  target="_blank" href="<?php echo BASE_PATH; ?>upload/adhar-demo.jpg">Click here</a></li>
						</ul>
					  </div>
                     
                    </div>
                  </div>
                </div>
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
<script type="text/javascript" src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-add").on('submit',function(){
			if($("#add_type").val()==""){
				alert("Please select address type?");
				$("#add_type").focus();
				return false;
			}
			if($("#document_add").val()==""){
				alert("Please enter document no?");
				$("#document_add").focus();
				return false;
			}
			if($("#file_address").val()==""){
				alert("Please select doucment to upload?");
				return false;
			}
			return true;
			
		});
		$("#form-id").on('submit',function(){
			if($("#id_type").val()==""){
				alert("Please select id type?");
				$("#id_type").focus();
				return false;
			}
			if($("#document_id").val()==""){
				alert("Please enter document no?");
				$("#document_id").focus();
				return false;
			}
			if($("#file_passport").val()==""){
				alert("Please select doucment to upload?");
				return false;
			}
			return true;
			
		});
		 $(".imageFormat").on('change',function(){
			$in=$(this);
			 var fileUpload = $in[0];
			var FileType = ImageFile($in.val());
				if(FileType==0){
					alert('Please select a correct file format(.png/.jpg/.jpeg/.gif) !!!');
					$in.val("");
				}
		});
	});
</script>
</html>
