<?php 
$segment = $this->uri->uri_to_assoc(1);
$page_name = $this->router->fetch_method();  


$AR_USER = $this->OperationModel->getMember($this->session->userdata("mem_id"));


$select_cat_id = getTool($segment['category_id'],$_REQUEST['category_id']);

$cart_count = $this->OperationModel->getCartCount();
$cart_total = 	$this->OperationModel->getCartTotal();
$QR_SEL_CART = "SELECT tc.*,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price, tpl.post_pv,  tpl.update_date, tpl.post_slug
			FROM tbl_cart AS tc
			LEFT JOIN tbl_post AS tp ON tp.post_id=tc.post_id 
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			WHERE tp.delete_sts>0  AND tc.cart_session='".$cart_session."'
			GROUP BY tc.cart_id  
			ORDER BY tc.cart_id ASC";
$RS_SEL_CART = $this->SqlModel->runQuery($QR_SEL_CART);



$QR_OT_CAT = "SELECT tc.*, COUNT(tpc.post_id) AS prod_ctrl
		FROM tbl_category  AS tc  
		LEFT JOIN tbl_post_category AS tpc ON tpc.category_id=tc.category_id
		WHERE tc.category_sts>0 AND tc.delete_sts>0  AND tc.parent_id='0'
		GROUP BY tc.category_id 
		ORDER BY tc.category_id DESC LIMIT 7";
$RS_OT_CAT =  $this->SqlModel->runQuery($QR_OT_CAT);
?>

