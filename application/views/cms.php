<!DOCTYPE HTML>
<html lang="en-US">

<head>
	<?php $this->load->view('layout/pagehead'); ?>
</head>
	<body>

		<!--  Start Header  -->
		<!--  Preloader  -->
		
		
		<?php $this->load->view('layout/header'); ?>
		<!--  Start Header  -->
				<!--  End Header  -->
		
		<!-- Page item Area -->
		<div id="page_item_area">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 text-left">
						<h3><?php echo $ROW['cms_title']; ?></h3>
					</div>		

					<div class="col-sm-6 text-right">
						<ul class="p_items">
							<li><a href="<?php echo BASE_PATH; ?>">home</a></li>
							<li><span><?php echo $ROW['cms_title']; ?></span></li>
						</ul>					
					</div>	
				</div>
			</div>
		</div>
		
	<!-- About Page -->
	
	<div class="about_page_area fix">
		<div class="container">
			<div class="row about_inner" style="margin-bottom: 0px; padding-bottom: 0px;">
				<div class="about_img_area col-lg-12 col-md-12 col-xs-12">
					<?php echo $ROW['content']; ?>
				</div>
				
				
				
			</div>

			

		</div>
	</div>

		

		<!--  FOOTER START  -->
		<?php $this->load->view('layout/footer'); ?>

		<?php $this->load->view('layout/footerjs'); ?>
	</body>

</html>