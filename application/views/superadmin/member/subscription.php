<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$wallet_id = $model->getWallet(WALLET1);


if($_REQUEST['order_no']!=''){
	$order_no = FCrtRplc($_REQUEST['order_no']);
	$StrWhr .=" AND ( tm.order_no = '$order_no' )";
	$SrchQ .="&order_no=$order_no";
}

if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);
	$StrWhr .=" AND DATE(ts.date_from) BETWEEN '$from_date' AND '$to_date'";
	$SrchQ .="&from_date=$from_date&to_date=$to_date";
}

$QR_PAGES = "SELECT ts.*, tp.pin_name , tm.user_id,
				CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.date_join,
				tmsp.user_id AS spsr_user_id
				FROM tbl_subscription AS ts 
				LEFT JOIN tbl_pintype AS tp ON tp.type_id=ts.type_id
				LEFT JOIN tbl_members AS tm ON tm.member_id=ts.member_id
				LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
				WHERE 1 $StrWhr
				GROUP BY ts.subcription_id
				ORDER BY ts.subcription_id ASC";
$PageVal = DisplayPages($QR_PAGES, 500, $Page, $SrchQ);
ExportQuery($QR_PAGES);
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
<script type="text/javascript">
	$(function(){
		$(".open_modal").on('click',function(){
			$('#search-modal').modal('show');
			return false;
		});
	});
</script>
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
            <h1> User <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Subscription </small> </h1>
          </div>
          <!-- /.page-header -->
          <div class="row">
            <?php get_message(); ?>
            <div class="col-xs-12">
              <div class="row">
                <div class="col-xs-12">
                  <div class="clearfix">
                    <div class="col-md-9">
                      <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("member","subscription",""); ?>">
                        <b>Subscription No </b>
                        <div class="form-group">
                          <div class="clearfix">
                            <input id="order_no" placeholder="Order No" name="order_no"  class="col-xs-12 col-sm-6 validate[required]" type="text" value="<?php echo $_REQUEST['order_no']; ?>">
                          </div>
                        </div>
                        <b>Date </b>
                        <div class="form-inline">
                          <div class="input-group">
                            <input class="form-control col-xs-4 col-sm-3  date-picker" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                            <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span></div>
                          &nbsp;&nbsp;To&nbsp;&nbsp;
                          <div class="input-group">
                            <input class="form-control col-xs-4 col-sm-3  date-picker" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                            <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span> </div>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                        <a href="<?php echo generateSeoUrlAdmin("member","profilelist",""); ?>"  class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
                      </form>
                    </div>
                    <div class="col-md-3">
                      <div class="pull-right tableTools-container">
                        <div class="dt-buttons btn-overlap btn-group"> <a  href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a  href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix">&nbsp;</div>
                  <div>
                    <table id="" class="table">
                      <thead>
                      </thead>
                      <tbody>
                        <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						#$model->setReferralIncome($AR_DT['member_id'],$AR_DT['subcription_id']);
			       ?>
                        <tr>
                          <td width="22" rowspan="3" class="center"><label class="pos-rel"> <?php echo $Ctrl; ?> <span class="lbl"></span> </label></td>
                          <td width="112">Full Name : </td>
                          <td width="148"><a href="javascript:void(0)"><?php echo $AR_DT['full_name']; ?></a></td>
                          <td width="126">Order No : </td>
                          <td width="120"><?php echo $AR_DT['order_no']; ?></td>
                          <td width="140">Date: </td>
                          <td width="164"><?php echo DisplayDate($AR_DT['date_from']); ?></td>
                          <td width="90" rowspan="3"><div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                              <ul class="dropdown-menu dropdown-default">
                                <li> <a href="<?php echo generateSeoUrlAdmin("bonus","dailyincome",""); ?>?member_id=<?php echo  _e($AR_DT['member_id']); ?>&subcription_id=<?php echo _e($AR_DT['subcription_id']); ?>">View</a> </li>
                                <li> <a onClick="return confirm('Make sure , you want to change subscription status?')" 
							  href="<?php echo generateSeoUrlAdmin("member","subscription",array("subcription_id"=>_e($AR_DT['subcription_id']),
							  "status"=>$AR_DT['isActive'],"action_request"=>"STATUS")); ?>"> <?php echo ($AR_DT['isActive']=="0")? "Resume":"Suspend"; ?> </a> </li>
                              </ul>
                            </div></td>
                        </tr>
                        <tr>
                          <td>User Id</td>
                          <td><a href="javascript:void(0)"><?php echo $AR_DT['user_id']; ?></a></td>
                          <td>Plan : </td>
                          <td><?php echo $AR_DT['pin_name']; ?></td>
                          <td>Payment Type</td>
                          <td><?php echo $AR_DT['payment_type']; ?></td>
                        </tr>
                        <tr>
                          <td>D.O.J : </td>
                          <td><?php echo DisplayDate($AR_DT['date_join']); ?></td>
                          <td>Amount : </td>
                          <td><?php echo number_format($AR_DT['net_amount']); ?></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="8" class="center"><hr class="divider">
                            </hr></td>
                        </tr>
                        <?php $Ctrl++; endforeach; }else{ ?>
                        <tr>
                          <td colspan="8" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
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
  <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse"> <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i> </a> </div>
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
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		});
		$("#form-valid").validationEngine();
		
	});
</script>
</body>
</html>
