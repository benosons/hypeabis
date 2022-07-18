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

class Admin_category extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $category_index = 0;
	var $category_list = array();
  var $pagination_per_page = 20;
  var $category_pic_width = 600;
	var $category_pic_height = 300;
  var $module_name = 'admin_category';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_category');
    
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    if(strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1'){
      redirect('admin_dashboard/index');
    }
  }
	
  public function index(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    //ambil julah total module di database..
		$data['total_row'] = $this->mdl_category->getAllCategoryCount();
	
    //ambil semua category dan susun berdasarkan parentnya
    $data['categories'] = $this->getAllCategory();
		
		//load view all module
		$content = $this->load->view('admin/category/all', $data, true);
		
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
		//load view add admin ...
		$content = $this->load->view('admin/category/add', null, true);
		
		$this->render($content);
	}
	
  public function addSub($id_category = ''){
		//ambil data category bedasarkan id..
		$data['category'] = $this->mdl_category->getCategoryByID($id_category);
		
		//jika tidak ada data, atau tidak updatable, redirect ke index.
		if((! is_array($data['category'])) || count($data['category']) < 1){
			redirect('admin_category/add');
		}
		
		//load view add category ...
		$content = $this->load->view('admin/category/add_sub', $data, true);
		
    $this->render($content);
	}
  
	public function saveAdd(){
		$this->form_validation->set_rules('category_name','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('category_parent','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('category_view','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('show_sidebar','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('updatable','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('deletable','', 'htmlentities|strip_tags|trim|xss_clean|integer');
    $this->form_validation->set_rules('file_pic','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('order','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
      if($this->input->post('category_parent') > 0){
				redirect('admin_category/addSub/' . $this->input->post('category_parent'));
			}
			else{
        redirect('admin_category/add');
      }
		}
		else{
			//upload category picture
			$category_picture = '';
			if (!empty($_FILES['file_pic']['name'])){
				$config = $this->categoryPicUploadConfig($_FILES['file_pic']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_pic')){
					$upload_data = array('upload_data' => $this->upload->data());
					$category_picture = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->category_pic_width, $this->category_pic_height, true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					$this->session->set_flashdata('message', $message);
          if($this->input->post('category_parent') > 0){
            redirect('admin_category/addSub/' . $this->input->post('category_parent'));
          }
          else{
            redirect('admin_category/add');
          }
				}
			}
      
			//insert data ke database..
			$insert_data = array(
				"category_name" => $this->input->post('category_name'),
				"category_parent" => $this->input->post('category_parent'),
				"category_view" => $this->input->post('category_view'),
				"show_sidebar" => $this->input->post('show_sidebar'),
				"updatable" => $this->input->post('updatable'),
				"deletable" => $this->input->post('deletable'),
				"order" => $this->input->post('order'),
				'category_picture' => $category_picture,
			);
			$this->mdl_category->insertCategory($insert_data);
			
      $message =  $this->global_lib->generateMessage("New category has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_category/index');
		}
	}
	
	public function edit($id_category=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data category yang akan diedit.
		$data['category'] = $this->mdl_category->getCategoryByID($id_category);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['category'])) || count($data['category']) < 1){
			redirect('admin_category/index');
		}
		
		//load view edit admin ...
		$content = $this->load->view('admin/category/edit', $data, true);
		
		$this->render($content);
	}
	
	public function saveEdit($id_category=''){
		//ambil data category yang akan diedit.
		$data['category'] = $this->mdl_category->getCategoryByID($id_category);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['category'])) || count($data['category']) < 1){
			redirect('admin_category/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('category_name','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('category_view','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('show_sidebar','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('updatable','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('deletable','', 'htmlentities|strip_tags|trim|xss_clean|integer');
    $this->form_validation->set_rules('file_pic','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('order','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_category/edit/' . $id_category);
		}
		else{
			//upload category picture..
			$category_picture = '';
			if (!empty($_FILES['file_pic']['name'])){
				$config = $this->categoryPicUploadConfig($_FILES['file_pic']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_pic')){
					$upload_data = array('upload_data' => $this->upload->data());
					$category_picture = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->category_pic_width, $this->category_pic_height, true);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_category/add');
				}
			}
			
			// update data admin ke database..
			$update_data = array(
				"category_name" => $this->input->post('category_name'),
				"category_view" => $this->input->post('category_view'),
				"show_sidebar" => $this->input->post('show_sidebar'),
				"updatable" => $this->input->post('updatable'),
				"deletable" => $this->input->post('deletable'),
				"order" => $this->input->post('order')
			);
			if(strlen(trim($category_picture)) > 0){
				$update_data['category_picture'] = $category_picture;
			}
			$this->mdl_category->updateCategory($update_data, $id_category);
			
      $message =  $this->global_lib->generateMessage("Category has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_category/edit/' . $id_category);
		}
	}
  
	public function delete($id_category=''){
		//ambil data category yang akan diedit.
		$data = $this->mdl_category->getCategoryByID($id_category);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_category/index');
		}
		
		if(isset($data[0]->category_picture) && strlen(trim($data[0]->category_picture)) > 0){
			@unlink('assets/category/'.$data[0]->category_picture);
		}
		
    $this->deleteChild($id_category);
		
    $message =  $this->global_lib->generateMessage("Category has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_category/index');
	}
  
  private function deleteChild($id_category=''){
    $this->mdl_category->deleteCategory($id_category);
		$category_child = $this->mdl_category->getCategoryChild($id_category);
		foreach($category_child as $item){
			$this->deleteChild($item->id_category);
		}
  }
	
	public function deletePic($id_category=''){
		//ambil data admin yang akan diedit.
		$data = $this->mdl_category->getCategoryByID($id_category);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_category/index');
		}
		
		//jika tidak ada redirect ke halaman edit
		if((! isset($data[0]->category_picture)) || strlen(trim($data[0]->category_picture)) == 0){
			redirect('admin_category/edit/' . $id_category);
		}
		else{
			$update_data = array(
				'category_picture' => "",
			);
			$this->mdl_category->updateCategory($update_data, $id_category);
			
			//hapus file.
			@unlink('assets/category/' . $data[0]->category_picture);
			redirect("admin_category/edit/" . $id_category);
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
			redirect('admin_category/index');
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
			$this->session->set_userdata('search_category', $search_param);
			
			redirect('admin_category/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_category');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('category_name');
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
		// validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_category/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_category/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_category->getSearchResultCount($search_param);
		$data['categories'] = $this->mdl_category->getSearchResultArr($search_param);
		
		//load view search result..
		$content = $this->load->view('admin/category/all', $data, true);
		
		$this->render($content);
	}
	
  private function getAllCategory(){
    //ambil data semua module utama / parent..
		$categories = $this->mdl_category->getAllCategoryParentArr();

		//ambil semua category child.
		foreach($categories as $x => $category){
      $has_child = $this->mdl_category->hasChild($category['id_category']);
      $categories[$x]['has_child'] = ($has_child ? 1 : 0);
      
			//cek apakah punya child.
			if($has_child){
				$categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
			}
		}
		
		$level = 0;
		foreach($categories as $x => $category){
      $this->category_list[$this->category_index] = $category;
      $this->category_list[$this->category_index]['category_name'] = '- ' . $category['category_name'];
			$this->category_index++;

      //cek apakah punya child.
			if($category['has_child'] == 1){
				$this->generateCategoryChildList($category['child'], $level);
			}
		}
    
    return $this->category_list;
  }
  
  private function getCategoryChild($id_category = ''){
    $categories = array();
    $categories = $this->mdl_category->getCategoryChildArr($id_category);
    
    //ambil semua category child.
		foreach($categories as $x => $category){
      $has_child = $this->mdl_category->hasChild($category['id_category']);
      $categories[$x]['has_child'] = ($has_child ? 1 : 0);
      
			//cek apakah punya child.
			if($has_child){
				$categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
			}
		}
    return $categories;
  }
  
  private function generateCategoryChildList($categories, $level){
    $level++;
    $indentation = "";
    for($x = 0; $x < $level; $x++){
      $indentation .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
		foreach($categories as $x => $category){
      $this->category_list[$this->category_index] = $category;
      $this->category_list[$this->category_index]['category_name'] = $indentation . '- ' . $category['category_name'];
			$this->category_index++;
      
      //cek apakah punya child.
			if($category['has_child'] == 1){
				$this->generateCategoryChildList($category['child'], $level);
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
  
  private function categoryPicUploadConfig($filename=''){
		$config['upload_path'] = './assets/category/';
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
		$this->session->set_userdata('search_category', $search_param);
	}
}
