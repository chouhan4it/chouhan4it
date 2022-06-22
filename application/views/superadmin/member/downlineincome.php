<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = _d(FCrtRplc($_REQUEST['member_id']));
if($member_id == ""){$member_id="1";}
$first_date = InsertDate($model->getStartOrderDate());
$today_date = InsertDate(getLocalTime());

if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND DATE(tmp.date_time) BETWEEN '".$date_from."' AND '".$date_to."'";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$Q_GEN =  "SELECT member_id, nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='$member_id'";
$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
$nlevel = $AR_GEN['nlevel'];
$nleft = $AR_GEN['nleft'];
$nright = $AR_GEN['nright'];

$SrchQ .= "&member_id="._e($member_id)."";

$QR_MEM = "SELECT tmp.*, tm.user_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name,
   		   tm.city_name, tm.state_name,  tm.date_join,
		   CONCAT_WS(' ',tmsp.first_name,tmsp.last_name) AS spsr_full_name, tmsp.user_id AS spsr_user_id,
		   tree.nlevel, tree.nleft, tree.nright
		   FROM tbl_mem_point AS tmp
		   LEFT JOIN tbl_members AS tm ON tm.member_id=tmp.member_id
		   LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tmp.member_id
		   LEFT JOIN tbl_members AS tmsp  ON tmsp.member_id=tree.sponsor_id
		   WHERE tree.nleft BETWEEN '$nleft' AND '$nright' 
		   AND tmp.point_sub_type IN('PKG')
		   $StrWhr 
		   ORDER BY tmp.point_id ASC";
$PageVal = DisplayPages($QR_MEM, 500, $Page, $SrchQ);
ExportQuery($QR_MEM,array("d_month"=>$d_month,"d_year"=>$d_year));
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
<script src="<?php echo BASE_PATH; ?>assets/javascript/genvalidator.js"></script>
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
tr > td {
	font-size: 10px;
}
</style>
<style type="text/css">
.thumbnail {
	position: relative;
	z-index: 0;/*width:25px;
height:25px;*/
}
.thumbnail:hover {
	background-color: transparent;
	z-index: 50;
}
.thumbnail span { /*CSS for enlarged image*/
	position: absolute;
	background-color: lightyellow;
	padding: 5px;
	left: -1000px;
	border: 1px dashed gray;
	visibility: hidden;
	color: black;
	text-decoration: none;
}
.thumbnail span img { /*CSS for enlarged image*/
	border-width: 0;
	padding: 2px;
	width: 300px;
	height: 300px;
}
.thumbnail:hover span { /*CSS for enlarged image on hover*/
	visibility: visible;
	top: 0;
	left: 60px; /*position where enlarged image should offset horizontally */
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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Downline Income </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-md-5">
            <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("member","downlineincome",""); ?>">
              <div class="form-group">
                <div class="col-md-3"><strong>User Id</strong></div>
                <div class="col-md-9">
                  <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id" style="width:200px;" />
                  <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                </div>
              </div>
              <div class="clearfix">&nbsp;</div>
              <div class="form-group">
                <div class="col-md-3"><strong>Date Between</strong></div>
                <div class="col-md-5">
                  <div class="input-group">
                    <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="Date From" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
              </div>
              <div class="clearfix">&nbsp;</div>
              <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
              <a href="<?php echo generateSeoUrlAdmin("member","downlineincome",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a> <a href="<?php echo generateSeoUrlAdmin("excel","downlineincome",""); ?>"  class="btn btn-success m-t-n-xs" value=" Excel "> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
            </form>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr class="">
                <td ><strong>Srl NO</strong></td>
                <td ><strong>User Id </strong></td>
                <td ><strong>Full Name</strong></td>
                <td ><strong>Sponsor Id</strong></td>
                <td ><strong>Date</strong></td>
                <td ><strong>Detail</strong></td>
                <td ><strong>Type</strong></td>
                <td ><strong>Net Amount</strong></td>
                <td ><strong>Total Amount</strong></td>
                <td ><strong>BV</strong></td>
              </tr>
              <?php 
			if($PageVal['TotalRecords'] > 0){
			$Ctrl = $PageVal['RecordStart']+1;
			foreach($PageVal['ResultSet'] as $AR_DT){
				$point_sub_type = $AR_DT['point_sub_type'];
				$sum_point_rcp +=$AR_DT['point_rcp'];
				$sum_point_vol +=$AR_DT['point_vol'];
				$sum_point_pv +=$AR_DT['point_pv'];
				
				if($point_sub_type=="PKG"){
					$AR_ORDER = $model->getSubscriptionByOrder($AR_DT['point_ref']);
					$pin_detail = "[<small>".$AR_ORDER['pin_key']."</small>]";
				}
			?>
              <tr >
                <td><?php echo $Ctrl;?></td>
                <td><?php echo $AR_DT['user_id'];?></td>
                <td><?php echo $AR_DT['full_name'];?></td>
                <td><?php echo $AR_DT['spsr_user_id'];?></td>
                <td><?php echo DisplayDate($AR_DT['date_time']);?></td>
                <td><?php echo getTool($AR_ORDER['pin_name'],"N/A")." ".$pin_detail;?></td>
                <td><?php echo $AR_DT['point_sub_type'];?></td>
                <td><?php echo number_format($AR_DT['point_vol'],2);?></td>
                <td><?php echo number_format($AR_DT['point_rcp'],2);?></td>
                <td><?php echo number_format($AR_DT['point_pv'],2);?></td>
              </tr>
              <?php $Ctrl++; }?>
              <tr >
                <td colspan="7" align="right"><strong>Total :</strong></td>
                <td><strong><?php echo number_format($sum_point_vol,2);?></strong></td>
                <td><strong><?php echo number_format($sum_point_rcp,2);?></strong></td>
                <td><strong><?php echo number_format($sum_point_pv,2);?></strong></td>
              </tr>
              <?php }else{?>
              <tr>
                <td colspan="10" align="center" class="errMsg">No record found</td>
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
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
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
