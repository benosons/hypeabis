<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Page
 *
 * @property Mdl_content2 $mdl_content2
 */
class Page extends CI_Controller
{
    private $amp_link;

    var $js = [];
    var $css = [];
    var $category_index = 0;
    var $category_list = array();
    var $pagination_per_page = 12;

    public $per_page_comment = 10;

    public function __construct()
    {
        parent::__construct();

        //load config
        $this->load->config('facebook');
        $this->load->config('google');
        $this->load->config('twitter');
        $this->load->config('linkedin');

        //load library..
        $this->load->library('frontend_lib');
        $this->load->library('ads_lib');
        //add>>
        $this->load->helper('security');
        $this->load->library('form_validation');
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
        $this->load->model('mdl_content');
        $this->load->model('mdl_sponsoredcontent');
        $this->load->model('mdl_category');
        $this->load->model('mdl_job');
        $this->load->model('mdl_jobfield');
        $this->load->model('mdl_interest');
        $this->load->model('mdl_competition');
    }

    public function index()
    {
        $controller = $this->uri->segment(1);
        $function = $this->uri->segment(2);

        if (strtolower($controller) == 'page') {
            redirect(base_url());
        }

        $data = array();
        $this->session->set_userdata(array('keyword' => ''));

        //ambil ads homepage..
        $data['ads'] = $this->ads_lib->getHomepageAds();
        $has_newest_section_ads = (
            (isset($data['ads']['md_rec1_hm']['builtin'][0]['id_ads']) && $data['ads']['md_rec1_hm']['builtin'][0]['id_ads'] > 0)
            || (isset($data['ads']['md_rec1_hm']['googleads'][0]['id_ads']) && $data['ads']['md_rec1_hm']['googleads'][0]['id_ads'] > 0)
        );

        //ambi featured article .._
        $data['featured_article'] = $this->mdl_content->getHomepageFeaturedArticle(6);
        $data['featured_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(1);
        //trending article
        $data['trending_article'] = $this->mdl_content->getHomepageTrendingArticle();
        //newest article (Gres)
        $data['newest_article'] = $this->mdl_content->getHomepageNewestArticle($has_newest_section_ads ? 10 : 14);
        $data['newest_article_see_more_limit'] = $has_newest_section_ads ? 4 : 6;
        $data['newest_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(2);
        //recommended (Buah Bibir)
        $data['recommended_article'] = $this->mdl_content->getHomepageRecommendedArticle();
        //$data['recommended_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(5);
        $data['recommended_big_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(3);
        $data['recommended_small_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(4);
        //terpopuler
        $data['popular_article'] = $this->mdl_content->getHomepagePopularArticle(12);
        $data['popular_article_see_more_limit'] = 6;
        $data['popular_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(5);
        //$data['popular_big_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(6);
        //$data['popular_small_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(7);
        //penulis pilihan..
        $data['featured_author'] = $this->mdl_content->getHomepageFeaturedAuthor(3);
        //most commented
        $data['commented_article'] = $this->mdl_content->getCommentedArticle();
        //unread articlr
        $data['unread_article'] = $this->getUnreadArticle();

        $this->load->model('mdl_photo');
        $this->load->library('recaptcha');
        $pinned_photo = $this->mdl_photo->join_competition(null)->with_user_like($this->session->userdata('id_user'))->get_pinned();
        $data['photo_contents'] = $this->mdl_photo
            ->join_competition(null)
            ->except_id_content($pinned_photo->id_content ?? NULL)
            ->with_user_like($this->session->userdata('id_user'))
            ->all_published_for_homepage(6);

        if ($pinned_photo) {
            array_splice($data['photo_contents'], 1, 0, [$pinned_photo]);
        }

        $this->load->model('mdl_poll');
        $data['polls'] = $this->mdl_poll->all_published(12);
        $data['polls_see_more_limit'] = 6;
        $data['polls_count'] = $this->mdl_poll->count_published();

        $this->load->model('mdl_quiz');
        $data['quizzes'] = $this->mdl_quiz->all_published(12);
        $data['quizzes_see_more_limit'] = 6;
        $data['quiz_count'] = $this->mdl_quiz->count_published();
        $data['total_quiz_active'] = $this->mdl_quiz->getTotalQuizActive();

        $this->load->model('mdl_shoppable');
        $data['shoppables'] = $this->mdl_shoppable->all_published_for_homepage(6);
        $data['shoppable_count'] = $this->mdl_shoppable->count_published_for_homepage();

        $data['home_layout_type'] = $this->mdl_global->getHomeLayoutType();

        $this->amp_link = base_url() . 'amp';

        //load view
        $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/frontend/js/home.js"></script>';
        $content = $this->load->view('frontend/home', $data, true);
        $this->render($content);

        $this->mdl_sponsoredcontent->increase_active_view();
        // $this->ads_lib->test();
        // print_r('<pre>');
        // print_r($data['ads']);
        // print_r('</pre>');
    }

    public function contents()
    {
        $models = [
            'articles' => 'mdl_article',
            'popular' => 'mdl_article',
            'polls' => 'mdl_poll',
            'quizzes' => 'mdl_quiz',
            'hypeshop' => 'mdl_shoppable',
            'hypephoto' => 'mdl_photo',
            'search-articles' => 'mdl_article',
        ];
        $asset_folders = [
            'articles' => 'content',
            'popular' => 'content',
            'polls' => 'poll',
            'quizzes' => 'quiz',
            'hypeshop' => 'shoppable',
            'hypephoto' => 'photo',
            'search-articles' => 'content',
        ];
        $urls = [
            'articles' => 'read',
            'popular' => 'read',
            'polls' => 'poll',
            'quizzes' => 'quiz',
            'hypeshop' => 'shoppable',
            'hypephoto' => '#',
            'search-articles' => 'read',
        ];

        $this->session->set_userdata(['keyword' => '']);
        $this->load->model('mdl_content2');

        $model_name = $models[$this->uri->segment(2)];
        $is_popular = $this->uri->segment(2) == 'popular';
        $this->load->model($model_name);
        $model = $this->$model_name;

        $id_user = $this->input->get('author', true);
        $data = [
            'is_articles' => in_array($this->uri->segment(2), ['articles', 'popular']),
            'disable_search' => $this->uri->segment(2) != 'search-articles',
            'search_param' => [
                'title' => $this->input->get('title', true) ?: '',
                'author' => $id_user ?: '',
                'author_name' => $id_user ? $this->mdl_user->getNameByID($id_user) : '',
                'start_date' => $this->input->get('start_date', true) ?: '',
                'finish_date' => $this->input->get('finish_date', true) ?: ''
            ],
            'meta' => ['title' => 'Daftar Artikel']
        ];

        if ($data['is_articles']) {
            $data['search_param']['category'] = $this->input->get('category', true) ?: '';
            $data['categories'] = $this->getAllCategory();
        }

        $function_url = $this->uri->segment(2);
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url() . "page/{$this->uri->segment(2)}/";
        $config['per_page'] = ($this->pagination_per_page);
        $config['uri_segment'] = 3;
        $config['total_rows'] = $data['total_row'] = $model->search($data['search_param'])->popular($is_popular)->count_published();
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        $data['assets_url'] = base_url() . 'assets/' . $asset_folders[$this->uri->segment(2)];
        $data['content_base_url'] = base_url() . $urls[$this->uri->segment(2)];

        $data['contents'] = $model->search($data['search_param'])
            ->with_user_like($this->session->userdata('id_user'))
            ->popular($is_popular)->all_published(
                $config['per_page'],
                ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0)
            );

        if ($data['is_articles']) {
            $this->amp_link = base_url() . 'amp/articles';
        }

        if (strtolower($function_url) == 'hypephoto') {
            $this->load->library('recaptcha');

            foreach ($data['contents'] as $content) {
                $content->total_comments = $model->count_all_comments($content->id_content);
                $content->comments = $model->all_comments($content->id_content, $this->per_page_comment);
            }

            $view = 'frontend/contents-hypephoto';
        }
        elseif (strtolower($function_url) == 'hypeshop') {
            $view = 'frontend/contents-hypeshop';
        }
        else {
            $view = 'frontend/contents';
        }

        $this->render($this->load->view($view, $data, true));
    }

    public function article_authors($id_user = null)
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url() . 'page/articles');
        }

        if (is_null($id_user)) {
            $data = ['results' => $this->mdl_user->searchUserSelect2($this->input->get('q', TRUE))];
        }
        else {
            $data = $this->mdl_user->getUserByIDSelect2($id_user);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function submitSearch()
    {
        $this->session->set_userdata(array('keyword' => ''));
        $this->form_validation->set_rules('keyword', '', 'htmlentities|strip_tags|trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Login gagal. Silakan coba kembali.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        else {
            $keyword = $this->input->post('keyword');
            $this->session->set_userdata(array('keyword' => $keyword));
            redirect('page/search');
        }
    }

    public function submitSearchAmp()
    {
        $this->session->set_userdata(['keyword' => '']);
        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('keyword', '', 'htmlentities|strip_tags|trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            redirect(base_url());
        }
        else {
            $keyword = $this->input->get('keyword');
            $this->session->set_userdata(array('keyword' => $keyword));
            redirect('page/search');
        }
    }

    public function search()
    {
        $this->session->set_userdata(['keyword' => '']);
        $this->load->model('mdl_content2');

        $id_user = $this->input->get('author', true);
        $category = $this->input->get('category', true);

        $data = [
            'search_param' => [
                'title' => $this->input->get('title', true) ?: '',
                'author' => $id_user ?: '',
                'author_name' => $id_user ? $this->mdl_user->getNameByID($id_user) : '',
                'category' => !in_array($category, ['hypephoto', 'hypeshop', 'hypevirtual']) ? $category : '',
                'hypeshop' => $category === 'hypeshop',
                'hypevirtual' => $category === 'hypevirtual',

                'hypephoto' => $category === 'hypephoto',
                'start_date' => $this->input->get('start_date', true) ?: '',
                'finish_date' => $this->input->get('finish_date', true) ?: ''
            ],
            'meta' => ['title' => 'Daftar Konten'],
            'categories' => $this->getAllCategory(),
        ];

        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url('search');
        $config['per_page'] = $this->pagination_per_page;
        $config['uri_segment'] = 2;
        $config['total_rows'] = $data['total_row'] = $this->mdl_content2
            ->search($data['search_param'])
            ->count_all_type_published();
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        $data['contents'] = $this->mdl_content2
            ->search($data['search_param'])
            ->with_user_like($this->session->userdata('id_user'))
            ->all_type_published(
                $config['per_page'],
                ($this->uri->segment(2) > 0 ? $this->uri->segment(2) : 0),
                TRUE
            );

        $this->render(
            $this->load->view('frontend/search/index', $data, true)
        );
    }

    public function login($redirect_url = '')
    {
        //jika sudah login, redirect ke dashboard..
        if ($this->session->userdata('user_logged_in') === true) {

            $redirect_url = $this->session->userdata('redirect_url');
            $this->session->set_userdata(array(
                'redirect_url' => ''
            ));
            if (isset($redirect_url) && strlen($redirect_url) == 0) {
                redirect("user_dashboard/index");
            }
            else {
                try {
                    redirect(decodeUrl($redirect_url));
                }
                catch (Exception $e) {
                    redirect("user_dashboard/index");
                }
            }
            redirect('user_dashboard/index');

        }

        //get facebooh auth URL.
        $this->facebook->destroy_session();
        $data['fb_auth_url'] = $this->facebook->login_url();

        //get google auth URL.
        $data['google_auth_url'] = $this->config->item('google_redirect_url');

        //get twitter auth URL.
        // $data['twitter_auth_url'] = $this->config->item('twitter_redirect_url');

        //get linkedin auth url
        // $data['linkedin_auth_url'] = $this->config->item('linkedin_redirect_url') . '?oauth_init=1';

        //load library recaptcha.
        $this->load->library('recaptcha');

        $data['redirect_url'] = $redirect_url;
        $this->session->set_userdata(array(
            'redirect_url' => $redirect_url
        ));

        //load view
        $content = $this->load->view('frontend/login', $data, true);
        $this->render($content);
        // print_r("<pre>");
        // print_r($data['fb_auth_url']);
        // print_r("</pre>");
    }

    public function validateLogin($redirect_url = '')
    {
        // $email = $this->input->post('email');
        // var_dump($email);die();
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("Login gagal. Silakan coba kembali.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('page/login/' . $redirect_url);
        }
        else {
            $email = $this->input->post('email');
            $password = sha1($this->input->post('password') . $this->config->item('binary_salt'));

            //cek captcha..
            $this->load->library('recaptcha');
            $captcha_answer = $this->input->post('g-recaptcha-response');
            $response = $this->recaptcha->verifyResponse($captcha_answer);
            // Processing captcha ...
            if (!$response['success']) {
                $message = $this->global_lib->generateMessage("Please check the recaptcha for validation", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('page/login/' . $redirect_url);
            }

            $user_check = $this->mdl_user->validateLogin($email, $password);
            if ($user_check == 1) { // login berhasil
                $user = $this->mdl_user->getUserByEmailAndPassword($email, $password);
                $session_data = array(
                    'id_user' => $user[0]->id_user,
                    'user_email' => $user[0]->email,
                    'user_name' => $user[0]->name,
                    'user_gender' => $user[0]->gender,
                    'user_point' => $user[0]->point,
                    'user_verified' => $user[0]->verified,
                    'user_internal' => $user[0]->is_internal,
                    'user_contact_number' => $user[0]->contact_number,
                    'user_picture_from' => $user[0]->picture_from,
                    'user_picture' => (isset($user[0]->picture) && strlen(trim($user[0]->picture)) > 0 ? $user[0]->picture : ''),
                    'user_logged_in' => true,
                    'user_login_time' => date('Y-m-d H:i:s')
                );
                $this->session->set_userdata($session_data);
                $this->mdl_user->updateUser(['last_login_at' => date('Y-m-d H:i:s')], $user[0]->id_user);

                $redirect_url = $this->session->userdata('redirect_url');
                $this->session->set_userdata(array(
                    'redirect_url' => ''
                ));

                if (strlen($redirect_url) == 0) {
                    redirect("user_dashboard/index");
                }
                else {
                    try {
                        redirect(decodeUrl($redirect_url));
                    }
                    catch (Exception $e) {
                        redirect("user_dashboard/index");
                    }
                }
            }
            else {
                if ($user_check == 2) { // login benar, tetapi akun belum aktivasi email
                    $message = $this->global_lib->generateMessage("Anda harus mengaktifkan akun anda terlebih dahulu. Silakan check email anda dan klik link konfirmasi yang kami kirimkan. <br/><br/>Apabila anda tidak menerima email dari kami, mohon check juga folder spam email anda, atau hubungi kami untuk bantuan lebih lanjut.", "warning");
                }
                else {
                    if ($user_check == 3) { // login benar, akun belum di aktifkan
                        $message = $this->global_lib->generateMessage("Your account is non active. Contact us to follow up you account status.", "danger");
                    }
                    else {
                        if ($user_check == 4) { // login benar, akun di banned
                            $message = $this->global_lib->generateMessage("Your account has been banned by admin due to several reasons. Please contact our help center for more information.", "danger");
                        }
                        else { // login salah
                            $message = $this->global_lib->generateMessage("Login gagal. Silakan coba kembali.", "danger");
                        }
                    }
                }
            }

            $this->session->set_flashdata('message', $message);
            redirect('page/login/' . $redirect_url);
        }
    }

    public function signup($redirect_url = '')
    {
        //jika sudah login, redirect ke dashboard..
        if ($this->session->userdata('user_logged_in') === true) {
            redirect("user_dashboard");
        }

        $data['redirect_url'] = $redirect_url;

        //load library recaptcha.
        $this->load->library('recaptcha');

        //ambil job, jobfield, dan interest
        $data['job'] = $this->mdl_job->getAllJob();
        $data['jobfield'] = $this->mdl_jobfield->getAllJobfield();
        $data['interest'] = $this->mdl_interest->getAllInterest();

        //load view
        $content = $this->load->view('frontend/signup', $data, true);
        $this->render($content);
    }

    public function submitSignup($redirect_url = '')
    {
        $this->form_validation->set_rules('name', '', 'htmlentities|strip_tags|trim|required|xss_clean');
        $this->form_validation->set_rules('email', '', 'htmlentities|strip_tags|trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('contact_number', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_job', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_jobfield', '', 'htmlentities|strip_tags|trim|xss_clean');
        $this->form_validation->set_rules('id_interest', '', 'htmlentities|strip_tags|trim|xss_clean|required');
        $this->form_validation->set_rules('password', '', 'htmlentities|strip_tags|trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password', '', 'htmlentities|strip_tags|trim|required|xss_clean|matches[password]');

        if ($this->form_validation->run() == false) {
            $message = $this->global_lib->generateMessage("Gagal membuat akun. Mohon isi form dengan format yang benar dan lengkap.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('page/signup/' . $redirect_url);
        }
        else {
            //cek captcha..
            $this->load->library('recaptcha');
            $captcha_answer = $this->input->post('g-recaptcha-response');
            $response = $this->recaptcha->verifyResponse($captcha_answer);
            // Processing captcha ...
            if (!$response['success']) {
                $message = $this->global_lib->generateMessage("Please check the recaptcha for validation", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('page/signup/' . $redirect_url);
            }

            //cek email sudah ada yang pakai / belum.
            $email = $this->input->post('email');
            $cek = $this->mdl_user->checkUserByEmail($email);
            if ($cek) {
                $message = $this->global_lib->generateMessage("Email sudah pernah digunakan. Silakan login atau gunakan email yang lain.", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('page/signup/' . $redirect_url);
            }

            //generate hash string
            $hash = $this->global_lib->generateHash();
            $hash .= date('YmdHisu');
            $now = date('Y-m-d H:i:s');

            //insert data ke database..
            $insert_data = array(
                'oauth_provider' => 'web|',
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                // 'contact_number' => $this->input->post('contact_number'),
                // 'id_job' => $this->input->post('id_job'),
                // 'id_jobfield' => $this->input->post('id_jobfield'),
                'id_interest' => $this->input->post('id_interest'),
                'password' => sha1($this->input->post('password') . $this->config->item('binary_salt')),
                'status' => 1,
                'confirm_email' => 0,
                'hash' => $hash,
                'created' => $now,
                'modified' => $now,
            );
            $this->mdl_user->insertUser($insert_data);

            //kirim email konfirmasi
            $this->load->library('email_lib');
            $config = array(
                'verification_key' => $hash,
                'email' => $this->input->post('email'),
            );
            $send_email = $this->email_lib->signup($config, false);

            $message = $this->global_lib->generateMessage("Please check your email to activate your account.", "info");
            $this->session->set_flashdata('message', $message);
            redirect('page/login');
        }
    }

    public function activation($hash = '')
    {
        //jika sudah login, redirect ke dashboard..
        if ($this->session->userdata('user_logged_in') === true) {
            redirect("user_dashboard");
        }

        if ($hash == null || strlen(trim($hash)) <= 0) {
            redirect('page/signup');
        }

        //cek user bedasarkan hash..
        $check_user = $this->mdl_user->checkUserByHash($hash);
        if ($check_user) {
            //jika user ditemukan di database, ambil data user..
            $user_data = $this->mdl_user->getUserByHash($hash);
            if ($user_data[0]->confirm_email != 1) {
                //aktifasi user jadi aktif, dan update kuka point..
                $update_data = array(
                    'confirm_email' => 1,
                );
                $this->mdl_user->updateUser($update_data, $user_data[0]->id_user);
            }
            $message = $this->global_lib->generateMessage("Akun anda sudah aktif. Silakan login menggunakan email dan password anda.", "info");
            $this->session->set_flashdata('message', $message);
            redirect('page/login');
        }
        else {
            //jika user tidak ada di database (hash tidak valid)..
            redirect('page/signup');
        }
    }

    public function forgot($redirect_url = '')
    {
        $data['redirect_url'] = $redirect_url;

        //load view
        $content = $this->load->view('frontend/forgot', $data, true);
        $this->render($content);
    }

    public function submitForgotPassword()
    {
        $this->form_validation->set_rules('email', 'email', 'htmlentities|strip_tags|trim|required|xss_clean|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $message = $this->global_lib->generateMessage("<strong>Form validation failed. </strong>Please make sure the email you enter are in the right format.", "danger");
            $this->session->set_flashdata('message', $message);
            redirect('page/forgot');
        }
        else {
            //ambil data user by email.
            $email = $this->input->post('email');
            $user = $this->mdl_user->getUserByEmail($email);

            if (isset($user[0]->id_user) && $user[0]->id_user > 0) {
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

                $message = $this->global_lib->generateMessage("Silakan cek email anda untuk me-reset password", "info");
                $this->session->set_flashdata('message', $message);
                redirect('page/forgot');
            }
            else {
                $message = $this->global_lib->generateMessage("Email tidak terdaftar", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('page/forgot');
            }
        }
    }

    public function resetPassword($hash = '')
    {
        //jika sudah login, redirect ke dashboard..
        if ($this->session->userdata('user_logged_in') === true) {
            redirect("user_dashboard");
        }

        if ($hash == null || $hash == '') {
            redirect('user');
        }

        //cek user bedasarkan hash..
        $check_user = $this->mdl_user->checkUserByHash($hash);
        if ($check_user) {
            //jika user ditemukan di database, ambil data user..
            $user = $this->mdl_user->getUserByHash($hash);

            //cek limit URL forgot password.
            $now = strtotime(date('Y-m-d H:i:s'));
            if ($now <= strtotime($user[0]->forgot_password_limit)) {
                $content = $this->load->view('frontend/reset_password', array(), true);
                $this->render($content);
            }
            else {
                $message = $this->global_lib->generateMessage("URL reset password anda telah expired. Silakan lakukan permintaan reset password ulang.", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('page/forgot');
            }
        }
        else {
            redirect(base_url());
        }
    }

    public function submitResetPassword($hash = '')
    {
        //jika sudah login, redirect ke dashboard..
        if ($this->session->userdata('user_logged_in') === true) {
            redirect("user_dashboard");
        }

        if ($hash == null || $hash == '') {
            redirect('user');
        }

        //cek user bedasarkan hash..
        $check_user = $this->mdl_user->checkUserByHash($hash);
        if ($check_user) {
            //validasi..
            $this->form_validation->set_rules('new_password', 'password', 'htmlentities|strip_tags|trim|required|xss_clean');
            $this->form_validation->set_rules('confirm_password', 'password', 'htmlentities|strip_tags|trim|required|xss_clean|matches[new_password]');
            if ($this->form_validation->run() == FALSE) {
                $message = $this->global_lib->generateMessage("Gagal mengupdate password. Silakan coba kembali.", "danger");
                $this->session->set_flashdata('message', $message);
                redirect('page/resetPassword/' . $hash);
            }
            else {
                //jika user ditemukan di database, ambil data user..
                $user = $this->mdl_user->getUserByHash($hash);

                //update password..
                $new_password = $this->input->post('new_password');
                $update_data = array(
                    'password' => sha1($new_password . $this->config->item('binary_salt'))
                );
                $this->mdl_user->updateUser($update_data, $user[0]->id_user);

                $message = $this->global_lib->generateMessage("Password anda berhasil di update.", "info");
                $this->session->set_flashdata('message', $message);
                redirect('page/login');
            }
        }
        else {
            redirect('page/login');
        }
    }

    public function terms_service()
    {
        $data['global'] = $this->global_lib->getGlobalData();

        //load view
        $content = $this->load->view('frontend/terms_service', $data, true);
        $this->render($content);
    }

    public function privacy_policy()
    {
        $data['global'] = $this->global_lib->getGlobalData();

        //load view
        $content = $this->load->view('frontend/privacy_policy', $data, true);
        $this->render($content);
    }

    public function content($id_page = '', $title = '')
    {
        $data['page'] = $this->mdl_page->getPageByID($id_page);
        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['page'])) || count($data['page']) < 1) {
            redirect(base_url());
        }

        //load view
        $content = $this->load->view('frontend/page', $data, true);
        $this->render($content);
    }

    public function sitemap()
    {
        $this->load->view('sitemap/index');
    }

    public function sitemapIndex()
    {
        $url = $this->uri->segment(1);
        $url = str_replace('sitemap-', '', str_replace('.xml', '', strtolower($url)));

        if ($url == 'index') {
            //jika index, ambil kategori dan 50 artikel terakhir..
            //get category (for menu)
            $data['categories'] = $this->mdl_category->getAllCategoryParentArr();
            foreach ($data['categories'] as $x => $category) {
                $data['categories'][$x]['child'] = $this->mdl_category->getCategoryChildArr($category['id_category']);
            }
            $data['contents'] = $this->mdl_content->getPublishedContentLimit(50, 0);
            $this->load->view('sitemap/update', $data);
        }
        else {
            $is_valid_date = $this->global_lib->validateDate($url, 'Ymd');
            if ($is_valid_date) {
                $formatted_date = $this->global_lib->formatDate($url, 'Ymd', 'Y-m-d');
                $data['contents'] = $this->mdl_content->getPublishedContentByDate($formatted_date);
                $this->load->view('sitemap/by_date', $data);
            }
            else {
                redirect(base_url());
            }
        }
    }

    public function rss()
    {
        //jika index, ambil kategori dan 50 artikel terakhir..
        //get category (for menu)
        $data['categories'] = $this->mdl_category->getAllCategoryParentArr();
        foreach ($data['categories'] as $x => $category) {
            $data['categories'][$x]['child'] = $this->mdl_category->getCategoryChildArr($category['id_category']);
        }
        $data['contents'] = $this->mdl_content->getPublishedContentLimit(20, 0);
        $this->load->view('rss/update', $data);
    }

    public function rssByDate()
    {
        $url = $this->uri->segment(1);
        $url = str_replace('rss-', '', str_replace('.xml', '', strtolower($url)));
        if (isset($url) && strlen(trim($url)) > 0) {
            $is_valid_date = $this->global_lib->validateDate($url, 'Ymd');
            if ($is_valid_date) {
                $formatted_date = $this->global_lib->formatDate($url, 'Ymd', 'Y-m-d');
                $data['contents'] = $this->mdl_content->getPublishedContentByDate($formatted_date);
                $this->load->view('rss/by_date', $data);
            }
            else {
                redirect(base_url());
            }
        }
        else {
            redirect(base_url());
        }
    }

    public function redirectAds($id_ads)
    {
        $ads = $this->mdl_ads->getAdsByID($id_ads);
        if (isset($ads[0]->id_ads) && $ads[0]->id_ads > 0) {
            //update click count ads.
            $update_data = array(
                'click_count' => ($ads[0]->click_count + 1)
            );
            $this->mdl_ads->updateAds($update_data, $ads[0]->id_ads);

            if (substr($ads[0]->redirect_url, 0, 7) !== "http://" && substr($ads[0]->redirect_url, 0, 8) !== "https://") {
                redirect("http://{$ads[0]->redirect_url}");
            }
            else {
                redirect($ads[0]->redirect_url);
            }
        }
        else {
            redirect(base_url());
        }
    }

    public function redirectSponsored($id_content)
    {
        $sponsored_content = $this->mdl_sponsoredcontent->find_published($id_content);
        if ($sponsored_content) {
            $this->mdl_sponsoredcontent->increase_click($id_content);
            redirect(base_url() . "read-sponsored/{$id_content}/" . strtolower(url_title($sponsored_content->title)));
        }
        else {
            redirect(base_url());
        }
    }

    public function merchandise()
    {
        $this->load->model('mdl_merchandise');
        $data['merchandises'] = $this->mdl_merchandise->getPublishedMerchandises();

        //load view
        $content = $this->load->view('frontend/merchandise', $data, true);
        $this->render($content);
    }

    private function getUnreadArticle()
    {
        $article = array();
        if ($this->session->userdata('user_logged_in') === true) {
            $id_user = $this->session->userdata('id_user');
            if (isset($id_user) && $id_user > 0) {
                //ambil category yg paling sering dibaca..
                $category = $this->mdl_content->getTopReadCategoryByIDUser($id_user);
                if (isset($category[0]->id_category) && $category[0]->id_category > 0) {
                    //ambil artikel yang sudah pernah dibaca..
                    $readed_article = $this->mdl_content->getReadArticleByIDUserAndIDCategory($id_user, $category[0]->id_category);
                    $exclude_id = array();
                    foreach ($readed_article as $art) {
                        array_push($exclude_id, $art->id_content);
                    }
                    //ambil article exclude dari yang sudah dibaca..
                    $article = $this->mdl_content->getUnreadArticleExcludeIDArr($exclude_id, $category[0]->id_category);
                }
            }
        }

        return $article;
    }

    private function render($page = null, $ads = array())
    {
        if (isset($page) && $page !== null) {
            //load page view
            $data['content'] = $page;

            //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer
            $data['css_files'] = $this->css;
            $data['js_files'] = $this->js;

            //load module global data
            $data['global_data'] = $this->global_lib->getGlobalData();

            //get category (for menu)
            $data['categories'] = $this->mdl_category->getAllCategoryParentArr();
            foreach ($data['categories'] as $x => $category) {
                $data['categories'][$x]['child'] = $this->mdl_category->getCategoryChildArr($category['id_category']);
            }
            $data['categories_filter'] = $this->getAllCategory();

            //ambil ads footer
            $data['ads'] = $this->ads_lib->getFooterAds();
            $data['amp_link'] = $this->amp_link;

            //check ada kompetisi aktif
            $data['is_competition_exist'] = $this->mdl_competition->getActiveCompetitionCount();

            //load view template
            $this->load->view('frontend/template', $data);
        }
        else {
            redirect(base_url());
        }
    }

    private function searchPaginationConfig($total_row)
    {
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url() . 'page/search/';
        $config['total_rows'] = $total_row;
        $config['per_page'] = $this->pagination_per_page;
        $config['uri_segment'] = 3;
        return $config;
    }

    public function tes_email()
    {
        $this->load->library('email_lib');
        $this->email_lib->test([], false);
    }

    public function tes_email2()
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'no-reply@bisnismuda.id',
            'smtp_pass' => 'BismudNoReply1234',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        // Set to, from, message, etc.
        $this->email->from('no-reply@bisnismuda.id', 'testing 2');
        $this->email->to('mail@binary-project.com');
        $this->email->subject('Testing 2 Host SSL - Posrt 465');

        //message
        $data = array(
            'subject' => 'Testing 2 Host SSL - Posrt 465',
            'email_id' => 'sdfg9783246823692346',
            'email_content' => $this->load->view('email/testing', array(), true)
        );
        $message = $this->load->view('email/template', $data, true);
        $this->email->message($message);

        $result = $this->email->send();
        print_r('<pre>');
        print_r($result);
        print_r('</pre>');
    }

    public function tes_email_tls()
    {
        // $data = array(
        // 'subject' => 'Testing TLS - 20200809 - 0643',
        // 'email_id' => 'sdfg9783246823692346',
        // 'email_content' => $this->load->view('email/testing', array(), true)
        // );
        // $message = $this->load->view('email/template', $data, true);
        $subject = "Testing TLS - " . date('Ymd') . " - " . date('Hi');
        $message = "tes sending email";

        try {
            $this->load->library("phpmailer");
            $mail = $this->phpmailer->load();

            //Mail server settings
            $mail->SMTPDebug = 2;                                         // Enable verbose debug output
            $mail->isSMTP();                                              // Set mailer to use SMTP
            // $mail->Host = 'smtp.googlemail.com';                       // Specify main and backup SMTP servers
            $mail->Host = 'smtp.gmail.com';                               // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                       // Enable SMTP authentication
            $mail->Username = 'no-reply@bisnismuda.id';                   // SMTP username
            $mail->Password = 'BismudNoReply1234';                        // SMTP password
            $mail->SMTPSecure = 'tls';                                    // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                            // TCP port to connect to

            //Recipients
            $mail->setFrom('no-reply@bisnismuda.id', 'testing SMTP TLS'); // from email
            $mail->addAddress('mail@binary-project.com');                 // send to
            $mail->addReplyTo('no-reply@bisnismuda.id');                  // set reply to

            //Content
            $mail->isHTML(true);                                          // Set email format to HTML
            $mail->Subject = $subject;                                    // email title
            $mail->Body = $message;                                    // email message body

            if (!$mail->send()) {
                print_r('<pre>');
                print_r($mail);
                print_r('</pre>');
            }
            else {
                print_r('<pre>');
                print_r($mail);
                print_r('</pre>');
            }

            $mail->clearAddresses();
            $mail->clearAttachments();
        }
        catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        }
        catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

    public function tes_email_ssl()
    {
        // $data = array(
        // 'subject' => 'Testing TLS - 20200809 - 0643',
        // 'email_id' => 'sdfg9783246823692346',
        // 'email_content' => $this->load->view('email/testing', array(), true)
        // );
        // $message = $this->load->view('email/template', $data, true);
        $subject = "Testing SSL - " . date('Ymd') . " - " . date('Hi');
        $message = "tes sending email";

        try {
            $this->load->library("phpmailer");
            $mail = $this->phpmailer->load();

            //Mail server settings
            $mail->SMTPDebug = 2;                                             // Enable verbose debug output
            $mail->isSMTP();                                                  // Set mailer to use SMTP
            $mail->Host = 'smtp.googlemail.com';                              // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                           // Enable SMTP authentication
            $mail->Username = 'no-reply@bisnismuda.id';                       // SMTP username
            $mail->Password = 'BismudNoReply1234';                            // SMTP password
            $mail->SMTPSecure = 'ssl';                                        // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                                // TCP port to connect to

            //Recipients
            $mail->setFrom('no-reply@bisnismuda.id', 'testing SMTP SSL');     // from email
            $mail->addAddress('mail@binary-project.com');                     // send to
            $mail->addReplyTo('no-reply@bisnismuda.id');                      // set reply to

            //Content
            $mail->isHTML(true);                                              // Set email format to HTML
            $mail->Subject = $subject;                                        // email title
            $mail->Body = $message;                                        // email message body

            if (!$mail->send()) {
                print_r('<pre>');
                print_r($mail);
                print_r('</pre>');
            }
            else {
                print_r('<pre>');
                print_r($mail);
                print_r('</pre>');
            }

            $mail->clearAddresses();
            $mail->clearAttachments();
        }
        catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        }
        catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

    public function tess()
    {
        $subject = "Testing TLS (success example) - " . date('Ymd') . " - " . date('Hi');
        $message = "tes sending email";

        try {
            $this->load->library("phpmailer");
            $mail = $this->phpmailer->load();

            //Mail server settings
            $mail->SMTPDebug = 2;                                         // Enable verbose debug output
            // $mail->isSMTP();                                              // Set mailer to use SMTP
            // $mail->Host = 'smtp.googlemail.com';                       // Specify main and backup SMTP servers
            $mail->Host = 'smtp.gmail.com';                               // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                       // Enable SMTP authentication
            $mail->Username = 'noreply.system.001@gmail.com';             // SMTP username
            $mail->Password = 'noreply2019.,';                            // SMTP password
            $mail->SMTPSecure = 'tls';                                    // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                            // TCP port to connect to

            //Recipients
            $mail->setFrom('noreply.system.001@gmail.com', 'testing SMTP TLS'); // from email
            $mail->addAddress('mail@binary-project.com');                 // send to
            $mail->addReplyTo('noreply.system.001@gmail.com');             // set reply to

            //Content
            $mail->isHTML(true);                                          // Set email format to HTML
            $mail->Subject = $subject;                                    // email title
            $mail->Body = $message;                                    // email message body

            if (!$mail->send()) {
                $message['type'] = 'error';
                $message['msg'] = "Mailer Error: " . $mail->ErrorInfo;
            }
            else {
                $message['type'] = 'success';
                $message['msg'] = "Message has been sent successfully";
            }

            print_r("<pre>");
            print_r($message);
            print_r("</pre>");
        }
        catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

    public function tes_email_success()
    {
        // $data = array(
        // 'subject' => 'Testing TLS - 20200809 - 0643',
        // 'email_id' => 'sdfg9783246823692346',
        // 'email_content' => $this->load->view('email/testing', array(), true)
        // );
        // $message = $this->load->view('email/template', $data, true);
        $subject = "Testing TLS (success example) - " . date('Ymd') . " - " . date('Hi');
        $message = "tes sending email dari noreply@hypeabis.id";

        try {
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
            $mail->setFrom('noreply@hypeabis.id', 'testing SMTP TLS'); // from email
            $mail->addAddress('mail@binary-project.com');                 // send to
            $mail->addReplyTo('noreply@hypeabis.id');             // set reply to

            //Content
            $mail->isHTML(true);                                          // Set email format to HTML
            $mail->Subject = $subject;                                    // email title
            $mail->Body = $message;                                    // email message body

            //Send email
            if (!$mail->send()) {
                echo "<b>Gagal</b><br/>";
            }
            else {
                echo "<b>Berhasil</b><br/>";
            }

            //debugging
            print_r('<pre>');
            print_r($mail);
            print_r('</pre>');

            $mail->clearAddresses();
            $mail->clearAttachments();
        }
        catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        }
        catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

    public function tes_email_binari()
    {
        // $data = array(
        // 'subject' => 'Testing TLS - 20200809 - 0643',
        // 'email_id' => 'sdfg9783246823692346',
        // 'email_content' => $this->load->view('email/testing', array(), true)
        // );
        // $message = $this->load->view('email/template', $data, true);
        $subject = "Testing TLS (success example) - " . date('Ymd') . " - " . date('Hi');
        $message = "tes sending email dari noreply@hypeabis.id";

        try {
            $this->load->library("phpmailer");
            $mail = $this->phpmailer->load();

            //Mail server settings
            $mail->SMTPDebug = 2;                                         // Enable verbose debug output
            $mail->isSMTP();                                              // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                               // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                       // Enable SMTP authentication
            $mail->Username = 'noreply.system.001@gmail.com';             // SMTP username
            $mail->Password = 'noreply2019.,';                            // SMTP password
            $mail->SMTPSecure = 'tls';                                    // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                            // TCP port to connect to

            //Recipients
            $mail->setFrom('noreply@hypeabis.id', 'testing SMTP TLS'); // from email
            $mail->addAddress('mail@binary-project.com');                 // send to
            $mail->addReplyTo('noreply@hypeabis.id');             // set reply to

            //Content
            $mail->isHTML(true);                                          // Set email format to HTML
            $mail->Subject = $subject;                                    // email title
            $mail->Body = $message;                                    // email message body

            //Send email
            if (!$mail->send()) {
                echo "<b>Gagal</b><br/>";
            }
            else {
                echo "<b>Berhasil</b><br/>";
            }

            //debugging
            print_r('<pre>');
            print_r($mail);
            print_r('</pre>');

            $mail->clearAddresses();
            $mail->clearAttachments();
        }
        catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        }
        catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

    public function subscribe()
    {
        //cek apakah ajax request atau bukan..
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $this->load->model('mdl_subscriber');

        $data['status'] = '';
        $data['message'] = '';
        $data['csrf_token_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token_hash'] = $this->security->get_csrf_hash();

        $this->form_validation->set_rules('email', '', 'htmlentities|strip_tags|trim|xss_clean|valid_email');

        if ($this->form_validation->run() == false) {
            $data['status'] = 'failed';
            $data['message'] = 'Gagal mendaftarkan email. Isi form dengan lengkap dan benar.';
        }
        else {
            $this->mdl_subscriber->insertSubscriber([
                'email' => $this->input->post('email'),
                'ip' => $this->input->ip_address(),
                'subscribe_date' => date('Y-m-d H:i:s'),
            ]);
            $data['status'] = 'success';
        }

        echo json_encode($data);
    }

    private function getAllCategory()
    {
        //ambil data semua module utama / parent..
        $categories = $this->mdl_category->getAllCategoryParentArr();

        //ambil semua category child.
        foreach ($categories as $x => $category) {
            $has_child = $this->mdl_category->hasChild($category['id_category']);
            $categories[$x]['has_child'] = ($has_child ? 1 : 0);

            //cek apakah punya child.
            if ($has_child) {
                $categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
            }
        }

        $level = 0;
        foreach ($categories as $x => $category) {
            $this->category_list[$this->category_index] = $category;
            $this->category_list[$this->category_index]['category_name'] = $category['category_name'];
            $this->category_index++;

            //cek apakah punya child.
            if ($category['has_child'] == 1) {
                $this->generateCategoryChildList($category['child'], $level);
            }
        }

        return $this->category_list;
    }

    private function getCategoryChild($id_category = '')
    {
        $categories = array();
        $categories = $this->mdl_category->getCategoryChildArr($id_category);

        //ambil semua category child.
        foreach ($categories as $x => $category) {
            $has_child = $this->mdl_category->hasChild($category['id_category']);
            $categories[$x]['has_child'] = ($has_child ? 1 : 0);

            //cek apakah punya child.
            if ($has_child) {
                $categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
            }
        }
        return $categories;
    }

    private function generateCategoryChildList($categories, $level)
    {
        $level++;
        $indentation = "";
        for ($x = 0; $x < $level; $x++) {
            $indentation .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        foreach ($categories as $x => $category) {
            $this->category_list[$this->category_index] = $category;
            $this->category_list[$this->category_index]['category_name'] = $indentation . $category['category_name'];
            $this->category_index++;

            //cek apakah punya child.
            if ($category['has_child'] == 1) {
                $this->generateCategoryChildList($category['child'], $level);
            }
        }
    }

    public function phpinfo()
    {
        echo phpinfo();
    }
}
