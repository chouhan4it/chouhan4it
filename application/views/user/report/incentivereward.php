<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = InsertDate(getLocalTime());
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = _d(FCrtRplc($_REQUEST['member_id']));
if($member_id == ""){$member_id = $this->session->userdata('mem_id');}

$first_date = InsertDate($model->getStartOrderDate());
$today_date = InsertDate(getLocalTime());

if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$Q_GEN =  "SELECT member_id, nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='$member_id'";
$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
$nlevel = $AR_GEN['nlevel'];
$nleft = $AR_GEN['nleft'];
$nright = $AR_GEN['nright'];

$SrchQ .= "&member_id="._e($member_id)."";

$QR_MEM = "SELECT tm.member_id, tm.user_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name,
			 CONCAT_WS(' ',tmsp.first_name,tmsp.last_name) AS spsr_full_name, tmsp.user_id AS spsr_user_id ,
			 tm.city_name, tm.state_name, tm.member_mobile, tm.date_join,
			 tree.nlevel, tree.nleft, tree.nright, tm.date_join, tr.rank_name
			 FROM tbl_members AS tm	
			 LEFT JOIN tbl_members AS tmsp  ON tmsp.member_id=tm.sponsor_id
			 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
			 LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
			 WHERE   tree.nleft BETWEEN '$nleft' AND '$nright' 
			 $StrWhr ORDER BY tree.nlevel ASC";
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
        <h4 class="page-title">Incentive Reward</h4>
        <p class="text-muted page-title-alt">Your group incentive reward status</p>
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
                <div class="dataTables_wrapper form-inline" id="">
                  <div class="row">
                    <div class="col-md-12">
                      <form id="form-search" name="form-search" method="get" action="<?php echo generateMemberForm("report","incentivereward",""); ?>">
                        <b>User Id </b>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                          <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id"  />
                          <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                        <a href="<?php echo generateMemberForm("report","incentivereward",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a> <a href="<?php echo generateSeoUrlMember("download","incentivereward",""); ?>"  class="btn btn-success m-t-n-xs" value=" Excel "> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                      </form>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
            <tr class="">
              <td align="left"><strong>Srl No </strong></td>
              <td align="left"><strong>Name</strong></td>
              <td align="left"><strong>User Id </strong></td>
              <td align="left"><strong>Register Date </strong></td>
              <td align="left"><strong>Qualify Status </strong></td>
              <td align="left"><strong>Qualification Start Date </strong></td>
              <td  align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td colspan="5" align="center"><strong> Android Mobile </strong></td>
                  </tr>
                  <tr>
                    <td align="center">COMPLETED</td>
                    <td align="center">PENDING</td>
                    <td align="center">DAYS LEFT</td>
                    <td align="center">END DATE </td>
                    <td align="center">STATUS</td>
                  </tr>
                </table>
			  </td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                <tr>
                  <td colspan="5" align="center"><strong> Bangkok / Pattaya Trip </strong></td>
                </tr>
                <tr>
                  <td align="center">COMPLETED</td>
                  <td align="center">PENDING</td>
                  <td align="center">DAYS LEFT</td>
                  <td align="center">END DATE </td>
                  <td align="center">STATUS</td>
                </tr>
              </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                <tr>
                  <td colspan="5" align="center"><strong> Alto Car </strong></td>
                </tr>
                <tr>
                  <td align="center">COMPLETED</td>
                  <td align="center">PENDING</td>
                  <td align="center">DAYS LEFT</td>
                  <td align="center">END DATE </td>
                  <td align="center">STATUS</td>
                </tr>
              </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                <tr>
                  <td colspan="5" align="center"><strong> Duster Car </strong></td>
                </tr>
                <tr>
                  <td align="center">COMPLETED</td>
                  <td align="center">PENDING</td>
                  <td align="center">DAYS LEFT</td>
                  <td align="center">END DATE </td>
                  <td align="center">STATUS</td>
                </tr>
              </table></td>
            </tr>
            <?php 
			if($PageVal['TotalRecords'] > 0){
			$Ctrl = $PageVal['RecordStart']+1;
			
			$android_initial = $model->getRewardtarget(1);
			$bangkok_initial =   $model->getRewardtarget(2);
			$alto_initial =   $model->getRewardtarget(3);
			$duster_initial =   $model->getRewardtarget(4);
			
			$year_end = InsertDate(date("Y")."-12-31");
			foreach($PageVal['ResultSet'] as $AR_DT):
			
			$member_id = FCrtRplc($AR_DT['member_id']);
			
			$reward_ctrl = $model->checkRewardCriteria($member_id);
			
			$date_join = InsertDate($AR_DT['date_join']);
			$date_manual = "2017-05-28";
			if(strtotime( $date_join ) <= strtotime( $date_manual )){
				$date_join = $date_manual;
			}
			$android_end = InsertDate(AddToDate($date_join,"+ 1 Month"));
			$date_android_end = (strtotime($android_end) >= strtotime($year_end))? $year_end:$android_end;
			
			$bangkok_end = InsertDate(AddToDate($date_join,"+ 2 Month"));
			$date_bangkok_end = (strtotime($bangkok_end) >= strtotime($year_end))? $year_end:$bangkok_end;
			
			$alto_end = InsertDate(AddToDate($date_join,"+ 4 Month"));
			$date_alto_end = (strtotime($alto_end) >= strtotime($year_end))? $year_end:$alto_end;
			
			$duster_end = InsertDate(AddToDate($date_join,"+ 6 Month"));
			$date_duster_end = (strtotime($duster_end) >= strtotime($year_end))? $year_end:$duster_end;
			
			$day_android_diff = dayDiff($today_date,$date_android_end);
			$day_bangkok_diff = dayDiff($today_date,$date_bangkok_end);
			$day_alto_diff = dayDiff($today_date,$date_alto_end);
			$day_duster_diff = dayDiff($today_date,$date_duster_end);
			
			
			
			$android_target += $android_initial;
			$bangkok_target += $bangkok_initial;
			$alto_target += $alto_initial;
			$duster_target += $duster_initial;
			
			
			$android_achive = $model->getRewardAchive($member_id,$android_target,$date_join,$date_android_end);
			$android_complete = $android_achive;
			$android_value = ($android_complete>0)? $android_complete:0;
			$android_pending  = ( ($android_target-$android_value)>0 )? $android_target-$android_value:0;
			$android_expiry = (strtotime($date_android_end)>strtotime($today_date))? "RUNNING":"NOTACHIEVED";
			$android_sts = ($android_pending==0)? "ACHIEVED":$android_expiry;
			
			$bangkok_target += ($android_pending==0)? ($android_target):0;
			
			$bangkok_achive = $model->getRewardAchive($member_id,$bangkok_target,$date_join,$date_bangkok_end);
			$android_carry = (strtotime($date_android_end)>strtotime($today_date))? 0:$bangkok_achive;
			$bangkok_complete = ($android_pending<=0)? ($bangkok_achive-$android_target):$android_carry;
			$bangkok_value = ($bangkok_complete>0)? $bangkok_complete:0;
			$bangkok_pending  = ( ($bangkok_initial-$bangkok_value)>0 )? $bangkok_initial-$bangkok_value:0;
			$bangkok_expiry = (strtotime($date_bangkok_end)>strtotime($today_date))? "RUNNING":"NOTACHIEVED";
			$bangkok_sts = ($bangkok_pending==0)? "ACHIEVED":$bangkok_expiry;
			
			$alto_target += ($bangkok_pending==0)? ($bangkok_target):0;
			
			$alto_achive = $model->getRewardAchive($member_id,$alto_target,$date_join,$date_alto_end);
			$bangkok_carry = (strtotime($date_bangkok_end)>strtotime($today_date))? 0:$alto_achive;
			$alto_complete = ($bangkok_pending<=0)? $alto_achive-($android_target+$bangkok_target):$bangkok_carry;
			$alto_value = ($alto_complete>0)? $alto_complete:0;
			$alto_pending  = ( ($alto_initial-$alto_value)>0 )? $alto_initial-$alto_value:0;
			$alto_expiry = (strtotime($date_alto_end)>strtotime($today_date))? "RUNNING":"NOTACHIEVED";
			$alto_sts = ($alto_pending==0)? "ACHIEVED":$alto_expiry;
			
			$duster_target +=  ($alto_pending==0)? ($alto_target):0;
			
			$duster_achive = $model->getRewardAchive($member_id,$duster_target,$date_join,$date_duster_end);
			$alto_carry = (strtotime($date_alto_end)>strtotime($today_date))? 0:$duster_achive;
			$duster_complete = ($alto_pending<=0)? $duster_achive-($android_target+$bangkok_target+$alto_target):$alto_carry;
			$duster_value = ($duster_complete>0)? $duster_complete:0;
			$duster_pending  = ( ($duster_initial-$duster_value)>0 )? $duster_initial-$duster_value:0;
			$duster_expiry = (strtotime($date_duster_end)>strtotime($today_date))? "RUNNING":"NOTACHIEVED";
			$duster_sts = ($duster_pending==0)? "ACHIEVED":$duster_expiry;
			
			?>
            <tr>
              <td><?php echo $Ctrl;?></td>
              <td align="left"><?php echo strtoupper($AR_DT['full_name']);?></td>
              <td align="left"><?php echo strtoupper($AR_DT['user_id']);?></td>
              <td align="left"><?php echo DisplayDate($AR_DT['date_join']); ?></td>
              <td align="left"><?php echo ($reward_ctrl>0)? "Yes":"No"; ?></td>
              <td align="left"><?php echo  DisplayDate($date_join); ?></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td><?php echo OneDcmlPoint(($android_complete>0)? $android_complete:0); ?></td>
                    <td><?php echo OneDcmlPoint($android_pending); ?></td>
                    <td><?php echo $day_android_diff; ?></td>
                    <td><?php echo DisplayDate($date_android_end); ?></td>
                    <td><?php echo $android_sts; ?></td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                <tr>
                  <td><?php echo OneDcmlPoint(($bangkok_complete>0)? $bangkok_complete:0); ?></td>
                  <td><?php echo OneDcmlPoint($bangkok_pending); ?></td>
                  <td><?php echo $day_bangkok_diff; ?></td>
                  <td><?php echo DisplayDate($date_bangkok_end); ?></td>
                  <td><?php echo $bangkok_sts; ?></td>
                </tr>
              </table></td>
              <td align="left"><table width="100%"  border="1" style="border-collapse:collapse;">
                <tr>
                  <td><?php echo OneDcmlPoint(($alto_complete>0 && $bangkok_pending==0)? $alto_complete:0); ?></td>
                  <td><?php echo OneDcmlPoint($alto_pending); ?></td>
                  <td><?php echo $day_alto_diff; ?></td>
                  <td><?php echo DisplayDate($date_alto_end); ?></td>
                  <td><?php echo $alto_sts; ?></td>
                </tr>
              </table></td>
              <td align="left"><table width="100%"  border="1" style="border-collapse:collapse;">
                <tr>
                  <td><?php echo OneDcmlPoint(($duster_complete>0 && $alto_pending==0)? $duster_complete:0); ?></td>
                  <td><?php echo OneDcmlPoint($duster_pending); ?></td>
                  <td><?php echo $day_duster_diff; ?></td>
                  <td><?php echo DisplayDate($date_duster_end); ?></td>
                  <td><?php echo $duster_sts; ?></td>
                </tr>
              </table></td>
            </tr>
            <?php
			$Ctrl++;
			unset($android_carry,$bangkok_carry,$alto_carry);
			unset($android_target,$bangkok_target,$alto_target,$duster_target,$android_achive,$bangkok_achive,$alto_achive,$duster_achive);
			unset($android_complete,$android_pending,$day_android_diff,$android_sts,$bangkok_complete,$bangkok_pending,$day_bangkok_diff,$bangkok_sts);
			unset($alto_complete,$alto_pending,$day_alto_diff,$alto_sts,$duster_complete,$duster_pending,$day_duster_diff,$duster_sts);
			endforeach;
			 }else{?>
            <tr>
              <td colspan="10" align="center" class="errMsg">No record found</td>
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
<div class="modal" id="modal-direct-detail"  aria-hidden="true">
  <div class="modal-dialog" style="width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"> Collection</h4>
      </div>
      <div class="modal-body">
        <div class="login-box">
          <div id="row">
            <div class="input-box frontForms">
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div class="load-direct"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<?php jquery_validation(); auto_complete(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		
		$(".modal-direct").on('click',function(){
			var member_id = $(this).attr("member_id");
			var from_date = $(this).attr("from_date");
			var to_date = $(this).attr("to_date");
			var order_by = $(this).attr("order_by");
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			var data = {
				switch_type:"DIRECT_COLLECTION",
				member_id : member_id,
				from_date : from_date,
				to_date : to_date,
				order_by : order_by
			}
			$.post(URL_GET,data,function(JsonEval){
				$(".load-direct").html(JsonEval);
				$("#modal-direct-detail").modal({
					backdrop: 'static',
					keyboard: false
				});
			});
			
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