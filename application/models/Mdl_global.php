<?php
 
Class Mdl_global extends CI_Model{

	//=============================== GET QUERY ===============================//

	function getGlobalData(){
		return $this->db->get_where('tbl_global',array('id_global' => '1'))->result();
	}

	function getHomeLayoutType()
	{
		return $this->db->select('home_layout_type')->get_where('tbl_global',array('id_global' => '1'))->row()->home_layout_type ?: NULL;
	}

	function getVerifiedMemberPoint()
	{
		$global = $this->db->select('verified_member_point')->get_where('tbl_global', ['id_global' => '1'])->row();
		return $global ? $global->verified_member_point : 0;
	}
	
	//============================== CHECK QUERY ==============================//

	//============================== INSERT QUERY =============================//
	
	function insertGlobalData($insertData){
		$this->db->insert('tbl_global',$insertData);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateGlobalData($updateData){
		$this->db->where('id_global',1);
		$this->db->update('tbl_global',$updateData);
	}
	
	//============================== DELETE QUERY =============================//
	

}

