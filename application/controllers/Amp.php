<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amp extends CI_Controller {
	private $css = array();
	private $js = array();
	private $category_index = 0;
	private $category_list = array();
	private $pagination_per_page = 12;
	public $per_page_comment = 10;

	public function __construct()
	{
		parent::__construct();

		//load library..
		$this->load->library('frontend_lib');
		$this->load->library('ads_lib');

		//load model..
		$this->load->model('mdl_user');
		$this->load->model('mdl_content');
		$this->load->model('mdl_sponsoredcontent');
		$this->load->model('mdl_category');
	}

	public function index()
	{
		$controller = $this->uri->segment(1);
		$function = $this->uri->segment(2);
		if(strtolower($controller) == 'page'){
			redirect(base_url());
		}

		$data = array();
		$this->session->set_userdata(array('keyword' => ''));

		//ambi featured article
		$data['featured_article'] = $this->mdl_content->getHomepageFeaturedArticle();
		/* dump($data['featured_article']); */
		$featured_sponsored = $this->mdl_sponsoredcontent->find_active_by_position(1);

		if ($featured_sponsored)
		{
			array_splice($data['featured_article'], 3, 0, [$featured_sponsored]);
		}

		//trending article
		$data['trending_article'] = $this->mdl_content->getHomepageTrendingArticle();
		$data['trending_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(2);
		//newest article
		$data['newest_article'] = $this->mdl_content->getHomepageNewestArticle();
		$data['fresh_big_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(3);
		$data['fresh_small_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(4);
		//recommended
		$data['recommended_article'] = $this->mdl_content->getHomepageRecommendedArticle();
		$data['recommended_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(5);
		//terpopuler
		$data['popular_article'] = $this->mdl_content->getHomepagePopularArticle();
		$data['popular_big_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(6);
		$data['popular_small_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(7);
		//penulis pilihan..
		$data['featured_author'] = $this->mdl_content->getHomepageFeaturedAuthor();
		//most commented
		$data['commented_article'] = $this->mdl_content->getCommentedArticle();
		//unread articlr
		$data['unread_article'] = $this->getUnreadArticle();

		$this->load->model('mdl_poll');

		$data['polls'] = $this->mdl_poll->all_published(4);
		$data['polls_count'] = $this->mdl_poll->count_published();

		$this->load->model('mdl_quiz');

		$data['quizzes'] = $this->mdl_quiz->all_published(4);
		$data['quiz_count'] = $this->mdl_quiz->count_published();

		$this->load->model('mdl_shoppable');

		$data['shoppables'] = $this->mdl_shoppable->all_published(4);
		$data['shoppable_count'] = $this->mdl_shoppable->count_published();

		//ambil ads homepage..
		$data['ads'] = $this->ads_lib->getHomepageAds();

		$data['home_layout_type'] = $this->mdl_global->getHomeLayoutType();

		//load view
		$content = $this->load->view('amp/home', $data, true);
		$this->css[] = 'home.min.css';
		$this->js[] = '<script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>';
		$this->_render($content, base_url());

		$this->mdl_sponsoredcontent->increase_active_view();
	}

	public function articles()
	{
		$this->load->model('mdl_content2');

		$models = [
			'articles' => 'mdl_article',
			'polls' => 'mdl_poll',
			'quizzes' => 'mdl_quiz',
			'shoppables' => 'mdl_shoppable'
		];
		$asset_folders = ['articles' => 'content', 'polls' => 'poll', 'quizzes' => 'quiz', 'shoppables' => 'shoppable'];
		$urls = ['articles' => 'read', 'polls' => 'poll', 'quizzes' => 'quiz', 'shoppables' => 'shoppable'];

		$this->session->set_userdata(['keyword' => '']);
		$this->load->model('mdl_content2');

		$model_name = $models[$this->uri->segment(2)];
		$this->load->model($model_name);
		$model = $this->$model_name;

		$data = [
			'is_articles' => $this->uri->segment(2) === 'articles',
			'search_param' => [
				'author' => $this->input->get('author', true) ?: '',
				'start_date' => $this->input->get('start_date', true) ?: '',
				'finish_date' => $this->input->get('finish_date', true) ?: ''
			],
			'meta' => ['title' => 'Daftar Artikel'],
			'oldest_content_date' => $model->get_oldest_submit_date(),
		];

		if ($data['is_articles'])
		{
			$data['search_param']['category'] = $this->input->get('category', true) ?: '';
			$data['categories'] = $this->getAllCategory();
		}

		$config = $this->global_lib->paginationConfig();
		$config['base_url']    = base_url() . "page/{$this->uri->segment(2)}/";
		$config['per_page']    = $this->pagination_per_page;
		$config['uri_segment'] = 3;
		$config['total_rows']  = $data['total_row'] = $model->search($data['search_param'])->count_published();
		$config['reuse_query_string'] = true;

		$this->pagination->initialize($config);

		$data['assets_url'] = base_url() . 'assets/' . $asset_folders[$this->uri->segment(2)];
		$data['content_base_url'] = base_url() . $urls[$this->uri->segment(2)];

		$data['contents'] = $model->search($data['search_param'])->all_published(
			$config['per_page'],
			($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0)
		);

		$this->css[] = 'contents.min.css';
		$this->js[] = '<script async custom-element="amp-autocomplete" src="https://cdn.ampproject.org/v0/amp-autocomplete-0.1.js"></script>';
		$this->js[] = '<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>';
		$this->js[] = '<script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>';
		$this->js[] = '<script async custom-element="amp-date-picker" src="https://cdn.ampproject.org/v0/amp-date-picker-0.1.js"></script>';
		$content = $this->load->view('amp/contents', $data, true);
		$this->_render($content, base_url() . 'page/articles');
	}

	public function search_authors($id_user = null)
	{
		if (!$this->input->is_ajax_request()) {
			redirect(base_url() . 'page/articles');
		}

		if (is_null($id_user))
		{
			$data = ['items' => $this->mdl_user->searchUserSelect2($this->input->get('q', TRUE))];
		}
		else
		{
			$data = $this->mdl_user->getUserByIDSelect2($id_user);
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function read($params, $title = '', $page_no = '1')
	{
		$this->load->model('mdl_content2');
		$this->load->model('mdl_article');

		$model = $this->mdl_article;
		$params = explode('-', $params);
		$id_content = $params[0];
		$id_user = $this->session->userdata('id_user');
		$id_admin = $this->session->userdata('id_admin');
		$is_preview = $this->input->get('is_preview');

		$content = $is_preview ? $model->find_with_counts($id_content) : $model->find_published_with_counts($id_content);

		if (is_null($content) || $is_preview && (is_null($id_admin) || $id_user !== $content->id_user))
		{
			redirect(base_url());
		}

		$assets_url = base_url() . 'assets/content';
		$page = NULL;
		$page_no = intval($page_no);
		$max_page_no = intval($model->get_max_page_no($id_content));

		if ($page_no > 1)
		{
			redirect(
				"amp/read/{$id_content}" .
				($content->id_user ? '-' . strtolower(url_title($content->name)) : '')
				. '/' . strtolower(url_title($content->title))
			);
		}

		$comments = $model->all_comments($id_content, $this->per_page_comment);
		$liked = $id_user ? $model->has_like_from($id_content, $id_user) : FALSE;
		$reacted = $id_user ? $model->has_reaction_from($id_content, $id_user) : FALSE;
		$reactions = $model->count_all_reactions($id_content);

		$content->content = $this->_replace_with_amp_tag($content->content);

		$data = compact(
			'assets_url',
			'comments',
			'content',
			'is_preview',
			'liked',
			'max_page_no',
			'page',
			'page_no',
			'reacted',
			'reactions'
		);

		$data['ads'] = $this->ads_lib->getArticleAds();

		if ($content->show_sidebar)
		{
			if ($content->id_category && $content->type === '1')
			{
				$data['newest_article'] = $this->mdl_content->getPublishedContentByIDCategoryExcludeID($content->id_category, $id_content, 6);
				$data['commented_article'] = $this->mdl_content->getCommentedArticleByIDCategory($content->id_category);
				$data['featured_author'] = $this->mdl_content->getCategoryFeaturedAuthor($content->id_category);
			}
			elseif ($content->type !== '1')
			{
				$data['newest_article'] = $this->mdl_content->getPublishedContentLimit(6, 0);
				$data['commented_article'] = $this->mdl_content->getCommentedArticle();
				$data['featured_author'] = $this->mdl_content->getHomepageFeaturedAuthor();
			}
		}

		$data['previous_content'] = $model->get_published_previous($id_content);
		$data['next_content'] = $model->get_published_next($id_content);

		$data['content_url'] = base_url() . "read/{$id_content}" .
			($content->id_user ? '-' . strtolower(url_title($content->name)) : '')
			. '/' . strtolower(url_title($content->title));

		$this->css[] = 'content.min.css';
		$page = $this->load->view('amp/content', $data, true);

		$this->_render($page, $data['content_url'], [
			'title' => $content->meta_title ?: $content->title ?: NULL,
			'description' => $content->meta_desc ?: $content->short_desc ?: NULL,
			'picture' => $content->content_pic ? "{$assets_url}/{$content->content_pic}": NULL,
			'keyword' => $content->meta_keyword ?: NULL,
			'author' => $content->name ?: NULL,
			'date_published' => (new DateTime($content->publish_date))->format('c')?: NULL,
		]);

		if (!$is_preview)
		{
			$model->update_read_stats($id_content);
		}
	}

	private function getUnreadArticle()
	{
		$article = array();
		if($this->session->userdata('user_logged_in') === true){
			$id_user = $this->session->userdata('id_user');
			if(isset($id_user) && $id_user > 0){
				//ambil category yg paling sering dibaca..
				$category = $this->mdl_content->getTopReadCategoryByIDUser($id_user);
				if(isset($category[0]->id_category) && $category[0]->id_category > 0){
					//ambil artikel yang sudah pernah dibaca..
					$readed_article = $this->mdl_content->getReadArticleByIDUserAndIDCategory($id_user, $category[0]->id_category);
					$exclude_id = array();
					foreach($readed_article as $art){
						array_push($exclude_id, $art->id_content);
					}
					//ambil article exclude dari yang sudah dibaca..
					$article = $this->mdl_content->getUnreadArticleExcludeIDArr($exclude_id, $category[0]->id_category);
				}
			}
		}

		return $article;
	}

	private function getAllCategory(){
		//ambil data semua module utama / parent..
		$categories = $this->mdl_category->getAllCategoryParentArr();

		//ambil semua category child.
		foreach($categories as $x => $category){
			$has_child = $this->mdl_category->hasChild($category['id_category']);
			$categories[$x]['has_child'] = ($has_child ? 1 : 0);

			//cek apakah punya child.
			if($has_child){
				$categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
			}
		}

		$level = 0;
		foreach($categories as $x => $category){
			$this->category_list[$this->category_index] = $category;
			$this->category_list[$this->category_index]['category_name'] = $category['category_name'];
			$this->category_index++;

			//cek apakah punya child.
			if($category['has_child'] == 1){
				$this->generateCategoryChildList($category['child'], $level);
			}
		}

		return $this->category_list;
	}

	private function getCategoryChild($id_category = ''){
		$categories = array();
		$categories = $this->mdl_category->getCategoryChildArr($id_category);

		//ambil semua category child.
		foreach($categories as $x => $category){
			$has_child = $this->mdl_category->hasChild($category['id_category']);
			$categories[$x]['has_child'] = ($has_child ? 1 : 0);

			//cek apakah punya child.
			if($has_child){
				$categories[$x]['child'] = $this->getCategoryChild($category['id_category']);
			}
		}
		return $categories;
	}

	private function generateCategoryChildList($categories, $level){
		$level++;
		$indentation = "";
		for($x = 0; $x < $level; $x++){
			$indentation .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		foreach($categories as $x => $category){
			$this->category_list[$this->category_index] = $category;
			$this->category_list[$this->category_index]['category_name'] = $indentation . $category['category_name'];
			$this->category_index++;

			//cek apakah punya child.
			if($category['has_child'] == 1){
				$this->generateCategoryChildList($category['child'], $level);
			}
		}
	}

	private function _replace_with_amp_tag($content)
	{
		$htmlDom = new DOMDocument;
		$htmlDom->loadHTML(html_entity_decode($content));
		$imageTags = $htmlDom->getElementsByTagName('img');
		$imageTagsCount = $imageTags->count();

		for ($i = 0; $i < $imageTagsCount; $i++)
		{
			$imageTag = $imageTags->item(0);
			$base_file_url = base_url() . "assets/content/";
			$filepath_encoded = $imageTag->getAttribute('src');
			$filepath = str_replace('##BASE_URL##', 'assets/content/', $imageTag->getAttribute('src'));
			$imageSize = getimagesize($filepath);
			$ampImg = $htmlDom->createElement('amp-img');

			$ampImg->setAttribute('src', str_replace('##BASE_URL##', $base_file_url, $imageTag->getAttribute('src')));
			$ampImg->setAttribute('layout', 'intrinsic');
			$ampImg->setAttribute('width', $imageSize[0]);
			$ampImg->setAttribute('height', $imageSize[1]);

			foreach (['alt', 'data-caption', 'data-max-width', 'style'] as $attribute)
			{
				if ($imageTag->hasAttribute($attribute))
				{
					$ampImg->setAttribute($attribute, $imageTag->getAttribute($attribute));
				}
			}

			$imageTag->parentNode->replaceChild($ampImg, $imageTag);
		}
		$new_content = str_replace(['<body>', '</body>'], '', $htmlDom->saveHtml($htmlDom->getElementsByTagName('body')->item(0)));

		return $new_content;
	}
	/* private function _replace_with_amp_tag($content) */
	/* { */
	/* 	$content_file_path = base_url() . "assets/content/"; */
	/* 	$imageTags = []; */
	/* 	$search = '/&lt;img( align=&quot;\w*&quot;)*(.*)\/&gt;/'; */
	/* 	dd(preg_match_all($search, $content, $imageTags), $imageTags); */

	/* 	$new_content = preg_replace( */
	/* 		$search, */
	/* 		'<amp-img $2 layout=&quot;intrinsic&quot;></amp-img>', */
	/* 		$content */
	/* 	); */
	/* 	/1* dump($new_content, $content); *1/ */
	/* 	/1* echo str_replace("##BASE_URL##", $content_file_path, ($content)); *1/ */
	/* 	/1* die; *1/ */
	/* 	return $new_content; */
	/* } */

	private function _render($page, $canonical, $meta = [])
	{
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
		foreach($data['categories'] as $x => $category)
		{
			$data['categories'][$x]['child'] = $this->mdl_category->getCategoryChildArr($category['id_category']);
		}

		//ambil ads footer..
		$data['ads'] = $this->ads_lib->getFooterAds();

		$data['canonical'] = $canonical;

		//load view template
		$this->load->view('amp/template', $data);
	}
}
