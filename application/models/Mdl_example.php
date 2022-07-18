<?php
 
Class Mdl_example extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getExampleByID($id){
		$this->db->select('tbl_example.*');
		return $this->db->get_where('tbl_example', array('id_example' => $id))->result();
	}
  
  function getAllExample(){
		$this->db->select('tbl_example.*');
		$this->db->order_by('id_example','desc');
		return $this->db->get('tbl_example')->result();
	}
	
	function getAllExampleCount(){
		$this->db->select('tbl_example.*');
		return $this->db->get('tbl_example')->num_rows();
	}
	
	function getAllExampleLimit($num,$offset){
		$this->db->select('tbl_example.*');
		$this->db->order_by('id_example','desc');
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_example')->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_example.*');
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
		//check for example_select..
		if($search_param['example_select_withsearch'] != 'all'){
			$this->db->where('tbl_example.example_select_withsearch', $search_param['example_select_withsearch']);
		}
		
		//cek for radio..
		if($search_param['example_radio'] == '1'){
			$this->db->where('tbl_example.example_radio', 1);
		}
		else if($search_param['example_radio'] == '0'){
			$this->db->where('tbl_example.example_radio', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		return $this->db->get('tbl_example')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_example.*');
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
		//check for example_select..
		if($search_param['example_select_withsearch'] != 'all'){
			$this->db->where('tbl_example.example_select_withsearch', $search_param['example_select_withsearch']);
		}
		
		//cek for radio..
		if($search_param['example_radio'] == '1'){
			$this->db->where('tbl_example.example_radio', 1);
		}
		else if($search_param['example_radio'] == '0'){
			$this->db->where('tbl_example.example_radio', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_example')->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkExampleByID($id){
		$query = $this->db->get_where('tbl_example', array('id_example' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertExample($insert_data){
		$this->db->insert('tbl_example',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateExample($update_data, $id){
		$this->db->where('id_example', $id);
		$this->db->update('tbl_example',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteExample($id){
		$this->db->delete('tbl_example',array('id_example' => $id));
	}
	
}

