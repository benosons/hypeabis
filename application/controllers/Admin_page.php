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

class Admin_page extends CI_Controller {
  var $js = array();
  var $css = array();
  var $category_index = 0;
	var $category_list = array();
  var $pagination_per_page = 20;
  var $header_width = 960;
	var $header_height = 400;
  var $header_thumb_width = 320;
	var $header_thumb_height = 200;
  var $module_name = 'admin_page';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_page');
    
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
		$data['total_row'] = $this->mdl_page->getAllPageCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['page'] = $this->mdl_page->getAllPageLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all module
		$content = $this->load->view('admin/page/all', $data, true);
		
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    $data = array();
    
		//load view add admin ...
		$content = $this->load->view('admin/page/add', $data, true);
		
    array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('page_title','', 'htmlentities|strip_tags|trim|required|max_length[1000]|xss_clean');
    $this->form_validation->set_rules('page_status','', 'htmlentities|strip_tags|required|trim|xss_clean|integer');
    $this->form_validation->set_rules('page_content','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('updatable','', 'htmlentities|strip_tags|required|trim|xss_clean|integer');
		$this->form_validation->set_rules('deletable','', 'htmlentities|strip_tags|required|trim|xss_clean|integer');
		$this->form_validation->set_rules('file_header','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_page/add');
		}
		else{
			//upload page picture
			$page_header = '';
			$page_header_thumb = '';
			if (!empty($_FILES['file_header']['name'])){
				$config = $this->pagePicUploadConfig($_FILES['file_header']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_header')){
					$upload_data = array('upload_data' => $this->upload->data());
					$page_header = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->header_width, $this->header_height, true);
          $page_header_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'],$this->header_thumb_width,$this->header_thumb_height);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_page/add');
				}
			}
      
			//insert data ke database..
			$insert_data = array(
				"page_title" => $this->input->post('page_title'),
				"page_status" => $this->input->post('page_status'),
				"updatable" => $this->input->post('updatable'),
				"deletable" => $this->input->post('deletable'),
				"page_content" => str_replace(base_url() . "asset/page_file/", "##BASE_URL##",$this->input->post('page_content')),
				"page_header" => $page_header,
				"page_header_thumb" => $page_header_thumb
			);
			$this->mdl_page->insertPage($insert_data);
      
      $message =  $this->global_lib->generateMessage("New page has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_page/index');
		}
	}
	
	public function edit($id_page=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data page yang akan diedit.
		$data['page'] = $this->mdl_page->getPageByID($id_page);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['page'])) || count($data['page']) < 1){
			redirect('admin_page/index');
		}
    
    //cek status updatable dan superadmin..
		if($data['page'][0]->updatable == 0 && $this->session->userdata('admin_level') != '1'){
			redirect('admin_page/index');
		}
    
		//load view edit admin ...
		$content = $this->load->view('admin/page/edit', $data, true);
		
    array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
		$this->render($content);
	}
	
	public function saveEdit($id_page=''){
		//ambil data page yang akan diedit.
		$data['page'] = $this->mdl_page->getPageByID($id_page);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['page'])) || count($data['page']) < 1){
			redirect('admin_page/index');
		}
    
    //cek status updatable dan superadmin..
		if(isset($data['page'][0]->updatable) && $data['page'][0]->updatable == 0 && $this->session->userdata('admin_level') != '1'){
			redirect('admin_page/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('page_title','', 'htmlentities|strip_tags|trim|required|max_length[1000]|xss_clean');
    $this->form_validation->set_rules('page_status','', 'htmlentities|strip_tags|required|trim|xss_clean|integer');
    $this->form_validation->set_rules('page_content','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('updatable','', 'htmlentities|strip_tags|required|trim|xss_clean|integer');
		$this->form_validation->set_rules('deletable','', 'htmlentities|strip_tags|required|trim|xss_clean|integer');
		$this->form_validation->set_rules('file_header','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_page/edit/' . $id_page);
		}
		else{
      //upload page picture
			$page_header = '';
			$page_header_thumb = '';
			if (!empty($_FILES['file_header']['name'])){
				$config = $this->pagePicUploadConfig($_FILES['file_header']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('file_header')){
					$upload_data = array('upload_data' => $this->upload->data());
					$page_header = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->header_width, $this->header_height, true);
          $page_header_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'],$this->header_thumb_width,$this->header_thumb_height);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_page/add');
				}
			}
			
			// update data admin ke database..
			$update_data = array(
				"page_title" => $this->input->post('page_title'),
				"page_status" => $this->input->post('page_status'),
				"updatable" => $this->input->post('updatable'),
				"deletable" => $this->input->post('deletable'),
				"page_content" => str_replace(base_url() . "asset/page_file/", "##BASE_URL##",$this->input->post('page_content')),
			);
			if(strlen(trim($page_header)) > 0 && strlen(trim($page_header_thumb)) > 0){
				$update_data['page_header'] = $page_header;
				$update_data['page_header_thumb'] = $page_header_thumb;
			}
			$this->mdl_page->updatePage($update_data, $id_page);
      
      $message =  $this->global_lib->generateMessage("Page has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_page/edit/' . $id_page);
		}
	}
  
	public function delete($id_page=''){
		//ambil data page yang akan diedit.
		$data = $this->mdl_page->getPageByID($id_page);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_page/index');
		}
    
    //cek apakah page deletable atau tidak.
		if($data[0]->deletable != '1' && $this->session->userdata('admin_level') != '1'){
      $message =  $this->global_lib->generateMessage("You can't delete this page. Contact your super adminintrator.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_page/index');
		}
    
		if(isset($data[0]->page_header) && strlen(trim($data[0]->page_header)) > 0){
			@unlink('assets/page/' . $data[0]->page_header);
		}
		
    $this->mdl_page->deletePage($id_page);
		
    $message =  $this->global_lib->generateMessage("Page has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_page/index');
	}
  
	public function deletePic($id_page=''){
		//ambil data admin yang akan diedit.
		$data = $this->mdl_page->getPageByID($id_page);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_page/index');
		}
		
		//jika tidak ada redirect ke halaman edit
		if((! isset($data[0]->page_header)) || strlen(trim($data[0]->page_header)) == 0){
			redirect('admin_page/edit/' . $id_page);
		}
		else{
			$update_data = array(
				'page_header' => "",
				'page_header_thumb' => ""
			);
			$this->mdl_page->updatePage($update_data, $id_page);
			
			//hapus file.
			@unlink('assets/page/' . $data[0]->page_header);
			@unlink('assets/page/thumb/' . $data[0]->page_header_thumb);
			redirect("admin_page/edit/" . $id_page);
		}
	}
	
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('page_status','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_page/index');
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
				'page_status' => $this->input->post('page_status'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_page', $search_param);
			
			redirect('admin_page/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_page');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('page_title', 'page_content');
    $sort_by_list = array(
			'default' => 'page_title ASC',
			'newest' => 'id_page DESC',
			'oldest' => 'id_page ASC',
			'title_asc' => 'page_title ASC',
			'title_desc' => 'page_title DESC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_page/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_page/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_page/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_page/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_page->getSearchResultCount($search_param);
    $config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
    
		$data['page'] = $this->mdl_page->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
		//load view search result..
		$content = $this->load->view('admin/page/all', $data, true);
		
		$this->render($content);
	}
	
  private function render($content = null){
    if(isset($content) && $content !== null){
      //load page view
      $data['content'] = $content;
      
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
  
  private function pagePicUploadConfig($filename=''){
		$config['upload_path'] = './assets/page/';
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
		$config['base_url'] 		= base_url().'admin_page/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_page/search/';
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
			'page_status' => 'all',
			'search_collapsed' => '1',
		);
		$this->session->set_userdata('search_page', $search_param);
	}
}
