<?php
// classes/order_class.php
require_once dirname(__DIR__) . '/settings/db_class.php';

class order_class extends db_connection {

    /** Create order → returns new order_id or null */
    public function create_order(?int $customer_id, string $invoice_no, string $status = 'Pending') {
        $conn = $this->db_conn();
        if (!$conn) return null;

        $sql = "INSERT INTO orders (customer_id, invoice_no, order_date, order_status)
                VALUES (?, ?, NOW(), ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param('iss', $customer_id, $invoice_no, $status);
        if (!$stmt->execute()) { $stmt->close(); return null; }

        $newId = $conn->insert_id;
        $stmt->close();
        return $newId ?: null;
    }

    /** Add line to orderdetails */
    public function add_order_detail(int $order_id, int $product_id, int $qty, float $unit_price): bool {
        $conn = $this->db_conn();
        if (!$conn) return false;

        // Adjust table/column names here if yours differ
        $sql = "INSERT INTO orderdetails (order_id, product_id, qty, unit_price)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('iiid', $order_id, $product_id, $qty, $unit_price);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /** Record (simulated) payment */
    public function record_payment($order_id, $customer_id, $amount, $currency = 'GHS') {
        // match your table: payment(pay_id, amt, customer_id, order_id, currency, payment_date)
        $order_id    = (int)$order_id;
        $customer_id = $customer_id ? (int)$customer_id : 'NULL';
        $amount      = (float)$amount;
        $currency    = mysqli_real_escape_string($this->db_conn(), $currency);

        $sql = "
            INSERT INTO payment (order_id, customer_id, amt, currency, payment_date)
            VALUES (
                {$order_id},
                {$customer_id},
                {$amount},
                '{$currency}',
                NOW()
            )
        ";

        return $this->db_query($sql);
    }

    /** List user’s orders */
    public function get_user_orders(int $customer_id): array {
        $conn = $this->db_conn();
        if (!$conn) return [];

        $sql = "SELECT order_id, invoice_no, order_date, order_status
                FROM orders
                WHERE customer_id = ?
                ORDER BY order_date DESC";

        $stmt = $conn->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param('i', $customer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows ?: [];
    }
}
