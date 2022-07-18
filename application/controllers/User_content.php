<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
*
* @author 		: Hengky Mulyono <hengkymulyono301@gmail.com>
* @copyright	: Binari - 2020
* @copyright	: mail@binary-project.com
* @version		: Release: v1
* @link			  : www.binary-project.com
* @contact		: 0822 3709 9004
*
*/

class User_content extends CI_Controller
{
    var $js = [];
    var $css = [];
    var $category_index = 0;
    var $category_list = array();
    var $pagination_per_page = 20;
    var $pic_width = 1900;
    var $pic_height = 998;
    var $pic_thumb_width = 390;
    var $pic_thumb_height = 260;
    var $pic_square_width = 400;
    var $pic_square_height = 400;

    public function __construct()
    {
        parent::__construct();

        //load library
        $this->load->library('email_lib');

        //load model
        $this->load->model('mdl_category');
        $this->load->model('mdl_content');
        $this->load->model('mdl_user');
        $this->load->model('mdl_competition');

        //construct script
        if ($this->session->userdata('user_logged_in') !== true) {
            //$redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())), "=");
            $redirect_url = encodeUrl($this->uri->uri_string());
            redirect("page/login/" . $redirect_url);
        }
    }

    public function index()
    {
        $id_user = $this->session->userdata('id_user');
        //clear search session yang lama
        $this->clearSearchSession();

        //ambil total row untuk keperluan config pagination dan jumlah data di depan
        $data['total_row'] = $this->mdl_content->getAllContentByIDUserCount($id_user);
        $config = $this->paginationConfig($data['total_row']);
        $this->pagination->initialize($config);

        $data['content'] = $this->mdl_content->getAllContentByIDUserLimit($id_user, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));

        //ambil semua category
        $data['categories'] = $this->getAllCategory();

