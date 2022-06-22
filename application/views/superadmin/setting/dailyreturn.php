<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();

$date_time = InsertDate($_REQUEST['date_time']);
$today_date = InsertDate(getLocalTime());

$load_date = ($date_time)? $date_time:$today_date;

$QR_PAGES="SELECT tpy.* FROM tbl_pintype AS tpy WHERE tpy.isDelete>0 $StrWhr ORDER BY tpy.type_id ASC";
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
		$("#date_time").on('change',function(){
			var date_time = $(this).val();
			window.location.href='?date_time='+date_time;
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
          <h1> Setting <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Daily Return </small> </h1>
        </div>
        <form action="<?php  echo generateAdminForm("setting","dailyreturn","");  ?>" method="post" name="form-valid" id="form-valid">
          <div class="row">
            <?php get_message(); ?>
            <div class="col-xs-12">
              <div class="clearfix">
                <div class="tableTools-container">
                  <div class="dt-buttons btn-overlap btn-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date :</label>
                    <div class="col-sm-9">
                      <select name="date_time" id="date_time"  class="col-xs-12 col-sm-12 validate[required]" style="width:500px;">
                        <?php echo DisplayCombo($load_date,"DATE_TIME"); ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <div>
                    <table  class="table  table-bordered table-hover">
                      <tr>
                        <th width="197">Plan  Name</th>
                        <th width="163">Daily Return </th>
                      </tr>
                      <tbody>
                        <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						$daily_return = $model->getDailyReturn($AR_DT['type_id'],$load_date);
			       ?>
                        <tr>
                          <td><?php echo $AR_DT['pin_name']; ?> &nbsp;[<?php echo $AR_DT['pin_letter']; ?>] </td>
                          <td><input type="hidden" name="type_id[]" id="type_id" value="<?php echo $AR_DT['type_id']; ?>">
                            <input class="validate[required]" type="text" name="daily_return[]" id="daily_return" placeholder="In %" value="<?php echo $daily_return; ?>"></td>
                        </tr>
                        <?php $Ctrl++; endforeach; }else{ ?>
                        <tr>
                          <td colspan="2" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-xs-6">
                        <div id="dynamic-table_paginate" class="dataTables_paginate paging_simple_numbers">
                          <button type="submit" name="submitDailyReturnForm" value="1" class="btn btn-success"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                          <button  class="btn btn-danger" type="button" onClick="window.location.href='<?php echo generateSeoUrlAdmin("epin","dailyreturn",array("")); ?>'"> <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel </button>
                          <button  class="btn btn-info" type="reset"> <i class="ace-icon fa fa-refresh bigger-110"></i> Reset </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
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
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		});
		
	});
</script>
</body>
</html>
