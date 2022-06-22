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

	$QR_PAGES="SELECT tcb.* ,   tp.start_date, tp.end_date
			   FROM tbl_cmsn_binary AS tcb 
			   LEFT JOIN tbl_members AS tm ON tm.member_id=tcb.member_id
			   LEFT JOIN tbl_process_binary AS tp ON tp.process_id=tcb.process_id
			   WHERE tcb.member_id='".$member_id."' 
			   $StrWhr 
			   ORDER BY tcb.binary_id DESC";
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
.item-pic{
	width:30px;
}
tr > td {
	font-size:12px !important;
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
        <h4 class="page-title">Matching Bonus</h4>
        <p class="text-muted page-title-alt">Your Matching Bonus</p>
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
                      <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateMemberForm("report","binaryincome",""); ?>" method="post" enctype="multipart/form-data">
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
                                            <th  class="">Process Week </th>
                                            <th  class="">Ceiling</th>
                                            <th  class="">Pre Left Carry </th>
                                            <th  class="">Pre Right Carry </th>
                                            <th  class="">Left<br />
                                              Collection </th>
                                            <th  class="">Right <br />
                                              Collection </th>
                                            <th  class="">Matching</th>
                                            <th class="">Left <br />
                                              Carry Forward </span></th>
                                            <th  class="">Right <br />
                                              Carry Forward </th>
                                            <th  class="">Rate</th>
                                            <th  class="">Net Income </th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php 
								if($PageVal['TotalRecords'] > 0){
								$Ctrl=1;
									foreach($PageVal['ResultSet'] as $AR_DT):
									$binary_ceiling = $AR_DT['binary_ceiling'];
									
								?>
                                          <tr class="odd" role="row">
                                            <td class="sorting_1"><?php echo DisplayDate($AR_DT['start_date'])." - To - ".DisplayDate($AR_DT['end_date']); ?></td>
                                            <td><?php echo $binary_ceiling; ?></td>
                                            <td><?php echo number_format($AR_DT['preLcrf']); ?></td>
                                            <td><?php echo number_format($AR_DT['preRcrf']); ?></td>
                                            <td><?php echo number_format($AR_DT['newLft']); ?></td>
                                            <td><?php echo number_format($AR_DT['newRgt']); ?></td>
                                            <td><?php echo number_format($AR_DT['pair_match']); ?></td>
                                            <td><?php echo number_format($AR_DT['leftCrf']); ?></td>
                                            <td><?php echo number_format($AR_DT['rightCrf']); ?></td>
                                            <td><?php echo number_format($AR_DT['binary_rate']); ?></td>
                                            <td><?php echo number_format($AR_DT['amount'],2); ?></td>
                                          </tr>
                                          <?php endforeach;
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
