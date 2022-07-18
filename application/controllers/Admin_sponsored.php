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

class Admin_sponsored extends CI_Controller {
	
  var $js = array();
  var $css = array();
  var $module_name = 'admin_sponsored';
  var $category_index = 0;
  var $pic_width = 1900;
	var $pic_height = 998;
  var $pic_thumb_width = 600;
	var $pic_thumb_height = 400;
  var $pic_square_width = 400;
  var $pic_square_height = 400;
  
  public function __construct(){
    parent::__construct();
		
		//load library..
		//-
    
		//load model..
		$this->load->model('mdl_sponsoredcontent');
		$this->load->model('mdl_content_editor');
		
    /* DO NOT CHANGE THIS SECTION */
		if($this->session->userdata('admin_logged_in') !== true){
			redirect("adminarea/index");
		}
    if(strpos($this->session->userdata('admin_grant'), $this->module_name . '|') === false && $this->session->userdata('admin_level') != '1'){
      redirect('admin_dashboard/index');
    }
  }
	
	public function index()
	{
		$content_counts = $this->mdl_sponsoredcontent->count();
		$config = $this->global_lib->paginationConfigAdmin();

		$config['base_url'] 		= base_url() . 'admin_sponsored/index';
		$config['total_rows'] 	= $content_counts;
		$config['per_page'] 		= 20;
		$config['uri_segment']	= 3;

		$this->pagination->initialize($config);

		$offset = $this->uri->segment($config['uri_segment']) ?: 0;
    $contents = $this->mdl_sponsoredcontent
                     ->order_by_admin_editor($this->session->userdata('id_admin'))
                     ->with_admin_editor()
                     ->all($config['per_page'], $offset);
    
		$this->render($this->load->view('admin/sponsored/all', compact('contents', 'offset'), true));
	}

	public function add()
	{
		$heading_text = 'Tambah';
		$submit_url = 'admin_sponsored/saveAdd';
		$positions = $this->mdl_sponsoredcontent->all_positions();
		$form_value = $this->session->flashdata('form_value') ?: [
			'id_position' => '',
			'start_date' => '',
			'finish_date' => '',
			'title' => '',
			'short_desc' => '',
			'meta_title' => '',
			'meta_desc' => '',
			'meta_keyword' => '',
			'pic_caption' => '',
			'content' => '',
			'content_status' => '-1',
		];

    $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>';
    $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
    $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/moment/moment.min.js"></script>';
		$data = compact('form_value', 'heading_text', 'submit_url', 'positions');
		$this->render($this->load->view('admin/sponsored/form', $data, true));
	}

