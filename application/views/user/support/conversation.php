<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$segment = $this->uri->uri_to_assoc(2);
	$member_id = $this->session->userdata('mem_id');
	
	
	$enquiry_id = $model->setgetEnquiry($member_id,"");

	
	$QR_TICK = "SELECT ts.* FROM tbl_support AS ts WHERE enquiry_id='".$enquiry_id."'";
	$AR_TICK = $this->SqlModel->runQuery($QR_TICK,true);

		
	$QR_PAGES="SELECT tsr.* FROM tbl_support_rply AS tsr WHERE  tsr.enquiry_id='".$enquiry_id."' AND tsr.parent_id='0' ORDER BY tsr.enquiry_id ASC";
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
        <h4 class="page-title">Support</h4>
        <p class="text-muted page-title-alt">Contact support</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <!-- Transactions -->
      <?php echo get_message(); ?>
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="card-box">
                  <h4 class="m-t-0 m-b-20 header-title"><b>Chat</b></h4>
                  <div class="chat-conversation">
                    <ul class="conversation-list nicescroll">
                      <?php 
				if($PageVal['TotalRecords'] > 0){
				$Ctrl=1;
				foreach($PageVal['ResultSet'] as $AR_DT):
					if($AR_DT['member_id']==$member_id){
				?>
                      <li class="clearfix">
                        <div class="chat-avatar"> <img src="<?php echo BASE_PATH ?>assets/img/default_profile.png" alt="male"> <i><?php echo getDateFormat($AR_DT['reply_date'],"d M Y h:i A"); ?></i> </div>
                        <div class="conversation-text">
                          <div class="ctext-wrap"> <i><?php echo $AR_DT['type']; ?></i>
                            <p>
                              <?php  echo $AR_DT['enquiry_reply']; ?>
                            </p>
                            <ul style="list-style:decimal;">
                              <?php 
					$QR_ATTACH = "SELECT A.reply_id FROM tbl_support_rply AS A WHERE A.parent_id='".$AR_DT['reply_id']."'";
					$RS_ATTACH = $this->SqlModel->runQuery($QR_ATTACH);
					foreach($RS_ATTACH as $AR_ATTACH):
					?>
                              <li> <a target="_blank" href="<?php echo  $model->getAttachSrc($AR_ATTACH['reply_id']); ?>"> <i class="fa fa-chain-broken" aria-hidden="true"></i>Attachment </a> </li>
                              <?php endforeach; ?>
                            </ul>
                          </div>
                        </div>
                      </li>
                      <?php } ?>
                      <?php if($AR_DT['member_id']!=$member_id){ ?>
                      <li class="clearfix odd">
                        <div class="chat-avatar"> <img src="<?php echo BASE_PATH ?>assets/img/default_profile.png" alt="male"> <i><?php echo getDateFormat($AR_DT['reply_date'],"d M Y h:i A"); ?></i> </div>
                        <div class="conversation-text">
                          <div class="ctext-wrap"> <i><?php echo $AR_DT['type']; ?></i>
                            <p>
                              <?php  echo $AR_DT['enquiry_reply']; ?>
                            </p>
                            <ul style="list-style:decimal;">
                              <?php 
					$QR_ATTACH = "SELECT A.reply_id FROM tbl_support_rply AS A WHERE A.parent_id='".$AR_DT['reply_id']."'";
					$RS_ATTACH = $this->SqlModel->runQuery($QR_ATTACH);
					foreach($RS_ATTACH as $AR_ATTACH):
					?>
                              <li> <a target="_blank" href="<?php echo  $model->getAttachSrc($AR_ATTACH['reply_id']); ?>"> <i class="fa fa-chain-broken" aria-hidden="true"></i>Attachment </a> </li>
                              <?php endforeach; ?>
                            </ul>
                          </div>
                        </div>
                      </li>
                      <?php } ?>
                      <?php $Ctrl++; endforeach;
				}else{ ?>
                      <li>No conversation found</li>
                      <?php } ?>
                    </ul>
                    <div class="clearfix">&nbsp;</div>
					<br><br>
                    <form action="<?php echo generateMemberForm("support","conversation",""); ?>" enctype="multipart/form-data" method="post" name="form-chat" id="form-chat">
                      <div class="row">
                        <div class="col-sm-6 chat-inputbar">
                          <input class="form-control validate[required] chat-input" type="text" name="enquiry_reply"  id="enquiry_reply" placeholder="Type a message here..."  style="width:100%!important; float:left;"/>
                          
                          <input type="hidden" name="enquiry_id" id="enquiry_id"  value="<?php echo $enquiry_id; ?>">
                        </div>
						<div class="col-sm-4 chat-inputbar">
							<input type="file" name="file_attach[]" id="file_attach" style=" float:left;" class="imageFormat" multiple>
						</div>
                        <div class="col-sm-2 chat-send">
                          <button type="submit" name="chatSubmit" value="1" class="btn btn-md btn-info btn-block waves-effect waves-light">Send</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <!-- END PORTLET-->
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
