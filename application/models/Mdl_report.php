<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property-read CI_DB $db
 */
class Mdl_report extends CI_Model {
  public function all_author($search_param = [], $limit = NULL, $offset = NULL)
  {
    $this->_search_author($search_param);

    return $this->db
                ->select('tbl_user.*')
                ->select('COUNT(CASE WHEN tbl_content.type = 1 THEN 1 END) AS article_count')
                ->select('COUNT(CASE WHEN tbl_content.type = 7 THEN 1 END) AS photo_count')
                ->join('tbl_content', 'tbl_user.id_user = tbl_content.id_user AND content_status = 1', 'left')
                ->group_by('tbl_user.id_user')
                ->order_by('tbl_user.created','desc')
                ->get_where('tbl_user', ['tbl_user.deleted' => 0], $limit, $offset)
                ->result();
  }

  public function count_author($search_param = [])
  {
    $this->_search_author($search_param);

    return $this->db
                ->select('tbl_user.*')
                ->from('tbl_user')
                ->group_by('tbl_user.id_user')
                ->where('tbl_user.deleted', 0)
                ->count_all_results();
  }

  public function all_author_activity($search_param = [], $limit = NULL, $offset = NULL)
  {
    $this->_search_author_activity($search_param);

    $active_login_date = date('Y-m-d', strtotime(date('Y-m') . ' -3 month'));

    return $this->db
                ->select('tbl_user.*')
                ->select('COUNT(CASE WHEN tbl_content.type = 1 THEN 1 END) AS article_count')
                ->select('COUNT(CASE WHEN tbl_content.type = 7 THEN 1 END) AS photo_count')
                ->select("last_login_at >= '{$active_login_date}' AS is_active", FALSE)
                ->join('tbl_content', 'tbl_user.id_user = tbl_content.id_user AND content_status = 1', 'left')
                ->group_by('tbl_user.id_user')
                ->order_by('tbl_user.last_login_at','desc')
                ->get_where('tbl_user', ['tbl_user.deleted' => 0], $limit, $offset)
                ->result();
  }

  public function count_author_activity($search_param = [])
  {
    $this->_search_author_activity($search_param);

    return $this->db
                ->select('tbl_user.*')
                ->from('tbl_user')
                ->group_by('tbl_user.id_user')
                ->where('tbl_user.deleted', 0)
                ->count_all_results();
  }

  public function all_author_productivity($search_param = [], $limit = NULL, $offset = NULL)
  {
    $this->_search_author_productivity($search_param);

    $active_publish_date = date('Y-m-d', strtotime(date('Y-m') . ' -3 month'));
    $join_content_where = '';
    if (!empty($search_param['start_date'])) {
      $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d');
      $join_content_where .= "AND tbl_content.publish_date >= '{$start_date_formatted}'";
    }

    if (!empty($search_param['finish_date'])) {
      $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d');
      $join_content_where .= "AND tbl_content.publish_date <= '{$finish_date_formatted}'";
    }

    return $this->db
                ->select('tbl_user.*')
                ->select('COUNT(CASE WHEN tbl_content.type = 1 THEN 1 END) AS article_count')
                ->select('COUNT(CASE WHEN tbl_content.type = 7 THEN 1 END) AS photo_count')
                ->select('latest_content.last_publish_date')
                ->select("latest_content.last_publish_date >= '{$active_publish_date}' AS is_active", FALSE)
                ->join('tbl_content', "tbl_user.id_user = tbl_content.id_user AND content_status = 1 {$join_content_where}", 'left')
                ->join('(SELECT id_user, MAX(publish_date) AS last_publish_date FROM tbl_content GROUP BY id_user) AS latest_content', 'tbl_user.id_user = latest_content.id_user', 'left')
                ->group_by('tbl_user.id_user')
                ->get_where('tbl_user', ['tbl_user.deleted' => 0], $limit, $offset)
                ->result();
  }

