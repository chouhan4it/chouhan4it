<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = _d(FCrtRplc($_REQUEST['member_id']));
if($member_id == ""){$member_id="0";}

if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);
	#$StrWhr .=" AND DATE(tm.date_join) BETWEEN '".$from_date."' AND '".$to_date."'";
	$SrchQ .="&from_date=$from_date&to_date=$to_date";
}

if($_REQUEST['rank_id']>0){
	$rank_id = FCrtRplc($_REQUEST['rank_id']);
	$StrWhr .=" AND tm.rank_id='".$rank_id."'";
	$SrchQ .="&rank_id=$rank_id";
}

$Q_GEN =  "SELECT member_id, nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='$member_id'";
$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
$nlevel = $AR_GEN['nlevel'];
$nleft = $AR_GEN['nleft'];
$nright = $AR_GEN['nright'];

$SrchQ .= "&member_id=$member_id";

$QR_MEM = "SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,
			 tree.nlevel, tree.left_right, tree.nleft, tree.nright, tree.date_join, tr.rank_name FROM tbl_members AS tm	
			 LEFT JOIN tbl_members AS tmsp  ON tmsp.member_id=tm.sponsor_id
			 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
			 LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
			 WHERE tm.sponsor_id='".$AR_GEN['member_id']."' AND  tree.nleft BETWEEN '$nleft' AND '$nright' $StrWhr ORDER BY tree.nlevel ASC";
$PageVal = DisplayPages($QR_MEM, 200, $Page, $SrchQ);
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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Downline Income </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-md-4">
            <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("member","downlinebonus",""); ?>">
              <b>User Id </b>
              <div class="form-group">
                <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id" style="width:200px;" />
                <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
              </div>
              <b>Date from </b>
              <div class="input-group">
                <input class="form-control  validate[required] date-picker" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
              <b>To</b>
              <div class="input-group">
                <input class="form-control  validate[required] date-picker" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
              <div class="clearfix">&nbsp;</div>
              <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
              <a href="javascript:void(0)" onClick="window.history.back()" class="btn btn-danger m-t-n-xs" value=" Reset ">Back</a>
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
                <td colspan="2" align="left"><strong>Level</strong></td>
                <td width="11%" align="left"><strong>User Id </strong></td>
                <td width="12%" align="center"><strong>Introducer </strong></td>
                <td width="7%" align="left"><strong>Rank</strong></td>
                <td width="8%" align="left"><strong>Register Date</strong></td>
                <td width="8%" align="left"><strong>City</strong></td>
                <td width="7%" align="left"><strong>State</strong></td>
                <td width="7%" align="left"><strong>Self </strong></td>
                <td width="7%" align="left"><strong>Team</strong></td>
              </tr>
              <?php 
			if($PageVal['TotalRecords'] > 0){
			$CurrLvl = $PreLvl = 0;
			$Ctrl = $PageVal['RecordStart'];
			foreach($PageVal['ResultSet'] as $AR_Fld){
			$Ctrl++;
			
			$AR_IMG = $model->getCurrentImg($AR_Fld['member_id']);
			
			$AR_SELF = $model->getSumSelfCollection($AR_Fld['member_id'],$from_date,$to_date);
			
			$AR_GROUP = $model->getSumGroupCollection($AR_Fld['member_id'],$AR_Fld['nleft'],$AR_Fld['nright'],$from_date,$to_date);
			
			$CurrLvl = $AR_Fld['nlevel'];
				if(($CurrLvl != $PreLvl)){
					$LvlCtrl = $CurrLvl - $fldiLevel;
			?>
              <tr bgcolor="#B8E6FE">
                <td colspan="10" align="left" valign="middle" class="cmntext"><strong>Level <?php echo $LvlCtrl;?></strong></td>
              </tr>
              <?php 
			$PreLvl = $AR_Fld['nlevel'];
			}
?>
              <tr >
                <td width="10%" height="20" align="center" valign="top" class="cmntext"><?php echo $Ctrl;?></td>
                <td width="15%"  valign="top" class="cmntext"><img src="<?php echo $AR_IMG['IMG_SRC'];?>" width="23" height="23" /><?php echo $AR_Fld['first_name']." ".$AR_Fld['last_name'];?></td>
                <td align="left" valign="middle" class="<?php echo $AR_IMG[CssCls];?>"><?php echo strtoupper($AR_Fld['user_id']);?></td>
                <td align="center" valign="middle" class="cmntext"><?php echo $AR_Fld['spsr_user_id'];?></td>
                <td align="left" valign="middle" class="cmntext"><?php echo ($AR_Fld['rank_name'])? $AR_Fld['rank_name']:"Registered Consumer"; ?></td>
                <td align="left" valign="middle" class="cmntext"><?php echo DisplayDate($AR_Fld['date_join']);?></td>
                <td align="left" valign="middle" class="cmntext"><?php echo $AR_Fld['city_name'];?></td>
                <td align="left" valign="middle" class="cmntext"><?php echo $AR_Fld['state_name'];?></td>
                <td align="left" valign="middle" class="cmntext"><table width="100%" border="0">
                    <tr>
                      <td>BV : </td>
                      <td><?php echo number_format($AR_SELF['total_pv']); ?></td>
                    </tr>
                  </table></td>
                <td align="left" valign="middle" class="cmntext"><table width="100%" border="0">
                    <tr>
                      <td>BV : </td>
                      <td><?php echo number_format($AR_GROUP['total_pv']); ?></td>
                    </tr>
                  </table></td>
              </tr>
              <?php }?>
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
