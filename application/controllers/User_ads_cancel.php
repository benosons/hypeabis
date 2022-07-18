<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_ads_cancel
 *
 * @property Mdl_ads_cancel $mdl_ads_cancel
 */
class User_ads_cancel extends CI_Controller {
  private $base_url;

  public $css = [];
  public $js = [];

  public function __construct()
  {
    parent::__construct();
    $this->base_url = base_url() . $this->uri->segment(1);
    $this->data = [];

    $this->load->model('mdl_ads_cancel');

    if ($this->session->userdata('user_logged_in') !== true)
    {
      $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())) , "=");
      redirect("page/login/{$redirect_url}");
    }
  }

  public function index()
  {
    $id_user = $this->session->userdata('id_user');
    $pagination_config = $this->global_lib->paginationConfig();
    $pagination_config['base_url'] = "{$this->base_url}/index/";
    $pagination_config['total_rows'] = $this->mdl_ads_cancel->count($id_user);
    $pagination_config['per_page'] = 20;
    $pagination_config['uri_segment'] = 3;

    $this->pagination->initialize($pagination_config);

    $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
    $this->data['ads'] = $this->mdl_ads_cancel->all($pagination_config['per_page'], $this->data['offset'], $id_user);

    $this->_render('user/ads_cancel/all');
  }

  public function add($id)
  {
    $this->data['ads'] = $this->mdl_ads_cancel->find_uncancelled_ads($id);

    $this->_check_ads_cancel($this->data['ads'], 'user_ads');

    $this->data['submit_url'] = "{$this->base_url}/save_add/{$id}";
    $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array) $this->data['ads'];

    $this->_render('user/ads_cancel/form');
  }

  public function save_add($id)
  {
    $ads = $this->mdl_ads_cancel->find_uncancelled_ads($id);

    $this->_check_ads_cancel($ads, 'user_ads');

    $this->session->set_flashdata('form_value', $this->input->post());
    $this->_set_form_validation_rules();

    if ($this->form_validation->run())
    {
      $data = $this->_get_input_data();
      $data['id_ads_order_item'] = $ads->id_ads_order_item;
      $data['request_date'] = date('Y-m-d H:i:s');

      $this->mdl_ads_cancel->insert($data);

      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata('message', $this->global_lib->generateMessage('Pembatalan Iklan berhasil diajukan.', 'info'));

      redirect($this->base_url);
    }
    else
    {
      $this->session->set_flashdata(
        'message',
        $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
      );

      redirect("{$this->base_url}/add/{$id}");
    }
  }

  public function edit($id)
  {
    $this->data['ads'] = $this->mdl_ads_cancel->find($id) ?: redirect($this->uri->segment(1));

    $this->_check_ads_cancel($this->data['ads']);

    $this->data['heading_text'] = 'Edit';
    $this->data['submit_url'] = "{$this->base_url}/save_edit/{$id}";
    $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array) $this->data['ads'];

    $this->_render('user/ads_cancel/form');
  }

  public function save_edit($id)
  {
    $ads = $this->mdl_ads_cancel->find($id);

    $this->_check_ads_cancel($ads, 'user_ads');

    $this->session->set_flashdata('form_value', $this->input->post());
    $this->_set_form_validation_rules();

    if ($this->form_validation->run())
    {
      $data = $this->_get_input_data();
      $data['status'] = 0;

      $this->mdl_ads_cancel->update($data, $id);

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

      redirect("{$this->base_url}/add/{$id}");
    }
  }

  public function delete($id)
  {
    $ads = $this->mdl_ads_cancel->find($id);

    $this->_check_ads_cancel($ads, 'user_ads');

    $this->mdl_ads_cancel->delete($id);

    $this->session->set_flashdata('form_value', NULL);
    $this->session->set_flashdata('message', $this->global_lib->generateMessage('Pembatalan Iklan berhasil dibatalkan.', 'info'));

    redirect($this->base_url);
  }

  private function _check_ads_cancel($ads, $redirect_url = NULL)
  {
    $redirect_url = $redirect_url ?? $this->base_url;

    if (is_null($ads))
    {
      redirect($redirect_url);
    }

    $is_live = strtotime('now') >= strtotime($ads->start_date);

    if ($is_live)
    {
      redirect($redirect_url);
    }
  }

  private function _set_form_validation_rules()
  {
    $this->form_validation->set_rules('account_number', '', "htmlentities|strip_tags|trim|xss_clean|required");
    $this->form_validation->set_rules('account_bank', '', "htmlentities|strip_tags|trim|xss_clean|required");
    $this->form_validation->set_rules('account_name', '', "htmlentities|strip_tags|trim|xss_clean|required");
    $this->form_validation->set_rules('reason', '', "htmlentities|strip_tags|trim|xss_clean|required");
  }

  private function _get_input_data()
  {
    return [
      'account_number' => $this->input->post('account_number'),
      'account_bank' => $this->input->post('account_bank'),
      'account_name' => $this->input->post('account_name'),
      'reason' => $this->input->post('reason'),
    ];
  }

  private function _render($view_path)
  {
    $this->data['base_url'] = $this->base_url;
    $page = $this->load->view($view_path, $this->data, TRUE);

    $this->load->view('/user/template', [
      'content' => $page,
      'type' => $page,
      'css_files' => $this->css,
      'js_files' => $this->js,
      'global_data' => $this->global_lib->getGlobalData(),
    ]);
  }
}
