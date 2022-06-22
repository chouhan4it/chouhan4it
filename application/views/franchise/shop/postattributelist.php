<?php defined('BASEPATH') OR exit('No direct script access allowed');
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$model = new OperationModel();
$form_data = $this->input->post();
$segment = $this->uri->uri_to_assoc(2);
$post_id = ($form_data['post_id'])? $form_data['post_id']:_d($segment['post_id']);
		
$AR_POST = $model->getPostDetail($post_id);

$QR_PAGES= "SELECT tpa.*, tpai.field_id
			FROM tbl_post_attribute AS tpa
			LEFT JOIN tbl_post_attribute_image AS tpai ON tpai.post_attribute_id=tpa.post_attribute_id
			WHERE tpa.delete_sts>0 AND tpa.post_id='".$post_id."'
			GROUP BY tpa.post_attribute_id
		   	ORDER BY tpa.post_attribute_id DESC";
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

</head>
<body class="no-skin">
<div class="main-content">
  <div class="main-content-inner">
    <?= $this->load->view(ADMIN_FOLDER.'/layout/breadcumb'); ?>
    <div class="page-content">
      <div class="page-header">
        <h1> Product Attribute List <small> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp;  <?php echo $AR_POST['post_title'];  ?>    </small> </h1>
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
       
					<div class="col-md-6">
                    	<a class="btn btn-sm btn-danger" href="<?php echo generateSeoUrlFranchise("shop","productlist",""); ?>"> <i class="ace-icon fa fa-arrow-left"></i> Back </a>
						<a  class="btn btn-sm btn-success" href="<?php echo generateSeoUrlFranchise("shop","postattribute",array("post_id"=>_e($AR_POST['post_id']))); ?>"> <i class="ace-icon fa fa-plus"></i> New </a>
          				
					</div>
                  </form>
                </div>
              </div>
            </div>
			<hr>
            <div class="col-xs-12">
              <div>
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th >Srl No</th>
                      <th >Image</th>
                      <th >Attribute Value</th>
                      <th >Product Price</th>
                      <th >Discount </th>
                      <th >Net Price</th>
                      <th >Default</th>
                      <th >Status</th>
                      <th >Action</th>
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
                      <td><img width="60" class="img-responsive" src="<?php echo $model->getFileSrc($AR_DT['field_id']); ?>"></td>
                      <td><?php echo DisplayAttrCombination($AR_DT['post_attribute_id']); ?></td>
                      <td><?php echo $AR_DT['post_attribute_mrp']; ?></td>
                      <td><?php echo $AR_DT['post_attribute_discount']; ?></td>
                      <td><?php echo $AR_DT['post_attribute_price']; ?></td>
                      <td><input type="radio" class="attribute_default" id="default_sts<?php echo $AR_DT['post_attribute_id']; ?>" name="default_sts[]" data_id="<?php echo $AR_DT['post_attribute_id']; ?>" <?php checkRadio("1",$AR_DT['default_sts']); ?> value="1"></td>
                      <td><?php echo ($AR_DT['post_attribute_sts']>0)? "Active":"In-Active"; ?></td>
                      <td><div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle"> Action <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
                          <ul class="dropdown-menu dropdown-default">
						  	<li> <a href="<?php echo generateSeoUrlFranchise("shop","postattribute",array("post_attribute_id"=>_e($AR_DT['post_attribute_id']),"post_id"=>_e($AR_DT['post_id']),"action_request"=>"EDIT")); ?>">Edit</a> </li>					  								
                            <li> <a onClick="return confirm('Are you sure,  want to duplicate this combination?')"  href="<?php echo generateSeoUrlAdmin("shop","postattribute",array("post_attribute_id"=>_e($AR_DT['post_attribute_id']),"post_id"=>_e($AR_DT['post_id']),"action_request"=>"DUPLICATE")); ?>">Duplicate</a> </li>					  								
                            <li> <a onClick="return confirm('Make sure , you want to change this attribute  status?')" href="<?php echo generateSeoUrlFranchise("shop","postattribute",array("post_attribute_id"=>_e($AR_DT['post_attribute_id']),"post_id"=>_e($AR_DT['post_id']),"action_request"=>"STATUS","post_attribute_sts"=>$AR_DT['post_attribute_sts'])); ?>"><?php echo ($AR_DT['post_attribute_sts']>0)? "Active":"In-Active"; ?></a> </li>
							<li> <a onClick="return confirm('Make sure , you want to delete this attribute?')" href="<?php echo generateSeoUrlFranchise("shop","postattribute",array("post_attribute_id"=>_e($AR_DT['post_attribute_id']),"post_id"=>_e($AR_DT['post_id']),"action_request"=>"DELETE")); ?>">Delete</a> </li>
                          </ul>
                        </div></td>
                    </tr>
                    <?php $Ctrl++; endforeach; }else{ ?>
                    <tr>
                      <td colspan="9" class="center text-danger"><i class="ace-icon fa fa-times bigger-110 red"></i> &nbsp; No record found</td>
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
	$(".attribute_default").on('click',function(){
		var post_attribute_id = $(this).attr("data_id");
		var URL_LOAD = "<?php echo generateSeoUrl("json","jsonhandler"); ?>";
		$.getJSON(URL_LOAD,{switch_type:"ATTR_DEFAULT",post_attribute_id:post_attribute_id},function(JsonEval){
			if(JsonEval){
				if(JsonEval.error_sts>0){
					alert("Updated successfully");
					return true;
				}
			}
		})
	});
});
</script>
</body>
</html>