        //load view.
        $content = $this->load->view('user/content/all', $data, true);
        $this->render($content);
    }

    public function add()
    {
        //clear search session yang lama
        $this->clearSearchSession();

        //ambil semua category
        $data['is_verified_member'] = $this->session->userdata('user_verified') === '1';
        $data['categories'] = $this->getAllCategory();

        $data['content_value'] = $this->session->flashdata('content_value') ?: ['paginated' => 0];
        $data['content_value']['join_competition'] = $this->input->get('join_competition') ?: '0';
        $data['content_value']['agree_competition_terms'] = $this->input->get('join_competition') ?: '0';

        $data['competitions'] = $this->mdl_competition->get_active('article');
        $data['competition_category'] = [];
        if(isset($data['competitions']) && count($data['competitions']) > 0) {
            $data['competition_category'] = $this->mdl_competition->getCategory($data['competitions'][0]->id_competition);
        }

        //load view add admin
        $content = $this->load->view('user/content/add', $data, true);

        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>';
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
        $this->render($content);
    }

    public function saveAdd()
    {
        $isPreview = !is_null($this->input->post('preview'));
        $is_verified_member = $this->session->userdata('user_verified') === '1';
        $status = $this->input->post('status');
        $required = ($status !== '-1' ? true : false);

        $join_competition = $this->input->post('join_competition');
        $category_required = (!(isset($join_competition) && $join_competition == '1'));

        $this->session->set_flashdata([
            "content_value" => [
                "title" => $this->input->post('title'),
                "short_desc" => $this->input->post('short_desc'),
                "id_category" => $this->input->post('category'),
                "pic_caption" => $this->input->post('pic_caption'),
                "join_competition" => $this->input->post('join_competition'),
                "id_competition" => $this->input->post('id_competition'),
                "id_competition_category" => $this->input->post('id_competition_category'),
                "content" => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('content')),
                "content_status" => $status
            ]
        ]);

        if ($is_verified_member) {
            $this->form_validation->set_rules('paginated', '', 'htmlentities|strip_tags|trim|required|integer|xss_clean');
        }

        $this->form_validation->set_rules('title', 'Judul', 'htmlentities|strip_tags|trim|required|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('short_desc', 'Deskripsi', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('category', 'Kategori', 'htmlentities|strip_tags|trim|xss_clean' . ($category_required ? '|required' : ''));
        $this->form_validation->set_rules('content', 'Konten', 'htmlentities|strip_tags|trim|xss_clean' . ($required ? '|required' : ''));
        $this->form_validation->set_rules('tags', 'Tag', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('join_competition', 'Kompetisi', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_competition', 'Kompetisi', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_competition_category', 'Kategori Kompetisi', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'htmlentities|strip_tags|trim|xss_clean|integer|in_list[-1,0]');

        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Validasi form gagal. Mohon isi form artikel dengan format yang benar dan lengkap." . validation_errors(), "danger");
            $this->session->set_flashdata('message', $message);
            redirect('user_content/add');
        }
        else {
            $id_category = $this->input->post('category');
            $id_competition = $this->input->post('id_competition');
            $id_competition_category = $this->input->post('id_competition_category');
            $paginated = $this->input->post('paginated');

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
                    $message = $this->global_lib->generateMessage("Gagal mengupload gambar artikel. <br/>" . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('user_content/add');
                }
            }

            //jika ikut kompetisi, check maximum kompetisi
            if (isset($join_competition) && $join_competition == '1' && isset($id_competition) && $id_competition > 0){
                $submission = $this->checkCompetitionMaximumSubmit($id_competition);
                if(! $submission['allow_submit']){
                    $message = $this->global_lib->generateMessage($submission['message'], "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('user_content/add');
                }
                else{
                    $paginated = 0;
                }
            }

            //check join competition
            if ($join_competition != '1'){
                $id_competition = null;
                $id_competition_category = null;
            }
            else{
                $id_category = null;
            }

            //insert data ke database
            $hash = $this->global_lib->generateHash();
            $insert_data = [
                'paginated' => $paginated,
                'id_competition' => $id_competition,
                'id_competition_category' => $id_competition_category,
                'id_user' => $this->session->userdata('id_user'),
                'title' => $this->input->post('title'),
                'short_desc' => $this->input->post('short_desc'),
                'id_category' => $id_category,
                'pic_caption' => $this->input->post('pic_caption'),
                'content' => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('content')),
                'content_status' => $status,
                'deletable' => 1,
                'featured_on_homepage' => 0,
                'trending' => 0,
                'recommended' => 0,
                'recommended_category' => 0,
                // "show_sidebar" => 1,
                'content_pic' => $content_pic,
                'content_pic_thumb' => $content_pic_thumb,
                'content_pic_square' => $content_pic_thumb_square,
                'submit_date' => $now,
                'hash' => $hash
            ];
            $this->mdl_content->insertContent($insert_data);

            //ambil id content yang baru di insert..
            $id = $this->mdl_content->getLatestContentIDByDateTime($now);

            //insert tag article ke dataabse..
            $tag_str = $this->input->post('tags');
            $this->inputTagContent($tag_str, $id);

            if ($is_verified_member) {
                $this->load->library('point_lib');
                $point_config = [
                    'trigger_type' => 'add_content',
                    'id_user' => $this->session->userdata('id_user'),
                    'desc' => $this->input->post('title'),
                ];
                if ($status === '1') {
                    $this->point_lib->addPoint($point_config);
                }
            }

            if ($status === '-1') {
                $message = 'Artikel anda berhasil disimpan.';

                if (!$is_verified_member && !$isPreview) {
                    $message .= 'Setelah anda mengirim artikel, selanjutnya admin akan melakukan moderasi sebelum artikel anda dapat ditayangkan di website.';
                }
            }
            else {
                $message = 'Artikel anda berhasil dikirim. Admin akan melakukan moderasi sebelum artikel anda dapat ditayangkan di website.';

                //send email notification to admin..
                $email_config = array(
                    'id_user' => $this->session->userdata('id_user'),
                    'id_content' => $id,
                    'title' => $this->input->post('title')
                );
                $this->email_lib->newArticleAdmin($email_config);
            }

            if (!$isPreview) {
                $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

                if (!is_null($this->input->post('submit_and_add_next_page'))) {
                    redirect("user_content/addNextPage/{$id}");
                }

                redirect('user_content/index');
            }
            else {
                $this->session->set_userdata('is_admin_dashboard', false);
                $this->session->set_flashdata('message', $message);
                // $redirect_url = base_url() . "read/{$id}-" . strtolower(url_title($this->session->userdata('user_name'))) . '/' . strtolower(url_title($this->input->post('title')));
                // $redirect_url2 = base_url() . 'user_content/index';
                // echo "<script>window.open('" . $redirect_url . "', '_blank');window.focus();window.location.replace('" . $redirect_url2 . "');</script>";
                // redirect('user_content/index');
                // redirect("read/{$id}-" . strtolower(url_title($this->session->userdata('user_name'))) . '/' . strtolower(url_title($this->input->post('title'))));
                redirect('user_content/index');
            }
        }
    }

    public function addNextPage($id_content)
    {
        $this->_check_verified_member();
        $this->clearSearchSession();

        $data['content'] = $this->mdl_content->getContentByID($id_content);
        $data['max_page_no'] = intval($this->mdl_content->getMaxPageNo($id_content)) + 1;

        if (!is_array($data['content']) && count($data['content']) < 1) {
            redirect('user_content/index');
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
        $this->_check_verified_member();

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

            redirect("user_content/addNextPage/{$id_content}");
        }

        $this->mdl_content->insertContentPage([
            'id_content' => $id_content,
            'page_no' => $page_no,
            'content' => str_replace(base_url() . 'assets/content/', '##BASE_URL##', $this->input->post('content')),
        ]);

        $message = 'Halaman Artikel berhasil disimpan.';
        if (!$isPreview) {
            $this->session->set_flashdata('next_page_message', $this->global_lib->generateMessage($message, 'info'));
            redirect("user_content/edit/{$id_content}#next-page");
        }
        else {
            $this->session->set_userdata('is_admin_dashboard', false);
            $this->session->set_flashdata('message', $message);

            $name = strtolower(url_title($this->mdl_content->getUserNameByID($id_content)));
            $title = strtolower(url_title($this->mdl_content->getTitleByID($id_content)));

            redirect("read/{$id_content}-{$name}/{$title}/{$page_no}");
        }
    }

    public function edit($id_content = '')
    {
        $id_user = $this->session->userdata('id_user');
        //clear search session yang lama
        $this->clearSearchSession();

        $data['user_name'] = $this->session->userdata('user_name');
        //ambil data content yang akan diedit.
        $data['content'] = $this->mdl_content->getContentByIDAndIDUser($id_content, $id_user);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['content'])) || count($data['content']) < 1) {
            redirect('user_content/index');
        }

        $data['content_value'] = $this->session->flashdata('content_value') ?: (array)$data['content'][0];
        $data['content_value']['agree_competition_terms'] = !is_null($data['content'][0]->id_competition);

        if ($data['content'][0]->paginated === '1') {
            $data['content'][0]->pages = $this->mdl_content->getAllPages($id_content);
        }

        $data['is_verified_member'] = $this->session->userdata('user_verified') === '1';

        //ambil semua category
        $data['categories'] = $this->getAllCategory();

        //ambil tag content
        $data['tags'] = $this->mdl_content->getTagByIDContent($id_content);

        $data['competitions'] = $this->mdl_competition->get_active('article');
        $data['competition_category'] = [];
        if(isset($data['competitions']) && count($data['competitions']) > 0) {
            if (isset($data['content'][0]->id_competition) && $data['content'][0]->id_competition > 0) {
                $data['competition_category'] = $this->mdl_competition->getCategory($data['content'][0]->id_competition);
            }
            else{
                $data['competition_category'] = $this->mdl_competition->getCategory($data['competitions'][0]->id_competition);
            }
        }

        //jika kompetisi sudah lewat, tidak boleh diedit..
        $data['allow_competition_edit'] = true;
        if(isset($data['content'][0]->id_competition) && $data['content'][0]->id_competition > 0 &&
            strtotime($data['content'][0]->finish_date . ' 00:00:00') < strtotime(date('Y-m-d H:i:s'))
        ){
            $data['allow_competition_edit'] = false;
        }

        //load view edit admin
        $content = $this->load->view('user/content/edit', $data, true);
        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
        $this->render($content);
    }

    public function saveEdit($id_content = '')
    {
        $id_user = $this->session->userdata('id_user');
        $user_name = $this->session->userdata('user_name');
        $is_verified_member = $this->session->userdata('user_verified') === '1';

        //ambil data content yang akan diedit.
        $data['content'] = $this->mdl_content->getContentByIDAndIDUser($id_content, $id_user);
        if ((!is_array($data['content'])) || count($data['content']) < 1) {
            redirect('user_content/index');
        }

        $isPreview = !is_null($this->input->post('preview'));
        $status = $this->input->post('status') ?? -1;
        $required = ($status !== '-1' ? true : false);

        $join_competition = $this->input->post('join_competition');
        $category_required = (!(isset($join_competition) && $join_competition == '1'));

        $this->session->set_flashdata(array(
            "content_value" => array(
                "paginated" => $this->input->post('paginated') ?: 0,
                "title" => $this->input->post('title'),
                "short_desc" => $this->input->post('short_desc'),
                "id_category" => $this->input->post('category'),
                "pic_caption" => $this->input->post('pic_caption'),
                "join_competition" => $this->input->post('join_competition'),
                "id_competition" => $this->input->post('id_competition'),
                "id_competition_category" => $this->input->post('id_competition_category'),
                "content" => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('content')),
                "content_status" => $status
            )
        ));

        if ($is_verified_member) {
            $this->form_validation->set_rules('paginated', '', 'htmlentities|strip_tags|trim|required|integer|xss_clean');
        }

        //validasi input
        $this->form_validation->set_rules('title', '', 'htmlentities|strip_tags|trim|required|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('short_desc', '', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('category', '', 'htmlentities|strip_tags|trim|xss_clean' . ($category_required ? '|required' : ''));
        $this->form_validation->set_rules('content', '', 'htmlentities|strip_tags|trim|xss_clean' . ($required ? '|required' : ''));
        $this->form_validation->set_rules('tags', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('join_competition', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_competition', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_competition_category', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('status', '', 'htmlentities|strip_tags|trim|xss_clean|integer|in_list[-1,0]');

        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Validasi form gagal. Mohon isi form artikel dengan format yang benar dan lengkap.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('user_content/edit/' . $id_content);
        }
        else {
            $id_category = $this->input->post('category');
            $id_competition = $this->input->post('id_competition');
            $id_competition_category = $this->input->post('id_competition_category');
            $paginated = $this->input->post('paginated');

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
                    $message = $this->global_lib->generateMessage("Gagal mengupload gambar artikel. <br/>" . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('user_content/edit/' . $id_content);
                }
            }

            //jika ikut kompetisi, check maximum kompetisi
            if (
                (isset($join_competition) && $join_competition == '1' && $data['content'][0]->id_competition > 0) &&
                (isset($id_competition) && $id_competition > 0 && $id_competition != ($data['content'][0]->id_competition ?? '0'))
            ){
                $submission = $this->checkCompetitionMaximumSubmit($id_competition);
                if(! $submission['allow_submit']){
                    $message = $this->global_lib->generateMessage($submission['message'], "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('user_content/edit/' . $id_content);
                }
                else{
                    $paginated = 0;
                }
            }

            //check join competition
            if ($join_competition != '1'){
                $id_competition = null;
                $id_competition_category = null;
            }
            else{
                $id_category = null;
            }

            // update data admin ke database..
            $update_data = array(
                'paginated' => $paginated,
                'id_competition' => $id_competition,
                'id_competition_category' => $id_competition_category,
                "title" => $this->input->post('title'),
                "short_desc" => $this->input->post('short_desc'),
                "id_category" => $id_category,
                "pic_caption" => $this->input->post('pic_caption'),
                "content" => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('content')),
                "content_status" => $status
            );
            if (strlen(trim($content_pic)) > 0 && strlen(trim($content_pic_thumb)) > 0) {
                $update_data['content_pic'] = $content_pic;
                $update_data['content_pic_thumb'] = $content_pic_thumb;
                $update_data['content_pic_square'] = $content_pic_thumb_square;
            }
            $this->mdl_content->updateContent($update_data, $id_content);

            //update tag content ke dataabse..
            $tag_str = $this->input->post('tags');
            $this->inputTagContent($tag_str, $id_content);

            /*
            // Bugs: kalau update dari status draft ke menunggu konfirmasi poinnya berkurang (harusnya tidak)
                  if ($is_verified_member && $data['content'][0]->content_status !== $status)
                  {
                      $this->load->library('point_lib');
                      $point_config = [
                          'trigger_type' => 'add_content',
                          'id_user' => $this->session->userdata('id_user'),
                          'desc' => $this->input->post('title'),
                      ];

                      $status === '1'
                          ? $this->point_lib->addPoint($point_config)
                          : $this->point_lib->substractPoint($point_config);
                  }
            */

            if ($is_verified_member) {
                $this->load->library('point_lib');
                $point_config = [
                    'trigger_type' => 'add_content',
                    'id_user' => $this->session->userdata('id_user'),
                    'desc' => $this->input->post('title'),
                ];

                if ($status == '1') {
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
            }

            if ($status === '-1') {
                $message = 'Artikel anda berhasil disimpan.';

                if (!$is_verified_member && !$isPreview) {
                    $message .= ' Setelah anda mengirim artikel, selanjutnya admin akan melakukan moderasi sebelum artikel anda dapat ditayangkan di website.';
                }
            }
            else {
                $message = 'Artikel anda berhasil dikirim. Admin akan melakukan moderasi sebelum artikel anda dapat ditayangkan di website.';
            }

            if (!$isPreview) {
                $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, "info"));
                // redirect('user_content/edit/' . $id_content);
                redirect('user_content/index');
            }
            else {
                $this->session->set_userdata('is_admin_dashboard', false);
                $this->session->set_flashdata('message', $message);
                redirect("read/{$id_content}-" . strtolower(url_title($this->session->userdata('user_name'))) . '/' . strtolower(url_title($this->input->post('title'))));
            }
        }
    }

    public function editNextPage($id)
    {
        $this->_check_verified_member();
        $this->clearSearchSession();

        $data['page'] = $this->mdl_content->getContentPageByID($id);
        $data['max_page_no'] = intval($this->mdl_content->getMaxPageNo($data['page']->id_content));

        if (is_null($data['page'])) {
            redirect("user_content/edit/{$data['page']->id_content}#next-page");
        }

        $data['content'] = $this->mdl_content->getContentByID($data['page']->id_content);

        if (!is_array($data['content']) && count($data['content']) < 1) {
            redirect('user_content/index');
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
        $this->_check_verified_member();

        $isPreview = !is_null($this->input->post('preview'));
        $page_no = $this->input->post('page_no');
        $page = $this->mdl_content->getContentPageByID($id);

        if (is_null($page)) {
            redirect("user_content/edit/{$page->id_content}#next-page");
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

            redirect("user_content/editNextPage/{$id}");
        }

        $this->mdl_content->updateContentPage([
            'page_no' => $page_no,
            'content' => str_replace(base_url() . 'assets/content/', '##BASE_URL##', $this->input->post('content')),
        ], $page);

        $message = 'Halaman Artikel berhasil disimpan.';
        if (!$isPreview) {
            $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));
            redirect("user_content/editNextPage/{$id}");
        }
        else {
            $this->session->set_userdata('is_admin_dashboard', false);
            $this->session->set_flashdata('message', $message);

            $name = strtolower(url_title($this->mdl_content->getUserNameByID($page->id_content)));
            $title = strtolower(url_title($this->mdl_content->getTitleByID($page->id_content)));

            redirect("read/{$page->id_content}-{$name}/{$title}/{$page_no}");
        }
    }

    public function delete($id_content = '')
    {
        $id_user = $this->session->userdata('id_user');
        $is_verified_member = $this->session->userdata('user_verified') === '1';

        //ambil data content yang akan diedit.
        $data = $this->mdl_content->getContentByIDAndIDUser($id_content, $id_user);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('user_content/index');
        }

        if (isset($data[0]->content_pic) && strlen(trim($data[0]->content_pic)) > 0) {
            @unlink('assets/content/' . $data[0]->content_pic);
        }

        $this->mdl_content->deleteContent($id_content);
        $this->mdl_content->deleteTagByIDContent($id_content);

        if ($is_verified_member) {
            $this->load->library('point_lib');

            $point_config = [
                'trigger_type' => 'add_content',
                'id_user' => $this->session->userdata('id_user'),
                'desc' => $data[0]->title,
            ];

            if ($data[0]->content_status === '1') {
                $this->point_lib->substractPoint($point_config);
            }
        }

        $message = $this->global_lib->generateMessage('Artikel berhasil dihapus', "info");
        $this->session->set_flashdata('message', $message);
        redirect('user_content/index');
    }

    public function deletePic($id_content = '')
    {
        //ambil data admin yang akan diedit.
        $data = $this->mdl_content->getContentByID($id_content);
        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('user_content/index');
        }

        //jika tidak ada redirect ke halaman edit
        if ((!isset($data[0]->content_pic)) || strlen(trim($data[0]->content_pic)) == 0) {
            redirect('user_content/edit/' . $id_content);
        }
        else {
            $update_data = array(
                'content_pic' => "",
            );
            $this->mdl_content->updateContent($update_data, $id_content);

            //hapus file.
            @unlink('assets/content/' . $data[0]->content_pic);
            @unlink('assets/content/thumb/' . $data[0]->content_pic_thumb);
            redirect("user_content/edit/" . $id_content);
        }
    }

    public function deleteNextPage($id)
    {
        $this->_check_verified_member();

        $page = $this->mdl_content->getContentPageByID($id);

        if (is_null($page)) {
            redirect("user_content/edit/{$page->id_content}#next-page");
        }

        $data = $this->mdl_content->getContentByID($page->id_content);

        if ((!is_array($data)) || count($data) < 1) {
            redirect('user_content/index');
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

        $this->session->set_flashdata('next_page_message', $this->global_lib->generateMessage('Halaman Artikel berhasil dihapus', "info"));

        redirect("user_content/edit/{$page->id_content}#next-page");
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
        $this->form_validation->set_rules('content_status', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('category', '', 'htmlentities|strip_tags|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $message = $this->global_lib->generateMessage(validation_errors(), "info");
            $this->session->set_flashdata('message', $message);
            redirect('user_content/index');
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
                'start_date' => $this->input->post('start_date'),
                'finish_date' => $this->input->post('finish_date'),
                'search_collapsed' => $this->input->post('search_collapsed')
            );
            $this->session->set_userdata('search_content', $search_param);

            redirect('user_content/search');
        }
    }

    public function search()
    {
        $id_user = $this->session->userdata('id_user');

        // ambil parameter search di session..
        $search_param = $this->session->userdata('search_content');

        // ================= Syncronize parameter dengan field di database =====================//
        $operator_list = array('like', 'not like');
        $field_list = array('title', 'short_desc', 'content');
        $sort_by_list = array(
            'default' => 'id_content DESC',
            'newest' => 'id_content DESC',
            'oldest' => 'id_content ASC',
            'title_asc' => 'title ASC',
            'title_desc' => 'title DESC'
        );
        // ======================================================================================//

        // ========================== Validasi parameter2 searching =============================//
        // validasi search by..
        if (!in_array($search_param['search_by'], $field_list)) {
            redirect('user_content/index');
        }

        //validasi operator..
        if (!in_array($search_param['operator'], $operator_list)) {
            redirect('user_content/index');
        }

        //validasi sort_by..
        $sort_by = $sort_by_list[$search_param['sort_by']];
        if ($sort_by == '' || $sort_by == null) {
            redirect('user_content/index');
        }
        //ganti search_by (field alias) dengan nama field..
        $search_param['sort_by'] = $sort_by;

        //validasi per page..
        $per_page = $search_param['per_page'];
        if ($per_page <= 0) {
            redirect('user_content/index');
        }
        // =========================================================================================//

        //ambil parameter2 dan hasil pencarian..
        $data['total_row'] = $this->mdl_content->getSearchResultByIDUserCount($id_user, $search_param);
        $config = $this->paginationConfig($data['total_row']);
        $this->pagination->initialize($config);

        $data['content'] = $this->mdl_content->getSearchResultByIDUser($id_user, $search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));

        //ambil semua category
        $data['categories'] = $this->getAllCategory();

        //load view search result..
        $content = $this->load->view('user/content/all', $data, true);

        $this->render($content);
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

            //load view template
            $this->load->view('user/template', $data);
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
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url() . 'user_content/index/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->pagination_per_page;
        $config['uri_segment'] = 3;
        return $config;
    }

    private function searchPaginationConfig($total_row, $per_page)
    {
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url() . 'user_content/search/';
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
            'content_status' => 'all',
            'start_date' => null,
            'finish_date' => null,
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

    private function _check_verified_member()
    {
        if ($this->session->userdata('user_verified') !== '1') {
            redirect('user_content/index');
        }
    }

    private function checkCompetitionMaximumSubmit($id_competition)
    {
        $allow_submit = true;
        $message = '';
        $id_user = $this->session->userdata('id_user');

        if (isset($id_user) && (! empty($id_user))){
            $competition = $this->mdl_competition->getById($id_competition);
            if ($competition->id_competition ?? false){
                $submission = $this->mdl_content->getContentByIdCompetitionCount($id_user, $competition->id_competition);
                if ($submission >= ($competition->max_content ?? '9999')){
                    $allow_submit = false;
                    $message = 'Maaf, Anda tidak dapat mendaftarkan konten ke kompetisi ini karena telah melebihi batas maksimum yang diperbolehkan (maximum ' . $competition->max_content . ' konten)';
                }
            }
            else{
                $allow_submit = false;
                $message = "Competition not found.";
            }
        }

        return compact('allow_submit', 'message');
    }
}
