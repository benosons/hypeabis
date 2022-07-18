<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
 
class Log_lib{
	
  function __construct(){
		//load CI instance..
		$this->CI = & get_instance();
		
		//load model
		$this->CI->load->model('mdl_log');
		
		//construct script..
		date_default_timezone_set("Asia/Jakarta");
		
		//initialize session and language library..
  }
	
	public function insertLogAdmin($modul = null, $log_type = null, $log = null, $log_data = null, $old_data = null){
		$insertData = array(
			'id_admin' => $this->CI->session->userdata('id_admin'),
			'modul' => $modul,
			'log_type' => $log_type,
			'log' => $log,
			'log_data' => $log_data,
			'old_data' => $old_data,
			'log_date' => date("Y-m-d H:i:s"),
		);
    $this->CI->mdl_log->InsertLogAdmin($insertData);
  }
  
}