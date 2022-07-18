<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
 
class Lazada{
	
  protected $_CI;// CodeIgniter instance
  
  function __construct(){
    $this->_CI = & get_instance();
    ini_set('memory_limit', '-1');
    
    date_default_timezone_set("Asia/Jakarta");
  }
  
  private function generateTimestamp(){
    //compute signature parameter
    // Pay no attention to this statement.
    // It's only needed if timezone in php.ini is not set correctly.
    date_default_timezone_set("UTC");
    // The current time. Needed to create the Timestamp parameter below.
    $now = new DateTime();
    $timestamp = $now->format(DateTime::ISO8601);
    //return timestamp value
    return $timestamp;
  }
  
  private function generateQueryString($parameters = array()){
    // Sort parameters by name.
    ksort($parameters);
    // URL encode the parameters.
    $encoded = array();
    foreach ($parameters as $name => $value) {
        $encoded[] = rawurlencode($name) . '=' . rawurlencode($value);
    }
    // Concatenate the sorted and URL encoded parameters into a string.
    $concatenated = implode('&', $encoded);
    // The API key for the user as generated in the Seller Center GUI.
    // Must be an API key associated with the UserID parameter.
    // Compute signature and add it to the parameters.
    $parameters['Signature'] = rawurlencode(hash_hmac('sha256', $concatenated, $this->_CI->config->item('lazada_api_key'), false));
    // Build Query String
    $queryString = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
    
    return $queryString;
  }
	
  public function getProducts($offset = ''){
    $result = array();
    // $date_from = date('c', strtotime($date_from));
    
    $result['request_time'] = array();
    $result['api_result'] = array();
    
    $data_per_request = 100;
    
    //start time..
    $time_start = microtime(true);
    
    $parameter = array(
      'Action'        => 'GetProducts',
      'UserID'        => $this->_CI->config->item('lazada_user_id'),
      'Version'       => $this->_CI->config->item('lazada_api_version'),
      'Format'        => $this->_CI->config->item('lazada_api_format'),
      'Timestamp'     => $this->generateTimestamp(),
      'Limit'         => $data_per_request,
      'Offset'        => $offset,
      'Filter'        => 'all'
    );
    
    //record parameter data..
    $result['parameter'] = $parameter;
    
    // Build Query String
    $queryString = $this->generateQueryString($parameter);
    
    // Open and Close cURL connection
    try{
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->_CI->config->item('lazada_api_url') . "?" . $queryString);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $data = curl_exec($ch);
      curl_close($ch);
    }
    catch(Exception $e){
      $result['error'] = $e->getMessage();
    }
    
    //stop timer
    $time_end = microtime(true);
    
    $result['data'] = $data;
    $result['request_time'] = $time_end - $time_start;
    
