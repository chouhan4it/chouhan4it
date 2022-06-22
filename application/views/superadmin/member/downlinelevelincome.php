<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = _d(FCrtRplc($_REQUEST['member_id']));
if($member_id!=""){
$first_date = InsertDate($model->getStartOrderDate());
$today_date = InsertDate(getLocalTime());

if($_REQUEST['rank_id']>0){
	$rank_id = FCrtRplc($_REQUEST['rank_id']);
	$StrWhr .=" AND tm.rank_id='".$rank_id."'";
	$SrchQ .="&rank_id=$rank_id";
}
if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);
	$SrchQ .="&from_date=$from_date&to_date=$to_date";
}

$Q_GEN =  "SELECT member_id, nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='$member_id'";
$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
$nlevel = $AR_GEN['nlevel'];
$nleft = $AR_GEN['nleft'];
$nright = $AR_GEN['nright'];

$order_by = "cadre";
$SrchQ .= "&member_id="._e($member_id)."&order_by=$order_by";

$QR_MEM = "SELECT tm.member_id, tm.user_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name,
		   CONCAT_WS(' ',tmsp.first_name,tmsp.last_name) AS spsr_full_name, tmsp.user_id AS spsr_user_id ,
		   tm.city_name, tm.state_name,
		   tree.nlevel, tree.nleft, tree.nright, tm.date_join, tr.rank_name
		   FROM tbl_members AS tm	
		   LEFT JOIN tbl_members AS tmsp  ON tmsp.member_id=tm.sponsor_id
		   LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
		   LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
		   WHERE tree.nleft BETWEEN '$nleft' AND '$nright' $StrWhr ORDER BY tm.user_id ASC";
