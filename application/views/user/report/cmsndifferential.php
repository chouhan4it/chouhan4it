<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

$member_id = $this->session->userdata('mem_id');

if($_REQUEST['process_id']>0){
	$process_id = FCrtRplc($_REQUEST['process_id']);
	$SrtWhr .=" AND tcm.process_id='".$process_id."'";
	$SrchQ .="&process_id=$process_id";
}

$QR_PAGES="SELECT tcm.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id,  tr.rank_name, tsm.tds as lesstds 
		   FROM tbl_cmsn_mstr AS tcm 
		  LEFT JOIN tbl_members AS tm ON tcm.member_id = tm.member_id
		  LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
		  LEFT JOIN tbl_process AS tp  ON tp.process_id=tcm.process_id
		  LEFT JOIN tbl_cmsn_mstr_sum AS tsm ON tsm.member_id=tcm.member_id AND tsm.process_id=tcm.process_id
		  WHERE tcm.member_id='".$member_id."' AND tp.process_sts='Y'  $SrtWhr ORDER BY tcm.master_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);		

$tds = $model->getValue("CONFIG_TDS");
$processing = $model->getValue("CONFIG_PROCESSING");
$charity_charge = $model->getValue("CONFIG_FOUNDATION");
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
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
        <h4 class="page-title">Repurchase Bonus</h4>
        <p class="text-muted page-title-alt">Your  Repurchase Bonus</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <?php echo get_message(); ?>
      <div class="col-lg-12">
        <div class="portlet">
          <!-- /primary heading -->
          <div class="portlet-body" >
            <div class="row">
              <div class="col-xs-12">
                <div class="box-body">
                  <div class="box-wrap clear"> <br>
				  	<div class="row">
						
						<div class="col-md-4">
						  <form id="form-search" name="form-search" method="get" action="<?php echo generateMemberForm("report","cmsndifferential",""); ?>">
							<b>Cycle No :</b>
							<div class="form-group">
								<select name="process_id" id="process_id" class="form-control">
									<option value="">-----select-----</option>
									<?php echo DisplayCombo($_REQUEST['process_id'],"PROCESS"); ?>
								</select>
							 </div>
							<br>
							<input class="btn btn-sm btn-primary m-t-n-xs" value=" Search " type="submit">
							<a href="<?php  echo generateSeoUrlMember("report","cmsndifferential",array()); ?>" class="btn btn-sm btn-default m-t-n-xs" value=" Reset ">Reset</a>
						  </form>
						</div>
					 </div>
                    <br>
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="wallet_deposit_wrapper">
                      <div class="row">
                        <div class="col-sm-12">
                          <table aria-describedby="wallet_deposit_info" role="grid" id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                              <tr role="row">
                                <th class="">Cycle No</th>
                                <th class="">Self BV</th>
                                <th class="">Group BV</th>
                                <th class="">Total BV</th>
                                <th class="">Gros Commission</th>
                                <th class="">Tds @ <?php echo $tds; ?> %</th>
                                <th class="">Processing @ <?php echo $processing; ?> %</th>
                                <th class="">Social Charity @ <?php echo $charity_charge; ?>%</th>
                                <th class="">Net Commission</th>
                                <th class="">&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
									if($PageVal['TotalRecords'] > 0){
									$Ctrl=1;
									foreach($PageVal['ResultSet'] as $AR_DT):
									$total_pv = $AR_DT['self_pv']+$AR_DT['group_pv'];
									$total_bv = $AR_DT['self_bv']+$AR_DT['group_bv'];
								?>
                              <tr class="odd" role="row">
                                <td class="sorting_1"><?php echo $AR_DT['process_id'] ?></td>
                                <td ><?php echo OneDcmlPoint($AR_DT['self_pv']); ?></td>
                                <td><?php echo OneDcmlPoint($AR_DT['group_pv']); ?></td>
                                <td><?php echo OneDcmlPoint($total_pv); ?></td>
                                <td><?php echo OneDcmlPoint($AR_DT['total_bv']); ?></td>
                                <td><?php echo OneDcmlPoint($AR_DT['tds']); ?></td>
                                <td><?php echo OneDcmlPoint($AR_DT['processing']); ?></td>
                                <td><?php echo OneDcmlPoint($AR_DT['charity_charge']); ?></td>
                                <td><?php echo OneDcmlPoint($AR_DT['net_total_bv']); ?></td>
                                <td align="center"><a class="modal-diffrential" master_id="<?php echo $AR_DT['master_id']; ?>" href="javascript:void(0)">View</a></td>
                              </tr>
                              <?php endforeach;
									}else{
									?>
                              <tr class="odd" role="row">
                                <td colspan="3">No record found</td>
                              </tr>
                              <?php 
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
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<div class="modal" id="modal-diffrential-detail"  aria-hidden="true">
  <div class="modal-dialog" style="width:800px;">
    <div class="modal-content">
      <div class="modal-header">
	  	 <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
         <h4 class="modal-title">Cadres Differential Income</h4>
      </div>
      <div class="modal-body" >
        <div class="login-box" >
          <div id="row">
            <div class="input-box frontForms">
              <div class="row">
                <div class="col-md-12 col-xs-12">
					<div class="load-diffrential"></div>
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
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$(".modal-diffrential").on('click',function(){
			var master_id = $(this).attr("master_id");
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			$.post(URL_GET,{switch_type:"DIFFRENTIAL",master_id:master_id},function(JsonEval){
				$(".load-diffrential").html(JsonEval);
				$("#modal-diffrential-detail").modal({
					backdrop: 'static',
					keyboard: false
				});
			});
			
		});
	});
</script>
</html>
