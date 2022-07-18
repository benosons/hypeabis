<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comingsoon extends CI_Controller {
  
  public function __construct(){
    parent::__construct();
  }
  
	public function index(){
    //load module global data
    $data['global_data'] = $this->global_lib->getGlobalData();
    
    $this->load->view('frontend/comingsoon', $data);
	}
}