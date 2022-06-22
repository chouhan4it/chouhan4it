<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
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
        <h4 class="page-title">Events</h4>
        <p class="text-muted page-title-alt">Events & Announcement</p>
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
              <?php 
				$QR_PAGES = "SELECT tn.* FROM tbl_news  AS tn 
				WHERE tn.news_id  NOT IN(SELECT news_id FROM tbl_new_access)
				AND tn.isDelete>0	 AND tn.news_sts>0 ORDER BY  tn.news_date ASC";
				$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
				if($PageVal['TotalRecords'] > 0){
				$Ctrl=1;
					foreach($PageVal['ResultSet'] as $AR_DT):
				?>
              <div class="col-md-12">
                <div class="panel panel-sm">
                  <div class="panel-body">
                    <div class="panel panel-info news-wrap">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-12">
                            <h3> <?php echo $AR_DT['news_title']; ?> </h3>
                            <span class="date"> <i class="fa fa-calendar"></i> <?php echo getDateFormat($AR_DT['news_date'],"d M Y"); ?> </span>
                            <p> <?php echo $AR_DT['news_detail']; ?> </p>
                            <a href="<?php echo generateSeoUrlMember("account","newsdetails",array("news_id"=>_e($AR_DT['news_id']))); ?>"> Read More.... </a> </div>
                        </div>
                      </div>
                    </div>
                    <!-- /panel 1 -->
                  </div>
                </div>
              </div>
              <?php endforeach; }else{ ?>
			  	<div class="alert alert-danger bg-white">
				<i class="fa fa-times"> </i> Events not found
				</div>
			  <?php } ?>
              <div class="col-md-12 pg-wrap">
                <ul class="pagination pull-right">
                  <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
                </ul>
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
