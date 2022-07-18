<?php
 
Class Mdl_jobfield extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getJobfieldByID($id){
		$this->db->select('tbl_jobfield.*');
		return $this->db->get_where('tbl_jobfield', array('id_jobfield' => $id, 'deleted' => 0))->result();
	}
  
  function getAllJobfield(){
		$this->db->select('tbl_jobfield.*');
    $this->db->order_by('order', 'asc');
		$this->db->order_by('job_field','asc');
		$this->db->order_by('id_jobfield','desc');
		return $this->db->get_where('tbl_jobfield', array('deleted' => 0))->result();
	}
	
	function getAllJobfieldCount(){
    $this->db->select('tbl_jobfield.*');
		return $this->db->get_where('tbl_jobfield', array('deleted' => 0))->num_rows();
	}
	
	function getAllJobfieldLimit($num,$offset){
    $this->db->select('tbl_jobfield.*');
    $this->db->order_by('order', 'asc');
    $this->db->order_by('job_field','asc');
		$this->db->order_by('id_jobfield','desc');
		$this->db->limit($num,$offset);
		return $this->db->get_where('tbl_jobfield', array('deleted' => 0))->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_jobfield.*');
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
		return $this->db->get_where('tbl_jobfield', array('deleted' => 0))->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_jobfield.*');
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
		return $this->db->get_where('tbl_jobfield', array('deleted' => 0))->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkJobfieldByID($id){
		$query = $this->db->get_where('tbl_jobfield', array('id_jobfield' => $id, 'deleted' => 0));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertJobfield($insert_data){
		$this->db->insert('tbl_jobfield',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateJobfield($update_data, $id){
		$this->db->where('id_jobfield', $id);
		$this->db->update('tbl_jobfield',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteJobfield($id){
    // $this->db->delete('tbl_jobfield',array('id_jobfield' => $id));
    $this->db->where('id_jobfield', $id);
		$this->db->update('tbl_jobfield',array('deleted' => 1));
	}
	
}

