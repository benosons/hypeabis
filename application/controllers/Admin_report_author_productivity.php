<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin_report_author_productivity
 *
 * @property Global_lib $global_lib
 * @property CI_Input $input
 * @property Mdl_report $mdl_report
 */
class Admin_report_author_productivity extends CI_Controller {
  public $module_name = 'admin_report_author';
  private $base_url;
  private $pagination_per_page = 20;
  private $data = [];
  public $css = [];
  public $js = [];
  public $pic_width = 1000;

  public function __construct()
  {
    parent::__construct();

    $this->base_url = base_url($this->uri->segment(1));
    $this->data = [
      'module' => $this->global_lib->getModuleDetail($this->module_name),
      'base_url' => $this->base_url,
    ];

    $this->load->model('mdl_report');

    if (!$this->session->userdata('admin_logged_in')) {
      redirect('admin_dashboard/index');
    }

    if (strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === FALSE && $this->session->userdata('admin_level') !== '1')
    {
      redirect('admin_dashboard/index');
    }
  }

  public function index($search_param = NULL)
  {
    if (is_null($search_param)) {
      $search_param = [
        'sort_by' => 'default',
        'status' => null,
        'start_date' => null,
        'finish_date' => null,
      ];
      $this->session->set_userdata('search_report', $search_param);
    }

    $pagination_config = $this->global_lib->paginationConfigAdmin();
    $pagination_config['base_url'] = "{$this->base_url}/index/";
    $pagination_config['total_rows'] = $this->mdl_report->count_author_productivity($search_param);
    $pagination_config['per_page'] = $this->pagination_per_page;
    $pagination_config['uri_segment'] = 3;

    $this->pagination->initialize($pagination_config);

    $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
    $this->data['items'] = $this->mdl_report->all_author_productivity($search_param, $pagination_config['per_page'], $this->data['offset']);

    $this->_render('admin/report/author_productivity');
  }

  public function search()
  {
    $this->index($this->session->userdata('search_report'));
  }

  public function submitSearch()
  {
    $this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
    $this->form_validation->set_rules('status','', 'htmlentities|strip_tags|trim|xss_clean');

    if ($this->form_validation->run() == false) {
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
      $this->session->set_flashdata('message', $message);
      redirect($this->base_url);
    } else {
      $this->session->set_userdata('search_report', [
        'sort_by' => $this->input->post('sort_by'),
        'status' => $this->input->post('status'),
        'start_date' => $this->input->post('start_date'),
        'finish_date' => $this->input->post('finish_date'),
      ]);

      redirect("{$this->base_url}/search");
    }
  }

  public function export()
  {
    $search_param = $this->session->userdata('search_report');
    if (is_null($search_param)) {
      $search_param = [
        'sort_by' => 'default',
        'status' => null,
        'start_date' => null,
        'finish_date' => null,
      ];
      $this->session->set_userdata('search_report', $search_param);
    }

    //get search result
    $data = $this->mdl_report->all_author_productivity($search_param);
    $clean_data = [];

    //cleansing data
    foreach ($data as $x => $item) {
      array_push($clean_data, [
        'nama' => $item->name,
        'email' => $item->email,
        'email facebook' => $item->email_facebook,
        'email google' => $item->email_google,
        'ID facebook' => $item->oauth_uid_facebook,
        'ID google' => $item->oauth_uid_google,
        'jumlah artikel' => $item->article_count,
        'jumlah hypephoto' => $item->photo_count,
        'point' => $item->point,
        'status' => ($item->is_active == '1' ? 'Aktif' : 'Tidak aktif'),
        'Terakhir Publish' => $item->last_publish_date
      ]);
      // unset($data[$x]['id_content']);
      // unset($data[$x]['type']);
      // unset($data[$x]['user_picture']);
      // unset($data[$x]['user_picture_from']);
      // unset($data[$x]['user_profile_desc']);
    }
    // print_r("<pre>");
    // print_r($clean_data);
    // print_r("</pre>");
    // die();

    $this->load->library("excel");
    $this->excel->setActiveSheetIndex(0);
    $reports = [
      [
        'title' => "Produktivitas Author",
        'data' => $clean_data
      ]
    ];
    $this->excel->streamMultipleSheet('report_produktivitas_author_' . date('YmdHis') . '.xls', $reports);
  }

  private function _render($view_path)
  {
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
