<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_ads_order
 *
 * @property Midtrans_lib $midtrans_lib
 * @property Mdl_user $mdl_user
 * @property Mdl_ads_order $mdl_ads_order
 * @property Mdl_ads_voucher $mdl_ads_voucher
 */
class User_ads_order extends CI_Controller {
  private $base_url;
  private $data;
  private $id_user;

  public $css = [];
  public $js = [];

  public function __construct()
  {
    parent::__construct();
    $this->base_url = base_url() . $this->uri->segment(1);
    $this->id_user = $this->session->userdata('id_user');
    $this->data = [];

    $this->load->model('mdl_ads_order');

    if ($this->session->userdata('user_logged_in') !== true)
    {
      $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())) , "=");
      redirect("page/login/{$redirect_url}");
    }
  }

  public function index()
  {
    $pagination_config = $this->global_lib->paginationConfig();
    $pagination_config['base_url'] = "{$this->base_url}/index/";
    $pagination_config['total_rows'] = $this->mdl_ads_order->count($this->id_user);
    $pagination_config['per_page'] = 20;
    $pagination_config['uri_segment'] = 3;

    $this->pagination->initialize($pagination_config);

    $this->data['offset'] = $this->uri->segment($pagination_config['uri_segment']) ?: 0;
    $this->data['orders'] = $this->mdl_ads_order
                                 ->order_by_checkout_date()
                                 ->all($pagination_config['per_page'], $this->data['offset'], $this->id_user);

    $this->_render('user/ads/order/all');
  }

  public function view($id)
  {
    $this->load->library('midtrans_lib');

    $this->data['ads_order'] = $this->mdl_ads_order->find($id) ?: redirect($this->uri->segment(1));
    $this->data['subtotal'] = array_sum(array_column($this->data['ads_order']->items, 'subtotal'));

    $this->_render('user/ads/order/view');
  }

  public function invoice($id)
  {
    $this->data['ads_order'] = $this->mdl_ads_order->with_user_name()->find($id) ?: redirect($this->uri->segment(1));
    $this->data['subtotal'] = array_sum(array_column($this->data['ads_order']->items, 'subtotal'));
    $this->data['global_data'] = $this->global_lib->getGlobalData();

    $this->_render('user/ads/order/invoice');
  }

  public function cart()
  {
    $this->load->library('midtrans_lib');
    $this->load->model('mdl_adstype');

    $this->data['ad_cart'] = $this->mdl_ads_order->get_cart($this->id_user);
    $this->data['ad_cart']->has_booked_item = FALSE;

    if (empty($this->data['ad_cart']->items))
    {
      redirect("{$this->uri->segment(1)}/add_position");
    }

    foreach ($this->data['ad_cart']->items as $item)
    {
      if ($item->is_booked)
      {
        $this->data['ad_cart']->has_booked_item = TRUE;

        if (!$this->mdl_adstype->has_been_used($item->id_adstype, $item->start_date, $item->finish_date))
        {
          $this->mdl_ads_order->update_item(['is_booked' => FALSE], $item->id_ads_order_item);

          $item->is_booked = FALSE;
          $this->data['ad_cart']->has_booked_item = FALSE;
        }
      }
    }

    $this->css[] = link_tag(base_url() . 'files/backend/css/loader.min.css');

    $this->_render('user/ads/order/cart', $this->data);
  }

  public function payment_token()
  {
    if (!$this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') !== 'POST')
    {
      redirect("{$this->base_url}/cart");
    }

    $this->load->model('mdl_user');
    $this->load->model('mdl_adstype');
    $this->load->model('mdl_ads_order');
    $this->load->model('mdl_ads_voucher');

    $user = $this->mdl_user->getUserByID($this->id_user);

    if (!(is_array($user) && count($user) > 0))
    {
      redirect("{$this->base_url}/cart");
    }

    $data = [
      'status' => '',
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_token_hash' => $this->security->get_csrf_hash(),
    ];
    $cart = $this->mdl_ads_order->get_cart($this->id_user);
    $ads_voucher = $cart->id_ads_voucher ? $this->mdl_ads_voucher->active()->find($cart->id_ads_voucher) : NULL;
    $subtotal = array_sum(array_column($cart->items, 'subtotal'));
    $total = $cart->id_ads_voucher ? $subtotal - $cart->voucher_value : $subtotal;
    $customer_details = $this->_get_customer_detail($user[0]);
    $is_booked = false;
    $booked_items = [];

    if (!empty($cart->items))
    {
      $this->load->library('midtrans_lib');

      $transaction = [
        'transaction_details' => [
          'order_id' => "{$cart->id_ads_order}-" . date('YmdHis'),
          'gross_amount' => $total,
        ],
        'customer_details' => $customer_details,
        'item_details' => [],
      ];

      foreach ($cart->items as $item)
      {
        if ($this->mdl_adstype->has_been_used($item->id_adstype, $item->start_date, $item->finish_date))
        {
          $is_booked = true;
          $booked_items[] = $item->id_ads_order_item;
          $this->mdl_ads_order->update_item(['is_booked' => TRUE], $item->id_ads_order_item);
        }

        $transaction['item_details'][] = [
          'id' => 'ads-order-item-' . $item->id_ads_order_item,
          'name' => date('d-m-y', strtotime($item->start_date)) . ' - ' . date('d-m-y', strtotime($item->finish_date)) . ", {$item->ads_name}" ,
          'category' => $item->ads_name,
          'quantity' => $item->total_days,
          'price' => $item->price_per_day,
        ];
      }

      if (!$is_booked)
      {
        if ($cart->id_ads_voucher) {
          if ($ads_voucher && !$this->mdl_ads_order->is_used_voucher($ads_voucher->id_ads_voucher)) {
            $transaction['item_details'][] = [
              'id' => 'ads-voucher-' . $cart->id_ads_voucher,
              'name' => "Voucher {$cart->voucher_code}",
              'quantity' => 1,
              'price' => -$cart->voucher_value,
            ];

            try
            {
              $data['status'] = 'success';
              $data['token'] = $this->midtrans_lib->getSnapToken($transaction);
            }
            catch (Exception $exception)
            {
              $data['status'] = 'failed';
              $data['message'] = 'Pembayaran tidak dapat dilakukan';
              log_message('error', "Payment Error: {$exception->getMessage()}");
            }
          } else {
            $data['status'] = 'invalid_voucher';
            $data['message'] = 'Kode voucher tidak valid , silahkan batalkan voucher terlebih dahulu.';
            $data['booked_items'] = $booked_items;
          }
        } else {
          try
          {
            $data['status'] = 'success';
            $data['token'] = $this->midtrans_lib->getSnapToken($transaction);
          }
          catch (Exception $exception)
          {
            $data['status'] = 'failed';
            $data['message'] = 'Pembayaran tidak dapat dilakukan';
            log_message('error', "Payment Error: {$exception->getMessage()}");
          }
        }
      }
      else
      {
        $data['status'] = 'booked';
        $data['message'] = 'Posisi iklan telah terpesan, silahkan hapus terlebih dahulu posisi iklan yang telah terpesan.';
        $data['booked_items'] = $booked_items;
      }
    }
    else
    {
      $data['status'] = 'failed';
      $data['message'] = 'Belum terdapat posisi iklan';
    }

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($data));
  }

  public function update_cart_payment()
  {
    $this->load->model('mdl_ads');
    $this->load->model('mdl_adstype');
    $this->load->model('mdl_ads_voucher');

    $transaction_status = $this->input->get('transaction_status');
    $cart = $this->mdl_ads_order->get_cart($this->id_user, false);

    if (is_null($cart) || empty($cart->items))
    {
      redirect("{$this->base_url}/cart");
    }

    $this->db->trans_start();

    foreach ($cart->items as $item)
    {
      if (in_array($transaction_status, ['capture', 'settlement'])) {
        if (!$this->mdl_ads->checkAdsByIDOrderItem($item->id_ads_order_item)) {
          $this->mdl_ads->insertAds([
            'id_ads_order_item' => $item->id_ads_order_item,
            'id_user'           => $this->id_user,
            'id_adstype'        => $item->id_adstype,
            'ads_source'        => 'builtin',
            'status'            => -1,
            'start_date'        => $item->start_date,
            'finish_date'       => $item->finish_date,
          ]);
        }
      }

      $this->mdl_ads_order->update_item(['price_per_day' => $item->price_per_day], $item->id_ads_order_item);
    }

    $order_data = [
      'order_status' => 1,
      'payment_status' => $transaction_status,
      'checkout_date' => date('Y-m-d H:i:s'),
    ];

    if ($cart->id_ads_voucher) {
      $voucher = $this->mdl_ads_voucher->find($cart->id_ads_voucher);

      $order_data['voucher_code'] = $voucher->code;
      $order_data['voucher_value'] = $voucher->value;
    }

    $this->mdl_ads_order->update($order_data, $cart->id_ads_order);

    $this->db->trans_complete();

    $message = 'Pembayaran transaksi anda telah berhasil.';

    if ($transaction_status === 'pending') {
      $message = 'Jika anda telah menyelesaikan pembayaran, anda dapat mengatur iklan anda di halaman ini.';
    }

    $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));
    redirect("user_ads");
  }

  public function apply_voucher()
  {
    $this->form_validation->set_rules('code', '', "htmlentities|strip_tags|trim|xss_clean|required");

    if ($this->form_validation->run()) {
      $this->load->model('mdl_ads_voucher');

      $id_ads_order = $this->mdl_ads_order->get_cart_id($this->id_user);
      $code = $this->input->post('code');
      $ads_voucher = $this->mdl_ads_voucher->active()->find_by_code($code);

      if ($ads_voucher && !$this->mdl_ads_order->is_used_voucher($ads_voucher->id_ads_voucher))
      {
        $this->mdl_ads_order->update(['id_ads_voucher' => $ads_voucher->id_ads_voucher], $id_ads_order);
      }
      else
      {
        $this->session->set_flashdata(
          'voucher_message',
          $this->global_lib->generateMessage('Kode Voucher tidak valid.', "danger")
        );
      }
    }
    else
    {
      $this->session->set_flashdata(
        'voucher_message',
        $this->global_lib->generateMessage('Kode Voucher harus diisi.', "danger")
      );
    }

    redirect("{$this->base_url}/cart#voucher");
  }

  public function remove_voucher()
  {
    $this->load->model('mdl_ads_voucher');

    $id_ads_order = $this->mdl_ads_order->get_cart_id($this->id_user);

    $this->mdl_ads_order->update(['id_ads_voucher' => NULL], $id_ads_order);

    redirect("{$this->base_url}/cart#voucher");
  }

  public function add_position()
  {
    $this->data['heading_text'] = 'Tambah';
    $this->data['submit_url'] = "{$this->base_url}/save_add_position";
    $this->data['form_value'] = $this->session->flashdata('form_value') ?: [
      'id_adstype' => '',
      'ads_name' => '',
      'start_date' => '',
      'finish_date' => '',
      'price_per_day' => '',
      'subtotal' => '',
    ];

    $this->_render('user/ads/order/form', $this->data);
  }

  public function save_add_position()
  {
    $this->session->set_flashdata('form_value', $this->input->post());
    $this->_set_form_validation_rules();

    if ($this->form_validation->run())
    {
      $id_ads_order = $this->mdl_ads_order->get_cart_id($this->id_user);
      $data = $this->_get_input_data();

      $this->_has_position_been_used($id_ads_order, $data, 'add_position');

      $this->mdl_ads_order->insert_item($id_ads_order, $data);

      $message = 'Posisi iklan berhasil ditambahkan.';
      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata('message', $this->global_lib->generateMessage($message, 'info'));

      redirect("{$this->uri->segment(1)}/cart");
    }
    else
    {
      $this->session->set_flashdata(
        'message',
        $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
      );

      redirect("{$this->uri->segment(1)}/add_position");
    }
  }

  public function edit_position($id)
  {
    $this->data['item'] = $this->mdl_ads_order->find_item($id) ?: redirect($this->uri->segment(1));

    if ($this->data['item']->is_booked)
    {
      redirect("{$this->uri->segment(1)}/cart");
    }

    $this->data['item']->start_date = date_create($this->data['item']->start_date)->format('d-m-Y');
    $this->data['item']->finish_date = date_create($this->data['item']->finish_date)->format('d-m-Y');
    $this->data['heading_text'] = 'Edit';
    $this->data['submit_url'] = "{$this->base_url}/save_edit_position/{$id}";
    $this->data['form_value'] = $this->session->flashdata('form_value') ?: (array) $this->data['item'];

    $this->_render('user/ads/order/form', $this->data);
  }

  public function save_edit_position($id)
  {
    $order_item = $this->mdl_ads_order->find_item($id);

    if (is_null($order_item))
    {
      redirect("{$this->uri->segment(1)}/cart");
    }

    $this->session->set_flashdata('form_value', $this->input->post());
    $this->_set_form_validation_rules();

    if ($this->form_validation->run())
    {
      $data = $this->_get_input_data();

      $this->_has_position_been_used($order_item->id_ads_order, $data, 'edit_position', $id);

      $this->mdl_ads_order->update_item($data, $id);

      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata('message', $this->global_lib->generateMessage('Posisi Iklan berhasil diubah.', 'info'));

      redirect("{$this->uri->segment(1)}/cart");
    }
    else
    {
      $this->session->set_flashdata(
        'message',
        $this->global_lib->generateMessage("Form validation invalid. Please check your form.", "danger")
      );

      redirect("{$this->uri->segment(1)}/edit_position/{$id}");
    }
  }

  public function delete_position($id)
  {
    if ($this->mdl_ads_order->count_item($id) > 0)
    {
      $this->mdl_ads_order->delete_item($id);

      $this->session->set_flashdata(
        'message',
        $this->global_lib->generateMessage('Posisi iklan berhasil dihapus.', 'info')
      );
    }

    redirect("{$this->uri->segment(1)}/cart");
  }

  public function positions()
  {
    if (!$this->input->is_ajax_request())
    {
      redirect(base_url() . 'user_ads');
    }

    $data = ['results' => []];
    $start_date = !empty($this->input->get('start_date')) ? date_create($this->input->get('start_date')) : false;
    $finish_date = !empty($this->input->get('finish_date')) ? date_create($this->input->get('finish_date')) : false;

    if ($start_date !== false && $finish_date !== false)
    {
      $id_ads_order = $this->mdl_ads_order->get_cart_id($this->id_user);
      $start_date = $start_date->format('Y-m-d');
      $finish_date = $finish_date->format('Y-m-d');

      $data['results'] = $this->mdl_ads_order->get_free_positions_select2(
        $id_ads_order,
        $start_date,
        $finish_date,
        $this->input->get('q'),
        $this->input->get('except_id'),
      );

      foreach ($data['results'] as $result) {
        $result->location_pic = $result->location_pic ? base_url("assets/adstype/{$result->location_pic}") : null;
      }
    }
    else
    {
      $data['results'] = [['id' => 0, 'text' => 'Pilih tanggal tayang terlebih dahulu', 'disabled' => TRUE]];
    }

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($data));
  }

  /**
   * Get customer detail for Midtrans payment
   *
   * 30 character limit for name is instructed in: https://api-docs.midtrans.com/#customer-details-object
   *
   * @return array
   */
  private function _get_customer_detail($user)
  {
    $user_names = explode(' ', $user->name, 2);
    $data = [
      'first_name' => substr($user_names[0], 0, 30),
    ];

    if (isset($user_names[1])) {
      $data['last_name'] = substr($user_names[1], 0, 30);
    }

    if ($user->contact_number) {
      $data['phone'] = $user->contact_number;
    }

    $data['billing_address'] = $data;
    $data['email'] = $user->email;

    return $data;
  }

  private function _has_position_been_used($id_ads_order, $data, $fail_redirect_url, $except_id = NULL)
  {
    $this->load->model('mdl_adstype');

    $hasBeenUsed = $this->mdl_adstype->has_been_used($data['id_adstype'], $data['start_date'], $data['finish_date'], $except_id);

    if ($hasBeenUsed)
    {
      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata(
        'message',
        $this->global_lib->generateMessage('Posisi iklan sudah digunakan, silahkan posisi iklan yang lain', 'danger')
      );

      redirect("{$this->uri->segment(1)}/{$fail_redirect_url}");
    }

    $hasBeenOrdered = $this->mdl_ads_order->has_been_used($data['id_adstype'], $data['start_date'], $data['finish_date'], $id_ads_order, $except_id);

    if ($hasBeenOrdered)
    {
      $this->session->set_flashdata('form_value', NULL);
      $this->session->set_flashdata(
        'message',
        $this->global_lib->generateMessage('Posisi iklan sudah dipesan, silahkan posisi iklan yang lain', 'danger')
      );

      redirect("{$this->uri->segment(1)}/{$fail_redirect_url}");
    }

    return $hasBeenUsed || $hasBeenOrdered;
  }

  private function _set_form_validation_rules()
  {
    $base_rule = 'htmlentities|strip_tags|trim|xss_clean';

    $this->form_validation->set_rules('start_date', '', "{$base_rule}|required|callback__validateDate[d-m-Y]");
    $this->form_validation->set_rules('finish_date', '', "{$base_rule}|required|callback__validateDate[d-m-Y]");
    $this->form_validation->set_rules('id_adstype', '', "{$base_rule}|required|integer");
  }

  public function _validateDate($date_text, $format)
  {
    $is_valid_date = $this->global_lib->validateDate($date_text, $format);

    if (!$is_valid_date)
    {
      $this->form_validation->set_message('_validateDate', 'The format of {field} must be ' . strtoupper($format));
    }

    return $is_valid_date;
  }

  private function _get_input_data()
  {
    return [
      'start_date'   => date_create($this->input->post('start_date'))->format('Y-m-d'),
      'finish_date'   => date_create($this->input->post('finish_date'))->format('Y-m-d'),
      'id_adstype' => $this->input->post('id_adstype'),
    ];
  }

  private function _render($view_path)
  {
    $this->data['base_url'] = $this->base_url;
    $page = $this->load->view($view_path, $this->data, TRUE);

    $this->load->view('/user/template', [
      'content' => $page,
      'type' => $page,
      'css_files' => $this->css,
      'js_files' => $this->js,
      'global_data' => $this->global_lib->getGlobalData(),
    ]);
  }

}
