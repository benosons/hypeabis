<?php
/**
 * This is a CodeIgniter Firebase Dynamic Link API.
 *
 * @category  API
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @author    Hengky Mulyono <hengky@binary-project.com>
 * @created   2019-04-17
 *  
 */ 

class Firebase {

  private $_api_url;
  private $_api_key;
  private $_page_link;
  
  function __construct()
  {
      $this->_obj =& get_instance();
      $this->_obj->load->config('firebase');
      $this->_api_url     = $this->_obj->config->item("firebase_dynamic_link");
      $this->_api_key     = $this->_obj->config->item("firebase_api_key");
      $this->_page_link   = $this->_obj->config->item("firebase_page_link");
      $this->_api_url     = $this->_api_url . '?key=' . $this->_api_key;
  }

  /**
   * Shorten a long URL
   *      
   * @param $url
   * @return JSON object                       
   */ 
  public function shorten($url)
  {
      $response = $this->send($url);
      return $response;    
  }
  
  private function send($url) 
  {
    $data = array(
       "dynamicLinkInfo" => array(
          "domainUriPrefix" => $this->_page_link,
          "link" => $url
       )
    );

    $headers = array('Content-Type: application/json');

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $this->_api_url);
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

    $data = curl_exec ( $ch );
    curl_close ( $ch );

    $short_url = json_decode($data);
    if(isset($short_url->error)){
        return $short_url;
    } else {
        return $short_url;
    }
  }
}