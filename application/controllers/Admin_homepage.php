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

class Admin_homepage extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 20;
  
  var $module_name = 'admin_homepage';
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
		$this->load->model('mdl_user');
		$this->load->model('mdl_content');
		$this->load->model('mdl_homepage');
    
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    if(strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1'){
      redirect('admin_dashboard/index');
    }
  }
	
  /*========== SECTION 1: FEATURED KONTEN ============*/
  public function index($tab = ''){
    //ambi featured article
    $data['featured_article'] = $this->mdl_content->getHomepageFeaturedArticle();
    //trending article
    $data['trending_article'] = $this->mdl_content->getHomepageTrendingArticleAll();
    //recommended
    $data['recommended_article'] = $this->mdl_content->getHomepageRecommendedArticleAll();
    //penulis pilihan..
    $data['featured_author'] = $this->mdl_content->getHomepageFeaturedAuthorAll();
		
		//load view all module
		$content = $this->load->view('admin/homepage/all', $data, true);
		
		$this->render($content);
	}
  
  public function deleteFeatured($id_content = ''){
    //ambil data content yang akan diedit.
		$data['content'] = $this->mdl_content->getContentByID($id_content);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['content'])) || count($data['content']) < 1){
			redirect('admin_homepage/index');
		}
    
    $update_data = array(
      'featured_on_homepage' => 0
    );
    $this->mdl_content->updateContent($update_data, $id_content);
    
    $message =  $this->global_lib->generateMessage("Konten berhasil dihapus dari homepage.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('admin_homepage/index/0');
  }
  
  public function deleteTrending($id_content = ''){
    //ambil data content yang akan diedit.
		$data['content'] = $this->mdl_content->getContentByID($id_content);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['content'])) || count($data['content']) < 1){
			redirect('admin_homepage/index');
		}
    
    $update_data = array(
      'trending' => 0
    );
    $this->mdl_content->updateContent($update_data, $id_content);
    
    $message =  $this->global_lib->generateMessage("Konten berhasil dihapus dari section trending.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('admin_homepage/index/1');
  }
  
  public function deleteRecommended($id_content = ''){
    //ambil data content yang akan diedit.
		$data['content'] = $this->mdl_content->getContentByID($id_content);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['content'])) || count($data['content']) < 1){
			redirect('admin_homepage/index');
		}
    
    $update_data = array(
      'recommended' => 0
    );
    $this->mdl_content->updateContent($update_data, $id_content);
    
    $message =  $this->global_lib->generateMessage("Konten berhasil dihapus dari section rekomendasi.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('admin_homepage/index/2');
  }
  
  public function addFeaturedAuthor(){
    $this->form_validation->set_rules('id_user','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		$this->form_validation->set_rules('author_order','', 'htmlentities|strip_tags|trim|xss_clean|required|integer');
		if ($this->form_validation->run() == FALSE){
			redirect('admin_homepage/index/3');
		}
		else{
      $id_user = $this->input->post('id_user');
      $data['user'] = $this->mdl_user->getUserByID($id_user);
      //jika tidak ada data, redirect ke index.
      if((! is_array($data['user'])) || count($data['user']) < 1){
        redirect('admin_homepage/index/3');
      }
      
      //check apakah sudah ada di tbl featured sebelumnya..
      $author = $this->mdl_homepage->getFeaturedAuthorByIDUser($id_user);
      if(isset($author[0]->id_author_homepage) && $author[0]->id_author_homepage > 0){
        $update_data = array(
          'author_order' => $this->input->post('author_order')
        );
        $this->mdl_homepage->updateFeaturedAuthor($update_data, $author[0]->id_author_homepage);
      }
      else{
        $insert_data = array(
          'id_user' => $this->input->post('id_user'),
          'author_order' => $this->input->post('author_order')
        );
        $this->mdl_homepage->insertFeaturedAuthor($insert_data);
      }
      
      $message =  $this->global_lib->generateMessage("User berhasil ditambahkan ke section penulis pilihan.", "info");
      $this->session->set_flashdata('message', $message);
      redirect('admin_homepage/index/3');
    }
  }
  
  public function deleteFeaturedAuthor($id_user = ''){
    //ambil data user yang akan diedit.
		$data['user'] = $this->mdl_user->getUserByID($id_user);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['user'])) || count($data['user']) < 1){
			redirect('admin_homepage/index');
		}
    
    $this->mdl_homepage->deleteHomepageFeaturedAuthor($id_user);
    
    $message =  $this->global_lib->generateMessage("User berhasil dihapus dari section penulis pilihan.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('admin_homepage/index/3');
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
  
  private function homepagePicUploadConfig($filename=''){
		$config['upload_path'] = './assets/homepage/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '12000';
		$config['max_width']  = '8000';
		$config['max_height']  = '8000';
		if(strlen(trim($filename)) > 0){
			$config['file_name']  = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
		}
		return $config;
	}
}
