<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set("memory_limit", "1024M");

class Dompdf_gen {
	
	public function pdf_create($html, $filename, $paper, $orientation, $attachment, $stream=TRUE) 
	{
        require_once APPPATH."third_party/dompdf/dompdf_config.inc.php";
		require_once APPPATH."third_party/dompdf/dompdf_config.custom.inc.php";
        spl_autoload_register('DOMPDF_autoload');
		
        $pdf = new DOMPDF();
		$pdf->load_html($html);
		$pdf->set_paper($paper, $orientation);
        $pdf->render();
		 
        if ($stream) {
			$pdf->stream($filename.".pdf",array("attachment" => $attachment));
        } 
		else {
			$CI =& get_instance();
			$CI->load->helper('file');
			write_file($filename.".pdf", $pdf->output());
			
		}
    }
	
}