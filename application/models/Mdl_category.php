<?php

class Mdl_category extends CI_Model
{

    //=============================== GET QUERY ===============================//

    function getAllCategoryCount()
    {
        return $this->db->count_all('tbl_category');
    }

    function getAllCategory()
    {
        $this->db->order_by('id_category', 'asc');
        return $this->db->get('tbl_category')->result();
    }

    function getAllCategoryParent()
    {
        $this->db->order_by('order', 'asc');
        $this->db->order_by('category_name', 'asc');
        return $this->db->get_where('tbl_category', array('category_parent' => 0))->result();
    }

    function getAllCategoryParentArr()
    {
        $this->db->order_by('order', 'asc');
        $this->db->order_by('category_name', 'asc');
        return $this->db->get_where('tbl_category', array('category_parent' => 0))->result_array();
    }

    function getAllCategoryLimit($num, $offset)
    {
        $this->db->limit($num, $offset);
        $this->db->order_by('category_parent', 'asc');
        $this->db->order_by('order', 'asc');
        $this->db->order_by('id_category', 'desc');
        return $this->db->get('tbl_category')->result();
    }

    function getAllProductivityCategoryParentArr($search_param = [])
    {
        $this->db->select('tbl_category.id_category, tbl_category.category_name');
        $this->db->select('COUNT(tbl_content.id_content) AS article_count');
        $this->db->join('tbl_content', 'tbl_category.id_category = tbl_content.id_category AND content_status = 1 AND type = 1', 'left');
        $this->db->group_by('tbl_category.id_category');
        $this->db->group_by('tbl_category.category_name');
        $this->db->order_by('order', 'asc');
        $this->db->order_by('category_name', 'asc');

        if (!empty($search_param['author'])) {
            $this->db->where('tbl_content.id_user', $search_param['author']);
        }

        if (!empty($search_param['admin'])) {
            $this->db->join('tbl_content_editor', 'tbl_content.id_content=tbl_content_editor.id_content', 'left');
            $this->db->where('tbl_content_editor.id_admin', $search_param['admin']);
        }

        if (!empty($search_param['start_date'])) {
            $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
            $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
        }

        if (!empty($search_param['finish_date'])) {
            $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
            $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
        }

        return $this->db->get_where('tbl_category', ['category_parent' => 0])->result_array();
    }

    function getProductivityCategoryChildArr($id_category, $search_param = [])
    {
        $this->db->select('tbl_category.id_category, category_parent, tbl_category.category_name');
        $this->db->select('COUNT(tbl_content.id_content) AS article_count');
        $this->db->join('tbl_content', 'tbl_category.id_category = tbl_content.id_category AND content_status = 1 AND type = 1', 'left');
        $this->db->group_by('tbl_category.id_category');
        $this->db->group_by('tbl_category.category_name');
        $this->db->order_by('order', 'asc');
        $this->db->order_by('category_name', 'asc');

        if (!empty($search_param['author'])) {
            $this->db->where('tbl_content.id_user', $search_param['author']);
        }

        if (!empty($search_param['admin'])) {
            $this->db->join('tbl_content_editor', 'tbl_content.id_content=tbl_content_editor.id_content', 'left');
            $this->db->where('tbl_content_editor.id_admin', $search_param['admin']);
        }

        if (!empty($search_param['start_date'])) {
            $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
            $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
        }

        if (!empty($search_param['finish_date'])) {
            $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
            $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
        }

        return $this->db->get_where('tbl_category', ['category_parent' => $id_category])->result_array();
    }

    function getCategoryByID($id)
    {
        $this->db->select('a.*, b.category_name AS parent_name');
        $this->db->join('tbl_category b', 'a.category_parent = b.id_category', 'left');
        return $this->db->get_where("tbl_category a", array('a.id_category' => $id))->result();
    }

