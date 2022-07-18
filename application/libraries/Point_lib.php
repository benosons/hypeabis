<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
 
class Point_lib{
	
  function __construct(){
		//load CI instance..
		$this->CI = & get_instance();
		
		//load model
    $this->CI->load->model('mdl_point');
    $this->CI->load->model('mdl_user');
		
		//construct script..
		
		//initialize session and language library..
  }
  
  public function addPoint($param = array()){
    //ambil data user.
    $data['user'] = $this->CI->mdl_user->getUserByID($param['id_user']);
    
    //ambil data point.
    $data['point'] = $this->CI->mdl_point->getPointByTriggerType($param['trigger_type']);
    
    //jika data ada,update poin user dan insert history.
    if(isset($data['user'][0]->id_user) && $data['user'][0]->id_user > 0 && isset($data['point'][0]->id_point) && $data['point'][0]->id_point > 0){
      //update point user..
      $update_data = array(
        'point' => $data['user'][0]->point + $data['point'][0]->point
      );
      $this->CI->mdl_user->updateUser($update_data, $data['user'][0]->id_user);
      
      //insert history point..
      $insert_data = array(
        'id_user' => $data['user'][0]->id_user,
        'id_point' => $data['point'][0]->id_point,
        'submit_date' => date('Y-m-d H:i:s'),
        'point' => $data['point'][0]->point,
        'description' => '<b>' . $data['point'][0]->trigger_str . '</b><br/>' . $param['desc']
      );
      $this->CI->mdl_point->insertPointHistory($insert_data);
    }
  }
  
  public function addReactionPoint($param = array(), $id_reaction){
    //ambil data user..
    $data['user'] = $this->CI->mdl_user->getUserByID($param['id_user']);
    
    //ambil reaction poin..
    $reaction = $this->CI->mdl_content->getReactionByID($id_reaction);
    if(isset($reaction[0]->id_reaction) && $reaction[0]->reaction_point != 0){
      //update point user..
      $update_data = array(
        'point' => $data['user'][0]->point + $reaction[0]->reaction_point
      );
      $this->CI->mdl_user->updateUser($update_data, $data['user'][0]->id_user);
      
      //insert history point..
      $insert_data = array(
        'id_user' => $data['user'][0]->id_user,
        'id_reaction' => $id_reaction,
        'submit_date' => date('Y-m-d H:i:s'),
        'point' => $reaction[0]->reaction_point,
        'description' => '<b>' . $reaction[0]->point_desc . '</b><br/>' . $param['desc']
      );
      $this->CI->mdl_point->insertPointHistory($insert_data);
    }
  }
  
  public function substractPoint($param = array()){
    //ambil data user..
    $data['user'] = $this->CI->mdl_user->getUserByID($param['id_user']);
    
    //ambil data point..
    $data['point'] = $this->CI->mdl_point->getPointByTriggerType($param['trigger_type']);
    
    //jika data ada,update poin user dan insert history..
    if(isset($data['user'][0]->id_user) && $data['user'][0]->id_user > 0 && isset($data['point'][0]->id_point) && $data['point'][0]->id_point > 0){
      //update point user..
      $update_data = array(
        'point' => $data['user'][0]->point + $data['point'][0]->point_min
      );
      $this->CI->mdl_user->updateUser($update_data, $data['user'][0]->id_user);
      
      //insert history point..
      $insert_data = array(
        'id_user' => $data['user'][0]->id_user,
        'id_point' => $data['point'][0]->id_point,
        'submit_date' => date('Y-m-d H:i:s'),
        'point' => $data['point'][0]->point_min,
        'description' => '<b>' . $data['point'][0]->trigger_str_min . '</b><br/>' . $param['desc']
      );
      $this->CI->mdl_point->insertPointHistory($insert_data);
    }
  }
  
  public function substractPointByIDUser($id_user, $point){
    //ambil data user..
    $data['user'] = $this->CI->mdl_user->getUserByID($id_user);
    
    //jika data ada,update poin user dan insert history..
    if(isset($data['user'][0]->id_user) && $data['user'][0]->id_user > 0){
      //update point user..
      $update_data = array(
        'point' => ($data['user'][0]->point - $point)
      );
      $this->CI->mdl_user->updateUser($update_data, $data['user'][0]->id_user);
      
      //insert history..
      $insert_data = array(
        'id_user' => $id_user,
        'id_point' => 0,
        'submit_date' => date('Y-m-d H:i:s'),
        'point' => ($point * -1),
        'description' => '<b>Menukarkan poin dengan merchandise.</b>'
      );
      $this->CI->mdl_point->insertPointHistory($insert_data);
    }
  }
  
  public function addPointByIDUser($id_user, $point){
    //ambil data user..
    $data['user'] = $this->CI->mdl_user->getUserByID($id_user);
    
    //jika data ada,update poin user dan insert history..
    if(isset($data['user'][0]->id_user) && $data['user'][0]->id_user > 0){
      //update point user..
      $update_data = array(
        'point' => ($data['user'][0]->point + $point)
      );
      $this->CI->mdl_user->updateUser($update_data, $data['user'][0]->id_user);
      
      //insert history..
      $insert_data = array(
        'id_user' => $id_user,
        'id_point' => 0,
        'submit_date' => date('Y-m-d H:i:s'),
        'point' => ($point),
        'description' => '<b>Pengembalian poin penukaran merchandise.</b>'
      );
      $this->CI->mdl_point->insertPointHistory($insert_data);
    }
  }
  
}