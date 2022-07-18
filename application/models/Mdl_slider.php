<?php
 
Class Mdl_slider extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getSliderByID($id){
		$this->db->select('tbl_slider.*');
		return $this->db->get_where('tbl_slider', array('id_slider' => $id))->result();
	}
  
  function getAllSlider(){
		$this->db->select('tbl_slider.*');
		$this->db->order_by('id_slider','desc');
		return $this->db->get('tbl_slider')->result();
	}
	
	function getAllSliderCount(){
		$this->db->select('tbl_slider.*');
		return $this->db->get('tbl_slider')->num_rows();
	}
	
	function getAllSliderLimit($num,$offset){
		$this->db->select('tbl_slider.*');
		$this->db->order_by('id_slider','desc');
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_slider')->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_slider.*');
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
		return $this->db->get('tbl_slider')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_slider.*');
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
		return $this->db->get('tbl_slider')->num_rows();
	}
	
  function getSliderContentByIDSlider($id_slider){
    $this->db->select('tbl_slider_content.*, tbl_slider.slider_name');
		$this->db->join('tbl_slider', 'tbl_slider.id_slider = tbl_slider_content.id_slider', 'inner');
		$this->db->order_by('tbl_slider_content.slider_order', 'asc');
		return $this->db->get_where('tbl_slider_content', array('tbl_slider_content.id_slider' => $id_slider))->result();
  }
  
  function getSliderContentByID($id_slider_content, $id_slider){
    $this->db->select('tbl_slider_content.*, tbl_slider.slider_name');
		$this->db->join('tbl_slider', 'tbl_slider.id_slider = tbl_slider_content.id_slider', 'inner');
		return $this->db->get_where('tbl_slider_content', array('tbl_slider_content.id_slider' => $id_slider, 'tbl_slider_content.id_slider_content' => $id_slider_content))->result();
  }
  
	//============================== CHECK QUERY ==============================//
	
	function checkSliderByID($id){
		$query = $this->db->get_where('tbl_slider', array('id_slider' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertSlider($insert_data){
		$this->db->insert('tbl_slider',$insert_data);
	}
	
  function insertSliderContent($insert_data){
    $this->db->insert('tbl_slider_content',$insert_data);
  }
  
	//============================== UPDATE QUERY =============================//
	
	function updateSlider($update_data, $id){
		$this->db->where('id_slider', $id);
		$this->db->update('tbl_slider',$update_data);
	}
  
  function updateSliderContent($update_data, $id){
    $this->db->where('id_slider_content', $id);
		$this->db->update('tbl_slider_content',$update_data);
  }
  
	//============================== DELETE QUERY =============================//
	
	function deleteSlider($id){
		$this->db->delete('tbl_slider',array('id_slider' => $id));
	}
	
  function deleteSliderContent($id){
		$this->db->delete('tbl_slider_content',array('id_slider_content' => $id));
	}
  
  function deleteSliderContentByIDSlider($id){
		$this->db->delete('tbl_slider_content',array('id_slider' => $id));
	}
}

