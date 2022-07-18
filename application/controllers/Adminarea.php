<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminarea extends CI_Controller {
  
  var $js = array();
  var $css = array();
  
  public function __construct(){
    parent::__construct();
    //load library..
    
    //load model..
    $this->load->model('mdl_admin');
  }
  
	public function index($token = ''){
    //jika sudah login, redirect ke dashboard..
    if($this->session->userdata('admin_logged_in') === true){
      redirect("admin_dashboard");
    }
    
    //load library recaptcha.
		$this->load->library('recaptcha');
    
    $admin_url = $this->uri->segment(1);
    $data['global_data'] = $this->global_lib->getGlobalData();
    if($token == $data['global_data'][0]->admin_token && strtolower($admin_url) == 'hypeadmin'){
      $this->load->view('admin/login', $data);
    }
    else{
      redirect(base_url());
      // echo $admin_url;
      // print_r("<pre>");
      // print_r($data);
      // print_r("</pre>");
    }
	}
  
  public function validateLogin(){
    $token = $this->input->post('token');
    $this->form_validation->set_rules('token','', 'htmlentities|strip_tags|trim|required|xss_clean');
    $this->form_validation->set_rules('username','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			$message =  $this->global_lib->generateMessage("Login failed, please try again.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('hypeadmin/' . $token);
		}
		else{
      $token = $this->input->post('token');
      $username = $this->input->post('username');
			$password = sha1($this->input->post('password') . $this->config->item('binary_salt'));

      
      //cek captcha..
      $this->load->library('recaptcha');
      $captcha_answer = $this->input->post('g-recaptcha-response');
      $response = $this->recaptcha->verifyResponse($captcha_answer);
      // Processing captcha..
      if ((! $response['success']) && false) {
        $message =  $this->global_lib->generateMessage("Please check the recaptcha for validation", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('hypeadmin/' . $token);
      }
			
			//ambil data admin bedasarkan username dan password
			$admin_data = $this->mdl_admin->getAdminByUsernameAndPassword($username, $password);
			
			if(count($admin_data) > 0){ //data ada, cek akun aktif atau tidak
				if($admin_data[0]->status == '1'){ //loign berhasil
				
					//ambil data global..
					$global_data = $this->global_lib->getGlobalData();
					
          $login_data = array(
						"admin_logged_in"=>true,
						"id_admin"=>$admin_data[0]->id_admin,
						"admin_name"=>$admin_data[0]->name,
						"admin_username"=>$admin_data[0]->username,
						"admin_email"=>$admin_data[0]->email,
						"admin_level"=>$admin_data[0]->level,
						"admin_grant"=>$admin_data[0]->grant,
						"admin_photo"=>(strlen(trim($admin_data[0]->admin_photo)) > 0 ? $admin_data[0]->admin_photo : 'default.png'),
						"admin_page_state" => "Dashboard",
						"admin_login_time" => date("Y-m-d H:i:s"),
						"global_logo" => $global_data[0]->logo,
						"global_cover" => $global_data[0]->cover,
						"global_website_name" => $global_data[0]->website_name,
					);
					$this->session->set_userdata($login_data);
					
					redirect("admin_dashboard/index");
				}
				else{
          $message =  $this->global_lib->generateMessage("Your account status was not active / banned. Please contact super admin for information.", "danger");
					$this->session->set_flashdata('message', $message);
					redirect('hypeadmin/' . $token);
				}
			}
			else{ // login gagal
        $message =  $this->global_lib->generateMessage("We don’t recognize that username or password, please try again.", "danger");
				$this->session->set_flashdata('message', $message);
				redirect('hypeadmin/' . $token);
			}
		}
	}
	
  public function forgot(){
    //jika sudah login, redirect ke dashboard..
    if($this->session->userdata('admin_logged_in') === true){
      redirect("admin_dashboard");
    }
    
    $data['global_data'] = $this->global_lib->getGlobalData();
		$this->load->view('admin/forgot', $data);
	}
  
  public function submitForgotPassword(){
    $this->form_validation->set_rules('email','email', 'htmlentities|strip_tags|trim|required|xss_clean|valid_email');
		if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("<strong>Form validation failed. </strong>Please make sure the email you enter are in the right format.", "danger");
      $this->session->set_flashdata('message', $message);
      redirect('adminarea/forgot');
    }
		else{
      //ambil data user by email.
      $email = $this->input->post('email');
      $admin = $this->mdl_admin->getAdminByEmail($email);
      
      if(isset($admin[0]->id_admin) && $admin[0]->id_admin > 0){
        // buat hash admin..
        $admin_hash = sha1($admin[0]->password . $this->config->item('binari_salt'));
        
        //update forgot password limit.
        $update_data = array(
          'hash' => $admin_hash,
          'forgot_password_limit' => date('Y-m-d H:i:s', strtotime('now +1 hour'))
        );
        $this->mdl_admin->updateAdmin($update_data, $admin[0]->id_admin);
        
        //kirim email konfirmasi reset password
        $this->load->library('email_lib');
        $config = array(
          'hash' => $admin_hash,
          'email' => $admin[0]->email,
        );
        $send_email = $this->email_lib->resetAdminPasswordViaURL($config, false);
        
        $message =  $this->global_lib->generateMessage("Please check you email to reset your password", "info");
        $this->session->set_flashdata('message', $message);
        redirect('adminarea/forgot');
      }
      else{
        $message =  $this->global_lib->generateMessage("Email not found", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('adminarea/forgot');
      }
    }
  }
  
  public function resetPassword($hash = ''){
    //jika sudah login, redirect ke dashboard..
    if($this->session->userdata('admin_logged_in') === true){
      redirect("admin_dashboard");
    }
    
    if($hash == null || $hash == '')redirect('adminarea');
    
    //cek user bedasarkan hash..
		$check_admin = $this->mdl_admin->checkAdminByHash($hash);
		if($check_admin){
			//jika user ditemukan di database, ambil data user..
			$admin = $this->mdl_admin->getAdminByHash($hash);
      
      $data['global_data'] = $this->global_lib->getGlobalData();
      
      //cek limit URL forgot password.
      $now = strtotime(date('Y-m-d H:i:s'));
			if($now <= strtotime($admin[0]->forgot_password_limit)){
        $this->load->view('admin/reset_password', $data);
			}
      else{
        $message =  $this->global_lib->generateMessage("Your password reset URL has been expired. Please request for pasword reset again", "danger");
        $this->session->set_flashdata('message', $message);
        
        $this->load->view('admin/forgot', $data);
      }
		}
		else{
			redirect('admin');
		}
  }
  
  public function submitResetPassword($hash = ''){
    //jika sudah login, redirect ke dashboard..
    if($this->session->userdata('admin_logged_in') === true){
      redirect("admin_dashboard");
    }
    
    if($hash == null || $hash == '')redirect('adminarea');
    
    //cek user bedasarkan hash..
		$check_admin = $this->mdl_admin->checkAdminByHash($hash);
		if($check_admin){
      //validasi..
      $this->form_validation->set_rules('new_password','password', 'htmlentities|strip_tags|trim|required|xss_clean');
      $this->form_validation->set_rules('confirm_password','password', 'htmlentities|strip_tags|trim|required|xss_clean|matches[new_password]');
      if ($this->form_validation->run() == FALSE){
        $message =  $this->global_lib->generateMessage("Failed to reset password. Pease try again", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('adminarea/resetPassword/' . $hash);
      }
      else{
        //jika user ditemukan di database, ambil data user..
        $admin = $this->mdl_admin->getAdminByHash($hash);
        
        //update password..
        $new_password = $this->input->post('new_password');
        $update_data = array(
          'password' => sha1($new_password . $this->config->item('binary_salt'))
        );
        $this->mdl_admin->updateAdmin($update_data, $admin[0]->id_admin);
        
        $message =  $this->global_lib->generateMessage("Your password has been updated.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('adminarea');
      }
    }
  }
  
	public function logout(){
    if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    
		$login_data = array(
			"admin_logged_in"=>false,
      "id_admin"=>null,
      "admin_username"=>null,
      "admin_name"=>null,
      "admin_email"=>null,
      "admin_level"=>null,
      "admin_grant"=>null,
      "admin_photo"=>null,
      "admin_page_state" => null,
      "admin_login_time" => null
		);
		$this->session->set_userdata($login_data);
		$this->session->sess_destroy();
    
    // print_r('<pre>');
    // print_r($this->session->all_userdata());
    // print_r('</pre>');
		
		redirect('adminarea/index');
	}
  
}
