<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(2);
$order_no = ($form_data['order_no'])? $form_data['order_no']:_d($segment['order_no']);

$QR_MSTR =  "SELECT tds.*
			FROM tbl_demo_stock AS tds 
			WHERE  tds.demo_id>0 AND tds.order_no='".$order_no."'
			ORDER BY tds.demo_id DESC";
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
          <h1>Demo <small> <i class="ace-icon fa fa-angle-double-right"></i> Invoice Detail</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
              <tr>
                <td width="444">&nbsp;</td>
                <td width="76" align="right"><img src="<?php echo BASE_PATH; ?>setupimages/button_back.gif" alt="Back" width="50" height="20" border="0" align="right" class="pointer" onClick="window.location.href='<?php echo generateSeoUrlAdmin("stock","demotransaction",array("")); ?>'" /></td>
                <td width="50" align="right"><img src="<?php echo BASE_PATH; ?>setupimages/button_print.gif" alt="Print" name="PrintImg" width="50" height="20" class="pointer" id="PrintImg" /></td>
              </tr>
              <tr>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3" align="center" class="cmntext" id="Letter"><table class="table" width="100%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #EBEBEB;">
                    <tr>
                      <td colspan="2" height="1"><h2><?php echo WEBSITE; ?></h2></td>
                    </tr>
                    <?php /*
                  <tr>
                    <td width="42%" class="cmntext"><?php echo $AR_MSTR['franchisee_name_from']; ?></td>
                    <td width="58%" rowspan="3" align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left" valign="top" class="cmntext"><?php echo $AR_MSTR['franchisee_name_to']; ?></td>
                  </tr>
				  */ ?>
                    <tr>
                      <td align="left" valign="top" class="cmntext"><strong>To,</strong><br>
                        <?php echo $AR_MSTR['name_to']; ?><br>
                        <br>
                        Order No:
                        <?php  echo $AR_MSTR['order_no']; ?>
                        <br />
                        Order Date:
                        <?php  echo $AR_MSTR['order_date']; ?>
                        <br>
                        Remarks:
                        <?php  echo $AR_MSTR['trns_remark']; ?>
                        <br>
                        <br></td>
                      <td align="left" valign="top" class="cmntext">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" align="left" valign="top"><strong class="cmntext">Product   Details </strong></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="center" valign="top"><table class="table" width="100%" border="0" cellpadding="5" cellspacing="0" style="border:1px solid #EBEBEB;">
                          <tr>
                            <td width="10%" align="center" class="cmntext"><strong>SRL # </strong></td>
                            <td width="40%" align="left" class="cmntext"><strong>PRODUCT</strong></td>
                            <td width="10%" align="left" class="cmntext"><strong>MRP</strong></td>
                            <td align="center" class="cmntext"><strong>AP</strong></td>
                            <td align="center" class="cmntext"><strong>BV</strong></td>
                            <td align="center" class="cmntext"><strong>QTY</strong></td>
                            <td align="center" class="cmntext"><strong>TOTAL</strong></td>
                          </tr>
                          <?php
						$Ctrl=1;
						$Q_STOCK = "SELECT tds.*, tpl.post_title, tpl.post_price AS product_price FROM tbl_demo_stock AS tds 
						LEFT JOIN tbl_post AS tp ON tp.post_id=tds.post_id
						LEFT JOIN tbl_post_lang AS tpl ON tpl.post_id=tp.post_id
						WHERE tds.order_no='".$order_no."'";
						$RS_STOCK = $this->SqlModel->runQuery($Q_STOCK);
						foreach($RS_STOCK as $AR_STOCK){
						$net_pay_amount +=$AR_STOCK['post_amount'];
						?>
                          <tr class="<?php echo ($AR_STOCK['stock_sts']=="R")? "text-danger":""; ?>">
                            <td align="center" class="cmntext"><?php echo $Ctrl;?></td>
                            <td align="left" class="cmntext"><?php echo $AR_STOCK['post_title'];?></td>
                            <td align="left" class="cmntext"><?php echo number_format($AR_STOCK['product_price']);?></td>
                            <td width="12%" align="center" class="cmntext"><?php echo number_format($AR_STOCK['post_price']);?></td>
                            <td width="12%" align="center" class="cmntext"><?php echo $AR_STOCK['post_pv'];?></td>
                            <td width="19%" align="center" class="cmntext"><?php echo $AR_STOCK['post_qty'];?></td>
                            <td width="19%" align="center" class="cmntext"><strong><?php echo number_format($AR_STOCK['post_amount']);?></strong></td>
                          </tr>
                          <?php 
						$Ctrl++;
						} 
						?>
                          <tr>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="" class="cmntext">&nbsp;</td>
                            <td align="" class="cmntext"><strong>TOTAL</strong></td>
                            <td align="" class="">&nbsp;<strong><?php echo number_format($net_pay_amount,2);?></strong></td>
                          </tr>
                          <tr>
                            <td colspan="7" align="right" class="cmntext"><strong>(Rupees <?php echo convert_number($net_pay_amount);?> Only)</strong></td>
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
<?php jquery_validation();  ?>
<script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/jquery/jquery.print.js"></script> 
<script type="text/javascript">
	$(function(){$( "#PrintImg" ).click(function(){$( "#Letter" ).print(); return( false );});});
</script>
</body>
</html>
