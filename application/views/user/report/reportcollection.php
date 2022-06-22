<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = InsertDate(getLocalTime());
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = _d(FCrtRplc($_REQUEST['member_id']));
if($member_id == ""){$member_id = $this->session->userdata('mem_id');}
$from_date = InsertDate($today_date);
if($_REQUEST['d_month']>0){
	$d_month = FCrtRplc($_REQUEST['d_month']);
	$d_year = FCrtRplc($_REQUEST['d_year']);
	$d_year = ($d_year>0)? $d_year:date("Y");
	$start_date = $d_year."-".$d_month."-01";
	$PERIOD = getMonthDates($start_date);
	$from_date = $PERIOD['flddFDate'];
	$to_date = $PERIOD['flddTDate'];
	$SrchQ .="&d_month=$d_month";
}
if($_REQUEST['d_month']==''){
	$d_month = date("m");
	$d_year = FCrtRplc($_REQUEST['d_year']);
	$d_year = ($d_year>0)? $d_year:date("Y");
	$start_date = $d_year."-".$d_month."-01"; 
	$PERIOD = getMonthDates($start_date);
	$from_date = $PERIOD['flddFDate'];
	$to_date = $PERIOD['flddTDate'];
	$SrchQ .="&d_year=$d_year";
}
if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);
	$SrchQ .="&from_date=$from_date&to_date=$to_date";
}
$Q_GEN =  "SELECT nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='".$member_id."'";
$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
$nlevel = $AR_GEN['nlevel'];
$nleft = $AR_GEN['nleft'];
$nright = $AR_GEN['nright'];

$SrchQ = "&member_id=$member_id";
$order_by = "cadre";
$QR_MEM = "SELECT tm.member_id, tm.user_id, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name,
			 CONCAT_WS(' ',tmsp.first_name,tmsp.last_name) AS spsr_full_name, tmsp.user_id AS spsr_user_id ,
			 tm.city_name, tm.state_name, tm.member_mobile,
			 tree.nlevel, tree.nleft, tree.nright, tm.date_join, tr.rank_name
			 FROM tbl_members AS tm	
			 LEFT JOIN tbl_members AS tmsp  ON tmsp.member_id=tm.sponsor_id
			 LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
			 LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
			 WHERE   tree.nleft BETWEEN '$nleft' AND '$nright' $StrWhr ORDER BY tree.nlevel ASC";
$PageVal = DisplayPages($QR_MEM, 100, $Page, $SrchQ);
ExportQuery($QR_MEM,array("from_date"=>$from_date,"to_date"=>$to_date,"order_by"=>$order_by));
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
        <h4 class="page-title">BV/AP Report</h4>
        <p class="text-muted page-title-alt">Your Group BV/AP Details</p>
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
                      <form id="form-search" name="form-search" method="get" action="<?php echo generateMemberForm("report","reportcollection",""); ?>">
                        <b>User Id </b>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                          <input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id"  />
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
                        <b>Date </b>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                          <div class="col-md-6">
                            <div class="input-group">
                              <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" placeholder="Date From" type="text"  />
                              <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                          </div>
                          <div class="col-md-6">
                            <div class="input-group">
                              <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" placeholder="Date To" type="text"  />
                              <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                          </div>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <strong>Note</strong> : <small>Showing Order Sequence By Rank </small>
                        <div class="clearfix">&nbsp;</div>
                        <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                        <a href="<?php echo generateMemberForm("report","reportcollection",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a> <a href="<?php echo generateSeoUrlMember("download","reportcollection",""); ?>"  class="btn btn-success m-t-n-xs" value=" Excel "> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
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
              <td align="left"><strong>Rank</strong></td>
              <td  align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td colspan="3" align="center"><strong> PV </strong></td>
                  </tr>
                  <tr>
                    <td align="center">SELF</td>
                    <td align="center">GROUP</td>
                    <td align="center">TOTAL</td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td colspan="3" align="center"><strong> BV </strong></td>
                  </tr>
                  <tr>
                    <td align="center">SELF</td>
                    <td align="center">GROUP</td>
                    <td align="center">TOTAL</td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td colspan="3" align="center"><strong> AP </strong></td>
                  </tr>
                  <tr>
                    <td align="center">SELF</td>
                    <td align="center">GROUP</td>
                    <td align="center">TOTAL</td>
                  </tr>
                </table></td>
              <td align="left"><strong>City</strong></td>
              <td align="left"><strong>State</strong></td>
            </tr>
            <?php 
			if($PageVal['TotalRecords'] > 0){
			$Ctrl = $PageVal['RecordStart']+1;
			foreach($PageVal['ResultSet'] as $AR_Fld):
			
			
			$AR_SELF = $model->getSumSelfCollection($AR_Fld['member_id'],$date_from,$date_to);
			
			$AR_GROUP = $model->getSumGroupCollection($AR_Fld['member_id'],$AR_Fld['nleft'],$AR_Fld['nright'],$date_from,$date_to);

			?>
            <tr>
              <td><?php echo $Ctrl;?></td>
              <td align="left"><?php echo strtoupper($AR_Fld['full_name']);?></td>
              <td align="left"><?php echo strtoupper($AR_Fld['user_id']);?></td>
              <td align="left"><?php echo ($rank_name['rank_name'])? $rank_name['rank_name']:$AR_Fld['rank_name']; ?></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td><?php echo OneDcmlPoint($AR_SELF['total_pv']); ?></td>
                    <td><?php echo OneDcmlPoint($AR_GROUP['total_pv']); ?></td>
                    <td><?php echo OneDcmlPoint($AR_SELF['total_pv']+$AR_GROUP['total_pv']); ?></td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                  <tr>
                    <td><?php echo OneDcmlPoint($AR_SELF['total_bv']); ?></td>
                    <td><?php echo OneDcmlPoint($AR_GROUP['total_bv']); ?></td>
                    <td><?php echo OneDcmlPoint($AR_SELF['total_bv']+$AR_GROUP['total_bv']); ?></td>
                  </tr>
                </table></td>
              <td align="left"><table width="100%" border="1" style="border-collapse:collapse;">
                <tr>
                  <td><?php echo OneDcmlPoint($AR_SELF['total_rcp']); ?></td>
                  <td><?php echo OneDcmlPoint($AR_GROUP['total_rcp']); ?></td>
                  <td><?php echo OneDcmlPoint($AR_SELF['total_rcp']+$AR_GROUP['total_rcp']); ?></td>
                </tr>
              </table></td>
              <td align="left" valign="middle" class="cmntext"><?php echo ($AR_Fld['city_name']);?></td>
              <td align="left" valign="middle" class="cmntext"><?php echo ($AR_Fld['state_name']);?></td>
            </tr>
            <?php
			$Ctrl++;
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