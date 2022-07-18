<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Content2 extends CI_Controller
{
    const ASSET_FOLDER_TYPES = [
        'read-sponsored' => 'sponsored-content',
        'hypeshop' => 'shoppable',
        'hypephoto' => 'photo',
    ];
    const MODELS = [
        'read-sponsored' => 'mdl_sponsoredcontent',
        'poll' => 'mdl_poll',
        'quiz' => 'mdl_quiz',
        'hypeshop' => 'mdl_shoppable',
        'hypephoto' => 'mdl_photo',
    ];

    private $model;
    private $type;

    public $js = [];
    public $css = [];
    public $per_page_comment = 10;
    public $pagination_per_page = 10;
    public $assets_url;
    var $category_index = 0;
    var $category_list = array();

    public function __construct()
    {
        parent::__construct();

        //load library..
        $this->load->library('frontend_lib');
        $this->load->library('ads_lib');

        //load model..
        $this->load->model('mdl_user');
        $this->load->model('mdl_content');
        $this->load->model('mdl_content2');
        $this->load->model('mdl_category');
        $this->load->model('mdl_competition');

        $this->load->helper('url');

        $this->type = $this->uri->segment(1);
        $this->model = $this->mdl_content2;

        $model_name = (isset(self::MODELS[$this->type]) ? self::MODELS[$this->type] : null);
        if (isset($model_name) && strlen(trim($model_name)) > 0) {
            $this->load->model($model_name);
            $this->model = $this->$model_name;
        }

        $this->assets_url = base_url() . 'assets/' . (self::ASSET_FOLDER_TYPES[$this->type] ?? $this->type);
    }

    public function index()
    {
        $this->load->library('recaptcha');

        $data = [
            'assets_url' => $this->assets_url,
            'contents_see_more_limit' => 12,
        ];

        if ($this->type === 'hypephoto') {
            $data['contents'] = $this->model->with_user_like($this->session->userdata('id_user'))->all_published(24, 0);
            $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/frontend/js/hypephoto.js"></script>';
        }
        else {
            $data['contents'] = $this->model->all_published(24, 0);
        }

        $content = $this->load->view("frontend/{$this->type}", $data, true);
        $this->_render($content, $data);
    }

    public function article($params, $title = '', $page_no = '1')
    {
        $this->load->library('recaptcha');

        $uri1 = $this->uri->segment(1);
        $full_param = $params;
        $params = explode('-', $params);
        $id_content = $params[0];
        //redirect jika masih akses pakai URL lama.
        if (!is_numeric($full_param)) {
            redirect(base_url() . $uri1 . '/' . $id_content . '/' . $title);
            // echo $uri1;
        }

        $id_user = $this->session->userdata('id_user');
        $id_admin = $this->session->userdata('id_admin');
        $is_preview = $this->input->get('is_preview');

        $content = $this->_get_content($id_content, $is_preview);
        $tags = $this->model->get_tags($id_content);

        $page = NULL;
        $page_no = intval($page_no);
        $max_page_no = 1;

        $total_comments = $this->model->count_all_comments($id_content);
        $comments = $this->model->all_comments($id_content, $this->per_page_comment);
        $liked = $id_user ? $this->model->has_like_from($id_content, $id_user) : FALSE;
        $reacted = $id_user ? $this->model->has_reaction_from($id_content, $id_user) : FALSE;
        $reactions = $this->model->count_all_reactions($id_content);
        $meta = [
            'title' => $content->meta_title ?: $content->title ?: NULL,
            'description' => $content->meta_desc ?: $content->short_desc ?: NULL,
            'picture' => $content->content_pic_thumb ?: NULL,
            'keyword' => $content->meta_keyword ?: NULL,
        ];

        $data = compact(
            'total_comments',
            'comments',
            'content',
            'tags',
            'is_preview',
            'liked',
            'max_page_no',
            'page',
            'page_no',
            'reacted',
            'reactions'
        );

        $data['assets_url'] = $this->assets_url;
        $data['ads'] = $this->ads_lib->getArticleAds();

        if (in_array($content->type, ['3', '4'])) {
            $data['user_vote'] = $id_user ? $this->model->get_vote_of_user($id_content, $id_user) : NULL;
            $data['questions'] = $this->model->get_questions_with_answers($id_content, $content->paginated === '1' ? $page_no : NULL);
            $data['max_page_no'] = $data['total_question'] = $this->model->count_all_questions($id_content);
        }
        elseif ($content->type === '5') {
            $data['user_answer'] = $id_user ? $this->model->get_answer_of_user($id_content, $id_user) : NULL;
            $data['total_correct_answer'] = $id_user ? array_sum(array_values($data['user_answer'])) : NULL;
            $data['questions'] = $this->model->get_questions_with_choices($id_content, $content->paginated === '1' ? $page_no : NULL);
            $data['max_page_no'] = $data['total_question'] = $this->model->count_all_questions($id_content);
        }
        elseif ($content->type === '6') {
            $data['items'] = $this->mdl_shoppable->get_items($id_content);
        }
        elseif ($content->type === '7') {
            $data['photos'] = $this->mdl_photo->get_photos($id_content);
            $meta['description'] = $data['photos'][0]->short_desc ?: $meta['description'];

            if (isset($data['photos'][0]->picture)) {
                $meta['picture'] = site_url("assets/photo/thumb/{$data['photos'][0]->picture_thumb}");
            }
        }

        if ($is_preview) {
            $titles = [
                '1' => 'Artikel',
                '2' => 'Artikel - sponsored',
                '3' => 'Polling',
                '4' => 'Polling',
                '5' => 'Quiz',
                '6' => 'Shoppable content',
            ];
            $edit_paths = [
                '1' => 'content',
                '2' => 'sponsored',
                '3' => 'polling',
                '4' => 'polling',
                '5' => 'quiz',
                '6' => 'shoppable',
            ];

            $role_path = $content->type == 2 || $this->session->is_admin_dashboard ? 'admin' : 'user';

            $data['preview_title'] = "Preview {$titles[$content->type]}.";
            $message = $this->session->flashdata('message');
            // $data['preview_message'] = $this->session->flashdata('message') ?? '';
            $data['preview_message'] = isset($message) ? $message : '';
            $data['preview_edit_link'] = base_url() . "{$role_path}_{$edit_paths[$content->type]}/edit/{$content->id_content}";
        }

        $this->_render($this->load->view('frontend/content', $data, TRUE), $meta);

        if (!$is_preview) {
            $this->articleReaded($content);
        }
    }

    public function view_photo($id_content)
    {
        $this->input->is_ajax_request() ?: redirect(base_url());

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        $this->load->model('mdl_photo');

        $content = $this->mdl_photo->find_published($id_content);

        if ($content) {
            $this->articleReaded($content);

            $data['status'] = 'success';
        }
        else {
            $data['status'] = 'failed';
            $data['message'] = "Gagal. Photo tidak tersedia.";
        }

        echo json_encode($data);
    }

    public function vote($id_content)
    {
        $this->input->is_ajax_request() ?: redirect(base_url());

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        if ($this->session->userdata('user_logged_in')) {
            $this->load->model('mdl_poll');

            $id_user = $this->session->userdata('id_user');
            $content = $this->mdl_poll->find_published($id_content);

            if ($content) {
                $user_vote = $this->mdl_poll->get_vote_of_user($id_content, $id_user);

                if (empty($user_vote)) {
                    $questions = $this->mdl_poll->get_questions($id_content);

                    if ($content->paginated === '1') {
                        $this->form_validation->set_data($this->session->userdata());
                    }

                    foreach ($questions as $index => $question) {
                        $this->form_validation->set_rules(
                            "answers[{$question->id}]", 'Pertanyaan ' . ($index + 1), 'htmlentities|strip_tags|trim|xss_clean|required|integer'
                        );
                    }
                    $this->form_validation->set_message('required', '{field}');

                    if ($this->form_validation->run()) {
                        $this->mdl_poll->add_votes(
                            $id_user,
                            $content->paginated === '1' ? $this->session->userdata('answers') : $this->input->post('answers')
                        );

                        if ($content->paginated === '1') {
                            $this->session->unset_userdata('answers');
                        }

                        $data['status'] = 'success';
                        $data['message'] = 'Vote berhasil dilakukan';
                        $data['votes_count'] = $this->mdl_poll->count_all_answer_votes($id_content);

                        foreach ($data['votes_count'] as $votes) {
                            foreach ($votes as $vote) {
                                $vote->percentage = rtrim(rtrim(number_format($vote->percentage, 1, ',', '.'), '0'), ',');
                            }
                        }
                    }
                    else {
                        $data['status'] = 'failed';
                        $data['message'] = 'Anda belum memilih vote untuk semua pertanyaan: ' . implode(', ', $this->form_validation->error_array());
                    }
                }
                else {
                    $data['status'] = 'failed';
                    $data['message'] = "Vote telah dilakukan.";
                }
            }
            else {
                $data['status'] = 'failed';
                $data['message'] = "Gagal memberikan vote. Polling tidak tersedia.";
            }
        }
        else {
            $data['status'] = 'failed';
            $data['message'] = 'Anda harus login terlebih dahulu.';
        }

        echo json_encode($data);
    }

    public function answer($id_content)
    {
        $this->input->is_ajax_request() ?: redirect(base_url());

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        if ($this->session->userdata('user_logged_in')) {
            $this->load->model('mdl_quiz');

            $id_user = $this->session->userdata('id_user');
            $content = $this->mdl_quiz->find_published($id_content);

            if ($content) {
                $user_answer = $this->mdl_quiz->get_answer_of_user($id_content, $id_user);

                if (empty($user_answer)) {
                    $questions = $this->mdl_quiz->get_questions($id_content);

                    if ($content->paginated === '1') {
                        $this->form_validation->set_data($this->session->userdata());
                    }

                    foreach ($questions as $index => $question) {
                        $this->form_validation->set_rules(
                            "answers[{$question->id}]", 'Pertanyaan ' . ($index + 1), 'htmlentities|strip_tags|trim|xss_clean|required|integer'
                        );
                    }
                    $this->form_validation->set_message('required', '{field}');

                    if ($this->form_validation->run()) {
                        $this->mdl_quiz->add_answers(
                            $id_user,
                            $content->paginated === '1' ? $this->session->userdata('answers') : $this->input->post('answers')
                        );

                        if ($content->paginated === '1') {
                            $this->session->unset_userdata('answers');
                        }

                        $data['status'] = 'success';
                        $data['message'] = 'Quiz berhasil dijawab.';
                        $data['correct_answers'] = $this->mdl_quiz->get_true_answers($id_content);
                        $data['total_correct_answers'] = $this->mdl_quiz->count_correct_answer_of_user($id_content, $id_user);
                    }
                    else {
                        $data['status'] = 'failed';
                        $data['message'] = 'Anda belum memilih jawaban untuk semua pertanyaan: ' . implode(', ', $this->form_validation->error_array());
                    }
                }
                else {
                    $data['status'] = 'failed';
                    $data['message'] = "Quiz sudah dijawab.";
                }
            }
            else {
                $data['status'] = 'failed';
                $data['message'] = "Gagal memberikan jawaban. Quiz tidak tersedia.";
            }
        }
        else {
            $data['status'] = 'failed';
            $data['message'] = 'Anda harus login terlebih dahulu.';
        }

        echo json_encode($data);
    }

    public function add_vote_session($id_content)
    {
        $this->input->is_ajax_request() ?: redirect(base_url());

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        if ($this->session->userdata('user_logged_in')) {
            $this->load->model('mdl_poll');

            $id_user = $this->session->userdata('id_user');

            if ($this->mdl_poll->count_published_paginated($id_content) > 0) {
                $user_vote = $this->mdl_poll->get_vote_of_user($id_content, $id_user);

                if (empty($user_vote)) {
                    $data['status'] = 'success';
                    $data['message'] = 'Penambahan vote berhasil dilakukan';
                    $data['answers'] = array_replace($this->session->userdata('answers') ?: [], $this->input->post('answers'));
                    $this->session->set_userdata('answers', $data['answers']);
                }
                else {
                    $data['status'] = 'failed';
                    $data['message'] = "Vote telah dilakukan.";
                }
            }
            else {
                $data['status'] = 'failed';
                $data['message'] = "Gagal memberikan vote. Polling tidak tersedia.";
            }
        }
        else {
            $data['status'] = 'failed';
            $data['message'] = 'Anda harus login terlebih dahulu.';
        }

        echo json_encode($data);
    }

    public function add_answer_session($id_content)
    {
        $this->input->is_ajax_request() ?: redirect(base_url());

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        if ($this->session->userdata('user_logged_in')) {
            $this->load->model('mdl_quiz');

            $id_user = $this->session->userdata('id_user');

            if ($this->mdl_quiz->count_published_paginated($id_content) > 0) {
                $user_vote = $this->mdl_quiz->get_answer_of_user($id_content, $id_user);

                if (empty($user_vote)) {
                    $data['status'] = 'success';
                    $data['message'] = 'Penambahan jawaban berhasil dilakukan';
                    $data['answers'] = array_replace($this->session->userdata('answers') ?: [], $this->input->post('answers'));
                    $this->session->set_userdata('answers', $data['answers']);
                }
                else {
                    $data['status'] = 'failed';
                    $data['message'] = 'Quiz telah dijawab.';
                }
            }
            else {
                $data['status'] = 'failed';
                $data['message'] = "Gagal memberikan jawaban. Quiz tidak tersedia.";
            }
        }
        else {
            $data['status'] = 'failed';
            $data['message'] = 'Anda harus login terlebih dahulu.';
        }

        echo json_encode($data);
    }

    public function reset_quiz_answer($id_content)
    {
        $this->load->model('mdl_quiz');

        $content = $this->mdl_quiz->find_published($id_content);

        if ($content) {
            if ($this->session->userdata('user_logged_in')) {
                $id_user = $this->session->userdata('id_user');

                $this->mdl_quiz->delete_answer_by_user($id_user);
            }

            $user_slug = $content->id_user ? '-' . strtolower(url_title($content->user_name)) : '';
            redirect(base_url() . "quiz/{$id_content}{$user_slug}/" . strtolower(url_title($content->title)));
        }

        redirect(base_url());
    }

    public function reactContent()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        if ($this->session->userdata('user_logged_in') !== true) {
            $data['status'] = 'nologin';
        }
        else {
            $this->form_validation->set_rules('id_content', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
            $this->form_validation->set_rules('id_reaction', '', 'htmlentities|strip_tags|trim|xss_clean|integer');

            if ($this->form_validation->run() == false) {
                $data['status'] = 'failed';
                $data['message'] = "Gagal memberikan reaksi. Mohon coba kembali.";
            }
            else {
                $id_user = $this->session->userdata('id_user');
                $id_content = $this->input->post('id_content');
                $id_reaction = $this->input->post('id_reaction');

                if ($this->model->count_published($id_content) > 0) {
                    $reacted = $this->model->has_reaction_from($id_content, $id_user);

                    if ($reacted) {
                        // sudah pernah like sebelumnya..
                        $data['status'] = 'already_reacted';
                    }
                    else {
                        $this->model->add_reaction($id_content, $id_user, $id_reaction);

                        // insert reaction baru..
                        $data['status'] = 'success';
                        $data['reaction_result'] = $this->model->count_all_reactions($id_content);

                        foreach ($data['reaction_result'] as $reaction) {
                            $reaction->percentage = rtrim(rtrim(number_format($reaction->percentage, 1, ',', '.'), '0'), ',');
                        }
                    }
                }
                else {
                    $data['status'] = 'failed';
                    $data['message'] = "Gagal memberikan reaksi. Mohon coba kembali.";
                }
            }
        }
        echo json_encode($data);
    }

    public function likeContent()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        if ($this->session->userdata('user_logged_in') !== true) {
            $data['status'] = 'nologin';
        }
        else {
            $this->form_validation->set_rules('id_content', '', 'htmlentities|strip_tags|trim|xss_clean|integer');

            if ($this->form_validation->run() == false) {
                $data['status'] = 'failed';
                $data['message'] = "Parameter Salah";
            }
            else {
                $id_user = $this->session->userdata('id_user');
                $id_content = $this->input->post('id_content');

                //checck id user dan id content..
                if ($this->model->count_published($id_content) > 0) {
                    $liked = $this->model->has_like_from($id_content, $id_user);

                    if ($liked) {
                        $this->model->substract_like($id_content, $id_user);

                        // unlike..
                        $data['action'] = 'unlike';
                        $data['status'] = 'success';
                        $data['message'] = 'Like anda telah dibatalkan';
                        $data['like_count'] = number_format($this->model->count_all_likes($id_content), 0, ',', '.');
                    }
                    else {
                        $this->model->add_like($id_content, $id_user);

                        // like
                        $data['action'] = 'like';
                        $data['status'] = 'success';
                        $data['message'] = 'Telah menyukai konten ini';
                        $data['like_count'] = number_format($this->model->count_all_likes($id_content), 0, ',', '.');
                    }
                }
                else {
                    $data['status'] = 'failed';
                    $data['message'] = "Gagal menyukai artikel ini. Mohon coba kembali.";
                }
            }
        }
        echo json_encode($data);
    }

    public function unlikeContent()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        if ($this->session->userdata('user_logged_in') !== true) {
            $data['status'] = 'nologin';
        }
        else {
            $this->form_validation->set_rules('id_content', '', 'htmlentities|strip_tags|trim|xss_clean|integer');

            if ($this->form_validation->run() == false) {
                $data['status'] = 'failed';
                $data['message'] = "Parameter Salah";
            }
            else {
                $id_user = $this->session->userdata('id_user');
                $id_content = $this->input->post('id_content');

                //checck id user dan id content..
                if ($this->model->count_published($id_content) > 0) {
                    $liked = $this->model->has_like_from($id_content, $id_user);

                    if ($liked) {
                        $this->model->substract_like($id_content, $id_user);

                        // insert like baru..
                        $data['status'] = 'success';
                        $data['like_count'] = number_format($this->model->count_all_likes($id_content), 0, ',', '.');
                    }
                    else {
                        // belum pernah like..
                        $data['status'] = 'already_unliked';
                    }
                }
                else {
                    $data['status'] = 'failed';
                    $data['message'] = "Gagal membatalkan like. Mohon coba kembali.";
                }
            }
        }
        echo json_encode($data);
    }

    public function submitComment()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        if ($this->session->userdata('user_logged_in') !== true) {
            $data['status'] = 'nologin';
        }
        else {
            //cek captcha..
            $this->load->library('recaptcha');
            $captcha_answer = $this->input->post('g-recaptcha-response');
            $response = $this->recaptcha->verifyResponse($captcha_answer);
            // Processing captcha ...
            if (!$response['success']) {
                $data['status'] = 'failed';
                $data['message'] = $this->global_lib->generateMessage("Anda harus check recaptcha.", "danger");
            }
            else {
                $this->form_validation->set_rules('id_content', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
                $this->form_validation->set_rules('comment_content', '', 'htmlentities|strip_tags|trim|xss_clean|required');

                if ($this->form_validation->run() == false) {
                    $data['status'] = 'failed';
                    $data['message'] = $this->global_lib->generateMessage("Gagal menambahkan komentar. Isi form dengan lengkap", "danger");
                }
                else {
                    $id_content = $this->input->post('id_content');
                    $comment = $this->input->post('comment_content');

                    //jika tidak ada data, redirect ke index.
                    if ($this->model->count_published($id_content) < 1) {
                        $data['status'] = 'failed';
                        $data['message'] = $this->global_lib->generateMessage("Gagal menambahkan komentar", "danger");
                    }
                    else {
                        $this->model->add_comment($id_content, $this->session->userdata('id_user'), $comment);
                        $data['status'] = 'success';
                    }
                }
            }
        }

        echo json_encode($data);
    }

    public function loadComment()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data = [
            'status' => '',
            'message' => '',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_token_hash' => $this->security->get_csrf_hash(),
        ];

        $this->form_validation->set_rules('id_content', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('comment_offset', '', 'htmlentities|strip_tags|trim|xss_clean|required');

        if ($this->form_validation->run() == false) {
            $data['status'] = 'failed';
        }
        else {
            $id_content = $this->input->post('id_content');
            $comment_offset = $this->input->post('comment_offset');

            if ($this->model->count_published($id_content) < 1) {
                $data['status'] = 'failed';
            }
            else {
                $data['status'] = 'success';
                $data['next_offset'] = $comment_offset + $this->per_page_comment;
                $data['comments'] = $this->model->all_comments($id_content, $this->per_page_comment, $comment_offset);
                $data['comments_number'] = count($data['comments']);

                foreach ($data['comments'] as $x => $comment) {
                    $data['comments'][$x]->picture_src = $this->frontend_lib->getUserPictureURL($comment->picture, $comment->picture_from);
                    $data['comments'][$x]->date_str = date('d M Y - H:i', strtotime($comment->comment_date));
                    $data['comments'][$x]->content_str = nl2br($comment->comment);
                    $data['comments'][$x]->name_url = strtolower(url_title($comment->name));
                }

                if ($comment_offset + $data['comments_number'] < $this->model->count_all_comments($id_content)) {
                    $data['show_loadmore'] = 1;
                }
                else {
                    $data['show_loadmore'] = 0;
                }
            }
        }
        echo json_encode($data);
    }

    private function _render($page, $meta = [])
    {
        //load page view
        $data['content'] = $page;
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
        $data['categories_filter'] = $this->getAllCategory();

        //ambil ads footer..
        $data['ads'] = $this->ads_lib->getFooterAds();

        $data['is_competition_exist'] = $this->mdl_competition->getActiveCompetitionCount();

        //load view template
        $this->load->view('frontend/template', $data);
    }

    private function _get_content($id, $is_preview)
    {
        $id_admin = $this->session->userdata('id_admin');
        $id_user = $this->session->userdata('id_user');
        $content = $is_preview ? $this->model->find_with_counts($id) : $this->model->find_published_with_counts($id);

        if (is_null($content)) {
            redirect(base_url());
        }
        elseif ($is_preview && (is_null($id_admin) && $id_user !== $content->id_user)) {
            redirect(base_url());
        }

        return $content;
    }

    private function articleReaded($article_data)
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P3D'));
        $date_limit = $date->format('Y-m-d');

        $update_data = array(
            'last_read' => date('Y-m-d H:i:s'),
            'read_count' => ($article_data->read_count + 1)
        );
        $this->mdl_content->updateContent($update_data, $article_data->id_content);

        //hapus history read yg kurang dari tgl limit
        // $this->mdl_content->deleteReadHistoryByDateLimit($date_limit);
        //insert/update read counter by date
        $now = date("Y-m-d");
        $content_read = $this->mdl_content->getReadHistoryByIdAndDate($article_data->id_content, $now);
        if (isset($content_read->id_content_read) && is_numeric($content_read[0]->id_content_read)) {
            //update
            $this->mdl_content->updateReadHistory([
                'read' => ($content_read[0]->read + 1)
            ], $article_data[0]->id_content, $now);
        }
        else {
            //insert
            $this->mdl_content->insertReadHistory([
                'id_content' => $article_data->id_content,
                'read_date' => $now,
                'read' => 1
            ]);
        }

        //jika sedang login, input history read user (untuk fitur 'belum dibaca nih')..
        if ($this->session->userdata('user_logged_in') === true) {
            $id_user = $this->session->userdata('id_user');
            if (isset($id_user) && $id_user > 0) {
                //cek apakah sudah pernah dibaca atau belum..
                $has_readed = $this->mdl_content->checkUserContentRead($id_user, $article_data->id_content);
                //jika belum pernah, insert record..
                if (!$has_readed) {
                    $insert_data = array(
                        'id_user' => $id_user,
                        'id_category' => $article_data->id_category,
                        'id_content' => $article_data->id_content
                    );
                    $this->mdl_content->insertUserRead($insert_data);
                }
            }
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
}
