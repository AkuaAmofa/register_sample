<?php
// controllers/cart_controller.php
require_once dirname(__DIR__) . '/classes/cart_classes.php';

function cart_add_ctr(int $p_id, int $qty, ?int $c_id, ?string $ip) {
    $c = new cart_class();
    return $c->add_or_increment($p_id, $qty, $c_id, $ip);
}
function cart_update_qty_ctr(int $cart_id, int $qty) {
    $c = new cart_class();
    return $c->update_qty($cart_id, $qty);
}
function cart_remove_ctr(int $cart_id) {
    $c = new cart_class();
    return $c->remove_item($cart_id);
}
function cart_clear_ctr(?int $c_id, ?string $ip) {
    $c = new cart_class();
    return $c->clear_cart($c_id, $ip);
}
function cart_items_ctr(?int $c_id, ?string $ip): array {
    $c = new cart_class();
    return $c->get_items($c_id, $ip);
}
