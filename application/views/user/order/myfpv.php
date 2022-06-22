<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$flddToday = InsertDate(getLocalTime());
$flddExpire = AddToDate($flddToday, '-1 Day');
$QR_PAGES = "SELECT A.*, (CASE A.use_status WHEN 'N' THEN 'Unused' WHEN 'Y' THEN 'Used' ELSE 'Expired' END) AS current_status, 
			 B.user_id, CONCAT_WS(' ',B.first_name,B.last_name) AS full_name, B.city_name, B.member_mobile, 
			 C.rank_name, D.user_id AS from_id, CONCAT_WS(' ',D.first_name,D.last_name) AS from_name, D.city_name AS from_city 
			 FROM tbl_coupon AS A 
			 LEFT JOIN tbl_members AS B ON A.assigned_to=B.member_id 
			 LEFT JOIN tbl_rank AS C ON B.rank_id=C.rank_id 
			 LEFT JOIN tbl_members AS D ON A.from_id=D.member_id 
			 WHERE 1 AND A.assigned_to='$member_id' AND A.ready_to_use='Y' 
			 ORDER BY A.coupon_id DESC";
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
        <h4 class="page-title">Free Product Vouchers</h4>
        <p class="text-muted page-title-alt">List of all free product vouchers received on your User Id</p>
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
                  <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
                <tr class="">
                    <td align="center" colspan="6" bgcolor="#333399" style="color:#FFF;"><strong>PURCHASE INFORMATION</strong></td>
                    <td align="center" colspan="6" bgcolor="#336633" style="color:#FFF;"><strong>COUPON INFORMATION</strong></td>
                </tr>
                <tr class="">
                    <td align="center"><strong>SR NO</strong></td>
                    <td align="center"><strong>User Id</strong></td>
                    <td align="left"><strong>User Name</strong></td>
                    <td align="right"><strong>INVOICE NO</strong></td>
                    <td align="right"><strong>INVOICE DATE</strong></td>
                    <td align="right"><strong>INVOICE AMOUNT</strong></td>
                    <td align="left"><strong>FPV NO</strong></td>
                    <td align="right"><strong>FPV VALUE</strong></td>
                    <td align="center"><strong>ASSIGNED ON</strong></td>
                    <td align="center"><strong>EXPIRES ON</strong></td>
                    <td align="right"><strong>DAYS LEFT</strong></td>
                    <td align="center"><strong>STATUS</strong></td>
                </tr>
				<?php
                $Ctrl = $PageVal['RecordStart']+1;
				foreach($PageVal['ResultSet'] as $AR_LIST):
				?>
                <tr>
                    <td align="center"><?php echo $Ctrl;?></td>
                    <td align="center"><?php echo ($AR_LIST['from_id']!='')? $AR_LIST['from_id']:'NA';?></td>
                    <td align="left"><?php echo ($AR_LIST['from_name']!='')? strtoupper($AR_LIST['from_name']):'NA';?></td>
                    <td align="right"><?php echo ($AR_LIST['invoice_no']!='')? $AR_LIST['invoice_no']:'NA';?></td>
                    <td align="right"><?php echo DisplayDate($model->getInvoiceDate($AR_LIST['invoice_no']));?></td>
                    <td align="right"><?php echo $AR_LIST['order_rcp'];?></td>
                    <td align="left"><?php echo $AR_LIST['coupon_no'];?></td>
                    <td align="right"><?php echo $AR_LIST['coupon_val'];?></td>
                    <td align="center"><?php echo DisplayDate($AR_LIST['assigned_on']);?></td>
                    <td align="center"><?php echo DisplayDate($AR_LIST['expires_on']);?></td>
                    <td align="center">
					<?php 
					if($AR_LIST['current_status']!='Unused') echo 0; else echo dayDiff($flddExpire,$AR_LIST['expires_on']);
					?>
                    </td>
                    <td align="center"><?php echo $AR_LIST['current_status']; ?></td>
                </tr>
				<?php
                $Ctrl++;
				endforeach;
                ?>
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
</html>