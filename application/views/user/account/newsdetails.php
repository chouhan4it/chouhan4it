<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(2);
$news_id = _d($segment['news_id']);
$model->setNewsRead($news_id,$this->session->userdata('mem_id'));
if($news_id>0){
	$StrWhr .=" AND tn.news_id='$news_id'";
}
$QR_NEWS = "SELECT tn.* FROM tbl_news  AS tn 
WHERE tn.isDelete>0	 AND tn.news_sts>0 $StrWhr ORDER BY  tn.news_date ASC";
$AR_NEWS = $this->SqlModel->runQuery($QR_NEWS,true); 
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
        <h4 class="page-title"><?php echo $AR_NEWS['news_title']; ?></h4>
        <p class="text-muted page-title-alt"><?php echo getDateFormat($AR_NEWS['news_date'],"d M Y"); ?> </p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <!-- /primary heading -->
          <div class="portlet-heading">
            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Profile : </label>
            <div class="clearfix"></div>
          </div>
          <div class="portlet-body" >
            <div class="row">
              <div class="col-md-12">
                <div class="panel panel-sm1">
                  <div class="panel-body">
                    <div class="panel panel-info news-wrap">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-12 details">
                            <h3> <?php echo $AR_NEWS['news_title']; ?> </h3>
                            <span class="date"> <i class="fa fa-calendar"></i><?php echo getDateFormat($AR_NEWS['news_date'],"d M Y"); ?> </span>
                            <p> <?php echo $AR_NEWS['news_detail']; ?> </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /panel 1 -->
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