<header>
  <div class="hsticky">
    <div class="container">
      <div class="row hbottom">
        <div id="logo" class="col-lg-3 col-md-3 col-sm-4 col-xs-5"><a href="<?php echo BASE_PATH; ?>"><img src="<?php echo LOGO; ?>" title="<?php echo LOGO; ?>" alt="<?php echo LOGO; ?>" class="img-responsive" /></a> </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
          <div id="search" class="desktop-search">
            <div id="search" class="wbSearch">
              <form  method="get" action="<?php echo generateForm("product","catalog",""); ?>" autocomplete="off">
              <div id="search_block_top">
                <select class="select_option" name="category_id" id="winter-search-category">
                <?php 
                $QR_SRCH_CAT = "SELECT tc.*, COUNT(tpc.post_id) AS prod_ctrl
                                FROM tbl_category  AS tc  
                                LEFT JOIN tbl_post_category AS tpc ON tpc.category_id=tc.category_id
                                WHERE tc.category_sts>0 AND tc.delete_sts>0  AND tc.parent_id='0'
                                GROUP BY tc.category_id 
                                ORDER BY tc.category_id DESC";
                $RS_SRCH_CAT =  $this->SqlModel->runQuery($QR_SRCH_CAT);
                
                ?>
                <option value=""> All Categories</option>
                <?php  foreach($RS_SRCH_CAT as $AR_SRCH_CAT): ?>
                <option value="<?php echo $AR_SRCH_CAT['category_id']; ?>" <?php if($AR_SRCH_CAT['category_id']==$_REQUEST['category_id']){ ?> selected="selected" <?php } ?>> <?php echo $AR_SRCH_CAT['category_name']; ?></option>
                <?php endforeach; ?>
                </select>
               
                <div class="input-group">
                  <input type="text" name="q" value="<?php echo $_REQUEST['q']; ?>" placeholder="Search" class="search_query form-control input-lg winter-search" autofocus />
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search  hidden-sm hidden-md hidden-lg"></i><span class="hidden-xs">Search</span></button>
                  </div>
                  <!-- Winter Search Start  -->
                  <!--<div class="winter-search text-left">
                    <div class="winter-search-loader" style="display: none;">
                      <div class="loaders"></div>
                    </div>
                    <div class="winter-search-result"></div>
                  </div>-->
                  <!-- Winter Search End  --> 
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-right head-right">
          <ul class="list-inline">
            <li class="dropdown inuser"> <a href="indexe223.html?route=account/account" title="My Account" class="dropdown-toggle" data-toggle="dropdown"> <?php echo getTool($AR_USER['full_name'],""); ?><svg width="25px" height="25px">
              <use xlink:href="#huser"></use> 
              </svg> </a>
              <ul class="dropdown-menu dropdown-menu-right haccount">
                <?php if($AR_USER['member_id']>0){ ?>
                  <li><a href="<?php echo MEMBER_PATH; ?>"><i class="fa fa-user"></i>My Account</a></li>
                  <li><a href="<?php echo generateSeoUrlMember("order","orderlist",""); ?>"><i class="fa fa-list"></i>Order History</a></li>
                  <li><a href="<?php echo generateSeoUrlMember("order","trackorder",""); ?>"><i class="fa fa-map-marker"></i>Track Order</a></li>
                  <li><a href="<?php echo generateSeoUrlMember("financial","wallet",""); ?>"><i class="fa fa-user"></i>My Wallet</a></li>
                  <li><a href="<?php echo generateSeoUrlMember("account","kyc",""); ?>"><i class="fa fa-file"></i>My KYC</a></li>
                  <li><a href="<?php echo generateSeoUrlMember("login","logouthandler",""); ?>"><i class="fa fa-sign-out"></i>Logout</a></li>
                <?php }else{ ?>
                <li><a href="<?php echo generateSeoUrl("account","register",""); ?>"><i class="fa fa-user-plus"></i>Register</a></li>
                <li><a href="<?php echo generateSeoUrl("account","login",""); ?>"><i class="fa fa-lock"></i>Login</a></li>
                <?php } ?>
              </ul>
            </li>
            <div id="cart" class="btn-group">
              <!-- <button type="button" data-toggle="dropdown" data-loading-text="Loading..." class="dropdown-toggle"></button>-->
              <a  href="<?php echo generateSeoUrl("product","cart",""); ?>"> <svg width="25px" height="25px">
              <use xlink:href="#hcart"></use>
              </svg><span id="cart-total"><span class="cartta"><?php echo number_format($cart_count); ?></span><span class="hidden-xs"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($cart_total,2); ?></span></span></a>
              <!--<ul class="dropdown-menu pull-right">
                <li>
                  <p class="text-center">Your shopping cart is empty!</p>
                </li>
              </ul>-->
            </div>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="menuwidth text-right">
    <div class="container">
      <div class="menurel pull-left">
        <nav id="menu" class="navbar">
          <div class="navbar-header">
            <button type="button" class="btn btn-navbar navbar-toggle" onclick="openNav()" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
          </div>
          <div id="mySidenav" class="sidenav">
          <div class="close-nav hidden-md hidden-lg hidden-xl"> <span class="categories">Category</span> <a href="javascript:void(0)" class="closebtn pull-right" onclick="closeNav()"><i class="fa fa-close"></i></a> </div>
          <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
            <li ><a href="<?php echo BASE_PATH; ?>" class="">  <i class="fa fa-home"></i> Home</a>
            </li>
			<?php 
				$menu_ctrl = 1;
				foreach($RS_OT_CAT as $AR_OT_CAT):
				$CT_OPTION = $this->OperationModel->getCategoryOption($AR_OT_CAT['category_id']);
				$QR_CT_PROD = "SELECT tp.*,  tpl.lang_id, tpl.post_size, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
						tpl.post_price, tpl.post_pv,  tpl.update_date , tpl.post_slug, GROUP_CONCAT(tc.category_name) AS category_name
						FROM tbl_post AS tp
						LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
						LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
						LEFT JOIN tbl_category AS tc ON tc.category_id=tpc.category_id 
						WHERE tp.delete_sts>0 AND tp.post_sts>0  
						AND tpc.category_id='".$AR_OT_CAT['category_id']."'
						GROUP BY tp.post_id
						ORDER BY tpl.post_title ASC LIMIT 5";
				$RS_CT_PROD =  $this->SqlModel->runQuery($QR_CT_PROD);
            ?>
              <li class="dropdown m-menu"><a href="<?php echo generateSeoUrl("product","catalog",array("category_id"=>$AR_OT_CAT['category_id'])); ?>/<?php echo $AR_OT_CAT['category_slug']; ?>" class="dropdown-toggle header-menu" data-toggle="dropdown"> <?php echo ucfirst(strtolower($AR_OT_CAT['category_name'])); ?> <i class="fa fa-angle-down pull-right enangle"></i></a>
                <div class="dropdown-menu">
                  <div class="dropdown-inner">
                    <ul class="list-unstyled">
                      <!--3rd level-->
                      <li class="dropdown-submenu"> <a href="<?php echo generateSeoUrl("product","catalog",array("category_id"=>$AR_OT_CAT['category_id'])); ?>/<?php echo $AR_OT_CAT['category_slug']; ?>" class="submenu-title"> All (<?php echo number_format(count($RS_CT_PROD)) ?>) </a>
                        <ul class="list-unstyled grand-child">
							<?php 
                            foreach($RS_CT_PROD as $AR_CT_PROD):
                            ?>
                          <li> <a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_CT_PROD['post_id'])); ?>/<?php echo $AR_CT_PROD['post_slug']; ?>"><?php echo $AR_CT_PROD['post_title']; ?></a> </li>
                          <?php endforeach; ?>
                        </ul>
                      </li>
                      <!--3rd level over-->
                    </ul>
                    
                    <a href="index98dc.html?route=product/category&amp;path=20" class="see-all visible-xs visible-sm">Show All Desktops</a> </div>
                </div>
              </li>
              <?php endforeach; ?>
              
              
              
            </ul>
          </div>
        </nav>
      </div>
      <script type="text/javascript">
 function headermenu() {
     if (jQuery(window).width() < 992)
     {
         jQuery('ul.nav li.dropdown a.header-menu').attr("data-toggle","dropdown");        
     }
     else
     {
         jQuery('ul.nav li.dropdown a.header-menu').attr("data-toggle",""); 
     }
}
$(document).ready(function(){headermenu();});
jQuery(window).resize(function() {headermenu();});
jQuery(window).scroll(function() {headermenu();});
</script>
      <li class="d-inline-block hidden-sm hidden-xs"><a href="index2724.html?route=information/contact"><svg height="32px" width="32px">
        <use xlink:href="#callus"></use>
        </svg></a> <span class="hidden-xs hidden-sm hidden-md">123456789</span></li>
      
      
    </div>
  </div>
</header>
