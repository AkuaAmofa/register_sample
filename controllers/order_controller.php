<?php
require_once dirname(__DIR__) . '/classes/order_class.php';

function create_order_ctr($customer_id, $invoice_no, $status='Pending') {
  $ord = new order_class();
  return $ord->create_order($customer_id, $invoice_no, $status);
}
function add_order_details_ctr($order_id, $product_id, $qty, $unit_price) {
  $ord = new order_class();
  return $ord->add_order_detail($order_id, $product_id, $qty, $unit_price);
}
function record_payment_ctr($order_id, $customer_id, $amount, $currency='GHS') {
  $ord = new order_class();
  return $ord->record_payment($order_id, $customer_id, $amount, $currency);
}
function get_user_orders_ctr($customer_id) {
  $ord = new order_class();
  return $ord->get_user_orders($customer_id);
}
