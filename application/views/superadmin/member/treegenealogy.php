<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();

$member_id = _d(FCrtRplc($_REQUEST['member_id']));
$user_id = FCrtRplc($_REQUEST['user_id']);
if($member_id == ""){$member_id="1";}

$AR_MEM = $model->getMember($member_id);

$QR_TREE = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$member_id' AND (nleft>0 AND nright>0)";
$RS_TREE = $this->SqlModel->runQuery($QR_TREE);
foreach($RS_TREE as $AR_TREE):
	if($AR_TREE['left_right'] == "L"){
		$Left_Lft = $AR_TREE['nleft'];
		$Left_Rgt = $AR_TREE['nright'];
	}
	if($AR_TREE['left_right'] == "R"){
		$Right_Lft = $AR_TREE['nleft'];
		$Right_Rgt = $AR_TREE['nright'];
	}
endforeach;

if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);
	$StrWhr .=" AND DATE(ts.date_from) BETWEEN '$from_date' AND '$to_date'";
	$SrchQ .="&from_date=$from_date&to_date=$to_date";
}

$Q_LeftMem = "SELECT ts.* ,  tree.nlevel, tree.left_right, tree.nleft, tree.nright, tree.date_join,
			  tm.first_name, tm.last_name, tm.user_id
			  FROM tbl_subscription AS ts 
			  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=ts.member_id
			  LEFT JOIN tbl_members AS tm ON tm.member_id=ts.member_id
			  WHERE tree.nleft BETWEEN '$Left_Lft' AND '$Left_Rgt'
			  $StrWhr
			  ORDER BY tree.nlevel ASC";
$RS_LftMem = $this->SqlModel->runQuery($Q_LeftMem);


$Q_RightMem = "SELECT ts.* ,  tree.nlevel, tree.left_right, tree.nleft, tree.nright, tree.date_join,
			  tm.first_name, tm.last_name, tm.user_id
			  FROM tbl_subscription AS ts 
			  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=ts.member_id
			  LEFT JOIN tbl_members AS tm ON tm.member_id=ts.member_id
			  WHERE tree.nleft BETWEEN '$Right_Lft' AND '$Right_Rgt' $StrWhr 
			  $StrWhr
			  ORDER BY tree.nlevel ASC";
$RS_RgtMem = $this->SqlModel->runQuery($Q_RightMem);	


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
</style>
</head>
<body class="no-skin">
<?= $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?= $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Genealogy </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-md-3">
            <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("member","treegenealogy",array()); ?>">
              <b>User Id </b>
              <div class="">
                <input id="form-field-1" placeholder="User Id" name="user_id"  class="col-xs-12 col-sm-12" type="text" value="<?php echo $_REQUEST['user_id']; ?>">
                <input type="hidden" name="member_id" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>">
              </div>
              <b>Date from </b>
              <div class="input-group">
                <input class="form-control validate[required] date-picker col-md-3" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
              <b>To</b>
              <div class="input-group">
                <input class="form-control  validate[required] date-picker" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span> </div>
              <br>
              <input class="btn btn-sm btn-primary m-t-n-xs" value=" Search " type="submit">
              <input type="button" onClick="window.location.href='<?php echo generateSeoUrlAdmin("member","treegenealogy",""); ?>'" class="btn btn-sm btn-danger m-t-n-xs" value="Reset" name="reset_button">
            </form>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
              
              <tr class="header">
                <td width="35%" align="center">Left Member : <?php echo count($RS_LftMem); ?></td>
                <td width="35%" align="center">Right Member :  <?php echo count($RS_RgtMem); ?></td>
              </tr>
              
              <tr>
                <td height="82" align="center" valign="top"><table width="80%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse">
                    <tr class="lightbg">
                      <td align="center" class="cmntext" scope="col"><strong>SLN</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>Member     Id</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong> Name</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>Level</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>D.O.J</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>D.O.A</strong></td>
                    </tr>
                    <?php
					$Ctrl = 1;
					$curr_left_lvl = $pre_left_lvl = 0;
					foreach($RS_LftMem as $AR_LftMem):
					$curr_left_lvl = $AR_LftMem['nlevel'];
					if(($curr_left_lvl != $pre_left_lvl)){
						$level_left_ctrl = $curr_left_lvl - $AR_MEM['nlevel'];
					}
					$pre_left_lvl = $AR_LftMem['nlevel'];
				?>
                    <tr class="<?php echo $text_class; ?>">
                      <td  align="center" class="cmntext" scope="col"><?php echo $Ctrl;?></td>
                      <td  align="center" class="" scope="col"><?php echo $AR_LftMem['user_id']; ?></td>
                      <td  scope="col" class="cmntext" align="center"><?php echo $AR_LftMem['first_name']." ".$AR_LftMem['last_name'];?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo $level_left_ctrl; ?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_LftMem['date_join']);?></td>
                      <td  scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_LftMem['date_from']);?></td>
                    </tr>
                    <?php $Ctrl++;
				  endforeach;
				  ?>
                  </table></td>
                <td align="center" valign="top"><table width="80%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse;">
                    <tr class="lightbg">
                      <td align="center" class="cmntext" scope="col"><strong>SLN</strong></td>
                      <td  align="center" class="cmntext" scope="col"><strong>Member     Id </strong></td>
                      <td align="center" class="cmntext" scope="col"><strong> Name</strong></td>
                      <td  align="center" class="cmntext" scope="col"><strong>Level</strong></td>
                      <td  align="center" class="cmntext" scope="col"><strong>D.O.J</strong></td>
                      <td  align="center" class="cmntext" scope="col"><strong>D.O.A</strong></td>
                    </tr>
                    <?php
					$Ctrl = 1;
					$curr_right_lvl = $pre_right_lvl = 0;
					foreach($RS_RgtMem as $AR_RgtMem):
					$curr_right_lvl = $AR_RgtMem['nlevel'];
					if(($curr_right_lvl != $pre_right_lvl)){
						$level_right_ctrl = $curr_right_lvl - $AR_MEM['nlevel'];
					}
					$pre_right_lvl = $AR_RgtMem['nlevel'];
					?>
                    <tr class="<?php echo $text_class; ?>">
                      <td align="center" class="cmntext" scope="col"><?php echo $Ctrl;?></td>
                      <td align="center" class="" scope="col"><?php echo $AR_RgtMem['user_id'];?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo $AR_RgtMem['first_name']." ".$AR_RgtMem['last_name'];?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo $level_right_ctrl; ?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_RgtMem['date_join']);?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_RgtMem['date_from']);?></td>
                    </tr>
                    <?php $Ctrl++;
				  endforeach; ?>
                  </table></td>
              </tr>
            </table>
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
  <?= $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
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
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</html>