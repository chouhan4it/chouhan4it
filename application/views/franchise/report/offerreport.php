<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalTime();
$franchisee_id = $this->session->userdata('fran_id');

$QR_SHOPPE = "SELECT franchisee_id, name, city FROM tbl_franchisee WHERE franchisee_id='$franchisee_id'";
$AR_SHOPPE = $this->SqlModel->runQuery($QR_SHOPPE,true);

if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND (A.offer_expiry BETWEEN '$date_from' AND '$date_to')";
	$SrchQ .="&date_from=".$_REQUEST[date_from]."&date_to=".$_REQUEST[$date_to]."";
}

$QR_PAGES ="SELECT A.* FROM tbl_offer AS A WHERE 1 $StrWhr ORDER BY A.offer_id DESC";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
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
</head>
</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(FRANCHISE_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Offer <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Report </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-12">
              <div class="clearfix">
                <div class="row">
                  <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("report","offerreport",""); ?>" method="post">
                    <div class="col-md-2">
                     <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST[date_from];?>" placeholder="Date From" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST[date_to];?>" placeholder="Date To" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
					<div class="col-md-3">
						<button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
          				<button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?franchisee_id=<?php echo $franchisee_id;?>'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
					</div>
                  </form>
                </div>
              </div>
            </div>
			<hr>
            <div class="clearfix">
			<div class="col-xs-12">
              <table width="100%" align="center" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
                  <tr style="font-weight:bold; font-size:18px;">
                    <td colspan="10" align="right"><a onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a></td>
                  </tr>
                  <tr style="font-weight:bold; font-size:14px;">
                    <td width="6%" class="center">Sr No</td>
                    <td>Offer Name</td>
                    <td width="8%">Offer Code</td>
                    <td width="8%">Offer Expiry</td>
                    <td width="8%">Total Sale</td>
                    <td width="16%" colspan="2">Offer Products</td>
                    <td width="8%">Amount Collected</td>
                    <td width="8%">Offer Value</td>
                    <td width="6%">&nbsp;</td>
                  </tr>
                  <tr style="font-weight:bold; font-size:14px;">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="8%">NOS</td>
                    <td width="8%">VALUE</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
					$Ctrl=1;
					foreach($PageVal['ResultSet'] as $AR_DT):
					$QR_OFFER = "SELECT A.post_id, A.post_mrp, A.post_discount, A.post_price, C.offer_module, C.offer_title, C.offer_expiry, 
						C.offer_price FROM tbl_post_lang AS A, tbl_offer_product AS B, tbl_offer AS C WHERE A.post_id = B.post_id AND B.offer_id = 
						'$AR_DT[offer_id]' AND B.offer_id = C.offer_id";
					$AR_OFFER = $this->SqlModel->runQuery($QR_OFFER,true);
					$total_sale = $model->getOfrSaleByFra('sale',$AR_SHOPPE['franchisee_id'],$AR_DT['offer_id']);
					$total_count = $model->getOfrSaleByFra('icnt',$AR_SHOPPE['franchisee_id'],$AR_DT['offer_id']);
					$total_value = $model->getOfrSaleByFra('ival',$AR_SHOPPE['franchisee_id'],$AR_DT['offer_id']);
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
						if($AR_DT['offer_id']<159){
						$amount_collected = ($AR_OFFER['post_discount']*($total_count/2));
						}else{
						$amount_collected = ($AR_OFFER['post_discount']*$total_count);
						}
					}elseif($AR_OFFER['offer_module']=='FPOF'){
						$amount_collected = ($AR_OFFER['offer_price']*$total_count);
					}elseif($AR_OFFER['offer_module']=='FPOF-T'){
						$amount_collected = ($AR_OFFER['offer_price']*$total_count);
					}elseif($AR_OFFER['offer_module']=='ONDO'){
						$amount_collected = ($AR_OFFER['offer_price']*$total_count);
					}else{
						$amount_collected = $total_count*1;
					}
			       ?>
                  <tr>
                    <td class="center"><label class="pos-rel"><?php echo $Ctrl;?><span class="lbl"></span></label></td>
                    <td><?php echo $AR_DT['offer_title'];?></td>
                    <td><?php echo $AR_DT['offer_code'];?></td>
                    <td><?php echo DisplayDate($AR_DT['offer_expiry']);?></td>
                    <td><?php echo $total_sale;?></td>
                    <td><?php echo $total_count;?></td>
                    <td><?php echo $total_value;?></td>
                    <td><?php echo $amount_collected;?></td>
                    <td><?php echo $total_value-$amount_collected;?></td>
                    <td><a href="<?php echo generateFranchiseForm("report","offerinvoices","");?>?offer_id=<?php echo $AR_DT['offer_id']; ?>">View</a></td>
                  </tr>
                  <?php $Ctrl++; endforeach; }else{ ?>
                  <tr>
                    <td colspan="10" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                  </tr>
                  <?php } ?>
              </table>
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
        <!-- PAGE CONTENT ENDS -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.page-content -->
</div>
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