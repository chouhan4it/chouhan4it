<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<body>
<div id="wrapper">
  <?php $this->load->view('layout/header'); ?>
  <section class="pagebg banner">
    <div class="page-header-top"></div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1><strong>Forgot Password</strong></h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo BASE_PATH; ?>" target="_blank">Home</a></li>
            <li class="active">Forgot Password</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section id="LoginForm">
    <div class="pagebefore"></div>
    <div class="container">
      <div class="login-form">
        <div class="main-div">
       
          <p>Please enter your email address or username</p>
          <?php get_message(); ?>
          <form action="<?php echo generateForm("account","forgotpassword",""); ?>" id="customer-form" name="customer-form" method="post" >
            <div class="form-group">
              <input type="text" class="form-control"  name="user_name" id="user_name" value="<?php echo $_REQUEST['user_name']; ?>" placeholder="Username or Email">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="captcha_code" id="captcha_code"  placeholder="Security Code">
            </div>
            <div class="form-group">
            	<div class="row">
                	<div class="col-md-6"><img class="img-responsive" src="<?php echo BASE_PATH."captcha/code"; ?>?sid=<?php echo md5(uniqid(time())); ?>" name="SecImg" id="SecImg" style="border-radius:5px; border:#AEAEAE 1px solid; width:100px;"> </div>
                    <div class="col-md-6"><a href="javascript:void(0)" onclick="document.getElementById('SecImg').src = '<?php echo BASE_PATH."captcha/code"; ?>?sid=' + Math.random(); return false" ><i class="fa fa-refresh"  >&nbsp;</i></a></div>
                </div>
             
             
            </div>
            <button type="submit" name="submit-forgot" id="submit-forgot" value="1" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </section>
  <?php $this->load->view('layout/footer'); ?>
</div>
<script src="<?php echo THEME_PATH; ?>js/jquery.min.js"></script> 
<script src="<?php echo THEME_PATH; ?>js/bootstrap.min.js"></script> 
<script>eval(mod_pagespeed_7QQog4axEa);</script> 
<script>eval(mod_pagespeed_J5NY_q70tQ);</script> 
<script>eval(mod_pagespeed_zVuu2aGFPL);</script> 
<script>eval(mod_pagespeed_oVQpHUoKeg);</script> 
<script src="<?php echo THEME_PATH; ?>js/flexslider.js"></script> 
<script>eval(mod_pagespeed_PjvXuOl1Ug);</script> 
<script>eval(mod_pagespeed_8TDZNHW_4k);</script> 
<script>eval(mod_pagespeed_RbsVnMPefx);</script>
</body>
</html>
