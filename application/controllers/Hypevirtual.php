<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Competition Controller
 *
 * @property CI_Input $input
 * @property Mdl_competition $mdl_competition
 */
class Hypevirtual extends CI_Controller
{
    const ASSET_FOLDER_TYPES = [
        'hypevirtual-gallery' => 'gallery',
    ];
    const TYPES = [
        '0' => 'gallery',
    ];
    const MODELS = [
        'hypevirtual' => 'mdl_gallery',
    ];

    private $model;
    private $type;

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
        $this->load->model('mdl_gallery');

        $this->type = $this->uri->segment(1);
        $this->model = $this->mdl_gallery;

        $this->assets_url = base_url() . 'assets/' . (self::ASSET_FOLDER_TYPES[$this->type] ?? $this->type);
    }

    public function index()
    {
        $type = in_array($this->input->get('type'), ['article', 'photo']) ? $this->input->get('type') : null;
        $config = $this->getInitializedPaginationConfig();
        $offset = ($this->uri->segment($config['uri_segment']) > 0 ? $this->uri->segment($config['uri_segment']) : 0);

        $gallery = $this->mdl_gallery->getAllGalleryLimitN($config['per_page'], $offset);
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/frontend/js/hypevirtual.js"></script>';
        
        $this->_render('frontend/hypevirtual', compact('gallery', 'type'));
    }

    public function show($params, $type, $title = '', $page_no = '1')
    {
        $this->load->library('recaptcha');
        
        $uri1 = $this->uri->segment(1);
        $full_param = $params;
        $params = explode('-', $params);
        $id_content = $params[0];
        $this->metaData($id_content, 'show', true);
        //redirect jika masih akses pakai URL lama.
        if (!is_numeric($full_param)) {
            redirect(base_url() . $uri1 . '/' . $id_content . '/' . $title);
            // echo $uri1;
        }

        $id_user = $this->session->userdata('id_user');
        $id_admin = $this->session->userdata('id_admin');
        $is_preview = $this->input->get('is_preview');

        $contents = $this->model->getGalleryByID($id_content);
        
        $page = NULL;
        $page_no = intval($page_no);
        $max_page_no = 1;

        $data = compact(
            'contents',
            'max_page_no',
            'page',
            'page_no',
        );

        $data['assets_url'] = $this->assets_url;
        $data['ads'] = $this->ads_lib->getArticleAds();
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/frontend/js/hypevirtual.js"></script>';
        $this->_render('frontend/hypevirtual-gallery', $data);
    }

    private function metaData($id_galery, $type, $isDisplay)
    {
        $now = date("Y-m-d");

        $totalShow = $this->mdl_gallery->getGalleryByID($id_galery)[0]->read_count + 1;
        $totalClick = $this->mdl_gallery->getGalleryByID($id_galery)[0]->clicked_count + 1;

        $update_data = array();
        if($type == 'show'){
            $update_data['read_count'] = $totalShow;
        }

        if($type == 'click'){
            $update_data['clicked_count'] = $totalClick;
        }

        if($isDisplay){
            $metadata = $this->mdl_gallery->updateGaleri($update_data, $id_galery);
        }
        
    }

    private function clicked($id)
    {
        $now = date("Y-m-d");
        print_r('ada');die;
        $totalShow = $this->mdl_gallery->getGalleryByID($id_galery)[0]->read_count + 1;
        $totalClick = $this->mdl_gallery->getGalleryByID($id_galery)[0]->clicked_count + 1;

        $update_data = array();
        if($type == 'show'){
            $update_data['read_count'] = $totalShow;
        }

        if($type == 'click'){
            $update_data['clicked_count'] = $totalClick;
        }

        if($isDisplay){
            $metadata = $this->mdl_gallery->updateGaleri($update_data, $id_galery);
        }
        
    }

    private function getInitializedPaginationConfig()
    {
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url('hypevirtual');
        $config['per_page'] = $this->pagination_per_page;
        $config['uri_segment'] = 2;
        $config['reuse_query_string'] = true;
        $config['total_rows'] = $this->mdl_gallery->getAllGalleryCount();

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
        // print_r('ada');die;
        $this->load->view('frontend/template', $data);
    }
}
