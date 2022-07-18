<?php

Class Mdl_subdistrict extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getAllSubdistrict(){
		$this->db->order_by('tbl_province.id_province','asc');
		$this->db->order_by('tbl_city.city_name','asc');
		$this->db->order_by('tbl_subdistrict.subdistrict_name','asc');
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get('tbl_subdistrict')->result();
	}
	
	function getAllSubdistrictCount(){
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get('tbl_subdistrict')->num_rows();
	}
	
	function getAllSubdistrictLimit($num,$offset){
		$this->db->order_by('tbl_province.id_province','asc');
		$this->db->order_by('tbl_city.city_name','asc');
		$this->db->order_by('tbl_subdistrict.subdistrict_name','asc');
		$this->db->limit($num,$offset);
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get('tbl_subdistrict')->result();
	}
	
	function getSubdistrictByID($id){
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where("tbl_subdistrict",array('id_subdistrict' => $id))->result();
	}
	
	function getSubdistrictByIDInArray($id){
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where("tbl_subdistrict",array('id_subdistrict' => $id))->result_array();
	}
	
	function getSubdistrictByIDCity($id){
		$this->db->order_by('subdistrict_name','asc');
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where("tbl_subdistrict",array('tbl_subdistrict.id_city' => $id))->result();
	}
	
	function getSubdistrictByIDCityInArray($id){
		$this->db->order_by('subdistrict_name','asc');
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where("tbl_subdistrict",array('tbl_subdistrict.id_city' => $id))->result_array();
	}
	
	function getSubdistrictByIDRajaongkirAndSubdistrictCode($id, $code){
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where("tbl_subdistrict",array('id_subdistrict_rajaongkir' => $id, 'subdistrict_code' => $code))->result();
	}
	
	function getSubdistrictByIDRajaongkirOrSubdistrictCode($id, $code){
		$this->db->where(array('tbl_subdistrict.id_subdistrict_rajaongkir' => $id));
		$this->db->or_where(array('tbl_subdistrict.subdistrict_code' => $code));
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get("tbl_subdistrict")->result();
	}
	
	function getSearchResult($search_param, $num, $offset){
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		
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
		//cek for province..
		if($search_param['province'] != 'all' && $search_param['province'] >= 0){
			$this->db->where('tbl_province.id_province', $search_param['province']);
		}
		
		//cek for city..
		if($search_param['city'] != 'all' && $search_param['city'] >= 0){
			$this->db->where('tbl_subdistrict.id_city', $search_param['city']);
		}
		//===============================================================================================//
		
		if($search_param['sort_by'] == 'default'){
			$this->db->order_by('tbl_province.id_province','asc');
			$this->db->order_by('tbl_city.city_name','asc');
		}
		else{
			$this->db->order_by($search_param['sort_by']);
		}
		
		$this->db->limit($num, $offset);
		return $this->db->get('tbl_subdistrict')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->join('tbl_city', 'tbl_city.id_city = tbl_subdistrict.id_city', 'inner');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		
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
		//cek for province..
		if($search_param['province'] != 'all' && $search_param['province'] >= 0){
			$this->db->where('tbl_province.id_province', $search_param['province']);
		}
		
		//cek for city..
		if($search_param['city'] != 'all' && $search_param['city'] >= 0){
			$this->db->where('tbl_subdistrict.id_city', $search_param['city']);
		}
		//===============================================================================================//
		return $this->db->get('tbl_subdistrict')->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkSubdistrictByID($id){
		$query = $this->db->get_where('tbl_subdistrict',array('id_subdistrict'=>$id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertSubdistrict($insert_data){
		$this->db->insert('tbl_subdistrict',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateSubdistrict($update_data, $id){
		$this->db->where('id_subdistrict',$id);
		$this->db->update('tbl_subdistrict',$update_data);
	}
	
	//============================== DELETE QUERY =============================//
	
	function deleteSubdistrict($id){
		$this->db->delete('tbl_subdistrict',array('id_subdistrict' => $id));
	}

}