<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(2);
$member_id = (_d($_REQUEST['member_id'])!='')? _d($_REQUEST['member_id']):_d($segment['member_id']);
$AR_MEM  = $model->getMember($member_id);

if($_REQUEST['wallet_id']!=''){
	$wallet_id = FCrtRplc($_REQUEST['wallet_id']);
	$StrWhr .=" AND twt.wallet_id='$wallet_id'";
	$SrchQ .="&wallet_id=$wallet_id";
}

if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
		$from_date = InsertDate($_REQUEST['from_date']);
		$to_date = InsertDate($_REQUEST['to_date']);
		$StrWhr .=" AND DATE(twt.date_time) BETWEEN '$from_date' AND '$to_date'";
		$SrchQ .="&from_date=$from_date&to_date=$to_date";
}

$QR_PAGES = "SELECT twt.* , tm.user_name AS user_id , CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name
			FROM tbl_wallet_trns AS twt 
			LEFT JOIN tbl_members AS tm ON tm.member_id=twt.member_id
			WHERE twt.wallet_trns_id>0 AND twt.member_id='".$member_id."' 
			$StrWhr 
			ORDER BY twt.wallet_trns_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);

$wallet_id = $model->getDefaultWallet();
$LDGR = $model->getCurrentBalance($AR_MEM['member_id'],$wallet_id,"","");

$QR_WALLET = "SELECT CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id, tm.city_name, tm.state_name,
	SUM(COALESCE(CASE WHEN trns_type = 'Dr' THEN trns_amount END,0)) debit_amount,
	SUM(COALESCE(CASE WHEN trns_type = 'Cr' THEN trns_amount END,0)) credit_amount,
	SUM(COALESCE(CASE WHEN trns_type = 'Cr' THEN trns_amount END,0))
	- SUM(COALESCE(CASE WHEN trns_type = 'Dr' THEN trns_amount END,0)) balance 
	FROM tbl_wallet_trns AS twt 
	LEFT JOIN tbl_members AS tm ON tm.member_id=twt.member_id
	WHERE  twt.wallet_trns_id>0 
	$StrWhr
	GROUP BY twt.member_id
	HAVING balance <> 0
	ORDER BY twt.member_id ASC";
ExportQuery($QR_WALLET);
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
<?php auto_complete(); ?>
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
          <h1> Financial <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;Member E-Wallet </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-md-12">
                <div class="portlet light bordered">
                  <div class="panel-body">
                    <div class="main pagesize"> 
                      <!-- *** mainpage layout *** -->
                      <div class="main-wrap">
                        <div class="content-box">
                          <div class="box-body">
                            <div class="box-wrap clear">
                              <h2>E-Wallet &nbsp;&nbsp;<?php echo ($AR_MEM['user_name']!='')? "[".$AR_MEM['user_name']."]":""; ?></h2>
                              <br>
                              <div class="actions">
                                <div class="row">
                                  <div class="col-md-3">
                                    <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("financial","memberwallet",array()); ?>">
                                      <b>User Id </b>
                                      <div class="">
                                        <input id="form-field-1" placeholder="User Id" name="user_id"  class="col-xs-12 col-sm-12" type="text" value="<?php echo $_REQUEST['user_id']; ?>">
                                        <input type="hidden" name="member_id" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>">
                                      </div>
                                      <b>Date from </b>
                                      <div class="input-group">
                                        <input class="form-control validate[required] date-picker col-md-3" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                                      <b>To</b>
                                      <div class="input-group">
                                        <input class="form-control  validate[required] date-picker" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                                        <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span> </div>
                                      <br>
                                      <input class="btn btn-sm btn-primary m-t-n-xs" value=" Search " type="submit">
                                      <input type="button" onClick="window.location.href='<?php echo generateSeoUrlAdmin("financial","memberwallet",""); ?>'" class="btn btn-sm btn-danger m-t-n-xs" value="Reset" name="reset_button">
                                      <input onClick="window.location.href='<?php echo generateSeoUrlAdmin("export","excel",""); ?>'" class="btn btn-sm btn-warning m-t-n-xs" value=" Download " type="button">
                                    </form>
                                  </div>
                                </div>
                              </div>
                              <br>
                              <h4>Current Balance: <strong><?php echo number_format($LDGR['net_balance'],2); ?></strong></h4>
                              <br>
                              <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="wallet_deposit_wrapper">
                                <div class="row">
                                  <div class="col-sm-12">
                                    <table aria-describedby="wallet_deposit_info" role="grid" id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                                      <thead>
                                        <tr role="row">
                                          <th  style="width: 180px;" rowspan="1" >Trns No </th>
                                          <th  style="width: 180px;" rowspan="1" >Type</th>
                                          <th  style="width: 180px;" colspan="1" rowspan="1" >Amount</th>
                                          <th style="width: 526px;" colspan="1" rowspan="1" class="">Description</th>
                                          <th style="width: 526px;" rowspan="1" class="">Date</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php 
								if($PageVal['TotalRecords'] > 0){
								$Ctrl=1;
									foreach($PageVal['ResultSet'] as $AR_DT):
								?>
                                        <tr class="odd" role="row">
                                          <td><?php echo $AR_DT['trans_no'] ?></td>
                                          <td><?php echo DisplayText("TRNS_".$AR_DT['trns_type']); ?></td>
                                          <td><?php echo $AR_DT['trns_amount']; ?></td>
                                          <td><?php echo $AR_DT['trns_remark']; ?></td>
                                          <td><span class="sorting_1"><?php echo getDateFormat($AR_DT['trns_date'],"d M Y h:i"); ?></span></td>
                                        </tr>
                                        <?php endforeach;
								}else{
								 ?>
                                        <tr class="text-danger">
                                          <td colspan="5">No transaction found </td>
                                        </tr>
                                        <?php } ?>
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
                          <!-- end of box-wrap --> 
                        </div>
                        <!-- end of box-body --> 
                      </div>
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
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</body>
</html>
