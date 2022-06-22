<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$today_date = InsertDate(getLocalTime());

$member_id = $this->session->userdata('mem_id');
$user_id = $this->session->userdata('user_id');

$wallet_id_1 = $model->getWallet(WALLET1);
$wallet_id_2 = $model->getWallet(WALLET2);

$AR_MEM = $model->getMember($member_id);	

$order_count = $model->getOrderCount($member_id);


$AR_SELF = $model->getSumSelfCollection($member_id,"","");
$self_collection = $AR_SELF['total_val_vol'];

$AR_GROUP = $model->getSumGroupCollection($member_id,$AR_MEM['nleft'],$AR_MEM['nright'],"","");
$group_collection = $AR_GROUP['total_bal_vol'];

$downline_count = $model->BinaryCount($member_id,"TotalCount");
$direct_count = $model->BinaryCount($member_id,"DirectCount");

$inactive_count = $model->BinaryCount($member_id,"InActiveCount");
$active_count = $model->BinaryCount($member_id,"ActiveCount");

$AR_ANCE = $model->getMemberNews($member_id);
	
$AR_KYC = $model->getKycDetail($member_id);


$AR_CASH = $model->getCurrentBalance($member_id,$wallet_id_1,"","");
#$AR_PROM = $model->getCurrentBalance($member_id,$wallet_id_2,"","");

$direct_bonus = $model->getTotalDirectCmsn($member_id);
$level_bonus = $model->getTotalLevelCmsn($member_id);
$royalty_r1 = $model->getTotalRoyaltyCmsn($member_id,'R1');
$royalty_r2 = $model->getTotalRoyaltyCmsn($member_id,'R2');
$binary_bonus = $model->getTotalBinaryCmsn($member_id);

$total_bonus = $direct_bonus + $level_bonus + $royalty_r1 + $royalty_r2 + $binary_bonus;

$confirm_withdraw = $model->getMemberWithdraw($member_id,"C");
$pending_withdraw = $model->getMemberWithdraw($member_id,"P");
$total_withdraw = $confirm_withdraw + $pending_withdraw;

