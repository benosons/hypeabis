<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
*
* @author 		: Hengky Mulyono <hengkymulyono301@gmail.com>
* @copyright	: Binary Project 2017
* @copyright	: mail@binary-project.com
* @version		: Release: v5.0
* @link			  : www.binary-project.com
* @contact		: 0822 3709 9004
*
*/

class Dev_module extends CI_Controller {
	
  var $js = array();
  var $css = array();
	var $module_index = 0;
	var $module_list = array();
  var $module_icon_width = 128;
	var $module_icon_height = 128;
	
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_module');
		
		//construct script..
		if($this->session->userdata('dev_logged_in') !== true){
			redirect("devarea/index");
		}
  }
	
	public function index(){
		//ambil julah total module di database..
		$data['total_row'] = $this->mdl_module->getAllModuleCount();
	
		//ambil data semua module utama / parent..
		$data['main_module'] = $this->mdl_module->getAllModuleParent();

		//ambil semua module child dan urutkan berdasarkan levelnya.
		foreach($data['main_module'] as $pg){
			$this->module_list[$this->module_index] = $pg;
			$this->module_index++;
			
			//cek apakah punya child.
			if($this->mdl_module->hasChild($pg->id_module)){
				$this->generateModuleChild($pg->id_module);
			}
		}
		
		$x = 0;
		foreach($this->module_list as $pl){
			// cek apakah module mempunyai parent.
			$modulecheck = $this->mdl_module->getModuleByID($pl->id_module);
			if(($modulecheck[0]->module_parent != null ? true : false)){
				$indentation = $this->generateIndentationStr($pl->id_module);
				$this->module_list[$x]->module_name = $indentation."- ".$this->module_list[$x]->module_name;
			}
			$x++;
		}
		$data['module'] = $this->module_list;
		
		//load view all module
		$content = $this->load->view('dev/module/all', $data, true);
		
		$this->render($content);
	}
	
	public function add(){
		$data = array();
	
		//load view add module ...
		$content = $this->load->view('dev/module/add', $data, true);
		
		$this->render($content);
	}
	
	public function addSub($id_module = ''){
		//ambil data module bedasarkan id..
		$data['module'] = $this->mdl_module->getModuleByID($id_module);
		
		//jika tidak ada data, atau tidak updatable, redirect ke index.
		if((! is_array($data['module'])) || count($data['module']) < 1){
			redirect('dev_module/index');
		}
		//cek status updatable dan superadmin..
		if($data['module'][0]->updatable == 0){
			redirect('dev_module/index');
		}
		
		//load view add module ...
		$content = $this->load->view('dev/module/add_sub', $data, true);
		
    $this->render($content);
	}
	
	public function saveAdd(){
		//validasi input
		$this->form_validation->set_rules('module_name','', 'htmlentities|strip_tags|required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('module_desc','', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
		$this->form_validation->set_rules('module_icon','', 'htmlentities|strip_tags|required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('module_parent','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('module_order','', 'htmlentities|strip_tags|required|trim|xss_clean|integer');
    $this->form_validation->set_rules('module_redirect','', 'htmlentities|strip_tags|required|trim|max_length[200]|xss_clean');
    $this->form_validation->set_rules('module_status','', 'htmlentities|strip_tags|required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('file_icon','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			if($this->input->post('module_parent') > 0){
				redirect('dev_module/addSub/'.$this->input->post('module_parent'));
			}
			else{
				redirect('dev_module/add');
			}
		}
		else{
			//upload module header
			$module_icon_big = '';
			if (!empty($_FILES['file_icon']['name'])){
				$config = $this->moduleIconUploadConfig($_FILES['file_icon']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_icon')){
					$upload_data = array('upload_data' => $this->upload->data());
					$module_icon_big = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->module_icon_width, $this->module_icon_height, true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					if($this->input->post('module_parent') > 0){
            redirect('dev_module/addSub/'.$this->input->post('module_parent'));
          }
          else{
            redirect('dev_module/add');
          }
				}
			}
      else{
        $message =  $this->global_lib->generateMessage("You neede to choose file for module icon.", "danger");
        $this->session->set_flashdata('message', $message);
        if($this->input->post('module_parent') > 0){
          redirect('dev_module/addSub/'.$this->input->post('module_parent'));
        }
        else{
          redirect('dev_module/add');
        }
      }
    
			//insert data module yang baru ke database..
			$insert_data = array(
				'module_name' => $this->input->post('module_name'),
				'module_desc' => $this->input->post('module_desc'),
				'module_icon' => $this->input->post('module_icon'),
				'module_parent' => $this->input->post('module_parent'),
				'module_redirect' => strtolower($this->input->post('module_redirect')),
				'module_status' => $this->input->post('module_status'),
				'module_order' => $this->input->post('module_order'),
				'module_icon_big' => $module_icon_big
			);
			$this->mdl_module->insertModule($insert_data);
			
      $message =  $this->global_lib->generateMessage("New module has been added.", "info");
      $this->session->set_flashdata('message', $message);
      redirect('dev_module/index');
		}
	}
	
	public function edit($id_module=''){
		//ambil data module bedasarkan id..
		$data['module'] = $this->mdl_module->getModuleByID($id_module);
		
		//jika tidak ada data, atau tidak updatable, redirect ke index.
		if((! is_array($data['module'])) || count($data['module']) < 1){
			redirect('dev_module/index');
		}
		
    //cek status updatable dan superadmin..
		if($data['module'][0]->updatable == 0){
			redirect('dev_module/index');
		}
		
		//load view add module
		$content = $this->load->view('dev/module/edit', $data, true);
		
    $this->render($content);
	}
	
	public function saveEdit($id_module=''){
		//ambil data module bedasarkan id..
		$module_data = $this->mdl_module->getModuleByID($id_module);
		
		//jika tidak ada data, atau tidak updatable, redirect ke index.
		if((! is_array($module_data)) || count($module_data) < 1){
			redirect('dev_module/index');
		}
		//cek status updatable dan superadmin..
		if($module_data[0]->updatable == 0){
			redirect('dev_module/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('module_name','', 'htmlentities|strip_tags|required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('module_desc','', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
		$this->form_validation->set_rules('module_icon','', 'htmlentities|strip_tags|required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('module_order','', 'htmlentities|strip_tags|required|trim|xss_clean|integer');
    $this->form_validation->set_rules('module_redirect','', 'htmlentities|strip_tags|required|trim|max_length[200]|xss_clean');
    $this->form_validation->set_rules('module_status','', 'htmlentities|strip_tags|required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('file_icon','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
			$message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('dev_module/edit/' . $id_module);
		}
		else{
      //upload module header
			$module_icon_big = '';
			if (!empty($_FILES['file_icon']['name'])){
				$config = $this->moduleIconUploadConfig($_FILES['file_icon']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_icon')){
					$upload_data = array('upload_data' => $this->upload->data());
					$module_icon_big = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->module_icon_width, $this->module_icon_height, true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('dev_module/edit/' . $id_module);
				}
			}
      
			//update data module yang ke database..
			$update_data = array(
				'module_name' => $this->input->post('module_name'),
				'module_desc' => $this->input->post('module_desc'),
				'module_icon' => $this->input->post('module_icon'),
				'module_redirect' => strtolower($this->input->post('module_redirect')),
				'module_status' => $this->input->post('module_status'),
				'module_order' => $this->input->post('module_order')
			);
      if(strlen(trim($module_icon_big)) > 0){
				$update_data['module_icon_big'] = $module_icon_big;
			}
			$this->mdl_module->updateModule($id_module, $update_data);
			
			$message =  $this->global_lib->generateMessage("Module has been updated.", "info");
      $this->session->set_flashdata('message', $message);
      redirect('dev_module/index');
		}
	}
	
	public function delete($id_module=''){
		//ambil data module bedasarkan id..
		$module_data = $this->mdl_module->getModuleByID($id_module);
		
		//jika tidak ada data, atau tidak updatable, redirect ke index.
		if((! is_array($module_data)) || count($module_data) < 1){
			redirect('dev_module/index');
		}
		//cek status updatable dan superadmin..
		if($module_data[0]->deletable == 0){
			redirect('dev_module/index');
		}
		
		$this->deleteChild($id_module);
		
		$message = "<div class='alert alert-info'>Module has been deleted.</div>";
		$this->session->set_flashdata('message', $message);
		redirect('dev_module/index');
	}
	
	private function deleteChild($id_module=''){
		$this->mdl_module->deleteModule($id_module);
		$module_child = $this->mdl_module->getModuleChild($id_module);
		foreach($module_child as $item){
			$this->deleteChild($item->id_module);
		}
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
  
	private function generateModuleChild($id_module = ""){
		$data['module_child'] = $this->mdl_module->getModuleChild($id_module);
		foreach($data['module_child'] as $pg):
			$this->module_list[$this->module_index] = $pg;
			$this->module_index++;
			
			// cek apakah punya child.
			if($this->mdl_module->hasChild($pg->id_module)){
				$this->generateModuleChild($pg->id_module);
			}
		endforeach;
	}
	
	private function generateIndentationStr($id_module = ""){
		$flag = true;
		$count = 0;
		$id = $id_module;
		$str = "";
		
		while($flag){
			$count++;
			//ambil data detail module lalu cek module parent nya.
			$module = $this->mdl_module->getModuleByID($id);
			if(isset($module[0]->module_parent) && $module[0]->module_parent != null){
				$id = $module[0]->module_parent;
			}
			else{
				$flag = false;
			}
		}
		for($x = 1; $x < ($count - 1); $x++){
			$str .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		return $str;
	}
	
  private function moduleIconUploadConfig($filename=''){
		$config['upload_path'] = './assets/icon/';
		$config['allowed_types'] = 'jpg|png|';
		$config['max_size'] = '12000';
		$config['max_width']  = '8000';
		$config['max_height']  = '8000';
		if(strlen(trim($filename)) > 0){
			$config['file_name']  = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
		}
		return $config;
	}
}