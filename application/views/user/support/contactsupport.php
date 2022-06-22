<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$member_id = $this->session->userdata('mem_id');
	
	
	$QR_PAGES="SELECT ts.* FROM tbl_support AS ts WHERE ts.from_id='".$member_id."' 	ORDER BY ts.enquiry_id DESC";
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
        <h4 class="page-title">Order</h4>
        <p class="text-muted page-title-alt">Your Order</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <?php echo get_message(); ?>
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="main pagesize">
              <!-- *** mainpage layout *** -->
              <div class="main-wrap">
                <div class="content-box">
                  <div class="box-body">
                    <div class="box-wrap clear">
                      <h2>Contact Support</h2>
                      <div class="row">
                        <div class="col-md-6">
                          <form method="post" action="<?php echo MEMBER_PATH."support/contactsupport"; ?>" enctype="multipart/form-data" name="form-page" id="form-page">
                            <div class="form-group">
                              <label>Query Type</label>
                              <select name="query_id" id="query_id" class="form-control input-xlarge form-half validate[required]">
                                <option value="">-----select----</option>
                                <?php DisplayCombo($ROW['query_id'],"SUPPORT_QUERY"); ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Subject</label>
                              <input name="subject" value="" class="form-control input-xlarge form-half validate[required]" type="text">
                            </div>
                            <div class="form-group">
                              <label>Attachment</label>
                              <input type="file" name="file_attach[]" id="file_attach" class="imageFormat" multiple>
                              <div id="FileId" class="" style="padding:4px; border:0px solid #993399; text-align:center;border-radius:5px; width:100%;"></div>
                            </div>
                            <div class="form-group">
                              <label>Your Query</label>
                              <textarea name="enquiry_detail" class="form-control input-xlarge form-half validate[required]"></textarea>
                            </div>
                            <input class="btn btn-sm btn-primary m-t-n-xs" name="logaTicket" value="Log a Ticket " type="submit">
                          </form>
                        </div>
                      </div>
                      <h4>Ticket  Number: <strong><?php echo number_format($PageVal['TotalRecords']); ?></strong></h4>
                      <br>
                      <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="wallet_deposit_wrapper">
                        <div class="row">
                          <div class="col-sm-12">
                            <table aria-describedby="wallet_deposit_info" role="grid" id="wallet_deposit" class="table table-striped table-bordered table-hover dataTable no-footer">
                              <thead>
                                <tr role="row">
                                  <th  style="width: 255px;" colspan="1" rowspan="1"  tabindex="0" class="">Ticket #</th>
                                  <th  style="width: 180px;" colspan="1" rowspan="1"  tabindex="0">Status</th>
                                  <th  style="width: 526px;" colspan="1" rowspan="1"  tabindex="0">Date</th>
                                  <th  style="width: 526px;" rowspan="1"  tabindex="0">Query Type </th>
                                  <th  style="width: 526px;" rowspan="1"  tabindex="0">Query Subject </th>
                                  <th  style="width: 526px;" rowspan="1"  tabindex="0">&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
								if($PageVal['TotalRecords'] > 0){
								$Ctrl=1;
									foreach($PageVal['ResultSet'] as $AR_DT):
								?>
                                <tr class="odd" role="row">
                                  <td><?php echo $AR_DT['ticket_no']; ?></td>
                                  <td><?php echo DisplayText("TICKET_".$AR_DT['enquiry_sts']); ?></td>
                                  <td><?php echo getDateFormat($AR_DT['reply_date'],"d M Y h:i"); ?></td>
                                  <td><?php echo $AR_DT['type']; ?></td>
                                  <td><?php echo $AR_DT['subject']; ?></td>
                                  <td><a href="<?php echo generateSeoUrlMember("support","conversation",array("enquiry_id"=>_e($AR_DT['enquiry_id']))); ?>"> <img src="<?php echo BASE_PATH; ?>assets/img/policyicon.gif" id="<?php echo $AR_DT['enquiry_id']; ?>" class="viewChat pointer"> </a> &nbsp;&nbsp;
                                    <?php if($AR_DT['enquiry_sts']!='C'){ ?>
                                    <a onClick="return confirm('Make sure, you want to close this tickets?')" href="<?php echo generateSeoUrlMember("support","contactsupport",array("enquiry_id"=>_e($AR_DT['enquiry_id']),"action_request"=>"CLOSE")); ?>"> Close Ticket </a>
                                    <?php }else{ ?>
                                    <a onClick="alert('This ticket is already closed')" href="javascript:void(0)">Closed</a>
                                    <?php } ?>
                                  </td>
                                </tr>
                                <?php endforeach;
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
                  <!-- end of box-wrap -->
                </div>
                <!-- end of box-body -->
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
