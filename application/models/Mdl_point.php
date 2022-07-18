<?php
 
Class Mdl_point extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getPointByID($id){
		$this->db->select('tbl_point.*');
		return $this->db->get_where('tbl_point', array('id_point' => $id, 'deleted' => 0))->result();
	}
  
  function getPointByTriggerType($trigger_type){
    $this->db->select('tbl_point.*');
		return $this->db->get_where('tbl_point', array('trigger_type' => $trigger_type, 'deleted' => 0))->result();
  }
  
  function getAllPoint(){
		$this->db->select('tbl_point.*');
		$this->db->order_by('id_point','asc');
		return $this->db->get_where('tbl_point', array('deleted' => 0))->result();
	}
	
	function getAllPointCount(){
		$this->db->select('tbl_point.*');
		return $this->db->get_where('tbl_point', array('deleted' => 0))->num_rows();
	}
	
	function getAllPointLimit($num,$offset){
		$this->db->select('tbl_point.*');
		$this->db->order_by('id_point','asc');
		$this->db->limit($num,$offset);
		return $this->db->get_where('tbl_point', array('deleted' => 0))->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_point.*');
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
		//check for point_select..
		if($search_param['point_select_withsearch'] != 'all'){
			$this->db->where('tbl_point.point_select_withsearch', $search_param['point_select_withsearch']);
		}
		
		//cek for radio..
		if($search_param['point_radio'] == '1'){
			$this->db->where('tbl_point.point_radio', 1);
		}
		else if($search_param['point_radio'] == '0'){
			$this->db->where('tbl_point.point_radio', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		return $this->db->get_where('tbl_point', array('deleted' => 0))->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_point.*');
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
		//check for point_select..
		if($search_param['point_select_withsearch'] != 'all'){
			$this->db->where('tbl_point.point_select_withsearch', $search_param['point_select_withsearch']);
		}
		
		//cek for radio..
		if($search_param['point_radio'] == '1'){
			$this->db->where('tbl_point.point_radio', 1);
		}
		else if($search_param['point_radio'] == '0'){
			$this->db->where('tbl_point.point_radio', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get_where('tbl_point', array('deleted' => 0))->num_rows();
	}
	
  function getAllPointHistoryCount($id_user){
    $this->db->select('tbl_point_history.*');
    $this->db->select('tbl_point.trigger_type, tbl_point.trigger_str');
    $this->db->select('tbl_reaction.point_desc, tbl_reaction.reaction_point');
    $this->db->join('tbl_user', 'tbl_point_history.id_user = tbl_user.id_user', 'inner');
    $this->db->join('tbl_point', 'tbl_point_history.id_point = tbl_point.id_point', 'left');
    $this->db->join('tbl_reaction', 'tbl_point_history.id_reaction = tbl_reaction.id_reaction', 'left');
    $this->db->order_by('id_point_history', 'desc');
		return $this->db->get_where('tbl_point_history', array('tbl_point_history.id_user' => $id_user))->num_rows();
  }
  
  function getAllPointHistoryLimit($id_user, $num, $offset){
    $this->db->select('tbl_point_history.*');
    $this->db->select('tbl_point.trigger_type, tbl_point.trigger_str');
    $this->db->select('tbl_reaction.point_desc, tbl_reaction.reaction_point');
    $this->db->join('tbl_user', 'tbl_point_history.id_user = tbl_user.id_user', 'inner');
    $this->db->join('tbl_point', 'tbl_point_history.id_point = tbl_point.id_point', 'left');
    $this->db->join('tbl_reaction', 'tbl_point_history.id_reaction = tbl_reaction.id_reaction', 'left');
    $this->db->limit($num, $offset);
    $this->db->order_by('id_point_history', 'desc');
		return $this->db->get_where('tbl_point_history', array('tbl_point_history.id_user' => $id_user))->result();
  }
  
	//============================== CHECK QUERY ==============================//
	
	function checkPointByID($id){
		$query = $this->db->get_where('tbl_point', array('id_point' => $id, 'deleted' => 0));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertPoint($insert_data){
		$this->db->insert('tbl_point',$insert_data);
	}
  
  function insertPointHistory($insert_data){
    $this->db->insert('tbl_point_history',$insert_data);
  }
	
	//============================== UPDATE QUERY =============================//
	
	function updatePoint($update_data, $id){
		$this->db->where('id_point', $id);
		$this->db->update('tbl_point',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deletePoint($id){
		$this->db->delete('tbl_point',array('id_point' => $id));
	}
	
}

