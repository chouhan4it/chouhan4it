<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();

if(_d($_REQUEST['member_id'])!=''){
	$member_id = _d(FCrtRplc($_REQUEST['member_id']));
}else{
	$member_id = $model->getFirstId();
}
$AR_MEM  = $model->getMember($member_id);
$AR_OPR  = $model->getOprtrDetail($AR_MEM['oprt_id']);
if($member_id<=0 || $member_id==''){ set_message("warning","Unable to load tree, please enter valid member"); }
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
    <script src="<?php echo BASE_PATH; ?>jquery/jquery.jOrgChart.js"></script>
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
.text-green {
	color: #008000 !important;
}
.text-red {
	color: #FF0000 !important;
}
.text-orange {
	color: #FF8000 !important;
}
.pointer {
	cursor: pointer;
}
.jOrgChart .line {
	height : 20px;
	width : 2px;
}
.jOrgChart .down {
	background-color : #868686;
	margin : 0px auto;
}
.jOrgChart .top {
	border-top : 2px solid #868686;
}
.jOrgChart .left {
	border-right : 1px solid #868686;
}
.jOrgChart .right {
	border-left : 1px solid #868686;
}
/* node cell */
.jOrgChart td {
	text-align : center;
	vertical-align : top;
	padding : 0;
}
/* Tree Box */
.jOrgChart .node {
	background-color : #FFFFFF;
	display : inline-block;
	width : 120px;
	height : 50px;
	z-index : 10;
	margin : 0 2px;
	border-radius : 8px;
	-moz-border-radius : 8px;
	padding : 3px;
}
.small_font {
	font-size: 9px !important;
}
.flexcroll {
	width: auto;
	height: auto;
	overflow: scroll;
}
.flexcroll {
	scrollbar-face-color: #367CD2;
	scrollbar-shadow-color: #FFFFFF;
	scrollbar-highlight-color: #FFFFFF;
	scrollbar-3dlight-color: #FFFFFF;
	scrollbar-darkshadow-color: #FFFFFF;
	scrollbar-track-color: #FFFFFF;
	scrollbar-arrow-color: #FFFFFF;
}
		
		/* Let's get this party started */
		.flexcroll::-webkit-scrollbar {
 width: 12px;
}
		 
		/* Track */
		.flexcroll::-webkit-scrollbar-track {
 -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
 -webkit-border-radius: 10px;
 border-radius: 10px;
}
		 
		/* Handle */
		.flexcroll::-webkit-scrollbar-thumb {
 -webkit-border-radius: 10px;
 border-radius: 10px;
 background: rgba(255,0,0,0.8);
 -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
}
</style>
    <script>
    jQuery(document).ready(function() {
        $("#org").jOrgChart({
            chartElement : '#chart',
            dragAndDrop  : false
        });
    });
    </script>
    </head>
    <body class="no-skin" style="background-color:#fff;">
