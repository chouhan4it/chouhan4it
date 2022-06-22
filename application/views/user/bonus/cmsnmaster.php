<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');

if($_REQUEST['process_id']>0){
	$process_id = FCrtRplc($_REQUEST['process_id']);
	$SrtWhr .=" AND tcms.process_id='$process_id'";
	$SrchQ .="&process_id=$process_id";
}

$QR_PAGES = "SELECT tcms.* 
		     FROM tbl_cmsn_mstr_sum AS tcms
		     WHERE tcms.member_id='$member_id' 
			 $SrtWhr 
		     GROUP BY tcms.member_id, tcms.process_id 
		     ORDER BY tcms.process_id DESC";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);
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
        <h4 class="page-title">Commission Statement</h4>
        <p class="text-muted page-title-alt">Your Monthly Commission</p>
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
                  <div class="box-wrap clear">
                    <div class="row">
                      <div class="col-md-4">
                        <form id="form-search" name="form-search" method="get" action="<?php echo generateMemberForm("bonus","cmsnstatement",""); ?>">
                          <div class="form-group">
                          	<br>
                            <select name="process_id" id="process_id" class="form-control" style="width:200px;">
                              <option value="">-----Select Month-----</option>
                              <?php echo DisplayCombo($_REQUEST['process_id'],"PROCESS_MEM"); ?>
                            </select>
                          </div>
                          <input class="btn btn-sm btn-primary m-t-n-xs" value=" Show Statement " type="submit">
                          <a href="<?php  echo generateSeoUrlMember("bonus","cmsnstatement",array()); ?>" class="btn btn-sm btn-default m-t-n-xs" value=" Reset ">Reset</a>
                        </form>
                      </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="wallet_deposit_wrapper">
                      <div class="row">
                        <div class="col-sm-12">
                        <?php /*
                          <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                              <tr role="row">
                                <th class="">Cycle No</th>
                                <th class="">Diff. Income</th>
                                <th class="">Sr. Additional</th>
                                <th class="">Leadership Bonus</th>
                                <th class="">Car Budget</th>
                                <th class="">House Budget</th>
                                <th class="">Gross</th>
                                <th class="">TDS</th>
                                <th class="">Processing</th>
                                <th class="">Charity</th>
                                <th class="">Net</th>
                                <th class="">&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
									if($PageVal['TotalRecords'] > 0){
									$Ctrl=1;
									foreach($PageVal['ResultSet'] as $AR_DT):
								?>
                              <tr class="odd" role="row">
                                <td class="sorting_1"><?php echo $AR_DT['process_id'] ?></td>
                                <td><?php echo number_format($AR_DT['cadre_diffrential'],2); ?></td>
                                <td><?php echo number_format($AR_DT['sr_additional'],2); ?></td>
                                <td><?php echo number_format($AR_DT['sr_leadership'],2); ?></td>
                                <td><?php echo number_format($AR_DT['car_budget'],2); ?></td>
                                <td><?php echo number_format($AR_DT['house_budget'],2); ?></td>
                                <td><?php echo number_format($AR_DT['total_cmsn'],2); ?></td>
                                <td><?php echo number_format($AR_DT['tds'],2); ?></td>
                                <td><?php echo number_format($AR_DT['processing'],2); ?></td>
                                <td><?php echo number_format($AR_DT['charity_charge'],2); ?></td>
                                <td><?php echo number_format($AR_DT['net_cmsn'],2); ?></td>
                                <td><a href="<?php echo generateSeoUrlMember("bonus","cmsnstatement",""); ?>?process_id=<?php echo $AR_DT['process_id']; ?>">View</a></td>
                              </tr>
                              <?php endforeach;
									}else{
									?>
                              <tr class="odd" role="row">
                                <td colspan="8">No Record Found</td>
                              </tr>
                              <?php 
									}
								 ?>
                            </tbody>
                          </table>
						  */ ?>
                        </div>
                      </div>
                      <?php /*
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
                      */ ?>
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