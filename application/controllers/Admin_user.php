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

class Admin_user extends CI_Controller
{

    var $js = array();
    var $css = array();
    var $pagination_per_page = 20;
    var $user_pic_width = 300;
    var $user_pic_height = 300;
    var $module_name = 'admin_user';

    public function __construct()
    {
        parent::__construct();

        //load library

        //load model
        $this->load->model('mdl_user');
        $this->load->model('mdl_job');
        $this->load->model('mdl_jobfield');
        $this->load->model('mdl_interest');

        //construct script
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
        $data['total_row'] = $this->mdl_user->getAllUserCount();
        $config = $this->paginationConfig($data['total_row']);
        $this->pagination->initialize($config);

        $data['user'] = $this->mdl_user->getAllUserLimit($config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));

        //load view all module
        $content = $this->load->view('admin/user/all', $data, true);

        $this->render($content);
    }

    public function searchUserByKeyword()
    {
        //cek apakah ajax request atau bukan..
        if (!$this->input->is_ajax_request()) {
            redirect('admin_user');
        }
        $q = preg_replace("/[^a-zA-Z0-9-]+/", "", $this->input->get('q'));

        //ambil user by keyword
        $users = $this->mdl_user->getUserByKeywordForSelect2($q);

        $option = array();
        foreach ($users as $item) {
            $id = $item->id_user;
            $text = $item->name . ' (' . $item->email . ')';
            array_push($option, array('id' => $id, 'text' => $text));
        }
        echo json_encode($option);
    }

    public function add()
    {
        //clear search session yang lama..
        $this->clearSearchSession();

        $data['job'] = $this->mdl_job->getAllJob();
        $data['jobfield'] = $this->mdl_jobfield->getAllJobfield();
        $data['interest'] = $this->mdl_interest->getAllInterest();

        //load view add admin ...
        $content = $this->load->view('admin/user/add', $data, true);
        $this->render($content);
    }

    public function saveAdd()
    {
        $this->form_validation->set_rules('name', '', 'htmlentities|strip_tags|trim|xss_clean|required');
        $this->form_validation->set_rules('profile_desc', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('email', '', 'htmlentities|strip_tags|trim|xss_clean|valid_email|required');
        $this->form_validation->set_rules('gender', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('contact_number', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('tempat_lahir', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('dob', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('address', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('password', '', 'htmlentities|strip_tags|trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password', '', 'htmlentities|strip_tags|trim|required|xss_clean|matches[password]');
        $this->form_validation->set_rules('picture', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('facebook', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('twitter', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('instagram', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_job', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('id_jobfield', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('id_interest', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('status', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('verified', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('confirm_email', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('is_internal', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('admin_user/add');
        }
        else {
            //check email sudah ada yang pakai / belum.
            $email = $this->input->post('email');
            $check = $this->mdl_user->checkUserByEmail($email);
            if ($check) {
                $message = $this->global_lib->generateMessage("This email already use. Please use another email.", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('admin_user/add');
            }

            // --- START uploading picture without thumbnail
            $picture = '';
            if (!empty($_FILES['picture']['name'])) {
                $config = $this->userPicUploadConfig($_FILES['picture']['name']);
                $this->upload->initialize($config);

                //upload file article..
                if ($this->upload->do_upload('picture')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $picture = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->user_pic_width, $this->user_pic_height, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_user/add');
                }
            }

            $now = date('Y-m-d H:i:s');
            $hash = $this->global_lib->generateHash();

            $dob = null;
            $dob = $this->input->post('dob');
            $is_valid_date = $this->global_lib->validateDate($dob, 'd-m-Y');
            if ($is_valid_date) {
                $dob = $this->global_lib->formatDate($dob, 'd-m-Y', 'Y-m-d');
            }

            //insert data ke database..
            $insert_data = array(
                "oauth_provider" => "web|",
                "name" => $this->input->post('name'),
                "profile_desc" => $this->input->post('profile_desc'),
                "email" => $this->input->post('email'),
                "tempat_lahir" => $this->input->post('tempat_lahir'),
                "dob" => $dob,
                "contact_number" => $this->input->post('contact_number'),
                "address" => $this->input->post('address'),
                'password' => sha1($this->input->post('password') . $this->config->item('binary_salt')),
                "gender" => $this->input->post('gender'),
                "facebook" => str_replace('http://', '', str_replace('https://', '', $this->input->post('facebook'))),
                "twitter" => str_replace('http://', '', str_replace('https://', '', $this->input->post('twitter'))),
                "instagram" => str_replace('http://', '', str_replace('https://', '', $this->input->post('instagram'))),
                "id_job" => $this->input->post('id_job'),
                "id_jobfield" => $this->input->post('id_jobfield'),
                "id_interest" => $this->input->post('id_interest'),
                "status" => $this->input->post('status'),
                "verified" => $this->input->post('verified'),
                "confirm_email" => $this->input->post('confirm_email'),
                "is_internal" => $this->input->post('is_internal'),
                "created" => $now,
                "modified" => $now,
                "picture" => $picture,
                "hash" => $hash
            );
            $this->mdl_user->insertUser($insert_data);

            $message = $this->global_lib->generateMessage("New user has been added.", "info");
            $this->session->set_flashdata('message', $message);
            redirect('admin_user/index');
        }
    }

    public function edit($id_user = '')
    {
        $this->load->library('frontend_lib');

        //clear search session yang lama..
        $this->clearSearchSession();

        //ambil data user yang akan diedit.
        $data['user'] = $this->mdl_user->getUserByID($id_user);
        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['user'])) || count($data['user']) < 1) {
            redirect('admin_user/index');
        }

        $data['job'] = $this->mdl_job->getAllJob();
        $data['jobfield'] = $this->mdl_jobfield->getAllJobfield();
        $data['interest'] = $this->mdl_interest->getAllInterest();

        //load view edit admin ...
        $content = $this->load->view('admin/user/edit', $data, true);
        $this->render($content);
    }

    public function saveEdit($id_user = '')
    {
        //ambil data user yang akan diedit.
        $data['user'] = $this->mdl_user->getUserByID($id_user);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['user'])) || count($data['user']) < 1) {
            redirect('admin_user/index');
        }

        //validasi input
        $this->form_validation->set_rules('name', '', 'htmlentities|strip_tags|trim|xss_clean|required');
        $this->form_validation->set_rules('profile_desc', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('email', '', 'htmlentities|strip_tags|trim|xss_clean|valid_email|required');
        $this->form_validation->set_rules('gender', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('contact_number', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('tempat_lahir', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('dob', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('address', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('picture', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('facebook', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('twitter', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('instagram', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_job', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('id_jobfield', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('id_interest', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('status', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('verified', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('confirm_email', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('is_internal', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('admin_user/edit/' . $id_user);
        }
        else {
            //jika email di update, check email masih available atau tidak di database..
            $email = $this->input->post('email');
            if (strtolower($data['user'][0]->email) != strtolower($email)) {
                $check = $this->mdl_user->checkUserByEmail($email);
                if ($check) {
                    $message = $this->global_lib->generateMessage("This email already use. Please use another email.", "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_user/edit/' . $id_user);
                }
            }

            // --- START uploading picture without thumbnail
            $picture = '';
            if (!empty($_FILES['picture']['name'])) {
                $config = $this->userPicUploadConfig($_FILES['picture']['name']);
                $this->upload->initialize($config);

                //upload file article..
                if ($this->upload->do_upload('picture')) {
                    $upload_data = array('upload_data' => $this->upload->data());
                    $picture = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->user_pic_width, $this->user_pic_height, true);
                }
                else {
                    $message = $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_user/edit/' . $id_user);
                }
            }

            $now = date('Y-m-d H:i:s');

            $dob = null;
            $dob = $this->input->post('dob');
            $is_valid_date = $this->global_lib->validateDate($dob, 'd-m-Y');
            if ($is_valid_date) {
                $dob = $this->global_lib->formatDate($dob, 'd-m-Y', 'Y-m-d');
            }

            // update data admin ke database..
            $update_data = array(
                "name" => $this->input->post('name'),
                "profile_desc" => $this->input->post('profile_desc'),
                "email" => $this->input->post('email'),
                "tempat_lahir" => $this->input->post('tempat_lahir'),
                "dob" => $dob,
                "contact_number" => $this->input->post('contact_number'),
                "address" => $this->input->post('address'),
                "gender" => $this->input->post('gender'),
                "facebook" => str_replace('http://', '', str_replace('https://', '', $this->input->post('facebook'))),
                "twitter" => str_replace('http://', '', str_replace('https://', '', $this->input->post('twitter'))),
                "instagram" => str_replace('http://', '', str_replace('https://', '', $this->input->post('instagram'))),
                "id_job" => $this->input->post('id_job'),
                "id_jobfield" => $this->input->post('id_jobfield'),
                "id_interest" => $this->input->post('id_interest'),
                "status" => $this->input->post('status'),
                "verified" => $this->input->post('verified'),
                "confirm_email" => $this->input->post('confirm_email'),
                "is_internal" => $this->input->post('is_internal'),
                "modified" => $now
            );
            if (strlen(trim($picture)) > 0) {
                $update_data['picture'] = $picture;
            }
            $this->mdl_user->updateUser($update_data, $id_user);

            //jika status dari tidak aktif -> aktif, kirim email notifikasi
            $confirm_email = $this->input->post('confirm_email');
            if ($data['user'][0]->confirm_email != $confirm_email && $confirm_email == '1') {
                //kirim email konfirmasi
                $this->load->library('email_lib');
                $config = array(
                    'email' => $this->input->post('email'),
                );
                $send_email = $this->email_lib->accountActivated($config, false);
                //d($data['user']);
                //dd($confirm_email);
            }

            $message = $this->global_lib->generateMessage("User account has been updated.", "info");
            $this->session->set_flashdata('message', $message);
            redirect('admin_user/edit/' . $id_user);
        }
    }

    public function updatePassword($id_user = '')
    {
        //ambil data user yang akan diedit.
        $data['user'] = $this->mdl_user->getUserByID($id_user);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['user'])) || count($data['user']) < 1) {
            redirect('admin_user/index');
        }

        //jika password baru diisi, validasi form ganti password..
        if (strlen($this->input->post('new_password')) > 0) {
            // $this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|required|xss_clean');
            $this->form_validation->set_rules('new_password', '', 'htmlentities|strip_tags|trim|required|xss_clean');
            $this->form_validation->set_rules('confirm_password', '', 'htmlentities|strip_tags|trim|required|xss_clean|matches[new_password]');
            if ($this->form_validation->run() == FALSE) {
                $message = $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('admin_user/edit/' . $id_user);
            }
            else {
                //cek password
                // $old_password = sha1($this->input->post('password') . $this->config->item('binary_salt'));
                // $check_password = $this->mdl_user->checkUserByIDAndPassword($id_user, $old_password);
                $check_password = true;

                //jika password salah, redirect ke halaman edit..
                if (!$check_password) {
                    $message = $this->global_lib->generateMessage("Wrong password. You must input correct password to change this user password.", "danger");
                    $this->session->set_flashdata('message', $message);
                    redirect('admin_user/edit/' . $id_user);
                }

                $update_data = array();
                $update_data['password'] = sha1($this->input->post('new_password') . $this->config->item('binary_salt'));
                $this->mdl_user->updateUser($update_data, $id_user);

                $message = $this->global_lib->generateMessage("Password berhasil diupdate.", "info");
                $this->session->set_flashdata('message', $message);
                redirect('admin_user/edit/' . $id_user);
            }
        }
        else {
            redirect('admin_user/edit/' . $id_user);
        }
    }

    public function resetPassword($id_user = '')
    {
        //ambil data user yang akan diedit.
        $data['user'] = $this->mdl_user->getUserByID($id_user);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['user'])) || count($data['user']) < 1) {
            redirect('admin_user/index');
        }

        //generate 6 character untuk password..
        $hash = $this->global_lib->generateHash($data['user'][0]->id_user . date('YmdHis'));
        $new_password = strtoupper(substr($hash, 0, 8));

        $update_data = array(
            'password' => sha1($new_password . $this->config->item('binary_salt'))
        );
        $this->mdl_user->updateUser($update_data, $data['user'][0]->id_user);

        //kirim email konfirmasi reset password
        $this->load->library('email_lib');
        $config = array(
            'new_password' => $new_password,
            'email' => $data['user'][0]->email,
        );
        $send_email = $this->email_lib->resetPassword($config, false);

        $message = $this->global_lib->generateMessage("User password has been reset. Please check email to get the new password.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('admin_user/edit/' . $id_user);
    }

    public function delete($id_user = '')
    {
        //ambil data user yang akan diedit.
        $data = $this->mdl_user->getUserByID($id_user);

        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('admin_user/index');
        }

        // $this->mdl_user->deleteUser($id_user);
        $this->mdl_user->updateUser(array('deleted' => 1), $id_user);

        $message = $this->global_lib->generateMessage("User has been deleted.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('admin_user/index');
    }

    public function deletePic($id_user = '')
    {
        //ambil data admin yang akan diedit.
        $data = $this->mdl_user->getUserByID($id_user);
        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('admin_user/index');
        }

        //jika tidak ada redirect ke halaman edit
        if ((!isset($data[0]->picture)) || strlen(trim($data[0]->picture)) == 0) {
            redirect('admin_user/edit/' . $id_user);
        }
        else {
            $update_data = array(
                'picture' => ""
            );
            $this->mdl_user->updateUser($update_data, $id_user);

            //hapus file.
            @unlink('assets/user/' . $data[0]->picture);
            redirect("admin_user/edit/" . $id_user);
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
        $this->form_validation->set_rules('status', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('confirm_email', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('is_internal', '', 'htmlentities|strip_tags|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $message = $this->global_lib->generateMessage(validation_errors(), "info");
            $this->session->set_flashdata('message', $message);
            redirect('admin_user/index');
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
                'confirm_email' => $this->input->post('confirm_email'),
                'is_internal' => $this->input->post('is_internal'),
                'status' => $this->input->post('status'),
                'search_collapsed' => $this->input->post('search_collapsed')
            );
            $this->session->set_userdata('search_user', $search_param);

            redirect('admin_user/search');
        }
    }

    public function search()
    {
        // ambil parameter search di session..
        $search_param = $this->session->userdata('search_user');

        // ================= Syncronize parameter dengan field di database =====================//
        $operator_list = array('like', 'not like');
        $field_list = array('name', 'email');
        $sort_by_list = array(
            'default' => 'id_user DESC',
            'newest' => 'id_user DESC',
            'oldest' => 'id_user ASC',
            'name_asc' => 'name ASC',
            'name_desc' => 'name DESC',
            'article_desc' => 'content_count DESC',
            'point_desc' => 'point DESC'
        );
        // ======================================================================================//

        // ========================== Validasi parameter2 searching =============================//
        // validasi search by..
        if (!in_array($search_param['search_by'], $field_list)) {
            redirect('admin_user/index');
        }

        //validasi operator..
        if (!in_array($search_param['operator'], $operator_list)) {
            redirect('admin_user/index');
        }

        //validasi sort_by..
        $sort_by = $sort_by_list[$search_param['sort_by']];
        if ($sort_by == '' || $sort_by == null) {
            redirect('admin_user/index');
        }
        //ganti search_by (field alias) dengan nama field..
        $search_param['sort_by'] = $sort_by;

        //validasi per page..
        $per_page = $search_param['per_page'];
        if ($per_page <= 0) {
            redirect('admin_user/index');
        }
        // =========================================================================================//

        //ambil parameter2 dan hasil pencarian..
        $data['total_row'] = $this->mdl_user->getSearchResultCount($search_param);
        $config = $this->searchPaginationConfig($data['total_row'], $search_param['per_page']);
        $this->pagination->initialize($config);

        $data['user'] = $this->mdl_user->getSearchResult($search_param, $config['per_page'], ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0));

        //load view search result..
        $content = $this->load->view('admin/user/all', $data, true);

        $this->render($content);
    }

    public function ban($id_user = '')
    {
        //ambil data admin yang akan diedit.
        $data = $this->mdl_user->getUserByID($id_user);
        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('admin_user/index');
        }

        $update_data = array('status' => 0);
        $this->mdl_user->updateUser($update_data, $id_user);

        $message = $this->global_lib->generateMessage("User account has been banned.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('admin_user/index');
    }

    public function activate($id_user = '')
    {
        //ambil data admin yang akan diedit.
        $data = $this->mdl_user->getUserByID($id_user);
        //jika tidak ada data, redirect ke index.
        if ((!is_array($data)) || count($data) < 1) {
            redirect('admin_user/index');
        }

        $update_data = array('status' => 1);
        $this->mdl_user->updateUser($update_data, $id_user);

        $message = $this->global_lib->generateMessage("User account has been activated.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('admin_user/index');
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

    private function userPicUploadConfig($filename = '')
    {
        $config['upload_path'] = './assets/user/';
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
        $config['base_url'] = base_url() . 'admin_user/index/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->pagination_per_page;
        $config['uri_segment'] = 3;
        return $config;
    }

    private function searchPaginationConfig($total_row, $per_page)
    {
        $config = $this->global_lib->paginationConfigAdmin();
        $config['base_url'] = base_url() . 'admin_user/search/';
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
            'status' => 'all',
            'confirm_email' => 'all',
            'is_internal' => 'is_internal',
            'search_collapsed' => '1'
        );
        $this->session->set_userdata('search_user', $search_param);
    }
}
