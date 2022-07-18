<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mdl_content_tag
 *
 * @property-read CI_DB $db
 */
class Mdl_content_tag extends CI_Model {
  public function all($tag_name = NULL, $limit = NULL, $offset = NULL)
  {
    if ($tag_name) {
      $this->db->like('tag_name', $tag_name, 'both');
    }

    return $this->db
                ->select('DISTINCT tag_name', false)
                ->get('tbl_content_tag', $limit, $offset)->result();
  }
}
