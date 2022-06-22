<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$today_date = getLocalTime();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['fullname']!=''){
	$fullname = FCrtRplc($_REQUEST['fullname']);
	$StrWhr .=" AND ( tm.first_name LIKE '%$fullname%' OR tm.last_name LIKE '%$fullname%' OR tm.member_email LIKE '%$fullname%' )";
	$SrchQ .="&fullname=$fullname";
}
if($_REQUEST['user_name']!=''){
	$user_name = FCrtRplc($_REQUEST['user_name']);
	$StrWhr .=" AND ( tm.user_name = '%$user_name%' OR tm.user_id = '$user_name')";
	$SrchQ .="&user_name=$user_name";
}
if($_REQUEST['rank_id']>0){
	$rank_id = FCrtRplc($_REQUEST['rank_id']);
	$StrWhr .=" AND ( tm.rank_id = '$rank_id' )";
	$SrchQ .="&rank_id=$rank_id";
}
if($_REQUEST['city_name']!=''){
	$city_name = FCrtRplc($_REQUEST['city_name']);
	$StrWhr .=" AND ( tm.city_name = '$city_name' )";
	$SrchQ .="&city_name=$city_name";
}

if($_REQUEST['block_sts']!=''){
	$block_sts = FCrtRplc($_REQUEST['block_sts']);
	$StrWhr .=" AND ( tm.block_sts = '$block_sts' )";
	$SrchQ .="&block_sts=$block_sts";
}
if($_REQUEST['join_date']!=''){
	$join_date = FCrtRplc($_REQUEST['join_date']);
	$StrWhr .=" AND ( DATE(tm.date_join) = '".InsertDate($join_date)."')";
	$SrchQ .="&join_date=$join_date";
}
if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = FCrtRplc($_REQUEST['from_date']);
	$to_date = FCrtRplc($_REQUEST['to_date']);
	$StrWhr .=" AND ( DATE(tm.date_join) BETWEEN '".InsertDate($from_date)."' AND '".InsertDate($to_date)."')";
	$SrchQ .="&from_date=$from_date&to_date=$to_date";
}
$QR_PAGES="SELECT tm.*, tmsp.first_name AS spsr_first_name, tmsp.last_name AS spsr_last_name,  tmsp.user_name AS spsr_user_id ,
		 ts.date_from, tp.pin_name, tr.rank_name
		 FROM tbl_members AS tm	
		 LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
		 LEFT JOIN tbl_rank AS tr ON tr.rank_id=tm.rank_id
		 LEFT JOIN tbl_subscription AS ts ON ts.subcription_id=tm.subcription_id
		 LEFT JOIN tbl_pintype AS tp ON tp.type_id=ts.type_id
		 WHERE tm.delete_sts>0    $StrWhr ORDER BY tm.member_id ASC";
