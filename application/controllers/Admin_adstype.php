<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin_adstype
 *
 * @property Mdl_adstype $mdl_adstype
 * @property Picture $picture
 * @property Global_lib $global_lib
 */
class Admin_adstype extends CI_Controller {
  private $module_name = 'admin_adstype';
  private $base_url;

  public $pic_width = 1280;
  public $pic_height = 1280;
  public $css = [];
  public $js = [];

  public function __construct()
  {
    parent::__construct();
    $this->base_url = base_url() . $this->uri->segment(1);

    $this->load->model('mdl_adstype');

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
    $pagination_config['total_rows'] = $this->mdl_adstype->count();
    $pagination_config['per_page'] = 20;
    $pagination_config['uri_segment'] = 3;

    $this->pagination->initialize($pagination_config);

    $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
    $this->data['adstypes'] = $this->mdl_adstype->all($pagination_config['per_page'], $this->data['offset']);

    $this->_render('admin/adstype/all');
  }

  public function edit($id)
  {
    $this->data['adstype'] = $this->mdl_adstype->find($id) ?: redirect($this->uri->segment(1));
    $this->data['heading_text'] = 'Edit';
    $this->data['submit_url'] = "{$this->base_url}/save_edit/{$id}";
    $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array) $this->data['adstype'];

    $this->_render('admin/adstype/form');
  }

  public function save_edit($id)
  {
    if ($this->mdl_adstype->count($id) < 1)
    {
      redirect($this->uri->segment(1));
    }

    $this->session->set_flashdata('form_value', $this->input->post());
    $this->_set_form_validation_rules($this->input->post('content_status'));

    if ($this->form_validation->run())
    {
      $this->mdl_adstype->update(
        $this->_get_input_content_data(),
        $id
      );

      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata('message', $this->global_lib->generateMessage('Posisi Iklan berhasil diubah.', 'info'));

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

  private function _set_form_validation_rules()
  {
    $base_rule = 'htmlentities|strip_tags|trim|xss_clean';

    $this->form_validation->set_rules('price_per_day', '', "{$base_rule}|integer");
    $this->form_validation->set_rules('ads_order', '', "{$base_rule}|integer");
  }

  private function _get_input_content_data()
  {
    return [
      'price_per_day' => $this->input->post('price_per_day'),
      'ads_order'     => $this->input->post('ads_order'),
      'location_pic'  => $this->_upload_location_picture(),
    ];
  }

  private function _upload_location_picture()
  {
    $filename = $_FILES["location_pic"]['name'];

    $picture = null;

    if (!empty($filename))
    {
      $config = [
        'upload_path' => './assets/adstype/',
        'allowed_types' => 'jpg|jpeg|png|gif',
        'max_size' => '5000',
        'max_width' => '8000',
        'max_height' => '8000',
        'file_name' => 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename),
      ];

      $this->upload->initialize($config);

      if ($this->upload->do_upload("location_pic"))
      {
        $upload_data = $this->upload->data();
        $picture = $this->picture->resizePhoto($upload_data['full_path'], $this->pic_width, $this->pic_height, TRUE);
      }
      else
      {
        $this->session->set_flashdata(
          'message',
          $this->global_lib->generateMessage("Gagal mengupload upload Gambar Lokasi karena: " . $this->upload->display_errors(), 'danger')
        );
        redirect($_SERVER['HTTP_REFERER'] ?? $this->base_url);
      }
    }

    return $picture;
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
