<?php
 
Class Mdl_interest extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getInterestByID($id){
		$this->db->select('tbl_interest.*');
		return $this->db->get_where('tbl_interest', array('id_interest' => $id, 'deleted' => 0))->result();
	}
  
  function getAllInterest(){
		$this->db->select('tbl_interest.*');
    $this->db->order_by('order', 'asc');
		$this->db->order_by('interest','asc');
		$this->db->order_by('id_interest','desc');
		return $this->db->get_where('tbl_interest', array('deleted' => 0))->result();
	}
	
	function getAllInterestCount(){
		$this->db->select('tbl_interest.*');
		return $this->db->get_where('tbl_interest', array('deleted' => 0))->num_rows();
	}
	
	function getAllInterestLimit($num,$offset){
    $this->db->select('tbl_interest.*');
    $this->db->order_by('order', 'asc');
    $this->db->order_by('interest','asc');
		$this->db->order_by('id_interest','desc');
		$this->db->limit($num,$offset);
		return $this->db->get_where('tbl_interest', array('deleted' => 0))->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_interest.*');
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
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		return $this->db->get_where('tbl_interest', array('deleted' => 0))->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_interest.*');
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
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get_where('tbl_interest', array('deleted' => 0))->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkInterestByID($id){
		$query = $this->db->get_where('tbl_interest', array('id_interest' => $id, 'deleted' => 0));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertInterest($insert_data){
		$this->db->insert('tbl_interest',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateInterest($update_data, $id){
		$this->db->where('id_interest', $id);
		$this->db->update('tbl_interest',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteInterest($id){
    // $this->db->delete('tbl_interest',array('id_interest' => $id));
    $this->db->where('id_interest', $id);
		$this->db->update('tbl_interest',array('deleted' => 1));
	}
	
}

