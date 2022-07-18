<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {
  
  var $js = array();
  var $css = array();
  var $category_index = 0;
  var $category_list = array();
  
  public function __construct(){
    parent::__construct();
    
    //load config
    
    //load library..
    $this->load->library('frontend_lib');
    
    //load model..
    $this->load->model('mdl_category');
    $this->load->model('mdl_contact');
    $this->load->model('mdl_contactsetting');
  }
  
	public function index(){
    $data = array();
    $controller = $this->uri->segment(1);
    $function = $this->uri->segment(2);
    
    if(strtolower($function) != 'us' && 
      strtolower($function) != 'kolaborasi-bisnis' && 
      strtolower($function) != 'lapor-gangguan'){
        redirect('contact/us');
    }
    
    //ambil id contact setting.
    if(strtolower($function) == 'lapor-gangguan'){
      $id_contactsetting = 1;
    }
    else if(strtolower($function) == 'kolaborasi-bisnis'){
      $id_contactsetting = 2;
    }
    else{
      $id_contactsetting = 3;
    }
    
    //ambil data contactsetting
    $data['contactsetting'] = $this->mdl_contactsetting->getContactsettingByID($id_contactsetting);
    
    //load library recaptcha.
		$this->load->library('recaptcha');
    
    //load view
		$content = $this->load->view('frontend/contact', $data, true);
		$this->render($content);
	}
  
  public function submitContact($id_contactsetting = ''){
    //ambil data contactsetting yang akan diedit.
		$data['contactsetting'] = $this->mdl_contactsetting->getContactsettingByID($id_contactsetting);
		//jika tidak ada data, redirect ke index.
		if((! is_array($data['contactsetting'])) || count($data['contactsetting']) < 1){
			redirect('contact/us');
		}
    
    //ambil id contact setting.
    $contact_url = "";
    if($data['contactsetting'][0]->id_contactsetting == 1){
      $contact_url = 'lapor-gangguan';
    }
    else if($data['contactsetting'][0]->id_contactsetting == 2){
      $contact_url = 'kolaboorasi-bisnis';
    }
    else{
      $contact_url = 'us';
    }
    
    $this->form_validation->set_rules('name','', 'htmlentities|strip_tags|trim|required|xss_clean');
		$this->form_validation->set_rules('phone','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('email','', 'htmlentities|strip_tags|trim|xss_clean|valid_email|required');
		$this->form_validation->set_rules('message','', 'htmlentities|strip_tags|trim|xss_clean|required');
		if($this->form_validation->run() == false){
			$message = "<div class='alert alert-danger'>Gagal menyimpan pesan. Mohon pastikan anda mengisi data dengan format yang benar dan lengkap.</div>";
			$this->session->set_flashdata('message', $message);
			redirect('contact/' . $contact_url);
		}
		else{
      //cek captcha..
			$this->load->library('recaptcha');
			$captcha_answer = $this->input->post('g-recaptcha-response');
			$response = $this->recaptcha->verifyResponse($captcha_answer);
			// Processing captcha ...
			if (! $response['success']) {
				$message = "<div class='alert alert-danger'>Check recaptcha dengan benar.</div>";
        $this->session->set_flashdata('message', $message);
        redirect('contact/' . $contact_url);
			}
			else{
        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');
        $message = $this->input->post('message');
        $submit_date = date('Y-m-d H:i:s');
        $hash = $this->global_lib->generateHash();
        
        //insert data konsultasi ke database
        $insert_data = array(
          'id_contactsetting' => $id_contactsetting,
          'name' => $name,
          'phone' => $phone,
          'email' => $email,
          'message' => $message,
          'submit_date' => $submit_date,
          'hash' => $hash
        );
        $this->mdl_contact->insertContact($insert_data);
        
        //Kirim contact ke email admin
        //ambil id contact.
        $contact_data = $this->mdl_contact->getContactByHash($hash);
        if(isset($contact_data[0]->id_contact)){
          $this->load->library('email_lib');
          $config = array(
            'contact_data' => $contact_data,
            'subject' => $data['contactsetting'][0]->contact_title,
            'email' => $data['contactsetting'][0]->contact_email,
          );
          $send_email = $this->email_lib->sendContact($config, false);
        }
        
        $message = "<div class='alert alert-info'>Terima kasih. <br/>Kami akan segera menindaklanjuti pesan anda.</div>";
        $this->session->set_flashdata('message', $message);
        redirect('contact/' . $contact_url);
      }
    }
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
      
      //get category (for menu)
      $data['categories'] = $this->mdl_category->getAllCategoryParentArr();
      foreach($data['categories'] as $x => $category){
        $data['categories'][$x]['child'] = $this->mdl_category->getCategoryChildArr($category['id_category']);
      }
      $data['categories_filter'] = $this->getAllCategory();
      
      //load view template
      $this->load->view('frontend/template', $data);
    }
    else{
      redirect(base_url());
    }
  }

  private function getAllCategory()
  {
    //ambil data semua module utama / parent..
    $categories = $this->mdl_category->getAllCategoryParentArr();

    //ambil semua category child.
    foreach ($categories as $x => $category) {
      $has_child = $this->mdl_category->hasChild($category['id_category']);
      $categories[$x]['has_child'] = ($has_child ? 1 : 0);

      //cek apakah punya child.
      if ($has_child) {
        $categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
      }
    }

    $level = 0;
    foreach ($categories as $x => $category) {
      $this->category_list[$this->category_index] = $category;
      $this->category_list[$this->category_index]['category_name'] = $category['category_name'];
      $this->category_index++;

      //cek apakah punya child.
      if ($category['has_child'] == 1) {
        $this->generateCategoryChildList($category['child'], $level);
      }
    }

    return $this->category_list;
  }

  private function getCategoryChild($id_category = '')
  {
    $categories = array();
    $categories = $this->mdl_category->getCategoryChildArr($id_category);

    //ambil semua category child.
    foreach ($categories as $x => $category) {
      $has_child = $this->mdl_category->hasChild($category['id_category']);
      $categories[$x]['has_child'] = ($has_child ? 1 : 0);

      //cek apakah punya child.
      if ($has_child) {
        $categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
      }
    }
    return $categories;
  }

  private function generateCategoryChildList($categories, $level)
  {
    $level++;
    $indentation = "";
    for ($x = 0; $x < $level; $x++) {
      $indentation .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    foreach ($categories as $x => $category) {
      $this->category_list[$this->category_index] = $category;
      $this->category_list[$this->category_index]['category_name'] = $indentation . $category['category_name'];
      $this->category_index++;

      //cek apakah punya child.
      if ($category['has_child'] == 1) {
        $this->generateCategoryChildList($category['child'], $level);
      }
    }
  }

}