<?php
	$title_page = WEBSITE." | Payment";
	
	$model = new OperationModel();
	$form_data = $this->input->post();
	$segment = $this->uri->uri_to_assoc(1);
	$member_id = $this->session->userdata('mem_id');
	$AR_MEM  = $model->getMember($member_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<body class="cnt-home">
<!-- ============================================== HEADER ============================================== -->

<?php $this->load->view('layout/header'); ?>

<!-- ============================================== HEADER : END ============================================== -->
<div class="breadcrumb">
  <div class="container">
    <div class="breadcrumb-inner">
      <ul class="list-inline list-unstyled">
        <li><a href="<?php echo BASE_PATH; ?>">Home</a></li>
        <li class='active'>Transaction Cancel</li>
      </ul>
    </div>
    <!-- /.breadcrumb-inner --> 
  </div>
  <!-- /.container --> 
</div>
<!-- /.breadcrumb -->

<div class="body-content">
<div class="container">
  <div class="contact-page">
    <div class="row"> 
    	 <h3 class=""><i class="fa fa-info-circle" aria-hidden="true"></i> Payment failed , this may be reason !</h3>
    <div class="clearfix">&nbsp;</div>
    <div class="row" style="min-height:300px;">
      <div class="col-md-12">
	 
		<ul style="text-align:left;">
			<li style="margin:4px;">You have double click on payment button</li>
			<li style="margin:4px;">Your session <strong>or</strong> cookies  has beeen expired.</li>
			<li style="margin:4px;">Invalid payment details.</li>
			<li style="margin:4px;">You have selected a payment cancel option.</li>
			<li style="margin:4px;">For any support , you can  write us 24*7 <a style="text-decoration:none; color:#0080FF;" href="mailto:info@amkeyworld.com">info@amkeyworld.com</a>.</li>
		</ul>
		
      </div>
    </div>
    </div>
  </div>
  <!-- /.row --> 
</div>
<!-- /.container --> 
<!-- ============================================================= FOOTER ============================================================= --> 

<!-- ============================================== INFO BOXES ============================================== -->
<?php $this->load->view('layout/infobox'); ?>
<!-- /.info-boxes --> 
<!-- ============================================== INFO BOXES : END ============================================== --> 

<!-- ============================================================= FOOTER ============================================================= -->
<?php $this->load->view('layout/footer'); ?>
<!-- ============================================================= FOOTER : END============================================================= --> 

<!-- For demo purposes – can be removed on production --> 

<!-- For demo purposes – can be removed on production : End --> 

<!-- JavaScripts placed at the end of the document so the pages load faster -->
<?php $this->load->view('layout/footerjs'); ?>
</body>
</html>