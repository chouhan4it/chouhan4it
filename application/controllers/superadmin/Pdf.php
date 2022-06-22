<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends MY_Controller {


	public function invoice(){	
		$model = new OperationModel();
		$segment = $this->uri->uri_to_assoc(2);
		$order_id = FcrtRplc($segment['order_id']);
		$output = "";
		
		$QR_ORDER = "SELECT ord.*, CONCAT_WS(' ',tm.first_name,tm.last_name) AS full_name, tm.user_id AS user_id , tm.current_address,
					 tm.city_name, tm.state_name, tm.country_name, tm.pin_code, tm.member_mobile,
					 tad.current_address AS order_address, tad.city_name AS ship_city_name, tad.state_name AS ship_state_name, tad.country_code AS 
					 ship_country_code, tad.pin_code AS ship_pin_code, tos.name AS order_state
					 FROM tbl_orders AS ord
					 LEFT JOIN tbl_members AS tm ON tm.member_id=ord.member_id
					 LEFT JOIN tbl_address AS tad ON tad.address_id=ord.address_id
					 LEFT JOIN tbl_order_state AS tos ON ord.id_order_state=tos.id_order_state
					 WHERE ord.order_id>0  AND ord.order_id='".$order_id."'
					 GROUP BY ord.order_id";
		$AR_ORDER = $this->SqlModel->runQuery($QR_ORDER,true);
		$AR_FRAN = $model->getFranchiseeDetail($AR_ORDER['franchisee_id']);
		
		$QR_GST = "SELECT SUM(tod.post_pv*tod.post_qty) AS order_pv, SUM(tod.original_post_price*tod.post_qty) AS  order_mrp,
		   SUM(tod.post_price*tod.post_qty) AS order_rcp,
		   tod.post_tax, tod.tax_age, tod.post_qty, tod.net_amount
		   FROM tbl_order_detail AS tod 
		   WHERE tod.order_id='".$AR_ORDER['order_id']."' 
		   GROUP BY tod.tax_age 
		   ORDER BY tod.order_detail_id ASC"; 
		$RS_GST = $this->SqlModel->runQuery($QR_GST);
		foreach($RS_GST as $AR_GST):
		  $post_tax_gst = $AR_GST['tax_age'];
		  $order_tax_devide_gst = $post_tax_gst/2;
		  $order_rcp_gst = ( $AR_GST['order_rcp']  /  ( ($post_tax_gst/100)+1 ) );								
		  $order_tax_calc_gst = ($order_rcp_gst*$order_tax_devide_gst)/100;
		  $sum_order_rcp_gst +=$order_rcp_gst;
		  $sum_order_tax_calc_gst +=$order_tax_calc_gst;
		endforeach;
		
		$output .='<div class="row">';
          $output .='<div class="col-sm-10 col-sm-offset-1">';
           $output .='<div  class="print_area">';
             $output .='<div class="widget-box transparent">';
               $output .='<div class="widget-body">';
                 $output .='<div class="widget-main padding-24">';
                   $output .='<div class="row">';
                     $output .='<div class="col-md-12">';
                       $output .='<div>';
                         $output .='<table width="100%" border="0">';
                           $output .='<tr><td colspan="2" align="center"><h3 class="green">TAX INVOICE/RETAIL INVOICE</h3></td></tr>';
                           $output .='<tr><td align="left">';
						   $output .='<table width="100%" border="0">
                                    <tr>
                                      <td><strong>'.strtoupper($AR_FRAN['name']).'</strong></td>
                                    </tr>
                                    <tr>
                                      <td>'.wordwrap($AR_FRAN['address'],30,"<br>\n").'</td>
                                    </tr>
                                    
                                    <tr>
                                      <td><strong>GSTIN/UIN:</strong>'.strtoupper($AR_FRAN['gst_no']).'</td>
                                    </tr>
                                    <tr>
                                      <td><strong>E-Mail:</strong>'.($AR_FRAN['email']).'</td>
                                    </tr>
                                    <tr>
                                      <td><strong>Phone:</strong>'.($AR_FRAN['mobile']).'</td>
                                    </tr>
                                  </table>';
								 $output .= '</td>';
                               	$output .='<td align="right">';
								$output .= '<table width="100%" border="0">
                                    <tr>
                                      <td><table width="100%" border="0">
                                          <tr>
                                            <td width="47%"><strong>Invoice No : </strong>'.$AR_ORDER['invoice_number'].'</td>
                                            <td width="53%" rowspan="6"><img src="'.LOGO.'" width="100" height="100"> </td>
                                          </tr>
                                          <tr>
                                            <td><strong>Date  :</strong>'.getDateFormat($AR_ORDER['invoice_date'],"D d M Y").'</td>
                                          </tr>
                                          
                                          <tr>
                                            <td>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td><strong>Suppliers Ref.:</strong></td>
                                          </tr>
                                          <tr>
                                            <td><strong>'.strtoupper($AR_FRAN['franchisee_type']).'</strong></td>
                                          </tr>
                                        </table>';
						   			$output .='</td>';
                           		  $output .='</tr>';
                                    
                           		  $output .='<tr><td align="center"><h5>Remarks (Discount Summary)</h5></td></tr>';
                          		  $output .='<tr>';
                            	    $output .='<td>';
										$output .='<table width="100%" border="0">
                                          <tr>
                                            <td><strong>MRP</strong></td>
                                            <td><strong>NET AMT</strong></td>
                                            <td><strong> DISCOUNT</strong></td>
                                          </tr>
                                        </table>';
									$output .='</td>';
                          		 $output .='</tr>';
                           		 $output .='<tr><td align="center"><strong>Other Reference(s)</strong></td></tr>';
                          		 $output .='</table>';
								$output .='</td>';
                             $output .='</tr>';
                         $output .='<tr><td align="left"><strong>Buyer:</strong></td><td align="center">&nbsp;</td></tr>';
                         $output .='<tr>
                                <td align="left">'.getTool($AR_ORDER['ship_full_name'],$AR_ORDER['full_name']).'</td>
                                <td align="center">&nbsp;</td>
                              </tr>';
                        $output .='<tr>
                                <td align="left">Mobile:'.getTool($AR_ORDER['ship_mobile_number'],$AR_ORDER['member_mobile']).'</td>
                                <td align="center">&nbsp;</td>
                              </tr>';
                        $output .='<tr>
                                <td width="50%" align="left">Email:   <span class="green">'.getTool($AR_ORDER['ship_email_address'],$AR_ORDER['member_email']).'</span></td>
                                <td width="50%" align="center">&nbsp;</td>
                              </tr>';
                        $output .='<tr>
                                <td align="left">Address : '.$AR_ORDER['order_address'].",".$AR_ORDER['ship_land_mark'].",".$AR_ORDER['ship_city_name'].",".$AR_ORDER['ship_state_name'].",".$AR_ORDER['ship_pin_code'].'</td>
                                <td align="center">&nbsp;</td>
                              </tr>';
                        $output .='<tr>
                                <td align="left"><strong>GSTIN : (Unregistered)</strong> </td>
                                <td align="center">&nbsp;</td>
                              </tr>';
                     $output .='</table>';
                  $output .='</div>';
                         
                       $output .='<div class="hr hr8 hr-double hr-dotted"></div>';
                       $output .='<div>';
                          $output .='<table width="100%" border="0">';
                          $output .='<tr><td colspan="2" align="center">';
						  	$output .='<table width="100%"  class="table">';
                                $output .='<thead>
                                      <tr>
                                        <th width="6%" align="left">Sr No</th>
                                        <th width="7%" align="left">Code</th>
                                        <th width="7%" align="left">HSN</th>
                                        <th align="left">Item Detail</th>
                                        <th width="6%" align="left">Pack</th>
                                        <th width="6%" align="left">Batch</th>
                                        <th width="5%" class="center">MRP</th>
                                        <th width="5%" class="center">Price</th>
                                        <th width="4%" class="center">Qty</th>
                                        <th width="9%" class="center">Amount</th>
                                      </tr>
                                    </thead>';
								$output .='<tbody>';
                                      
										$QR_ORD_DT = "SELECT tod.* FROM tbl_order_detail AS tod WHERE tod.order_id='".$AR_ORDER['order_id']."' ORDER BY tod.order_detail_id ASC";
										$RS_ORD_DT = $this->SqlModel->runQuery($QR_ORD_DT);
										$Ctrl=1;
										foreach($RS_ORD_DT as $AR_ORD_DT):
										$AR_POST = $model->getPostDetail($AR_ORD_DT['post_id']);
										
										$order_pv +=($AR_ORD_DT['post_pv']*$AR_ORD_DT['post_qty']);
										$order_mrp +=($AR_ORD_DT['original_post_price']*$AR_ORD_DT['post_qty']);
										$order_tax +=($AR_ORD_DT['post_tax']*$AR_ORD_DT['post_qty']);
										
										$order_qty +=$AR_ORD_DT['post_qty'];
										$order_total +=$AR_ORD_DT['net_amount'];
										
                                 $output .='<tr>
                                        <td align="left">'.$Ctrl.'</td>
                                        <td align="left">'.$AR_POST['post_code'].'</td>
                                        <td align="left">'.$AR_POST['post_hsn'].'</td>
                                        <td align="left">'.setWord($AR_ORD_DT['post_title'],30).'</td>
                                        <td align="left">'.$AR_POST['post_size'].'</td>
                                        <td align="left">'.$AR_ORD_DT['batch_no'].'</td>
                                        <td align="center">'.$AR_ORD_DT['original_post_price'].'</td>
                                        <td align="center">'.$AR_ORD_DT['post_price'].'</td>
                                        <td align="center"'.$AR_ORD_DT['post_qty'].'</td>
                                        <td align="center">'.number_format($AR_ORD_DT['net_amount'],2).'</td>
                                      </tr>';
									$Ctrl++; endforeach;
									$base_amount =  $order_total-$order_tax;
									$vat_age = ($order_tax/$base_amount)*100;
									
                                    $output .='<tr>
                                        <td colspan="7" align="left" class="left"><strong>Amount in words :</strong> &nbsp;&nbsp; <u>'.convert_number($order_total).' only </u> /-</td>
                                        <td align="right" class="center"><strong>Total</strong></td>
                                        <td align="right" class="hidden-480"><strong>'.number_format($order_qty).'</strong></td>
                                        <td align="right"><strong>'.number_format($order_total,2).'</strong><br><small style="font-size:8px;">E. & O.E</small></td>
                                      </tr>';
                                    $output .='<tr>
                                        <td colspan="10" align="left" class="left"><div class="clearfix">&nbsp;</div></td>
                                      </tr>';
                               $output .='</tbody>';
                            $output .='</table></td>';
                          $output .='</tr>';
                        $output .='</table>';
                      $output .='</div>';
					  $output .='<div class="hr hr8 hr-double hr-dotted"></div>';
                        $output .='<div>';
                          $output .='<table width="100%" border="0">';
                            $output .='<tr>
                                <td width="45%" rowspan="2" align="center"><strong>HSN/SAC</strong></td>
                                <td width="13%"><strong>Taxable Value</strong></td>
                                <td colspan="2" align="center"><strong>CGST</strong></td>
                                <td colspan="2" align="center"><strong>SGST</strong></td>
                              </tr>';
                             $output .='<tr>
                                <td>&nbsp;</td>
                                <td width="11%">Rate</td>
                                <td width="10%">Amount</td>
                                <td width="11%">Rate</td>
                                <td width="10%">Amount</td>
                              </tr>';
							  
							  	$QR_ORD_TAX = "SELECT SUM(tod.post_pv*tod.post_qty) AS order_pv, SUM(tod.original_post_price*tod.post_qty) AS  order_mrp  ,
								SUM(tod.post_price*tod.post_qty) AS order_rcp,
								tod.post_tax, tod.tax_age, tod.post_qty, tod.net_amount
								FROM tbl_order_detail AS tod 
								WHERE tod.order_id='".$AR_ORDER['order_id']."' 
								GROUP BY tod.tax_age 
								ORDER BY tod.order_detail_id ASC"; 
							  	$RS_ORD_TAX = $this->SqlModel->runQuery($QR_ORD_TAX);
								foreach($RS_ORD_TAX as $AR_ORD_TAX):
								$post_tax = $AR_ORD_TAX['tax_age'];
								
								$order_tax_devide = $post_tax/2;
								#$tax_formula =  ( $AR_ORD_TAX['order_tax'] / 10 );
								
								$order_rcp = ( $AR_ORD_TAX['order_rcp']  /  ( ($post_tax/100)+1 ) );								
								
								$order_tax_calc = ($order_rcp*$order_tax_devide)/100;
								
								$sum_order_rcp +=$order_rcp;
								$sum_order_tax_calc +=$order_tax_calc;
							  
                            $output .='<tr>
                                <td>&nbsp;</td>
                                <td>'.number_format($order_rcp,2).'</td>
                                <td>'.number_format($order_tax_devide,2).' %</td>
                                <td>'.number_format($order_tax_calc,2).'</td>
                                <td>'.number_format($order_tax_devide,2).' %</td>
                                <td>'.number_format($order_tax_calc,2).'</td>
                              </tr>';

							 	endforeach;
								$gst_order_tax_calc = $sum_order_tax_calc*2;							
							
                           $output .='<tr>
                                <td align="right"><strong>Total</strong> :</td>
                                <td align="left"><strong><?php echo number_format($sum_order_rcp,2); ?></strong></td>
                                <td>&nbsp;</td>
                                <td><strong><?php echo number_format($sum_order_tax_calc,2); ?></strong></td>
                                <td>&nbsp;</td>
                                <td><strong><?php echo number_format($sum_order_tax_calc,2); ?></strong></td>
                              </tr>';
                          $output .='<tr>
                                <td colspan="6">Tax Amount (In words):  <u>'.convert_number(number_format($gst_order_tax_calc,2)).' Only /-</u></td>
                              </tr>';
                         $output .='</table>';
						   $output .='</div>';
						 $output .='</div>';
						$output .='</div>';
					   $output .='<div>';
					  $output .='</div>';
                   $output .='<div class="hr hr8 hr-double hr-dotted"></div>';
                  $output .='<div class="row">
                        <div class="col-sm-12 pull-left"><strong>Terms & Condition</strong>
                          <ul  style="list-style-type:decimal; font-size:8px;">
                            <li>GOODS ONCE SOLD WILL NOT BE TAKEN BACK AFTER 30 DAYS & IF SEAL BROCKEN</li>
                            <li>SUBJECT TO PUNE JURISDICTION ONLY</li>
                          </ul>
                        </div>
                      </div>';
                 $output .='<div class="hr hr8 hr-double hr-dotted"></div>';
                 $output .='<div class="row">
                        <div class="col-sm-12 pull-left"><strong>
                          <table width="100%" border="0">
                            <tr>
                              <td width="50%">.</td>
                              <td width="50%">&nbsp;</td>
                            </tr>
                            <tr>
                              <td><strong>Declaration:</strong></td>
                              <td align="right">For <strong>'.strtoupper($AR_FRAN['name']).'</td>
                            </tr>
                            <tr>
                              <td rowspan="2">We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td align="right">Authorised Signatory</td>
                            </tr>
                          </table>
                        </div>
                      </div>';
                   $output .='<div class="hr hr8 hr-double hr-dotted"></div>';
                   $output .='<div class="row">
                        <div class="col-sm-9">This is a Computer Generated Invoice</div>
                        <div class="col-sm-3 pull-right">&nbsp;</div>
                      </div>';
                 $output .='</div>';
                $output .='</div>';
               $output .='</div>';
             $output .='</div>';
            $output .='</div>';
          $output .='</div>';
		
		echo $output; exit;
		ob_end_clean();
		$this->load->view('pdflib/mpdf');
		$mpdf=new mPDF('c'); 
		#$mpdf->showImageErrors = true;
		$stylesheet  = '';
		$stylesheet .= file_get_contents(BASE_PATH."assets/css/bootstrap.min.css");
		$stylesheet .= file_get_contents(BASE_PATH."assets/font-awesome/4.5.0/css/font-awesome.min.css");
		$stylesheet .= file_get_contents(BASE_PATH."assets/css/fonts.googleapis.com.css");
		
		$stylesheet .= file_get_contents(BASE_PATH."assets/css/ace.min.css");
		$stylesheet .= file_get_contents(BASE_PATH."assets/css/ace-skins.min.css");
		$stylesheet .= file_get_contents(BASE_PATH."assets/css/ace-rtl.min.css");
		
		$html = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
		$mpdf->WriteHTML($stylesheet,1);

		
		
		$mpdf->WriteHTML($output);
		$mpdf->Output();
		exit;
	}
	
	
}
