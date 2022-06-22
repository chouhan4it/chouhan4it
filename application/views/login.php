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
<div id="account-login" class="container acpage">
  <ul class="breadcrumb">
    <li><a href="<?php echo BASE_PATH; ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo generateSeoUrl("account","login",""); ?>">Account</a></li>
    <li><a href="<?php echo generateSeoUrl("account","login",""); ?>">Login</a></li>
  </ul>
  <div class="row">
    <div id="content" class="col-sm-8 col-md-9 col-xs-12 colright">
      <?php get_message(); ?>
      <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xs-12">
          <div class="well">
            <h1>New Customer</h1>
            <p><strong>Register Account</strong></p>
            <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
            <a href="<?php echo generateSeoUrl("account","register",""); ?>" class="btn btn-primary">Continue</a></div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xs-12">
          <div class="well">
            <h1>Returning Customer</h1>
            <p><strong>I am a returning customer</strong></p>
            <form action="<?php echo generateForm("account","loginhandler",""); ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label class="control-label" for="input-email">E-Mail Address</label>
                <input type="text" name="user_name_login" value="<?php echo $_REQUEST['user_name_login']; ?>" placeholder="Email Id / Username" id="user_name_login" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-password">Password</label>
                <input type="password" name="user_password_login" value="<?php echo $_REQUEST['user_password_login']; ?>"  placeholder="Password" id="user_password_login" class="form-control" />
                <div class="text-right"><a href="javascript:void(0)">Forgotten Password</a></div>
              </div>
              <input type="submit" value="Login"  name="submit-login" id="submit-login" class="btn btn-primary" />
            </form>
          </div>
        </div>
      </div>
    </div>
    <aside id="column-right" class="col-sm-4 col-md-3 col-xs-12 hidden-xs">
      <div class="list-group accolumn">
        <h3><svg class="" width="20px" height="20px">
          <use xlink:href="#acluser"></use>
          </svg>ACCOUNT SETTINGS</h3>
        <a href="<?php echo generateSeoUrl("account","login",""); ?>" class="list-group-item">Login</a> <a href="<?php echo generateSeoUrl("account","register",""); ?>" class="list-group-item">Register</a> <a href="javascript:void(0)" class="list-group-item">Forgotten Password</a> <a href="javascript:void(0)" class="list-group-item">My Account</a> </div>
    </aside>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
</body>
</html>
