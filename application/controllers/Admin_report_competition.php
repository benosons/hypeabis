<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Admin_report_competition
 *
 * @property Global_lib $global_lib
 * @property CI_Input $input
 * @property Mdl_report $mdl_report
 * @property Mdl_competition $mdl_competition
 */
class Admin_report_competition extends CI_Controller {
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
        $this->load->model('mdl_admin');
        $this->load->model('mdl_user');
        $this->load->model('mdl_competition');

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
                'id_competition' => null,
                'author' => null,
                'admin' => null,
                'status' => null,
                'start_date' => null,
                'finish_date' => null,
            ];
            $this->session->set_userdata('search_report', $search_param);
        }

        $pagination_config = $this->global_lib->paginationConfigAdmin();
        $pagination_config['base_url'] = "{$this->base_url}/index/";
        $pagination_config['total_rows'] = $this->mdl_report->count_competition_content($search_param);
        $pagination_config['per_page'] = $this->pagination_per_page;
        $pagination_config['uri_segment'] = 3;

        $this->pagination->initialize($pagination_config);

        $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
        $this->data['items'] = $this->mdl_report->all_competition_content($search_param, $pagination_config['per_page'], $this->data['offset']);

        if(!empty($search_param['author']) && is_numeric($search_param['author']) && $search_param['author'] > 0){
            $this->data['author'] = $this->mdl_user->getUserByID($search_param['author']);
        }
        if (!empty($search_param['admin']) && is_numeric($search_param['admin']) && $search_param['admin'] > 0) {
            $this->data['admin_name'] = $this->mdl_admin->getNameByID($search_param['admin']);
        }

        $this->data['competitions'] = $this->mdl_competition->all();

        $this->_render('admin/report/competition');
    }

    public function search()
    {
        $this->index($this->session->userdata('search_report'));
    }

    public function submitSearch()
    {
        $this->form_validation->set_rules('author', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_competition', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('admin', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('sort_by','', 'htmlentities|strip_tags|required|trim|xss_clean');
        $this->form_validation->set_rules('status','', 'htmlentities|strip_tags|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $message =  $this->global_lib->generateMessage(validation_errors(), "info");
            $this->session->set_flashdata('message', $message);
            redirect($this->base_url);
        } else {
            $this->session->set_userdata('search_report', [
                'sort_by' => $this->input->post('sort_by'),
                'author' => $this->input->post('author'),
                'id_competition' => $this->input->post('id_competition'),
                'admin' => $this->input->post('admin'),
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
                'author' => null,
                'admin' => null,
                'status' => null,
                'start_date' => null,
                'finish_date' => null,
            ];
            $this->session->set_userdata('search_report', $search_param);
        }

        //get search result
        $data = $this->mdl_report->all_competition_content($search_param);

        //cleansing data
        foreach($data as $x => $item){
            $data[$x] = (array)$data[$x];
            unset($data[$x]['id_content']);
            unset($data[$x]['type']);
            unset($data[$x]['user_picture']);
            unset($data[$x]['user_picture_from']);
            unset($data[$x]['user_profile_desc']);
        }
        // print_r("<pre>");
        // print_r($data);
        // print_r("</pre>");
        // die();

        $this->load->library("excel");
        $this->excel->setActiveSheetIndex(0);
        $reports = [
            [
                'title' => "Artikel",
                'data' => $data
            ]
        ];
        $this->excel->streamMultipleSheet('report_kompetisi_' . date('YmdHis') . '.xls', $reports);
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
