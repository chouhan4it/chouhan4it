<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$AR_ADD = $model->getAddress($member_id);
?>
<!DOCTYPE html>
<html>
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card-box">
          <h4 class="m-t-0 header-title"><b>Order Preference</b></h4>
          <p class="text-muted m-b-30 font-13"> <strong>Note :</strong> Please fix your preferred shipping address/payment details before proceeding to shop online for a swift and speedy ordering experience. </p>
          <div class="row m-b-30">
            <div class="col-sm-12">
             
              <form  role="form" name="form-valid" id="form-valid" method="post" action="<?php echo generateMemberForm("order","shoppingtype",""); ?>">
               
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Order Method:</label>
                       <div class="col-xs-12 col-sm-9">
                          <div class="clearfix">
                             <!-- <input type="radio" name="order_method" id="order_method" <?php echo checkRadio($_REQUEST['order_method'],"GRAPH",true); ?> value="GRAPH">
                              Graphical &nbsp;&nbsp;-->
                              <input type="radio" name="order_method" id="order_method" <?php echo checkRadio($_REQUEST['order_method'],"TAB",true); ?> value="TAB">
                              Tabular </div>
                     </div>
                  </div>
				  <div class="clearfix">&nbsp;</div>
				  <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Order To:</label>
                       <div class="col-xs-12 col-sm-9">
                          <div class="clearfix">
						  	<select name="order_to" id="order_to">
								<option value="self_order">Self Order</option>
								<option value="dp_order">DP order</option>
							</select>
                           </div>
                     </div>
                  </div>
				   <div class="clearfix">&nbsp;</div>
				  <!--<div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Billing Option:</label>
                       <div class="col-xs-12 col-sm-9">
                          <div class="clearfix">
						  	<select name="billing_type" id="billing_type">
								<option value="Normal">Normal</option>
							</select>
                           </div>
                     </div>
                  </div>-->
				  <div class="clearfix">&nbsp;</div>
				  <div class="form-group">
                       <div class="col-xs-12 col-sm-12">
                          <div class="clearfix">
						  	  <button type="submit" name="submitShoppingType" value="1" class="btn btn-success waves-effect waves-light m-l-10 btn-md">Go</button>
                           </div>
                     </div>
                  </div>
                
              
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End row -->
    <!-- Footer -->
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
    <!-- End Footer -->
  </div>
  <!-- end container -->
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
	});
</script>
</html>
