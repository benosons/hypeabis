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

class Admin_global extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $logo_width = 300;
	var $logo_height = 300;
	var $cover_width = 1500;
	var $cover_height = 500;
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
  }
	
	public function index(){
    //ambil global data
		$data['global'] = $this->global_lib->getGlobalData();
		
		//load view.
    $content = $this->load->view('admin/global/detail', $data, true);
    
    array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
		$this->render($content);
	}
  
  public function updateGlobal(){
		$this->form_validation->set_rules('admin_token','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('website_name','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('tagline','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('url','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('address','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('longitude','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('latitude','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('zoom_level','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('email','', 'htmlentities|strip_tags|trim|xss_clean|required|valid_email');
		$this->form_validation->set_rules('phone1','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('phone2','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('fax','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('facebook','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('twitter','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('youtube','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('instagram','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('linkedin','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('logo_width','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('logo_height','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('logo','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('logo_white','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('meta_title','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('meta_desc','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('meta_keyword','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('privacy_policy','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('term_condition','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('cover','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('cover_user','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('home_layout_type','', 'htmlentities|strip_tags|trim|xss_clean|in_list[1,2]');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_global/index');
		}
		else{
      //ambil data info bedasarkan id admin.
      $global_data = $this->mdl_global->getGlobalData();
    
			$logo = '';
			$logo_white = '';
			$cover = '';
			
			if (!empty($_FILES['file_logo']['name'])){
				$config = $this->logoUploadConfig($_FILES['file_logo']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_logo')){
					$upload_data = array('upload_data' => $this->upload->data());
					$logo = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], ($global_data[0]->logo_width > 0 ? $global_data[0]->logo_width : $this->logo_width), ($global_data[0]->logo_height > 0 ? $global_data[0]->logo_height : $this->logo_height), true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file logo. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_global/index');
				}
			}
      
      if (!empty($_FILES['file_logo_white']['name'])){
				$config = $this->logoUploadConfig($_FILES['file_logo_white']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_logo_white')){
					$upload_data = array('upload_data' => $this->upload->data());
					$logo_white = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], ($global_data[0]->logo_width > 0 ? $global_data[0]->logo_width : $this->logo_width), ($global_data[0]->logo_height > 0 ? $global_data[0]->logo_height : $this->logo_height), true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file logo. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_global/index');
				}
			}
			
			if (!empty($_FILES['file_cover']['name'])){
				$config = $this->coverUploadConfig($_FILES['file_cover']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_cover')){
					$upload_data = array('upload_data' => $this->upload->data());
					$cover = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->cover_width, $this->cover_height, true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file cover. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_global/index');
				}
			}

      if (!empty($_FILES['file_cover_user']['name'])) {
        $config = $this->coverUploadConfig($_FILES['file_cover_user']['name']);
        $this->upload->initialize($config);

        //upload file article..
        if ($this->upload->do_upload('file_cover_user')) {
          $upload_data = array('upload_data' => $this->upload->data());
          $cover_user = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->cover_width, $this->cover_height, true);
        } else {
          $message =  $this->global_lib->generateMessage("Failed to upload file cover for user dashboard. <br/> cause: " . $this->upload->display_errors(), "danger");
          $this->session->set_flashdata('message', $message);
          redirect('admin_global/index');
        }
      }

			$update_data = array(
				'admin_token' => $this->input->post('admin_token'),
				'website_name' => $this->input->post('website_name'),
				'tagline' => $this->input->post('tagline'),
				'url' => $this->input->post('url'),
				'address' => $this->input->post('address'),
				'longitude' => $this->input->post('longitude'),
				'latitude' => $this->input->post('latitude'),
				'zoom_level' => $this->input->post('zoom_level'),
				'home_layout_type' => $this->input->post('home_layout_type'),
				'email' => $this->input->post('email'),
				'phone1' => $this->input->post('phone1'),
				'phone2' => $this->input->post('phone2'),
				'fax' => $this->input->post('fax'),
				'facebook' => $this->input->post('facebook'),
				'twitter' => $this->input->post('twitter'),
				'youtube' => $this->input->post('youtube'),
				'instagram' => $this->input->post('instagram'),
				'linkedin' => $this->input->post('linkedin'),
				'meta_title' => $this->input->post('meta_title'),
				'meta_desc' => $this->input->post('meta_desc'),
				'meta_keyword' => $this->input->post('meta_keyword'),
        'privacy_policy' => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('privacy_policy')),
				'term_condition' => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('term_condition'))
			);
			if(strlen(trim($logo)) > 0){
				$update_data['logo'] = $logo;
			}
      if(strlen(trim($logo_white)) > 0){
				$update_data['logo_white'] = $logo_white;
			}
			if(strlen(trim($cover)) > 0){
				$update_data['cover'] = $cover;
			}
      if (strlen(trim($cover_user)) > 0) {
        $update_data['cover_user'] = $cover_user;
      }
			
			$this->mdl_global->updateGlobalData($update_data);
			
      $message =  $this->global_lib->generateMessage("Global setting has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_global/index');
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
  
  private function logoUploadConfig($filename=''){
		$config['upload_path'] = './assets/logo/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '12000';
		$config['max_width']  = '8000';
		$config['max_height']  = '8000';
		if(strlen(trim($filename)) > 0){
			$config['file_name']  = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
		}
		return $config;
	}
	
	private function coverUploadConfig($filename=''){
		$config['upload_path'] = './assets/cover/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '12000';
		$config['max_width']  = '8000';
		$config['max_height']  = '8000';
		if(strlen(trim($filename)) > 0){
			$config['file_name']  = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
		}
		return $config;
	}
	
}
