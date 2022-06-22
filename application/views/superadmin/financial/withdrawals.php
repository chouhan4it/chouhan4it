<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

	
$model = new OperationModel();
$form_data = $this->input->post();
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);
$action_request = ($form_data['action_request'])? $form_data['action_request']:$segment['action_request'];
$transfer_id = ($form_data['transfer_id'])? $form_data['transfer_id']:_d($segment['transfer_id']);
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}


if($_REQUEST['user_id']!=''){
	$member_id = $model->getMemberId($_REQUEST['user_id']);
	$StrWhr .=" AND tm.member_id='$member_id'";
	$SrchQ .="&user_id=$user_id";
}

if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND (DATE(tft.trns_date) BETWEEN '$date_from' AND '$date_to')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}
	
if($action_request!='')	{
	$trns_status = _d($segment['trns_status']);
	switch($action_request){
		case "STS":
			switch($trns_status):
				case "C":
					$AR_TRF = $model->getFundTransfer($transfer_id);
					$AR_MEM = $model->getMember($AR_TRF['to_member_id']);
					
					if($AR_TRF['trns_status']=='P'){
						if($AR_TRF['trns_amount']>0){
								$this->SqlModel->updateRecord("tbl_fund_transfer",array("trns_status"=>$trns_status,"status_up_date"=>$today_date),
								array("transfer_id"=>$transfer_id));
								set_message("success","Fund transfer successfull");		
								redirect_page("financial","withdrawals",array());
							}else{
								set_message("warning","Inavlid process, please check withdrawal request");		
								redirect_page("financial","withdrawals",array());
							}
					}else{
						set_message("warning","Unable to process your request");		
						redirect_page("financial","withdrawals",array());
					}
				break;
				case "R":
					$trns_date = InsertDate($today_date);
					$AR_TRF = $model->getFundTransfer($transfer_id);
					$AR_MEM = $model->getMember($AR_TRF['to_member_id']);
					if($AR_TRF['initial_amount']>0){
						if($AR_TRF['transfer_id']>0 && $AR_MEM['member_id']>0){
							$this->SqlModel->updateRecord("tbl_fund_transfer",array("trns_status"=>$trns_status,"status_up_date"=>$today_date),array("transfer_id"=>$transfer_id));
							$model->wallet_transaction($AR_TRF['wallet_id'],$AR_MEM['member_id'],"Cr",$AR_TRF['initial_amount'],"Withdrawal request rejected",$trns_date,$AR_TRF['trans_no'],array("trns_for"=>"REFUND"));							
							set_message("success","Fund transfer rejected succesfully");		
							redirect_page("financial","withdrawals",array());
						}else{
							set_message("warning","Tranaction failed , please try again");		
							redirect_page("financial","withdrawals",array());	
						}
					}else{
						set_message("warning","Unable to process your request");		
						redirect_page("financial","withdrawals",array());
					}
				break;
			endswitch;				
		break;
	}
}
$QR_PAGES="SELECT tft.*, tm.first_name, tm.last_name, tm.user_id FROM tbl_fund_transfer AS tft 
		   LEFT JOIN tbl_members AS tm ON tft.to_member_id=tm.member_id WHERE tft.trns_for LIKE 'WTD' $StrWhr ORDER BY tft.date_time DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
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
          <h1> Withdrawal <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;Requests </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12" >
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="smaller lighter blue no-margin">Search</h3>
                    </div>
                    <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("financial","withdrawals",""); ?>" method="post">
                      <div class="modal-body">
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> User Id	  :</label>
                          <div class="col-sm-7">
                            <input name="user_id" type="text" class="col-xs-12 col-sm-12" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id">
                            <input type="hidden" name="member_id" id="member_id">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Date From	  :</label>
                          <div class="col-sm-7">
                            <div class="input-group">
                  <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="Date From" type="text"  />
                  <span class="input-group-addon"> <i class="fa fa-calendar"></i></span></div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Date To	  :</label>
                          <div class="col-sm-7">
                            <div class="input-group">
                  <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                  <span class="input-group-addon"> <i class="fa fa-calendar"></i></span></div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
                        <button type="button" class="btn btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                      </div>
                    </form>
                  </div>
                  <!-- /.modal-content --> 
                </div>
                <br>
                <br>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div>
                  <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="center">Srl No</th>
                        <th  class="center">Date </th>
                        <th >User Id </th>
                        <th >Withdraw Amount </th>
                        <th >Withdraw Fee </th>
                        <th >Amount</th>
                        <th >Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr>
                        <td class="center"><?php echo $Ctrl; ?></td>
                        <td class="center"><?php echo $AR_DT['date_time']; ?></td>
                        <td><?php echo $AR_DT['first_name']; ?> &nbsp;<?php echo $AR_DT['last_name']; ?> &nbsp; [<?php echo $AR_DT['user_id']; ?>]</td>
                        <td><?php echo CURRENCY; ?> &nbsp;<?php echo ($AR_DT['initial_amount']); ?></td>
                        <td><?php echo CURRENCY; ?> &nbsp; <?php echo ($AR_DT['withdraw_fee']); ?></td>
                        <td><?php echo CURRENCY; ?> &nbsp;<?php echo ($AR_DT['trns_amount']); ?></td>
                        <td><?php if($AR_DT['trns_status']=="C"){ ?>
                          <a href="javascript:void(0)" onClick="alert('Already confirmed')" class="label label-success arrowed-in arrowed-in-right">Confirmed</a>
                          <?php }elseif($AR_DT['trns_status']=="R"){ ?>
                          <a  href="javascript:void(0)" onClick="alert('Already rejected')"   class="label label-warning">Rejected</a>
                          <?php }else{ ?>
                          <a href="<?php echo generateSeoUrlAdmin("financial","withdrawals",array("transfer_id"=>_e($AR_DT['transfer_id']),"trns_status"=>_e("C"),"action_request"=>"STS")); ?>" onClick="return confirm('Make sure want to approved this  withdrawal request')" class="label label-success arrowed-in arrowed-in-right">Confirm</a> &nbsp;&nbsp; <a  href="<?php echo generateSeoUrlAdmin("financial","withdrawals",array("transfer_id"=>_e($AR_DT['transfer_id']),"trns_status"=>_e("R"),"action_request"=>"STS")); ?>"  onClick="return confirm('Make sure want to reject this withdrawal request')" class="label label-warning">Reject</a>
                          <?php } ?></td>
                      </tr>
                      <?php $Ctrl++; endforeach; }else{ ?>
                      <tr>
                        <td colspan="7" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No withdrawals requests found</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
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
<?php jquery_validation(); auto_complete(); ?>
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
