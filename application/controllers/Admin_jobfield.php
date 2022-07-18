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

class Admin_jobfield extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_jobfield';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_jobfield');
    
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
		$data['total_row'] = $this->mdl_jobfield->getAllJobfieldCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['jobfield'] = $this->mdl_jobfield->getAllJobfieldLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all module
		$content = $this->load->view('admin/jobfield/all', $data, true);
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    $data = array();
    
		//load view add admin ...
		$content = $this->load->view('admin/jobfield/add', $data, true);
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('job_field','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('order','', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_jobfield/add');
		}
		else{
			//insert data ke database..
			$insert_data = array(
        "job_field" => $this->input->post('job_field'),
        "order" => $this->input->post('order')
			);
			$this->mdl_jobfield->insertJobfield($insert_data);
      
      $message =  $this->global_lib->generateMessage("Opsi bidang pekerjaan berhasil ditambahkan.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_jobfield/index');
		}
	}
	
	public function edit($id_jobfield=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data jobfield yang akan diedit.
		$data['jobfield'] = $this->mdl_jobfield->getJobfieldByID($id_jobfield);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['jobfield'])) || count($data['jobfield']) < 1){
			redirect('admin_jobfield/index');
		}
    
		//load view edit admin ...
		$content = $this->load->view('admin/jobfield/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_jobfield=''){
		//ambil data jobfield yang akan diedit.
		$data['jobfield'] = $this->mdl_jobfield->getJobfieldByID($id_jobfield);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['jobfield'])) || count($data['jobfield']) < 1){
			redirect('admin_jobfield/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('job_field','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('order','', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_jobfield/edit/' . $id_jobfield);
		}
		else{
			// update data admin ke database..
			$update_data = array(
				"job_field" => $this->input->post('job_field'),
				"order" => $this->input->post('order')
			);
			$this->mdl_jobfield->updateJobfield($update_data, $id_jobfield);
      
      $message =  $this->global_lib->generateMessage("Opsi bidang pekerjaan berhasil di update.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_jobfield/edit/' . $id_jobfield);
		}
	}
  
	public function delete($id_jobfield=''){
		//ambil data jobfield yang akan diedit.
		$data = $this->mdl_jobfield->getJobfieldByID($id_jobfield);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_jobfield/index');
		}
    
    $this->mdl_jobfield->deleteJobfield($id_jobfield);
		
    $message =  $this->global_lib->generateMessage("Opsi bidang pekerjaan berhasil dihapus.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_jobfield/index');
	}
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('jobfield_radio','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('jobfield_select_withsearch','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_jobfield/index');
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
				'jobfield_select_withsearch' => $this->input->post('jobfield_select_withsearch'),
				'jobfield_radio' => $this->input->post('jobfield_radio'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_jobfield', $search_param);
			
			redirect('admin_jobfield/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_jobfield');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('jobfield_text');
    $sort_by_list = array(
			'default' => 'order ASC',
			'newest' => 'id_jobfield DESC',
			'oldest' => 'id_jobfield ASC',
			'name_asc' => 'jobfield_text ASC',
			'name_desc' => 'jobfield_text DESC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_jobfield/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_jobfield/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_jobfield/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_jobfield/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_jobfield->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
    
		$data['jobfield'] = $this->mdl_jobfield->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
		//load view search result..
		$content = $this->load->view('admin/jobfield/all', $data, true);
		
		$this->render($content);
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
		$config['base_url'] 		= base_url().'admin_jobfield/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_jobfield/search/';
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
			'jobfield_select_withsearch' => 'all',
			'jobfield_radio' => 'all',
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_jobfield', $search_param);
	}
}