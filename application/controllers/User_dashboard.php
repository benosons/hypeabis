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

class User_dashboard extends CI_Controller {
	
  var $js = array();
  var $css = array();
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
    $this->load->model('mdl_user');
		
		//construct script..
		if($this->session->userdata('user_logged_in') !== true){
      $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())) , "=");
			redirect("page/login/" . $redirect_url);
		}
    
    //set page state
		$this->session->set_userdata(array('page_state' => 'Dashboard'));
  }
	
	public function index(){
    $this->load->model('mdl_verifiedmembersubmission');

    $data['global_data'] = $this->global_lib->getGlobalData();
    
    //ambil data user
		$id_user = $this->session->userdata('id_user');
		$data['user'] = $this->mdl_user->getUserByID($id_user);
		$data['can_be_verified_member'] = $this->mdl_user->canBeVerifiedMember($id_user);
		$data['latest_submission'] = $this->mdl_verifiedmembersubmission->latest($id_user);
    
    //ambil level user..
    $point = $data['user'][0]->point;
    $level = $this->mdl_user->getUserLevelByPoint($point);
    if(isset($level[0]->id_level) && $level[0]->id_level > 0){
      $data['point'] = $point;
      $data['level_name'] = $level[0]->level_name;
      $data['bg_color'] = $level[0]->bg_color;
      $data['text_color'] = $level[0]->text_color;
    }
    
		//load dashboard view
		$content = $this->load->view('user/dashboard/dashboard', $data, true);
		$this->render($content);
	}
  
  public function terms(){
    //load terms & conditions view
		$content = $this->load->view('user/dashboard/terms', null, true);
		$this->render($content);
  }
  
  private function render($page = null){
    if(isset($page) && $page !== null){
      //load page view
      $data['content'] = $page;
      
      //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
      $data['css_files'] = $this->css;
      $data['js_files'] = $this->js;
      
      //load global data
      $data['global_data'] = $this->global_lib->getGlobalData();
      
      //load view template
      $this->load->view('user/template', $data);
    }
    else{
      redirect('page/index');
    }
  }
	
}
