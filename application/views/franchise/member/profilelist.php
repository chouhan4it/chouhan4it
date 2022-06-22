<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$franchisee_id = $this->session->userdata('fran_id');

$QR_PAGES="SELECT tm.* FROM  tbl_members AS tm	 WHERE tm.delete_sts>0 AND  tm.franchisee_id='".$franchisee_id."'   $StrWhr ORDER BY tm.member_id ASC";
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
<script src="<?php echo BASE_PATH; ?>assets/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/ace-extra.min.js"></script>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->

</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> PC <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; Details  </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-12">
              <div>
                <table id="" class="table">
                  <thead>
                  </thead>
                  <tbody>
                    <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
						$plan_name = $model->getPackageName($AR_DT['package_id']);
			       ?>
                    <tr>
                      <td width="22" rowspan="3" class="center"><label class="pos-rel"> <?php echo $AR_DT['member_id']; ?> <span class="lbl"></span> </label>                      </td>
                      <td width="112">Full Name : </td>
                      <td width="148"><a href="javascript:void(0)"><?php echo $AR_DT['first_name']." ".$AR_DT['last_name']; ?></a></td>
                      <td width="126">Email Address : </td>
                      <td width="120"><?php echo $AR_DT['member_email']; ?></td>
                      <td width="140">Last Login : </td>
                      <td width="164"><?php echo getDateFormat($AR_DT['last_login'],"d D Y M"); ?></td>
                    </tr>
                    <tr>
                      <td>Mobile : </td>
                      <td><?php echo $AR_DT['member_mobile']; ?></td>
                      <td>Block Status :</td>
                      <td><?php echo ($AR_DT['block_sts']=="Y")? "Yes":"No"; ?></td>
                      <td>Status : </td>
                      <td><?php echo ($AR_DT['status']=="Y")? "Active":"In-Active"; ?></td>
                    </tr>
                    <tr>
                      <td>User Id : </td>
                      <td><?php echo $AR_DT['user_id']; ?></td>
                      <td>City</td>
                      <td><?php echo $AR_DT['country_name']; ?></td>
                      <td>Date of Join  </td>
                      <td><?php echo DisplayDate($AR_DT['date_join']); ?></td>
                    </tr>
                    
                    <tr>
                      <td colspan="7" class="center"><hr class="divider"></hr></td>
                    </tr>
                    <?php $Ctrl++; endforeach; }else{ ?>
                    <tr>
                      <td colspan="7" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
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