$PageVal = DisplayPages($QR_MEM, 100, $Page, $SrchQ);
ExportQuery($QR_MEM,array("from_date"=>$from_date,"to_date"=>$to_date,"order_by"=>$order_by));
}
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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Group wise PV/BV  Collection </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-md-5">
            <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("member","downlinelevelincome",""); ?>">
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
                    <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" placeholder="Date From" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" placeholder="Date To" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
              </div>
              <div class="clearfix">&nbsp;</div>
              <div class="form-group">
                <div class="col-md-3"><strong>Rank</strong></div>
                <div class="col-md-9">
                  <div class="clearfix">
                    <select name="rank_id" id="rank_id" class="form-control validate[required]">
                      <option value="0">Select Rank</option>
                      <?php DisplayCombo($_REQUEST[rank_id], "RANK");?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="clearfix">&nbsp;</div>
              <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
              <a href="<?php echo generateSeoUrlAdmin("member","downlinelevelincome",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a> <a href="<?php echo generateSeoUrlAdmin("excel","downlinelevelincome",""); ?>"  class="btn btn-success m-t-n-xs" value=" Excel "> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
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
                <td align="left"><strong>SRL NO </strong></td>
                <td align="left"><strong>PROFILE</strong></td>
                <td align="left"><strong>User Name</strong></td>
                <td align="left"><strong>User Id </strong></td>
                <td  align="left"><strong>INTRODUCER</strong></td>
                <td align="left"><strong>CITY</strong></td>
                <td colspan="4"  align="center"><strong>STATICS</strong></td>
              </tr>
              <?php 
			if($PageVal['TotalRecords'] > 0){
			$CurrLvl = $PreLvl = 0;
			$Ctrl = $PageVal['RecordStart']+1;
			foreach($PageVal['ResultSet'] as $AR_DT):
			
			
			$AR_IMG = $model->getCurrentImg($AR_DT['member_id']);
			$AR_SELF = $model->getSumSelfCollection($AR_DT['member_id'],$from_date,$to_date);
			
			$AR_ALL = $model->getDirectMemberCollection($AR_DT['member_id'],$from_date,$to_date,$order_by);
			
			$net_total_pv = $AR_SELF['total_pv']+$AR_ALL['G_ALL_PV'];
			$net_total_bv = $AR_SELF['total_bv']+$AR_ALL['G_ALL_BV'];
			if($net_total_pv>0){
			?>
              <tr>
                <td   align="center" valign="top" class="cmntext"><?php echo $Ctrl;?></td>
                <td   valign="top" class="cmntext"><a class="thumbnail" href="#thumb"><img src="<?php echo $AR_IMG['IMG_SRC'];?>" width="23" height="23" /><span><img src="<?php echo $AR_IMG['IMG_SRC'];?>" /></span></a></td>
                <td align="left" valign="middle" class="<?php echo $AR_IMG[CssCls];?>"><?php echo strtoupper($AR_DT['full_name']);?><br>
                  <strong>Cadre:</strong> <?php echo $AR_DT['rank_name'];?></td>
                <td align="left" valign="middle" class="<?php echo $AR_IMG[CssCls];?>"><?php echo strtoupper($AR_DT['user_id']);?></td>
                <td align="left" valign="middle" class="cmntext"><?php echo ($AR_DT['spsr_user_id']);?></td>
                <td align="left" valign="middle" class="cmntext"><?php echo $AR_DT['city_name'];?></td>
                <td align="left" valign="middle" class="cmntext"><table width="100%" border="1" style="border-collapse:collapse;">
                    <tr>
                      <td align="center">SELF</td>
                    </tr>
                    <tr>
                      <td align="center">BV</td>
                    </tr>
                    <tr>
                      <td align="center"><?php echo OneDcmlPoint($AR_SELF['total_pv']); ?></td>
                    </tr>
                  </table></td>
                <td align="left" valign="middle" class="cmntext"><table width="100%" border="1" style="border-collapse:collapse;">
                    <tr>
                      <td align="center">G1 COUNT </td>
                      <td align="center">G2 COUNT </td>
                      <td align="center">G3 COUNT </td>
                      <td align="center">G4 COUNT </td>
                      <td align="center">G5 COUNT &amp; ABOVE </td>
                    </tr>
                    <tr>
                      <td align="center">BV</td>
                      <td align="center">BV</td>
                      <td align="center">BV</td>
                      <td align="center">BV</td>
                      <td align="center">BV</td>
                    </tr>
                    <tr>
                      <td align="center"><?php echo OneDcmlPoint($AR_ALL['G1_PV']); ?></td>
                      <td align="center"><?php echo OneDcmlPoint($AR_ALL['G2_PV']); ?></td>
                      <td align="center"><?php echo OneDcmlPoint($AR_ALL['G3_PV']); ?></td>
                      <td align="center"><?php echo OneDcmlPoint($AR_ALL['G4_PV']); ?></td>
                      <td align="center"><?php echo OneDcmlPoint($AR_ALL['G5_PV']); ?></td>
                    </tr>
                  </table></td>
                <td align="left" valign="middle" class="cmntext"><table width="100%" border="1" style="border-collapse:collapse;">
                    <tr>
                      <td align="center">TOTAL</td>
                    </tr>
                    <tr>
                      <td align="center">BV</td>
                    </tr>
                    <tr>
                      <td align="center"><?php echo OneDcmlPoint($net_total_pv); ?></td>
                    </tr>
                  </table></td>
                <td align="left" valign="middle" class="cmntext"><a class="modal-direct" member_id="<?php echo $AR_DT['member_id']; ?>" from_date="<?php echo $from_date; ?>" to_date="<?php echo $to_date; ?>" order_by="<?php echo $_REQUEST['order_by']; ?>" href="javascript:void(0)">View</a></td>
              </tr>
              <?php
				$Ctrl++;
			 }
			 endforeach; ?>
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
  <div class="modal" id="modal-direct-detail"  aria-hidden="true">
    <div class="modal-dialog" style="width:800px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title"> Collection</h4>
        </div>
        <div class="modal-body">
          <div class="login-box">
            <div id="row">
              <div class="input-box frontForms">
                <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <div class="load-direct"></div>
                  </div>
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
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		$(".modal-direct").on('click',function(){
			var member_id = $(this).attr("member_id");
			var from_date = $(this).attr("from_date");
			var to_date = $(this).attr("to_date");
			var order_by = $(this).attr("order_by");
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			var data = {
				switch_type:"DIRECT_COLLECTION",
				member_id : member_id,
				from_date : from_date,
				to_date : to_date,
				order_by : order_by
			}
			$.post(URL_GET,data,function(JsonEval){
				$(".load-direct").html(JsonEval);
				$("#modal-direct-detail").modal({
					backdrop: 'static',
					keyboard: false
				});
			});
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