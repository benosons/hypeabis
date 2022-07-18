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

class Admin_profile extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $admin_pic_width = 300;
	var $admin_pic_height = 300;
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_admin');
    
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
  }
	
	public function index(){
    //ambil global data
		$data['admin'] = $this->mdl_admin->getAdminByID($this->session->userdata('id_admin'));
		
		//load view.
    $content = $this->load->view('admin/profile/detail', $data, true);
    
		$this->render($content);
	}
  
  public function updateAdmin(){
		$this->form_validation->set_rules('name','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('email','', 'htmlentities|strip_tags|trim|xss_clean|required|valid_email');
		$this->form_validation->set_rules('contact_number','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('file_admin','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_profile/index');
		}
		else{
			$admin_photo = '';
			if (!empty($_FILES['file_admin']['name'])){
				$config = $this->adminPhotoUploadConfig($_FILES['file_admin']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_admin')){
					$upload_data = array('upload_data' => $this->upload->data());
					$admin_photo = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->admin_pic_width, $this->admin_pic_height, true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_profile/index');
				}
			}

			$update_data = array(
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'contact_number' => $this->input->post('contact_number'),
			);
			if(strlen(trim($admin_photo)) > 0){
				$update_data['admin_photo'] = $admin_photo;
        
        //ganti global_logo di session..
				$this->session->set_userdata(array("admin_photo" => $admin_photo));
			}
			$this->mdl_admin->updateAdmin($update_data, $this->session->userdata('id_admin'));
			
      $message =  $this->global_lib->generateMessage("Your account has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_profile/index');
		}
	}
  
  public function updatePassword(){
		$this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('new_password','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('confirm_password','', 'htmlentities|strip_tags|trim|xss_clean|matches[new_password]|required');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_profile/index');
		}
		else{
			$update_data = array();
      
			//cek password jika ganti password..
			if(strlen(trim($this->input->post('new_password'))) > 0){
				$old_password = sha1($this->input->post('password') . $this->config->item('binary_salt'));
        $check_password = $this->mdl_admin->checkAdminByIDAndPassword($this->session->userdata('id_admin'), $old_password);
				
				//jika password salah, redirect ke halaman edit..
				if(! $check_password){
          $message =  $this->global_lib->generateMessage("Wrong password. You must enter your current password for change your password.", "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_profile/index');
				}
				else{
					$update_data['password'] = sha1($this->input->post('new_password') . $this->config->item('binary_salt'));
          $this->mdl_admin->updateAdmin($update_data, $this->session->userdata('id_admin'));
				}
			}
			
      $message =  $this->global_lib->generateMessage("Your account has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_profile/index');
		}
	}
  
  public function deleteAdminPic(){
    $update_data['admin_photo'] = '';
    $this->mdl_admin->updateAdmin($update_data, $this->session->userdata('id_admin'));
    
    $message =  $this->global_lib->generateMessage("Super admin picture has been removed.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('admin_profile/index');
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
  
  private function adminPhotoUploadConfig($filename=''){
		$config['upload_path'] = './assets/admin/';
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
