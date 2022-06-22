<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();

if(_d($_REQUEST['member_id'])>0 && $this->session->userdata('mem_id')>0){
	$member_id = _d(FCrtRplc($_REQUEST['member_id']));
}else{
	$member_id = $this->session->userdata('mem_id');	
}
if($member_id<=0 || $member_id==''){ set_message("warning","Unable to load tree, please enter valid member"); }

$Var1 = $member_id;
$Var2 = "";
$Var3 = "";
$Var4 = "";
$Var5 = "";
$Var6 = "";
$Var7 = "";
$Var8 = "";
$Var9 = "";
$Var10 = "";
$Var11 = "";
$Var12 = "";
$Var13 = "";
$Var14 = "";
$Var15 = "";
$Var16 = "";
$Var17 = "";
$Var18 = "";
$Var19 = "";
$Var20 = "";
$Var21 = "";
$Var22 = "";
$Var23 = "";
$Var24 = "";
$Var25 = "";
$Var26 = "";
$Var27 = "";
$Var28 = "";
$Var29 = "";
$Var30 = "";
$Var31 = "";

if($Var1!=""){
	$QR_TREE1 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var1' AND (nleft>0 AND nright>0) ";
	$RS_TREE1 = $this->SqlModel->runQuery($QR_TREE1);
	foreach($RS_TREE1 as $AR_TREE1):
		if($AR_TREE1['left_right'] == "L"){
			$Var2 = $AR_TREE1['member_id'];
		}
		if($AR_TREE1['left_right'] == "R"){
			$Var3 = $AR_TREE1['member_id'];
		}
	endforeach;

}

if($Var2!=""){
	
	$QR_TREE2 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var2' AND (nleft>0 AND nright>0) ";
	$RS_TREE2 = $this->SqlModel->runQuery($QR_TREE2);
	foreach($RS_TREE2 as $AR_TREE2):
		if($AR_TREE2['left_right'] == "L"){
			$Var4 = $AR_TREE2['member_id'];
		}
		if($AR_TREE2['left_right'] == "R"){
			$Var5 = $AR_TREE2['member_id'];
		}
	endforeach;
}

if($Var3!=""){
	
	$QR_TREE3 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var3' AND (nleft>0 AND nright>0) ";
	$RS_TREE3 = $this->SqlModel->runQuery($QR_TREE3);
	foreach($RS_TREE3 as $AR_TREE3):
		if($AR_TREE3['left_right'] == "L"){
			$Var6 = $AR_TREE3['member_id'];
		}
		if($AR_TREE3['left_right'] == "R"){
			$Var7 = $AR_TREE3['member_id'];
		}
	endforeach;

}

if($Var4!=""){
	$QR_TREE4= "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var4' AND (nleft>0 AND nright>0) ";
	$RS_TREE4 = $this->SqlModel->runQuery($QR_TREE4);
	foreach($RS_TREE4 as $AR_TREE4):
		if($AR_TREE4['left_right'] == "L"){
			$Var8 = $AR_TREE4['member_id'];
		}
		if($AR_TREE4['left_right'] == "R"){
			$Var9 = $AR_TREE4['member_id'];
		}
	endforeach;
}

if($Var5!=""){
	$QR_TREE5= "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var5' AND (nleft>0 AND nright>0) ";
	$RS_TREE5 = $this->SqlModel->runQuery($QR_TREE5);
	
	foreach($RS_TREE5 as $AR_TREE5):
		if($AR_TREE5['left_right'] == "L"){
			$Var10 = $AR_TREE5['member_id'];
		}
		if($AR_TREE5['left_right'] == "R"){
			$Var11 = $AR_TREE5['member_id'];
		}
	endforeach;
	
}

if($Var6!=""){
	
	$QR_TREE6= "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var6' AND (nleft>0 AND nright>0) ";
	$RS_TREE6 = $this->SqlModel->runQuery($QR_TREE6);
	
	foreach($RS_TREE6 as $AR_TREE6):
		if($AR_TREE6['left_right'] == "L"){
			$Var12 = $AR_TREE6['member_id'];
		}
		if($AR_TREE6['left_right'] == "R"){
			$Var13 = $AR_TREE6['member_id'];
		}
	endforeach;
}