  public function count_author_productivity($search_param = [])
  {
    $this->_search_author_productivity($search_param);

    $active_publish_date = date('Y-m-d', strtotime(date('Y-m') . ' -3 month'));
    $join_content_where = '';
    if (!empty($search_param['start_date'])) {
      $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d');
      $join_content_where .= "AND tbl_content.publish_date >= '{$start_date_formatted}'";
    }

    if (!empty($search_param['finish_date'])) {
      $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d');
      $join_content_where .= "AND tbl_content.publish_date <= '{$finish_date_formatted}'";
    }

    return $this->db
                ->select('tbl_user.*')
                ->select('COUNT(CASE WHEN tbl_content.type = 1 THEN 1 END) AS article_count')
                ->select('COUNT(CASE WHEN tbl_content.type = 7 THEN 1 END) AS photo_count')
                ->select('latest_content.last_publish_date')
                ->select("latest_content.last_publish_date >= '{$active_publish_date}' AS is_active", FALSE)
                ->join('tbl_content', "tbl_user.id_user = tbl_content.id_user AND content_status = 1 {$join_content_where}", 'left')
                ->join('(SELECT id_user, MAX(publish_date) AS last_publish_date FROM tbl_content GROUP BY id_user) AS latest_content', 'tbl_user.id_user = latest_content.id_user', 'left')
                ->group_by('tbl_user.id_user')
                ->get_where('tbl_user', ['tbl_user.deleted' => 0])
                ->num_rows();
  }

  public function all_editor_productivity($search_param = [], $limit = NULL, $offset = NULL)
  {
    $this->_search_editor_productivity($search_param);

    $active_publish_date = date('Y-m-d', strtotime(date('Y-m') . ' -3 month'));

    $join_content_where = '';

    if (!empty($search_param['start_date'])) {
      $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d');
      $join_content_where .= "AND tbl_content_editor.latest_edited_at >= '{$start_date_formatted}'";
    }

    if (!empty($search_param['finish_date'])) {
      $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d');
      $join_content_where .= "AND tbl_content_editor.latest_edited_at <= '{$finish_date_formatted}'";
    }

    return $this->db
                ->select('tbl_admin.*')
                ->select('COUNT(CASE WHEN tbl_content.type = 1 THEN 1 END) AS article_count')
                ->select('COUNT(CASE WHEN tbl_content.type = 7 THEN 1 END) AS photo_count')
                ->select('MAX(CASE WHEN tbl_content.type IN (1,7) THEN publish_date END) last_publish_date')
                ->join('tbl_content_editor', "tbl_admin.id_admin = tbl_content_editor.id_admin {$join_content_where}", 'left')
                ->join('tbl_content', "tbl_content_editor.id_content = tbl_content.id_content AND content_status = 1", 'left')
                ->group_by('tbl_admin.id_admin')
                ->get('tbl_admin', $limit, $offset)
                ->result();
  }

  public function count_editor_productivity($search_param = [])
  {
    $this->_search_editor_productivity($search_param);

    return $this->db
                ->select('tbl_admin.*')
                ->from('tbl_admin')
                ->count_all_results();
  }

  public function all_content($type, $search_param = [], $limit = NULL, $offset = NULL)
  {
    $this->_search_content($search_param);
    $this->db->select('tbl_content.id_content, tbl_content.title, tbl_content.publish_date, tbl_content.type');
    $this->db->select('tbl_category.category_name');
    $this->db->select('tbl_user.name AS user_name, tbl_user.picture AS user_picture, tbl_user.picture_from AS user_picture_from, tbl_user.profile_desc AS user_profile_desc');
    $this->db->select('tbl_admin.name AS admin_name');
    $this->db->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count');
    $this->db->select('(SELECT SUM(tbl_content_read.read) FROM tbl_content_read WHERE tbl_content_read.id_content=tbl_content.id_content) AS read_count');
    $this->db->select('(SELECT COUNT(tbl_content_like.id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content=tbl_content.id_content) AS like_count');
    // $this->db->select('(SELECT MAX(comment_date) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS last_comment');
    $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
    $this->db->join('tbl_category', 'tbl_category.id_category = tbl_content.id_category', 'left');
    $this->db->join('tbl_content_editor', 'tbl_content.id_content=tbl_content_editor.id_content', 'left');
    $this->db->join('tbl_admin', 'tbl_admin.id_admin=tbl_content_editor.id_admin', 'left');
    $this->db->order_by('publish_date', 'desc');
    $this->db->order_by('tbl_content.id_content', 'desc');
    $this->db->where('content_status', 1);
    $this->db->where('type', $type);
    return $this->db->get('tbl_content', $limit, $offset)->result();
  }

