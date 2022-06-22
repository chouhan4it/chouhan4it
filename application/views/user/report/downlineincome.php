<?php
	defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$today_date = InsertDate(getLocalTime());
	$yester_date = InsertDate(AddToDate($today_date,"-1 Day"));
	
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$first_date = InsertDate($model->getStartOrderDate());
	
	
	$member_id = _d(FCrtRplc($_REQUEST['member_id']));
	if($member_id == ""){$member_id = $this->session->userdata('mem_id');}
	$AR_MEM = $model->getMember($member_id,"LVL");
	
	$from_date = InsertDate($today_date);
	if($_REQUEST['d_month']>0){
		$d_month = FCrtRplc($_REQUEST['d_month']);
		$d_year = FCrtRplc($_REQUEST['d_year']);
		$d_year = ($d_year>0)? $d_year:date("Y");
		$start_date = $d_year."-".$d_month."-01";
		$PERIOD = getMonthDates($start_date);
		$from_date = $PERIOD['flddFDate'];
		$to_date = $PERIOD['flddTDate'];
		$SrchQ .="&d_month=$d_month&d_year=$d_year";
	}
	if($_REQUEST['d_month']==''){
		$d_month = date("m");
		$d_year = FCrtRplc($_REQUEST['d_year']);
		$d_year = ($d_year>0)? $d_year:date("Y");
		$start_date = $d_year."-".$d_month."-01"; 
		$PERIOD = getMonthDates($start_date);
		$from_date = $PERIOD['flddFDate'];
		$to_date = $PERIOD['flddTDate'];
	}
	
	
	
	
	$Q_GEN =  "SELECT nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='".$member_id."'";
	$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
	$nlevel = $AR_GEN['nlevel'];
	$max_level = $nlevel+1;
	$nleft = $AR_GEN['nleft'];
	$nright = $AR_GEN['nright'];
	
	$SrchQ .= "&member_id="._e($member_id)."";
	
	
	$QR_MEM = "SELECT tm.member_id, tm.user_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name,
			   CONCAT_WS(' ',tmsp.first_name,tmsp.last_name) AS spsr_full_name, tmsp.user_id AS spsr_user_id ,
		       tm.city_name, tm.state_name,
			   tree.nlevel, tree.nleft, tree.nright, tm.date_join, tr.rank_name
			   FROM tbl_members AS tm	
			   LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
			   LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id
			   LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
			   WHERE tm.delete_sts>0 
			   AND ( tree.member_id='".$member_id."' OR  tree.sponsor_id ='".$member_id."' )
			   $StrWhr 
			   ORDER BY tree.nlevel ASC, tree.member_id ASC";
	$PageVal = DisplayPages($QR_MEM, 100, $Page, $SrchQ);
	
	ExportQuery($QR_MEM,array("d_month"=>$d_month,"d_year"=>$d_year));
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
<style type="text/css">
.img-circle {
    border-radius: 50%;
}
.item-pic{
	width:30px;
}
tr > td {
	font-size:10px !important;
	color:#303030;
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
        <h4 class="page-title">Downline Collection</h4>
        <p class="text-muted page-title-alt">Your downline collection</p>
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
                    <div class="col-md-12">
                      <form id="form-search" name="form-search" method="get" action="<?php echo generateMemberForm("report","downlineincome",""); ?>">
                        <b>User Id </b>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                          <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id" style="width:200px;" />
                          <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <b>Select Month </b>
                        <div class="clearfix">&nbsp;</div>
						 <div class="form-group">
							<div class="col-md-6">
								<select name="d_month" class="form-control"  id="d_month">
									<option value="">---select month---</option>
									<?php echo DisplayCombo($d_month,"MONTH"); ?>
								</select>                
							</div>
							<div class="col-md-6">
								<select name="d_year" class="form-control" id="d_year">
									<option value="0">---select year---</option>
									<?php echo DisplayCombo($_REQUEST['d_year'],"PAST_5_YEAR"); ?>     
								</select>           
								
							</div>
						</div>        
                        <div class="clearfix">&nbsp;</div>
                        <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                        <a href="<?php echo generateMemberForm("report","downlineincome",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
						<a href="<?php echo generateSeoUrlMember("download","downlineincome",""); ?>"  class="btn btn-success m-t-n-xs" value=" Excel "> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                      </form>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
                        <tr role="row">
                          <td ><strong>Srl # </strong></td>
                          <td  ><strong>Profile</strong></td>
                          <td ><strong>Member Name</strong></td>
                          <td ><strong>User Id</strong></td>
                          <td ><strong>Register Date </strong></td>
                          <td  ><strong>City</strong></td>
                          <td  ><strong>Rank</strong></td>
                          <td ><table width="100%" border="1" style="border-collapse:collapse;">
                            <tr>
                              <td colspan="3" align="center"><strong> "<?php echo getDateFormat($from_date,"F"); ?>" Month</strong></td>
                              </tr>
                            <tr>
                              <td>SELF</td>
                              <td>GROUP</td>
                              <td>TOTAL</td>
                            </tr>
                          </table></td>
                          <td ><table width="100%" border="1" style="border-collapse:collapse;">
                            <tr>
                              <td colspan="3" align="center"><strong> Accumulated till "<?php echo getDateFormat($from_date,"F"); ?>" Month</strong></td>
                              </tr>
                            <tr>
                              <td>SELF</td>
                              <td>GROUP</td>
                              <td>TOTAL</td>
                              </tr>
                          </table></td>
                        </tr>
                        <?php 
					if($PageVal['TotalRecords'] > 0){
					$CurrLvl = $PreLvl = 0;
					$Ctrl = $PageVal['RecordStart'];
					
					foreach($PageVal['ResultSet'] as $AR_Fld):
					$Ctrl++;
					
					$AR_IMG = $model->getCurrentImg($AR_Fld['member_id']);
					$AR_SELF = $model->getSumSelfCollection($AR_Fld['member_id'],$from_date,$to_date);
					$AR_GROUP = $model->getSumGroupCollection($AR_Fld['member_id'],$AR_Fld['nleft'],$AR_Fld['nright'],$from_date,$to_date);
					
					$TILL_SELF = $model->getSumSelfCollection($AR_Fld['member_id'],$first_date,$to_date);
					$TILL_GROUP = $model->getSumGroupCollection($AR_Fld['member_id'],$AR_Fld['nleft'],$AR_Fld['nright'],$first_date,$to_date);
					
					#$NEW_RANK = $model->getUpgradeRank($AR_Fld['rank_id'],$TILL_SELF['total_pv']+$TILL_GROUP['total_pv']);
					
					
					$CurrLvl = $AR_Fld['nlevel'];
						if(($CurrLvl != $PreLvl)){
						$LvlCtrl = $CurrLvl - $nlevel;
					?>
                        <tr bgcolor="#B8E6FE">
                          <td colspan="9" align="left" valign="middle" class="cmntext"><strong>Level <?php echo $LvlCtrl;?></strong></td>
                        </tr>
                        <?php 
					$PreLvl = $AR_Fld['nlevel'];
					}
					?>
                        <tr class="<?php echo ($AR_Fld['rank_id']==0)? "text-danger":""; ?>" onClick="window.location.href='?user_id=<?php echo $AR_Fld['user_id']?>&member_id=<?php echo _e($AR_Fld['member_id']);?>'" style="cursor:pointer">
                          <td align="center" valign="top" class="cmntext"><?php echo $Ctrl;?></td>
                          <td  align="" valign="" class=""><img src="<?php echo $AR_IMG['IMG_SRC'];?>" class="img-circle" width="30px" height="28px" /> <br>
                            </td>
                          <td align="left" valign="middle" class=""><?php echo $AR_Fld['full_name'];?></td>
                          <td align="left" valign="middle" class="<?php echo $AR_IMG['CssCls'];?>"><?php echo strtoupper($AR_Fld['user_id']);?></td>
                          <td align="left" class=""><?php echo DisplayDate($AR_Fld['date_join']);?></td>
                          <td align="left"  class=""><?php echo $AR_Fld['city_name'];?></td>
                          <td align="left"  class=""><?php echo ($AR_Fld['rank_name'])? $AR_Fld['rank_name']:"N/A"; ?></td>
                          <td align="left"  class=""><table width="100%" border="0">
                            
                            <tr>
                              <td>BV: <?php echo number_format($AR_SELF['total_pv']); ?></td>
                              <td>BV: <?php echo number_format($AR_GROUP['total_pv']); ?></td>
                              <td>BV: <?php echo number_format($AR_SELF['total_pv']+$AR_GROUP['total_pv']); ?></td>
                            </tr>
                          </table></td>
                          <td align="left"  class=""><table width="100%" border="0">
                            <tr>
                              <td>BV: <?php echo number_format($TILL_SELF['total_pv']); ?></td>
                              <td>BV: <?php echo number_format($TILL_GROUP['total_pv']); ?></td>
                              <td>BV: <?php echo number_format($TILL_SELF['total_pv']+$TILL_GROUP['total_pv']); ?></td>
                              </tr>
                          </table></td>
                        </tr>
                        <?php endforeach; ?>
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM'
		});
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
