<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
 
class Pdf_lib{
	
  var $color_theme = '#89a230';
	var $logo_width = 167;
	var $logo_height = 35;
  
  function __construct(){
		$this->CI =& get_instance();
		
		//initialize model
		
		//initialize library
		$this->CI->load->library('dompdf_gen');
		$this->CI->load->library('global_lib');
  }
	
  public function receipt($donation_data){
    //global setting..
    $data = $this->globalConfigSetting(array());
    $data['donation_data'] = $donation_data;
    
    //generate nama file invoice, dan update ke tabel order..
		$filename = date("YmdHisu") . '-' . $donation_data[0]->donation_number . '.pdf';
		
		// generate invoice template..
		$html = $this->CI->load->view('pdf/receipt', $data, true);
    
		// Convert to PDF
		$this->CI->dompdf->load_html($html);
		$this->CI->dompdf->render();
		$pdf = $this->CI->dompdf->output();
		$file_location = "assets/receipt/" . $filename;
		file_put_contents($file_location, $pdf);
		
		return $filename;
  }
  
  private function globalConfigSetting($config){
		//get global data
		$config['color_theme'] = $this->color_theme;
		$config['logo_width'] = $this->logo_width;
		$config['logo_height'] = $this->logo_height;
		$config['global_data'] = $this->CI->global_lib->getGlobalData();
    return $config;
  }
}