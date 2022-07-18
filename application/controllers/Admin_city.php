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

class Admin_city extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_city';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_province');
		$this->load->model('mdl_city');
    
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
		$data['total_row'] = $this->mdl_city->getAllCityCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['city'] = $this->mdl_city->getAllCityLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
    //ambil semua province untuk dropdown parent di search dan form add..
		$data['province'] = $this->mdl_province->getAllProvince();
    
		//load view all module
		$content = $this->load->view('admin/city/all', $data, true);
		
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    //ambil semua province untuk dropdown parent di search dan form add..
		$data['province'] = $this->mdl_province->getAllProvince();
    
		//load view add admin ...
		$content = $this->load->view('admin/city/add', $data, true);
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('province','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		$this->form_validation->set_rules('city_name','', 'htmlentities|strip_tags|trim|required|max_length[300]|xss_clean');
		$this->form_validation->set_rules('city_type','', 'htmlentities|strip_tags|trim|required|max_length[300]|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_city/add');
		}
		else{
			//insert data ke database..
			$insert_data = array(
        'id_province' => $this->input->post('province'),
				'city_name' => $this->input->post('city_name'),
				'city_type' => $this->input->post('city_type'),
				'city_code' => strtolower($this->global_lib->cleanString($this->input->post('city_name')))
			);
			$this->mdl_city->insertCity($insert_data);
      
      $message =  $this->global_lib->generateMessage("New city has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_city/index');
		}
	}
	
	public function edit($id_city=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data city yang akan diedit.
		$data['city'] = $this->mdl_city->getCityByID($id_city);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['city'])) || count($data['city']) < 1){
			redirect('admin_city/index');
		}
    
    //ambil semua province untuk dropdown parent di search dan form add..
		$data['province'] = $this->mdl_province->getAllProvince();
    
		//load view edit admin ...
		$content = $this->load->view('admin/city/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_city=''){
		//ambil data city yang akan diedit.
		$data['city'] = $this->mdl_city->getCityByID($id_city);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['city'])) || count($data['city']) < 1){
			redirect('admin_city/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('province','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		$this->form_validation->set_rules('city_name','', 'htmlentities|strip_tags|trim|required|max_length[300]|xss_clean');
		$this->form_validation->set_rules('city_type','', 'htmlentities|strip_tags|trim|required|max_length[300]|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_city/edit/' . $id_city);
		}
		else{
			// update data admin ke database..
			$update_data = array(
				'id_province' => $this->input->post('province'),
				'city_name' => $this->input->post('city_name'),
				'city_type' => $this->input->post('city_type'),
				'city_code' => strtolower($this->global_lib->cleanString($this->input->post('city_name')))
			);
			$this->mdl_city->updateCity($update_data, $id_city);
      
      $message =  $this->global_lib->generateMessage("City has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_city/edit/' . $id_city);
		}
	}
  
	public function delete($id_city=''){
		//ambil data city yang akan diedit.
		$data = $this->mdl_city->getCityByID($id_city);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_city/index');
		}
    
    // $this->mdl_city->deleteCity($id_city);
    $this->mdl_city->updateCity(array('deleted' => 1), $id_city);
		
    $message =  $this->global_lib->generateMessage("City has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_city/index');
	}
  
  public function syncRajaongkir(){
    //clear search session yang lama..
		$this->clearSearchSession();
		
    //ambil semua province untuk dropdown parent di search dan form add..
		$data['province'] = $this->mdl_province->getAllProvince();
    
		//load view
    $content = $this->load->view('admin/city/sync_rajaongkir', $data, true);
		$this->render($content);
  }
  
  public function submitProvinceSyncRajaongkir(){
    //validasi id province yg dipilih..
		$this->form_validation->set_rules('province','', 'htmlentities|strip_tags|trim|xss_clean|required');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage("Choose province.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_city/syncRajaongkir');
		}
		else{
			$id_province = $this->input->post('province');
			redirect('admin_city/startSyncRajaongkir/' . $id_province);
		}
  }
  
  public function startSyncRajaongkir($id_province = ''){
    //ambil semua provinsi
		$data['province'] = $this->mdl_province->getAllProvince();
    
    $data['province_data'] = array();
		if(isset($id_province)){
			$province_data = $this->mdl_province->getProvinceByID($id_province);
			
			//cek province bedasarkan id..
			if($province_data[0]->id_province <= 0){
        $message =  $this->global_lib->generateMessage("Province not found. Please choose province.", "danger");
				$this->session->set_flashdata('message', $message);
				redirect('admin_city/syncRajaongkir');
			}
			
			//cek apakah ada id rajaongkir (harus sudah syncronize province sebelumnya).
			if($province_data[0]->id_province_rajaongkir <= 0){
        $message =  $this->global_lib->generateMessage("Province not syncronize with rajaongkir.com yet. Please Syncronize province first.", "danger");
				$this->session->set_flashdata('message', $message);
				redirect('admin_city/syncRajaongkir');
			}
      
      $data['province_data'] = $province_data;
		}
    else{
      $message = $this->global_lib->generateMessage("Please choose province.", "danger");
      $this->session->set_flashdata('message', $message);
      redirect('admin_city/syncRajaongkir');
    }
		
    //ambil city bedasarkan id province yg dipilih..
    $data['city'] = $this->mdl_city->getCityByIDProvince($id_province);
    
    //declare format city..
    $city_all = array();
    
    //ambil data city di db local by id province..
    $city_local = $this->mdl_city->getCityByIDProvince($id_province);
    foreach($city_local as $ct){
      $city_code = $ct->city_code;
      $city_all[$city_code] = array(
        'local_id_city' => $ct->id_city,
        'local_id_city_rajaongkir' => $ct->id_city_rajaongkir,
        'local_city_type' => $ct->city_type,
        'local_city_name' => $ct->city_name,
        'local_city_code' => $ct->city_code,
        'ro_id_city' => '',
        'ro_city_type' => '',
        'ro_city_name' => '',
        'ro_city_code' => '',
        'match_status' => '',
        'match_status_str' => ''
      );
    }
    
    //ambil data city di rajaongkir..
    $this->load->library('rajaongkir');
    $id_province_rajaongkir = $province_data[0]->id_province_rajaongkir;
		$rajaongkirData = json_decode($this->rajaongkir->city($province_data[0]->id_province_rajaongkir));
    if(isset($rajaongkirData->rajaongkir->status->code) && $rajaongkirData->rajaongkir->status->code == '200'){
      $city_ro = $rajaongkirData->rajaongkir->results;
      foreach($city_ro as $ct){
        $city_code = strtolower($this->global_lib->cleanString($ct->city_name));
        if(isset($city_all[$city_code]) && is_array($city_all[$city_code])){
          $city_all[$city_code]['ro_id_city'] = $ct->city_id;
          $city_all[$city_code]['ro_city_type'] = $ct->type;
          $city_all[$city_code]['ro_city_name'] = $ct->city_name;
          $city_all[$city_code]['ro_city_code'] = $city_code;
        }
        else{
          $city_all[$city_code] = array(
            'local_id_city' => '',
            'local_id_city_rajaongkir' => '',
            'local_city_type' => '',
            'local_city_name' => '',
            'local_city_code' => '',
            'ro_id_city' => $ct->city_id,
            'ro_city_type' => $ct->type,
            'ro_city_name' => $ct->city_name,
            'ro_city_code' => $city_code,
            'match_status' => '',
            'match_status_str' => ''
          );
        }
      }
    }
    
    //proses match status
    foreach($city_all as $index => $ct){
      if($ct['local_id_city_rajaongkir'] == $ct['ro_id_city']){
        if($ct['local_city_type'] == $ct['ro_city_type'] && $ct['local_city_name'] == $ct['ro_city_name'] && $ct['local_city_code'] == $ct['ro_city_code']){
          $city_all[$index]['match_status'] = "match";
          $city_all[$index]['match_status_str'] = "<span class='label label-info'>Match</span>";
        }
        else{
          $city_all[$index]['match_status'] = "suggested";
          $city_all[$index]['match_status_str'] = "<span class='label label-info'>Need Update</span>";
        }
      }
      else{
        $city_all[$index]['match_status'] = 'nomatch';
        $city_all[$index]['match_status_str'] = "<span class='label label-danger'>No Match</span>";
      }
    }
    
    $data['city_match'] = $city_all;
    
    $content = $this->load->view('admin/city/sync_rajaongkir_form', $data, true);
		$this->render($content);
  }
  
  public function submitSyncRajaongkir($id_province = ''){
    //ambil semua provinsi
		$sync_data['province'] = $this->mdl_province->getAllProvince();
    
    $sync_data['province_data'] = array();
		if(isset($id_province)){
			$province_data = $this->mdl_province->getProvinceByID($id_province);
			
			//cek province bedasarkan id..
			if($province_data[0]->id_province <= 0){
        $message = $this->global_lib->generateMessage("Province not found. Please choose province.", "danger");
				$this->session->set_flashdata('message', $message);
				redirect('admin_city/syncRajaongkir');
			}
			
			//cek apakah ada id rajaongkir (harus sudah syncronize province sebelumnya).
			if($province_data[0]->id_province_rajaongkir <= 0){
        $message = $this->global_lib->generateMessage("Province not syncronize with rajaongkir.com yet. Please Syncronize province first.", "danger");
				$this->session->set_flashdata('message', $message);
				redirect('admin_city/syncRajaongkir');
			}
      
      $sync_data['province_data'] = $province_data;
		}
    else{
      $message = $this->global_lib->generateMessage("Please choose province.", "danger");
      $this->session->set_flashdata('message', $message);
      redirect('admin_city/syncRajaongkir');
    }
		
    //ambil city bedasarkan id province yg dipilih..
    $sync_data['city'] = $this->mdl_city->getCityByIDProvince($id_province);
    
    //declare format city..
    $city_all = array();
    
    //ambil data city di db local by id province..
    $city_local = $this->mdl_city->getCityByIDProvince($id_province);
    foreach($city_local as $ct){
      $city_code = $ct->city_code;
      $city_all[$city_code] = array(
        'local_id_city' => $ct->id_city,
        'local_id_city_rajaongkir' => $ct->id_city_rajaongkir,
        'local_city_type' => $ct->city_type,
        'local_city_name' => $ct->city_name,
        'local_city_code' => $ct->city_code,
        'ro_id_city' => '',
        'ro_city_type' => '',
        'ro_city_name' => '',
        'ro_city_code' => '',
        'match_status' => '',
        'match_status_str' => ''
      );
    }
    
    //ambil data city di rajaongkir..
    $this->load->library('rajaongkir');
    $id_province_rajaongkir = $province_data[0]->id_province_rajaongkir;
		$rajaongkirData = json_decode($this->rajaongkir->city($province_data[0]->id_province_rajaongkir));
    if(isset($rajaongkirData->rajaongkir->status->code) && $rajaongkirData->rajaongkir->status->code == '200'){
      $city_ro = $rajaongkirData->rajaongkir->results;
      foreach($city_ro as $ct){
        $city_code = strtolower($this->global_lib->cleanString($ct->city_name));
        if(isset($city_all[$city_code]) && is_array($city_all[$city_code])){
          $city_all[$city_code]['ro_id_city'] = $ct->city_id;
          $city_all[$city_code]['ro_city_type'] = $ct->type;
          $city_all[$city_code]['ro_city_name'] = $ct->city_name;
          $city_all[$city_code]['ro_city_code'] = $city_code;
        }
        else{
          $city_all[$city_code] = array(
            'local_id_city' => '',
            'local_id_city_rajaongkir' => '',
            'local_city_type' => '',
            'local_city_name' => '',
            'local_city_code' => '',
            'ro_id_city' => $ct->city_id,
            'ro_city_type' => $ct->type,
            'ro_city_name' => $ct->city_name,
            'ro_city_code' => $city_code,
            'match_status' => '',
            'match_status_str' => ''
          );
        }
      }
    }
    
    //validasi form sync
    foreach($city_all as $index => $ct){
      if(isset($ct['ro_id_city']) && $ct['ro_id_city'] > 0){
        $field_name = 'city_sync_'.$ct['ro_id_city'];
      }
      else{
        $field_name = 'local_city_sync_'.$ct['local_id_city'];
      }
			$this->form_validation->set_rules($field_name,'', 'htmlentities|strip_tags|trim|xss_clean');
    }
    if($this->form_validation->run() == false){
      $message = $this->global_lib->generateMessage("Failed to syncronize. form invalid.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_city/startSyncRajaongkir/' . $id_province);
		}
		else{
      foreach($city_all as $index => $ct){
        if(isset($ct['ro_id_city']) && $ct['ro_id_city'] > 0){
          $action = $this->input->post('city_sync_'.$ct['ro_id_city']);
        }
        else{
          $action = $this->input->post('local_city_sync_'.$ct['local_id_city']);
        }
        
        //jika action yg dipilih adalah insert baru..
        if($action == 'insert'){
          //ambil id province bedasarkan id rajaongkir..
          $insert_data = array(
            'id_city_rajaongkir' => $ct['ro_id_city'],
            'id_province' => $id_province,
            'city_type' => $ct['ro_city_type'],
            'city_name' => $ct['ro_city_name'],
            'city_code' => strtolower($this->global_lib->cleanString($ct['ro_city_name']))
          );
          $this->mdl_city->insertCity($insert_data);
        }
        else if(strpos($action, 'delete') !== false){ //delete
          $this->mdl_city->deleteCity(str_replace('delete_', '', $action));
        }
        else if($action > 0){ //update
          $cek = $this->mdl_city->checkCityByID($action);
          if($cek){
            $update_data = array(
              'id_city_rajaongkir' => $ct['ro_id_city'],
              'city_type' => $ct['ro_city_type'],
              'city_name' => $ct['ro_city_name'],
              'city_code' => strtolower($this->global_lib->cleanString($ct['ro_city_name']))
            );
            $this->mdl_city->updateCity($update_data, $action);
          }
          else{
            $insert_data = array(
              'id_city_rajaongkir' => $ct['ro_id_city'],
              'id_province' => $id_province,
              'city_type' => $ct['ro_city_type'],
              'city_name' => $ct['ro_city_name'],
              'city_code' => strtolower($this->global_lib->cleanString($ct['ro_city_name']))
            );
            $this->mdl_city->insertCity($insert_data);
          }
        }
        else{}
      }
      
      $message = $this->global_lib->generateMessage("Syncronization process has been done.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_city/startSyncRajaongkir/' . $id_province);
    }
  }
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('province','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('city_type','', 'htmlentities|strip_tags|trim|required|xss_clean');
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_city/index');
		}
		else{
			//clear search session yang lama..
			$this->clearSearchSession();
		
			//ambil data input dan restore ke session sebagai parameter search..
			$search_param = array(
        'city_type' => $this->input->post('city_type'),
				'province' => $this->input->post('province'),
				'search_by' => $this->input->post('search_by'),
				'operator' => html_entity_decode($this->input->post('operator')),
				'keyword' => $this->input->post('keyword'),
				'per_page' => $this->input->post('per_page'),
				'sort_by' => $this->input->post('sort_by'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_city', $search_param);
			
			redirect('admin_city/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_city');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('city_name');
    $sort_by_list = array(
			'default' => 'id_city DESC',
			'newest' => 'id_city DESC',
			'oldest' => 'id_city ASC',
			'name_asc' => 'city_name ASC',
			'name_desc' => 'city_name DESC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_city/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_city/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_city/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_city/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_city->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
    
		$data['city'] = $this->mdl_city->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
    //ambil semua province untuk dropdown parent di search..
		$data['province'] = $this->mdl_province->getAllProvince();
    
		//load view search result..
		$content = $this->load->view('admin/city/all', $data, true);
		
		$this->render($content);
	}
	
  public function getCityByIDProvince(){
    //cek apakah ajax request atau bukan..
		if(! $this->input->is_ajax_request()) redirect('admin_city/index');
		
		$data['status'] = '';
		
		//ambil dan validasi id_province..
		$this->form_validation->set_rules('id_province','', 'htmlentities|strip_tags|required|trim|xss_clean');
		if($this->form_validation->run() == false){
			$data['status'] = 'failed';
		}
		else{
			$id_province = $this->input->post('id_province');
			
			//cek id_province..
			$cek = $this->mdl_province->checkProvinceByID($id_province);
			if($cek){
				//ambil data city bedasarkan id province..
				$data['status'] = 'success';
				$data['city_data'] = $this->mdl_city->getCityByIDProvinceInArray($id_province);
			}
			else{
				$data['status'] = 'failed';
			}
		}
		
		echo json_encode($data);
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
		$config['base_url'] 		= base_url().'admin_city/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_city/search/';
		$config['total_rows'] 		= $total_row;
		$config['per_page'] 		= ($per_page > 0 ? $per_page : $this->pagination_per_page);
		$config['uri_segment']		= 3;
		return $config;
	}
  
	private function clearSearchSession(){
		//declare session search..
		$search_param = array(
      'province' => 'all',
			'city_type' => 'all',
			'search_by' => 'default',
			'operator' => null,
			'keyword' => null,
			'sort_by' => 'default',
			'per_page' => $this->pagination_per_page,
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_city', $search_param);
	}
}