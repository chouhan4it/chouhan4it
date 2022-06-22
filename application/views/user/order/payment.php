<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(2);
$member_id = $this->session->userdata('mem_id');
$wallet_id = $model->getWallet(WALLET1);

$AR_LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");

$cart_session = $this->session->userdata('session_id');
$QR_PAGES = "SELECT tc.*,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_pv,  tpl.update_date , GROUP_CONCAT(tpc.category_id) AS category_id,
			GROUP_CONCAT(tt.tag_name) AS tags_name, COUNT(tpv.view_id) AS view_ctrl, COUNT(tpr.review_id) AS review_ctrl
			FROM tbl_cart AS tc
			LEFT JOIN tbl_post AS tp ON tp.post_id=tc.post_id 
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tag_id,tpl.post_tags)
			LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
			LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
			WHERE tp.delete_sts>0  AND tc.cart_session='".$cart_session."'
			GROUP BY tc.cart_id  
			ORDER BY tc.cart_id ASC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
</head>
<body>
<!-- Navigation Bar-->
<?= $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container">
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Payment</h4>
        <ol class="breadcrumb">
          <li> <a href="<?php echo generateSeoUrlMember("order","shipping",""); ?>">Shipping</a> </li>
          <li class="active"> Payment </li>
        </ol>
      </div>
    </div>
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <div class="row">
          <div class="col-lg-12">
            <div class="card-box">
              <h5 class="text-muted text-uppercase m-t-0 m-b-30"><b>Payment</b></h5>
              <?php get_message(); ?>
              <table class="table table-actions-bar">
                <thead>
                  <tr>
                    <th >No</th>
                    <th >Item</th>
                    <th >Product</th>
                    <th >Code</th>
                    <th >Amount</th>
                    <th >Qty</th>
                    <th >Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
			if($PageVal['TotalRecords'] > 0){
			$Ctrl=1;
			foreach($PageVal['ResultSet'] as $AR_DT):
				$net_cat_total +=$AR_DT['cart_total'];
			?>
                  <tr>
                    <td><?php echo $Ctrl; ?> </td>
                    <td><img src="<?php echo $model->getDefaultPhoto($AR_DT['post_id']); ?>" class="thumb-md"> </td>
                    <td><?php echo $AR_DT['cart_title']; ?></td>
                    <td align="left"><?php echo $AR_DT['cart_code']; ?></td>
                    <td align="left"><i class="fa fa-inr"></i> <?php echo number_format($AR_DT['cart_price'],2); ?></td>
                    <td align="left"><?php echo $AR_DT['cart_qty']; ?></td>
                    <td align="left"><?php echo $AR_DT['cart_total']; ?></td>
                  </tr>
                  <?php $Ctrl++; endforeach; 
				}else{ ?>
                <div class="alert alert-danger"> No item in your cart</div>
                <?php  } ?>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td align="left"><strong>Order Total</strong> </td>
                  <td align="left"><strong><?php echo number_format($net_cat_total,2); ?></strong></td>
                </tr>
                </tbody>
                
              </table>
              <form action="<?php echo generateMemberForm("order","payment",""); ?>" method="post" name="form-payment" id="form-payment" onSubmit="return confirm('Make sure, want proceed with selection?')">
                <div class="row">
                <div class="panel panel-border panel-custom">
                  <div class="panel-heading">
                    <h3 class="panel-title">Payment Option</h3>
                  </div>
                </div>
                <div class="cold-md-6">
                <div class="form-group">
                  <textarea  name="order_message"  class="form-control validate[required] input-large" placeholder="You can write order message"  id="order_message"><?php echo $_REQUEST['order_message']; ?></textarea>
                </div>
                <div class="cold-md-12">
                  <input type="radio" class="validate[required]" name="payment_type" id="ONLINE" value="ONLINE" >
					<label for="ONLINE">
							<strong> Online</strong> 
							<small>(Multiple payment option is available)</small>
				   </label>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="cold-md-12">
                  <input type="radio" class="validate[required]"  name="payment_type" id="WALLET" value="WALLET">
					<label for="WALLET">
							<strong> e-Wallet</strong> 
							<small>(&nbsp;You have <i class="fa fa-inr"></i> <?php echo number_format($AR_LDGR['net_balance'],2); ?> in your wallet)</small>
				   </label>
                </div>
                </div>
                </div>
                <div class="row">
                  <div class="cold-md-12 pull-right">
                    <button type="submit"  name="submit-payment" value="1" class="btn w-sm btn-default"> <i class="fa fa-check"></i> Confirm Order</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
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
		$("#form-payment").validationEngine();
	});
</script>
</html>
