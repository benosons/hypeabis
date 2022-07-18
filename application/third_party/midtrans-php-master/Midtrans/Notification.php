<?php

namespace Midtrans;

/**
 * Read raw post input and parse as JSON. Provide getters for fields in notification object
 *
 * Example:
 *
 * ```php
 * 
 *   namespace Midtrans;
 * 
 *   $notif = new Notification();
 *   echo $notif->order_id;
 *   echo $notif->transaction_status;
 * ```
 */
class Notification
{
    private $response;

    public function __construct($input_source = "php://input")
    {
        $raw_notification = json_decode(file_get_contents($input_source), true);
        if(isset($raw_notification['transaction_id']) && strlen(trim($raw_notification['transaction_id'])) > 0){
          $status_response = Transaction::status($raw_notification['transaction_id']);
        }
        else{
          $status_response = array();
        }
        $this->response = $status_response;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->response)) {
          return $this->response->$name;
        }
        else{
          return false;
        }
    }
}
