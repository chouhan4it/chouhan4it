<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$StrWhr .=" AND (ord.member_id='".$member_id."')";

if($_REQUEST['order_no']!=''){
	$order_no = FCrtRplc($_REQUEST['order_no']);
	$StrWhr .=" AND ( ord.order_no = '$order_no' )";
	$SrchQ .="&order_no=$order_no";
}

$QR_PAGES = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id ,
			 tad.current_address AS order_address, tos.name AS order_state
			 FROM tbl_orders AS ord
			 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
			 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
			 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
			 WHERE ord.order_id>0 AND ord.id_order_state IN(4,5)
			 $StrWhr
			 GROUP BY ord.order_id
			 ORDER BY ord.order_id DESC";
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
        <h4 class="page-title">Order Return</h4>
        <p class="text-muted page-title-alt">Your Order Return</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
	  <?php echo get_message(); ?>
      <div class="col-lg-12">
        <div class="portlet">
          <!-- /primary heading -->
          <div class="portlet-body" >
            <div class="row"> 
              <div class="col-xs-12">
                <div>
                  <table id="" class="table">
                    <thead>
                      <tr>
                        <td class="center">ID</td>
                        <td>Order No </td>
                        <td>No of Product</td>
                        <td>Total</td>
                        <td>Payment </td>
                        <td>Status</td>
                        <td>Date</td>
                        <td>&nbsp;</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr>
                        <td width="38" class="center"><label class="pos-rel"> <?php echo $AR_DT['order_id']; ?> <span class="lbl"></span> </label>
                        </td>
                        <td width="143"><?php echo $AR_DT['order_no']; ?> </td>
                        <td width="134"><?php echo $AR_DT['full_name']; ?> </td>
                        <td width="158"><?php echo number_format($AR_DT['total_paid_real'],2); ?></td>
                        <td width="164"><?php echo $AR_DT['payment']; ?></td>
                        <td width="165"><?php echo $AR_DT['order_state']; ?></td>
                        <td width="146"><?php echo $AR_DT['date_add']; ?></td>
                        <td width="94">
						<a class="btn btn-primary" href="<?php echo generateSeoUrlMember("order","orderview",array("order_id"=>_e($AR_DT['order_id']))); ?>">View</a> 
						</td>
                      </tr>
                      <?php $Ctrl++; endforeach; }else{ ?>
                      <tr>
                        <td colspan="8" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <div class="row">
                    <div class="col-xs-6">
                      <div aria-live="polite" role="status" id="dynamic-table_info" class="dataTables_info"> Showing <?php echo $PageVal['RecordStart']+1; ?> to <?php echo  count($PageVal['ResultSet']); ?> of <?php echo $PageVal['TotalRecords']; ?> entries </div>
                    </div>
                    <div class="col-xs-6">
                      <div id="dynamic-table_paginate" class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                          <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine({
				'custom_error_messages': {
					'#pin_code': {
						'custom[integer]': {
							'message': "Not a valid postal code ."
						}
					}
					,'#member_mobile': {
						'custom[integer]': {
							'message': "Not a valid phone no."
						}
					}
					
				}
			});
		
	});
</script>
</html>
