<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Admin_competition
 *
 * @property Mdl_competition $mdl_competition
 * @property Global_lib $global_lib
 */
class Admin_competition extends CI_Controller
{
    private $module_name = 'admin_competition';
    private $data = [];
    public $css = [];
    public $js = [];
    public $pic_width = 1000;
    public $default_pic_width = 390;
    public $default_pic_height = 260;

    public function __construct()
    {
        parent::__construct();

        $this->base_url = site_url('admin_competition');
        $this->data = [
            'module' => $this->global_lib->getModuleDetail($this->module_name),
            'base_url' => $this->base_url,
        ];

        $this->load->model('mdl_competition');

        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin_dashboard/index');
        }

        if (strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === FALSE && $this->session->userdata('admin_level') !== '1') {
            redirect('admin_dashboard/index');
        }
    }

    public function index()
    {
        $pagination_config = $this->global_lib->paginationConfigAdmin();
        $pagination_config['base_url'] = $this->base_url;
        $pagination_config['total_rows'] = $this->mdl_competition->count();
        $pagination_config['per_page'] = 20;
        $pagination_config['uri_segment'] = 3;

        $this->pagination->initialize($pagination_config);

        $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
        $this->data['items'] = $this->mdl_competition->all($pagination_config['per_page'], $this->data['offset']);
        $this->_render('admin/competition/all');
    }

    public function add()
    {
        $this->data['heading_text'] = 'Tambah';
        $this->data['submit_url'] = "{$this->base_url}/save_add";
        $this->data['mode'] = 'add';
        $this->data['form_value'] = $this->session->flashdata('form_value') ?: [
            'id' => '',
            'title' => '',
            'competition_type' => '',
            'start_date' => '',
            'finish_date' => '',
            'max_content' => '',
            'description' => '',
            'pic' => '',
            'cover_status' => '1',
        ];

        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->_render('admin/competition/form', $this->data);
    }

    public function save_add()
    {
        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_set_form_validation_rules();

        if ($this->form_validation->run()) {
            $data = $this->_get_input_data();

            //check tanggal kompetisi
            //$this->_is_competition_exists($data['start_date'], $data['finish_date'], 'add');

            //upload file pic
            $pic = '';
            if (!empty($_FILES['pic']['name'])) {
                $config = $this->picUploadConfig($_FILES['pic']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $pic = $this->picture->resizePhotoWithRatioByWidth($upload_data['upload_data']['full_path'], $this->pic_width, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect("{$this->base_url}/add");
                }
            }
            $data['pic'] = $pic;

            $default_pic = '';
            if (!empty($_FILES['default_pic']['name'])) {
                $config = $this->picUploadConfig($_FILES['default_pic']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('default_pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $default_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->default_pic_width, $this->default_pic_height, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload default picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect("{$this->base_url}/add");
                }
            }
            else{
                $message = $this->global_lib->generateMessage("Anda harus mengupload file gambar default untuk artikel.", "danger");
                $this->session->set_flashdata('message', $message);
                redirect("{$this->base_url}/add");
            }
            $data['default_pic'] = $default_pic;

            //insert data kompetisi
            $id_competition = $this->mdl_competition->insert($data);
            //jika berhasil, insert category kompetisi
            if (isset($id_competition) && is_numeric($id_competition)){
                $categories_str = $this->input->post('categories');
                $categories = explode(',', $categories_str);
                $insert_data = [];
                foreach ($categories as $category) {
                    if (strlen(trim($category)) > 0) {
                        $insert_arr = array(
                            'id_competition' => $id_competition,
                            'category_name' => $category
                        );
                        $insert_data[] = $insert_arr;
                    }
                }
                if (count($insert_data) > 0) {
                    $this->mdl_competition->insertCategoryBatch($insert_data);
                }
            }

            $message = 'Kompetisi berhasil ditambahkan.';
            $this->session->set_flashdata('form_value', NULL);
            $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));
            redirect($this->base_url);
        }
        else {
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );
            redirect("{$this->base_url}/add");
        }
    }

    public function deletePic($id)
    {
        $this->data['competition'] = $this->mdl_competition->find($id) ?: redirect($this->base_url);
        $this->mdl_competition->update($id, ['pic' => '']);

        $message = 'Kompetisi Hypephoto berhasil diubah.';
        $this->session->set_flashdata('form_value', NULL);
        $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));
        redirect("{$this->base_url}/edit/" . $id);
    }

    public function edit($id)
    {
        $this->data['id'] = $id;
        $this->data['competition'] = $this->mdl_competition->find($id) ?: redirect($this->base_url);
        $this->data['competition']->start_date = date_create($this->data['competition']->start_date)->format('d-m-Y');
        $this->data['competition']->finish_date = date_create($this->data['competition']->finish_date)->format('d-m-Y');

        $this->data['heading_text'] = 'Edit';
        $this->data['submit_url'] = "{$this->base_url}/save_edit/{$id}";
        $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array)$this->data['competition'];
        $this->data['categories'] = $this->mdl_competition->getCategory($id);

        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->_render('admin/competition/form', $this->data);
    }

    public function save_edit($id)
    {
        $data = $this->mdl_competition->find($id) ?: redirect($this->base_url);

        $this->session->set_flashdata('form_value', $this->input->post());
        $this->_set_form_validation_rules();

        if ($this->form_validation->run()) {
            $data = $this->_get_input_data();

            //check tanggal kompetisi
            //$this->_is_competition_exists($data['start_date'], $data['finish_date'], "edit/{$id}", $id);

            //upload file
            $pic = '';
            if (!empty($_FILES['pic']['name'])) {
                $config = $this->picUploadConfig($_FILES['pic']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $pic = $this->picture->resizePhotoWithRatioByWidth($upload_data['upload_data']['full_path'], $this->pic_width, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect("{$this->base_url}/edit/" . $id);
                }
            }
            if (isset($pic) && strlen(trim($pic)) > 0) {
                $data['pic'] = $pic;
            }

            $default_pic = '';
            if (!empty($_FILES['default_pic']['name'])) {
                $config = $this->picUploadConfig($_FILES['default_pic']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('default_pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $default_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->default_pic_width, $this->default_pic_height, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload default picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect("{$this->base_url}/edit/" . $id);
                }
            }
            if (isset($default_pic) && strlen(trim($default_pic)) > 0) {
                $data['default_pic'] = $default_pic;
            }

            $this->mdl_competition->update($id, $data);

            $message = 'Kompetisi berhasil diubah.';
            $this->session->set_flashdata('form_value', NULL);
            $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));
            redirect($this->base_url);
        }
        else {
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );

            redirect("{$this->base_url}/edit/" . $id);
        }
    }

    public function delete($id)
    {
        // if ($this->mdl_competition->inactive()->find($id) > 0)
        if ($this->mdl_competition->find($id) > 0) {
            $this->mdl_competition->delete($id);
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage('Kompetisi berhasil dihapus.', 'info')
            );
        }

        redirect($this->base_url);
    }

    public function addCategory($id)
    {
        $this->data['id'] = $id;
        $this->data['competition'] = $this->mdl_competition->find($id) ?: redirect($this->base_url);
        $this->data['submit_url'] = "{$this->base_url}/save_add_category/{$id}";
        $this->_render('admin/competition/add_category', $this->data);
    }

    public function save_add_category($id){
        $this->data['id'] = $id;
        $this->data['competition'] = $this->mdl_competition->find($id) ?: redirect($this->base_url);

        $base_rule = 'htmlentities|strip_tags|trim|xss_clean';
        $this->form_validation->set_rules('category_name', '', "{$base_rule}|required");
        if ($this->form_validation->run()) {
            $this->mdl_competition->insertCategory([
                'id_competition' => $id,
                'category_name' => $this->input->post('category_name')
            ]);
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Kategori kompetisi berhasil ditambahkan.", "info")
            );
            redirect("{$this->base_url}/edit/{$id}");
        }
        else{
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );
            redirect("{$this->base_url}/addCategory/{$id}");
        }
    }

    public function editCategory($id = '', $id_category = '')
    {
        $this->data['id'] = $id;
        $this->data['competition'] = $this->mdl_competition->find($id) ?: redirect($this->base_url);
        $this->data['competition_category'] = $this->mdl_competition->findCategory($id_category) ?: redirect("{$this->base_url}/edit/{$id}");
        $this->data['submit_url'] = "{$this->base_url}/save_edit_category/{$id}/{$id_category}";
        $this->_render('admin/competition/edit_category', $this->data);
    }

    public function save_edit_category($id = '', $id_category = '')
    {
        $this->data['id'] = $id;
        $this->data['competition'] = $this->mdl_competition->find($id) ?: redirect($this->base_url);
        $this->data['competition_category'] = $this->mdl_competition->findCategory($id_category) ?: redirect("{$this->base_url}/edit/{$id}");
        $base_rule = 'htmlentities|strip_tags|trim|xss_clean';
        $this->form_validation->set_rules('category_name', '', "{$base_rule}|required");
        if ($this->form_validation->run()) {
            $this->mdl_competition->updateCompetitionCategory([
                'category_name' => $this->input->post('category_name')
            ], $id_category);
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Kategori kompetisi berhasil diperbaharui.", "info")
            );
            redirect("{$this->base_url}/edit/{$id}");
        }
        else{
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
            );
            redirect("{$this->base_url}/editCategory/{$id}/{$id_category}");
        }
    }

    public function deleteCategory($id = '', $id_category = '')
    {
        $this->data['id'] = $id;
        $this->data['competition'] = $this->mdl_competition->find($id) ?: redirect($this->base_url);
        $this->data['competition_category'] = $this->mdl_competition->findCategory($id_category) ?: redirect("{$this->base_url}/edit/{$id}");

        $this->mdl_competition->deleteCompetitionCategory($id_category);

        $this->session->set_flashdata(
            'message',
            $this->global_lib->generateMessage("Kategori kompetisi telah dihapus.", "info")
        );
        redirect("{$this->base_url}/edit/{$id}");
    }

    private function _set_form_validation_rules()
    {
        $base_rule = 'htmlentities|strip_tags|trim|xss_clean';
        $this->form_validation->set_rules('title', '', "{$base_rule}|required");
        $this->form_validation->set_rules('competition_type', '', "{$base_rule}|required");
        $this->form_validation->set_rules('start_date', '', "{$base_rule}|required|callback__validateDate[d-m-Y]");
        $this->form_validation->set_rules('finish_date', '', "{$base_rule}|required|callback__validateDate[d-m-Y]");
        $this->form_validation->set_rules('max_content', '', "{$base_rule}|required|numeric|greater_than_equal_to[1]");
        $this->form_validation->set_rules('description', '', "{$base_rule}|required");
        $this->form_validation->set_rules('pic', '', "{$base_rule}");
        $this->form_validation->set_rules('cover_status', '', "{$base_rule}|integer");

    }

    public function _validateDate($date_text, $format)
    {
        $is_valid_date = $this->global_lib->validateDate($date_text, $format);
        if (!$is_valid_date) {
            $this->form_validation->set_message('_validateDate', 'The format of {field} must be ' . strtoupper($format));
        }

        return $is_valid_date;
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

    private function _get_input_data()
    {
        return [
            'title' => $this->input->post('title'),
            'competition_type' => $this->input->post('competition_type'),
            'start_date' => date_create($this->input->post('start_date'))->format('Y-m-d'),
            'finish_date' => date_create($this->input->post('finish_date'))->format('Y-m-d'),
            'max_content' => $this->input->post('max_content'),
            'description' => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('description')),
            'cover_status' => $this->input->post('cover_status'),
        ];
    }

    private function _is_competition_exists($start_date, $finish_date, $fail_redirect_url, $except_id = NULL)
    {
        if ($this->mdl_competition->has_been_used($start_date, $finish_date, $except_id)) {
            $this->session->set_flashdata(
                'message',
                $this->global_lib->generateMessage('Sudah terdapat Kompetisi pada rentang tanggal yang dimasukkan', 'danger')
            );
            redirect("{$this->base_url}/{$fail_redirect_url}");
        }
    }

    private function picUploadConfig($filename = '')
    {
        $config['upload_path'] = './assets/competition/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = '12000';
        $config['max_width'] = '8000';
        $config['max_height'] = '8000';
        if (strlen(trim($filename)) > 0) {
            $config['file_name'] = date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
        }
        return $config;
    }

    public function controller_url($uri = NULL)
    {
        return site_url("admin_competition/{$uri}");
    }
}