if($Var7!=""){
	
	$QR_TREE7= "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var7' AND (nleft>0 AND nright>0) ";
	$RS_TREE7 = $this->SqlModel->runQuery($QR_TREE7);
	
	foreach($RS_TREE7 as $AR_TREE7):
		if($AR_TREE7['left_right'] == "L"){
			$Var14 = $AR_TREE7['member_id'];
		}
		if($AR_TREE7['left_right'] == "R"){
			$Var15 = $AR_TREE7['member_id'];
		}
	endforeach;

}

if($Var8!=""){
	
	$QR_TREE8 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var8' AND (nleft>0 AND nright>0) ";
	$RS_TREE8  = $this->SqlModel->runQuery($QR_TREE8);
	
	foreach($RS_TREE8 as $AR_TREE8):
		if($AR_TREE8['left_right'] == "L"){
			$Var16 = $AR_TREE8['member_id'];
		}
		if($AR_TREE8['left_right'] == "R"){
			$Var17 = $AR_TREE8['member_id'];
		}
	endforeach;
	
}

if($Var9!=""){
	
	$QR_TREE9 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var9' AND (nleft>0 AND nright>0) ";
	$RS_TREE9  = $this->SqlModel->runQuery($QR_TREE9);
	
	foreach($RS_TREE9 as $AR_TREE9):
		if($AR_TREE9['left_right'] == "L"){
			$Var18 = $AR_TREE9['member_id'];
		}
		if($AR_TREE9['left_right'] == "R"){
			$Var19 = $AR_TREE9['member_id'];
		}
	endforeach;
	
}

if($Var10!=""){

	$QR_TREE10 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var10' AND (nleft>0 AND nright>0) ";
	$RS_TREE10  = $this->SqlModel->runQuery($QR_TREE10);
	
	foreach($RS_TREE10 as $AR_TREE10):
		if($AR_TREE10['left_right'] == "L"){
			$Var20 = $AR_TREE10['member_id'];
		}
		if($AR_TREE10['left_right'] == "R"){
			$Var21 = $AR_TREE10['member_id'];
		}
	endforeach;
	
}

if($Var11!=""){
	
	$QR_TREE11 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var11' AND (nleft>0 AND nright>0) ";
	$RS_TREE11  = $this->SqlModel->runQuery($QR_TREE11);
	
	foreach($RS_TREE11 as $AR_TREE11):
		if($AR_TREE11['left_right'] == "L"){
			$Var22 = $AR_TREE11['member_id'];
		}
		if($AR_TREE11['left_right'] == "R"){
			$Var23 = $AR_TREE11['member_id'];
		}
	endforeach;
	
}

if($Var12!=""){
	
	$QR_TREE12 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var12' AND (nleft>0 AND nright>0) ";
	$RS_TREE12  = $this->SqlModel->runQuery($QR_TREE12);
	
	foreach($RS_TREE12 as $AR_TREE12):
		if($AR_TREE12['left_right'] == "L"){
			$Var24 = $AR_TREE12['member_id'];
		}
		if($AR_TREE12['left_right'] == "R"){
			$Var25 = $AR_TREE12['member_id'];
		}
	endforeach;
	
}

if($Var13!=""){
	
	$QR_TREE13 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var13' AND (nleft>0 AND nright>0) ";
	$RS_TREE13  = $this->SqlModel->runQuery($QR_TREE13);
	
	foreach($RS_TREE13 as $AR_TREE13):
		if($AR_TREE13['left_right'] == "L"){
			$Var26 = $AR_TREE13['member_id'];
		}
		if($AR_TREE12['left_right'] == "R"){
			$Var27 = $AR_TREE13['member_id'];
		}
	endforeach;
	
}

if($Var14!=""){
	
	$QR_TREE14 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var14' AND (nleft>0 AND nright>0) ";
	$RS_TREE14  = $this->SqlModel->runQuery($QR_TREE14);
	
	foreach($RS_TREE14 as $AR_TREE14):
		if($AR_TREE14['left_right'] == "L"){
			$Var28 = $AR_TREE14['member_id'];
		}
		if($AR_TREE14['left_right'] == "R"){
			$Var29 = $AR_TREE14['member_id'];
		}
	endforeach;
	
}

