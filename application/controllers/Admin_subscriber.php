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

class Admin_subscriber extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_subscriber';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_subscriber');
    
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
		$data['total_row'] = $this->mdl_subscriber->getAllSubscriberCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['subscriber'] = $this->mdl_subscriber->getAllSubscriberLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all module
		$content = $this->load->view('admin/subscriber/all', $data, true);
		
		$this->render($content);
	}
	
	public function delete($id_subscriber=''){
		//ambil data subscriber yang akan diedit.
		$data = $this->mdl_subscriber->getSubscriberByID($id_subscriber);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_subscriber/index');
		}
    
		if(isset($data[0]->subscriber_pic) && strlen(trim($data[0]->subscriber_pic)) > 0){
			@unlink('assets/subscriber/'.$data[0]->subscriber_pic);
		}
    if(isset($data[0]->subscriber_pic2) && strlen(trim($data[0]->subscriber_pic2)) > 0){
			@unlink('assets/subscriber/'.$data[0]->subscriber_pic2);
		}
    if(isset($data[0]->subscriber_pic2_thumb) && strlen(trim($data[0]->subscriber_pic2_thumb)) > 0){
			@unlink('assets/subscriber/thumb/'.$data[0]->subscriber_pic2_thumb);
		}
		
    $this->mdl_subscriber->deleteSubscriber($id_subscriber);
		
    $message =  $this->global_lib->generateMessage("Subscriber has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_subscriber/index');
	}
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('start_date','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('finish_date','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_subscriber/index');
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
				'start_date' => $this->input->post('start_date'),
				'finish_date' => $this->input->post('finish_date'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_subscriber', $search_param);
			
			redirect('admin_subscriber/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_subscriber');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('email');
    $sort_by_list = array(
			'default' => 'tbl_subscriber.id_subscriber DESC',
			'newest' => 'tbl_subscriber.id_subscriber DESC',
			'oldest' => 'tbl_subscriber.id_subscriber ASC',
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_subscriber/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_subscriber/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_subscriber/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_subscriber/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_subscriber->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
    
		$data['subscriber'] = $this->mdl_subscriber->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
		//load view search result..
		$content = $this->load->view('admin/subscriber/all', $data, true);
		
		$this->render($content);
	}
	
  public function export(){
    //clear search session yang lama..
		$this->clearSearchSession();
    
    //load view all module
		$content = $this->load->view('admin/subscriber/export', null, true);
		
		$this->render($content);
  }
  
  public function submitExport(){
    //validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('start_date','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('finish_date','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_subscriber/export');
		}
		else{
      //ambil data input dan restore ke session sebagai parameter search..
			$search_param = array(
				'search_by' => $this->input->post('search_by'),
				'operator' => html_entity_decode($this->input->post('operator')),
				'keyword' => $this->input->post('keyword'),
				'sort_by' => $this->input->post('sort_by'),
				'start_date' => $this->input->post('start_date'),
				'finish_date' => $this->input->post('finish_date'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
      
      $sort_by_list = array(
        'default' => 'tbl_subscriber.id_subscriber DESC',
        'newest' => 'tbl_subscriber.id_subscriber DESC',
        'oldest' => 'tbl_subscriber.id_subscriber ASC',
      );
      $search_param['sort_by'] = $sort_by_list[$search_param['sort_by']];
      
      $this->load->library("excel");
      $this->excel->setActiveSheetIndex(0);
      $data = $this->mdl_subscriber->getSearchResultForExport($search_param);

      $now = date("YmdHis");
      $this->excel->stream('data_subscriber_' . $now . '.xls', $data);
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
		$config['base_url'] 		= base_url().'admin_subscriber/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_subscriber/search/';
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
      'start_date' => null,
			'finish_date' => null,
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_subscriber', $search_param);
	}
}
