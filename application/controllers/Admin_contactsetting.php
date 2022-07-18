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

class Admin_contactsetting extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $module_name = 'admin_contactsetting';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_contactsetting');
    
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    if(strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1'){
      redirect('admin_dashboard/index');
    }
  }
	
  public function index(){
		$data['contactsetting'] = $this->mdl_contactsetting->getContactsetting();
		
		//load view all module
		$content = $this->load->view('admin/contactsetting/detail', $data, true);
		
		$this->render($content);
	}
	
	public function saveContactsetting($id_contactsetting=''){
		//ambil data contactsetting yang akan diedit.
		$data['contactsetting'] = $this->mdl_contactsetting->getContactsettingByID($id_contactsetting);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['contactsetting'])) || count($data['contactsetting']) < 1){
			redirect('admin_contactsetting/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('contact_desc','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('contact_email','', 'htmlentities|strip_tags|trim|xss_clean|valid_email|required');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validasi invalid. isi form dengan lengkap dan format yang benar.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_contactsetting/index');
		}
		else{
			// update data admin ke database..
			$update_data = array(
				"contact_desc" => $this->input->post('contact_desc'),
				"contact_email" => $this->input->post('contact_email')
			);
			$this->mdl_contactsetting->updateContactsetting($update_data, $id_contactsetting);
      
      $message =  $this->global_lib->generateMessage("Pengaturan kontak berhasil diupdate.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_contactsetting/index');
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