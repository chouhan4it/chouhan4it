<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$AR_MEM = $model->getMember($member_id);
$QR_PAGES = "SELECT tf.* , mth.month
			 FROM tbl_form16 AS tf 
			 LEFT JOIN tbl_months AS mth ON mth.month_id=tf.form_month
			 WHERE  tf.pan_no='".$AR_MEM['pan_no']."' 
			 ORDER BY tf.form_id DESC";
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
        <h4 class="page-title">Form 16 A</h4>
        <p class="text-muted page-title-alt">Download your form 16</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <!-- /primary heading -->
          <div class="portlet-heading">
            <div class="clearfix"></div>
          </div>
          <div class="portlet-body" >
            <div class="row">
                    <div class="col-sm-12">
                      <table aria-describedby="wallet_deposit_info" role="grid" id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                    <tr>
                      <th width="22" class="center"> <label class="pos-rel"> ID <span class="lbl"></span> </label>                      </th>
                      <th width="157" align="center">Year</th>
                      <th width="157" align="center">Month</th>
                      <th width="157" align="center">File</th>
                      <th width="101" align="center">Date</th>
                      <th width="101" align="center">&nbsp;</th>
                    </tr>
                  </thead>
                  
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr>
                        <td class="center"><label class="pos-rel"> <?php echo $AR_DT['form_id']; ?> <span class="lbl"></span> </label>                        </td>
                        <td align="left"><?php echo $AR_DT['form_year']; ?></td>
                        <td align="left"><?php echo $AR_DT['month']; ?>(<?php echo $AR_DT['form_month']; ?>)</td>
                        <td align="left"><a target="_blank" href="<?php echo $AR_DT['form_path']; ?>"><?php echo ($AR_DT['form_file']); ?></a></td>
                        <td align="center"><?php  echo DisplayDate($AR_DT['date_time']);?></td>
                        <td align="center"><a target="_blank" href="<?php echo  $AR_DT['form_path']; ?>"><i class="fa fa-download"></i></a></td>
                      </tr>
                      <?php $Ctrl++; endforeach; ?>
                      <?php  }else{ ?>
                      <tr>
                        <td colspan="6" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No form found</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                      </table>
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
