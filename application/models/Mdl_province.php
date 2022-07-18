<?php
 
Class Mdl_province extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getProvinceByID($id){
		$this->db->select('tbl_province.*');
		return $this->db->get_where('tbl_province', array('id_province' => $id))->result();
	}
  
  function getAllProvince(){
		$this->db->select('tbl_province.*');
		$this->db->order_by('province_name','asc');
		return $this->db->get('tbl_province')->result();
	}
  
  function getAllProvinceArray(){
    $this->db->select('tbl_province.*');
		$this->db->order_by('province_name','asc');
		return $this->db->get('tbl_province')->result_array();
  }
	
	function getAllProvinceCount(){
		$this->db->select('tbl_province.*');
		return $this->db->get('tbl_province')->num_rows();
	}
	
	function getAllProvinceLimit($num,$offset){
		$this->db->select('tbl_province.*');
		$this->db->order_by('province_name','asc');
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_province')->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_province.*');
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
		return $this->db->get('tbl_province')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_province.*');
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
		return $this->db->get('tbl_province')->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkProvinceByID($id){
		$query = $this->db->get_where('tbl_province', array('id_province' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertProvince($insert_data){
		$this->db->insert('tbl_province',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateProvince($update_data, $id){
		$this->db->where('id_province', $id);
		$this->db->update('tbl_province',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteProvince($id){
		$this->db->delete('tbl_province',array('id_province' => $id));
	}
	
}

