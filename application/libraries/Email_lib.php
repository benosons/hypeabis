<?php if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Email_lib
{

    // Note: Untuk testing harus online,
    // Enable php_openssl.dll
    // Harus https
    var $color_theme = '#007bff';
    var $smtp_server = 'smtp.office365.com';
    var $email_from = 'noreply@hypeabis.id';
    var $email_account = 'noreply@hypeabis.id';
    var $email_password = 'reply#HypeAbis123!!!';
    //var $email_password = 'welcome123#@!';
    var $smtp_secure = 'tls'; //tls or ssl
    var $port = 587; //587 or 465

    function __construct()
    {
        //load CI instance..
        $this->CI = &get_instance();

        //load model
        $this->CI->load->model('mdl_email');

        //load library
        $this->CI->load->library('email');
        $this->CI->load->library('global_lib');
    }

    public function test($config = array(), $debug_view = false)
    {
        // email content..
        $config['email'] = "mail@binary-project.com";
        $config['subject'] = "Testing Sending Email " . date('Y-m-d H:i:s');
        $config['email_content'] = $this->CI->load->view('email/testing', $config, true);
        $config['message'] = $this->CI->load->view('email/template', $config, true);

        // $this->CI->db->insert('tbl_debug', array('content' => 'Email_lib - test'));

        // send email in background
        $this->sendEmail($config);
        // send email in debugging mode
        // $result = $this->debugSendEmail($config, true);
    }

    public function sendContact($config = array(), $debug_view = false)
    {
        $config = $this->generateEmailID($config);

        //email content..
        $config['from'] = $config['contact_data'][0]->email;
        $config['from_name'] = $config['contact_data'][0]->name;
        $config['email_content'] = $this->CI->load->view('email/contact', $config, true);
        $config['message'] = $this->CI->load->view('email/template', $config, true);

        if (isset($config['email']) && strlen(trim($config['email'])) > 0 && (!$debug_view)) {
            //send email and insert record to database
            $result = $this->sendEmail($config);
            //$result = $this->sendMailSendgrid($config);
            return $result;
        }
        else {
            return $config['message'];
        }
    }

    public function signup($config = array(), $debug_view = false)
    {
        $config = $this->generateEmailID($config);

        $this->CI->load->library('SendEmail');
        $mail = new SendEmail();

        //email content..
        $config['subject'] = "Account Activation";
        $config['email_content'] = $this->CI->load->view('email/signup', $config, true);
        $config['message'] = $this->CI->load->view('email/template', $config, true);

        if (isset($config['email']) && strlen(trim($config['email'])) > 0 && (!$debug_view)) {
            //send email and insert record to database
            // $result = $this->sendMailSendgrid($config);
            $result = $this->sendEmail($config);
            return $result;
        }
        else {
            return $config['message'];
        }
    }

    public function accountActivated($config = array(), $debug_view = false)
    {
        $config = $this->generateEmailID($config);

        $this->CI->load->library('SendEmail');
        $mail = new SendEmail();

        //email content..
        $config['subject'] = "Akun Hypeabis Anda Telah Aktif";
        $config['email_content'] = $this->CI->load->view('email/account_activated', $config, true);
        $config['message'] = $this->CI->load->view('email/template', $config, true);

        if (isset($config['email']) && strlen(trim($config['email'])) > 0 && (!$debug_view)) {
            //send email and insert record to database
            // $result = $this->sendMailSendgrid($config);
            $result = $this->sendEmail($config);
            return $result;
        }
        else {
            return $config['message'];
        }
    }

    public function resetPassword($config = array(), $debug_view = false)
    {
        $config = $this->generateEmailID($config);

        //email content..
        $config['subject'] = "Reset Password";
        $config['email_content'] = $this->CI->load->view('email/reset_password', $config, true);
        $config['message'] = $this->CI->load->view('email/template', $config, true);

        if (isset($config['email']) && strlen(trim($config['email'])) > 0 && (!$debug_view)) {
            //send email and insert record to database
            $result = $this->sendEmail($config);
            //$result = $this->sendMailSendgrid($config);
            return $result;
        }
        else {
            return $config['message'];
        }
    }

    public function resetPasswordViaURL($config = array(), $debug_view = false)
    {
        $config = $this->generateEmailID($config);

        //email content..
        $config['subject'] = "Reset Password";
        $config['email_content'] = $this->CI->load->view('email/reset_password_url', $config, true);
        $config['message'] = $this->CI->load->view('email/template', $config, true);

        if (isset($config['email']) && strlen(trim($config['email'])) > 0 && (!$debug_view)) {
            //send email and insert record to database
            $result = $this->sendEmail($config);
            //$result = $this->sendMailSendgrid($config);
            return $result;
        }
        else {
            return $config['message'];
        }
    }

    public function sendNewFollowerEmail($config = array(), $debug_view = false)
    {
        $config = $this->generateEmailID($config);

        //email content..
        $config['subject'] = "New Follower";
        $config['email_content'] = $this->CI->load->view('email/new_follower', $config, true);
        $config['message'] = $this->CI->load->view('email/template', $config, true);

        if (isset($config['email']) && strlen(trim($config['email'])) > 0 && (!$debug_view)) {
            //send email and insert record to database
            $result = $this->sendEmail($config);
            //$result = $this->sendMailSendgrid($config);
            return $result;
        }
        else {
            return $config['message'];
        }
    }

    public function sendVerifiedMemberVerification($config = [], $debug_view = FALSE)
    {
        $config = $this->generateEmailID($config);

        //email content..
        $config['subject'] = "Verified Member Verification Result";
        $config['email_content'] = $this->CI->load->view('email/verified_member_verification', $config, TRUE);
        $config['message'] = $this->CI->load->view('email/template', $config, TRUE);

        if (isset($config['email']) && strlen(trim($config['email'])) > 0 && (!$debug_view)) {
            //send email and insert record to database
            $result = $this->sendEmail($config);
            //$result = $this->sendMailSendgrid($config);
            return $result;
        }
        else {
            return $config['message'];
        }
    }

    //kirim email ke admin saat ada artikel baru yang membutuhkan moderasi
    public function newArticleAdmin($config = [], $debug_view = FALSE)
    {

    }

    private function sendEmail($config = array())
    {
        if (isset($config['email']) && isset($config['subject']) && isset($config['message'])) {
            try {
                $this->CI->load->library("phpmailer");
                $mail = $this->CI->phpmailer->load();

                //Mail server settings
                $mail->SMTPDebug = 2;                                         // Enable verbose debug output
                $mail->isSMTP();                                              // Set mailer to use SMTP
                //$mail->Host = 'smtp.gmail.com';                            // Specify main and backup SMTP servers
                $mail->Host = 'smtp.office365.com';                            // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                                       // Enable SMTP authentication
                //$mail->Username = 'noreply.system.001@gmail.com';                       // SMTP username
                $mail->Username = 'noreply@hypeabis.id';                       // SMTP username
                //$mail->Password = 'noreply2019.,';                      // SMTP password
                $mail->Password = 'reply#HypeAbis123!!!';                      // SMTP password
                $mail->SMTPSecure = 'tls';                                    // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                            // TCP port to connect to

                //Recipients
                $mail->setFrom('noreply@hypeabis.id', 'Hypeabis'); // from email
                $mail->addAddress($config['email']);                 // send to
                $mail->addReplyTo('noreply@hypeabis.id');             // set reply to

                //Content
                $mail->isHTML(true);                                          // Set email format to HTML
                $mail->Subject = $config['subject'];                                    // email title
                $mail->Body = $config['message'];                                    // email message body

                //Send email
                if (!$mail->send()) {
                    $this->CI->db->insert('tbl_debug', array('content' => date('Y-m-d H:i:s') . ' - Email_lib - sendEmail - Gagal'));
                }
                else {
                    $this->CI->db->insert('tbl_debug', array('content' => date('Y-m-d H:i:s') . ' - Email_lib - sendEmail - Success'));
                }

                $mail->clearAddresses();
                $mail->clearAttachments();
            }
            catch (phpmailerException $e) {
                $this->CI->db->insert('tbl_debug', array('content' => date('Y-m-d H:i:s') . ' - Email_lib - sendEmail - ' . $e->errorMessage()));
                // echo $e->errorMessage(); //Pretty error messages from PHPMailer
            }
            catch (Exception $e) {
                $this->CI->db->insert('tbl_debug', array('content' => date('Y-m-d H:i:s') . ' - Email_lib - sendEmail - ' . $e->getMessage()));
                // echo $e->getMessage(); //Boring error messages from anything else!
            }
        }
        else {
            $this->CI->db->insert('tbl_debug', array('content' => date('Y-m-d H:i:s') . ' - Email_lib - sendEmail - parameter tidak lengkap'));
        }
    }

    private function sendMailSendgrid($config = array())
    {
        // using SendGrid's PHP Library
        // https://github.com/sendgrid/sendgrid-php
        // require 'vendor/autoload.php'; // If you're using Composer (recommended)
        // Comment out the above line if not using Composer
        require APPPATH . 'third_party/Sendgrid/sendgrid-php.php';
        // If not using Composer, uncomment the above line
        $email = new \SendGrid\Mail\Mail();
        if (isset($config['from']) && strlen(trim($config['from'])) > 0 && isset($config['from_name']) && strlen(trim($config['from_name'])) > 0) {
            $email->setFrom($config['from'], $config['from_name']);
        }
        else {
            $email->setFrom('noreply@hypeabis.id', $this->email_from);
        }
        $email->setSubject($config['subject']);
        $email->addTo($config['email'], '');
        $email->addContent(
            "text/html", $config['message']
        );
        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
            // print_r('<pre>');
            // print_r($response);
            // print_r('</pre>');
            return $response;
        }
        catch (Exception $e) {
            // print_r('<pre>');
            // print_r($e->getMessage());
            // print_r('</pre>');
            return 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }

    private function debugSendEmail($config = array())
    {
        $result = array(
            'sended' => false,
            'message' => ''
        );

        //ambil setting SMTP..
        $smtp_setting = $this->CI->global_lib->getGlobalData();
        $smtp_server = (isset($smtp_setting[0]->smtp_server) && strlen(trim($smtp_setting[0]->smtp_server)) > 0 ? $smtp_setting[0]->smtp_server : $this->smtp_server);
        $smtp_email = (isset($smtp_setting[0]->smtp_email) && strlen(trim($smtp_setting[0]->smtp_email)) > 0 ? $smtp_setting[0]->smtp_email : $this->email_account);
        $smtp_pass = (isset($smtp_setting[0]->smtp_pass) && strlen(trim($smtp_setting[0]->smtp_pass)) > 0 ? $smtp_setting[0]->smtp_pass : $this->email_password);
        $smtp_secure = (isset($smtp_setting[0]->smtp_secure) && strlen(trim($smtp_setting[0]->smtp_secure)) > 0 ? $smtp_setting[0]->smtp_secure : $this->smtp_secure);
        $smtp_port = (isset($smtp_setting[0]->smtp_port) && strlen(trim($smtp_setting[0]->smtp_port)) > 0 ? $smtp_setting[0]->smtp_port : $this->port);

        try {
            $this->CI->load->library("phpmailer");
            $mail = $this->CI->phpmailer->load();

            //Mail server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $smtp_server;                           // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $smtp_email;                        // SMTP username
            $mail->Password = $smtp_pass;                         // SMTP password
            $mail->SMTPSecure = $smtp_secure;                     // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $smtp_port;                             // TCP port to connect to

            //Recipients
            // $mail->setFrom($this->email_account, $config['global'][0]->website_name);    // from email
            $mail->setFrom($smtp_email, $this->email_from);                                 // from email
            $mail->addAddress($config['email']);                                            // send to
            $mail->addReplyTo($smtp_email, 'Reply from - ' . $config['email']);             // set reply to

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

            if (!$mail->send()) {
                if ($debug) {
                    $result['message'] = $mail->ErrorInfo;
                }
            }
            else {
                $result['sended'] = true;
            }

            $mail->clearAddresses();
            $mail->clearAttachments();
            return $result;
        }
        catch (phpmailerException $e) {
            if ($debug) {
                return $e->errorMessage(); //Pretty error messages from PHPMailer
            }
        }
        catch (Exception $e) {
            if ($debug) {
                return $e->getMessage(); //Boring error messages from anything else!
            }
        }
    }

    private function generateEmailID($config)
    {
        //generate email id
        $config['salt'] = $this->CI->global_lib->generateHash();
        $config['email_id'] = $this->CI->global_lib->generateHash(date('YmdHisu'));

        //get global data
        $config['color_theme'] = $this->color_theme;
        $config['global'] = $this->CI->global_lib->getGlobalData();
        $config['email_hash'] = sha1($config['salt'] . $config['email_id']);
        return $config;
    }

    private function insertEmailHistory($config, $result = array())
    {
        $insert_data = array(
            'salt' => $config['salt'],
            'email_id' => $config['email_id'],
            'email_to' => $config['email'],
            'email_subject' => $config['subject'],
            'email_content' => strip_tags(htmlentities($config['message'])),
            'sended' => (isset($result['sended']) ? $result['sended'] : ''),
            'message' => (isset($result['message']) ? $result['message'] : '')
        );
        $this->CI->mdl_email->insertEmail($insert_data);
    }

}
