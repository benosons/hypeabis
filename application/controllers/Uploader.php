<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
*
* @author 		: Hengky Mulyono <hengkymulyono301@gmail.com>
* @copyright	: Binary Project 2017
* @copyright	: mail@binary-project.com
* @version		: Release: v5.0
* @link			: www.binary-project.com
* @contact		: 0822 3709 9004
*
*/

class Uploader extends CI_Controller
{

    var $file_folder_name = "content";
    public function __construct()
    {
        parent::__construct();

        //load library..

        //load model..

        //construct script..
        if (!($this->session->userdata('admin_logged_in') === true || $this->session->userdata('user_logged_in') === true)) {
            redirect(base_url());
        }
    }

    public function index()
    {
        $data['CKEditor'] = $this->input->get('CKEditor');
        $data['CKEditorFuncNum'] = $this->input->get('CKEditorFuncNum');
        $data['langCode'] = $this->input->get('langCode');

        $this->load->view('uploader/upload_form', $data);
    }

    public function upload()
    {
        $data['CKEditor'] = $this->input->get('CKEditor');
        $data['CKEditorFuncNum'] = $this->input->get('CKEditorFuncNum');
        $data['langCode'] = $this->input->get('langCode');

        $this->load->view('uploader/upload_form', $data);
    }

    /**
     * function untuk handle upload file melalui ckeditor
     * @return void
     */
    public function uploadFile()
    {
        $CKEditor = $this->input->get('CKEditor');
        $CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
        $langCode = $this->input->get('langCode');

        $now = date("YmdHis");
        $file = '';
        $file_thumb = '';

        if (!empty($_FILES['content_file']['name'])) {
            $config = $this->photoUploadConfig();
            //config allowed type
            if ($this->uri->segment(3) == 'image') {
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
            }
            else {
                if ($this->uri->segment(3) == 'all') {
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|csv|ppt|pptx|txt';
                }
                else {
                    redirect("uploader/upload/all/?CKEditor=" . $CKEditor . "&CKEditorFuncNum=" . $CKEditorFuncNum . "&langCode=" . $langCode);
                }
            }

            $name = explode(".", $_FILES['content_file']['name']);
            $config['file_name'] = $now . '_' . str_replace(array('.', '_'), '', $name[0]) . '.' . $name[1];
            $this->upload->initialize($config);

            //upload file..
            if ($this->upload->do_upload('content_file')) {
                $data_file = array('upload_data' => $this->upload->data());
                $url_file = base_url() . "assets/" . $this->file_folder_name . "/" . $data_file['upload_data']['file_name'];

                $js = "<html><body><script language=\"javascript\">";
                $js .= "window.opener.CKEDITOR.tools.callFunction('" . $CKEditorFuncNum . "', '" . $url_file . "', '');";
                $js .= "window.close();</script></body></html>";
                echo $js;
            }
            else {
                $message = "Failed to upload file. Cause: " . str_replace(array('<p>', '</p>'), "", $this->upload->display_errors());
                $js = "<html><body><script language=\"javascript\">";
                $js .= "window.opener.CKEDITOR.tools.callFunction('" . $CKEditorFuncNum . "', '', '" . $message . "');";
                $js .= "window.close();</script></body></html>";
                echo $js;
            }
        }
        else {
            $message = "<div class='alert alert-danger'>Choose file for upload</div>";
            $this->session->set_flashdata('message', $message);
            redirect("uploader/upload/all/?CKEditor=" . $CKEditor . "&CKEditorFuncNum=" . $CKEditorFuncNum . "&langCode=" . $langCode);
        }
    }

    /**
     * function untuk handle direct upload
     * @return void
     */
    public function direct()
    {
        $CKEditor = $this->input->get('CKEditor');
        $CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
        $langCode = $this->input->get('langCode');

        $now = date("YmdHis");
        $file = '';
        $file_thumb = '';

        if (!empty($_FILES['upload']['name'])) {
            $config = $this->photoUploadConfig();

            //config allowed type
            if ($this->uri->segment(3) == 'image') {
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
            }
            else {
                if ($this->uri->segment(3) == 'all') {
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|ppt|pptx|txt';
                }
                else {
                    redirect("uploader/upload/all/?CKEditor=" . $CKEditor . "&CKEditorFuncNum=" . $CKEditorFuncNum . "&langCode=" . $langCode);
                }
            }
            //config filename
            $name = explode(".", $_FILES['upload']['name']);
            $config['file_name'] = $now . '_' . str_replace(array('.', '_'), '', $name[0]) . '.' . $name[1];
            $this->upload->initialize($config);

            //upload file article..
            if ($this->upload->do_upload('upload')) {
                $data_file = array('upload_data' => $this->upload->data());
                $url_file = base_url() . "assets/" . $this->file_folder_name . "/" . $data_file['upload_data']['file_name'];

                $js = "<html><body><script language=\"javascript\">";
                $js .= "window.parent.CKEDITOR.tools.callFunction('" . $CKEditorFuncNum . "', '" . $url_file . "', '');";
                $js .= "</script></body></html>";
                echo $js;
            }
            else {
                $message = "Failed to upload file. Cause: " . str_replace(array('<p>', '</p>'), "", $this->upload->display_errors());
                $js = "<html><body><script language=\"javascript\">";
                $js .= "window.opener.CKEDITOR.tools.callFunction('" . $CKEditorFuncNum . "', '', '" . $message . "');";
                $js .= "window.close();</script></body></html>";
                echo $js;
            }
        }
        else {
            $message = "Please choose file to upload.";
            $js = "<html><body><script language=\"javascript\">";
            $js .= "window.opener.CKEDITOR.tools.callFunction('" . $CKEditorFuncNum . "', '', '" . $message . "');";
            $js .= "window.close();</script></body></html>";
            echo $js;
        }
    }

    private function photoUploadConfig(): array
    {
        $config['upload_path'] = './assets/' . $this->file_folder_name . '/';
        $config['max_size'] = '5000';
        $config['max_width'] = '8000';
        $config['max_height'] = '8000';
        return $config;
    }

}