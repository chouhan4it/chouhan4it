<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if(_d($_REQUEST['member_id'])>0){
	$member_id = _d($_REQUEST['member_id']);
	$StrWhr .=" AND ord.member_id='".$member_id."'";
	$SrchQ .="&member_id=$member_id";
}


if($_REQUEST['pan_no']!=''){
	$pan_no = FCrtRplc($_REQUEST['pan_no']);
	$StrWhr .=" AND tm.pan_no='$pan_no'";
	$SrchQ .="&pan_no=$pan_no";
}



if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = FCrtRplc($_REQUEST['date_from']);
	$date_to = FCrtRplc($_REQUEST['date_to']);
	$StrWhr .=" AND ( DATE(tm.date_join) BETWEEN '".InsertDate($date_from)."' AND '".InsertDate($date_to)."')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}


$QR_PAGES = "SELECT tm.* 
			 FROM tbl_members AS tm
			 WHERE tm.delete_sts>0 AND tm.parent_id='0'
			 $StrWhr
			 GROUP BY tm.member_id 
			 ORDER BY tm.member_id  ASC";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
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
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<?php auto_complete(); ?>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
<![endif]-->
<style type="text/css">
tr > td {
	font-size: 11px;
}
</style>
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
          <h1> Report <small> <i class="ace-icon fa fa-angle-double-right"></i> SUB ID Report</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-4">
            <div class="clearfix">
              <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("report","subidreport",""); ?>">
                        <b>USER ID</b>
                        <div class="form-group">
                        <input name="user_id" type="text" class="form-control col-xs-12 col-sm-6" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id"  />
                        <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <b>PAN NO</b>
                        <div class="form-group">
                        <input name="pan_no" type="text" class="form-control" id="pan_no" value="<?php echo $_REQUEST['pan_no']; ?>" placeholder="PAN NO"  />
                        </div>
                        
                        
                <b>FROM DATE</b>
                        <div class="form-group">
                        <div class="input-group">
                        <input class="form-control col-xs-12 col-sm-6  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="FROM DATE" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                        </div>
                        <b>TO DATE</b>
                        <div class="form-group">
                        <div class="input-group">
                  <input class="form-control col-xs-12 col-sm-6  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                  <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                        
                        </div>
                     
                      
                      <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                      <a href="<?php echo generateSeoUrlAdmin("report","subidreport",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
                    </form>
            </div>
           
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr class="">
                <td align="left"><strong>SR NO</strong></td>
                <td align="center"><strong>D.O.J</strong></td>
                <td align="center"><strong>USER ID</strong></td>
                <td align="left"><strong>USER NAME</strong></td>
                <td align="center"><strong>PAN</strong></td>
                <td align="right"><strong>NO OF ID</strong></td>
                <td align="right"><strong>USER ID DETAILS</strong></td>
              </tr>
             
			   <?php 
                    if($PageVal['TotalRecords'] > 0){
                    $Ctrl = $PageVal['RecordStart']+1;
                    foreach($PageVal['ResultSet'] as $AR_DT):
					$sub_id_count = $model->checkCount("tbl_members","parent_id",$AR_DT['member_id']);
               ?>
              <tr>
                <td><?php echo $Ctrl; ?></td>
                <td align="center"><?php echo DisplayDate($AR_DT['date_join']); ?></td>
                <td align="center"><?php echo $AR_DT['user_id']; ?></td>
                <td align="left"><?php echo $AR_DT['full_name']; ?></td>
                <td align="left"><?php echo $AR_DT['pan_no']; ?></td>
                <td align="right"><?php echo number_format($sub_id_count); ?></td>
                <td align="right"><a class="label label-info modal-subid"    parent_id="<?php echo $AR_DT['member_id']; ?>"   href="javascript:void(0)">View</a></td>
              </tr>
              
              <?php 
			  $Ctrl++;
			  endforeach;
			
       			 }else{
       		  ?>
              <tr>
                <td colspan="9" align="center" class="errMsg">No record found</td>
              </tr>
              <?php } ?>
            </table>
            <ul class="pagination">
              <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
            </ul>
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
<div class="modal" id="modal-subid-detail"  aria-hidden="true">
    <div class="modal-dialog" style="width:800px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">SUB ID LIST</h4>
        </div>
        <div class="modal-body" >
          <div class="login-box" >
            <div id="row">
              <div class="input-box frontForms">
                <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <div class="load-subid"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
	});
	$(function(){
		$(".modal-subid").on('click',function(){
			var parent_id = $(this).attr("parent_id");
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			$.post(URL_GET,{switch_type:"SUBID_LIST",parent_id:parent_id},function(JsonEval){
				$(".load-subid").html(JsonEval);
				$("#modal-subid-detail").modal({
					backdrop: 'static',
					keyboard: false
				});
			});
			
		});
	});
</script>
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</body>
</html>