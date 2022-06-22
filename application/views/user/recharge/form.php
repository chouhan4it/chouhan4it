<?php 
$model = new OperationModel();
defined('BASEPATH') OR exit('No direct script access allowed');
$member_id = $this->session->userdata('mem_id');
$wallet_id_1 = $model->getWallet(WALLET1);

$AR_LDGR = $model->getCurrentBalance($member_id,$wallet_id_1,"","");

$net_balance = $AR_LDGR['net_balance'];
 ?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?php $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
</head>
<body>
<!-- Navigation Bar-->
<?php $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
  <div class="container"> 
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Recharge</h4>
        <p class="text-muted page-title-alt">You can recharge your Mobile, DTH, Data Card, Post Paid, Electricity, Telephone, Gas, Water Bill</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row"> 
      <!-- Transactions -->
      <div class="col-lg-12">
        <div class="portlet"> 
          <!-- /primary heading -->
          <div class="portlet-heading">
            <h3 class="lighter block green"><?php echo ucfirst(strtolower(WEBSITE)); ?> Recharge</h3>
            <div class="clearfix"></div>
          </div>
          <div class="portlet-body" >
            <div class="row">
              <div class="col-lg-12">
                <ul class="nav nav-tabs tabs">
                  <li class="active tab"> <a href="#mobile-2" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-home"></i></span> <span class="hidden-xs">Mobile</span> </a> </li>
                  <li class="tab"> <a href="#dth-2" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">DTH</span> </a> </li>
                  <li class="tab"> <a href="#datacard-2" data-toggle="tab" aria-expanded="true"> <span class="visible-xs"><i class="fa fa-envelope-o"></i></span> <span class="hidden-xs">Data Card</span> </a> </li>
                  <li class="tab"> <a href="#postpaid-2" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Post Paid</span> </a> </li>
                  <li class="tab"> <a href="#electricity-2" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Electricity</span> </a> </li>
                  <li class="tab"> <a href="#telephone-2" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Telephone</span> </a> </li>
                  <li class="tab"> <a href="#gas-2" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Gas</span> </a> </li>
                  <li class="tab"> <a href="#water-2" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Water</span> </a> </li>
                </ul>
                <div class="tab-content"><?php get_message(); ?>
                  <div class="tab-pane active" id="mobile-2">
                    <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateMemberForm("recharge","form",""); ?>" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="mobile_number">Mobile Number : </label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <input name="mobile_number" id="mobile_number" class="form-control input-xlarge validate[required,custom[integer]]" type="text" placeholder="Mobile Number" value="<?php echo $ROW['mobile_number']; ?>" maxlength="10">
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Operator:</label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <select name="operator_id" id="operator_id" class="form-control">
                              <option value="">-----select operator-----</option>
                              <?php echo DisplayCombo($_REQUEST['operator_id'],"MOBILE_OPERATOR"); ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Amount:</label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <input name="recharge_amount" id="recharge_amount" class="form-control input-xlarge validate[required,custom[integer]]" type="text" placeholder="Amount" value="<?php echo $ROW['recharge_amount']; ?>" maxlength="5">
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                      <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="available">&nbsp;</label>
                        <div class="col-xs-12 col-sm-9">Available amount in your cash wallet <small><strong><?php echo  number_format($net_balance,2); ?></strong></small></div>
                     </div>
                      <div class="space-2"></div>
                      <div class="clearfix form-action">
                        <div class="col-md-offset-3 col-md-6">
                          <input type="hidden" name="recharge_type" id="recharge_type" value="MOBILE">
                          <button type="submit" name="submit-mobile-recharge" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Recharge </button>
                          &nbsp;&nbsp;
                          <button onClick="window.location.href='<?php echo generateSeoUrlMember("recharge","form",""); ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane" id="dth-2">
                    <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateMemberForm("recharge","form",""); ?>" method="post" enctype="multipart/form-data">
                      
                      
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Operator:</label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <select name="operator_id" id="operator_id" class="form-control">
                              <option value="">-----select operator-----</option>
                              <?php echo DisplayCombo($_REQUEST['operator_id'],"DTH_OPERATOR"); ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="card_number">Card Number : </label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <input name="card_number" id="card_number" class="form-control input-xlarge validate[required,custom[integer]]" type="text" placeholder="Card Number" value="<?php echo $ROW['card_number']; ?>" m>
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="recharge_amount">Amount:</label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <input name="recharge_amount" id="recharge_amount" class="form-control input-xlarge validate[required,custom[integer]]" type="text" placeholder="Amount" value="<?php echo $ROW['recharge_amount']; ?>" maxlength="5">
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="clearfix form-action">
                        <div class="col-md-offset-3 col-md-6">
                          <input type="hidden" name="recharge_type" id="recharge_type" value="DTH">
                          <button type="submit" name="submit-dth-recharge" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Recharge </button>
                          &nbsp;&nbsp;
                          <button onClick="window.location.href='<?php echo generateSeoUrlMember("recharge","form",""); ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane" id="datacard-2">
                    	<form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateMemberForm("recharge","form",""); ?>" method="post" enctype="multipart/form-data">
                      
                      
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Operator:</label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <select name="operator_id" id="operator_id" class="form-control">
                              <option value="">-----select operator-----</option>
                              <?php echo DisplayCombo($_REQUEST['operator_id'],"DCD_OPERATOR"); ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="card_number">Customer  Number : </label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <input name="card_number" id="card_number" class="form-control input-xlarge validate[required,custom[integer]]" type="text" placeholder="Customer Number" value="<?php echo $ROW['card_number']; ?>" m>
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="recharge_amount">Amount:</label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <input name="recharge_amount" id="recharge_amount" class="form-control input-xlarge validate[required,custom[integer]]" type="text" placeholder="Amount" value="<?php echo $ROW['recharge_amount']; ?>" maxlength="5">
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="clearfix form-action">
                        <div class="col-md-offset-3 col-md-6">
                          <input type="hidden" name="recharge_type" id="recharge_type" value="DCD">
                          <button type="submit" name="submit-dcd-recharge" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Recharge </button>
                          &nbsp;&nbsp;
                          <button onClick="window.location.href='<?php echo generateSeoUrlMember("recharge","form",""); ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane" id="postpaid-2">
                   	<form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateMemberForm("recharge","form",""); ?>" method="post" enctype="multipart/form-data">
                      
                      
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password">Operator:</label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <select name="operator_id" id="operator_id" class="form-control">
                              <option value="">-----select operator-----</option>
                              <?php echo DisplayCombo($_REQUEST['operator_id'],"POP_OPERATOR"); ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="mobile_number">Postpaid Number : </label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <input name="mobile_number" id="mobile_number" class="form-control input-xlarge validate[required,custom[integer]]" type="text" placeholder="Postpaid Number" value="<?php echo $ROW['mobile_number']; ?>" maxlength="10">
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="recharge_amount">Amount:</label>
                        <div class="col-xs-12 col-sm-4">
                          <div class="clearfix">
                            <input name="recharge_amount" id="recharge_amount" class="form-control input-xlarge validate[required,custom[integer]]" type="text" placeholder="Amount" value="<?php echo $ROW['recharge_amount']; ?>" maxlength="5">
                          </div>
                        </div>
                      </div>
                      <div class="space-2"></div>
                      <div class="clearfix form-action">
                        <div class="col-md-offset-3 col-md-6">
                          <input type="hidden" name="recharge_type" id="recharge_type" value="POP">
                          <button type="submit" name="submit-pop-recharge" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Recharge </button>
                          &nbsp;&nbsp;
                          <button onClick="window.location.href='<?php echo generateSeoUrlMember("recharge","form",""); ?>'"  class="btn btn-danger" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Cancel </button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane" id="electricity-2">
                   <div class="alert alert-danger">Unser construction , coming soon..</div>
                  </div>
                  <div class="tab-pane" id="telephone-2">
                    <div class="alert alert-danger">Unser construction , coming soon..</div>
                  </div>
                  <div class="tab-pane" id="gas-2">
                   <div class="alert alert-danger">Unser construction , coming soon..</div>
                  </div>
                  <div class="tab-pane" id="water-2">
                   <div class="alert alert-danger">Unser construction , coming soon..</div>
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
		$("#form-page").validationEngine({
				'custom_error_messages': {
					'#pin_code': {
						'custom[integer]': {
							'message': "Not a valid postal code ."
						}
					}
					,'#member_mobile': {
						'custom[integer]': {
							'message': "Not a valid phone no."
						}
					}
					
				}
			});
		
	});
</script>
</html>
