<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
	$model = new OperationModel();
	$segment = $this->uri->uri_to_assoc(1);
	$member_id  = FCrtRplc(_d($segment['email']));
	
	 $QR_GET = "SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,
		 tree.nlevel, tree.left_right, tree.nleft, tree.nright  FROM  tbl_members AS tm	
		 LEFT JOIN  tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
		 LEFT JOIN  tbl_mem_tree AS tree ON tm.member_id=tree.member_id
		 WHERE tm.member_id='$member_id'";
	$AR_MEM = $this->SqlModel->runQuery($QR_GET,true);
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<body class="cnt-home">
<!-- ============================================== HEADER ============================================== -->

<?php $this->load->view('layout/header'); ?>

<!-- ============================================== HEADER : END ============================================== -->
<div class="breadcrumb">
  <div class="container">
    <div class="breadcrumb-inner">
      <ul class="list-inline list-unstyled">
        <li><a href="<?php echo BASE_PATH; ?>">Home</a></li>
        <li class='active'>Email Verification</li>
      </ul>
    </div>
    <!-- /.breadcrumb-inner --> 
  </div>
  <!-- /.container --> 
</div>
<!-- /.breadcrumb -->

<div class="body-content">
<div class="container">
  <div class="contact-page">
    <div class="row"> 
    	<div class="col-md-10 col-md-offset-1">
          <?php 
				if($AR_MEM['email_sts']=="N" && $AR_MEM['member_id']>0){
					$fldcType="alert alert-block alert-success";
					$fldvMessage = "Your email verified successfully";
					$this->SqlModel->updateRecord("tbl_members",array("email_sts"=>"Y"),array("member_id"=>$AR_MEM['member_id']));
					
				}elseif($AR_MEM['email_sts']=="Y" && $AR_MEM['member_id']>0){
					$fldcType="alert alert-block alert-danger";
					$fldvMessage = "This email has already verifed";
				}else{
					$fldcType="alert alert-block alert-danger";
					$fldvMessage = "No direct script access allowed";
				}
				echo "<div class='".$fldcType."' id='jsCallId'><i class='ace-icon fa fa-check green'></i>&nbsp;".$fldvMessage."</div>";
			?>
        </div>
    </div>
  </div>
  <!-- /.row --> 
</div>
<!-- /.container --> 
<!-- ============================================================= FOOTER ============================================================= --> 

<!-- ============================================== INFO BOXES ============================================== -->
<?php $this->load->view('layout/infobox'); ?>
<!-- /.info-boxes --> 
<!-- ============================================== INFO BOXES : END ============================================== --> 

<!-- ============================================================= FOOTER ============================================================= -->
<?php $this->load->view('layout/footer'); ?>
<!-- ============================================================= FOOTER : END============================================================= --> 

<!-- For demo purposes – can be removed on production --> 

<!-- For demo purposes – can be removed on production : End --> 

<!-- JavaScripts placed at the end of the document so the pages load faster -->
<?php $this->load->view('layout/footerjs'); ?>
</body>
</html>