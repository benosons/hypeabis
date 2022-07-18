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

class Admin_level extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_level';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_level');
    
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    if(strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1'){
      redirect('admin_dashboard/index');
    }
  }
	
  public function index(){
    //ambil level
		$data['level'] = $this->mdl_level->getAllLevel();
		
		//load view all module
		$content = $this->load->view('admin/level/all', $data, true);
		$this->render($content);
	}
	
	public function add(){
    $data = array();
    
		//load view add admin ...
		$content = $this->load->view('admin/level/add', $data, true);
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('level_name','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('level_point','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('bg_color','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('text_color','', 'htmlentities|strip_tags|trim|xss_clean|required');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_level/add');
		}
		else{
			//insert data ke database..
			$insert_data = array(
        "level_name" => $this->input->post('level_name'),
        "level_point" => $this->input->post('level_point'),
        "bg_color" => $this->input->post('bg_color'),
        "text_color" => $this->input->post('text_color'),
			);
			$this->mdl_level->insertLevel($insert_data);
      
      $message =  $this->global_lib->generateMessage("New level has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_level/index');
		}
	}
	
	public function edit($id_level=''){
		//ambil data level yang akan diedit.
		$data['level'] = $this->mdl_level->getLevelByID($id_level);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['level'])) || count($data['level']) < 1){
			redirect('admin_level/index');
		}
    
		//load view edit admin ...
		$content = $this->load->view('admin/level/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_level=''){
		//ambil data level yang akan diedit.
		$data['level'] = $this->mdl_level->getLevelByID($id_level);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['level'])) || count($data['level']) < 1){
			redirect('admin_level/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('level_name','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('level_point','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('bg_color','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('text_color','', 'htmlentities|strip_tags|trim|xss_clean|required');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_level/edit/' . $id_level);
		}
		else{
			// update data admin ke database..
			$update_data = array(
				"level_name" => $this->input->post('level_name'),
        "level_point" => $this->input->post('level_point'),
        "bg_color" => $this->input->post('bg_color'),
        "text_color" => $this->input->post('text_color')
			);
			$this->mdl_level->updateLevel($update_data, $id_level);
      
      $message =  $this->global_lib->generateMessage("Level has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_level/edit/' . $id_level);
		}
	}
  
	public function delete($id_level=''){
		//ambil data level yang akan diedit.
		$data = $this->mdl_level->getLevelByID($id_level);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_level/index');
		}
    
    $this->mdl_level->deleteLevel($id_level);
		
    $message =  $this->global_lib->generateMessage("Level has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_level/index');
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