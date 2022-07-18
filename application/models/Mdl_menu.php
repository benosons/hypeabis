<?php
 
Class Mdl_menu extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getAllMenuCount(){
		return $this->db->count_all('tbl_menu');
	}
	
	function getAllMenu(){
		$this->db->order_by('id_menu','asc');
		return $this->db->get('tbl_menu')->result();
	}
	
	function getAllMenuParent(){
		$this->db->order_by('menu_order','asc');
		$this->db->order_by('menu_name','asc');
		return $this->db->get_where('tbl_menu',array('menu_parent' => 0))->result();
	}
  
  function getAllMenuParentArr(){
    $this->db->order_by('menu_order','asc');
		$this->db->order_by('menu_name','asc');
		return $this->db->get_where('tbl_menu',array('menu_parent' => 0))->result_array();
  }
	
	function getAllMenuLimit($num,$offset){
		$this->db->limit($num,$offset);
		$this->db->order_by('menu_parent','asc');
		$this->db->order_by('menu_order','asc');
		$this->db->order_by('id_menu','desc');
		return $this->db->get('tbl_menu')->result();
	}
	
	function getMenuByID($id){
		$this->db->select('a.*, b.menu_name AS parent_name');
		$this->db->join('tbl_menu b', 'a.menu_parent = b.id_menu', 'left');
		return $this->db->get_where("tbl_menu a",array('a.id_menu' => $id))->result();
	}
  
	function getMenuChild($id){
		$this->db->order_by('menu_order','asc');
		$this->db->order_by('menu_name','asc');
		return $this->db->get_where("tbl_menu",array('menu_parent' => $id))->result();
	}
  
  function getMenuChildArr($id){
		$this->db->order_by('menu_order','asc');
    $this->db->order_by('menu_name','asc');
		return $this->db->get_where("tbl_menu",array('menu_parent' => $id))->result_array();
	}
	
	function getSearchResult($search_param){
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
		$this->db->order_by('menu_parent','asc');
		$this->db->order_by('menu_order','asc');
		$this->db->order_by('id_menu','desc');
		return $this->db->get('tbl_menu')->result();
	}
  
  function getSearchResultArr($search_param){
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
		$this->db->order_by('menu_parent','asc');
		$this->db->order_by('menu_order','asc');
		$this->db->order_by('id_menu','desc');
		return $this->db->get('tbl_menu')->result_array();
  }
	
	function getSearchResultCount($search_param){
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
    $this->db->order_by('menu_parent','asc');
		$this->db->order_by('menu_order','asc');
		$this->db->order_by('id_menu','desc');
		return $this->db->get('tbl_menu')->num_rows();
	}
	
  //======================= FRONTEND =======================//
  function getAllActiveMenuParentArr(){
    $this->db->order_by('menu_order','asc');
		$this->db->order_by('menu_name','asc');
		return $this->db->get_where('tbl_menu',array('menu_parent' => 0, 'menu_status' => 1))->result_array();
  }
  
  function getActiveMenuChildArr($id){
    $this->db->order_by('menu_order','asc');
    $this->db->order_by('menu_name','asc');
		return $this->db->get_where("tbl_menu",array('menu_parent' => $id, 'menu_status' => 1))->result_array();
  }
  
	//============================== CHECK QUERY ==============================//
	
	function checkMenuByID($id){
		$query = $this->db->get_where('tbl_menu',array('id_menu'=>$id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function hasChild($id){
		$query = $this->db->get_where('tbl_menu',array('menu_parent'=>$id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
  
	//============================== INSERT QUERY =============================//
	
	function insertMenu($insert_data){
		$this->db->insert('tbl_menu',$insert_data);
	}
  
	//============================== UPDATE QUERY =============================//
	
	function updateMenu($update_data, $id){
		$this->db->where('id_menu',$id);
		$this->db->update('tbl_menu',$update_data);
	}
	
	//============================== DELETE QUERY =============================//
	
	function deleteMenu($id){
		$this->db->delete('tbl_menu',array('id_menu' => $id));
	}
	
	function deleteChildMenu($id){
		$this->db->delete('tbl_menu',array('menu_parent' => $id));
	}

}