	public function saveAdd()
	{
		$this->session->set_flashdata('form_value', $this->input->post());
		$this->_setFormValidationRules($this->input->post('content_status'));

		if ($this->form_validation->run())
		{
			$picture = $this->_uploadPicture($this->input->post('content_status') !== '-1', 'add');
			$content_data = $this->_getInputContentData($picture);
			$sponsored_data = $this->_getInputSponsoredData();

			if ($content_data['content_status'] === '1')
			{
				$this->_checkHasPublishedContent($sponsored_data, 'add');
			}

			$tags = !empty($this->input->post('tags')) ? explode(',', $this->input->post('tags')) : [];
			$id_content = $this->mdl_sponsoredcontent->insert_sponsored_content($content_data, $sponsored_data, $tags);
			$this->mdl_content_editor->insert_or_update($id_content, $this->session->userdata('id_admin'));

			$message = 'Sponsored Content has been added.';
			if (is_null($this->input->post('preview')))
			{
				$this->session->set_flashdata('form_value', NULL);
				$this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

				redirect('admin_sponsored');
			}
			else
			{
				$this->session->set_flashdata('message', $message);

				redirect("read-sponsored/{$id_content}/" . strtolower(url_title($content_data['title'])) . '?is_preview=1');
			}
		}
		else
		{
			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
			);

			redirect('admin_sponsored/add');
		}
	}

	public function edit($id_content)
	{
		$content = $this->mdl_sponsoredcontent->find($id_content) ?: redirect('admin_verifiedmember');
		$content->start_date = date_create($content->start_date)->format('d-m-Y');
		$content->finish_date = date_create($content->finish_date)->format('d-m-Y');

    if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin'))
    {
      redirect('admin_sponsored/index');
    }

		$heading_text = 'Edit';
		$submit_url = "admin_sponsored/saveEdit/{$id_content}";
		$form_value = $this->session->flashdata('form_value') ?: (array) $content;
		$positions = $this->mdl_sponsoredcontent->all_positions();
		$locked_content_id = $this->mdl_sponsoredcontent->get_locked_content_id($this->session->userdata('id_admin'));

    $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/ckeditor.js"></script>';
    $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/ckeditor/adapters/jquery.js"></script>';
    $this->js[] = '<script type="text/javascript" src="' . base_url() . 'files/backend/plugins/moment/moment.min.js"></script>';
		$data = compact('content', 'heading_text', 'form_value', 'submit_url', 'positions', 'locked_content_id');
		$this->render($this->load->view('admin/sponsored/form', $data, true));
	}

	public function saveEdit($id_content)
	{
		$content = $this->mdl_sponsoredcontent->find($id_content) ?: redirect('admin_sponsored');

    if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin'))
    {
      redirect('admin_sponsored/index');
    }

    $status = $this->input->post('content_status');
		$this->session->set_flashdata('form_value', $this->input->post());
		$this->_setFormValidationRules($status);

		if ($this->form_validation->run())
		{
			$picture = $this->_uploadPicture(
				$status !== '-1' && empty($content->content_pic),
				"edit/{$id_content}"
			);
			$content_data = $this->_getInputContentData($picture);
			$sponsored_data = $this->_getInputSponsoredData();

			$content_data['content_pic']        = $content_data['content_pic'] ?: $content->content_pic;
			$content_data['content_pic_thumb']  = $content_data['content_pic_thumb'] ?: $content->content_pic_thumb;
			$content_data['content_pic_square'] = $content_data['content_pic_square'] ?: $content->content_pic_square;
			$content_data['edit_id_admin'] = in_array($status, ['1', '2']) ? null : $content->edit_id_admin;

			if ($content_data['content_status'] === '1')
			{
				$this->_checkHasPublishedContent($sponsored_data, "edit/{$id_content}", $id_content);
			}

			$tags = !empty($this->input->post('tags')) ? explode(',', $this->input->post('tags')) : [];
			$this->mdl_sponsoredcontent->update_sponsored_content($id_content, $content_data, $sponsored_data, $tags);
			$this->mdl_content_editor->insert_or_update($id_content, $this->session->userdata('id_admin'));

			if ($content_data['content_pic'] !== $content->content_pic) {
				@unlink("assets/sponsored-content/{$content->content_pic}");
				@unlink("assets/sponsored-content/thumb/{$content->content_pic_thumb}");
				@unlink("assets/sponsored-content/thumb/{$content->content_pic_square}");
			}

			$message = 'Sponsored Content has been updated.';
			if (is_null($this->input->post('preview')))
			{
				$this->session->set_flashdata('form_value', NULL);
				$this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

				redirect('admin_sponsored');
			}
			else
			{
				$this->session->set_flashdata('message', $message);

				redirect("read-sponsored/{$id_content}/" . strtolower(url_title($content_data['title'])) . '?is_preview=1');
			}
		}
		else
		{
			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
			);
		}

		redirect("admin_sponsored/edit/{$id_content}");
	}

	public function delete($id_content)
	{
		$content = $this->mdl_sponsoredcontent->find($id_content);

		if (!is_null($content))
		{
      if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin'))
      {
        redirect('admin_sponsored/index');
      }

			$this->mdl_sponsoredcontent->delete($id_content);

			@unlink("assets/sponsored-content/{$content->content_pic}");
			@unlink("assets/sponsored-content/thumb/{$content->content_pic_thumb}");
			@unlink("assets/sponsored-content/thumb/{$content->content_pic_square}");

			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage('Sponsored Content has been deleted.', 'info')
			);
		}

		redirect('admin_sponsored');
	}

	public function deletePicture($id_content)
	{
		$content = $this->mdl_sponsoredcontent->find($id_content);

		if (!is_null($content) && $content->content_status !== '1')
		{
      if ($content->edit_id_admin && $content->edit_id_admin != $this->session->userdata('id_admin'))
      {
        redirect('admin_sponsored/index');
      }

			$this->mdl_sponsoredcontent->remove_picture($id_content);

			@unlink("assets/sponsored-content/{$content->content_pic}");
			@unlink("assets/sponsored-content/thumb/{$content->content_pic_thumb}");
			@unlink("assets/sponsored-content/thumb/{$content->content_pic_square}");

			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage('Sponsored Content\'s Picture has been deleted.', 'info')
			);
		}

		redirect("admin_sponsored/edit/{$id_content}");
	}

  public function lock_edit($id_content = '')
  {
    $this->_toggle_lock_edit(TRUE, $id_content);
  }

  public function unlock_edit($id_content = '')
  {
    $this->_toggle_lock_edit(FALSE, $id_content);
  }

  private function _toggle_lock_edit($is_lock, $id_content)
  {
		$content = $this->mdl_sponsoredcontent->find($id_content) ?: redirect('admin_sponsored');

    if (!$is_lock && $content->edit_id_admin != $this->session->userdata('id_admin') && $this->session->userdata('admin_level') !== '1')
    {
      redirect($_SERVER['HTTP_REFERER'] ?? 'admin_sponsored/index');
    }

    $locked_content_id = $this->mdl_sponsoredcontent->get_locked_content_id($this->session->userdata('id_admin'));
    $is_valid_lock = $is_lock && is_null($locked_content_id);
    $is_valid_unlock = !$is_lock && (
      $content->edit_id_admin == $this->session->userdata('id_admin') || $this->session->userdata('admin_level') === '1'
    );

    if ($is_valid_lock || $is_valid_unlock) {
      $this->mdl_sponsoredcontent->update_content_without_tags($id_content, [
        'edit_id_admin' => $is_lock ? $this->session->userdata('id_admin') : NULL,
      ]);
    }

    redirect($_SERVER['HTTP_REFERER'] ??"admin_sponsored/edit/{$id_content}");
  }

	private function _checkHasPublishedContent($sponsored_data, $fail_redirect_url = NULL, $except_id = NULL)
	{
		$hasPublishedContent = $this->mdl_sponsoredcontent->has_published_content(
			$sponsored_data['id_position'],
			$sponsored_data['start_date'],
			$sponsored_data['finish_date'],
			$except_id
		);

		if ($hasPublishedContent)
		{
			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage('There is a published sponsored content already, please pick another date or another position.', 'danger')
			);

			redirect("admin_sponsored/{$fail_redirect_url}");
		}

		return $hasPublishedContent;
	}

	private function _setFormValidationRules($status)
	{
		$base_rule = 'htmlentities|strip_tags|trim|xss_clean';
    $required_if_not_draft = ($status !== '-1' ? "|required" : '');

		$this->form_validation->set_rules('id_position', '', "{$base_rule}|required|integer");
		$this->form_validation->set_rules('start_date', '', "{$base_rule}|required|callback__validateDate[d-m-Y]");
		$this->form_validation->set_rules('finish_date', '', "{$base_rule}|required|callback__validateDate[d-m-Y]");
		$this->form_validation->set_rules('title', '', "{$base_rule}|required|max_length[1000]");
		$this->form_validation->set_rules('pic_caption', '', "{$base_rule}{$required_if_not_draft}|max_length[200]");
		$this->form_validation->set_rules('short_desc', '', "{$base_rule}|max_length[1000]");
		$this->form_validation->set_rules('meta_title', '', "{$base_rule}|max_length[500]");
		$this->form_validation->set_rules('meta_desc', '', $base_rule);
		$this->form_validation->set_rules('meta_keyword', '', $base_rule);
		$this->form_validation->set_rules('content', '', "{$base_rule}{$required_if_not_draft}");
		$this->form_validation->set_rules('tags', '', $base_rule);
		$this->form_validation->set_rules('content_status', '', "{$base_rule}|integer");
	}
	
	public function _validateDate($date_text, $format)
	{
		$is_valid_date = $this->global_lib->validateDate($date_text, $format);

		if (!$is_valid_date)
		{
			$this->form_validation->set_message('_validateDate', 'The format of {field} must be ' . strtoupper($format));
		}

		return $is_valid_date;
	}
	
	private function _getInputContentData($picture)
	{
		return [
			'id_admin'           => $this->session->userdata('id_admin'),
			'title'              => $this->input->post('title') ?: NULL,
			'short_desc'         => $this->input->post('short_desc') ?: NULL,
			'meta_title'         => $this->input->post('meta_title') ?: NULL,
			'meta_desc'          => $this->input->post('meta_desc') ?: NULL,
			'meta_keyword'       => $this->input->post('meta_keyword') ?: NULL,
			'pic_caption'        => $this->input->post('pic_caption') ?: NULL,
			'content'            => $this->input->post('content') ?: NULL,
			'content_pic'        => $picture['picture'] ?: NULL,
			'content_pic_thumb'  => $picture['thumb'] ?: NULL,
			'content_pic_square' => $picture['thumb_square'] ?: NULL,
			'content_status'     => $this->input->post('content_status'),
			'publish_date'       => $this->global_lib->formatDate($this->input->post('start_date'), 'd-m-Y', 'Y-m-d') . ' 00:00:00',
		];
	}

	private function _getInputSponsoredData()
	{
		return [
			'id_position' => $this->input->post('id_position'),
			'start_date'  => $this->global_lib->formatDate($this->input->post('start_date'), 'd-m-Y', 'Y-m-d'),
			'finish_date' => $this->global_lib->formatDate($this->input->post('finish_date'), 'd-m-Y', 'Y-m-d'),
		];
	}
	
	

	private function _uploadPicture($is_required = false, $fail_redirect_url = null)
	{
		$filename = $_FILES['file_pic']['name'];

		$data = ['picture' => '', 'thumb' => '', 'thumb_square' => ''];

		if (!empty($filename))
		{
			$config = [
				'upload_path' => './assets/sponsored-content/',
				'allowed_types' => 'jpg|jpeg|png|gif',
				'max_size' => '12000',
				'max_width' => '8000',
				'max_height' => '8000',
				'file_name' => 'Original_' . date('YmdHisu') . '_' . str_replace('Original_', '', $filename),
			];

			$this->upload->initialize($config);

			if ($this->upload->do_upload('file_pic'))
			{
				$upload_data = $this->upload->data();
				$this->picture->resizePhoto($upload_data['full_path'], $this->pic_width, $this->pic_height, FALSE);

				$data['picture'] = $upload_data['file_name'];
				$data['thumb'] = $this->picture->createThumb($upload_data['full_path'], $this->pic_thumb_width, $this->pic_thumb_height);
				$data['thumb_square'] = $this->picture->createThumbSquare($upload_data['full_path'], $this->pic_square_width, $this->pic_square_height);
			}
			else
			{
				$this->session->set_flashdata(
					'message',
					$this->global_lib->generateMessage('Failed to upload file. <br/> cause: ' . $this->upload->display_errors(), 'danger')
				);
				redirect("admin_sponsored/{$fail_redirect_url}");
			}
		}
		elseif ($is_required)
		{
			$this->session->set_flashdata(
				'message',
				$this->global_lib->generateMessage('You must upload article picture', 'danger')
			);
			redirect("admin_sponsored/{$fail_redirect_url}");
		}

		return $data;
	}

  /* DO NOT CHANGE THIS SECTION */
  private function render($page = null){
    if(isset($page) && $page !== null){
      //load page view
      $data['content'] = $page;
      
      //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
      $data['css_files'] = $this->css;
      $data['js_files'] = $this->js;
      
      //load module global data
      $data['global_data'] = $this->global_lib->getGlobalData();
      $data['modules'] = $this->global_lib->generateAdminModule();
      
      //load view template
      $this->load->view('/admin/template', $data);
    }
    else{
      redirect('page/index');
    }
  }
}
