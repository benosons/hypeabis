<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");

require_once APPPATH . 'third_party/midtrans-php-master/Midtrans.php';
use Midtrans\Config;
use Midtrans\Transaction;
use Midtrans\Notification;
use Midtrans\Snap;

class Midtrans_lib{
  
  function __construct(){
		//load CI instance..
		$this->CI = & get_instance();
    
    //Set Your server key
    Config::$serverKey = $this->CI->config->item('midtrans_serverkey');
    
    // Uncomment for production environment
    Config::$isProduction = true;
  }
  
  function getSnapToken($transaction){
    // Enable sanitization
    Config::$isSanitized = true;
    
    // Enable 3D-Secure
    Config::$is3ds = true;
    
    $snapToken = Snap::getSnapToken($transaction);
    
    return $snapToken;
  }
  
  function getTransactionStatus($id){
    try {
        $status = Transaction::status($id);
    } catch (Exception $e) {
        echo $e->getMessage();
        die();
    }
    
    return $status;
  }
  
  function getNotification(){
    $notif = new Notification();
    return $notif;
  }
  
  function script()
  {
    $midtrans_url = $this->CI->config->item('midtrans_url');
    $client_key = $this->CI->config->item('midtrans_clientkey');

    return "<script src='{$midtrans_url}' data-client-key='$client_key'></script>";
  }
}
