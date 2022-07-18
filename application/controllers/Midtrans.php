<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans extends CI_Controller {
  
  public function __construct(){
    parent::__construct();
    
    //load library
    $this->load->library('midtrans_lib');
    
    //load model..
    $this->load->model('mdl_ads');
    $this->load->model('mdl_ads_order');
    
    //initialize session and language library..
    $default_lang = $this->session->userdata('lang');
    if($default_lang != 'ina' && $default_lang != 'en' && $this->uri->segment(2) != 'changeLang'){
      $this->session->set_userdata(array('lang' => $this->config->item('language')));
    }
    
    set_error_handler(array($this, 'errorFound'));
  }
  
  public function index(){
    redirect(base_url());
  }
  
  //function untuk handle post dari midtrans
  public function notify(){
    $json_result = file_get_contents('php://input');
    $result = json_decode($json_result);
    
    if (!empty($json_result)) {
      $this->mdl_ads_order->insert_notification(
        $result->order_id,
        ['notification' => $json_result, 'status_code' => $result->status_code, 'id_ads_order' => $result->order_id]
      );

      if (isset($result->order_id) && isset($result->status_code)) {
        $ads_order = $this->mdl_ads_order->find($result->order_id);

        if ($ads_order) {
          foreach ($ads_order->items as $item)
          {
            if (in_array($result->transaction_status, ['capture', 'settlement'])) {
              if (!$this->mdl_ads->checkAdsByIDOrderItem($item->id_ads_order_item)) {
                $this->mdl_ads->insertAds([
                  'id_ads_order_item' => $item->id_ads_order_item,
                  'id_user'           => $ads_order->id_user,
                  'id_adstype'        => $item->id_adstype,
                  'ads_source'        => 'builtin',
                  'status'            => -1,
                  'start_date'        => $item->start_date,
                  'finish_date'       => $item->finish_date,
                ]);
              }
            } elseif (in_array($result->transaction_status, ['deny', 'cancel', 'expire', 'failure']) && !is_null($item->id_ads)) {
              $this->mdl_ads->deleteAds($item->id_ads);
            }
          }

          $this->mdl_ads_order->update([
            'payment_status' => $result->transaction_status,
            'payment_date' => $result->transaction_time,
          ], $result->order_id);
        }
      }
    }
  }
  
  //function untuk handle jika transaction finish dari snap..
  //transaksi berhasil, update status donasi
  public function onTransactionFinish(){
    redirect('donate/track');
  }
  
  // function untuk handle jika transaction pending dari snap..
  // (transaksi sudah dibuat / ter-insert tetapi pembayaran belum selesai)
  // tampilkan status & instruksi pembayaran + button pay lagi.
  public function onTransactionPending(){
    redirect('donate/track');
  }
  
  //function untuk handle jika transaction error dari snap..
  public function onTransactionError(){
    redirect('donate/track');
  }
  
  function errorFound(){
    // header("Location:" . base_url());
  }
}
