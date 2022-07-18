<?php
 
Class Mdl_subscriber extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getSubscriberByID($id){
		$this->db->select('tbl_subscriber.*');
		return $this->db->get_where('tbl_subscriber', array('id_subscriber' => $id))->result();
	}
  
  function getAllSubscriber(){
		$this->db->select('tbl_subscriber.*');
		$this->db->order_by('id_subscriber','desc');
		return $this->db->get('tbl_subscriber')->result();
	}
  
	function getAllSubscriberCount(){
		$this->db->select('tbl_subscriber.*');
		return $this->db->get('tbl_subscriber')->num_rows();
	}
	
	function getAllSubscriberLimit($num,$offset){
		$this->db->select('tbl_subscriber.*');
		$this->db->order_by('id_subscriber','desc');
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_subscriber')->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_subscriber.*');
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
		//cek for start date..
    $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
    $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
		if($search_param['start_date'] != null && $search_param['start_date'] != ''){
			$this->db->where('tbl_subscriber.subscribe_date >=', $start_date_formatted);
		}
		//cek for finish date
		if($search_param['finish_date'] != null && $search_param['finish_date'] != ''){
			$this->db->where('tbl_subscriber.subscribe_date <=', $finish_date_formatted);
		}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		return $this->db->get('tbl_subscriber')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_subscriber.*');
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
		//cek for start date..
    $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
    $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
		if($search_param['start_date'] != null && $search_param['start_date'] != ''){
			$this->db->where('tbl_subscriber.subscribe_date >=', $start_date_formatted);
		}
		//cek for finish date
		if($search_param['finish_date'] != null && $search_param['finish_date'] != ''){
			$this->db->where('tbl_subscriber.subscribe_date <=', $finish_date_formatted);
		}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_subscriber')->num_rows();
	}
	
  function getSearchResultForExport($search_param){
		$this->db->select('tbl_subscriber.*');
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
		//cek for start date..
    $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
    $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
		if($search_param['start_date'] != null && $search_param['start_date'] != ''){
			$this->db->where('tbl_subscriber.subscribe_date >=', $start_date_formatted);
		}
		//cek for finish date
		if($search_param['finish_date'] != null && $search_param['finish_date'] != ''){
			$this->db->where('tbl_subscriber.subscribe_date <=', $finish_date_formatted);
		}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_subscriber')->result();
	}
  
	//============================== CHECK QUERY ==============================//
	
	function checkSubscriberByID($id){
		$query = $this->db->get_where('tbl_subscriber', array('id_subscriber' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertSubscriber($insert_data){
		$this->db->insert('tbl_subscriber',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	//============================== DELETE QUERY =============================//
	
	function deleteSubscriber($id){
		$this->db->delete('tbl_subscriber',array('id_subscriber' => $id));
	}
	
}