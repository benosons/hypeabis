<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property-read CI_DB $db
 */
class Mdl_content2 extends CI_Model
{
    const TYPE = NULL;

    private $id_user = NULL;

    public function all($limit = NULL, $offset = NULL)
    {
        $this->_where_user();
        $this->_where_type();

        return $this
            ->db
            ->select('tbl_content.*')
            ->select('tbl_category.category_name')
            ->select('tbl_user.name AS user_name, tbl_user.picture AS user_picture, tbl_user.picture_from AS user_picture_from, tbl_user.profile_desc AS user_profile_desc')
            ->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count')
            ->select('(SELECT SUM(tbl_content_read.read) FROM tbl_content_read WHERE tbl_content_read.id_content=tbl_content.id_content) AS read_count')
            ->select('(SELECT COUNT(tbl_content_like.id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content=tbl_content.id_content) AS like_count')
            ->select('(SELECT MAX(comment_date) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS last_comment')
            ->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left')
            ->join('tbl_category', 'tbl_category.id_category = tbl_content.id_category', 'left')
            ->order_by('content_status', 'asc')
            ->order_by('publish_date', 'desc')
            ->order_by('tbl_content.id_content', 'desc')
            ->get('tbl_content', $limit, $offset)
            ->result();
    }

    public function all_published($limit = NULL, $offset = NULL)
    {
        $this->db->where('content_status', 1);
        return $this->all($limit, $offset);
    }

    public function all_type_published($limit = NULL, $offset = NULL)
    {
        $this->db->select("(SELECT COUNT(id) FROM `tbl_content_photo`WHERE `tbl_content`.`id_content`=`id_content`) AS photo_counts");
        $this->db->select("(SELECT picture_thumb FROM tbl_content_photo JOIN (SELECT MIN(id) as id, id_content FROM tbl_content_photo GROUP BY tbl_content_photo.id_content) AS firstPhoto ON tbl_content_photo.id=firstPhoto.id WHERE tbl_content_photo.id_content=tbl_content.id_content) AS first_photo_thumb");

        $this->db->where('content_status', 1);
        $this->db->where('type !=', 2);

        return $this->all($limit, $offset);
    }

    public function all_published_for_homepage($limit = NULL, $offset = NULL)
    {
        $this->db->where('featured_on_homepage', 1);
        $this->db->where('content_status', 1);

        return $this->all($limit, $offset);
    }

    public function all_statistic($limit = NULL, $offset = NULL)
    {
        $this->_where_user();
        $this->_where_type();

        return $this
            ->db
            ->select('tbl_content.id_content, tbl_content.title')
            ->select('tbl_user.name AS user_name, tbl_user.picture AS user_picture, tbl_user.picture_from AS user_picture_from, tbl_user.profile_desc AS user_profile_desc')
            ->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count')
            ->select('(SELECT COUNT(tbl_content_like.id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content=tbl_content.id_content) AS like_count')
            ->select('(SELECT MAX(comment_date) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS last_comment')
            ->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left')
            ->order_by('publish_date', 'desc')
            ->order_by('tbl_content.id_content', 'desc')
            ->get('tbl_content', $limit, $offset)
            ->result();
    }

