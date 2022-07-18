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

class Admin_authorcategory extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $category_index = 0;
	var $category_list = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_authorcategory';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_user');
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
    
    //ambil total row untuk keperluan config pagination dan jumlah data di depan..
		$data['total_row'] = $this->mdl_user->getAllFeaturedUserCategoryCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		$data['users'] = $this->mdl_user->getAllFeaturedUserCategoryLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
    //ambil semua category
		$data['categories'] = $this->getAllCategory();
		
		//load view all module
		$content = $this->load->view('admin/authorcategory/all', $data, true);
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    //ambil semua category
		$data['categories'] = $this->getAllCategory();
    
		//load view add admin ...
		$content = $this->load->view('admin/authorcategory/add', $data, true);
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('id_category','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('id_user','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('author_order','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_authorcategory/add');
		}
		else{
      $id_user = $this->input->post('id_user');
      $id_category = $this->input->post('id_category');
      $author_order = $this->input->post('author_order');
      
      //check apakah data sudah ada / belum..
      $author = $this->mdl_user->getFeaturedUserCategoryByIDUserAndIDCategory($id_user, $id_category);
      if(isset($author[0]->id_author_category) && $author[0]->id_author_category > 0){
        $update_data = array(
          "author_order" => $author_order
        );
        $this->mdl_user->updateFeaturedUserCategory($update_data, $author[0]->id_author_category);
      }
      else{
        //insert..
        $insert_data = array(
          "id_category" => $id_category,
          "id_user" => $id_user,
          "author_order" => $author_order
        );
        $this->mdl_user->insertFeaturedUserCategory($insert_data);
      }
      
      $message =  $this->global_lib->generateMessage("User berhasil ditambahkan sebagai penulis pilihan pada halaman kategori.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_authorcategory/index');
		}
	}
	
	public function edit($id_authorcategory=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data authorcategory yang akan diedit.
		$data['author'] = $this->mdl_user->getFeaturedUserCategoryByID($id_authorcategory);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['author'])) || count($data['author']) < 1){
			redirect('admin_authorcategory/index');
		}
    
    //ambil semua category
		$data['categories'] = $this->getAllCategory();
    
		//load view edit admin ...
		$content = $this->load->view('admin/authorcategory/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_authorcategory=''){
		//ambil data authorcategory yang akan diedit.
		$data['author'] = $this->mdl_user->getFeaturedUserCategoryByID($id_authorcategory);
		if((! is_array($data['author'])) || count($data['author']) < 1){
			redirect('admin_authorcategory/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('id_category','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('id_user','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('author_order','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_authorcategory/edit/' . $id_authorcategory);
		}
		else{
			// update data admin ke database..
			$update_data = array(
				"author_order" => $this->input->post('author_order')
			);
			$this->mdl_user->updateFeaturedUserCategory($update_data, $id_authorcategory);
      
      $message =  $this->global_lib->generateMessage("Data penulis pilihan berhasil di update", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_authorcategory/edit/' . $id_authorcategory);
		}
	}
  
	public function delete($id_authorcategory=''){
		//ambil data authorcategory yang akan diedit.
		$data['author'] = $this->mdl_user->getFeaturedUserCategoryByID($id_authorcategory);
		if((! is_array($data['author'])) || count($data['author']) < 1){
			redirect('admin_authorcategory/index');
		}
		
    $this->mdl_user->deleteFeaturedUserCategory($id_authorcategory);
		
    $message =  $this->global_lib->generateMessage("Data user berhasil dihapus dari daftar penulis pilihan halaman kategori", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_authorcategory/index');
	}
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('category','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_authorcategory/index');
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
				'category' => $this->input->post('category'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_authorcategory', $search_param);
			redirect('admin_authorcategory/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_authorcategory');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('tbl_user.name', 'tbl_category.category_name');
    $sort_by_list = array(
			'default' => 'tbl_category.order ASC, tbl_author_category.author_order ASC',
			'newest' => 'tbl_author_category.id_author_category DESC',
			'oldest' => 'tbl_author_category.id_author_category ASC',
			'name_asc' => 'tbl_user.name ASC',
			'name_desc' => 'tbl_user.name DESC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_authorcategory/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_authorcategory/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_authorcategory/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_authorcategory/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_user->getFeaturedUserCategorySearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
		$data['users'] = $this->mdl_user->getFeaturedUserCategorySearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
    //ambil semua category
		$data['categories'] = $this->getAllCategory();
    
		//load view search result..
		$content = $this->load->view('admin/authorcategory/all', $data, true);
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
      $this->category_list[$this->category_index]['category_name'] = $category['category_name'];
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
      // $indentation .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      $indentation .= "";
    }
		foreach($categories as $x => $category){
      $this->category_list[$this->category_index] = $category;
      $this->category_list[$this->category_index]['category_name'] = $indentation . $category['category_name'];
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
  
  private function paginationConfig($total_rows){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_authorcategory/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_authorcategory/search/';
		$config['total_rows'] 		= $total_row;
		$config['per_page'] 		= ($per_page > 0 ? $per_page : $this->pagination_per_page);
		$config['uri_segment']		= 3;
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
			'category' => 'all',
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_authorcategory', $search_param);
	}
}