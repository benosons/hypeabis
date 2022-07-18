<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Photo
 *
 * @property Mdl_photo $mdl_photo
 * @property Mdl_competition $mdl_competition
 * @property Global_lib $global_lib
 */
class Photo extends CI_Controller
{
    private $module_name = 'admin_photo';
    private $is_admin;
    private $base_url;
    private $data = [];
    private $pic_thumb_width = 420;
    private $pic_thumb_height = 300;
    private $pic_square_width = 480;
    private $pic_square_height = 480;
    private $min_total_photos = 1;
    private $pagination_per_page = 20;

    public $pic_width = 1080;
    public $pic_height = 720;
    public $css = [];
    public $js = [];

    public function __construct()
    {
        parent::__construct();

        $this->base_url = base_url() . $this->uri->segment(1);
        $this->is_admin = $this->session->userdata('admin_logged_in') && $this->uri->segment(1) === 'admin_photo';
        $this->data = [
            'is_verified_member' => $this->session->userdata('user_verified') === '1',
            'base_url' => $this->base_url,
            'is_admin' => $this->is_admin,
            'module_name' => $this->module_name,
            'min_total_photos' => $this->min_total_photos,
        ];

        $this->session->set_userdata('is_admin_dashboard', $this->is_admin);

        $this->load->model('mdl_user');
        $this->load->model('mdl_content2');
        $this->load->model('mdl_photo');
        $this->load->model('mdl_content_editor');
        $this->load->model('mdl_competition');

        if ($this->is_admin) {
            $this->load->model('mdl_content_editor');
        }

        $this->_authorize();

        if ($this->uri->segment(1) === 'user_photo') {
            $this->mdl_photo->filter_by_user($this->session->userdata('id_user'));
        }

        if ($this->is_admin) {
            $this->data['users'] = $this->mdl_user->getAllUser();
        }
    }

    public function index()
    {
        $this->clearSearchSession();

        if ($this->is_admin) {
            $pagination_config = $this->global_lib->paginationConfigAdmin();
        }
        else {
            $pagination_config = $this->global_lib->paginationConfig();
        }
        $pagination_config['base_url'] = "{$this->base_url}/index/";
        $pagination_config['total_rows'] = $this->mdl_photo->without_draft($this->is_admin)->count();
        $pagination_config['per_page'] = $this->pagination_per_page;
        $pagination_config['uri_segment'] = 3;
        $this->pagination->initialize($pagination_config);

        $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
        $this->data['photos'] = $this->mdl_photo
            ->order_by_admin_editor($this->session->userdata('id_admin'))
            ->without_draft($this->is_admin)
            ->with_admin_editor()
            ->all($pagination_config['per_page'], $this->data['offset']);

        $this->data['competitions'] = $this->mdl_competition->getByType('photo');

        $this->_render($this->load->view('shared/photo/all', $this->data, TRUE));
    }

    public function add()
    {
        if ($this->is_admin) {
            redirect($this->uri->segment(1));
        }

        $this->data['heading_text'] = 'Tambah';
        $this->data['submit_url'] = "{$this->base_url}/save_add";
        $this->data['photos'] = [];
        $this->data['competition'] = $this->mdl_competition->get_active('photo');
        $this->data['has_reach_submit_limit'] = false;
        //$this->data['has_reach_submit_limit'] = (isset($this->data['competition']) && count($this->data['competition']) == 1)
        //    ? $this->mdl_photo->count_submit(
        //        $this->data['competition'][0]->id_competition,
        //        $this->session->userdata('id_user')
        //    ) >= $this->data['competition'][0]->max_content
        //    : false;

        if(isset($this->data['competition']) && count($this->data['competition']) > 0) {
            $this->data['competition_category'] = $this->mdl_competition->getCategory($this->data['competition'][0]->id_competition);
        }
        else{
            $this->data['competition_category'] = [];
        }

        $this->data['form_value'] = $this->session->flashdata('form_value') ?: [
            'type' => '3',
            'title' => '',
            'content_status' => '-1',
            'join_competition' => $this->input->get('join_competition') ?: '0',
            'agree_competition_terms' => $this->input->get('join_competition') == '1' ? '1' : '0',
            'publish_date' => NULL,
            'publish_time' => NULL,
        ];

        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>';
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
        $this->_render($this->load->view('shared/photo/form_add', $this->data, TRUE));
    }

