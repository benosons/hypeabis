<?php
 
Class Mdl_contactsetting extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getContactsettingByID($id){
		$this->db->select('tbl_contactsetting.*');
		return $this->db->get_where('tbl_contactsetting', array('id_contactsetting' => $id))->result();
	}
  
  function getContactsetting(){
		$this->db->select('tbl_contactsetting.*');
		$this->db->order_by('id_contactsetting','asc');
		return $this->db->get('tbl_contactsetting')->result();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkContactsettingByID($id){
		$query = $this->db->get_where('tbl_contactsetting', array('id_contactsetting' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertContactsetting($insert_data){
		$this->db->insert('tbl_contactsetting',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateContactsetting($update_data, $id){
		$this->db->where('id_contactsetting', $id);
		$this->db->update('tbl_contactsetting',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteContactsetting($id){
		$this->db->delete('tbl_contactsetting',array('id_contactsetting' => $id));
	}
	
}