  public function all_competition_content($search_param = [], $limit = NULL, $offset = NULL)
  {
    $this->_search_content($search_param);
    $this->db->select('tbl_content.id_content, tbl_content.title, tbl_content.publish_date, tbl_content.type');
    $this->db->select('tbl_category.category_name');
    $this->db->select('tbl_user.name AS user_name, tbl_user.picture AS user_picture, tbl_user.picture_from AS user_picture_from, tbl_user.profile_desc AS user_profile_desc');
    $this->db->select('tbl_admin.name AS admin_name');
    $this->db->select('(SELECT COUNT(tbl_content_comment.id_content_comment) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS comment_count');
    $this->db->select('(SELECT SUM(tbl_content_read.read) FROM tbl_content_read WHERE tbl_content_read.id_content=tbl_content.id_content) AS read_count');
    $this->db->select('(SELECT COUNT(tbl_content_like.id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content=tbl_content.id_content) AS like_count');
    // $this->db->select('(SELECT MAX(comment_date) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS last_comment');
    $this->db->select("CASE WHEN type = 1 THEN LENGTH(content) - LENGTH(REPLACE(content, ' ', '')) + 1 ELSE '-' END AS word_count");
    $this->db->select("CASE WHEN type = 1 THEN CHAR_LENGTH(content) ELSE '-' END AS char_count");
    $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
    $this->db->join('tbl_category', 'tbl_category.id_category = tbl_content.id_category', 'left');
    $this->db->join('tbl_content_editor', 'tbl_content.id_content=tbl_content_editor.id_content', 'left');
    $this->db->join('tbl_admin', 'tbl_admin.id_admin=tbl_content_editor.id_admin', 'left');
    $this->db->order_by('publish_date', 'desc');
    $this->db->order_by('tbl_content.id_content', 'desc');
    $this->db->where('content_status', 1);
    $this->db->where_in('type', [1, 7]);
    return $this->db->get('tbl_content', $limit, $offset)->result();
  }

  public function count_content($type, $search_param = [])
  {
    $this->_search_content($search_param);
    $this->db->select('tbl_content.id_content, tbl_content.title, tbl_content.publish_date, tbl_content.type');
    $this->db->select('tbl_category.category_name');
    $this->db->select('tbl_user.name AS user_name, tbl_user.picture AS user_picture, tbl_user.picture_from AS user_picture_from, tbl_user.profile_desc AS user_profile_desc');
    $this->db->select('tbl_admin.name AS admin_name');
    // $this->db->select('(SELECT MAX(comment_date) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS last_comment');
    $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
    $this->db->join('tbl_category', 'tbl_category.id_category = tbl_content.id_category', 'left');
    $this->db->join('tbl_content_editor', 'tbl_content.id_content=tbl_content_editor.id_content', 'left');
    $this->db->join('tbl_admin', 'tbl_admin.id_admin=tbl_content_editor.id_admin', 'left');
    return $this->db
                ->where('content_status', 1)
                ->where('type', $type)
                ->count_all_results('tbl_content');
  }

  public function count_competition_content($search_param = [])
  {
    $this->_search_content($search_param);
    $this->db->select('tbl_content.id_content, tbl_content.title, tbl_content.publish_date, tbl_content.type');
    $this->db->select('tbl_category.category_name');
    $this->db->select('tbl_user.name AS user_name, tbl_user.picture AS user_picture, tbl_user.picture_from AS user_picture_from, tbl_user.profile_desc AS user_profile_desc');
    $this->db->select('tbl_admin.name AS admin_name');
    // $this->db->select('(SELECT MAX(comment_date) FROM tbl_content_comment WHERE tbl_content_comment.id_content=tbl_content.id_content) AS last_comment');
    $this->db->join('tbl_user', 'tbl_content.id_user = tbl_user.id_user', 'left');
    $this->db->join('tbl_category', 'tbl_category.id_category = tbl_content.id_category', 'left');
    $this->db->join('tbl_content_editor', 'tbl_content.id_content=tbl_content_editor.id_content', 'left');
    $this->db->join('tbl_admin', 'tbl_admin.id_admin=tbl_content_editor.id_admin', 'left');
    $this->db->where_in('type', [1, 7]);
    return $this->db
                ->where('content_status', 1)
                ->count_all_results('tbl_content');
  }

  private function _search_author($search_param)
  {
    if (!empty($search_param['start_date'])) {
      $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d');
      $this->db->where('tbl_user.created >=', $start_date_formatted);
    }

    if (!empty($search_param['finish_date'])) {
      $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d');
      $this->db->where('tbl_user.created <=', $finish_date_formatted);
    }
  }

