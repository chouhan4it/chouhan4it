<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();

$member_id = _d(FCrtRplc($_REQUEST['member_id']));
if($member_id == ""){$member_id="1";}

$Q_GEN =  "SELECT nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='$member_id'";
$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
$nlevel = $AR_GEN['nlevel'];
$nleft = $AR_GEN['nleft'];
$nright = $AR_GEN['nright'];

$StrQuery = "&member_id=$member_id";

$QR_MEM = "SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,
			 tree.nlevel, tree.left_right, tree.nleft, tree.nright
			 FROM tbl_members AS tm	
			 LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
			 LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id
			 WHERE tm.sponsor_id='$member_id' $StrWhr ORDER BY tree.nlevel ASC";
$PageVal = DisplayPages($QR_MEM, 200, $Page, $StrQuery);
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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Directs </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr>
                <td colspan="8" align="left" valign="bottom" class="bigtexthdr">Member Directs</td>
              </tr>
              <form method="get" >
                <tr>
                  <td colspan="2" align="left" valign="bottom" class=""><input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" style="width:200px;" />
                    <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                    &nbsp; </td>
                  <td colspan="6" align="left" valign="bottom" class=""><input name="input_request" type="submit" class="btn btn-primary" id="input_request"  value="Search" />
                    <input name="Back" type="button" class="btn btn-danger" id="Back" onClick="window.history.back()" value="&lt;&lt;Back" /></td>
                </tr>
              </form>
              <tr class="">
                <td align="center">Srl No</td>
                <td align="center">Profile</td>
                <td align="left">Member Id</td>
                <td align="left">Full Name</td>
                <td align="center">Sponsor Id</td>
                <td align="left">Joining Date</td>
                <td align="left">Email</td>
                <td align="left">Mobile</td>
              </tr>
              <?php 
			if($PageVal['TotalRecords'] > 0){
			$Ctrl = $PageVal['RecordStart']+1;
			foreach($PageVal['ResultSet'] as $AR_DT){
			
			$AR_IMG = $model->getCurrentImg($AR_DT['member_id']);
			
			
			?>
              <tr class="" >
                <td align="center" valign="top" class="cmntext"><?php echo $Ctrl;?></td>
                <td align="center" valign="top" class="cmntext"><img src="<?php echo $AR_IMG['IMG_SRC'];?>" width="23" height="23" /></td>
                <td align="left" valign="middle" class="<?php echo $AR_IMG['CssCls'];?>"><?php echo strtoupper($AR_DT['user_id']);?></td>
                <td align="left" valign="middle" class="cmntext"><?php echo $AR_DT['first_name']." ".$AR_DT['last_name'];?></td>
                <td align="center" valign="middle" class="cmntext"><?php echo $AR_DT['spsr_user_id'];?></td>
                <td align="left" valign="middle" class="cmntext"><?php echo DisplayDate($AR_DT['date_join']);?></td>
                <td align="left" valign="middle" class=""><span class="cmntext"><?php echo $AR_DT['member_email'];?></span></td>
                <td align="left" valign="middle" class="cmntext"><?php echo $AR_DT['member_mobile'];?></td>
              </tr>
              <?php  $Ctrl++; }?>
              <?php }else{?>
              <tr>
                <td colspan="8" align="center" class="errMsg">No record found</td>
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