    public function save_add()
    {
        if ($this->is_admin) {
            redirect($this->uri->segment(1));
        }

        $status = $this->input->post('content_status');

        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_validate_total_photos($status, 'add');
        $this->_validate_total_submit_competition($status, 'add');
        $this->_set_content_validation_rules($status);

        if ($this->form_validation->run()) {
            $photos = [];

            for ($i = 1; $i <= 6; $i++) {
                if (!empty($_FILES["file_pic_{$i}"]['name'])) {
                    $photos[$i] = $this->_upload_photo($i, 'add');
                    $photos[$i]['short_desc'] = $this->input->post("short_desc_{$i}");
                }
            }

            $content_data = $this->_get_input_content_data();
            $tags = !empty($this->input->post('tags')) ? explode(',', $this->input->post('tags')) : [];
            $id_content = $this->mdl_photo->insert_content($content_data, $tags);

            foreach ($photos as $photo) {
                $this->mdl_photo->add_photo($id_content, $photo);
            }

            if (!$this->is_admin) {
                $this->load->library('point_lib');
                $point_config = [
                    'trigger_type' => 'add_photo',
                    'id_user' => $this->session->userdata('id_user'),
                    'desc' => $content_data['title'],
                ];

                if ($content_data['content_status'] === '1') {
                    $this->point_lib->addPoint($point_config);
                }
            }

            $message = 'Hypephoto berhasil ditambahkan.';
            if (is_null($this->input->post('preview'))) {
                $this->session->set_flashdata('form_value', NULL);
                $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

                redirect("{$this->uri->segment(1)}/edit/{$id_content}");
            }
            else {
                $this->session->set_flashdata('message', $message);
                $name = $this->is_admin ? '' : '-' . strtolower(url_title($this->mdl_user->getNameByID($content_data['id_user'])));

                redirect("photo/{$id_content}{$name}/" . strtolower(url_title($content_data['title'])) . '?is_preview=1');
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
        $this->data['content'] = $this->mdl_photo->with_competition()->find($id_content) ?: redirect($this->uri->segment(1));

        if ($this->data['content']->edit_id_admin && $this->data['content']->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect('admin_photo/index');
        }

        $this->data['photos'] = $this->mdl_photo->get_photos($id_content);
        $this->data['competition'] = $this->mdl_competition->get_active('photo');
        $this->data['has_reach_submit_limit'] = false;

        $this->data['heading_text'] = 'Edit';
        $this->data['submit_url'] = "{$this->base_url}/save_edit/{$id_content}";
        $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array)$this->data['content'];

        $this->data['form_value']['id_competition'] = ($this->data['content']->id_competition ?: 0);
        $this->data['form_value']['join_competition'] = (isset($this->data['competition']) && count($this->data['competition']) == 1)
            ? !is_null($this->data['content']->id_competition) && $this->data['content']->id_competition === $this->data['competition'][0]->id_competition
            : false;
        $this->data['form_value']['agree_competition_terms'] = (isset($this->data['competition']) && count($this->data['competition']) == 1)
            ? !is_null($this->data['content']->id_competition) && $this->data['content']->id_competition === $this->data['competition'][0]->id_competition
            : false;
        $this->data['form_value']['publish_time'] = $this->data['content']->publish_date ? date('H:i', strtotime($this->data['content']->publish_date)) : NULL;
        $this->data['locked_content_id'] = $this->mdl_photo->get_locked_content_id($this->session->userdata('id_admin'));

        //jika kompetisi sudah lewat, tidak boleh diedit
        $this->data['allow_competition_edit'] = true;
        if(isset($this->data['content']->id_competition) && $this->data['content']->id_competition > 0 &&
            strtotime($this->data['content']->competition_finish_date . ' 00:00:00') < strtotime(date('Y-m-d H:i:s'))
        ){
            $this->data['allow_competition_edit'] = false;
        }

        //ambil category kompetisi
        if(isset($this->data['content']->id_competition) && $this->data['content']->id_competition > 0){
            $this->data['competition_category'] = $this->mdl_competition->getCategory($this->data['content']->id_competition);
        }
        else if(isset($this->data['competition']) && count($this->data['competition']) > 0) {
            $this->data['competition_category'] = $this->mdl_competition->getCategory($this->data['competition'][0]->id_competition);
        }
        else{
            $this->data['competition_category'] = [];
        }

        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>';
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
        $this->_render($this->load->view('shared/photo/form_edit', $this->data, TRUE));
    }

    public function save_edit($id_content)
    {
        $content = $this->mdl_photo->find($id_content) ?: redirect($this->uri->segment(1));
        $id_competition = $content->id_competition;

        if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
            redirect('admin_photo/index');
        }

        $join_competition = boolval($this->input->post('join_competition'));
        $content_photos = $this->mdl_photo->get_photos($id_content);
        $status = $this->input->post('content_status');

        //if (is_null($id_competition) || $id_competition === $current_id_competition) {
        //    $id_competition = $join_competition ? $current_id_competition : NULL;
        //}

        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_validate_total_photos($status, "edit/{$id_content}", $content_photos);
        $this->_validate_pinned_photos($id_content, "edit/{$id_content}");
        $this->_set_content_validation_rules($status);

        if ($this->form_validation->run()) {
            $content_data = $this->_get_input_content_data_for_update($content);
            $content_data['edit_id_admin'] = in_array($status, ['1', '2']) ? null : $content->edit_id_admin;

            $tags = !empty($this->input->post('tags')) ? explode(',', $this->input->post('tags')) : [];
            $this->mdl_photo->update_content($id_content, $content_data, $tags);

            if ($this->is_admin) {
                $this->mdl_content_editor->insert_or_update($id_content, $this->session->userdata('id_admin'));
            }

            for ($i = 1; $i <= 6; $i++) {
                $short_desc = $this->input->post("short_desc_{$i}");
                $photo = isset($content_photos[$i - 1]) ? (array)$content_photos[$i - 1] : [];

                if (!empty($_FILES["file_pic_{$i}"]['name'])) {
                    $is_photo_required = $i === 1 && $status !== '-1' && !isset($content_photos[$i]);
                    $photo = $this->_upload_photo($i, $is_photo_required, "edit/{$id_content}");
                }

                if (!empty($short_desc)) {
                    $photo['short_desc'] = $short_desc;
                }

                if (!empty($photo)) {
                    if (!isset($content_photos[$i - 1])) {
                        if (!empty($_FILES["file_pic_{$i}"]['name'])) {
                            $this->mdl_photo->add_photo($id_content, $photo);
                        }
                    }
                    else {
                        $content_photo = $content_photos[$i - 1];
                        $this->mdl_photo->update_photo($content_photo->id, $photo);

                        if ($content_photo->picture !== $photo['picture']) {
                            $this->_delete_photo($content_photo);
                        }
                    }
                }

                if ($join_competition && $i == 1) {
                    break;
                }
            }

            for ($i = 1; $i < count($content_photos) && $join_competition; $i++) {
                $this->_delete_photo($content_photos[$i]);
                $this->mdl_photo->delete_photo($content_photos[$i]->id);
            }

            if ($content->content_status !== $content_data['content_status']) {
                $this->load->library('point_lib');
                $point_config = ['trigger_type' => 'add_photo', 'id_user' => $content->id_user, 'desc' => $content->title];

                if ($content->id_user) {
                    if ($content->content_status !== '1' && $content_data['content_status'] === '1') {
                        $this->point_lib->addPoint($point_config);
                    }
                    elseif ($content->content_status === '1' && $content_data['content_status'] !== '1') {
                        $this->point_lib->substractPoint($point_config);
                    }
                }
            }

            $message = 'Hypephoto berhasil diubah.';
            if (is_null($this->input->post('preview'))) {
                $this->session->set_flashdata('form_value', NULL);
                $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

                redirect($this->uri->segment(1));
            }
            else {
                $this->session->set_flashdata('message', $message);
                $name = $this->is_admin && !$content->id_user ? '' : '-' . strtolower(url_title($this->mdl_user->getNameByID($content->id_user)));

                redirect("photo/{$id_content}{$name}/" . strtolower(url_title($content_data['title'])) . '?is_preview=1');
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
        $content = $this->mdl_photo->find($id_content);

        if (!is_null($content)) {
            if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
                redirect('admin_photo/index');
            }

            if ($content->deletable !== '1' && $this->session->userdata('admin_level') !== '1') {
                $this->session->set_flashdata(
                    'message',
                    $this->global_lib->generateMessage("You can't delete this content. Contact your super adminintrator.", 'danger')
                );
                return redirect($this->uri->segment(1));
            }

            $photos = $this->mdl_photo->get_photos($id_content);

            $this->mdl_photo->delete($id_content);

            foreach ($photos as $photo) {
                $this->_delete_photo($photo);
            }

            if ($content->id_user) {
                $this->load->library('point_lib');

                $point_config = ['trigger_type' => 'add_photo', 'id_user' => $content->id_user, 'desc' => $content->title];

                if ($content->content_status === '1') {
                    $this->point_lib->substractPoint($point_config);
                }
            }

            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage('Hypephoto berhasil dihapus.', 'info')
            );
        }

        redirect($this->uri->segment(1));
    }

