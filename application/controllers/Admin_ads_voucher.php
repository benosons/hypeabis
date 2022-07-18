<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_ads_voucher extends CI_Controller {
  private $module_name = 'admin_ads_voucher';
  private $base_url;

  public $css = [];
  public $js = [];

  public function __construct()
  {
    parent::__construct();
    $this->base_url = base_url() . $this->uri->segment(1);

    $this->load->model('mdl_ads_voucher');

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
    $pagination_config = $this->global_lib->paginationConfigAdmin();
    $pagination_config['base_url'] = "{$this->base_url}/index/";
    $pagination_config['total_rows'] = $this->mdl_ads_voucher->count();
    $pagination_config['per_page'] = 20;
    $pagination_config['uri_segment'] = 3;

    $this->pagination->initialize($pagination_config);

    $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
    $this->data['ads_vouchers'] = $this->mdl_ads_voucher->all($pagination_config['per_page'], $this->data['offset']);

    $this->_render('admin/ads_voucher/all');
  }

  public function add()
  {
    $this->data['heading_text'] = 'Tambah';
    $this->data['submit_url'] = "{$this->base_url}/save_add";
    $this->data['form_value'] = $this->session->flashdata('form_value') ?: [
      'start_date' => '',
      'end_date'   => '',
      'code'       => '',
      'value'      => '',
    ];

    $this->_render('admin/ads_voucher/form');
  }

  public function save_add()
  {
    $this->session->set_flashdata('form_value', $this->input->post());
    $this->_set_form_validation_rules($this->input->post('content_status'));

    if ($this->form_validation->run())
    {
      $this->mdl_ads_voucher->insert(
	$this->_get_input_content_data()
      );

      $message = 'Voucher iklan berhasil ditambahkan.';
      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

      redirect($this->uri->segment(1));
    }
    else
    {
      $this->session->set_flashdata(
	'message',
	$this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
      );

      redirect("{$this->uri->segment(1)}/add");
    }
  }

  public function edit($id)
  {
    $this->data['ads_voucher'] = $this->mdl_ads_voucher->find($id) ?: redirect($this->uri->segment(1));
    $this->data['ads_voucher']->start_date = date_create($this->data['ads_voucher']->start_date)->format('d-m-Y');
    $this->data['ads_voucher']->end_date = date_create($this->data['ads_voucher']->end_date)->format('d-m-Y');
    $this->data['heading_text'] = 'Edit';
    $this->data['submit_url'] = "{$this->base_url}/save_edit/{$id}";
    $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array) $this->data['ads_voucher'];

    $this->_render('admin/ads_voucher/form');
  }

  public function save_edit($id)
  {
    if ($this->mdl_ads_voucher->count($id) < 1)
    {
      redirect($this->uri->segment(1));
    }

    $this->session->set_flashdata('form_value', $this->input->post());
    $this->_set_form_validation_rules($this->input->post('content_status'));

    if ($this->form_validation->run())
    {
      $this->mdl_ads_voucher->update(
	$this->_get_input_content_data(),
	$id
      );

      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata('message', $this->global_lib->generateMessage('Voucher iklan berhasil diubah.', 'info'));

      redirect($this->uri->segment(1));
    }
    else
    {
      $this->session->set_flashdata(
	'message',
	$this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
      );

      redirect("{$this->uri->segment(1)}/edit/{$id}");
    }
  }

  public function delete($id)
  {
    if ($this->mdl_ads_voucher->count($id) > 0)
    {
      $this->mdl_ads_voucher->delete($id);

      $this->session->set_flashdata(
	'message',
	$this->global_lib->generateMessage('Voucher iklan berhasil dihapus.', 'info')
      );
    }

    redirect($this->uri->segment(1));
  }

  private function _set_form_validation_rules()
  {
    $base_rule = 'htmlentities|strip_tags|trim|xss_clean';

    $this->form_validation->set_rules('start_date', '', "{$base_rule}|required|callback__validateDate[d-m-Y]");
    $this->form_validation->set_rules('end_date', '', "{$base_rule}|required|callback__validateDate[d-m-Y]");
    $this->form_validation->set_rules('code', '', "{$base_rule}|required|max_length[100]");
    $this->form_validation->set_rules('value', '', "{$base_rule}|integer");
  }

  public function _validateDate($date_text, $format)
  {
    $is_valid_date = $this->global_lib->validateDate($date_text, $format);

    if (!$is_valid_date)
    {
      $this->form_validation->set_message('_validateDate', 'The format of {field} must be ' . strtoupper($format));
    }

    return $is_valid_date;
  }

  private function _get_input_content_data()
  {
    return [
      'start_date' => $this->global_lib->formatDate($this->input->post('start_date'), 'd-m-Y', 'Y-m-d'),
      'end_date'   => $this->global_lib->formatDate($this->input->post('end_date'), 'd-m-Y', 'Y-m-d'),
      'code'       => $this->input->post('code'),
      'value'      => $this->input->post('value'),
    ];
  }

  private function _render($view_path)
  {
    $this->data['base_url'] = $this->base_url;
    $this->data['module_name'] = $this->module_name;
    $page = $this->load->view($view_path, $this->data, TRUE);

    $this->load->view('/admin/template', [
      'content' => $page,
      'type' => $page,
      'css_files' => $this->css,
      'js_files' => $this->js,
      'global_data' => $this->global_lib->getGlobalData(),
      'modules' => $this->global_lib->generateAdminModule(),
    ]);
  }

}
