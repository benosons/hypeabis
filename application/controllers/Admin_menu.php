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

class Admin_menu extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $menu_index = 0;
	var $menu_list = array();
  var $pagination_per_page = 20;
  var $menu_pic_width = 600;
	var $menu_pic_height = 300;
  var $module_name = 'admin_menu';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_menu');
    
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    if(strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1'){
      redirect('admin_dashboard/index');
      
      // print_r('<pre>');
      // print_r($this->session->userdata('admin_grant'));
      // print_r('</pre>');
    }
  }
	
  public function index(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    //ambil julah total module di database..
		$data['total_row'] = $this->mdl_menu->getAllMenuCount();
	
    //ambil semua menu dan susun berdasarkan parentnya
    $data['menus'] = $this->getAllMenu();
		
		//load view all module
		$content = $this->load->view('admin/menu/all', $data, true);
		
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
		//load view add admin ...
		$content = $this->load->view('admin/menu/add', null, true);
		
		$this->render($content);
	}
	
  public function addSub($id_menu = ''){
		//ambil data menu bedasarkan id..
		$data['menu'] = $this->mdl_menu->getMenuByID($id_menu);
		
		//jika tidak ada data, atau tidak updatable, redirect ke index.
		if((! is_array($data['menu'])) || count($data['menu']) < 1){
			redirect('admin_menu/add');
		}
		//cek status updatable dan superadmin..
		if($data['menu'][0]->menu_type == 'builtin'){
			redirect('admin_menu/index');
		}
		
		//load view add menu ...
		$content = $this->load->view('admin/menu/add_sub', $data, true);
		
    $this->render($content);
	}
  
	public function saveAdd(){
		$this->form_validation->set_rules('menu_name','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('menu_name_lang1','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('menu_parent','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('menu_type','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('redirect_url','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('menu_status','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('updatable','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('deletable','', 'htmlentities|strip_tags|trim|xss_clean|integer');
    $this->form_validation->set_rules('file_pic','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('menu_order','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
    $this->form_validation->set_rules('dev_code','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
      if($this->input->post('menu_parent') > 0){
				redirect('admin_menu/addSub/' . $this->input->post('menu_parent'));
			}
			else{
        redirect('admin_menu/add');
      }
		}
		else{
			//upload menu picture
			$menu_picture = '';
			if (!empty($_FILES['file_pic']['name'])){
				$config = $this->menuPicUploadConfig($_FILES['file_pic']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_pic')){
					$upload_data = array('upload_data' => $this->upload->data());
					$menu_picture = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->menu_pic_width, $this->menu_pic_height, true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					if($this->input->post('menu_parent') > 0){
            redirect('admin_menu/addSub/' . $this->input->post('menu_parent'));
          }
          else{
            redirect('admin_menu/add');
          }
				}
			}
      
			//insert data ke database..
			$insert_data = array(
				"menu_name" => $this->input->post('menu_name'),
				"menu_name_lang1" => $this->input->post('menu_name_lang1'),
				"menu_parent" => $this->input->post('menu_parent'),
				"redirect_url" => $this->input->post('redirect_url'),
				"menu_type" => $this->input->post('menu_type'),
				"menu_status" => $this->input->post('menu_status'),
				"updatable" => $this->input->post('updatable'),
				"deletable" => $this->input->post('deletable'),
				"menu_order" => $this->input->post('menu_order'),
				"dev_code" => $this->input->post('dev_code'),
				'menu_picture' => $menu_picture,
			);
			$this->mdl_menu->insertMenu($insert_data);
			
      $message =  $this->global_lib->generateMessage("New menu has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_menu/index');
		}
	}
	
	public function edit($id_menu=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data menu yang akan diedit.
		$data['menu'] = $this->mdl_menu->getMenuByID($id_menu);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['menu'])) || count($data['menu']) < 1){
			redirect('admin_menu/index');
		}
		
		//load view edit admin ...
		$content = $this->load->view('admin/menu/edit', $data, true);
		
		$this->render($content);
	}
	
	public function saveEdit($id_menu=''){
		//ambil data menu yang akan diedit.
		$data['menu'] = $this->mdl_menu->getMenuByID($id_menu);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['menu'])) || count($data['menu']) < 1){
			redirect('admin_menu/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('menu_name','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('menu_name_lang1','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('menu_type','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('redirect_url','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('menu_status','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('updatable','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('deletable','', 'htmlentities|strip_tags|trim|xss_clean|integer');
    $this->form_validation->set_rules('file_pic','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('menu_order','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
    $this->form_validation->set_rules('dev_code','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form." . validation_errors(), "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_menu/edit/' . $id_menu);
		}
		else{
			//upload menu picture..
			$menu_picture = '';
			if (!empty($_FILES['file_pic']['name'])){
				$config = $this->menuPicUploadConfig($_FILES['file_pic']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_pic')){
					$upload_data = array('upload_data' => $this->upload->data());
					$menu_picture = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->menu_pic_width, $this->menu_pic_height, true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_menu/add');
				}
			}
			
			// update data admin ke database..
			$update_data = array(
				"menu_name" => $this->input->post('menu_name'),
				"menu_name_lang1" => $this->input->post('menu_name_lang1'),
				"redirect_url" => $this->input->post('redirect_url'),
				"menu_type" => $this->input->post('menu_type'),
				"menu_status" => $this->input->post('menu_status'),
				"updatable" => $this->input->post('updatable'),
				"deletable" => $this->input->post('deletable'),
				"menu_order" => $this->input->post('menu_order'),
				"dev_code" => $this->input->post('dev_code')
			);
			if(strlen(trim($menu_picture)) > 0){
				$update_data['menu_picture'] = $menu_picture;
			}
			$this->mdl_menu->updateMenu($update_data, $id_menu);
			
      $message =  $this->global_lib->generateMessage("Menu has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_menu/edit/' . $id_menu);
		}
	}
  
	public function delete($id_menu=''){
		//ambil data menu yang akan diedit.
		$data = $this->mdl_menu->getMenuByID($id_menu);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_menu/index');
		}
		
		if(isset($data[0]->menu_picture) && strlen(trim($data[0]->menu_picture)) > 0){
			@unlink('assets/menu/'.$data[0]->menu_picture);
		}
		
    $this->deleteChild($id_menu);
		
    $message =  $this->global_lib->generateMessage("Menu has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_menu/index');
	}
  
  private function deleteChild($id_menu=''){
    $this->mdl_menu->deleteMenu($id_menu);
		$menu_child = $this->mdl_menu->getMenuChild($id_menu);
		foreach($menu_child as $item){
			$this->deleteChild($item->id_menu);
		}
  }
	
	public function deletePic($id_menu=''){
		//ambil data admin yang akan diedit.
		$data = $this->mdl_menu->getMenuByID($id_menu);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_menu/index');
		}
		
		//jika tidak ada redirect ke halaman edit
		if((! isset($data[0]->menu_picture)) || strlen(trim($data[0]->menu_picture)) == 0){
			redirect('admin_menu/edit/' . $id_menu);
		}
		else{
			$update_data = array(
				'menu_picture' => "",
			);
			$this->mdl_menu->updateMenu($update_data, $id_menu);
			
			//hapus file.
			@unlink('assets/menu/' . $data[0]->menu_picture);
			redirect("admin_menu/edit/" . $id_menu);
		}
	}
	
	public function submitSearch(){
		//validasi input..
		$this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_menu/index');
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
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_menu', $search_param);
			
			redirect('admin_menu/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_menu');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('menu_name');
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
		// validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_menu/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_menu/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_menu->getSearchResultCount($search_param);
		$data['menus'] = $this->mdl_menu->getSearchResultArr($search_param);
		
		//load view search result..
		$content = $this->load->view('admin/menu/all', $data, true);
		
		$this->render($content);
	}
	
  private function getAllMenu(){
    //ambil data semua module utama / parent..
		$menus = $this->mdl_menu->getAllMenuParentArr();

		//ambil semua menu child.
		foreach($menus as $x => $menu){
      $has_child = $this->mdl_menu->hasChild($menu['id_menu']);
      $menus[$x]['has_child'] = ($has_child ? 1 : 0);
      
			//cek apakah punya child.
			if($has_child){
				$menus[$x]['child'] = $this->getMenuChild($menu['id_menu']);
			}
		}
		
		$level = 0;
		foreach($menus as $x => $menu){
      $this->menu_list[$this->menu_index] = $menu;
      $this->menu_list[$this->menu_index]['menu_name'] = '- ' . $menu['menu_name'];
			$this->menu_index++;

      //cek apakah punya child.
			if($menu['has_child'] == 1){
				$this->generateMenuChildList($menu['child'], $level);
			}
		}
    
    return $this->menu_list;
  }
  
  private function getMenuChild($id_menu = ''){
    $menus = array();
    $menus = $this->mdl_menu->getMenuChildArr($id_menu);
    
    //ambil semua menu child.
		foreach($menus as $x => $menu){
      $has_child = $this->mdl_menu->hasChild($menu['id_menu']);
      $menus[$x]['has_child'] = ($has_child ? 1 : 0);
      
			//cek apakah punya child.
			if($has_child){
				$menus[$x]['child'] = $this->getMenuChild($menu['id_menu']);
			}
		}
    return $menus;
  }
  
  private function generateMenuChildList($menus, $level){
    $level++;
    $indentation = "";
    for($x = 0; $x < $level; $x++){
      $indentation .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
		foreach($menus as $x => $menu){
      $this->menu_list[$this->menu_index] = $menu;
      $this->menu_list[$this->menu_index]['menu_name'] = $indentation . '- ' . $menu['menu_name'];
			$this->menu_index++;
      
      //cek apakah punya child.
			if($menu['has_child'] == 1){
				$this->generateMenuChildList($menu['child'], $level);
			}
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
  
  private function menuPicUploadConfig($filename=''){
		$config['upload_path'] = './assets/menu/';
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
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_menu', $search_param);
	}
}
