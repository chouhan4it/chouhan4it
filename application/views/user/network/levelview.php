<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	$model = new OperationModel();
	$today_date = InsertDate(getLocalTime());
	$yester_date = InsertDate(AddToDate($today_date,"-1 Day"));
	
	$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
	$member_id = _d(FCrtRplc($_REQUEST['member_id']));
	if($member_id == ""){$member_id = $this->session->userdata('mem_id');}
	$AR_MEM = $model->getMember($member_id,"LVL");
	
	
	$Q_GEN =  "SELECT nlevel, nleft, nright FROM tbl_mem_tree WHERE member_id='".$member_id."'";
	$AR_GEN = $this->SqlModel->runQuery($Q_GEN,true);
	$nlevel = $AR_GEN['nlevel'];
	$nleft = $AR_GEN['nleft'];
	$nright = $AR_GEN['nright'];
	
	//$StrQuery = "&member_id=$member_id";
	
	$QR_MEM = "SELECT tm.*, CONCAT_WS(' ',tmsp.first_name,tmsp.last_name) AS spsr_full_name,  tmsp.user_id AS spsr_user_id ,
			   tree.nlevel, tree.nleft, tree.nright, tree.date_join ,tr.rank_name
			   FROM tbl_members AS tm	
			   LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
			   LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id
			   LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
			   WHERE tm.block_sts='N' AND tm.delete_sts>0 AND  tree.nleft BETWEEN '$nleft' AND '$nright'
			   $StrWhr 
			   ORDER BY tree.nlevel ASC, tree.member_id ASC";
	$PageVal = DisplayPages($QR_MEM, 20000, $Page, $SrchQ);
	
	$QR_EXPORT = "SELECT  tree.nlevel AS LEVEL, CONCAT_WS(' ',tm.first_name,tm.last_name) AS FULL_NAME, tm.user_id AS PC_RC_ID, 
		 CONCAT_WS(' ',tmsp.first_name,tmsp.last_name) AS COSULTANT_NAME,  tmsp.user_id AS COSULTANT_ID ,
		 tr.rank_name AS CADRE, tm.date_join AS REGISTER_DATE, 
		 tm.city_name AS CITY, tm.state_name AS STATE
		 FROM tbl_members AS tm	
		  LEFT JOIN tbl_members AS tmsp  ON tm.sponsor_id=tmsp.member_id
		 LEFT JOIN tbl_mem_tree AS tree ON tm.member_id=tree.member_id
		 LEFT JOIN tbl_rank AS tr  ON tr.rank_id=tm.rank_id
		 WHERE  tm.delete_sts>0  AND  tree.nleft BETWEEN '$nleft' AND '$nright'
		 $StrWhr
		 ORDER BY tree.nlevel ASC, tree.member_id ASC";
	ExportQuery($QR_EXPORT);
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
        <h4 class="page-title">My Level</h4>
        <p class="text-muted page-title-alt">Your downline view</p>
      </div>
    </div>
    <!-- end row -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card-box">
          <div class="row">
              <div class="col-md-3">
                <form method="post" action="<?php echo generateMemberForm("network","levelview",""); ?>" enctype="multipart/form-data" name="form-page" id="form-page">
                  <div class="form-group">
                   	<input name="user_id" type="text" class="form-control" id="user_id" value="<?php echo $_REQUEST['user_id']; ?>" placeholder="User Id" style="width:200px;" />
					<input name="member_id" type="hidden" id="member_id" value="<?php echo $_REQUEST['member_id']; ?>" />
                  </div>
                  
				  
                  <input class="btn btn-sm btn-primary m-t-n-xs" name="searchRequest" value="Search" type="submit">
				  <input type="button" class="btn btn-sm btn-danger m-t-n-xs" name="reset" id="reset" value="Reset" onClick="window.location.href='?'">
                </form>
              </div>
          </div>
		<div class="clearfix">&nbsp;</div>
          <div class="table-responsive">
            <table class="table table-actions-bar">
              <thead>
                <tr>
                  <th>Srl #</th>
                  <th>Profile</th>
                  <th>Full Name</th>
                  <th>User Id </th>
                  <th>Sponsor  Id </th>
                  <th >D.O.J</th>
                  <th >D.O.A</th>
                  <th >City</th>
                  <th >State</th>
                </tr>
              </thead>
              <tbody>
				 <?php 
                if($PageVal['TotalRecords'] > 0){
                $CurrLvl = $PreLvl = 0;
                $Ctrl = $PageVal['RecordStart'];
				
                foreach($PageVal['ResultSet'] as $AR_Fld){
                $Ctrl++;
                
                
				$AR_IMG = $model->getCurrentImg($AR_Fld['member_id']);
                
                $CurrLvl = $AR_Fld['nlevel'];
                if(($CurrLvl != $PreLvl)){
                    $LvlCtrl = $CurrLvl - $nlevel;
                ?>
                <tr >
                     <td colspan="9" align="left" valign="middle" class="alert alert-info"><strong>Level <?php echo $LvlCtrl;?></strong></td>
                </tr>
                <?php 
               	 $PreLvl = $AR_Fld['nlevel'];
                }
                ?>
                <tr >
                  <td><?php echo $Ctrl;?></td>
                  <td><img src="<?php echo $AR_IMG['IMG_SRC'];?>" class="img-circle" width="25" height="25" /></td>
                  <td><a href="?user_id=<?php echo $AR_Fld['user_id']?>&member_id=<?php echo _e($AR_Fld['member_id']);?>"><?php echo $AR_Fld['first_name']." ".$AR_Fld['last_name'];?></a></td>
                  <td><?php echo strtoupper($AR_Fld['user_id']);?></td>
                  <td><?php echo $AR_Fld['spsr_user_id'];?></td>
                  <td><?php echo DisplayDate($AR_Fld['date_join']);?></td>
                  <td><?php echo DisplayDate($AR_CHNL['date_from']);?></td>
                  <td><?php echo $AR_Fld['city_name'];?></td>
                  <td><?php echo $AR_Fld['state_name'];?></td>
                </tr>
               
				  <?php }?>
           		 <?php }else{?>
                 	<tr >
                   		 <td colspan="9" align="left" valign="middle" class="cmntext">No record found</td>
                    </tr>
                   <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- end col -->
    </div>
    <?= $this->load->view(MEMBER_FOLDER.'/footer'); ?>
  </div>
</div>
<!-- jQuery  -->
<?= $this->load->view(MEMBER_FOLDER.'/footerjs'); ?>
</body>
<?php jquery_validation(); auto_complete(); ?>
<script type="text/javascript" language="javascript">
new Autocomplete("user_id", function(){
	this.setValue = function( id ) {document.getElementsByName("member_id")[0].value = id;}
	if(this.isModified) this.setValue("");
	if(this.value.length < 1 && this.isNotClick ) return;
	return "<?php echo BASE_PATH; ?>autocomplete/listing?srch=" + this.value+"&switch_type=MEM_DOWNLINE&member_id=<?php echo $this->session->userdata('mem_id'); ?>";
});
</script>
</html>
