<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

class User_profile extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $user_pic_width = 300;
	var $user_pic_height = 300;
  var $cover_width = 1351;
	var $cover_height = 300;
  
  public function __construct(){
    parent::__construct();
		
    //load config.
    $this->load->config('facebook');
    $this->load->config('google');
    $this->load->config('twitter');
    $this->load->config('linkedin');
    
		//load library
    //linkeding auth
    $this->load->library('linkedin', array(
      'linkedin_api_key' => $this->config->item('linkedin_api_key'),
      'linkedin_api_secret' => $this->config->item('linkedin_api_secret'),
      'linkedin_redirect_url' =>  $this->config->item('linkedin_redirect_url2')
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
    $this->load->model('mdl_job');
    $this->load->model('mdl_jobfield');
    $this->load->model('mdl_interest');
    
		//construct script..
		if($this->session->userdata('user_logged_in') !== true){
      $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())) , "=");
			redirect("page/login/" . $redirect_url);
		}
  }
	
	public function index(){
    //get facebooh auth URL.
    $data['fb_auth_url'] =  $this->facebook->login_url(2);
    
    //get google auth URL.
    $data['google_auth_url'] =  $this->config->item('google_redirect_url2');
    
    //get twitter auth URL.
    $data['twitter_auth_url'] = $this->config->item('twitter_redirect_url2');
    
    //get linkedin auth url
    $data['linkedin_auth_url'] = $this->config->item('linkedin_redirect_url2') . '?oauth_init=1';
    
    //ambil data user
		$id_user = $this->session->userdata('id_user');
		$data['user'] = $this->mdl_user->getUserByID($id_user);
    $data['can_be_verified_member'] = $this->mdl_user->canBeVerifiedMember($id_user);
    
    $data['job'] = $this->mdl_job->getAllJob();
    $data['jobfield'] = $this->mdl_jobfield->getAllJobfield();
    $data['interest'] = $this->mdl_interest->getAllInterest();
		
		//load view.
    $content = $this->load->view('user/profile/detail', $data, true);
		$this->render($content);
	}
  
  public function updateUser(){
		$old_cover = $this->mdl_user->getCoverByID($this->session->userdata('id_user'));
		$old_cover_path = "assets/user-cover/{$old_cover}";

		$this->form_validation->set_rules('name','', 'htmlentities|strip_tags|trim|xss_clean|required');
		$this->form_validation->set_rules('profile_desc','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('gender','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('tempat_lahir','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('dob','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('contact_number','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('address','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('facebook','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('twitter','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('instagram','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('picture','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('cover','', 'htmlentities|strip_tags|trim|xss_clean');
		$this->form_validation->set_rules('id_job','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('id_jobfield','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		$this->form_validation->set_rules('id_interest','', 'htmlentities|strip_tags|trim|xss_clean|integer');
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('user_profile/index');
		}
		else{
      // --- START uploading picture without thumbnail
			$picture = '';
			if (!empty($_FILES['picture']['name'])){
				$config = $this->userPicUploadConfig($_FILES['picture']['name']);
				$this->upload->initialize($config);

				//upload file article..
				if ($this->upload->do_upload('picture')){
					$upload_data = array('upload_data' => $this->upload->data());
					$picture = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->user_pic_width, $this->user_pic_height);
				}
        else{
          $message =  $this->global_lib->generateMessage("Failed to upload picture. <br/> cause: " . $this->upload->display_errors(), "danger");
          $this->session->set_flashdata('message', $message);
          redirect('user_profile/index');
        }
			}
      
			$coverFilename = trim(($_FILES['cover']['name']));
			if (!empty($coverFilename))
			{
				$coverFilename = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $coverFilename);
				$this->upload->initialize(array(
					'upload_path'   => './assets/user-cover/',
					'allowed_types' => 'jpg|jpeg|png|gif',
					'max_size'      => '12000',
					'max_width'     => '8000',
					'max_height'    => '8000',
					'file_name'     => $coverFilename
				));

				//upload file article..
				if ($this->upload->do_upload('cover')){
					$upload_data = array('upload_data' => $this->upload->data());
					$newCoverFilename = $this->picture->resizePhoto($upload_data['upload_data']['full_path'], $this->cover_width, $this->cover_height);
					if (file_exists("assets/user-cover/{$coverFilename}")) {
						unlink("assets/user-cover/{$coverFilename}");
					}

					$coverFilename = $newCoverFilename;
				}
        else{
          $message =  $this->global_lib->generateMessage("Failed to upload cover. <br/> cause: " . $this->upload->display_errors(), "danger");
          $this->session->set_flashdata('message', $message);
          redirect('user_profile/index');
        }
			}
			else
			{
				$coverFilename = $old_cover;
      }
      
      $dob = null;
      $dob = $this->input->post('dob');
      $is_valid_date = $this->global_lib->validateDate($dob, 'd-m-Y');
      if($is_valid_date){
        $dob = $this->global_lib->formatDate($dob, 'd-m-Y', 'Y-m-d');
      }
      
      $session_data = array(
        "user_name" => $this->input->post('name'),
				"user_contact_number" => $this->input->post('contact_number'),
				"user_gender" => $this->input->post('gender'),
      );
			$update_data = array(
				"name" => $this->input->post('name'),
        "profile_desc" => $this->input->post('profile_desc'),
        "gender" => $this->input->post('gender'),
				"tempat_lahir" => $this->input->post('tempat_lahir'),
				"dob" => $dob,
				"contact_number" => $this->input->post('contact_number'),
				"address" => $this->input->post('address'),
				"id_job" => $this->input->post('id_job'),
				"id_jobfield" => $this->input->post('id_jobfield'),
				"id_interest" => $this->input->post('id_interest'),
				"facebook" => str_replace('http://', '', str_replace('https://', '', $this->input->post('facebook'))),
				"twitter" => str_replace('http://', '', str_replace('https://', '', $this->input->post('twitter'))),
				"instagram" => str_replace('http://', '', str_replace('https://', '', $this->input->post('instagram'))),
				'cover' => $coverFilename
			);
			if(strlen(trim($picture)) > 0){
				$update_data['picture_from'] = 'web';
				$update_data['picture'] = $picture;
        $session_data['user_picture_from'] = 'web';
				$session_data['user_picture'] = $picture;
        //ganti global_logo di session..
				$this->session->set_userdata(array("user_picture" => $picture));
			}
      
      //update db
			$this->mdl_user->updateUser($update_data, $this->session->userdata('id_user'));
      //update session
      $this->session->set_userdata($session_data);
      
			if (!empty($old_cover) && $old_cover !== $coverFilename && file_exists($old_cover_path))
			{
				unlink($old_cover_path);
			}
      
      $message =  $this->global_lib->generateMessage("Your account has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('user_profile/index');
		}
	}
  
  public function updateAccount(){
    //ambil data user
		$user = $this->mdl_user->getUserByID($this->session->userdata('id_user'));
    //jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('user_profile/index');
		}
    
    //validate input
    $this->form_validation->set_rules('email','', 'htmlentities|strip_tags|trim|xss_clean|required|valid_email');
    //jika new password di set, maka confirm password harus diisi (required)
    if(strlen(trim($this->input->post('new_password'))) > 0){
      $required = "";
      if(strlen(trim($user[0]->password)) > 0){
        $required .= "|required";
      }
      $this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|xss_clean' . $required);
      $this->form_validation->set_rules('new_password','', 'htmlentities|strip_tags|trim|xss_clean|required');
      $this->form_validation->set_rules('confirm_password','', 'htmlentities|strip_tags|trim|xss_clean|matches[new_password]|required');
    }
    else{
      $this->form_validation->set_rules('password','', 'htmlentities|strip_tags|trim|xss_clean');
      $this->form_validation->set_rules('new_password','', 'htmlentities|strip_tags|trim|xss_clean');
      $this->form_validation->set_rules('confirm_password','', 'htmlentities|strip_tags|trim|xss_clean|matches[new_password]');
    }
    
		if($this->form_validation->run() == false){
      $message =  $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect('user_profile/index');
		}
		else{
			$update_data = array();
      
      //jika email di update.
      $email = $this->input->post('email');
      if(strtolower($this->session->userdata('user_email')) != strtolower($email)){
        //check password dulu sudah di set atau belum.
        if(strlen(trim($user[0]->password)) <= 0){
          $message =  $this->global_lib->generateMessage("You need set your password first before updating your account email.", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('user_profile/index');
        }
        
        //check email masih available atau tidak di database..
        $check = $this->mdl_user->checkUserByEmail($email);
        if($check){
          $message =  $this->global_lib->generateMessage("This email already use. Please use another email.", "danger");
          $this->session->set_flashdata('message', $message);
          redirect('user_profile/index');
        }
      }
      
      $session_data = array(
				"user_email" => $this->input->post('email')
      );
      $update_data = array(
				"email" => $this->input->post('email')
			);
      
      //cek password jika ganti password..
			if(strlen(trim($this->input->post('new_password'))) > 0){
        $new_account = (strlen(trim($user[0]->password)) > 0 ? false : true);
				$old_password = sha1($this->input->post('password') . $this->config->item('binary_salt'));
        $check_password = $this->mdl_user->checkUserByIDAndPassword($this->session->userdata('id_user'), $old_password);
				
				//jika password salah, redirect ke halaman edit..
				if((! $check_password) && (! $new_account)){
          $message =  $this->global_lib->generateMessage("Wrong password. You must enter your current password for change your password.", "danger");
					$this->session->set_flashdata('message', $message);
					redirect('user_profile/index');
				}
				else{
					$update_data['password'] = sha1($this->input->post('new_password') . $this->config->item('binary_salt'));
				}
			}
      
      //update db
      $this->mdl_user->updateUser($update_data, $this->session->userdata('id_user'));
      //update session
      $this->session->set_userdata($session_data);
			
      $message =  $this->global_lib->generateMessage("Your account has been updated.", "info");
			$this->session->set_flashdata('message', $message);
			redirect('user_profile/index');
		}
	}
  
  public function deleteUserPic(){
    $update_data['picture_from'] = '';
    $update_data['picture'] = '';
    $this->mdl_user->updateUser($update_data, $this->session->userdata('id_user'));
    
    $session_data['user_picture_from'] = 'web';
    $session_data['user_picture'] = '';
    
    //ganti picture user di session..
    $this->session->set_userdata($session_data);
    
    $message =  $this->global_lib->generateMessage("Profile picture has been removed.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('user_profile/index');
  }
  
  public function deleteUserCover(){
		$old_cover = $this->mdl_user->getCoverByID($this->session->userdata('id_user'));
		$old_cover_path = "assets/user-cover/{$old_cover}";

    $this->mdl_user->updateUser(array('cover' => NULL), $this->session->userdata('id_user'));

		if (!empty($old_cover) && file_exists($old_cover_path))
		{
			unlink($old_cover_path);
		}

    $message =  $this->global_lib->generateMessage("Cover picture has been removed.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('user_profile/index');
  }
  
  public function fbLoginCallback(){
    $data = array();
    
    //ambil data user
		$user = $this->mdl_user->getUserByID($this->session->userdata('id_user'));
    //jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('user_profile/index');
		}
    
    // Check if user is logged in
    if($this->facebook->is_authenticated()){
      // Get user facebook profile details
      $fb_user = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,picture');

      // Preparing data for database insertion
      $data['oauth_provider']     = 'facebook|';
      $data['oauth_uid_facebook'] = !empty($fb_user['id'])?$fb_user['id']:'';
      $data['name']               = (!empty($fb_user['first_name'])?$fb_user['first_name']:'') . ' ' . (!empty($fb_user['last_name'])?$fb_user['last_name']:'');
      $data['email']              = !empty($fb_user['email'])?$fb_user['email']:'';
      $data['picture']            = !empty($fb_user['picture']['data']['url'])?$fb_user['picture']['data']['url']:'';
      
      if(isset($user[0]->id_user) && $user[0]->id_user > 0){
        //update data..
        $update_data = array(
          'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
          'oauth_uid_facebook' => $data['oauth_uid_facebook'],
          'email_facebook' => $data['email']
        );
        $this->mdl_user->updateUser($update_data, $user[0]->id_user);
        
        //logout facebook.
        $this->facebook->destroy_session();
      }
    }
    
    $message =  $this->global_lib->generateMessage("Your account has been connected with your Facebook account.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('user_profile/index');
  }
  
  public function googleLoginCallback(){
    $data = array();
    
    //ambil data user
		$user = $this->mdl_user->getUserByID($this->session->userdata('id_user'));
    //jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('user_profile/index');
		}
    
    $client_id = $this->config->item('google_client_id'); //Google client ID
		$client_secret = $this->config->item('google_client_secret'); //Google client secret
		$redirect_url = $this->config->item('google_redirect_url2'); //Google callback / redirect URL
		
		//Call Google API
		$gclient = new Google_Client();
		$gclient->setApplicationName('Login');
		$gclient->setClientId($client_id);
		$gclient->setClientSecret($client_secret);
		$gclient->setRedirectUri($redirect_url);
		$google_oauthV2 = new Google_Oauth2Service($gclient);
		
		if(isset($_GET['code']))
		{
			$gclient->authenticate($_GET['code']);
			$_SESSION['token'] = $gclient->getAccessToken();
			header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
		}

		if (isset($_SESSION['token'])) 
		{
			$gclient->setAccessToken($_SESSION['token']);
		}
		
		if ($gclient->getAccessToken()) { //berhasil login
      $google_user = $google_oauthV2->userinfo->get();
      
      // Preparing data for database insertion
      $data['oauth_provider']   = 'google|';
      $data['oauth_uid_google'] = !empty($google_user['id'])?$google_user['id']:'';
      $data['name']             = !empty($google_user['name'])?$google_user['name']:'';
      $data['email']            = !empty($google_user['email'])?$google_user['email']:'';
      $data['picture']          = !empty($google_user['picture'])?$google_user['picture']:'';
			
      if(isset($user[0]->id_user) && $user[0]->id_user > 0){
        //update data..
        $update_data = array(
          'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
          'oauth_uid_google' => $data['oauth_uid_google'],
          'email_google' => $data['email']
        );
        $this->mdl_user->updateUser($update_data, $user[0]->id_user);
        
        //logout google.
        unset($_SESSION['access_token']);
      }
      
      //redirect to page.
      $message =  $this->global_lib->generateMessage("Your account has been connected with your Google account.", "info");
      $this->session->set_flashdata('message', $message);
      redirect('user_profile/index');
    }
		else 
		{
      $url = $gclient->createAuthUrl();
      header("Location: $url");
      exit;
    }
  }
  
  public function twitterLoginCallback(){
    $data = array();
    
    //ambil data user
		$user = $this->mdl_user->getUserByID($this->session->userdata('id_user'));
    //jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('user_profile/index');
		}
    
    $consumer_key = $this->config->item('twitter_consumer_key');
    $consumer_secret = $this->config->item('twitter_consumer_secret');
    $oauth_callback = $this->config->item('twitter_redirect_url2');
    $oauth_token = $this->input->get('oauth_token');
    $oauth_verifier = $this->input->get('oauth_verifier');
    
    if(isset($oauth_token) && isset($oauth_verifier) && strlen(trim($oauth_token)) > 0 &&  strlen(trim($oauth_verifier)) > 0){
      //Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
      $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $consumer_secret);
      $access_token = $connection->getAccessToken($oauth_verifier);
      if($connection->http_code == '200'){
        //Get user profile info
        $twitter_user = $connection->get('account/verify_credentials', ['include_email' => "true"]);
        
        $data['oauth_provider']     = 'twitter|';
        $data['oauth_uid_twitter']  = !empty($twitter_user->id) ? $twitter_user->id : '';
        $data['name']               = !empty($twitter_user->name) ? $twitter_user->name : '';
        $data['email']              = !empty($twitter_user->email) ? $twitter_user->email : '';
        $data['picture']            = !empty($twitter_user->profile_image_url_https) ? $twitter_user->profile_image_url_https : '';
        
        if(isset($user[0]->id_user) && $user[0]->id_user > 0){
          //update
          $update_data = array(
            'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
            'oauth_uid_twitter' => $data['oauth_uid_twitter'],
            'email_twitter' => $data['email']
          );
          $this->mdl_user->updateUser($update_data, $user[0]->id_user);
        }
        
        //redirect to page.
        $message =  $this->global_lib->generateMessage("Your account has been connected with your Twitter account.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('user_profile/index');
      }
      else{
        $message =  $this->global_lib->generateMessage("Some problem occurred when trying sign in with twitter account, please try again later.", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('user_profile/index');
      }
    }
    else{
      //Fresh authentication
      $connection = new TwitterOAuth($consumer_key, $consumer_secret);
      $request_token = $connection->getRequestToken($oauth_callback);
      if($connection->http_code == '200'){
        //Get twitter oauth url
        $twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
        redirect($twitter_url);
      }
      else{
        $message =  $this->global_lib->generateMessage("Error connecting to twitter. try again later.", "danger");
        $this->session->set_flashdata('message', $message);
        redirect('user_profile/index');
      }
    }
  }
  
  public function linkedinLoginCallback(){
    $data = array();
    
    //ambil data user
		$user = $this->mdl_user->getUserByID($this->session->userdata('id_user'));
    //jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('user_profile/index');
		}
    
    $oauth_init = $this->input->get('oauth_init');
    $oauth_token = $this->input->get('oauth_token');
    $oauth_verifier = $this->input->get('oauth_verifier');
    $code = $this->input->get('code');
    $state = $this->input->get('state');
    
    if((isset($oauth_init) && $oauth_init == 1) || (isset($oauth_token) && isset($oauth_verifier)) || (isset($code) && isset($state))){
      if($this->linkedin->authenticate()){
        // Get the user account info
        $linkendin_user = $this->linkedin->getUserInfo();
        $linkedin_first_name  = !empty($linkendin_user['account']->firstName->localized->en_US)?$linkendin_user['account']->firstName->localized->en_US:'';
        $linkedin_last_name   = !empty($userInfo['account']->lastName->localized->en_US)?$userInfo['account']->lastName->localized->en_US:'';
        $linkedin_name        = $linkedin_first_name . ' ' . $linkedin_last_name;
        
        $data['oauth_provider']     = 'linkedin|';
        $data['oauth_uid_linkedin'] = !empty($linkendin_user['account']->id) ? $linkendin_user['account']->id : '';
        $data['name']               = !empty($linkedin_name) ? $linkedin_name : '';
        $data['email']              = !empty($userInfo['email']->elements[0]->{'handle~'}->emailAddress)?$userInfo['email']->elements[0]->{'handle~'}->emailAddress:'';
        $data['picture']            = !empty($userInfo['account']->profilePicture->{'displayImage~'}->elements[0]->identifiers[0]->identifier)?$userInfo['account']->profilePicture->{'displayImage~'}->elements[0]->identifiers[0]->identifier:'';
        
        //check user by email.
        //if data already exist: update, if not insert new user to database
        if(isset($user[0]->id_user) && $user[0]->id_user > 0){
          //update data..
          $update_data = array(
            'oauth_provider' => (str_replace($data['oauth_provider'], '', $user[0]->oauth_provider) . $data['oauth_provider']),
            'oauth_uid_linkedin' => $data['oauth_uid_linkedin'],
            'email_linkedin' => $data['email']
          );
          $this->mdl_user->updateUser($update_data, $user[0]->id_user);
        }
        
        //redirect to page.
        $message =  $this->global_lib->generateMessage("Your account has been connected with your LinkedIn account.", "info");
        $this->session->set_flashdata('message', $message);
        redirect('user_profile/index');
      }
      else{
        echo 'Error connecting to LinkedIn. try again later. <br/>'.$this->linkedin->client->error;
        $message =  $this->global_lib->generateMessage("Error connecting to LinkedIn. " . $this->linkedin->client->error, "danger");
        $this->session->set_flashdata('message', $message);
        redirect('user_profile/index');
      }
    }
    else{
      $message =  $this->global_lib->generateMessage("Error connecting to LinkedIn. try again later.", "danger");
      $this->session->set_flashdata('message', $message);
      redirect('user_profile/index');
    }
  }
  
  public function disconnectFacebook(){
    //ambil data user
		$user = $this->mdl_user->getUserByID($this->session->userdata('id_user'));
    //jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('user_profile/index');
		}
    
    //update data..
    $update_data = array(
      'oauth_provider' => str_replace('facebook|', '', $user[0]->oauth_provider),
      'oauth_uid_facebook' => '',
      'email_facebook' => ''
    );
    $this->mdl_user->updateUser($update_data, $this->session->userdata('id_user'));
    
    //redirect to page.
    $message =  $this->global_lib->generateMessage("Your account has been disconnected from your Facebook account.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('user_profile/index');
  }
  
  public function disconnectGoogle(){
    //ambil data user
		$user = $this->mdl_user->getUserByID($this->session->userdata('id_user'));
    //jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('user_profile/index');
		}
    
    //update data..
    $update_data = array(
      'oauth_provider' => str_replace('google|', '', $user[0]->oauth_provider),
      'oauth_uid_google' => '',
      'email_google' => ''
    );
    $this->mdl_user->updateUser($update_data, $this->session->userdata('id_user'));
    
    //redirect to page.
    $message =  $this->global_lib->generateMessage("Your account has been disconnected from your Google account.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('user_profile/index');
  }
  
  public function disconnectTwitter(){
    //ambil data user
		$user = $this->mdl_user->getUserByID($this->session->userdata('id_user'));
    //jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('user_profile/index');
		}
    
    //update data..
    $update_data = array(
      'oauth_provider' => str_replace('twitter|', '', $user[0]->oauth_provider),
      'oauth_uid_twitter' => '',
      'email_twitter' => ''
    );
    $this->mdl_user->updateUser($update_data, $this->session->userdata('id_user'));
    
    //redirect to page.
    $message =  $this->global_lib->generateMessage("Your account has been disconnected from your twitter account.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('user_profile/index');
  }
  
  public function disconnectLinkedin(){
    //ambil data user
		$user = $this->mdl_user->getUserByID($this->session->userdata('id_user'));
    //jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('user_profile/index');
		}
    
    //update data..
    $update_data = array(
      'oauth_provider' => str_replace('linkedin|', '', $user[0]->oauth_provider),
      'oauth_uid_linkedin' => '',
      'email_linkedin' => ''
    );
    $this->mdl_user->updateUser($update_data, $this->session->userdata('id_user'));
    
    //redirect to page.
    $message =  $this->global_lib->generateMessage("Your account has been disconnected from your LinkedIn account.", "info");
    $this->session->set_flashdata('message', $message);
    redirect('user_profile/index');
  }
  
  private function render($page = null){
    if(isset($page) && $page !== null){
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
    else{
      redirect('page/index');
    }
  }
  
  private function userPicUploadConfig($filename=''){
		$config['upload_path'] = './assets/user/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '12000';
		$config['max_width']  = '8000';
		$config['max_height']  = '8000';
		if(strlen(trim($filename)) > 0){
			$config['file_name']  = 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename);
		}
		return $config;
	}
	
}