<script language="javascript" type="text/javascript" src="<?php echo BASE_PATH; ?>javascripts/wz_tooltip.js"></script>
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
      <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
      <div class="main-content">
    <div class="main-content-inner">
          <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
          <div class="page-content">
        <div class="page-header">
              <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Level Tree</small> </h1>
            </div>
        <!-- /.page-header -->
        <div class="row">
              <?php  get_message(); ?>
              <div class="col-xs-12">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr bgcolor="#FFFFFF">
                <td  align="" valign="middle"><input name="user_id" type="text" class="cmnfld" id="user_id" value="<?php echo $AR_MEM['user_id']; ?>" />
                      &nbsp;&nbsp;
                      <input name="member_id" type="hidden" id="member_id" value="" />
                      <input name="ViewTree" type="button" class="btn  btn-sm btn-success" id="ViewTree" onClick="SearchTree()" value="View Tree" />
                      <input name="Back" type="button" class="btn  btn-sm btn-warning" id="Back" onClick="GoBack()" value="&lt;&lt;Back" />
                      <input name="FullScreen" type="button" class="btn btn-sm btn-danger" id="FullScreen" onClick="OpenPage('<?php echo $_SERVER['REQUEST_URI']; ?>', 'BinaryTree','700', '600', '10', '10', '1','1')" value="View Full Screen" /></td>
              </tr>
                  <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
                  <tr>
                <td colspan="2"><ul class="col-md-2 list-group">
                    <?php
					$QR_LVL = "SELECT DISTINCT nlevel FROM  tbl_mem_tree WHERE nlevel>0 ORDER BY nlevel ASC LIMIT 5";
					$RS_LVL = $this->SqlModel->runQuery($QR_LVL);
					foreach($RS_LVL as $AR_LVL):
				  ?>
                    <li class="list-group-item"><a  href="<?php echo generateSeoUrlAdmin("member","treeauto",""); ?>?nlevel=<?php echo $AR_LVL['nlevel']; ?>&member_id=<?php echo _e($member_id); ?>">Level <?php echo $AR_LVL['nlevel']; ?></a></li>
                    <?php endforeach;  ?>
                  </ul></td>
              </tr>
                </table>
            <div class="flexcroll">
                  <ul id="org" style="display:none;">
                <li> <?php echo $model->getNameStatus($AR_MEM['member_id']); ?>
                      <ul>
                    <?php
				$nlevel = 5; #FCrtRplc($_REQUEST['nlevel']);
				$Q_FLVL = "SELECT tm.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
				FROM tbl_members AS tm
				LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				WHERE  tree.member_id!='".$member_id."' 
				AND tree.sponsor_id='".$member_id."'";
				$RS_FLVL = $this->SqlModel->runQuery($Q_FLVL);
				foreach($RS_FLVL as $AR_FLVL):
					
					$Q_SLVL = "SELECT tm.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
					FROM tbl_members AS tm
					LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
					WHERE  tree.member_id!='".$AR_FLVL['member_id']."' 
					AND tree.sponsor_id='".$AR_FLVL['member_id']."'";
	  		 ?>
                    <li> <?php echo $model->getNameStatus($AR_FLVL['member_id']); ?>
                          <ul>
                        <?php
					
					$RS_SLVL = $this->SqlModel->runQuery($Q_SLVL);
					foreach($RS_SLVL as $AR_SLVL):
					if($nlevel>=2){
					$AR_IMG_SLVL = $model->getMemberTree($AR_SLVL['member_id']);
					?>
                        <li> <?php echo $model->getNameStatus($AR_SLVL['member_id']); ?>
                              <ul>
                            <?php 
									$Q_TLVL = "SELECT tm.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
									FROM tbl_members AS tm
									LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
									WHERE  tree.member_id!='".$AR_SLVL['member_id']."' 
									AND tree.sponsor_id='".$AR_SLVL['member_id']."'";
									$RS_TLVL = $this->SqlModel->runQuery($Q_TLVL);
									foreach($RS_TLVL as $AR_TLVL):
									if($nlevel>=3){
							 ?>
                            <li><?php echo $model->getNameStatus($AR_TLVL['member_id']); ?>
                                  <ul>
                                <?php 
                                            $Q_FTHLVL = "SELECT tm.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
                                            FROM tbl_members AS tm
                                            LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
                                            WHERE  tree.member_id!='".$AR_TLVL['member_id']."' 
                                            AND tree.sponsor_id='".$AR_TLVL['member_id']."'";
                                            $RS_FTHLVL = $this->SqlModel->runQuery($Q_FTHLVL);
                                            foreach($RS_FTHLVL as $AR_FTHLVL):
                                            if($nlevel>=4){
                                     ?>
                                <li><?php echo $model->getNameStatus($AR_FTHLVL['member_id']); ?>
                                      <ul>
                                    <?php 
                                                            $Q_FVELVL = "SELECT tm.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
                                                                         FROM tbl_members AS tm
                                                                         LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
                                                                         WHERE  tree.member_id!='".$AR_FTHLVL['member_id']."' 
                                                                         AND tree.sponsor_id='".$AR_FTHLVL['member_id']."'";
                                                            $RS_FVELVL = $this->SqlModel->runQuery($Q_FVELVL);
                                                            foreach($RS_FVELVL as $AR_FVELVL):
                                                           	    if($nlevel>=5){
                                                            ?>
                                    <li><?php echo $model->getNameStatus($AR_FVELVL['member_id']); ?></li>
                                    <?php } endforeach; ?>
                                  </ul>
                                    </li>
                                <?php } endforeach; ?>
                              </ul>
                                </li>
                            <?php } endforeach; ?>
                          </ul>
                            </li>
                        <?php } endforeach; ?>
                      </ul>
                        </li>
                    <?php  endforeach ?>
                  </ul>
                    </li>
              </ul>
                  <div id="chart" class="orgChart" align="center"></div>
                </div>
            <br>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                  <tr>
                <td height="26" colspan="8">&nbsp;</td>
              </tr>
                  <tr>
                <td height="26">&nbsp;</td>
                <td height="26" colspan="4"><span class="cmntext"><img src="<?php echo BASE_PATH; ?>setupimages/sample1_sub_arrow.gif" alt="Arrow" width="14" height="14" align="left" /></span><span class="bluetext">Click on Consultant id to view  Tree </span></td>
                <td height="26" colspan="3"><span class="bluetext"><img src="<?php echo BASE_PATH; ?>setupimages/sample1_sub_arrow.gif" alt="Arrow" width="14" height="14" align="left" />Click on Member icon to view   Statistics</span></td>
              </tr>
                  <tr>
                <td width="3%" height="45">&nbsp;</td>
                <td width="12%" class="txtred" align="center"><span class="txtred">Paid User </span></td>
                <td width="13%"><span class="txtgrn"><img src="<?php echo BASE_PATH; ?>setupimages/male.png" alt="Active Member" /></span><span class="txtgrn"><img src="<?php echo BASE_PATH; ?>setupimages/female.png" alt="Active Member" /></span></td>
                <td width="11%" class="txtgrn" align="center"><span class="cmntext">OPEN SPACE</span></td>
                <td width="9%" class="txtgrn" align="center"><img src="<?php echo BASE_PATH; ?>setupimages/add_member.gif" alt="Active Member"/></td>
                <td width="8%">&nbsp;</td>
                <td width="14%" class="cmntext" align="center">&nbsp;</td>
                <td width="30%">&nbsp;</td>
              </tr>
                </table>
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
    <script language="javascript" type="text/javascript">
function GoBack(){
	<?php if($member_id != ""){?>window.history.go(-1);<?php } ?>
}
function ViewTop(){
	window.location="?#";
}
function SearchTree(){
	var member_id = document.getElementById("member_id").value;
	if(trim(member_id) != ""){moveTree(member_id);}
}
function moveTree(member_id){
	if(member_id != ""){
		document.frmtree.member_id.value=member_id;
		document.frmtree.submit();
	}else{
		return false;
	}
}
</script>
    <form name="frmtree" method=get action="?">
  <input type=hidden name="temp" value="<?php echo base64_encode("temp");?>">
  <input type=hidden name="mytree" value="<?php echo base64_encode("mytree");?>">
  <input type=hidden name="member_id">
  <input type=hidden name="view" value="<?php echo base64_encode("myview");?>">
  <input type=hidden name="others" value="<?php echo base64_encode("others".$_REQUEST['member_id']);?>">
</form>
    <script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo BASE_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</html>
