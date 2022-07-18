<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__ . '/Mdl_content2.php');

class Mdl_sponsoredcontent extends Mdl_content2 {
	const TYPE = 2;

	public function all($limit = NULL, $offset = NULL)
	{
		$this
			->db
			->select('tbl_content_sponsored.*, tbl_content_sponsored_position.name as position_name')
			->join('tbl_content_sponsored', 'tbl_content.id_content=tbl_content_sponsored.id_content')
			->join('tbl_content_sponsored_position', 'tbl_content_sponsored.id_position=tbl_content_sponsored_position.id');

		return parent::all($limit, $offset);
	}

	public function all_positions()
	{
		return $this->db->get('tbl_content_sponsored_position')->result();
	}

	public function find($id_content)
	{
		$this
			->db
			->select('tbl_content_sponsored.*')
			->join('tbl_content_sponsored', 'tbl_content.id_content=tbl_content_sponsored.id_content');

		return parent::find($id_content);
	}

	public function find_published($id_content)
	{
		$this->_where_active();

		$this->db->select('tbl_content_sponsored.*');

		return parent::find($id_content);
	}

	public function find_published_with_counts($id_content)
	{
		$this->_where_active();
		return parent::find_published_with_counts($id_content);
	}

	public function find_active_by_position($id_position)
	{
		$this->_where_type();
		$this->_where_active();

		return $this
			->db
			->where(compact('id_position'))
			->get('tbl_content')
			->row();
	}

	public function count_published($id_content = NULL)
	{
		$this->_where_active();

		return parent::count_published($id_content);
	}

	public function has_published_content($id_position, $start_date, $finish_date, $except_id = NULL)
	{
		$this
			->db
			->from('tbl_content')
			->join('tbl_content_sponsored', 'tbl_content.id_content=tbl_content_sponsored.id_content')
			->where('content_status', 1)
			->where('type', 2)
			->where('id_position', $id_position)
			->group_start()
			->where("'{$start_date}' BETWEEN start_date AND finish_date")
			->or_where("'{$finish_date}' BETWEEN start_date AND finish_date")
			->or_where("start_date BETWEEN '{$start_date}' AND '{$finish_date}'")
			->or_where("finish_date BETWEEN '{$start_date}' AND '{$finish_date}'")
			->group_end();

		if (!empty($except_id))
		{
			$this->db->where('tbl_content.id_content !=', $except_id);
		}

		return $this->db->count_all_results() > 0;
	}

	public function insert_sponsored_content($content_data, $sponsored_data, $tags)
	{
		$last_id = parent::insert_content($content_data, $tags);

		$this->db->trans_start();
		$sponsored_data['id_content'] = $last_id;
		$this->db->insert('tbl_content_sponsored', $sponsored_data);
		$this->db->trans_complete();

		return $last_id;
	}

	public function increase_click($id_content)
	{
		$this->db->set('click_count', 'click_count+1', FALSE)->where(compact('id_content'))->update('tbl_content_sponsored');
	}

	public function increase_active_view()
	{
		$today = date('Y-m-d');

		$this->db->set('view_count', 'view_count+1', FALSE)
			->where('start_date <=', $today)
			->where('finish_date >=', $today)
			->where('id_content IN (SELECT id_content FROM tbl_content WHERE content_status=1 AND type=2)')
			->update('tbl_content_sponsored');
	}

	public function update_sponsored_content($id_content, $content_data, $sponsored_data, $tags)
	{
		parent::update_content($id_content, $content_data, $tags);

		$this->db->trans_start();
		$this->db->where(compact('id_content'))->update('tbl_content_sponsored', $sponsored_data);
		$this->db->trans_complete();
	}

	public function delete($id_content)
	{
		parent::delete($id_content);

		$this->db->trans_start();
		$this->db->delete('tbl_content_sponsored', compact('id_content'));
		$this->db->trans_complete();
	}

	protected function _where_active()
	{
		$today = date('Y-m-d');

		$this
			->db
			->join('tbl_content_sponsored', 'tbl_content.id_content=tbl_content_sponsored.id_content')
			->where('content_status', 1)
			->where('start_date <=', $today)
			->where('finish_date >=', $today);
	}
}
