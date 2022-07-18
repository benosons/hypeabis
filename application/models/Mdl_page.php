<?php
 
Class Mdl_page extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getPageByID($id){
		$this->db->select('tbl_page.*');
		return $this->db->get_where('tbl_page', array('id_page' => $id))->result();
	}
  
  function getAllPage(){
		$this->db->select('tbl_page.*');
		$this->db->order_by('tbl_page.page_title','asc');
		$this->db->order_by('tbl_page.id_page','desc');
		return $this->db->get('tbl_page')->result();
	}
	
	function getAllPageCount(){
		$this->db->select('tbl_page.*');
		return $this->db->get('tbl_page')->num_rows();
	}
	
	function getAllPageLimit($num,$offset){
		$this->db->select('tbl_page.*');
		$this->db->order_by('tbl_page.page_title','asc');
		$this->db->order_by('tbl_page.id_page','desc');
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_page')->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_page.*');
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
		//cek for page_status..
		if($search_param['page_status'] == 'publish'){
			$this->db->where('tbl_page.page_status', 1);
		}
		else if($search_param['page_status'] == 'unpublish'){
			$this->db->where('tbl_page.page_status', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		return $this->db->get('tbl_page')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_page.*');
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
		//cek for page_status..
		if($search_param['page_status'] == 'publish'){
			$this->db->where('tbl_page.page_status', 1);
		}
		else if($search_param['page_status'] == 'unpublish'){
			$this->db->where('tbl_page.page_status', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_page')->num_rows();
	}
	
  //============================== GET QUERY (FRONTEND) ==============================//
  function getPublishedPageByID($id){
		$this->db->select('tbl_page.*');
		return $this->db->get_where('tbl_page', array('id_page' => $id, 'page_status' => 1))->result();
	}
	
  function getAllPublishedPageCount(){
    $this->db->select('tbl_page.*');
		return $this->db->get_where('tbl_page', array('page_status' => 1))->num_rows();
  }
  
  function getAllPublishedPageLimit($num, $offset){
    $this->db->select('tbl_page.*');
    $this->db->limit($num,$offset);
		return $this->db->get_where('tbl_page', array('page_status' => 1))->result();
  }
  
  function getPageByKeyword($keyword){
    $this->db->order_by('page_title', 'asc');
    $this->db->like('page_title', $keyword);
    return $this->db->get('tbl_page')->result();
  }
	
  //============================== CHECK QUERY ==============================//
	
	function checkPageByID($id){
		$query = $this->db->get_where('tbl_page', array('id_page' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
  function checkSubscriberByEmail($email){
    $query = $this->db->get_where('tbl_subscriber', array('email' => $email));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
  }
  
	//============================== INSERT QUERY =============================//
	
	function insertPage($insert_data){
		$this->db->insert('tbl_page',$insert_data);
	}
	
  function insertSubscriber($insert_data){
    $this->db->insert('tbl_subscriber',$insert_data);
  }
  
	//============================== UPDATE QUERY =============================//
	
	function updatePage($update_data, $id){
		$this->db->where('id_page', $id);
		$this->db->update('tbl_page',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deletePage($id){
		$this->db->delete('tbl_page',array('id_page' => $id));
	}
	
}

