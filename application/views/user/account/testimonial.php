<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
 ?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
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
        <h4 class="page-title">Testimonials</h4>
        <p class="text-muted page-title-alt">You can write your testimonials</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="portlet">
          <div class="portlet-body" >
            <div class="row">
              <div class="col-md-12"> &nbsp;&nbsp;&nbsp; <a href="javascript:void(0)" class="btn btn-success newTestimonial">Add Yours</a></div>
            </div>
			<div class="clearfix">&nbsp;</div>
            <?php get_message(); ?>
            <div class="row">
				<?php 
				$QR_PAGES = "SELECT tt.*, tm.city_name FROM tbl_testimonial  AS tt 
				LEFT JOIN tbl_members AS tm ON tm.member_id=tt.member_id
				WHERE 1 ORDER BY  tt.testimonial_date DESC";
				$PageVal = DisplayPages($QR_PAGES, 10, $Page, $SrchQ);
				if($PageVal['TotalRecords'] > 0){
				$Ctrl=1;
				
				?>
              <div class="col-md-12">
                <div  class="nicescroll p-l-r-10" style="max-height: 535px; overflow: hidden;">
                  <div class="timeline-2">
                    <?php foreach($PageVal['ResultSet'] as $AR_DT): ?>
                    <div class="time-item">
                      <div class="item-info">
                        <div class="text-muted"><small><?php echo getDateFormat($AR_DT['testimonial_date'],"d M Y h:i:s"); ?> </small> </div>
                        <p><strong><a href="javascript:void(0)" class="text-info"><?php echo $AR_DT['testimonial_by']; ?></a></strong> <?php echo $AR_DT['testimonial_detail']; ?> -  <small><?php echo $AR_DT['city_name']; ?></small></p>
                      </div>
                    </div>
					<div class="clearfix">&nbsp;Status: <?php echo ($AR_DT['testimonial_sts']==0)? "<span class='text text-danger'>Pending</span>":"<span class='text text-success'>Active</span>"; ?></div>
                    <?php endforeach; ?>
					
                    <?php }else{ ?>
	                    <div class="alert alert-danger">Please write your testimonials</div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12 pg-wrap">
                <ul class="pagination pull-right">
                  <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<div class="modal" id="load-testimonial" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Your Testimonial</h4>
      </div>
      <div class="modal-body">
        <div class="login-box">
          <div id="row">
            <div class="input-box frontForms">
              <div class="row"> <?php echo display_message(); ?>
                <div class="col-md-12 col-xs-12">
                  <form action="<?php echo  generateMemberForm("account","testimonial",array("")); ?>" id="otpForm" name="otpForm" method="post">
                    <div class="form-group">
                      <label for="transaction_password">Testimonial:</label>
                      <textarea name="testimonial_detail" class="form-control validate[required]" id="testimonial_detail" style="width:540px; height:200px;"></textarea>
                      <div class="clear">&nbsp;</div>
                    </div>
                    <div class="form-group">
                      <input type="submit" name="submit-testimonail" value="Submit" class="btn btn-primary btn-submit" id="Submit"/>
                      &nbsp;&nbsp;
                      <input type="button" name="closeButton" value="Close" class="btn btn-danger btn-submit"  data-dismiss="modal" id="closeButton"/>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<script type="text/javascript">
	$(function(){
		$(".newTestimonial").on('click',function(){	
			$("#load-testimonial").modal('show');
		});
	});
</script>
</html>
