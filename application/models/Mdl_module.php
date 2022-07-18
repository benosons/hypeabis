<?php
 
Class Mdl_module extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getAllModuleCount(){
		return $this->db->count_all('tbl_module');
	}
	
	function getAllModule(){
		$this->db->order_by('id_module','asc');
		return $this->db->get('tbl_module')->result();
	}
	
	function getAllModuleParent(){
		$this->db->order_by('module_order','asc');
		$this->db->order_by('module_name','asc');
		return $this->db->get_where('tbl_module',array('module_parent' => 0))->result();
	}
  
  function getAllModuleParentArr(){
    $this->db->order_by('module_order','asc');
		$this->db->order_by('module_name','asc');
		return $this->db->get_where('tbl_module',array('module_parent' => 0))->result_array();
  }
  
  function getAllActiveModuleParentArr(){
    $this->db->order_by('module_order','asc');
		$this->db->order_by('module_name','asc');
		return $this->db->get_where('tbl_module',array('module_parent' => 0, 'module_status' => 1))->result_array();
  }
	
	function getAllModuleLimit($num,$offset){
		$this->db->limit($num,$offset);
		$this->db->order_by('module_parent','asc');
		$this->db->order_by('module_order','asc');
		$this->db->order_by('id_module','desc');
		return $this->db->get('tbl_module')->result();
	}
	
	function getModuleByID($id){
		$this->db->select('a.*, b.module_name AS parent_name');
		$this->db->join('tbl_module b', 'a.module_parent = b.id_module', 'left');
		return $this->db->get_where("tbl_module a",array('a.id_module' => $id))->result();
	}
  
  function getModuleByControllerName($controller){
    $this->db->select('a.*, b.module_name AS parent_name');
		$this->db->join('tbl_module b', 'a.module_parent = b.id_module', 'left');
		return $this->db->get_where("tbl_module a",array('a.module_redirect' => $controller))->result();
  }
	
	function getModuleChild($id){
		$this->db->order_by('module_order','asc');
		$this->db->order_by('module_name','asc');
		return $this->db->get_where("tbl_module",array('module_parent' => $id))->result();
	}
  
  function getModuleChildArr($id){
		$this->db->order_by('module_order','asc');
    $this->db->order_by('module_name','asc');
		return $this->db->get_where("tbl_module",array('module_parent' => $id))->result_array();
	}
  
  function getActiveModuleChildArr($id){
		$this->db->order_by('module_order','asc');
    $this->db->order_by('module_name','asc');
		return $this->db->get_where("tbl_module",array('module_parent' => $id, 'module_status' => 1))->result_array();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkModuleByID($id){
		$query = $this->db->get_where('tbl_module',array('id_module'=>$id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function hasChild($id){
		$query = $this->db->get_where('tbl_module',array('module_parent'=>$id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
  
	//============================== INSERT QUERY =============================//
	
	function insertModule($insert_data){
		$this->db->insert('tbl_module',$insert_data);
	}
  
	//============================== UPDATE QUERY =============================//
	
	function updateModule($id,$update_data){
		$this->db->where('id_module',$id);
		$this->db->update('tbl_module',$update_data);
	}
	
	//============================== DELETE QUERY =============================//
	
	function deleteModule($id){
		$this->db->delete('tbl_module',array('id_module' => $id));
	}
	
	function deleteChildModule($id){
		$this->db->delete('tbl_module',array('module_parent' => $id));
	}

}

