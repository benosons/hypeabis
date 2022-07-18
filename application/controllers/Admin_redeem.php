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

class Admin_redeem extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_redeem';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
    $this->load->library('point_lib');
		
		//load model..
		$this->load->model('mdl_redeem');
		$this->load->model('mdl_merchandise');
    
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
		$data['total_row'] = $this->mdl_redeem->getAllRedeemCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['redeem'] = $this->mdl_redeem->getAllRedeemLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all module
		$content = $this->load->view('admin/redeem/all', $data, true);
		
		$this->render($content);
	}
	
	public function edit($id_redeem=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data redeem yang akan diedit.
		$data['redeem'] = $this->mdl_redeem->getRedeemByID($id_redeem);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['redeem'])) || count($data['redeem']) < 1){
			redirect('admin_redeem/index');
		}
    
		//load view edit admin ...
		$content = $this->load->view('admin/redeem/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_redeem=''){
		//ambil data redeem yang akan diedit.
		$data['redeem'] = $this->mdl_redeem->getRedeemByID($id_redeem);
		if((! is_array($data['redeem'])) || count($data['redeem']) < 1){
			redirect('admin_redeem/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('redeem_status','', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_redeem/edit/' . $id_redeem);
		}
		else{
      $allow_update = true;
      if($data['redeem'][0]->redeem_status == 2 || $data['redeem'][0]->redeem_status == 3){
        $allow_update = false;
      }
      if(! $allow_update){
        $message =  $this->global_lib->generateMessage("Tidak bisa mengedit status done dan batal.", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('admin_redeem/edit/' . $id_redeem);
      }
      
			// update data admin ke database..
      $status = $this->input->post('redeem_status');
			$update_data = array(
				"redeem_status" => $status
			);
			$this->mdl_redeem->updateRedeem($update_data, $id_redeem);
      
      if(
        ($data['redeem'][0]->redeem_status == 0 && $status == 3) ||
        ($data['redeem'][0]->redeem_status == 1 && $status == 3)
      ){
        //tambah quota merchandise..
        $update_data = array(
          'merch_quota' => ($data['redeem'][0]->merch_quota + 1)
        );
        $this->mdl_merchandise->updateMerchandise($update_data, $data['redeem'][0]->id_merchandise);
        
        //tambah point..
        $this->point_lib->addPointByIDUser($data['redeem'][0]->id_user, $data['redeem'][0]->point);
      }
      
      $message =  $this->global_lib->generateMessage("Redeem request status has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_redeem/index');
		}
	}
  
	public function delete($id_redeem=''){
		//ambil data redeem yang akan diedit.
		$data = $this->mdl_redeem->getRedeemByID($id_redeem);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_redeem/index');
		}
    
		if(isset($data[0]->redeem_pic) && strlen(trim($data[0]->redeem_pic)) > 0){
			@unlink('assets/redeem/'.$data[0]->redeem_pic);
		}
    if(isset($data[0]->redeem_pic2) && strlen(trim($data[0]->redeem_pic2)) > 0){
			@unlink('assets/redeem/'.$data[0]->redeem_pic2);
		}
    if(isset($data[0]->redeem_pic2_thumb) && strlen(trim($data[0]->redeem_pic2_thumb)) > 0){
			@unlink('assets/redeem/thumb/'.$data[0]->redeem_pic2_thumb);
		}
		
    $this->mdl_redeem->deleteRedeem($id_redeem);
		
    $message =  $this->global_lib->generateMessage("Redeem has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_redeem/index');
	}
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('redeem_status','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_redeem/index');
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
				'redeem_status' => $this->input->post('redeem_status'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_redeem', $search_param);
			
			redirect('admin_redeem/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_redeem');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('tbl_user.name', 'tbl_user.email', 'tbl_merchandise.merch_name');
    $sort_by_list = array(
			'default' => 'tbl_merchandise_redeem.redeem_status ASC',
			'newest' => 'tbl_merchandise_redeem.id_merchandise_redeem DESC',
			'oldest' => 'tbl_merchandise_redeem.id_merchandise_redeem ASC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_redeem/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_redeem/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_redeem/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_redeem/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_redeem->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
    
		$data['redeem'] = $this->mdl_redeem->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
		//load view search result..
		$content = $this->load->view('admin/redeem/all', $data, true);
		
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
		$config['base_url'] 		= base_url().'admin_redeem/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_redeem/search/';
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
			'redeem_status' => 'all',
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_redeem', $search_param);
	}
}
