<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('layout/pagehead'); ?>
</head>
<body>
    <?php $this->load->view('layout/topheader'); ?>
    <?php $this->load->view('layout/header'); ?>

    <!-- Start Top Search -->
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
                    <h2>BANK DETAILS</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Bank Details</li>
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
                    <div class="box">
                        <img src="<?php echo BASE_PATH; ?>setupimages/axis.png" width="40%" alt="bank">
                        <div class="box-title">
                            <h3 class="pt-3">P2P TOURS OPC PVT LTD</h3>
                        </div>
                        <div class="box-text">
                            <p><strong>A/C No:</strong> 919020078354734</p>
                            <p><strong>IFSC Code:</strong> UTIB0001409</p>
                            <p><strong>Branch:</strong> DUMUDUMA</p>
                        </div>
                     </div>
                </div>
                <div class="col-lg-6">
                    <div class="box">
                        <img src="<?php echo BASE_PATH; ?>setupimages/icici.png" width="40%" alt="bank">
                        <div class="box-title">
                            <h3 class="pt-3">P2P TOURS (OPC) PVT LTD</h3>
                        </div>
                        <div class="box-text">
                            <p><strong>A/C No:</strong> 279705500069</p>
                            <p><strong>IFSC Code:</strong> ICIC0002797</p>
                            <p><strong>Branch:</strong> BARAMUNDA</p>
                        </div>
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