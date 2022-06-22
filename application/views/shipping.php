<?php
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(1);
$member_id = $this->session->userdata('mem_id');
$AR_MEM = $model->getMember($member_id);
$AR_ADD = $model->getAddress($member_id);
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]>
<html dir="ltr" lang="en" class="ie8">
<![endif]-->
<!--[if IE 9 ]>
<html dir="ltr" lang="en" class="ie9">
<![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
<!--<![endif]-->
<!-- Mirrored from opencart.dostguru.com/FD01/fruitino_04/index.php?route=account/login by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Dec 2021 08:33:10 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<!-- /Added by HTTrack -->
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<?php $this->load->view('layout/svgsymbol'); ?>
<body>
<?php $this->load->view('layout/header'); ?>
<div id="checkout-checkout" class="container">
<div class="row">
    <div id="content" class="col-xs-12 acpage">
        <ul class="breadcrumb">
            <li><a href="<?php echo BASE_PATH; ?>"><i class="fa fa-home"></i></a></li>
            <li><a href="<?php echo generateSeoUrl("product","shipping",""); ?>">Shipping Address</a></li>
        </ul>
        <div class="panel panel-default">
            
            
            <div class="panel-body">
                
            <div class="row">
                        <?php get_message(); ?>
                        <form method="post" action="<?php echo generateForm("product","shipping","") ?>" name="form-address" id="form-address">
                            <div class="col-sm-6">
                                <fieldset id="account">
                                <legend> Select Shipping Address</legend>
                                
                                <?php 
                                $QR_SHIP = "SELECT * FROM tbl_address WHERE member_id='$member_id' AND delete_sts>0 ORDER BY address_id ASC";
                                $RS_SHIP = $this->SqlModel->runQuery($QR_SHIP);
                                foreach($RS_SHIP as $AR_SHIP):
                                ?>
      
                                <div class="default">
                                    <div class="holderAddress">
                                        <div class="headingadd">
                                        <p>
                                            <input type="radio"  name="address_id" id="address_id<?php echo $AR_SHIP['address_id']; ?>" value="<?php echo $AR_SHIP['address_id']; ?>" required>
                                            <label for="address_id<?php echo $AR_SHIP['address_id']; ?>"><?php echo ucfirst(strtolower($AR_SHIP['adress_type'])); ?></label></p>
                                        </div>
                                        <div class="username">
                                         <p><?php echo $AR_SHIP['full_name']; ?></p>
                                        </div>
                                        <div class="userAdd">
                                        <p><span>Address:</span> <?php echo $AR_SHIP['current_address']; ?>,<?php echo $AR_SHIP['city_name']; ?>,<?php echo $AR_SHIP['state_name']; ?></p>
                                        <p><span>Pincode:</span> <?php echo $AR_SHIP['pin_code']; ?> </p>
                                        </div>
                                        <div class="userContact">
                                        <p><span>Phone No.:</span> <?php echo $AR_SHIP['mobile_number']; ?></p>
                                        <p><span>E-mail Id:</span> <?php echo $AR_SHIP['email_address']; ?></p>
                                        </div>
                                        <div class="footerbtn">
                                        <div class=""> <a class="text text-danger" href="<?php echo generateSeoUrl("product","shipping",array("address_id"=>_e($AR_SHIP['address_id']),"action_request"=>"DELETE")); ?>" onClick="return confirm('Make sure want to delete this address?')"> Delete ?</a> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>       
                                <?php endforeach; ?>       
                                
                                </fieldset>
                                <div class="clearfix">&nbsp;</div>
                                <?php if(count($RS_SHIP)>0){ ?>
                                <button class="btn btn-success" name="submit-address" id="submit-address" value="1" type="submit" ><i class="fa fa-arrow-right" aria-hidden="true"></i> Continue</button>
                                <?php } ?>
                            </div>
                            
                        </form>
                        <form method="post" action="<?php echo generateForm("product","shipping","") ?>" name="form-shipping" id="form-shipping">
                            <div class="col-sm-6">
                                <fieldset id="address" class="">
                                    <legend>Add New Address</legend>
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="input" class="form-control validate[required]" id="first_name" name="first_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone No:</label>
                                        <input type="tell" maxlength="10" class="form-control validate[required,custom[integer]]" maxlength="10" id="mobile_number" name="mobile_number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email ID:</label>
                                        <input type="email"  class="form-control validate[required,custom[email]]" id="email_address" name="email_address" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address:</label>
                                        <textarea class="form-control validate[required]" style="height:auto;" rows="3" id="current_address" name="current_address" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Pin Code:</label>
                                        <input type="text"  class="form-control validate[required,custom[integer]]" maxlength="6"  id="pin_code" name="pin_code" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="city_name">City:</label>
                                        <input type="text"  class="form-control validate[required]"  id="city_name" name="city_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="state_name">State:</label>
                                        <input type="text"  class="form-control validate[required]"  id="state_name" name="state_name" required>
                                    </div>
                                    <div class="form-group" >
                                        <label for="sel1">Address type:</label>
                                        <select class="form-control validate[required]" id="adress_type" name="adress_type" required>
                                            <option value="HOME">Home </option>
                                            <option value="OFFICE">Office </option>
                                            <option value="NEIGHBOR">Neighbor </option>
                                            <option value="FAMILY">Family Member</option>
                                        </select>
                                    </div>
                                    
                                    <div class="buttons">
                                        <div class="pull-right">
                                          <input type="submit" value="Submit" name="submit-shipping" id="submit-shipping" class="btn btn-primary">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </form>
                    </div>        
                   
                    
            </div>
            
            </div>
            
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>
</body>
</html>

