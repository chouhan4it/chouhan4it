<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(2);
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['document_sts']!=''){
	$document_sts = FCrtRplc($_REQUEST['document_sts']);
	switch($document_sts){
		case "Y";
			$StrWhr .=" AND tm.cancel_cheque!=''";		
		break;
		case "N":
			$StrWhr .=" AND tm.cancel_cheque=''";		
		break;
	}
	$SrchQ .="&document_sts=$document_sts";
}

if($_REQUEST['approved_sts']!=''){
	$approved_sts = FCrtRplc($_REQUEST['approved_sts']);
	switch($approved_sts){
		case "Y";
		case "R":
			$StrWhr .=" AND tmn.neft_sts='$approved_sts'";		
		break;
		case "N":
			$StrWhr .=" AND tm.member_id NOT IN(SELECT member_id FROM tbl_mem_neft_sts)";		
		break;
		
	}
	$SrchQ .="&approved_sts=$approved_sts";
}
if($_REQUEST['city_name']!=''){
	$city_name = FCrtRplc($_REQUEST['city_name']);
	$StrWhr .=" AND tm.city_name LIKE '%$city_name%'";	
	$SrchQ .="&city_name=$city_name";
}
if($_REQUEST['state_name']!=''){
	$state_name = FCrtRplc($_REQUEST['state_name']);
	$StrWhr .=" AND tm.state_name LIKE '%$state_name%'";	
	$SrchQ .="&state_name=$state_name";
}

if($_REQUEST['user_id']!=''){
	$member_id = $model->getMemberId($_REQUEST['user_id']);
	$StrWhr .=" AND tm.member_id='$member_id'";
	$SrchQ .="&member_id=$member_id";
}


$QR_PAGES = "SELECT tm.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name , tmn.neft_sts, tmn.neft_sts_date
		   FROM  tbl_members AS tm 
		   LEFT JOIN tbl_mem_neft_sts AS tmn ON tmn.member_id=tm.member_id
		   WHERE tm.member_id>0 AND tm.bank_name!='' AND  tm.account_number!='' $StrWhr 
		   GROUP BY tm.member_id 
		   ORDER BY tmn.neft_sts_date DESC";
$PageVal = DisplayPages($QR_PAGES, 50, $Page, $SrchQ);
ExportQuery($QR_PAGES);

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
<script type="text/javascript">
	$(function(){
		$(".open_modal").on('click',function(){
			$('#search-modal').modal('show');
			return false;
		});
		
	});
