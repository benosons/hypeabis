<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin_ads_order
 *
 * @property Mdl_ads_order $mdl_ads_order
 */
class Admin_ads_order extends CI_Controller {
  private $module_name = 'admin_ads_order';
  private $base_url;
  private $data;

  public $css = [];
  public $js = [];

  public function __construct()
  {
    parent::__construct();
    $this->base_url = base_url() . $this->uri->segment(1);
    $this->data = [
      'module_name' => $this->module_name,
    ];

    $this->load->model('mdl_ads_order');

    $has_permission = (
      strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === FALSE
      && $this->session->userdata('admin_level') !== '1'
    );

    if (!$this->session->userdata('admin_logged_in') || $has_permission)
    {
      redirect('admin_dashboard/index');
    }
  }

  public function index()
  {
    $pagination_config = $this->global_lib->paginationConfigAdmin();
    $pagination_config['base_url'] = "{$this->base_url}/index/";
    $pagination_config['total_rows'] = $this->mdl_ads_order->count();
    $pagination_config['per_page'] = 20;
    $pagination_config['uri_segment'] = 3;

    $this->pagination->initialize($pagination_config);

    $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
    $this->data['orders'] = $this->mdl_ads_order
                                 ->order_by_checkout_date()
                                 ->with_user_name()
                                 ->all($pagination_config['per_page'], $this->data['offset']);

    $this->_render('admin/ads_order/all');
  }

  public function view($id)
  {
    $this->data['ads_order'] = $this->mdl_ads_order->with_user_name()->find($id) ?: redirect($this->uri->segment(1));
    $this->data['subtotal'] = array_sum(array_column($this->data['ads_order']->items, 'subtotal'));

    $this->_render('admin/ads_order/view');
  }

  public function invoice($id)
  {
    $this->data['ads_order'] = $this->mdl_ads_order->with_user_name()->find($id) ?: redirect($this->uri->segment(1));
    $this->data['subtotal'] = array_sum(array_column($this->data['ads_order']->items, 'subtotal'));
    $this->data['global_data'] = $this->global_lib->getGlobalData();

    $this->_render('admin/ads_order/invoice');
  }

  private function _render($view_path)
  {
    $this->data['base_url'] = $this->base_url;
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

