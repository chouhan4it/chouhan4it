<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['type_id']!=''){
	$type_id = FCrtRplc($_REQUEST['type_id']);
	$StrWhr .=" AND tpd.type_id='$type_id'";
	$SrchQ .="&type_id=$type_id";
}

if($_REQUEST['member_id']!=''){
	$member_id = FCrtRplc($_REQUEST['member_id']);
	$StrWhr .=" AND tpd.member_id='$member_id'";
	$SrchQ .="&member_id=$member_id";
}
if($_REQUEST['pin_key']!=''){
	$pin_key = FCrtRplc($_REQUEST['pin_key']);
	$StrWhr .=" AND tpd.pin_key LIKE '%$pin_key%'";
	$SrchQ .="&pin_key=$pin_key";
}
if($_REQUEST['block_sts']!=''){
	$block_sts = FCrtRplc($_REQUEST['block_sts']);
	$StrWhr .=" AND tpd.block_sts = '$block_sts'";
	$SrchQ .="&block_sts=$block_sts";
}
$QR_PAGES= "SELECT tpd.*, tm.user_id, tm.first_name, tm.last_name, tpy.pin_name FROM tbl_pinsdetails AS tpd 
			LEFT JOIN tbl_pinsmaster AS tpm ON tpd.mstr_id=tpm.mstr_id
			LEFT JOIN tbl_members AS tm ON tpd.member_id=tm.member_id
			LEFT JOIN tbl_pintype AS tpy ON tpd.type_id=tpy.type_id WHERE tpd.pin_sts='N' $StrWhr ORDER BY tpd.pin_id ASC";
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
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
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
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
<script type="text/javascript">
	$(function(){
		$(".open_modal").on('click',function(){
			$('#search-modal').modal('show');
			return false;
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
          <h1> E-pin <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Block / Un-block </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="smaller lighter blue no-margin">Search</h3>
                    </div>
                    <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo ADMIN_PATH."epin/blockpin"; ?>" method="post">
                      <div class="modal-body">
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> E-Pin Type 	  :</label>
                          <div class="col-sm-7">
                            <select  name="type_id" id="type_id" class="col-xs-12 col-sm-12 validate[required] getPinPrice">
                              <option value="">Select Pin</option>
                              <?php echo DisplayCombo($_REQUEST['type_id'],"PIN_TYPE"); ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Member ID 	  :</label>
                          <div class="col-sm-7">
                            <input name="user_id" type="text" class="col-xs-12 col-sm-12 validate[required]" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="">
                            <input type="hidden" name="member_id" id="member_id">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> E-Pin Number 	  :</label>
                          <div class="col-sm-7">
                            <input name="pin_key" type="text" class="col-xs-12 col-sm-12 validate[required]" id="pin_key" value="<?php echo $_REQUEST['pin_key']; ?>" placeholder="">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Status	  :</label>
                          <div class="col-sm-7">
                            <input type="radio" name="block_sts" id="block_sts" <?php echo checkRadio($_REQUEST['block_sts'],"N"); ?> value="N">
                            Un-block &nbsp;&nbsp;
                            <input type="radio" name="block_sts" id="block_sts" <?php echo checkRadio($_REQUEST['block_sts'],"Y"); ?> value="Y">
                            Block </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
                        <button type="button" class="btn btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
                      </div>
                    </form>
                  </div>
                  <!-- /.modal-content --> 
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div>
                  <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th width="197">Assigned To </th>
                        <th width="163">Name</th>
                        <th width="133">E-Pin Type </th>
                        <th width="133">E-Pin Number </th>
                        <th width="133">E-Pin Key </th>
                        <th width="133">E-Pin Value </th>
                        <th width="133">E-Pin Status </th>
                        <th width="133">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr>
                        <td><?php echo $AR_DT['user_id']; ?></td>
                        <td><?php echo $AR_DT['first_name']; ?></td>
                        <td><?php echo $AR_DT['pin_name']; ?></td>
                        <td><?php echo $AR_DT['pin_no']; ?></td>
                        <td><?php echo $AR_DT['pin_key']; ?></td>
                        <td><?php echo $AR_DT['pin_price']; ?></td>
                        <td><?php echo ($AR_DT['block_sts']=="Y")? "Block":"Un-block"; ?></td>
                        <td><?php if($AR_DT['block_sts']=="Y"){ ?>
                          <a onClick="return confirm('Make sure , you want to un-block this pin')" href="<?php echo generateSeoUrlAdmin("epin","blockpin",array("pin_id"=>_e($AR_DT['pin_id']),"block_sts"=>"N","action_request"=>"BLOCK")); ?>"><img src="<?php echo BASE_PATH; ?>assets/setupimages/block_off.gif"   height="20" width="20" border="0"></a>
                          <?php }else{ ?>
                          <a onClick="return confirm('Make sure , you want to block this pin')" href="<?php echo generateSeoUrlAdmin("epin","blockpin",array("pin_id"=>_e($AR_DT['pin_id']),"block_sts"=>"Y","action_request"=>"BLOCK")); ?>"><img src="<?php echo BASE_PATH; ?>assets/setupimages/block_on.gif"   height="20" width="20" border="0"></a>
                          <?php } ?></td>
                      </tr>
                      <?php $Ctrl++; endforeach; }else{ ?>
                      <tr>
                        <td colspan="8" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <div class="row">
                    <div class="col-xs-6">
                      <div aria-live="polite" role="status" id="dynamic-table_info" class="dataTables_info"> Showing <?php echo $PageVal['RecordStart']+1; ?> to <?php echo  count($PageVal['ResultSet']); ?> of <?php echo $PageVal['TotalRecords']; ?> entries </div>
                    </div>
                    <div class="col-xs-6">
                      <div id="dynamic-table_paginate" class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                          <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation();  auto_complete();  ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		});
		
	});
</script> 
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo ADMIN_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEMBER";
});
</script>
</body>
</html>
