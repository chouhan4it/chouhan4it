
<footer>
  <div class="container">
    <div class="footer-top text-center">
      <div class="footbf">
        <div class="foo-logo"><a href="#"><img src="<?php echo LOGO; ?>" title="" alt="<?php echo WEBSITE; ?>" class="img-responsive center-block" /></a> </div>
        <div>
          <div class="foot-des"> Direct Selling Industry lead the charge in this new economy in India. This is realistic model in which customers register as in dependent distributor and sell product and build there own team. </div>
        </div>
        <script type="text/javascript">
$(document).ready(function() {
    $("#owl-testi").owlCarousel({
    itemsCustom : [
    [0, 1]
    ],
      autoPlay: false,
      navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
      navigation : false,
      pagination:true
    });
    });

  </script> 
      </div>
      <?php $this->load->view('layout/newsletter'); ?>
    </div>
    
  </div>
  
  <div class="middle-footer">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-3 col-xs-12 fborder">
          <aside id="column-left1" class="col-xs-12">
            <div>
              <div class="storeinfo">
                <h5><span>Contact us</span>
                  <button type="button" class="btn toggle collapsed" data-toggle="collapse" data-target="#contact"></button>
                </h5>
                <div id="contact" class="collapse footer-collapse footcontact">
                  <ul class="list-unstyled f-left">
                    <li><svg width="20px" height="20px">
                      <use xlink:href="#add"></use>
                      </svg>Krushnanagar,balia, Balasore,756001(Odisha)</li>
                    <li><svg width="20px" height="20px">
                      <use xlink:href="#phone"></use>
                      </svg>7606095946</li>
                    <li><svg width="22px" height="22px">
                      <use xlink:href="#mail"></use>
                      </svg>support@tatkaorders.com</li>
                  </ul>
                  <ul class="list-inline list-unstyled footpay">
                    <li><svg>
                      <use xlink:href="#ae"></use>
                      </svg></li>
                    <li><svg>
                      <use xlink:href="#mc"></use>
                      </svg></li>
                    <li><svg>
                      <use xlink:href="#dis"></use>
                      </svg></li>
                    <li><svg>
                      <use xlink:href="#visa"></use>
                      </svg></li>
                  </ul>
                </div>
              </div>
            </div>
            <script type="text/javascript">
$(document).ready(function() {
    $("#owl-testi").owlCarousel({
    itemsCustom : [
    [0, 1]
    ],
      autoPlay: false,
      navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
      navigation : false,
      pagination:true
    });
    });

  </script> 
          </aside>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12 fborder">
          <h5>Information
            <button type="button" class="btn toggle collapsed" data-toggle="collapse" data-target="#info"></button>
          </h5>
          <div id="info" class="collapse footer-collapse">
            <ul class="list-unstyled">
              <li><a href="<?php echo generateSeoUrl("web","aboutus",""); ?>">About Us</a></li>
              <li><a href="<?php echo generateSeoUrl("web","disclaimer",""); ?>">Disclaimer</a></li>
              <li><a href="<?php echo generateSeoUrl("web","privacypolicy",""); ?>">Privacy Policy</a></li>
              <li><a href="<?php echo generateSeoUrl("web","termscondition",""); ?>">Terms &amp; Conditions</a></li>
              <li><a href="<?php echo generateSeoUrl("web","refundcancellation",""); ?>">Refund & Cancellation</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12 fborder">
          <h5>My Account
            <button type="button" class="btn toggle collapsed" data-toggle="collapse" data-target="#account"></button>
          </h5>
          <div id="account" class="collapse footer-collapse">
            <ul class="list-unstyled lastb">
              <li><a href="<?php echo MEMBER_PATH; ?>">My Account</a></li>
              <li><a href="<?php echo generateSeoUrlMember("order","orderlist",""); ?>">Order History</a></li>
              <li><a href="<?php echo generateSeoUrlMember("order","trackorder",""); ?>">Track Order</a></li>
              <li><a href="<?php echo generateSeoUrlMember("financial","wallet",""); ?>">My Wallet</a></li>
              <li><a href="<?php echo generateSeoUrlMember("account","kyc",""); ?>">My KYC</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12 fborder">
          <h5>Customer Service
            <button type="button" class="btn  toggle collapsed" data-toggle="collapse" data-target="#service"></button>
          </h5>
          <div id="service" class="collapse footer-collapse">
            <ul class="list-unstyled lastb">
              <li><a href="<?php echo generateSeoUrl("web","contactus",""); ?>">Contact Us</a></li>
              <li><a href="<?php echo generateSeoUrlMember("order","orderlist",""); ?>">Returns</a></li>
              <li><a href="javascript:void(0)">Site Map</a></li>
     
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="foo-bottom text-center hidden-xs">
    <div class="container">
      <div>
        <div class="foot-tag">
          <ul class="header-link">
          	<?php
				$QR_TAGS  = "SELECT * FROM tbl_tags WHERE tag_id>0 ORDER BY tag_id DESC LIMIT 30";
				$RS_TAGS = $this->SqlModel->runQuery($QR_TAGS);
				foreach($RS_TAGS as $AR_TAGS):
			 ?>
            <li class="d-inline-block"><a href="<?php echo generateSeoUrl("product","catalog",array("tag_id"=>$AR_TAGS['tag_id'])); ?>/<?php echo gen_slug($AR_TAGS['tag_name']); ?>"><?php echo $AR_TAGS['tag_name']; ?></a></li>
            <?php endforeach; ?>
           
          </ul>
        </div>
      </div>
      <script type="text/javascript">
$(document).ready(function() {
    $("#owl-testi").owlCarousel({
    itemsCustom : [
    [0, 1]
    ],
      autoPlay: false,
      navigationText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
      navigation : false,
      pagination:true
    });
    });

  </script> 
    </div>
  </div>
  <div class="copy">
    <div class="container">
      <div class="text-center"> <?php echo WEBSITE; ?> &copy; 2021</div>
    </div>
  </div>
</footer>
