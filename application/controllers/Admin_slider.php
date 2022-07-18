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

class Admin_slider extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  var $pic_width = 1900;
	var $pic_height = 850;
  var $pic_thumb_width = 300;
	var $pic_thumb_height = 100;
  var $module_name = 'admin_slider';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_slider');
    
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
		$data['total_row'] = $this->mdl_slider->getAllSliderCount();
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
		
		$data['slider'] = $this->mdl_slider->getAllSliderLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
		//load view all module
		$content = $this->load->view('admin/slider/all', $data, true);
		
		$this->render($content);
	}
	
	public function add(){
		//clear search session yang lama..
		$this->clearSearchSession();
    
    $data = array();
    
		//load view add admin ...
		$content = $this->load->view('admin/slider/add', $data, true);
		$this->render($content);
	}
	
	public function saveAdd(){
		$this->form_validation->set_rules('slider_name','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('width','', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
		$this->form_validation->set_rules('height','', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
    $this->form_validation->set_rules('width_thumb','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		$this->form_validation->set_rules('height_thumb','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		$this->form_validation->set_rules('updatable','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		$this->form_validation->set_rules('deletable','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_slider/add');
		}
		else{
			//insert data ke database..
			$insert_data = array(
        "slider_name" => $this->input->post('slider_name'),
				"width" => $this->input->post('width'),
				"height" => $this->input->post('height'),
				"width_thumb" => $this->input->post('width_thumb'),
				"height_thumb" => $this->input->post('height_thumb'),
				"updatable" => $this->input->post('updatable'),
				"deletable" => $this->input->post('deletable')
			);
			$this->mdl_slider->insertSlider($insert_data);
      
      $message =  $this->global_lib->generateMessage("New slider has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_slider/index');
		}
	}
	
	public function edit($id_slider=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data slider yang akan diedit.
		$data['slider'] = $this->mdl_slider->getSliderByID($id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider'])) || count($data['slider']) < 1){
			redirect('admin_slider/index');
		}
    
		//load view edit admin ...
		$content = $this->load->view('admin/slider/edit', $data, true);
		$this->render($content);
	}
	
	public function saveEdit($id_slider=''){
		//ambil data slider yang akan diedit.
		$data['slider'] = $this->mdl_slider->getSliderByID($id_slider);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider'])) || count($data['slider']) < 1){
			redirect('admin_slider/index');
		}
		
		//validasi input
		$this->form_validation->set_rules('slider_name','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('width','', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
		$this->form_validation->set_rules('height','', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
    $this->form_validation->set_rules('width_thumb','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		$this->form_validation->set_rules('height_thumb','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		$this->form_validation->set_rules('updatable','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		$this->form_validation->set_rules('deletable','', 'htmlentities|strip_tags|trim|required|xss_clean|integer');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_slider/edit/' . $id_slider);
		}
		else{
			// update data admin ke database..
			$update_data = array(
				"slider_name" => $this->input->post('slider_name'),
				"width" => $this->input->post('width'),
				"height" => $this->input->post('height'),
				"width_thumb" => $this->input->post('width_thumb'),
				"height_thumb" => $this->input->post('height_thumb'),
				"updatable" => $this->input->post('updatable'),
				"deletable" => $this->input->post('deletable')
			);
			$this->mdl_slider->updateSlider($update_data, $id_slider);
      
      $message =  $this->global_lib->generateMessage("Slider has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_slider/edit/' . $id_slider);
		}
	}
  
	public function delete($id_slider=''){
		//ambil data slider yang akan diedit.
		$data = $this->mdl_slider->getSliderByID($id_slider);
		
		//jika tidak ada data, redirect ke index.
		if((! is_array($data)) || count($data) < 1){
			redirect('admin_slider/index');
		}
    
    //cek apakah slider deletable atau tidak.
		if($data[0]->deletable != '1' && $this->session->userdata('level') != '1'){
      $message =  $this->global_lib->generateMessage("You can't delete this slider. Contact your super adminintrator.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_slider/index');
		}
    
    $this->mdl_slider->deleteSlider($id_slider);
    $this->deleteSliderContentByIDSlider($id_slider);
		
    $message =  $this->global_lib->generateMessage("Slider has been deleted.", "info");
		$this->session->set_flashdata('message', $message);
		redirect('admin_slider/index');
	}
  
  private function deleteSliderContentByIDSlider($id_slider){
    //ambil data child slider..
    $slides = $this->mdl_slider->getSliderContentByIDSlider($id_slider);
    
    //hapus slider content..
    $this->mdl_slider->deleteSliderContentByIDSlider($id_slider);
    
    foreach($slides as $slide){
      //hapus file.
			@unlink('assets/slider/' . $slide->slider_pic);
			@unlink('assets/slider/thumb/' . $slide->slider_pic_thumb);
    }
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
			redirect('admin_slider/index');
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
			$this->session->set_userdata('search_slider', $search_param);
			
			redirect('admin_slider/search');
		}
	}
	
	public function search(){
		// ambil parameter search di session..
		$search_param = $this->session->userdata('search_slider');
		
		// ================= Syncronize parameter dengan field di database =====================// 
		$operator_list = array('like', 'not like');
		$field_list = array('slider_name');
    $sort_by_list = array(
			'default' => 'id_slider DESC',
			'newest' => 'id_slider DESC',
			'oldest' => 'id_slider ASC',
			'name_asc' => 'slider_name ASC',
			'name_desc' => 'slider_name DESC'
		);
		// ======================================================================================//
		
		// ========================== Validasi parameter2 searching =============================//
    // validasi search by..
		if(! in_array($search_param['search_by'], $field_list)){
			redirect('admin_slider/index');
		}
		
		//validasi operator..
		if(! in_array($search_param['operator'], $operator_list)){
			redirect('admin_slider/index');
		}
		
		//validasi sort_by..
		$sort_by = $sort_by_list[$search_param['sort_by']];
		if($sort_by == '' || $sort_by == null){
			redirect('admin_slider/index');
		}
		//ganti search_by (field alias) dengan nama field..
		$search_param['sort_by'] = $sort_by;
		
		//validasi per page..
		$per_page = $search_param['per_page'];
		if($per_page <= 0){
			redirect('admin_slider/index');
		}
		// =========================================================================================//
		
		//ambil parameter2 dan hasil pencarian..
		$data['total_row'] = $this->mdl_slider->getSearchResultCount($search_param);
    $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
		$this->pagination->initialize($config);
    
		$data['slider'] = $this->mdl_slider->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
    
		//load view search result..
		$content = $this->load->view('admin/slider/all', $data, true);
		
		$this->render($content);
	}
	
  public function manage($id_slider=''){
		//clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data slider yang akan diedit.
		$data['slider'] = $this->mdl_slider->getSliderByID($id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider'])) || count($data['slider']) < 1){
			redirect('admin_slider/index');
		}
    
    //set breadcrumb
    $this->global_lib->clearBreadcrumb();
    $current_breadcrumb = $this->session->userdata('breadcrumb');
    array_push($current_breadcrumb, array(
      'text' => 'Manage slider',
      'href' => '#'
    ));
    $this->session->set_userdata('breadcrumb', $current_breadcrumb);
    
    //ambil data content slider..
		$data['slider_content'] = $this->mdl_slider->getSliderContentByIDSlider($id_slider);
    
		//load view edit admin ...
		$content = $this->load->view('admin/slider/manage', $data, true);
		$this->render($content);
	}
  
  public function addContent($id_slider = ''){
    //clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data slider yang akan diedit.
		$data['slider'] = $this->mdl_slider->getSliderByID($id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider'])) || count($data['slider']) < 1){
			redirect('admin_slider/index');
		}
    
    //set breadcrumb
    $this->global_lib->clearBreadcrumb();
    $current_breadcrumb = $this->session->userdata('breadcrumb');
    array_push($current_breadcrumb, array(
      'text' => 'Add slide',
      'href' => '#'
    ));
    $this->session->set_userdata('breadcrumb', $current_breadcrumb);
    
		//load view edit admin ...
		$content = $this->load->view('admin/slider/add_content', $data, true);
		$this->render($content);
  }
  
  public function saveAddContent($id_slider = ''){
    //ambil data slider yang akan diedit.
		$data['slider'] = $this->mdl_slider->getSliderByID($id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider'])) || count($data['slider']) < 1){
			redirect('admin_slider/index');
		}
    
    //validate slider content data
		$this->form_validation->set_rules('text1','', 'htmlentities|strip_tags|trim|max_length[300]|xss_clean');
		$this->form_validation->set_rules('text1_lang1','', 'htmlentities|strip_tags|trim|max_length[300]|xss_clean');
		$this->form_validation->set_rules('text2','', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
		$this->form_validation->set_rules('text2_lang1','', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
		$this->form_validation->set_rules('text3','', 'htmlentities|strip_tags|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('button_text','', 'htmlentities|strip_tags|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('redirect_url','', 'htmlentities|strip_tags|trim|max_length[500]|xss_clean');
		$this->form_validation->set_rules('slider_order','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('slider_pic','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_slider/addContent/' . $id_slider);
		}
		else{
      // --- START uploading picture and generate thumbnail
			$slider_pic = '';
			$slider_pic_thumb = '';
			if (!empty($_FILES['slider_pic']['name'])){
				$config = $this->sliderPicUploadConfig($_FILES['slider_pic']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('slider_pic')){
					$upload_data = array('upload_data' => $this->upload->data());
					$slider_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $data['slider'][0]->width, $data['slider'][0]->height, true);
          $slider_pic_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'], $data['slider'][0]->width_thumb, $data['slider'][0]->height_thumb);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_slider/addContent/' . $id_slider);
				}
			}
      else{
        $message = $this->global_lib->generateMessage("You must upload a file for slide picture", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('admin_slider/addContent/' . $id_slider);
      }
      
      //insert data ke database..
			$insert_data = array(
				'id_slider' => $id_slider,
				'slider_pic' => $slider_pic,
				'slider_pic_thumb' => $slider_pic_thumb,
				'text1' => $this->input->post('text1'),
				'text1_lang1' => $this->input->post('text1_lang1'),
				'text2' => $this->input->post('text2'),
				'text2_lang1' => $this->input->post('text2_lang1'),
				'text3' => $this->input->post('text3'),
				'button_text' => $this->input->post('button_text'),
				'redirect_url' => $this->input->post('redirect_url'),
				'slider_order' => $this->input->post('slider_order')
			);
			$this->mdl_slider->insertSliderContent($insert_data);
		
      $message = $this->global_lib->generateMessage("New slide has been added.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_slider/manage/' . $id_slider);
    }
  }
  
  public function editContent($id_slider = '', $id_slider_content = ''){
    //clear search session yang lama..
		$this->clearSearchSession();
		
		//ambil data slider yang akan diedit.
		$data['slider'] = $this->mdl_slider->getSliderByID($id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider'])) || count($data['slider']) < 1){
			redirect('admin_slider/index');
		}
    
    //ambild ata slider content.
    $data['slider_content'] = $this->mdl_slider->getSliderContentByID($id_slider_content, $id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider_content'])) || count($data['slider_content']) < 1){
			redirect('admin_slider/manage/' . $id_slider);
		}
    
    //set breadcrumb
    $this->global_lib->clearBreadcrumb();
    $current_breadcrumb = $this->session->userdata('breadcrumb');
    array_push($current_breadcrumb, array(
      'text' => 'Edit slide',
      'href' => '#'
    ));
    $this->session->set_userdata('breadcrumb', $current_breadcrumb);
    
		//load view edit admin ...
		$content = $this->load->view('admin/slider/edit_content', $data, true);
		$this->render($content);
  }
  
  public function saveEditContent($id_slider = '', $id_slider_content = ''){
    //ambil data slider yang akan diedit.
		$data['slider'] = $this->mdl_slider->getSliderByID($id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider'])) || count($data['slider']) < 1){
			redirect('admin_slider/index');
		}
    
    //ambild ata slider content.
    $data['slider_content'] = $this->mdl_slider->getSliderContentByID($id_slider_content, $id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider_content'])) || count($data['slider_content']) < 1){
			redirect('admin_slider/manage/' . $id_slider);
		}
    
    //validate slider content data
		$this->form_validation->set_rules('text1','', 'htmlentities|strip_tags|trim|max_length[300]|xss_clean');
		$this->form_validation->set_rules('text1_lang1','', 'htmlentities|strip_tags|trim|max_length[300]|xss_clean');
		$this->form_validation->set_rules('text2','', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
		$this->form_validation->set_rules('text2_lang1','', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
		$this->form_validation->set_rules('text3','', 'htmlentities|strip_tags|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('button_text','', 'htmlentities|strip_tags|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('redirect_url','', 'htmlentities|strip_tags|trim|max_length[500]|xss_clean');
		$this->form_validation->set_rules('slider_order','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('slider_pic','', 'htmlentities|strip_tags|trim|xss_clean');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('admin_slider/editContent/' . $id_slider . '/' . $id_slider_content);
		}
		else{
      // --- START uploading picture and generate thumbnail
			$slider_pic = '';
			$slider_pic_thumb = '';
			if (!empty($_FILES['slider_pic']['name'])){
				$config = $this->sliderPicUploadConfig($_FILES['slider_pic']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('slider_pic')){
					$upload_data = array('upload_data' => $this->upload->data());
					$slider_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $data['slider'][0]->width, $data['slider'][0]->height, true);
          $slider_pic_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'], $data['slider'][0]->width_thumb, $data['slider'][0]->height_thumb);
				}
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('admin_slider/editContent/' . $id_slider . '/' . $id_slider_content);
				}
			}
      
      $update_data = array(
        'text1' => $this->input->post('text1'),
        'text1_lang1' => $this->input->post('text1_lang1'),
				'text2' => $this->input->post('text2'),
				'text2_lang1' => $this->input->post('text2_lang1'),
				'text3' => $this->input->post('text3'),
				'button_text' => $this->input->post('button_text'),
				'redirect_url' => $this->input->post('redirect_url'),
				'slider_order' => $this->input->post('slider_order')
      );
      if(strlen(trim($slider_pic)) > 0){
        $update_data['slider_pic'] = $slider_pic;
        $update_data['slider_pic_thumb'] = $slider_pic_thumb;
      }
      $this->mdl_slider->updateSliderContent($update_data, $id_slider_content);
      
      $message = $this->global_lib->generateMessage("Slide has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('admin_slider/editContent/' . $id_slider . '/' . $id_slider_content);
    }
  }
  
  public function deleteContent($id_slider = '', $id_slider_content = ''){
    //ambil data slider yang akan diedit.
		$data['slider'] = $this->mdl_slider->getSliderByID($id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider'])) || count($data['slider']) < 1){
			redirect('admin_slider/index');
		}
    
    //ambild ata slider content.
    $data['slider_content'] = $this->mdl_slider->getSliderContentByID($id_slider_content, $id_slider);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['slider_content'])) || count($data['slider_content']) < 1){
			redirect('admin_slider/manage/' . $id_slider);
		}
    
    //hapus slider content
    $this->mdl_slider->deleteSliderContent($id_slider_content);
    
    //hapus file.
    @unlink('assets/slider/' . $data['slider_content'][0]->slider_pic);
    @unlink('assets/slider/thumb/' . $data['slider_content'][0]->slider_pic_thumb);
    
    $message = $this->global_lib->generateMessage("Slide has been deleted.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('admin_slider/manage/' . $id_slider);
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
  
  private function sliderPicUploadConfig($filename=''){
		$config['upload_path'] = './assets/slider/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '12000';
		$config['max_width']  = '8000';
		$config['max_height']  = '8000';
		if(strlen(trim($filename)) > 0){
			$config['file_name']  = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', preg_replace("/[^a-zA-Z0-9]+/", "", $filename));
		}
		return $config;
	}
	
  private function paginationConfig($total_rows){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_slider/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
	private function searchPaginationConfig($total_row, $per_page){
		$config = $this->global_lib->paginationConfigAdmin();
		$config['base_url'] 		= base_url().'admin_slider/search/';
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
		$this->session->set_userdata('search_slider', $search_param);
	}
}
