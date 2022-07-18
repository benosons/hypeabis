<?php

class Mdl_content extends CI_Model
{

    //=============================== GET QUERY ===============================//

    function getTitleByID($id_content)
    {
        $content = $this->db->select('title')->where('type', 1)->get_where('tbl_content', compact('id_content'))->row();
        return $content ? $content->title : null;
    }

    function getUserNameByID($id_content)
    {
        $user = $this
            ->db
            ->select('name')
            ->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left')
            ->where('type', 1)
            ->get_where('tbl_content', compact('id_content'))
            ->row();

        return $user ? $user->name : null;
    }

    function getAllPages($id_content)
    {
        return $this
            ->db
            ->order_by('page_no', 'asc')
            ->get_where('tbl_content_page', array('id_content' => $id_content))
            ->result();
    }

    function getContentByID($id)
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count');
        $this->db->select('(SELECT SUM(tbl_content_read.read) FROM tbl_content_read WHERE tbl_content_read.id_content=tbl_content.id_content) AS read_count');
        $this->db->select('(SELECT COUNT(tbl_content_like.id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content=tbl_content.id_content) AS like_count');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        return $this->db->get_where('tbl_content', array('id_content' => $id, 'type' => 1))->result();
    }

    function getContentByIDAndIDUser($id, $id_user)
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->select('tbl_competition.competition_type, tbl_competition.title AS competition_title, tbl_competition.start_date, tbl_competition.finish_date');
        $this->db->select('tbl_competition_category.category_name AS competition_category_name');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->join('tbl_competition', 'tbl_content.id_competition = tbl_competition.id_competition', 'left');
        $this->db->join('tbl_competition_category', 'tbl_content.id_competition_category = tbl_competition_category.id_competition_category', 'left');
        return $this->db->get_where('tbl_content', array('id_content' => $id, 'type' => 1, 'id_user' => $id_user))->result();
    }

    function getContentPageByID($id)
    {
        return $this
            ->db
            ->get_where('tbl_content_page', ['id' => $id])
            ->row();
    }

    function getContentPageByIDContentAndPage($id_content, $page_no)
    {
        return $this
            ->db
            ->get_where('tbl_content_page', compact('id_content', 'page_no'))
            ->row();
    }

    function getLockedContentId($id_admin)
    {
        $content = $this
            ->db
            ->select('id_content')
            ->get_where('tbl_content', [
                'type' => 1,
                'edit_id_admin' => $id_admin,
            ])
            ->row();

        return $content->id_content ?? null;
    }

    function getAllContent()
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        return $this->db->get('tbl_content')->result();
    }

    function getAllContentCount()
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_admin.name AS admin_name, tbl_admin.username AS admin_username, tbl_admin.email AS admin_email');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        return $this->db->get_where('tbl_content', array('type' => 1))->num_rows();
    }

    function getMaxPageNo($id_content)
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

    function getAllContentLimit($id_admin, $num, $offset)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_admin.name AS admin_name, tbl_admin.username AS admin_username, tbl_admin.email AS admin_email');
        $this->db->select('tbl_edit_admin.name AS edit_admin_name, tbl_edit_admin.username AS admin_username');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->select('tbl_competition_category.category_name AS competition_category_name');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_admin AS tbl_edit_admin', 'tbl_content.edit_id_admin = tbl_edit_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->join('tbl_competition_category', 'tbl_content.id_competition_category = tbl_competition_category.id_competition_category', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where('type', 1);
        $this->db->limit($num, $offset);
        return $this->db->get_where('tbl_content')->result();
    }

    function getAllContentWithoutDraftCount()
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_admin.name AS admin_name, tbl_admin.username AS admin_username, tbl_admin.email AS admin_email');
        $this->db->select('tbl_edit_admin.name AS edit_admin_name, tbl_edit_admin.username AS admin_username');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->select('tbl_competition_category.category_name AS competition_category_name');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_admin AS tbl_edit_admin', 'tbl_content.edit_id_admin = tbl_edit_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->join('tbl_competition_category', 'tbl_content.id_competition_category = tbl_competition_category.id_competition_category', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where('type', 1);
        $this->db->where('content_status !=', -1);
        return $this->db->get_where('tbl_content')->num_rows();
    }

    function getAllContentWithoutDraftLimit($id_admin, $num, $offset)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count');
        $this->db->select('(SELECT SUM(tbl_content_read.read) FROM tbl_content_read WHERE tbl_content_read.id_content=tbl_content.id_content) AS read_count');
        $this->db->select('(SELECT COUNT(tbl_content_like.id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content=tbl_content.id_content) AS like_count');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_competition_category.category_name AS competition_category_name');
        $this->db->select('tbl_admin.name AS admin_name, tbl_admin.username AS admin_username, tbl_admin.email AS admin_email');
        $this->db->select('tbl_edit_admin.name AS edit_admin_name, tbl_edit_admin.username AS admin_username');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_admin AS tbl_edit_admin', 'tbl_content.edit_id_admin = tbl_edit_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->join('tbl_competition_category', 'tbl_content.id_competition_category = tbl_competition_category.id_competition_category', 'left');
        $this->db->order_by("COALESCE(tbl_content.edit_id_admin = '{$id_admin}', 0)", 'desc', FALSE);
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where('type', 1);
        $this->db->where('content_status !=', -1);
        $this->db->limit($num, $offset);
        return $this->db->get_where('tbl_content')->result();
    }

    function getAllContentByIDUserCount($id_user)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_admin.name AS admin_name, tbl_admin.username AS admin_username, tbl_admin.email AS admin_email');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->select('tbl_competition_category.category_name AS competition_category_name');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->join('tbl_competition_category', 'tbl_content.id_competition_category = tbl_competition_category.id_competition_category', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        return $this->db->get_where('tbl_content', array('tbl_content.id_user' => $id_user, 'type' => 1))->num_rows();
    }

    function getAllContentByIDUserLimit($id_user, $num, $offset)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count');
        $this->db->select('(SELECT SUM(tbl_content_read.read) FROM tbl_content_read WHERE tbl_content_read.id_content=tbl_content.id_content) AS read_count');
        $this->db->select('(SELECT COUNT(tbl_content_like.id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content=tbl_content.id_content) AS like_count');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_admin.name AS admin_name, tbl_admin.username AS admin_username, tbl_admin.email AS admin_email');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->select('tbl_competition_category.category_name AS competition_category_name');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->join('tbl_competition_category', 'tbl_content.id_competition_category = tbl_competition_category.id_competition_category', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->limit($num, $offset);
        return $this->db->get_where('tbl_content', array('tbl_content.id_user' => $id_user, 'type' => 1))->result();
    }

    function getAllContentByIDCategoryCount($id_category = '')
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->where('tbl_content.id_category', $id_category);
        return $this->db->get('tbl_content')->num_rows();
    }

    function getAllContentByIDCategoryLimit($id_category, $num, $offset)
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->where('tbl_content.id_category', $id_category);
        $this->db->limit($num, $offset);
        return $this->db->get('tbl_content')->result();
    }

    function getAllContentByIDArrCount($id_arr)
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->where_in('tbl_content.id_category', $id_arr);
        return $this->db->get('tbl_content')->num_rows();
    }

    function getAllContentByIDArrLimit($id_arr, $num, $offset)
    {
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->where_in('tbl_content.id_category', $id_arr);
        $this->db->limit($num, $offset);
        return $this->db->get('tbl_content')->result();
    }

    function getTagByIDContent($id)
    {
        $this->db->order_by('tag_name', 'asc');
        return $this->db->get_where('tbl_content_tag', array('id_content' => $id))->result();
    }

    function getTagByID($id)
    {
        return $this->db->get_where('tbl_content_tag', array('id_content_tag' => $id))->result();
    }

    function getContentByIDCategory($id)
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        return $this->db->get_where('tbl_content', array('tbl_content.id_category' => $id))->result();
    }

    function getContentByIDCategoryLimit($id, $num, $offset)
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->limit($num, $offset);
        return $this->db->get_where('tbl_content', array('tbl_content.id_category' => $id))->result();
    }

    function getContentByIDCategoryCount($id)
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        return $this->db->get_where('tbl_content', array('tbl_content.id_category' => $id))->num_rows();
    }

    function getAllContentByTagCount($tag_name)
    {
        $this->db->select("tbl_content.*");
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->group_by('tbl_content.id_content');
        $this->db->join("tbl_content", "tbl_content_tag.id_content = tbl_content.id_content", "inner");
        return $this->db->get_where('tbl_content_tag', array('tbl_content_tag.tag_name' => $tag_name))->num_rows();
    }

    function getAllContentByTagLimit($tag_name, $num, $offset)
    {
        $this->db->select("tbl_content.*");
        $this->db->limit($num, $offset);
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->group_by('tbl_content.id_content');
        $this->db->join("tbl_content", "tbl_content_tag.id_content = tbl_content.id_content", "inner");
        return $this->db->get_where('tbl_content_tag', array('tbl_content_tag.tag_name' => $tag_name))->result();
    }

    function getContentByIDCategoryWithException($id_category, $id_content, $num, $offset)
    {
        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->where('tbl_content.id_category', $id_category);
        $this->db->where('tbl_content.id_content !=', $id_content);
        $this->db->limit($num, $offset);
        return $this->db->get('tbl_content')->result();
    }

    function getLatestContentIDByDateTime($date)
    {
        $this->db->limit(1);
        $content = $this->db->get_where('tbl_content', array('submit_date' => $date, 'type' => 1))->result();
        return $content[0]->id_content;
    }

    function getSearchResult($search_param, $num, $offset)
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


        $this->db->select('tbl_content.*, tbl_category.category_name, tbl_admin.name, tbl_admin.username, tbl_admin.email');
        $this->db->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count');
        $this->db->select('(SELECT SUM(tbl_content_read.read) FROM tbl_content_read WHERE tbl_content_read.id_content=tbl_content.id_content) AS read_count');
        $this->db->select("($likeCountSql) AS like_count");
        $this->db->select('tbl_edit_admin.name AS edit_admin_name, tbl_edit_admin.username AS admin_username');
        $this->db->select('tbl_competition_category.category_name AS competition_category_name');
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
        if ($search_param['category'] != 'all' && $search_param['category'] >= 0) {
            $this->db->where('tbl_content.id_category', $search_param['category']);
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

        //cek for start date..
        $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
        $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
        if ($search_param['start_date'] != null && $search_param['start_date'] != '') {
            $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
        }
        //cek for finish date
        if ($search_param['finish_date'] != null && $search_param['finish_date'] != '') {
            $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
        }
        //===============================================================================================//

        $this->db->order_by($search_param['sort_by']);
        $this->db->limit($num, $offset);

        $this->db->join('tbl_admin AS tbl_edit_admin', 'tbl_content.edit_id_admin = tbl_edit_admin.id_admin', 'left');
        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->join('tbl_competition_category', 'tbl_content.id_competition_category = tbl_competition_category.id_competition_category', 'left');
        return $this->db->get_where('tbl_content', array('tbl_content.content_status !=' => -1, 'type' => 1))->result();
    }

    function getSearchResultCount($search_param)
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
        if ($search_param['category'] != 'all' && $search_param['category'] >= 0) {
            $this->db->where('tbl_content.id_category', $search_param['category']);
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
            $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
        }
        //cek for finish date
        if ($search_param['finish_date'] != null && $search_param['finish_date'] != '') {
            $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
        }
        //===============================================================================================//

        $this->db->order_by($search_param['sort_by']);

        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        return $this->db->get_where('tbl_content', array('tbl_content.content_status !=' => -1, 'type' => 1))->num_rows();
    }

    function getSearchResultByIDUser($id_user, $search_param, $num, $offset)
    {
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
        if ($search_param['category'] != 'all' && $search_param['category'] >= 0) {
            $this->db->where('tbl_content.id_category', $search_param['category']);
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

        //cek for start date..
        $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
        $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
        if ($search_param['start_date'] != null && $search_param['start_date'] != '') {
            $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
        }
        //cek for finish date
        if ($search_param['finish_date'] != null && $search_param['finish_date'] != '') {
            $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
        }
        //===============================================================================================//

        $this->db->order_by($search_param['sort_by']);
        $this->db->limit($num, $offset);

        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        return $this->db->get_where('tbl_content', array('tbl_content.id_user' => $id_user, 'type' => 1))->result();
    }

    function getSearchResultByIDUserCount($id_user, $search_param)
    {
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
        if ($search_param['category'] != 'all' && $search_param['category'] >= 0) {
            $this->db->where('tbl_content.id_category', $search_param['category']);
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
            $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
        }
        //cek for finish date
        if ($search_param['finish_date'] != null && $search_param['finish_date'] != '') {
            $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
        }
        //===============================================================================================//

        $this->db->order_by($search_param['sort_by']);

        $this->db->join('tbl_admin', 'tbl_content.id_admin = tbl_admin.id_admin', 'left');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        return $this->db->get_where('tbl_content', array('tbl_content.id_user' => $id_user, 'type' => 1))->num_rows();
    }

    function getContentByIdCompetitionCount($id_user, $id_competition){
        return $this->db->get_where('tbl_content', compact('id_user', 'id_competition'))->num_rows();
    }

    //============================== GET QUERY (FRONTEND) ==============================//


    private function _getNewestArticlesQuery($search_param)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where(array('tbl_content.content_status' => 1));
        $this->db->where('type', 1);

        if (!empty($search_param['category'])) {
            $this->db->where('tbl_content.id_category', $search_param['category']);
        }

        if (!empty($search_param['author'])) {
            $this->db->where('tbl_user.id_user', $search_param['author'], 'none');
        }

        if (!empty($search_param['start_date'])) {
            $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
            $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
        }

        if (!empty($search_param['finish_date'])) {
            $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
            $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
        }
    }

    public function getNewestArticlesCount($search_param)
    {
        $this->_getNewestArticlesQuery($search_param);

        return $this->db->get('tbl_content')->num_rows();
    }

    public function getNewestArticles($search_param, $num, $offset)
    {
        $this->_getNewestArticlesQuery($search_param);

        $this->db->limit($num, $offset);

        return $this->db->get('tbl_content')->result();
    }

    function getPopularTags()
    {
        $this->db->limit(15);
        $this->db->select('tbl_content_tag.id_content_tag, tbl_content_tag.tag_name, COUNT(tbl_content_tag.tag_name) AS tag_count');
        $this->db->group_by('tbl_content_tag.tag_name');
        $this->db->order_by('COUNT(tbl_content_tag.tag_name)', 'desc');
        return $this->db->get_where('tbl_content_tag')->result();
    }

    function getContentByKeyword($keyword)
    {
        $this->db->order_by('title', 'asc');
        $this->db->like('title', $keyword);
        $this->db->or_like('short_desc', $keyword);
        return $this->db->get('tbl_content')->result();
    }

    function getRelatedContentByTagArr($tag_arr, $exclude_id)
    {
        $this->db->select('id_content');
        $this->db->where_in('tag_name', $tag_arr);
        $this->db->where('id_content !=', $exclude_id);
        $this->db->where('type', 1);
        $this->db->group_by('id_content');
        return $this->db->get('tbl_content_tag')->result_array();
    }

    // get all tag by id content
    function getAllTagByIDContent($id)
    {
        $this->db->select('tag_name');
        $this->db->where('id_content', $id);
        return $this->db->get('tbl_content_tag')->result();
    }

    // get all content by tag
    function getRelatedContentByTag($tags, $exclude_id)
    {
        $this->db->select('tbl_category.category_name, tbl_content_tag.id_content, tbl_content.id_user, tbl_content.id_category, tbl_content.content_pic_thumb, tbl_content.title, tbl_user.name, tbl_user.picture, tbl_user.picture_from');
        $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_tag.id_content', "LEFT");
        $this->db->join('tbl_user', 'tbl_user.id_user = tbl_content.id_user');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category');
        $this->db->where_in('tbl_content_tag.tag_name', $tags);
        $this->db->where('tbl_content.content_status', 1);
        $this->db->where('tbl_content.type', 1);
        $this->db->limit(6);
        $this->db->where('tbl_content_tag.id_content !=', $exclude_id);
        $this->db->group_by('tbl_content_tag.id_content');
        return $this->db->get('tbl_content_tag')->result();
    }

    // get all content by tag count
    function getRelatedContentByTagCount($tags, $exclude_id)
    {
        $this->db->select('tbl_content_tag.id_content, tbl_content.content_pic_thumb, tbl_content.title, tbl_user.name, tbl_user.picture, tbl_user.picture_from');
        $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_tag.id_content');
        $this->db->join('tbl_user', 'tbl_user.id_user = tbl_content.id_user');
        $this->db->where_in('tbl_content_tag.tag_name', $tags);
        $this->db->where('tbl_content.content_status', 1);
        $this->db->where('tbl_content.type', 1);
        $this->db->where('tbl_content_tag.id_content !=', $exclude_id);
        // $this->db->group_by('id_content');
        return $this->db->get('tbl_content_tag')->num_rows();
    }

    // get all content by category id
    function getRelatedContentByCategory($category, $exclude_id, $limit)
    {
        $this->db->select('tbl_category.category_name, tbl_content.id_content, tbl_content.content_pic_thumb, tbl_content.title, tbl_user.name, tbl_user.picture, tbl_user.picture_from');
        $this->db->join('tbl_user', 'tbl_user.id_user = tbl_content.id_user');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', "LEFT");
        $this->db->where('tbl_content.id_category', $category);
        $this->db->where('tbl_content.content_status', 1);
        $this->db->where('tbl_content.type', 1);
        $this->db->where('tbl_content.id_content !=', $exclude_id);
        $this->db->limit($limit);
        // $this->db->group_by('id_content');
        return $this->db->get('tbl_content')->result();
    }

    function getRelatedContentByIDCategoryArr($id_category, $exclude_id, $limit = 6)
    {
        $this->db->select('tbl_content.id_content, tbl_content.title, tbl_content.content_pic_thumb, tbl_content.id_user, tbl_content.id_category');
        $this->db->select('tbl_category.category_name, tbl_user.name, tbl_user.picture, tbl_user.picture_from');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->join('tbl_user', 'tbl_user.id_user = tbl_content.id_user');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', "LEFT");
        if (isset($exclude_id) && is_array($exclude_id)) {
            $this->db->where_not_in('id_content', $exclude_id);
        }
        $this->db->limit($limit);
        return $this->db->get_where('tbl_content', array('tbl_content.id_category' => $id_category, 'tbl_content.content_status' => 1, 'type' => 1))->result_array();
    }

    function getRecommendedArticleByIDCategory($id_category, $limit)
    {
        $id_category_arr = $this->getCategoryIDWithChild($id_category);
        $this->db->flush_cache();
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->where(['tbl_content.recommended_category' => 1]);
        $this->db->where_in('tbl_content.id_category', $id_category_arr);
        $this->db->limit($limit);
        $this->db->order_by('tbl_content.id_content', 'desc');
        return $this->db->get_where('tbl_content', [
            'tbl_content.content_status' => 1,
            'type' => 1
        ])->result();
    }

    function getRelatedContentByIdCompetitionAndIdCompetitionCategory($id_competition, $id_competition_category, $exclude_id)
    {
        $this->db->select('tbl_category.category_name, tbl_content_tag.id_content, tbl_content.id_user, tbl_content.id_category, tbl_content.content_pic_thumb, tbl_content.title, tbl_user.name, tbl_user.picture, tbl_user.picture_from');
        $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_tag.id_content', "LEFT");
        $this->db->join('tbl_user', 'tbl_user.id_user = tbl_content.id_user');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category');
        $this->db->where('tbl_content.id_competition', $id_competition);
        $this->db->where('tbl_content.id_competition_category', $id_competition_category);
        $this->db->where('tbl_content.content_status', 1);
        $this->db->where('tbl_content.type', 1);
        $this->db->limit(6);
        $this->db->where('tbl_content_tag.id_content !=', $exclude_id);
        $this->db->group_by('tbl_content_tag.id_content');
        return $this->db->get('tbl_content_tag')->result();
    }

    function getRelatedContentByIdCompetitionAndIdCompetitionCategoryCount($id_competition, $id_competition_category, $exclude_id)
    {
        $this->db->select('tbl_category.category_name, tbl_content_tag.id_content, tbl_content.id_user, tbl_content.id_category, tbl_content.content_pic_thumb, tbl_content.title, tbl_user.name, tbl_user.picture, tbl_user.picture_from');
        $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_tag.id_content', "LEFT");
        $this->db->join('tbl_user', 'tbl_user.id_user = tbl_content.id_user');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category');
        $this->db->where('tbl_content.id_competition', $id_competition);
        $this->db->where('tbl_content.id_competition_category', $id_competition_category);
        $this->db->where('tbl_content.content_status', 1);
        $this->db->where('tbl_content.type', 1);
        $this->db->limit(6);
        $this->db->where('tbl_content_tag.id_content !=', $exclude_id);
        $this->db->group_by('tbl_content_tag.id_content');
        return $this->db->get('tbl_content_tag')->num_rows();
    }

    function getRelatedContentByIdCompetition($id_competition, $exclude_id, $limit)
    {
        $this->db->select('tbl_category.category_name, tbl_content_tag.id_content, tbl_content.id_user, tbl_content.id_category, tbl_content.content_pic_thumb, tbl_content.title, tbl_user.name, tbl_user.picture, tbl_user.picture_from');
        $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_tag.id_content', "LEFT");
        $this->db->join('tbl_user', 'tbl_user.id_user = tbl_content.id_user');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category');
        $this->db->where('tbl_content.id_competition', $id_competition);
        $this->db->where('tbl_content.content_status', 1);
        $this->db->where('tbl_content.type', 1);
        $this->db->limit($limit);
        if (isset($exclude_id) && is_array($exclude_id)) {
            $this->db->where_not_in('tbl_content.id_content', $exclude_id);
        }
        $this->db->group_by('tbl_content_tag.id_content');
        return $this->db->get('tbl_content_tag')->result_array();
    }


    function getUserContentByID($id)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count');
        $this->db->select('(SELECT SUM(tbl_content_read.read) FROM tbl_content_read WHERE tbl_content_read.id_content=tbl_content.id_content) AS read_count');
        $this->db->select('(SELECT COUNT(tbl_content_like.id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content=tbl_content.id_content) AS like_count');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified, tbl_user.profile_desc');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'left');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.publish_date', 'desc');
        $this->db->order_by('tbl_content.submit_date', 'desc');
        return $this->db->get_where('tbl_content', array('tbl_content.id_content' => $id))->result();
    }

    function getPublishedContentByID($id)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        return $this->db->get_where('tbl_content', array('tbl_content.id_content' => $id, 'tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getPublishedContentByIDArr($id_arr, $limit)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        if (isset($id_arr) && is_array($id_arr)) {
            $this->db->where_in('id_content', $id_arr);
        }
        $this->db->limit($limit);
        return $this->db->get_where('tbl_content', array('tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getPublishedContentByIDCategoryCount($id)
    {
        $id_category_arr = $this->getCategoryIDWithChild($id);
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where_in('tbl_content.id_category', $id_category_arr);
        return $this->db->get_where('tbl_content', [
            'tbl_content.content_status' => 1,
            'type' => 1
        ])->num_rows();
    }

    function getPublishedContentLimit($num, $offset)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->limit($num, $offset);
        return $this->db->get_where('tbl_content', array('tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getPublishedContentByDate($date)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where(array(
            'tbl_content.submit_date >= ' => $date . " 00:00:00",
            'tbl_content.submit_date <= ' => $date . " 23:59:59",
            'tbl_content.content_status' => 1,
            'type' => 1
        ));
        return $this->db->get('tbl_content')->result();
    }

    function getPublishedContentByIDCategoryLimit($id, $num, $offset)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->limit($num, $offset);
        return $this->db->get_where('tbl_content', array('tbl_content.id_category' => $id, 'tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getPublishedContentByKeywordCount($keyword)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where("(tbl_content.title LIKE '%" . $keyword . "%' OR tbl_content.short_desc LIKE '%" . $keyword . "%')");
        return $this->db->get_where('tbl_content', array('tbl_content.content_status' => 1, 'type' => 1))->num_rows();
    }

    function getPublishedContentByKeyword($keyword, $num, $offset)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->limit($num, $offset);
        $this->db->where("(tbl_content.title LIKE '%" . $keyword . "%' OR tbl_content.short_desc LIKE '%" . $keyword . "%')");
        return $this->db->get_where('tbl_content', array('tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getPublishedContentByTagCount($tag)
    {
        $this->db->select("tbl_content.*");
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->group_by('tbl_content.id_content');
        $this->db->join("tbl_content", "tbl_content_tag.id_content = tbl_content.id_content", "inner");
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->like('tbl_content_tag.tag_name', $tag);
        return $this->db->get_where('tbl_content_tag', array('tbl_content.content_status' => 1, 'type' => 1))->num_rows();
    }

    function getPublishedContentByTagLimit($tag, $num, $offset)
    {
        $this->db->select("tbl_content.*");
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->limit($num, $offset);
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->group_by('tbl_content.id_content');
        $this->db->join("tbl_content", "tbl_content_tag.id_content = tbl_content.id_content", "inner");
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->like('tbl_content_tag.tag_name', $tag);
        return $this->db->get_where('tbl_content_tag', array('tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getPublishedContentByIDCategoryExcludeID($id_category, $id_content, $limit)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->limit($limit);
        return $this->db->get_where('tbl_content', array('tbl_content.id_category' => $id_category, 'tbl_content.id_content !=' => $id_content, 'tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getPublishedContentByIDUserCount($id)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        return $this->db->get_where('tbl_content', array('tbl_content.id_user' => $id, 'tbl_content.content_status' => 1, 'type' => 1))->num_rows();
    }

    function getContentStatisticByIDUser($id)
    {
        $this->db->select('SUM(read_count) AS author_read, SUM(comment_count) AS author_comment');
        $this->db->group_by('tbl_content.id_user');
        return $this->db->get_where('tbl_content', array('tbl_content.id_user' => $id, 'tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getPublishedContentByIDUserLimit($id, $num, $offset)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->limit($num, $offset);
        return $this->db->get_where('tbl_content', array('tbl_content.id_user' => $id, 'tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    private function _getFollowingPublishedContentByIDUserLimit($id_user)
    {
        return $this
            ->db
            ->select('tbl_content.*')
            ->select('tbl_category.category_name')
            ->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified')
            ->from('tbl_content')
            ->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner')
            ->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left')
            ->join('tbl_user_following', 'tbl_user.id_user=tbl_user_following.id_user_following')
            ->where('tbl_user_following.id_user', $id_user)
            ->where('tbl_content.content_status', 1)
            ->where('type', 1)
            ->order_by('tbl_content.id_content', 'desc');
    }

    public function getFollowingPublishedContent($id_user, $num, $offset)
    {
        return $this->_getFollowingPublishedContentByIDUserLimit($id_user)->limit($num, $offset)->get()->result();
    }

    public function getTotalFollowingPublishedContent($id_user)
    {
        return $this->_getFollowingPublishedContentByIDUserLimit($id_user)->count_all_results();
    }

    function getContentComment($id_content, $num, $offset)
    {
        $this->db->select('tbl_content_comment.*');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
        $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'left');
        $this->db->limit($num, $offset);
        $this->db->order_by('tbl_content_comment.id_content_comment', 'desc');
        return $this->db->get_where('tbl_content_comment', array('tbl_content_comment.id_content' => $id_content, 'tbl_content_comment.comment_status =' => 1, 'type' => 1))->result();
    }

    function getContentCommentArr($id_content, $num, $offset)
    {
        $this->db->select('tbl_content_comment.*');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
        $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'left');
        $this->db->limit($num, $offset);
        $this->db->order_by('tbl_content_comment.id_content_comment', 'desc');
        return $this->db->get_where('tbl_content_comment', array('tbl_content_comment.id_content' => $id_content, 'tbl_content_comment.comment_status =' => 1, 'type' => 1))->result_array();
    }

    function getContentCommentCount($id_content)
    {
        $this->db->select('tbl_content_comment.*');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
        $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content_comment.id_content_comment', 'desc');
        return $this->db->get_where('tbl_content_comment', array('tbl_content_comment.id_content' => $id_content, 'tbl_content_comment.comment_status =' => 1, 'type' => 1))->num_rows();
    }

    function getPrevContent($id)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->limit(1);
        return $this->db->get_where('tbl_content', array('tbl_content.id_content < ' => $id, 'tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getNextContent($id)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'asc');
        $this->db->limit(1);
        return $this->db->get_where('tbl_content', array('tbl_content.id_content > ' => $id, 'tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    // get articles featured on home .._
    function getHomepageFeaturedArticle($limit = NULL)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where(array(
            'tbl_content.featured_on_homepage' => 1,
            'tbl_content.content_status' => 1,
            'tbl_content.type' => 1,
            'tbl_content.content_pic_square !=' => null,
        ));
        return $this->db->get('tbl_content', $limit)->result();
    }

    // get articles featured on category .._
    function getCategoryFeaturedArticle($id_category)
    {
        $id_category_arr = $this->getCategoryIDWithChild($id_category);
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where_in('tbl_content.id_category', $id_category_arr);
        $this->db->where([
            'tbl_content.featured_on_category' => 1,
            'tbl_content.content_status' => 1,
            'tbl_content.type' => 1,
            'tbl_content.content_pic_square !=' => null,
        ]);
        $this->db->limit(5);
        return $this->db->get('tbl_content')->result();
    }

    // get articles on top category .._
    function getOnTopCategory($id_category)
    {
        $id_category_arr = $this->getCategoryIDWithChild($id_category);
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where_in('tbl_content.id_category', $id_category_arr);
        $this->db->where(array(
            'tbl_content.on_top_category' => 1,
            'tbl_content.content_status' => 1,
            'tbl_content.type' => 1,
            'tbl_content.content_pic_square !=' => null,
        ));
        $this->db->limit(4);
        return $this->db->get('tbl_content')->result();
    }

    // get trending (buah bibir) articles on home .._
    function getHomepageTrendingArticle()
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where(array(
            'tbl_content.trending' => 1,
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1
        ));
        $this->db->limit(5);
        return $this->db->get('tbl_content')->result();
    }

    // get trending (buah bibir) articles on home .._
    function getHomepageTrendingArticleByCategory($id_category)
    {
        $id_category_arr = $this->getCategoryIDWithChild($id_category);
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where_in('tbl_content.id_category', $id_category_arr);
        $this->db->where([
            'tbl_content.trending' => 1,
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1
        ]);
        $this->db->limit(5);
        return $this->db->get('tbl_content')->result();
    }

    function getHomepageTrendingArticleAll()
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where(array(
            'tbl_content.trending' => 1,
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1
        ));
        return $this->db->get('tbl_content')->result();
    }

    // get newest article on home .._
    function getHomepageNewestArticle($limit = 6)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.publish_date', 'desc');
        $this->db->order_by('tbl_content.submit_date', 'desc');
        $this->db->where(array(
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1,
            'tbl_content.id_competition' => null,
        ));
        $this->db->limit($limit);
        return $this->db->get('tbl_content')->result();
    }

    // get newest article by category .._
    function getHomepageNewestArticleByCategory($id_category, $limit = 6)
    {
        $id_category_arr = $this->getCategoryIDWithChild($id_category);
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.publish_date', 'desc');
        $this->db->order_by('tbl_content.submit_date', 'desc');
        $this->db->where_in('tbl_content.id_category', $id_category_arr);
        $this->db->where([
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1
        ]);
        $this->db->limit(12);
        return $this->db->get('tbl_content')->result();
    }

    function getHomepageRecommendedArticle()
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where(array(
            'tbl_content.recommended' => 1,
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1,
            'tbl_content.id_competition' => null,
        ));
        $this->db->limit(5);
        return $this->db->get('tbl_content')->result();
    }

    function getHomepageRecommendedArticleAll()
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where(array(
            'tbl_content.recommended' => 1,
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1
        ));
        return $this->db->get('tbl_content')->result();
    }

    function getHomepagePopularArticle($limit = 6)
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P3D'));
        $date_limit = $date->format('Y-m-d');

        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_content', "tbl_content.id_content = tbl_content_read.id_content AND read_date >= '{$date_limit}'", 'inner', FALSE);
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('SUM(tbl_content_read.read)', 'desc');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->group_by('tbl_content_read.id_content');
        $this->db->where(array(
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1,
            'tbl_content.id_competition' => null,
        ));
        $this->db->limit($limit);
        return $this->db->get('tbl_content_read')->result();
    }

    function getCommentedArticle()
    {
        $now = new DateTime();
        $last_week_dt = $now->sub(new DateInterval('P1W'));
        $last_week = $last_week_dt->format('Y-m-d H:i:s');

        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.comment_count', 'desc');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->where(array(
            'tbl_content.last_comment > ' => $last_week,
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1
        ));
        $this->db->limit(5);
        return $this->db->get('tbl_content')->result();
    }

    function getCommentedArticleByIDCategory($id_category)
    {
        $now = new DateTime();
        $last_week_dt = $now->sub(new DateInterval('P1W'));
        $last_week = $last_week_dt->format('Y-m-d H:i:s');
        $id_category_arr = $this->getCategoryIDWithChild($id_category);

        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.comment_count', 'desc');
        $this->db->order_by('tbl_content.id_content', 'desc');
        $this->db->limit(5);
        $this->db->where_in('tbl_content.id_category', $id_category_arr);
        return $this->db->get_where('tbl_content', [
            'tbl_content.last_comment > ' => $last_week,
            'tbl_content.type' => 1,
            'tbl_content.content_status' => 1
        ])->result();
    }

    function getHomepageFeaturedAuthor($limit = 10)
    {
        $this->db->join('tbl_user', 'tbl_author_homepage.id_user = tbl_user.id_user', 'left');
        $this->db->limit($limit);
        $this->db->order_by('tbl_author_homepage.author_order', 'asc');
        $this->db->order_by('tbl_author_homepage.id_author_homepage', 'desc');
        return $this->db->get('tbl_author_homepage')->result();
    }

    function getHomepageFeaturedAuthorAll()
    {
        $this->db->join('tbl_user', 'tbl_author_homepage.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_author_homepage.author_order', 'asc');
        $this->db->order_by('tbl_author_homepage.id_author_homepage', 'desc');
        return $this->db->get('tbl_author_homepage')->result();
    }

    function getCategoryFeaturedAuthor($id_category)
    {
        $id_category_arr = $this->getCategoryIDWithChild($id_category);
        $this->db->join('tbl_user', 'tbl_author_category.id_user = tbl_user.id_user', 'left');
        $this->db->limit(10);
        $this->db->order_by('tbl_author_category.author_order', 'asc');
        $this->db->order_by('tbl_author_category.id_author_category', 'desc');
        $this->db->where_in('tbl_author_category.id_category', $id_category_arr);
        return $this->db->get('tbl_author_category')->result();
    }

    function getTopReadCategoryByIDUser($id_user)
    {
        $this->db->select('id_category');
        $this->db->group_by('id_category');
        $this->db->order_by('COUNT(id_category)');
        $this->db->limit(1);
        return $this->db->get_where('tbl_user_read', array('id_user' => $id_user))->result();
    }

    function getReadArticleByIDUserAndIDCategory($id_user, $id_category)
    {
        $this->db->select('id_content');
        return $this->db->get_where('tbl_user_read', array('id_user' => $id_user, 'id_category' => $id_category))->result();
    }

    function getUnreadArticleExcludeIDArr($id_arr, $id_category)
    {
        $this->db->select('tbl_content.*');
        $this->db->select('tbl_category.category_name');
        $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
        $this->db->join('tbl_category', 'tbl_content.id_category = tbl_category.id_category', 'inner');
        $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
        $this->db->order_by('tbl_content.id_content', 'desc');
        if (isset($id_arr) && is_array($id_arr)) {
            $this->db->where_not_in('id_content', $id_arr);
        }
        $this->db->limit(8);
        return $this->db->get_where('tbl_content', array('tbl_content.id_category' => $id_category, 'tbl_content.content_status' => 1, 'type' => 1))->result();
    }

    function getContentLikeByIDAndIDUser($id_content, $id_user)
    {
        return $this->db->get_where('tbl_content_like', array('id_content' => $id_content, 'id_user' => $id_user))->result();
    }

    function getAllReaction()
    {
        $this->db->order_by('reaction_order', 'asc');
        return $this->db->get('tbl_reaction')->result();
    }

    function getReactionByID($id)
    {
        return $this->db->get_where('tbl_reaction', array('id_reaction' => $id))->result();
    }

    function getContentReaction($id_content)
    {
        $this->db->select('tbl_content_reaction.id_reaction, COUNT(tbl_content_reaction.id_reaction) AS reaction_count');
        $this->db->group_by('tbl_content_reaction.id_reaction');
        return $this->db->get_where('tbl_content_reaction', array('tbl_content_reaction.id_content' => $id_content))->result();
    }

    function getContentReactionByIDUser($id_content, $id_user)
    {
        $this->db->group_by('tbl_content_reaction.id_reaction');
        return $this->db->get_where('tbl_content_reaction', array('tbl_content_reaction.id_content' => $id_content, 'tbl_content_reaction.id_user' => $id_user))->result();
    }

    function getReadHistoryByIdAndDate($id_content, $date)
    {
        $this->db->order_by("read", "desc");
        return $this->db->get_where("tbl_content_read", [
            'id_content' => $id_content,
            'read_date' => $date
        ])->result();
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
    //============================== CHECK QUERY ==============================//

    function checkContentByID($id)
    {
        $query = $this->db->get_where('tbl_content', array('id_content' => $id));
        if ($query->num_rows() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    function checkUserContentRead($id_user, $id_content)
    {
        $query = $this->db->get_where('tbl_user_read', array('id_user' => $id_user, 'id_content' => $id_content));
        if ($query->num_rows() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param $filepath_encoded : ##BASE_URL##/filename.jpg
     */
    function isContentImageStillUsed($filepath_encoded)
    {
        $inContentCount = $this->db->like('content', $filepath_encoded)->from('tbl_content')->count_all_results();
        $inContentPageCount = $this->db->like('content', $filepath_encoded)->from('tbl_content_page')->count_all_results();

        return $inContentCount > 0 || $inContentCount > 0;
    }

    //============================== INSERT QUERY =============================//

    function insertContent($insert_data)
    {
        $this->db->insert('tbl_content', $insert_data);
    }

    function insertContentComment($insert_data)
    {
        $this->db->insert('tbl_content_comment', $insert_data);
    }

    function insertTag($insert_data)
    {
        $this->db->insert_batch('tbl_content_tag', $insert_data);
    }

    function insertUserRead($insert_data)
    {
        $this->db->insert('tbl_user_read', $insert_data);
    }

    function insertContentLike($insert_data)
    {
        $this->db->insert('tbl_content_like', $insert_data);
    }

    function insertContentReaction($insert_data)
    {
        $this->db->insert('tbl_content_reaction', $insert_data);
    }

    function insertContentPage($insert_data)
    {
        $this->db->trans_start();

        $this
            ->db
            ->set('page_no', 'page_no+1', FALSE)
            ->where('id_content', $insert_data['id_content'])
            ->where('page_no >=', $insert_data['page_no'])
            ->update('tbl_content_page');

        $this->db->insert('tbl_content_page', $insert_data);
        $this->db->trans_complete();
    }

    public function insertReadHistory($insert_data)
    {
        $this->db->insert('tbl_content_read', $insert_data);
    }

    //============================== UPDATE QUERY =============================//

    function updateContent($update_data, $id)
    {
        $this->db->where('id_content', $id);
        $this->db->update('tbl_content', $update_data);
    }

    function updateContentPage($update_data, $page)
    {
        $this->db->trans_start();

        if ($update_data['page_no'] > $page->page_no) {
            $this
                ->db
                ->set('page_no', 'page_no-1', FALSE)
                ->where('id_content', $page->id_content)
                ->where('page_no >', $page->page_no)
                ->where('page_no <=', $update_data['page_no'])
                ->update('tbl_content_page');
        }
        elseif ($update_data['page_no'] < $page->page_no) {
            $this
                ->db
                ->set('page_no', 'page_no+1', FALSE)
                ->where('id_content', $page->id_content)
                ->where('page_no >=', $update_data['page_no'])
                ->where('page_no <', $page->page_no)
                ->update('tbl_content_page');
        }

        $this->db->where('id', $page->id)->update('tbl_content_page', $update_data);
        $this->db->trans_complete();
    }

    public function updateScheduledContent()
    {
        $this
            ->db
            ->set('content_status', 1)
            ->where('publish_date <=', date('Y-m-d H:i:s'))
            ->where('content_status', 2)
            ->update('tbl_content');
    }

    public function updateReadHistory($update_data, $id, $date)
    {
        $this->db->where('id_content', $id);
        $this->db->where('read_date', $date);
        $this->db->update('tbl_content_read', $update_data);
    }

    //============================== DELETE QUERY =============================//

    function deleteContent($id)
    {
        $this->db->trans_start();
        $this->db->delete('tbl_content', ['id_content' => $id]);
        $this->db->delete('tbl_content_page', ['id_content' => $id]);
        $this->db->trans_complete();
    }

    function deleteReadHistoryByDateLimit($date_limit)
    {
        $this->db->delete('tbl_content_read', ['read_date <' => $date_limit]);
    }

    function deleteTagByIDContent($id_content)
    {
        $this->db->delete('tbl_content_tag', array('id_content' => $id_content));
    }

    function deleteContentPage($contentPage)
    {
        $this->db->trans_start();
        $this->db->delete('tbl_content_page', ['id' => $contentPage->id]);
        $this
            ->db
            ->set('page_no', 'page_no-1', FALSE)
            ->where('id_content', $contentPage->id_content)
            ->where('page_no >', $contentPage->page_no)
            ->update('tbl_content_page');
        $this->db->trans_complete();
    }
}

