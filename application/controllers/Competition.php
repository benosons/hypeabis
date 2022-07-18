<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Competition Controller
 *
 * @property Mdl_article $mdl_article
 * @property Mdl_photo $mdl_photo
 * @property Mdl_competition $mdl_competition
 */
class Competition extends CI_Controller
{
    public $js = [];
    public $css = [];
    public $pagination_per_page = 40;
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
        $this->load->model('mdl_article');
        $this->load->model('mdl_photo');
        $this->load->model('mdl_category');
    }

    public function show($id, $title = '', $page_no = '1')
    {
        $competition = $this->mdl_competition->find($id) ?: redirect(base_url());
        $model = $competition->competition_type == 'article' ? $this->mdl_article : $this->mdl_photo;
        $category = !in_array($this->input->get('id_competition_category'), [null, ''], true)
            ? $this->input->get('id_competition_category')
            : null;
        $is_sorted_by_competition_like = $this->uri->segment(4) != 'terbaru';
        $base_url = base_url(
            "kompetisi/{$id}/"
            . strtolower(url_title($competition->title)) . ($is_sorted_by_competition_like ? '' : '/terbaru')
        );

        $config = $this->global_lib->paginationConfig();
        $config['uri_segment'] = $is_sorted_by_competition_like ? 4 : 5;
        $config['base_url'] = base_url() . "kompetisi/{$this->uri->segment(2)}/{$this->uri->segment(3)}" . ($is_sorted_by_competition_like ? '' : '/terbaru');
        $config['per_page'] = ($this->pagination_per_page);
        $config['total_rows'] = $data['total_row'] = $model->join_competition($id)->count_published();

        $this->pagination->initialize($config);

        $contents = $model
            ->with_competition_like($competition)
            ->join_competition($id)
            ->where_competition_category($category)
            ->with_user_like($this->session->userdata('id_user'))
            ->order_by_like($is_sorted_by_competition_like)
            ->all_published(
                $config['per_page'],
                $this->uri->segment($config['uri_segment'])
            );

        $categories = $this->mdl_competition->getCategory($id);

        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/frontend/js/competition.js"></script>';
        $this->_render('frontend/competition', compact('competition', 'contents', 'categories', 'category', 'base_url'));
    }

    public function temsAndConditions($id, $title = '', $page_no = '1')
    {
        $competition = $this->mdl_competition->find($id) ?: redirect(base_url());
        $category = !in_array($this->input->get('id_competition_category'), [null, ''], true)
            ? $this->input->get('id_competition_category')
            : null;
        $base_url = base_url("kompetisi/{$id}/" . strtolower(url_title($competition->title)));
        $categories = $this->mdl_competition->getCategory($id);

        $this->_render('frontend/competition-terms', compact('competition', 'categories', 'category', 'base_url'));
    }

    public function getCategory(){
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data['status'] = '';

        $this->form_validation->set_rules('id_competition', '', 'htmlentities|strip_tags|required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data['status'] = 'failed';
        }
        else {
            $id_competition = $this->input->post('id_competition');
            //ambil data category
            $data['status'] = 'success';
            $data['categories'] = (array)$this->mdl_competition->getCategory($id_competition);
        }

        echo json_encode($data);
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

        //ambil ads footer
        $data['ads'] = $this->ads_lib->getFooterAds();

        //check active competition
        $data['is_competition_exist'] = $this->mdl_competition->getActiveCompetitionCount();

        //load view template
        $this->load->view('frontend/template', $data);
    }

    private function getAllCategory()
    {
        //ambil data semua module utama / parent..
        $categories = $this->mdl_category->getAllCategoryParentArr();

        //ambil semua category child.
        foreach ($categories as $x => $category) {
            $has_child = $this->mdl_category->hasChild($category['id_category']);
            $categories[$x]['has_child'] = ($has_child ? 1 : 0);

            //cek apakah punya child.
            if ($has_child) {
                $categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
            }
        }

        $level = 0;
        foreach ($categories as $x => $category) {
            $this->category_list[$this->category_index] = $category;
            $this->category_list[$this->category_index]['category_name'] = $category['category_name'];
            $this->category_index++;

            //cek apakah punya child.
            if ($category['has_child'] == 1) {
                $this->generateCategoryChildList($category['child'], $level);
            }
        }

        return $this->category_list;
    }

    private function getCategoryChild($id_category = '')
    {
        $categories = array();
        $categories = $this->mdl_category->getCategoryChildArr($id_category);

        //ambil semua category child.
        foreach ($categories as $x => $category) {
            $has_child = $this->mdl_category->hasChild($category['id_category']);
            $categories[$x]['has_child'] = ($has_child ? 1 : 0);

            //cek apakah punya child.
            if ($has_child) {
                $categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
            }
        }
        return $categories;
    }

    private function generateCategoryChildList($categories, $level)
    {
        $level++;
        $indentation = "";
        for ($x = 0; $x < $level; $x++) {
            $indentation .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        foreach ($categories as $x => $category) {
            $this->category_list[$this->category_index] = $category;
            $this->category_list[$this->category_index]['category_name'] = $indentation . $category['category_name'];
            $this->category_index++;

            //cek apakah punya child.
            if ($category['has_child'] == 1) {
                $this->generateCategoryChildList($category['child'], $level);
            }
        }
    }
}
