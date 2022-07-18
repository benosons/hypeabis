<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Content_tag Controller
 *
 * @property-read Mdl_content_tag $mdl_content_tag
 */
class Content_tag extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    if (!$this->session->userdata('admin_logged_in') && ! $this->session->userdata('user_logged_in')) {
      redirect(base_url());
    }

    $this->load->model('mdl_content_tag');
  }

  public function index()
  {
    if (! $this->input->is_ajax_request()) {
      redirect(base_url());
    }

    $query = trim($this->input->get('q'));
    $query = !empty($query) ? $query : NULL;
    $data = $this->mdl_content_tag->all($query);
    
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(array_column($data, 'tag_name')));
  }
}
