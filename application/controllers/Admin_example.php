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

class Admin_example extends CI_Controller
{

    var $js = array();
    var $css = array();
    var $pagination_per_page = 20;
    var $pic_width = 960;
    var $pic_height = 400;
    var $pic_thumb_width = 320;
    var $pic_thumb_height = 200;
    var $allowed_format = 'doc|docx|pdf|ppt|pptx|txt|jpg|png|jpeg';
    var $module_name = 'admin_example';

    public function __construct()
    {
        parent::__construct();

        //load library..

        //load model..
        $this->load->model('mdl_example');

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
        $data['total_row'] = $this->mdl_example->getAllExampleCount();
        $config = $this->paginationConfig($data['total_row']);
        $this->pagination->initialize($config);

        $data['example'] = $this->mdl_example->getAllExampleLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));

        //load view all module
        $content = $this->load->view('admin/example/all', $data, true);

        $this->render($content);
    }

    public function add()
    {
        //clear search session yang lama..
        $this->clearSearchSession();

        $data = array();

        //load view add admin ...
        $content = $this->load->view('admin/example/add', $data, true);

        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->render($content);
    }

    public function add_test()
    {
        //clear search session yang lama..
        $this->clearSearchSession();

        $data = array();

        //load view add admin ...
        $content = $this->load->view('admin/example/add_test', $data, true);
        $this->render($content);
    }

    public function saveAdd()
    {
        $this->form_validation->set_rules('example_text', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_textarea', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_texteditor', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_file', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_pic', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->s2et_rules('example_pic2', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_select_nosearch', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_select_withsearch', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_radio', '', 'htmlentities|strip_tags|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('admin_example/add');
        }
        else {
            // --- START uploading file (non picture)
            $example_file = '';
            if (!empty($_FILES['example_file']['name'])) {
                $config = $this->exampleFileUploadConfig($_FILES['example_file']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('example_file')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $example_file = $upload_data['upload_data']['file_name'];
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_example/add');
                }
            }
            //uncomment this section if you want force user upload photo
            /*
            else{
              $message =  $this->global_lib->generateMessage("You must upload a file.", "danger");
              $this->session->set_flashdata('message', $message);
              redirect('admin_example/add');
            }
            */
            // --- END uploading picture and generate thumbnail

            // --- START uploading picture without thumbnail
            $example_pic = '';
            if (!empty($_FILES['example_pic']['name'])) {
                $config = $this->examplePicUploadConfig($_FILES['example_pic']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('example_pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $example_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_example/add');
                }
            }
            //uncomment this section if you want force user upload photo
            /*
            else{
              $message = $this->global_lib->generateMessage("You must upload a file for picture", "danger");
              $this->session->set_flashdata('message', $message);
              redirect('admin_example/add');
            }
            */
            // --- END uploading picture without thumbnail

            // --- START uploading picture and generate thumbnail
            $example_pic2 = '';
            $example_pic2_thumb = '';
            if (!empty($_FILES['example_pic2']['name'])) {
                $config = $this->examplePicUploadConfig($_FILES['example_pic2']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('example_pic2')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $example_pic2 = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
                    $example_pic2_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'], $this->pic_thumb_width, $this->pic_thumb_height);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_example/add');
                }
            }
            //uncomment this section if you want force user upload photo
            /*
            else{
              $message = $this->global_lib->generateMessage("You must upload a file for picture", "danger");
              $this->session->set_flashdata('message', $message);
              redirect('admin_example/add');
            }
            */
            // --- END uploading picture and generate thumbnail

            //Datepicker example..
            $example_datepicker = null;
            $example_datepicker = $this->input->post('example_datepicker');
            $is_valid_date = $this->global_lib->validateDate($example_datepicker, 'd-m-Y');
            if ($is_valid_date) {
                $example_datepicker = $this->global_lib->formatDate($example_datepicker, 'd-m-Y', 'Y-m-d');
            }

            //insert data ke database..
            $insert_data = array(
                "example_text" => $this->input->post('example_text'),
                "example_textarea" => $this->input->post('example_textarea'),
                "example_texteditor" => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('example_texteditor')),
                "example_select_nosearch" => $this->input->post('example_select_nosearch'),
                "example_select_withsearch" => $this->input->post('example_select_withsearch'),
                "example_radio" => $this->input->post('example_radio'),
                "example_file" => $example_file,
                "example_pic" => $example_pic,
                "example_pic2" => $example_pic2,
                "example_pic2_thumb" => $example_pic2_thumb
            );
            $this->mdl_example->insertExample($insert_data);

            $message = $this->global_lib->generateMessage("New example has been added.", "info");
            $this->session->set_flashdata('message', $message);
            redirect('admin_example/index');
        }
    }

    public function saveAddTest()
    {
        // --- START uploading file (non picture)
        $example_file = '';
        if (!empty($_FILES['example_file']['name'])) {
            $config = $this->exampleFileUploadConfig($_FILES['example_file']['name']);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('example_file')) {
                $upload_data = array('upload_data' => $this->upload->data());
                $example_file = $upload_data['upload_data']['file_name'];

                print_r("<pre>");
                print_r($upload_data);
                print_r("</pre>");
                die();
            }
            else {
                echo "Failed to upload file. <br/> cause: " . $this->upload->display_errors();
            }
        }
    }

    public function edit($id_example = '')
    {
        //clear search session yang lama..
        $this->clearSearchSession();

        //ambil data example yang akan diedit.
        $data['example'] = $this->mdl_example->getExampleByID($id_example);
        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['example'])) || count($data['example']) < 1) {
            redirect('admin_example/index');
        }

        //load view edit admin ...
        $content = $this->load->view('admin/example/edit', $data, true);

        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->render($content);
    }

    public function saveEdit($id_example = '')
    {
        //ambil data example yang akan diedit.
        $data['example'] = $this->mdl_example->getExampleByID($id_example);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['example'])) || count($data['example']) < 1) {
            redirect('admin_example/index');
        }

        //validasi input
        $this->form_validation->set_rules('example_text', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_textarea', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_texteditor', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_file', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_pic', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_pic2', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_select_nosearch', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_select_withsearch', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_radio', '', 'htmlentities|strip_tags|trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('admin_example/edit/' . $id_example);
        }
        else {
            // --- START uploading file (non picture)
            $example_file = '';
            if (!empty($_FILES['example_file']['name'])) {
                $config = $this->exampleFileUploadConfig($_FILES['example_file']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('example_file')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $example_file = $upload_data['upload_data']['file_name'];
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload file. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_example/edit/' . $id_example);
                }
            }
            // --- END uploading picture and generate thumbnail

            // --- START uploading picture without thumbnail
            $example_pic = '';
            if (!empty($_FILES['example_pic']['name'])) {
                $config = $this->examplePicUploadConfig($_FILES['example_pic']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('example_pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $example_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_example/edit/' . $id_example);
                }
            }
            // --- END uploading picture without thumbnail

            // --- START uploading picture and generate thumbnail
            $example_pic2 = '';
            $example_pic2_thumb = '';
            if (!empty($_FILES['example_pic2']['name'])) {
                $config = $this->examplePicUploadConfig($_FILES['example_pic2']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('example_pic2')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $example_pic2 = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
                    $example_pic2_thumb = $this->picture->createThumb($upload_data['upload_data']['full_path'], $this->pic_thumb_width, $this->pic_thumb_height);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_example/edit/' . $id_example);
                }
            }
            // --- END uploading picture and generate thumbnail

            // update data admin ke database..
            $update_data = array(
                "example_text" => $this->input->post('example_text'),
                "example_textarea" => $this->input->post('example_textarea'),
                "example_texteditor" => str_replace(base_url() . "assets/content/", "##BASE_URL##", $this->input->post('example_texteditor')),
                "example_select_nosearch" => $this->input->post('example_select_nosearch'),
                "example_select_withsearch" => $this->input->post('example_select_withsearch'),
                "example_radio" => $this->input->post('example_radio')
            );
            if (strlen(trim($example_file)) > 0) {
                $update_data['example_file'] = $example_file;
            }
            if (strlen(trim($example_pic)) > 0) {
                $update_data['example_pic'] = $example_pic;
            }
            if (strlen(trim($example_pic2)) > 0) {
                $update_data['example_pic2'] = $example_pic2;
                $update_data['example_pic2_thumb'] = $example_pic2_thumb;
            }
            $this->mdl_example->updateExample($update_data, $id_example);

            $message = $this->global_lib->generateMessage("Example has been updated.", "info");
            $this->session->set_flashdata('message', $message);
            redirect('admin_example/edit/' . $id_example);
        }
    }

    public function delete($id_example = '')
    {
        //ambil data example yang akan diedit.
        $data = $this->mdl_example->getExampleByID($id_example);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('admin_example/index');
        }

        if (isset($data[0]->example_pic) && strlen(trim($data[0]->example_pic)) > 0) {
            @unlink('assets/example/' . $data[0]->example_pic);
        }
        if (isset($data[0]->example_pic2) && strlen(trim($data[0]->example_pic2)) > 0) {
            @unlink('assets/example/' . $data[0]->example_pic2);
        }
        if (isset($data[0]->example_pic2_thumb) && strlen(trim($data[0]->example_pic2_thumb)) > 0) {
            @unlink('assets/example/thumb/' . $data[0]->example_pic2_thumb);
        }

        $this->mdl_example->deleteExample($id_example);

        $message = $this->global_lib->generateMessage("Example has been deleted.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('admin_example/index');
    }

    public function deletePic($id_example = '')
    {
        //ambil data admin yang akan diedit.
        $data = $this->mdl_example->getExampleByID($id_example);
        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('admin_example/index');
        }

        //jika tidak ada redirect ke halaman edit
        if ((!isset($data[0]->example_pic2)) || strlen(trim($data[0]->example_pic2)) == 0) {
            redirect('admin_example/edit/' . $id_example);
        }
        else {
            $update_data = array(
                'example_pic2' => "",
                'example_pic2_thumb' => "",
            );
            $this->mdl_example->updateExample($update_data, $id_example);

            //hapus file.
            @unlink('assets/example/' . $data[0]->example_pic2);
            @unlink('assets/example/thumb/' . $data[0]->example_pic2_thumb);
            redirect("admin_example/edit/" . $id_example);
        }
    }

    public function submitSearch()
    {
        //validasi input..
        $this->form_validation->set_rules('search_by', '', 'htmlentities|strip_tags|trim|required|xss_clean');
        $this->form_validation->set_rules('operator', '', 'htmlentities|strip_tags|required|trim|xss_clean');
        $this->form_validation->set_rules('keyword', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('per_page', '', 'htmlentities|strip_tags|required|trim|xss_clean');
        $this->form_validation->set_rules('sort_by', '', 'htmlentities|strip_tags|required|trim|xss_clean');
        $this->form_validation->set_rules('example_radio', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('example_select_withsearch', '', 'htmlentities|strip_tags|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $message = $this->global_lib->generateMessage(validation_errors(), "info");
            $this->session->set_flashdata('message', $message);
            redirect('admin_example/index');
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
                'example_select_withsearch' => $this->input->post('example_select_withsearch'),
                'example_radio' => $this->input->post('example_radio'),
                'search_collapsed' => $this->input->post('search_collapsed')
            );
            $this->session->set_userdata('search_example', $search_param);

            redirect('admin_example/search');
        }
    }

    public function search()
    {
        // ambil parameter search di session..
        $search_param = $this->session->userdata('search_example');

        // ================= Syncronize parameter dengan field di database =====================//
        $operator_list = array('like', 'not like');
        $field_list = array('example_text', 'example_textarea');
        $sort_by_list = array(
            'default' => 'id_example DESC',
            'newest' => 'id_example DESC',
            'oldest' => 'id_example ASC',
            'name_asc' => 'example_text ASC',
            'name_desc' => 'example_text DESC'
        );
        // ======================================================================================//

        // ========================== Validasi parameter2 searching =============================//
        // validasi search by..
        if (!in_array($search_param['search_by'], $field_list)) {
            redirect('admin_example/index');
        }

        //validasi operator..
        if (!in_array($search_param['operator'], $operator_list)) {
            redirect('admin_example/index');
        }

        //validasi sort_by..
        $sort_by = $sort_by_list[$search_param['sort_by']];
        if ($sort_by == '' || $sort_by == null) {
            redirect('admin_example/index');
        }
        //ganti search_by (field alias) dengan nama field..
        $search_param['sort_by'] = $sort_by;

        //validasi per page..
        $per_page = $search_param['per_page'];
        if ($per_page <= 0) {
            redirect('admin_example/index');
        }
        // =========================================================================================//

        //ambil parameter2 dan hasil pencarian..
        $data['total_row'] = $this->mdl_example->getSearchResultCount($search_param);
        $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
        $this->pagination->initialize($config);

        $data['example'] = $this->mdl_example->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));

        //load view search result..
        $content = $this->load->view('admin/example/all', $data, true);

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
            $data['modules'] = $this->global_lib->generateAdminModule();

            //load view template
            $this->load->view('/admin/template', $data);
        }
        else {
            redirect('page/index');
        }
    }

    private function exampleFileUploadConfig($filename = '')
    {
        $config['upload_path'] = './assets/test/';
        // $config['upload_path'] = '../../../data1/hypeabis/test/';
        $config['allowed_types'] = $this->allowed_format;
        $config['max_size'] = '12000';
        $config['max_width'] = '8000';
        $config['max_height'] = '8000';
        if (strlen(trim($filename)) > 0) {
            $config['file_name'] = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
        }
        return $config;
    }

    private function examplePicUploadConfig($filename = '')
    {
        $config['upload_path'] = './assets/example/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
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
        $config['base_url'] = base_url() . 'admin_example/index/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->pagination_per_page;
        $config['uri_segment'] = 3;
        return $config;
    }

    private function searchPaginationConfig($total_row, $per_page)
    {
        $config = $this->global_lib->paginationConfigAdmin();
        $config['base_url'] = base_url() . 'admin_example/search/';
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
            'example_select_withsearch' => 'all',
            'example_radio' => 'all',
            'search_collapsed' => '1'
        );
        $this->session->set_userdata('search_example', $search_param);
    }
}
