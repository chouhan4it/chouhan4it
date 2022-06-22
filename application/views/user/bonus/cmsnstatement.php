<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$Page = $_REQUEST[page]; if($Page == "" or $Page <=0){$Page=1;}
$member_id = $this->session->userdata('mem_id');
$AR_MEM = $model->getMember($member_id);
$process_id = FCrtRplc($_REQUEST['process_id']);

$QR_INCOME = "SELECT tcms.*, tr.rank_name, MONTHNAME(DATE(prcss.start_date)) AS month_name, YEAR(DATE(prcss.start_date)) AS year  
			  FROM tbl_cmsn_mstr_sum AS tcms
			  LEFT JOIN tbl_rank AS tr ON tr.rank_id=tcms.rank_id 
			  LEFT JOIN tbl_process AS prcss ON prcss.process_id=tcms.process_id 
			  WHERE tcms.member_id='$member_id' AND tcms.process_id='$process_id'";
$AR_INCOME = $this->SqlModel->runQuery($QR_INCOME,true);

$QR_DIFFPV = "SELECT SUM(net_bv) AS net_bv FROM tbl_cmsn_trns WHERE member_id='$member_id' AND process_id='$AR_INCOME[process_id]'";
$AR_DIFFPV = $this->SqlModel->runQuery($QR_DIFFPV,true);

$QR_SENIOR = "SELECT total_bv, net_total_bv FROM tbl_cmsn_mstr_senior WHERE member_id='$member_id' AND process_id='$AR_INCOME[process_id]'";
$AR_SENIOR = $this->SqlModel->runQuery($QR_SENIOR,true);

$QR_LEADER = "SELECT net_cmsn FROM tbl_cmsn_leadership WHERE member_id='$member_id' AND process_id='$AR_INCOME[process_id]'";
$AR_LEADER = $this->SqlModel->runQuery($QR_LEADER,true);

$QR_FORCAR = "SELECT (net_point*calc_point) AS car_budget FROM tbl_budget_mstr WHERE member_id='$member_id' AND process_id='$AR_INCOME[process_id]'";
$AR_FORCAR = $this->SqlModel->runQuery($QR_FORCAR,true);

