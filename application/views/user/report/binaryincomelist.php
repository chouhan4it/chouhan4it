<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['process_id']>0){
	$process_id  = $_REQUEST['process_id'];
	$StrWhr .=" AND tcb.process_id='".$process_id."'";
	$SrchQ .="&process_id=$process_id";
}else{ set_message("warning","unable to load binary detail"); redirect_page("bonus","binaryincome",""); }

$QR_PAGES="SELECT tcb.*, tm.user_id, tpt.prod_pv, tpt.pin_name , tp.start_date, tp.end_date
		 FROM tbl_cmsn_binary AS tcb 
		 LEFT JOIN tbl_process AS tp ON tp.process_id=tcb.process_id
		 LEFT JOIN tbl_members AS tm ON tm.member_id=tcb.member_id
		 LEFT JOIN tbl_pintype AS tpt ON tpt.type_id=tm.type_id
		 WHERE 1 $StrWhr ORDER BY tcb.binary_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);	
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
        <h4 class="page-title">Matching Bonus Detail</h4>
        <p class="text-muted page-title-alt">Your Matching Bonus Detail</p>
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
                      <table id="no-more-tables"  class="table table-striped table-bordered table-hover">
                <thead>
                  <tr role="row">
                    <th  class="">Week Date </th>
                    <th  class="">Member</th>
                    <th  class="">Plan </th>
                    <th  class=""> Ceiling </th>
                    <th  class="">Left<br />
                      Collection </th>
                    <th  class="">Right <br />
                      Collection </th>
                    <th  class="">Matching</th>
                    <th class="">Left <br />
                      Carry Forward </span></th>
                    <th  class="">Right <br />
                      Carry Forward </th>
                    <th  class="">Binary </th>
                    <th  class="">Net Income </th>
                    <th  class="">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
					if($PageVal['TotalRecords'] > 0){
					$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						$package_ceiling = ($AR_DT['prod_pv']*7);
					    $ceiling_max_limit = 35000;
						$binary_ceiling = ($package_ceiling<=$ceiling_max_limit)? $package_ceiling:$ceiling_max_limit;
					?>
                  <tr class="odd" role="row">
                    <td  data-title="Week Date"><?php echo DisplayDate($AR_DT['start_date']); ?> - To - <?php echo DisplayDate($AR_DT['end_date']); ?></td>
                    <td data-title="Member"><?php echo $AR_DT['user_id']; ?></td>
                    <td data-title="Plan"><?php echo $AR_DT['pin_name']; ?></td>
                    <td data-title="Ceiling"><?php echo $binary_ceiling; ?></td>
                    <td data-title="Left Collection"><?php echo $AR_DT['newLft']; ?></td>
                    <td data-title="Right Collection"><?php echo $AR_DT['newRgt']; ?></td>
                    <td data-title="Matching"><?php echo $AR_DT['pair_match']; ?></td>
                    <td data-title="Left Carry Forward "><?php echo $AR_DT['leftCrf']; ?></td>
                    <td data-title="Right Carry Forward "><?php echo $AR_DT['rightCrf']; ?></td>
                    <td data-title="Binary "><?php echo $AR_DT['binary_rate']; ?></td>
                    <td data-title="Net Income"><?php echo $AR_DT['amount']; ?></td>
                    <td data-title="Net Income"><?php echo $AR_DT['binary_narration']; ?></td>
                  </tr>
                  <?php 
						endforeach;
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
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
