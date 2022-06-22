<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}


if(_d($_REQUEST['member_id'])>0){
	$member_id = _d($_REQUEST['member_id']);
	$StrWhr .=" AND tmr.member_id='".$member_id."'";
	$SrchQ .="&member_id=$member_id";
}


$QR_PAGES = "SELECT tmr.*,  CONCAT_WS(' ',tm.first_name,tm.last_name) AS  full_name, 
		   tm.user_id, tr.rank_name, tr.rank_cmsn
		   FROM  tbl_mem_rank AS tmr	
		   LEFT JOIN  tbl_rank AS tr  ON tr.rank_id=tmr.to_rank_id
		   LEFT JOIN  tbl_members AS tm  ON tm.member_id=tmr.member_id
		   WHERE 1
		   $StrWhr 
		   ORDER BY tmr.from_rank_id ASC, tmr.date_time DESC";
$PageVal = DisplayPages($QR_PAGES, 200, $Page, $SrchQ);
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
.danger_alert {
	background-color: #f2dede;
	border-color: #ebccd1;
	color: #a94442;
}
.success_alert {
	background-color: #dff0d8;
	border-color: #d6e9c6;
	color: #3c763d;
}
.pointer {
	cursor: pointer;
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
          <h1> Bonus <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Rank & Reward </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-md-4">
            <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("bonus","awardreward",""); ?>">
              <b>User Id </b>
              <div class="form-group">
                <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id" style="width:200px;" />
                <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
              </div>
              <div class="clearfix">&nbsp;</div>
              <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
              <a href="<?php echo generateSeoUrlAdmin("bonus","awardreward",""); ?>" class="btn btn-danger m-t-n-xs" value=" Reset ">Back</a>
            </form>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12">
            <table id="no-more-tables" class="table table-striped table-bordered table-hover">
              <thead>
                <tr role="row">
                  <th>Sr. No </th>
                  <th >Date </th>
                  <th>User Id</th>
                  <th>Full Name</th>
                  <th>Rank</th>
                  <th>Reward </th>
                  <th>Pair Required</th>
                  <th>Pair Achived</th>
                </tr>
              </thead>
              <tbody>
                <?php 
						if($PageVal['TotalRecords'] > 0){
						$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
               			 ?>
                <tr>
                  <td><?php echo $Ctrl; ?></td>
                  <td><?php echo $AR_DT['date_time']; ?></td>
                  <td><?php echo $AR_DT['user_id']; ?></td>
                  <td><?php echo $AR_DT['full_name']; ?></td>
                  <td><?php echo $AR_DT['rank_name']; ?></td>
                  <td><?php echo $AR_DT['rank_cmsn']; ?></td>
                  <td><?php echo number_format($AR_DT['pair_set']); ?></td>
                  <td><?php echo number_format($AR_DT['pair_get']); ?></td>
                </tr>
                <?php $Ctrl++; 
							endforeach;
						}
						?>
              </tbody>
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
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</html>
