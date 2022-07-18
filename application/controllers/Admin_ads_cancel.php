<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin_ads_cancel
 *
 * @property Mdl_ads_cancel $mdl_ads_cancel
 * @property Mdl_ads $mdl_ads
 */
class Admin_ads_cancel extends CI_Controller {
  private $module_name = 'admin_ads_cancel';
  private $base_url;

  public $css = [];
  public $js = [];

  public function __construct()
  {
    parent::__construct();
    $this->base_url = base_url() . $this->uri->segment(1);
    $this->data = [];

    $this->load->model('mdl_ads_cancel');

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
    $pagination_config['total_rows'] = $this->mdl_ads_cancel->count();
    $pagination_config['per_page'] = 20;
    $pagination_config['uri_segment'] = 3;

    $this->pagination->initialize($pagination_config);

    $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
    $this->data['ads'] = $this->mdl_ads_cancel->all($pagination_config['per_page'], $this->data['offset']);

    $this->_render('admin/ads_cancel/all');
  }

  public function edit($id)
  {
    $this->data['ads'] = $this->mdl_ads_cancel->find($id) ?: redirect($this->uri->segment(1));

    $this->_check_ads_can_be_cancelled($this->data['ads']);

    $this->data['heading_text'] = 'Edit';
    $this->data['submit_url'] = "{$this->base_url}/save_edit/{$id}";
    $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array) $this->data['ads'];

    $this->_render('admin/ads_cancel/form');
  }

  public function save_edit($id)
  {
    $ads = $this->mdl_ads_cancel->find($id) ?: redirect($this->uri->segment(1));

    $this->_check_ads_can_be_cancelled($ads);

    $this->session->set_flashdata('form_value', $this->input->post());
    $this->_set_form_validation_rules();

    if ($this->form_validation->run())
    {
      $data = $this->_get_input_data();

      $this->db->trans_start();

      if (in_array($data['status'], ['1', '2']))
      {
        $this->load->model('mdl_ads');

        if ($ads->revised_ads_pic)
        {
          @unlink("assets/adv/{$ads->revised_ads_pic}");
        }

        if ($ads->ads_pic)
        {
          @unlink("assets/adv/{$ads->ads_pic}");
        }

        if ($ads->id_ads) {
          $this->mdl_ads->deleteAds($ads->id_ads);
        }
      }

      $this->mdl_ads_cancel->update($data, $id);
      $this->db->trans_complete();

      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata('message', $this->global_lib->generateMessage('Pembatalan Iklan berhasil diubah.', 'info'));

      redirect($this->base_url);
    }
    else
    {
      $this->session->set_flashdata(
        'message',
        $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
      );

      redirect("{$this->base_url}/edit/{$id}");
    }
  }

  private function _check_ads_can_be_cancelled($ads, $redirect_url = NULL)
  {
    $redirect_url = $redirect_url ?? $this->base_url;

    if (is_null($ads))
    {
      redirect($redirect_url);
    }

    $is_live = strtotime('now') >= strtotime($ads->start_date);

    if ($is_live && !in_array($ads->status, ['1', '2']))
    {
      redirect($redirect_url);
    }
  }

  private function _set_form_validation_rules()
  {
		$base_rule = 'htmlentities|strip_tags|trim|xss_clean';
    $status = $this->input->post('status');
		$required_if_rejected = ($status === '-1' ? '|required' : '');
		$required_if_finished = ($status === '2' ? '|required' : '');

    $this->form_validation->set_rules('status', '', "{$base_rule}|required");
    $this->form_validation->set_rules('reject_note', '', "{$base_rule}{$required_if_rejected}");
    $this->form_validation->set_rules('nominal', '', "{$base_rule}{$required_if_finished}");
  }

  private function _get_input_data()
  {
    $status = $this->input->post('status');
    $data =  [
      'status' => $this->input->post('status'),
      'reject_note' => NULL,
    ];

    if ($status === '-1')
    {
      $data['reject_note'] = $this->input->post('reject_note');
    }
    elseif ($status === '2')
    {
      $data['nominal'] = $this->input->post('nominal');
    }

    return $data;
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
