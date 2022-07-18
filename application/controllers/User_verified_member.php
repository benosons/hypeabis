<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_verified_member extends CI_Controller {
	private $css = [];
	private $js = [];
  public $user_pic_width = 300;
	public $user_pic_height = 300;
 
	public function __construct()
	{
		parent::__construct();

		$this->load->model('mdl_user');
		$this->load->model('mdl_job');
		$this->load->model('mdl_jobfield');
		$this->load->model('mdl_interest');
	}
	
	public function index()
	{
		$id_user = $this->session->userdata('id_user');
		if (!$this->mdl_user->canBeVerifiedMember($id_user))
		{
			redirect('user_dashboard');
		}
    $data['user'] = $this->mdl_user->getUserByID($id_user)[0];
    
    $data['job'] = $this->mdl_job->getAllJob();
    $data['jobfield'] = $this->mdl_jobfield->getAllJobfield();
    $data['interest'] = $this->mdl_interest->getAllInterest();

		$this->_render($this->load->view('user/verified_member/detail', $data, TRUE));
	}

	public function submit()
	{
		$this->load->helper('file');
		$id_user = $this->session->userdata('id_user');
		if (!$this->mdl_user->canBeVerifiedMember($id_user)){
			redirect('user_dashboard');
		}

		$user = $this->mdl_user->getUserByID($id_user)[0];
    $this->form_validation->set_rules('name', 'Nama lengkap', 'htmlentities|strip_tags|trim|xss_clean|required');
    $this->form_validation->set_rules('contact_number', 'Nomor kontak', 'htmlentities|strip_tags|trim|xss_clean|required');
    $this->form_validation->set_rules('address', 'Alamat', 'htmlentities|strip_tags|trim|xss_clean|required');
    $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'htmlentities|strip_tags|trim|xss_clean|required');
    $this->form_validation->set_rules('dob', 'Tanggal lahir', 'htmlentities|strip_tags|trim|xss_clean|required');
    $this->form_validation->set_rules('id_job', 'Pekerjaan', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
    $this->form_validation->set_rules('id_jobfield', 'Bidang pekerjaan', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
    $this->form_validation->set_rules('id_interest', 'Interest', 'htmlentities|strip_tags|trim|xss_clean|integer|required');
		$this->form_validation->set_rules('picture', 'Foto Profil', [['_is_image',function($value) use ($user){return $this->_is_image($value, 'picture', empty($user->picture));}]]);
		$this->form_validation->set_rules('ktp_picture', 'Foto KTP', 'callback__is_image[ktp_picture]');
		if ($this->form_validation->run()){
			$upload_config = [
				'upload_path' => './assets/user/',
				'allowed_types' => 'jpg|jpeg|png|gif',
				'max_size' => '12000',
				'max_width'  => '8000',
				'max_height'  => '8000',
			];

			if (empty($user->picture)){
				$upload_config['file_name'] = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $_FILES['picture']['name']);
				$this->upload->initialize($upload_config);
				if ($this->upload->do_upload('picture')){
					$upload_data = $this->upload->data();
					$this->picture->resizePhoto($upload_data['full_path'], $this->user_pic_width, $this->user_pic_height, FALSE);
				}
				else{
					$this->session->set_flashdata(
						'message',
						$this->global_lib->generateMessage(
							'Gagal melakukan upload Foto Profil. <br/> cause: ' . $this->upload->display_errors(),
							'danger'
						)
					);
          redirect('user_verified_member');
        }

				$this->mdl_user->updateUser(
          [
            'picture_from' => 'web', 
            'picture' => $upload_data['file_name']
          ], 
          $id_user
        );
				$this->session->set_userdata('user_picture_from', 'web');
				$this->session->set_userdata('user_picture', $upload_config['file_name']);
			}

			$upload_config['upload_path'] = './assets/user-ktp/';
			$upload_config['file_name'] = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $_FILES['ktp_picture']['name']);
			$this->upload->initialize($upload_config);
			if ($this->upload->do_upload('ktp_picture')){
				$upload_config['file_name'] = $this->upload->data()['file_name'];
			}
			else{
				$this->session->set_flashdata(
					'message',
					$this->global_lib->generateMessage(
						'Gagal melakukan upload foto identitas. <br/> cause: ' . $this->upload->display_errors(),
						'danger'
					)
				);
				redirect('user_verified_member');
      }
      
      //ambil data DOB
      $dob = $this->input->post('dob');
      $is_valid_date = $this->global_lib->validateDate($dob, 'd-m-Y');
      if($is_valid_date){
        $dob = $this->global_lib->formatDate($dob, 'd-m-Y', 'Y-m-d');
      }

      //update data user
      $this->mdl_user->updateUser(
        [
          'name' => $this->input->post("name"),
          'contact_number' => $this->input->post("contact_number"),
          'address' => $this->input->post("address"),
          'tempat_lahir' => $this->input->post("tempat_lahir"),
          'dob' => $dob,
          'id_job' => $this->input->post("id_job"),
          'id_jobfield' => $this->input->post("id_jobfield"),
          'id_interest' => $this->input->post("id_interest")
        ], 
        $id_user
      );

      //apply verified member
			$this->mdl_user->applyVerifiedMember($id_user, $upload_config['file_name']);
			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage('Pengajuan Verified Member anda sudah disubmit.', 'info')
			);
			redirect('user_dashboard');
		}
		else{
			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage('Validasi form tidak valid. Form harus diisi dengan lengkap.', 'danger')
			);
			redirect('user_verified_member');
		}
	}
	
	public function _is_image($value, $input_name, $is_required = TRUE)
	{
		$labels = ['picture' => 'Foto Profil', 'ktp_picture' => 'Foto KTP'];
		$allowed_mime_type_arr = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png'];

		if (!empty($_FILES[$input_name]['name']))
		{
			$mime = get_mime_by_extension($_FILES[$input_name]['name']);

			if (in_array($mime, $allowed_mime_type_arr))
			{
				return true;
			}
			else
			{
				$this->form_validation->set_message('_is_image', 'Please select only pdf/gif/jpg/png file for {field}.');
				return false;
			}
		}
		elseif ($is_required)
		{
			$this->form_validation->set_message('_is_image', "Please choose a file to upload for {field}.");
			return false;
		}
	}
	
	private function _render($page = null)
	{
		if(isset($page) && $page !== null)
		{
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
    else
		{
      redirect('page/index');
    }
  }
}
