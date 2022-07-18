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

class Admin_province extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_province';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_province');
    
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
		$data['total_row'] = $this->mdl_province->getAllProvinceCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['province'] = $this->mdl_province->getAllProvinceLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all module
		$content = $this->load->view('admin/province/all', $data, true);
		
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    $data = array();
    
		//load view add admin ...
		$content = $this->load->view('admin/province/add', $data, true);
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('province_name','', 'htmlentities|strip_tags|trim|xss_clean|required');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_province/add');
		}
		else{
			//insert data ke database..
			$insert_data = array(
        "province_name" => $this->input->post('province_name'),
				"province_code" => strtolower($this->global_lib->cleanString($this->input->post('province_name'))),
			);
			$this->mdl_province->insertProvince($insert_data);
      
      $message =  $this->global_lib->generateMessage("New province has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_province/index');
		}
	}
	
	public function edit($id_province=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data province yang akan diedit.
		$data['province'] = $this->mdl_province->getProvinceByID($id_province);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['province'])) || count($data['province']) < 1){
			redirect('admin_province/index');
		}
    
		//load view edit admin ...
		$content = $this->load->view('admin/province/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_province=''){
		//ambil data province yang akan diedit.
		$data['province'] = $this->mdl_province->getProvinceByID($id_province);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['province'])) || count($data['province']) < 1){
			redirect('admin_province/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('province_name','', 'htmlentities|strip_tags|trim|xss_clean|required');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_province/edit/' . $id_province);
		}
		else{
			// update data admin ke database..
			$update_data = array(
				"province_name" => $this->input->post('province_name'),
				"province_code" => strtolower($this->global_lib->cleanString($this->input->post('province_name')))
			);
			$this->mdl_province->updateProvince($update_data, $id_province);
      
      $message =  $this->global_lib->generateMessage("Province has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_province/edit/' . $id_province);
		}
	}
  
	public function delete($id_province=''){
		//ambil data province yang akan diedit.
		$data = $this->mdl_province->getProvinceByID($id_province);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_province/index');
		}
    
    // $this->mdl_province->deleteProvince($id_province);
    $this->mdl_province->updateProvince(array('deleted' => 1), $id_province);
		
    $message =  $this->global_lib->generateMessage("Province has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_province/index');
	}
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_province/index');
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
			$this->session->set_userdata('search_province', $search_param);
			
			redirect('admin_province/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_province');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('province_name');
    $sort_by_list = array(
			'default' => 'province_name ASC',
			'newest' => 'id_province DESC',
			'oldest' => 'id_province ASC',
			'name_asc' => 'province_name ASC',
			'name_desc' => 'province_name DESC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_province/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_province/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_province/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_province/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_province->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
    
		$data['province'] = $this->mdl_province->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
		//load view search result..
		$content = $this->load->view('admin/province/all', $data, true);
		
		$this->render($content);
	}
	
  public function syncRajaongkir(){
    //clear search session yang lama..
		$this->clearSearchSession();
		
    //load view add admin ...
		$content = $this->load->view('admin/province/sync_rajaongkir', null, true);
		$this->render($content);
  }
  
  public function startSyncRajaongkir(){
    $province_all = array();
    $data['province'] = $this->mdl_province->getAllProvince();
    
    //ambil data province local..
    foreach($data['province'] as $prov){
      $province_all[$prov->province_code] = array(
        'local_id_province' => $prov->id_province,
        'local_id_province_rajaongkir' => $prov->id_province_rajaongkir,
        'local_province_name' => $prov->province_name,
        'local_province_code' => $prov->province_code,
        'ro_id_province' => '',
        'ro_province_name' => '',
        'ro_province_code' => '',
        'match_status' => '',
        'match_status_str' => ''
      );
    }
    
    //ambil province rajaongkir..
    $this->load->library('rajaongkir');
    $rajaongkir_data = json_decode($this->rajaongkir->province());
		$data['rajaongkir_province'] = $rajaongkir_data->rajaongkir->results;
		if(isset($rajaongkir_data->rajaongkir->status->code) && $rajaongkir_data->rajaongkir->status->code == '200'){
      $province_ro = $rajaongkir_data->rajaongkir->results;
      foreach($province_ro as $prov){
        $province_code = strtolower($this->global_lib->cleanString($prov->province));
        if(isset($province_all[$province_code]) && is_array($province_all[$province_code])){
          $province_all[$province_code]['ro_id_province'] = $prov->province_id;
          $province_all[$province_code]['ro_province_name'] = $prov->province;
          $province_all[$province_code]['ro_province_code'] = strtolower($this->global_lib->cleanString($prov->province));
        }
        else{
          $province_all[$province_code] = array(
            'local_id_province' => '',
            'local_id_province_rajaongkir' => '',
            'local_province_name' => '',
            'local_province_code' => '',
            'ro_id_province' => $prov->province_id,
            'ro_province_name' => $prov->province,
            'ro_province_code' => strtolower($this->global_lib->cleanString($prov->province)),
            'match_status' => '',
            'match_status_str' => ''
          );
        }
      }
    }
    
    //proses match status
    foreach($province_all as $index => $prov){
      if($prov['local_id_province_rajaongkir'] == $prov['ro_id_province']){
        if($prov['local_province_name'] == $prov['ro_province_name'] && $prov['local_province_code'] == $prov['ro_province_code']){
          $province_all[$index]['match_status'] = 'match';
          $province_all[$index]['match_status_str'] = "<span class='label label-info'>Match</span>";
        }
        else{
          $province_all[$index]['match_status'] = 'suggested';
          $province_all[$index]['match_status_str'] = "<span class='label label-info'>Need Update</span>";
        }
      }
      else{
        $province_all[$index]['match_status'] = 'nomatch';
        $province_all[$index]['match_status_str'] = "<span class='label label-danger'>No Match</span>";
      }
    }
    
    $data['province_all'] = $province_all;
  
    //load view add province ...
    $content = $this->load->view('admin/province/sync_rajaongkir_form', $data, true);
		$this->render($content);
  }
  
  public function submitSyncRajaongkir(){
    $province_all = array();
    $data['province'] = $this->mdl_province->getAllProvince();
    
    //ambil data province local..
    foreach($data['province'] as $prov){
      $province_all[$prov->province_code] = array(
        'local_id_province' => $prov->id_province,
        'local_id_province_rajaongkir' => $prov->id_province_rajaongkir,
        'local_province_name' => $prov->province_name,
        'local_province_code' => $prov->province_code,
        'ro_id_province' => '',
        'ro_province_name' => '',
        'ro_province_code' => '',
        'match_status' => '',
        'match_status_str' => ''
      );
    }
    
    //ambil province rajaongkir..
    $this->load->library('rajaongkir');
    $rajaongkir_data = json_decode($this->rajaongkir->province());
		$data['rajaongkir_province'] = $rajaongkir_data->rajaongkir->results;
		if(isset($rajaongkir_data->rajaongkir->status->code) && $rajaongkir_data->rajaongkir->status->code == '200'){
      $province_ro = $rajaongkir_data->rajaongkir->results;
      foreach($province_ro as $prov){
        $province_code = strtolower($this->global_lib->cleanString($prov->province));
        if(isset($province_all[$province_code]) && is_array($province_all[$province_code])){
          $province_all[$province_code]['ro_id_province'] = $prov->province_id;
          $province_all[$province_code]['ro_province_name'] = $prov->province;
          $province_all[$province_code]['ro_province_code'] = strtolower($this->global_lib->cleanString($prov->province));
        }
        else{
          $province_all[$province_code] = array(
            'local_id_province' => '',
            'local_id_province_rajaongkir' => '',
            'local_province_name' => '',
            'local_province_code' => '',
            'ro_id_province' => $prov->province_id,
            'ro_province_name' => $prov->province,
            'ro_province_code' => strtolower($this->global_lib->cleanString($prov->province)),
            'match_status' => '',
            'match_status_str' => ''
          );
        }
      }
    }
    
    //validasi form sync
    foreach($province_all as $index => $prov){
      if(isset($prov['ro_id_province']) && $prov['ro_id_province'] > 0){
        $field_name = 'province_sync_'.$prov['ro_id_province'];
      }
      else{
        $field_name = 'local_province_sync_'.$prov['local_id_province'];
      }
			$this->form_validation->set_rules($field_name,'', 'htmlentities|strip_tags|trim|xss_clean');
    }
    if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage("Failed to syncronize. form invalid.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_province/startSyncRajaongkir');
		}
		else{
      foreach($province_all as $index => $prov){
        if(isset($prov['ro_id_province']) && $prov['ro_id_province'] > 0){
          $action = $this->input->post('province_sync_'.$prov['ro_id_province']);
        }
        else{
          $action = $this->input->post('local_province_sync_'.$prov['local_id_province']);
        }
        
        //jika action yg dipilih adalah insert baru..
        if($action == 'insert'){
          //ambil id province bedasarkan id rajaongkir..
          $insert_data = array(
            'id_province_rajaongkir' => $prov['ro_id_province'],
            'province_name' => $prov['ro_province_name'],
            'province_code' => strtolower($this->global_lib->cleanString($prov['ro_province_name']))
          );
          $this->mdl_province->insertProvince($insert_data);
        }
        else if(strpos($action, 'delete') !== false){ //delete
          $this->mdl_province->deleteProvince(str_replace('delete_', '', $action));
        }
        else if($action > 0){ //update
          $cek = $this->mdl_province->checkProvinceByID($action);
          if($cek){
            $update_data = array(
              'id_province_rajaongkir' => $prov['ro_id_province'],
              'province_name' => $prov['ro_province_name'],
              'province_code' => strtolower($this->global_lib->cleanString($prov['ro_province_name']))
            );
            $this->mdl_province->updateProvince($update_data, $action);
          }
          else{
            $insert_data = array(
              'id_province_rajaongkir' => $prov['ro_id_province'],
              'province_name' => $prov['ro_province_name'],
              'province_code' => strtolower($this->global_lib->cleanString($prov['ro_province_name']))
            );
            $this->mdl_province->insertProvince($insert_data);
          }
        }
        else{}
      }
      
      $message =  $this->global_lib->generateMessage("Syncronization process has been done.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_province/startSyncRajaongkir');
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
		$config['base_url'] 		= base_url().'admin_province/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_province/search/';
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
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_province', $search_param);
	}
}