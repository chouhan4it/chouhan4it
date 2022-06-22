<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
#set_cookie(array("name"=>"home_set","value"=>"home page Change","expire"=>time()+86500));
#echo get_cookie('home_set');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title><?php echo WEBSITE; ?>-  Admin</title>
<meta name="description" content="User login page" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/fonts.googleapis.com.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" />
<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" />
		<![endif]-->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-rtl.min.css" />
<style type="text/css">
	span.title {
		display: block;
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-weight: 600;
		font-size: 34px;
		color: #fff;
		letter-spacing: 1px;
		padding-left: 15px;
	}
	div.login-container{
		margin:10% 10% 10% 35%;
	}
</style>
</head>
<body class="login-layout">
<div class="main-container">
  <div class="main-content">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="login-container">
          <div class="center">
		  	<img   src="<?php echo LOGO; ?>" class="img-responsive" 
            style="border-radius: 5px; padding: 6px;margin:0% auto;"> 
           </div>
          <div class="space-6"></div>
          <div class="position-relative">
            <div id="login-box" class="login-box visible widget-box no-border">
              <div class="widget-body">
                <div class="widget-main">
                  <h4 class="header blue lighter bigger"> <i class="ace-icon fa fa-coffee green"></i> Admin Login Panel </h4>
                  <div class="space-6"></div>
                  <form action="<?php echo  generateAdminForm("login","loginhandler",""); ?>" method="post" name="form-login" id="form-login">
                    <fieldset>
                    <label class="block clearfix"> <span class="block input-icon input-icon-right">
                    <input type="text" class="form-control" placeholder="Username" name="user_name" id="user_name" />
                    <i class="ace-icon fa fa-user"></i> </span> </label>
                    <label class="block clearfix"> <span class="block input-icon input-icon-right">
                    <input type="password" class="form-control" name="user_password" id="user_password" placeholder="Password" />
                    <i class="ace-icon fa fa-lock"></i> </span> </label>
                    <div class="space"></div>
                    <div class="clearfix">
                      <label class="inline">
                      <input type="checkbox" class="ace" />
                      <span class="lbl"> Remember Me</span> </label>
                      <button type="submit"  name="loginSubmit" value="1" class="width-35 pull-right btn btn-sm btn-primary"> <i class="ace-icon fa fa-key"></i> <span class="bigger-110">Login</span> </button>
                    </div>
                    <div class="space-4"></div>
                    </fieldset>
                  </form>
                  <div class="space-6"></div>
                </div>
               
              </div>
            </div>
            <?php get_message(); ?>
           
          </div>
         
        </div>
      </div>
    </div>
  </div>
</div>
<!--[if !IE]> -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<!-- <![endif]-->
<!--[if IE]>
	<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script type="text/javascript">
		if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo BASE_PATH; ?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			 
			});
		</script>
</body>
</html>
