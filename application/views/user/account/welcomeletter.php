<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(2);
$member_id = $this->session->userdata('mem_id');
$AR_MEM = $model->getMember($member_id);

$CONFIG_COMPANY_NAME = $model->getValue("CONFIG_COMPANY_NAME");
$SUPPOT_MAIL = $model->getValue("SUPPOT_MAIL");
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
        <h4 class="page-title">Welcome letter</h4>
        <p class="text-muted page-title-alt">Your welcome letter</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-sm1">
          <div class="panel-body">
            <div class="panel panel-info news-wrap">
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-12 details">
                   <a  href="javascript:void(0)"  id="print-now" ><i class="ti-printer"></i></a>
                    <a  href="<?php echo generateSeoUrlMember("account","welcomeletterpdf",""); ?>" target="_blank" ><i class="ti-zip"></i></a>
                    <div class="" id="welcome-letter" role="dialog">
                      <div class="modal-dialog"> 
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <img src="<?php echo LOGO; ?>" alt="<?php echo WEBSITE; ?>"> </div>
                          <div class="modal-body" style="margin-bottom:0px; padding-bottom:2px;">
                            <p class="cmntext" style="line-height:30px; text-align:justify"> Dear <span class="smalltxt" style="line-height:30px; text-align:justify"><?php echo $AR_MEM['full_name']; ?></span>,<br />
                              Your application dated <?php echo DisplayDate($AR_MEM['date_join'])?> is received.  After scrutinizing the same you are found to be competent person.
                              Given below are the ID No. along with other  details for accessing your account &amp; any related information at our  Official 
                              website: <u><em>www.<?php echo DOMAIN;?></em></u>. </p>
                             <p> We suggest you to change your password immediately  &amp; if any problem relating to login occurs,  &amp;
                              you need any assistance  please do not hesitate to contact us at our Email Id: <u><em><?php echo $SUPPOT_MAIL; ?></em></u>. <br />
                              Last but not least you are a very important pillar of our Network marketing  System, it is very important that who so ever works will be rewarded with 
                              maximum returns, and it is very necessary for all customer including you to work  hard to promote our products and earn maximum income and assured payouts. </p>
                            <div class="clearfix">&nbsp;</div>
                            <label><strong>Your login credential :</strong></label>
                            <br>
                            <strong>Username : </strong> &nbsp;<?php echo $AR_MEM['user_id']; ?><br>
                            <strong>Password :</strong> &nbsp; <?php echo $AR_MEM['user_password']; ?><br>
                            <strong>Transaction Password :</strong> &nbsp; <?php echo $AR_MEM['trns_password']; ?>
                            <h5 class="btm"> The <?php echo WEBSITE;?> Team, <br />
                            <strong> Best Regards </strong> </h5>
                          </div>
                          <div class="modal-footer">
                           
                          </div>
                        </div>
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
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<script type="text/javascript" language="javascript" src="<?php echo BASE_PATH; ?>jquery/jquery.print.js"></script>
<script type="text/javascript">
$(function(){
	$("#print-now").click(function(){$( "#welcome-letter" ).print(); return( false );});
	
});
</script>
</html>
