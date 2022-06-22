<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalDate();
$order_no = $model->warehouseOrderNo();

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
<!--<script src="<?php echo BASE_PATH; ?>assets/jquery/jquery.livequery.js"></script>-->
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
<?= $this->load->view(ADMIN_FOLDER.'/stock/warehousejs'); ?>
</head>
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
          <h1> Stock<small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Entry  Company</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <form class="form-horizontal"  name="formstock" id="formstock" action="<?php echo generateAdminForm("stock","stockentrycompany",""); ?>" method="post" onSubmit="return ValidateForm()">
              <div class="row">
                <div class="col-xs-12">
                  <div class="clearfix">
                    <div class="row">
                      <div class="col-md-2">
                        <label>Warehouse No</label>
                        <input id="form-field-1" placeholder="Oder No" name="order_no"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $order_no; ?>"  readonly="true">
                      </div>
                      <div class="col-md-2">
                        <label>Stock Entry Date</label>
                        <div class="input-group">
                          <input class="form-control col-xs-10 col-sm-12  validate[required]" name="order_date" id="order_date" value="<?php echo $flddToday; ?>" type="text"  readonly="true" />
                          <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                      </div>
                      <div class="col-md-2">
                        <label>Bill No</label>
                        <input id="bill_no" placeholder="Bill No" name="bill_no"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $bill_no; ?>" >
                      </div>
                      <div class="col-md-2">
                        <label>Bill Date</label>
                        <div class="input-group">
                          <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="bill_date" id="bill_date" value="<?php echo $flddToday; ?>" type="text"  />
                          <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-12">
                    <div class="pull-right"> <img src="<?php echo BASE_PATH; ?>setupimages/icon_plus.gif" alt="Add New" border="0" class="pointer" id="add" />&nbsp; <img src="<?php echo BASE_PATH; ?>setupimages/icon_minus.gif" alt="Remove" id="remove" border="0" class="pointer" /> </div>
                  </div>
                </div>
                <div class="clearfix">
                  <table width="100%" class="table">
                    <tr>
                      <td colspan="5"><table class="table table-striped table-bordered table-hover" id="tblProdDtls">
                          <thead>
                            <tr>
                              <th>Product </th>
                              <th >Attribute</th>
                              <th >Batch No </th>
                              <th >Supplier</th>
                              <th>Mfg Date </th>
                              <th >Exp Date </th>
                              <th>Price</th>
                              <th >Qty </th>
                              <th>Total Amt </th>
                            </tr>
                          </thead>
                          <tbody id="tblProdBdy">
                            <?php
				 	$Ctrl=5;
				 	for($Li=0; $Li<=$Ctrl; $Li++){
				 ?>
                            <tr>
                              <td><input id="post_title<?php echo $Li?>" placeholder="" name="post_title[]" class="col-xs-12 col-sm-12 prod_search" type="text" ref="<?php echo $Li?>" style="width:250px;" >
                                <input name="post_id[]" type="hidden" class="post_id_class"  id="post_id<?php echo $Li?>" ref="<?php echo $Li?>" /></td>
                              <td><select class="col-xs-12 col-sm-12" id="post_attribute_id<?php echo $Li?>" name="post_attribute_id[]" style="width:auto;">
                                  <option value="">select attribute</option>
                                </select></td>
                              <td><input maxlength="12" id="batch_no<?php echo $Li?>" placeholder="Batch No" name="batch_no[]"  class="col-xs-12 col-sm-12 validate[custom[onlyLetterNumber]]" type="text" ref="<?php echo $Li?>" ></td>
                              <td><select class="col-xs-12 col-sm-12" id="supplier_id<?php echo $Li?>" name="supplier_id[]" style="width:auto;">
                                  <option value="">Select Supplier</option>
                                  <?php  DisplayCombo($_REQUEST['supplier_id'],"SUPPLIER");  ?>
                                </select></td>
                              <td><div class="input-group">
                                  <input class="form-control col-xs-10 col-sm-12" placeholder="YYYY-DD-MM" name="mfg_date[]" id="mfg_date<?php echo $Li?>" ref="<?php echo $Li?>" type="text"  />
                                </div></td>
                              <td><div class="input-group">
                                  <input class="form-control col-xs-10 col-sm-12"  placeholder="YYYY-DD-MM" name="exp_date[]" id="exp_date<?php echo $Li?>" ref="<?php echo $Li?>" type="text"  />
                                </div></td>
                              <td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                  <input id="post_price<?php echo $Li?>" placeholder="Price" name="post_price[]"  class="col-xs-12 col-sm-12"  type="text" ref="<?php echo $Li?>" >
                                </div></td>
                              <td><input name="post_qty[]" type="text" class="col-xs-12 col-sm-12 CalcTotal" id="post_qty<?php echo $Li?>" ref="<?php echo $Li?>" /></td>
                              <td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                  <input id="post_sum_price<?php echo $Li?>" placeholder="Total Price" name="post_sum_price[]"  class="col-xs-12 col-sm-12"  type="text" ref="<?php echo $Li?>" readonly>
                                </div></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="5"><div class="row">
                          <div class="col-md-6">
                            <div class="pull-right"> Total Amount : </div>
                          </div>
                          <div class="col-md-5">
                            <div class="pull-right">
                              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                <input id="net_payable" placeholder="Total Amount" name="net_payable"  class="col-xs-12 col-sm-12 validate[required] CalcTotal"  type="text">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-1"> <img src="<?php echo BASE_PATH; ?>setupimages/refresh2.png" alt="Refresh" align="absmiddle" id="NetCalcTotal"  style="cursor:pointer" /> </div>
                        </div></td>
                    </tr>
                  </table>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="pull-right">
                        <input type="hidden" name="action_request" id="action_request" value="ADD_UPDATE">
                        <input type="hidden" name="franchisee_id_from"  id="franchisee_id_from" value="0">
                        <button type="submit" name="submitStock" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Submit </button>
                        <button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
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
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#formstock").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		
		
	});
</script>
</body>
</html>
