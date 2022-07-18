<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_article extends Mdl_content2 {
  const TYPE = 1;

    public function with_competition_like($competition)
    {
        $this->db->select("(SELECT COUNT(id_content_like) FROM tbl_content_like WHERE tbl_content_like.id_content = tbl_content.id_content AND DATE(tbl_content_like.created_at) >= '{$competition->start_date}' AND DATE(tbl_content_like.created_at) <= '{$competition->finish_date}') AS competition_like_count");

        return $this;
    }

    public function join_competition($id_competition)
    {
        $this->db->where(compact('id_competition'));
        return $this;
    }
}
