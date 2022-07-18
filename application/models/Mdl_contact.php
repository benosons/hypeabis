<?php

Class Mdl_contact extends CI_Model{

    //=============================== GET QUERY ===============================//

    function getContactByID($id){
        $this->db->select('tbl_contact.*');
        $this->db->select('tbl_contactsetting.contact_title, tbl_contactsetting.contact_desc, tbl_contactsetting.contact_email');
        $this->db->join('tbl_contactsetting', 'tbl_contact.id_contactsetting = tbl_contactsetting.id_contactsetting', 'inner');
        return $this->db->get_where('tbl_contact', array('tbl_contact.id_contact' => $id))->result();
    }

    function getContactByHash($hash){
        $this->db->select('tbl_contact.*');
        $this->db->select('tbl_contactsetting.contact_title, tbl_contactsetting.contact_desc, tbl_contactsetting.contact_email');
        $this->db->join('tbl_contactsetting', 'tbl_contact.id_contactsetting = tbl_contactsetting.id_contactsetting', 'inner');
        return $this->db->get_where('tbl_contact', array('tbl_contact.hash' => $hash))->result();
    }

    function getAllContact(){
        $this->db->select('tbl_contact.*');
        $this->db->select('tbl_contactsetting.contact_title, tbl_contactsetting.contact_desc, tbl_contactsetting.contact_email');
        $this->db->join('tbl_contactsetting', 'tbl_contact.id_contactsetting = tbl_contactsetting.id_contactsetting', 'inner');
        $this->db->order_by('tbl_contact.id_contact','desc');
        return $this->db->get('tbl_contact')->result();
    }

    function getAllContactCount(){
        $this->db->select('tbl_contact.*');
        $this->db->select('tbl_contactsetting.contact_title, tbl_contactsetting.contact_desc, tbl_contactsetting.contact_email');
        $this->db->join('tbl_contactsetting', 'tbl_contact.id_contactsetting = tbl_contactsetting.id_contactsetting', 'inner');
        return $this->db->get('tbl_contact')->num_rows();
    }

    function getUnreadContactCount(){
        $this->db->select('tbl_contact.*');
        $this->db->select('tbl_contactsetting.contact_title, tbl_contactsetting.contact_desc, tbl_contactsetting.contact_email');
        $this->db->join('tbl_contactsetting', 'tbl_contact.id_contactsetting = tbl_contactsetting.id_contactsetting', 'inner');
        return $this->db->get_where('tbl_contact', ['read_status' => 0])->num_rows();
    }

    function getAllContactLimit($num,$offset){
        $this->db->select('tbl_contact.*');
        $this->db->select('tbl_contactsetting.contact_title, tbl_contactsetting.contact_desc, tbl_contactsetting.contact_email');
        $this->db->join('tbl_contactsetting', 'tbl_contact.id_contactsetting = tbl_contactsetting.id_contactsetting', 'inner');

        $this->db->order_by('tbl_contact.id_contact','desc');
        $this->db->limit($num,$offset);
        return $this->db->get('tbl_contact')->result();
    }

    function getSearchResult($search_param, $num, $offset){
        $this->db->select('tbl_contact.*');
        $this->db->select('tbl_contactsetting.contact_title, tbl_contactsetting.contact_desc, tbl_contactsetting.contact_email');
        $this->db->join('tbl_contactsetting', 'tbl_contact.id_contactsetting = tbl_contactsetting.id_contactsetting', 'inner');

        if($search_param['keyword'] != null && $search_param['keyword'] != ''){
            if($search_param['operator'] == 'like'){
                $this->db->like($search_param['search_by'], $search_param['keyword']);
            }
            else if($search_param['operator'] == 'not like'){
                $this->db->not_like($search_param['search_by'], $search_param['keyword']);
            }
            else{
                $this->db->where($search_param['search_by'].' '.$search_param['operator'], $search_param['keyword']);
            }
        }

        //============================== ADDITIONAL SEARCH PARAMETER ===================================//
        //cek for content_status..
        if($search_param['read_status'] == '1'){
            $this->db->where('tbl_contact.read_status', 1);
        }
        else if($search_param['read_status'] == '0'){
            $this->db->where('tbl_contact.read_status', 0);
        }
        else{}

            //cek for start date..
            $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
        $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
        if($search_param['start_date'] != null && $search_param['start_date'] != ''){
            $this->db->where('tbl_contact.submit_date >=', $start_date_formatted);
        }
        //cek for finish date
        if($search_param['finish_date'] != null && $search_param['finish_date'] != ''){
            $this->db->where('tbl_contact.submit_date <=', $finish_date_formatted);
        }
        //===============================================================================================//

        $this->db->order_by($search_param['sort_by']);
        $this->db->limit($num, $offset);
        return $this->db->get('tbl_contact')->result();
    }

    function getSearchResultCount($search_param){
        $this->db->select('tbl_contact.*');
        $this->db->select('tbl_contactsetting.contact_title, tbl_contactsetting.contact_desc, tbl_contactsetting.contact_email');
        $this->db->join('tbl_contactsetting', 'tbl_contact.id_contactsetting = tbl_contactsetting.id_contactsetting', 'inner');

        if($search_param['keyword'] != null && $search_param['keyword'] != ''){
            if($search_param['operator'] == 'like'){
                $this->db->like($search_param['search_by'], $search_param['keyword']);
            }
            else if($search_param['operator'] == 'not like'){
                $this->db->not_like($search_param['search_by'], $search_param['keyword']);
            }
            else{
                $this->db->where($search_param['search_by'].' '.$search_param['operator'], $search_param['keyword']);
            }
        }

        //============================== ADDITIONAL SEARCH PARAMETER ===================================//
        //cek for content_status..
        if($search_param['read_status'] == '1'){
            $this->db->where('tbl_contact.read_status', 1);
        }
        else if($search_param['read_status'] == '0'){
            $this->db->where('tbl_contact.read_status', 0);
        }
        else{}

            //cek for start date..
            $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
        $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
        if($search_param['start_date'] != null && $search_param['start_date'] != ''){
            $this->db->where('tbl_contact.submit_date >=', $start_date_formatted);
        }
        //cek for finish date
        if($search_param['finish_date'] != null && $search_param['finish_date'] != ''){
            $this->db->where('tbl_contact.submit_date <=', $finish_date_formatted);
        }
        //===============================================================================================//

        $this->db->order_by($search_param['sort_by']);
        return $this->db->get('tbl_contact')->num_rows();
    }

    //============================== CHECK QUERY ==============================//

    function checkContactByID($id){
        $query = $this->db->get_where('tbl_contact', array('tbl_contact.id_contact' => $id));
        if($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    //============================== INSERT QUERY =============================//

    function insertContact($insert_data){
        $this->db->insert('tbl_contact',$insert_data);
    }

    //============================== UPDATE QUERY =============================//

    function updateContact($update_data, $id){
        $this->db->where('tbl_contact.id_contact', $id);
        $this->db->update('tbl_contact',$update_data);
    }

    //============================== DELETE QUERY =============================//

    function deleteContact($id){
        $this->db->delete('tbl_contact',array('tbl_contact.id_contact' => $id));
    }

}

