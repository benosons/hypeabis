<?php
 
Class Mdl_ads extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getAdsByID($id){
		$this->db->select('tbl_ads.*');
		$this->db->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height');
		$this->db->select('tbl_user.name as user_name');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
    $this->db->join('tbl_user', 'tbl_user.id_user = tbl_ads.id_user', 'left');
		return $this->db->get_where('tbl_ads', array('tbl_ads.id_ads' => $id))->result();
	}
  
  function getLockedAdsId($id_admin){
		$content = $this
			->db
      ->select('id_ads')
      ->get_where('tbl_ads', ['edit_id_admin' => $id_admin])
			->row();

    return $content->id_ads ?? null;
  }

  function getAllAds(){
		$this->db->select('tbl_ads.*');
    $this->db->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
		$this->db->order_by('tbl_ads.id_ads','desc');
		return $this->db->get('tbl_ads')->result();
	}
  
  function getAllAdsOfUser($id_user, $limit = NULL, $offset = NULL){
		$this->db->select('tbl_ads.*');
    $this->db->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height');
    $this->db->select('tbl_ads_cancel.id_ads_cancel');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
    $this->db->join('tbl_ads_cancel', 'tbl_ads.id_ads_order_item = tbl_ads_cancel.id_ads_order_item', 'left');
		$this->db->order_by('tbl_ads.id_ads','desc');
		return $this->db->get_where('tbl_ads', compact('id_user'), $limit, $offset)->result();
	}
  
  function getAllAdsType(){
		$this->db->select('tbl_adstype.*');
		$this->db->order_by('tbl_adstype.ads_order','asc');
		return $this->db->get('tbl_adstype')->result();
	}
  
  function getAdsTypeByID($id){
		$this->db->select('tbl_adstype.*');
		$this->db->order_by('tbl_adstype.ads_order','asc');
		return $this->db->get_where('tbl_adstype', array('tbl_adstype.id_adstype' => $id))->result();
	}
  
  function getAdsTypeByTypeCode($type){
    	$this->db->select('tbl_adstype.*');
		$this->db->order_by('tbl_adstype.ads_order','asc');
		return $this->db->get_where('tbl_adstype', array('tbl_adstype.ads_type' => $type))->result();
  }
  
  function getActiveAds($now, $id_adstype, $source){
    $this->db->where(array(
      'tbl_ads.start_date <= ' => $now,
      'tbl_ads.finish_date >= ' => $now,
      'tbl_ads.ads_source' => $source
    ));
    $this->db->limit(1);
    $this->db->order_by('tbl_ads.start_date', 'desc');
    $this->db->select('tbl_ads.*, tbl_adstype.ads_width, tbl_adstype.ads_height');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
		return $this->db->get_where('tbl_ads', array('tbl_ads.id_adstype' => $id_adstype))->result_array();
  }
  
  function getGoogleAds($id_adstype, $source){
    $this->db->where(array(
      'tbl_ads.ads_source' => $source
    ));
    $this->db->limit(1);
		return $this->db->get_where('tbl_ads', array('tbl_ads.id_adstype' => $id_adstype))->result_array();
  }
	
	function getAllAdsCount(){
		$this->db->select('tbl_ads.*');
    $this->db->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
		return $this->db->get('tbl_ads')->num_rows();
	}
	
	function getRequireApprovalAdsCount(){
		$this->db->select('tbl_ads.*');
    $this->db->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
    $this->db->where_in('tbl_ads.status', [0, 2]);
		return $this->db->get('tbl_ads')->num_rows();
	}
	
  function getAllAdsCountByUser($id_user, $id_ads = NULL)
  {
    $this->db->from('tbl_ads');

    if ($id_ads)
    {
      $this->db->where(compact('id_ads'));
    }

    return $this->db->where(compact('id_user'))->count_all_results();
	}
	
	function getAllAdsLimit($id_admin, $num,$offset){
    $now = date('Y-m-d');

		$this->db->select('tbl_ads.*');
    $this->db->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height');
    $this->db->select('tbl_user.name as user_name');
    $this->db->select('tbl_edit_admin.name AS edit_admin_name, tbl_edit_admin.username AS admin_username');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
    $this->db->join('tbl_user', 'tbl_user.id_user = tbl_ads.id_user', 'left');
		$this->db->join('tbl_admin AS tbl_edit_admin','tbl_ads.edit_id_admin = tbl_edit_admin.id_admin','left');
    $this->db->order_by("COALESCE(tbl_ads.edit_id_admin = '{$id_admin}', 0)", 'desc', FALSE);
    $this->db->order_by('FIELD(tbl_ads.status, 0, 2, -2, -3, 1, -1)', 'asc', FALSE);
    $this->db->order_by("tbl_ads.start_date >= '{$now}'", 'desc', FALSE);
    $this->db->order_by("tbl_ads.start_date <= '{$now}' AND tbl_ads.finish_date >= '{$now}'", 'desc', FALSE);
		$this->db->order_by('tbl_ads.start_date','asc');
		$this->db->order_by('tbl_ads.id_ads','desc');
    $this->db->where('tbl_ads.status !=', -1);
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_ads')->result();
	}
  
  function getAdsByIDAdstypeAndSource($id_adstype, $ads_source){
    $this->db->select('tbl_ads.*');
		$this->db->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
		return $this->db->get_where('tbl_ads', array('tbl_ads.id_adstype' => $id_adstype, 'tbl_ads.ads_source' => $ads_source))->result();
  }
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_ads.*');
    $this->db->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height');
    $this->db->select('tbl_user.name as user_name');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
    $this->db->join('tbl_user', 'tbl_user.id_user = tbl_ads.id_user', 'left');
    $this->db->where('tbl_ads.status !=', -1);
    
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
		//check for ads_select..
		if($search_param['id_adstype'] != 'all'){
			$this->db->where('tbl_ads.id_adstype', $search_param['id_adstype']);
		}
		
		//cek for radio..
		if($search_param['ads_source'] == 'builtin'){
			$this->db->where('tbl_ads.ads_source', 'builtin');
		}
		else if($search_param['ads_source'] == 'googleads'){
			$this->db->where('tbl_ads.ads_source', 'googleads');
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		return $this->db->get('tbl_ads')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_ads.*');
    $this->db->select('tbl_adstype.ads_type, tbl_adstype.ads_code, tbl_adstype.ads_name, tbl_adstype.ads_width, tbl_adstype.ads_height');
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
    
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
		//check for ads_select..
		if($search_param['id_adstype'] != 'all'){
			$this->db->where('tbl_ads.id_adstype', $search_param['id_adstype']);
		}
		
		//cek for radio..
		if($search_param['ads_source'] == 'builtin'){
			$this->db->where('tbl_ads.ads_source', 'builtin');
		}
		else if($search_param['ads_source'] == 'googleads'){
			$this->db->where('tbl_ads.ads_source', 'googleads');
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_ads')->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkAdsByID($id){
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
		$query = $this->db->get_where('tbl_ads', array('tbl_ads.id_ads' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}

	function checkAdsByIDOrderItem($id){
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
		$query = $this->db->get_where('tbl_ads', array('tbl_ads.id_ads_order_item' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
  
  function checkAdsByIDAdstypeAndDate($id_adstype, $start_date, $finish_date){
    $start_date .= ' 00:00:00';
    $finish_date .= ' 23:59:59';
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
    $this->db->where(array(
      'tbl_ads.finish_date >' => $start_date,
      'tbl_ads.start_date <' => $finish_date
    ));
		$query = $this->db->get_where('tbl_ads', array('tbl_ads.id_adstype' => $id_adstype, 'tbl_ads.ads_source' => 'builtin'));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
  }
  
  function checkAdsByIDAdstypeAndDateExcludeIDAds($id_adstype, $start_date, $finish_date, $id_ads){
    $start_date .= ' 00:00:00';
    $finish_date .= ' 23:59:59';
    $this->db->join('tbl_adstype', 'tbl_adstype.id_adstype = tbl_ads.id_adstype', 'inner');
    $this->db->where(array(
      'tbl_ads.finish_date >' => $start_date,
      'tbl_ads.start_date <' => $finish_date,
      'tbl_ads.id_ads !=' => $id_ads
    ));
		$query = $this->db->get_where('tbl_ads', array('tbl_ads.id_adstype' => $id_adstype, 'tbl_ads.ads_source' => 'builtin'));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
  }
	
	//============================== INSERT QUERY =============================//
	
	function insertAds($insert_data){
		$this->db->insert('tbl_ads',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateAds($update_data, $id){
		$this->db->where('tbl_ads.id_ads', $id);
		$this->db->update('tbl_ads',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteAds($id){
		$this->db->delete('tbl_ads',array('tbl_ads.id_ads' => $id));
	}
	
}

