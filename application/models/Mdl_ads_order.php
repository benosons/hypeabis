<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_ads_order extends CI_Model {
  public function all($limit = NULL, $offset = NULL, $id_user = NULL)
  {
    if (!is_null($id_user)) {
      $this->db->where('id_user', $id_user);
    }

    return $this
      ->db
      ->select('tbl_ads_order.*')
      ->select('(SELECT COUNT(*) FROM tbl_ads_order_item WHERE tbl_ads_order.id_ads_order=tbl_ads_order_item.id_ads_order) as ads_count')
      ->select('(SELECT SUM(price_per_day * (DATEDIFF(finish_date, start_date) + 1)) FROM tbl_ads_order_item WHERE tbl_ads_order.id_ads_order=tbl_ads_order_item.id_ads_order) as total')
      ->get_where('tbl_ads_order', ['order_status !=' => 0], $limit, $offset)->result();
  }

  public function all_by_ids($ids, $limit = NULL, $offset = NULL)
  {
    if (!empty($ids))
    {
      $this->db->where_in('id_ads_order', $ids);
    }

    return $this->all($limit, $offset);
  }

  public function get_cart_id($id_user)
  {
    $cart = $this
      ->db
      ->select('tbl_ads_order.id_ads_order')
      ->get_where('tbl_ads_order', [
        'id_user' => $id_user,
        'order_status' => 0,
      ])
      ->row();

    if (is_null($cart)) {
      $this->db->insert('tbl_ads_order', compact('id_user'));
      return $this->db->insert_id();
    }

    return $cart->id_ads_order;
  }

  public function get_cart($id_user, $create_cart = true)
  {
    $cart = $this
      ->db
      ->select('tbl_ads_order.*, tbl_ads_voucher.code as voucher_code, tbl_ads_voucher.value as voucher_value')
      ->join('tbl_ads_voucher', 'tbl_ads_order.id_ads_voucher=tbl_ads_voucher.id_ads_voucher', 'left')
      ->get_where('tbl_ads_order', [
        'id_user' => $id_user,
        'order_status' => 0,
      ])
      ->row();

    if (is_null($cart) && $create_cart) {
      $this->db->insert('tbl_ads_order', compact('id_user'));
      $last_id = $this->db->insert_id();
      $cart = $this->find($last_id);
    }

    if ($cart) {
      $cart->items = $this->_get_items($cart->id_ads_order);
    }

    return $cart;
  }

  public function get_free_positions_select2($id_ads_order, $start_date, $finish_date, $query = NULL, $except_id = NULL)
  {
    $ads_subquery = $this
      ->db
      ->select('id_adstype')
      ->from('tbl_ads')
      ->where("start_date <= '{$finish_date}'")
      ->where("finish_date >= '{$start_date}'")
      ->get_compiled_select();

    $cart_subquery = $this
      ->db
      ->reset_query()
      ->select('id_adstype')
      ->from('(SELECT * FROM tbl_ads_order_item WHERE id_ads_order_item NOT IN (SELECT id_ads_order_item FROM tbl_ads_cancel WHERE status >= 1)) AS tbl_ads_order_item')
      ->where('tbl_ads_order_item.id_ads_order', $id_ads_order)
      ->join('tbl_ads_order', 'tbl_ads_order_item.id_ads_order=tbl_ads_order.id_ads_order', 'left')
      ->where("start_date <= '{$finish_date}'")
      ->where("finish_date >= '{$start_date}'")
      ->where_in('payment_status', ['capture', 'settlement', 'pending'])
      ->where('order_status', 1)
      ->or_group_start()
      ->where('tbl_ads_order_item.id_ads_order', $id_ads_order)
      ->where("start_date <= '{$finish_date}'")
      ->where("finish_date >= '{$start_date}'")
      ->group_end();

    if (!is_null($except_id))
    {
      $cart_subquery->where('id_ads_order_item !=', $except_id);
    }

    $cart_subquery = $cart_subquery->get_compiled_select();

    $this
      ->db
      ->reset_query()
      ->select('tbl_adstype.id_adstype as id, ads_name as text, price_per_day, location_pic')
      ->where("id_adstype NOT IN ({$ads_subquery})")
      ->where("id_adstype NOT IN ({$cart_subquery})")
      ->order_by('ads_order','asc');

    if (!empty($query))
    {
      $this->db->like('ads_name', $query);
    }

    return $this->db->get('tbl_adstype')->result();
  }

  public function find($id_ads_order)
  {
    $ads_order = $this
      ->db
      ->select('tbl_ads_order.*')
      ->get_where('tbl_ads_order', compact('id_ads_order'))
      ->row();

    if ($ads_order) {
      $ads_order->items = $this
        ->db
        ->select('tbl_ads_order_item.*')
        ->select('tbl_ads.id_ads, tbl_ads.edit_id_admin, tbl_ads.redirect_url, tbl_ads.ads_pic, tbl_ads.status')
        ->select('tbl_adstype.ads_name')
        ->select('tbl_ads_order_item.price_per_day * (DATEDIFF(tbl_ads_order_item.finish_date, tbl_ads_order_item.start_date) + 1) as subtotal')
        ->join('tbl_ads', 'tbl_ads.id_ads_order_item = tbl_ads_order_item.id_ads_order_item', 'left')
        ->join('tbl_adstype', 'tbl_ads_order_item.id_adstype = tbl_adstype.id_adstype', 'left')
        ->get_where('tbl_ads_order_item', ['id_ads_order' => $ads_order->id_ads_order])
        ->result();
    }

    return $ads_order;
  }

  public function find_item($id_ads_order_item)
  {
    return $this
      ->db
      ->select('tbl_ads_order_item.id_ads_order_item, tbl_ads_order_item.id_ads_order, tbl_ads_order_item.id_adstype, tbl_adstype.ads_name')
      ->select('tbl_ads_order_item.start_date, tbl_ads_order_item.finish_date')
      ->select('tbl_ads_order_item.is_booked')
      ->select('tbl_adstype.price_per_day, tbl_adstype.location_pic')
      ->select('tbl_adstype.price_per_day * (DATEDIFF(finish_date, start_date) + 1) as subtotal')
      ->join('tbl_adstype', 'tbl_ads_order_item.id_adstype = tbl_adstype.id_adstype', 'left')
      ->get_where('tbl_ads_order_item', compact('id_ads_order_item'))
      ->row();
  }

  public function with_user_name()
  {
    $this
      ->db
      ->select('tbl_user.name AS user_name')
      ->join('tbl_user', 'tbl_ads_order.id_user = tbl_user.id_user', 'left');

    return $this;
  }

  public function is_used_voucher($id_ads_voucher)
  {
    return $this
      ->db
      ->from('tbl_ads_order')
      ->where('id_ads_voucher', $id_ads_voucher)
      ->where('order_status', 1)
      ->where_in('payment_status', ['capture', 'settlement', 'pending'])
      ->count_all_results() > 0;
  }

  public function count($id_ads_order = NULL)
  {
    $this->db->from('tbl_ads_order');

    if ($id_ads_order)
    {
      $this->db->where(compact('id_ads_order'));
    }

    return $this->db->count_all_results();
  }

  public function count_item($id_ads_order_item = NULL)
  {
    $this->db->from('tbl_ads_order_item');

    if ($id_ads_order_item)
    {
      $this->db->where(compact('id_ads_order_item'));
    }

    return $this->db->count_all_results();
  }

  public function has_been_used($id_adstype, $start_date, $finish_date, $id_ads_order = NULL, $except_id = NULL)
  {
    $this
      ->db
      ->select('id_adstype')
      ->from('tbl_ads_order_item')
      ->join('tbl_ads_order', 'tbl_ads_order_item.id_ads_order=tbl_ads_order.id_ads_order', 'left')
      ->join('tbl_ads_cancel', 'tbl_ads_order_item.id_ads_order_item=tbl_ads_cancel.id_ads_order_item', 'left')
      ->where('id_adstype', $id_adstype)
      ->where('id_ads_cancel', NULL)
      ->where("start_date <= '{$finish_date}'")
      ->where("finish_date >= '{$start_date}'")
      ->where_in('payment_status', ['capture', 'settlement', 'pending'])
      ->where('order_status', 1);

    if (!empty($id_ads_order))
    {
      $this->db->where('tbl_ads_order_item.id_ads_order', $id_ads_order);
    }

    if (!empty($except_id))
    {
      $this->db->where('tbl_ads_order_item.id_ads_order_item !=', $except_id);
    }

    return $this->db->count_all_results() > 0;
  }

  public function insert_item($id_ads_order, $item_data)
  {
    $item_data['id_ads_order'] = $id_ads_order;

    $this->db->insert('tbl_ads_order_item', $item_data);
  }

  public function insert_notification($id_ads_order, $data)
  {
    $item_data['id_ads_order'] = $id_ads_order;

    $this->db->insert('tbl_ads_order_notification', $data);
  }

  public function update($data, $id_ads_order)
  {
    $this->db->where(compact('id_ads_order'))->update('tbl_ads_order', $data);
  }

  public function update_item($data, $id_ads_order_item)
  {
    $this->db->where(compact('id_ads_order_item'))->update('tbl_ads_order_item', $data);
  }

  public function delete_item($id_ads_order_item)
  {
    $this->db->delete('tbl_ads_order_item', compact('id_ads_order_item'));
  }

  private function _get_items($id_ads_order)
  {
    return $this
      ->db
      ->select('tbl_ads_order_item.id_ads_order_item, tbl_ads_order_item.id_adstype, tbl_adstype.ads_name')
      ->select('tbl_ads_order_item.start_date, tbl_ads_order_item.finish_date')
      ->select('tbl_adstype.price_per_day')
      ->select('(DATEDIFF(finish_date, start_date) + 1) as total_days')
      ->select('(DATEDIFF(finish_date, start_date) + 1) * tbl_adstype.price_per_day as subtotal')
      ->select('tbl_ads_order_item.is_booked')
      ->join('tbl_adstype', 'tbl_ads_order_item.id_adstype = tbl_adstype.id_adstype', 'left')
      ->get_where('tbl_ads_order_item', compact('id_ads_order'))
      ->result();
  }

  public function order_by_checkout_date()
  {
    $this->db->order_by('tbl_ads_order.checkout_date', 'desc');

    return $this;
  }
}
