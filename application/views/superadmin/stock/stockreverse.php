<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$segment = $this->uri->uri_to_assoc(2);
$order_no = _d($segment['order_no']);

$QR_ORDER = "SELECT tsm.*, tf.name AS franchisee_name_from, ttf.name AS franchisee_name_to
			FROM tbl_stock_master AS tsm 
			LEFT JOIN tbl_franchisee AS tf ON tf.franchisee_id = tsm.franchisee_id_from
			LEFT JOIN tbl_franchisee AS ttf ON ttf.franchisee_id = tsm.franchisee_id_to
			WHERE  tsm.stock_id>0 AND tsm.order_no='".$order_no."'
			ORDER BY tsm.stock_id DESC";
$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,true);
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
<script src="<?php echo BASE_PATH; ?>jquery/jquery-1.8.2.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/jquery/jquery.livequery.js"></script>
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<script src="<?php echo BASE_PATH; ?>jquery/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>jquery/jquery.autocomplete.css" />
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
<?php auto_complete();  ?>
<?= $this->load->view(ADMIN_FOLDER.'/stock/reversejs'); ?>
</head>
</head>
<body class="no-skin">
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="ace-icon fa fa-home home-icon"></i> <a href="#">Home</a> </li>
          <li> <a href="#">Branch</a> </li>
          <li class="active">&nbsp; Stock Update</li>
        </ul>
        <!-- /.breadcrumb --> 
        <!-- <div class="nav-search" id="nav-search">
	<form class="form-search">
	  <span class="input-icon">
	  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
	  <i class="ace-icon fa fa-search nav-search-icon"></i> </span>
	</form>
  </div>--> 
        <!-- /.nav-search --> 
      </div>
      <div class="page-content">
        <div class="page-header">
          <h1> Stock<small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Update </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("stock","stockreverse",""); ?>" method="post" onSubmit="return ValidateForm()">
              <div class="row">
                <div class="col-xs-12">
                  <div class="clearfix">
                    <div class="row">
                      <div class="col-md-4">
                        <ul style="list-style-type:none;">
                          <li>Order No : <strong><?php echo $AR_ORDER['order_no']; ?></strong></li>
                          <li>Franchise Name : <strong><?php echo $AR_ORDER['franchisee_name_to']; ?></strong></li>
                          <li>Order Date : <strong><?php echo getDateFormat($AR_ORDER['order_date'],"d D M Y h:i"); ?></strong></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="clearfix">
                  <table width="100%" class="table">
                    <tr>
                      <td colspan="5"><table class="table table-striped table-bordered table-hover" id="tblProdDtls">
                          <thead>
                            <tr>
                              <th width="201">&nbsp;</th>
                              <th width="201">Item </th>
                              <th width="117">Unit Price </th>
                              <th width="117">Qty</th>
                              <th width="117">Update Qty </th>
                              <th width="117">Total Amount </th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
							$QR_ORD_DT = "SELECT tsm.*, tpl.post_title, tpl.post_price AS product_price 
										  FROM tbl_stock_master AS tsm 
										  LEFT JOIN tbl_post AS tp ON tp.post_id=tsm.post_id
										  LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
										 WHERE tsm.order_no='".$order_no."'";
							$RS_ORD_DT = $this->SqlModel->runQuery($QR_ORD_DT);
							$Li=1;
							foreach($RS_ORD_DT as $AR_ORD_DT):
								$order_pv +=($AR_ORD_DT['post_pv']*$AR_ORD_DT['post_qty']);
								$order_total +=$AR_ORD_DT['net_amount'];
						?>
                            <tr>
                              <td><img src="<?php echo $model->getDefaultPhoto($AR_ORD_DT['post_id']); ?>" width="35px" height="35px" class="img-responsive"></td>
                              <td><?php echo setWord($AR_ORD_DT['post_title'],30); ?>
                                <input name="post_id[]" type="hidden" class="post_id_class" value="<?php echo $AR_ORD_DT['post_id']; ?>"  id="post_id<?php echo $Li; ?>" ref="<?php echo $Li; ?>" /></td>
                              <td><?php echo $AR_ORD_DT['post_price']; ?>
                                <input id="post_price<?php echo $Li; ?>" name="post_price[]"  type="hidden" ref="<?php echo $Li; ?>" value="<?php echo $AR_ORD_DT['post_price']; ?>">
                                <input name="post_pv[]" type="hidden"   id="post_pv<?php echo $Li?>" ref="<?php echo $Li?>"  value="<?php echo $AR_ORD_DT['post_pv']; ?>"/></td>
                              <td><?php echo $AR_ORD_DT['post_qty']; ?></td>
                              <td><input name="post_qty[]" type="text" class="col-xs-6 col-sm-6 validate[required,custom[integer]] CalcTotalReturn" id="post_qty<?php echo $Li; ?>" ref="<?php echo $Li; ?>" value="" /></td>
                              <td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                  <input id="post_sum_price<?php echo $Li; ?>" placeholder="Total Price" name="post_sum_price[]"  class="col-xs-12 col-sm-12"  type="text" ref="<?php echo $Li; ?>" readonly>
                                  <input id="post_sum_pv<?php echo $Li?>"  name="post_sum_pv[]"   type="hidden" ref="<?php echo $Li?>">
                                </div></td>
                            </tr>
                            <?php $Li++; endforeach;  ?>
                          </tbody>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="5"><div class="row">
                          <div class="col-md-6">
                            <div class="pull-right"> Total  Amount : </div>
                          </div>
                          <div class="col-md-5">
                            <div class="pull-right">
                              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                <input id="net_payable" placeholder="Total Amount" name="net_payable"  class="col-xs-12 col-sm-12 CalcTotal"  type="text">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-1"> <img src="<?php echo BASE_PATH; ?>setupimages/refresh2.png" alt="Refresh" align="absmiddle" id="NetCalcTotal"  style="cursor:pointer" /> </div>
                        </div></td>
                    </tr>
                    <tr>
                      <td colspan="5"><div class="row">
                          <div class="col-md-6">
                            <div class="pull-right"> Total  PV : </div>
                          </div>
                          <div class="col-md-5">
                            <div class="pull-right">
                              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                <input id="net_pv" placeholder="Total PV" name="net_pv"  class="col-xs-12 col-sm-12 CalcTotal"  type="text">
                              </div>
                            </div>
                          </div>
                        </div></td>
                    </tr>
                  </table>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="pull-right">
                        <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                        <input type="hidden" name="order_no" id="order_no" value="<?php echo _e($AR_ORDER['order_no']); ?>">
                        <button type="submit" name="submitOrderUpdate" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Submit </button>
                        <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
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
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		$("#payment_type").on('change',function(){
			if($(this).val()=="DD"){
				$(".cheque_field").attr("disabled",true);
				$(".bank_field").attr("disabled",false);
			}else if($(this).val()=="CQ"){
				$(".cheque_field").attr("disabled",false);
				$(".bank_field").attr("disabled",true);
			}else{
				$(".cheque_field").attr("disabled",true);
				$(".bank_field").attr("disabled",true);
			}
		});
		
	});
</script>
</body>
</html>
