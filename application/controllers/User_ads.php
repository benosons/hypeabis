<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_ads
 *
 * @property Mdl_ads $mdl_ads
 */
class User_ads extends CI_Controller {
  private $base_url;
  public $css = [];
  public $js = [];

  public function __construct()
  {
    parent::__construct();
    $this->base_url = base_url() . $this->uri->segment(1);
    $this->data = [];

    $this->load->model('mdl_ads');

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
    $pagination_config['total_rows'] = $this->mdl_ads->getAllAdsCountByUser($id_user);
    $pagination_config['per_page'] = 20;
    $pagination_config['uri_segment'] = 3;

    $this->pagination->initialize($pagination_config);

    $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
    $this->data['ads'] = $this->mdl_ads->getAllAdsOfUser($id_user, $pagination_config['per_page'], $this->data['offset']);

    $this->_render('user/ads/all');
  }

  public function edit($id)
  {
    $this->data['ads'] = $this->mdl_ads->getAdsByID($id);

    if ((!is_array($this->data['ads'])) || count($this->data['ads']) < 1) {
      redirect("{$this->base_url}/index");
    }

    if ($this->data['ads'][0]->edit_id_admin && $this->data['ads'][0]->edit_id_admin != $this->session->userdata('id_admin'))
    {
      redirect('user_ads/index');
    }

    $is_live = strtotime('now') >= strtotime($this->data['ads'][0]->start_date);

    if (! (strtotime('now') <= strtotime($this->data['ads'][0]->finish_date) && $this->data['ads'][0]->status === '-1')) {
      if ($is_live || !in_array($this->data['ads'][0]->status, ['-2', '-1', '0'])) {
        redirect("{$this->base_url}/index");
      }
    }

    $this->data['heading_text'] = 'Edit';
    $this->data['submit_url'] = "{$this->base_url}/save_edit/{$id}";
    $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array) $this->data['ads'][0];

