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

class Admin_contact extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_contact';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_contact');
    
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
		$data['total_row'] = $this->mdl_contact->getAllContactCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['contact'] = $this->mdl_contact->getAllContactLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all module
		$content = $this->load->view('admin/contact/all', $data, true);
		
		$this->render($content);
	}
	
	public function detail($id_contact=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data contact yang akan diedit.
		$data['contact'] = $this->mdl_contact->getContactByID($id_contact);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['contact'])) || count($data['contact']) < 1){
			redirect('admin_contact/index');
		}
    
    // update data category ke database..
    $update_data = array(
      "read_status" => 1
    );
    $this->mdl_contact->updateContact($update_data, $id_contact);
    
		//load view edit admin ...
		$content = $this->load->view('admin/contact/detail', $data, true);
		$this->render($content);
	}
	
	public function delete($id_contact=''){
		//ambil data contact yang akan diedit.
		$data = $this->mdl_contact->getContactByID($id_contact);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_contact/index');
		}
    
    $this->mdl_contact->deleteContact($id_contact);
		
    $message =  $this->global_lib->generateMessage("Contact has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_contact/index');
	}
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('read_status','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('start_date','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('finish_date','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_contact/index');
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
				'read_status' => $this->input->post('read_status'),
        'start_date' => $this->input->post('start_date'),
				'finish_date' => $this->input->post('finish_date'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_contact', $search_param);
			
			redirect('admin_contact/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_contact');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('name', 'email', 'contact_number');
    $sort_by_list = array(
			'default' => 'id_contact DESC',
			'newest' => 'id_contact DESC',
			'oldest' => 'id_contact ASC',
			'name_asc' => 'name ASC',
			'name_desc' => 'name DESC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_contact/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_contact/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_contact/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_contact/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_contact->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
    
		$data['contact'] = $this->mdl_contact->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
		//load view search result..
		$content = $this->load->view('admin/contact/all', $data, true);
		
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
		$config['base_url'] 		= base_url().'admin_contact/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_contact/search/';
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
			'read_status' => 'all',
      'start_date' => null,
			'finish_date' => null,
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_contact', $search_param);
	}
}