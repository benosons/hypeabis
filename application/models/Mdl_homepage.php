<?php
 
Class Mdl_homepage extends CI_Model{

	//=============================== GET QUERY ===============================//
	
  function getFeaturedAuthorByIDUser($id){
    return $this->db->get_where('tbl_author_homepage', array('id_user' => $id))->result();
  }
  
	//============================== CHECK QUERY ==============================//
	
  function checkFeaturedAuthorByIDUser($id){
		$query = $this->db->get_where('tbl_author_homepage', array('id_user' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
  
	//============================== INSERT QUERY =============================//
	
  function insertFeaturedAuthor($insert_data){
    $this->db->insert('tbl_author_homepage', $insert_data);
  }
  
	//============================== UPDATE QUERY =============================//
	
  function updateFeaturedAuthor($update_data, $id){
    $this->db->where('id_author_homepage', $id);
		$this->db->update('tbl_author_homepage',$update_data);
  }
  
	//============================== DELETE QUERY =============================//
	
	function deleteHomepageFeaturedAuthor($id){
		$this->db->delete('tbl_author_homepage',array('id_user' => $id));
	}
	
}

