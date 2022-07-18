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

class Admin_ads extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $allowed_format = 'jpg|png|gif|jpeg';
  var $module_name = 'admin_ads';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_ads');
    
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
		$data['total_row'] = $this->mdl_ads->getAllAdsCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
    $data['ads'] = $this->mdl_ads->getAllAdsLimit(
      $this->session->userdata('id_admin'),
      $config['per_page'],
      ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0)
    );
    
    //ambil type ads
    $data['ads_types'] = $this->mdl_ads->getAllAdsType();
		
		//load view all module
		$content = $this->load->view('admin/ads/all', $data, true);
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    //ambil type ads
    $data['ads_types'] = $this->mdl_ads->getAllAdsType();
    
		//load view add admin ...
		$content = $this->load->view('admin/ads/add', $data, true);
		$this->render($content);
	}
	
	public function saveAdd(){
    $ads_source = $this->input->post('ads_source');
    $builtin_required = false;
    $googleads_required = false;
    if(strtolower($ads_source) == 'builtin'){
      $builtin_required = true;
    }
    if(strtolower($ads_source) == 'googleads'){
      $googleads_required = true;
    }
    
		$this->form_validation->set_rules('ads_source','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('id_adstype','', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
		$this->form_validation->set_rules('redirect_url','', 'htmlentities|strip_tags|trim|xss_clean' . ($builtin_required ? '|required' : ''));
		$this->form_validation->set_rules('start_date','', 'htmlentities|strip_tags|trim|xss_clean' . ($builtin_required ? '|required' : ''));
		$this->form_validation->set_rules('finish_date','', 'htmlentities|strip_tags|trim|xss_clean' . ($builtin_required ? '|required' : ''));
		$this->form_validation->set_rules('ads_pic','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('googleads_code','', 'htmlentities|strip_tags|trim|xss_clean' . ($googleads_required ? '|required' : ''));
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_ads/add');
		}
		else{
      $ads_source = $this->input->post('ads_source');
      $id_adstype = $this->input->post('id_adstype');
      $redirect_url = $this->input->post('redirect_url');
      $start_date = $this->input->post('start_date');
      $finish_date = $this->input->post('finish_date');
      $googleads_code = $this->input->post('googleads_code');
      
      if(strtolower($ads_source) == 'googleads'){
        
        //check apakah sudah ada google ads sebelumnya..
        $ads = $this->mdl_ads->getAdsByIDAdstypeAndSource($id_adstype, 'googleads');
        //jika ada update, jika belum insert..
        if(isset($ads[0]->id_ads) && $ads[0]->id_ads > 0){
          $update_data = array(
            'googleads_code' => $googleads_code
          );
          $this->mdl_ads->updateAds($update_data, $ads[0]->id_ads);
        }
        else{
          $insert_data = array(
            'id_adstype' => $id_adstype,
            'ads_source' => $ads_source,
            'googleads_code' => $googleads_code
          );
          $this->mdl_ads->insertAds($insert_data);
        }
        $message =  $this->global_lib->generateMessage("Data iklan berhasil ditambahkan.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('admin_ads/index');
        
      }
      else if(strtolower($ads_source) == 'builtin'){
        
        //check start and finish date..
        $is_valid_date = $this->global_lib->validateDate($start_date, 'd-m-Y');
        if($is_valid_date){
          $start_date = $this->global_lib->formatDate($start_date, 'd-m-Y', 'Y-m-d');
        }
        else{
          $message =  $this->global_lib->generateMessage("Pilih tanggal mulai tayang.", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('admin_ads/add');
        }
        $is_valid_date = $this->global_lib->validateDate($finish_date, 'd-m-Y');
        if($is_valid_date){
          $finish_date = $this->global_lib->formatDate($finish_date, 'd-m-Y', 'Y-m-d');
        }
        else{
          $message =  $this->global_lib->generateMessage("Pilih tanggal selesai tayang.", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('admin_ads/add');
        }
        
        //check tanggal tayang (jangan ada yang bentrok)
        $this->load->model('mdl_ads_order');
        $check_ads = $this->mdl_ads->checkAdsByIDAdstypeAndDate($id_adstype, $start_date, $finish_date);
        $check_ads_order = $this->mdl_ads_order->has_been_used($id_adstype, $start_date, $finish_date);
        if($check_ads || $check_ads_order){
          $message =  $this->global_lib->generateMessage("Tidak dapat membuat iklan karena tanggal tayang overlap dengan iklan yang lain.", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('admin_ads/add');
        }
        
        //ambil adstype untuk ukuran gambar..
        $adstype = $this->mdl_ads->getAdsTypeByID($id_adstype);
        if(isset($adstype[0]->id_adstype) && $adstype[0]->id_adstype > 0){
          // --- START uploading picture without thumbnail
          $ads_pic = '';
          if (!empty($_FILES['ads_pic']['name'])){
            $config = $this->adsPicUploadConfig($_FILES['ads_pic']['name']);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('ads_pic')){
              $upload_data = array('upload_data' => $this->upload->data());
              if(strtolower($upload_data['upload_data']['file_ext']) == '.gif'){
                $ads_pic = $upload_data['upload_data']['file_name'];
              }
              else{
                $ads_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $adstype[0]->ads_width, $adstype[0]->ads_height, true);
              }
            }
            else{
              $message =  $this->global_lib->generateMessage("Gagal mengupload gambar iklan. <br/>" . $this->upload->display_errors(), "danger");
              $this->session->set_flashdata('message', $message);
              redirect('admin_ads/add');
            }
          }
          else{
            $message = $this->global_lib->generateMessage("Upload file gambar iklan.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('admin_ads/add');
          }
          // --- END uploading picture without thumbnail
          
          //insert data ke database..
          $insert_data = array(
            'id_adstype' => $id_adstype,
            'ads_source' => $ads_source,
            'redirect_url' => $redirect_url,
            'start_date' => $start_date,
            'finish_date' => $finish_date,
            'ads_pic' => $ads_pic
          );
          $this->mdl_ads->insertAds($insert_data);
          
          $message =  $this->global_lib->generateMessage("Data iklan berhasil ditambahkan.", "info");
          $this->session->set_flashdata('message', $message);
          redirect('admin_ads/index');
        }
        else{
          redirect('admin_ads/add');
        }
      }
      else{
        redirect('admin_ads/add');
      }
		}
	}
	
	public function edit($id_ads=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data ads yang akan diedit.
		$data['ads'] = $this->mdl_ads->getAdsByID($id_ads);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['ads'])) || count($data['ads']) < 1){
			redirect('admin_ads/index');
		}

    if ($data['ads'][0]->edit_id_admin && $data['ads'][0]->edit_id_admin != $this->session->userdata('id_admin'))
    {
			redirect('admin_ads/index');
    }

    if (!is_null($data['ads'][0]->id_user) && strtotime('now') >= strtotime($data['ads'][0]->start_date) && $data['ads'][0]->status === '1') {
      redirect('admin_ads/index');
    }

    if ($data['ads'][0]->status === '-1')
    {
			redirect('admin_ads/index');
    }
    
    //ambil type ads
    $data['ads_types'] = $this->mdl_ads->getAllAdsType();
		$data['locked_ads_id'] = $this->mdl_ads->getLockedAdsId($this->session->userdata('id_admin'));
    
		//load view edit admin ...
		$content = $this->load->view('admin/ads/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_ads=''){
		//ambil data ads yang akan diedit.
		$data['ads'] = $this->mdl_ads->getAdsByID($id_ads);
		if((! is_array($data['ads'])) || count($data['ads']) < 1){
			redirect('admin_ads/index');
		}
    
    $ads_source = $this->input->post('ads_source');
    $builtin_required = false;
    $googleads_required = false;
    $is_member_ads = !is_null($data['ads'][0]->id_user);
    $status = $this->input->post('status');
    if(strtolower($ads_source) == 'builtin'){
      $builtin_required = true;
    }
    if(strtolower($ads_source) == 'googleads'){
      $googleads_required = true;
    }
		
		//validasi input
		$this->form_validation->set_rules('ads_source','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('id_adstype','', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
		$this->form_validation->set_rules('redirect_url','', 'htmlentities|strip_tags|trim|xss_clean' . ($builtin_required ? '|required' : ''));
		$this->form_validation->set_rules('start_date','', 'htmlentities|strip_tags|trim|xss_clean' . ($builtin_required ? '|required' : ''));
		$this->form_validation->set_rules('finish_date','', 'htmlentities|strip_tags|trim|xss_clean' . ($builtin_required ? '|required' : ''));
		$this->form_validation->set_rules('ads_pic','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('googleads_code','', 'htmlentities|strip_tags|trim|xss_clean' . ($googleads_required ? '|required' : ''));

    if ($is_member_ads)
    {
      $this->form_validation->set_rules('status','', 'htmlentities|strip_tags|trim|xss_clean|required');
      $this->form_validation->set_rules('reject_note','', 'htmlentities|strip_tags|trim|xss_clean' . ($status === '-2' ? '|required' : ''));
    }

		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form." . validation_errors(), "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_ads/edit/' . $id_ads);
		}
		else{
      $ads_source = $this->input->post('ads_source');
      $id_adstype = $this->input->post('id_adstype');
      $redirect_url = $this->input->post('redirect_url');
      $start_date = $this->input->post('start_date');
      $finish_date = $this->input->post('finish_date');
      $googleads_code = $this->input->post('googleads_code');
      $edit_id_admin = $status === '1' ? null : $data['ads'][0]->edit_id_admin;
      
      if(strtolower($ads_source) == 'googleads'){
        
        $update_data = array(
          'redirect_url' => '',
          'start_date' => NULL,
          'finish_date' => NULL,
          'ads_pic' => '',
          'googleads_code' => $googleads_code,
          'edit_id_admin' => $edit_id_admin,
        );
        $this->mdl_ads->updateAds($update_data, $id_ads);
        $message =  $this->global_lib->generateMessage("Data iklan berhasil diupdate.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('admin_ads/index');
        
      }
      else if(strtolower($ads_source) == 'builtin'){
        
        //check start and finish date..
        $is_valid_date = $this->global_lib->validateDate($start_date, 'd-m-Y');
        if($is_valid_date){
          $start_date = $this->global_lib->formatDate($start_date, 'd-m-Y', 'Y-m-d');
        }
        else{
          $message =  $this->global_lib->generateMessage("Pilih tanggal mulai tayang.", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('admin_ads/edit/' . $id_ads);
        }
        $is_valid_date = $this->global_lib->validateDate($finish_date, 'd-m-Y');
        if($is_valid_date){
          $finish_date = $this->global_lib->formatDate($finish_date, 'd-m-Y', 'Y-m-d');
        }
        else{
          $message =  $this->global_lib->generateMessage("Pilih tanggal selesai tayang.", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('admin_ads/edit/' . $id_ads);
        }
        
        //check tanggal tayang (jangan ada yang bentrok)
        $this->load->model('mdl_ads_order');
        $check_ads = $this->mdl_ads->checkAdsByIDAdstypeAndDateExcludeIDAds($id_adstype, $start_date, $finish_date, $id_ads);
        $check_ads_order = $this->mdl_ads_order->has_been_used($id_adstype, $start_date, $finish_date, NULL, $data['ads'][0]->id_ads_order_item);
        if($check_ads || $check_ads_order){
          $message =  $this->global_lib->generateMessage("Tidak dapat mengupdate iklan karena tanggal tayang overlap dengan iklan yang lain.", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('admin_ads/edit/' . $id_ads);
        }
        
        //ambil adstype untuk ukuran gambar..
        $adstype = $this->mdl_ads->getAdsTypeByID($id_adstype);
        if(isset($adstype[0]->id_adstype) && $adstype[0]->id_adstype > 0){
          // --- START uploading picture without thumbnail
          $ads_pic = '';
          if (!empty($_FILES['ads_pic']['name'])){
            $config = $this->adsPicUploadConfig($_FILES['ads_pic']['name']);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('ads_pic')){
              $upload_data = array('upload_data' => $this->upload->data());
              if(strtolower($upload_data['upload_data']['file_ext']) == '.gif'){
                $ads_pic = $upload_data['upload_data']['file_name'];
              }
              else{
                $ads_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $adstype[0]->ads_width, $adstype[0]->ads_height, true);
              }
            }
            else{
              $message =  $this->global_lib->generateMessage("Gagal mengupload gambar iklan. <br/>" . $this->upload->display_errors(), "danger");
              $this->session->set_flashdata('message', $message);
              redirect('admin_ads/edit/' . $id_ads);
            }
          }
          
          //insert data ke database..
          $update_data = array(
            'redirect_url' => $redirect_url,
            'start_date' => $start_date,
            'finish_date' => $finish_date,
          );

          if ($is_member_ads)
          {
            $update_data = compact('status');

            if (in_array($status, ['-3', '-2']))
            {
              $update_data['reject_note'] = $this->input->post('reject_note');
            }
            elseif ($status === '1')
            {
              $update_data['reject_note'] = NULL;
              $update_data['reason_for_revision'] = NULL;

              if ($data['ads'][0]->status === '2')
              {
                if ($data['ads'][0]->revised_redirect_url)
                {
                  $update_data['redirect_url'] = $data['ads'][0]->revised_redirect_url;
                  $update_data['revised_redirect_url'] = NULL;
                }

                if ($data['ads'][0]->revised_ads_pic)
                {
                  @unlink("assets/adv/{$data['ads'][0]->ads_pic}");

                  $update_data['ads_pic'] = $data['ads'][0]->revised_ads_pic;
                  $update_data['revised_ads_pic'] = NULL;
                }
              }
            }
          }

          $update_data['edit_id_admin'] = $edit_id_admin;

          if(isset($ads_pic) && strlen(trim($ads_pic)) > 0){
            $update_data['ads_pic'] = $ads_pic;
          }
          $this->mdl_ads->updateAds($update_data, $id_ads);
          
          $message =  $this->global_lib->generateMessage("Data iklan berhasil diupdate.", "info");
          $this->session->set_flashdata('message', $message);
          redirect('admin_ads/index');
        }
        else{
          redirect('admin_ads/edit/' . $id_ads);
        }
        
      }
      else{
        redirect('admin_ads/edit/' . $id_ads);
      }
		}
	}
  
	public function delete($id_ads=''){
		//ambil data ads yang akan diedit.
		$data = $this->mdl_ads->getAdsByID($id_ads);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_ads/index');
		}
    
    if ($data[0]->id_user)
    {
			redirect('admin_ads/index');
    }

    if ($data[0]->edit_id_admin && $data[0]->edit_id_admin != $this->session->userdata('id_admin'))
    {
			redirect('admin_ads/index');
    }

    $this->mdl_ads->deleteAds($id_ads);
		
    $message =  $this->global_lib->generateMessage("Ads berhasil dihapus.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_ads/index');
	}
  
	public function submitSearch(){
		//validasi input..
    $this->form_validation->set_rules('search_by','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('operator','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('keyword','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('per_page','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
		$this->form_validation->set_rules('ads_source','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('id_adstype','', 'htmlentities|strip_tags|trim|xss_clean');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_ads/index');
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
				'id_adstype' => $this->input->post('id_adstype'),
				'ads_source' => $this->input->post('ads_source'),
				'search_collapsed' => $this->input->post('search_collapsed')
			);
			$this->session->set_userdata('search_ads', $search_param);
			redirect('admin_ads/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_ads');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('ads_text', 'ads_textarea');
    $sort_by_list = array(
			'default' => 'tbl_ads.id_ads DESC',
			'newest' => 'tbl_ads.start_date DESC, tbl_ads.id_ads DESC',
			'oldest' => 'tbl_ads.start_date ASC, tbl_ads.id_ads ASC',
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_ads/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_ads/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_ads/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_ads/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_ads->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
		$data['ads'] = $this->mdl_ads->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
    //ambil type ads
    $data['ads_types'] = $this->mdl_ads->getAllAdsType();
    
		//load view search result..
		$content = $this->load->view('admin/ads/all', $data, true);
		$this->render($content);
	}
	
  public function lock_edit($id_ads = '')
  {
    $this->_toggle_lock_edit(TRUE, $id_ads);
  }

  public function unlock_edit($id_ads = '')
  {
    $this->_toggle_lock_edit(FALSE, $id_ads);
  }
 
  private function _toggle_lock_edit($is_lock, $id_ads)
  {
		$ads = $this->mdl_ads->getAdsByID($id_ads);

    if ((!is_array($ads)) || count($ads) < 1)
    {
      redirect($_SERVER['HTTP_REFERER'] ?? 'admin_ads/index');
		}

    if (!$is_lock && $ads[0]->edit_id_admin != $this->session->userdata('id_admin') && $this->session->userdata('admin_level') !== '1')
    {
      redirect($_SERVER['HTTP_REFERER'] ?? 'admin_ads/index');
    }

    $locked_ads_id = $this->mdl_ads->getLockedAdsId($this->session->userdata('id_admin'));
    $is_valid_lock = $is_lock && is_null($locked_ads_id);
    $is_valid_unlock = !$is_lock && (
      $ads[0]->edit_id_admin == $this->session->userdata('id_admin') || $this->session->userdata('admin_level') === '1'
    );

    if ($is_valid_lock || $is_valid_unlock) {
      $this->mdl_ads->updateAds([
        'edit_id_admin' => $is_lock ? $this->session->userdata('id_admin') : NULL,
      ], $id_ads);
    }

    redirect($_SERVER['HTTP_REFERER'] ?? "admin_ads/edit/{$id_ads}");
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
  
  private function adsFileUploadConfig($filename=''){
    $config['upload_path'] = './assets/ads/';
		$config['allowed_types'] = $this->allowed_format;
		$config['max_size'] = '12000';
		$config['max_width']  = '8000';
		$config['max_height']  = '8000';
		if(strlen(trim($filename)) > 0){
			$config['file_name']  = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
		}
		return $config;
  }
  
  private function adsPicUploadConfig($filename=''){
		$config['upload_path'] = './assets/adv/';
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
		$config['base_url'] 		= base_url().'admin_ads/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_ads/search/';
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
			'id_adstype' => 'all',
			'ads_source' => 'all',
			'search_collapsed' => '1'
		);
		$this->session->set_userdata('search_ads', $search_param);
	}
}
