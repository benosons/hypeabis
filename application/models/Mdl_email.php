<?php
 
Class Mdl_email extends CI_Model{

  //============================== INSERT QUERY =============================//
	
	function insertEmail($insert_data){
		$this->db->insert('tbl_email',$insert_data);
	}
	
}