$QR_CMSNOTH = "SELECT total_amount,trns_remark FROM tbl_cmsn_other WHERE member_id='$member_id' AND process_id='$AR_INCOME[process_id]'";
$AR_CMSNOTH = $this->SqlModel->runQuery($QR_CMSNOTH,true);
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
<?php $this->load->view(MEMBER_FOLDER.'/header'); ?>
<!-- End Navigation Bar-->
<div class="wrapper">
	<div class="container">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">COMMISSION STATEMENT</h4>
            <p class="text-muted page-title-alt">Your Monthly Commission Statement</p>
        </div>
    </div>
    <!-- end row -->
	<div class="row">
		<div class="col-lg-12">
	        <div class="card-box">
    		    <div class="clearfix">&nbsp;</div>
        			<div class="row">
        				<div class="col-lg-12">
        					<div class="row">
						        <div class="col-md-12">
                                <table width="100%" border="1" class="table table-border">
                                	<tr>
                                    	<td colspan="5">
                                        <table width="100%" border="0">
                                            <tr>
                                                <td height="35"><strong>User Id : </strong></td>
                                                <td><?php echo $AR_MEM['user_id']; ?></td>
                                                <td><strong>User Name : </strong></td>
                                                <td><?php echo $AR_MEM['full_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <td height="35" width="25%"><strong>PAN No : </strong></td>
                                                <td width="25%"><?php echo $AR_MEM['pan_no']; ?></td>
                                                <td width="25%"><strong>Month : </strong></td>
                                                <td width="25%"><?php echo $AR_INCOME['month_name'].' '.$AR_INCOME['year']; ?></td>
                                            </tr>
                                            <tr>
                                                <td height="35"><strong>Qualified Rank : </strong> </td>
                                                <td colspan="3"><?php echo $AR_INCOME['rank_name']; ?></td>
                                            </tr>
                                        </table>
										</td>
                                    </tr>    	                                         
                                    <tr>
                                        <td width="35%"><strong>DETAILS</strong></td>
                                        <td width="25%"><strong>Remarks</strong></td>
                                        <td width="5%" align="right"><strong>(+/-)</strong></td>
                                        <td width="17%"><strong>IN RUPEES</strong></td>
                                        <td width="18%"><strong>CARRIED FORWARD</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Cadre Differential Income</td>
                                        <td>Upto Team Manager @ 24%</td>
                                        <td align="right">(+)</td>
                                        <td><?php echo round($AR_DIFFPV['net_bv'],2); ?></td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Sr. Cadre Additional Bonus</td>
                                        <td><?php echo ($AR_SENIOR['total_bv']>0)? "Qualified Asst. DD":"Not Qualified"; ?></td>
                                        <td align="right">(+)</td>
                                        <td><?php echo round($AR_SENIOR['total_bv'],2); ?></td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Leadership Bonus</td>
                                        <td><?php echo ($AR_LEADER['net_cmsn']>0)? "Qualified":"Not Qualified"; ?></td>
                                        <td align="right">(+)</td>
                                        <td><?php echo round($AR_LEADER['net_cmsn'],2); ?></td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Car Budget</td>
                                        <td><?php echo ($AR_FORCAR['car_budget']>0)? "Based On CB Points Earned":"Not Qualified"; ?></td>
                                        <td align="right">(+)</td>
                                        <td><?php echo round($AR_FORCAR['car_budget'],2); ?></td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>House Budget</td>
                                        <td>Not Qualified</td>
                                        <td align="right">(+)</td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Other Taxable Amount (If Any)</td>
                                        <td><?php echo setWord($AR_CMSNOTH['trns_remark'],50); ?></td>
                                        <td align="right">(+)</td>
                                        <td><?php echo round($AR_CMSNOTH['total_amount'],2); ?></td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Total Gross Income</td>
                                        <td>Gross</td>
                                        <td align="right">=</td>
                                        <td>
										<?php 
										$fldiGross = ($AR_DIFFPV['net_bv']+$AR_SENIOR['total_bv']+$AR_LEADER['net_cmsn']+$AR_FORCAR['car_budget']+$AR_CMSNOTH['total_amount']);
										echo round($fldiGross,2); 
										?>
                                        </td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>TAX Deducted (TDS)</td>
                                        <td>As Per Govt. Slab</td>
                                        <td align="right">(-)</td>
                                        <td>
										<?php 
										//echo round($AR_INCOME['tds'],2);
										$fldiTds = round(($fldiGross*5/100),2);
										echo $fldiTds;
										$fldiNet = ($fldiGross-$fldiTds);
										?>
                                        </td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Processing @ 3%</td>
                                        <td>Charges</td>
                                        <td align="right">(-)</td>
                                        <td>
										<?php 
										//echo round($AR_INCOME['processing'],2);
										$fldiProcess = round(($fldiGross*3/100),2);
                                        echo $fldiProcess;
										$fldiNet -= $fldiProcess;
                                        ?>
                                        </td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Charity @ 3%</td>
                                        <td>Social</td>
                                        <td align="right">(-)</td>
                                        <td>
										<?php 
										//echo round($AR_INCOME['charity_charge'],2);
										$fldiCharity = round(($fldiGross*3/100),2);
										echo $fldiCharity;
										$fldiNet -= $fldiCharity;
										?>
                                        </td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Other Non Taxable Amount/Balance Unpaid Payable</td>
                                        <td>Less Than Rs.100</td>
                                        <td align="right">(+)</td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Less Transfer to Leadership Bonus</td>
                                        <td>
                                        <?php
										$fldiRankId = $AR_INCOME['rank_id'];
										switch($fldiRankId){
											case 7:
											case 8:
												$fldvText = "Accumulated For Q'trly Payment";
											break;
											case 9:
											case 10:
												$fldvText = "Accumulated For H'yrly Payment";
											break;
											case 11:
											case 12:
											case 13:
											case 14:
											case 15:
												$fldvText = "Accumulated For Annual Payment";
											break;
											default:
												$fldvText = "Not Applicable";
											break;
										}
										echo $fldvText;
										?>
                                        </td>
                                        <td align="right">(-)</td>
                                        <td>
										<?php 
										$fldiLeader = round(($AR_LEADER['net_cmsn']-($AR_LEADER['net_cmsn']*11/100)),2);
										echo $fldiLeader;
										$fldiNet -= $fldiLeader;
										?>
                                        </td>
                                        <td><?php echo $fldiLeader; ?></td>
                                    </tr>                                    
                                    <tr>
                                        <td>Less Transfer to <?php echo WEBSITE; ?> Car Budget</td>
                                        <td>Accumulated For Car Purchase</td>
                                        <td align="right">(-)</td>
                                        <td>
										<?php
										$fldiCar = round(($AR_FORCAR['car_budget']-($AR_FORCAR['car_budget']*11/100)),2); 
										echo $fldiCar;
										$fldiNet -= $fldiCar;
										?>
                                        </td>
                                        <td><?php echo round(($AR_FORCAR['car_budget']-($AR_FORCAR['car_budget']*11/100)),2); ?></td>
                                    </tr>                                    
                                    <tr>
                                        <td>Less Transfer to <?php echo WEBSITE; ?> House Budget</td>
                                        <td>Accumulated For House Purchase</td>
                                        <td align="right">(-)</td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>                                    
                                    <tr>
                                        <td>Net Payment after Deduction/Transfer</td>
                                        <td>Net Payable</td>
                                        <td align="right">=</td>
                                        <td>
										<?php 
										//echo round($AR_INCOME['net_cmsn'],2); 
										echo round($fldiNet,2);
										?>
                                        </td>
                                        <td><?php echo ($fldiLeader+$fldiCar); ?> (Net Accumulated)</td>
                                    </tr>                                    
                                </table>
                                </div>
                            </div>
                        </div>
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
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/moment.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
</html>