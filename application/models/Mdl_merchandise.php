<?php
 
Class Mdl_merchandise extends CI_Model{

	//=============================== GET QUERY ===============================//
	function getMerchandiseByID($id){
		$this->db->select('tbl_merchandise.*');
		return $this->db->get_where('tbl_merchandise', array('id_merchandise' => $id, 'deleted' => 0))->result();
	}
  
  function getAllMerchandise(){
		$this->db->select('tbl_merchandise.*');
		$this->db->order_by('merch_point','asc');
		$this->db->order_by('id_merchandise','desc');
		return $this->db->get_where('tbl_merchandise', array('deleted' => 0))->result();
	}
	
	function getAllMerchandiseCount(){
		$this->db->select('tbl_merchandise.*');
		return $this->db->get_where('tbl_merchandise', array('deleted' => 0))->num_rows();
	}
	
	function getAllMerchandiseLimit($num,$offset){
		$this->db->select('tbl_merchandise.*');
		$this->db->order_by('merch_point','asc');
		$this->db->order_by('id_merchandise','desc');
		$this->db->limit($num,$offset);
		return $this->db->get_where('tbl_merchandise', array('deleted' => 0))->result();
	}
  
  function getPublishedMerchandises(){
    $this->db->select('tbl_merchandise.*');
		$this->db->order_by('merch_point','asc');
		$this->db->order_by('id_merchandise','desc');
		return $this->db->get_where('tbl_merchandise', array('merch_publish' => 1, 'deleted' => 0))->result();
  }
  
  function getRedeemRequestByIDUser($id_user){
    $this->db->select('tbl_merchandise_redeem.*');
    $this->db->select('tbl_merchandise.merch_name');
    $this->db->join('tbl_merchandise', 'tbl_merchandise_redeem.id_merchandise = tbl_merchandise.id_merchandise', 'inner');
		$this->db->order_by('tbl_merchandise_redeem.id_merchandise_redeem','desc');
		return $this->db->get_where('tbl_merchandise_redeem', array('id_user' => $id_user))->result();
  }
  
  function getActiveRedeemRequestByIDUser($id_user){
    $this->db->select('tbl_merchandise_redeem.*');
    $this->db->select('tbl_merchandise.merch_name');
    $this->db->join('tbl_merchandise', 'tbl_merchandise_redeem.id_merchandise = tbl_merchandise.id_merchandise', 'inner');
		$this->db->order_by('tbl_merchandise_redeem.id_merchandise_redeem','desc');
		return $this->db->get_where('tbl_merchandise_redeem', array('id_user' => $id_user, 'redeem_status' => 0))->result();
  }
  
	//============================== CHECK QUERY ==============================//
	
	function checkMerchandiseByID($id){
		$query = $this->db->get_where('tbl_merchandise', array('id_merchandise' => $id, 'deleted' => 0));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertMerchandise($insert_data){
		$this->db->insert('tbl_merchandise',$insert_data);
	}
  
  function insertRedeemRequest($insert_data){
    $this->db->insert('tbl_merchandise_redeem',$insert_data);
  }
	
	//============================== UPDATE QUERY =============================//
	
	function updateMerchandise($update_data, $id){
		$this->db->where('id_merchandise', $id);
		$this->db->update('tbl_merchandise',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteMerchandise($id){
		$this->db->delete('tbl_merchandise',array('id_merchandise' => $id));
	}
	
}

