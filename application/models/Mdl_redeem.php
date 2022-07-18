<?php
 
Class Mdl_redeem extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getRedeemByID($id){
		$this->db->select('tbl_merchandise_redeem.*');
    $this->db->select('tbl_merchandise.merch_name, tbl_merchandise.merch_quota');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.contact_number');
    $this->db->join('tbl_merchandise', 'tbl_merchandise_redeem.id_merchandise = tbl_merchandise.id_merchandise', 'inner');
    $this->db->join('tbl_user', 'tbl_merchandise_redeem.id_user = tbl_user.id_user', 'inner');
    $this->db->order_by('tbl_merchandise_redeem.redeem_status', 'ASC');
		return $this->db->get_where('tbl_merchandise_redeem', array('id_merchandise_redeem' => $id))->result();
	}
  
  function getAllRedeem(){
    $this->db->select('tbl_merchandise_redeem.*');
    $this->db->select('tbl_merchandise.merch_name, tbl_merchandise.merch_quota');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.contact_number');
    $this->db->join('tbl_merchandise', 'tbl_merchandise_redeem.id_merchandise = tbl_merchandise.id_merchandise', 'inner');
    $this->db->join('tbl_user', 'tbl_merchandise_redeem.id_user = tbl_user.id_user', 'inner');
    $this->db->order_by('tbl_merchandise_redeem.redeem_status', 'ASC');
		$this->db->order_by('tbl_merchandise_redeem.id_merchandise_redeem','desc');
		return $this->db->get('tbl_merchandise_redeem')->result();
	}
	
	function getAllRedeemCount(){
		$this->db->select('tbl_merchandise_redeem.*');
    $this->db->select('tbl_merchandise.merch_name, tbl_merchandise.merch_quota');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.contact_number');
    $this->db->join('tbl_merchandise', 'tbl_merchandise_redeem.id_merchandise = tbl_merchandise.id_merchandise', 'inner');
    $this->db->join('tbl_user', 'tbl_merchandise_redeem.id_user = tbl_user.id_user', 'inner');
		return $this->db->get('tbl_merchandise_redeem')->num_rows();
	}
	
	function getAllRedeemLimit($num,$offset){
		$this->db->select('tbl_merchandise_redeem.*');
    $this->db->select('tbl_merchandise.merch_name, tbl_merchandise.merch_quota');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.contact_number');
    $this->db->join('tbl_merchandise', 'tbl_merchandise_redeem.id_merchandise = tbl_merchandise.id_merchandise', 'inner');
    $this->db->join('tbl_user', 'tbl_merchandise_redeem.id_user = tbl_user.id_user', 'inner');
    $this->db->order_by('tbl_merchandise_redeem.redeem_status', 'ASC');
		$this->db->order_by('tbl_merchandise_redeem.id_merchandise_redeem','desc');
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_merchandise_redeem')->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_merchandise_redeem.*');
    $this->db->select('tbl_merchandise.merch_name, tbl_merchandise.merch_quota');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.contact_number');
    $this->db->join('tbl_merchandise', 'tbl_merchandise_redeem.id_merchandise = tbl_merchandise.id_merchandise', 'inner');
    $this->db->join('tbl_user', 'tbl_merchandise_redeem.id_user = tbl_user.id_user', 'inner');
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
		//check for redeem_select..
		if($search_param['redeem_status'] != 'all'){
			$this->db->where('tbl_merchandise_redeem.redeem_status', $search_param['redeem_status']);
		}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		return $this->db->get('tbl_merchandise_redeem')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_merchandise_redeem.*');
    $this->db->select('tbl_merchandise.merch_name, tbl_merchandise.merch_quota');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.contact_number');
    $this->db->join('tbl_merchandise', 'tbl_merchandise_redeem.id_merchandise = tbl_merchandise.id_merchandise', 'inner');
    $this->db->join('tbl_user', 'tbl_merchandise_redeem.id_user = tbl_user.id_user', 'inner');
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
		//check for redeem_select..
		if($search_param['redeem_status'] != 'all'){
			$this->db->where('tbl_merchandise_redeem.redeem_status', $search_param['redeem_status']);
		}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_merchandise_redeem')->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkRedeemByID($id){
    $this->db->select('tbl_merchandise_redeem.*');
    $this->db->select('tbl_merchandise.merch_name, tbl_merchandise.merch_quota');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.contact_number');
    $this->db->join('tbl_merchandise', 'tbl_merchandise_redeem.id_merchandise = tbl_merchandise.id_merchandise', 'inner');
    $this->db->join('tbl_user', 'tbl_merchandise_redeem.id_user = tbl_user.id_user', 'inner');
		$query = $this->db->get_where('tbl_merchandise_redeem', array('id_merchandise_redeem' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertRedeem($insert_data){
		$this->db->insert('tbl_merchandise_redeem',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateRedeem($update_data, $id){
		$this->db->where('id_merchandise_redeem', $id);
		$this->db->update('tbl_merchandise_redeem',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteRedeem($id){
		$this->db->delete('tbl_merchandise_redeem',array('id_merchandise_redeem' => $id));
	}
	
}

