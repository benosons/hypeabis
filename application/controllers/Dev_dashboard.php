<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
*
* @author 		: Hengky Mulyono <hengkymulyono301@gmail.com>
* @copyright	: Binari - 2020
* @copyright	: mail@binary-project.com
* @version		: Release: v1
* @link			  : www.binary-project.com
* @contact		: 0822 3709 9004
*
*/

class Dev_dashboard extends CI_Controller {
	
  var $js = array();
  var $css = array();
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		
		//construct script..
		if($this->session->userdata('dev_logged_in') !== true){
			redirect("devarea/index");
		}
  }
	
	public function index(){
		//load dashboard view
		$content = $this->load->view('dev/dashboard/dashboard', null, true);
		$this->render($content);
	}
  
  public function terms(){
    //load terms & conditions view
		$content = $this->load->view('dev/dashboard/terms', null, true);
		$this->render($content);
  }
  
  private function render($page = null){
    if(isset($page) && $page !== null){
      //load page view
      $data['content'] = $page;
      
      //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
      $data['css_files'] = $this->css;
      $data['js_files'] = $this->js;
      
      //load global data
      $data['global_data'] = $this->global_lib->getGlobalData();
      
      //load view template
      $this->load->view('/dev/template', $data);
    }
    else{
      redirect('page/index');
    }
  }
	
}