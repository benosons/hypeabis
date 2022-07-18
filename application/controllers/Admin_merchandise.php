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

class Admin_merchandise extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $pic_width = 800;
	var $pic_height = 800;
  var $pic_thumb_width = 250;
	var $pic_thumb_height = 250;
  var $module_name = 'admin_merchandise';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_merchandise');
    
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    if(strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1'){
      redirect('admin_dashboard/index');
    }
  }
	
  public function index(){
    //ambil total row untuk keperluan config pagination dan jumlah data di depan..
		$data['total_row'] = $this->mdl_merchandise->getAllMerchandiseCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['merchandise'] = $this->mdl_merchandise->getAllMerchandiseLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all module
		$content = $this->load->view('admin/merchandise/all', $data, true);
		$this->render($content);
	}
	
	public function add(){
    $data = array();
    
		//load view add admin ...
		$content = $this->load->view('admin/merchandise/add', $data, true);
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('merch_name','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('merch_desc','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('merch_quota','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('merch_point','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('merch_publish','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_merchandise/add');
		}
		else{
      // --- START uploading picture and generate thumbnail
			$merch_pic = '';
			$merch_pic_thumb = '';
			if (!empty($_FILES['merch_pic']['name'])){
				$config = $this->merchandisePicUploadConfig($_FILES['merch_pic']['name']);
				$this->upload->initialize($config);
				if ($this->upload->do_upload('merch_pic')){
					$upload_data = array('upload_data' => $this->upload->data());
					$merch_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
          $merch_pic_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'],$this->pic_thumb_width,$this->pic_thumb_height);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_merchandise/add');
				}
			}
      else{
        $message = $this->global_lib->generateMessage("You must upload a file for merch picture", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('admin_merchandise/add');
      }
      // --- END uploading picture and generate thumbnail
      
			//insert data ke database..
			$insert_data = array(
        "merch_name" => $this->input->post('merch_name'),
				"merch_desc" => $this->input->post('merch_desc'),
				"merch_quota" => $this->input->post('merch_quota'),
				"merch_point" => $this->input->post('merch_point'),
				"merch_publish" => $this->input->post('merch_publish'),
				"merch_pic" => $merch_pic,
				"merch_pic_thumb" => $merch_pic_thumb
			);
			$this->mdl_merchandise->insertMerchandise($insert_data);
      
      $message =  $this->global_lib->generateMessage("New merchandise has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_merchandise/index');
		}
	}
	
	public function edit($id_merchandise=''){
		//ambil data merchandise yang akan diedit.
		$data['merchandise'] = $this->mdl_merchandise->getMerchandiseByID($id_merchandise);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['merchandise'])) || count($data['merchandise']) < 1){
			redirect('admin_merchandise/index');
		}
    
		//load view edit admin ...
		$content = $this->load->view('admin/merchandise/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_merchandise=''){
		//ambil data merchandise yang akan diedit.
		$data['merchandise'] = $this->mdl_merchandise->getMerchandiseByID($id_merchandise);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['merchandise'])) || count($data['merchandise']) < 1){
			redirect('admin_merchandise/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('merch_name','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('merch_desc','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('merch_quota','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('merch_point','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('merch_publish','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_merchandise/edit/' . $id_merchandise);
		}
		else{
			// --- START uploading picture and generate thumbnail
			$merch_pic = '';
			$merch_pic_thumb = '';
			if (!empty($_FILES['merch_pic']['name'])){
				$config = $this->merchandisePicUploadConfig($_FILES['merch_pic']['name']);
				$this->upload->initialize($config);
				if ($this->upload->do_upload('merch_pic')){
					$upload_data = array('upload_data' => $this->upload->data());
					$merch_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
          $merch_pic_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'],$this->pic_thumb_width,$this->pic_thumb_height);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_merchandise/add');
				}
			}
      
			// update data admin ke database..
			$update_data = array(
				"merch_name" => $this->input->post('merch_name'),
				"merch_desc" => $this->input->post('merch_desc'),
				"merch_quota" => $this->input->post('merch_quota'),
				"merch_point" => $this->input->post('merch_point'),
				"merch_publish" => $this->input->post('merch_publish')
			);
      if(strlen(trim($merch_pic)) > 0){
				$update_data['merch_pic'] = $merch_pic;
				$update_data['merch_pic_thumb'] = $merch_pic_thumb;
			}
			$this->mdl_merchandise->updateMerchandise($update_data, $id_merchandise);
      
      $message =  $this->global_lib->generateMessage("Merchandise has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_merchandise/edit/' . $id_merchandise);
		}
	}
  
	public function delete($id_merchandise=''){
		//ambil data merchandise yang akan diedit.
		$data = $this->mdl_merchandise->getMerchandiseByID($id_merchandise);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_merchandise/index');
		}
    
    $this->mdl_merchandise->updateMerchandise(array('deleted' => 1), $id_merchandise);
		
    $message =  $this->global_lib->generateMessage("Merchandise has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_merchandise/index');
	}
  
	public function deletePic($id_merchandise=''){
		//ambil data admin yang akan diedit.
		$data = $this->mdl_merchandise->getMerchandiseByID($id_merchandise);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_merchandise/index');
		}
		
		//jika tidak ada redirect ke halaman edit
		if((! isset($data[0]->merch_pic)) || strlen(trim($data[0]->merch_pic)) == 0){
			redirect('admin_merchandise/edit/' . $id_merchandise);
		}
		else{
			$update_data = array(
				'merch_pic' => "",
				'merch_pic_thumb' => "",
			);
			$this->mdl_merchandise->updateMerchandise($update_data, $id_merchandise);
			
			//hapus file.
			@unlink('assets/merchandise/' . $data[0]->merchandise_pic2);
			@unlink('assets/merchandise/thumb/' . $data[0]->merchandise_pic2_thumb);
			redirect("admin_merchandise/edit/" . $id_merchandise);
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
  
  private function merchandisePicUploadConfig($filename=''){
		$config['upload_path'] = './assets/merchandise/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '12000';
		$config['max_width']  = '8000';
		$config['max_height']  = '8000';
		if(strlen(trim($filename)) > 0){
			$config['file_name']  = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
		}
		return $config;
	}
	
  private function paginationConfig($total_rows){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_merchandise/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
}
