<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	$model = new OperationModel();
		
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
<div id="information-contact" class="container">
  <ul class="breadcrumb">
    <li><a href="index9328.html?route=common/home"><i class="fa fa-home"></i></a></li>
    <li><a href="index2724.html?route=information/contact">Contact Us</a></li>
  </ul>
  <div class="row infobg">
    <div id="content" class="col-xs-12">
      <h1 class="heading text-center"><span>Contact Us</span><svg>
        <use xlink:href="#headingsvg"></use>
        </svg></h1>
      <div class="row">
      	
        <div class="col-md-4 col-xs-12 infocnt">
          <legend>Our Location</legend>
          <div class=""> 
            <!--              <img src="https://opencart.dostguru.com/FD01/fruitino_04/image/cache/catalog/logo-268x50.png" alt="Fruitino Organic" title="Fruitino Organic" class="img-responsive" />
             --> 
            
            <!-- <strong>Fruitino Organic</strong> -->
            
            <div class="pull-left"><i class="fa fa-home"></i></div>
            <div class="contsp">Krushnanagar,balia, Balasore,<br />
              756001(Odisha)</div>
            <div class="pull-left"><i class="fa fa-phone"></i></div>
            <div class="contsp"> 7606095946</div>
            <div class="pull-left"><i class="fa fa-inbox"></i></div>
            <div class="contsp"> support@tatkaorders.com </div>
          </div>
        </div>
        <div class="col-md-8 col-xs-12">
        	<?php echo get_message(); ?>
          <form action="<?php echo generateForm("web","contactus",""); ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <fieldset>
              <legend>Contact Form</legend>
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="first_name">Your Name</label>
                <div class="col-sm-9">
                  <input type="text" name="first_name" value="" id="first_name" class="form-control" required />
                </div>
              </div>
               <div class="form-group required">
                <label class="col-sm-3 control-label" for="phone">Phone</label>
                <div class="col-sm-9">
                  <input type="text" name="phone" value="" id="phone" class="form-control" required/>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="email">E-Mail Address</label>
                <div class="col-sm-9">
                  <input type="email" name="email" value="" id="email" class="form-control" required/>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="message">Enquiry</label>
                <div class="col-sm-9">
                  <textarea name="message" rows="10" id="message" class="form-control"></textarea>
                </div>
              </div>
            </fieldset>
            <div class="buttons">
              <div class="pull-right">
              	<input type="hidden" name="subject" id="subject" value="Webiste Enquiry">
                <input class="btn btn-primary" type="submit" name="submit-contact" id="submit-contact" value="Submit" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
</body>
</html>
