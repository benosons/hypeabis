<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_404 extends CI_Controller {
  
  var $js = array();
  var $css = array();
  
  public function __construct(){
    parent::__construct();
    
    //load config
    
    //load library..
    $this->load->library('frontend_lib');
    
    //load model..
    $this->load->model('mdl_user');
    $this->load->model('mdl_content');
    $this->load->model('mdl_category');
  }
  
	public function index(){
    //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
    $data['css_files'] = $this->css;
    $data['js_files'] = $this->js;
    
    //load module global data
    $data['global_data'] = $this->global_lib->getGlobalData();
    
    //get category (for menu)
    $data['categories'] = $this->mdl_category->getAllCategoryParentArr();
    foreach($data['categories'] as $x => $category){
      $data['categories'][$x]['child'] = $this->mdl_category->getCategoryChildArr($category['id_category']);
    }
    
    //load view template
    $this->load->view('frontend/template_404', $data);
	}
  
}
