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
        <h4 class="page-title">Download</h4>
        <p class="text-muted page-title-alt">Download your wallet statement, your order and your commission</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <!-- /primary heading -->
          <div class="portlet-heading">
            <div class="clearfix"></div>
          </div>
          <div class="portlet-body" >
            <div class="row">
              <div class="col-md-12">
                <div class="panel panel-sm">
                  <div class="panel-body">
                    <div class="panel panel-info news-wrap">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="panel panel-default panel-border">
                              <div class="panel-heading">
                                <h3 class="panel-title">E-wallet statement</h3>
                              </div>
                              <div class="panel-body">
                                <p> You can download your wallet transaction stament over here , click here to download <a  class="btn btn-white btn-custom btn-rounded waves-effect" href="<?php echo generateSeoUrlMember("download","report",array("action_request"=>"EWALLET")); ?>" onClick="return confirm('Make sure , you want to download it')">Download</a></p>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="panel panel-border panel-custom">
                              <div class="panel-heading">
                                <h3 class="panel-title">My Order</h3>
                              </div>
                              <div class="panel-body">
                                <p> You can download your oder and it's point value with details , click here to download <a  class="btn btn-default btn-custom btn-rounded waves-effect waves-light" href="<?php echo generateSeoUrlMember("download","report",array("action_request"=>"ORDER")); ?>" onClick="return confirm('Make sure , you want to download it')">Download</a> </p>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="panel panel-border panel-primary">
                              <div class="panel-heading">
                                <h3 class="panel-title">Commission Statement</h3>
                              </div>
                              <div class="panel-body">
                                <p>You can download your comission statement  details , click here to download <a  class="btn btn-primary btn-custom btn-rounded waves-effect waves-light" href="<?php echo generateSeoUrlMember("download","report",array("action_request"=>"COMMISSION")); ?>" onClick="return confirm('Make sure , you want to download it')">Download</a> </p>
                              </div>
                            </div>
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
