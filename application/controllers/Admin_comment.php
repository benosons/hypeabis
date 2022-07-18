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

class Admin_comment extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_comment';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_content');
		$this->load->model('mdl_comment');
    
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
		$data['total_row'] = $this->mdl_comment->getAllCommentCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['comment'] = $this->mdl_comment->getAllCommentLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all module
		$content = $this->load->view('admin/comment/all', $data, true);
		$this->render($content);
	}
  
  public function approve($id_comment){
    //ambil data comment yang akan diedit.
		$data['comment'] = $this->mdl_comment->getCommentByID($id_comment);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['comment'])) || count($data['comment']) < 1){
			redirect('admin_comment/index');
		}
    
    $update_data = array(
      'comment_status' => 1
    );
    $this->mdl_comment->updateComment($update_data, $id_comment);
    
    //tambahkan poin jika baru dipublish
    if($data['comment'][0]->comment_status == 0){
      $this->load->library('point_lib');
      //tambah poin..
      $point_config = array(
        'trigger_type' => 'add_comment',
        'id_user' => $data['comment'][0]->id_user,
        'desc' => $data['comment'][0]->title
      );
      $this->point_lib->addPoint($point_config);
      
      //tambah counter komentar.
      $update_data = array(
        'comment_count' => ($data['comment'][0]->comment_count + 1),
        'last_comment' => date('Y-m-d H:i:s')
      );
      $this->mdl_content->updateContent($update_data, $data['comment'][0]->id_content);
    }
    
    $message =  $this->global_lib->generateMessage("Komentar berhasil di publish.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('admin_comment/index');
  }
  
	public function delete($id_comment=''){
		//ambil data comment yang akan diedit.
		$data['comment'] = $this->mdl_comment->getCommentByID($id_comment);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['comment'])) || count($data['comment']) < 1){
			redirect('admin_comment/index');
		}
		
    $this->mdl_comment->deleteComment($id_comment);
    
    //jika komentar sudah dipublish, kurangi point, jika belum, tidak usah kurangi poin..
    if($data['comment'][0]->comment_status == 1){
      $this->load->library('point_lib');
      //tambah poin..
      $point_config = array(
        'trigger_type' => 'add_comment',
        'id_user' => $data['comment'][0]->id_user,
        'desc' => $data['comment'][0]->title
      );
      $this->point_lib->substractPoint($point_config);
      
      //kurangi counter komentar.
      $update_data = array(
        'comment_count' => ($data['comment'][0]->comment_count - 1),
        'last_comment' => date('Y-m-d H:i:s')
      );
      $this->mdl_content->updateContent($update_data, $data['comment'][0]->id_content);
    }
		
    $message =  $this->global_lib->generateMessage("Comment has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_comment/index');
	}
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('comment_status','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_comment/index');
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
				'comment_status' => $this->input->post('comment_status'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_comment', $search_param);
			
			redirect('admin_comment/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_comment');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('tbl_content_comment.comment', 'tbl_content.title', 'tbl_user.name');
    $sort_by_list = array(
			'default' => 'tbl_content_comment.comment_status ASC, tbl_content_comment.id_content_comment DESC',
			'newest' => 'tbl_content_comment.id_content_comment DESC',
			'oldest' => 'tbl_content_comment.id_content_comment ASC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_comment/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_comment/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_comment/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_comment/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_comment->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
    
		$data['comment'] = $this->mdl_comment->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
		//load view search result..
		$content = $this->load->view('admin/comment/all', $data, true);
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
		$config['base_url'] 		= base_url().'admin_comment/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_comment/search/';
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
			'comment_status' => 'all',
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_comment', $search_param);
	}
}