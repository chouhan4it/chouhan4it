<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	$model = new OperationModel();
	$segment = $this->uri->uri_to_assoc(1);
	$member_id  = FCrtRplc(_d($segment['member_id']));
	$AR_MEM = $model->getMember($member_id);
	
	$SUPPOT_MAIL = $model->getValue("SUPPOT_MAIL");
	
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
<!--<![endif]-->

<!-- Mirrored from opencart.dostguru.com/FD01/fruitino_04/index.php?route=account/login by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Dec 2021 08:33:10 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<!-- /Added by HTTrack -->
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<?php $this->load->view('layout/svgsymbol'); ?>
<body>
<?php $this->load->view('layout/header'); ?>
<div id="information-information" class="container">
  <ul class="breadcrumb">
    <li><a href="<?php echo BASE_PATH; ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="javascript:void(0)">Welcome </a></li>
  </ul>
  <div class="row">
    <div id="content" class="col-sm-12">
      <div class="col-lg-12 col-md-12">
        <?php get_message(); ?>
        <hr>
        <p class="" style="text-align:justify"> Dear <span class="smalltxt" style="line-height:30px; text-align:justify"><strong><?php echo $AR_MEM['full_name']; ?>&nbsp;(<?php echo $AR_MEM['user_id']; ?>),</strong></span></p>
        <p> Your application dated <?php echo DisplayDate($AR_MEM['date_join'])?> is received.  After scrutinizing the same you are found to be competent person.
          you can find any related information at our  Official 
          website: <u><?php echo DOMAIN;?></u>. We suggest you to change your password immediately  &amp; if any problem relating to login occurs,  &amp;        
          you need any assistance  please do not hesitate to contact us at our Email Id: <u><?php echo $SUPPOT_MAIL; ?></u>. </p>
        <p> Last but not least you are a very important pillar of our Network marketing  System, it is very important that who so ever works will be rewarded with 
          maximum returns, and it is very necessary for all customer including you to work  hard to promote our products and earn maximum income and assured payouts. </p>
        <br>
        <label><strong>Your login credential :</strong></label>
        <br>
        <strong>Username : </strong> &nbsp;<?php echo $AR_MEM['user_id']; ?><br>
        <strong>Password :</strong> &nbsp; <?php echo $AR_MEM['user_password']; ?><br>
        <strong>Transaction Password :</strong> &nbsp; <?php echo $AR_MEM['trns_password']; ?>
        <div class="clearfix">&nbsp;</div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
</body>
</html>
