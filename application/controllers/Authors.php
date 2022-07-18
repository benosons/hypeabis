<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authors extends CI_Controller {
  
  var $js = array();
  var $css = array();
  var $category_index = 0;
  var $category_list = array();
  var $per_page_author = 12;
	public $per_page_comment = 10;
  
  public function __construct(){
    parent::__construct();
    
    //load library..
    $this->load->library('frontend_lib');
    
    //load model..
    $this->load->model('mdl_user');
    $this->load->model('mdl_content');
		$this->load->model('mdl_content2');
		$this->load->model('mdl_photo');
		$this->load->model('mdl_poll');
		$this->load->model('mdl_quiz');
    $this->load->model('mdl_category');
    $this->load->model('mdl_competition');
  }
  
  public function index($id_user = '', $name = ''){
		$id_user_session = $this->session->id_user;
		$uri_segment_4 = $this->uri->segment(4);
		$user = $this->mdl_user->getUserByIDArr($id_user);

		//jika tidak ada data, redirect ke index.
		if((! is_array($user)) || count($user) < 1){
			redirect('content/index');
		}

		$data = array(
			'user' => $user,
			'author_post' => $this->mdl_content->getPublishedContentByIDUserCount($id_user),
			'author_hypephotos' => $this->mdl_photo->where_id_user($id_user)->count_published(),
			'author_polls' => $this->mdl_poll->where_id_user($id_user)->count_published(),
			'author_quizzes' => $this->mdl_quiz->where_id_user($id_user)->count_published(),
			'author_followers' => $this->mdl_user->getTotalFollower($id_user),
			'author_followings' => $this->mdl_user->getTotalFollowing($id_user),
			'author_following_articles' => $this->mdl_content->getTotalFollowingPublishedContent($id_user),
			'is_following' => null,
			'is_following_articles' => false,
			'articles' => [],
			'related_text' => '',
			'related_users' => [],
			'article_base_url' => base_url() . 'read',
			'assets_base_url' => base_url() . 'assets/content',
		);

		$data['user'][0]['cover_url'] = $user[0]['cover'] ? base_url() . "assets/user-cover/{$data['user'][0]['cover']}" : null;;

		if ($id_user_session !== $id_user)
		{
			$data['is_following'] = $this->mdl_user->isFollowingUser($id_user_session, $id_user);
		}
    
    //ambil level user..
    $level = $this->mdl_user->getUserLevelByPoint($data['user'][0]['point']);
    if(isset($level[0]->id_level) && $level[0]->id_level > 0){
      $data['user'][0]['level'] = $level[0]->level_name;
      $data['user'][0]['bg_color'] = $level[0]->bg_color;
      $data['user'][0]['text_color'] = $level[0]->text_color;
    }
		
		$user_route_url = 'author/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/';

		if ($uri_segment_4 === 'followers' || $uri_segment_4 === 'followings')
		{
			$data['related_text'] = $uri_segment_4 === 'followers' ? 'Followers' : 'Followings';
			$data['total_row'] = $data["author_{$uri_segment_4}"];

			$config = $this->_initializePagination("{$user_route_url}/{$uri_segment_4}", $data['total_row'], 5);

			$data['related_users'] = call_user_func_array(
				[$this->mdl_user, ($uri_segment_4 === 'followers' ? 'getUserFollowers' : 'getUserFollowings')],
				[$id_user, $config['per_page'], $this->uri->segment($config['uri_segment']) ?: 0]
			);
		}
		elseif ($uri_segment_4 === 'followings-article')
		{
			$data['is_following_articles'] = true;
			$data['total_row'] = $data['author_following_articles'];
			$config = $this->_initializePagination("{$user_route_url}/{$uri_segment_4}", $data['total_row'], 5);

			$data['articles'] = $this->mdl_content->getFollowingPublishedContent(
				$id_user,
				$config['per_page'],
				$this->uri->segment($config['uri_segment']) ?: 0
			);
		}
		elseif (in_array($uri_segment_4, ['hypephotos', 'polls', 'quizzes']))
		{
			$data['total_row'] = $data["author_{$uri_segment_4}"];
			$config = $this->_initializePagination("{$user_route_url}/{$uri_segment_4}", $data['total_row'], 5);

			$names = ['hypephotos' => 'photo', 'polls' => 'poll', 'quizzes' => 'quiz'];
			$model_string = 'mdl_' . $names[$uri_segment_4];

			$data['article_base_url'] = base_url() . $names[$uri_segment_4];
			$data['assets_base_url'] = base_url() . 'assets/' . $names[$uri_segment_4];
			$data['articles'] = $this->$model_string->where_id_user($id_user)->with_user_like($this->session->userdata('id_user'))->all_published(
				$config['per_page'],
				$this->uri->segment($config['uri_segment']) ?: 0
			);
		}
		else
		{
			$data['total_row'] = $data['author_post'];
			$config = $this->_initializePagination($user_route_url, $data['total_row'], 4);

			$data['articles'] = $this->mdl_content->getPublishedContentByIDUserLimit(
				$id_user,
				$config['per_page'],
				$this->uri->segment($config['uri_segment']) ?: 0
			);
		}
    
    //jumlah post
    //dibaca && komentar
    $article = $this->mdl_content->getContentStatisticByIDUser($id_user);
    $data['author_read'] = (isset($article[0]->author_read) && $article[0]->author_read > 0 ? $article[0]->author_read : 0);
    $data['author_comment'] = (isset($article[0]->author_comment) && $article[0]->author_comment > 0 ? $article[0]->author_comment : 0);
    
    $content = $this->load->view('frontend/author', $data, true);
    $this->render($content);
  }

	public function toggleFollow($id_user, $name)
	{
		$id_user_session = $this->session->userdata('id_user');
		$action = $this->uri->segment($this->uri->total_segments());
		$name = strtolower(url_title($this->mdl_user->getNameByID($id_user)));
		
		if ($this->session->userdata('user_logged_in') !== true)
		{
      $redirect_url = rtrim(base64_encode(urlencode("author/{$id_user}/{$name}/{$action}")), "=");
			$message =  $this->global_lib->generateMessage("Silahkan login terlebih dahulu.", "danger");
			$this->session->set_flashdata('message', $message);
			redirect("page/login/" . $redirect_url);
		}
		
		if ($this->session->userdata('id_user') !== $id_user)
		{
			if ($action === 'follow')
			{
				if (!$this->mdl_user->isFollowingUser($id_user_session, $id_user))
				{
					$this->load->library('email_lib');

					$this->mdl_user->insertUserFollowed($id_user_session, $id_user);
					$followed_user_email = $this->mdl_user->getEmailByID($id_user);
					$user = $this->mdl_user->getUserByID($id_user_session);
					$is_followed_back = $this->mdl_user->isFollowingUser($id_user, $id_user_session);
					$level = $this->mdl_user->getUserLevelByPoint($user[0]->point);

					if (isset($level[0]->id_level) && $level[0]->id_level > 0)
					{
						$user[0]->level = $level[0]->level_name;
						$user[0]->bg_color = $level[0]->bg_color;
						$user[0]->text_color = $level[0]->text_color;
					}

					$send_email = $this->email_lib->sendNewFollowerEmail([
						'user' => $user[0],
						'is_following' => $is_followed_back,
						'email' => $followed_user_email
					]);
				}
			}
			else
			{
				$this->mdl_user->deleteUserFollowed($id_user_session, $id_user);
			}
		}

		redirect("author/{$id_user}/{$name}");
	}

  private function render($page = null, $meta = array()){
    if(isset($page) && $page !== null){
      //load page view
      $data['content'] = $page;
      $data['meta'] = $meta;
      
      //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
      $data['css_files'] = $this->css;
      $data['js_files'] = $this->js;
      
      //load module global data
      $data['global_data'] = $this->global_lib->getGlobalData();
      
      //get category (for menu)
      $data['categories'] = $this->mdl_category->getAllCategoryParentArr();
      foreach($data['categories'] as $x => $category){
        $data['categories'][$x]['child'] = $this->mdl_category->getCategoryChildArr($category['id_category']);
      }
      $data['categories_filter'] = $this->getAllCategory();

        $data['is_competition_exist'] = $this->mdl_competition->getActiveCompetitionCount();
      
      //load view template
      $this->load->view('frontend/template', $data);
    }
    else{
      redirect(base_url());
    }
  }
  
	private function _initializePagination($route_url, $total_rows, $page_segment_index)
	{
		$config = $this->global_lib->paginationConfig();
		$config['base_url'] 		= base_url() . $route_url;
		$config['total_rows'] 	= $total_rows;
		$config['per_page'] 		= $this->per_page_author;
		$config['uri_segment']	= $page_segment_index;

		$this->pagination->initialize($config);

		return $config;
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
}
