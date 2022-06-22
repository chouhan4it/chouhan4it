<div class="header" style="height:70px;">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-5">
	              <!-- Logo -->
                  <div class="logo">
	                 <a href="<?php echo BASE_PATH; ?>"><img src="<?php echo LOGO_WH; ?>" alt="<?php echo WEBSITE; ?>" height="55" style="margin:1% auto; border-radius:5px;"></a>
	              </div>
	              
	           </div>
	           <div class="col-md-4">
	              <div class="row">
	                <!--<div class="col-lg-12">
	                  <div class="input-group form">
	                       <input type="text" class="form-control" placeholder="Search...">
	                       <span class="input-group-btn">
	                         <button class="btn btn-primary" type="button">Search</button>
	                       </span>
	                  </div>
	                </div>-->
	              </div>
	           </div>
	           <div class="col-md-3">
	              <div class="navbar navbar-inverse" role="banner">
	                  <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
	                    <ul class="nav navbar-nav">
	                      <li class="dropdown">
	                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Welcome, <?php echo ucfirst(strtolower($ROW['name'])); ?> <b class="caret"></b></a>
	                        <ul class="dropdown-menu animated fadeInUp">
							 <li><a target="MainIframe" href="<?php echo  generateSeoUrlFranchise("setting","password",array()); ?>">Setting</a></li>
	                          <li><a target="MainIframe" href="<?php echo  generateSeoUrlFranchise("setting","profile",
										 array("oprt_id"=>$this->session->userdata('oprt_id'),"action_request"=>"EDIT")); ?>">Profile</a></li>
	                          <li><a  href="<?php echo  generateSeoUrlFranchise("login","logouthandler",array()); ?>">Logout</a></li>
	                        </ul>
	                      </li>
	                    </ul>
	                  </nav>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>