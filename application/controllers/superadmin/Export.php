<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends MY_Controller {

	public function excel(){	
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$QR_SELECT = ImportQuery();
			$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		$output .='<table>';
		$output .="<tr>";
			for ($i = 0; $i < count($columns_total); $i++) {
				$heading = $columns_total[$i];
				$output .="<td>".$heading."</td>";
			}
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
		$output .="<tr>";
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .="<td>".$row[$columns_total[$i]]."</td>";
			}
		$output .="</tr>";
		endforeach;
		$output .='</table>';
		ob_end_clean();
		
		$FileName= strtotime(getLocalTime())."_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	public function pdf(){	
		$segment = $this->uri->uri_to_assoc(2);
		$output = "";
		$QR_SELECT = ImportQuery();
		$sql = $this->db->query($QR_SELECT);
		$columns_total = $sql->list_fields();
		$output .='<table align="center" width="100%" border="1" class="cmntext tableclass" style="border-collapse:collapse;">';
		$output .="<tr>";
			for ($i = 0; $i < count($columns_total); $i++) {
				$heading = $columns_total[$i];
				$output .="<td>".$heading."</td>";
			}
		$output .="</tr>";
		$fetchRows = $sql->result_array();
		foreach($fetchRows as $row):
		$output .="<tr>";
			for ($i = 0; $i < count($columns_total); $i++) {
				$output .="<td>".$row[$columns_total[$i]]."</td>";
			}
		$output .="</tr>";
		endforeach;
		$output .='</table>';
		ob_end_clean();
		$this->load->view('pdflib/mpdf');
		$mpdf=new mPDF('c'); 
		#$mpdf->showImageErrors = true;
		$css_path = BASE_PATH."public/css/webcustom.css";
		$stylesheet = file_get_contents($css_path);
		$html = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($output);
		$mpdf->Output();
		exit;
	}
	
	
	
}
