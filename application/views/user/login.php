<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
</head>
<body>
<div class="animationload">
  <div class="loader"></div>
</div>
<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
  <div class=" card-box">
    <div class="panel-heading">
	
      <h3 class="text-center"> Member Panel <strong class="text-custom"><?php echo ucfirst(strtolower(WEBSITE)); ?></strong>  </h3>
    </div>
	
    <div class="panel-body">
		
      <form class="form-horizontal m-t-20" action="<?php echo generateMemberForm("login","loginhandler",""); ?>" method="post">
        <div class="form-group ">
          <div class="col-xs-12">
            <input class="form-control" type="text" name="user_name" id="user_name" required="" placeholder="Username">
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12">
            <input class="form-control" type="password" name="user_password" id="user_password" required="" placeholder="Password">
          </div>
        </div>
        <div class="form-group ">
          <div class="col-xs-12">
            <div class="checkbox checkbox-primary">
              <input id="checkbox-signup" type="checkbox">
              <label for="checkbox-signup"> Remember me </label>
            </div>
          </div>
        </div>
        <div class="form-group text-center m-t-40">
          <div class="col-xs-12">
            <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" name="loginSubmit" value="1" type="submit">Log In</button>
          </div>
        </div>
       
      </form>
    </div>
  </div>
  
</div>
</body>
</html>
