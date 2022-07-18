<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 *
 * @author       : Hengky Mulyono <hengkymulyono301@gmail.com>
 * @copyright    : Binari - 2020
 * @copyright    : mail@binary-project.com
 * @version      : Release: v1
 * @link         : www.binary-project.com
 * @contact      : 0822 3709 9004
 *
 * @property-read Mdl_content_editor $mdl_content_editor
 */
class Admin_content extends CI_Controller
{

    public $js = array();
    public $css = array();
    public $category_index = 0;
    public $category_list = array();
    public $pagination_per_page = 20;
    public $pic_width = 1900;
    public $pic_height = 998;
    public $pic_thumb_width = 390;
    public $pic_thumb_height = 260;
    public $pic_square_width = 400;
    public $pic_square_height = 400;
    public $module_name = 'admin_content';

    public function __construct()
    {
        parent::__construct();

        //load library..

        //load model..
        $this->load->model('mdl_category');
        $this->load->model('mdl_content');
        $this->load->model('mdl_content_editor');
        $this->load->model('mdl_user');
        $this->load->model('mdl_competition');

        //construct script..
        if ($this->session->userdata('admin_logged_in') !== true) {
            redirect("adminarea/index");
        }
        if (strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1') {
            redirect('admin_dashboard/index');
        }
    }

    public function index()
    {
        //clear search session yang lama..
        $this->clearSearchSession();

        //ambil total row untuk keperluan config pagination dan jumlah data di depan..
        $data['total_row'] = $this->mdl_content->getAllContentWithoutDraftCount();
        $config = $this->paginationConfig($data['total_row']);
        $this->pagination->initialize($config);

        $data['content'] = $this->mdl_content->getAllContentWithoutDraftLimit(
            $this->session->userdata('id_admin'),
            $config['per_page'],
            ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0)
        );

        //ambil semua category
        $data['categories'] = $this->getAllCategory();
        $data['competitions'] = $this->mdl_competition->getByType('article');

        //load view all module
        $content = $this->load->view('admin/content/all', $data, true);

