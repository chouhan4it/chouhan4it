<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalTime();
$ofr_name = $model->getOfferCode($_REQUEST['offer_id']);
$QR_OFFER = "SELECT A.post_id, A.post_mrp, A.post_discount, A.post_price, C.offer_module, C.offer_title, C.offer_expiry, C.offer_price FROM tbl_post_lang 
			 AS A, tbl_offer_product AS B, tbl_offer AS C WHERE A.post_id = B.post_id AND B.offer_id = '$_REQUEST[offer_id]' AND B.offer_id = C.offer_id";
$AR_OFFER = $this->SqlModel->runQuery($QR_OFFER,true);
$QR_PAGES = "SELECT A.franchisee_id, A.name, A.city FROM tbl_franchisee AS A WHERE 1 ORDER BY A.name ASC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>public/jquery_token/token-input.css" />
<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>public/jquery_token/jquery.tokeninput.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
<?php #auto_complete();  ?>
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
          <h1> Product <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Offer Report </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <?php /*
                <div class="row">
                  <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("stock","stockreport",""); ?>" method="post">
                    <div class="col-md-4">
                      <input id="post_title" placeholder="Product Name" name="post_title"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['post_title']; ?>">
                     
                    </div>
                    <div class="col-md-2">
                     <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="" placeholder="Date From" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="" placeholder="Date To" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
					<div class="col-md-3">
						<button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
          				<button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
					</div>
                  </form>
                </div>
				*/ ?>
                </div>
              </div>
              <?php //<hr>?>
              <div class="clearfix">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th colspan="5">Offer: <?php echo $AR_OFFER['offer_title'];?></th>
                      <th colspan="4">Expiry: <?php echo DisplayDate($AR_OFFER['offer_expiry']);?></th>
                      <th align="right"><a onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a></th>
                    </tr>
                    <tr>
                      <th width="50" class="center">Sr No</th>
                      <th width="201">Shoppe Name</th>
                      <th width="117">City</th>
                      <th width="117">Offer Code</th>
                      <th width="100">Total Sale</th>
                      <th width="120" colspan="2">Offer Products</th>
                      <th width="120">Amount Collected</th>
                      <th width="100">Offer Value</th>
                      <th width="100">&nbsp;</th>
                    </tr>
                    <tr>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th width="60">NOS</th>
                      <th width="60">VALUE</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						$total_sale = $model->getOfrSaleByFra('sale',$AR_DT['franchisee_id'],$_REQUEST['offer_id']);
						$total_count = $model->getOfrSaleByFra('icnt',$AR_DT['franchisee_id'],$_REQUEST['offer_id']);
						$total_value = $model->getOfrSaleByFra('ival',$AR_DT['franchisee_id'],$_REQUEST['offer_id']);
					if($AR_OFFER['offer_module']=='OPOF'){
						$pos = strpos($AR_OFFER['offer_title'],'50 50 MRP OFFER');
						if($pos===false){
							$amount_collected = ($AR_OFFER['post_discount']+1)*$total_count;
						}else{
							$amount_collected = ($AR_OFFER['post_discount']*$total_count);
						}
					}elseif($AR_OFFER['offer_module']=='OPOF-T'){
						$amount_collected = ($AR_OFFER['post_discount']*$total_count);
					}elseif($AR_OFFER['offer_module']=='OPOF-U'){
						$amount_collected = ($AR_OFFER['post_discount']*$total_count);
					}elseif($AR_OFFER['offer_module']=='FPOF'){
						$amount_collected = ($AR_OFFER['offer_price']*$total_count);
					}else{
						$amount_collected = $total_count*1;
					}
		/*
		$QRY_ORDERS ="SELECT DISTINCT(A.order_id) AS order_id FROM tbl_orders AS A, tbl_order_detail AS B WHERE A.franchisee_id='$AR_DT[franchisee_id]' 
					AND A.invoice_number!='' AND B.offer_id='$_REQUEST[offer_id]' AND A.order_id=B.order_id ORDER BY A.order_id DESC";
		$RS_ORDERS = mysqli_query($QRY_ORDERS) or die(mysqli_error());
		while($AR_ORDERS=mysqli_fetch_assoc($RS_ORDERS)){
			$QRY_SUM ="SELECT SUM(net_amount) AS net_amount FROM tbl_order_detail WHERE order_id='$AR_ORDERS[order_id]'";
			$RS_SUM = mysqli_query($QRY_SUM) or die(mysqli_error());
			$AR_SUM = mysqli_fetch_assoc($RS_SUM);
			$total_sale = $total_sale + $AR_SUM['net_amount']; 
		}
		*/
			       ?>
                    <tr>
                      <td class="center"><label class="pos-rel"><?php echo $Ctrl;?><span class="lbl"></span></label></td>
                      <td><?php echo $AR_DT['name'];?></td>
                      <td><?php echo $AR_DT['city'];?></td>
                      <td><?php echo $ofr_name;?></td>
                      <td><?php echo $total_sale;?></td>
                      <td><?php echo $total_count;?></td>
                      <td><?php echo $total_value;?></td>
                      <td><?php echo $amount_collected;?></td>
                      <td><?php echo $total_value-$amount_collected;?></td>
                      <td><a href="<?php echo generateSeoUrlAdmin("shop","viewofferreport","");?>?franchisee_id=<?php echo $AR_DT['franchisee_id'];?>&offer_id=<?php echo $_REQUEST['offer_id']; ?>">View</a></td>
                    </tr>
                    <?php $Ctrl++; endforeach; }else{ ?>
                    <tr>
                      <td colspan="4" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
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
	new Autocomplete("post_name", function(){
	this.setValue = function( id ) {document.getElementsByName("post_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=PRODUCT";
	});
</script>
</body>
</html>