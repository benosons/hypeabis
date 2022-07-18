<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property-read CI_DB $db
 */
class Mdl_ads_cancel extends CI_Model {
  public function all($limit = NULL, $offset = NULL, $id_user = NULL)
  {
    if (!is_null($id_user)) {
      $this->db->where('tbl_ads_order.id_user', $id_user);
    }

    $now = date('Y-m-d');

    return $this
      ->db
      ->select('tbl_ads_cancel.*')
      ->select('tbl_ads.id_ads, tbl_ads.redirect_url, tbl_ads.ads_pic')
      ->select('tbl_user.id_user, tbl_user.name as user_name')
      ->select('tbl_ads_order_item.price_per_day, tbl_ads_order_item.start_date, tbl_ads_order_item.finish_date')
      ->select('tbl_ads_order_item.price_per_day * (DATEDIFF(tbl_ads_order_item.finish_date, tbl_ads_order_item.start_date) + 1) as subtotal')
      ->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height')
      ->join('tbl_ads', 'tbl_ads_cancel.id_ads_order_item = tbl_ads.id_ads_order_item', 'left')
      ->join('tbl_ads_order_item', 'tbl_ads_cancel.id_ads_order_item = tbl_ads_order_item.id_ads_order_item', 'left')
      ->join('tbl_ads_order', 'tbl_ads_order_item.id_ads_order = tbl_ads_order.id_ads_order', 'left')
      ->join('tbl_user', 'tbl_ads_order.id_user = tbl_user.id_user', 'left')
      ->join('tbl_adstype', 'tbl_ads_order_item.id_adstype = tbl_adstype.id_adstype', 'left')
      ->order_by("tbl_ads.start_date >= '{$now}'", 'desc', FALSE)
      ->order_by("tbl_ads.start_date <= '{$now}' AND tbl_ads.finish_date >= '{$now}'", 'desc', FALSE)
      ->order_by('tbl_ads_order_item.start_date', 'asc')
      ->get('tbl_ads_cancel', $limit, $offset)
      ->result();
  }

  public function find($id_ads_cancel)
  {
    return $this
      ->db
      ->select('tbl_ads_cancel.*')
      ->select('tbl_ads.id_ads, tbl_ads.redirect_url, tbl_ads.ads_pic, tbl_ads.revised_ads_pic')
      ->select('tbl_user.id_user, tbl_user.name as user_name')
      ->select('tbl_ads_order_item.price_per_day, tbl_ads_order_item.start_date, tbl_ads_order_item.finish_date')
      ->select('tbl_ads_order_item.price_per_day * (DATEDIFF(tbl_ads_order_item.finish_date, tbl_ads_order_item.start_date) + 1) as subtotal')
      ->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height')
      ->join('tbl_ads', 'tbl_ads_cancel.id_ads_order_item = tbl_ads.id_ads_order_item', 'left')
      ->join('tbl_ads_order_item', 'tbl_ads_cancel.id_ads_order_item = tbl_ads_order_item.id_ads_order_item', 'left')
      ->join('tbl_ads_order', 'tbl_ads_order_item.id_ads_order = tbl_ads_order.id_ads_order', 'left')
      ->join('tbl_user', 'tbl_ads_order.id_user = tbl_user.id_user', 'left')
      ->join('tbl_adstype', 'tbl_ads_order_item.id_adstype = tbl_adstype.id_adstype', 'left')
      ->get_where('tbl_ads_cancel', compact('id_ads_cancel'))
      ->row();
  }

  public function find_uncancelled_ads($id_ads)
  {
    return $this
      ->db
      ->select('tbl_ads_order_item.*')
      ->select('tbl_ads.id_ads, tbl_ads.redirect_url, tbl_ads.ads_pic')
      ->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height')
      ->select('tbl_ads_order_item.price_per_day * (DATEDIFF(tbl_ads_order_item.finish_date, tbl_ads_order_item.start_date) + 1) as subtotal')
      ->join('tbl_ads_order_item', 'tbl_ads.id_ads_order_item = tbl_ads_order_item.id_ads_order_item', 'right')
      ->join('tbl_adstype', 'tbl_ads_order_item.id_adstype = tbl_adstype.id_adstype', 'left')
      ->join('tbl_ads_cancel', 'tbl_ads_order_item.id_ads_order_item = tbl_ads_cancel.id_ads_order_item', 'left')
      ->get_where('tbl_ads', [
        'id_ads' => $id_ads,
        'id_ads_cancel' => NULL,
      ])
      ->row();
  }

  public function with_user_name()
  {
    throw new Exception('Not Implemented');
    $this
      ->db
      ->select('tbl_user.name AS user_name')
      ->join('tbl_user', 'tbl_ads_order.id_user = tbl_user.id_user', 'left');

    return $this;
  }

  public function count($id_ads_cancel = NULL)
  {
    $this->db->from('tbl_ads_cancel');

    if ($id_ads_cancel)
    {
      $this->db->where(compact('id_ads_cancel'));
    }

    return $this->db->count_all_results();
  }

  public function count_requires_approval()
  {
    $this->db->join('tbl_ads_order_item', 'tbl_ads_cancel.id_ads_order_item = tbl_ads_order_item.id_ads_order_item', 'left');
    $this->db->where('status', 0);
    $this->db->where('tbl_ads_order_item.start_date >=', date('Y-m-d'));

    return $this->count();
  }

  public function insert($item_data)
  {
    $this->db->insert('tbl_ads_cancel', $item_data);
  }

  public function update($data, $id_ads_cancel)
  {
    $this->db->where(compact('id_ads_cancel'))->update('tbl_ads_cancel', $data);
  }

  public function delete($id_ads_cancel)
  {
    $this->db->delete('tbl_ads_cancel', compact('id_ads_cancel'));
  }
}
