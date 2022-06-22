<?php defined('BASEPATH') OR exit('No direct script access allowed');
	$model = new OperationModel();
	$wallet_id = $model->getWallet(WALLET1);
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	
	
	
	if($_REQUEST['user_id']!=''){
		$member_id = $model->getMemberId($_REQUEST['user_id']);
		$StrWhr .=" AND top.member_id = '$member_id'";
		$SrchQ .="&user_id=$_REQUEST[user_id]";
	}
	
	
	if($_REQUEST['payment_sts']!=''){
		$payment_sts = FCrtRplc($_REQUEST['payment_sts']);
		$StrWhr .=" AND top.payment_sts = '$payment_sts'";
		$SrchQ .="&payment_sts=$_REQUEST[payment_sts]";
	}
	
	
	if($_REQUEST['trns_id']!=''){
		$trns_id = FCrtRplc($_REQUEST['trns_id']);
		$StrWhr .=" AND top.trns_id LIKE  '%$trns_id%'";
		$SrchQ .="&trns_id=$trns_id";
	}
	
	$QR_PAGES="SELECT top.*, tm.user_id
			FROM ".prefix."tbl_online_payment AS top 
			LEFT JOIN tbl_members AS tm ON tm.member_id=top.member_id
			WHERE 1
			$StrWhr 
			ORDER BY top.online_id DESC";
	$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
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
<style type="text/css">
	.table-wrapper {
		background: #eeeded;
		border-radius: 10px;
		width: 100%;
		height: auto;
		padding: 10px;
		box-sizing: border-box;
		border: 1px solid #CCC;
		margin: 30px 0;
	}
	table {
		background-color: #f9f9f9;
		border-collapse: collapse;
		color: black;
		margin: 0px !important;
		width: 100%;
		text-align: left
	}
	table tr:nth-child(odd) {
		background-color: #fefbf0;
	}
	table tr th {
		/*background-color: #E0CEF2;
		border-color: #B1A3BF;*/
	}
	table tr, table tr td {
		/*line-height: 40px !important;*/
	}
	table tr th, table tr td {
		border: 1px solid #aaa;
	}
 @media only screen and (max-width:992px) {
	/* Force table to not be like tables anymore */
	#no-more-tables table, #no-more-tables thead, #no-more-tables tbody, #no-more-tables th, #no-more-tables td, #no-more-tables tr {
		display: block;
	}
	/* Hide table headers (but not display: none;, for accessibility)  */
	#no-more-tables thead tr {
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	/* Behave like a "row" */
	#no-more-tables td:not(:first-child) {
		position: relative;
		padding-left: 52%;
		text-align: left;
		border-collapse: collapse;
		border-bottom: 0px;
	}
	#no-more-tables td:first-child {
		text-align: center;
		border-collapse: collapse;
		border-bottom: 0px;
		/*background-color: #359AF2;
		color: white;
		font-weight: bold*/
	}
	#no-more-tables td:last-child {
		border: 1px solid #aaa;
	}
	#no-more-tables tr {
		margin-bottom: 10px;
	}
	/* Now like a table header */
	#no-more-tables td:not(:first-child):before {
		position: absolute;
		left: 0;
		top: 0;
		height: 100%;
		width: 48%;
		padding-left: 2%;
		/*background-color: #f2f7fc;*/
		border-right: 1px solid #aaa;
		white-space: wrap;
		text-align: left;
		font-weight: bold;
		content: attr(data-title);
	}
}
</style>
</head>
<body class="no-skin">
<?= $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?= $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> Payment <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; History </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <form action="<?php echo generateAdminForm("financial","paymenthistory","");  ?>" method="post" name="form-search" id="form-search">
                  <div class="form-group">
                    <div class="col-sm-3">
                      <input id="trns_id" placeholder="Transaction No" name="trns_id"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['trns_id']; ?>">
                    </div>
					 <div class="col-sm-3">
                      <input id="user_id" placeholder="Member Id" name="user_id"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['user_id']; ?>">
                    </div>
                     <div class="col-sm-3">
                     	<select name="payment_sts" id="payment_sts" class="col-xs-10 col-sm-12 validate[required]">
                        	<option value="">----select status----</option>
                            <?php echo DisplayCombo($_REQUEST['payment_sts'],"PAYMENT_STS"); ?>
                        </select>
                    
                    </div>
                    <div class="col-sm-3">
                      <button type="submit" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
                      <button onClick="window.location.href=''" type="button" class="btn btn-sm btn-danger"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="clearfix">&nbsp;</div>
              <div class="col-xs-12">
                <table  id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                      <thead>
                        <tr role="row">
                          <th >Srl No </th>
                          <th >User Id</th>
                          <th >Bank </th>
                          <th >Ref No</th>
                          <th >Currency</th>
                          <th >Transaction No </th>
                          <th>Date</th>
                          <th>Amount</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
						<?php 
                        if($PageVal['TotalRecords'] > 0){
                        $Ctrl=1;
                        foreach($PageVal['ResultSet'] as $AR_DT):
                        ?>
                        <tr class="odd" role="row">
                          <td class=""><?php echo $Ctrl; ?></td>
                          <td class=""><?php echo $AR_DT['user_id']; ?></td>
                          <td class=""><?php echo $AR_DT['bank_name']; ?></td>
                          <td class=""><?php echo $AR_DT['bank_ref_no']; ?></td>
                          <td class=""><?php echo $AR_DT['trns_currency']; ?></td>
                          <td class=""><?php echo $AR_DT['trns_id']; ?></td>
                          <td class=""><?php echo getDateFormat($AR_DT['date_time'],"d M Y h:i"); ?></td>
                          <td><?php echo number_format($AR_DT['order_amount'],2); ?></td>
                          <td><?php echo ($AR_DT['payment_sts']); ?></td>
                        </tr>
						<?php 
                        $Ctrl++;
                        endforeach;
                        }
                        ?>
                      </tbody>
                    </table>
              </div>
              <div class="clearfix">&nbsp;</div>
              <div class="row">
                <div class="col-xs-4">
                  <div aria-live="polite" role="status" id="dynamic-table_info" class="dataTables_info"> Showing <?php echo $PageVal['RecordStart']+1; ?> to <?php echo  count($PageVal['ResultSet']); ?> of <?php echo $PageVal['TotalRecords']; ?> entries </div>
                </div>
                <div class="col-xs-8">
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
<div id="advert-view" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="smaller lighter blue no-margin">Advrt Management</h3>
      </div>
      <div class="modal-body" id="load_content"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?= $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse"> <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i> </a>
</div>
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
