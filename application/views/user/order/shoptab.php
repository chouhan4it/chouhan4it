<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$cart_session = $this->session->userdata('session_id');



if(($_REQUEST['category_id'])>0){
	$category_id = ($_REQUEST['category_id']);
	$StrWhr .=" AND (tpc.category_id = '".$category_id."')";
	$SrchQ .="&category_id=$_REQUEST[category_id]";
}

if(($_REQUEST['post_title'])!=''){
	$post_title = ($_REQUEST['post_title']);
	$StrWhr .=" AND (tpl.post_title LIKE '%".$post_title."%' OR tpl.post_desc LIKE '%".$post_title."%')";
	$SrchQ .="&post_title=$_REQUEST[post_title]";
}




$QR_PAGES = "SELECT tp.*,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_dp_price, tpl.post_pv, tpl.post_bv,  tpl.update_date , GROUP_CONCAT(tpc.category_id) AS category_id
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			WHERE tp.delete_sts>0  AND tp.post_sts>0  
			$StrWhr 
			GROUP BY tp.post_id  
			ORDER BY tp.post_id DESC";
$PageVal = DisplayPages($QR_PAGES, 500, $Page, $SrchQ);


$first_pv = $model->getValue("CONFIG_FIRST_PV");
				
$AR_SELF = $model->getSumSelfCollection($member_id,"","");
$total_pv  = $AR_SELF['total_bal_pv'];
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
    <!-- Page-Title -->
    <!-- Page-Title -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card-box">
          <?= $this->load->view(MEMBER_FOLDER.'/inccart'); ?>
		  
          <div class="row">
            <div class="col-lg-12">
              <div class="portlet">	
			  <div id="ajaxMessage"></div>
			  	<?php echo get_message(); ?>
                <div class="portlet-heading bg-inverse">
                  <h3 class="portlet-title"> Products </h3>
                  <div class="portlet-widgets"> 
					  <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a> 
					  <span class="divider"></span> 
					  
				  </div>
                  <div class="clearfix"></div>
                </div>
                <div id="bg-inverse" class="panel-collapse collapse in">
                  <div class="portlet-body">
                    <div class="row m-t-10 m-b-10">
					<form action="<?php echo generateMemberForm("order","shoptab",""); ?>" method="post" name="form-search" id="form-search">
						<div class="col-sm-6 col-lg-3">
                        <div class="h5 m-0"> 
                          <div class="btn-group vertical-middle" data-toggle="buttons">
                            	<select name="category_id" id="category_id" class="form-control"  style="width:250px;" > 
									<option value="">All Category</option>
									<?php DisplayCombo($category_id,"CATEGORY"); ?>
								</select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-lg-7">
                          <div class="form-group contact-search m-b-30">
                            <input type="text" name="post_title" id="post_title" class="form-control" placeholder="Search...">
                           
                          </div>
                      </div>
					   <div class="col-sm-6 col-lg-2">
                          <div class="form-group contact-search m-b-30">
                           		<button name="searchProduct" type="submit" class="btn  btn-success"  value="1">Search</button>
								<button type="button" class="btn  btn-danger" onClick="window.location.href='<?php echo generateSeoUrlMember("order","shoptab",""); ?>'" >Reset</button>
                          </div>
                      </div>
                      </form>
                    </div>
                    <?php if($total_pv<$first_pv){ ?>
						<div class="alert alert-danger">Enjoy all product upto DP price , after  purchase of 700  BV</div>
                    <?php } ?>
                    <div class="table-responsive">
                      <table class="table table-actions-bar">
                        <thead>
                          <tr>
                            <th>Srl No </th>
                            <th>&nbsp;</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>MRP</th>
                            <th>Price</th>
                            <th>BV</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Detail</th>
                          </tr>
                        </thead>
                        <tbody>
						<?php 
						if($PageVal['TotalRecords'] > 0){
						$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						$product_price = $model->getCartPrice($AR_DT['post_id']);
						$sum_product_price +=$product_price;
						?>
                          <tr>
                            <td><?php echo $Ctrl; ?></td>
                            <td><img src="<?php echo $model->getDefaultPhoto($AR_DT['post_id']); ?>" class="thumb-sm" > </td>
                            <td><?php echo $AR_DT['post_code']; ?></td>
                            <td><?php echo $AR_DT['post_title']; ?></td>
                            <td><b><?php echo $AR_DT['post_mrp']; ?></b> </td>
                            <td><b><?php echo $AR_DT['post_price']; ?></b> </td>
                            <td><b><?php echo $AR_DT['post_pv']; ?></b></td>
                            <td>
                              <a rel="<?php echo $AR_DT['post_id']; ?>" class="minus_qty" href="javascript:void(0)"><i class="fa fa-minus"></i></a>&nbsp;&nbsp;
                              <input type="text" name="post_qty" id="post_qty<?php echo $AR_DT['post_id']; ?>" style="width:40px;" value="<?php echo $model->getCartQty($AR_DT['post_id']); ?>" readonly>
                            <a rel="<?php echo $AR_DT['post_id']; ?>" class="plus_qty" href="javascript:void(0)"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;							</td>
                            <td align="center"><span id="post_price<?php echo $AR_DT['post_id']; ?>"><?php echo number_format($product_price,2); ?></span></td>
                            <td><a href="javascript:void(0)" pageName="<?php echo generateSeoUrlMember("order","incproduct",array("post_id"=>_e($AR_DT['post_id']))); ?>" rel="<?php echo $AR_DT['post_id']; ?>" alt="<?php echo $AR_DT['post_title']; ?>" class="table-action-btn open-prod-detail"><i class="md md-info"></i></a> </td>
                          </tr>
                          
				  <?php $Ctrl++; endforeach;  ?>
					<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><strong>Total Amount</strong> : </td>
					<td align="center"><strong id="cart_total_price"><?php echo number_format($sum_product_price,2); ?></strong></td>
					<td>&nbsp;</td>
					</tr>
				  <?php 
				 		 	}else{ 
								echo '<div class="alert alert-danger">No product found</div>';
							} ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end col -->
    </div>
    <!-- Footer -->
     <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
    <!-- End Footer -->
  </div>
  <!-- end container -->
