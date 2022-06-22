<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$today_date = InsertDate(getLocalTime());
	$yester_date = InsertDate(AddToDate($today_date,"-1 Day"));
	
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$member_id = _d(FCrtRplc($_REQUEST['member_id']));
	if($member_id == ""){$member_id = $this->session->userdata('mem_id');}
	
	
	$Q_GEN =  "SELECT member_id, nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='".$member_id."'";
	$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
	$nlevel = $AR_GEN['nlevel'];
	$nleft = $AR_GEN['nleft'];
	$nright = $AR_GEN['nright'];
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
        <h4 class="page-title">MY DIRECT'S </h4>
        <p class="text-muted page-title-alt">Your Direct Member</p>
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
                            <td colspan="2" align="left" valign="bottom" class=""><input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id"/>
                              <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                              &nbsp; </td>
                            <td colspan="5" align="left" valign="bottom" class=""><input name="input_request" type="submit" class="btn btn-success blue" id="input_request"  value="Search" />
                              <input name="Back" type="button" class="btn btn-danger red" id="Back" onClick="window.history.back()" value="&lt;&lt;Back" />                            </td>
                          </tr>
                        </form>
                        <tr class="">
                          <td align="left"><strong>Srl No </strong></td>
                          <td align="left"><strong>Profile</strong></td>
                          <td width="17%" align="left"><strong>User Id</strong></td>
                          <td width="10%"  align="left"><strong>City </strong></td>
                          <td width="15%" align="center"><strong>State</strong></td>
                          <td width="10%"  align="center"><strong>Register Date</strong></td>
                          <td width="13%"  align="left"><strong>Current Rank</strong></td>
                        </tr>
						<?php
			
								$QR_MEM = "SELECT tm.*, tree.nleft, tree.nright, tms.user_id AS spsr_user_id
										  FROM tbl_members AS tm 
										  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
										  LEFT JOIN tbl_members AS tms ON tms.member_id=tm.member_id
										  WHERE tm.delete_sts>0 
										  AND tm.sponsor_id='".$AR_GEN['member_id']."' ORDER BY tm.member_id ASC";
								$RS_MEM = $this->SqlModel->runQuery($QR_MEM);
	
								$Ctrl =1;
								foreach($RS_MEM as $AR_MEM):
								$rank_name = $model->getRankName($AR_MEM['rank_id']);

								$AR_IMG = $model->getCurrentImg($AR_MEM['member_id']);
							 ?>
							<tr class="<?php echo ($AR_MEM['rank_id']==0)? "text-danger":""; ?>"  style="cursor:pointer">
							  <td width="6%"   align="center" valign="top" class="cmntext"><?php echo $Ctrl;?></td>
							  <td width="19%"   valign="top" class="cmntext"><img src="<?php echo $AR_IMG['IMG_SRC'];?>" width="23" height="23" /><?php echo $AR_MEM['first_name']." ".$AR_MEM['last_name'];?></td>
							  <td align="left" valign="middle" class="<?php echo $AR_IMG['CssCls'];?>"><?php echo strtoupper($AR_MEM['user_id']);?></td>
							  <td align="left" valign="middle" class="cmntext"><?php echo $AR_MEM['city_name']; ?></td>
							  <td align="center" valign="middle" class="cmntext"><?php echo $AR_MEM['state_name']; ?></td>
							  <td align="center" valign="middle" class="cmntext"><?php echo DisplayDate($AR_MEM['date_join']);?></td>
							  <td align="left" valign="middle" class="cmntext"><?php echo ($rank_name)? $rank_name:"Registered Consumer"; ?></td>
							</tr>
							<?php $Ctrl++; endforeach;  ?>
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
