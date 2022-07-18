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

class Admin_account extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $admin_pic_width = 300;
	var $admin_pic_height = 300;
  var $pagination_per_page = 20;
  var $module_name = 'admin_account';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_admin');
    
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    if((strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1') && 
      $this->uri->segment(2) != 'searchUserByKeyword'){
      redirect('admin_dashboard/index');
    }
  }
	
  public function index(){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil total row untuk keperluan config pagination dan jumlah data di depan..
		$data['total_row'] = $this->mdl_admin->getAllAdminCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['admin'] = $this->mdl_admin->getAllAdminLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all admin..
		$content = $this->load->view('admin/admin/all', $data, true);
		
		$this->render($content);
	}
	
  public function searchUserByKeyword()
  {
    //cek apakah ajax request atau bukan..
    if (! $this->input->is_ajax_request()) redirect('admin_user');

    $q = preg_replace("/[^a-zA-Z0-9-]+/", "", $this->input->get('q'));

    //ambil user by keyword
    $admins = $this->mdl_admin->getAdminByKeywordForSelect2($q);

    echo json_encode($admins);
  }

	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    $data['modules'] = $this->global_lib->getModuleList();
    
		//load view add admin ...
		$content = $this->load->view('admin/admin/add', $data, true);
		
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('name','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('username','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('email','', 'htmlentities|strip_tags|trim|xss_clean|required|valid_email');
		$this->form_validation->set_rules('contact_number','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('confirm_password','', 'htmlentities|strip_tags|trim|required|xss_clean|matches[password]');
		$this->form_validation->set_rules('status','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('file_admin','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/add');
		}
		else{
			//check email sudah ada yang pakai / belum.
			$email = $this->input->post('email');
			$check = $this->mdl_admin->checkAdminByEmail($email);
			if($check){
        $message =  $this->global_lib->generateMessage("This email already use. Please use another email.", "danger");
				$this->session->set_flashdata('message', $message);
				redirect('admin_account/add');
			}
			
			//check username sudah ada yang pakai / belum.
			$username = $this->input->post('username');
			$check = $this->mdl_admin->checkAdminByUsername($username);
			if($check){
        $message =  $this->global_lib->generateMessage("This username already use. Please use another username.", "danger");
				$this->session->set_flashdata('message', $message);
				redirect('admin_account/add');
			}
		
			//upload admin foto
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
					redirect('admin_account/add');
				}
			}
      
      $grant_str = "";
      $modules = $this->global_lib->getModuleList();
      foreach($modules as $x => $module){
        if($module['has_child'] == '0'){
          $mod = strtolower($module['module_redirect']);
          if(strpos($this->session->userdata('admin_grant'), $mod . '|') !== false || $this->session->userdata('admin_level') == '1'){
            if($this->input->post($mod) == $mod){
              $grant_str .= $mod . "|";
            }
          }
        }
      }
			
			//insert data ke database..
			$insert_data = array(
				'name' => $this->input->post('name'),
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'contact_number' => $this->input->post('contact_number'),
				'password' => sha1($this->input->post('password') . $this->config->item('binary_salt')),
				'status' => $this->input->post('status'),
				'level' => 2,
				'grant' => $grant_str,
				'admin_photo' => $admin_photo,
			);
			$this->mdl_admin->insertAdmin($insert_data);
			
      $message =  $this->global_lib->generateMessage("Admin account has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/index');
		}
	}
	
	public function edit($id_admin=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//jika yg diedit adalah data sendiri, redirect ke admin_profile
		if($this->session->userdata('id_admin') == $id_admin){
			redirect('admin_profile/index');
		}
		
		//ambil data admin yang akan diedit.
		$data['admin'] = $this->mdl_admin->getAdminByID($id_admin);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['admin'])) || count($data['admin']) < 1){
			redirect('admin_account/index');
		}
		
		//cek untuk super admin, tidak bisa diedit oleh orang lain..
		if($data['admin'][0]->level == 1){
      $message =  $this->global_lib->generateMessage("You can't edit super admin data.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/index');
		}
    
    $data['modules'] = $this->global_lib->getModuleList();
		
		//load view edit admin ...
		$content = $this->load->view('admin/admin/edit', $data, true);
		
		$this->render($content);
	}
	
	public function saveEdit($id_admin=''){
		//ambil data admin yang akan diedit.
		$data['admin'] = $this->mdl_admin->getAdminByID($id_admin);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['admin'])) || count($data['admin']) < 1){
			redirect('admin_account/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('name','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('username','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('email','', 'htmlentities|strip_tags|trim|xss_clean|required|valid_email');
		$this->form_validation->set_rules('contact_number','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('status','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('file_admin','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/edit/'.$id_admin);
		}
		else{
			//cek email tersedia atau tidak jika ganti email..
			$email = $this->input->post('email');
			if(strtolower($email) != strtolower($data['admin'][0]->email)){
				$cek = $this->mdl_admin->checkAdminByEmail($email);
				if($cek){
          $message =  $this->global_lib->generateMessage("This email already use. Please use another email.", "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_account/edit/' . $id_admin);
				}
			}
			
			//cek username tersedia atau tidak jika ganti username..
			$username = $this->input->post('username');
			if(strtolower($username) != strtolower($data['admin'][0]->username)){
				$cek = $this->mdl_admin->checkAdminByUsername($username);
				if($cek){
          $message =  $this->global_lib->generateMessage("This username already use. Please use another username.", "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_account/add');
				}
			}
			
			//upload photo..
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
      
      $grant_str = "";
      $modules = $this->global_lib->getModuleList();
      foreach($modules as $x => $module){
        if($module['has_child'] == '0'){
          $mod = strtolower($module['module_redirect']);
          if(strpos($this->session->userdata('admin_grant'), $mod . '|') !== false || $this->session->userdata('admin_level') == '1'){
            if($this->input->post($mod) == $mod){
              $grant_str .= $mod . "|";
            }
          }
        }
      }
			
			// update data admin ke database..
			$update_data = array(
				'name' => $this->input->post('name'),
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'contact_number' => $this->input->post('contact_number'),
				'status' => $this->input->post('status'),
				'grant' => $grant_str
			);
			if(strlen(trim($admin_photo)) > 0){
				$update_data['admin_photo'] = $admin_photo;
				
				if($id_admin == $this->session->userdata('id_admin')){
					//ganti admin photo di session..
					$this->session->set_userdata(array("admin_photo" => $admin_photo));
				}
			}
			
			$this->mdl_admin->updateAdmin($update_data, $id_admin);
			
      $message =  $this->global_lib->generateMessage("Administrator account has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/edit/' . $id_admin);
		}
	}
  
  public function updatePassword($id_admin = ''){
    //ambil data admin yang akan diedit.
		$data['admin'] = $this->mdl_admin->getAdminByID($id_admin);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['admin'])) || count($data['admin']) < 1){
			redirect('admin_account/index');
		}
    
    //jika password baru diisi, validasi form ganti password..
		if(strlen($this->input->post('new_password')) > 0){
			// $this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|required|xss_clean');
			$this->form_validation->set_rules('new_password','', 'htmlentities|strip_tags|trim|required|xss_clean');
			$this->form_validation->set_rules('confirm_password','', 'htmlentities|strip_tags|trim|required|xss_clean|matches[new_password]');
      if ($this->form_validation->run() == FALSE){
        $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('admin_account/edit/'.$id_admin);
      }
      else{
        //cek password
        // $old_password = sha1($this->input->post('password') . $this->config->item('binary_salt'));
        // $check_password = $this->mdl_admin->checkAdminByIDAndPassword($id_admin, $old_password);
        
        //jika password salah, redirect ke halaman edit..
        // if(! $check_password){
        //   $message =  $this->global_lib->generateMessage("Wrong password. You must input correct password to change this admin password.", "danger");
        //   $this->session->set_flashdata('message', $message);
        //   redirect('admin_account/edit/'.$id_admin);
        // }
        
        $update_data = array();
        $update_data['password'] = sha1($this->input->post('new_password') . $this->config->item('binary_salt'));
        $this->mdl_admin->updateAdmin($update_data, $id_admin);
        
        $message =  $this->global_lib->generateMessage("Administrator password has been updated.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('admin_account/edit/' . $id_admin);
      }
		}
    else{
      redirect('admin_account/edit/' . $id_admin);
    }
  }
	
	public function delete($id_admin=''){
		//ambil data admin yang akan diedit.
		$data = $this->mdl_admin->getAdminByID($id_admin);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_account/index');
		}
		
		//tidak bisa hapus diri sendiri..
		if($this->session->userdata('id_admin') == $data[0]->id_admin){
      $message =  $this->global_lib->generateMessage("You can't delete your own data.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/index');
		}
		
		//cek untuk super admin, tidak bisa di delete oleh orang lain..
		if($data[0]->level == 1){
      $message =  $this->global_lib->generateMessage("You can't delete your own data.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/index');
		}
		
		if(isset($data[0]->admin_photo) && strlen(trim($data[0]->admin_photo)) > 0){
			@unlink('assets/admin/'.$data[0]->admin_photo);
		}
		
		$this->mdl_admin->deleteAdmin($id_admin);
		
    $message =  $this->global_lib->generateMessage("Administrator account has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_account/index');
	}
	
	public function deletePic($id_admin=''){
		//jika yg dihapus akun sendiri, redirect ke halaman profile..
		if($this->session->userdata('id_admin') == $id_admin){
			redirect('admin_profile/index');
		}
		
		//ambil data admin yang akan diedit.
		$data = $this->mdl_admin->getAdminByID($id_admin);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_account/index');
		}
		
		//cek untuk super admin, tidak bisa dihapus oleh orang lain..
		if($data[0]->level == 1){
      $message =  $this->global_lib->generateMessage("You can't delete super admin profile picture.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/index');
		}
	
		//jika tidak ada foto redirect ke halaman edit
		if((! isset($data[0]->admin_photo)) || strlen(trim($data[0]->admin_photo)) == 0){
			redirect('admin_account/edit/'.$id_admin);
		}
		else{
			$update_data = array(
				'admin_photo' => "",
			);
			$this->mdl_admin->updateAdmin($update_data, $id_admin);
			
			//hapus file foto.
			@unlink('assets/admin_photo/'.$data[0]->admin_photo);
			redirect("admin_account/edit/".$id_admin);
		}
	}
	
	public function banned($id_admin = ''){
		//jika yg dihapus akun sendiri, redirect ke halaman profile..
		if($this->session->userdata('id_admin') == $id_admin){
			redirect('admin_profile/index');
		}
		
		//ambil data admin yang akan diedit.
		$data = $this->mdl_admin->getAdminByID($id_admin);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_account/index');
		}
		
		if($data[0]->level == 1){
      $message =  $this->global_lib->generateMessage("You can't change super admin status.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/index');
		}
	
		$this->mdl_admin->updateAdmin(array('status' => '0'), $id_admin);
		
    $message =  $this->global_lib->generateMessage("Administrator account has been banned", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_account/index');
	}
	
	public function activate($id_admin = ''){
		//jika yg dihapus akun sendiri, redirect ke halaman profile..
		if($this->session->userdata('id_admin') == $id_admin){
			redirect('admin_profile/index');
		}
		
		//ambil data admin yang akan diedit.
		$data = $this->mdl_admin->getAdminByID($id_admin);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_account/index');
		}
		
		if($data[0]->level == 1){
      $message =  $this->global_lib->generateMessage("You can't change super admin status.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/index');
		}
	
		$this->mdl_admin->updateAdmin(array('status' => '1'), $id_admin);
		
    $message =  $this->global_lib->generateMessage("Administrator account has been activated.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_account/index');
	}
	
  public function resetPassword(){
		
	}	
	
	public function submitSearch(){
		//validasi input..
		$this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('status','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('search_collapsed','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_account/index');
		}
		else{
			//clear search session yang lama..
			$this->clearSearchSession();
		
			//ambil data input dan restore ke session sebagai parameter search..
			$search_param = array(
				'search_by' => $this->input->post('search_by'),
				'operator' => html_entity_decode($this->input->post('operator')),
				'keyword' => $this->input->post('keyword'),
				'per_page' => $this->input->post('per_page'),
				'sort_by' => $this->input->post('sort_by'),
				'status' => $this->input->post('status'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_admin', $search_param);
			
			redirect('admin_account/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_admin');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('name', 'username', 'grant', 'id_admin', 'email', 'contact_number');
		$sort_by_list = array(
			'default' => 'id_admin DESC',
			'newest' => 'id_admin DESC',
			'oldest' => 'id_admin ASC',
			'name_asc' => 'name ASC',
			'name_desc' => 'name DESC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
		// validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_account/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_account/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_account/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_account/index');
		}
		// =========================================================================================//
		
		// hitung total row hasil pencarian untuk pagination..
		$total_row = $this->mdl_admin->getSearchResultCount($search_param);
		// pagination config ..
		$config = $this->searchPaginationConfig($total_row, $per_page);
		$this->pagination->initialize($config);
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $total_row;
		$data['admin'] = $this->mdl_admin->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view search result..
		$content = $this->load->view('admin/admin/all', $data, true);
		
		$this->render($content);
	}
	
  private function paginationConfig($total_rows){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_account/index/';
		$config['total_rows'] 	= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']	= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_account/search/';
		$config['total_rows'] 	= $total_row;
		$config['per_page'] 		= ($per_page > 0 ? $per_page : $this->pagination_per_page);
		$config['uri_segment']	= 3;
		return $config;
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
	
	private function clearSearchSession(){
		//declare session search..
		$search_param = array(
			'search_by' => 'default',
			'operator' => null,
			'keyword' => null,
			'sort_by' => 'default',
			'per_page' => $this->pagination_per_page,
			'status' => 'all',
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_admin', $search_param);
	}
}