</div>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Select your extra package</h4>
      </div>
      <div class="modal-body" id="myModalBody"></div>
    </div>
  </div>
</div>
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>

<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$(".plus_qty").on('click',function(){
			var post_id = $(this).attr("rel");
			var post_qty = parseInt($("#post_qty"+post_id).val());
			var total_qty = parseInt(post_qty+1);
			if(total_qty>=0){
				process_cart(post_id,total_qty);
			}
			
		});
		$(".minus_qty").on('click',function(){
			var post_id = $(this).attr("rel");
			var post_qty = parseInt($("#post_qty"+post_id).val());
			var total_qty = parseInt(post_qty-1);
			if(total_qty>=0){
				process_cart(post_id,total_qty);
			}
			
		});
		
		
		function process_cart(post_id,total_qty){
			if(post_id>0){
				var data = {
					switch_type : "CART",
					post_id : post_id,
					total_qty : total_qty
				};
				var URL_CART = "<?php echo BASE_PATH; ?>json/jsonhandler";
				$.getJSON(URL_CART,data,function(JsonEval){	
					if(JsonEval){
						if(JsonEval.status>0){
							$("#cart_total_mrp").text(JsonEval.cart_total_mrp);
							$("#cart_total").text(JsonEval.cart_total);
							$("#cart_bv").text(JsonEval.cart_bv);
							$("#cart_pv").text(JsonEval.cart_pv);
							$("#cart_count").text(JsonEval.cart_count);
							$("#post_qty"+post_id).val(total_qty);
														
							$("#post_price"+post_id).text(JsonEval.post_price);
							$("#cart_total_price").text(JsonEval.cart_total);
							
							if(JsonEval.cart_count>0){
								$("#checkout_button").attr("disabled",false);
							}else{
								$("#checkout_button").attr("disabled",true);
							}
						}
					}
				});
			}
		}
		
		$(".open-prod-detail").on('click',function(e){
			e.preventDefault();
			var modal_title = $(this).attr("alt");
			var URL_PATH = $(this).attr("pageName");
			if(URL_PATH!=""){
				$.post(URL_PATH,function(JsonEval){
					if(JsonEval){
						$("#myModalLabel").text(modal_title);
						$('#myModalBody').html(JsonEval);
						$('#myModal').modal({backdrop: 'static', keyboard: false})  
						return false;
					}	
				});
			}
		});
	});
</script>
</html>