        $this->render($content);
    }

    public function add()
    {
        //clear search session yang lama..
        $this->clearSearchSession();

        //ambil semua category..
        $data['categories'] = $this->getAllCategory();

        //ambil semua user..
        $data['users'] = $this->mdl_user->getAllUser();

        $data['content'] = [
            (object)[
                'featured_on_homepage' => 0,
                'featured_on_category' => 0,
                'on_top_category' => 0,
                'publish_date' => NULL,
            ]
        ];

        //load view add admin ...
        $content = $this->load->view('admin/content/add', $data, true);

        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
        $this->render($content);
    }

    public function saveAdd()
    {
        $isPreview = !is_null($this->input->post('preview'));
        $status = $this->input->post('content_status');

        $this->session->set_flashdata(array(
            "content_value" => array(
                "paginated" => $this->input->post('paginated'),
                "id_user" => $this->input->post('id_user'),
                "title" => $this->input->post('title'),
                "meta_title" => $this->input->post('meta_title'),
                "meta_desc" => $this->input->post('meta_desc'),
                "meta_keyword" => $this->input->post('meta_keyword'),
                "short_desc" => $this->input->post('short_desc'),
                "id_category" => $this->input->post('category'),
                "pic_caption" => $this->input->post('pic_caption'),
                "featured_on_homepage" => $this->input->post('featured_on_homepage'),
                "trending" => $this->input->post('trending'),
                "recommended" => $this->input->post('recommended'),
                "recommended_category" => $this->input->post('recommended_category'),
                "featured_on_category" => $this->input->post('featured_on_category'),
                "on_top_category" => $this->input->post('on_top_category'),
                "content" => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('content')),
                "content_status" => $status
            )
        ));

        $requiredByStatus = ($status !== '-1' ? "|required" : '');
        $required_if_scheduled = ($status === '2' ? "|required" : '');

        $join_competition = $this->input->post('join_competition');
        $pic_required = '';
        if (!(isset($data['content'][0]->id_competition) && $data['content'][0]->id_competition > 0)){
            if (strlen($requiredByStatus) > 0){
                $pic_required = '|required';
            }
        }

        $this->form_validation->set_rules('id_user', '', 'htmlentities|strip_tags|trim|required|integer|xss_clean');
        $this->form_validation->set_rules('paginated', '', 'htmlentities|strip_tags|trim|required|integer|xss_clean');
        $this->form_validation->set_rules('title', '', 'htmlentities|strip_tags|trim|required|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('pic_caption', '', "htmlentities|strip_tags|trim{$pic_required}|max_length[1000]|xss_clean");
        $this->form_validation->set_rules('short_desc', '', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('meta_title', '', 'htmlentities|strip_tags|trim|max_length[500]|xss_clean');
        $this->form_validation->set_rules('meta_desc', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('meta_keyword', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('category', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('content', '', "htmlentities|strip_tags|trim{$requiredByStatus}|xss_clean");
        $this->form_validation->set_rules('tags', '', 'h tmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('content_status', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('publish_date', '', "htmlentities|strip_tags|trim|xss_clean{$required_if_scheduled}");
        $this->form_validation->set_rules('publish_time', '', "htmlentities|strip_tags|trim|xss_clean{$required_if_scheduled}");
        $this->form_validation->set_rules('featured_on_homepage', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('trending', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('recommended', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('recommended_category', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('featured_on_category', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('on_top_category', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('deletable', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('file_pic', '', 'htmlentities|strip_tags|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('admin_content/add');
        }
        else {
            $now = date("Y-m-d H:i:s");

            //upload content picture
            $content_pic = '';
            $content_pic_thumb = '';
            $content_pic_thumb_square = '';
            if (!empty($_FILES['file_pic']['name'])) {
                $config = $this->contentPicUploadConfig($_FILES['file_pic']['name']);
                $this->upload->initialize($config);

                //upload file article..
                if ($this->upload->do_upload('file_pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $content_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
                    $content_pic_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'], $this->pic_thumb_width, $this->pic_thumb_height);
                    $content_pic_thumb_big = $this->picture->createThumbWithPostfix('_big', $upload_data['upload_data']['full_path'], 810, 425);
                    $content_pic_thumb_small = $this->picture->createThumbWithPostfix('_small', $upload_data['upload_data']['full_path'], 120, 80);
                    $content_pic_thumb_square = $this->picture->createThumbSquare($upload_data['upload_data']['full_path'], $this->pic_square_width, $this->pic_square_height);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_content/add');
                }
            }
            elseif ($status !== '-1') {
                $message = $this->global_lib->generateMessage("You must upload article picture", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('admin_content/add');
            }

            if ($status == 2) {
                $publish_date = $this->input->post('publish_date');
                $is_valid_date = $this->global_lib->validateDate($publish_date, 'd-m-Y');
                if ($is_valid_date) {
                    $publish_date = $this->global_lib->formatDate($publish_date, 'd-m-Y', 'Y-m-d') . ' ' . $this->input->post('publish_time') . ':00';
                }
            }
            else {
                if ($status == 1) {
                    $publish_date = date('Y-m-d H:i:s');
                }
            }

            //insert data ke database..
            $insert_data = array(
                "id_admin" => $this->session->userdata('id_admin'),
                "id_user" => $this->input->post('id_user'),
                "paginated" => $this->input->post('paginated'),
                "title" => $this->input->post('title'),
                "short_desc" => $this->input->post('short_desc'),
                "meta_title" => $this->input->post('meta_title'),
                "meta_desc" => $this->input->post('meta_desc'),
                "meta_keyword" => $this->input->post('meta_keyword'),
                "id_category" => $this->input->post('category'),
                "pic_caption" => $this->input->post('pic_caption'),
                "content" => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('content')),
                "content_status" => $this->input->post('content_status'),
                "deletable" => $this->input->post('deletable'),
                "featured_on_homepage" => $this->input->post('featured_on_homepage'),
                "trending" => $this->input->post('trending'),
                "recommended" => $this->input->post('recommended'),
                "recommended_category" => $this->input->post('recommended_category'),
                "featured_on_category" => $this->input->post('featured_on_category'),
                "on_top_category" => $this->input->post('on_top_category'),
                "content_pic" => $content_pic,
                "content_pic_thumb" => $content_pic_thumb,
                "content_pic_square" => $content_pic_thumb_square,
                "submit_date" => $now,
                "publish_date" => $status > 0 ? $publish_date : NULL,
            );
            $this->mdl_content->insertContent($insert_data);

            //ambil id content yang baru di insert..
            $id = $this->mdl_content->getLatestContentIDByDateTime($now);

            //insert tag article ke dataabse..
            $tag_str = $this->input->post('tags');
            $this->inputTagContent($tag_str, $id);

            $message = 'New content has been added.';
            if (!$isPreview) {
                $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

                if (!is_null($this->input->post('submit_and_add_next_page'))) {
                    redirect("admin_content/addNextPage/{$id}");
                }

                redirect('admin_content/index');
            }
            else {
                $this->session->set_userdata('is_admin_dashboard', true);
                $this->session->set_flashdata('message', $message);

                $name = $this->mdl_user->getNameByID($this->input->post('id_user'));

                redirect("read/{$id}-" . strtolower(url_title($name)) . '/' . strtolower(url_title($this->input->post('title'))));
            }
        }
    }

    public function addNextPage($id_content)
    {
        $this->clearSearchSession();

        $data['content'] = $this->mdl_content->getContentByID($id_content);
        $data['max_page_no'] = intval($this->mdl_content->getMaxPageNo($id_content)) + 1;

        if (!is_array($data['content']) && count($data['content']) < 1) {
            redirect('admin_content/index');
        }

        $data['content'] = $data['content'][0];
        $data['categories'] = $this->getAllCategory();
        $data['users'] = $this->mdl_user->getAllUser();

        $data['form_value'] = $this->session->flashdata('form_value') ?: [
            'page_no' => $data['max_page_no'],
            'content' => ''
        ];

        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->render($this->load->view('shared/content/add_next_page', $data, true));
    }

    public function saveAddNextPage($id_content)
    {
        $isPreview = !is_null($this->input->post('preview'));
        $page_no = $this->input->post('page_no');
        $next_page_no = intval($this->mdl_content->getMaxPageNo($id_content)) + 1;

        $this->session->set_flashdata([
            'form_value' => [
                'page_no' => $page_no,
                'content' => str_replace(base_url() . 'assets/content/', '##BASE_URL##', $this->input->post('content')),
            ]
        ]);

        $this->form_validation->set_rules('page_no', '', "htmlentities|strip_tags|trim|required|integer|xss_clean|greater_than_equal_to[2]|less_than_equal_to[{$next_page_no}]");
        $this->form_validation->set_rules('content', '', "htmlentities|strip_tags|trim|required|xss_clean");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );

            redirect("admin_content/addNextPage/{$id_content}");
        }

        $this->mdl_content->insertContentPage([
            'id_content' => $id_content,
            'page_no' => $page_no,
            'content' => str_replace(base_url() . 'assets/content/', '##BASE_URL##', $this->input->post('content')),
        ]);

        $message = 'Content page has been created.';
        if (!$isPreview) {
            $this->session->set_flashdata('next_page_message', $this->global_lib->generateMessage($message, 'info'));
            redirect("admin_content/edit/{$id_content}#next-page");
        }
        else {
            $this->session->set_userdata('is_admin_dashboard', true);
            $this->session->set_flashdata('message', $message);

            $name = strtolower(url_title($this->mdl_content->getUserNameByID($id_content)));
            $title = strtolower(url_title($this->mdl_content->getTitleByID($id_content)));

            redirect("read/{$id_content}-{$name}/{$title}/{$page_no}");
        }
    }

    public function edit($id_content = '')
    {
        //clear search session yang lama..
        $this->clearSearchSession();

        //ambil data content yang akan diedit.
        $data['content'] = $this->mdl_content->getContentByID($id_content);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['content'])) || count($data['content']) < 1) {
            redirect('admin_content/index');
        }

        $data['content'][0]->publish_time = $data['content'][0]->publish_date ? date('H:i', strtotime($data['content'][0]->publish_date)) : NULL;

        if ($data['content'][0]->edit_id_admin && $data['content'][0]->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect('admin_content/index');
        }

        if ($data['content'][0]->paginated === '1') {
            $data['content'][0]->pages = $this->mdl_content->getAllPages($id_content);
        }

        //ambil semua category
        $data['categories'] = $this->getAllCategory();

        //ambil semua user..
        $data['users'] = $this->mdl_user->getAllUser();

        //ambil tag content.
        $data['tags'] = $this->mdl_content->getTagByIDContent($id_content);

        $data['competition'] = $this->mdl_competition->find($data['content'][0]->id_competition);
        $data['competitions'] = $this->mdl_competition->getByType('article');
        $data['competition_category'] = isset($data['competitions']) && count($data['competitions']) > 0
            ? $this->mdl_competition->getCategory($data['content'][0]->id_competition)
            : [];

        $data['locked_content_id'] = $this->mdl_content->getLockedContentId($this->session->userdata('id_admin'));

        //load view edit admin ...
        $content = $this->load->view('admin/content/edit', $data, true);
        // print_r("<pre>");
        // print_r($data['content']);
        // print_r("</pre>");
        // die();

        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
        $this->render($content);
    }

    public function saveEdit($id_content = '')
    {
        $isPreview = !is_null($this->input->post('preview'));
        $status = $this->input->post('content_status');

        //ambil data content yang akan diedit.
        $data['content'] = $this->mdl_content->getContentByID($id_content);
        if ((!is_array($data['content'])) || count($data['content']) < 1) {
            redirect('admin_content/index');
        }

        if ($data['content'][0]->edit_id_admin && $data['content'][0]->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect('admin_content/index');
        }

        $this->session->set_flashdata(array(
            "content_value" => array(
                "id_user" => $this->input->post('id_user'),
                "id_competition_category" => $this->input->post('id_competition_category'),
                "paginated" => $this->input->post('paginated'),
                "title" => $this->input->post('title'),
                "meta_title" => $this->input->post('meta_title'),
                "meta_desc" => $this->input->post('meta_desc'),
                "meta_keyword" => $this->input->post('meta_keyword'),
                "short_desc" => $this->input->post('short_desc'),
                "id_category" => $this->input->post('category'),
                "pic_caption" => $this->input->post('pic_caption'),
                "featured_on_homepage" => $this->input->post('featured_on_homepage'),
                "trending" => $this->input->post('trending'),
                "recommended" => $this->input->post('recommended'),
                "recommended_category" => $this->input->post('recommended_category'),
                "featured_on_category" => $this->input->post('featured_on_category'),
                "on_top_category" => $this->input->post('on_top_category'),
                "content" => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('content')),
                "content_status" => $status,
                "publish_date" => $this->input->post('publish_date') . ' ' . $this->input->post('publish_time') . ':00',
            )
        ));

        $requiredByStatus = ($status !== '-1' ? "|required" : '');

        $join_competition = $this->input->post('join_competition');
        $pic_required = '';
        if (!(isset($data['content'][0]->id_competition) && $data['content'][0]->id_competition > 0)){
            if (strlen($requiredByStatus) > 0){
                $pic_required = '|required';
            }
        }

        //validasi input
        $this->form_validation->set_rules('id_user', '', 'htmlentities|strip_tags|trim|integer|xss_clean');
        $this->form_validation->set_rules('id_competition_category', '', 'htmlentities|strip_tags|trim|integer|xss_clean');
        $this->form_validation->set_rules('paginated', '', 'htmlentities|strip_tags|trim|required|integer|xss_clean');
        $this->form_validation->set_rules('title', '', 'htmlentities|strip_tags|trim|required|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('short_desc', '', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('meta_title', '', 'htmlentities|strip_tags|trim|max_length[500]|xss_clean');
        $this->form_validation->set_rules('pic_caption', '', "htmlentities|strip_tags|trim{$pic_required}|max_length[1000]|xss_clean");
        $this->form_validation->set_rules('meta_desc', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('meta_keyword', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('category', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('content', '', "htmlentities|strip_tags|trim{$requiredByStatus}|xss_clean");
        $this->form_validation->set_rules('tags', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('content_status', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('deletable', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('featured_on_homepage', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('trending', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('recommended', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('recommended_category', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('on_top_category', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('featured_on_category', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('file_pic', '', 'htmlentities|strip_tags|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Form validation invalid. Please check your form." . validation_errors(), "danger");
            $this->session->set_flashdata('message', $message);
            redirect('admin_content/edit/' . $id_content);
        }
        else {
            //upload content picture
            $content_pic = '';
            $content_pic_thumb = '';
            $content_pic_thumb_square = '';
            if (!empty($_FILES['file_pic']['name'])) {
                $config = $this->contentPicUploadConfig($_FILES['file_pic']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('file_pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $content_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
                    $content_pic_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'], $this->pic_thumb_width, $this->pic_thumb_height);
                    $content_pic_thumb_big = $this->picture->createThumbWithPostfix('_big', $upload_data['upload_data']['full_path'], 810, 425);
                    $content_pic_thumb_small = $this->picture->createThumbWithPostfix('_small', $upload_data['upload_data']['full_path'], 120, 80);
                    $content_pic_thumb_square = $this->picture->createThumbSquare($upload_data['upload_data']['full_path'], $this->pic_square_width, $this->pic_square_height);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_content/edit/' . $id_content);
                }
            }

            if ($status == 2) {
                $publish_date = $this->input->post('publish_date');
                $is_valid_date = $this->global_lib->validateDate($publish_date, 'd-m-Y');
                if ($is_valid_date) {
                    $publish_date = $this->global_lib->formatDate($publish_date, 'd-m-Y', 'Y-m-d') . ' ' . $this->input->post('publish_time') . ':00';
                }
            }
            else {
                if ($status == 1) {
                    $publish_date = date('Y-m-d H:i:s');
                }
            }

            // update data admin ke database
            $update_data = array(
                "id_admin" => $this->session->userdata('id_admin'),
                "id_user" => $this->input->post('id_user'),
                "id_competition_category" => $this->input->post('id_competition_category'),
                "paginated" => $this->input->post('paginated'),
                "title" => $this->input->post('title'),
                "pic_caption" => $this->input->post('pic_caption'),
                "short_desc" => $this->input->post('short_desc'),
                "meta_title" => $this->input->post('meta_title'),
                "meta_desc" => $this->input->post('meta_desc'),
                "meta_keyword" => $this->input->post('meta_keyword'),
                "id_category" => $this->input->post('category'),
                "content" => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('content')),
                "content_status" => $this->input->post('content_status'),
                "deletable" => $this->input->post('deletable'),
                "featured_on_homepage" => $this->input->post('featured_on_homepage'),
                "trending" => $this->input->post('trending'),
                "recommended" => $this->input->post('recommended'),
                "recommended_category" => $this->input->post('recommended_category'),
                "on_top_category" => $this->input->post('on_top_category'),
                "featured_on_category" => $this->input->post('featured_on_category'),
                'edit_id_admin' => in_array($status, ['1', '2']) ? null : $data['content'][0]->edit_id_admin,
            );
            if(! isset($data['content'][0]->publish_date)){
                $update_data['publish_date'] = ($publish_date ?? null);
            }
            if (strlen(trim($content_pic)) > 0 && strlen(trim($content_pic_thumb)) > 0) {
                $update_data['content_pic'] = $content_pic;
                $update_data['content_pic_thumb'] = $content_pic_thumb;
                $update_data['content_pic_square'] = $content_pic_thumb_square;
            }
            $this->mdl_content->updateContent($update_data, $id_content);
            $this->mdl_content_editor->insert_or_update($id_content, $this->session->userdata('id_admin'));

            //update tag content ke dataabse..
            $tag_str = $this->input->post('tags');
            $this->inputTagContent($tag_str, $id_content);

            $this->load->library('point_lib');
            $point_config = array(
                'trigger_type' => 'add_content',
                'id_user' => $data['content'][0]->id_user,
                'desc' => $this->input->post('title')
            );
            if ($status == 1) {
                //jika artikel diaktifkan (sebelumnya tidak aktif), tambah poin..
                if ($data['content'][0]->content_status != 1) {
                    //tambah poin..
                    $this->point_lib->addPoint($point_config);
                }
            }
            else {
                //jika artikel di nonaktifkan (sebelumnya aktif), kurangi point
                if ($data['content'][0]->content_status == 1) {
                    //kurangi poin..
                    $this->point_lib->substractPoint($point_config);
                }
            }

            if (!$isPreview) {
                $message = $this->global_lib->generateMessage("Konten berhasil diupdate.", "info");
                $this->session->set_flashdata('message', $message);
                // redirect('admin_content/edit/' . $id_content);
                redirect('admin_content/index');
            }
            else {
                $this->session->set_userdata('is_admin_dashboard', true);
                $this->session->set_flashdata('message', 'Artikel berhasil disimpan.');
                $name = $this->mdl_user->getNameByID($this->input->post('id_user'));
                redirect("read/{$id_content}-" . strtolower(url_title($name)) . '/' . strtolower(url_title($this->input->post('title'))));
            }
        }
    }

    public function editNextPage($id)
    {
        $this->clearSearchSession();

        $data['page'] = $this->mdl_content->getContentPageByID($id);
        $data['max_page_no'] = intval($this->mdl_content->getMaxPageNo($data['page']->id_content));

        if (is_null($data['page'])) {
            redirect("admin_content/edit/{$data['page']->id_content}#next-page");
        }

        $data['content'] = $this->mdl_content->getContentByID($data['page']->id_content);

        if (!is_array($data['content']) && count($data['content']) < 1) {
            redirect('admin_content/index');
        }

        if ($data['content'][0]->edit_id_admin && $data['content'][0]->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect('admin_content/index');
        }

        $data['content'] = $data['content'][0];
        $data['categories'] = $this->getAllCategory();
        $data['users'] = $this->mdl_user->getAllUser();

        $data['form_value'] = $this->session->flashdata('form_value') ?: (array)$data['page'];

        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->render($this->load->view('shared/content/edit_next_page', $data, true));
    }

    public function saveEditNextPage($id)
    {
        $isPreview = !is_null($this->input->post('preview'));
        $page_no = $this->input->post('page_no');
        $page = $this->mdl_content->getContentPageByID($id);

        if (is_null($page)) {
            redirect("admin_content/edit/{$page->id_content}#next-page");
        }

        $data['content'] = $this->mdl_content->getContentByID($data['page']->id_content);

        if (!is_array($data['content']) && count($data['content']) < 1) {
            redirect('admin_content/index');
        }

        if ($data['content'][0]->edit_id_admin && $data['content'][0]->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect('admin_content/index');
        }

        $max_page_no = intval($this->mdl_content->getMaxPageNo($page->id_content));

        $this->session->set_flashdata([
            'form_value' => [
                'page_no' => $page_no,
                'content' => str_replace(base_url() . 'assets/content/', '##BASE_URL##', $this->input->post('content')),
            ]
        ]);

        $this->form_validation->set_rules('page_no', '', "htmlentities|strip_tags|trim|required|integer|xss_clean|greater_than_equal_to[2]|less_than_equal_to[{$max_page_no}]");
        $this->form_validation->set_rules('content', '', "htmlentities|strip_tags|trim|required|xss_clean");

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );

            redirect("admin_content/editNextPage/{$id}");
        }

        $this->mdl_content->updateContentPage([
            'page_no' => $page_no,
            'content' => str_replace(base_url() . 'assets/content/', '##BASE_URL##', $this->input->post('content')),
        ], $page);

        $message = 'Content page has been updated.';
        if (!$isPreview) {
            $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));
            redirect("admin_content/editNextPage/{$id}");
        }
        else {
            $this->session->set_userdata('is_admin_dashboard', true);
            $this->session->set_flashdata('message', $message);

            $name = strtolower(url_title($this->mdl_content->getUserNameByID($page->id_content)));
            $title = strtolower(url_title($this->mdl_content->getTitleByID($page->id_content)));

            redirect("read/{$page->id_content}-{$name}/{$title}/{$page_no}");
        }
    }

    public function delete($id_content = '')
    {
        //ambil data content yang akan diedit.
        $data = $this->mdl_content->getContentByID($id_content);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('admin_content/index');
        }

        if ($data[0]->edit_id_admin && $data[0]->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect('admin_content/index');
        }

        //cek apakah content deletable atau tidak.
        if ($data[0]->deletable != '1' && $this->session->userdata('admin_level') != '1') {
            $message = $this->global_lib->generateMessage("You can't delete this content. Contact your super adminintrator.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('admin_content/index');
        }

        if (isset($data[0]->content_pic) && strlen(trim($data[0]->content_pic)) > 0) {
            @unlink('assets/content/' . $data[0]->content_pic);
        }

        $this->mdl_content->deleteContent($id_content);
        $this->mdl_content->deleteTagByIDContent($id_content);

        //jika content status = 1. kurangi point. Jika status = 0 (non aktif) tidak usah dikurangi lagi poinnya.
        if ($data[0]->content_status == 1) {
            $this->load->library('point_lib');
            //kurangi poin..
            $point_config = array(
                'trigger_type' => 'add_content',
                'id_user' => $data[0]->id_user,
                'desc' => $data[0]->title
            );
            $this->point_lib->substractPoint($point_config);
        }

        $message = $this->global_lib->generateMessage("Content has been deleted.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('admin_content/index');
    }

    public function deleteNextPage($id)
    {
        $page = $this->mdl_content->getContentPageByID($id);

        if (is_null($page)) {
            redirect("admin_content/edit/{$page->id_content}#next-page");
        }

        $data = $this->mdl_content->getContentByID($page->id_content);

        if ((!is_array($data)) || count($data) < 1) {
            redirect('admin_content/index');
        }

        if ($data[0]->edit_id_admin && $data[0]->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect('admin_content/index');
        }

        //cek apakah content deletable atau tidak.
        if ($data[0]->deletable != '2' && $this->session->userdata('admin_level') != '1') {
            $this->session->set_flashdata(
                'next_page_message',
                $this->global_lib->generateMessage("You can't delete this content Page. Contact your super adminintrator.", "danger")
            );
            redirect("admin_content/edit/{$page->id_content}#next-page");
        }

        $this->mdl_content->deleteContentPage($page);

        // Delete content image
        $htmlDom = new DOMDocument;
        $htmlDom->loadHTML(html_entity_decode($page->content));
        $imageTags = $htmlDom->getElementsByTagName('img');

        foreach ($imageTags as $imageTag) {
            $filepath_encoded = $imageTag->getAttribute('src');
            $filepath = str_replace('##BASE_URL##', 'assets/content/', $imageTag->getAttribute('src'));

            if (file_exists($filepath) && !$this->mdl_content->isContentImageStillUsed($filepath_encoded)) {
                unlink($filepath);
            }
        }

        $this->session->set_flashdata('next_page_message', $this->global_lib->generateMessage("Content Page has been deleted.", "info"));

        redirect("admin_content/edit/{$page->id_content}#next-page");
    }

    public function lock_edit($id_content = '')
    {
        $this->_toggle_lock_edit(TRUE, $id_content);
    }

    public function unlock_edit($id_content = '')
    {
        $this->_toggle_lock_edit(FALSE, $id_content);
    }

    public function submitSearch()
    {
        //validasi input..
        $this->form_validation->set_rules('search_by', '', 'htmlentities|strip_tags|trim|required|xss_clean');
        $this->form_validation->set_rules('operator', '', 'htmlentities|strip_tags|required|trim|xss_clean');
        $this->form_validation->set_rules('keyword', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('per_page', '', 'htmlentities|strip_tags|required|trim|xss_clean');
        $this->form_validation->set_rules('sort_by', '', 'htmlentities|strip_tags|required|trim|xss_clean');
        $this->form_validation->set_rules('start_date', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('finish_date', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('like_start_date', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('like_finish_date', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('content_status', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('category', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('competition', '', 'htmlentities|strip_tags|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $message = $this->global_lib->generateMessage(validation_errors(), "info");
            $this->session->set_flashdata('message', $message);
            redirect('admin_content/index');
        }
        else {
            //clear search session yang lama..
            $this->clearSearchSession();

            //ambil data input dan restore ke session sebagai parameter search..
            $search_param = array(
                'search_by' => $this->input->post('search_by'),
                'operator' => html_entity_decode($this->input->post('operator')),
                'keyword' => $this->input->post('keyword'),
                'per_page' => $this->input->post('per_page'),
                'sort_by' => $this->input->post('sort_by'),
                'content_status' => $this->input->post('content_status'),
                'category' => $this->input->post('category'),
                'competition' => $this->input->post('competition'),
                'start_date' => $this->input->post('start_date'),
                'finish_date' => $this->input->post('finish_date'),
                'like_start_date' => $this->input->post('like_start_date'),
                'like_finish_date' => $this->input->post('like_finish_date'),
                'search_collapsed' => $this->input->post('search_collapsed')
            );
            $this->session->set_userdata('search_content', $search_param);

            redirect('admin_content/search');
        }
    }

    public function search()
    {
        // ambil parameter search di session..
        $search_param = $this->session->userdata('search_content');

        // ================= Syncronize parameter dengan field di database =====================//
        $operator_list = array('like', 'not like');
        $field_list = array('title', 'short_desc', 'content');
        $sort_by_list = array(
            'default' => 'publish_date DESC',
            'newest' => 'publish_date DESC',
            'oldest' => 'id_content ASC',
            'title_asc' => 'title ASC',
            'title_desc' => 'title DESC',
            'least_liked' => 'like_count ASC',
            'most_liked' => 'like_count DESC',
        );
        // ======================================================================================//

        // ========================== Validasi parameter2 searching =============================//
        // validasi search by..
        if (!in_array($search_param['search_by'], $field_list)) {
            redirect('admin_content/index');
        }

        //validasi operator..
        if (!in_array($search_param['operator'], $operator_list)) {
            redirect('admin_content/index');
        }

        //validasi sort_by..
        $sort_by = $sort_by_list[$search_param['sort_by']];
        if ($sort_by == '' || $sort_by == null) {
            redirect('admin_content/index');
        }
        //ganti search_by (field alias) dengan nama field..
        $search_param['sort_by'] = $sort_by;

        //validasi per page..
        $per_page = $search_param['per_page'];
        if ($per_page <= 0) {
            redirect('admin_content/index');
        }
        // =========================================================================================//

        //ambil parameter2 dan hasil pencarian..
        $data['total_row'] = $this->mdl_content->getSearchResultCount($search_param);
        $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
        $this->pagination->initialize($config);

        $data['content'] = $this->mdl_content->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));

        //ambil semua category
        $data['categories'] = $this->getAllCategory();
        $data['competitions'] = $this->mdl_competition->getByType('article');

        //load view search result..
        $content = $this->load->view('admin/content/all', $data, true);

        $this->render($content);
    }

    private function _toggle_lock_edit($is_lock, $id_content)
    {
        $data['content'] = $this->mdl_content->getContentByID($id_content);

        if (!$is_lock && $data['content'][0]->edit_id_admin != $this->session->userdata('id_admin') && $this->session->userdata('admin_level') !== '1') {
            redirect($_SERVER['HTTP_REFERER'] ?? 'admin_content/index');
        }

        $locked_content_id = $this->mdl_content->getLockedContentId($this->session->userdata('id_admin'));
        $is_valid_lock = $is_lock && is_null($locked_content_id);
        $is_valid_unlock = !$is_lock && (
                $data['content'][0]->edit_id_admin == $this->session->userdata('id_admin') || $this->session->userdata('admin_level') === '1'
            );

        if ($is_valid_lock || $is_valid_unlock) {
            $this->mdl_content->updateContent([
                'edit_id_admin' => $is_lock ? $this->session->userdata('id_admin') : NULL,
            ], $id_content);
        }

        redirect($_SERVER['HTTP_REFERER'] ?? "admin_content/edit/{$id_content}");
    }

    private function render($page = null)
    {
        if (isset($page) && $page !== null) {
            //load page view
            $data['content'] = $page;

            //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
            $data['css_files'] = $this->css;
            $data['js_files'] = $this->js;

            //load module global data
            $data['global_data'] = $this->global_lib->getGlobalData();
            $data['modules'] = $this->global_lib->generateAdminModule();

            //load view template
            $this->load->view('/admin/template', $data);
        }
        else {
            redirect('page/index');
        }
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
            // $indentation .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            $indentation .= "";
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

    private function contentPicUploadConfig($filename = '')
    {
        $config['upload_path'] = './assets/content/';
        $config['allowed_types'] = 'jpg|jpeg|JPG|JPEG';
        $config['max_size'] = '12000';
        $config['max_width'] = '8000';
        $config['max_height'] = '8000';
        if (strlen(trim($filename)) > 0) {
            $config['file_name'] = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
        }
        return $config;
    }

    private function paginationConfig($total_rows)
    {
        $config = $this->global_lib->paginationConfigAdmin();
        $config['base_url'] = base_url() . 'admin_content/index/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->pagination_per_page;
        $config['uri_segment'] = 3;
        return $config;
    }

    private function searchPaginationConfig($total_row, $per_page)
    {
        $config = $this->global_lib->paginationConfigAdmin();
        $config['base_url'] = base_url() . 'admin_content/search/';
        $config['total_rows'] = $total_row;
        $config['per_page'] = ($per_page > 0 ? $per_page : $this->pagination_per_page);
        $config['uri_segment'] = 3;
        return $config;
    }

    private function clearSearchSession()
    {
        //declare session search..
        $search_param = array(
            'search_by' => 'default',
            'operator' => null,
            'keyword' => null,
            'sort_by' => 'default',
            'per_page' => $this->pagination_per_page,
            'category' => 'all',
            'competition' => 'all',
            'content_status' => 'all',
            'start_date' => null,
            'finish_date' => null,
            'like_start_date' => null,
            'like_finish_date' => null,
            'search_collapsed' => '1',
        );
        $this->session->set_userdata('search_content', $search_param);
    }

    private function inputTagContent($tag_str, $id_content)
    {
        $this->mdl_content->deleteTagByIDContent($id_content);
        $tag_piece = explode(',', $tag_str);
        $insert_data = array();
        foreach ($tag_piece as $tg) {
            if (strlen(trim($tg)) > 0) {
                $insert_arr = array(
                    'id_content' => $id_content,
                    'tag_name' => $tg
                );
                array_push($insert_data, $insert_arr);
            }
        }
        if (count($insert_data) > 0) {
            $this->mdl_content->insertTag($insert_data);
        }
    }
}
