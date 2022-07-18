<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_photo extends Mdl_content2
{
    const TYPE = 7;

    public function all($limit = NULL, $offset = NULL)
    {
        $photo_count_sql = "SELECT COUNT(id) FROM `tbl_content_photo`WHERE `tbl_content`.`id_content`=`id_content`";

        $this->db->select("({$photo_count_sql}) AS photo_counts");

        return parent::all($limit, $offset);
    }

    public function all_published($limit = NULL, $offset = NULL)
    {
        $contents = parent::all_published($limit, $offset);

        foreach ($contents as $content) {
            $content->photos = $this
                ->db
                ->select('tbl_content_photo.*')
                ->get_where('tbl_content_photo', ['id_content' => $content->id_content])
                ->result();
        }

        return $contents;
    }

    public function all_published_for_homepage($limit = NULL, $offset = NULL)
    {
        $contents = parent::all_published_for_homepage($limit, $offset);

        foreach ($contents as $content) {
            $content->photos = $this
                ->db
                ->select('tbl_content_photo.*')
                ->get_where('tbl_content_photo', ['id_content' => $content->id_content])
                ->result();
        }

        return $contents;
    }

    public function find_photo($id)
    {
        return $this
            ->db
            ->where(compact('id'))
            ->get('tbl_content_photo')
            ->row();
    }

    public function get_pinned()
    {
        $this->db->where('pin_on_homepage', 1);

        $contents = parent::all_published_for_homepage(1);

        if (empty($contents)) {
            return NULL;
        }

        $contents[0]->photos = $this
            ->db
            ->select('tbl_content_photo.*')
            ->get_where('tbl_content_photo', ['id_content' => $contents[0]->id_content])
            ->result();

        return $contents[0];
    }

    public function get_photos($id_content)
    {
        return $this
            ->db
            ->where(compact('id_content'))
            ->order_by('id', 'asc')
            ->get('tbl_content_photo')
            ->result();
    }

    public function count_all_photos($id_content)
    {
        return $this
            ->db
            ->where(compact('id_content'))
            ->from('tbl_content_photo')
            ->count_all_results();
    }

    public function count_submit($id_competition, $id_user, $except_id = NULL)
    {
        $this
            ->db
            ->where(compact('id_competition'))
            ->where(compact('id_user'))
            ->from('tbl_content');

        if ($except_id) {
            $this->db->where('id_content !=', $except_id);
        }

        return $this->db->count_all_results();
    }

    public function add_photo($id_content_poll, $data)
    {
        $data['id_content'] = $id_content_poll;
        $this->db->insert('tbl_content_photo', $data);
    }

    public function update_photo($id, $data)
    {
        $this->db->update('tbl_content_photo', $data, compact('id'));
    }

    public function delete_photo($id)
    {
        $this->db->delete('tbl_content_photo', compact('id'));
    }

    public function with_competition()
    {
        $this
            ->db
            ->select('tbl_competition.title as competition_title')
            ->select('tbl_competition.start_date as competition_start_date')
            ->select('tbl_competition.finish_date as competition_finish_date')
            ->join('tbl_competition', 'tbl_content.id_competition=tbl_competition.id_competition', 'left', TRUE);

        return $this;
    }
}
