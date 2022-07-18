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

class Admin_subdistrict extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $module_name = 'admin_subdistrict';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_province');
		$this->load->model('mdl_city');
		$this->load->model('mdl_subdistrict');
    
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
		$data['total_row'] = $this->mdl_subdistrict->getAllSubdistrictCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['subdistrict'] = $this->mdl_subdistrict->getAllSubdistrictLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
    //ambil semua province untuk dropdown parent di search dan form add..
		$data['province'] = $this->mdl_province->getAllProvince();
    
		//load view all module
		$content = $this->load->view('admin/subdistrict/all', $data, true);
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    //ambil semua province untuk dropdown parent di search dan form add..
		$data['province'] = $this->mdl_province->getAllProvince();
    
		//load view add admin ...
		$content = $this->load->view('admin/subdistrict/add', $data, true);
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('city','', 'htmlentities|strip_tags|trim|required|integer|xss_clean');
    $this->form_validation->set_rules('subdistrict_name','', 'htmlentities|strip_tags|trim|required|max_length[300]|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_subdistrict/add');
		}
		else{
			//cek id_city..
			$cek = $this->mdl_city->checkCityByID($this->input->post('city'));
			if(! $cek){
				redirect('admin_subdistrict/add');
			}
		
			//insert data ke database..
			$insert_data = array(
				'id_city' => $this->input->post('city'),
				'subdistrict_name' => $this->input->post('subdistrict_name'),
				'subdistrict_code' => strtolower($this->global_lib->cleanString($this->input->post('subdistrict_name'))),
			);
			$this->mdl_subdistrict->insertSubdistrict($insert_data);
      
      $message =  $this->global_lib->generateMessage("New subdistrict has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_subdistrict/index');
		}
	}
	
	public function edit($id_subdistrict=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data subdistrict yang akan diedit.
		$data['subdistrict'] = $this->mdl_subdistrict->getSubdistrictByID($id_subdistrict);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['subdistrict'])) || count($data['subdistrict']) < 1){
			redirect('admin_subdistrict/index');
		}
    
    //ambil semua province untuk dropdown parent di search dan form add..
		$data['province'] = $this->mdl_province->getAllProvince();
    
		//load view edit admin ...
		$content = $this->load->view('admin/subdistrict/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_subdistrict=''){
		//ambil data subdistrict yang akan diedit.
		$data['subdistrict'] = $this->mdl_subdistrict->getSubdistrictByID($id_subdistrict);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['subdistrict'])) || count($data['subdistrict']) < 1){
			redirect('admin_subdistrict/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('city','', 'htmlentities|strip_tags|trim|required|integer|xss_clean');
		$this->form_validation->set_rules('subdistrict_name','', 'htmlentities|strip_tags|trim|required|max_length[300]|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_subdistrict/edit/' . $id_subdistrict);
		}
		else{
			// update data admin ke database..
			$update_data = array(
				'id_city' => $this->input->post('city'),
				'subdistrict_name' => $this->input->post('subdistrict_name'),
				'subdistrict_code' => strtolower($this->global_lib->cleanString($this->input->post('subdistrict_name')))
			);
			$this->mdl_subdistrict->updateSubdistrict($update_data, $id_subdistrict);
      
      $message =  $this->global_lib->generateMessage("Subdistrict has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_subdistrict/edit/' . $id_subdistrict);
		}
	}
  
	public function delete($id_subdistrict=''){
		//ambil data subdistrict yang akan diedit.
		$data = $this->mdl_subdistrict->getSubdistrictByID($id_subdistrict);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_subdistrict/index');
		}
    
    // $this->mdl_subdistrict->deleteSubdistrict($id_subdistrict);
    $this->mdl_subdistrict->updateSubdistrict(array('deleted' => 1), $id_subdistrict);
		
    $message =  $this->global_lib->generateMessage("Subdistrict has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_subdistrict/index');
	}
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('city','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('province','', 'htmlentities|strip_tags|trim|required|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_subdistrict/index');
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
				'city' => $this->input->post('city'),
				'province' => $this->input->post('province'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_subdistrict', $search_param);
			
			redirect('admin_subdistrict/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_subdistrict');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('subdistrict_name');
    $sort_by_list = array(
			'default' => 'id_subdistrict DESC',
			'newest' => 'id_subdistrict DESC',
			'oldest' => 'id_subdistrict ASC',
			'name_asc' => 'subdistrict_name ASC',
			'name_desc' => 'subdistrict_name DESC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_subdistrict/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_subdistrict/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_subdistrict/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_subdistrict/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_subdistrict->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
    
		$data['subdistrict'] = $this->mdl_subdistrict->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
    //ambil semua province untuk dropdown parent di search..
		$data['province'] = $this->mdl_province->getAllProvince();
		//ambil data city jika search_param province di set..
		if($search_param['province'] != 'all' && $search_param['province'] > 0){
			$data['city'] = $this->mdl_city->getCityByIDProvince($search_param['province']);
		}
    
		//load view search result..
		$content = $this->load->view('admin/subdistrict/all', $data, true);
		
		$this->render($content);
	}
	
  public function syncRajaongkir(){
    //clear search session yang lama..
		$this->clearSearchSession();
		
    //ambil semua province untuk dropdown parent di search dan form add..
		$data['province'] = $this->mdl_province->getAllProvince();
    
		//load view
		$content = $this->load->view('admin/subdistrict/sync_rajaongkir', $data, true);
		$this->render($content);
  }
  
  public function submitProvinceAndCitySyncRajaongkir(){
    //validasi id province yg dipilih..
		$this->form_validation->set_rules('province','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('city','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
			$message = "<div class='alert alert-danger'>Invalid province and city.</div>";
			$this->session->set_flashdata('message', $message);
			redirect('admin_subdistrict/syncRajaongkir');
		}
		else{
			$id_province = $this->input->post('province');
			$id_city = $this->input->post('city');
			redirect('admin_subdistrict/startSyncRajaongkir/'.$id_province.'/'.$id_city);
		}
  }
  
  public function startSyncRajaongkir($id_province = 0, $id_city = 0){
    $this->load->library('rajaongkir');
    
    //ambil semua provinsi
		$data['province'] = $this->mdl_province->getAllProvince();
    
    $data['province_data'] = array();
		if(isset($id_province)){
			$provinceData = $this->mdl_province->getProvinceByID($id_province);
			
			//cek province bedasarkan id..
			if($provinceData[0]->id_province <= 0){
				$message = "<div class='alert alert-danger'>Province not found. Please choose province.</div>";
				$this->session->set_flashdata('message', $message);
				redirect('admin_subdistrict/syncRajaongkir');
			}
			
			//cek apakah ada id rajaongkir (harus sudah syncronize province sebelumnya).
			if($provinceData[0]->id_province_rajaongkir <= 0){
				$message = "<div class='alert alert-danger'>Province not syncronize with rajaongkir.com yet. Please Syncronize province first.</div>";
				$this->session->set_flashdata('message', $message);
				redirect('admin_subdistrict/syncRajaongkir');
			}
      
      $data['province_data'] = $provinceData;
		}
    else{
      $message = "<div class='alert alert-danger'>Please choose province.</div>";
      $this->session->set_flashdata('message', $message);
      redirect('admin_subdistrict/syncRajaongkir');
    }

    //cek id city dan id city rajaongkir di database..
		if($id_city > 0){
			$city_data = $this->mdl_city->getCityByID($id_city);
			
			//cek province bedasarkan id..
			if($city_data[0]->id_city <= 0){
				$message = "<div class='alert alert-danger'>City / district not found.</div>";
				$this->session->set_flashdata('message', $message);
				redirect('admin_subdistrict/syncRajaongkir');
			}
			
			//cek apakah ada id rajaongkir.
			if($city_data[0]->id_city_rajaongkir <= 0){
				$message = "<div class='alert alert-danger'>City / district not syncronize with rajaongkir.com yet. Please Syncronize city first.</div>";
				$this->session->set_flashdata('message', $message);
				redirect('admin_subdistrict/syncRajaongkir');
			}
		}
		else{
			//ambil city bedasarkan id province..
			$city_data = $this->mdl_city->getCityByIDProvince($id_province);
			//cek id city rajaongkir harus ada..
			foreach($city_data as $item){
				if($item->id_city_rajaongkir <= 0){
					$message = "<div class='alert alert-danger'>City / district not syncronize with rajaongkir.com yet. Please Syncronize city first.</div>";
					$this->session->set_flashdata('message', $message);
					redirect('admin_subdistrict/syncRajaongkir');
					break;
				}
			}
		}
    $data['city_data'] = $city_data;
		
		$x = 0;
    $data['subdistrict_data'] = array();
		foreach($data['city_data'] as $item){
      
      $data['city_data'][$x]->subdistrict = array();
      $data['city_data'][$x]->subdistrict_rajaongkir = array();
      
      //ambil subdistrict di database lokal bedasarkan id city..
      $data['city_data'][$x]->subdistrict_local = $this->mdl_subdistrict->getSubdistrictByIDCity($item->id_city);
      
      //ambil subdistrict rajaongkir bedasarkan id city rajaongkir..
			$id_city_rajaongkir = ($item->id_city_rajaongkir > 0 ? $item->id_city_rajaongkir : '');
			$rajaongkirData = json_decode($this->rajaongkir->subdistrict($id_city_rajaongkir));
      
      if(isset($rajaongkirData->rajaongkir->status->code) && $rajaongkirData->rajaongkir->status->code == '200'){
        $data['city_data'][$x]->subdistrict_rajaongkir = $rajaongkirData->rajaongkir->results;
        
        //merge data subdistrict local dan rajaongkir..
        $data['city_data'][$x]->subdistrict = array();
        $subdistrict_all = array();
        //insert dari rajaongkir..
        foreach($data['city_data'][$x]->subdistrict_rajaongkir as $rjongkir){
          $subdistrict_code = strtolower($this->global_lib->cleanString($rjongkir->subdistrict_name));
          $subdistrict_all[$subdistrict_code] = array(
            'subdistrict_id' => $rjongkir->subdistrict_id,
            'city_id' => $rjongkir->city_id,
            'subdistrict_name' => $rjongkir->subdistrict_name,
            'subdistrict_code' => $subdistrict_code,
            'local_id_subdistrict' => '',
            'local_id_subdistrict_rajaongkir' => '',
            'local_id_city_rajaongkir' => '',
            'local_subdistrict_name' => '',
            'local_subdistrict_code' => '',
            'match_status' => '',
            'match_status_str' => ''
          );
          // array_push($subdistrict_all, $subdistrict_code);
        }
        
        //insert dari local jika belum ada di rajaongkir..
        foreach($data['city_data'][$x]->subdistrict_local as $local){
          $subdistrict_code = strtolower($this->global_lib->cleanString($local->subdistrict_name));
          if(isset($subdistrict_all[$subdistrict_code]) && is_array($subdistrict_all[$subdistrict_code])){
            $subdistrict_all[$subdistrict_code]['local_id_subdistrict'] = $local->id_subdistrict;
            $subdistrict_all[$subdistrict_code]['local_id_subdistrict_rajaongkir'] = $local->id_subdistrict_rajaongkir;
            $subdistrict_all[$subdistrict_code]['local_id_city_rajaongkir'] = $local->id_city_rajaongkir;
            $subdistrict_all[$subdistrict_code]['local_subdistrict_name'] = $local->subdistrict_name;
            $subdistrict_all[$subdistrict_code]['local_subdistrict_code'] = $subdistrict_code;
          }
          else{
            $subdistrict_all[$subdistrict_code] = array(
              'subdistrict_id' => '',
              'city_id' => '',
              'subdistrict_name' => '',
              'subdistrict_code' => '',
              'local_id_subdistrict' => $local->id_subdistrict,
              'local_id_subdistrict_rajaongkir' => $local->id_subdistrict_rajaongkir,
              'local_id_city_rajaongkir' => $local->id_city_rajaongkir,
              'local_subdistrict_name' => $local->subdistrict_name,
              'local_subdistrict_code' => $subdistrict_code,
              'match_status' => '',
              'match_status_str' => ''
            );
          }
        }
        
        //tentukan match status..
        foreach($subdistrict_all as $index => $sub){
          //match..
          if($sub['city_id'] == $sub['local_id_city_rajaongkir']){
            if($sub['subdistrict_id'] == $sub['local_id_subdistrict_rajaongkir']){
              $subdistrict_all[$index]['match_status'] = "match";
              $subdistrict_all[$index]['match_status_str'] = "<span class='label label-info'>Match</span>";
            }
            else if($sub['subdistrict_code'] == $sub['local_subdistrict_code']){
              $subdistrict_all[$index]['match_status'] = "suggested";
              $subdistrict_all[$index]['match_status_str'] = "<span class='label label-info'>Suggested</span>";
            }
            else{
              $subdistrict_all[$index]['match_status'] = "nomatch";
              $subdistrict_all[$index]['match_status_str'] = "<span class='label label-danger'>No Match</span>";
            }
          }
          else{
            $subdistrict_all[$index]['match_status'] = "nomatch";
            $subdistrict_all[$index]['match_status_str'] = "<span class='label label-danger'>No Match</span>";
          }
        }
        
        $data['subdistrict_data'][$x] = $subdistrict_all;
      }
      
      $x++;
    }

    //load view
		$content = $this->load->view('admin/subdistrict/sync_rajaongkir_form', $data, true);
		$this->render($content);
    
    // print_r('<pre>');
    // print_r($data);
    // print_r('</pre>');
  }
  
  public function submitSyncRajaongkir($id_province = 0, $id_city = 0){
    $this->load->library('rajaongkir');
    
    //cek id province dan id province rajaongkir di database..
		if($id_province > 0){
			$provinceData = $this->mdl_province->getProvinceByID($id_province);
			
			//cek province bedasarkan id..
			if($provinceData[0]->id_province <= 0){
				$message = "<div class='alert alert-danger'>Province not found.</div>";
				$this->session->set_flashdata('message', $message);
				redirect('admin_subdistrict/syncRajaongkir');
			}
			
			//cek apakah ada id rajaongkir.
			if($provinceData[0]->id_province_rajaongkir <= 0){
				$message = "<div class='alert alert-danger'>Province not syncronize with rajaongkir.com yet. Please Syncronize province first.</div>";
				$this->session->set_flashdata('message', $message);
				redirect('admin_subdistrict/syncRajaongkir');
			}
		}
		else{
			$message = "<div class='alert alert-danger'>Plese choose province.</div>";
			$this->session->set_flashdata('message', $message);
			redirect('admin_subdistrict/syncRajaongkir');
		}
		
		//cek id city dan id city rajaongkir di database..
		if($id_city > 0){
			$city_data = $this->mdl_city->getCityByID($id_city);
			
			//cek province bedasarkan id..
			if($city_data[0]->id_city <= 0){
				$message = "<div class='alert alert-danger'>City / district not found.</div>";
				$this->session->set_flashdata('message', $message);
				redirect('admin_subdistrict/syncRajaongkir');
			}
			
			//cek apakah ada id rajaongkir.
			if($city_data[0]->id_city_rajaongkir <= 0){
				$message = "<div class='alert alert-danger'>City / district not syncronize with rajaongkir.com yet. Please Syncronize city first.</div>";
				$this->session->set_flashdata('message', $message);
				redirect('admin_subdistrict/syncRajaongkir');
			}
		}
		else{
			//ambil city bedasarkan id province..
			$city_data = $this->mdl_city->getCityByIDProvince($id_province);
			//cek id city rajaongkir harus ada..
			foreach($city_data as $item){
				if($item->id_city_rajaongkir <= 0){
					$message = "<div class='alert alert-danger'>City / district not syncronize with rajaongkir.com yet. Please Syncronize city first.</div>";
					$this->session->set_flashdata('message', $message);
					redirect('admin_subdistrict/syncRajaongkir');
					break;
				}
			}
		}
		
		$sync_data['data'] = $city_data;
		
		//ambil data subdistrict rajaongkir
		$x = 0;
		foreach($sync_data['data'] as $item){
    
			//ambil subdistrict rajaongkir bedasarkan id city rajaongkir..
			$id_city_rajaongkir = ($item->id_city_rajaongkir > 0 ? $item->id_city_rajaongkir : '');
			$rajaongkirData = json_decode($this->rajaongkir->subdistrict($id_city_rajaongkir));
			$sync_data['data'][$x]->subdistrict_rajaongkir = $rajaongkirData->rajaongkir->results;
			
			//ambil subdistrict di database lokal bedasarkan id city..
			$sync_data['data'][$x]->subdistrict_local = $this->mdl_subdistrict->getSubdistrictByIDCity($item->id_city);
			
      //merge data subdistrict local dan rajaongkir..
      $sync_data['data'][$x]->subdistrict = array();
      $subdistrict_all = array();
      //insert dari rajaongkir..
      foreach($sync_data['data'][$x]->subdistrict_rajaongkir as $rjongkir){
        $subdistrict_code = strtolower($this->global_lib->cleanString($rjongkir->subdistrict_name));
        $subdistrict_all[$subdistrict_code] = array(
          'subdistrict_id' => $rjongkir->subdistrict_id,
          'city_id' => $rjongkir->city_id,
          'subdistrict_name' => $rjongkir->subdistrict_name,
          'subdistrict_code' => $subdistrict_code,
          'local_id_subdistrict' => '',
          'local_id_subdistrict_rajaongkir' => '',
          'local_id_city_rajaongkir' => '',
          'local_subdistrict_name' => '',
          'local_subdistrict_code' => '',
          'match_status' => '',
          'match_status_str' => ''
        );
        // array_push($subdistrict_all, $subdistrict_code);
      }
      
      //insert dari local jika belum ada di rajaongkir..
      foreach($sync_data['data'][$x]->subdistrict_local as $local){
        $subdistrict_code = strtolower($this->global_lib->cleanString($local->subdistrict_name));
        if(isset($subdistrict_all[$subdistrict_code]) && is_array($subdistrict_all[$subdistrict_code])){
          $subdistrict_all[$subdistrict_code]['local_id_subdistrict'] = $local->id_subdistrict;
          $subdistrict_all[$subdistrict_code]['local_id_subdistrict_rajaongkir'] = $local->id_subdistrict_rajaongkir;
          $subdistrict_all[$subdistrict_code]['local_id_city_rajaongkir'] = $local->id_city_rajaongkir;
          $subdistrict_all[$subdistrict_code]['local_subdistrict_name'] = $local->subdistrict_name;
          $subdistrict_all[$subdistrict_code]['local_subdistrict_code'] = $subdistrict_code;
        }
        else{
          $subdistrict_all[$subdistrict_code] = array(
            'subdistrict_id' => '',
            'city_id' => '',
            'subdistrict_name' => '',
            'subdistrict_code' => '',
            'local_id_subdistrict' => $local->id_subdistrict,
            'local_id_subdistrict_rajaongkir' => $local->id_subdistrict_rajaongkir,
            'local_id_city_rajaongkir' => $local->id_city_rajaongkir,
            'local_subdistrict_name' => $local->subdistrict_name,
            'local_subdistrict_code' => $subdistrict_code,
            'match_status' => '',
            'match_status_str' => ''
          );
        }
      }
      
      $sync_data['subdistrict_data'][$x] = $subdistrict_all;
      
			//validasi input..
			foreach($sync_data['subdistrict_data'][$x] as $subdistrict){
        if(isset($subdistrict['subdistrict_id']) && $subdistrict['subdistrict_id'] > 0){
          $fieldName = 'subdistrict_sync_'.$subdistrict['subdistrict_id'];
        }
        else{
          $fieldName = 'local_subdistrict_sync_'.$subdistrict['local_id_subdistrict'];
        }
				$this->form_validation->set_rules($fieldName,'', 'htmlentities|strip_tags|trim|xss_clean');
			}
			if($this->form_validation->run() == false){
				$message = "<div class='alert alert-danger'>Failed to syncronize.</div>";
				$this->session->set_flashdata('message', $message);
				redirect('admin_subdistrict/startSyncRajaongkir/'.$id_province.'/'.$id_city);
			}
			else{
				
				//proses subdistrict bedasarkan action yg dipilih..
				foreach($sync_data['subdistrict_data'][$x] as $subdistrict){
          if(isset($subdistrict['subdistrict_id']) && $subdistrict['subdistrict_id'] > 0){
            $action = $this->input->post('subdistrict_sync_' . $subdistrict['subdistrict_id']);
          }
          else{
            $action = $this->input->post('local_subdistrict_sync_' . $subdistrict['local_id_subdistrict']);
          }

          //jika action yg dipilih adalah insert baru..
          if($action == 'insert'){
            //tambah subdistrict baru..
            $insertData = array(
              'id_city' => $item->id_city,
              'id_city_rajaongkir' => $subdistrict['city_id'],
              'id_subdistrict_rajaongkir' => $subdistrict['subdistrict_id'],
              'subdistrict_name' => $subdistrict['subdistrict_name'],
              'subdistrict_code' => strtolower($this->global_lib->cleanString($subdistrict['subdistrict_name']))
            );
            $this->mdl_subdistrict->insertSubdistrict($insertData);
          }
          else if(strpos($action, 'delete') !== false){
            $this->mdl_subdistrict->deleteSubdistrict(str_replace('delete_', '', $action));
          }
          else if($action > 0){
            //cek id subdistrict di database bedasarkan id subdistrict..
            $cek = $this->mdl_subdistrict->checkSubdistrictByID($action);
            if($cek){
              //update id_rajaongkir..
              $updateData = array(
                'id_city' => $item->id_city,
                'id_city_rajaongkir' => $subdistrict['city_id'],
                'subdistrict_name' => $subdistrict['subdistrict_name'],
                'subdistrict_code' => strtolower($this->global_lib->cleanString($subdistrict['subdistrict_name']))
              );
              $this->mdl_subdistrict->updateSubdistrict($updateData, $action);
            }
            else{
              //tambah subdistrict baru..
              $insertData = array(
                'id_city' => $item->id_city,
                'id_city_rajaongkir' => $subdistrict['city_id'],
                'id_subdistrict_rajaongkir' => $subdistrict['subdistrict_id'],
                'subdistrict_name' => $subdistrict['subdistrict_name'],
                'subdistrict_code' => strtolower($this->global_lib->cleanString($subdistrict['subdistrict_name']))
              );
              $this->mdl_subdistrict->insertSubdistrict($insertData);
            }
          }
          else {}
          
				}
        
			}
			$x++;
		}
		
		$message = "<div class='alert alert-info'>Syncronization process has been done.</div>";
		$this->session->set_flashdata('message', $message);
		redirect('admin_subdistrict/startSyncRajaongkir/' . $id_province . '/' . $id_city);
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
		$config['base_url'] 		= base_url().'admin_subdistrict/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_subdistrict/search/';
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
			'city' => 'all',
			'province' => 'all',
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_subdistrict', $search_param);
	}
}