</script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
<style type="text/css">
.danger_alert {
	background-color: #f2dede;
	border-color: #ebccd1;
	color: #a94442;
}
.success_alert {
	background-color: #dff0d8;
	border-color: #d6e9c6;
	color: #3c763d;
}
.pointer {
	cursor: pointer;
}
</style>
<style type="text/css">
.thumbnail {
	position: relative;
	z-index: 0;/*width:25px;
height:25px;*/
}
.thumbnail:hover {
	background-color: transparent;
	z-index: 50;
}
.thumbnail span { /*CSS for enlarged image*/
	position: absolute;
	background-color: lightyellow;
	padding: 5px;
	left: -1000px;
	border: 1px dashed gray;
	visibility: hidden;
	color: black;
	text-decoration: none;
}
.thumbnail span img { /*CSS for enlarged image*/
	border-width: 0;
	padding: 2px;
	width: 300px;
	height: 300px;
}
.thumbnail:hover span { /*CSS for enlarged image on hover*/
	visibility: visible;
	top: 0;
	left: 60px; /*position where enlarged image should offset horizontally */
}
</style>
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
          <h1> Member <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; NEFT Detail </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php  get_message(); ?>
          <div class="col-xs-12">
            <div class="clearfix">
              <div class="pull-right tableTools-container">
                <div class="dt-buttons btn-overlap btn-group"> <a  class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold open_modal"><span><i class="fa fa-search bigger-110 pink"></i> <span class="hidden">Search</span></span></a> 
                  <!-- <a aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-excel buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Export to Excel</span></span>
                    </a> --> 
                  <a  href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("member","memberneft",""); ?>">
                  <b>Document Upload </b>
                  <div class="form-group">
                    <div class="clearfix">
                      <input type="radio" name="document_sts" id="document_sts" <?php echo checkRadio($_REQUEST['document_sts'],"Y"); ?> value="Y">
                      Yes &nbsp;&nbsp;
                      <input type="radio" name="document_sts" id="document_sts" <?php echo checkRadio($_REQUEST['document_sts'],"N"); ?> value="N">
                      No 
                      &nbsp;&nbsp;
                      <input type="radio" name="document_sts" id="document_sts" <?php echo checkRadio($_REQUEST['document_sts'],"A"); ?> value="">
                      All </div>
                  </div>
                  <b>Status </b>
                  <div class="form-group">
                    <div class="clearfix">
                      <input type="radio" name="approved_sts" id="approved_sts" <?php echo checkRadio($_REQUEST['approved_sts'],"Y"); ?> value="Y">
                      Approve &nbsp;&nbsp;
                      <input type="radio" name="approved_sts" id="approved_sts" <?php echo checkRadio($_REQUEST['approved_sts'],"R"); ?> value="R">
                      Reject
                      &nbsp;&nbsp;
                      <input type="radio" name="approved_sts" id="approved_sts" <?php echo checkRadio($_REQUEST['approved_sts'],"N"); ?> value="N">
                      Pending
                      &nbsp;&nbsp;
                      <input type="radio" name="approved_sts" id="approved_sts" <?php echo checkRadio($_REQUEST['approved_sts'],"A"); ?> value="">
                      All </div>
                  </div>
                  <input class="btn btn-primary m-t-n-xs" value=" Search " type="submit">
                  <a href="javascript:void(0)" onClick="window.location.href='<?php echo generateSeoUrlAdmin("member","memberneft",""); ?>'" class="btn btn-danger m-t-n-xs" value=" Reset ">Reset</a>
                </form>
              </div>
            </div>
            <div class="clearfix">&nbsp;</div>
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="table table-striped table-bordered table-hover">
              <tr class="">
                <td  align="center">Srl # </td>
                <td  align="center">Member Name </td>
                <td  align="center">User Id </td>
                <td  align="center">Bank Name </td>
                <td align="left">Account No </td>
                <td align="left">Branch</td>
                <td  align="left">IFC Code </td>
                <td  align="left">Cancel Cheque </td>
                <td  align="left">Approved Status </td>
                <td  align="left">Status Date </td>
              </tr>
              <?php 
			//if($PageVal['TotalRecords'] > 0){
			$Ctrl = $PageVal['RecordStart']+1;
			foreach($PageVal['ResultSet'] as $AR_DT):
			$cancel_cheque = $model->getCancelCheque($AR_DT['member_id']);
			?>
              <tr style="cursor:pointer">
                <td align="center" valign="middle" class=""><?php echo $Ctrl; ?><!--<a class="thumbnail" href="#thumb"><img class="img-circle" width="180px" height="180px" src="<?php echo getMemberImage($AR_DT['member_id']); ?>"><span><img src="<?php echo getMemberImage($AR_DT['member_id']); ?>" /></span></a>--></td>
                <td align="left" valign="middle" class=""><?php echo $AR_DT['full_name'];  ?></td>
                <td align="left" valign="middle" class=""><?php echo $AR_DT['user_id'];  ?></td>
                <td align="left" valign="middle" class=""><?php echo $AR_DT['bank_name']; ?></td>
                <td align="left" valign="middle" class=""><?php echo  $AR_DT['account_number']; ?></td>
                <td align="left" valign="middle" class=""><?php echo $AR_DT['branch'];  ?></td>
                <td align="left" valign="middle" class=""><?php echo $AR_DT['ifc_code']; ?></td>
                <td align="left" valign="middle" class=""><a href="<?php echo $cancel_cheque; ?>" target="_blank"><img src="<?php echo $cancel_cheque; ?>" class="img-thumbnail" width="100" height="30"></a></td>
                <td align="left" valign="middle" class=""><?php if($AR_DT['neft_sts']==""){ ?>
                  <a onClick="return confirm('Make sure, want to Approve this NEFT')" href="<?php echo generateSeoUrlAdmin("member","memberneft",array("member_id"=>_e($AR_DT['member_id']),"action_request"=>"NEFT","neft_sts"=>"Y")); ?>" class="label label-success">Approve</a> &nbsp; <a onClick="return confirm('Make sure, want to Reject this NEFT')" href="<?php echo generateSeoUrlAdmin("member","memberneft",array("member_id"=>_e($AR_DT['member_id']),"action_request"=>"NEFT","neft_sts"=>"R")); ?>" class="label label-danger">Reject</a>
                  <?php }elseif($AR_DT['neft_sts']=="R"){ ?>
                  <a href="javascript:void(0)" class="label label-danger">Rejected</a>
                  <?php }elseif($AR_DT['neft_sts']=="Y"){ ?>
                  <a href="javascript:void(0)" class="label label-success">Approved</a>
                  <?php }else{ ?>
                  <a  href="javascript:void(0)" class="label label-warning"> Pending</a>
                  <?php } ?></td>
                <td align="left" valign="middle" class="cmntext"><?php echo $AR_DT['neft_sts_date'];?></td>
              </tr>
              <?php  
			$Ctrl++; 
			endforeach; 
			?>
              <?php //}else{?>
              <!--
            <tr>
              <td colspan="11" align="center" class="errMsg">No kyc  found for this member  <a href="<?php echo generateSeoUrlAdmin("member","profilelist",""); ?>">&lt; &lt; Back</a></td>
            </tr>
            -->
              <?php //} ?>
            </table>
            <ul class="pagination">
              <?php echo $PageVal['FirstPage'].$PageVal['Prev10Page'].$PageVal['PrevPage'].$PageVal['NumString'].$PageVal['NextPage'].$PageVal['Next10Page'].$PageVal['LastPage'];?>
            </ul>
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
  </div>
  <div id="search-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="smaller lighter blue no-margin">Search</h3>
        </div>
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("member","memberneft",""); ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Member </label>
              <div class="col-sm-6">
                <input name="user_id" id="user_id"  class="col-xs-12 col-sm-12" type="text" placeholder="User Name" value="">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
            <button type="button" class="btn btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
            <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"> <i class="ace-icon fa fa-times"></i> Close </button>
          </div>
        </form>
      </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
	});
</script>
</body>
</html>