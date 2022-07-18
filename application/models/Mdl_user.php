<?php
 
/**
 * @property-read CI_DB $db
 */
Class Mdl_user extends CI_Model{

	//=============================== GET QUERY ===============================//
	
	function getNameByID($id){
		$this->db->select('tbl_user.name');
		$user = $this->db->get_where('tbl_user', array('id_user' => $id, 'deleted' => 0))->row();
		return $user ? $user->name : null;
	}
	
	function getEmailByID($id)
	{
		$user = $this->db->select('tbl_user.email')->get_where('tbl_user', array('id_user' => $id, 'deleted' => 0))->row();
		return $user ? $user->email : null;
	}
	
	function getUserByID($id){
		$this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('id_user' => $id, 'deleted' => 0))->result();
	}

	function getUserByIDSelect2($id)
	{
		return $this
			->db
			->select('tbl_user.id_user as id, name as text')
			->get_where('tbl_user', array('id_user' => $id, 'deleted' => 0))
			->row();
	}
	
	function getCoverByID($id)
	{
		$this->db->select('tbl_user.cover');
		$user = $this->db->get_where('tbl_user', array('id_user' => $id, 'deleted' => 0))->row();
		return $user ? $user->cover : null;
	}
  
  function getUserByIDArr($id){
    $this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('id_user' => $id, 'deleted' => 0))->result_array();
  }
  
  function getUserByHash($hash){
    $this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('hash' => $hash, 'deleted' => 0))->result();
  }
  
  function getUserByEmail($email){
    $this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('email' => $email, 'deleted' => 0))->result();
  }
  
  function getUserByAllEmail($email){
    $this->db->select('tbl_user.*');
    $where  = " (email = '" . $email . "' OR ";
    $where .= "email_google = '" . $email . "' OR ";
    $where .= "email_twitter = '" . $email . "' OR ";
    $where .= "email_facebook = '" . $email . "' OR ";
    $where .= "email_linkedin = '" . $email . "') AND";
    $where .= " (deleted = 0)";
    $this->db->where($where);
		return $this->db->get('tbl_user')->result();
  }
  
  function getUserByEmailFacebook($email){
    $this->db->select('tbl_user.*');
    $where  = " (email = '" . $email . "' OR ";
    $where .= "email_facebook = '" . $email . "') ";
    $this->db->where($where);
		return $this->db->get_where('tbl_user', array('deleted' => 0))->result();
  }
  
  function getUserByEmailGoogle($email){
    $this->db->select('tbl_user.*');
    $where  = " (email = '" . $email . "' OR ";
    $where .= "email_google = '" . $email . "') ";
    $this->db->where($where);
		return $this->db->get_where('tbl_user', array('deleted' => 0))->result();
  }
  
  function getUserByEmailTwitter($email){
    $this->db->select('tbl_user.*');
    $where  = " (email = '" . $email . "' OR ";
    $where .= "email_twitter = '" . $email . "') ";
    $this->db->where($where);
		return $this->db->get_where('tbl_user', array('deleted' => 0))->result();
  }
  
  function getUserByEmailLinkedin($email){
    $this->db->select('tbl_user.*');
    $where  = " (email = '" . $email . "' OR ";
    $where .= "email_linkedin = '" . $email . "') ";
    $this->db->where($where);
		return $this->db->get_where('tbl_user', array('deleted' => 0))->result();
  }
  
  function getUserByFacebookUid($uid){
    $this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('oauth_uid_facebook' => $uid, 'deleted' => 0))->result();
  }
  
  function getUserByGoogleUid($uid){
    $this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('oauth_uid_google' => $uid, 'deleted' => 0))->result();
  }
  
  function getUserByTwitterUid($uid){
    $this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('oauth_uid_twitter' => $uid, 'deleted' => 0))->result();
  }
  
  function getUserByLinkedinUid($uid){
    $this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('oauth_uid_linkedin' => $uid, 'deleted' => 0))->result();
  }
  
  function getAllUser(){
		$this->db->select('tbl_user.*');
		$this->db->order_by('id_user','desc');
		return $this->db->get_where('tbl_user', array('deleted' => 0))->result();
	}
	
	function getAllUserCount(){
		$this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('deleted' => 0))->num_rows();
	}
	
	function getAllUserLimit($num,$offset){
		$this->db->select('tbl_user.*');
    $this->db->select('COUNT(CASE WHEN tbl_content.type = 1 THEN 1 END) AS content_count');
    $this->db->select('COUNT(CASE WHEN tbl_content.type = 7 THEN 1 END) AS photo_count');
    $this->db->join('tbl_content', 'tbl_user.id_user = tbl_content.id_user', 'left');
    $this->db->group_by('tbl_user.id_user');
		$this->db->order_by('tbl_user.id_user','desc');
		$this->db->limit($num,$offset);
		return $this->db->get_where('tbl_user', array('tbl_user.deleted' => 0))->result();
	}

  function getAllProductiveUserLimit($num, $search_param = [])
  {
    $this->db->select('tbl_user.id_user, tbl_user.name');
    $this->db->select('COUNT(CASE WHEN type=1 THEN 1 END) AS article_count');
    $this->db->select('COUNT(CASE WHEN type=7 THEN 1 END) AS photo_count');
    $this->db->join('tbl_content', 'tbl_user.id_user = tbl_content.id_user AND content_status = 1 AND type IN (1, 7)', 'left');
    $this->db->group_by('tbl_user.id_user');
    $this->db->group_by('tbl_user.name');
    $this->db->order_by('article_count','desc');
    $this->db->order_by('photo_count','desc');
    $this->db->limit($num);

    if (!empty($search_param['author']))
    {
      $this->db->where('tbl_content.id_user', $search_param['author']);
    }

		if (!empty($search_param['admin']))
		{
			$this->db->join('tbl_content_editor', 'tbl_content.id_content=tbl_content_editor.id_content', 'left');
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

    return $this->db->get_where('tbl_user', array('tbl_user.deleted' => 0))->result();
  }

	function searchUserSelect2($query, $limit = 5)
	{
		$this
			->db
			->select('tbl_user.id_user as id, name as text')
			->order_by('id_user','desc')
			->where('deleted', 0)
			->limit($limit);
		
		if (!empty($query))
		{
			$this->db->like('name', $query);
		}

		return $this->db->get('tbl_user')->result();
	}
  
	function getSearchResult($search_param, $num, $offset){
		$this->db->select('tbl_user.*');
    $this->db->select('COUNT(tbl_content.id_user) AS content_count');
    $this->db->select('COUNT(CASE WHEN tbl_content.type = 7 THEN 1 END) AS photo_count');
    $this->db->join('tbl_content', 'tbl_user.id_user = tbl_content.id_user', 'left');
    $this->db->group_by('tbl_user.id_user');
    
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
		if($search_param['status'] == '1'){
			$this->db->where('tbl_user.status', 1);
		}
		else if($search_param['status'] == '0'){
			$this->db->where('tbl_user.status', 0);
		}
		else{}
    
    //cek for confirm_email..
		if($search_param['confirm_email'] == '1'){
			$this->db->where('tbl_user.confirm_email', 1);
		}
		else if($search_param['confirm_email'] == '0'){
			$this->db->where('tbl_user.confirm_email', 0);
		}
		else{}

    //cek for is_internal..
    if ($search_param['is_internal'] == '1') {
      $this->db->where('tbl_user.is_internal', 1);
    } else if ($search_param['is_internal'] == '0') {
      $this->db->where('tbl_user.is_internal', 0);
    } else {}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		$this->db->limit($num, $offset);
		return $this->db->get_where('tbl_user', array('deleted' => 0))->result();
	}
	
	function getSearchResultCount($search_param){
		$this->db->select('tbl_user.*');
    $this->db->select('COUNT(tbl_content.id_user) AS content_count');
    $this->db->join('tbl_content', 'tbl_user.id_user = tbl_content.id_user', 'left');
    $this->db->group_by('tbl_user.id_user');
    
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
		if($search_param['status'] == '1'){
			$this->db->where('tbl_user.status', 1);
		}
		else if($search_param['status'] == '0'){
			$this->db->where('tbl_user.status', 0);
		}
		else{}
    
    //cek for status..
		if($search_param['confirm_email'] == '1'){
			$this->db->where('tbl_user.confirm_email', 1);
		}
		else if($search_param['confirm_email'] == '0'){
			$this->db->where('tbl_user.confirm_email', 0);
		}
		else{}

    //cek for is_internal..
    if ($search_param['is_internal'] == '1') {
      $this->db->where('tbl_user.is_internal', 1);
    } else if ($search_param['is_internal'] == '0') {
      $this->db->where('tbl_user.is_internal', 0);
    } else {}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get_where('tbl_user', array('deleted' => 0))->num_rows();
	}
	
  function getUserByEmailAndPassword($email, $password){
    $this->db->select('tbl_user.*');
		return $this->db->get_where('tbl_user', array('email' => $email, 'password' => $password, 'deleted' => 0))->result();
  }
  
  function getUserLevelByPoint($point){
    $this->db->where(array('level_point <=' => $point));
    $this->db->order_by('level_point', 'DESC');
    $this->db->limit(1);
    return $this->db->get_where('tbl_level')->result();
  }
  
  function getUserByKeywordForSelect2($keyword){
    $this->db->like('tbl_user.name', $keyword);
    $this->db->or_like('tbl_user.email', $keyword);
    return $this->db->get_where('tbl_user', array('status' => 1, 'confirm_email' => 1, 'deleted' => 0))->result();
  }
  
  function getAllFeaturedUserCategoryCount(){
    $this->db->select('tbl_author_category.*');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->select('tbl_category.category_name, tbl_category.order');
    $this->db->join('tbl_user', 'tbl_author_category.id_user = tbl_user.id_user', 'inner');
    $this->db->join('tbl_category', 'tbl_author_category.id_category = tbl_category.id_category', 'inner');
    $this->db->order_by('tbl_category.order', 'asc');
    $this->db->order_by('tbl_author_category.author_order', 'asc');
		return $this->db->get('tbl_author_category')->num_rows();
  }
  
  function getAllFeaturedUserCategoryLimit($num, $offset){
    $this->db->select('tbl_author_category.*');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->select('tbl_category.category_name');
    $this->db->join('tbl_user', 'tbl_author_category.id_user = tbl_user.id_user', 'inner');
    $this->db->join('tbl_category', 'tbl_author_category.id_category = tbl_category.id_category', 'inner');
    $this->db->order_by('tbl_category.order', 'asc');
    $this->db->order_by('tbl_author_category.author_order', 'asc');
    $this->db->limit($num, $offset);
		return $this->db->get('tbl_author_category')->result();
  }
  
  function getFeaturedUserCategoryByID($id){
    $this->db->select('tbl_author_category.*');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->select('tbl_category.category_name');
    $this->db->join('tbl_user', 'tbl_author_category.id_user = tbl_user.id_user', 'inner');
    $this->db->join('tbl_category', 'tbl_author_category.id_category = tbl_category.id_category', 'inner');
    $this->db->order_by('tbl_category.order', 'asc');
    $this->db->order_by('tbl_author_category.author_order', 'asc');
		return $this->db->get_where('tbl_author_category', array('tbl_author_category.id_author_category' => $id))->result();
  }
  
  function getFeaturedUserCategoryByIDUserAndIDCategory($id_user, $id_category){
    $this->db->select('tbl_author_category.*');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->select('tbl_category.category_name');
    $this->db->join('tbl_user', 'tbl_author_category.id_user = tbl_user.id_user', 'inner');
    $this->db->join('tbl_category', 'tbl_author_category.id_category = tbl_category.id_category', 'inner');
    $this->db->order_by('tbl_category.order', 'asc');
    $this->db->order_by('tbl_author_category.author_order', 'asc');
		return $this->db->get_where('tbl_author_category', array('tbl_author_category.id_user' => $id_user, 'tbl_author_category.id_category' => $id_category))->result();
  }
  
  function getFeaturedUserCategorySearchResultCount($search_param){
    $this->db->select('tbl_author_category.*');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->select('tbl_category.category_name');
    $this->db->join('tbl_user', 'tbl_author_category.id_user = tbl_user.id_user', 'inner');
    $this->db->join('tbl_category', 'tbl_author_category.id_category = tbl_category.id_category', 'inner');
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
		//check for example_select..
		if($search_param['category'] != 'all'){
			$this->db->where('tbl_author_category.id_category', $search_param['category']);
		}
		//===============================================================================================//
		
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_author_category')->num_rows();
  }
  
  function getFeaturedUserCategorySearchResult($search_param, $num, $offset){
    $this->db->select('tbl_author_category.*');
    $this->db->select('tbl_user.name, tbl_user.email, tbl_user.picture_from, tbl_user.picture, tbl_user.verified');
    $this->db->select('tbl_category.category_name');
    $this->db->join('tbl_user', 'tbl_author_category.id_user = tbl_user.id_user', 'inner');
    $this->db->join('tbl_category', 'tbl_author_category.id_category = tbl_category.id_category', 'inner');
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
		//check for example_select..
		if($search_param['category'] != 'all'){
			$this->db->where('tbl_author_category.id_category', $search_param['category']);
		}
		//===============================================================================================//
		
    $this->db->limit($num, $offset);
		$this->db->order_by($search_param['sort_by']);
		return $this->db->get('tbl_author_category')->result();
  }

	public function getUserFollowers($id_user, $num, $offset)
	{
		return $this
			->db
			->select('tbl_user.id_user, name, picture, picture_from')
			->from('tbl_user_following')
			->join('tbl_user', 'tbl_user.id_user=tbl_user_following.id_user')
			->where('tbl_user_following.id_user_following', $id_user)
			->order_by('name', 'asc')
			->limit($num, $offset)
			->get()
			->result();
	}

	public function getUserFollowings($id_user, $num, $offset)
	{
		return $this
			->db
			->select('tbl_user.id_user, name, picture, picture_from')
			->from('tbl_user_following')
			->join('tbl_user', 'tbl_user.id_user=tbl_user_following.id_user_following')
			->where('tbl_user_following.id_user', $id_user)
			->order_by('name', 'asc')
			->limit($num, $offset)
			->get()
			->result();
	}
  
	public function getTotalFollower($id_user)
	{
		return $this->db->from('tbl_user_following')->where('id_user_following', $id_user)->count_all_results();
	}
  
	public function getTotalFollowing($id_user)
	{
		return $this->db->from('tbl_user_following')->where('id_user', $id_user)->count_all_results();
	}

	//============================== CHECK QUERY ==============================//
	
	function checkUserByID($id){
		$query = $this->db->get_where('tbl_user', array('id_user' => $id, 'deleted' => 0));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
  
  function checkUserByHash($hash){
    $query = $this->db->get_where('tbl_user', array('hash' => $hash, 'deleted' => 0));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
  }
  
  function checkUserByEmail($email){
    $this->db->where('email', $email);
    $this->db->or_where('email_google', $email);
    $this->db->or_where('email_facebook', $email);
    $query = $this->db->get_where('tbl_user', array('deleted' => 0));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
  }
  
  function checkUserByIDAndPassword($id, $password){
    $this->db->like('oauth_provider', 'web|');
    $query = $this->db->get_where('tbl_user', array('id_user' => $id, 'password' => $password, 'deleted' => 0));
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
  }

	function isFollowingUser($id_user, $id_user_following)
	{
		return $this->db->get_where('tbl_user_following', compact('id_user', 'id_user_following'))->num_rows() > 0;
	}
	
	public function canBeVerifiedMember($id_user)
	{
		$user_count = $this
			->db
			->select('tbl_user.id')
			->from('tbl_user')
			->where(['id_user' => $id_user, 'verified' => 0, 'deleted' => 0])
			->where('point >= (SELECT verified_member_point FROM tbl_global WHERE id_global = 1)')
			->count_all_results();

		if ($user_count > 0)
		{
			$unfinished_submission_count = $this
				->db
				->from('tbl_verified_member_submission')
				->where('id_user', $id_user)
				->where('is_accepted IS NULL')
				->count_all_results();

			return $unfinished_submission_count === 0;
		}

		return FALSE;
	}

  function validateLogin($email, $password){
    //ambil bedasarkan user dan password..
		$user_data = $this->db->get_where('tbl_user',array('email' => $email, 'password' => $password, 'deleted' => 0))->result();
		
		if(is_array($user_data) && count($user_data) > 0){
			//jika status bukan = 1 berarti user di banned.
			if($user_data[0]->status == "1"){
				//cek jika confirm_email bukan = 1 berarti user harus confirm email terlebih dahulu
				if($user_data[0]->confirm_email == "1"){
					return 1; // success
				}
				else{
					return 2; // need confirm email
				}
			}
      else if($user_data[0]->status == "2"){
        return 3; // non active
      }
			else{
				return 4; // banned
			}
		}
		else{
			return 5; // wrong login
		}
  }
  
	//============================== INSERT QUERY =============================//
	
	function insertUser($insert_data){
		$this->db->insert('tbl_user',$insert_data);
	}
  
  function insertFeaturedUserCategory($insert_data){
    $this->db->insert('tbl_author_category',$insert_data);
  }

	function insertUserFollowed($id_user, $id_user_following)
	{
			$query = $this->db->insert_string('tbl_user_following', compact('id_user', 'id_user_following'));

			$this->db->query(str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query));
	}

	function applyVerifiedMember($id_user, $ktp_picture)
	{
		$this->db->insert('tbl_verified_member_submission', compact('id_user', 'ktp_picture'));
	}
	
	//============================== UPDATE QUERY =============================//
	
	function updateUser($update_data, $id){
		$this->db->where('id_user', $id);
		$this->db->update('tbl_user',$update_data);
	}
  
  function updateFeaturedUserCategory($update_data, $id){
    $this->db->where('id_author_category', $id);
		$this->db->update('tbl_author_category',$update_data);
  }
  
	//============================== DELETE QUERY =============================//
	
	function deleteUser($id){
		$this->db->delete('tbl_user',array('id_user' => $id));
	}
  
  function deleteFeaturedUserCategory($id){
    $this->db->delete('tbl_author_category',array('id_author_category' => $id));
  }
	
	function deleteUserFollowed($id_user, $id_user_following)
	{
		$this->db->delete('tbl_user_following', compact('id_user', 'id_user_following'));
	}
}

