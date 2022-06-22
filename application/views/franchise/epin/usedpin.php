<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

$franchisee_id = $this->session->userdata('fran_id');

if($_REQUEST['type_id']!=''){
	$type_id = FCrtRplc($_REQUEST['type_id']);
	$StrWhr .=" AND tpd.type_id='$type_id'";
	$SrchQ .="&type_id=$type_id";
}

if($_REQUEST['member_id']!=''){
	$member_id = _d($_REQUEST['member_id']);
	$StrWhr .=" AND tpd.member_id='$member_id'";
	$SrchQ .="&member_id=$member_id";
}
if($_REQUEST['pin_key']!=''){
	$pin_key = FCrtRplc($_REQUEST['pin_key']);
	$StrWhr .=" AND ( tpd.pin_key LIKE '%$pin_key%' OR tpd.pin_no LIKE '%$pin_key%' )";
	$SrchQ .="&pin_key=$pin_key";
}
if($_REQUEST['used_by']!=''){
	$use_member_id = $model->getMemberId($_REQUEST['used_by']);
	$StrWhr .=" AND tpd.use_member_id = '".$use_member_id."'";
	$SrchQ .="&used_by=$used_by";
}
if($_REQUEST['block_sts']!=''){
	$block_sts = FCrtRplc($_REQUEST['block_sts']);
	$StrWhr .=" AND tpd.block_sts = '$block_sts'";
	$SrchQ .="&block_sts=$block_sts";
}

if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);
	$StrWhr .=" AND DATE(tpd.date_time) BETWEEN '$from_date' AND '$to_date'";
	$SrchQ .="&from_date=$from_date&to_date=$to_date";
}

$QR_PAGES = "SELECT tpd.*, tm.member_id as member,  tm.user_id, tm.first_name, tm.last_name, tpy.pin_name, 
			used.first_name AS use_first_name, used.last_name AS use_last_name,
			used.user_id AS user_user_id
			FROM tbl_pinsdetails AS tpd 
			LEFT JOIN tbl_pinsmaster AS tpm ON tpd.mstr_id=tpm.mstr_id
			LEFT JOIN tbl_members AS tm ON tpd.member_id=tm.member_id
			LEFT JOIN tbl_members AS used ON tpd.use_member_id=used.member_id
			LEFT JOIN tbl_pintype AS tpy ON tpd.type_id=tpy.type_id 
			WHERE tpd.pin_sts='Y' AND tpd.franchisee_id='".$franchisee_id."' 
			AND tpd.use_member_id>0 
			$StrWhr 
			ORDER BY tpd.pin_id ASC";
$PageVal = DisplayPages($QR_PAGES,50,$Page,$SrchQ);
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
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1>E-Pin<small> Used </small> </h1>
      </div>
      <!-- /.page-header -->
      
      <?php get_message(); ?>
      <div class="row">
        <div class="col-xs-6">
          <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("epin","usedpin",""); ?>" method="post">
            <div class="modal-body">
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Used By 	  :</label>
                <div class="col-sm-7">
                  <input name="used_by" type="text" class="col-xs-12 col-sm-12" id="used_by" value="<?php echo $_REQUEST['used_by']; ?>" placeholder="Used By">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> E-Pin Key  / E-Pin No	  :</label>
                <div class="col-sm-7">
                  <input name="pin_key" type="text" class="col-xs-12 col-sm-12" id="pin_key" value="<?php echo $_REQUEST['pin_key']; ?>" placeholder="E-Pin Key / No">
                </div>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
              <button type="button" class="btn btn-sm btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
            </div>
          </form>
        </div>
      </div>
      <div class="clearfix">&nbsp;</div>
      <div class="row">
        <div class="col-xs-12">
          <div>
            <table id="" class="table">
              <thead>
                <tr>
                  <td>Srl No</td>
                  <td>Name</td>
                  <td>E-Pin Type</td>
                  <td>E-Pin Number </td>
                  <td>E-Pin Key</td>
                  <td>E-Pin Value </td>
                  <td>Used By</td>
                  <td>Used On</td>
                </tr>
              </thead>
              <tbody>
                <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                <tr>
                  <td><?php echo $Ctrl; ?></td>
                  <td><?php echo $AR_DT['first_name']; ?></td>
                  <td><?php echo $AR_DT['pin_name']; ?></td>
                  <td><?php echo $AR_DT['pin_no']; ?></td>
                  <td><?php echo highlightWords($AR_DT['pin_key'],$_REQUEST['pin_key']); ?></td>
                  <td><?php echo $AR_DT['pin_price']; ?></td>
                  <td><?php echo $AR_DT['user_user_id']; ?></td>
                  <td><?php echo $AR_DT['used_date']; ?></td>
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
      <!-- /.col --> 
      
    </div>
    <!-- /.page-content --> 
  </div>
</div>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
</body>
</html>
