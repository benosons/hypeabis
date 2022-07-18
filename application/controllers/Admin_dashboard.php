<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @author 		: Hengky Mulyono <hengkymulyono301@gmail.com>
 * @copyright	: Binari - 2020
 * @copyright	: mail@binary-project.com
 * @version		: Release: v1
 * @link			  : www.binary-project.com
 * @contact		: 0822 3709 9004
 *
 * @property-read CI_Form_validation $form_validation
 * @property-read CI_Input $input
 * @property-read Mdl_content2 $mdl_content2
 * @property-read Mdl_article $mdl_article
 * @property-read Mdl_user $mdl_user
 * @property-read Mdl_category $mdl_category
 * @property-read Mdl_photo $mdl_photo
 * @property-read Mdl_shoppable $mdl_shoppable
 */
class Admin_dashboard extends CI_Controller {
	
  var $js = array();
  var $css = array();
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		
		//load model..
    $this->load->model('mdl_content2');
    $this->load->model('mdl_article');
    $this->load->model('mdl_user');
    $this->load->model('mdl_admin');
    $this->load->model('mdl_category');
    $this->load->model('mdl_photo');
    $this->load->model('mdl_shoppable');
		
		//construct script..
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    
    //set page state
		$this->session->set_userdata(array('page_state' => 'Dashboard'));
  }
	
  public function index()
  {
    $this->clearSearchSession();

    $data['modules'] = $this->global_lib->getModuleListWithoutIndentation();

    $data['published_articles_count'] = $this->mdl_article->count_published();
    $data['draft_articles_count'] = $this->mdl_article->count_draft();
    $data['popular_today_articles_alltime'] = $this->mdl_article->popular_today()->all_statistic(6);
    $data['popular_this_month_articles_alltime'] = $this->mdl_article->popular_this_month()->all_statistic(6);
    $data['popular_today_articles'] = $this->mdl_article->popular_today()->all_statistic(6);
    $data['popular_this_month_articles'] = $this->mdl_article->popular_this_month()->all_statistic(6);
    $data['most_productive_author'] = $this->mdl_user->getAllProductiveUserLimit(5);
    $data['most_productive_editor'] = $this->mdl_admin->getAllProductiveAdminLimit(5);
    $data['productivity_category'] = $this->getAllProductivityCategory();
    $data['productivity_hypeshop'] = $this->mdl_shoppable->count_published();
    $data['productivity_hypephoto'] = $this->mdl_photo->count_published();

    //load dashboard view
    $content = $this->load->view('admin/dashboard/dashboard', $data, true);
    $this->render($content);
  }

  public function submitSearch()
  {
    //validasi input..
    $this->form_validation->set_rules('author','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('admin','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('start_date','', 'htmlentities|strip_tags|trim|xss_clean');
    $this->form_validation->set_rules('finish_date','', 'htmlentities|strip_tags|trim|xss_clean');

    if ($this->form_validation->run() == false)
    {
      $message =  $this->global_lib->generateMessage(validation_errors(), "info");
      $this->session->set_flashdata('message', $message);
      redirect('admin_dashboard/index');
    }
    else
    {
      //clear search session yang lama..
      $this->clearSearchSession();

      //ambil data input dan restore ke session sebagai parameter search..
      $search_param = array(
        'author' => $this->input->post('author'),
        'admin' => $this->input->post('admin'),
        'start_date' => $this->input->post('start_date'),
        'finish_date' => $this->input->post('finish_date'),
      );
      $this->session->set_userdata('search_dashboard', $search_param);

      redirect('admin_dashboard/search');
    }
  }

  public function search()
  {
    $search_param = $this->session->userdata('search_dashboard');
    $data['modules'] = $this->global_lib->getModuleListWithoutIndentation();
    $data['author'] = $this->mdl_user->getUserByID($search_param['author']);
    $data['admin_name'] = $this->mdl_admin->getNameByID($search_param['admin']);
    
    $data['popular_today_articles_alltime'] = $this->mdl_article->popular_today()->all_statistic(6);
    $data['popular_this_month_articles_alltime'] = $this->mdl_article->popular_this_month()->all_statistic(6);
    $data['published_articles_count'] = $this->mdl_article->search($search_param)->count_published();
    $data['draft_articles_count'] = $this->mdl_article->search($search_param)->count_draft();
    $data['popular_today_articles'] = $this->mdl_article->search($search_param)->popular_today()->all_statistic(6);
    $data['popular_this_month_articles'] = $this->mdl_article->search($search_param)->popular_this_month()->all_statistic(6);
    $data['most_productive_author'] = $this->mdl_user->getAllProductiveUserLimit(5, $search_param);
    $data['most_productive_editor'] = $this->mdl_admin->getAllProductiveAdminLimit(5, $search_param);
    $data['productivity_category'] = $this->getAllProductivityCategory($search_param);
    $data['productivity_hypeshop'] = $this->mdl_shoppable->search($search_param)->count_published();
    $data['productivity_hypephoto'] = $this->mdl_photo->search($search_param)->count_published();

    //load dashboard view
    $content = $this->load->view('admin/dashboard/dashboard', $data, true);
    $this->render($content);
  }

  public function tes(){
    $data['modules'] = $this->global_lib->getModuleListWithoutIndentation();
    
		//load dashboard view
		$content = $this->load->view('admin/dashboard/dashboard', $data, true);
		// $this->render($content);
    
    print_r('<pre>');
    print_r($this->session->all_userdata());
    print_r('</pre>');
	}
  
  public function terms(){
    //load terms & conditions view
		$content = $this->load->view('admin/dashboard/terms', null, true);
		$this->render($content);
  }

  private function clearSearchSession()
  {
    //declare session search..
    $search_param = array(
      'author' => null,
      'admin' => null,
      'start_date' => null,
      'finish_date' => null,
    );
    $this->session->set_userdata('search_dashboard', $search_param);
  }

  private function getAllProductivityCategory($search_param = [])
  {
		$categories = $this->mdl_category->getAllProductivityCategoryParentArr($search_param);

		//ambil semua category child.
		foreach($categories as $x => $category){
      $has_child = $this->mdl_category->hasChild($category['id_category']);
      $categories[$x]['has_child'] = ($has_child ? 1 : 0);
      
			//cek apakah punya child.
			if($has_child){
				$categories[$x]['child'] = $this->getProductivityCategoryChild($category['id_category'], $search_param);
			}
		}
		
		foreach($categories as $x => $category){
			if($category['has_child'] == 1){
				$categories[$x]['article_count'] = $this->calculateProductivityCategoryChild($category['child']);
			}
		}
    
    return $categories;
  }

  private function getProductivityCategoryChild($id_category = '', $search_param = [])
  {
    $categories = array();
    $categories = $this->mdl_category->getProductivityCategoryChildArr($id_category, $search_param);
    
    //ambil semua category child.
		foreach($categories as $x => $category){
      $has_child = $this->mdl_category->hasChild($category['id_category']);
      $categories[$x]['has_child'] = ($has_child ? 1 : 0);
      
			//cek apakah punya child.
			if($has_child){
				$categories[$x]['child'] = $this->getProductivityCategoryChild($category['id_category'], $search_param);
			}
		}
    return $categories;
  }

  private function calculateProductivityCategoryChild($categories, $level = 0)
  {
    $total_articles = 0;

		foreach($categories as $category){
      $total_articles += $category['article_count'];

      //cek apakah punya child.
			if($category['has_child'] == 1){
				$total_articles += $this->calculateProductivityCategoryChild($category['child'], $level);
			}
		}

    return $total_articles;
  }

  private function render($page = null){
    if(isset($page) && $page !== null){
      //load page view
      $data['content'] = $page;
      
      //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
      $data['css_files'] = $this->css;
      $data['js_files'] = $this->js;
      
      //load global data
      $data['global_data'] = $this->global_lib->getGlobalData();
      $data['modules'] = $this->global_lib->generateAdminModule();
      
      //load view template
      $this->load->view('admin/template', $data);
    }
    else{
      redirect('page/index');
    }
  }
	
}
