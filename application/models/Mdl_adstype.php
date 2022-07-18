<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_adstype extends CI_Model {
  public function all($limit = NULL, $offset = NULL)
  {
    return $this->db->order_by('ads_order', 'asc')->get('tbl_adstype', $limit, $offset)->result();
  }

  public function find($id_adstype)
  {
    return $this
      ->db
      ->select('tbl_adstype.*')
      ->get_where('tbl_adstype', compact('id_adstype'))
      ->row();
  }

  public function count($id_adstype = NULL)
  {
    $this->db->from('tbl_adstype');

    if ($id_adstype)
    {
      $this->db->where(compact('id_adstype'));
    }

    return $this->db->count_all_results();
  }

  public function has_been_used($id_adstype, $start_date, $finish_date)
  {
    $this
      ->db
      ->select('id_adstype')
      ->from('tbl_ads')
      ->where('id_adstype', $id_adstype)
      ->where("start_date <= '{$finish_date}'")
      ->where("finish_date >= '{$start_date}'");

    return $this->db->count_all_results() > 0;
  }

  public function update($data, $id_adstype)
  {
    $this->db->where(compact('id_adstype'))->update('tbl_adstype', $data);
  }

}
