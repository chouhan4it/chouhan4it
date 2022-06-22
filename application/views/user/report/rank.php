<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$member_id = $this->session->userdata('mem_id');
	
	
	$QR_PAGES="SELECT tmr.* , tm.user_id, tr.rank_name
			  FROM tbl_mem_rank AS tmr 
			  LEFT JOIN  tbl_members AS tm ON tm.member_id=tuh.member_id
			  LEFT JOIN tbl_rank AS tr ON tr.rank_id=tmr.to_rank_id
			  WHERE   tmr.member_id='".$member_id."'
			  ORDER BY tmr.history_id ASC";
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
        <h4 class="page-title">My Rank</h4>
        <p class="text-muted page-title-alt">Your Rank history</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet">
          <div class="portlet-body" >
            <div class="row"> 
              <div class="col-lg-12">
                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="">
                  <div class="row">
                    <div class="col-sm-12">
                      <table id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                          <tr role="row">
                            <th   style="width: 255px;" colspan="1" rowspan="1" >Srl No </th>
                            <th  style="width: 526px;" rowspan="1"  tabindex="0">Rank</th>
                            <th  style="width: 526px;" rowspan="1"  tabindex="0">Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
							if($PageVal['TotalRecords'] > 0){
							$Ctrl=1;
								foreach($PageVal['ResultSet'] as $AR_DT):
							?>
                          <tr class="odd" role="row">
                            <td align="center" class="sorting_1"><?php echo $Ctrl; ?></td>
                            <td align="center"><?php echo $AR_DT['rank_name']; ?></td>
                            <td align="center"><?php echo DisplayDate($AR_DT['date_time']); ?></td>
                          </tr>
                          <?php $Ctrl++; endforeach;	
						  }else{ ?>
                         
                          <tr>
                            <td colspan="3" ><div class="text text-danger">&nbsp;</div></td>
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
