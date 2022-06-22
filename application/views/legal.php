<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('layout/pagehead'); ?>
</head>
<body>
    
    
    <?php $this->load->view('layout/topheader'); ?>
    <?php $this->load->view('layout/header'); ?>
    
    <div class="top-search">
        <div class="container">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search">
                <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
    <!-- End Top Search -->

    <!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>LEGAL</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_PATH; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Legal</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->

    <!-- Start About Page  -->
    <div class="about-box-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="banner-frame"> <img class="img-fluid" src="<?php echo THEME_PATH; ?>images/legal1.jpeg" alt="" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-frame"> <img class="img-fluid" src="<?php echo THEME_PATH; ?>images/legal2.jpeg" alt="" />
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- End About Page -->


  	<?php $this->load->view('layout/footer'); ?>
</body>
<?php $this->load->view('layout/footerjs'); ?>
</html>