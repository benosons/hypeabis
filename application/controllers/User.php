<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    var $js = array();
    var $css = array();

    public function __construct()
    {
        parent::__construct();

        //load config
        $this->load->config('facebook');
        $this->load->config('google');
        $this->load->config('twitter');
        $this->load->config('linkedin');

        //load library
        //linkeding auth
        $this->load->library('linkedin', array(
            'linkedin_api_key' => $this->config->item('linkedin_api_key'),
            'linkedin_api_secret' => $this->config->item('linkedin_api_secret'),
            'linkedin_redirect_url' => $this->config->item('linkedin_redirect_url')
        ));
        //facebook auth
        $this->load->library('facebook');
        //Google auth
        require_once APPPATH . 'third_party/Google/Google_Client.php';
        require_once APPPATH . 'third_party/Google/contrib/Google_Oauth2Service.php';
        //Twitter auth
        include_once APPPATH . 'third_party/TwitterOAuth/twitteroauth.php';

        //load model..
        $this->load->model('mdl_user');
    }

    /*
      public function index($redirect_url = ''){
      //jika sudah login, redirect ke dashboard..
      if($this->session->userdata('user_logged_in') === true){
        redirect('user_dashboard/index/');
      }

      //get facebooh auth URL.
      $this->facebook->destroy_session();
      $data['fb_auth_url'] =  $this->facebook->login_url();

      //get google auth URL.
      $data['google_auth_url'] = $this->config->item('google_redirect_url');

      //get twitter auth URL.
      $data['twitter_auth_url'] = $this->config->item('twitter_redirect_url');

      //get linkedin auth url
      $data['linkedin_auth_url'] = $this->config->item('linkedin_redirect_url') . '?oauth_init=1';

      $data['redirect_url'] = $redirect_url;
      $data['global_data'] = $this->global_lib->getGlobalData();
          $this->load->view('user/login', $data);
      }

    public function validateLogin($redirect_url = ''){
      $this->form_validation->set_rules('email','', 'htmlentities|strip_tags|trim|required|xss_clean');
          $this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|required|xss_clean');
          if ($this->form_validation->run() == FALSE){
              $message =  $this->global_lib->generateMessage("Login failed, please try again.", "danger");
              $this->session->set_flashdata('message', $message);
              redirect('page/login');
          }
          else{
        $email = $this->input->post('email');
              $password = sha1($this->input->post('password') . $this->config->item('binary_salt'));

        $user_check = $this->mdl_user->validateLogin($email, $password);
              if($user_check == 1){ // login berhasil
                  $user = $this->mdl_user->getUserByEmailAndPassword($email, $password);
                  $session_data = array(
                      'id_user' => $user[0]->id_user,
                      'user_email' => $user[0]->email,
                      'user_name' => $user[0]->name,
                      'user_gender' => $user[0]->gender,
                      'user_contact_number' => $user[0]->contact_number,
                      'user_picture_from' => $user[0]->picture_from,
                      'user_picture' => (isset($user[0]->picture) && strlen(trim($user[0]->picture)) > 0 ? $user[0]->picture : ''),
                      'user_logged_in' => true,
            'user_login_time' => date('Y-m-d H:i:s')
                  );
                  $this->session->set_userdata($session_data);

                  if(strlen($redirect_url) == 0){
                      redirect("user_dashboard/index");
                  }
                  else{
                      try{
                          redirect(base64_decode(urldecode($redirect_url)));
                      }
                      catch(Exception $e){
                          redirect("user_dashboard/index");
                      }
                  }
              }
              else if($user_check == 2){ // login benar, tetapi akun belum aktivasi email
          $message =  $this->global_lib->generateMessage("You must activate your account first. To activate your account, please check your email and click the activation link. <br/><br/><b>Note : </b>If you do not find our email, check your <b>spam folder</b> or contact our help center.", "warning");
              }
              else if($user_check == 3){ // login benar, akun belum di aktifkan
          $message =  $this->global_lib->generateMessage("Your account is non active. Contact us to follow up you account status.", "danger");
        }
        else if($user_check == 4){ // login benar, akun di banned
          $message =  $this->global_lib->generateMessage("Your account has been banned by admin due to several reasons. Please contact our help center for more information.", "danger");
              }
              else{ // login salah
          $message =  $this->global_lib->generateMessage("Login failed. Please try again.", "danger");
              }

        $this->session->set_flashdata('message', $message);
        redirect('page/login/'.$redirect_url);
          }
      }

    public function signup($redirect_url = ''){
      //jika sudah login, redirect ke dashboard..
      if($this->session->userdata('user_logged_in') === true){
        redirect("user_dashboard");
      }

      //get facebooh auth URL.
      $data['fb_auth_url'] =  $this->facebook->login_url();

      //get google auth URL.
      $data['google_auth_url'] =  $this->config->item('google_redirect_url');

      $data['redirect_url'] = $redirect_url;
      $data['global_data'] = $this->global_lib->getGlobalData();
          $this->load->view('user/signup', $data);
    }

    public function submitSignup($redirect_url = ''){
      $this->form_validation->set_rules('name','', 'htmlentities|strip_tags|trim|required|xss_clean');
          $this->form_validation->set_rules('email','', 'htmlentities|strip_tags|trim|required|xss_clean|valid_email');
          $this->form_validation->set_rules('contact_number','', 'htmlentities|strip_tags|trim|xss_clean');
          $this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|required|xss_clean');
          $this->form_validation->set_rules('confirm_password','', 'htmlentities|strip_tags|trim|required|xss_clean|matches[password]');
          if($this->form_validation->run() == false){
        $message =  $this->global_lib->generateMessage("Gagal membuat akun. Mohon isi form dengan format yang benar dan lengkap.", "danger");
              $this->session->set_flashdata('message', $message);
              redirect('page/signup');
          }
          else{
        //cek email sudah ada yang pakai / belum.
              $email = $this->input->post('email');
              $cek = $this->mdl_user->checkUserByEmail($email);
              if($cek){
          $message =  $this->global_lib->generateMessage("Email sudah pernah digunakan. Silakan login atau gunakan email yang lain.", "danger");
                  $this->session->set_flashdata('message', $message);
                  redirect('page/signup');
              }

        //generate hash string
              $hash = $this->global_lib->generateHash();
        $hash .= date('YmdHisu');

              //insert data ke database..
              $insert_data = array(
          'oauth_provider' => 'web|',
                  'name' => $this->input->post('name'),
                  'email' => $this->input->post('email'),
                  'contact_number' => $this->input->post('contact_number'),
                  'password' => sha1($this->input->post('password') . $this->config->item('binary_salt')),
                  'status' => 1,
                  'confirm_email' => 0,
                  'hash' => $hash,
              );
              $this->mdl_user->insertUser($insert_data);

        //kirim email konfirmasi
        $this->load->library('email_lib');
        $config = array(
          'verification_key' => $hash,
          'email' => $this->input->post('email'),
        );
        $send_email = $this->email_lib->signup($config, false);

        $message =  $this->global_lib->generateMessage("Please check your email to activate your account.", "info");
              $this->session->set_flashdata('message', $message);
              redirect('page/login');
      }
    }

    public function activation($hash = ''){
      //jika sudah login, redirect ke dashboard..
      if($this->session->userdata('user_logged_in') === true){
        redirect("user_dashboard");
      }

      if($hash == null || strlen(trim($hash)) <= 0)redirect('user/signup');

          //cek user bedasarkan hash..
          $check_user = $this->mdl_user->checkUserByHash($hash);
          if($check_user){
              //jika user ditemukan di database, ambil data user..
              $user_data = $this->mdl_user->getUserByHash($hash);
              if($user_data[0]->confirm_email != 1){
                  //aktifasi user jadi aktif, dan update kuka point..
                  $update_data = array(
                      'confirm_email' => 1,
                  );
                  $this->mdl_user->updateUser($update_data, $user_data[0]->id_user);
              }
              $message =  $this->global_lib->generateMessage("Your account has been activated. You can sign in using your email and password.", "info");
        $this->session->set_flashdata('message', $message);
              redirect('user/index');
          }
          else{
              //jika user tidak ada di database (hash tidak valid)..
              redirect('user/signup');
          }
    }

    public function forgot(){
      //jika sudah login, redirect ke dashboard..
      if($this->session->userdata('user_logged_in') === true){
        redirect("user_dashboard");
      }

      $data['global_data'] = $this->global_lib->getGlobalData();
          $this->load->view('user/forgot', $data);
    }

    public function submitForgotPassword(){
      $this->form_validation->set_rules('email','email', 'htmlentities|strip_tags|trim|required|xss_clean|valid_email');
          if ($this->form_validation->run() == FALSE){
        $message =  $this->global_lib->generateMessage("<strong>Form validation failed. </strong>Please make sure the email you enter are in the right format.", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('page/forgot');
      }
          else{
        //ambil data user by email.
        $email = $this->input->post('email');
        $user = $this->mdl_user->getUserByEmail($email);

        if(isset($user[0]->id_user) && $user[0]->id_user > 0){
          $user_hash = $user[0]->hash;

          //update forgot password limit.
          $update_data = array(
            'forgot_password_limit' => date('Y-m-d H:i:s', strtotime('now +1 hour'))
          );
          $this->mdl_user->updateUser($update_data, $user[0]->id_user);

          //kirim email konfirmasi reset password
          $this->load->library('email_lib');
          $config = array(
            'hash' => $user_hash,
            'email' => $user[0]->email,
          );
          $send_email = $this->email_lib->resetPasswordViaURL($config, false);

          $message =  $this->global_lib->generateMessage("Silakan cek email anda untuk me-reset password", "info");
          $this->session->set_flashdata('message', $message);
          redirect('page/forgot');
        }
        else{
          $message =  $this->global_lib->generateMessage("Email tidak terdaftar", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('page/forgot');
        }
      }
    }

    public function resetPassword($hash = ''){
      //jika sudah login, redirect ke dashboard..
      if($this->session->userdata('user_logged_in') === true){
        redirect("user_dashboard");
      }

      if($hash == null || $hash == '')redirect('user');

      //cek user bedasarkan hash..
          $check_user = $this->mdl_user->checkUserByHash($hash);
          if($check_user){
              //jika user ditemukan di database, ambil data user..
              $user = $this->mdl_user->getUserByHash($hash);

        //cek limit URL forgot password.
        $now = strtotime(date('Y-m-d H:i:s'));
              if($now <= strtotime($user[0]->forgot_password_limit)){
                  $data['global_data'] = $this->global_lib->getGlobalData();
          $this->load->view('page/reset_password', $data);
              }
        else{
          $message =  $this->global_lib->generateMessage("URL reset password anda telah expired. Silakan lakukan permintaan reset password ulang.", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('page/forgot');
        }
          }
          else{
              redirect('page');
          }
    }

    public function submitResetPassword($hash = ''){
      //jika sudah login, redirect ke dashboard..
      if($this->session->userdata('user_logged_in') === true){
        redirect("user_dashboard");
      }

      if($hash == null || $hash == '')redirect('user');

      //cek user bedasarkan hash..
          $check_user = $this->mdl_user->checkUserByHash($hash);
          if($check_user){
        //validasi..
        $this->form_validation->set_rules('new_password','password', 'htmlentities|strip_tags|trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password','password', 'htmlentities|strip_tags|trim|required|xss_clean|matches[new_password]');
        if ($this->form_validation->run() == FALSE){
          $message =  $this->global_lib->generateMessage("Failed to reset password. Pease try again", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('user/resetPassword/' . $hash);
        }
        else{
          //jika user ditemukan di database, ambil data user..
          $user = $this->mdl_user->getUserByHash($hash);

          //update password..
          $new_password = $this->input->post('new_password');
          $update_data = array(
            'password' => sha1($new_password . $this->config->item('binary_salt'))
          );
          $this->mdl_user->updateUser($update_data, $user[0]->id_user);

          $message =  $this->global_lib->generateMessage("Your password has been updated.", "info");
          $this->session->set_flashdata('message', $message);
          redirect('user');
        }
      }
      else{
        redirect('user');
      }
    }
    */

    public function fbLoginCallback()
    {
        $data = array();

        // Check if user is logged in
        if ($this->facebook->is_authenticated()) {
            // Get user facebook profile details
            $fb_user = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,picture');

            // Preparing data for database insertion
            $data['oauth_provider'] = 'facebook|';
            $data['oauth_uid_facebook'] = !empty($fb_user['id']) ? $fb_user['id'] : '';
            $data['name'] = (!empty($fb_user['first_name']) ? $fb_user['first_name'] : '') . ' ' . (!empty($fb_user['last_name']) ? $fb_user['last_name'] : '');
            $data['email'] = !empty($fb_user['email']) ? $fb_user['email'] : '';
            $data['picture'] = !empty($fb_user['picture']['data']['url']) ? $fb_user['picture']['data']['url'] : '';

            //check user by email.
            //if data already exist: update, if not insert new user to database
            if (isset($data['email']) && strlen(trim($data['email'])) > 0) {
                $user = $this->mdl_user->getUserByAllEmail($data['email']);
                if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                    //update data..
                    $update_data = array(
                        'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
                        'oauth_uid_facebook' => $data['oauth_uid_facebook'],
                        'email_facebook' => $data['email'],
                        'picture_from' => $user[0]->picture ? $user[0]->picture_from : 'facebook',
                        'picture' => $user[0]->picture ?? $data['picture'],
                        'last_login_at' => date('Y-m-d H:i:s'),
                    );
                    $this->mdl_user->updateUser($update_data, $user[0]->id_user);
                    $user = $this->mdl_user->getUserByID($user[0]->id_user);
                }
                else {
                    if (isset($data['email']) && strlen(trim($data['email'])) > 0) {
                        //generate hash string
                        $hash = $this->global_lib->generateHash();
                        $hash .= date('YmdHisu');

                        $insert_data = array(
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'email_facebook' => $data['email'],
                            'oauth_provider' => $data['oauth_provider'],
                            'oauth_uid_facebook' => $data['oauth_uid_facebook'],
                            'picture_from' => 'facebook',
                            'picture' => $data['picture'],
                            'status' => 1,
                            'confirm_email' => 1,
                            'hash' => $hash,
                            'last_login_at' => date('Y-m-d H:i:s'),
                        );
                        $this->mdl_user->insertUser($insert_data);
                        $user = $this->mdl_user->getUserByHash($hash);
                    }
                    else {
                    }
                }
                if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                    $this->setUserSession($user);
                }
            }
            else {
                if (isset($data['oauth_uid_facebook']) && strlen(trim($data['oauth_uid_facebook'])) > 0) {
                    $user = $this->mdl_user->getUserByFacebookUid($data['oauth_uid_facebook']);
                    if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                        //update data..
                        $update_data = array(
                            'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
                            'oauth_uid_facebook' => $data['oauth_uid_facebook'],
                            'email_facebook' => $data['email'],
                            'picture_from' => 'facebook',
                            'picture' => $data['picture']
                        );
                        $this->mdl_user->updateUser($update_data, $user[0]->id_user);
                        $user = $this->mdl_user->getUserByID($user[0]->id_user);
                    }
                    else {
                        //insert data baru.
                        //generate hash string
                        $hash = $this->global_lib->generateHash();
                        $hash .= date('YmdHisu');

                        $insert_data = array(
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'email_facebook' => $data['email'],
                            'oauth_provider' => $data['oauth_provider'],
                            'oauth_uid_facebook' => $data['oauth_uid_facebook'],
                            'picture_from' => 'facebook',
                            'picture' => $data['picture'],
                            'status' => 1,
                            'confirm_email' => 1,
                            'hash' => $hash
                        );
                        $this->mdl_user->insertUser($insert_data);
                        $user = $this->mdl_user->getUserByHash($hash);
                    }
                    if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                        $this->setUserSession($user);
                    }
                }
                else {
                }
            }
        }

        redirect('page/login');
    }

    public function googleLoginCallback()
    {
        $data = array();

        $client_id = $this->config->item('google_client_id'); //Google client ID
        $client_secret = $this->config->item('google_client_secret'); //Google client secret
        $redirect_url = $this->config->item('google_redirect_url'); //Google callback / redirect URL

        //Call Google API
        $gclient = new Google_Client();
        $gclient->setApplicationName('Login');
        $gclient->setClientId($client_id);
        $gclient->setClientSecret($client_secret);
        $gclient->setRedirectUri($redirect_url);
        $google_oauthV2 = new Google_Oauth2Service($gclient);

        if (isset($_GET['code'])) {
            $gclient->authenticate($_GET['code']);
            $_SESSION['token'] = $gclient->getAccessToken();
            header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
        }

        if (isset($_SESSION['token'])) {
            $gclient->setAccessToken($_SESSION['token']);
        }

        if ($gclient->getAccessToken()) { //berhasil login
            $google_user = $google_oauthV2->userinfo->get();

            // Preparing data for database insertion
            $data['oauth_provider'] = 'google|';
            $data['oauth_uid_google'] = !empty($google_user['id']) ? $google_user['id'] : '';
            $data['name'] = !empty($google_user['name']) ? $google_user['name'] : '';
            $data['email'] = !empty($google_user['email']) ? $google_user['email'] : '';
            $data['picture'] = !empty($google_user['picture']) ? $google_user['picture'] : '';

            //check user by email.
            //if data already exist: update, if not insert new user to database
            if (isset($data['email']) && strlen(trim($data['email'])) > 0) {
                $user = $this->mdl_user->getUserByAllEmail($data['email']);
                if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                    //update data..
                    $update_data = array(
                        'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
                        'oauth_uid_google' => $data['oauth_uid_google'],
                        'email_google' => $data['email'],
                        'picture_from' => $user[0]->picture ? $user[0]->picture_from : 'google',
                        'picture' => $user[0]->picture ?? $data['picture']
                    );
                    $this->mdl_user->updateUser($update_data, $user[0]->id_user);
                }
                else {
                    //generate hash string
                    $hash = $this->global_lib->generateHash();
                    $hash .= date('YmdHisu');

                    $insert_data = array(
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'email_google' => $data['email'],
                        'oauth_provider' => $data['oauth_provider'],
                        'oauth_uid_google' => $data['oauth_uid_google'],
                        'picture_from' => 'google',
                        'picture' => $data['picture'],
                        'status' => 1,
                        'confirm_email' => 1,
                        'hash' => $hash
                    );
                    $this->mdl_user->insertUser($insert_data);
                    $user = $this->mdl_user->getUserByHash($hash);
                }
                if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                    $this->setUserSession($user);
                }
            }
            else {
                if (isset($data['oauth_uid_google']) && strlen(trim($data['oauth_uid_google'])) > 0) {
                    $user = $this->mdl_user->getUserByGoogleUid($data['oauth_uid_google']);
                    if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                        //update data..
                        $update_data = array(
                            'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
                            'oauth_uid_google' => $data['oauth_uid_google'],
                            'email_google' => $data['email'],
                            'picture_from' => 'google',
                            'picture' => $data['picture']
                        );
                        $this->mdl_user->updateUser($update_data, $user[0]->id_user);
                    }
                    else {
                        //generate hash string
                        $hash = $this->global_lib->generateHash();
                        $hash .= date('YmdHisu');

                        $insert_data = array(
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'email_google' => $data['email'],
                            'oauth_provider' => $data['oauth_provider'],
                            'oauth_uid_google' => $data['oauth_uid_google'],
                            'picture_from' => 'google',
                            'picture' => $data['picture'],
                            'status' => 1,
                            'confirm_email' => 1,
                            'hash' => $hash
                        );
                        $this->mdl_user->insertUser($insert_data);
                        $user = $this->mdl_user->getUserByHash($hash);
                    }
                    if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                        $this->setUserSession($user);
                    }
                }
                else {
                }
            }

            redirect('page/login');
        }
        else {
            $url = $gclient->createAuthUrl();
            header("Location: $url");
            exit;
        }
    }

    public function twitterLoginCallback()
    {
        $data = array();
        $consumer_key = $this->config->item('twitter_consumer_key');
        $consumer_secret = $this->config->item('twitter_consumer_secret');
        $oauth_callback = $this->config->item('twitter_redirect_url');
        $oauth_token = $this->input->get('oauth_token');
        $oauth_verifier = $this->input->get('oauth_verifier');

        if (isset($oauth_token) && isset($oauth_verifier) && strlen(trim($oauth_token)) > 0 && strlen(trim($oauth_verifier)) > 0) {
            //Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
            $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $consumer_secret);
            $access_token = $connection->getAccessToken($oauth_verifier);
            if ($connection->http_code == '200') {
                //Get user profile info
                $twitter_user = $connection->get('account/verify_credentials', ['include_email' => "true"]);

                $data['oauth_provider'] = 'twitter|';
                $data['oauth_uid_twitter'] = !empty($twitter_user->id) ? $twitter_user->id : '';
                $data['name'] = !empty($twitter_user->name) ? $twitter_user->name : '';
                $data['email'] = !empty($twitter_user->email) ? $twitter_user->email : '';
                $data['picture'] = !empty($twitter_user->profile_image_url_https) ? $twitter_user->profile_image_url_https : '';

                // check user by email.
                // if data already exist: update, if not insert new user to database
                if (isset($data['email']) && strlen(trim($data['email'])) > 0) {
                    $user = $this->mdl_user->getUserByAllEmail($data['email']);
                    if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                        //update
                        $update_data = array(
                            'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
                            'oauth_uid_twitter' => $data['oauth_uid_twitter'],
                            'email_twitter' => $data['email'],
                            'picture_from' => 'twitter',
                            'picture' => $data['picture']
                        );
                        $this->mdl_user->updateUser($update_data, $user[0]->id_user);
                    }
                    else {
                        //insert
                        $hash = $this->global_lib->generateHash();
                        $hash .= date('YmdHisu');
                        $insert_data = array(
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'email_twitter' => $data['email'],
                            'oauth_provider' => $data['oauth_provider'],
                            'oauth_uid_twitter' => $data['oauth_uid_twitter'],
                            'picture_from' => 'twitter',
                            'picture' => $data['picture'],
                            'status' => 1,
                            'confirm_email' => 1,
                            'hash' => $hash
                        );
                        $this->mdl_user->insertUser($insert_data);
                        $user = $this->mdl_user->getUserByHash($hash);
                    }
                    if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                        $this->setUserSession($user);
                    }
                }
                else {
                    if (isset($data['oauth_uid_twitter']) && strlen(trim($data['oauth_uid_twitter'])) > 0) {
                        $user = $this->mdl_user->getUserByTwitterUid($data['oauth_uid_twitter']);
                        if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                            //update
                            $update_data = array(
                                'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
                                'oauth_uid_twitter' => $data['oauth_uid_twitter'],
                                'email_twitter' => $data['email'],
                                'picture_from' => 'twitter',
                                'picture' => $data['picture']
                            );
                            $this->mdl_user->updateUser($update_data, $user[0]->id_user);
                        }
                        else {
                            //generate hash string
                            $hash = $this->global_lib->generateHash();
                            $hash .= date('YmdHisu');

                            $insert_data = array(
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'email_twitter' => $data['email'],
                                'oauth_provider' => $data['oauth_provider'],
                                'oauth_uid_twitter' => $data['oauth_uid_twitter'],
                                'picture_from' => 'twitter',
                                'picture' => $data['picture'],
                                'status' => 1,
                                'confirm_email' => 1,
                                'hash' => $hash
                            );
                            $this->mdl_user->insertUser($insert_data);
                            $user = $this->mdl_user->getUserByHash($hash);
                        }
                        if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                            $this->setUserSession($user);
                        }
                    }
                    else {
                    }
                }
                redirect('page/login');

                // print_r('<pre>');
                // print_r($twitter_user);
                // print_r('</pre>');
            }
            else {
                $message = $this->global_lib->generateMessage("Some problem occurred when trying sign in with twitter account, please try again later.", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('page/login');
            }
        }
        else {
            //Fresh authentication
            $connection = new TwitterOAuth($consumer_key, $consumer_secret);
            $request_token = $connection->getRequestToken($oauth_callback);
            if ($connection->http_code == '200') {
                //Get twitter oauth url
                $twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
                redirect($twitter_url);
            }
            else {
                $message = $this->global_lib->generateMessage("Error connecting to twitter. try again later.", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('page/login');
            }
        }
    }

    public function linkedinLoginCallback()
    {
        $data = array();
        $oauth_init = $this->input->get('oauth_init');
        $oauth_token = $this->input->get('oauth_token');
        $oauth_verifier = $this->input->get('oauth_verifier');
        $code = $this->input->get('code');
        $state = $this->input->get('state');

        if ((isset($oauth_init) && $oauth_init == 1) || (isset($oauth_token) && isset($oauth_verifier)) || (isset($code) && isset($state))) {
            if ($this->linkedin->authenticate()) {
                // Get the user account info
                $linkendin_user = $this->linkedin->getUserInfo();
                $linkedin_first_name = !empty($linkendin_user['account']->firstName->localized->en_US) ? $linkendin_user['account']->firstName->localized->en_US : '';
                $linkedin_last_name = !empty($linkendin_user['account']->lastName->localized->en_US) ? $linkendin_user['account']->lastName->localized->en_US : '';
                $linkedin_name = $linkedin_first_name . ' ' . $linkedin_last_name;

                $data['oauth_provider'] = 'linkedin|';
                $data['oauth_uid_linkedin'] = !empty($linkendin_user['account']->id) ? $linkendin_user['account']->id : '';
                $data['name'] = !empty($linkedin_name) ? $linkedin_name : '';
                $data['email'] = !empty($linkendin_user['email']->elements[0]->{'handle~'}->emailAddress) ? $linkendin_user['email']->elements[0]->{'handle~'}->emailAddress : '';
                $data['picture'] = !empty($linkendin_user['account']->profilePicture->{'displayImage~'}->elements[0]->identifiers[0]->identifier) ? $linkendin_user['account']->profilePicture->{'displayImage~'}->elements[0]->identifiers[0]->identifier : '';

                //check user by email.
                //if data already exist: update, if not insert new user to database
                if (isset($data['email']) && strlen(trim($data['email'])) > 0) {
                    $user = $this->mdl_user->getUserByAllEmail($data['email']);
                    if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                        //update data..
                        $update_data = array(
                            'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
                            'oauth_uid_linkedin' => $data['oauth_uid_linkedin'],
                            'email_linkedin' => $data['email'],
                            'picture_from' => 'linkedin',
                            'picture' => $data['picture']
                        );
                        $this->mdl_user->updateUser($update_data, $user[0]->id_user);
                    }
                    else {
                        //generate hash string
                        $hash = $this->global_lib->generateHash();
                        $hash .= date('YmdHisu');

                        $insert_data = array(
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'email_linkedin' => $data['email'],
                            'oauth_provider' => $data['oauth_provider'],
                            'oauth_uid_linkedin' => $data['oauth_uid_linkedin'],
                            'picture_from' => 'linkedin',
                            'picture' => $data['picture'],
                            'status' => 1,
                            'confirm_email' => 1,
                            'hash' => $hash
                        );
                        $this->mdl_user->insertUser($insert_data);
                        $user = $this->mdl_user->getUserByHash($hash);
                    }
                    if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                        $this->setUserSession($user);
                    }
                }
                else {
                    if (isset($data['oauth_uid_linkedin']) && strlen(trim($data['oauth_uid_linkedin'])) > 0) {
                        $user = $this->mdl_user->getUserByLinkedinUid($data['oauth_uid_linkedin']);
                        if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                            //update data..
                            $update_data = array(
                                'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
                                'oauth_uid_linkedin' => $data['oauth_uid_linkedin'],
                                'email_linkedin' => $data['email'],
                                'picture_from' => 'linkedin',
                                'picture' => $data['picture']
                            );
                            $this->mdl_user->updateUser($update_data, $user[0]->id_user);
                        }
                        else {
                            //generate hash string
                            $hash = $this->global_lib->generateHash();
                            $hash .= date('YmdHisu');

                            $insert_data = array(
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'email_linkedin' => $data['email'],
                                'oauth_provider' => $data['oauth_provider'],
                                'oauth_uid_linkedin' => $data['oauth_uid_linkedin'],
                                'picture_from' => 'linkedin',
                                'picture' => $data['picture'],
                                'status' => 1,
                                'confirm_email' => 1,
                                'hash' => $hash
                            );
                            $this->mdl_user->insertUser($insert_data);
                            $user = $this->mdl_user->getUserByHash($hash);
                        }
                        if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
                            $this->setUserSession($user);
                        }
                    }
                }
                redirect('page/login');
            }
            else {
                echo 'Error connecting to LinkedIn. try again later. <br/>' . $this->linkedin->client->error;
                $message = $this->global_lib->generateMessage("Error connecting to LinkedIn. " . $this->linkedin->client->error, "danger");
                $this->session->set_flashdata('message', $message);
                redirect('page/login');
            }
        }
        else {
            $message = $this->global_lib->generateMessage("Error connecting to LinkedIn. try again later.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('page/login');
        }
    }

    public function logout()
    {
        if ($this->session->userdata('user_logged_in') !== true) {
            redirect("page/login");
        }

        $login_data = array(
            'id_user' => null,
            'user_email' => null,
            'user_name' => null,
            'user_point' => null,
            'user_gender' => null,
            'user_contact_number' => null,
            'user_picture' => null,
            'user_logged_in' => false,
            'user_login_time' => null
        );
        $this->session->set_userdata($login_data);
        $this->session->sess_destroy();
        redirect("page/login");
    }

    private function setUserSession($user = array())
    {
        //add session login.
        $session_data = array(
            'id_user' => $user[0]->id_user,
            'user_email' => $user[0]->email,
            'user_name' => $user[0]->name,
            'user_point' => $user[0]->point,
            'user_gender' => $user[0]->gender,
            'user_contact_number' => $user[0]->contact_number,
            'user_picture_from' => $user[0]->picture_from,
            'user_picture' => $user[0]->picture,
            'user_logged_in' => true,
            'user_login_time' => date('Y-m-d H:i:s')
        );
        $this->session->set_userdata($session_data);

        //debugging
        // print_r("<pre>");
        // print_r($this->session->all_userdata());
        // print_r("</pre>");
    }
}
