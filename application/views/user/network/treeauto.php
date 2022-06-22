<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
if($_REQUEST['member_id'] == ""){ $member_id=$this->session->userdata('mem_id'); }
else{$member_id = _d($_REQUEST['member_id']);}
$AR_MEM  = $model->getMember($member_id);
$less = _d($_REQUEST['less']);
?>
<!DOCTYPE html>
<html>
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
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
/*For Tree Lines*/
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
</head>
<body>
<script language="javascript" type="text/javascript" src="<?php echo BASE_PATH; ?>javascripts/wz_tooltip.js"></script> 
<!-- Navigation Bar-->
<?php if($less!="menu"){ $this->load->view(MEMBER_FOLDER.'/header'); } ?>
<!-- End Navigation Bar-->
<div class="wrapper" <?php echo ($less=="menu")? "style='margin:0px;'":""; ?>>
  <div class="container"> 
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">My Tree Level</h4>
        <p class="text-muted page-title-alt">My Tree Level</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
        <div class="portlet">
          <div class="portlet-body" >
            <div class="row">
              <?php  get_message(); ?>
              <div class="col-xs-12">
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr bgcolor="#FFFFFF">
                    <td width="18%" align="right" valign="middle"><input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $AR_MEM['user_id']; ?>" />
                      &nbsp;&nbsp;
                      <input name="member_id" type="hidden" id="member_id" value="" /></td>
                    <td width="82%" align="left" valign="middle"><input name="ViewTree" type="button" class="btn  btn-sm btn-success" id="ViewTree" onClick="SearchTree()" value="View Tree" />
                      <input name="Back" type="button" class="btn  btn-sm btn-danger" id="Back" onClick="GoBack()" value="&lt;&lt;Back" />
                      <input name="FullScreen" type="button" class="btn btn-sm btn-danger" id="FullScreen" onClick="OpenPage('<?php echo $_SERVER['REQUEST_URI']; ?>?less=<?php echo _e("menu"); ?>', 'BinaryTree','700', '600', '10', '10', '1','1')" value="View Full Screen" /></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"><ul class="col-md-2 list-group">
                        <?php
					  $nlevel = 5; #FCrtRplc($_REQUEST['nlevel']);
					$QR_LVL = "SELECT DISTINCT nlevel FROM  tbl_mem_tree WHERE nlevel>0 ORDER BY nlevel ASC LIMIT 5";
					$RS_LVL = $this->SqlModel->runQuery($QR_LVL);
					foreach($RS_LVL as $AR_LVL):
				  ?>
                        <li class="list-group-item"><a  href="<?php echo generateSeoUrlMember("network","treeauto",""); ?>?nlevel=<?php echo $AR_LVL['nlevel']; ?>&member_id=<?php echo _e($member_id); ?>">Level <?php echo $AR_LVL['nlevel']; ?></a></li>
                        <?php endforeach;  ?>
                      </ul></td>
                  </tr>
                </table>
                <ul id="org" style="display:none;">
                  <li> <?php echo $model->getNameStatus($AR_MEM['member_id']); ?>
                    <ul>
                      <?php
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
                      <?php  endforeach; ?>
                    </ul>
                  </li>
                </ul>
                <div class="flexcroll">
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
                    <td width="12%" class="txtred" align="center"><span class="txtred">Paid User</span></td>
                    <td width="13%"><span class="txtgrn"><img src="<?php echo BASE_PATH; ?>setupimages/male.png" alt="Active Member" /></span><span class="txtgrn"><img src="<?php echo BASE_PATH; ?>setupimages/female.png" alt="Active Member" /></span></td>
                    <td width="11%" class="txtgrn" align="center">Open Place</td>
                    <td width="9%" class="txtgrn" align="center"><img src="<?php echo BASE_PATH; ?>setupimages/add_member.gif" alt="Active Member"/></td>
                    <td width="8%">&nbsp;</td>
                    <td width="14%" class="cmntext" align="center">&nbsp;</td>
                    <td width="30%">&nbsp;</td>
                  </tr>
                </table>
              </div>
              <!-- /.col --> 
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php auto_complete(); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/jquery/jquery.treemenu.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<script src="<?php echo BASE_PATH; ?>jquery/jquery.jOrgChart.js"></script>
<script type="text/javascript">
	$(function(){
		$(".tree").treemenu({delay:300}).openActive();
	});
</script>
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
<script>
    jQuery(document).ready(function() {
        $("#org").jOrgChart({
            chartElement : '#chart',
            dragAndDrop  : false
        });
    });
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
	return "<?php echo BASE_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEM_DOWNLINE&member_id=<?php echo $this->session->userdata('mem_id'); ?>";
});
</script>
</html>
