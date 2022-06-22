<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HtmlToPdf extends MY_Controller {

	function packageinvoice(){
		$segment = $this->uri->uri_to_assoc(2);
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(2);
		$subcription_id = _d($segment['subcription_id']);
	
		$AR_SUB = $model->getSubscription($subcription_id);
	
		$member_id = $AR_SUB['member_id'];
		$AR_MEM = $model->getMember($member_id);
	
		$AR_PACK = $model->getPinType($AR_SUB['type_id']);
	
		$output = "";

		$output .='<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"  class="table">';
		   $output .='<tr>';
              $output .='<td colspan="2" align="left"   ><img src="'.LOGO.'" width="200" alt="'.WEBSITE.'"></td>';
           $output .='</tr>';
           $output .='<tr>';
		   	$output .='<td><strong>&nbsp;&nbsp;Invoice No :-</strong>'.$AR_SUB['order_no'].'</td>';
            $output .='<td><strong>Date :-</strong>'.DisplayDate($AR_SUB['date_from']).'</td>';
          $output .='</tr>';
          $output .='<tr>';
            $output .='<td><strong>&nbsp;&nbsp;Member Name :-</strong></td>';
            $output .='<td >'.$AR_MEM['full_name'].'</td>';
          $output .='</tr>';
          $output .='<tr>';
            $output .='<td valign="top"  ><strong>&nbsp;&nbsp;Member Code :-</strong></td>';
            $output .='<td >'.$AR_MEM['user_id'].'</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Received With thanks from :-</strong></td>';
             $output .='<td >'.getTool($AR_SUB['net_amount'],"NA").' /-</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Package Type :-</strong></td>';
             $output .='<td >'.$AR_PACK['pin_name'].'</td>';
          $output .='</tr>';
             $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Amount Paid :-</strong></td>';
             $output .='<td >'.number_format($AR_SUB['net_amount'],2).'</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Amount Paid in Word :-</strong></td>';
             $output .='<td >'.convert_number($AR_SUB['net_amount']).' Only /-</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td ><strong>&nbsp;&nbsp;Date of Joining :-</strong></td>';
              $output .='<td valign="top">'.$AR_MEM['date_join'].'</td>';
          $output .='</tr>';
          $output .='<tr>';
              $output .='<td ><strong>&nbsp;&nbsp;Distributor Address :-</strong></td>';
              $output .='<td >'.$AR_MEM['current_address']." ".$AR_MEM['state_name']." ".$AR_MEM['city_name']." Pincode : ".$AR_MEM['pin_code'].'</td>';
          $output .='</tr>';
          $output .='<tr>';
             $output .='<td >&nbsp;</td>';
             $output .='<td >&nbsp;</td>';
         $output .='</tr>';
         $output .='</table>';
				
		$this->load->view('pdflib/mpdf');
		$mpdf=new mPDF('c'); 
		#$mpdf->showImageErrors = true;
		$stylesheet = "";
		$stylesheet .= file_get_contents("u-assets/css/bootstrap.min.css");
		$stylesheet .= file_get_contents("u-assets/css/core.css");
		$stylesheet .= file_get_contents("u-assets/css/components.css");
		$stylesheet .= file_get_contents("u-assets/css/pages.css");
		$stylesheet .= file_get_contents("u-assets/css/responsive.css");
		$html = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		exit;
	}
		
}