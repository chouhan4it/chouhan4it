<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	
	$member_id = $this->session->userdata('mem_id');
	
	if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
		$from_date = InsertDate($_REQUEST['from_date']);
		$to_date = InsertDate($_REQUEST['to_date']);
		$StrWhr .=" AND DATE(tclbm.cmsn_date) BETWEEN '$from_date' AND '$to_date'";
		$SrchQ .="&from_date=$from_date&to_date=$to_date";
	}
	
	$QR_PAGES= "SELECT tclbm.* 
				FROM tbl_cmsn_lvl_benefit_mstr AS tclbm 
				WHERE tclbm.member_id='".$member_id."' 
				$StrWhr 
				ORDER BY tclbm.mstr_id DESC";
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
        <h4 class="page-title">Level Income</h4>
        <p class="text-muted page-title-alt">Your  Level Income</p>
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
                        <form id="form-search" name="form-search" method="get" action="<?php echo generateMemberForm("report","levelincome",""); ?>">
                          <b>Date :</b>
                          <div class="form-group">
                   	<div class="input-group">
                  <input class="form-control col-xs-3 col-sm-3  validate[required] date-picker" name="from_date" id="id-date-picker-1" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                  <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                  </div>
                  <div class="form-group">
                    	<div class="input-group">
                  <input class="form-control col-xs-3 col-sm-3  validate[required] date-picker" name="to_date" id="id-date-picker-1" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                  <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                  </div>
                          <br>
                          <input class="btn btn-sm btn-primary m-t-n-xs" value=" Search " type="submit">
                          <a href="<?php  echo generateSeoUrlMember("report","levelincome",array()); ?>" class="btn btn-sm btn-default m-t-n-xs" value=" Reset ">Reset</a>
                        </form>
                      </div>
                    </div>
                    <br>
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="wallet_deposit_wrapper">
                      <div class="row">
                        <div class="col-sm-12">
                          <table  id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                              <tr role="row">
                                <th  class="">Sr No. </th>
                                <th  class="">Income Date</th>
                                <th  class="">Level Income</th>
                                <th  class="">Tds Charge</th>
                                <th  class="">Admin Charge </th>
                                <th  class="">Net Income </th>
                                <th  class="">Detail</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
								if($PageVal['TotalRecords'] > 0){
								$Ctrl=$PageVal['RecordStart']+1;
									foreach($PageVal['ResultSet'] as $AR_DT):
								?>
                              <tr class="odd" role="row">
                                <td class=""><?php echo $Ctrl; ?></td>
                                <td class=""><?php echo DisplayDate($AR_DT['cmsn_date']); ?></td>
                                <td><?php echo CURRENCY; ?> &nbsp; <?php echo number_format($AR_DT['total_income'],2); ?></td>
                                <td><?php echo CURRENCY; ?> &nbsp; <?php echo number_format($AR_DT['tds_charge'],2); ?></td>
                                <td><?php echo CURRENCY; ?> &nbsp; <?php echo number_format($AR_DT['admin_charge'],2); ?></td>
                                <td><?php echo CURRENCY; ?> &nbsp; <?php echo number_format($AR_DT['net_income'],2); ?></td>
                                <td><a class="label label-info modal-level" 
                                            member_id="<?php echo $AR_DT['member_id']; ?>" cmsn_date="<?php echo $AR_DT['cmsn_date']; ?>"
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
         <h4 class="modal-title">Level Income Detail</h4>
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
<?php jquery_validation(); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
	});
</script>
<script type="text/javascript">
	$(function(){
		$(".modal-level").on('click',function(){
			var cmsn_date = $(this).attr("cmsn_date");
			var member_id = $(this).attr("member_id");
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			$.post(URL_GET,{switch_type:"LEVEL_INCOME",cmsn_date:cmsn_date,member_id:member_id},function(JsonEval){
				$(".load-level").html(JsonEval);
				$("#modal-level-detail").modal({
					backdrop: 'static',
					keyboard: false
				});
			});
			
		});
	});
</script>
</html>