    public function all_comments($id_content, $limit = NULL, $offset = NULL)
    {
        return $this
            ->db
            ->select('tbl_content_comment.*, tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified')
            ->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'left')
            ->where(compact('id_content'))
            ->where('tbl_content_comment.comment_status', 1)
            ->order_by('comment_date', 'desc')
            ->get('tbl_content_comment', $limit, $offset)
            ->result();
    }

    public function get_locked_content_id($id_admin)
    {
        $this->_where_type();

        $content = $this
            ->db
            ->select('id_content')
            ->get_where('tbl_content', ['edit_id_admin' => $id_admin])
            ->row();

        return $content->id_content ?? null;
    }

    public function get_published_previous($id_content)
    {
        $this->_where_type();

        return $this
            ->db
            ->select('tbl_content.*, tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified, tbl_category.category_name')
            ->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left')
            ->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left')
            ->order_by('submit_date', 'desc')
            ->order_by('tbl_content.id_content', 'desc')
            ->get_where('tbl_content', ['tbl_content.id_content <' => $id_content, 'content_status' => 1], 1)
            ->row();
    }

    public function get_published_next($id_content)
    {
        $this->_where_type();

        return $this
            ->db
            ->select('tbl_content.*, tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified, tbl_category.category_name')
            ->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left')
            ->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left')
            ->order_by('submit_date', 'asc')
            ->order_by('tbl_content.id_content', 'asc')
            ->get_where('tbl_content', ['tbl_content.id_content >' => $id_content, 'content_status' => 1], 1)
            ->row();
    }

    public function get_tags($id_content, $limit = NULL, $offset = NULL)
    {
        return $this->db->get_where('tbl_content_tag', compact('id_content'))->result();
    }

    public function get_oldest_submit_date()
    {
        $content = $this
            ->db
            ->select('DATE(submit_date) AS submit_date')
            ->order_by('submit_date', 'asc')
            ->get('tbl_content')
            ->row();

        return $content ? $content->submit_date : NULL;
    }

    public function get_max_page_no($id_content)
    {
        return $this
            ->db
            ->select('COALESCE(MAX(page_no), 1) AS max_page_no')
            ->from('tbl_content_page')
            ->where('id_content', $id_content)
            ->get()
            ->row()
            ->max_page_no;
    }

    public function find($id_content)
    {
        $this->_where_type();

        $content = $this
            ->db
            ->select('tbl_content.*')
            ->get_where('tbl_content', ['tbl_content.id_content' => $id_content])
            ->row();

        if ($content) {
            $content->tags = implode(
                ',',
                array_column(
                    $this->db->select('tag_name')->get_where('tbl_content_tag', ['id_content' => $id_content])->result(),
                    'tag_name'
                )
            );
        }

        return $content;
    }

    public function find_published($id_content)
    {
        $this->db->where('content_status', 1);
        return $this->find($id_content);
    }

    public function find_with_counts($id_content)
    {
        $this->_where_type();

        return $this
            ->db
            ->select('tbl_content.*')
            ->select('tbl_category.category_name')
            ->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified, tbl_user.profile_desc')
            ->select('(SELECT COUNT(id_content) FROM tbl_content_comment WHERE id_content=tbl_content.id_content) AS comment_count')
            ->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left')
            ->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left')
            ->get_where('tbl_content', ['tbl_content.id_content' => $id_content])
            ->row();
    }

    public function find_published_with_counts($id_content)
    {
        $this->db->where('content_status', 1);
        return $this->find_with_counts($id_content);
    }

    public function with_admin_editor($limit = NULL, $offset = NULL)
    {
        $this->db->select('tbl_edit_admin.name AS edit_admin_name, tbl_edit_admin.username AS admin_username');
        $this->db->join('tbl_admin AS tbl_edit_admin', 'tbl_content.edit_id_admin = tbl_edit_admin.id_admin', 'left');

        return $this;
    }

    public function order_by_admin_editor($id_admin)
    {
        $this->db->order_by("COALESCE(tbl_content.edit_id_admin = '{$id_admin}', 0)", 'desc', FALSE);

        return $this;
    }

    public function popular($is_popular)
    {
        if ($is_popular) {
            $date = new DateTime();
            $date->sub(new DateInterval('P3D'));
            $date_limit = $date->format('Y-m-d');

            $this->db->order_by('tbl_content_read_popular.read_count', 'desc');
            $this->db->join(
                "(SELECT id_content, SUM(`read`) AS read_count FROM tbl_content_read WHERE read_date>='{$date_limit}' GROUP BY id_content) as tbl_content_read_popular",
                'tbl_content.id_content=tbl_content_read_popular.id_content',
                'left'
            );
        }

        return $this;
    }

    public function popular_today()
    {
        $today = date('Y-m-d');
        $this->db->select('tbl_content_read.read_count');
        $this->db->where('tbl_content_read.read_count IS NOT NULL');
        $this->db->order_by('tbl_content_read.read_count', 'desc');
        $this->db->join(
            "(SELECT id_content, SUM(`read`) AS read_count FROM tbl_content_read WHERE read_date='{$today}' GROUP BY id_content) as tbl_content_read",
            'tbl_content.id_content=tbl_content_read.id_content',
            'left'
        );

        return $this;
    }

    public function popular_this_month()
    {
        $first_date = date('Y-m-01');
        $last_date = date('Y-m-t');
        $this->db->select('tbl_content_read.read_count');
        $this->db->where('tbl_content_read.read_count IS NOT NULL');
        $this->db->order_by('tbl_content_read.read_count', 'desc');
        $this->db->join(
            "(SELECT id_content, SUM(`read`) AS read_count FROM tbl_content_read WHERE read_date >= '{$first_date}' AND read_date <= '{$last_date}}' GROUP BY id_content) as tbl_content_read",
            'tbl_content.id_content=tbl_content_read.id_content',
            'left'
        );

        return $this;
    }

    public function without_draft($enabled = true)
    {
        if ($enabled) {
            $this->db->group_start();
            $this->db->where('tbl_content.content_status !=', -1);
            $this->db->or_where('tbl_content.id_user', NULL);
            $this->db->group_end();
        }

        return $this;
    }

    public function count($id_content = NULL)
    {
        $this->_where_user();
        $this->_where_type();
        $this->db->from('tbl_content');
        if ($id_content) {
            $this->db->where(compact('id_content'));
        }

        return $this->db->count_all_results();
    }

    public function count_draft()
    {
        $this->_where_type();
        $this->db->where('content_status', -1);

        return $this->count();
    }

    public function count_published($id_content = NULL)
    {
        $this->db->where('content_status', 1);

        return $this->count($id_content);
    }

    public function count_all_type_published($id_content = NULL)
    {
        $this->db->where('content_status', 1);
        $this->db->where('type !=', 2);

        return $this->count($id_content);
    }

    public function count_published_for_homepage($id_content = NULL)
    {
        $this->_where_type();
        $this->db->where('content_status', 1);
        $this->db->where('featured_on_homepage', 1);

        return $this->count($id_content);
    }

    public function count_requires_approval()
    {
        $this->_where_type();
        $this->db->where('content_status', 0);

        return $this->count();
    }

    public function count_all_comments($id_content)
    {
        $comment_status = 1;
        return $this->db->from('tbl_content_comment')->where(compact('id_content', 'comment_status'))->count_all_results();
    }

    public function count_all_likes($id_content)
    {
        return $this->db->from('tbl_content_like')->where(compact('id_content'))->count_all_results();
    }

    public function count_all_reactions($id_content)
    {
        $count_sql = 'COUNT(tbl_content_reaction.id_content_reaction)';
        $condition_sql = "id_content='{$id_content}'";
        $total_reaction_sql = "(SELECT COUNT(*) FROM tbl_content_reaction WHERE {$condition_sql}) * 100";

        return $this
            ->db
            ->select('tbl_reaction.id_reaction, reaction_name, reaction_pic')
            ->select("{$count_sql} AS counts")
            ->select("{$count_sql} / {$total_reaction_sql} AS percentage")
            ->from('tbl_reaction')
            ->join(
                'tbl_content_reaction',
                "tbl_reaction.id_reaction=tbl_content_reaction.id_reaction AND {$condition_sql}",
                'left',
                TRUE
            )
            ->group_by('tbl_reaction.id_reaction, reaction_name, reaction_pic')
            ->order_by('tbl_reaction.reaction_order')
            ->get()
            ->result();
    }

    public function count_search_result($search_param)
    {
        // Competition
        if (isset($search_param['competition'])) {
            if ($search_param['competition'] === 'not') {
                $this->db->where('tbl_content.id_competition', NULL);
            }
            elseif (!in_array($search_param['competition'], ['all', 'not'])) {
                $this->db->where('tbl_content.id_competition', $search_param['competition']);
            }
        }

        $this->_where_user();
        $this->_where_type();

        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');

        if ($search_param['keyword'] != null && $search_param['keyword'] != '') {
            if ($search_param['operator'] == 'like') {
                $this->db->like($search_param['search_by'], $search_param['keyword']);
            }
            else {
                if ($search_param['operator'] == 'not like') {
                    $this->db->not_like($search_param['search_by'], $search_param['keyword']);
                }
                else {
                    $this->db->where($search_param['search_by'] . ' ' . $search_param['operator'], $search_param['keyword']);
                }
            }
        }

        //============================== ADDITIONAL SEARCH PARAMETER ===================================//
        //check for category..
        if (isset($search_param['category']) && $search_param['category'] != 'all' && $search_param['category'] >= 0) {
            $id_category_arr = $this->getCategoryIDWithChild($search_param['category']);
            $this->db->where_in('tbl_content.id_category', $id_category_arr);
            //$this->db->where('tbl_content.id_category', $search_param['category']);
        }

        //cek for content_status..
        if ($search_param['content_status'] == 'publish') {
            $this->db->where('tbl_content.content_status', 1);
        }
        else {
            if ($search_param['content_status'] == 'unpublish') {
                $this->db->where('tbl_content.content_status', 0);
            }
            else {
            }
        }

        $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
        $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
        if ($search_param['start_date'] != null && $search_param['start_date'] != '') {
            $this->db->where('tbl_content.submit_date >=', $start_date_formatted);
        }
        //cek for finish date
        if ($search_param['finish_date'] != null && $search_param['finish_date'] != '') {
            $this->db->where('tbl_content.submit_date <=', $finish_date_formatted);
        }
        //===============================================================================================//

        $this->db->order_by($search_param['sort_by']);

        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');

        return $this->db->get_where('tbl_content', array('tbl_content.content_status !=' => -1))->num_rows();
    }

    public function has_like_from($id_content, $id_user)
    {
        return $this
                ->db
                ->where(compact('id_content', 'id_user'))
                ->from('tbl_content_like')
                ->count_all_results() > 0;
    }

    public function has_reaction_from($id_content, $id_user)
    {
        return $this
                ->db
                ->where(compact('id_content', 'id_user'))
                ->from('tbl_content_reaction')
                ->count_all_results() > 0;
    }

    public function filter_by_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function insert_content($content_data, $tags)
    {
        $this->db->trans_start();

        if (!is_array(static::TYPE)) {
            // $content_data['type'] = $content_data['type'] ?? static::TYPE;
            $content_data['type'] = isset($content_data['type']) ? $content_data['type'] : static::TYPE;
        }

        $content_data['submit_date'] = date('Y-m-d H:i:s');
        $this->db->insert('tbl_content', $content_data);
        $last_id = $this->db->insert_id();

        $this->_insert_tags($tags, $last_id);
        $this->db->trans_complete();

        return $last_id;
    }

    public function add_comment($id_content, $id_user, $comment)
    {
        $comment_date = date('Y-m-d H:i:s');

        $this->db->insert(
            'tbl_content_comment',
            compact('id_content', 'id_user', 'comment', 'comment_date')
        );
    }

    public function add_reaction($id_content, $id_user, $id_reaction)
    {
        $this->db->insert('tbl_content_reaction', compact('id_content', 'id_user', 'id_reaction'));
    }

    public function add_like($id_content, $id_user)
    {
        $this->db->set('like_count', '`like_count` + 1', FALSE)
            ->where('id_content', $id_content)
            ->update('tbl_content');
        $this->db->insert('tbl_content_like', compact('id_content', 'id_user'));
    }

    public function substract_like($id_content, $id_user)
    {
        $this->db->set('like_count', '`like_count` - 1', FALSE)
            ->where('id_content', $id_content)
            ->update('tbl_content');
        $this->db->delete('tbl_content_like', compact('id_content', 'id_user'));
    }

    public function update_content($id_content, $content_data, $tags)
    {
        $this->db->trans_start();
        $this->db->where(compact('id_content'))->update('tbl_content', $content_data);

        $this->_insert_tags($tags, $id_content);
        $this->db->trans_complete();
    }

    public function update_content_without_tags($id_content, $content_data)
    {
        $this->db->where(compact('id_content'))->update('tbl_content', $content_data);
    }

    public function update_read_stats($id_content)
    {
        $this
            ->db
            ->set('read_count', 'read_count+1', FALSE)
            ->set('last_read', date('Y-m-d H:i:s'))
            ->where(compact('id_content'))
            ->update('tbl_content');
    }

    public function delete($id_content)
    {
        $this->db->trans_start();
        $this->db->delete('tbl_content', compact('id_content'));
        $this->db->delete('tbl_content_tag', compact('id_content'));
        $this->db->trans_complete();
    }

    public function remove_picture($id_content)
    {
        $this->db->where(compact('id_content'))->update('tbl_content', [
            'content_pic' => NULL,
            'content_pic_thumb' => NULL,
            'content_pic_square' => NULL,
        ]);
    }

    public function where_id_user($id_user)
    {
        $this->db->where('tbl_content.id_user', $id_user);

        return $this;
    }

    public function where_tag($tag_name)
    {
        $this->db->join('tbl_content_tag', 'tbl_content.id_content = tbl_content_tag.id_content', 'inner');
        $this->db->where('tbl_content_tag.tag_name', $tag_name);

        return $this;
    }

    public function except_id_content($id)
    {
        $this->db->where_not_in('id_content', $id);

        return $this;
    }

    public function search($search_param)
    {
        if (!empty($search_param['title'])) {
            $this->db->like('tbl_content.title', $search_param['title'], 'both');
        }

        if (!empty($search_param['category'])) {
            $id_category_arr = $this->getCategoryIDWithChild($search_param['category']);
            $this->db->where_in('tbl_content.id_category', $id_category_arr);
            //$this->db->where('tbl_content.id_category', $search_param['category']);
        }

        if (isset($search_param['hypeshop']) && $search_param['hypeshop']) {
            $this->db->where('tbl_content.type', 6);
        }

        if (isset($search_param['hypephoto']) && $search_param['hypephoto']) {
            $this->db->where('tbl_content.type', 7);
        }

        if (!empty($search_param['author'])) {
            $this->where_id_user($search_param['author']);
        }

        if (!empty($search_param['admin'])) {
            $this->db->join('tbl_content_editor', 'tbl_content.id_content=tbl_content_editor.id_content', 'left');
            $this->db->where('tbl_content_editor.id_admin', $search_param['admin']);
        }

        if (!empty($search_param['start_date'])) {
            $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
            $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
        }

        if (!empty($search_param['finish_date'])) {
            $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
            $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
        }

        return $this;
    }

    public function search2($search_param, $num, $offset, $include_draft = false)
    {
        // Competition Like
        $searchLikeStartDate = $search_param['like_start_date'] != null && $search_param['like_start_date'] != '';
        $searchLikeFinishDate = $search_param['like_finish_date'] != null && $search_param['like_finish_date'] != '';
        $likeCountSql = "SELECT COUNT(tbl_content_like.id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content=tbl_content.id_content";

        if ($searchLikeStartDate || $searchLikeFinishDate) {
            $like_start_date_formatted = $this->global_lib->formatDate($search_param['like_start_date'], 'd-m-Y', 'Y-m-d 00:00:00');
            $like_finish_date_formatted = $this->global_lib->formatDate($search_param['like_finish_date'], 'd-m-Y', 'Y-m-d 00:00:00');

            if ($searchLikeStartDate) {
                $likeCountSql .= " AND created_at >= '{$like_start_date_formatted}'";
            }

            if ($searchLikeFinishDate) {
                $likeCountSql .= " AND created_at <= '{$like_finish_date_formatted}'";
            }
        }

        // Competition
        if (isset($search_param['competition'])) {
            if ($search_param['competition'] === 'not') {
                $this->db->where('tbl_content.id_competition', NULL);
            }
            elseif (!in_array($search_param['competition'], ['all', 'not'])) {
                $this->db->where('tbl_content.id_competition', $search_param['competition']);
            }
        }

        $this->_where_user();
        $this->_where_type();

        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->select('tbl_edit_admin.name AS edit_admin_name, tbl_edit_admin.username AS admin_username');
        $this->db->select('(SELECT SUM(tbl_content_read.read) FROM tbl_content_read WHERE tbl_content_read.id_content=tbl_content.id_content) AS read_count');
        $this->db->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count');
        $this->db->select("($likeCountSql) AS like_count");

        if ($search_param['keyword'] != null && $search_param['keyword'] != '') {
            if ($search_param['operator'] == 'like') {
                $this->db->like($search_param['search_by'], $search_param['keyword']);
            }
            else {
                if ($search_param['operator'] == 'not like') {
                    $this->db->not_like($search_param['search_by'], $search_param['keyword']);
                }
                else {
                    $this->db->where($search_param['search_by'] . ' ' . $search_param['operator'], $search_param['keyword']);
                }
            }
        }

        //============================== ADDITIONAL SEARCH PARAMETER ===================================//
        //check for category..
        if (isset($search_param['category']) && $search_param['category'] != 'all' && $search_param['category'] >= 0) {
            $id_category_arr = $this->getCategoryIDWithChild($search_param['category']);
            $this->db->where_in('tbl_content.id_category', $id_category_arr);
            //$this->db->where('tbl_content.id_category', $search_param['category']);
        }

        //cek for content_status..
        if ($search_param['content_status'] == 'publish') {
            $this->db->where('tbl_content.content_status', 1);
        }
        else if ($search_param['content_status'] == 'unpublish') {
            $this->db->where('tbl_content.content_status', 0);
        }
        else if ($search_param['content_status'] == 'draft') {
            $this->db->where('tbl_content.content_status', -1);
        }
        else{}

        //cek for start date..
        $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
        $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
        if ($search_param['start_date'] != null && $search_param['start_date'] != '') {
            $this->db->where('tbl_content.submit_date >=', $start_date_formatted);
        }
        //cek for finish date
        if ($search_param['finish_date'] != null && $search_param['finish_date'] != '') {
            $this->db->where('tbl_content.submit_date <=', $finish_date_formatted);
        }
        //===============================================================================================//

        if (! $include_draft){
            $this->db->where(['tbl_content.content_status !=' => -1]);
        }

        $this->db->order_by($search_param['sort_by']);
        $this->db->limit($num, $offset);

        $this->db->join('tbl_admin AS tbl_edit_admin', 'tbl_content.edit_id_admin = tbl_edit_admin.id_admin', 'left');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');

        return $this->db->get_where('tbl_content')->result();
    }

    public function getContentByIdCompetitionCount($id_user, $id_competition)
    {
        return $this->db->get_where('tbl_content', compact('id_user', 'id_competition'))->num_rows();
    }

    protected function _where_type()
    {
        if (static::TYPE) {
            $type = is_array(static::TYPE) ? static::TYPE : [static::TYPE];
            $this->db->where_in('type', static::TYPE);
        }
    }

    protected function _where_user()
    {
        if ($this->id_user) {
            $this->db->where('tbl_content.id_user', $this->id_user);
        }
    }

    private function _insert_tags($tags, $id_content)
    {
        $this->db->delete('tbl_content_tag', compact('id_content'));

        $filtered_tags = array_filter($tags, function ($tag) {
            return !empty($tag);
        });

        if (count($filtered_tags) > 0) {
            $this->db->insert_batch('tbl_content_tag', array_map(function ($tag_name) use ($id_content) {
                return compact('id_content', 'tag_name');
            }, $filtered_tags));
        }
    }

    public function with_user_like($id_user = NULL)
    {
        if ($id_user) {
            $this->db->select("(EXISTS (SELECT * FROM tbl_content_like WHERE tbl_content.id_content=tbl_content_like.id_content AND tbl_content_like.id_user={$id_user})) as is_liked");
        }
        else {
            $this->db->select('0 as is_liked');
        }

        return $this;
    }

    private function getCategoryIDWithChild($id_category): array
    {
        $id_arr = [];

        //get child category
        $this->db->start_cache();
        $this->db->select('tbl_category.id_category');
        $childs = $this->db->get_where('tbl_category', ['tbl_category.category_parent' => $id_category])->result();
        $this->db->stop_cache();
        $this->db->flush_cache();

        return array_merge(
            [0 => $id_category],
            array_map(function($child){
                return $child->id_category;
            }, ($childs ?? []))
        );
    }

    public function join_competition($id_competition)
    {
        $this->db->where(compact('id_competition'));
        return $this;
    }

    public function with_competition_like($competition)
    {
        $this->db->select("(SELECT COUNT(id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content = tbl_content.id_content AND DATE(tbl_content_like.created_at) >= '{$competition->start_date}' AND DATE(tbl_content_like.created_at) <= '{$competition->finish_date}') AS competition_like_count");

        return $this;
    }

    public function where_competition_category($id_competition_category)
    {
        if ($id_competition_category) {
            $this->db->where(compact('id_competition_category'));
        }

        return $this;
    }

    public function order_by_like($status = true)
    {
        if ($status) {
            $this->db->order_by('like_count', 'desc');
        }

        return $this;
    }

    public function order_by_competition_like($status = true)
    {
        if ($status) {
            $this->db->order_by('competition_like_count', 'desc');
        }

        return $this;
    }
}
