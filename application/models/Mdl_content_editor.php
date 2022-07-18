<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mdl_content_editor
 *
 * @property-read CI_DB $db
 */
class Mdl_content_editor extends CI_Model {
  public function get_id($id_content, $id_admin)
  {
    $data = $this->db
                 ->get_where('tbl_content_editor', compact('id_content', 'id_admin'))
                 ->row();

    return $data ? $data->id_content_editor : NULL;
  }

  public function get_all_names($id_content)
  {
    $data = $this
      ->db
      ->select('tbl_admin.name')
      ->join('tbl_admin', 'tbl_content_editor.id_admin=tbl_admin.id_admin')
      ->get_where('tbl_content_editor', compact('id_content'))
      ->result();

    return array_column($data, 'name');
  }

  public function insert_or_update($id_content, $id_admin)
  {
    $id_content_editor = $this->get_id($id_content, $id_admin);
    $latest_edited_at = date('Y-m-d H:i:s');

    if (is_null($id_content_editor)) {
      $this->db->insert('tbl_content_editor', compact('id_content', 'id_admin', 'latest_edited_at'));
    } else {
      $this->db->where(compact('id_content_editor'))->update('tbl_content_editor', compact('latest_edited_at'));
    }
  }
}
