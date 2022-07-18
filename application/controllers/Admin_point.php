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

class Admin_point extends CI_Controller {
	
  var $js = array();
  var $css = array();
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
    $this->load->model('mdl_point');
		
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
  }
	
	public function index(){
    //ambil global data
		$data['points'] = $this->mdl_point->getAllPoint();
		
		//load view.
    $content = $this->load->view('admin/point/detail', $data, true);
		$this->render($content);
	}
  
  public function updatePoint(){
    //ambil global data
		$data['points'] = $this->mdl_point->getAllPoint();
    
    foreach($data['points'] as $point){
      $this->form_validation->set_rules('point_' . $point->id_point,'', 'htmlentities|strip_tags|trim|required|integer|xss_clean');
      if(isset($point->trigger_str_min) && strlen(trim($point->trigger_str_min)) > 0){
        $this->form_validation->set_rules('point_min_' . $point->id_point,'', 'htmlentities|strip_tags|trim|required|integer|xss_clean');
      }
    }
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_point/index');
		}
		else{
      foreach($data['points'] as $point){
        $update_data = array();
        $update_data['point'] = $this->input->post('point_' . $point->id_point);
        if(isset($point->trigger_str_min) && strlen(trim($point->trigger_str_min)) > 0){
          $update_data['point_min'] = $this->input->post('point_min_' . $point->id_point);
        }
        $this->mdl_point->updatePoint($update_data, $point->id_point);
      }
      
      $message =  $this->global_lib->generateMessage("Sistem poin berhasil diupdate.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_point/index');
		}
	}
  
  private function render($page = null){
    if(isset($page) && $page !== null){
      //load page view
      $data['content'] = $page;
      
      //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
      $data['css_files'] = $this->css;
      $data['js_files'] = $this->js;
      
      //load module global data
      $data['global_data'] = $this->global_lib->getGlobalData();
      $data['modules'] = $this->global_lib->generateAdminModule();
      
      //load view template
      $this->load->view('/admin/template', $data);
    }
    else{
      redirect('page/index');
    }
  }
  
}