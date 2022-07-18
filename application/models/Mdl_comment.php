<?php
 
Class Mdl_comment extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getCommentByID($id){
		$this->db->select('tbl_content_comment.*');
		$this->db->select('tbl_content.title, tbl_content.submit_date,  tbl_content.comment_count');
    $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
    $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'inner');
		return $this->db->get_where('tbl_content_comment', array('id_content_comment' => $id))->result();
	}
  
  function getAllComment(){
		$this->db->select('tbl_content_comment.*');
		$this->db->select('tbl_content.title, tbl_content.submit_date');
    $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
    $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'inner');
		$this->db->order_by('comment_status','asc');
		$this->db->order_by('id_content_comment','desc');
		return $this->db->get('tbl_content_comment')->result();
	}
	
	function getAllCommentCount(){
		$this->db->select('tbl_content_comment.*');
		$this->db->select('tbl_content.title, tbl_content.submit_date');
    $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
    $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'inner');
		$this->db->order_by('comment_status','asc');
		$this->db->order_by('id_content_comment','desc');
		return $this->db->get('tbl_content_comment')->num_rows();
	}

	function getRequireApprovalCommentCount(){
		$this->db->select('tbl_content_comment.*');
		$this->db->select('tbl_content.title, tbl_content.submit_date');
    $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
    $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'inner');
		$this->db->where('comment_status','0');
		return $this->db->get('tbl_content_comment')->num_rows();
	}
	
	function getAllCommentLimit($num,$offset){
		$this->db->select('tbl_content_comment.*');
		$this->db->select('tbl_content.title, tbl_content.submit_date');
    $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
    $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'inner');
		$this->db->order_by('comment_status','asc');
		$this->db->order_by('id_content_comment','desc');
		$this->db->limit($num,$offset);
		return $this->db->get('tbl_content_comment')->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_content_comment.*');
		$this->db->select('tbl_content.title, tbl_content.submit_date');
    $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
    $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'inner');
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
		//cek for radio..
		if($search_param['comment_status'] == '1'){
			$this->db->where('tbl_content_comment.comment_status', 1);
		}
		else if($search_param['comment_status'] == '0'){
			$this->db->where('tbl_content_comment.comment_status', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		return $this->db->get('tbl_content_comment')->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_content_comment.*');
		$this->db->select('tbl_content.title, tbl_content.submit_date');
    $this->db->select('tbl_user.name, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->join('tbl_content', 'tbl_content.id_content = tbl_content_comment.id_content', 'inner');
    $this->db->join('tbl_user', 'tbl_content_comment.id_user = tbl_user.id_user', 'inner');
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
		//cek for radio..
		if($search_param['comment_status'] == '1'){
			$this->db->where('tbl_content_comment.comment_status', 1);
		}
		else if($search_param['comment_status'] == '0'){
			$this->db->where('tbl_content_comment.comment_status', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_content_comment')->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function checkCommentByID($id){
		$query = $this->db->get_where('tbl_content_comment', array('id_comment' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertComment($insert_data){
		$this->db->insert('tbl_content_comment',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateComment($update_data, $id){
		$this->db->where('id_content_comment', $id);
		$this->db->update('tbl_content_comment',$update_data);
	}
  
	//============================== DELETE QUERY =============================//
	
	function deleteComment($id){
		$this->db->delete('tbl_content_comment',array('id_content_comment' => $id));
	}
	
}

