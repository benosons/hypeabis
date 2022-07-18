<?php
 
Class Mdl_level extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getLevelByID($id){
		$this->db->select('tbl_level.*');
		return $this->db->get_where('tbl_level', array('id_level' => $id))->result();
	}
  
  function getAllLevel(){
		$this->db->select('tbl_level.*');
		$this->db->order_by('level_point','asc');
		return $this->db->get('tbl_level')->result();
	}
	
	function getAllLevelCount(){
		$this->db->select('tbl_level.*');
		return $this->db->get('tbl_level')->num_rows();
	}
	
	function getAllLevelLimit($num,$offset){
		$this->db->select('tbl_level.*');
		$this->db->order_by('level_point','asc');
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_level')->result();
	}
  
	//============================== CHECK QUERY ==============================//
	
	function checkLevelByID($id){
		$query = $this->db->get_where('tbl_level', array('id_level' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertLevel($insert_data){
		$this->db->insert('tbl_level',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateLevel($update_data, $id){
		$this->db->where('id_level', $id);
		$this->db->update('tbl_level',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteLevel($id){
		$this->db->delete('tbl_level',array('id_level' => $id));
	}
	
}

