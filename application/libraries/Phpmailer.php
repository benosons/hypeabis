<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Phpmailer
{
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load()
    {
        require_once(APPPATH . 'third_party/phpmailer/src/Exception.php');
        require_once(APPPATH . 'third_party/phpmailer/src/PHPMailer.php');
        require_once(APPPATH . 'third_party/phpmailer/src/SMTP.php');

        $objMail = new PHPMailer\PHPMailer\PHPMailer();
        return $objMail;
    }
}