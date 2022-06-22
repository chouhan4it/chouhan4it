<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(2);
$member_id = $this->session->userdata('mem_id');
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
       
        <h4 class="page-title">My Cart</h4>
        <ol class="breadcrumb">
          <li class=""> <a href="<?php echo $model->getShopType(); ?>">Products</a></li>
          <li class="active"> My Cart </li>
        </ol>
      </div>
    </div>
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <div class="row">
          <div class="col-lg-12">
            <div class="card-box">
              <h5 class="text-muted text-uppercase m-t-0 m-b-30"><b>My Cart</b></h5>
              <table class="table table-actions-bar">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Product</th>
                    <th align="right">Amount</th>
                    <th align="right">Bv </th>
                    <th align="right">Qty</th>
                    <th align="right">Total</th>
                    <th align="right">&nbsp;</th>
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
					<td align="left"><i class="fa fa-inr"></i> <?php echo number_format($AR_DT['cart_price'],2); ?></td>
					<td align="left"><?php echo $AR_DT['cart_bv']; ?></td>
					<td align="left">
						<input type="cart_qty" style="width:60px;" id="cart_qty<?php echo $Ctrl; ?>"  
						value="<?php echo $AR_DT['cart_qty']; ?>" class="update_cart" cart_id="<?php echo $AR_DT['cart_id']; ?>"></td>
					<td align="left"><i class="fa fa-inr"></i> <?php echo number_format($AR_DT['cart_total'],2); ?></td>
					<td align="right"><a onClick="return confirm('Make sure, want to delete this record?')" href="<?php echo generateSeoUrlMember("order","cart",array("cart_id"=>_e($AR_DT['cart_id']),"action_request"=>"DELETE")); ?>"><i class="fa fa-trash"></i></a></td>
					</tr>
					<?php $Ctrl++; endforeach; 
					}else{ ?>
					<tr>
					  <td colspan="8" class="text text-danger">No item in you cart</td>
				    </tr>
					
					<?php  } ?>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td align="left">&nbsp;</td>
					  <td align="left">&nbsp;</td>
					  <td align="left">Sub Total </td>
					  <td align="left"><i class="fa fa-inr"></i> <strong><?php echo number_format($net_cat_total,2); ?></strong></td>
					  <td align="left">&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="3" align="left"><a   href="<?php echo $model->getShopType(); ?>" class="btn btn-success">
					    <span><i class="fa fa-arrow-left"></i>&nbsp;Continue Shopping</span></a></td>
					  <td colspan="5" align="right"><a <?php echo ($net_cat_total==0)? "disabled='disabled'":""; ?> href="<?php echo generateSeoUrlMember("order","shipping",""); ?>" class="btn btn-purple">
					    <span>&nbsp;Proceed to Checkout <i class="fa fa-arrow-right"></i></span></a></td>
				    </tr>
                </tbody>
              </table>
            </div>
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
<script type="text/javascript">
	$(function(){
		$(".update_cart").on('blur',function(){
			var cart_id = $(this).attr("cart_id");
			var cart_qty = $(this).val();
			if(cart_qty>=1){
				var URL_LOAD = "<?php echo generateSeoUrlMember("json","jsonhandler",""); ?>";
				var data = {
					switch_type : "UPDATE_CART",
					cart_id : cart_id,
					cart_qty : cart_qty
				};
				$.getJSON(URL_LOAD,data,function(JsonEval){
					if(JsonEval){
						if(JsonEval.cart_qty>0){
							window.location.reload();
						}
					}else{
						alert("Please enter valid quantity?");
						return false;
					}
				});
			}else{
				alert("Please enter valid quantity?");
				return false;
			}
		});
	});
</script>
</html>
