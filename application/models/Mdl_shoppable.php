<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_shoppable extends Mdl_content2
{
    const TYPE = 6;

    public function all($limit = NULL, $offset = NULL)
    {
        $this
            ->db
            ->select('tbl_content_shoppable.*')
            ->join('tbl_content_shoppable', 'tbl_content.id_content=tbl_content_shoppable.id_content', 'left');

        return parent::all($limit, $offset);
    }

    public function get_max_item_order_no($id_content)
    {
        return $this
            ->db
            ->select('COALESCE(MAX(order_no) + 1, 1) AS max_order_no')
            ->where(compact('id_content'))
            ->get('tbl_content_shoppable_item')
            ->row()
            ->max_order_no;
    }

    public function get_items($id_content)
    {
        return $this
            ->db
            ->where(compact('id_content'))
            ->order_by('order_no', 'asc')
            ->get('tbl_content_shoppable_item')
            ->result();
    }

    public function find($id_content)
    {
        $this
            ->db
            ->select('tbl_content_shoppable.use_content_pic, tbl_content_shoppable.picture as shoppable_picture')
            ->join('tbl_content_shoppable', 'tbl_content.id_content=tbl_content_shoppable.id_content', 'left');

        return parent::find($id_content);
    }

    public function find_with_counts($id_content)
    {
        $this
            ->db
            ->select('tbl_content_shoppable.use_content_pic, tbl_content_shoppable.picture as shoppable_picture')
            ->join('tbl_content_shoppable', 'tbl_content.id_content=tbl_content_shoppable.id_content', 'left');

        return parent::find_with_counts($id_content);
    }

    public function find_item($id)
    {
        return $this
            ->db
            ->where(compact('id'))
            ->get('tbl_content_shoppable_item')
            ->row();
    }

    public function insert_shoppable_content($content_data, $shoppable_data, $tags)
    {
        $last_id = parent::insert_content($content_data, $tags);

        $this->db->trans_start();
        $shoppable_data['id_content'] = $last_id;
        $this->db->insert('tbl_content_shoppable', $shoppable_data);
        $this->db->trans_complete();

        return $last_id;
    }

    public function add_item($id_content, $data)
    {
        $data['id_content'] = $id_content;
        $this->db->insert('tbl_content_shoppable_item', $data);
    }

    public function update_shoppable_content($id_content, $content_data, $shoppable_data, $tags)
    {
        parent::update_content($id_content, $content_data, $tags);

        $this->db->trans_start();
        $this->db->where(compact('id_content'))->update('tbl_content_shoppable', $shoppable_data);
        $this->db->trans_complete();
    }

    public function update_item($id, $data)
    {
        $this->db->trans_start();

        $item = $this->db->select('id_content, order_no')->get_where('tbl_content_shoppable_item', compact('id'))->row();

        if (!empty($data['order_no'])) {
            if ($data['order_no'] > $item->order_no) {
                $this
                    ->db
                    ->set('order_no', 'order_no-1', FALSE)
                    ->where('id_content', $item->id_content)
                    ->where('order_no >', $item->order_no)
                    ->where('order_no <=', $data['order_no'])
                    ->update('tbl_content_shoppable_item');
            }
            elseif ($data['order_no'] < $item->order_no) {
                $this
                    ->db
                    ->set('order_no', 'order_no+1', FALSE)
                    ->where('id_content', $item->id_content)
                    ->where('order_no >=', $data['order_no'])
                    ->where('order_no <', $item->order_no)
                    ->update('tbl_content_shoppable_item');
            }
        }

        $this->db->update('tbl_content_shoppable_item', $data, compact('id'));

        $this->db->trans_complete();
    }

    public function delete($id_content)
    {
        $this->db->trans_start();
        $this->db->delete('tbl_content_shoppable', compact('id_content'));
        $this->db->delete('tbl_content_shoppable_item', compact('id_content'));
        parent::delete($id_content);

        $this->db->trans_complete();
    }

    public function delete_item($id)
    {
        $this->db->delete('tbl_content_shoppable_item', compact('id'));
    }

    public function remove_shoppable_picture($id_content)
    {
        $this->db->where(compact('id_content'))->update('tbl_content_shoppable', [
            'use_content_pic' => 1,
            'picture' => NULL,
        ]);
    }
}
