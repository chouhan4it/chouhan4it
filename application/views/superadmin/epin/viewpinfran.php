<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(2);
$mstr_id = (_d($form_data['mstr_id'])>0)? _d($form_data['mstr_id']):_d($segment['mstr_id']);

$QR_MSTR = "SELECT tpm.*, tpy.pin_name, tpy.pin_letter, tf.store, tf.name, tf.email, tf.mobile, tf.address, tf.pincode,
			tf.city, tf.state, tf.gst_no, tb.bank_name 
			FROM tbl_pinsmaster AS tpm 
			LEFT  JOIN tbl_pintype AS tpy ON tpm.type_id=tpy.type_id
			LEFT JOIN tbl_franchisee AS tf ON tf.franchisee_id=tpm.franchisee_id
			LEFT JOIN tbl_banks AS tb ON tpm.bank_id = tb.bank_id
			WHERE tpm.mstr_id='".$mstr_id."' ORDER BY tpm.mstr_id DESC";
$AR_MSTR = $this->SqlModel->runQuery($QR_MSTR,true);
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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/chosen.min.css" />
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
.danger_alert {
	background-color: #f2dede;
	border-color: #ebccd1;
	color: #a94442;
}
.success_alert {
	background-color: #dff0d8;
	border-color: #d6e9c6;
	color: #3c763d;
}
.pointer {
	cursor: pointer;
}
span.title {
	display: block;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: 600;
	font-size: 18px;
	color: #fff;
	letter-spacing: 27px;
	padding-left: 10px;
}
</style>
</head>
<body class="no-skin" >
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1>E-Pin <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Detail</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" >
              <tr>
                <td width="444">&nbsp;</td>
                <td width="76" align="right"><img src="<?php echo BASE_PATH; ?>setupimages/button_back.gif" alt="Back" width="50" height="20" border="0" align="right" class="pointer" onClick="window.location.href='<?php echo generateSeoUrlAdmin("epin","pingeneratefran",array("")); ?>'" /></td>
                <td width="50" align="right"><img src="<?php echo BASE_PATH; ?>setupimages/button_print.gif" alt="Print" name="PrintImg" width="50" height="20" class="pointer" id="PrintImg" /></td>
              </tr>
              <tr>
                <td colspan="3" align="center" class="cmntext" id="Letter"><table class="table" width="100%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #EBEBEB;">
                    <tr>
                      <td colspan="2" align="center"  valign="middle" class=""><img height="60" width="auto" src="<?php echo LOGO; ?>" /><br></td>
                    </tr>
                    <tr>
                      <td colspan="2" height="1"><hr style="padding:0; margin:0; color:#CCCCCC;" /></td>
                    </tr>
                    <tr>
                      <td width="40%" class="cmntext"><strong>TO</strong></td>
                      <td width="42%" rowspan="3" align="center">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" class="cmntext"><?php echo $AR_MSTR['user_id']; ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" class="cmntext"><?php
	echo strtoupper($AR_MSTR['store']."<br>".$AR_MSTR['name']."<br>".$AR_MSTR['address']."<br>"."PIN: ".$AR_MSTR['pincode']."<br>"."Mobile: ".$AR_MSTR['mobile']."");
	?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" class="cmntext"> Invoice No:
                        <?php  echo "PIN-".$AR_MSTR['mstr_id']; ?>
                        <br />
                        Pin Type: <?php echo $AR_MSTR['pin_name'];?><br />
                        No. Of Pin: <?php echo $AR_MSTR['no_pin'];?><br />
                        Total Amount: <?php echo $AR_MSTR['net_amount'];?></td>
                      <td align="left" valign="top" class="cmntext"> Bank: <?php echo $AR_MSTR['bank_name'];?><br />
                        Date: <?php echo DisplayDate($AR_MSTR['payment_date']);?><br />
                        Paid by: <?php echo $AR_MSTR['paid_by'];?><br />
                        Details: <?php echo FCrtAdd($AR_MSTR['payment_sts']);?></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="left" valign="top"><strong class="cmntext">Pin  Details </strong></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="center" valign="top"><table class="table" width="100%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #EBEBEB;">
                          <tr>
                            <td width="16%" align="center" class="cmntext"><strong>Srl No </strong></td>
                            <td width="27%" align="left" class="cmntext"><strong>e-Pin  Number </strong></td>
                            <td width="24%" align="left" class="cmntext"><strong>e-Pin Key </strong></td>
                            <td colspan="2" align="center" class="cmntext"><strong>e-Pin  Value </strong></td>
                          </tr>
                          <?php
						$Ctrl=1;
						$Q_PINS = "SELECT * FROM tbl_pinsdetails WHERE mstr_id='".$AR_MSTR['mstr_id']."'";
						$RS_PINS = $this->SqlModel->runQuery($Q_PINS);
						foreach($RS_PINS as $AR_PINS){
						?>
                          <tr class="<?php echo ($AR_PINS['pin_sts']=="Y")? "text text-danger":""; ?>">
                            <td align="center" class="cmntext"><?php echo $Ctrl;?></td>
                            <td align="left" class="cmntext"><?php echo $AR_PINS['pin_no'];?></td>
                            <td align="left" class="cmntext"><?php echo $AR_PINS['pin_key'];?></td>
                            <td width="20%" align="right" class="cmntext"><?php echo $AR_PINS['pin_price'];?></td>
                            <td width="13%" align="right" class="cmntext">&nbsp;</td>
                          </tr>
                          <?php 
						$Ctrl++;
						} 
						?>
                          <tr>
                            <td align="center" class="cmntext">&nbsp;</td>
                            <td align="left" class="cmntext">&nbsp;</td>
                            <td align="left" class="cmntext">&nbsp;</td>
                            <td align="right" class="cmntext"><strong><?php echo number_format($AR_MSTR['net_amount'],2);?></strong></td>
                            <td align="right" class="cmntext">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="4" align="right" class="cmntext"><strong>(Rupees <?php echo convert_number($AR_MSTR['net_amount']);?> Only)</strong></td>
                            <td align="right" class="cmntext">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="center" valign="top">(This is a computer generated statement/receipt and does not require any  signature.) </td>
                    </tr>
                  </table></td>
              </tr>
            </table>
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
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		$(".getPinPrice").on('blur',getPinPrice);
		$(".getCalculate").on('blur',getCalculate);
		function getPinPrice(){
			var type_id = $("#type_id").val();
			var URL_LOAD = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=PIN_TYPE&type_id="+type_id;
			$.getJSON(URL_LOAD,function(jsonReturn){
				if(jsonReturn.type_id>0){
					$("#pin_price").val(jsonReturn.pin_price);
					$("#no_pin").val('');
				}
			});
		}
		
		function getCalculate(){
			var no_pin = $("#no_pin").val();
			var pin_price = $("#pin_price").val();
			if(no_pin>0 && pin_price>0){
				var net_amount = pin_price*no_pin;
				$("#net_amount").val(net_amount);
			}
		}
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
<script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/jquery/jquery.print.js"></script> 
<script type="text/javascript">
	$(function(){$( "#PrintImg" ).click(function(){$( "#Letter" ).print(); return( false );});});
</script>
</body>
</html>
