<?php 
  $model = new OperationModel();
  $today_date = InsertDate(getLocalTime());
  $segment = $this->uri->uri_to_assoc(2);
  if($segment['cmd']=="confirm"){
 	 $AR_MEM = $model->getMember($model->getFirstId());
	 $this->db->query("TRUNCATE tbl_mem_tree",true);
	 $this->SqlModel->insertRecord("tbl_mem_tree",
				array("member_id"=>$AR_MEM['member_id'],
				"sponsor_id"=>0,"
				spil_id"=>0,"
				nlevel"=>1,
				"left_right"=>"",
				"nleft"=>1,
				"nright"=>2,
				"date_join"=>InsertDate($AR_MEM['date_join'])
	  ));
  }
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
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
</head>
<body class="no-skin">
<?php $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
<div class="main-container ace-save-state" id="main-container">
  <?php $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
  <div class="main-content">
    <div class="main-content-inner">
      <div class="page-content">
        <div class="page-header">
          <h1> Chain <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Referesh </small> </h1>
        </div>
        <div class="row">
          <div class="col-xs-12" style="min-height:500px;">
            <?php if($segment['cmd']=="confirm"){ ?>
            <div id="loadAjax">
              <div class="alert alert-danger"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i> &nbsp; &nbsp;Please wait we are processing.......</div>
            </div>
            <?php }else{ ?>
            <h2>Make sure, you want to referesh member chain ?</h2>
            <a  class="btn btn-success" href="<?php echo generateSeoUrlAdmin("member","chainreferesh",array("cmd"=>"confirm")); ?>"><i class="ace-icon fa fa-check"></i> Confirm</a> &nbsp;&nbsp;<a class="btn btn-danger" href="<?php echo generateSeoUrlAdmin("member","profilelist"); ?>"><i class="ace-icon fa fa-times"></i> Cancel</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
</body>
<script type="text/javascript">
	$(function(){
		<?php if($segment['cmd']=="confirm"){ ?>
		referesh_chain();
		function referesh_chain(){
			var data = {
				switch_type : "REFERESH_CHAIN",
			}
			var URL_LOAD = "<?php echo generateSeoUrlAdmin("json","jsonhandler",""); ?>";
			$.getJSON(URL_LOAD,data,function(JsonEval){	
				if(JsonEval){
					if(JsonEval.ErrorMsg!=''){
						switch(JsonEval.ErrorMsg){
							case "DONE":
								$("#loadAjax").html('<div class="alert alert-success"><i class="fa fa-check" style="font-size:24px"></i> &nbsp; &nbsp;Chain Referesh Successfully</div>');
							break;
							default:
								$("#loadAjax").html('<div class="alert alert-danger"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i> &nbsp; &nbsp;Please wait we are processing.......</div>');
								referesh_chain();
							break;
						}
					}
				}
			});
		}
		<?php } ?>
	});
</script>
</html>
