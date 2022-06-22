<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['user_name']!=''){
	$user_name = FCrtRplc($_REQUEST['user_name']);
	$StrWhr .=" AND ( tm.user_name LIKE '%$user_name%' )";
	$SrchQ .="&user_name=$user_name";
}
if($_REQUEST['process_id']!=''){
	$process_id = FCrtRplc($_REQUEST['process_id']);
	$StrWhr .=" AND tcms.process_id='$process_id'";
	$SrchQ .="&process_id=$process_id";
}

$QR_PAGES = "SELECT tcms.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id, tm.city_name, tm.state_name,
			 CONCAT_WS('',tm.mobile_code,tm.member_mobile) AS mobile_number,   tr.mode_wise,
		  	 tr.rank_name, tm.bank_name, tm.branch, tm.account_number, tm.ifc_code, tmns.neft_sts,  tp.start_date, tp.end_date
		  	 FROM tbl_cmsn_mstr_sum AS tcms
		  	 LEFT JOIN tbl_members AS tm ON tcms.member_id = tm.member_id
		  	 LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tcms.rank_id
		  	 LEFT JOIN tbl_mem_neft_sts AS tmns ON tmns.member_id=tm.member_id
			 LEFT JOIN tbl_process AS tp ON tp.process_id=tcms.process_id
		  	 WHERE 1 $StrWhr 
		  	 GROUP BY tcms.member_id,  tcms.process_id 
		  	 ORDER BY tcms.process_id DESC, tcms.member_id ASC";
$PageVal = DisplayPages($QR_PAGES, 250, $Page, $SrchQ);	

$QR_EXPORT = "SELECT tcms.*,
		  	  CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id, tm.city_name, tm.state_name,
			  CONCAT_WS('',tm.mobile_code,tm.member_mobile) AS mobile_number,  
			  tm.pan_no AS pan_card, tr.rank_name, tr.mode_wise,
			  tm.bank_name, tm.branch, CONCAT_WS('A/C- ','',tm.account_number) AS  account_number, tm.ifc_code, 
			  tmns.neft_sts,  tp.start_date, tp.end_date
		  	  FROM tbl_cmsn_mstr_sum AS tcms 
			  LEFT JOIN tbl_members AS tm ON tcms.member_id = tm.member_id
			  LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tcms.rank_id
			  LEFT JOIN tbl_mem_neft_sts AS tmns  ON tmns.member_id=tm.member_id
			  LEFT JOIN tbl_process AS tp ON tp.process_id=tcms.process_id
			  WHERE 1 $StrWhr 
			  GROUP BY tcms.member_id,  tcms.process_id
			  ORDER BY tcms.process_id DESC, tcms.member_id ASC";
ExportQuery($QR_EXPORT);

