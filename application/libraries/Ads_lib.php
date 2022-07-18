<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
 
class Ads_lib{
	
  function __construct(){
		//load CI instance..
		$this->CI = & get_instance();
		
		//load model
		$this->CI->load->model('mdl_ads');
		
		//construct script..
		date_default_timezone_set("Asia/Jakarta");
		
		//initialize session and language library..
  }
  
  public function getHomepageAds(){
    $data = array();
    $now = date('Y-m-d H:i:s');
    
    //ambil ads type 'hm'
    $ads_type = $this->CI->mdl_ads->getAdsTypeByTypeCode('hm');
    foreach($ads_type as $type){
      //ambil 
      // $data[$type->ads_code]['id_adstype'] = $type->id_adstype;
      $data[$type->ads_code]['builtin'] = $this->CI->mdl_ads->getActiveAds($now, $type->id_adstype, 'builtin');
      if(!(isset($data[$type->ads_code]['builtin'][0]['id_ads']) && $data[$type->ads_code]['builtin'][0]['id_ads'] > 0)){
        $data[$type->ads_code]['googleads'] = $this->CI->mdl_ads->getGoogleAds($type->id_adstype, 'googleads');
      }
      else{
        //update counter view ads 
        $update_data = array(
          'view_count' => ($data[$type->ads_code]['builtin'][0]['view_count'] + 1)
        );
        $this->CI->mdl_ads->updateAds($update_data, $data[$type->ads_code]['builtin'][0]['id_ads']);
      }
    }
    
    return $data;
  }
  
  public function getCategoryAds(){
    $data = array();
    $now = date('Y-m-d H:i:s');
    
    //ambil ads type 'ct'
    $ads_type = $this->CI->mdl_ads->getAdsTypeByTypeCode('ct');
    foreach($ads_type as $type){
      //ambil 
      // $data[$type->ads_code]['id_adstype'] = $type->id_adstype;
      $data[$type->ads_code]['builtin'] = $this->CI->mdl_ads->getActiveAds($now, $type->id_adstype, 'builtin');
      if(!(isset($data[$type->ads_code]['builtin'][0]['id_ads']) && $data[$type->ads_code]['builtin'][0]['id_ads'] > 0)){
        $data[$type->ads_code]['googleads'] = $this->CI->mdl_ads->getGoogleAds($type->id_adstype, 'googleads');
      }
      else{
        //update counter view ads 
        $update_data = array(
          'view_count' => ($data[$type->ads_code]['builtin'][0]['view_count'] + 1)
        );
        $this->CI->mdl_ads->updateAds($update_data, $data[$type->ads_code]['builtin'][0]['id_ads']);
      }
    }
    
    return $data;
  }
  
  public function getArticleAds(){
    $data = array();
    $now = date('Y-m-d H:i:s');
    
    //ambil ads type 'ct'
    $ads_type = $this->CI->mdl_ads->getAdsTypeByTypeCode('ar');
    foreach($ads_type as $type){
      //ambil 
      // $data[$type->ads_code]['id_adstype'] = $type->id_adstype;
      $data[$type->ads_code]['builtin'] = $this->CI->mdl_ads->getActiveAds($now, $type->id_adstype, 'builtin');
      if(!(isset($data[$type->ads_code]['builtin'][0]['id_ads']) && $data[$type->ads_code]['builtin'][0]['id_ads'] > 0)){
        $data[$type->ads_code]['googleads'] = $this->CI->mdl_ads->getGoogleAds($type->id_adstype, 'googleads');
      }
      else{
        //update counter view ads 
        $update_data = array(
          'view_count' => ($data[$type->ads_code]['builtin'][0]['view_count'] + 1)
        );
        $this->CI->mdl_ads->updateAds($update_data, $data[$type->ads_code]['builtin'][0]['id_ads']);
      }
    }
    
    return $data;
  }
  
  public function getFooterAds(){
    $data = array();
    $now = date('Y-m-d H:i:s');
    
    //ambil ads type 'ft'
    $ads_type = $this->CI->mdl_ads->getAdsTypeByTypeCode('ft');
    foreach($ads_type as $type){
      //ambil 
      // $data[$type->ads_code]['id_adstype'] = $type->id_adstype;
      $data[$type->ads_code]['builtin'] = $this->CI->mdl_ads->getActiveAds($now, $type->id_adstype, 'builtin');
      if(!(isset($data[$type->ads_code]['builtin'][0]['id_ads']) && $data[$type->ads_code]['builtin'][0]['id_ads'] > 0)){
        $data[$type->ads_code]['googleads'] = $this->CI->mdl_ads->getGoogleAds($type->id_adstype, 'googleads');
      }
      else{
        //update counter view ads 
        $update_data = array(
          'view_count' => ($data[$type->ads_code]['builtin'][0]['view_count'] + 1)
        );
        $this->CI->mdl_ads->updateAds($update_data, $data[$type->ads_code]['builtin'][0]['id_ads']);
      }
    }
    
    return $data;
  }
  
}