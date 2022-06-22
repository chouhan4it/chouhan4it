<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
?>
<!DOCTYPE html>
<html>
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
        
        <h4 class="page-title">Products</h4>
        <ol class="breadcrumb">
          <li> <a href="<?php echo generateSeoUrlMember("order","cart",""); ?>">My Cart</a> </li>
          <li class="active"> Shipping </li>
        </ol>
      </div>
    </div>
    <!-- Page-Title -->
    <!-- SECTION FILTER
                ================================================== -->
    <div class="row">
      <div class="col-sm-12">
        <?php echo get_message(); ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
			  	<div class="row">
                <form method="post" action="<?php echo generateMemberForm("order","shipping","") ?>" name="form-address" id="form-address">
                <div class="col-md-5">
                	<div class="row">
                    <?php 
           	    $QR_SHIP = "SELECT * FROM tbl_address WHERE member_id='$member_id' AND delete_sts>0 ORDER BY address_id ASC";
            	$RS_SHIP = $this->SqlModel->runQuery($QR_SHIP);
            	foreach($RS_SHIP as $AR_SHIP):
            ?>
                    <div class="col-lg-12">
                        <div class="panel panel-border panel-purple">
                            <div class="panel-heading">
                                <h3 class="panel-title"><label for="address_id<?php echo $AR_SHIP['address_id']; ?>"><input class="validate[required]" type="radio"  name="address_id" id="address_id<?php echo $AR_SHIP['address_id']; ?>" value="<?php echo $AR_SHIP['address_id']; ?>" >&nbsp;<strong><?php echo ucfirst(strtolower($AR_SHIP['adress_type'])); ?> Address:</strong></label></h3>
                            </div>
                            <div class="panel-body">
                                <p><?php echo $AR_SHIP['full_name']; ?></p>
                                <p><?php echo wordwrap($AR_SHIP['current_address'],20,"<br>\n");  ?></p>
                                <p><?php echo $AR_SHIP['city_name']." , ".$AR_SHIP['state_name']." , ".$AR_SHIP['country_code']; ?>[<?php echo $AR_SHIP['pin_code']; ?>]</p>
                                <p> <i class="fa fa-phone"></i> <?php echo $AR_SHIP['mobile_number']; ?></p>
                                <p>  <i class="fa fa-envelope"></i> <?php echo $AR_SHIP['email_address']; ?></p>
                                <p  class="pull-right"><a class="text text-danger" href="<?php echo generateSeoUrlMember("order","shipping",array("address_id"=>_e($AR_SHIP['address_id']),"action_request"=>"DELETE")); ?>" onClick="return confirm('Make sure want to delete this address?')">Delete ?</a> </p>
                                <p>&nbsp;</p>
                            </div>
                        </div>
                    </div>
                    
                    <?php endforeach; ?>
                     <div class="col-lg-12">
                     <a class="btn w-sm btn-danger"   href="<?php echo $model->getShopType(); ?>" ><i class="fa fa-arrow-left"></i> Your shopping cart </a>
                     <button type="submit" name="submit-address" value="1" class="btn w-sm btn-default waves-effect waves-light">  <i class="fa fa-arrow-right"></i> Proceeed to Payment </button>
                     </div>
                    </div>
                </div>
                </form>
                <form action="<?php echo generateMemberForm("order","shipping",""); ?>" method="post" name="form-valid" id="form-valid" enctype="multipart/form-data">
				<div class="col-md-7">
                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Shipping Detail</b></h5>
				
				 <div class="form-group m-b-20 address-filed">
                  <label>First Name <span class="text-danger">*</span></label>
                  <input value="<?php echo $_REQUEST['first_name']; ?>" class="form-control validate[required]" placeholder="First Name" name="first_name" id="first_name" type="text">
                </div>
                 <div class="form-group m-b-20 address-filed">
                  <label>Last Name <span class="text-danger">*</span></label>
                  <input value="<?php echo $_REQUEST['last_name']; ?>" class="form-control validate[required]" placeholder="Last Name" name="last_name" id="last_name" type="text">
                </div>
                <div class="form-group m-b-20 address-filed">
                  <label>Current Address <span class="text-danger">*</span></label>
                  <textarea class="form-control validate[required]" name="current_address" id="current_address" placeholder="Address"><?php echo $_REQUEST['current_address']; ?></textarea>
                </div>
                <div class="form-group m-b-20 address-filed">
                  <label>City <span class="text-danger">*</span></label>
                  <input value="<?php echo $_REQUEST['city_name']; ?>" class="form-control validate[required]" placeholder="Your City" name="city_name" id="city_name" type="text">
                </div>
                <div class="form-group m-b-20 address-filed">
                  <label> State<span class="text-danger">*</span></label>
                 <input value="<?php echo $_REQUEST['state_name']; ?>" class="form-control validate[required]" placeholder="Your State" name="state_name" id="state_name" type="text">
                </div>
                <div class="form-group m-b-20 address-filed">
                  <label>Country <span class="text-danger">*</span></label>
                    <select name="country_code" id="country_code" class="form-control" required>
                     <option> ---Country---</option>
                        <?php echo DisplayCombo($_REQUEST['country_code'],"COUNTRY"); ?>
                    </select>
                </div>
                <div class="form-group m-b-20 address-filed">
                   <label>Zip Code <span class="text-danger">*</span></label>
                  <input  value="<?php echo $_REQUEST['pin_code']; ?>" class="form-control validate[required]" placeholder="Your Pincode" name="pin_code" id="pin_code" type="text">
                </div>
                <div class="form-group m-b-20 address-filed">
                  <label>Mobile Number <span class="text-danger">*</span></label>
                  <input class="form-control validate[required]" placeholder="Mobile Number" name="mobile_number" id="mobile_number"  value="<?php echo $_REQUEST['mobile_number']; ?>" type="text">
                </div>
                 <div class="form-group m-b-20 address-filed">
                  <label>Email <span class="text-danger">*</span></label>
                  <input class="form-control validate[required]" placeholder="Email Addres" name="email_address" id="email_address"  value="<?php echo $_REQUEST['email_address']; ?>" type="text">
                </div>
                 <div class="form-group m-b-20 address-filed">
                  <label>Address Type <span class="text-danger">*</span></label>
                <select class="form-control" id="adress_type" name="adress_type">
                    <option value="HOME">Home (Delivery Time : 9am to 8pm)</option>
                    <option value="OFFICE">Office (Delivery Time : 10am to 6pm)</option>
                    <option value="NEIGHBOR">Neighbor (Delivery Time : 9am to 8pm)</option>
                    <option value="FAMILY">Family Member (Delivery Time : 9am to 8pm)</option>
                 </select>
                </div>
				<div class="form-group m-b-20">
				
                   <button type="submit" name="submit-shipping" value="1" class="btn w-sm btn-default waves-effect waves-light">  <i class="fa fa-save"></i> Save </button>
                </div>
              </div>
               </form>
			  </div>
			  </div>
            </div>
          </div>
          
       
      </div>
    </div>
    <!-- End row -->
    <!-- Footer -->
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
    <!-- End Footer -->
  </div>
  <!-- end container -->
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$("#form-address").validationEngine();
		
	
	});
</script>
</html>