if($Var15!=""){
	
	$QR_TREE15 = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$Var15' AND (nleft>0 AND nright>0) ";
	$RS_TREE15  = $this->SqlModel->runQuery($QR_TREE15);
	
	foreach($RS_TREE15 as $AR_TREE15):
		if($AR_TREE15['left_right'] == "L"){
			$Var30 = $AR_TREE15['member_id'];
		}
		if($AR_TREE15['left_right'] == "R"){
			$Var31 = $AR_TREE15['member_id'];
		}
	endforeach;


}

$left_count = getTool($model->BinaryCount($member_id, "LeftCount"),0); 
$right_count = getTool($model->BinaryCount($member_id, "RightCount"),0);

$left_direct_count = getTool($model->BinaryCount($member_id, "LeftCountDirect"),0);
$right_direct_count = getTool($model->BinaryCount($member_id, "RightCountDirect"),0);


$left_package = getTool($model->BinaryCount($member_id, "LeftPackage"),0);
$right_package = getTool($model->BinaryCount($member_id, "RightPackage"),0);
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
.text-green{
	color:#008000 !important;
}
.text-red{
	color:#FF0000 !important;
}
.text-orange{
	color:#FF8000 !important;
}
.pointer{
	cursor:pointer;
}
span.title {
		display: block;
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-weight: 600;
		font-size: 12px;
		color: #fff;
		letter-spacing: 12px;
		padding-left: 10px;
	}
	.minheight{
		min-height:1200px;
	}
	.pointer{
		cursor:pointer;
	}
	.half-circle {
		width: 50%;
		height: 20px;
		border-top-left-radius: 50px;
		border-top-right-radius: 50px;
		border: 3px solid #3570AF;
		border-bottom: 0;
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
        <h4 class="page-title">Matching Tree</h4>
        <p class="text-muted page-title-alt">Your Tree</p>
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
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
              <tr bgcolor="#FFFFFF">
              	<td colspan="5"><table width="100%" border="1" style="border-collapse:collapse;">
                      <tr>
                        <td colspan="3" align="center"><strong>Left Statistics </strong></td>
                      </tr>
                      <tr>
                        <td align="center">Package</td>
                        <td align="center">Direct Count</td>
                        <td align="center">Total Count</td>
                      </tr>
                      <tr>
                        <td align="center"><?php echo number_format($left_package); ?></td>
                        <td align="center"><?php echo number_format($left_direct_count); ?></td>
                        <td align="center"><?php echo number_format($left_count); ?></td>
                      </tr>
                    </table></td>
                <td colspan="7" align="center"><?php $model->getNameStatus($Var1, $Var1, "");?>
                <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenLarge.gif"  /></td>
                 <td colspan="5"><table width="100%" border="1" style="border-collapse:collapse;">
                      <tr>
                        <td colspan="3" align="center"><strong>Right Statistics </strong></td>
                      </tr>
                      <tr>
                        <td align="center">Package</td>
                        <td align="center">Direct Count</td>
                        <td align="center">Total Count</td>
                      </tr>
                      <tr>
                        <td align="center"><?php echo number_format($right_package); ?></td>
                        <td align="center"><?php echo number_format($right_direct_count); ?></td>
                        <td align="center"><?php echo number_format($right_count); ?></td>
                      </tr>
                    </table></td>
              </tr>
              <tr bgcolor="#FFFFFF">
                <td colspan="8" align="center" valign="top"><?php $model->getNameStatus($Var2, $Var1, "L");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenMid.gif"  />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="1" rowspan="4" align="center" valign="top" style="background-image:url(<?php echo BASE_PATH; ?>setupimages/arrow.gif)">&nbsp;</td>
                <td colspan="8" align="center" valign="top"><?php $model->getNameStatus($Var3, $Var1, "R");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenMid.gif"  /></td>
              </tr>
              <tr bgcolor="#FFFFFF">
                <td height="77" colspan="4" align="center" valign="top"><?php $model->getNameStatus($Var4, $Var2, "L"); ?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif"  /></td>
                <td colspan="4" align="center" valign="top"><?php $model->getNameStatus($Var5, $Var2, "R"); ?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif"  /></td>
                <td colspan="4" align="center" valign="top"><?php $model->getNameStatus($Var6, $Var3, "L");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif"  /></td>
                <td colspan="4" align="center" valign="top"><?php $model->getNameStatus($Var7, $Var3, "R");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif"  /></td>
              </tr>
              <tr bgcolor="#FFFFFF">
                <td height="" colspan="2" align="center" valign="top"><?php $model->getNameStatus($Var8, $Var4, "L");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif" /></td>
                <td colspan="2" align="center" valign="top"><?php $model->getNameStatus($Var9, $Var4, "R");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif"  /></td>
                <td colspan="2" align="center" valign="top"><?php $model->getNameStatus($Var10, $Var5, "L");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif"  /></td>
                <td colspan="2" align="center" valign="top"><?php $model->getNameStatus($Var11, $Var5, "R");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif" /></td>
                <td colspan="2" align="center" valign="top"><?php $model->getNameStatus($Var12, $Var6, "L");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif" /></td>
                <td colspan="2" align="center" valign="top"><?php $model->getNameStatus($Var13, $Var6, "R");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif"  /></td>
                <td colspan="2" align="center" valign="top"><?php $model->getNameStatus($Var14, $Var7, "L");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif"  /></td>
                <td colspan="2" align="center" valign="top"><?php $model->getNameStatus($Var15, $Var7, "R");?> <br>
                  <img src="<?php echo BASE_PATH; ?>setupimages/GenSmal.gif"  /></td>
              </tr>
              <tr height="59" bgcolor="#FFFFFF">
                <td width="73" align="center" valign="middle"><?php $model->getNameStatus($Var16, $Var8, "L");?></td>
                <td width="70" align="center" valign="middle"><?php $model->getNameStatus($Var17, $Var8, "R");?></td>
                <td width="66" align="center" valign="middle"><?php $model->getNameStatus($Var18, $Var9, "L");?></td>
                <td width="73" align="center" valign="middle"><?php $model->getNameStatus($Var19, $Var9, "R");?></td>
                <td width="67" align="center" valign="middle"><?php $model->getNameStatus($Var20, $Var10, "L");?></td>
                <td width="75" align="center" valign="middle"><?php $model->getNameStatus($Var21, $Var10, "R");?></td>
                <td width="73" align="center" valign="middle"><?php $model->getNameStatus($Var22, $Var11, "L");?></td>
                <td width="100" align="center" valign="middle"><?php $model->getNameStatus($Var23, $Var11, "R");?></td>
                <td width="89" align="center" valign="middle"><?php $model->getNameStatus($Var24, $Var12, "L");?></td>
                <td width="50" align="center" valign="middle"><?php $model->getNameStatus($Var25, $Var12, "R");?></td>
                <td width="73" align="center" valign="middle"><?php $model->getNameStatus($Var26, $Var13, "L");?></td>
                <td width="68" align="center" valign="middle"><?php $model->getNameStatus($Var27, $Var13, "R");?></td>
                <td width="73" align="center" valign="middle"><?php $model->getNameStatus($Var28, $Var14, "L");?></td>
                <td width="68" align="center" valign="middle"><?php $model->getNameStatus($Var29, $Var14, "R");?></td>
                <td width="64" align="center" valign="middle"><?php $model->getNameStatus($Var30, $Var15, "L");?></td>
                <td width="100" align="center" valign="middle"><?php $model->getNameStatus($Var31, $Var15, "R");?></td>
              </tr>
              <tr bgcolor="#FFFFFF">
                <td colspan="17" align="left" valign="middle" class="boldtext"><div class="row">
                            <div class="col-md-3">&nbsp;</div>
                            <div class="col-md-2">
                              <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" />
                              <input name="member_id" type="hidden" id="member_id" value="" />
                            </div>
                            <div class="col-md-4">
                              <input name="ViewTree" type="button" class="btn btn-success" id="ViewTree" onClick="SearchTree()" value="View Tree" />
                              <input name="Back" type="button" class="btn btn-danger" id="Back" onClick="GoBack()" value="&lt;&lt;Back" />
                              <input name="FullScreen" type="button" class="btn btn-info" id="FullScreen" 
						onClick="OpenPage('<?php echo $_SERVER['REQUEST_URI']; ?>', 'BinaryTree','700', '600', '10', '10', '1','1')" value="View Full Screen" />
                            </div>
                          </div></td>
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
<?php auto_complete(); ?>
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
<form name="frmtree" method="post" action="<?php echo generateMemberForm("network","tree",""); ?>">
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
	return "<?php echo BASE_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEM_DOWNLINE&member_id=<?php echo $this->session->userdata('mem_id');  ?>";
});
</script>
</html>
