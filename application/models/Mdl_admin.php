<?php
 
Class Mdl_admin extends CI_Model{

	//=============================== GET QUERY ===============================//

	function getNameByID($id){
		$this->db->select('tbl_admin.name');
		$admin = $this->db->get_where('tbl_admin', ['id_admin' => $id])->row();
		return $admin ? $admin->name : null;
	}
	
	function getSuperAdminData(){
		return $this->db->get_where('tbl_admin', array('level' => 1))->result();
	}
	
	function getAdminByUsernameAndPassword($username, $password){
		return $this->db->get_where('tbl_admin', array('username' => $username, 'password' => $password))->result();
	}
	
	function getAdminByID($id){
		return $this->db->get_where('tbl_admin', array('id_admin' => $id))->result();
	}
  
  function getAdminByHash($hash){
    return $this->db->get_where('tbl_admin', array('hash' => $hash))->result();
  }
  
  function getAdminByEmail($email){
    return $this->db->get_where('tbl_admin', array('email' => $email))->result();
  }

  function getAdminByKeywordForSelect2($keyword){
    $this->db->select('id_admin as id, name as text');
    $this->db->like('tbl_admin.name', $keyword);
    $this->db->or_like('tbl_admin.email', $keyword);
    return $this->db->get_where('tbl_admin', ['status' => 1])->result();
  }

	function getAllAdmin(){
		$this->db->order_by('id_admin','desc');
		return $this->db->get('tbl_admin')->result();
	}
	
	function getAllAdminCount(){
		$this->db->where('level >=', $this->session->userdata('admin_level'));
		$this->db->where('id_admin !=', $this->session->userdata('id_admin'));
		return $this->db->get('tbl_admin')->num_rows();
	}
	
	function getAllAdminLimit($num,$offset){
		$this->db->order_by('id_admin','desc');
		$this->db->limit($num,$offset);
		$this->db->where('level >=', $this->session->userdata('admin_level'));
		$this->db->where('id_admin !=', $this->session->userdata('id_admin'));
		return $this->db->get('tbl_admin')->result();
	}

  function getAllProductiveAdminLimit($num, $search_param = [])
  {
    $this->db->select('tbl_admin.id_admin, tbl_admin.name');
    $this->db->select('COUNT(CASE WHEN type=1 THEN 1 END) AS article_count');
    $this->db->select('COUNT(CASE WHEN type=7 THEN 1 END) AS photo_count');
    $this->db->join('tbl_content_editor', 'tbl_admin.id_admin = tbl_content_editor.id_admin', 'left');
    $this->db->join('tbl_content', 'tbl_content_editor.id_content = tbl_content.id_content AND content_status = 1 AND type IN (1, 7)', 'left');
    $this->db->group_by('tbl_admin.id_admin');
    $this->db->group_by('tbl_admin.name');
    $this->db->order_by('article_count','desc');
    $this->db->order_by('photo_count','desc');
    $this->db->limit($num);

    if (!empty($search_param['author']))
    {
      $this->db->where('tbl_content.id_user', $search_param['author']);
    }

		if (!empty($search_param['admin']))
		{
			$this->db->where('tbl_content_editor.id_admin', $search_param['admin']);
		}

    if (!empty($search_param['start_date']))
    {
      $start_date_formatted = $this->global_lib->formatDate($search_param['start_date'], 'd-m-Y', 'Y-m-d H:i:s');
      $this->db->where('tbl_content.publish_date >=', $start_date_formatted);
    }

    if (!empty($search_param['finish_date']))
    {
      $finish_date_formatted = $this->global_lib->formatDate($search_param['finish_date'], 'd-m-Y', 'Y-m-d H:i:s');
      $this->db->where('tbl_content.publish_date <=', $finish_date_formatted);
    }

    return $this->db->get('tbl_admin')->result();
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
		//cek for status..
		if($search_param['status'] == 'active'){
			$this->db->where('status', 1);
		}
		else if($search_param['status'] == 'banned'){
			$this->db->where('status', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->where('level >=', $this->session->userdata('admin_level'));
		$this->db->where('id_admin !=', $this->session->userdata('id_admin'));
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		
		return $this->db->get('tbl_admin')->result();
	}
	
	function getSearchResultCount($search_param){
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
		//cek for status..
		if($search_param['status'] == 'active'){
			$this->db->where('status', 1);
		}
		else if($search_param['status'] == 'banned'){
			$this->db->where('status', 0);
		}
		else{}
		//===============================================================================================//
		
		$this->db->where('level >=', $this->session->userdata('admin_level'));
		$this->db->where('id_admin !=', $this->session->userdata('id_admin'));
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_admin')->num_rows();
	}
	
	//============================== CHECK QUERY ==============================//
	
	function validateLogin(){
		$username = $this->input->post('username');
		$password = sha1($this->input->post('password') . $this->config->item('binary_salt'));
	
		$query = $this->db->get_where('tbl_admin',array('username' => $username, 'password' => $password));
		if($query->num_rows() > 0){
			$query2 = $this->db->get_where('tbl_admin',array('username' => $username, 'password' => $password, "status" => 1));
			if($query2->num_rows() > 0){
				return 1; // success
			}
			else{
				return 2; // banned
			}
		}
		else{
			return 3; // wrong login
		}
	}
	
	function checkAdminByID($id){
		$query = $this->db->get_where('tbl_admin', array('id_admin' => $id));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
  
  function checkAdminByHash($hash){
    $query = $this->db->get_where('tbl_admin', array('hash' => $hash));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
  }
	
	function checkAdminByEmail($email){
		$query = $this->db->get_where('tbl_admin', array('email' => $email));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function checkAdminByUsername($username){
		$query = $this->db->get_where('tbl_admin', array('username' => $username));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function checkSuperAdminPassword($password){
		$query = $this->db->get_where('tbl_admin', array('level' => 1, 'password' => $password));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function checkAdminByIDAndPassword($id, $password){
		$query = $this->db->get_where('tbl_admin', array('id_admin' => $id, 'password' => $password));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	//============================== INSERT QUERY =============================//
	
	function insertAdmin($insert_data){
		$this->db->insert('tbl_admin',$insert_data);
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateAdmin($update_data, $id){
		$this->db->where('id_admin', $id);
		$this->db->update('tbl_admin',$update_data);
	}
	
	function updateSuperAdmin($update_data){
		$this->db->where('level', 1);
		$this->db->update('tbl_admin',$update_data);
	}
	
	//============================== DELETE QUERY =============================//
	
	function deleteAdmin($id){
		$this->db->delete('tbl_admin',array('id_admin' => $id));
	}

}

