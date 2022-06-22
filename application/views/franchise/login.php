<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Seller Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?php echo BASE_PATH; ?>f-assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="<?php echo BASE_PATH; ?>f-assets/css/styles.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-bg">
  	
	<div class="clearfix">&nbsp;</div>
	<div class="clearfix">&nbsp;</div>
	<div class="page-content container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-wrapper">
			        <div class="box">
					<form action="<?php echo generateFranchiseForm("login","loginhandler",""); ?>" method="post" name="form-login" id="form-login">
			            <div class="content-wrap">
							<a href="<?php echo BASE_PATH; ?>"><img src="<?php echo LOGO; ?>" style="margin:0 auto;" width="250" class="img-responsive"></a>
							
							<div class="clearfix">&nbsp;</div>
							<div class="clearfix">&nbsp;</div>
			                <h6>Welcome to Seller Login Panel</h6>
			               
			                <input class="form-control" placeholder="Branch Login Id" name="user_name" id="user_name" type="text">
			                <input type="password" class="form-control" name="user_password" id="user_password" placeholder="Password" />
			                <div class="action">
								<button type="submit"  name="loginSubmit" value="1" class="btn btn-primary signup">
									<i class="ace-icon fa fa-key"></i>
									<span class="bigger-110">Login</span>
								</button>
			                </div>                
			            </div>
					</form>
			        </div>
					<div class="already">
			           <?php get_message(); ?>
			        </div>
					
			    </div>
			</div>
		</div>
	</div>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
  </body>
</html>