    public function delete_photo($id_content_photo)
    {
        $photo = $this->mdl_photo->find_photo($id_content_photo);
        $content = $this->mdl_photo->find($photo->id_content);
        $photo_counts = $this->mdl_photo->count_all_photos($photo->id_content);

        if (!is_null($content) && $content->content_status !== '1' && $photo_counts > 1) {
            if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin')) {
                redirect('admin_photo/index');
            }

            $this->mdl_photo->delete_photo($id_content_photo);
            $this->_delete_photo($photo);

            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage('Gambar berhasil dihapus', 'info')
            );
        }

        redirect("{$this->uri->segment(1)}/edit/{$photo->id_content}");
    }

    private function _delete_photo($photo)
    {
        $fileinfo = pathinfo("assets/photo/{$photo->picture}");
        $image = str_replace('Original', '', preg_replace("/[^a-zA-Z0-9]+/", "", $fileinfo['filename'])) . '.' . $fileinfo['extension'];

        @unlink("assets/photo/{$image}");
        @unlink("assets/photo/{$photo->picture}");
        @unlink("assets/photo/thumb/{$photo->picture_thumb}");
        @unlink("assets/photo/thumb/{$photo->picture_square}");
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
        $this->form_validation->set_rules('competition', '', 'htmlentities|strip_tags|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $message = $this->global_lib->generateMessage(validation_errors(), "info");
            $this->session->set_flashdata('message', $message);
            redirect("{$this->base_url}/index");
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
                'competition' => $this->input->post('competition'),
                'start_date' => $this->input->post('start_date'),
                'finish_date' => $this->input->post('finish_date'),
                'like_start_date' => $this->input->post('like_start_date'),
                'like_finish_date' => $this->input->post('like_finish_date'),
                'search_collapsed' => $this->input->post('search_collapsed')
            );
            $this->session->set_userdata('search_content', $search_param);

            redirect("{$this->base_url}/search");
        }
    }

    public function search()
    {
        $id_user = $this->is_admin ? NULL : $this->session->userdata('id_user');

        // ambil parameter search di session..
        $search_param = $this->session->userdata('search_content');

        // ================= Syncronize parameter dengan field di database =====================//
        $operator_list = array('like', 'not like');
        $field_list = array('title', 'short_desc', 'content');
        $sort_by_list = array(
            'default' => 'content_status ASC, publish_date DESC, id_content DESC',
            'newest' => 'id_content DESC',
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
        $this->data['total_row'] = $this->mdl_photo->count_search_result($search_param);

        $config = $this->is_admin ? $this->global_lib->paginationConfigAdmin() : $this->global_lib->paginationConfig();
        $config['base_url'] = "{$this->base_url}/search/";
        $config['total_rows'] = $this->data['total_row'];
        $config['per_page'] = ($search_param['per_page'] > 0 ? $search_param['per_page'] : $this->pagination_per_page);
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $this->data['offset'] = $this->uri->segment($config['uri_segment']) ?: 0;
        $this->data['photos'] = $this->mdl_photo->search2($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0), (isset($id_user) && $id_user > 0));

        $this->data['competitions'] = $this->mdl_competition->getByType('photo');

        //ambil semua category`

        //load view search result..
        $content = $this->load->view('shared/photo/all', $this->data, true);

        $this->_render($content);
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

    private function _toggle_lock_edit($is_lock, $id_content)
    {
        $content = $this->mdl_photo->find($id_content) ?: redirect('admin_photo');

        if (!$is_lock && $content->edit_id_admin != $this->session->userdata('id_admin') && $this->session->userdata('admin_level') !== '1') {
            redirect($_SERVER['HTTP_REFERER'] ?? $this->base_url);
        }

        $locked_content_id = $this->mdl_photo->get_locked_content_id($this->session->userdata('id_admin'));
        $is_valid_lock = $is_lock && is_null($locked_content_id);
        $is_valid_unlock = !$is_lock && (
                $content->edit_id_admin == $this->session->userdata('id_admin') || $this->session->userdata('admin_level') === '1'
            );

        if ($is_valid_lock || $is_valid_unlock) {
            $this->mdl_photo->update_content_without_tags($id_content, [
                'edit_id_admin' => $is_lock ? $this->session->userdata('id_admin') : NULL,
            ]);
        }

        redirect($_SERVER['HTTP_REFERER'] ?? "{$this->base_url}/edit/{$id_content}");
    }

    private function _validate_total_photos($status, $fail_redirect_url = NULL, $photos = [])
    {
        if ($status !== '-1') {
            $total_photos = count($photos);

            for ($i = 1; $i <= 6; $i++) {
                if (!empty($_FILES["file_pic_{$i}"]['name']) || (isset($photos[$i]) && !is_null($photos[$i]->picture))) {
                    $total_photos++;
                }
            }

            if ($total_photos < $this->min_total_photos) {
                $this->session->set_flashdata(
                    'message',
                    $this->global_lib->generateMessage("Anda harus upload minimal {$this->min_total_photos} foto", 'danger')
                );
                redirect("{$this->uri->segment(1)}/{$fail_redirect_url}");
            }
        }
    }

    private function _validate_pinned_photos($edit_id_content, $fail_redirect_url = NULL)
    {
        if ($this->is_admin) {
            $pinned_photo = $this->mdl_photo->get_pinned();
            $is_pinned = $this->input->post('pin_on_homepage') === '1';

            if ($is_pinned && $pinned_photo && $pinned_photo != $edit_id_content) {
                $this->session->set_flashdata(
                    'message',
                    $this->global_lib->generateMessage(
                        "Hypephoto <a href='{$this->base_url}/edit/{$pinned_photo->id_content}' class='alert-link'>{$pinned_photo->title}</a> telah di pin.",
                        'danger'
                    )
                );


                $form_value = $this->input->post();
                $form_value['pin_on_homepage'] = '0';
                $this->session->set_flashdata('form_value', $form_value);

                redirect("{$this->uri->segment(1)}/{$fail_redirect_url}");
            }
        }
    }

    private function _validate_total_submit_competition($id_competition, $id_user, $except_id = NULL)
    {
        return null;
    }

    private function _set_content_validation_rules($status)
    {
        $base_rule = 'htmlentities|strip_tags|trim|xss_clean';
        // $required_if_not_draft = ($status !== '-1' ? "|required" : '');
        $required_if_scheduled = ($status === '2' ? "|required" : '');

        $this->form_validation->set_rules('title', '', "{$base_rule}|required|max_length[1000]");
        $this->form_validation->set_rules('tags', '', $base_rule);
        $this->form_validation->set_rules('content_status', '', "{$base_rule}|integer|in_list" . ($this->is_admin ? '[-1,0,1,2]' : '[-1,0]'));
        $this->form_validation->set_rules('id_competition', '', "{$base_rule}|integer");
        $this->form_validation->set_rules('agree_competition_terms', '', "{$base_rule}|integer");
        $this->form_validation->set_rules('featured_on_homepage', '', "{$base_rule}|integer");
        $this->form_validation->set_rules('publish_date', '', "{$base_rule}{$required_if_scheduled}");
        $this->form_validation->set_rules('publish_time', '', "{$base_rule}{$required_if_scheduled}");

        for ($i = 1; $i <= 6; $i++) {
            $required = $status !== '-1' && !empty($_FILES["file_pic_{$i}"]['name']) ? '|required' : '';

            $this->form_validation->set_rules("short_desc_{$i}", '', "{$base_rule}{$required}");
        }

        if ($this->is_admin) {
            $this->form_validation->set_rules('id_user', '', "{$base_rule}|required|integer");
        }
    }

    private function _get_input_content_data($id_competition = NULL)
    {
        $status = $this->input->post('content_status');
        $join_competition = $this->input->post('join_competition');

        $id_category = NULL;
        if(! isset($id_competition)) {
            if ($join_competition === '1') {
                $id_competition = $this->input->post('id_competition');
                $id_category = $this->input->post('id_competition_category');
                $this->checkCompetitionMaximumSubmit($id_competition);
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

        $data = [
            'title' => $this->input->post('title') ?: NULL,
            'pic_caption' => $this->input->post('pic_caption') ?: NULL,
            'content_status' => $status ?? -1,
            'featured_on_homepage' => $this->input->post('featured_on_homepage') ?? 0,
            'publish_date' => $status > 0 ? $publish_date : NULL,
            'id_competition' => $id_competition ?: NULL,
            'id_competition_category' => $id_category ?: NULL,
        ];

        if ($status === '0') {
            $data['submit_date'] = date('Y-m-d H:i:s');
        }

        if ($this->is_admin) {
            $pin_on_homepage = $this->input->post('pin_on_homepage') ?? 0;
            $data['pin_on_homepage'] = $pin_on_homepage;
            $data['featured_on_homepage'] = $pin_on_homepage ? 1 : $this->input->post('featured_on_homepage');
            $data['id_admin'] = $this->session->userdata('id_admin');
            $data['id_user'] = $this->input->post('id_user');
        }
        else {
            $data['id_user'] = $this->session->userdata('id_user');
        }

        return $data;
    }

    private function _get_input_content_data_for_update($content)
    {
        $status = $this->input->post('content_status');
        $join_competition = $this->input->post('join_competition');
        $id_competition = $content->id_competition;
        $id_competition_category = $content->id_competition_category;

        //d($this->input->post());
        //dd($content);

        //if ($join_competition === '1' && $content->id_competition != $this->input->post('id_competition')) {
        if ($content->id_competition != $this->input->post('id_competition')) {
            //kompetisi di edit
            $id_competition = $this->input->post('id_competition');
            $id_competition_category = $this->input->post('id_competition_category');
            $this->checkCompetitionMaximumSubmit($id_competition);
        }
        else{
            //check kategorinya diedit atau tidak. kalau diedit ganti dengan id category yg baru
            if($id_competition_category != $this->input->post('id_competition_category')){
                $id_competition_category = $this->input->post('id_competition_category');
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

        $data = [
            'title' => $this->input->post('title') ?: NULL,
            'pic_caption' => $this->input->post('pic_caption') ?: NULL,
            'content_status' => $status ?? -1,
            'featured_on_homepage' => $this->input->post('featured_on_homepage') ?? 0,
            'publish_date' => $status > 0 ? $publish_date : NULL,
            'id_competition' => $id_competition,
            'id_competition_category' => $id_competition_category
        ];

        if ($status === '0') {
            $data['submit_date'] = date('Y-m-d H:i:s');
        }

        if ($this->is_admin) {
            $pin_on_homepage = $this->input->post('pin_on_homepage') ?? 0;
            $data['pin_on_homepage'] = $pin_on_homepage;
            $data['featured_on_homepage'] = $pin_on_homepage ? 1 : $this->input->post('featured_on_homepage');
            $data['id_admin'] = $this->session->userdata('id_admin');
            $data['id_user'] = $this->input->post('id_user');
        }
        else {
            $data['id_user'] = $this->session->userdata('id_user');
        }

        return $data;
    }

    private function _upload_photo($number, $fail_redirect_url = NULL)
    {
        $filename = $_FILES["file_pic_{$number}"]['name'];

        $data = ['picture' => '', 'picture_thumb' => '', 'picture_square' => ''];

        if (!empty($filename)) {
            $config = [
                'upload_path' => './assets/photo/',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => '5000',
                'max_width' => '8000',
                'max_height' => '8000',
                'file_name' => 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename),
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload("file_pic_{$number}")) {
                $upload_data = $this->upload->data();
                $data['picture'] = $this->picture->resizePhoto($upload_data['full_path'], $this->pic_width, $this->pic_height, TRUE, TRUE);
                $data['picture_thumb'] = $this->picture->createThumb($upload_data['full_path'], $this->pic_thumb_width, $this->pic_thumb_height, TRUE);
                $picture_thumb_homepage = $this->picture->createThumbWithPostfixBasedOnWidth('_homepage', $upload_data['full_path'], 300);
                $data['picture_square'] = $this->picture->createThumbSquare($upload_data['full_path'], $this->pic_square_width, $this->pic_square_height);
                $this->picture->watermarkPhoto('assets/photo/' . $data['picture'], 'assets/logo/logo-overlay.png');
            }
            else {
                $this->session->set_flashdata(
                    'message',
                    $this->global_lib->generateMessage("Gagal mengupload upload Gambar {$number} karena: " . $this->upload->display_errors(), 'danger')
                );
                redirect("{$this->uri->segment(1)}/{$fail_redirect_url}");
            }
        }

        return $data;
    }

    private function _render($page)
    {
        $is_admin = $this->uri->segment(1) === 'admin_photo';
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
        if ($this->uri->segment(1) === 'admin_photo') {
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
        elseif ($this->uri->segment(1) === 'user_photo') {
            if (!$this->session->userdata('user_logged_in')) {
                redirect('page/login/' . urlencode(rtrim(base64_encode($this->uri->uri_string()), "=")));
            }
        }
        else {
            redirect(base_url());
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
                $submission = $this->mdl_photo->getContentByIdCompetitionCount($id_user, $competition->id_competition);
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

        if (! $allow_submit){
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage($message, "danger")
            );
            redirect($this->uri->segment(1));
        }
    }
}
