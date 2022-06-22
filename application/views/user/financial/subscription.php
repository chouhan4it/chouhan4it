<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$member_id = $this->session->userdata('mem_id');
	$AR_MEM = $model->getMember($member_id);
	
	
	$QR_PAGE = "SELECT ts.*, tp.pin_name , tm.user_id,
				CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.date_join,
				tmsp.user_id AS spsr_user_id
				FROM ".prefix."tbl_subscription AS ts 
				LEFT JOIN ".prefix."tbl_pintype AS tp ON tp.type_id=ts.type_id
				LEFT JOIN ".prefix."tbl_members AS tm ON tm.member_id=ts.member_id
				LEFT JOIN ".prefix."tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
				WHERE ts.member_id='".$member_id."'
				 $StrWhr
				GROUP BY ts.subcription_id
				ORDER BY ts.subcription_id ASC";
	$PageVal = DisplayPages($QR_PAGE, 500, $Page, $SrchQ);
	
	ExportQuery($QR_PAGE);
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<style type="text/css">
.img-circle {
    border-radius: 50%;
}
.item-pic{
	width:30px;
}
</style>
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
        <h4 class="page-title">My Subscription (Topup)</h4>
        <p class="text-muted page-title-alt">Your Subscription</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <div class="portlet-body" >
            <div class="row"> <?php echo get_message(); ?>
              <div class="col-lg-12">
                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="">
                  <div class="row">
                    <div class="col-sm-12">
                      <table id="" class="table">
                    <thead>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						
			       ?>
                      <tr>
                        <td width="22" rowspan="3" class="center"><label class="pos-rel"> <?php echo $Ctrl; ?> <span class="lbl"></span> </label>                        </td>
                        <td width="112">Full Name : </td>
                        <td width="148"><a href="javascript:void(0)"><?php echo $AR_DT['full_name']; ?></a></td>
                        <td width="126">Order No : </td>
                        <td width="120"><?php echo $AR_DT['order_no']; ?></td>
                        <td width="140">Date: </td>
                        <td width="164"><?php echo DisplayDate($AR_DT['date_from']); ?></td>
                        </tr>
                      <tr>
                        <td>User Id</td>
                        <td><a href="javascript:void(0)"><?php echo $AR_DT['user_id']; ?></a></td>
                        <td>Plan : </td>
                        <td><?php echo $AR_DT['pin_name']; ?></td>
                        <td>Pay Type :</td>
                        <td><?php echo $AR_DT['payment_type']; ?></td>
                      </tr>
                      <tr>
                        <td>D.O.J : </td>
                        <td><?php echo DisplayDate($AR_DT['date_join']); ?></td>
                        <td>Amount : </td>
                        <td><?php echo number_format($AR_DT['net_amount']); ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      
                      <tr>
                        <td colspan="7" class="center"><hr class="divider" style="border-bottom:#F00 1px solid;"></hr></td>
                      </tr>
                      <?php $Ctrl++; endforeach; }else{ ?>
                      <tr>
                        <td colspan="7" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No subscription found</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                    </div>
                  </div>
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
		$("#form-valid").validationEngine();
	});
</script>
</html>
