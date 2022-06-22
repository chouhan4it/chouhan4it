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
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
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
                    <label class="btn btn-sm btn-yellow" onClick="window.location.href='<?php echo  generateSeoUrlAdmin("member","profile",array("member_id"=>_e($prev_member))); ?>'"> <span class="bigger-110"> <i class="ace-icon fa fa-arrow-left icon-on-left"></i>&nbsp;Prev</span> </label>
                    <?php } ?>
                    <?php if($next_member>0){ ?>
                    <label class="btn btn-sm btn-yellow" onClick="window.location.href='<?php echo  generateSeoUrlAdmin("member","profile",array("member_id"=>_e($next_member))); ?>'"> <span class="bigger-110">Next <i class="ace-icon fa fa-arrow-right icon-on-right"></i></span> </label>
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
                  <!--
                  <li> <a data-toggle="tab" href="#feed"> <i class="orange ace-icon fa fa-user-secret bigger-120"></i> Direct Sponsor</a> </li>
                  <li> <a data-toggle="tab" href="#friends"> <i class="blue ace-icon fa fa-users bigger-120"></i> Downline Member </a> </li>
                  -->
                  <li> <a data-toggle="tab" href="#uplines"> <i class="orange ace-icon fa fa-users bigger-120"></i> Line of Generation </a> </li>
                  <li> <a data-toggle="tab" href="#pictures"> <i class="pink ace-icon fa fa-lock bigger-120"></i> Change Password </a> </li>
                </ul>
                <div class="tab-content no-border padding-24">
                  <div id="home" class="tab-pane in active">
                    <div class="row">
                      <div class="col-xs-12 col-sm-3 center"> <span class="profile-picture"> <img class="editable img-responsive" alt="<?php echo $ROW['first_name']; ?>" id="avatar2" src="<?php echo getMemberImage($ROW['member_id']); ?>"> </span>
                        <div class="space space-4"></div>
                      </div>
                      <!-- /.col -->
                      <div class="col-xs-12 col-sm-9">
                        <h4 class="blue"> <span class="middle"><?php echo $ROW['first_name']." ".$ROW['last_name']; ?></span> <span class="label label-purple arrowed-in-right"> <i class="ace-icon fa fa-circle smaller-80 align-middle"></i> <?php echo $ROW['user_id']; ?> </span> </h4>
                        <div class="profile-user-info">
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Username </div>
                            <div class="profile-info-value"> <span><?php echo $ROW['user_name']; ?></span> </div>
                          </div>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Location </div>
                            <div class="profile-info-value"> <i class="fa fa-map-marker light-orange bigger-110"></i> <span><?php echo $ROW['current_address']; ?></span> <span><?php echo $ROW['city_name']; ?></span> </div>
                          </div>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Email Address </div>
                            <div class="profile-info-value"> <span><?php echo $ROW['member_email']; ?></span> </div>
                          </div>
                          <?php if($ROW['pan_no']!=''){ ?>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Pan Card </div>
                            <div class="profile-info-value"> <span><?php echo $ROW['pan_no']; ?></span> </div>
                          </div>
                          <?php } ?>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Date of Join </div>
                            <div class="profile-info-value"> <span><?php echo getDateFormat($ROW['date_join'],"Y/M/d"); ?></span> </div>
                          </div>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Last Online </div>
                            <div class="profile-info-value"> <span><?php echo getDateFormat($ROW['last_login'],"Y , M d h:i"); ?></span> </div>
                          </div>
                        </div>
                        <div class="hr hr-8 dotted"></div>
                        <div class="profile-user-info">
                          <div class="profile-info-row">
                            <div class="profile-info-name"> Sponsor </div>
                            <div class="profile-info-value"> <a href="javascript:void(0)" target="_blank"><?php echo ($ROW['spsr_first_name'])? $ROW['spsr_first_name']." ".$ROW['spsr_last_name']:"N/A"; ?></a> </div>
                          </div>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> <i class="middle ace-icon fa fa-user-secret bigger-150 blue"></i> </div>
                            <div class="profile-info-value"> <a href="javascript:void(0)"><?php echo ($ROW['spsr_user_id'])? $ROW['spsr_user_id']:"N/A"; ?></a> </div>
                          </div>
                          <div class="profile-info-row">
                            <div class="profile-info-name"> <i class="middle ace-icon fa fa-arrows bigger-150 blue"></i> </div>
                            <div class="profile-info-value"> <a href="javascript:void(0)"><?php echo DisplayText("MEM_".$ROW['left_right']); ?></a> </div>
                          </div>
                        </div>
                      </div>
                      <!-- /.col --> 
                    </div>
                    <!-- /.row -->
                    <div class="space-20"></div>
                  </div>
                  <!-- /#home -->
                  
                  <div id="uplines" class="tab-pane">
                    <div class="profile-feed row">
                      <?php 
					$QR_UPLINES = "SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,
					tree.nlevel, tree.left_right, tree.nleft, tree.nright, tree.date_join, tr.rank_name 
					FROM tbl_members AS tm	
					LEFT JOIN tbl_members AS tmsp  ON tmsp.member_id=tm.sponsor_id
					LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
					WHERE   tree.nleft BETWEEN '$ROW[nleft]' AND '$ROW[nright]' $StrWhr ORDER BY tree.nlevel ASC";
					$RS_UPLINES = $this->SqlModel->runQuery($QR_UPLINES);
					?>
                      <div class="col-sm-6">
                        <?php 
							$i=1;
							foreach($RS_UPLINES as $AR_DT):
							
						?>
                        <div class="profile-activity clearfix">
                          <div><img class="pull-left"  src="<?php echo BASE_PATH; ?>assets/images/avatars/avatar.png"> <a class="user" href="<?php echo  generateSeoUrlAdmin("member","profile",array("member_id"=>$AR_DT['member_id'])); ?>"> [<?php echo $AR_DT['user_id'];?>] <?php echo strtoupper($AR_DT['first_name']." ".$AR_DT['last_name']); ?></a>
                            <div><?php echo $AR_DT['city_name'].", ".$AR_DT['state_name'].' | Mobile: '.$AR_DT['member_mobile']; ?></div>
                            <div><?php echo $AR_DT['rank_name']; ?></div>
                          </div>
                        </div>
                        <?php /*if($i>=$fldiCount){ echo '</div><div class="col-sm-6">'; $i=0; }*/ $i++; endforeach; ?>
                      </div>
                      
                      <!-- /.col --> 
                    </div>
                    <!-- /.row --> 
                  </div>
                  <!-- /#uplines -->
                  
                  <?php /*
                  <div id="feed" class="tab-pane">
                    <div class="profile-feed row">
					<?php 
						$QR_DIRECT =  "SELECT tm.* FROM tbl_members AS tm WHERE tm.sponsor_id='".$ROW['member_id']."'";
						$RS_DIRECT = $this->SqlModel->getQuery($QR_DIRECT);
						$AR_ROW = $RS_DIRECT->result_array();
						$fldiCount = count($AR_ROW)/2;
					?>
                      <div class="col-sm-6">
					  	<?php 
							$i=1;
							foreach($AR_ROW as $AR_DT): 
						?>
	                        <div class="profile-activity clearfix">
                          <div> <img class="pull-left"  src="<?php echo BASE_PATH; ?>assets/images/avatars/avatar.png"> <a class="user" href="<?php echo  generateSeoUrlAdmin("agent","profile",array("member_id"=>$AR_DT['member_id'])); ?>"> 
						  <?php echo $AR_DT['first_name']." ".$AR_DT['last_name']; ?> </a> <?php echo $AR_DT['current_address']." ".$AR_DT['city_name']; ?>
						   <a href="javascript:void(0)"><?php echo $AR_DT['state_name']." ".$AR_DT['country_name']; ?></a>
                            <div class="time"> <i class="ace-icon fa fa-clock-o bigger-110"></i> <?php echo getDateFormat($AR_DT['last_login'],"Y , M d h:i"); ?> </div>
                          </div>
                          
                        </div>
						<?php if($i>=$fldiCount){ echo '</div><div class="col-sm-6">'; $i=0; } $i++; endforeach; ?>
                        
                      </div>
                     
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="space-12"></div>
                    <div class="center">
                      <button onClick="window.location.href='<?php echo  generateSeoUrlAdmin("member","direct",array()); ?>'" type="button" class="btn btn-sm btn-primary btn-white btn-round"> <i class="ace-icon fa fa-list bigger-150 middle orange2"></i> <span class="bigger-110">View All Sponsor</span> <i class="icon-on-right ace-icon fa fa-arrow-right"></i> </button>
                    </div>
                  </div>
                  <!-- /#feed -->
				  */ ?>
                  <?php /*
                  <div id="friends" class="tab-pane">
                    <div class="profile-users clearfix">
					<?php 
						$QR_DOWN =  "SELECT tm.* FROM tbl_members AS tm
						 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
						 WHERE tree.nleft BETWEEN '".$ROW['nleft']."' AND '".$ROW['nright']."'";
						$RS_DOWN = $this->SqlModel->getQuery($QR_DOWN);
						$AR_DOWN = $RS_DOWN->result_array();
						foreach($AR_DOWN as $AR_DT):
					 ?>
                      <div class="itemdiv memberdiv">
                        <div class="inline pos-rel">
                          <div class="user"> <a href="<?php echo  generateSeoUrlAdmin("member","profile",array("member_id"=>$AR_DT['member_id'])); ?>"> <img src="<?php echo getMemberImage($AR_DT['member_id']); ?>" alt="<?php echo $AR_DT['first_name']." ".$AR_DT['last_name']; ?>"> </a> </div>
                          <div class="body">
                            <div class="name"> <a href="<?php echo  generateSeoUrlAdmin("member","profile",array("member_id"=>$AR_DT['member_id'])); ?>"> <span class="user-status status-online"></span> <?php echo $AR_DT['first_name']." ".$AR_DT['last_name']; ?> </a> </div>
                          </div>
                          
                        </div>
                      </div>
                     <?php  endforeach; ?>
                    </div>
					<div class="space-12"></div>
                    <div class="center">
                      <button onClick="window.location.href='<?php echo  generateSeoUrlAdmin("member","level",array()); ?>'" type="button" class="btn btn-sm btn-primary btn-white btn-round"> <i class="ace-icon fa fa-list bigger-150 middle orange2"></i> <span class="bigger-110">View All Downline</span> <i class="icon-on-right ace-icon fa fa-arrow-right"></i> </button>
                    </div>
                    <!--<div class="hr hr10 hr-double"></div>
                    <ul class="pager pull-right">
                      <li class="previous disabled"> <a href="#">← Prev</a> </li>
                      <li class="next"> <a href="#">Next →</a> </li>
                    </ul>-->
                  </div>
                  <!-- /#friends -->
				  */ ?>
                  <div id="pictures" class="tab-pane">
                    <h3 class="lighter block green">Change Password</h3>
                    <form  name="form-valid" id="form-valid" method="post" class="form-horizontal" action="<?php echo generateAdminForm("member","profile",""); ?>">
                      <!--<div class="form-group">
                          <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Old - Password : </label>
                          <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                              
                            </div>
                          </div>
                        </div>-->
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">New - Password:</label>
                        <div class="col-xs-12 col-sm-9">
                          <div class="clearfix">
                            <input name="old_password" id="old_password" class="col-xs-12 col-sm-4 validate[required]" type="hidden" placeholder="current password" 
							 value="<?php echo $ROW['user_password']; ?>">
                            <input name="user_password" id="user_password"  class="col-xs-12 col-sm-4 validate[required,minSize[6]]" type="password" placeholder="new password" value="">
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Confirm - Password:</label>
                        <div class="col-xs-12 col-sm-9">
                          <div class="clearfix">
                            <input name="confirm_user_password" id="confirm_user_password"  class="col-xs-12 col-sm-4 validate[required,equals[user_password]]" type="password" placeholder="confirm password" value="">
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <hr>
                      <div class="wizard-actions">
                        <input type="hidden" name="member_id" id="member_id" value="<?php echo $ROW['member_id'];  ?>">
                        <button name="submitMemberSavePassword" type="submit"  value="1" class="btn btn-success"> <i class="ace-icon fa fa-lock"></i> Update Password </button>
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
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
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
