<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$franchisee_id = $this->session->userdata('fran_id');

if($_REQUEST['post_title']!=''){
	$post_title = FCrtRplc($_REQUEST['post_title']);
	$StrWhr .=" AND ( tpl.post_title LIKE '%$post_title%' OR tpl.post_desc LIKE '%$post_title%' )";
	$SrchQ .="&post_title=$post_title";
}


if($_REQUEST['date_from']!='' && $_REQUEST['date_to']!=''){
	$date_from = InsertDate($_REQUEST['date_from']);
	$date_to = InsertDate($_REQUEST['date_to']);
	$StrWhr .=" AND ( DATE(tp.post_date) BETWEEN '$date_from' AND '$date_to')";
	$SrchQ .="&date_from=$date_from&date_to=$date_to";
}

if(isset($_REQUEST['is_product'])){
	$is_product = FCrtRplc($_REQUEST['is_product']);
	$StrWhr .=" AND ( tp.is_product = '$is_product' )";
	$SrchQ .="&is_product=$is_product";
	
}

if($_REQUEST['post_sts']!=''){
	$post_sts = FCrtRplc($_REQUEST['post_sts']);
	$StrWhr .=" AND ( tp.post_sts = '$post_sts' )";
	$SrchQ .="&post_sts=$post_sts";
}
if($_REQUEST['category_id']>0){
	$category_id = FCrtRplc($_REQUEST['category_id']);
	
}
if(is_array($_REQUEST['category_id'])){
	$category_id = $_REQUEST['category_id'];
	$category_id_array = implode(",",array_unique(array_filter($category_id)));
	$StrWhr .=" AND (tpc.category_id IN($category_id_array) )";
	$SrchQ .="&category_id=$category_id";
}
$QR_PAGES = "SELECT tp.*,  tpl.lang_id, tpl.post_title, tpl.post_tags, tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
			tpl.post_price,  tpl.update_date , tpl.post_pv, GROUP_CONCAT(tpc.category_id) AS category_id,
			GROUP_CONCAT(tt.tag_name) AS tags_name, COUNT(tpv.view_id) AS view_ctrl, COUNT(tpr.review_id) AS review_ctrl
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tt.tag_id,tpl.post_tags)
			LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
			LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
			WHERE tp.delete_sts>0 
			AND tp.franchisee_id='".$franchisee_id."'
			  $StrWhr 
			GROUP BY tp.post_id  
			ORDER BY tp.post_id DESC";
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
        <h1> Product <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; List  </small> </h1>
      </div>
      <!-- /.page-header -->
      <div class="row">
        <?php get_message(); ?>
        <div class="col-xs-12">
          <div class="row">
		  	<div class="col-xs-12">
              <div class="clearfix">
                <div class="row">
                  <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateFranchiseForm("shop","productlist",""); ?>" method="post">
                    <div class="col-md-2">
                      <input id="post_title" placeholder="Product Name" name="post_title"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['post_title']; ?>">
                     
                    </div>
                    <div class="col-md-2">
                     <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_from" id="date_from" value="<?php echo $_REQUEST['date_from']; ?>" placeholder="Date From" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input class="form-control col-xs-10 col-sm-12  validate[required] date-picker" name="date_to" id="date_to" value="<?php echo $_REQUEST['date_to']; ?>" placeholder="Date To" type="text"  />
                        <span class="input-group-addon"> <i class="fa fa-calendar"></i></span> </div>
                    </div>
					<div class="col-md-6">
						<button type="submit" name="submitSearch" value="1" class="btn btn-sm btn-success"> <i class="ace-icon fa fa-check"></i> Search </button>
          				<button type="button" class="btn btn-sm btn-danger" onClick="window.location.href='?'"> <i class="ace-icon fa fa-refresh"></i> Reset </button>
						<button type="button" class="btn btn-sm btn-info" onClick="window.location.href='<?php echo generateSeoUrlAdmin("export","excel",""); ?>'"> <i class="ace-icon fa fa-download"></i> Download </button>
					</div>
                  </form>
                </div>
              </div>
            </div>
			<hr>
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
			       ?>
                    <tr  class="<?php echo ($AR_DT['post_sts']>0)? "":"text text-danger"; ?>">
                      <td width="60" rowspan="3" class="center"><label class="pos-rel"> <img class="img-responsive" src="<?php echo $model->getDefaultPhoto($AR_DT['post_id']); ?>"> <span class="lbl"></span> </label>                      </td>
                      <td width="65"><strong>Product Name : </strong></td>
                      <td width="169"><a href="javascript:void(0)"><?php echo $AR_DT['post_title']; ?></a></td>
                      <td width="104"><strong>Type (Networking) :</strong></td>
                      <td width="166"><?php echo ($AR_DT['is_product']>0)? "Yes":"No"; ?></td>
                      <td width="87"><strong>Update Date  : </strong></td>
                      <td width="217"><?php echo getDateFormat($AR_DT['update_date'],"d D Y M"); ?></td>
                      <td width="94" rowspan="3"><div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                          <ul class="dropdown-menu dropdown-default">
						  						
							<li> <a href="<?php echo generateSeoUrlFranchise("shop","postproduct",array("post_id"=>_e($AR_DT['post_id']))); ?>">Edit</a> </li>
							<li> <a href="<?php echo generateSeoUrlFranchise("shop","postphoto",array("post_id"=>_e($AR_DT['post_id']))); ?>">Product Image</a> </li>
                            <li> <a href="<?php echo generateSeoUrlFranchise("shop","postattributelist",array("post_id"=>_e($AR_DT['post_id']))); ?>">Product Attribute</a> </li>
							<li> <a onClick="return confirm('Make sure , you want to change this product stock status?')" href="<?php echo generateSeoUrlFranchise("shop","postproduct",array("post_id"=>_e($AR_DT['post_id']),"action_request"=>"STOCK_STS","stock_sts"=>$AR_DT['stock_sts'])); ?>"><?php echo ($AR_DT['stock_sts']>0)? "Un-Available":"Available"; ?></a> </li>
							<li> <a onClick="return confirm('Make sure, want to delete this product?')" 
							href="<?php echo generateSeoUrlFranchise("shop","postproduct",array("action_request"=>"DELETE","post_id"=>_e($AR_DT['post_id']))); ?>">Delete</a> </li>
                          </ul>
                      </div></td>
                    </tr>
                    <tr  class="<?php echo ($AR_DT['post_sts']>0)? "":"text text-danger"; ?>">
                      <td><strong>Product Code  : </strong></td>
                      <td><?php echo $AR_DT['post_code']; ?></td>
                      <td><strong>Category : </strong></td>
                      <td><?php echo $model->bootstrap_category($AR_DT['category_id']); ?></td>
                      <td><strong>Stock Status : </strong></td>
                      <td><?php echo ($AR_DT['stock_sts']>0)? "<span class='green'>Available</span>":"<span class='red'>Un-Available</spane>"; ?></td>
                    </tr>
                    <tr  class="<?php echo ($AR_DT['post_sts']>0)? "":"text text-danger"; ?>">
                      <td><strong> Price: </strong></td>
                      <td><?php echo number_format($AR_DT['post_price'],2); ?></td>
                      <td><strong>No of Review : </strong></td>
                      <td><?php echo number_format($AR_DT['review_ctrl']); ?></td>
                      <td><strong>Viewed : </strong></td>
                      <td><?php echo number_format($AR_DT['view_ctrl']); ?></td>
                    </tr>
                    
                    <tr>
                      <td colspan="8" class="center"><hr class="divider"></hr></td>
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
      <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("shop","orderlist",""); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Order No  :</label>
            <div class="col-sm-7">
              <input id="form-field-1" placeholder="Order No" name="order_no"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['order_no']; ?>">
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
