<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$today_date = InsertDate(getLocalTime());
	$yester_date = InsertDate(AddToDate($today_date,"-1 Day"));
	
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$member_id = $this->session->userdata('mem_id');

	if($_REQUEST['process_id']!=''){
		$process_id = FCrtRplc($_REQUEST['process_id']);
		$StrWhr .=" AND tcb.process_id='$process_id'";
		$SrchQ .="&process_id=$process_id";
	}

	$QR_PAGES= "SELECT tclbml.*, tmf.user_id, CONCAT_WS(' ',tmf.first_name,tmf.last_name) AS full_name
			FROM tbl_cmsn_lvl_benefit_mstr_lvl AS tclbml 
			LEFT JOIN tbl_members AS tmf ON tclbml.member_id=tmf.member_id
			WHERE  tclbml.process_id>0 AND tclbml.member_id='".$member_id."'
			 $StrWhr 
			ORDER BY tclbml.process_id DESC";
	$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
	
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
<style type="text/css">
.img-circle {
	border-radius: 50%;
}
.item-pic {
	width: 30px;
}
tr > td {
	font-size: 12px !important;
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
        <h4 class="page-title">Matching Sponsor Bonus</h4>
        <p class="text-muted page-title-alt">Your Matching Sponsor Bonus</p>
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
                      <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateMemberForm("report","levelincomebinary",""); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                          <label class="control-label" for="process_id">Cycle No: </label>
                          <div class="col-lg-12">
                            <div class="clearfix">
                              <select name="process_id" id="process_id" class="form-control">
                                <option value="">-----select-----</option>
                                <?php echo DisplayCombo($_REQUEST['process_id'],"BINARY_PROCESS"); ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <div class=" form-action">
                          <div class="col-lg-12">
                            <input name="input_request" type="submit" class="btn btn-success blue" id="input_request"  value="Search" />
                            <input name="Back" type="button" class="btn btn-danger red" id="Back" onClick="window.location.href='?'" value="Reset" />
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table  class="table mb-0">
                        <thead>
                          <tr role="row">
                            <th  class="sorting">Sr. No </th>
                            <th  class="sorting">Cycle No</th>
                            <th  class="sorting">Level  Income </th>
                            <th  class="sorting">Tds Charge</th>
                            <th  class="sorting">Admin Charge </th>
                            <th  class="sorting">Net Income</th>
                            <th  class="sorting">&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
						if($PageVal['TotalRecords'] > 0){
						$Ctrl=$PageVal['RecordStart']+1;
							foreach($PageVal['ResultSet'] as $AR_DT):
						?>
                          <tr class="odd" role="row">
                            <td><?php echo $Ctrl; ?></td>
                            <td>Cycle No <?php echo $AR_DT['process_id']; ?></td>
                            <td><?php echo $AR_DT['total_income'];?></td>
                            <td><?php echo $AR_DT['tds_charge'];?></td>
                            <td><?php echo $AR_DT['admin_charge']; ?></td>
                            <td><?php echo $AR_DT['net_income']; ?></td>
                            <td><a class="label label-info modal-level" 
                            member_id="<?php echo $AR_DT['member_id']; ?>" process_id="<?php echo $AR_DT['process_id']; ?>"
                            href="javascript:void(0)">View</a></td>
                          </tr>
                          <?php $Ctrl++; endforeach;
						}
						 ?>
                        </tbody>
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
<div class="modal" id="modal-level-detail"  aria-hidden="true">
    <div class="modal-dialog" style="width:800px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">Sponsor Income Detail</h4>
        </div>
        <div class="modal-body" >
          <div class="login-box" >
            <div id="row">
              <div class="input-box frontForms">
                <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <div class="load-level"></div>
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
<?php jquery_validation(); auto_complete(); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		
		
	});
	$(function(){
		$(".modal-level").on('click',function(){
			var process_id = $(this).attr("process_id");
			var member_id = $(this).attr("member_id");
			
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			$.post(URL_GET,{switch_type:"SPONSOR_LEVEL_INCOME",process_id:process_id,member_id:member_id},function(JsonEval){
				$(".load-level").html(JsonEval);
				$("#modal-level-detail").modal({
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