    $this->_render('user/ads/form');
  }

  public function save_edit($id)
  {
    $ads = $this->mdl_ads->getAdsByID($id);

    if ((!is_array($ads)) || count($ads) < 1)
    {
      redirect("{$this->base_url}/index");
    }

    $is_live = strtotime('now') >= strtotime($ads[0]->start_date);

    if (! (strtotime('now') <= strtotime($ads[0]->finish_date) && $ads[0]->status === '-1')) {
      if ($is_live || !in_array($ads[0]->status, ['-2', '-1', '0'])) {
        redirect("{$this->base_url}/index");
      }
    }

    $ads = $ads[0];
    $form_value = $this->input->post();
    $form_value['ads_name'] = $ads->ads_name;

    $this->session->set_flashdata('form_value', $form_value);
    $this->_set_form_validation_rules($this->input->post('content_status'));

    if ($this->form_validation->run())
    {
      $picture = $this->_upload_picture(
        'ads_pic',
        $ads->ads_width,
        $ads->ads_height,
        $this->input->post('status') !== '-1' && empty($ads->ads_pic),
        "edit/{$ads->id_ads}"
      );
      $data = $this->_get_input_data($picture);

      $data['ads_pic'] = $data['ads_pic'] ?: $ads->ads_pic;

      $this->mdl_ads->updateAds($data, $id);

      if ($data['ads_pic'] !== $ads->ads_pic)
      {
        @unlink("assets/adv/{$ads->ads_pic}");
      }

      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata('message', $this->global_lib->generateMessage('Iklan berhasil diubah.', 'info'));

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

  public function revise($id)
  {
    $this->data['ads'] = $this->mdl_ads->getAdsByID($id);

    if ((!is_array($this->data['ads'])) || count($this->data['ads']) < 1)
    {
      redirect("{$this->base_url}/index");
    }

    if ($this->data['ads'][0]->edit_id_admin && $this->data['ads'][0]->edit_id_admin != $this->session->userdata('id_admin'))
    {
      redirect('user_ads/index');
    }

    $is_live = strtotime('now') >= strtotime($this->data['ads'][0]->start_date);

    if ($is_live || !in_array($this->data['ads'][0]->status, ['-3', '1', '2']))
    {
      redirect("{$this->base_url}/index");
    }

    $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array) $this->data['ads'][0];

    $this->_render('user/ads/form_revise');
  }

  public function save_revise($id)
  {
    $ads = $this->mdl_ads->getAdsByID($id);

    if ((!is_array($ads)) || count($ads) < 1)
    {
      redirect($this->base_url);
    }

    if ($ads[0]->edit_id_admin && $ads[0]->edit_id_admin != $this->session->userdata('id_admin'))
    {
      redirect('user_ads/index');
    }

    $ads = $ads[0];
    $is_live = strtotime('now') >= strtotime($ads->start_date);

    if ($is_live || !in_array($ads->status, ['-3', '1', '2']))
    {
      redirect($this->base_url);
    }

    $form_value = $this->input->post();
    $form_value['ads_name'] = $ads->ads_name;

    $this->session->set_flashdata('form_value', $form_value);
    $this->_set_form_revise_validation_rules($this->input->post('content_status'));

    if ($this->form_validation->run())
    {
      $picture = $this->_upload_picture(
        'revised_ads_pic',
        $ads->ads_width,
        $ads->ads_height,
        empty($ads->revised_ads_pic) && empty($this->input->post('revised_redirect_url')),
        "revise/{$ads->id_ads}"
      );
      $data = $this->_get_revise_input_data($picture);

      $data['revised_ads_pic'] = $data['revised_ads_pic'] ?: $ads->revised_ads_pic;

      $this->mdl_ads->updateAds($data, $id);

      if ($data['revised_ads_pic'] !== $ads->revised_ads_pic)
      {
        @unlink("assets/adv/{$ads->revised_ads_pic}");
      }

      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata('message', $this->global_lib->generateMessage('Iklan berhasil direvisi.', 'info'));

      redirect($this->base_url);
    }
    else
    {
      $this->session->set_flashdata(
        'message',
        $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
      );

      redirect("{$this->base_url}/revise/{$id}");
    }
  }

  public function delete_revised_picture($id)
  {
    $ads = $this->mdl_ads->getAdsByID($id);

    if ((!is_array($ads)) || count($ads) < 1)
    {
      redirect($this->base_url);
    }

    $ads = $ads[0];
    $is_live = strtotime('now') >= strtotime($ads->start_date);

    if ($is_live || !in_array($ads->status, ['-3', '1', '2']))
    {
      redirect($this->base_url);
    }

    if (file_exists("assets/adv/{$ads->revised_ads_pic}"))
    {
      @unlink("assets/adv/{$ads->revised_ads_pic}");
    }

    $this->mdl_ads->updateAds(['revised_ads_pic' => NULL], $id);

    $this->session->set_flashdata(
      'message',
      $this->global_lib->generateMessage('Revisi gambar iklan berhasil dihapus', 'info')
    );

    redirect("{$this->base_url}/revise/{$id}");
  }

  private function _set_form_revise_validation_rules()
  {
    $required_if_no_pic = (empty($_FILES['revised_ads_pic']['name']) ? "|required" : '');

    $this->form_validation->set_rules('revised_redirect_url', '', "htmlentities|strip_tags|trim|xss_clean{$required_if_no_pic}");
  }

  private function _set_form_validation_rules()
  {
    $status = $this->input->post('status');
    $required_if_not_draft = ($status !== '-1' ? "|required" : '');

    $this->form_validation->set_rules('redirect_url', '', "htmlentities|strip_tags|trim|xss_clean{$required_if_not_draft}");
  }

  private function _upload_picture($field_name, $ads_width, $ads_height, $is_required = false, $fail_redirect_url = null)
  {
    $filename = $_FILES[$field_name]['name'];

    $picture = '';

    if (!empty($filename))
    {
      $config = [
        'upload_path' => './assets/adv/',
        'allowed_types' => 'jpg|jpeg|png|gif',
        'max_size' => '12000',
        'max_width' => '8000',
        'max_height' => '8000',
        'file_name' => 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename),
      ];

      $this->upload->initialize($config);

      if ($this->upload->do_upload($field_name))
      {
        $upload_data = $this->upload->data();

        $picture = $this->picture->resizePhoto($upload_data['full_path'], $ads_width, $ads_height);
      }
      else
      {
        $this->session->set_flashdata(
          'message',
          $this->global_lib->generateMessage('Failed to upload file. <br/> cause: ' . $this->upload->display_errors(), 'danger')
        );
        redirect("{$this->base_url}/{$fail_redirect_url}");
      }
    }
    elseif ($is_required)
    {
      $this->session->set_flashdata(
        'message',
        $this->global_lib->generateMessage('You must upload article picture', 'danger')
      );
      redirect("{$this->base_url}/{$fail_redirect_url}");
    }

    return $picture;
  }

  private function _get_revise_input_data($picture)
  {
    return [
      'revised_ads_pic'      => $picture ?: NULL,
      'revised_redirect_url' => $this->input->post('revised_redirect_url') ?: NULL,
      'reason_for_revision'  => $this->input->post('reason_for_revision') ?: NULL,
      'status'               => 2,
    ];
  }

  private function _get_input_data($picture)
  {
    return [
      'ads_pic'      => $picture ?: NULL,
      'redirect_url' => $this->input->post('redirect_url'),
      'status'       => $this->input->post('status'),
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
