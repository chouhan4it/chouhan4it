<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST['page']; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalDate();
$order_no = $model->stockrevOrderNo();
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
<?= $this->load->view(ADMIN_FOLDER.'/stock/shoppetocompanyjs'); ?>
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
          <li> <a href="#">Shoppe</a> </li>
          <li class="active">&nbsp; Shoppe To Company</li>
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
          <h1> Stock<small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Shoppe to Company </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <form class="form-horizontal"  name="formstock" id="formstock" action="<?php echo generateAdminForm("stock","shoppetocompany",""); ?>" method="post" onSubmit="return ValidateForm()">
              <div class="row">
                <div class="col-xs-12">
                  <div class="clearfix">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Franchise Name</label>
                        <input id="franchisee_name_to" placeholder="Branch To" name="franchisee_name_to"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['franchisee_name_to']; ?>">
                        <input type="hidden" name="franchisee_id_to" id="franchisee_id_to">
                      </div>
                      <div class="col-md-2">
                        <label>Order No</label>
                        <input id="form-field-1" placeholder="Oder No" name="order_no"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $order_no; ?>"  readonly="true">
                      </div>
                      <div class="col-md-2">
                        <label>Order Date</label>
                        <div class="input-group">
                          <input class="form-control col-xs-10 col-sm-12  validate[required]" name="order_date" id="order_date" value="<?php echo InsertDate($flddToday); ?>" type="text"  readonly="true" />
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
                      <td colspan="4"><table class="table table-striped table-bordered table-hover" id="tblProdDtls" width="100%">
                          <thead>
                            <tr>
                              <th>PRODUCT </th>
                              <th>Available Qty </th>
                              <th>MRP</th>
                              <th>QTY </th>
                              <th>DP</th>
                              <th>TOTAL AMT </th>
                            </tr>
                          </thead>
                          <tbody id="tblProdBdy">
                            <?php
				 	$Ctrl=5;
				 	for($Li=0; $Li<=$Ctrl; $Li++){
				 ?>
                            <tr>
                              <td><input id="post_title<?php echo $Li?>" placeholder="" name="post_title[]"  class="col-xs-12 col-sm-12 prod_search" type="text" ref="<?php echo $Li?>" style="width:400px;" >
                                <input name="post_id[]" type="hidden" class="post_id_class"  id="post_id<?php echo $Li?>" ref="<?php echo $Li?>" /></td>
                              <td><input name="available_qty[]" type="text" class="col-xs-6 col-sm-6" id="available_qty<?php echo $Li?>" ref="<?php echo $Li?>"  readonly="true" /></td>
                              <td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                  <input id="post_price<?php echo $Li?>" placeholder="MRP Price" name="post_price[]"  class="col-xs-12 col-sm-12"  type="text" ref="<?php echo $Li?>" readonly>
                                </div></td>
                              <td><input name="post_qty[]" type="text" class="col-xs-6 col-sm-6 CalcTotal checkQuantity" id="post_qty<?php echo $Li?>" ref="<?php echo $Li?>" /></td>
                              <td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                  <input id="post_dp_price<?php echo $Li?>" placeholder="DP Price" name="post_dp_price[]"  class="col-xs-12 col-sm-12 CalcTotal"  type="text" ref="<?php echo $Li?>">
                                </div></td>
                              <td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                  <input id="post_sum_price<?php echo $Li?>" placeholder="Total Price" name="post_sum_price[]"  class="col-xs-12 col-sm-12"  type="text" ref="<?php echo $Li?>" readonly>
                                </div></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table></td>
                    </tr>
                    <tr>
                      <td width="3%">&nbsp;</td>
                      <td width="5%">&nbsp;</td>
                      <td width="44%">&nbsp;</td>
                      <td width="48%"><div class="row">
                          <div class="col-md-12">
                            <div class="space-4"></div>
                            <div class="form-group">
                              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Amount : </label>
                              <div class="col-sm-7">
                                <input id="net_payable" placeholder="Total Amount" name="net_payable"  class="col-xs-12 col-sm-12 CalcTotal"  type="text">
                              </div>
                              <div class="col-sm-2"> <img src="<?php echo BASE_PATH; ?>setupimages/refresh2.png" alt="Refresh" align="absmiddle" id="NetCalcTotal"  style="cursor:pointer" /> </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="form-group">
                              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Payment Type : </label>
                              <div class="col-sm-9">
                                <select name="payment_type" class="col-xs-10 col-sm-5 validate[required]" id="payment_type">
                                  <?php DisplayCombo($ROW['payment_type'],"PMTTYPE"); ?>
                                </select>
                              </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="form-group">
                              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Chq/DD No. : </label>
                              <div class="col-sm-9">
                                <input id="cheque_no" placeholder="Chq/DD No" name="cheque_no"  class="col-xs-10 col-sm-5 cheque_field" type="text" 
				value="<?php echo $ROW['cheque_no']; ?>" disabled="disabled">
                              </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="form-group">
                              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Chq/DD Date : </label>
                              <div class="col-sm-3">
                                <div class="input-group">
                                  <input class="form-control col-xs-10 col-sm-12 date-picker cheque_field" name="cheque_date" id="cheque_date" value="" type="text"  disabled="disabled" />
                                  <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                              </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="form-group">
                              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Bank Name : </label>
                              <div class="col-sm-9">
                                <input id="bank_name" placeholder="Bank Name " name="bank_name"  class="col-xs-10 col-sm-5 bank_field" type="text" 
				value="<?php echo $ROW['bank_name']; ?>" disabled="disabled">
                              </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="form-group">
                              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Bank Branch : </label>
                              <div class="col-sm-9">
                                <input id="bank_branch" placeholder="Bank Branch " name="bank_branch"  class="col-xs-10 col-sm-5 bank_field" type="text" 
				value="<?php echo $ROW['bank_branch']; ?>" disabled="disabled">
                              </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="form-group">
                              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Description : </label>
                              <div class="col-sm-9">
                                <textarea name="trns_remark" class="col-xs-10 col-sm-5 validate[required]" id="form-field-1" placeholder="Remarks"><?php echo $ROW['trns_remark']; ?></textarea>
                              </div>
                            </div>
                            <div class="space-4"></div>
                          </div>
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
<script type="text/javascript" language="javascript">
	new Autocomplete("franchisee_name_to", function(){
	this.setValue = function( id ) {document.getElementsByName("franchisee_id_to")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=FRANCHISEE";
	});
</script>
</body>
</html>