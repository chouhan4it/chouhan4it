<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$today_date = InsertDate(getLocalTime());	
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	
	$member_id = $this->session->userdata('mem_id');
	
	if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
		$date_from = InsertDate($_REQUEST['date_from']);
		$date_to = InsertDate($_REQUEST['date_to']);	
		$StrWhr .=" AND DATE(tmr.date_time) BETWEEN '".$date_from."' AND '".$date_to."'";
		$SrchQ  = "&date_from=$date_from&date_to=$date_to";
	}
		
	$QR_MEM = "SELECT tmr.*,  CONCAT_WS(' ',tm.first_name,tm.last_name) AS  full_name, 
		   tm.user_id, tr.rank_name, tr.rank_cmsn
		   FROM  tbl_mem_rank AS tmr	
		   LEFT JOIN  tbl_rank AS tr  ON tr.rank_id=tmr.to_rank_id
		   LEFT JOIN  tbl_members AS tm  ON tm.member_id=tmr.member_id
		   WHERE tmr.member_id='".$member_id."'
		   $StrWhr 
		   ORDER BY tmr.from_rank_id ASC, tmr.date_time DESC";
	$PageVal = DisplayPages($QR_MEM, 20, $Page, $SrchQ);
	
?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
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
        <h4 class="page-title">Reward  Bonus</h4>
        <p class="text-muted page-title-alt">Your  Reward  Bonus</p>
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
                <div class="box-body">
                  <div class="box-wrap clear"> <br>
                    <div class="row">
                      <div class="col-md-4">
                        <form method="post" action="<?php echo generateMemberForm("report","rewardincome",""); ?>" enctype="multipart/form-data" name="form-page" id="form-page">
                          <div class="form-group">
                            <div class="input-group">
                              <input class="form-control col-xs-3 col-sm-3  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" type="text"  />
                              <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                          </div>
                          <div class="form-group">
                            <div class="input-group">
                              <input class="form-control col-xs-3 col-sm-3  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" type="text"  />
                              <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                          </div>
                          <input class="btn btn-sm btn-primary m-t-n-xs" name="searchRequest" value="Search" type="submit">
                          <input type="button" class="btn btn-sm btn-danger m-t-n-xs" name="reset" id="reset" value="Reset" onClick="window.location.href='?'">
                        </form>
                      </div>
                    </div>
                    <br>
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="wallet_deposit_wrapper">
                      <div class="row">
                        <div class="col-sm-12">
                          <table aria-describedby="wallet_deposit_info" role="grid" id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                              <tr role="row">
                                <th>Sr. No </th>
                                <th >Date </th>
                                <th>Rank</th>
                                <th>Reward </th>
                                <th>Pair Required</th>
                                <th>Pair Achived</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
						if($PageVal['TotalRecords'] > 0){
						$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
               			 ?>
                              <tr>
                                <td><?php echo $Ctrl; ?></td>
                                <td><?php echo $AR_DT['date_time']; ?></td>
                                <td><?php echo $AR_DT['rank_name']; ?></td>
                                <td><?php echo $AR_DT['rank_cmsn']; ?></td>
                                <td><?php echo number_format($AR_DT['pair_set']); ?></td>
                                <td><?php echo number_format($AR_DT['pair_get']); ?></td>
                              </tr>
                              <?php $Ctrl++; 
							endforeach;
						}
						?>
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
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>

<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		
	});
</script>
</html>
