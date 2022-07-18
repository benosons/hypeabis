<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property-read CI_DB $db
 */
class Mdl_competition extends CI_Model
{
    public function all($limit = NULL, $offset = NULL)
    {
        $this->excludeDeleted();
        $this->db->order_by('tbl_competition.finish_date', 'DESC');
        return $this->db->get('tbl_competition', $limit, $offset)->result();
    }

    public function allCompetitionType()
    {
        $this->excludeDeleted();
        $this->db->select('competition_type');
        $this->db->distinct();
        return $this->db->get('tbl_competition')->result();
    }

    public function allWithCategoryAndEntryCount($limit = NULL, $offset = NULL)
    {
        $this->excludeDeleted();
        $this->db->order_by('tbl_competition.finish_date', 'DESC');
        $this->db->select('tbl_competition.*');
        $this->db->select(
            '('
            . 'SELECT COUNT(*) FROM tbl_content WHERE tbl_competition.id_competition = tbl_content.id_competition'
            . ' AND content_status = 1'
            . ') AS contents_count'
        );
        $competitions = $this->db->get('tbl_competition', $limit, $offset)->result();

        foreach ($competitions as $competition) {
            $competition->categories = $this->getCategory($competition->id_competition);
        }

        return $competitions;
    }

    public function getByType($type, $limit = NULL, $offset = NULL)
    {
        $this->excludeDeleted();
        $this->db->order_by('tbl_competition.finish_date', 'DESC');
        $this->db->where(['tbl_competition.competition_type' => $type]);
        return $this->db->get('tbl_competition', $limit, $offset)->result();
    }

    public function getByTypeWithCategory($type, $limit = NULL, $offset = NULL)
    {
        $this->excludeDeleted();
        $this->db->order_by('tbl_competition.finish_date', 'DESC');
        $this->db->where(['tbl_competition.competition_type' => $type]);
        $competitions = $this->db->get('tbl_competition', $limit, $offset)->result();

        foreach ($competitions as $competition) {
            $competition->categories = $this->getCategory($competition->id_competition);
        }

        return $competitions;
    }

    public function getById($id_competition)
    {
        return $this->db->get_where('tbl_competition', ['id_competition' => $id_competition, 'deleted' => 0])->row();
    }

    public function getActiveCompetitionCount(): int
    {
        $this->active();
        $this->excludeDeleted();
        return $this->db->get_where('tbl_competition')->num_rows();
    }

    public function get_active($competition_type = '')
    {
        $this->active();
        $this->excludeDeleted();
        return $this->db->get_where('tbl_competition', compact('competition_type'))->result();
    }

    public function get_active_id()
    {
        $this->active();
        $this->excludeDeleted();
        $competition = $this->db->select('tbl_competition.id_competition')->get('tbl_competition')->row();

        return $competition ? $competition->id_competition : NULL;
    }

    public function find($id_competition)
    {
        $this->excludeDeleted();
        return $this->db->get_where('tbl_competition', compact('id_competition'))->row();
    }

    public function count(): int
    {
        $this->excludeDeleted();
        return $this->db->from('tbl_competition')->count_all_results();
    }

    public function has_been_used($start_date, $finish_date, $except_id = NULL): bool
    {
        $this->excludeDeleted();
        $this->db->where("start_date <= '{$finish_date}'")->where("finish_date >= '{$start_date}'");

        if (!empty($except_id)) {
            $this->db->where('id_competition !=', $except_id);
        }

        return $this->count() > 0;
    }

    public function getCategory($id_competition){
        return $this->db->get_where('tbl_competition_category', ['id_competition' => $id_competition, 'deleted' => 0])->result();
    }

    public function findCategory($id_competition_category)
    {
        return $this->db->get_where('tbl_competition_category', ['id_competition_category' => $id_competition_category, 'deleted' => 0])->row();
    }

    public function insert($data)
    {
        return ($this->db->insert('tbl_competition', $data) ? $this->db->insert_id() : false);
    }

    public function insertCategory($data)
    {
        $this->db->insert('tbl_competition_category', $data);
    }

    public function insertCategoryBatch($data)
    {
        $this->db->insert_batch('tbl_competition_category', $data);
    }

    public function update($id_competition, $data)
    {
        $this->db->where(compact('id_competition'))->update('tbl_competition', $data);
    }

    public function updateCompetitionCategory($update_data, $id)
    {
        $this->db->where(['id_competition_category' => $id])->update('tbl_competition_category', $update_data);
    }

    public function delete($id_competition)
    {
        //$this->db->delete('tbl_competition', compact('id_competition'));
        $this->db->where(compact('id_competition'))->update('tbl_competition', ['deleted' => 1]);
    }

    public function deleteCompetitionCategory($id_competition_category)
    {
        $this->db->where(['id_competition_category' => $id_competition_category])->update('tbl_competition_category', ['deleted' => 1]);
    }

    public function active(): Mdl_competition
    {
        $now = date('Y-m-d');
        $this->db->where("start_date <= '{$now}'")->where("finish_date >= '{$now}'");
        return $this;
    }

    public function hasType($type): Mdl_competition
    {
        if ($type) {
            $this->db->where(['tbl_competition.competition_type' => $type]);
        }

        return $this;
    }

    public function hasCategory($category): Mdl_competition
    {
        if ($category) {
            $this->db->where(
                'exists ('
                . 'select * from tbl_competition_category where '
                . 'tbl_competition.id_competition = tbl_competition_category.id_competition'
                . ' AND tbl_competition_category.category_name = \'' . $this->db->escape_str($category) . '\''
                . ' AND tbl_competition_category.deleted = 0'
                . ')'
            );
        }

        return $this;
    }

    public function inactive(): Mdl_competition
    {
        $now = date('Y-m-d');
        $this->db->not_group_start()->where("start_date <= '{$now}'")->where("finish_date >= '{$now}'")->group_end();
        return $this;
    }

    public function excludeDeleted(): Mdl_competition
    {
        $this->db->where("deleted", 0);
        return $this;
    }

    public function has_participant($id_user = NULL): Mdl_competition
    {
        $user_sql = $id_user ? "AND id_user='{$id_user}'" : '';
        $this->db->where("exists (select * from tbl_content where tbl_competition.id_competition=tbl_content.id_competition {$user_sql})");
        return $this;
    }
}
