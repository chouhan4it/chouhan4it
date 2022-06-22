<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$cart_session = $this->session->userdata('session_id');


$cart_total = $model->getCartTotal();
$cart_pv = $model->getCartTotalPv();
#$offer_id = $model->checkMemberOffer($cart_total,$cart_pv,$member_id,"","");

$QR_PAGES = "SELECT tof.* FROM tbl_offer AS tof 
			WHERE tof.delete_sts>0 AND tof.offer_sts>0  
			AND  tof.offer_id='".$offer_id."'
			AND tof.offer_expiry>=CURDATE()
			GROUP BY tof.offer_id  
			ORDER BY tof.offer_id DESC";
$PageVal = DisplayPages($QR_PAGES, 1, $Page, $SrchQ);
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
        
		  
          <div class="row">
            <div class="col-lg-12">
              <div class="portlet">	
			  <div id="ajaxMessage"></div>
			  	<?php echo get_message(); ?>
                <div class="portlet-heading bg-inverse">
                  <h3 class="portlet-title"> Offers </h3>
                  <div class="portlet-widgets"> 
					 
					  <span class="divider"></span> 
					  
				  </div>
                  <div class="clearfix"></div>
                </div>
				<form action="<?php echo generateMemberForm("order","offer",""); ?>" method="post" name="form-valid" id="form-valid">
                <div id="bg-inverse" class="panel-collapse collapse in">
                  <div class="portlet-body">					
                    <div class="table-responsive">
                      <table class="table table-actions-bar">
                        <thead>
                          <tr>
                            <th>Srl No </th>
                            <th>&nbsp;</th>
                            <th>Offer Code</th>
                            <th>Offer  Name</th>
                            <th>MRP</th>
                            <th>AP</th>
                            <th >BV</th>
                            <th>Select</th>
                          </tr>
                        </thead>
                        <tbody>
						<?php 
						
						$Ctrl=1;
						if($PageVal['TotalRecords']>0){
						foreach($PageVal['ResultSet'] as $AR_DT):
						
						?>
                          <tr>
                            <td><?php echo $Ctrl; ?></td>
                            <td><img src="<?php echo $model->getOfferPhoto($AR_DT['offer_id']); ?>" class="thumb-sm" > </td>
                            <td><?php echo $AR_DT['offer_code']; ?></td>
                            <td><?php echo $AR_DT['offer_title']; ?></td>
                            <td><b><?php echo $AR_DT['offer_price']; ?></b> </td>
                            <td><?php echo $AR_DT['offer_price']; ?> </td>
                            <td><?php echo $AR_DT['offer_pv']; ?></td>
                            <td><input type="radio" name="offer_id" id="offer_id"  value="<?php echo $AR_DT['offer_id']; ?>" class="validate[required]"></td>
                          </tr>
                          
				  <?php  $Ctrl++; endforeach; 
				  	}else{ redirect_member("order","shipping",""); }  ?>
					<tr>
					  <td colspan="3"><a class="btn w-sm btn-danger"   href="<?php echo generateSeoUrlMember("order","shipping",""); ?>" ><i class="fa fa-arrow-right"></i>  Skip & Continue </a></td>
					  <td colspan="5" align="right"><button  type="submit" name="submitOffer" value="1"  class="btn btn-purple pull-right col-md-offset-1" id="checkout_button"> <span>Continue <i class="fa fa-arrow-right"></i></span></button></td>
					  </tr>
				  
                        </tbody>
                      </table>
                    </div>
					<div class="clearfix">&nbsp;</div>
						<strong>Terms & Condition</strong>
					<div class="clearfix">&nbsp;</div>
					<div class="row">
						<div class="col-md-12">
							<ul>
								<li>Offer for <?php echo WEBSITE; ?> Consultants only</li>
								<li>Offer for single invoice only</li>
								<li>One offer applicable at one time</li>
								
							</ul>
						</div>
					</div>
					
                  </div>
                </div>
				</form>
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