$AR_ROYALTY = $model->getCurrentRoyalty($member_id);
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
        <h4 class="page-title">Dashboard</h4>
        <p class="text-muted page-title-alt">Welcome to <?php echo ucfirst(strtolower(WEBSITE)); ?> user panel !</p>
      </div>
    </div>
    <!-- <?php if($ROW['email_sts']=='N'){ ?>
	 		 <div class="col-md-12">
          <div class="alert alert-success bg-white">
            <p> Hi <?php echo $ROW['first_name']; ?>,  please verify your email to continue with this account</p>
          </div>
        </div>
	  <?php } ?>-->
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-border panel-custom">
          <div class="panel-heading">
            <h3 class="panel-title">Your Referral Link</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <p> <a target="_blank" href="<?php echo generateSeoUrl("account","register",array("referral"=>$AR_MEM['user_id'])); ?>"><?php echo generateSeoUrl("account","register",array("referral"=>$AR_MEM['user_id'])); ?></a> </p>
              </div>
              <?php if($AR_ROYALTY['royalty_id']>0){ ?>
                  <div class="col-md-6">
                        <a class="pull-right" href="javascript:void(0)"> <i class="fa fa-trophy" aria-hidden="true"></i> <?php echo $AR_ROYALTY['royalty_name']; ?></a>
                  </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3">
        <div class="card-box">
          <div class="bar-widget">
            <div class="table-box">
              <div class="table-detail">
                <div class="iconbox bg-info"> <i class="icon-layers"></i> </div>
              </div>
              <div class="table-detail">
                <h4 class="m-t-0 m-b-5"><b><a href="javascript:void(0)"><?php echo $AR_MEM['spsr_user_id']; ?></a></b></h4>
                <h5 class="text-muted m-b-0 m-t-0">Sponsor Id</h5>
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card-box">
          <div class="bar-widget">
            <div class="table-box">
              <div class="table-detail">
                <div class="iconbox bg-custom"> <i class="icon-layers"></i> </div>
              </div>
              <div class="table-detail">
                <h4 class="m-t-0 m-b-5"><b><a href="javascript:void(0)"><?php echo getTool($AR_MEM['rank_name'],"User"); ?></a></b></h4>
                <h5 class="text-muted m-b-0 m-t-0">Current Rank</h5>
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card-box">
          <div class="bar-widget">
            <div class="table-box">
              <div class="table-detail">
                <div class="iconbox bg-warning"> <i class="icon-layers"></i> </div>
              </div>
              <div class="table-detail">
                <h4 class="m-t-0 m-b-5"><b><a href="javascript:void(0)"><?php echo number_format($self_collection,2); ?></a></b></h4>
                <h5 class="text-muted m-b-0 m-t-0">Self Collection</h5>
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card-box">
          <div class="bar-widget">
            <div class="table-box">
              <div class="table-detail">
                <div class="iconbox bg-warning"> <i class="icon-layers"></i> </div>
              </div>
              <div class="table-detail">
                <h4 class="m-t-0 m-b-5"><b><a href="javascript:void(0)"><?php echo number_format($group_collection,2); ?></a></b></h4>
                <h5 class="text-muted m-b-0 m-t-0">Group Collection</h5>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white"> <i class="md md-account-child text-custom"></i>
          <h2 class="m-0 text-dark counter font-600"><?php echo number_format($direct_count); ?></h2>
          <div class="text-muted m-t-5">Direct Member</div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white"> <i class="md md-account-child text-custom"></i>
          <h2 class="m-0 text-dark counter font-600"><?php echo number_format($inactive_count); ?></h2>
          <div class="text-muted m-t-5">Inactive Member</div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white"> <i class="md md-account-child text-custom"></i>
          <h2 class="m-0 text-dark counter font-600"><?php echo number_format($active_count); ?></h2>
          <div class="text-muted m-t-5">Active  Member</div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white"> <i class="md md-account-child text-custom"></i>
          <h2 class="m-0 text-dark counter font-600"><?php echo number_format($downline_count); ?></h2>
          <div class="text-muted m-t-5">Downline Member</div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-lg-6">
        <div class="portlet"> 
          <!-- /primary heading -->
          <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase"> KYC STATUS </h3>
            <div class="clearfix"></div>
          </div>
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" >
              <div class="table-responsive">
                <table width="100%"  border="0"  class="table">
                  <tr class="">
                    <td class="white" align="left" valign="top" width="35%"  ><b>KYC address </b></td>
                    <td class="white" align="left" valign="top" width="35%" ><b>Status</b></td>
                    <td class="white" align="left" valign="top" width="30%" ><b>Date</b></td>
                  </tr>
                  <tbody>
                    <?php if($AR_KYC['file_address']!=''){ ?>
                    <tr>
                      <td  align="left" ><?php if(  $AR_KYC['file_address']!=''){ ?>
                        <span class="blue">KYC SUBMITTED</span>
                        <?php } ?></td>
                      <td  align="left" ><?php if( $AR_KYC['approved_sts']=='Y' ){ ?>
                        <span  class="green">APPROVED</span>
                        <?php }else if($AR_KYC['approved_sts']=="R"){ ?>
                        <span  class="red">REJECTED</span>
                        <?php }else{ ?>
                        <span class="blue">PENDING</span>
                        <?php } ?></td>
                      <td align="left"><?php if($AR_KYC['approved_date']!="0000-00-00 00:00:00"){  ?>
                        <span id=""><?php echo getDateFormat($AR_KYC['approved_date'],"d D M Y"); ?></span>
                        <?php } ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <table width="100%"  border="0"  class="table">
                  <tr class="">
                    <td class="white" align="left" valign="top" width="35%"  ><b>KYC Id </b></td>
                    <td class="white" align="left" valign="top" width="35%" ><b>Status</b></td>
                    <td class="white" align="left" valign="top" width="30%" ><b>Date</b></td>
                  </tr>
                  <tbody>
                    <?php if($AR_KYC['file_passport']!=''){ ?>
                    <tr>
                      <td  align="left" ><?php if(  $AR_KYC['file_passport']!=''){ ?>
                        <span class="blue">KYC SUBMITTED</span>
                        <?php } ?></td>
                      <td  align="left" ><?php if( $AR_KYC['approved_sts_id']=='Y' ){ ?>
                        <span  class="green">APPROVED</span>
                        <?php }else if($AR_KYC['approved_sts_id']=="R"){ ?>
                        <span  class="red">REJECTED</span>
                        <?php }else{ ?>
                        <span class="blue">PENDING</span>
                        <?php } ?></td>
                      <td align="left"><?php if($AR_KYC['approved_date_id']!="0000-00-00 00:00:00"){  ?>
                        <span id=""><?php echo getDateFormat($AR_KYC['approved_date_id'],"d D M Y"); ?></span>
                        <?php } ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="portlet"> 
          <!-- /primary heading -->
          <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase"> NEFT STATUS </h3>
            <div class="clearfix"></div>
          </div>
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" >
              <div class="table-responsive">
                <table width="100%" border="0"  class="table">
                  <tr class="">
                    <td class="white" align="left" valign="top" width="35%" ><b>NEFT Status</b></td>
                    <td class="white" align="left" valign="top" width="35%"><b>Status</b></td>
                    <td class="white" align="left" valign="top" width="30%" ><b>Date</b></td>
                  </tr>
                  <tbody>
                    <tr>
                      <td class="main-text" align="left" valign="top"><?php if( $AR_MEM['neft_sts']=='N'){ ?>
                        <span class="blue">NEFT SUBMITTED</span>
                        <?php }else if($AR_MEM['neft_sts']==''){ ?>
                        <span class="blue">NOT UPDATED</span>
                        <?php } ?></td>
                      <td class="main-text" align="left" valign="top" ><?php if(  $AR_MEM['neft_sts']=='N' ){ ?>
                        <span class="blue">PENDING</span>
                        <?php }else if($AR_MEM['neft_sts']=="R"){ ?>
                        <span  class="red">REJECTED</span>
                        <?php }elseif($AR_MEM['neft_sts']=="Y"){ ?>
                        <span  class="red">APPROVED</span>
                        <?php } ?></td>
                      <td class="main-text" align="left" valign="top" ><?php if($AR_MEM['neft_sts_date']!="0000-00-00 00:00:00"){  ?>
                        <span id=""><?php echo getDateFormat($AR_MEM['neft_sts_date'],"d D M Y"); ?></span>
                        <?php } ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end row -->
    <div class="row"> 
      <!-- Transactions -->
      <div class="col-lg-6">
        <div class="portlet"> 
          <!-- /primary heading -->
          <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase"> Login History </h3>
            <div class="clearfix"></div>
          </div>
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" >
              <div class="table-responsive" style="min-height:335px;">
                <table class="table" >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Browser</th>
                      <th>Ip</th>
                      <th>Login Time </th>
                      <th>Logout Time </th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
						$QR_LOGS = "SELECT tol.* FROM tbl_mem_logs AS tol WHERE member_id = '".$member_id."' ORDER BY tol.login_id DESC LIMIT 10";
						$AR_LOGS = $this->SqlModel->runQuery($QR_LOGS);
						$x=1;
					 foreach($AR_LOGS as $AR_LOG): ?>
                    <tr>
                      <td><?php echo $x; ?></td>
                      <td><?php echo $AR_LOG['browser']; ?></td>
                      <td><?php echo $AR_LOG['oprt_ip']; ?></td>
                      <td><?php echo $AR_LOG['login_time']; ?></td>
                      <td><?php //echo $AR_LOG['logout_time']; ?></td>
                      <td><span class="label <?php echo ($AR_LOG['log_sts']!="S")? "label-pink":"label-success"; ?>">Login <?php echo DisplayText("LOG_".$AR_LOG['log_sts']); ?></span></td>
                    </tr>
                    <?php $x++; endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card-box">
          <h4 class="m-t-0 m-b-20 header-title"><b>Support</b></h4>
          <div class="chat-conversation">
            <form action="<?php echo generateMemberForm("support","conversation",""); ?>" enctype="multipart/form-data" method="post" name="form-chat" id="form-chat">
              <div class="row">
                <div class="col-sm-6 chat-inputbar">
                  <input class="form-control validate[required] chat-input" type="text" name="enquiry_reply"  id="enquiry_reply" placeholder="Type a message here..."  style="width:100%!important; float:left;"/>
                  <input type="hidden" name="enquiry_id" id="enquiry_id"  value="<?php echo $enquiry_id; ?>">
                </div>
                <div class="col-sm-2 chat-send">
                  <button type="submit" name="chatSubmit" value="1" class="btn btn-md btn-info btn-block waves-effect waves-light">Send</button>
                </div>
              </div>
            </form>
            <div class="clearfix">&nbsp;</div>
            <ul class="conversation-list nicescroll">
              <?php 
				$member_id = $this->session->userdata('mem_id');	
				$enquiry_id = $model->setgetEnquiry($member_id,"");
				$QR_TICK = "SELECT ts.* FROM tbl_support AS ts WHERE enquiry_id='".$enquiry_id."'";
				$AR_TICK = $this->SqlModel->runQuery($QR_TICK,true);
				
				
				$QR_PAGES="SELECT tsr.* FROM tbl_support_rply AS tsr WHERE  tsr.enquiry_id='".$enquiry_id."' AND tsr.parent_id='0' 
				ORDER BY tsr.reply_id DESC";
				$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
	
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
          </div>
        </div>
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<div class="modal" id="modal-annoucement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><i class="fa fa-bullhorn"></i> &nbsp;<?php echo $AR_ANCE['news_title']; ?></h4>
      </div>
      <div class="modal-body" style="margin-bottom:0px; padding-bottom:2px;">
        <div> <?php echo $AR_ANCE['news_detail']; ?>
          <div> <br>
            Best Regards<br />
            <strong><?php echo ucfirst(strtolower(WEBSITE)); ?> team</strong> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<script type="text/javascript">
	$(function(){
		<?php if($AR_ANCE['news_id']>0 ){ ?>
			$("#modal-annoucement").modal('show');
		<?php } ?>
	});
</script>
<div class="modal" id="modal-reward-detail" aria-hidden="true">
  <div class="modal-dialog" style="width:900px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><strong>ROYALTY STATUS</strong></h4>
      </div>
      <div class="modal-body" >
        <div class="login-box" >
          <div id="row">
            <div class="input-box frontForms">
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div class="load-reward"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="modal-special-detail" aria-hidden="true">
  <div class="modal-dialog" style="width:900px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><strong>SPECIAL INCOME</strong></h4>
      </div>
      <div class="modal-body" >
        <div class="login-box" >
          <div id="row">
            <div class="input-box frontForms">
              <div class="row">
                <div class="col-md-12 col-xs-12">
                  <div class="load-special"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	$(function(){
		$(".modal-reward").on('click',function(){
			var switch_type = $(this).attr("switch_type");
			var member_id = $(this).attr("member_id");
			var qualify_date = $(this).attr("qualify_date");
			var full_name = $(this).attr("full_name");
			var user_id = $(this).attr("user_id");
			var qualify_status = $(this).attr("qualify_status");
			var mtype = $(this).attr("mtype");
			var net_income = $(this).attr("net_income");
			var month_no = $(this).attr("month_no");
			var start_date = $(this).attr("start_date");
			var end_date = $(this).attr("end_date");
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			var data = {
					switch_type:switch_type,
					member_id:member_id,
					qualify_date:qualify_date,
					full_name:full_name,
					user_id:user_id,
					qualify_status:qualify_status,
					mtype:mtype,
					net_income:net_income,
					month_no:month_no,
					start_date:start_date,
					end_date:end_date
			};
			$.post(URL_GET,data,function(JsonEval){
				$(".load-reward").html(JsonEval);
				$("#modal-reward-detail").modal({
					backdrop: 'static',
					keyboard: false
				});
			});
		});
	});
</script>
<script type="text/javascript">
	$(function(){
		$(".modal-special").on('click',function(){
			var switch_type = $(this).attr("switch_type");
			var member_id = $(this).attr("member_id");
			var user_id = $(this).attr("user_id");
			var full_name = $(this).attr("full_name");
			var cadre_name = $(this).attr("cadre_name");
			var rank_name = $(this).attr("rank_name");
			var actual_cnt = $(this).attr("actual_cnt");
			var total_cnt = $(this).attr("total_cnt");
			var qualify_date = $(this).attr("qualify_date");
			var register_date = $(this).attr("register_date");
			var qualify_status = $(this).attr("qualify_status");
			var start_date = $(this).attr("start_date");
			var end_date = $(this).attr("end_date");
			var total_req = $(this).attr("total_req");
			var single_line = $(this).attr("single_line");
			var pending_cnt = $(this).attr("pending_cnt");
			var URL_GET = "<?php echo generateSeoUrl("json","jsonhandler",""); ?>";
			var data = {
					switch_type:switch_type,
					member_id:member_id,
					user_id:user_id,
					full_name:full_name,
					cadre_name:cadre_name,
					rank_name:rank_name,
					actual_cnt:actual_cnt,
					total_cnt:total_cnt,
					qualify_date:qualify_date,
					register_date:register_date,
					qualify_status:qualify_status,
					start_date:start_date,
					end_date:end_date,
					total_req:total_req,
					single_line:single_line,
					pending_cnt:pending_cnt
			};
			$.post(URL_GET,data,function(JsonEval){
				$(".load-special").html(JsonEval);
				$("#modal-special-detail").modal({
					backdrop: 'static',
					keyboard: false
				});
			});
		});
	});
</script>
</html>