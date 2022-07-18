<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Polling extends CI_Controller
{
    private $template;
    private $base_url;
    private $is_admin;
    private $module_name = 'admin_polling';
    private $data = [];
    private $pic_thumb_width = 600;
    private $pic_thumb_height = 400;
    private $pic_square_width = 400;
    private $pic_square_height = 400;

    public $pic_width = 1900;
    public $pic_height = 998;
    public $css = [];
    public $js = [];

    public function __construct()
    {
        parent::__construct();
        $this->base_url = base_url() . $this->uri->segment(1);
        $this->is_admin = $this->session->userdata('admin_logged_in') && $this->uri->segment(1) === 'admin_polling';
        $this->data = [
            'base_url' => $this->base_url,
            'is_admin' => $this->is_admin,
        ];

        $this->session->set_userdata('is_admin_dashboard', $this->is_admin);

        $this->load->model('mdl_user');
        $this->load->model('mdl_content2');
        $this->load->model('mdl_poll');
        $this->load->model('mdl_competition');

        if ($this->is_admin) {
            $this->load->model('mdl_content_editor');
        }

        $this->_authorize();

        if ($this->uri->segment(1) === 'user_polling') {
            $this->mdl_poll->filter_by_user($this->session->userdata('id_user'));
        }
    }

    public function index()
    {
        if ($this->is_admin) {
            $pagination_config = $this->global_lib->paginationConfigAdmin();
        }
        else {
            $pagination_config = $this->global_lib->paginationConfig();
        }
        $pagination_config['base_url'] = "{$this->base_url}/index/";
        $pagination_config['total_rows'] = $this->mdl_poll->without_draft($this->is_admin)->count();
        $pagination_config['per_page'] = 20;
        $pagination_config['uri_segment'] = 3;

        $this->pagination->initialize($pagination_config);

        $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
        $this->data['polls'] = $this->mdl_poll
            ->order_by_admin_editor($this->session->userdata('id_admin'))
            ->without_draft($this->is_admin)
            ->with_admin_editor()
            ->all($pagination_config['per_page'], $this->data['offset']);

        $this->_render($this->load->view('shared/polling/all', $this->data, TRUE));
    }

    public function view($id_content)
    {
        $this->data['content'] = $this->mdl_poll->find($id_content) ?: redirect($this->uri->segment(1));
        $this->data['tags'] = $this->mdl_poll->get_tags($id_content);
        $this->data['questions'] = $this->mdl_poll->get_questions_with_answers($id_content);

        $this->_render($this->load->view('shared/polling/view', $this->data, TRUE));
    }

    public function add()
    {
        $this->data['heading_text'] = 'Tambah';
        $this->data['submit_url'] = "{$this->base_url}/save_add";
        $this->data['form_value'] = $this->session->flashdata('form_value') ?: [
            'paginated' => '0',
            'type' => '3',
            'title' => '',
            'short_desc' => '',
            'meta_title' => '',
            'meta_desc' => '',
            'meta_keyword' => '',
            'pic_caption' => '',
            'content' => '',
            'content_status' => '-1',
            'featured_on_homepage' => '0',
            'trending' => '0',
            'recommended' => '0',
            'deletable' => '1',
            'publish_date' => NULL,
            'publish_time' => NULL,
        ];

        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>';
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';

        $this->_render($this->load->view('shared/polling/form', $this->data, TRUE));
    }

    public function save_add()
    {
        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_set_content_validation_rules($this->input->post('content_status'));

        if ($this->form_validation->run()) {
            $picture = $this->_upload_content_picture($this->input->post('content_status') !== '-1', 'add');
            $content_data = $this->_get_input_content_data($picture);
            $tags = !empty($this->input->post('tags')) ? explode(',', $this->input->post('tags')) : [];
            $id_content = $this->mdl_poll->insert_content($content_data, $tags);

            if ($this->is_admin) {
                $this->mdl_content_editor->insert_or_update($id_content, $this->session->userdata('id_admin'));
            }

            if (!$this->is_admin) {
                $this->load->library('point_lib');

                $point_config = [
                    'trigger_type' => 'add_polling',
                    'id_user' => $this->session->userdata('id_user'),
                    'desc' => $content_data['title'],
                ];

                if ($content_data['content_status'] === '1') {
                    $this->point_lib->addPoint($point_config);
                }
            }

            $message = 'Polling berhasil ditambahkan.';
            if (is_null($this->input->post('preview'))) {
                $this->session->set_flashdata('form_value', NULL);
                $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

                redirect("{$this->uri->segment(1)}/edit/{$id_content}");
            }
            else {
                $this->session->set_flashdata('message', $message);
                $name = $this->is_admin ? '' : '-' . strtolower(url_title($this->mdl_user->getNameByID($content_data['id_user'])));

                redirect("poll/{$id_content}{$name}/" . strtolower(url_title($content_data['title'])) . '?is_preview=1');
            }
        }
        else {
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );

            redirect("{$this->uri->segment(1)}/add");
        }
    }

    public function edit($id_content)
    {
        $this->data['content'] = $this->mdl_poll->find($id_content) ?: redirect($this->uri->segment(1));

        if ($this->data['content']->edit_id_admin && $this->data['content']->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $this->data['questions'] = $this->mdl_poll->get_questions($id_content);
        $this->data['heading_text'] = 'Edit';
        $this->data['submit_url'] = "{$this->base_url}/save_edit/{$id_content}";
        $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array)$this->data['content'];
        $this->data['form_value']['publish_time'] = $this->data['content']->publish_date ? date('H:i', strtotime($this->data['content']->publish_date)) : NULL;
        $this->data['locked_content_id'] = $this->mdl_poll->get_locked_content_id($this->session->userdata('id_admin'));

        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>';
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/moment/moment.min.js"></script>';

        $this->_render($this->load->view('shared/polling/form', $this->data, TRUE));
    }

    public function save_edit($id_content)
    {
        $content = $this->mdl_poll->find($id_content) ?: redirect($this->uri->segment(1));

        if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $status = $this->input->post('content_status');
        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_set_content_validation_rules($status);

        if ($this->form_validation->run()) {
            $picture = $this->_upload_content_picture(
                $status !== '-1' && empty($content->content_pic),
                "edit/{$id_content}"
            );

            $content_data = $this->_get_input_content_data($picture);
            $content_data['content_pic'] = $content_data['content_pic'] ?: $content->content_pic;
            $content_data['content_pic_thumb'] = $content_data['content_pic_thumb'] ?: $content->content_pic_thumb;
            $content_data['content_pic_square'] = $content_data['content_pic_square'] ?: $content->content_pic_square;
            $content_data['edit_id_admin'] = in_array($status, ['1', '2']) ? null : $content->edit_id_admin;

            $tags = !empty($this->input->post('tags')) ? explode(',', $this->input->post('tags')) : [];
            $this->mdl_poll->update_content($id_content, $content_data, $tags);

            if ($this->is_admin) {
                $this->mdl_content_editor->insert_or_update($id_content, $this->session->userdata('id_admin'));
            }

            if ($content_data['content_pic'] !== $content->content_pic) {
                @unlink("assets/poll/{$content->content_pic}");
                @unlink("assets/poll/thumb/{$content->content_pic_thumb}");
                @unlink("assets/poll/thumb/{$content->content_pic_square}");
            }

            if ($content->content_status !== $content_data['content_status']) {
                $this->load->library('point_lib');
                $point_config = ['trigger_type' => 'add_polling', 'id_user' => $content->id_user, 'desc' => $content->title];

                if ($content->id_user) {
                    if ($content->content_status !== '1' && $content_data['content_status'] === '1') {
                        $this->point_lib->addPoint($point_config);
                    }
                    elseif ($content->content_status === '1' && $content_data['content_status'] !== '1') {
                        $this->point_lib->substractPoint($point_config);
                    }
                }
            }

            $message = 'Polling berhasil diubah.';
            if (is_null($this->input->post('preview'))) {
                $this->session->set_flashdata('form_value', NULL);
                $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

                redirect($this->uri->segment(1));
            }
            else {
                $this->session->set_flashdata('message', $message);
                $name = $this->is_admin && !$content->id_user ? '' : '-' . strtolower(url_title($this->mdl_user->getNameByID($content->id_user)));

                redirect("poll/{$id_content}{$name}/" . strtolower(url_title($content_data['title'])) . '?is_preview=1');
            }
        }
        else {
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );
        }

        redirect("{$this->uri->segment(1)}/edit/{$id_content}");
    }

    public function delete($id_content)
    {
        $content = $this->mdl_poll->find($id_content);

        if (!is_null($content)) {
            if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
                redirect($this->uri->segment(1));
            }

            if ($content->deletable !== '1' && $this->session->userdata('admin_level') !== '1') {
                $this->session->set_flashdata(
                    'message',
                    $this->global_lib->generateMessage("You can't delete this content. Contact your super adminintrator.", 'danger')
                );
                return redirect($this->uri->segment(1));
            }

            $questions = $this->mdl_poll->get_questions_with_answers($id_content);

            $this->mdl_poll->delete($id_content);

            @unlink("assets/poll/{$content->content_pic}");
            @unlink("assets/poll/thumb/{$content->content_pic_thumb}");
            @unlink("assets/poll/thumb/{$content->content_pic_square}");

            foreach ($questions as $question) {
                if (!empty($question->picture)) {
                    @unlink("assets/poll/questions/{$question->picture}");
                }

                foreach ($question->answers as $answer) {
                    if (!empty($answer->picture)) {
                        @unlink("assets/poll/answers/{$answer->picture}");
                    }
                }
            }

            if ($content->id_user) {
                $this->load->library('point_lib');

                $point_config = ['trigger_type' => 'add_polling', 'id_user' => $content->id_user, 'desc' => $content->title];

                if ($content->content_status === '1') {
                    $this->point_lib->substractPoint($point_config);
                }
            }

            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage('Polling berhasil dihapus.', 'info')
            );
        }

        redirect($this->uri->segment(1));
    }

    public function delete_picture($id_content)
    {
        $content = $this->mdl_poll->find($id_content);

        if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        if (!is_null($content) && $content->content_status !== '1') {
            $this->mdl_poll->remove_picture($id_content);

            @unlink("assets/poll/{$content->content_pic}");
            @unlink("assets/poll/thumb/{$content->content_pic_thumb}");
            @unlink("assets/poll/thumb/{$content->content_pic_square}");

            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage('Gambar konten Polling berhasil dihapus', 'info')
            );
        }

        redirect("{$this->uri->segment(1)}/edit/{$id_content}");
    }

    public function add_question($id_content)
    {
        $this->data['content'] = $this->mdl_poll->find($id_content) ?: redirect($this->uri->segment(1));

        if ($this->data['content']->edit_id_admin && $this->data['content']->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $this->data['max_order_no'] = $this->mdl_poll->get_max_question_order_no($id_content);

        $this->data['heading_text'] = 'Tambah';
        $this->data['submit_url'] = "{$this->base_url}/save_add_question/{$id_content}";
        $this->data['form_value'] = $this->session->flashdata('form_value') ?: [
            'type' => '1',
            'order_no' => $this->data['max_order_no'],
            'question' => '',
        ];

        $this->_render($this->load->view('shared/polling/form_question.php', $this->data, TRUE));
    }

    public function save_add_question($id_content)
    {
        $content = $this->mdl_poll->find($id_content) ?: redirect($this->uri->segment(1));

        if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $max_order_no = $this->mdl_poll->get_max_question_order_no($id_content);

        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_set_question_validation_rules($max_order_no);

        if ($this->form_validation->run()) {
            $picture = $this->_upload_question_picture("add_question/{$id_content}");
            $data = [
                'type' => $this->input->post('type'),
                'order_no' => $this->input->post('order_no'),
                'question' => $this->input->post('question'),
                'picture' => $picture,
            ];

            $this->mdl_poll->add_question($id_content, $data);

            $this->session->set_flashdata('form_value', NULL);
            $this->session->set_flashdata(
                'question_message',
                $this->global_lib->generateMessage('Pertanyaan berhasil ditambahkan.', 'info')
            );

            redirect("{$this->uri->segment(1)}/edit/{$id_content}#questions");
        }
        else {
            $this->session->set_flashdata(
                'question_message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );

            redirect("{$this->uri->segment(1)}/add_question/{$id_content}");
        }
    }

    public function edit_question($id)
    {
        $this->data['question'] = $this->mdl_poll->find_question($id) ?: redirect($this->uri->segment(1));
        $this->data['content'] = $this->mdl_poll->find($this->data['question']->id_content);

        if ($this->data['content']->edit_id_admin && $this->data['content']->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $this->data['max_order_no'] = $this->mdl_poll->get_max_question_order_no($this->data['question']->id_content) - 1;

        $this->data['heading_text'] = 'Edit';
        $this->data['submit_url'] = "{$this->base_url}/save_edit_question/{$id}";
        $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array)$this->data['question'];

        $this->_render($this->load->view('shared/polling/form_question.php', $this->data, TRUE));
    }

    public function save_edit_question($id)
    {
        $question = $this->mdl_poll->find_question($id) ?: redirect($this->uri->segment(1));
        $content = $this->mdl_poll->find($question->id_content) ?: redirect($this->uri->segment(1));

        if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $max_order_no = $this->mdl_poll->get_max_question_order_no($question->id_content) - 1;

        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_set_question_validation_rules($max_order_no);

        if ($this->form_validation->run()) {
            $picture = $this->_upload_question_picture("edit_question/{$id}");
            $data = [
                'type' => $this->input->post('type'),
                'order_no' => $this->input->post('order_no'),
                'question' => $this->input->post('question'),
                'picture' => $picture ?: $question->picture,
            ];

            $this->mdl_poll->update_question($id, $data);

            if ($picture) {
                @unlink("assets/poll/questions/{$question->picture}");
            }

            $this->session->set_flashdata('form_value', NULL);
            $this->session->set_flashdata(
                'question_message',
                $this->global_lib->generateMessage('Pertanyaan berhasil diubah.', 'info')
            );

            redirect("{$this->uri->segment(1)}/edit/{$question->id_content}#questions");
        }
        else {
            $this->session->set_flashdata(
                'question_message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );

            redirect("{$this->uri->segment(1)}/edit_question/{$id}");
        }
    }

    public function delete_question($id)
    {
        $question = $this->mdl_poll->find_question($id);

        if (!is_null($question)) {
            $content = $this->mdl_poll->find($question->id_content) ?: redirect($this->uri->segment(1));

            if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
                redirect($this->uri->segment(1));
            }

            $this->mdl_poll->delete_question($id);

            if (!empty($question->picture)) {
                @unlink("assets/poll/questions/{$question->picture}");
            }

            foreach ($question->answers as $answer) {
                if (!empty($answer->picture)) {
                    @unlink("assets/poll/answers/{$answer->picture}");
                }
            }

            $this->session->set_flashdata(
                'question_message',
                $this->global_lib->generateMessage('Pertanyaan berhasil dihapus.', 'info')
            );
        }

        redirect("{$this->uri->segment(1)}/edit/{$question->id_content}#questions");
    }

    public function delete_question_picture($id)
    {
        $question = $this->mdl_poll->find_question($id);

        if ($question && $question->picture) {
            $content = $this->mdl_poll->find($question->id_content) ?: redirect($this->uri->segment(1));

            if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
                redirect($this->uri->segment(1));
            }

            $this->mdl_poll->update_question($id, ['picture' => NULL]);

            if (!empty($question->picture)) {
                @unlink("assets/poll/questions/{$question->picture}");
            }

            $this->session->set_flashdata(
                'question_message',
                $this->global_lib->generateMessage('Gambar Pertanyaan berhasil dihapus.', 'info')
            );
        }

        redirect("{$this->uri->segment(1)}/edit/{$question->id_content}#questions");
    }

    public function add_answer($id)
    {
        $this->data['question'] = $this->mdl_poll->find_question($id) ?: redirect($this->uri->segment(1));

        $content = $this->mdl_poll->find($this->data['question']->id_content) ?: redirect($this->uri->segment(1));

        if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $this->data['max_order_no'] = $this->mdl_poll->get_max_answer_order_no($id);

        $this->data['heading_text'] = 'Tambah';
        $this->data['submit_url'] = "{$this->base_url}/save_add_answer/{$id}";
        $this->data['form_value'] = $this->session->flashdata('form_value') ?: [
            'order_no' => $this->data['max_order_no'],
            'answer' => '',
        ];

        $this->_render($this->load->view('shared/polling/form_answer.php', $this->data, TRUE));
    }

    public function save_add_answer($id)
    {
        $question = $this->mdl_poll->find_question($id) ?: redirect($this->uri->segment(1));
        $content = $this->mdl_poll->find($question->id_content) ?: redirect($this->uri->segment(1));

        if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $max_order_no = $this->mdl_poll->get_max_answer_order_no($id);

        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_set_answer_validation_rules($max_order_no);

        if ($this->form_validation->run()) {
            $picture = $this->_upload_answer_picture("add_answer/{$id}");
            $data = [
                'order_no' => $this->input->post('order_no'),
                'answer' => $this->input->post('answer'),
                'picture' => $picture,
            ];

            $this->mdl_poll->add_answer($id, $data);

            $this->session->set_flashdata('form_value', NULL);
            $this->session->set_flashdata(
                'answer_message',
                $this->global_lib->generateMessage('Jawaban berhasil ditambahkan.', 'info')
            );

            redirect("{$this->uri->segment(1)}/edit_question/{$id}#answers");
        }
        else {
            $this->session->set_flashdata(
                'answer_message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );

            redirect("{$this->uri->segment(1)}/add_answer/{$id}");
        }
    }

    public function edit_answer($id)
    {
        $this->data['answer'] = $this->mdl_poll->find_answer($id) ?: redirect($this->uri->segment(1));
        $this->data['question'] = $this->mdl_poll->find_question($this->data['answer']->id_content_poll, FALSE);
        $content = $this->mdl_poll->find($this->data['question']->id_content) ?: redirect($this->uri->segment(1));

        if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $this->data['max_order_no'] = $this->mdl_poll->get_max_answer_order_no($this->data['question']->id) - 1;

        $this->data['heading_text'] = 'Edit';
        $this->data['submit_url'] = "{$this->base_url}/save_edit_answer/{$id}";
        $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array)$this->data['answer'];

        $this->_render($this->load->view('shared/polling/form_answer.php', $this->data, TRUE));
    }

    public function save_edit_answer($id)
    {
        $answer = $this->mdl_poll->find_answer($id) ?: redirect($this->uri->segment(1));
        $question = $this->mdl_poll->find_question($answer->id_content_poll) ?: redirect($this->uri->segment(1));
        $content = $this->mdl_poll->find($question->id_content) ?: redirect($this->uri->segment(1));

        if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect($this->uri->segment(1));
        }

        $max_order_no = $this->mdl_poll->get_max_answer_order_no($answer->id_content_poll) - 1;

        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_set_answer_validation_rules($max_order_no);

        if ($this->form_validation->run()) {
            $picture = $this->_upload_answer_picture("edit_answer/{$id}");
            $data = [
                'order_no' => $this->input->post('order_no'),
                'answer' => $this->input->post('answer'),
                'picture' => $picture ?: $answer->picture,
            ];

            $this->mdl_poll->update_answer($id, $data);

            if ($picture) {
                @unlink("assets/poll/answers/{$answer->picture}");
            }

            $this->session->set_flashdata('form_value', NULL);
            $this->session->set_flashdata(
                'answer_message',
                $this->global_lib->generateMessage('Jawaban berhasil diubah.', 'info')
            );

            redirect("{$this->uri->segment(1)}/edit_question/{$answer->id_content_poll}#answers");
        }
        else {
            $this->session->set_flashdata(
                'answer_message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );

            redirect("{$this->uri->segment(1)}/edit_answer/{$id}");
        }
    }

    public function delete_answer($id)
    {
        $answer = $this->mdl_poll->find_answer($id);

        if (!is_null($answer)) {
            $question = $this->mdl_poll->find_question($answer->id_content_poll) ?: redirect($this->uri->segment(1));
            $content = $this->mdl_poll->find($question->id_content) ?: redirect($this->uri->segment(1));

            if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
                redirect($this->uri->segment(1));
            }

            $this->mdl_poll->delete_answer($id);

            if (!empty($answer->picture)) {
                @unlink("assets/poll/answers/{$answer->picture}");
            }

            $this->session->set_flashdata(
                'answer_message',
                $this->global_lib->generateMessage('Jawaban berhasil dihapus.', 'info')
            );
        }

        redirect("{$this->uri->segment(1)}/edit_question/{$answer->id_content_poll}#answers");
    }

    public function delete_answer_picture($id)
    {
        $answer = $this->mdl_poll->find_answer($id);

        if ($answer && $answer->picture) {
            $question = $this->mdl_poll->find_question($answer->id_content_poll) ?: redirect($this->uri->segment(1));
            $content = $this->mdl_poll->find($question->id_content) ?: redirect($this->uri->segment(1));

            if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
                redirect($this->uri->segment(1));
            }

            $this->mdl_poll->update_answer($id, ['picture' => NULL]);

            if (!empty($answer->picture)) {
                @unlink("assets/poll/answers/{$answer->picture}");
            }

            $this->session->set_flashdata(
                'question_message',
                $this->global_lib->generateMessage('Gambar Jawaban berhasil dihapus.', 'info')
            );
        }

        redirect("{$this->uri->segment(1)}/edit_question/{$answer->id_content_poll}#answers");
    }

    public function lock_edit($id_content = '')
    {
        $this->_toggle_lock_edit(TRUE, $id_content);
    }

    public function unlock_edit($id_content = '')
    {
        $this->_toggle_lock_edit(FALSE, $id_content);
    }

    private function _toggle_lock_edit($is_lock, $id_content)
    {
        $content = $this->mdl_poll->find($id_content) ?: redirect($this->uri->segment(1));

        if (!$is_lock && $content->edit_id_admin != $this->session->userdata('id_admin') && $this->session->userdata('admin_level') !== '1') {
            redirect($_SERVER['HTTP_REFERER'] ?? $this->base_url);
        }

        $locked_content_id = $this->mdl_poll->get_locked_content_id($this->session->userdata('id_admin'));
        $is_valid_lock = $is_lock && is_null($locked_content_id);
        $is_valid_unlock = !$is_lock && (
                $content->edit_id_admin == $this->session->userdata('id_admin') || $this->session->userdata('admin_level') === '1'
            );

        if ($is_valid_lock || $is_valid_unlock) {
            $this->mdl_poll->update_content_without_tags($id_content, [
                'edit_id_admin' => $is_lock ? $this->session->userdata('id_admin') : NULL,
            ]);
        }

        redirect($_SERVER['HTTP_REFERER'] ?? "{$this->base_url}/edit/{$id_content}");
    }

    private function _set_content_validation_rules($status)
    {
        $base_rule = 'htmlentities|strip_tags|trim|xss_clean';
        $required_if_not_draft = ($status !== '-1' ? "|required" : '');
        $required_if_scheduled = ($status === '2' ? "|required" : '');

        $this->form_validation->set_rules('paginated', '', "{$base_rule}|required|in_list[0,1]");
        $this->form_validation->set_rules('type', '', "{$base_rule}|required|in_list[3,4]");
        $this->form_validation->set_rules('title', '', "{$base_rule}|required|max_length[1000]");
        $this->form_validation->set_rules('pic_caption', '', "{$base_rule}{$required_if_not_draft}|max_length[200]");
        $this->form_validation->set_rules('short_desc', '', "{$base_rule}|max_length[1000]");
        $this->form_validation->set_rules('meta_title', '', "{$base_rule}|max_length[500]");
        $this->form_validation->set_rules('meta_desc', '', $base_rule);
        $this->form_validation->set_rules('meta_keyword', '', $base_rule);
        $this->form_validation->set_rules('content', '', "{$base_rule}{$required_if_not_draft}");
        $this->form_validation->set_rules('tags', '', $base_rule);
        $this->form_validation->set_rules('content_status', '', "{$base_rule}|required|in_list" . ($this->is_admin ? '[-1,0,1,2]' : '[-1,0]'));
        $this->form_validation->set_rules('publish_date', '', "{$base_rule}{$required_if_scheduled}");
        $this->form_validation->set_rules('publish_time', '', "{$base_rule}{$required_if_scheduled}");

        if ($this->is_admin) {
            $this->form_validation->set_rules('featured_on_homepage', '', "{$base_rule}|required|in_list[0,1]");
            $this->form_validation->set_rules('trending', '', "{$base_rule}|required|in_list[0,1]");
            $this->form_validation->set_rules('recommended', '', "{$base_rule}|required|in_list[0,1]");
            $this->form_validation->set_rules('deletable', '', "{$base_rule}|required|in_list[0,1]");
        }
    }

    private function _set_question_validation_rules($max_order_no)
    {
        $base_rule = 'htmlentities|strip_tags|trim|xss_clean';

        $this->form_validation->set_rules('type', '', "{$base_rule}|required|in_list[1,2]");
        $this->form_validation->set_rules('order_no', '', "{$base_rule}|required|integer|greater_than[0]|less_than_equal_to[{$max_order_no}]");
        $this->form_validation->set_rules('question', '', "{$base_rule}|required|max_length[500]");
    }

    private function _set_answer_validation_rules($max_order_no)
    {
        $base_rule = 'htmlentities|strip_tags|trim|xss_clean';

        $this->form_validation->set_rules('order_no', '', "{$base_rule}|required|integer|greater_than[0]|less_than_equal_to[{$max_order_no}]");
        $this->form_validation->set_rules('answer', '', "{$base_rule}|required|max_length[500]");
    }

    private function _get_input_content_data($picture)
    {
        $status = $this->input->post('content_status');

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

        $data = [
            'paginated' => $this->input->post('paginated'),
            'type' => $this->input->post('type'),
            'title' => $this->input->post('title') ?: NULL,
            'short_desc' => $this->input->post('short_desc') ?: NULL,
            'meta_title' => $this->input->post('meta_title') ?: NULL,
            'meta_desc' => $this->input->post('meta_desc') ?: NULL,
            'meta_keyword' => $this->input->post('meta_keyword') ?: NULL,
            'pic_caption' => $this->input->post('pic_caption') ?: NULL,
            'content' => $this->input->post('content') ?: NULL,
            'content_pic' => $picture['picture'] ?: NULL,
            'content_pic_thumb' => $picture['thumb'] ?: NULL,
            'content_pic_square' => $picture['thumb_square'] ?: NULL,
            'content_status' => $status ?? -1,
            'publish_date' => $status > 0 ? $publish_date : NULL,
        ];

        if ($this->is_admin) {
            $data['id_admin'] = $this->session->userdata('id_admin');
            $data['featured_on_homepage'] = $this->input->post('featured_on_homepage');
            $data['trending'] = $this->input->post('trending');
            $data['recommended'] = $this->input->post('recommended');
            $data['deletable'] = $this->input->post('deletable');
        }
        else {
            $data['id_user'] = $this->session->userdata('id_user');
        }

        return $data;
    }

    private function _upload_content_picture($is_required = FALSE, $fail_redirect_url = NULL)
    {
        $filename = $_FILES['file_pic']['name'];

        $data = ['picture' => '', 'thumb' => '', 'thumb_square' => ''];

        if (!empty($filename)) {
            $config = [
                'upload_path' => './assets/poll/',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => '12000',
                'max_width' => '8000',
                'max_height' => '8000',
                'file_name' => 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename),
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_pic')) {
                $upload_data = $this->upload->data();

                $data['picture'] = $this->picture->resizePhoto($upload_data['full_path'], $this->pic_width, $this->pic_height);
                $data['thumb'] = $this->picture->createThumb($upload_data['full_path'], $this->pic_thumb_width, $this->pic_thumb_height);
                $data['thumb_square'] = $this->picture->createThumbSquare($upload_data['full_path'], $this->pic_square_width, $this->pic_square_height);
            }
            else {
                $this->session->set_flashdata(
                    'message',
                    $this->global_lib->generateMessage('Failed to upload file. <br/> cause: ' . $this->upload->display_errors(), 'danger')
                );
                redirect("{$this->uri->segment(1)}/{$fail_redirect_url}");
            }
        }
        elseif ($is_required) {
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage('You must upload article picture', 'danger')
            );
            redirect("{$this->uri->segment(1)}/{$fail_redirect_url}");
        }

        return $data;
    }

    private function _upload_question_picture($fail_redirect_url = NULL)
    {
        $filename = $_FILES['picture']['name'];
        $picture = NULL;

        if (!empty($filename)) {
            $picture = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
            $config = [
                'upload_path' => './assets/poll/questions',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => '12000',
                'max_width' => '8000',
                'max_height' => '8000',
                'file_name' => 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename),
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('picture')) {
                $picture = $this->upload->data()['file_name'];
            }
            else {
                $this->session->set_flashdata(
                    'question_message',
                    $this->global_lib->generateMessage('Failed to upload file. <br/> cause: ' . $this->upload->display_errors(), 'danger')
                );
                redirect("{$this->uri->segment(1)}/{$fail_redirect_url}");
            }

            return $picture;
        }

        return $picture;
    }

    private function _upload_answer_picture($fail_redirect_url = NULL)
    {
        $filename = $_FILES['picture']['name'];
        $picture = NULL;

        if (!empty($filename)) {
            $picture = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
            $config = [
                'upload_path' => './assets/poll/answers',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => '12000',
                'max_width' => '8000',
                'max_height' => '8000',
                'file_name' => 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename),
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('picture')) {
                $picture = $this->upload->data()['file_name'];
            }
            else {
                $this->session->set_flashdata(
                    'answer_message',
                    $this->global_lib->generateMessage('Failed to upload file. <br/> cause: ' . $this->upload->display_errors(), 'danger')
                );
                redirect("{$this->uri->segment(1)}/{$fail_redirect_url}");
            }

            return $picture;
        }

        return $picture;
    }

    private function _render($page)
    {
        $is_admin = $this->uri->segment(1) === 'admin_polling';
        $data = [
            'content' => $page,
            'type' => $page,
            'css_files' => $this->css,
            'js_files' => $this->js,
            'global_data' => $this->global_lib->getGlobalData(),
        ];

        if ($is_admin) {
            $data['modules'] = $this->global_lib->generateAdminModule();
        }

        //check ada kompetisi aktif
        $data['is_competition_exist'] = $this->mdl_competition->getActiveCompetitionCount();

        $this->load->view($is_admin ? '/admin/template' : '/user/template', $data);
    }

    private function _authorize()
    {
        if ($this->uri->segment(1) === 'admin_polling') {
            if (
                !$this->session->userdata('admin_logged_in')
                || (
                    strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === FALSE
                    && $this->session->userdata('admin_level') !== '1'
                )
            ) {
                redirect('admin_dashboard/index');
            }
        }
        elseif ($this->uri->segment(1) === 'user_polling') {
            if (!($this->session->userdata('user_logged_in') && $this->session->userdata('user_verified') === '1')) {
                redirect('page/login/' . rtrim(base64_encode(urlencode($this->uri->uri_string())), "="));
            }
        }
        else {
            redirect(base_url());
        }
    }
}
