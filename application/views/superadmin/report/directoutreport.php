<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if(_d($_REQUEST['member_id'])>0){
	$member_id = _d($_REQUEST['member_id']);
	$StrWhr .=" AND tcb.member_id='".$member_id."'";
	$SrchQ .="&member_id=$member_id";
}


if($_REQUEST['process_id']!=''){
	$process_id = FCrtRplc($_REQUEST['process_id']);
	$StrWhr .=" AND tp.process_id='$process_id'";
	$SrchQ .="&process_id=$process_id";
}

if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = FCrtRplc($_REQUEST['date_from']);
	$date_to = FCrtRplc($_REQUEST['date_to']);
	$StrWhr .=" AND ( DATE(tcb.date_time) BETWEEN '".InsertDate($date_from)."' AND '".InsertDate($date_to)."')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

$QR_PAGES="SELECT tcb.*, tm.user_id,  CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name,
         spr.user_id AS spr_user_id, CONCAT_WS(' ',spr.first_name,spr.last_name) AS spr_full_name,
		 tm.pan_no, tp.start_date, tp.end_date
		 FROM ".prefix."tbl_cmsn_binary AS tcb 
		 LEFT JOIN ".prefix."tbl_process AS tp ON tp.process_id=tcb.process_id
		 LEFT JOIN ".prefix."tbl_members AS tm ON tm.member_id=tcb.member_id
		 LEFT JOIN ".prefix."tbl_members AS spr ON spr.member_id=tm.sponsor_id
		 WHERE 1 $StrWhr ORDER BY tcb.binary_id DESC";
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
<script src="<?php echo BASE_PATH; ?>javascripts/genvalidator.js"></script>
<?php auto_complete(); ?>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
<![endif]-->
<style type="text/css">
tr > td {
	font-size: 11px;
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
          <h1> REPORT <small> <i class="ace-icon fa fa-angle-double-right"></i>DIRECT REFFERAL INCOME </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-4">
            <div class="clearfix">
              <form id="form-search" name="form-search" method="get" action="<?php echo generateAdminForm("report","directoutreport",""); ?>">
                <b>USER ID</b>
                        <div class="form-group">
                        <input name="user_id" type="text" class="form-control col-xs-12 col-sm-6" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id"  />
                        <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <b>PAN NO</b>
                        <div class="form-group">
                        <input name="pan_no" type="text" class="form-control" id="pan_no" value="<?php echo $_REQUEST['pan_no']; ?>" placeholder="Pan No"  />
                        </div>
                        <b>FROM DATE</b>
                        <div class="form-group">
                        <div class="input-group">
                        <input class="form-control col-xs-12 col-sm-6  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="FROM DATE" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                        </div>
                        <b>TO DATE</b>
                        <div class="form-group">
                        <div class="input-group">
                  <input class="form-control col-xs-12 col-sm-6  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                  <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                        
                        </div>
                      <b>PAYOUT PERIOD </b>
                      <div class="form-group">
                        <div class="clearfix">
                          <select class="form-control validate[required]" id="process_id" name="process_id" >
                            <option value="">----select process----</option>
                            <?php  DisplayCombo($_REQUEST['process_id'],"PROCESS");  ?>
                          </select>
                        </div>
                      </div>
                <input class="btn btn-sm btn-primary m-t-n-xs" value=" Search " type="submit">
                <a href="<?php echo generateSeoUrlAdmin("report","directoutreport",""); ?>"  class="btn btn-sm btn-danger m-t-n-xs" value=" Reset ">Reset</a>
              </form>
            </div>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <table id="no-more-tables" class="table table-striped table-bordered table-hover">
              
                <tr role="row">
                  <td  class="sorting">S.NO</td>
                  <td  class="sorting">DATE</td>
                  <td  class="sorting">PAYOUT PERIOD</td>
                  <td  class="sorting">USER ID</td>
                  <td  class="sorting">USER NAME</td>
                  <td  class="sorting">PAN NO.</td>
                  <td  class="sorting">BINARY INCOME</td>
                  <td  class="sorting">SUPER ADMIN CHARGE %</td>
                  <td  class="sorting">SUPER ADMIN CHARGE</td>
                  <td  class="sorting">NET BINARY INCOME</td>
                  <td  class="sorting">SPONSER DR % </td>
                  <td  class="sorting">SPONSER DR INCOME</td>
                  <td  class="sorting">SPONSER USER ID</td>
                  <td  class="sorting">SPONSER USER NAME</td>
                </tr>
              
             
				<?php 
                if($PageVal['TotalRecords'] > 0){
                $Ctrl=$PageVal['RecordStart']+1;
                foreach($PageVal['ResultSet'] as $AR_DT):
				
				$total_income = $AR_DT['total_income'];
				$direct_ratio = $AR_DT['direct_ratio'];
				
				$direct_out_income = $total_income*$direct_ratio/100;
                ?>
                <tr class="odd" role="row">
                  <td><?php echo $Ctrl; ?></td>
                  <td><?php echo $AR_DT['date_time']; ?></td>
                  <td><?php echo DisplayDate($AR_DT['start_date']); ?> - To - <?php echo DisplayDate($AR_DT['end_date']); ?></td>
                  <td><?php echo $AR_DT['user_id']; ?></td>
                  <td><?php echo $AR_DT['full_name']; ?></td>
                  <td><?php echo $AR_DT['pan_no']; ?></td>
                  <td><?php echo number_format($AR_DT['amount '],2);?></td>
                  <td><?php echo number_format($AR_DT['superadmin_charge_ratio'],2);?></td>
                  <td><?php echo number_format($AR_DT['superadmin_charge'],2);?></td>
                  <td><?php echo number_format($total_income,2);?></td>
                  <td><?php echo number_format($direct_ratio,2);?> %</td>
                  <td><?php echo number_format($direct_out_income,2);?></td>
                  <td><?php echo $AR_DT['spr_user_id']; ?></td>
                  <td><?php echo $AR_DT['spr_full_name']; ?></td>
                </tr>
                <?php $Ctrl++; endforeach;
						}
						 ?>
              
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
</div>
<?php $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>

<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace-elements.min.js"></script> 
<script src="<?php echo BASE_PATH; ?>assets/js/ace.min.js"></script>
<?php jquery_validation(); ?>
<script type="text/javascript">
	$(function(){
		$("#form-valid").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
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