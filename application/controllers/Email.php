<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email extends CI_Controller
{

    var $color_theme = '#007bff';
    var $smtp_server = 'smtp.office365.com';
    var $email_from = 'Hypeabis';
    var $email_account = 'noreply@hypeabis.id';
    var $email_password = 'reply#HypeAbis123!!!';
    var $smtp_secure = 'tls'; //tls or ssl
    var $port = 587; //587 or 465

    public function __construct()
    {
        parent::__construct();

        $this->load->model('mdl_email');
        $this->load->library('email_lib');
    }

    /*
    // Param:
    // email
    // cc
    // bcc
    // attachment
    // subject
    // message
    */
    public function sendEMail()
    {
        $result = array(
            'sended' => false,
            'message' => ''
        );

        $this->db->insert('tbl_debug', array('content' => 'Email - sendEmail 20210527'));

        //get config from param..
        $config = array();
        $config['email'] = $_POST['email'];
        $config['cc'] = (isset($_POST['cc']) ? $_POST['cc'] : null);
        $config['bcc'] = (isset($_POST['bcc']) ? $_POST['bcc'] : null);
        $config['attachment'] = (isset($_POST['attachment']) && is_array($_POST['attachment']) && count($_POST['attachment']) > 0 ? $_POST['cc'] : null);
        $config['subject'] = $_POST['subject'];
        $config['message'] = $_POST['message'];

        // $config['email']   = "mail@binary-project.com";
        // $config['subject'] = "Testing Sending Email";
        // $config['email_content'] = $this->load->view('email/testing', $config, true);
        // $config['message'] = $this->load->view('email/template', $config, true);

        while (ob_get_level()) ob_end_clean();
        header('Connection: close');
        ignore_user_abort();
        ob_start();
        // echo('Connection Closed');
        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();
        flush();

        //ambil setting SMTP..
        $smtp_setting = $this->global_lib->getGlobalData();
        $email_from = (isset($smtp_setting[0]->website_name) && strlen(trim($smtp_setting[0]->website_name)) > 0 ? $smtp_setting[0]->website_name : $this->email_from);
        $smtp_server = (isset($smtp_setting[0]->smtp_server) && strlen(trim($smtp_setting[0]->smtp_server)) > 0 ? $smtp_setting[0]->smtp_server : $this->smtp_server);
        $smtp_email = (isset($smtp_setting[0]->smtp_email) && strlen(trim($smtp_setting[0]->smtp_email)) > 0 ? $smtp_setting[0]->smtp_email : $this->email_account);
        $smtp_pass = (isset($smtp_setting[0]->smtp_password) && strlen(trim($smtp_setting[0]->smtp_password)) > 0 ? $smtp_setting[0]->smtp_password : $this->email_password);
        $smtp_secure = (isset($smtp_setting[0]->smtp_secure) && strlen(trim($smtp_setting[0]->smtp_secure)) > 0 ? $smtp_setting[0]->smtp_secure : $this->smtp_secure);
        $smtp_port = (isset($smtp_setting[0]->smtp_port) && strlen(trim($smtp_setting[0]->smtp_port)) > 0 ? $smtp_setting[0]->smtp_port : $this->port);

        $this->load->library("phpmailer");
        $mail = $this->phpmailer->load();

        //Mail server settings
        $mail->SMTPDebug = 2;                                         // Enable verbose debug output
        $mail->isSMTP();                                              // Set mailer to use SMTP
        $mail->Host = 'smtp.office365.com';                               // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                       // Enable SMTP authentication
        $mail->Username = 'noreply@hypeabis.id';             // SMTP username
        $mail->Password = 'reply#HypeAbis123!!!';                            // SMTP password
        $mail->SMTPSecure = 'tls';                                    // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                            // TCP port to connect to

        //Recipients
        $mail->setFrom('noreply@hypeabis.id', 'Hypeabis');                                 // from email
        $mail->addAddress($config['email']);                                      // send to
        $mail->addReplyTo($smtp_email, 'Reply from - ' . $config['email']);       // set reply to

        if (isset($config['cc'])) {
            $mail->addCC($config['cc']);
        }
        if (isset($config['bcc'])) {
            $mail->addBCC($config['bcc']);
        }

        //Attachments
        if (isset($config['attachment']) && is_array($config['attachment']) && count($config['attachment']) > 0) {
            foreach ($config['attachment'] as $att) {
                $mail->addAttachment($att);                       // Add attachments
            }
        }

        //Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = $config['subject'];                    // email title
        $mail->Body = $config['message'];                    // email message body

        // $mail->send();
        // $mail->clearAddresses();
        // $mail->clearAttachments();

        if ($mail->send()) {
            $result['sended'] = true;
        }
        $result['message'] = $mail;

        $mail->clearAddresses();
        $mail->clearAttachments();
        $this->insertEmailHistory($config, $result);
    }

    public function testEmail()
    {
        $this->load->library('email_lib');
        $this->db->insert('tbl_debug', array('content' => 'Email - testEmail'));
        $this->email_lib->test(array(), true);
    }

    private function insertEmailHistory($config, $result = array())
    {
        $insert_data = array(
            'salt' => $config['salt'] ?? '',
            'email_id' => $config['email_id'] ?? '',
            'email_to' => $config['email'],
            'email_subject' => $config['subject'],
            'email_content' => strip_tags(htmlentities($config['message'])),
            'sended' => (isset($result['sended']) ? $result['sended'] : ''),
            'message' => (isset($result['message']) ? json_encode($result['message']) : '')
        );
        $this->mdl_email->insertEmail($insert_data);
        $this->db->insert('tbl_debug', array('content' => 'Email - insertEmail 20210527'));
    }

}