    function getCategoryChild($id)
    {
        $this->db->order_by('order', 'asc');
        $this->db->order_by('category_name', 'asc');
        return $this->db->get_where("tbl_category", array('category_parent' => $id))->result();
    }

    function getCategoryChildArr($id)
    {
        $this->db->order_by('order', 'asc');
        $this->db->order_by('category_name', 'asc');
        return $this->db->get_where("tbl_category", array('category_parent' => $id))->result_array();
    }

    function getSearchResult($search_param)
    {
        if ($search_param['keyword'] != null && $search_param['keyword'] != '') {
            if ($search_param['operator'] == 'like') {
                $this->db->like($search_param['search_by'], $search_param['keyword']);
            }
            else {
                if ($search_param['operator'] == 'not like') {
                    $this->db->not_like($search_param['search_by'], $search_param['keyword']);
                }
                else {
                    $this->db->where($search_param['search_by'] . ' ' . $search_param['operator'], $search_param['keyword']);
                }
            }
        }
        //============================== ADDITIONAL SEARCH PARAMETER ===================================//
        //===============================================================================================//
        $this->db->order_by('category_parent', 'asc');
        $this->db->order_by('order', 'asc');
        $this->db->order_by('id_category', 'desc');
        return $this->db->get('tbl_category')->result();
    }

    function getSearchResultArr($search_param)
    {
        if ($search_param['keyword'] != null && $search_param['keyword'] != '') {
            if ($search_param['operator'] == 'like') {
                $this->db->like($search_param['search_by'], $search_param['keyword']);
            }
            else {
                if ($search_param['operator'] == 'not like') {
                    $this->db->not_like($search_param['search_by'], $search_param['keyword']);
                }
                else {
                    $this->db->where($search_param['search_by'] . ' ' . $search_param['operator'], $search_param['keyword']);
                }
            }
        }
        //============================== ADDITIONAL SEARCH PARAMETER ===================================//
        //===============================================================================================//
        $this->db->order_by('category_parent', 'asc');
        $this->db->order_by('order', 'asc');
        $this->db->order_by('id_category', 'desc');
        return $this->db->get('tbl_category')->result_array();
    }

    function getSearchResultCount($search_param)
    {
        if ($search_param['keyword'] != null && $search_param['keyword'] != '') {
            if ($search_param['operator'] == 'like') {
                $this->db->like($search_param['search_by'], $search_param['keyword']);
            }
            else {
                if ($search_param['operator'] == 'not like') {
                    $this->db->not_like($search_param['search_by'], $search_param['keyword']);
                }
                else {
                    $this->db->where($search_param['search_by'] . ' ' . $search_param['operator'], $search_param['keyword']);
                }
            }
        }
        //============================== ADDITIONAL SEARCH PARAMETER ===================================//
        //===============================================================================================//
        $this->db->order_by('category_parent', 'asc');
        $this->db->order_by('order', 'asc');
        $this->db->order_by('id_category', 'desc');
        return $this->db->get('tbl_category')->num_rows();
    }

    //============================== CHECK QUERY ==============================//

    function checkCategoryByID($id)
    {
        $query = $this->db->get_where('tbl_category', array('id_category' => $id));
        if ($query->num_rows() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    function hasChild($id)
    {
        $query = $this->db->get_where('tbl_category', array('category_parent' => $id));
        if ($query->num_rows() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    //============================== INSERT QUERY =============================//

    function insertCategory($insert_data)
    {
        $this->db->insert('tbl_category', $insert_data);
    }

    //============================== UPDATE QUERY =============================//

    function updateCategory($update_data, $id)
    {
        $this->db->where('id_category', $id);
        $this->db->update('tbl_category', $update_data);
    }

    //============================== DELETE QUERY =============================//

    function deleteCategory($id)
    {
        $this->db->delete('tbl_category', array('id_category' => $id));
    }

    function deleteChildCategory($id)
    {
        $this->db->delete('tbl_category', array('category_parent' => $id));
    }

}

