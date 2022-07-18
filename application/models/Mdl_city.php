<?php
 
Class Mdl_city extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getCityByID($id){
		$this->db->order_by('tbl_province.province_name','asc');
		$this->db->order_by('tbl_city.city_name','asc');
		$this->db->order_by('tbl_city.id_city','desc');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where('tbl_city', array('id_city' => $id))->result();
	}
  
  function getAllCity(){
		$this->db->order_by('tbl_province.province_name','asc');
		$this->db->order_by('tbl_city.city_name','asc');
		$this->db->order_by('tbl_city.id_city','desc');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get('tbl_city')->result();
	}
	
	function getAllCityCount(){
		$this->db->order_by('tbl_province.province_name','asc');
		$this->db->order_by('tbl_city.city_name','asc');
		$this->db->order_by('tbl_city.id_city','desc');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get('tbl_city')->num_rows();
	}
	
	function getAllCityLimit($num,$offset){
		$this->db->order_by('tbl_province.province_name','asc');
		$this->db->order_by('tbl_city.city_name','asc');
		$this->db->order_by('tbl_city.id_city','desc');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_city')->result();
	}
  
	function getCityByIDInArray($id){
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where("tbl_city",array('id_city' => $id))->result_array();
	}
	
	function getCityByIDRajaongkir($id){
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where("tbl_city",array('id_city_rajaongkir' => $id))->result();
	}
	
	function getCityByIDProvince($id){
		$this->db->order_by('tbl_city.city_name','asc');
		return $this->db->get_where("tbl_city",array('id_province' => $id))->result();
	}
	
	function getCityByIDProvinceInArray($id){
		$this->db->order_by('tbl_city.city_name','asc');
		return $this->db->get_where("tbl_city",array('id_province' => $id))->result_array();
	}
	
	function getCityByIDProvinceRajaongkir($id_province_rajaongkir){
		$this->db->order_by('tbl_city.city_name','asc');
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where("tbl_city",array('tbl_province.id_province_rajaongkir' => $id_province_rajaongkir))->result();
	}
	
	function getCityByIDRajaongkirAndCityCode($id_rajaongkir, $city_code){
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get_where("tbl_city", array('id_city_rajaongkir' => $id_rajaongkir, 'city_code' => $city_code))->result();
	}
	
	function getCityByIDRajaongkirOrCityCode($id_rajaongkir, $city_code){
		$this->db->where(array('id_city_rajaongkir' => $id_rajaongkir));
		$this->db->or_where(array('city_code' => $city_code));
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get("tbl_city")->result();
	}
	
	function getSearchResult($search_param, $num, $offset){
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
			$this->db->where('tbl_city.id_province', $search_param['province']);
		}
		
		//cek for city type..
		if($search_param['city_type'] == 'Kabupaten' || $search_param['city_type'] == 'Kota'){
			$this->db->where('tbl_city.city_type', $search_param['city_type']);
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
    $this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get('tbl_city')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_city.*');
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
			$this->db->where('tbl_city.id_province', $search_param['province']);
		}
		
		//cek for city type..
		if($search_param['city_type'] == 'Kabupaten' || $search_param['city_type'] == 'Kota'){
			$this->db->where('tbl_city.city_type', $search_param['city_type']);
		}
		//===============================================================================================//
		
		$this->db->join('tbl_province', 'tbl_province.id_province = tbl_city.id_province', 'inner');
		return $this->db->get('tbl_city')->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkCityByID($id){
		$query = $this->db->get_where('tbl_city',array('id_city'=>$id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function checkCityByIDCityAndIDProvince($id_city, $id_province){
		$query = $this->db->get_where('tbl_city',array('id_city'=>$id_city, 'id_province'=>$id_province));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertCity($insert_data){
		$this->db->insert('tbl_city',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateCity($update_data, $id){
		$this->db->where('id_city', $id);
		$this->db->update('tbl_city',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteCity($id){
		$this->db->delete('tbl_city',array('id_city' => $id));
	}
	
}

