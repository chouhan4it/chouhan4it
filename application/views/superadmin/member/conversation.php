<?php defined('BASEPATH') OR exit('No direct script access allowed');
	$model = new OperationModel();
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$segment = $this->uri->uri_to_assoc(2);
	$enquiry_id = ($form_data['enquiry_id'])? $form_data['enquiry_id']:_d($segment['enquiry_id']);


	$member_id = $this->session->userdata('mem_id');
	
	$QR_TICK = "SELECT ts.* FROM tbl_support AS ts WHERE enquiry_id='".$enquiry_id."'";
	$AR_TICK = $this->SqlModel->runQuery($QR_TICK,true);
		
	$QR_PAGES="SELECT tsr.*, tm.first_name, tm.last_name FROM tbl_support_rply AS tsr LEFT JOIN tbl_members AS tm ON tsr.member_id=tm.member_id WHERE  
				tsr.enquiry_id='".$enquiry_id."' ORDER BY tsr.enquiry_id ASC";
	$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
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
<script type="text/javascript">
	$(function(){
		$(".open_modal").on('click',function(){
			$('#search-modal').modal('show');
			return false;
		});
		
	});
</script>
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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Conversation </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="widget-box">
              <div class="widget-body">
                <div class="widget-main no-padding">
                  <div class="dialogs">
                    <?php 
				if($PageVal['TotalRecords'] > 0){
				$Ctrl=1;
				foreach($PageVal['ResultSet'] as $AR_DT):
				?>
                    <div class="itemdiv dialogdiv">
                      <div class="user"> <img alt="Bob's Avatar" src="<?php echo BASE_PATH; ?>assets/img/default_profile.png" /> </div>
                      <div class="body">
                        <div class="time"> <i class="ace-icon fa fa-clock-o"></i> <span class="orange"><?php echo getDateFormat($AR_DT['reply_date'],"d M, Y h:i"); ?></span> </div>
                        <div class="name"> <a href="#"><?php echo ($AR_DT['first_name'])? $AR_DT['first_name']:"Admin"; ?></a>
                          <?php if($AR_DT['member_id']==0){ ?>
                          <span class="label label-info arrowed arrowed-in-right">admin</span>
                          <?php } ?>
                        </div>
                        <div class="text"><?php echo $AR_DT['enquiry_reply']; ?></div>
                        <div class="tools"> <a href="javascript:void(0)" class="btn btn-minier btn-info"> <i class="icon-only ace-icon fa fa-share"></i> </a> </div>
                      </div>
                    </div>
                    <?php endforeach;
				 }
				  ?>
                  </div>
                  <?php if($AR_TICK['enquiry_sts']!="C"){ ?>
                  <form action="<?php echo ADMIN_PATH; ?>member/conversation" method="post" name="form-chat" id="form-chat">
                    <div class="form-actions">
                      <div class="input-group">
                        <input placeholder="Type your message here ..." type="text" class="form-control validate[required]"  name="enquiry_reply" id="enquiry_reply" />
                        <input type="hidden" name="enquiry_id" id="enquiry_id"  value="<?php echo $enquiry_id; ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-sm btn-info no-radius" name="chatSubmit" value="1" type="submit"> <i class="ace-icon fa fa-share"></i> Send </button>
                        <button onClick="window.location.href='<?php echo generateSeoUrlAdmin("member","membersupport",""); ?>'" class="btn btn-sm btn-info no-radius" name="back" style="margin-left:6px;"  type="button"> <i class="fa fa-backward"></i> Back </button>
                        </span> </div>
                    </div>
                  </form>
                  <?php } ?>
                </div>
                <!-- /.widget-main --> 
              </div>
              <!-- /.widget-body --> 
            </div>
            <!-- /.widget-box --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
  <div id="advert-view" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="smaller lighter blue no-margin">Advrt Management</h3>
        </div>
        <div class="modal-body" id="load_content"> </div>
      </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>
  <div id="search-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="smaller lighter blue no-margin">Search</h3>
        </div>
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo ADMIN_PATH."member/membersupport"; ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Member Name  :</label>
              <div class="col-sm-7">
                <input id="form-field-1" placeholder="Member Name / Member ID" name="member_name"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['member_name']; ?>">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Date  :</label>
              <div class="col-sm-7">
                <div class="input-group">
                  <input class="form-control col-xs-6 col-sm-3  validate[required] date-picker" name="from_date" id="id-date-picker-1" value="<?php echo $ROW['from_date']; ?>" type="text"  placeholder="From Date" />
                  <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span> </div>
                <br>
                <div class="input-group">
                  <input class="form-control col-xs-6 col-sm-3  validate[required] date-picker" name="to_date" id="id-date-picker-1" value="<?php echo $ROW['to_date']; ?>" type="text" 	 placeholder="To Date"  />
                  <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span> </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
            <button type="button" class="btn btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
            <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"> <i class="ace-icon fa fa-times"></i> Close </button>
          </div>
        </form>
      </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$("#form-chat").validationEngine();
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		});
		
	});
</script>
</body>
</html>