$tds = $model->getValue("CONFIG_TDS");
$processing = $model->getValue("CONFIG_PROCESSING");
$charity_charge = $model->getValue("CONFIG_FOUNDATION");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title><?php echo title_name(); ?></title>
<meta name="description" content="Static &amp; Dynamic Tables" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/fonts.googleapis.com.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-skins.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-rtl.min.css" />
<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>
<body class="no-skin">
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> CMSN Master <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Statement </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a  href="<?php echo generateSeoUrlAdmin("excel","cmsnstatement",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <div class="clearfix">
                  <div class="col-md-6">
                    <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("bonus","cmsnstatement",""); ?>">
                      <b>User Id </b>
                      <div class="form-group">
                        <div class="clearfix">
                          <input id="form-field-1" placeholder="User Name" name="user_name"  class="col-xs-12 col-sm-6 validate[required]" type="text" value="<?php echo $_REQUEST['user_name']; ?>">
                        </div>
                      </div>
                      <b>Process No </b>
                      <div class="form-group">
                        <div class="clearfix">
                          <select class="col-xs-12 col-sm-6 validate[required]" id="process_id" name="process_id" >
                            <option value="">----select process no----</option>
                            <?php  DisplayCombo($_REQUEST['process_id'],"PROCESS_ALL");  ?>
                          </select>
                        </div>
                      </div>
                      <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                      <a href="<?php echo generateSeoUrlAdmin("bonus","cmsnstatement",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
                    </form>
                  </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <table  id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                  <thead>
                    <tr role="row">
                      <th  class="">SRL NO</th>
                      <th  class="">MONTH' <small>YEAR</small> </th>
                      <th  class="">User Id </th>
                      <th  class="">User Name </th>
                      <th  class="">Rank</th>
                      <th  class="">RANK DIFF. INCOME </th>
                      <th  class="">SR. RANK ADDL BONUS</th>
                      <th  class=""> LEADERSHIP BONUS</th>
                      <th  class="">BIKE BUDGET</th>
                      <th  class="">CAR BUDGET </th>
                      <th  class="">HOUSE BUDGET </th>
                      <th  class="">TOTAL</th>
                      <th  class="">&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
									if($PageVal['TotalRecords'] > 0){
									$Ctrl=$PageVal['RecordStart']+1;
									foreach($PageVal['ResultSet'] as $AR_DT):
									
										$tds_charge = ( $AR_DT['tds'] / $AR_DT['total_cmsn'] * 100 );
										$cadre_diffrential = $AR_DT['cadre_diffrential'];
										$sr_additional = $AR_DT['sr_additional'];
										$sr_leadership = $model->getLeadershipBonus($AR_DT['member_id'],$AR_DT['process_id']);
										$bike_budget = $model->getSumBudgetPoint($AR_DT['member_id'],$AR_DT['process_id'],"BB");
										$car_budget = $model->getCarBudgetTotalPoint($AR_DT['member_id'],$AR_DT['process_id']);
										$house_budget = $model->getSumBudgetPoint($AR_DT['member_id'],$AR_DT['process_id'],"HB");
										$cmsn_other = $model->getCmsnOther($AR_DT['member_id'],$AR_DT['process_id']);
										$royalty_cmsn =  $model->getRoyaltyCommission($AR_DT['member_id'],$AR_DT['start_date'],$AR_DT['end_date']);
										#$special_cmsn = $model->getSpecialCommission($AR_DT['member_id'],$AR_DT['start_date'],$AR_DT['end_date']);
										$total_cmsn = $cadre_diffrential + $sr_additional + $sr_leadership + $bike_budget + $car_budget 
										+ $house_budget + $cmsn_other + $royalty_cmsn + $special_cmsn;
										
										$accumulated_leadership =  $sr_leadership;
										$accumultaed_car_budget = $car_budget;
										$accumulated_housebudget = $house_budget;
										$total_accumulated = $accumulated_leadership + $accumultaed_car_budget + $accumulated_housebudget;
										
										$total_gross_cmsn = ( $total_cmsn - $total_accumulated );
										$add_bf_amount_till  = $model->getCarryForwardPayout($AR_DT['member_id'],$AR_DT['process_id']);
										$add_leadership_bonus_modewise = $model->getSeniorModeWise($AR_DT['member_id'],$AR_DT['mode_wise'],$AR_DT['start_date'],$AR_DT['end_date']);
										
										$net_total =  $total_gross_cmsn + $add_bf_amount_till + $add_leadership_bonus_modewise;
										$carry_forward = $model->setCarryForwardPayout($net_total,$AR_DT['neft_sts'],$tds_charge);
										$calc_total = ($carry_forward==0)? $net_total:0;
										$tds  = $calc_total * $tds_charge / 100;
										$processing = $calc_total * 3 / 100;
										$charity = $calc_total * 3 / 100;
										$net_payable = $calc_total - ($tds + $processing + $charity);
										$net_accumulated = $total_accumulated;

								?>
                    <tr class="odd" role="row">
                      <td class=""><?php echo $Ctrl; ?></td>
                      <td class=""><?php echo getDateFormat($AR_DT['start_date'],"M' Y"); ?></td>
                      <td ><?php echo $AR_DT['user_id']; ?></td>
                      <td ><?php echo $AR_DT['full_name']; ?></td>
                      <td ><?php echo $AR_DT['rank_name']; ?></td>
                      <td><?php echo number_format($AR_DT['cadre_diffrential'],2); ?></td>
                      <td><?php echo number_format($AR_DT['sr_additional'],2); ?></td>
                      <td><?php echo number_format($sr_leadership,2); ?></td>
                      <td><?php echo number_format($bike_budget,2); ?></td>
                      <td><?php echo number_format($car_budget,2); ?></td>
                      <td><?php echo number_format($house_budget,2); ?></td>
                      <td><?php echo number_format($cadre_diffrential+$sr_additional+$sr_leadership+$bike_budget+$car_budget+$house_budget,2); ?></td>
                      <td><a href="javascript:void(0)">View</a></td>
                    </tr>
                    <?php $Ctrl++;
							   endforeach;
									}else{
									?>
                    <tr class="odd" role="row">
                      <td colspan="13">No record found</td>
                    </tr>
                    <?php 
									}
								 ?>
                  </tbody>
                </table>
                <div class="clearfix">&nbsp;</div>
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
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		});
		
		
	});
</script>
</body>
</html>
