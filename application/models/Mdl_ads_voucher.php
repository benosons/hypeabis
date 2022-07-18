<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_ads_voucher extends CI_Model {
  public function all($limit = NULL, $offset = NULL)
  {
    return $this->db->where('is_deleted', 0)->get('tbl_ads_voucher', $limit, $offset)->result();
  }

  public function find($id_ads_voucher)
  {
    return $this
      ->db
      ->select('tbl_ads_voucher.*')
      ->get_where('tbl_ads_voucher', compact('id_ads_voucher'))
      ->row();
  }

  public function find_by_code($code)
  {
    return $this
      ->db
      ->select('tbl_ads_voucher.*')
      ->get_where('tbl_ads_voucher', compact('code'))
      ->row();
  }

  public function count($id_ads_voucher = NULL)
  {
    $this->db->from('tbl_ads_voucher');

    if ($id_ads_voucher)
    {
      $this->db->where(compact('id_ads_voucher'));
    }

    return $this->db->where('is_deleted', 0)->count_all_results();
  }

  public function insert($data)
  {
    $this->db->insert('tbl_ads_voucher', $data);
  }

  public function update($data, $id_ads_voucher)
  {
    $this->db->where(compact('id_ads_voucher'))->update('tbl_ads_voucher', $data);
  }

  public function delete($id_ads_voucher)
  {
    $this->db->where(compact('id_ads_voucher'))->update('tbl_ads_voucher', ['is_deleted' => 1]);
  }

  public function active()
  {
    $now = date('Y-m-d');

    $this->db
         ->where("start_date <= '{$now}'")
         ->where("end_date >= '{$now}'");

    return $this;
  }
}
