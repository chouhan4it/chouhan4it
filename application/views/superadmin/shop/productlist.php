<?php defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}

if($_REQUEST['post_title']!=''){
	$post_title = FCrtRplc($_REQUEST['post_title']);
	$StrWhr .=" AND ( tpl.post_title LIKE '%$post_title%' OR tpl.post_desc LIKE '%$post_title%' )";
	$SrchQ .="&post_title=$post_title";
}

if(isset($_REQUEST['is_product'])){
	$is_product = FCrtRplc($_REQUEST['is_product']);
	$StrWhr .=" AND ( tp.is_product = '$is_product' )";
	$SrchQ .="&is_product=$is_product";
	
}

if($_REQUEST['franchisee_id']>0){
	$franchisee_id = FCrtRplc($_REQUEST['franchisee_id']);
	$StrWhr .=" AND ( tp.franchisee_id = '$franchisee_id' )";
	$SrchQ .="&franchisee_id=$franchisee_id";
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
			GROUP_CONCAT(tt.tag_name) AS tags_name, COUNT(tpv.view_id) AS view_ctrl, COUNT(tpr.review_id) AS review_ctrl,
			tf.user_name AS seller_name
			FROM tbl_post AS tp
			LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
			LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
			LEFT JOIN tbl_tags AS tt ON  FIND_IN_SET(tt.tag_id,tpl.post_tags)
			LEFT JOIN tbl_post_view AS tpv ON tpv.post_id=tp.post_id
			LEFT JOIN tbl_post_review AS tpr ON tpr.post_id=tp.post_id
			LEFT JOIN tbl_franchisee AS tf ON tf.franchisee_id=tp.franchisee_id
			WHERE tp.delete_sts>0    $StrWhr 
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
          <h1> Product <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp; List </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <?php get_message(); ?>
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12">
                <div class="clearfix">
                  <div class="pull-right tableTools-container">
                    <div class="dt-buttons btn-overlap btn-group"> <a href="<?php echo generateSeoUrlAdmin("shop","postproduct",array("")); ?>" title="" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-collection buttons-colvis btn btn-white btn-primary btn-bold"><span><i class="fa fa-plus bigger-110 blue"></i> <span class="hidden">Show/hide columns</span></span></a> <a  class="dt-button buttons-copy buttons-html5 btn btn-white btn-primary btn-bold open_modal"><span><i class="fa fa-search bigger-110 pink"></i> <span class="hidden">Search</span></span></a> <a  href="<?php echo generateSeoUrlAdmin("export","excel",array("")); ?>" data-original-title="" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-csv buttons-html5 btn btn-white btn-primary btn-bold"><span><i class="fa fa-database bigger-110 orange"></i> <span class="hidden">Export to CSV</span></span></a> <a  href="<?php echo generateSeoUrlAdmin("export","pdf",""); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a> <a  onClick="window.print()" aria-controls="dynamic-table" tabindex="0" class="dt-button buttons-print btn btn-white btn-primary btn-bold"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a> </div>
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
                      <tr class="<?php echo ($AR_DT['post_sts']>0)? "":"text text-danger"; ?>">
                        <td width="60" rowspan="3" class="center"><label class="pos-rel"> <img class="img-responsive" src="<?php echo $model->getDefaultPhoto($AR_DT['post_id']); ?>"> <span class="lbl"></span> </label></td>
                        <td width="65"><strong>Product Name : </strong></td>
                        <td width="169"><a href="javascript:void(0)"><?php echo $AR_DT['post_title']; ?> </a> [<?php echo $AR_DT['post_code']; ?>]</td>
                        <td width="104"><strong>Tags :</strong></td>
                        <td width="166"><?php echo ($AR_DT['tags_name']); ?></td>
                        <td width="87"><strong>Update Date  : </strong></td>
                        <td width="217"><?php echo getDateFormat($AR_DT['update_date'],"d D Y M"); ?></td>
                        <td width="94" rowspan="3"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                            <ul class="dropdown-menu dropdown-default">
                              <li> <a href="<?php echo generateSeoUrlAdmin("shop","postproduct",array("post_id"=>_e($AR_DT['post_id']))); ?>">Edit</a> </li>
                              <li> <a href="<?php echo generateSeoUrlAdmin("shop","postphoto",array("post_id"=>_e($AR_DT['post_id']))); ?>">Product Image</a> </li>
                              <li> <a href="<?php echo generateSeoUrlAdmin("shop","postattributelist",array("post_id"=>_e($AR_DT['post_id']))); ?>">Product Attribute</a> </li>
                              <li> <a onClick="return confirm('Make sure , you want to change this product status?')" href="<?php echo generateSeoUrlAdmin("shop","postproduct",array("post_id"=>_e($AR_DT['post_id']),"action_request"=>"STATUS","post_sts"=>$AR_DT['post_sts'])); ?>"><?php echo ($AR_DT['post_sts']>0)? "In-Active":"Active"; ?></a> </li>
                              <li> <a onClick="return confirm('Make sure, want to delete this product?')" 
							href="<?php echo generateSeoUrlAdmin("shop","postproduct",array("action_request"=>"DELETE","post_id"=>_e($AR_DT['post_id']))); ?>">Delete</a> </li>
                            </ul>
                          </div></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['post_sts']>0)? "":"text text-danger"; ?>">
                        <td><strong>Seller  : </strong></td>
                        <td><?php echo $AR_DT['seller_name']; ?></td>
                        <td><strong>Category : </strong></td>
                        <td><?php echo $model->bootstrap_category($AR_DT['category_id']); ?></td>
                        <td><strong>Status : </strong></td>
                        <td><?php echo ($AR_DT['post_sts']>0)? "Active":"<span class='red'>In-Active</spane>"; ?></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['post_sts']>0)? "":"text text-danger"; ?>">
                        <td><strong> Price: </strong></td>
                        <td><?php echo number_format($AR_DT['post_price'],2); ?></td>
                        <td><strong>No of Review : </strong></td>
                        <td><?php echo number_format($AR_DT['review_ctrl']); ?></td>
                        <td><strong>Viewed : </strong></td>
                        <td><?php echo number_format($AR_DT['view_ctrl']); ?></td>
                      </tr>
                      <tr class="<?php echo ($AR_DT['post_sts']>0)? "":"text text-danger"; ?>">
                        <td colspan="8" class="center"><hr class="divider">
                          </hr></td>
                      </tr>
                      <?php $Ctrl++; 
					  endforeach;
					   }else{ ?>
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
        <form class="form-horizontal"  name="form-page" id="form-page" action="<?php echo generateAdminForm("shop","productlist",""); ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Branch : </label>
              <div class="col-sm-9">
                <select name="franchisee_id" id="franchisee_id" class="col-xs-12 col-sm-5 validate[required]" >
                  <option value="">----select branch----</option>
                  <?php echo DisplayCombo($ROW['franchisee_id'],"STORE_LOCATOR"); ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Product Title  :</label>
              <div class="col-sm-7">
                <input id="form-field-1" placeholder="Product Name / Code" name="post_title"  class="col-xs-10 col-sm-12" type="text" value="<?php echo $_REQUEST['post_title']; ?>">
              </div>
            </div>
            <div class="space-2"></div>
            <div class="form-group">
              <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Category : </label>
              <div class="col-xs-12 col-sm-7">
                <div class="clearfix">
                  <select name="category_id[]" id="category_id[]" class="col-xs-12 col-sm-12" multiple="multiple">
                    <?php echo DisplayCombo($_REQUEST['category_id'],"CATEGORY_ALL"); ?>
                  </select>
                </div>
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
