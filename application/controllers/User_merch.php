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

class User_merch extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $pagination_per_page = 10;
  
  public function __construct(){
    parent::__construct();
		
		//load model
		$this->load->model('mdl_point');
		$this->load->model('mdl_merchandise');
		$this->load->model('mdl_user');
    
    //load library
    $this->load->library('point_lib');
    
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
    
    //ambil level user..
    $level = $this->mdl_user->getUserLevelByPoint($data['user'][0]['point']);
    if(isset($level[0]->id_level) && $level[0]->id_level > 0){
      $data['user'][0]['level'] = $level[0]->level_name;
      $data['user'][0]['bg_color'] = $level[0]->bg_color;
      $data['user'][0]['text_color'] = $level[0]->text_color;
    }
    
    $data['merchandises'] = $this->mdl_merchandise->getPublishedMerchandises();
    
		//load view.
    $content = $this->load->view('user/merchandise/all', $data, true);
		$this->render($content);
	}
  
  public function redeem(){
    $id_user = $this->session->userdata('id_user');
    $data['user'] = $this->mdl_user->getUserByIDArr($id_user);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['user'])) || count($data['user']) < 1){
			redirect('user_dashboard');
		}
    
    //ambil level user..
    $level = $this->mdl_user->getUserLevelByPoint($data['user'][0]['point']);
    if(isset($level[0]->id_level) && $level[0]->id_level > 0){
      $data['user'][0]['level'] = $level[0]->level_name;
      $data['user'][0]['bg_color'] = $level[0]->bg_color;
      $data['user'][0]['text_color'] = $level[0]->text_color;
    }
    
    $data['redeem_requests'] = $this->mdl_merchandise->getRedeemRequestByIDUser($id_user);
    
		//load view.
    $content = $this->load->view('user/merchandise/all_request', $data, true);
		$this->render($content);
  }
  
  public function redeemPoint($id_merchandise = ''){
    $id_user = $this->session->userdata('id_user');
    $data['user'] = $this->mdl_user->getUserByIDArr($id_user);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['user'])) || count($data['user']) < 1){
			redirect('user_dashboard');
		}
    
    //ambil level user..
    $level = $this->mdl_user->getUserLevelByPoint($data['user'][0]['point']);
    if(isset($level[0]->id_level) && $level[0]->id_level > 0){
      $data['user'][0]['level'] = $level[0]->level_name;
      $data['user'][0]['bg_color'] = $level[0]->bg_color;
      $data['user'][0]['text_color'] = $level[0]->text_color;
    }
    
    //ambil data merchandise..
    $data['merchandise'] = $this->mdl_merchandise->getMerchandiseByID($id_merchandise);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['merchandise'])) || count($data['merchandise']) < 1){
			redirect('user_merch');
		}
    
    //check ada request yang masih dalam proses atau tidak..
    $data['redeem_requests'] = $this->mdl_merchandise->getActiveRedeemRequestByIDUser($id_user);
    if(isset($data['redeem_requests']) && is_array($data['redeem_requests']) && count($data['redeem_requests']) > 0){
      $message =  $this->global_lib->generateMessage("Proses penukaran anda masih dalam proses tim Bisnismuda.id", "warning");
			$this->session->set_flashdata('message', $message);
			redirect('user_merch');
    }
    
    //poin cukup atau tidak?
    if($data['merchandise'][0]->merch_point > $data['user'][0]['point']){
      $message =  $this->global_lib->generateMessage("Poin anda belum cukup untuk ditukarkan dengan merchandise yang dipilih.", "warning");
			$this->session->set_flashdata('message', $message);
			redirect('user_merch');
    }
    
    //quota masih ada / tidak?
    if(!($data['merchandise'][0]->merch_quota > 0)){
      $message =  $this->global_lib->generateMessage("Maaf, kuota untuk merchandise ini sudah habis. Silakan pilih merchandise yang lain.", "warning");
			$this->session->set_flashdata('message', $message);
			redirect('user_merch');
    }
    
    //insert data penukaran..
    $insert_data = array(
      'id_merchandise' => $id_merchandise,
      'id_user' => $id_user,
      'point' => $data['merchandise'][0]->merch_point,
      'request_date' => date('Y-m-d H:i:s')
    );
    $this->mdl_merchandise->insertRedeemRequest($insert_data);
    
    //kurangi quota merchandise..
    $update_data = array(
      'merch_quota' => ($data['merchandise'][0]->merch_quota - 1)
    );
    $this->mdl_merchandise->updateMerchandise($update_data, $id_merchandise);
    
    //kurangi dan insert histroy point..
    $this->point_lib->substractPointByIDUser($id_user, $data['merchandise'][0]->merch_point);
    
    redirect('user_merch/redeem');
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
		$config['base_url'] 		= base_url().'user_merch/index/';
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $this->pagination_per_page;
		$config['uri_segment']		= 3;
		return $config;
	}
	
}
