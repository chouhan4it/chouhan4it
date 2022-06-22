<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$flddToday = getLocalDate();
$fldvTime = getTime();
$fran_id = $this->session->userdata('fran_id');
$order_no = $model->franchiseOrderNo($fran_id);
$current_time = crntTime();
$from_time = "23:30:00";
$to_time = "23:59:59";
$new_invoice = 1;
if(TimeIsBetweenTwoTimes($current_time, $from_time, $to_time)){ $new_invoice = 0;}
if($model->getValue("CONFIG_STOP_INVOICING_ALL")=='Y'){ $new_invoice = 0;}
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
<?= $this->load->view(FRANCHISE_FOLDER.'/order/orderjs'); ?>
</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
      <ul class="breadcrumb">
        <li> <i class="ace-icon fa fa-home home-icon"></i> <a href="#">Home</a> </li>
        <li> <a href="#">Invoice</a> </li>
        <li class="active">&nbsp; New Invoice</li>
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
        <h1> New <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  Invoice </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <div class="col-md-12">
          <div class="pull-right"> <img src="<?php echo BASE_PATH; ?>setupimages/icon_plus.gif" alt="Add New" border="0" class="pointer" id="add" />&nbsp; <img src="<?php echo BASE_PATH; ?>setupimages/icon_minus.gif" alt="Remove" id="remove" border="0" class="pointer" /> </div>
        </div>
      </div>
      <?php 
	  if($new_invoice==1){
		//if($fran_id==1){
	  ?>
      <div class="row">
        <?php  get_message(); ?>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <form class="form-horizontal"  name="formstock" id="formstock" action="<?php echo generateFranchiseForm("order","placeorder",""); ?>" method="post" onSubmit="return ValidateForm()">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="row">
                    <div class="col-md-4">
                    	User Id<br>
                      <input id="member_name" placeholder="Consultant" name="member_name"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['member_name']; ?>">
                      <input type="hidden" name="member_id" id="member_id">
                    </div>
                    <div class="col-md-2">
                    	Order No<br>
                      <input id="form-field-1" placeholder="Oder No" name="order_no"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $order_no; ?>"  readonly="true">
                    </div>
                    <div class="col-md-2">
                    Date<br>
                      <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required]" name="order_date" id="order_date" value="<?php echo $flddToday; ?>" type="text" readonly  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
                    <div class="col-md-2">
                    	Offer<br>
                      <select name="offer_module" id="offer_module" class="form-control" style="width:auto;" onChange="return ">
						<option value="" selected>---select---</option>
                        <option value="OPOF">One Plus One Offer</option>
                        <option value="OPOF-M">Green Tea One Plus One Multiple Offer</option>
						<?php DisplayCombo($_REQUEST['offer_module'],"OFFER_MODULE"); ?>
					</select>
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
                            <th width="6%">SR NO</th>
                            <th>PRODUCT</th>
                            <th width="10%">CODE</th>
                            <th width="10%">SIZE</th>
                            <th width="10%">BATCH NO</th>
                            <th width="8%">AVL. QTY</th>
                            <th width="10%">RCP</th>
                            <th width="8%">QTY</th>
                            <th width="10%">TOTAL AMT</th>
                          </tr>
                        </thead>
                        <tbody id="tblProdBdy">
                          <?php
				 	$Ctrl=6;
				 	for($Li=1; $Li<=$Ctrl; $Li++){
				 ?>
                          <tr>
                            <td><?php echo $Li; ?></td>
                            <td><input id="post_title<?php echo $Li?>" placeholder="" name="post_title[]"  class="col-xs-10 col-sm-12 prod_search" type="text" ref="<?php echo $Li?>" >
                              <input name="post_id[]" type="hidden" class="post_id_class"  id="post_id<?php echo $Li?>" ref="<?php echo $Li?>" />
                            </td>
                            <td><input id="post_code<?php echo $Li?>" placeholder="" name="post_code[]"  class="col-xs-10 col-sm-12" type="text" ref="<?php echo $Li?>" ></td>
                            <td><input id="post_size<?php echo $Li?>" placeholder="" name="post_size[]"  class="col-xs-10 col-sm-12" type="text" ref="<?php echo $Li?>" ></td>
                            <td><input id="post_batch<?php echo $Li?>" placeholder="" name="post_batch[]"  class="col-xs-10 col-sm-12" type="text" ref="<?php echo $Li?>" ></td>
                            <td><input id="available_qty<?php echo $Li?>" placeholder="" name="available_qty[]"  class="col-xs-10 col-sm-12 CalcTotal" type="text" ref="<?php echo $Li?>"  readonly="true"></td>
                            <td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                <input id="post_price<?php echo $Li?>" placeholder="RCP Price" name="post_price[]"  class="col-xs-12 col-sm-12 CalcTotal"  type="text" ref="<?php echo $Li?>" readonly>
                                <input name="post_pv[]" type="hidden"   id="post_pv<?php echo $Li?>" ref="<?php echo $Li?>" />
                              </div></td>
                            <td><input name="post_qty[]" type="text" class="col-xs-8 col-sm-8 CalcTotal" id="post_qty<?php echo $Li?>" ref="<?php echo $Li?>" />
                            </td>
                            <td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                <input id="post_sum_price<?php echo $Li?>" placeholder="Total Price" name="post_sum_price[]"  class="col-xs-12 col-sm-12"  type="text" ref="<?php echo $Li?>" readonly>
                                <input id="post_sum_pv<?php echo $Li?>"  name="post_sum_pv[]"   type="hidden" ref="<?php echo $Li?>">
                              </div></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      
                      <div class="clearfix" id="offer-section" style="display:none;">
					  <h4>Your Offer </h4>
                        <table width="100%" border="0" class="table" id="tblofferdtls">
                          <thead>
                            <tr>
                              <td>Srl No </td>
                              <td>Offer Name</td>
                              <td>Offer Code </td>
                              <td>Offer PV </td>
                              <td>Offer BV </td>
                              <td>Offer Price </td>
                              <td>&nbsp;</td>
                            </tr>
                          </thead>
                          <tbody id="tblOfferBdy">
                          </tbody>
                        </table>
                         </div></td>
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
                              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                <input id="net_payable" placeholder="Total Amount" name="net_payable"  class="col-xs-12 col-sm-12 CalcTotal"  type="text">
                              </div>
                            </div>
                            <div class="col-sm-2"> <img src="<?php echo BASE_PATH; ?>setupimages/refresh2.png" alt="Refresh" align="absmiddle" id="NetCalcTotal"  style="cursor:pointer" /> </div>
                          </div>
                          <div class="space-4"></div>
                          <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Total PV : </label>
                            <div class="col-sm-7">
                              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-dot-circle-o"></i></span>
                                <input id="net_pv" placeholder="Total PV" name="net_pv"  class="col-xs-12 col-sm-12 CalcTotal"  type="text">
                              </div>
                            </div>
                          </div>
                          
                          <div class="space-4"></div>
                          <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> FPV No : </label>
                            <div class="col-sm-7">
                              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-dot-circle-o"></i></span>
                                <input id="fpv_no" placeholder="FPV No" name="fpv_no"  class="col-xs-12 col-sm-12 CheckFPV"  type="text">
                              </div>
                            </div>
                            <div class="col-sm-2"> <img src="<?php echo BASE_PATH; ?>setupimages/refresh2.png" alt="Refresh" align="absmiddle" id="CheckFPV" style="cursor:pointer" /> </div>
                          </div>
                          <div class="space-4"></div>
                          <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> FPV Discount : </label>
                            <div class="col-sm-7">
                              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                              <input type="hidden" name="post_cat" id="post_cat" value="0" />
                                <input id="fpv_value" placeholder="FPV Value" name="fpv_value"  class="col-xs-12 col-sm-12" type="text" readonly>
                              </div>
                            </div>
                          </div>
                          <div class="space-4"></div>
                          <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Net Payable : </label>
                            <div class="col-sm-7">
                              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                <input id="net_balance" placeholder="Balance Amount" name="net_balance"  class="col-xs-12 col-sm-12" type="text" readonly>
                              </div>
                            </div>
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
                      <input type="hidden" name="franchisee_id" id="franchisee_id" value="<?php echo $this->session->userdata('fran_id'); ?>">
                      <button type="submit" id="submitOrder" name="submitOrder" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Submit </button>
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
      <?php }else{ echo "Sorry for the inconvenience, Invoicing has been stopped temporarily. Kindly bear with us for sometime!!!";}?>
      <!-- /.row -->
    </div>
    <!-- /.page-content -->
  </div>
</div>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<?php jquery_validation(); auto_complete(); ?>
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
		$("#offer_module").on('change',function(){
			if($(this).val()!=""){
				document.getElementById('fpv_no').disabled = true;
			}
		});
	});
</script>
<script type="text/javascript" language="javascript">
	new Autocomplete("member_name", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo BASE_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
	});
</script>
</body>
</html>