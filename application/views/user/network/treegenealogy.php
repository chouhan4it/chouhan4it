<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
if($_REQUEST['member_id'] == ""){ $member_id=$this->session->userdata('mem_id'); }
else{$member_id = _d($_REQUEST['member_id']);}


$QR_TREE = "SELECT * FROM tbl_mem_tree WHERE spil_id = '$member_id' AND (nleft>0 AND nright>0)";
$RS_TREE = $this->SqlModel->runQuery($QR_TREE);
foreach($RS_TREE as $AR_TREE):
	if($AR_TREE['left_right'] == "L"){
		$Left_Lft = $AR_TREE['nleft'];
		$Left_Rgt = $AR_TREE['nright'];
	}
	if($AR_TREE['left_right'] == "R"){
		$Right_Lft = $AR_TREE['nleft'];
		$Right_Rgt = $AR_TREE['nright'];
	}
endforeach;


if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!=''){
	$from_date = InsertDate($_REQUEST['from_date']);
	$to_date = InsertDate($_REQUEST['to_date']);	
	$StrWhr .=" AND DATE(ts.date_from) BETWEEN '".$from_date."' AND '".$to_date."'";
	$SrchQ  = "&from_date=$from_date&to_date=$to_date";
}

$Q_LeftMem = "SELECT ts.* ,  tree.nlevel, tree.left_right, tree.nleft, tree.nright, tree.date_join,
			  tm.first_name, tm.last_name, tm.user_id
			  FROM tbl_subscription AS ts 
			  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=ts.member_id
			  LEFT JOIN tbl_members AS tm ON tm.member_id=ts.member_id
			  WHERE tree.nleft BETWEEN '$Left_Lft' AND '$Left_Rgt'
			  $StrWhr
			  ORDER BY tree.nlevel ASC";
$RS_LftMem = $this->SqlModel->runQuery($Q_LeftMem);


$Q_RightMem = "SELECT ts.* ,  tree.nlevel, tree.left_right, tree.nleft, tree.nright, tree.date_join,
			  tm.first_name, tm.last_name, tm.user_id
			  FROM tbl_subscription AS ts 
			  LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=ts.member_id
			  LEFT JOIN tbl_members AS tm ON tm.member_id=ts.member_id
			  WHERE tree.nleft BETWEEN '$Right_Lft' AND '$Right_Rgt' $StrWhr 
			  $StrWhr
			  ORDER BY tree.nlevel ASC";
$RS_RgtMem = $this->SqlModel->runQuery($Q_RightMem);
 ?>
