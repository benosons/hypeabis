<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_verifiedmembersetting extends CI_Controller {
	private $css = [];
	private $js = [];
	private $module_name = 'admin_verifiedmembersetting';

	public function __construct()
	{
		parent::__construct();

		$this->load->model('mdl_global');

		/* DO NOT CHANGE THIS SECTION */
		if ($this->session->userdata('admin_logged_in') !== true)
		{
			redirect("adminarea/index");
		}
		if (strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1')
		{
			redirect('admin_dashboard/index');
		}
	}
	public function index()
	{
		$form_value = $this->session->flashdata('form_value') ?: [
			'verified_member_point' => $this->mdl_global->getVerifiedMemberPoint()
		];

		$this->_render($this->load->view('admin/verifiedmembersetting/detail', compact('form_value'), true));
	}

	public function update()
	{
		$this->session->set_flashdata(['form_value' => $this->input->post()]);

		$this->form_validation->set_rules('verified_member_point', '', 'htmlentities|strip_tags|trim|xss_clean|required|integer');

		if ($this->form_validation->run())
		{
			$this->mdl_global->updateGlobalData(['verified_member_point' => $this->input->post('verified_member_point')]);

			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage("Minimum Point for Verified Member has been updated", "info")
			);
		}
		else
		{
			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
			);
		}

		redirect("admin_verifiedmembersetting");
	}

	private function _render($page = null)
	{
		if (isset($page) && $page !== null)
		{
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
		else
		{
			redirect('page/index');
		}
	}
}
