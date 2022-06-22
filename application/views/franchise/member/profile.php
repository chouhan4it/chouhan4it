<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title><?php echo title_name(); ?></title>
<meta name="description" content="Static &amp; Dynamic Tables" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/fonts.googleapis.com.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-skins.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-rtl.min.css" />
<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Profile</small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <div class="col-xs-12">
		 <?php get_message(); ?>
          <!-- PAGE CONTENT BEGINS -->
          <div class="clearfix">
            
            <div class="pull-right"> <span class="green middle bolder">Choose profile: &nbsp;</span>
              <div class="btn-toolbar inline middle no-margin">
                <div data-toggle="buttons" class="btn-group no-margin">
				<?php
					$prev_member = $model->checkPrevMember($ROW['member_id']);
					$next_member = $model->checkNextMember($ROW['member_id']);
				 ?>
				 <?php if($prev_member>0){ ?>
                  <label class="btn btn-sm btn-yellow" onClick="window.location.href='<?php echo  generateSeoUrlAdmin("member","profile",array("member_id"=>_e($prev_member))); ?>'"> <span class="bigger-110"> <i class="ace-icon fa fa-arrow-left icon-on-left"></i>&nbsp;Prev</span>
                  </label>
				  <?php } ?>
				  <?php if($next_member>0){ ?>
                  <label class="btn btn-sm btn-yellow" onClick="window.location.href='<?php echo  generateSeoUrlAdmin("member","profile",array("member_id"=>_e($next_member))); ?>'"> <span class="bigger-110">Next  <i class="ace-icon fa fa-arrow-right icon-on-right"></i></span>
                  </label>
                 <?php } ?>
                </div>
              </div>
            </div>
          </div>
          <div class="hr dotted"></div>
            <div id="user-profile-1" class="user-profile">
              <div class="tabbable">
                <ul class="nav nav-tabs padding-18">
                  <li class="active"> <a data-toggle="tab" href="#home"> <i class="green ace-icon fa fa-user bigger-120"></i> Profile </a> </li>
                  <li> <a data-toggle="tab" href="#password"> <i class="pink ace-icon fa fa-lock bigger-120"></i> Change Username </a> </li>
                </ul>
                <div class="tab-content no-border padding-24">
                  <div id="home" class="tab-pane in active">
                    <div class="row">
                      <div class="col-xs-12 col-sm-3 center">  <span class="profile-picture"> <img class="editable img-responsive" alt="<?php echo $ROW['first_name']; ?>" id="avatar2" src="<?php echo getMemberImage($ROW['member_id']); ?>"> </span>
                        <div class="space space-4"></div>
                       </div>
                      <!-- /.col -->
                      <div class="col-xs-12 col-sm-9">
                        <h4 class="blue"> <span class="middle"><?php echo $ROW['first_name']." ".$ROW['last_name']; ?></span> <span class="label label-purple arrowed-in-right"> <i class="ace-icon fa fa-circle smaller-80 align-middle"></i> <?php echo $ROW['title']; ?> </span> </h4>
                        <div class="profile-user-info">
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Username </div>
                            <div class="profile-info-value"> <span><?php echo $ROW['user_name']; ?></span> </div>
                          </div>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Country </div>
                            <div class="profile-info-value"> <i class="fa fa-map-marker light-orange bigger-110"></i> <span><?php echo $ROW['country_name']; ?></span> </div>
                          </div>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Email Address </div>
                            <div class="profile-info-value"> <span><?php echo $ROW['member_email']; ?></span> </div>
                          </div>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Joined </div>
                            <div class="profile-info-value"> <span><?php echo getDateFormat($ROW['date_join'],"Y/M/d"); ?></span> </div>
                          </div>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Last Login </div>
                            <div class="profile-info-value"> <span><?php echo getDateFormat($ROW['last_login'],"Y , M d h:i"); ?></span> </div>
                          </div>
                        </div>
                        <div class="hr hr-8 dotted"></div>
                        <div class="profile-user-info">
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Company  </div>
                            <div class="profile-info-value"> <a href="javascript:void(0)" target="_blank"><?php echo ($ROW['member_company'])? $ROW['member_company']:"N/A"; ?></a> </div>
                          </div>
                          
                          
                        </div>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="space-20"></div>
                   
                  </div>
                  
                  <!-- /#friends -->
                  <div id="password" class="tab-pane">
                     <h3 class="lighter block green">Change Username</h3>
                     
                      <form  name="form-valid" id="form-valid" method="post" class="form-horizontal" action="<?php echo  generateAdminForm("member","profile","");  ?>">
                        <!--<div class="form-group">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Old - Password : </label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                              
                            </div>
                          </div>
                        </div>-->
                        <div class="space-2"></div>
                        <div class="form-group">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">New - Username:</label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
							
                              <input name="user_name" id="user_name"  class="col-xs-12 col-sm-4 validate[required]" type="text" placeholder="new username" value=""> 
                            </div>
                          </div>
                        </div>
                       
						 
                        <div class="space-2"></div>
						<hr>
						<div class="wizard-actions">
						<input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id'];  ?>">
						<button name="submitMemberUsername" type="submit"  value="1" class="btn btn-success"> <i class="ace-icon fa fa-lock"></i> Update Username </button>
						</div>
                        </form>
                  </div>
                  <!-- /#pictures -->
                </div>
              </div>
            </div>

          
          <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.page-content -->
  </div>
</div>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
	});
</script>
</body>
</html>