$PageVal = DisplayPages($QR_PAGES, 100, $Page, $SrchQ);

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
          <h1> Member Profile <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; List </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a href="<?php echo generateSeoUrlAdmin("member","addmember",array("")); ?>" title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-collection buttons-colvis btn btn-white btn-primary btn-bold"><span><i class="fa fa-plus bigger-110 blue"></i> <span class="hidden">Show/hide columns</span></span></a> <a class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold open_modal"><span><i class="fa fa-search bigger-110 pink"></i> <span class="hidden">Search</span></span></a> 
                      <!-- <a aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-excel buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Export to Excel</span></span>
                    </a> --> 
                      <a  href="<?php echo generateSeoUrlAdmin("export","excel",""); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div style="min-height:500px;">
                  <table id="" class="table">
                    <thead>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=$PageVal['RecordStart']+1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						
			       ?>
                      <tr class="<?php echo ($AR_DT['block_sts']=="Y")? "text-danger":""; ?>">
                        <td width="22" rowspan="3" class="center"><label class="pos-rel"> <?php echo $Ctrl; ?> <span class="lbl"></span> </label></td>
                        <td width="112">Full Name : </td>
                        <td width="148"><a href="javascript:void(0)"><?php echo $AR_DT['first_name']." ".$AR_DT['last_name']; ?></a></td>
                        <td width="126">Sponsor ID : </td>
                        <td width="120"><?php echo ($AR_DT['spsr_user_id']!='')? $AR_DT['spsr_user_id']:"Admin"; ?></td>
                        <td width="140"><?php if($this->session->userdata('oprt_id')<=2){echo "Password :";} ?></td>
                        <td width="164"><?php if($this->session->userdata('oprt_id')<=2){echo $AR_DT['user_password'];} ?></td>
                        <td width="90" rowspan="3"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                              <li> <a href="<?php echo generateSeoUrlAdmin("member","profile",array("member_id"=>_e($AR_DT['member_id']))); ?>">View</a> </li>
                              <li> <a href="<?php echo generateSeoUrlAdmin("financial","memberwallet",array("member_id"=>_e($AR_DT['member_id']))); ?>">E-wallet</a> </li>
                              <li> <a href="<?php echo generateSeoUrlAdmin("member","kyc",array("member_id"=>_e($AR_DT['member_id']))); ?>">KYC</a> </li>
                              <li> <a target="_blank" href="<?php echo generateSeoUrlAdmin("member","directaccesspanel",array("user_id"=>$AR_DT['user_id'])); ?>">Access Panel</a> </li>
                              <li> <a onClick="return confirm('Make sure , you want to change member email status?')" href="<?php echo generateSeoUrlAdmin("member","profile",array("member_id"=>_e($AR_DT['member_id']),"action_request"=>"EMAIL_STS","email_sts"=>$AR_DT['email_sts'])); ?>"><?php echo ($AR_DT['email_sts']=="N")? "Un-verified":"Verified"; ?></a> </li>
                              <li> <a onClick="return confirm('Make sure , you want to change this member block status?')" href="<?php echo generateSeoUrlAdmin("member","profile",array("member_id"=>_e($AR_DT['member_id']),"action_request"=>"BLOCK_UNBLOCK","block_sts"=>$AR_DT['block_sts'])); ?>"><?php echo ($AR_DT['block_sts']=="N")? "Block":"Un-block"; ?></a> </li>
                              <li> <a onClick="return confirm('Make sure , you want to change this member active / in-active status?')" href="<?php echo generateSeoUrlAdmin("member","profile",array("member_id"=>_e($AR_DT['member_id']),"action_request"=>"STATUS","status"=>$AR_DT['status'])); ?>"><?php echo ($AR_DT['status']=="N")? "Resume":"Suspend"; ?></a> </li>
                             
                              <li><a onClick="return confirm('Do you want to upload NEFT document? Please confirm')" href="<?php echo generateSeoUrlAdmin("member","editmemberneft",array("member_id"=>_e($AR_DT['member_id']))); ?>">
                                <?php if($AR_DT['cancel_cheque']!='') echo "Edit NEFT"; else echo "Upload NEFT"; ?>
                                </a></li>
                            </ul>
                          </div></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['block_sts']=="Y")? "text-danger":""; ?>">
                        <td>Mobile : </td>
                        <td><?php echo $AR_DT['member_mobile']; ?></td>
                        <td>Email Address : </td>
                        <td><?php echo $AR_DT['member_email']; ?></td>
                        <td>Status : </td>
                        <td><?php echo ($AR_DT['status']=="Y")? "Active":"In-Active"; ?></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['block_sts']=="Y")? "text-danger":""; ?>">
                        <td>User Id : </td>
                        <td><?php echo $AR_DT['user_name']; ?>
                          <?php if($AR_DT['photo']!=''){?>
                          <a target="_blank" href="<?php echo $model->profilePhoto($AR_DT['photo']); ?>"><i class="fa fa-download"></i></a>
                          <?php }?></td>
                        <td>Rank : </td>
                        <td><?php echo $AR_DT['rank_name']; ?></td>
                        <td>Date of Joining : </td>
                        <td><?php echo DisplayDate($AR_DT['date_join']); ?></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['block_sts']=="Y")? "text-danger":""; ?>">
                        <td class="center">&nbsp;</td>
                        <td>City :</td>
                        <td><?php echo $AR_DT['city_name']; ?></td>
                        <td>State :</td>
                        <td><?php echo $AR_DT['state_name']; ?></td>
                        <td>Last Login</td>
                        <td><?php echo DisplayDate($AR_DT['last_login']); ?></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="8" class="center"><hr class="divider">
                          </hr></td>
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
  <div id="search-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="smaller lighter blue no-margin">Search</h3>
        </div>
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo ADMIN_PATH."member/profilelist"; ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Name / Email Address  :</label>
              <div class="col-sm-7">
                <input id="form-field-1" placeholder="Name / Email" name="fullname"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['fullname']; ?>">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> User Id  :</label>
              <div class="col-sm-7">
                <input id="form-field-1" placeholder="User Name" name="user_name"  class="col-xs-10 col-sm-12 validate[required]" type="text" value="<?php echo $_REQUEST['user_name']; ?>">
              </div>
            </div>
             <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Date from  :</label>
              <div class="col-sm-3">
                <div class="input-group">
                                        <input class="form-control validate[required] date-picker col-md-3" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
              </div>
              <div class="col-sm-3">
              <div class="input-group">
                                        <input class="form-control  validate[required] date-picker" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                                        <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i></span> </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> City Name  :</label>
              <div class="col-sm-7">
                <input id="form-field-1" placeholder="City Name" name="city_name"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['city_name']; ?>">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Member Status   :</label>
              <div class="col-sm-7">
                <input type="radio" name="block_sts" id="block_sts" <?php if($_REQUEST['block_sts']=="Y"){ echo 'checked="checked"'; } ?>  value="Y">
                Block &nbsp;&nbsp;
                <input type="radio" name="block_sts" id="block_sts" value="N" <?php if($_REQUEST['block_sts']=="N"){ echo 'checked="checked"'; } ?> >
                Un-Block </div>
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		});
		
	});
</script> 
</body>
</html>
