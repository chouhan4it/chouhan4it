<!DOCTYPE HTML>
<html lang="en-US">
<head>
<?php $this->load->view('layout/pagehead'); ?>
</head>
<body>

<!--  Start Header  --> 
<!--  Preloader  --> 

<!--  Start Header  -->
<?php $this->load->view('layout/header'); ?>
<!--  End Header  --> 

<!-- Page item Area -->
<div id="page_item_area">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 text-left">
        <h3>Testimonials</h3>
      </div>
      <div class="col-sm-6 text-right">
        <ul class="p_items">
          <li><a href="<?php echo BASE_PATH; ?>">home</a></li>
          <li><span>Testimonials</span></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Contact Page -->
<div class="contact_page_area fix">
  <div class="container">
    <div class="row">
      <div class="contact_frm_area text-left col-lg-6 col-md-12 col-xs-12">
        <h3>Testimonial Form</h3>
        <div id="s_error"></div>
        <form method="post" id="contact_form">
          <div class="form-row">
            <div class="form-group col-sm-6" style="padding: 0px;">
              <input type="text" class="form-control" name="name" placeholder="Name*">
            </div>
            <div class="form-group col-sm-6" style="padding: 0px;">
              <input type="text" class="form-control" name="email" placeholder="Email*">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-sm-12" style="padding: 0px;" >
              <input type="url" class="form-control" name="link" placeholder="Youtube Link*">
            </div>
          </div>
          <div class="input-area submit-area">
            <button class="btn border-btn" id="submit_btn" type="submit">SUBMIT</button>
          </div>
        </form>
      </div>
      <div class="contact_info col-lg-6 col-md-12 col-xs-12">
        <h3>Submit Your Testimonial</h3>
        <p class="subtitle" style="text-align: justify;"> SPLEN ONLINE SOLUTIONS PVT. LTD is inviting sincere and helpful reviews that can help other users in gaining useful information about our services and estimating the glory of the company. Your reviews can be objects that can encourage us in attaining more keenness towards letting you meet high-yielding goals and improve our service policies and applications. If you wish to contribute your valuable words to our services, you just need to fill up the following form with the required details along with youtube video link. </p>
      </div>
    </div>
    <div class="row" style="margin-bottom: 50px; border-top: 1px solid #ddd;"> </div>
  </div>
</div>

<!--  FOOTER START  -->
<?php $this->load->view('layout/footer'); ?>

<!--  FOOTER END  -->

<?php $this->load->view('layout/footerjs'); ?>
</body>
</html>