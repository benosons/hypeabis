<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimage extends CI_Controller {

  var $pic_width = 1000;
  var $pic_height = 1000;
  var $pic_thumb_width = 600;
  var $pic_thumb_height = 600;

  public function __construct(){
    parent::__construct();
  }
  
	public function index(){
    $this->load->view('testimage', null);
	}

  public function saveAdd(){
    $this->form_validation->set_rules('file_pic','', 'htmlentities|strip_tags|trim|xss_clean');
    if ($this->form_validation->run() == FALSE){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('Testimage/index');
		}
		else{
      $file_pic = '';
			$file_pic_thumb = '';
			if (!empty($_FILES['file_pic']['name'])){
				$config = $this->testPicUploadConfig($_FILES['file_pic']['name']);
				$this->upload->initialize($config);
				if ($this->upload->do_upload('file_pic')){
					$upload_data = array('upload_data' => $this->upload->data());
					
          //resize photo & buat thumbnail
          $file_pic = $this->picture->resizePhotoWithRatio($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
          $file_pic_thumb = $this->picture->createThumbWithRatio($upload_data['upload_data']['full_path'],$this->pic_thumb_width,$this->pic_thumb_height);

          //add watermark
          $file_pic = $this->picture->generateWatermark($upload_data['upload_data']['full_path'], $file_pic);
          
          $message =  $this->global_lib->generateMessage("<img src='" . base_url() . "assets/example/" . $file_pic . "' />", "info");
					$this->session->set_flashdata('message', $message);
					redirect('Testimage/index');
        }
				else{
          $message =  $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
					$this->session->set_flashdata('message', $message);
					redirect('Testimage/index');
				}
			}
    }
  }

  private function testPicUploadConfig($filename=''){
		$config['upload_path'] = './assets/example/';
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
