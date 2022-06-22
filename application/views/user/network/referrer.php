<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$member_id = $this->session->userdata('mem_id');
	$AR_MEM = $model->getMember($member_id);
	
	if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);	
	$StrWhr .=" AND DATE(tm.date_join) BETWEEN '".$from_date."' AND '".$to_date."'";
	$SrchQ  = "&from_date=$from_date&to_date=$to_date";
}
	
	$QR_PAGE = "SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_id AS spsr_user_id ,
		 tree.nlevel, tree.nleft, tree.nright FROM tbl_members AS tm	
		 LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
		 LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id
		 WHERE tm.sponsor_id='".$member_id."' AND tm.delete_sts>0   $StrWhr
		 ORDER BY tm.member_id ASC";
	$PageVal = DisplayPages($QR_PAGE, 50, $Page, $SrchQ);
	
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>

<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
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
        <h4 class="page-title">My Direct Enrollments</h4>
        <p class="text-muted page-title-alt">Your referral</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card-box">
          <div class="row">
              <div class="col-md-3">
                <form method="post" action="<?php echo generateMemberForm("network","referrer",""); ?>" enctype="multipart/form-data" name="form-page" id="form-page">
               
                  <div class="form-group">
                   <label>Date of Joining</label>
                   	<div class="input-group">
                  <input class="form-control col-xs-3 col-sm-3  validate[required] date-picker" name="from_date" id="id-date-picker-1" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                  <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                  </div>
                  <div class="form-group">
                    	<div class="input-group">
                  <input class="form-control col-xs-3 col-sm-3  validate[required] date-picker" name="to_date" id="id-date-picker-1" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                  <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                  </div>
				  
                  <input class="btn btn-sm btn-primary m-t-n-xs" name="searchRequest" value="Search" type="submit">
				  <input type="button" class="btn btn-sm btn-danger m-t-n-xs" name="reset" id="reset" value="Reset" onClick="window.location.href='?'">
                </form>
              </div>
          </div>
		<div class="clearfix">&nbsp;</div>
          <div class="table-responsive">
            <table class="table table-actions-bar">
              <thead>
                <tr>
                  <th>Srl #</th>
                  <th>Profile</th>
                  <th>Full Name</th>
                  <th>User Id </th>
                  <th>D.O.J</th>
                  <th>Position</th>
                  <th>City</th>
                  <th>State</th>
                </tr>
              </thead>
              <tbody>
			  	 <?php 
				$Ctrl=$PageVal['RecordStart']+1;
				foreach($PageVal['ResultSet'] as $AR_DT):
				$left_right = $model->getMemberPlacement($AR_DT['member_id']);
				$rank_name = $model->getRankName($AR_DT['rank_id']);
				?>
                <tr>
                  <td><?php echo $Ctrl; ?> </td>
                  <td><img alt="<?php echo $AR_DT['first_name']; ?>" class="img-circle" width="25" height="25" src="<?php echo getMemberImage($AR_DT['member_id']); ?>"></td>
                  <td><?php echo $AR_DT['first_name']; ?> <?php echo $AR_DT['last_name']; ?></td>
                  <td><?php echo $AR_DT['user_id']; ?></td>
                  <td><?php echo DisplayDate($AR_DT['date_join']); ?></td>
                  <td><?php echo $left_right; ?></td>
                  <td><?php echo $AR_DT['city_name']; ?></td>
                  <td><?php echo $AR_DT['state_name']; ?></td>
                </tr>
               
				 <?php $Ctrl++; endforeach;	?>
				 <tr>
			  <td colspan="8"><?php
			  	if($PageVal['TotalRecords']==0 && $PageRight['TotalRecords']==0){
					echo '<div class="text text-danger">No direct referral found</div>';
				}
			   ?></td>
			  </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- end col -->
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
	});
</script>
</html>
