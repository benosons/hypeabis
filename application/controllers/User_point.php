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

class User_point extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 10;
  
  public function __construct(){
    parent::__construct();
		
		//load library
    $this->load->model('mdl_category');
		$this->load->model('mdl_point');
		$this->load->model('mdl_user');
    
		//construct script..
		if($this->session->userdata('user_logged_in') !== true){
      $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())) , "=");
			redirect("page/login/" . $redirect_url);
		}
  }
	
	public function index(){
    $id_user = $this->session->userdata('id_user');
    $data['user'] = $this->mdl_user->getUserByIDArr($id_user);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['user'])) || count($data['user']) < 1){
			redirect('user_dashboard');
		}
    
    //ambil list informasi point..
		$data['points'] = $this->mdl_point->getAllPoint($id_user);
    
    //ambil data histori point..
    $data['total_row'] = $this->mdl_point->getAllPointHistoryCount($id_user);
		$config = $this->paginationConfig($data['total_row']);
		$this->pagination->initialize($config);
    $data['histories'] = $this->mdl_point->getAllPointHistoryLimit($id_user, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));
		
    //ambil level user..
    $level = $this->mdl_user->getUserLevelByPoint($data['user'][0]['point']);
    if(isset($level[0]->id_level) && $level[0]->id_level > 0){
      $data['user'][0]['level'] = $level[0]->level_name;
      $data['user'][0]['bg_color'] = $level[0]->bg_color;
      $data['user'][0]['text_color'] = $level[0]->text_color;
    }
      
		//load view.
    $content = $this->load->view('user/point/all', $data, true);
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
      
      //load view template
      $this->load->view('user/template', $data);
    }
    else{
      redirect('page/index');
    }
  }
  
  private function paginationConfig($total_rows){
		$config = $this->global_lib->paginationConfig();
		$config['base_url'] 		= base_url().'user_point/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
}
