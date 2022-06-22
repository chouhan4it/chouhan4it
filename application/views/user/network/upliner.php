<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$today_date = InsertDate(getLocalTime());
	$yester_date = InsertDate(AddToDate($today_date,"-1 Day"));
	$sss_member_id = $this->session->userdata('mem_id');
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	
	
	$member_id = _d(FCrtRplc($_REQUEST['member_id']));
	if($member_id == ""){$member_id = $sss_member_id;}
	$AR_MEM = $model->getMember($member_id);
	
	
	
	$Q_GEN =  "SELECT nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='".$member_id."'";
	$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
	$nlevel = $AR_GEN['nlevel'];
	$nleft = $AR_GEN['nleft'];
	$nright = $AR_GEN['nright'];
	
	$StrQuery = "&member_id=$member_id";
	
	
   $QR_MEM = "SELECT tmp.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,
			ptree.nlevel, ptree.left_right, ptree.nleft, ptree.nright, tmp.date_join
			FROM tbl_mem_tree_lvl AS ctree LEFT JOIN tbl_members AS tmc ON tmc.member_id=ctree.member_id, 
			tbl_mem_tree_lvl AS ptree LEFT JOIN tbl_members AS tmp ON tmp.member_id=ptree.member_id
			LEFT JOIN tbl_members AS tmsp  ON tmsp.member_id=tmp.sponsor_id
			WHERE ctree.nleft BETWEEN ptree.nleft AND ptree.nright  AND ctree.member_id = '".$member_id."' 
			ORDER BY ptree.nleft DESC";
	$PageVal = DisplayPages($QR_MEM, 100, $Page, $StrQuery);
	
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<style type="text/css">
.img-circle {
    border-radius: 50%;
}
.item-pic{
	width:30px;
}
</style>
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container">
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Member Upliner</h4>
        <p class="text-muted page-title-alt">Your Group Upliner</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <div class="portlet-body" >
            <div class="row"> <?php echo get_message(); ?>
              <div class="col-lg-12">
                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="">
                  <div class="row">
                    <div class="col-sm-12">
                      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
            
			<form method="get" >
            <tr>
              <td colspan="3" align="left" valign="bottom" class="">
                  
                 <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id"  />
				 <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" /> </td>
              <td colspan="3" align="left" valign="bottom" class=""><input name="input_request" type="submit" class="btn btn-success blue" id="input_request"  value="Search" />
                <input name="Back" type="button" class="btn btn-danger red" id="Back" onClick="window.history.back()" value="&lt;&lt;Back" />
				</td>
              <td align="left" valign="bottom" class="">&nbsp;</td>
              <td align="left" valign="bottom" class="">&nbsp;</td>
              <td align="left" valign="bottom" class="">&nbsp;</td>
            </tr>
			</form>
            <tr role="row">
              <td align="left"><strong>Srl # </strong></td>
              <td align="left"><strong>Profile</strong></td>
              <td width="13%" align="left"><strong> User Id </strong></td>
              <td width="13%" align="center"><strong>Introducer User Id </strong></td>
              <td width="12%" align="left"><strong>Introducer User Name </strong></td>
              <td width="12%" align="left"><strong>Register Date </strong></td>
              <td width="9%" align="left"><strong>Rank</strong></td>
              <td width="9%" align="left"><strong>City</strong></td>
              <td width="10%" align="left"><strong>State</strong></td>
            </tr>
            <?php 
			if($PageVal['TotalRecords'] > 0){
			$CurrLvl = $PreLvl = 0;
			$Ctrl = $PageVal['RecordStart'];
			foreach($PageVal['ResultSet'] as $AR_Fld){
			$Ctrl++;
			
			$AR_IMG = $model->getCurrentImg($AR_Fld['member_id']);
			
			$CurrLvl = $AR_Fld['nlevel'];
				if(($CurrLvl != $PreLvl)){
					$LvlCtrl = $CurrLvl - $fldiLevel;
			?>
            <tr bgcolor="#B8E6FE">
              <td colspan="9" align="left" valign="middle" class="cmntext"><strong>Level <?php echo $LvlCtrl;?></strong></td>
            </tr>
            <?php 
			$PreLvl = $AR_Fld['nlevel'];
			}
?>
            <tr class="<?php echo ($AR_Fld['rank_id']==0)? "text-danger":""; ?>" onClick="window.location.href='?user_id=<?php echo $AR_Fld['user_id']?>&member_id=<?php echo _e($AR_Fld['member_id']);?>'" style="cursor:pointer">
              <td width="5%" align="center" valign="top" class="cmntext"><?php echo $Ctrl;?></td>
              <td width="17%"  align="" valign="" class=""><img src="<?php echo $AR_IMG['IMG_SRC'];?>" class="img-circle" width="60px" height="60px" /><?php echo $AR_Fld['first_name']." ".$AR_Fld['last_name'];?></td>
              <td align="left" valign="middle" class="<?php echo $AR_IMG[CssCls];?>"><?php echo strtoupper($AR_Fld['user_id']);?></td>
              <td align="center" valign="middle" class="cmntext">&nbsp;<?php echo $AR_Fld['spsr_user_id'];?></td>
              <td align="left" valign="middle" class="cmntext"><?php echo $AR_Fld['spsr_full_name'];?></td>
              <td align="left" valign="middle" class="cmntext"><?php echo DisplayDate($AR_Fld['date_join']);?></td>
              <td align="left" valign="middle" class=""><?php echo ($AR_Fld['rank_name'])? $AR_Fld['rank_name']:"Registered Consumer"; ?></td>
              <td align="left" valign="middle" class=""><?php echo $AR_Fld['city_name'];?></td>
              <td align="left" valign="middle" class="cmntext"><?php echo $AR_Fld['state_name'];?></td>
            </tr>
            <?php }?>
            <?php }else{?>
            <tr>
              <td colspan="9" align="center" class="errMsg">No record found</td>
            </tr>
            <?php } ?>
          </table>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-6">
                      <div aria-live="polite" role="status" id="dynamic-table_info" class="dataTables_info"> Showing <?php echo $PageVal['RecordStart']+1; ?> to <?php echo  count($PageVal['ResultSet']); ?> of <?php echo $PageVal['TotalRecords']; ?> entries </div>
                    </div>
                    <div class="col-xs-6">
                      <div id="dynamic-table_paginate" class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                          <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
                        </ul>
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
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); auto_complete(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
	});
</script>
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo MEMBER_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER_DOWNLINE";
});
</script>
</html>
