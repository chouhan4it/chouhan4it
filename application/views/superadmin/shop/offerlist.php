<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['offer_title']!=''){
	$offer_title = FCrtRplc($_REQUEST['offer_title']);
	$StrWhr .=" AND ( tof.offer_title LIKE '%$offer_title%'  )";
	$SrchQ .="&offer_title=$offer_title";
}
if($_REQUEST['offer_module']!=''){
	$offer_module = FCrtRplc($_REQUEST['offer_module']);
	$StrWhr .=" AND ( tof.offer_module = '$offer_module'  )";
	$SrchQ .="&offer_module=$offer_module";
}


$QR_PAGES = "SELECT tof.* FROM tbl_offer  AS tof
			WHERE tof.delete_sts>0   $StrWhr 
			GROUP BY tof.offer_id  
			ORDER BY tof.offer_id DESC";
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
          <h1> Offer <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; List </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a href="<?php echo generateSeoUrlAdmin("shop","postoffer",array("")); ?>" title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-collection buttons-colvis btn btn-white btn-primary btn-bold"><span><i class="fa fa-plus bigger-110 blue"></i> <span class="hidden">Show/hide columns</span></span></a> <a  class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold open_modal"><span><i class="fa fa-search bigger-110 pink"></i> <span class="hidden">Search</span></span></a> <a  href="<?php echo generateSeoUrlAdmin("export","excel",array("")); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a  href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> <a href="<?php echo generateSeoUrlAdmin("shop","postmultipleoffer",array("")); ?>" title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-collection buttons-colvis btn btn-white btn-primary btn-bold"><span><i class="fa fa-plus bigger-110 blue"></i> <span class="hidden">Show/hide columns</span></span></a></div>
                  </div>
                </div>
                <!-- div.table-responsive --> 
                <!-- div.dataTables_borderWrap -->
                <div>
                  <table id="" class="table">
                    <thead>
                    </thead>
                    <tbody>
                      <?php 
			 	 	if($PageVal['TotalRecords'] > 0){
				  		$Ctrl=1;
						foreach($PageVal['ResultSet'] as $AR_DT):
			       ?>
                      <tr>
                        <td width="60" rowspan="3" class="center"><label class="pos-rel"> <img class="img-responsive" src="<?php echo $model->getOfferPhoto($AR_DT['offer_id']); ?>"> <span class="lbl"></span> </label></td>
                        <td width="65"><strong>Offer Name : </strong></td>
                        <td width="169"><a href="javascript:void(0)"><?php echo $AR_DT['offer_title']; ?></a></td>
                        <td width="104"><strong>Min Price  :</strong></td>
                        <td width="166"><?php echo $AR_DT['offer_min_price']; ?></td>
                        <td width="87"><strong> Type  : </strong></td>
                        <td width="217"><?php echo $AR_DT['offer_type']; ?></td>
                        <td width="94" rowspan="3"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                              <?php if($this->session->userdata('oprt_id')<=2){?>
                              <li> <a href="<?php echo generateSeoUrlAdmin("shop","postoffer",array("offer_id"=>_e($AR_DT['offer_id']))); ?>">Edit</a> </li>
                              <li> <a onClick="return confirm('Make sure , you want to change this member status?')" href="<?php echo generateSeoUrlAdmin("shop","postoffer",array("offer_id"=>_e($AR_DT['offer_id']),"action_request"=>"STATUS","offer_sts"=>$AR_DT['offer_sts'])); ?>"><?php echo ($AR_DT['offer_sts']>0)? "In-Active":"Active"; ?></a> </li>
                              <li> <a onClick="return confirm('Make sure, want to copy this offer')" 
							href="<?php echo generateSeoUrlAdmin("shop","postoffer",array("action_request"=>"COPY","offer_id"=>_e($AR_DT['offer_id']))); ?>">Copy</a> </li>
                              <li> <a onClick="return confirm('Make sure, want to delete this member')" 
							href="<?php echo generateSeoUrlAdmin("shop","postoffer",array("action_request"=>"DELETE","offer_id"=>_e($AR_DT['offer_id']))); ?>">Delete</a> </li>
                              <?php }?>
                              <li> <a href="<?php echo generateSeoUrlAdmin("shop","reportoffer",""); ?>?offer_id=<?php echo $AR_DT['offer_id']; ?>">Report</a> </li>
                            </ul>
                          </div></td>
                      </tr>
                      <tr>
                        <td><strong>Offer Code  : </strong></td>
                        <td><?php echo $AR_DT['offer_code']; ?></td>
                        <td><strong>Max Price  : </strong></td>
                        <td><?php echo $AR_DT['offer_max_price']; ?></td>
                        <td><strong>Expiry Date  : </strong></td>
                        <td><?php echo getDateFormat($AR_DT['offer_expiry'],"d D Y M"); ?></td>
                      </tr>
                      <tr>
                        <td><strong>PV: </strong></td>
                        <td><?php echo number_format($AR_DT['offer_pv']); ?></td>
                        <td><strong>BV : </strong></td>
                        <td><?php echo number_format($AR_DT['offer_bv']); ?></td>
                        <td><strong>Price : </strong></td>
                        <td><?php echo number_format($AR_DT['offer_price']); ?></td>
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
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("shop","offerlist",""); ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Offer Title  :</label>
              <div class="col-sm-7">
                <input id="form-field-1" placeholder="Keywords" name="offer_title"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['offer_title']; ?>">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Offer Type  :</label>
              <div class="col-sm-7">
                <select name="offer_module" id="offer_module" class="form-control validate[required]" style="width:auto;">
                  <option value="">---select---</option>
                  <!--<option value="OPOF-M" <?php if($_REQUEST['offer_module']=='OPOF-M'){echo "selected";} ?>>Green Tea One Plus One Multiple Offer</option>-->
                  <?php DisplayCombo($_REQUEST['offer_module'],"OFFER_MODULE"); ?>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
            <button type="button" class="btn  btn-sm btn-warning" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
            <button type="button" class="btn btn-sm btn-danger pull-right" data-dismiss="modal"> <i class="ace-icon fa fa-times"></i> Close </button>
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