<!DOCTYPE html>
<html>
<!-- Mirrored from coderthemes.com/ubold_1.3/menu_2/dashboard_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 31 Jan 2016 06:40:15 GMT -->
<head>
<?= $this->load->view(MEMBER_FOLDER.'/pagehead'); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/bootstrap-datetimepicker.min.css" />
<!--<link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />-->
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
        <h4 class="page-title">Tree Genealogy</h4>
        <p class="text-muted page-title-alt">Your Genealogy</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
       <?php echo get_message(); ?>
      <div class="col-lg-12">
        <div class="portlet">
          <div class="portlet-body" >
          <div class="row">
            <div class="col-lg-3">
              <form method="post" action="<?php echo generateMemberForm("network","treegenealogy",""); ?>" enctype="multipart/form-data" name="form-page" id="form-page">
              	<b>User Id</b>
			  	<div class="form-group">
                 	<input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>"  />
                      <input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" /> 
                </div>
				<b>Date</b>
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-3 col-sm-3 date-picker" name="from_date" id="id-date-picker-1" value="<?php echo $_REQUEST['from_date']; ?>" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input class="form-control col-xs-3 col-sm-3  date-picker" name="to_date" id="id-date-picker-1" value="<?php echo $_REQUEST['to_date']; ?>" type="text"  />
                    <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                </div>
                <input class="btn btn-sm btn-primary m-t-n-xs" name="searchRequest" value="Search" type="submit">
                <input type="button" class="btn btn-sm btn-danger m-t-n-xs" name="reset" id="reset" value="Reset" onClick="window.location.href='?'">
              </form>
            </div>
          </div>
          <div class="clearfix">&nbsp;</div>
            <div class="row">
              <div class="col-lg-12">
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
            
           
            
            <tr class="header">
              <td  align="center"><strong>Left Count : <?php echo count($RS_LftMem); ?></strong></td>
              <td  align="center"><strong>Right Count : <?php echo count($RS_RgtMem); ?></strong></td>
            </tr>
           
            <tr>
              <td align="center" valign="top"><table width="80%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse">
                    <tr class="lightbg">
                      <td align="center" class="cmntext" scope="col"><strong>SLN</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>Member     Id</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong> Name</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>Level</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>D.O.J</strong></td>
                      <td align="center" class="cmntext" scope="col"><strong>D.O.A</strong></td>
                    </tr>
                    <?php
					$Ctrl = 1;
					$curr_left_lvl = $pre_left_lvl = 0;
					foreach($RS_LftMem as $AR_LftMem):
					$curr_left_lvl = $AR_LftMem['nlevel'];
					if(($curr_left_lvl != $pre_left_lvl)){
						$level_left_ctrl = $curr_left_lvl - $AR_MEM['nlevel'];
					}
					$pre_left_lvl = $AR_LftMem['nlevel'];
				?>
                    <tr class="<?php echo $text_class; ?>">
                      <td  align="center" class="cmntext" scope="col"><?php echo $Ctrl;?></td>
                      <td  align="center" class="" scope="col"><?php echo $AR_LftMem['user_id']; ?></td>
                      <td  scope="col" class="cmntext" align="center"><?php echo $AR_LftMem['first_name']." ".$AR_LftMem['last_name'];?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo $level_left_ctrl; ?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_LftMem['date_join']);?></td>
                      <td  scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_LftMem['date_from']);?></td>
                    </tr>
                    <?php $Ctrl++;
				  endforeach;
				  ?>
                  </table></td>
              <td align="center" valign="top"><table width="80%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse;">
                    <tr class="lightbg">
                      <td align="center" class="cmntext" scope="col"><strong>SLN</strong></td>
                      <td  align="center" class="cmntext" scope="col"><strong>Member     Id </strong></td>
                      <td align="center" class="cmntext" scope="col"><strong> Name</strong></td>
                      <td  align="center" class="cmntext" scope="col"><strong>Level</strong></td>
                      <td  align="center" class="cmntext" scope="col"><strong>D.O.J</strong></td>
                      <td  align="center" class="cmntext" scope="col"><strong>D.O.A</strong></td>
                    </tr>
                    <?php
					$Ctrl = 1;
					$curr_right_lvl = $pre_right_lvl = 0;
					foreach($RS_RgtMem as $AR_RgtMem):
					$curr_right_lvl = $AR_RgtMem['nlevel'];
					if(($curr_right_lvl != $pre_right_lvl)){
						$level_right_ctrl = $curr_right_lvl - $AR_MEM['nlevel'];
					}
					$pre_right_lvl = $AR_RgtMem['nlevel'];
					?>
                    <tr class="<?php echo $text_class; ?>">
                      <td align="center" class="cmntext" scope="col"><?php echo $Ctrl;?></td>
                      <td align="center" class="" scope="col"><?php echo $AR_RgtMem['user_id'];?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo $AR_RgtMem['first_name']." ".$AR_RgtMem['last_name'];?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo $level_right_ctrl; ?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_RgtMem['date_join']);?></td>
                      <td scope="col" class="cmntext" align="center"><?php echo DisplayDate($AR_RgtMem['date_from']);?></td>
                    </tr>
                    <?php $Ctrl++;
				  endforeach; ?>
                  </table></td>
            </tr>
          </table>
                
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); ?> <?php  auto_complete(); ?>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#form-page").validationEngine();
		$('.date-picker').datetimepicker({
			format: 'YYYY-MM-DD'
		});
	});
</script>
<script type="text/javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo BASE_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEM_DOWNLINE&member_id=<?php echo _e($this->session->userdata('mem_id'));  ?>";
});
</script>
</html>
