<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Competition Controller
 *
 * @property CI_Input $input
 * @property Mdl_competition $mdl_competition
 */
class Hypevent extends CI_Controller
{
    private $js = [];
    public $css = [];
    public $pagination_per_page = 12;
    public $category_index = 0;
    public $category_list = array();

    public function __construct()
    {
        parent::__construct();

        //load library..
        $this->load->library('frontend_lib');
        $this->load->library('ads_lib');

        //load model..
        $this->load->model('mdl_competition');
        $this->load->model('mdl_content2');
        $this->load->model('mdl_category');
    }

    public function show()
    {
        $type = in_array($this->input->get('type'), ['article', 'photo']) ? $this->input->get('type') : null;
        $config = $this->getInitializedPaginationConfig($type);
        $offset = ($this->uri->segment($config['uri_segment']) > 0 ? $this->uri->segment($config['uri_segment']) : 0);

        $competitions = $this->mdl_competition
                             ->hasType($type)
                             ->allWithCategoryAndEntryCount($config['per_page'], $offset);

        $this->_render('frontend/hypevent', compact('competitions', 'type'));
    }

    private function getInitializedPaginationConfig($type)
    {
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url('hypevent');
        $config['per_page'] = $this->pagination_per_page;
        $config['uri_segment'] = 2;
        $config['reuse_query_string'] = true;
        $config['total_rows'] = $this->mdl_competition->hasType($type)->count();

        $this->pagination->initialize($config);

        return $config;
    }

    private function _render($view_path, $data, $meta = [])
    {
        //load page view
        $data['content'] = $this->load->view($view_path, $data, TRUE);
        $data['meta'] = $meta;

        //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
        $data['css_files'] = $this->css;
        $data['js_files'] = $this->js;

        //load module global data
        $data['global_data'] = $this->global_lib->getGlobalData();

        //get category (for menu)
        $data['categories'] = $this->mdl_category->getAllCategoryParentArr();
        foreach ($data['categories'] as $x => $category) {
            $data['categories'][$x]['child'] = $this->mdl_category->getCategoryChildArr($category['id_category']);
        }

        //ambil ads footer..
        $data['ads'] = $this->ads_lib->getFooterAds();

        $data['is_competition_exist'] = $this->mdl_competition->getActiveCompetitionCount();

        //load view template
        $this->load->view('frontend/template', $data);
    }
}
