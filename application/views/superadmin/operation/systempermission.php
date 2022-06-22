<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$QR_PAGES="SELECT tsms.*, tsmm.type_name FROM tbl_sys_menu_sub AS tsms 
		   LEFT JOIN tbl_sys_menu_main AS tsmm ON tsms.ptype_id=tsmm.ptype_id  
		   WHERE 1 ORDER BY tsms.order_id ASC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title><?php echo title_name(); ?></title>
<meta name="description" content="Static &amp; Dynamic Tables" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/fonts.googleapis.com.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-skins.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace-rtl.min.css" />
<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<?php jquery_validation(); ?>
<script>
JsURLPath = "<?php echo BASE_PATH; ?>";
$(function() { 
	$("#frmMenus").validationEngine(
		{onValidationComplete: function(form, valid){
            $("#fldvTList > option").each(function(){
			    	$('#fldvTList option').attr("selected", "selected");
			});
			return true;
        }}
	);
	
	$(".ViewList").change(function(){
		var group_id = $(this).val();
		window.location.href='?group_id='+group_id;
	})
	
	$(".AddMenus").click(function(){
		$("#fldvFList > option:selected").each(function(){
			$(this).remove().appendTo("#fldvTList");
		});
	});

	$(".RemoveMenus").click(function(){
		$("#fldvTList > option:selected").each(function(){
			$(this).remove().appendTo("#fldvFList");
		});
	});
});
</script>
</head>
<body class="no-skin">
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
      <div class="page-content">
        <div class="page-header">
          <h1> System <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Permission </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" method="post" name="frmMenus" id="frmMenus" autocomplete="off" action="<?php echo ADMIN_PATH."operation/systempermission"; ?>">
              <div class="space-6"></div>
              <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="food">Group Name</label>
                <div class="col-sm-3">
                  <select class="form-control validate[required] ViewList" id="form-field-select-1 group_id" name="group_id" >
                    <option value="">Select Group</option>
                    <?php echo DisplayCombo($_REQUEST['group_id'],"USRGRP"); ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-top" for="duallist"> Available Menu Listing </label>
                <div class="col-sm-8">
                  <div class="bootstrap-duallistbox-container row moveonselect">
                    <div class="box1 col-md-6">
                      <div class="btn-group buttons">
                        <button title="Move selected" type="button" class="btn move btn-white btn-bold btn-info AddMenus"> <i class="fa fa-arrow-right"></i> </button>
                      </div>
                      <select style="height: 270px;" name="fldvFList[]" class="form-control" id="fldvFList" multiple="multiple">
                        <?php
                            $QR_SELECT ="SELECT A.page_id, A.page_title, B.type_name FROM tbl_sys_menu_sub AS A, tbl_sys_menu_main AS B 
                            WHERE A.ptype_id=B.ptype_id   AND A.page_id NOT IN (SELECT page_id FROM tbl_sys_menu_acs AS C WHERE 
                            C.group_id='$_REQUEST[group_id]')   AND A.order_id>0 AND B.order_id>0 ORDER BY B.order_id ASC, A.order_id ASC";
                            $RS_SELECT = $this->db->query($QR_SELECT);
                            $AR_ROWS = $RS_SELECT->result_array();
                            foreach($AR_ROWS as $AR_ROW):
					            	?>
                        <option value="<?php echo $AR_ROW['page_id']; ?>"><?php echo $AR_ROW['type_name']."=>".$AR_ROW['page_title'];?></option>
                        <?php	
						                endforeach;
					            	?>
                      </select>
                    </div>
                    <div class="box2 col-md-6">
                      <div class="btn-group buttons">
                        <button title="Remove selected" type="button" class="btn remove btn-white btn-bold btn-info RemoveMenus"> <i class="fa fa-arrow-left"></i> </button>
                      </div>
                      <select style="height: 270px;" name="fldvTList[]" class="form-control" id="fldvTList" multiple="multiple">
                        <?php
                          $QR_SELECT ="SELECT A.page_id, A.page_title, B.type_name FROM tbl_sys_menu_sub AS A, tbl_sys_menu_main AS B WHERE 
                          A.ptype_id=B.ptype_id   AND A.page_id IN (SELECT page_id FROM tbl_sys_menu_acs AS C WHERE 
                          C.group_id='$_REQUEST[group_id]') ORDER BY B.order_id ASC, A.order_id ASC";
                          $RS_SELECT = $this->db->query($QR_SELECT);
                          $AR_ROWS = $RS_SELECT->result_array();
                          foreach($AR_ROWS as $AR_ROW):
						            ?>
                        <option value="<?php echo $AR_ROW['page_id']; ?>"><?php echo $AR_ROW['type_name']."=>".$AR_ROW['page_title'];?></option>
                        <?php	
					              	endforeach;
						            ?>
                      </select>
                    </div>
                  </div>
                  <div class="hr hr-16 hr-dotted"></div>
                </div>
              </div>
              <div class="form-action">
                <div class="col-md-offset-3 col-md-9">
                  <button type="submit" name="submitForm" value="1" class="btn btn-info"> <i class="ace-icon fa fa-check bigger-110"></i> Submit </button>
                  <button onclick="window.parent.frames.location.reload();"  class="btn" type="button"> <i class="ace-icon fa fa-undo bigger-110"></i> Load Menu </button>
                </div>
              </div>
            </form>
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
</body>
</html>
