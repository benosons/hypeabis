<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Devarea extends CI_Controller {
  
  var $js = array();
  var $css = array();
  
  public function __construct(){
    parent::__construct();
    //load library..
    
    //load model..
  }
  
	public function index(){
    //jika sudah login, redirect ke dashboard..
    if($this->session->userdata('dev_logged_in') === true){
      redirect("dev_dashboard");
    }
    
		$this->load->view('dev/login');
	}
  
  public function validateLogin(){
		$this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			$message =  $this->global_lib->generateMessage("Login failed, please try again.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('devarea/index');
		}
		else{
			$pass = $this->input->post('password');
			$encrypted_pass = sha1($pass . $this->config->item('binari_salt'));
			
			if($encrypted_pass == $this->config->item('binari_devpass')){
				$login_data = array(
					"dev_logged_in"=>true
				);
				$this->session->set_userdata($login_data);
				redirect('dev_dashboard/index');
			}
			else{
				$message =  $this->global_lib->generateMessage("Wrong password. please try again", "danger");
				$this->session->set_flashdata('message', $message);
				redirect('devarea/index');
			}
		}
	}
	
	public function logout(){
		$login_data = array(
			"dev_logged_in"=>false
		);
		$this->session->set_userdata($login_data);
		$this->session->sess_destroy();
		
		redirect('devarea/index');
	}
  
}
