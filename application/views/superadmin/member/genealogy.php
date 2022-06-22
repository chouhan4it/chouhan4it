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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Genealogy </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
              <?php
			$member_id = _d(FCrtRplc($_REQUEST['member_id']));
			$user_id = FCrtRplc($_REQUEST['user_id']);
			if($member_id == ""){$member_id="1";}

			
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
			


		// Count All Left Memebers
		$Q_Left = "SELECT COUNT(A.member_id) AS fldiCtrl FROM tbl_members AS A LEFT JOIN  tbl_mem_tree AS B ON A.member_id=B.member_id WHERE 
		B.nleft BETWEEN '$Left_Lft' AND '$Left_Rgt'  $StrWhr";
		$AR_Left = $this->SqlModel->runQuery($Q_Left,true);
		
		// Count All Right Members
		$Q_Right = "SELECT COUNT(A.member_id) AS fldiCtrl FROM tbl_members AS A LEFT JOIN tbl_mem_tree AS B ON A.member_id=B.member_id  WHERE 
		B.nleft BETWEEN '$Right_Lft' AND '$Right_Rgt'  $StrWhr";
		$Ar_Right = $this->SqlModel->runQuery($Q_Right,true);
?>
              <tr class="header">
                <td align="left"><form method="get" name="frmSrch" id="frmSrch" autocomplete="off" action="<?php echo ADMIN_PATH."member/genealogy"; ?>">
                    <div class="col-xs-12 col-sm-6">
                      <div class="clearfix">
                        <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" style="width:250px;" />
                        <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                        &nbsp; </div>
                    </div>
                    <label class="control-label col-xs-12 col-sm-6" for="email">
                      <input name="input_request" type="submit" class="btn btn-success btn-sm" id="input_request"  value="Search" />
                      <input name="Back" type="button" class="btn btn-danger btn-sm" id="Back" onClick="window.history.back()" value="&lt;&lt;Back" />
                    </label>
                  </form></td>
                <td align="center">&nbsp;</td>
              </tr>
              <tr class="header">
                <td width="35%" align="center">Left Member : <?php echo $AR_Left['fldiCtrl']; ?></td>
                <td width="35%" align="center">Right Member : <?php echo $Ar_Right['fldiCtrl']; ?></td>
              </tr>
              <?php
			$Q_LeftMem = "SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,
					 tree.nlevel, tree.left_right, tree.nleft, tree.nright, tree.date_join FROM tbl_members AS tm	
					 LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
					 LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id
					 WHERE tree.nleft BETWEEN '$Left_Lft' AND '$Left_Rgt' $StrWhr ORDER BY tm.member_id ASC;";
			$RS_LftMem = $this->SqlModel->runQuery($Q_LeftMem);

			$Q_RightMem = "SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,
				 tree.nlevel, tree.left_right, tree.nleft, tree.nright, tree.date_join FROM tbl_members AS tm	
				 LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
				 LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id
				 WHERE tree.nleft BETWEEN '$Right_Lft' AND '$Right_Rgt' $StrWhr ORDER BY tm.member_id ASC;";
			$RS_RgtMem = $this->SqlModel->runQuery($Q_RightMem);
?>
              <tr>
                <td height="82" align="center" valign="top"><table width="80%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse">
                    <tr class="lightbg">
                      <td align="center" class="cmntext" scope="col"><strong>SLN</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>Member     Id</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong> Name</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>D.O.J</strong></td>
                    </tr>
                    <?php
					$Ctrl = 1;
					foreach($RS_LftMem as $AR_LftMem):
				?>
                    <tr>
                      <td width="7%" align="center" class="cmntext" scope="col"><?php echo $Ctrl;?></td>
                      <td width="26%" align="center" class="" scope="col"><?php echo $AR_LftMem['user_id']; ?></td>
                      <td width="41%" scope="col" class="cmntext" align="center"><?php echo $AR_LftMem['first_name']." ".$AR_LftMem['last_name'];?></td>
                      <td width="26%" scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_LftMem['date_join']);?></td>
                    </tr>
                    <?php $Ctrl++;
				  endforeach;
				  ?>
                  </table></td>
                <td align="center" valign="top"><table width="80%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse;">
                    <tr class="lightbg">
                      <td width="8%" align="center" class="cmntext" scope="col"><strong>SLN</strong></td>
                      <td width="26%" align="center" class="cmntext" scope="col"><strong>Member     Id </strong></td>
                      <td width="43%" align="center" class="cmntext" scope="col"><strong> Name</strong></td>
                      <td width="23%" align="center" class="cmntext" scope="col"><strong>D.O.J</strong></td>
                    </tr>
                    <?php
					$Ctrl = 1;
					foreach($RS_RgtMem as $AR_RgtMem):
					?>
                    <tr>
                      <td align="center" class="cmntext" scope="col"><?php echo $Ctrl;?></td>
                      <td align="center" class="" scope="col"><?php echo $AR_RgtMem['user_id'];?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo $AR_RgtMem['first_name']." ".$AR_RgtMem['last_name'];?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_RgtMem['date_join']);?></td>
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
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</html>