    return $result;
  }
  
  public function updateProductPrice($sellerSku = null, $price = null, $sale_price = null){
    $result = array();
    $result['request_time'] = array();
    $result['api_result'] = array();
    
    //start time..
    $time_start = microtime(true);
    
    if(isset($sellerSku) && strlen($sellerSku) > 0){
    
      $parameter = array(
        'Action'        => 'UpdatePriceQuantity',
        'UserID'        => $this->_CI->config->item('lazada_user_id'),
        'Version'       => $this->_CI->config->item('lazada_api_version'),
        'Format'        => $this->_CI->config->item('lazada_api_format'),
        'Timestamp'     => $this->generateTimestamp()
      );
      
      //record parameter data..
      $result['parameter'] = $parameter;
      
      //Build Query String
      $queryString = $this->generateQueryString($parameter);
      
      $xml_data = '<?xml version="1.0" encoding="UTF-8"?>' . 
        '<Request>' .
          '<Product>' .
            '<Skus>' .
              '<Sku>' .
                '<SellerSku>' . $sellerSku . '</SellerSku>' .
                '<Quantity/>' .
                (isset($price) ? '<Price>' . $price . '</Price>' : '<Price/>') .
                (isset($sale_price) ? '<SalePrice>' . $sale_price . '</SalePrice>' : '<SalePrice/>') .
                '<SaleStartDate/>' .
                '<SaleEndDate/>' .
              '</Sku>' .
            '</Skus>' .
          '</Product>' .
        '</Request>';
      
      // Open and Close cURL connection
      try{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_CI->config->item('lazada_api_url') . "?" . $queryString);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        $data = curl_exec($ch);
        curl_close($ch);
      }
      catch(Exception $e){
        $result['error'] = $e->getMessage();
      }
      
      //stop timer
      $time_end = microtime(true);
      
      $result['data'] = $data;
      $result['request_time'] = $time_end - $time_start;
    }
    
    return $result;
  }
  
  public function getOrders($date_from = ''){
    $result = array();
    $date_from = date('c', strtotime($date_from));
    
    $result['request_time'] = array();
    $result['api_result'] = array();
    
    $data_per_request = 100;
    
    //start time..
    $time_start = microtime(true);
    
    $parameter = array(
      'Action'        => 'GetOrders',
      'UserID'        => $this->_CI->config->item('lazada_user_id'),
      'Version'       => $this->_CI->config->item('lazada_api_version'),
      'Format'        => $this->_CI->config->item('lazada_api_format'),
      'Timestamp'     => $this->generateTimestamp(),
      'CreatedAfter'  => $date_from,
      'UpdatedAfter'  => $date_from,
      'Limit'         => 100,
      'Offset'        => 0,
      'SortBy'        => 'updated_at',
      'SortDirection' => 'ASC'
    );
    
    //record parameter data..
    $result['parameter'] = $parameter;
    
    // Build Query String
    $queryString = $this->generateQueryString($parameter);
    
    // Open and Close cURL connection
    try{
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->_CI->config->item('lazada_api_url') . "?" . $queryString);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $data = curl_exec($ch);
      curl_close($ch);
    }
    catch(Exception $e){
      $result['error'] = $e->getMessage();
    }
    
    //stop timer
    $time_end = microtime(true);
    
    $result['data'] = $data;
    $result['request_time'] = $time_end - $time_start;
    
    return $result;
  }
  
  public function updateOrders($date_from = ''){
    $result = array();
    $date_default = date('c', strtotime('2018-02-18 06:27:00')); //untuk set createdAt
    $date_from = date('c', strtotime($date_from));
    
    $result['request_time'] = array();
    $result['api_result'] = array();
    
    $data_per_request = 100;
    
    //start time..
    $time_start = microtime(true);
    
    $parameter = array(
      'Action'        => 'GetOrders',
      'UserID'        => $this->_CI->config->item('lazada_user_id'),
      'Version'       => $this->_CI->config->item('lazada_api_version'),
      'Format'        => $this->_CI->config->item('lazada_api_format'),
      'Timestamp'     => $this->generateTimestamp(),
      'CreatedAfter'  => $date_default,
      'UpdatedAfter'  => $date_from,
      'Limit'         => $data_per_request,
      'Offset'        => 0,
      'SortBy'        => 'updated_at',
      'SortDirection' => 'ASC'
    );
    
    //record parameter data..
    $result['parameter'] = $parameter;
    
    // Build Query String
    $queryString = $this->generateQueryString($parameter);
    
    // Open and Close cURL connection
    try{
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->_CI->config->item('lazada_api_url') . "?" . $queryString);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $data = curl_exec($ch);
      curl_close($ch);
    }
    catch(Exception $e){
      $result['error'] = $e->getMessage();
    }
    
    //stop timer
    $time_end = microtime(true);
    
    $result['data'] = $data;
    $result['request_time'] = $time_end - $time_start;
    
    return $result;
  }
  
  public function getOrderItem($OrderIdList = ''){
    $result = array();
    $result['request_time'] = array();
    $result['api_result'] = array();
    
    //start time..
    $time_start = microtime(true);
    
    $parameter = array(
      'Action'        => 'GetMultipleOrderItems',
      'UserID'        => $this->_CI->config->item('lazada_user_id'),
      'Version'       => $this->_CI->config->item('lazada_api_version'),
      'Format'        => $this->_CI->config->item('lazada_api_format'),
      'Timestamp'     => $this->generateTimestamp(),
      'OrderIdList'   => $OrderIdList
    );
    
    //record parameter data..
    $result['parameter'] = $parameter;
    
    // Build Query String
    $queryString = $this->generateQueryString($parameter);
    
    // Open and Close cURL connection
    try{
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->_CI->config->item('lazada_api_url') . "?" . $queryString);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $data = curl_exec($ch);
      curl_close($ch);
    }
    catch(Exception $e){
      $result['error'] = $e->getMessage();
    }
    
    //stop timer
    $time_end = microtime(true);
    
    $result['data'] = $data;
    $result['request_time'] = $time_end - $time_start;
    
    return $result;
  }
  
  public function getTransactionDetails($date = '', $offset = ''){
    $result = array();
    $date_start = date('c', strtotime($date));
    $date_end = date('c', strtotime($date));
    
    $result['request_time'] = array();
    $result['api_result'] = array();
    
    $total_data = 0;
    
    //start time..
    $time_start = microtime(true);
    
    $parameter = array(
      'Action'        => 'GetTransactionDetails',
      'UserID'        => $this->_CI->config->item('lazada_user_id'),
      'Version'       => $this->_CI->config->item('lazada_api_version'),
      'Format'        => $this->_CI->config->item('lazada_api_format'),
      'Timestamp'     => $this->generateTimestamp(),
      'startTime'     => $date_start,
      'endTime'       => $date_end,
      'limit'         => 500,
      'offset'        => $offset,
      'transType'     => -1
    );
    
    //record parameter data..
    $result['parameter'] = $parameter;
    
    // Build Query String
    $queryString = $this->generateQueryString($parameter);
    
    // Open and Close cURL connection
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->_CI->config->item('lazada_api_url') . "?" . $queryString);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $data = curl_exec($ch);
    curl_close($ch);
    
    //stop timer
    $time_end = microtime(true);
    
    $result['data'] = $data;
    $result['request_time'] = $time_end - $time_start;
    
    return $result;
  }
  
  /*
	public function getOrdersLoop($date_from='', $date_to=''){
    $result = array();
    $date_from = date('c', strtotime($date_from));
    $date_to = date('c', strtotime($date_to));
    
    $result['request_data'] = array();
    $result['api_result'] = array();
    $result['total_data'] = 0;
    
    $data_per_request = 10;
    $flag = true;
    $x = 0;
    $total_data = 0;
    $num = 1;
    
    // while($flag){
    for($x = 0; $x < 7; $x++){
      
      $time_start = microtime(true);
      
      $parameter = array(
        'Action'        => 'GetOrders',
        'UserID'        => $this->_CI->config->item('lazada_user_id'),
        'Version'       => $this->_CI->config->item('lazada_api_version'),
        'Format'        => $this->_CI->config->item('lazada_api_format'),
        'Timestamp'     => $this->generateTimestamp(),
        'CreatedAfter'  => $date_from,
        'UpdatedAfter'  => $date_from,
        'CreatedBefore' => $date_to,
        'Status'        => 'delivered',
        'Limit'         => $data_per_request,
        'Offset'        => $x * $data_per_request
      );
      
      //record parameter data..
      $result['parameter'][$x] = $parameter;
      
      // Build Query String
      $queryString = $this->generateQueryString($parameter);
      
      // Open and Close cURL connection
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->_CI->config->item('lazada_api_url') . "?" . $queryString);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $data = curl_exec($ch);
      curl_close($ch);
      
      $result_from_api[$x] = $data;
      // $result_from_api[$x] = json_decode($data, true);
      
      $time_end = microtime(true);
      
      //proses data api ke array.
      if(isset($result_from_api['SuccessResponse'])){
        foreach($result_from_api['SuccessResponse']['Body']['Orders'] as $item){
          $temp_arr = array(
            'No' => $num,
            'OrderId' => $item['OrderId'],
            'OrderNumber' => $item['OrderNumber'],
            'CustomerFirstName' => $item['CustomerFirstName'],
            'CustomerLastName' => $item['CustomerLastName'],
            'Price' => $item['Price'],
            'ShippingFee' => $item['ShippingFee'],
            'CreatedAt' => $item['CreatedAt'],
          );
          array_push($result['order_data'], $temp_arr);
          $num++;
        }
      }
      
      $result['request_data'][$x] = 'Request #' . $x . ': ' . ($time_end - $time_start) . ' seconds.';
      
      //flag ambil api lagi atau tidak.
      // if(isset($result_from_api[$x]['SuccessResponse']['Head']['TotalCount']) && $result_from_api[$x]['SuccessResponse']['Head']['TotalCount'] > 0){
        // $total_data +=  $result_from_api[$x]['SuccessResponse']['Head']['TotalCount'];
        // $x++;
      // }
      // else{
        // $flag = false;
        // break;
      // }
    }
    
    $result['total_data'] = $total_data;
    $result['api_result'] = $result_from_api;
    
    return $result;
	}
  */
  
}




