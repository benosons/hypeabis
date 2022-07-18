<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User_gallery extends CI_Controller
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
        $this->load->model('mdl_user');
        $this->load->model('mdl_gallery');
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
        $data['total_row'] = $this->mdl_gallery->getAllGalleryCount();
        $config = $this->paginationConfig($data['total_row']);
        $this->pagination->initialize($config);

        $data['gallery'] = $this->mdl_gallery->getAllGalleryLimit($id_user, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));


        //load view.
        $content = $this->load->view('user/gallery/all', $data, true);
        $this->render($content);
    }

    public function add()
    {
        //clear search session yang lama
        $this->clearSearchSession();

        $data['competitions'] = $this->mdl_competition->get_active('article');
        $data['competition_category'] = [];
        if(isset($data['competitions']) && count($data['competitions']) > 0) {
            $data['competition_category'] = $this->mdl_competition->getCategory($data['competitions'][0]->id_competition);
        }
        //load view add admin
        $content = $this->load->view('user/gallery/add', $data, true);

        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>';
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
        $this->render($content);
    }

    public function saveAdd()
    {
        $isPreview = !is_null($this->input->post('preview'));
        $is_verified_member = $this->session->userdata('user_verified') === '1';


        $this->form_validation->set_rules('title', 'Judul', 'htmlentities|strip_tags|trim|required|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('statuss', 'Status', 'htmlentities|strip_tags|trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Validasi form gagal. Mohon isi form gallery dengan format yang benar dan lengkap." . validation_errors(), "danger");
            $this->session->set_flashdata('message', $message);
            redirect('user_gallery/add');
        }
        else {
            
            $now = date("Y-m-d H:i:s");
            //upload content picture
            $content_pic = '';
            if (!empty($_FILES['file_pic']['name'])) {
                $config = $this->contentPicUploadConfig($_FILES['file_pic']['name']);
                $this->upload->initialize($config);

                //upload file article..
                if ($this->upload->do_upload('file_pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $content_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
                    }
                else {
                    $message = $this->global_lib->generateMessage("Gagal mengupload gambar gallery. <br/>" . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('user_gallery/add');
                }
            }

            $file_galeri = '';
            $virtual_galeri = '';
            if (!empty($_FILES['file_gallery']['name'])) {
                $config = $this->contentPicUploadConfig($_FILES['file_gallery']['name']);
                $this->upload->initialize($config);

                //upload file article..
                
                if ($this->upload->do_upload('file_gallery')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $file_galeri = $upload_data['upload_data']['full_path'];

                    $zip = new ZipArchive;
                    $info = pathinfo($_FILES['file_gallery']['name']);
                    
                    if ($zip->open($upload_data['upload_data']['full_path']) === TRUE) {
                        if (!file_exists(dirname(__FILE__, 3)."./virtual/".date("Y/m/d")."/".$_FILES['file_gallery']['size']."/")) {
                            mkdir(dirname(__FILE__, 3)."./virtual/".date("Y/m/d")."/".$_FILES['file_gallery']['size']."/", 0777, true);
                        }

                        $zip->extractTo(dirname(__FILE__, 3)."./virtual/".date("Y/m/d")."/".$_FILES['file_gallery']['size']."/");
                        $zip->close();
                        $virtual_galeri = dirname(__FILE__, 3)."./virtual/".date("Y/m/d")."/".$_FILES['file_gallery']['size']."/".$info['filename'];
                    }

                }
                else {
                    $message = $this->global_lib->generateMessage("Gagal mengupload file gallery. <br/>" . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('user_gallery/add');
                }
            }

            //insert data ke database
            $insert_data = [
                
                // 'id_user' => $this->session->userdata('id_user'),
                'judul_galeri' => $this->input->post('title'),
                'deskripsi' => $this->input->post('deskripsi'),
                'link_galeri' => $this->input->post('link'),
                'thumb_galeri' => $content_pic,
                'file_galeri' => $file_galeri,
                'virtual_galeri' => $virtual_galeri,
                'statuss' => $this->input->post('statuss'),
                'tgl_submit' => $now
            ];
            
            $proses = $this->mdl_gallery->insertGalleri($insert_data);

         
            if ($proses) {
                $message = 'Gallery anda berhasil disimpan.';

            }

            $this->session->set_userdata('is_admin_dashboard', false);
            $this->session->set_flashdata('message', $message);
            redirect('user_gallery/index');
            
        }
    }


    public function edit($id_content = '')
    {
        $id_user = $this->session->userdata('id_user');
        //clear search session yang lama
        $this->clearSearchSession();

        $data['user_name'] = $this->session->userdata('user_name');
        //ambil data content yang akan diedit.
        $data['content'] = $this->mdl_gallery->getGalleriByIDAndIDUser($id_content);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['content'])) || count($data['content']) < 1) {
            redirect('user_gallery/index');
        }

        //load view edit admin
        $content = $this->load->view('user/gallery/edit', $data, true);
        array_push($this->js, '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>'); //ckeditor
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
        $this->render($content);
    }

    public function saveEdit($id_content = '')
    {
        $id_user = $this->session->userdata('id_user');
        $user_name = $this->session->userdata('user_name');

        //ambil data content yang akan diedit.
        $data['content'] = $this->mdl_gallery->getGalleriByIDAndIDUser($id_content);
        if ((!is_array($data['content'])) || count($data['content']) < 1) {
            redirect('user_gallery/index');
        }


        //validasi input
        $this->form_validation->set_rules('title', 'Judul', 'htmlentities|strip_tags|trim|required|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'htmlentities|strip_tags|trim|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('statuss', 'Status', 'htmlentities|strip_tags|trim|xss_clean');


        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Validasi form gagal. Mohon isi form gallery dengan format yang benar dan lengkap.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('user_gallery/edit/' . $id_content);
        }
        else {

            //upload content picture
            
            $content_pic = '';
            if (!empty($_FILES['file_pic']['name'])) {
                $config = $this->contentPicUploadConfig($_FILES['file_pic']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('file_pic')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $content_pic = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Gagal mengupload gambar gallery. <br/>" . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('user_gallery/edit/' . $id_content);
                }
            }

            $file_galery = '';
            if (!empty($_FILES['file_galery']['name'])) {
                $config = $this->contentPicUploadConfig($_FILES['file_galery']['name']);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('file_galery')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $file_galery = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->pic_width, $this->pic_height, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Gagal mengupload file gallery. <br/>" . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('user_gallery/edit/' . $id_content);
                }
            }

            // update data admin ke database..
            $update_data = array(
                'judul_galeri' => $this->input->post('title'),
                'deskripsi' => $this->input->post('deskripsi'),
                'statuss' => $this->input->post('statuss')
            );

            if($this->input->post('link')){
                $update_data['link_galeri'] = $this->input->post('link');
            }
            
            if($content_pic){
                $update_data['thumb_galeri'] = $content_pic;
            }

            if($file_galery){
                $update_data['file_galeri'] = $file_galery;
            }

            if (strlen(trim($content_pic)) > 0) {
                $update_data['thumb_galeri'] = $content_pic;
            }
            if (strlen(trim($file_galery)) > 0) {
                $update_data['file_galeri'] = $file_galery;
            }
            
            $edit = $this->mdl_gallery->updateGaleri($update_data, $id_content);

            if ($edit) {
                $message = 'Gallery anda berhasil disimpan.';

            }

            $this->session->set_userdata('is_admin_dashboard', false);
            $this->session->set_flashdata('message', $message);
            redirect('user_gallery/index');ct("read/{$id_content}-" . strtolower(url_title($this->session->userdata('user_name'))) . '/' . strtolower(url_title($this->input->post('title'))));
        
        }
    }



    public function delete($id_content = '')
    {

        //ambil data content yang akan diedit.
        $data = $this->mdl_gallery->getGalleriByIDAndIDUser($id_content);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('user_gallery/index');
        }

        if (isset($data[0]->thumb_galeri) && strlen(trim($data[0]->thumb_galeri)) > 0) {
            @unlink('assets/content/' . $data[0]->thumb_galeri);
        }

        if (isset($data[0]->file_galeri) && strlen(trim($data[0]->file_galeri)) > 0) {
            @unlink('assets/content/' . $data[0]->file_galeri);
        }

        $this->mdl_gallery->deleteGaleri($id_content);



        $message = $this->global_lib->generateMessage('Gallery berhasil dihapus', "info");
        $this->session->set_flashdata('message', $message);
        redirect('user_gallery/index');
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
        $this->form_validation->set_rules('category', '', 'htmlentities|strip_tags|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $message = $this->global_lib->generateMessage(validation_errors(), "info");
            $this->session->set_flashdata('message', $message);
            redirect('user_gallery/index');
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
                'start_date' => $this->input->post('start_date'),
                'finish_date' => $this->input->post('finish_date'),
                'search_collapsed' => $this->input->post('search_collapsed')
            );
            $this->session->set_userdata('search_content', $search_param);

            redirect('user_gallery/search');
        }
    }

    public function search()
    {
        $id_user = $this->session->userdata('id_user');

        // ambil parameter search di session..
        $search_param = $this->session->userdata('search_content');

        // ================= Syncronize parameter dengan field di database =====================//
        $operator_list = array('like', 'not like');
        $field_list = array('judul_galeri', 'deskripsi');
        $sort_by_list = array(
            'default' => 'id_galeri DESC',
            'newest' => 'id_galeri DESC',
            'oldest' => 'id_galeri ASC',
            'title_asc' => 'judul_galeri ASC',
            'title_desc' => 'judul_galeri DESC'
        );
        // ======================================================================================//

        // ========================== Validasi parameter2 searching =============================//
        // validasi search by..
        if (!in_array($search_param['search_by'], $field_list)) {
            redirect('user_gallery/index');
        }

        //validasi operator..
        if (!in_array($search_param['operator'], $operator_list)) {
            redirect('user_gallery/index');
        }

        //validasi sort_by..
        $sort_by = $sort_by_list[$search_param['sort_by']];
        if ($sort_by == '' || $sort_by == null) {
            redirect('user_gallery/index');
        }
        //ganti search_by (field alias) dengan nama field..
        $search_param['sort_by'] = $sort_by;

        //validasi per page..
        $per_page = $search_param['per_page'];
        if ($per_page <= 0) {
            redirect('user_gallery/index');
        }
        // =========================================================================================//

        //ambil parameter2 dan hasil pencarian..
        $data['total_row'] = $this->mdl_gallery->getSearchResultByIDUserCount($search_param);
        $config = $this->paginationConfig($data['total_row']);
        $this->pagination->initialize($config);

        $data['content'] = $this->mdl_gallery->getSearchResultByIDUser($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));


        //load view search result..
        $content = $this->load->view('user/gallery/all', $data, true);

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



    private function contentPicUploadConfig($filename = '')
    {
        $config['upload_path'] = './assets/content/';
        $config['extract_path'] = './assets/content/virtual/';
        $config['allowed_types'] = 'jpg|jpeg|JPG|JPEG|zip|rar';
        $config['max_size'] = '100000';
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
        $config['base_url'] = base_url() . 'user_gallery/index/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->pagination_per_page;
        $config['uri_segment'] = 3;
        return $config;
    }

    private function searchPaginationConfig($total_row, $per_page)
    {
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url() . 'user_gallery/search/';
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


}
