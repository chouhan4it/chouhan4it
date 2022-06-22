<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$franchisee_id = $this->session->userdata('fran_id');
$StrWhr .=" AND (ord.franchisee_id='".$franchisee_id."')";

if($_REQUEST['order_no']!=''){
	$order_no = FCrtRplc($_REQUEST['order_no']);
	$StrWhr .=" AND ( ord.order_no = '$order_no' )";
	$SrchQ .="&order_no=$order_no";
}
if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND ( DATE(ord.date_add) BETWEEN '$date_from' AND '$date_to')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$QR_PAGES = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id ,
			 tad.current_address AS order_address, tos.name AS order_state, SUM(tod.post_qty) AS no_of_qty
			 FROM  tbl_orders AS ord
			 LEFT  JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT  JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT  JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 LEFT JOIN  tbl_order_detail AS tod ON tod.order_id=ord.order_id
			 WHERE ord.order_id>0 AND ( ord.invoice_number='0' OR ord.invoice_number='' ) 
			 
			 $StrWhr
			 GROUP BY ord.order_id
			 ORDER BY ord.order_id DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);

$QR_EXPORT = "SELECT ord.order_no AS ORDER_NO, ord.payment AS PURCHASE_TYPE,  ord.total_paid AS TOTAL_PAID, 
			 SUM(tod.post_qty) AS NO_OF_PRODUCT,
			 ord.total_bv AS TOTAL_BV, ord.total_pv AS TOTAL_PV, ord.invoice_number AS INVOICE_NO, 
			 ord.invoice_date AS INVOICE_DATE,
			 ord.delivery_date AS DELIVERY_DATE, ord.date_add AS ORDER_DATE,
			 CONCAT_WS(' ',tm.first_name,tm.last_name) AS FULL_NAME, tm.user_id AS USER_ID ,
			 tad.current_address AS ORDER_ADDRESS, tos.name AS ORDER_STATUS, 
			 ord.order_message AS ORDER_MESSAGE
			 FROM  tbl_orders AS ord
			 LEFT JOIN  tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN  tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN  tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 LEFT JOIN  tbl_order_detail AS tod ON tod.order_id=ord.order_id
			 WHERE ord.order_id>0 AND ( ord.invoice_number='0' OR ord.invoice_number='' ) 
			
			 $StrWhr
			 GROUP BY ord.order_id
			 ORDER BY ord.order_id DESC";
ExportQuery($QR_EXPORT);
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
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Order <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; List  </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
          <div class="row">
		  	<div class="col-xs-12">
              <div class="clearfix">
                <div class="row">
                  <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("order","orderlist",""); ?>" method="post">
                    <div class="col-md-2">
                      <input id="order_no" placeholder="Order No" name="order_no"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['order_no']; ?>">
                     
                    </div>
                    <div class="col-md-2">
                     <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="Date From" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
					<div class="col-md-6">
						<button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
          				<button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
						<button type="button" class="btn btn-sm btn-info" onClick="window.location.href='<?php echo generateSeoUrlAdmin("export","excel",""); ?>'"> <i class="ace-icon fa fa-download"></i> Download </button>
					</div>
                  </form>
                </div>
              </div>
            </div>
			<hr>
            <div class="col-xs-12">
              <div>
                <table id="" class="table">
                  <thead>
				  	<tr>
                      <td class="center">Srl No </td>
                      <td>Order No </td>
                      <td>Customer</td>
                      <td>No of Product</td>
                      <td>Total</td>
                      <td>Payment </td>
                      <td>Status</td>
                      <td>Date</td>
                      <td>&nbsp;</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl= $PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						
			       ?>
                    
                    <tr>
                      <td width="38" class="center"><label class="pos-rel"> <?php echo $Ctrl; ?> <span class="lbl"></span> </label>                      </td>
                      <td width="143"> <?php echo $AR_DT['order_no']; ?> </td>
                      <td width="134"><?php echo strtoupper($AR_DT['full_name']); ?>-[<?php echo $AR_DT['user_id']; ?>]</td>
                      <td width="134"> <?php echo $AR_DT['total_products']; ?> </td>
                      <td width="158"><?php echo number_format($AR_DT['total_paid'],2); ?></td>
                      <td width="164"><?php echo $AR_DT['payment']; ?></td>
                      <td width="165"><?php echo $AR_DT['order_state']; ?></td>
                      <td width="146"><?php echo $AR_DT['date_add']; ?></td>
                      <td width="94"><div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                          <ul class="dropdown-menu dropdown-default">		
								<li> <a href="<?php echo generateSeoUrlFranchise("order","orderview",array("order_id"=>_e($AR_DT['order_id']))); ?>">View</a> </li>
                                <li> <a href="<?php echo generateSeoUrlFranchise("order","generateinvoice",array("order_id"=>_e($AR_DT['order_id']))); ?>">Generate Invoice</a> </li>
							
								<?php /*if( $return_count==0 ){ ?>
									<li> <a href="<?php echo generateSeoUrlFranchise("order","returnorder",array("order_id"=>_e($AR_DT['order_id']))); ?>">Order Return</a> </li>
								<?php }*/ ?>
                          </ul>
                        </div></td>
                    </tr>
                    
                    
                    
                   
                    <?php $Ctrl++; endforeach; }else{ ?>
                    <tr>
                      <td colspan="9" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
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
<div id="search-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="smaller lighter blue no-margin">Search</h3>
      </div>
      <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("shop","orderlist",""); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Order No  :</label>
            <div class="col-sm-7">
              <input id="form-field-1" placeholder="Order No" name="order_no"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['order_no']; ?>">
            </div>
          </div>
            
		  
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
          <button type="button" class="btn  btn-sm btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
          <button type="button" class="btn btn-sm btn-danger pull-right" data-dismiss="modal"> <i class="ace-icon fa fa-times"></i> Close </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
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
