<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
 
class Frontend_lib{
	
  function __construct(){
		//load CI instance..
		$this->CI = & get_instance();
		
		//load model
    $this->CI->load->model('mdl_menu');
		
		//construct script..
		
		//initialize session and language library..
  }
  
  public function getUserPictureURL($picture = '', $picture_from=''){
    $picture_url = null;
    if($picture_from == 'facebook' || $picture_from == 'google' || $picture_from == 'twitter' || $picture_from == 'linkedin'){
      $picture_url = $picture;
    }
    else{
      if(isset($picture) && strlen(trim($picture)) > 0){
        $picture_url = base_url() . 'assets/user/' . $picture;
      }
      else{
        $picture_url = base_url() . 'assets/user/default.png';
      }
    }
    
    // $picture_url = base_url() . 'assets/user/default.png';
    return $picture_url;
  }
  
  public function generatePageLink($url = ''){
    if(strpos(strtolower($url), 'http://') !== false || strpos(strtolower($url), 'https://') !== false){
      $redirect_url = $url;
    }
    else if(strpos(strtolower($url), 'www') !== false){
      $redirect_url = 'http://' . $url;
    }
    else{
      $redirect_url = base_url() . $url;
    }
    return $redirect_url;
  }
  
  
}