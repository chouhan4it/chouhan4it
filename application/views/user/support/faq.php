<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

	
	$QR_PAGES="SELECT tf.* FROM tbl_faq AS tf   WHERE tf.faq_delete>0 AND tf.faq_active>0 $StrWhr ORDER BY tf.faq_id ASC";
	$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
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
        <h4 class="page-title">Support</h4>
        <p class="text-muted page-title-alt">FAQ</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <?php echo get_message(); ?>
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12"> <?php echo get_message(); ?>
                <div class="portlet light bordered">
                  <h3 class="lighter block green">Frequently ask question</h3>
                  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?php 
			if($PageVal['TotalRecords'] > 0){
			$Ctrl=1;
			foreach($PageVal['ResultSet'] as $AR_DT):
			?>
                    <div class="panel panel-default">
                      <div class="panel-heading" role="tab" id="heading<?php echo $Ctrl; ?>">
                        <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $Ctrl; ?>" aria-expanded="false" aria-controls="collapse<?php echo $Ctrl; ?>"><i class="fa fa-arrow-right"></i> <?php echo $AR_DT['faq_question']; ?> </a> </h4>
                      </div>
                      <div style="height: 0px;" aria-expanded="false" id="collapse<?php echo $Ctrl; ?>" class="panel-collapse collapse" role="tabpane<?php echo $Ctrl; ?>" aria-labelledby="heading<?php echo $Ctrl; ?>">
                        <div class="panel-body"> <?php echo $AR_DT['faq_answer']; ?> </div>
                      </div>
                    </div>
                    <?php $Ctrl++; endforeach; } ?>
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
</html>
