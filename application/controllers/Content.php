<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Content extends CI_Controller
{
    private $amp_link;

    var $js = array();
    var $css = array();
    var $category_index = 0;
    var $category_list = array();
    var $per_page_tag = 12;
    var $per_page_category = 12;
    var $per_page_author = 12;
    var $per_page_comment = 10;

    public function __construct()
    {
        parent::__construct();

        //load library..
        $this->load->library('frontend_lib');
        $this->load->library('ads_lib');

        //load model..
        $this->load->model('mdl_user');
        $this->load->model('mdl_content');
        $this->load->model('mdl_category');
        $this->load->model('mdl_competition');
    }

    public function category($id_category = '', $category_name = '')
    {
        //ambil data category yang akan diedit.
        $data['category'] = $this->mdl_category->getCategoryByID($id_category);
        if ((!is_array($data['category'])) || count($data['category']) < 1) {
            redirect(base_url());
        }

        //get recommended article category.
        $data['article_recommended'] = $this->mdl_content->getRecommendedArticleByIDCategory($id_category, 4);

        //most commented
        $data['commented_article'] = $this->mdl_content->getCommentedArticleByIDCategory($id_category);

        //penulis pilihan..
        $data['featured_author'] = $this->mdl_content->getCategoryFeaturedAuthor($id_category);

        //on top category..
        $data['article_on_top'] = $this->mdl_content->getOnTopCategory($id_category);

        //get category article..
        $data['total_row'] = $this->mdl_content->getPublishedContentByIDCategoryCount($id_category);
        $config = $this->categoryPaginationConfig($data['total_row']);
        $this->pagination->initialize($config);
        $data['featured_on_category'] = $this->mdl_content->getCategoryFeaturedArticle($id_category);

        //get new article by category.
        $data['newest_articles'] = $this->mdl_content->getHomepageNewestArticleByCategory($id_category, 12);
        $data['newest_articles_see_more_limit'] = 6;

        //trending article by category.
        $data['trending_article'] = $this->mdl_content->getHomepageTrendingArticleByCategory($id_category);

        //ambil ads homepage.
        $data['ads'] = $this->ads_lib->getCategoryAds();

        //meta tag..
        $meta = array(
            'title' => $data['category'][0]->category_name,
        );

        //load view
        if ($category_name == "hypephoto") {
            $content = $this->load->view('frontend/hypephoto', $data, true);
            $this->render($content, $meta);
        }
        else {
            $content = $this->load->view('frontend/category', $data, true);
            $this->render($content, $meta);
        }
        // print_r('<pre>');
        // print_r($data);
        // print_r('</pre>');
    }

    public function article($id_param = '', $title = '', $page_no = '1')
    {
        setlocale(LC_TIME, 'id_ID.UTF-8');

        $param_arr = explode('-', $id_param);
        $id_user = $this->session->userdata('id_user');
        $id_admin = $this->session->userdata('id_admin');
        $id_content = $param_arr[0];
        //redirect jika masih akses pakai URL lama.
        if (isset($id_param) && (!is_numeric($id_param))) {
            redirect(base_url() . 'read/' . $id_content . '/' . $title);
        }

        //jika tidak ada data, redirect ke index
        $data['article'] = $this->mdl_content->getUserContentByID($id_content);
        if ((!is_array($data['article'])) || count($data['article']) < 1) {
            redirect(base_url());
        }

        if ($data['article'][0]->type === '7') {
            redirect("hypephoto/{$id_content}/" . url_title(strtolower($data['article'][0]->title)));
        }

        $article = $data['article'][0];
        $isNotPublished = in_array($article->content_status, ['-1', '0', '2']);
        $data['is_preview'] = $isNotPublished && ($this->session->userdata('admin_logged_in') === true || $article->id_user === $id_user);
        if ($isNotPublished && !$data['is_preview']) {
            redirect(base_url());
        }

        $data['page_no'] = $page_no;
        if ($data['article'][0]->paginated === '1') {
            $data['max_page_no'] = $this->mdl_content->getMaxPageNo($id_content);
            if ($page_no !== '1') {
                $data['page'] = $this->mdl_content->getContentPageByIDContentAndPage($id_content, $page_no);
                if (is_null($data['page'])) {
                    redirect(base_url());
                }
            }
        }

        $data['tags'] = $tags = $this->mdl_content->getTagByIDContent($article->id_content);

        if (is_null($article->id_competition)) {
            // ambil content yang tag nya sama
            $tag2 = [];
            foreach ($tags as $tag) {
                array_push($tag2, $tag->tag_name);
            }
            $data['read_too'] = !empty($tags) ? $this->mdl_content->getRelatedContentByTag($tag2, $article->id_content) : [];
            $data['total_content_by_tag'] = !empty($tags) ? $this->mdl_content->getRelatedContentByTagCount($tag2, $article->id_content) : 0;
            // $limit_content_by_category = 0;
            if ($data['total_content_by_tag'] < 6) {
                $limit_content_by_category = 6 - $data['total_content_by_tag'];
                $exclude_ids = array_column($data['read_too'], 'id_content');
                $exclude_ids[] = $article->id_content;

                $data['content_by_category'] = $this->mdl_content->getRelatedContentByIDCategoryArr(
                    $data['article'][0]->id_category,
                    $exclude_ids,
                    $limit_content_by_category
                );
            }
        } else {
            $data['read_too'] = !empty($tags) ? $this->mdl_content->getRelatedContentByIdCompetitionAndIdCompetitionCategory($article->id_competition, $article->id_competition_category, $article->id_content) : [];
            $data['total_content_by_tag'] = !empty($tags) ? $this->mdl_content->getRelatedContentByIdCompetitionAndIdCompetitionCategoryCount($article->id_competition, $article->id_competition_category, $article->id_content) : 0;

            if ($data['total_content_by_tag'] < 6) {
                $limit = 6 - $data['total_content_by_tag'];
                $exclude_ids = array_column($data['read_too'], 'id_content');
                $exclude_ids[] = $article->id_content;

                $data['content_by_category'] = $this->mdl_content->getRelatedContentByIdCompetition(
                    $article->id_competition,
                    $exclude_ids,
                    $limit
                );
            }
        }

        //ambil previous & next article.
        $data['article_prev'] = $this->mdl_content->getPrevContent($id_content);
        $data['article_next'] = $this->mdl_content->getNextContent($id_content);

        //ambil komentar
        $data['total_comments'] = $this->mdl_content->getContentCommentCount($id_content);
        $data['comments'] = $this->mdl_content->getContentComment($id_content, $this->per_page_comment, 0);

        //newest artikel in category
        $data['newest_article'] = $this->mdl_content->getPublishedContentByIDCategoryExcludeID($data['article'][0]->id_category, $id_content, 6);
        //most commented
        $data['commented_article'] = $this->mdl_content->getCommentedArticleByIDCategory($data['article'][0]->id_category);
        //penulis pilihan
        $data['featured_author'] = $this->mdl_content->getCategoryFeaturedAuthor($data['article'][0]->id_category);
        //baca juga
        //recommended
        $this->load->model('mdl_content');
        $this->load->model('mdl_sponsoredcontent');
        $data['recommended_article'] = $this->mdl_content->getHomepageRecommendedArticle();
        $data['recommended_sponsored'] = $this->mdl_sponsoredcontent->find_active_by_position(3);
        //load library recaptcha.
        $this->load->library('recaptcha');

        if (!$data['is_preview']) {
            //action artikel readed
            $this->articleReaded($data['article']);
        }

        //check article like
        $data['liked'] = false;
        if ($this->session->userdata('user_logged_in')) {
            //check id article sudah di like / tidak..
            $id_user = $this->session->userdata('id_user');
            $liked = $this->mdl_content->getContentLikeByIDAndIDUser($id_content, $id_user);
            if (isset($liked[0]->id_content_like) && $liked[0]->id_content_like > 0) {
                $data['liked'] = true;
            }
        }

        //ambil reaction
        $data['reaction'] = $this->mdl_content->getAllReaction();
        //ambil content reaction
        $data['content_reaction'] = $this->mdl_content->getContentReaction($id_content);
        //check sudah reaction / belum
        $data['reacted'] = false;
        if ($this->session->userdata('user_logged_in')) {
            $id_user = $this->session->userdata('id_user');
            $user_reaction = $this->mdl_content->getContentReactionByIDUser($id_content, $id_user);
            if (isset($user_reaction[0]->id_content_reaction) && $user_reaction[0]->id_content_reaction > 0) {
                $data['reacted'] = true;
            }
        }

        //ambil meta tag.
        $meta = $this->getMetaData($data['article']);

        //ambil breadcrumb.
        $breadcrumb = $this->getBreadcrumb($data['article']);

        //ambil ads homepage..
        $data['ads'] = $this->ads_lib->getArticleAds();

        if (intval($page_no) === 1) {
            $this->amp_link = base_url() . "amp/read/{$id_content}" .
                ($article->id_user ? '-' . strtolower(url_title($article->name)) : '')
                . '/' . strtolower(url_title($article->title));
        }

        $content = $this->load->view('frontend/article', $data, true);
        $this->render($content, $meta, $breadcrumb);
    }

    public function likeContent()
    {
        //cek apakah ajax request atau bukan..
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data['status'] = '';
        $data['message'] = '';
        $data['csrf_token_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token_hash'] = $this->security->get_csrf_hash();

        if ($this->session->userdata('user_logged_in') !== true) {
            $data['status'] = 'nologin';
        }
        else {
            $this->form_validation->set_rules('id_content', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
            if ($this->form_validation->run() == false) {
                $data['status'] = 'failed';
                $data['message'] = "Gagal menyukai artikel ini. Mohon coba kembali.";
            }
            else {
                $id_user = $this->session->userdata('id_user');
                $id_content = $this->input->post('id_content');
                //checck id user dan id content..
                $content_data = $this->mdl_content->getContentByID($id_content);
                if (isset($content_data[0]->id_content) && $content_data[0]->id_content > 0) {
                    $liked = $this->mdl_content->getContentLikeByIDAndIDUser($id_content, $id_user);
                    if (isset($liked[0]->id_content_like) && $liked[0]->id_content_like > 0) {
                        // sudah pernah like sebelumnya..
                        $data['status'] = 'already_liked';
                    }
                    else {
                        // insert like baru..
                        $data['status'] = 'success';
                        $data['like_count'] = $this->articleLiked($content_data, $id_user);
                    }
                }
                else {
                    $data['status'] = 'failed';
                    $data['message'] = "Gagal menyukai artikel ini. Mohon coba kembali.";
                }
            }
        }
        echo json_encode($data);
    }

    public function reactContent()
    {
        //cek apakah ajax request atau bukan..
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data['status'] = '';
        $data['message'] = '';
        $data['csrf_token_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token_hash'] = $this->security->get_csrf_hash();

        if ($this->session->userdata('user_logged_in') !== true) {
            $data['status'] = 'nologin';
        }
        else {
            $this->form_validation->set_rules('id_content', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
            $this->form_validation->set_rules('id_reaction', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
            if ($this->form_validation->run() == false) {
                $data['status'] = 'failed';
                $data['message'] = "Gagal memberikan reaksi. Mohon coba kembali.";
            }
            else {
                $id_user = $this->session->userdata('id_user');
                $id_content = $this->input->post('id_content');
                $id_reaction = $this->input->post('id_reaction');
                //checck id user dan id content..
                $content_data = $this->mdl_content->getContentByID($id_content);
                if (isset($content_data[0]->id_content) && $content_data[0]->id_content > 0) {
                    $reacted = $this->mdl_content->getContentReactionByIDUser($id_content, $id_user);
                    if (isset($reacted[0]->id_content_reaction) && $reacted[0]->id_content_reaction > 0) {
                        // sudah pernah like sebelumnya..
                        $data['status'] = 'already_reacted';
                    }
                    else {
                        // insert reaction baru..
                        $data['status'] = 'success';
                        $data['reaction_result'] = $this->articleReacted($content_data, $id_reaction, $id_user);
                    }
                }
                else {
                    $data['status'] = 'failed';
                    $data['message'] = "Gagal memberikan reaksi. Mohon coba kembali.";
                }
            }
        }
        echo json_encode($data);
    }

    public function loadComment()
    {
        //cek apakah ajax request atau bukan..
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data['status'] = '';
        $data['message'] = '';
        $data['csrf_token_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token_hash'] = $this->security->get_csrf_hash();

        $this->form_validation->set_rules('id_content', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
        $this->form_validation->set_rules('comment_offset', '', 'htmlentities|strip_tags|trim|xss_clean|required');
        if ($this->form_validation->run() == false) {
            $data['status'] = 'failed';
        }
        else {
            $id_content = $this->input->post('id_content');
            $comment_offset = $this->input->post('comment_offset');
            //cek id content..
            //ambil data content yang akan diedit.
            $article = $this->mdl_content->getPublishedContentByID($id_content);
            //jika tidak ada data, redirect ke index.
            if ((!is_array($article)) || count($article) < 1) {
                $data['status'] = 'failed';
            }
            else {
                $data['status'] = 'success';
                $data['next_offset'] = $comment_offset + $this->per_page_comment;
                $data['comments'] = $this->mdl_content->getContentCommentArr($id_content, $this->per_page_comment, $comment_offset);
                foreach ($data['comments'] as $x => $comment) {
                    $data['comments'][$x]['picture_src'] = $this->frontend_lib->getUserPictureURL($comment['picture'], $comment['picture_from']);
                    $data['comments'][$x]['date_str'] = date('d M Y - H:i', strtotime($comment['comment_date']));
                    $data['comments'][$x]['content_str'] = nl2br($comment['comment']);
                    $data['comments'][$x]['name_url'] = strtolower(url_title($comment['name']));
                }
                $data['comments_number'] = $this->mdl_content->getContentCommentCount($id_content);
                if ($data['next_offset'] < $data['comments_number']) {
                    $data['show_loadmore'] = 1;
                }
                else {
                    $data['show_loadmore'] = 0;
                }
            }
        }
        echo json_encode($data);
    }

    public function submitComment()
    {
        //cek apakah ajax request atau bukan..
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }

        $data['status'] = '';
        $data['message'] = '';
        $data['csrf_token_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token_hash'] = $this->security->get_csrf_hash();

        //cek captcha..
        $this->load->library('recaptcha');
        $captcha_answer = $this->input->post('g-recaptcha-response');
        $response = $this->recaptcha->verifyResponse($captcha_answer);
        // Processing captcha ...
        if (!$response['success']) {
            $data['status'] = 'failed';
            $data['message'] = $this->global_lib->generateMessage("Anda harus check recaptcha.", "danger");
        }
        else {
            $this->form_validation->set_rules('id_content', '', 'htmlentities|strip_tags|trim|xss_clean|integer');
            $this->form_validation->set_rules('comment_content', '', 'htmlentities|strip_tags|trim|xss_clean|required');
            if ($this->form_validation->run() == false) {
                $data['status'] = 'failed';
                $data['message'] = $this->global_lib->generateMessage("Gagal menambahkan komentar. Isi form dengan lengkap", "danger");
            }
            else {
                $id_content = $this->input->post('id_content');
                $comment = $this->input->post('comment_content');

                //cek id content..
                //ambil data content yang akan diedit.
                $article_data = $this->mdl_content->getPublishedContentByID($id_content);
                //jika tidak ada data, redirect ke index.
                if ((!is_array($article_data)) || count($article_data) < 1) {
                    $data['status'] = 'failed';
                    $data['message'] = $this->global_lib->generateMessage("Gagal menambahkan komentar", "danger");
                }
                else {
                    if ($this->session->userdata('user_logged_in') === true) {
                        //insert komentar..
                        $insert_data = array(
                            'id_content' => $id_content,
                            'id_user' => $this->session->userdata('id_user'),
                            'comment' => $comment,
                            'comment_date' => date('Y-m-d H:i:s')
                        );
                        $this->mdl_content->insertContentComment($insert_data);
                        $data['status'] = 'success';
                    }
                    else {
                        $data['status'] = 'failed';
                        $data['message'] = $this->global_lib->generateMessage("Gagal menambahkan komentar", "danger");
                    }
                }
            }
        }

        echo json_encode($data);
    }

    public function author($id_user = '', $name = '')
    {
        //check id user..
        $data['user'] = $this->mdl_user->getUserByIDArr($id_user);
        //jika tidak ada data, redirect ke index.
        if ((!is_array($data['user'])) || count($data['user']) < 1) {
            redirect(base_url());
        }

        //ambil level user..
        $level = $this->mdl_user->getUserLevelByPoint($data['user'][0]['point']);
        if (isset($level[0]->id_level) && $level[0]->id_level > 0) {
            $data['user'][0]['level'] = $level[0]->level_name;
            $data['user'][0]['bg_color'] = $level[0]->bg_color;
            $data['user'][0]['text_color'] = $level[0]->text_color;
        }

        //ambil artikel by id user..
        $data['total_row'] = $this->mdl_content->getPublishedContentByIDUserCount($id_user);
        $config = $this->authorPaginationConfig($data['total_row']);
        $this->pagination->initialize($config);
        $data['articles'] = $this->mdl_content->getPublishedContentByIDUserLimit($id_user, $config['per_page'], ($this->uri->segment(5) > 0 ? $this->uri->segment(3) : 0));

        $content = $this->load->view('frontend/author', $data, true);
        $this->render($content);
        // print_r('<pre>');
        // print_r($data);
        // print_r('</pre>');
    }

    public function tag($id_tag = '', $tag_name = '')
    {
        $tag_data = $this->mdl_content->getTagByID($id_tag);

        if (!(isset($tag_data[0]->id_content_tag) && $tag_data[0]->id_content_tag > 0)) {
            redirect(base_url());
        }
        if (strtolower(url_title($tag_data[0]->tag_name)) != strtolower($tag_name)) {
            redirect('content/tag/' . $id_tag . '/' . strtolower(url_title($tag_data[0]->tag_name)));
        }

        $this->load->model('mdl_content2');

        $data = [
            'tag' => $tag_data[0]->tag_name,
            'meta' => ['title' => 'Daftar Konten'],
        ];

        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url('content/tag/' . $id_tag . '/' . strtolower(url_title($tag_data[0]->tag_name)));
        $config['per_page'] = $this->per_page_tag;
        $config['uri_segment'] = 5;
        $config['total_rows'] = $data['total_row'] = $this->mdl_content2
            ->where_tag($tag_data[0]->tag_name)
            ->count_all_type_published();
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        $data['contents'] = $this->mdl_content2
            ->where_tag($tag_data[0]->tag_name)
            ->with_user_like($this->session->userdata('id_user'))
            ->all_type_published(
                $config['per_page'],
                ($this->uri->segment(5) > 0 ? $this->uri->segment(5) : 0),
                TRUE
            );

        $this->render(
            $this->load->view('frontend/tag', $data, true)
        );
    }

    public function checkScheduledContent()
    {
        $this->mdl_content->updateScheduledContent();
    }

    private function articleReacted($article_data, $id_reaction, $id_user)
    {
        $result = array();

        //insert reaction
        $insert_data = array(
            'id_content' => $article_data[0]->id_content,
            'id_user' => $id_user,
            'id_reaction' => $id_reaction
        );
        $this->mdl_content->insertContentReaction($insert_data);

        //tambah poin reaction..
        $this->load->library('point_lib');
        $point_config = array(
            'trigger_type' => 'reacted',
            'id_user' => $article_data[0]->id_user,
            'desc' => $article_data[0]->title
        );
        $this->point_lib->addReactionPoint($point_config, $id_reaction);

        //hitung ulang presentase reaksi
        //ambil reaction
        $reactions = $this->mdl_content->getAllReaction();
        //ambil content reaction
        $content_reactions = $this->mdl_content->getContentReaction($article_data[0]->id_content);
        foreach ($reactions as $x => $reaction) {
            $percent = 0;
            $total = 0;
            $pembagi = 0;
            foreach ($content_reactions as $cr) {
                $pembagi += $cr->reaction_count;
                if ($cr->id_reaction == $reaction->id_reaction) {
                    $total = $cr->reaction_count;
                }
            }
            if ($total > 0 && $pembagi > 0) {
                $percent = ($total / $pembagi) * 100;
            }
            if ($percent > 0) {
                $percent_str = rtrim(rtrim(number_format($percent, 1, ',', '.'), '0'), ',') . '%';
            }
            else {
                $percent_str = '0%';
            }
            array_push($result, array(
                'id_reaction' => $reaction->id_reaction,
                'percent' => $percent_str
            ));
        }

        return $result;
    }

    private function articleLiked($article_data, $id_user)
    {
        $result = "";
        $updated_like = $article_data[0]->like_count + 1;
        $updated_like_str = number_format(ceil($updated_like), 0, ',', '.');
        $update_data = array(
            'like_count' => $updated_like
        );
        $this->mdl_content->updateContent($update_data, $article_data[0]->id_content);

        //insert content like..
        $insert_data = array(
            'id_content' => $article_data[0]->id_content,
            'id_user' => $id_user
        );
        $this->mdl_content->insertContentLike($insert_data);

        //tambah poin untuk author..
        $this->load->library('point_lib');
        $point_config = array(
            'trigger_type' => 'liked',
            'id_user' => $article_data[0]->id_user,
            'desc' => $article_data[0]->title
        );
        $this->point_lib->addPoint($point_config);

        //tambah poin untuk yang nge-like..
        $point_config = array(
            'trigger_type' => 'like',
            'id_user' => $id_user,
            'desc' => $article_data[0]->title
        );
        $this->point_lib->addPoint($point_config);
        return $updated_like_str;
    }

    private function articleReaded($article_data)
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P3D'));
        $date_limit = $date->format('Y-m-d');

        $update_data = array(
            'last_read' => date('Y-m-d H:i:s'),
            'read_count' => ($article_data[0]->read_count + 1)
        );
        $this->mdl_content->updateContent($update_data, $article_data[0]->id_content);

        //hapus history read yg kurang dari tgl limit
        // $this->mdl_content->deleteReadHistoryByDateLimit($date_limit);
        //insert/update read counter by date
        $now = date("Y-m-d");
        $content_read = $this->mdl_content->getReadHistoryByIdAndDate($article_data[0]->id_content, $now);
        if (isset($content_read[0]->id_content_read) && is_numeric($content_read[0]->id_content_read)) {
            //update
            $this->mdl_content->updateReadHistory([
                'read' => ($content_read[0]->read + 1)
            ], $article_data[0]->id_content, $now);
        }
        else {
            //insert
            $this->mdl_content->insertReadHistory([
                'id_content' => $article_data[0]->id_content,
                'read_date' => $now,
                'read' => 1
            ]);
        }

        //jika sedang login, input history read user (untuk fitur 'belum dibaca nih')..
        if ($this->session->userdata('user_logged_in') === true) {
            $id_user = $this->session->userdata('id_user');
            if (isset($id_user) && $id_user > 0) {
                //cek apakah sudah pernah dibaca atau belum..
                $has_readed = $this->mdl_content->checkUserContentRead($id_user, $article_data[0]->id_content);
                //jika belum pernah, insert record..
                if (!$has_readed) {
                    $insert_data = array(
                        'id_user' => $id_user,
                        'id_category' => $article_data[0]->id_category,
                        'id_content' => $article_data[0]->id_content
                    );
                    $this->mdl_content->insertUserRead($insert_data);
                }
            }
        }
    }

    private function getMetaData($article_data)
    {
        $meta = array();
        //title
        if (isset($article_data[0]->meta_title) && strlen(trim($article_data[0]->meta_title)) > 0) {
            $meta['title'] = $article_data[0]->meta_title;
        }
        else {
            $meta['title'] = $article_data[0]->title;
        }
        //description
        if (isset($article_data[0]->meta_desc) && strlen(trim($article_data[0]->meta_desc)) > 0) {
            $meta['description'] = $article_data[0]->meta_desc;
        }
        else {
            if (isset($article_data[0]->short_desc) && strlen(trim($article_data[0]->short_desc)) > 0) {
                $meta['description'] = $article_data[0]->short_desc;
            }
        }
        //keyword
        if (isset($article_data[0]->meta_keyword) && strlen(trim($article_data[0]->meta_keyword)) > 0) {
            $meta['keyword'] = $article_data[0]->meta_keyword;
        }
        //picture
        if (isset($article_data[0]->content_pic_thumb) && strlen(trim($article_data[0]->content_pic_thumb)) > 0) {
            $meta['picture'] = base_url() . 'assets/content/thumb/' . $article_data[0]->content_pic_thumb;
        }

        return $meta;
    }

    private function getBreadcrumb($article_data)
    {
        $breadcrumb = '';
        if ($article_data[0]->id_category ?? false) {
            $breadcrumb = '<script type = "application/ld+json" >
            {
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement": [
                    {
                        "@type": "ListItem",
                        "position": 1,
                        "name": "Home",
                        "item": "' . base_url() . '"
                    },{
                        "@type": "ListItem",
                        "position": 2,
                        "name": "' . $article_data[0]->category_name . '",
                        "item": "' . base_url() . 'category/' . $article_data[0]->id_category . '/' . strtolower(url_title($article_data[0]->category_name)) . '"
                    }
                ]
            }
            </script>';
        }

        return $breadcrumb;
    }

    private function render($page = null, $meta = array(), $breadcrumb = '')
    {
        if (isset($page) && $page !== null) {
            //load page view
            $data['content'] = $page;
            $data['meta'] = $meta;
            $data['breadcrumb'] = $breadcrumb;

            //load file2 plugin yang dibutuhkan (jika ada) untuk di load di footer..
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

            //ambil ads footer..
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

    private function tagPaginationConfig($total_rows)
    {
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url() . 'content/tag/' . $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->per_page_tag;
        $config['uri_segment'] = 5;
        return $config;
    }

    private function categoryPaginationConfig($total_rows)
    {
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url() . 'category/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->per_page_category;
        $config['uri_segment'] = 4;
        return $config;
    }

    private function authorPaginationConfig($total_rows)
    {
        $config = $this->global_lib->paginationConfig();
        $config['base_url'] = base_url() . 'content/author/' . $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->per_page_author;
        $config['uri_segment'] = 5;
        return $config;
    }

    private function getTagsAndRelatedContent($data)
    {
        $id_arr = array();
        $tag_arr = array();
        $id_content = $data['article'][0]->id_content;

        //ambil tag content.
        $data['tags'] = $this->mdl_content->getTagByIDContent($id_content);

        //related content.
        foreach ($data['tags'] as $tag) {
            array_push($tag_arr, $tag->tag_name);
        }

        //related article by tag..
        $related_content = array();
        if (isset($tag_arr) && is_array($tag_arr) && count($tag_arr) > 0) {
            $related_content = $this->mdl_content->getRelatedContentByTagArr($tag_arr, $id_content);
            foreach ($related_content as $related) {
                array_push($id_arr, $related['id_content']);
            }
        }
        if (count($id_arr) < 3) {
            // related content by id category..
            $exclude_id = $id_arr;
            array_push($exclude_id, $id_content);
            $related_content = $this->mdl_content->getRelatedContentByIDCategoryArr($data['article'][0]->id_category, $exclude_id);
            foreach ($related_content as $related) {
                array_push($id_arr, $related['id_content']);
            }
        }
        $data['related_article'] = array();
        if (isset($id_arr) && is_array($id_arr) && count($id_arr) > 0) {
            $data['related_article'] = $this->mdl_content->getPublishedContentByIDArr($id_arr, 3);
        }

        return $data;
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