  private function _search_author_activity($search_param)
  {
    if (isset($search_param['status'])) {
      $active_login_date = date('Y-m-d', strtotime(date('Y-m') . ' -3 month'));

      if ($search_param['status'] === '1') {
        $this->db->where('tbl_user.last_login_at >=', $active_login_date);
      } else {
        $this->db->where('tbl_user.last_login_at <', $active_login_date)->or_where('tbl_user.last_login_at', NULL);
      }
    }
  }

  private function _search_author_productivity($search_param)
  {
    if (isset($search_param['status'])) {
      $active_publish_date = date('Y-m-d', strtotime(date('Y-m') . ' -3 month'));

      if ($search_param['status'] === '1') {
        $this->db->where('latest_content.last_publish_date >=', $active_publish_date);
      } elseif ($search_param['status'] === '0') {
        $this->db->where('latest_content.last_publish_date <', $active_publish_date)->or_where('latest_content.last_publish_date', NULL);
      }
    }

    if (isset($search_param['sort_by'])) {
      if ($search_param['sort_by'] === 'article_count_asc') {
        $this->db->order_by('article_count', 'asc');
      } elseif ($search_param['sort_by'] === 'article_count_desc') {
        $this->db->order_by('article_count', 'desc');
      } elseif ($search_param['sort_by'] === 'photo_count_asc') {
        $this->db->order_by('photo_count', 'asc');
      } elseif ($search_param['sort_by'] === 'photo_count_desc') {
        $this->db->order_by('photo_count', 'desc');
      } else {
        $this->db->order_by('latest_content.last_publish_date', 'desc');
      }
    } else {
      $this->db->order_by('latest_content.last_publish_date', 'desc');
    }
  }

  private function _search_editor_productivity($search_param)
  {
    if (isset($search_param['sort_by'])) {
      if ($search_param['sort_by'] === 'article_count_asc') {
        $this->db->order_by('article_count', 'asc');
      } elseif ($search_param['sort_by'] === 'article_count_desc') {
        $this->db->order_by('article_count', 'desc');
      } elseif ($search_param['sort_by'] === 'photo_count_asc') {
        $this->db->order_by('photo_count', 'asc');
      } elseif ($search_param['sort_by'] === 'photo_count_desc') {
        $this->db->order_by('photo_count', 'desc');
      } else {
        $this->db->order_by('tbl_admin.id_admin', 'asc');
      }
    } else {
      $this->db->order_by('tbl_admin.id_admin', 'asc');
    }
  }

  private function _search_content($search_param)
  {
    if (isset($search_param['sort_by'])) {
      if ($search_param['sort_by'] === 'publish_date_asc') {
        $this->db->order_by('publish_date', 'asc');
      } elseif ($search_param['sort_by'] === 'publish_date_desc') {
        $this->db->order_by('publish_date', 'desc');
      } elseif ($search_param['sort_by'] === 'read_count_asc') {
        $this->db->order_by('read_count', 'asc');
      } elseif ($search_param['sort_by'] === 'read_count_desc') {
        $this->db->order_by('read_count', 'desc');
      } elseif ($search_param['sort_by'] === 'like_count_asc') {
        $this->db->order_by('like_count', 'asc');
      } elseif ($search_param['sort_by'] === 'like_count_desc') {
        $this->db->order_by('like_count', 'desc');
      } elseif ($search_param['sort_by'] === 'comment_count_asc') {
        $this->db->order_by('comment_count', 'asc');
      } elseif ($search_param['sort_by'] === 'comment_count_desc') {
        $this->db->order_by('comment_count', 'desc');
      } else {
        $this->db->order_by('id_content', 'asc');
      }
    } else {
      $this->db->order_by('id_content', 'asc');
    }

    if (!empty($search_param['start_date'])) {
      $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
      $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
    }

    if (!empty($search_param['finish_date'])) {
      $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
      $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
    }

    if (!empty($search_param['author']) && is_numeric($search_param['author']) && $search_param['author'] > 0) {
      $this->db->where('tbl_content.id_user', $search_param['author']);
    }

    if (!empty($search_param['admin']) && is_numeric($search_param['admin']) && $search_param['admin'] > 0) {
      $this->db->where('tbl_content_editor.id_admin', $search_param['admin']);
    }

    if (!empty($search_param['id_competition']) && is_numeric($search_param['id_competition']) && $search_param['id_competition'] > 0) {
      $this->db->where('tbl_content.id_competition', $search_param['id_competition']);
    }
  }
}
