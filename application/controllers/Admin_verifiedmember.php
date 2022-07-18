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

class Admin_verifiedmember extends CI_Controller {
  var $js = array();
  var $css = array();
  var $module_name = 'admin_verifiedmember';
  
	public function __construct()
	{
    parent::__construct();
    
		//load model..
		$this->load->model('mdl_verifiedmembersubmission');
		
    /* DO NOT CHANGE THIS SECTION */
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    if(strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1'){
      redirect('admin_dashboard/index');
    }
  }
	
	public function index()
	{
		$pagination_config = $this->global_lib->paginationConfigAdmin();
		$pagination_config['base_url'] = base_url() . 'admin_verifiedmember/index/';
		$pagination_config['total_rows'] = $this->mdl_verifiedmembersubmission->count();
		$pagination_config['per_page'] = 20;
		$pagination_config['uri_segment'] = 3;

		$this->pagination->initialize($pagination_config);

		$offset = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
		$submissions = $this->mdl_verifiedmembersubmission->all($pagination_config['per_page'], $offset);
    
		$this->render($this->load->view('admin/verifiedmember/all', compact('submissions', 'offset'), true));
	}

	public function edit($id)
	{
		$submission = $this->_findOrFail($id);
		$form_value = $this->session->flashdata('form_value') ?: $submission;
		$this->render($this->load->view('admin/verifiedmember/edit', compact('submission', 'form_value'), true));
	}

	public function saveEdit($id)
	{
		$submission = $this->_findOrFail($id);
		$data = [
			'is_accepted' => $this->input->post('is_accepted'),
			'reject_description' => $this->input->post('is_accepted') === '1' ? NULL : $this->input->post('reject_description'),
		];
		$this->session->set_flashdata(['form_value' => (object) $data]);

		$this->form_validation->set_rules(
			'is_accepted',
			'',
			'htmlentities|strip_tags|trim|xss_clean|required|in_list[1,0]'
		);
		$this->form_validation->set_rules(
			'reject_description',
			'',
			'htmlentities|strip_tags|trim|xss_clean' . ($data['is_accepted'] === '0' ? '|required' : '')
		);

		if ($this->form_validation->run())
		{
				$this->load->model('mdl_user');
				$this->load->library('email_lib');

				$message = 'accepted';
				$reject_description = $this->input->post('reject_description');

				$email_config = $data;
				$email_config['email'] = $this->mdl_user->getEmailByID($submission->id_user);

				$this->db->trans_start();
				$this->mdl_verifiedmembersubmission->update($data, $id);

				if ($data['is_accepted'] === '1') {
					$this->mdl_user->updateUser(['verified' => 1], $submission->id_user);
				}
				$this->db->trans_complete();
				$this->email_lib->sendVerifiedMemberVerification($email_config);

				$this->session->set_flashdata(
					'message',
					$this->global_lib->generateMessage("Verified Member Submission has been {$message}", "info")
				);

				redirect('admin_verifiedmember');
		}
		else
		{
			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
			);

			redirect("admin_verifiedmember/edit/{$id}");
		}
	}

	public function delete($id)
	{
		$this->_findOrFail($id);

		$this->mdl_verifiedmembersubmission->delete($id);

		$this->session->set_flashdata(
			'message',
			$this->global_lib->generateMessage("Verified Member Submission has been deleted.", "info")
		);

		redirect('admin_verifiedmember');
	}
	
	private function _findOrFail($id)
	{
		$submission = $this->mdl_verifiedmembersubmission->find($id);

		if (is_null($submission))
		{
			redirect('admin_verifiedmember');
		}

		return $submission;
	}
	
  /* DO NOT CHANGE THIS SECTION */
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
}
