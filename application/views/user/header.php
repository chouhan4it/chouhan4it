<?php 
	$model = new OperationModel();
	$member_id = $this->session->userdata('mem_id');
	$AR_MEM = $model->getMember($member_id);
	
	$today_date = InsertDate(getLocalTime());
	$C_MONTH = getMonthDates($today_date);
	$month_date = InsertDate($C_MONTH['flddFDate']);
	$end_date = InsertDate($C_MONTH['flddTDate']);
	
	$class_name = $this->router->fetch_class();
	$method_name =  $this->router->fetch_method();
?>

<header id="topnav">
  <div class="topbar-main">
    <div class="container"> 
      <!-- Logo container-->
      <div class="logo"> <a href="<?php echo BASE_PATH; ?>" class="logo"><img style="background-color:#FEFEFE; border-radius: 5px;"  alt="<?php echo WEBSITE; ?>"  height="65" src="<?php echo LOGO; ?>"></a> </div>
      <!-- End Logo container-->
      <div class="menu-extras">
        <ul class="nav navbar-nav navbar-right pull-right">
          <!-- <li>
                                <form role="search" class="navbar-left app-search pull-left hidden-xs">
                                     <input type="text" placeholder="Search..." class="form-control">
                                     <a href="#"><i class="fa fa-search"></i></a>
                                </form>
                            </li>-->
          <li class="visible-sm-block visible-lg visible-md"> <a>Welcome back, <?php echo $AR_MEM['full_name']; ?> [<?php echo $AR_MEM['user_id']; ?>]</a> </li>
          <?php 
							$QR_NEW = "SELECT tn.* FROM tbl_news  AS tn 
							WHERE tn.news_type LIKE 'PUBLIC'  AND 
							tn.isDelete>0	 AND tn.news_sts>0 
							ORDER BY  tn.news_date ASC LIMIT 10";
							$RS_NEW = $this->SqlModel->runQuery($QR_NEW); 
							?>
          <li class="dropdown hidden-xs"> <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"> <i class="icon-bell"></i> <span class="badge badge-xs badge-danger"><?php echo count($RS_NEW); ?></span> </a>
            <ul class="dropdown-menu dropdown-menu-lg">
              <li class="notifi-title"><span class="label label-default pull-right">Event <?php echo count($RS_NEW); ?></span>Notification</li>
              <li class="list-group nicescroll notification-list">
                <?php
				if(count($RS_NEW)>0){
				foreach($RS_NEW as $AR_NEW):
				?>
                <a href="<?php echo generateSeoUrlMember("account","news",array("news_id"=>_e($AR_NEW['news_id']))); ?>" class="list-group-item">
                <div class="media">
                  <div class="pull-left p-r-10"> <em class="fa fa-diamond fa-2x text-primary"></em> </div>
                  <div class="media-body">
                    <h5 class="media-heading"><?php echo $AR_NEW['news_title']; ?></h5>
                    <p class="m-0"> <small><?php echo getDateFormat($AR_NEW['news_date'],"d M, Y h:i"); ?></small> </p>
                  </div>
                </div>
                </a>
                <?php endforeach; }else{ ?>
                <a class="list-group-item" href="javascript:void(0)"> No notification found </a>
                <?php } ?>
                <!-- list item--> 
              </li>
            </ul>
          </li>
          <li class="dropdown"> <a href="#" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo getMemberImage($this->session->userdata('mem_id')); ?>" alt="user-img" class="img-circle"> </a>
            <ul class="dropdown-menu">
               <li><a href="<?php echo generateSeoUrlMember("account","profile",""); ?>"><i class="ti-user m-r-5"></i> Profile </a></li>
              <li><a href="<?php echo generateSeoUrlMember("account","avtar",""); ?>"><i class="ti-user m-r-5"></i> Profile Picture</a></li>
              <li><a href="<?php echo generateSeoUrlMember("account","welcomeletter",""); ?>"><i class="ti-bookmark-alt m-r-5"></i> Welcome Letter</a></li>
              <li><a href="<?php echo generateSeoUrlMember("account","changepassword",""); ?>"><i class="ti-lock m-r-5"></i> Change Password</a></li>
               <li><a href="<?php echo generateSeoUrlMember("account","changetrnspassword",""); ?>"><i class="ti-unlock m-r-5"></i> Change Transaction Password</a></li>
              <li><a href="<?php echo generateSeoUrlMember("account","userlogs",""); ?>"><i class="ti-settings m-r-5"></i> Login History</a></li>
              <li><a href="<?php echo generateSeoUrlMember("login","logouthandler",""); ?>"><i class="ti-power-off m-r-5"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
        <div class="menu-item"> 
          <!-- Mobile menu toggle--> 
          <a class="navbar-toggle">
          <div class="lines"> <span></span> <span></span> <span></span> </div>
          </a> 
          <!-- End mobile menu toggle--> 
        </div>
      </div>
    </div>
  </div>
  <!-- End topbar --> 
  <!-- Navbar Start -->
  <div class="navbar-custom">
    <div class="container">
      <div id="navigation"> 
        <!-- Navigation Menu-->
        <ul class="navigation-menu">
          <li class="has-submenu <?php echo ($class_name=="dashboard")? "active":""; ?>"> <a href="<?php echo MEMBER_PATH; ?>"><i class="md md-dashboard"></i>Dashboard</a></li>
          <li class="has-submenu <?php echo ($class_name=="account")? "active":""; ?>"> <a href="#"><i class="md md-color-lens"></i>My Account</a>
            <ul class="submenu">
             
              <li><a href="<?php echo generateSeoUrlMember("account","kyc",""); ?>">KYC Upload</a></li>
              <li><a href="<?php echo generateSeoUrlMember("account","bankdetail",""); ?>">Bank Details</a></li>
              <li><a href="<?php echo generateSeoUrlMember("account","testimonial",""); ?>">Testimonial</a></li>             
              <li><a href="<?php echo generateSeoUrlMember("account","news",""); ?>">Events</a></li>
             
              
            </ul>
          </li>
         
          <li class="has-submenu <?php echo ($class_name=="network")? "active":""; ?>"> <a href="#"><i class="md md-layers"></i>My Network</a>
            <ul class="submenu megamenu">
              <li>
                <ul>
                  <li><a href="<?php echo generateSeoUrlMember("network","treeauto",""); ?>">My Tree</a></li>
                   <li><a href="<?php echo generateSeoUrlMember("network","levelautoview",""); ?>">My Level</a></li>
                   <li><a href="<?php echo generateSeoUrlMember("network","referrer",""); ?>">My Direct </a></li>
                </ul>
              </li>
            </ul>
          </li>
          
          <li class="has-submenu  <?php echo ($class_name=="financial")? "active":""; ?>"> <a href="#"><i class="ion-bag"></i>My Wallet</a>
            <ul class="submenu">
             
              <li><a href="<?php echo generateSeoUrlMember("financial","wallet",""); ?>"> Wallet Statement</a></li>
              <li><a href="<?php echo generateSeoUrlMember("financial","paymenthistory",""); ?>"> Payment History</a></li>
            </ul>
          </li>
           <li class="has-submenu <?php echo ($class_name=="report" && ( $method_name=="directincome" || $method_name=="levelincome" ) )? "active":""; ?>"> <a href="#"><i class="md md-folder-special"></i>My Commission</a>
            <ul class="submenu">
              <li><a href="<?php echo generateSeoUrlMember("report","directincome",""); ?>">Direct Bonus</a></li>
              
            </ul>
          </li>
           <li class="has-submenu <?php echo ($class_name=="report" && ( $method_name=="selfcollection" || $method_name=="groupcollection" ) )? "active":""; ?>"> <a href="#"><i class="md md-class"></i>My Reports</a>
            <ul class="submenu megamenu">
              <li>
                <ul>
                  <li><a href="<?php echo generateSeoUrlMember("report","selfcollection",""); ?>">Self Collection</a></li>
                  <li><a href="<?php echo generateSeoUrlMember("report","groupcollection",""); ?>">Group Collection</a></li>
				   
                </ul>
              </li>
            </ul>
          </li>
         
          <li class="has-submenu <?php echo ($class_name=="order")? "active":""; ?>"> <a href="#"><i class="md md-shopping-cart"></i>My Shopping</a>
            <ul class="submenu">
              <li><a href="<?php echo generateSeoUrlMember("order","shoptab",""); ?>"> My Cart</a></li>
              <li><a href="<?php echo generateSeoUrlMember("order","savecart",""); ?>"> My Saved Order</a></li>
              <li><a href="<?php echo generateSeoUrlMember("order","orderlist",""); ?>"> My Order</a></li>
              <li><a href="<?php echo generateSeoUrlMember("order","trackorder",""); ?>"> Track Order</a></li>
            </ul>
          </li>
          
          
        </ul>
        <!-- End navigation menu        --> 
      </div>
    </div>
  </div>
</header>
