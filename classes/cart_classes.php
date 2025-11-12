<?php
// classes/cart_class.php
require_once dirname(__DIR__) . '/settings/db_class.php';

class cart_class extends db_connection {

    /** Insert or increase qty for a product */
    public function add_or_increment(int $p_id, int $qty, ?int $c_id, ?string $ip): bool {
        $conn = $this->db_conn();
        if (!$conn) return false;

        // Decide identification fields
        $where = $c_id ? 'c_id = ? AND p_id = ?' : 'ip_add = ? AND p_id = ?';
        $sel = $conn->prepare("SELECT cart_id, qty FROM cart WHERE $where LIMIT 1");
        if ($c_id) $sel->bind_param('ii', $c_id, $p_id);
        else       $sel->bind_param('si', $ip, $p_id);
        $sel->execute();
        $res = $sel->get_result();
        $row = $res->fetch_assoc();
        $sel->close();

        if ($row) {
            $newQty = max(1, (int)$row['qty'] + max(1, $qty));
            $upd = $conn->prepare("UPDATE cart SET qty = ? WHERE cart_id = ?");
            $upd->bind_param('ii', $newQty, $row['cart_id']);
            $ok = $upd->execute();
            $upd->close();
            return $ok;
        }

        // Insert fresh row
        if ($c_id) {
            $ins = $conn->prepare("INSERT INTO cart (p_id, c_id, qty) VALUES (?, ?, ?)");
            $ins->bind_param('iii', $p_id, $c_id, $qty);
        } else {
            $ins = $conn->prepare("INSERT INTO cart (p_id, ip_add, qty) VALUES (?, ?, ?)");
            $ins->bind_param('isi', $p_id, $ip, $qty);
        }
        $ok = $ins->execute();
        $ins->close();
        return $ok;
    }

    /** Update a specific cart line’s qty by cart_id */
    public function update_qty(int $cart_id, int $qty): bool {
        $conn = $this->db_conn();
        if (!$conn) return false;
        $qty = max(1, $qty);
        $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE cart_id = ?");
        $stmt->bind_param('ii', $qty, $cart_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /** Remove a cart line by cart_id */
    public function remove_item(int $cart_id): bool {
        $conn = $this->db_conn();
        if (!$conn) return false;
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
        $stmt->bind_param('i', $cart_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /** Clear all rows for current user or IP */
    public function clear_cart(?int $c_id, ?string $ip): bool {
        $conn = $this->db_conn();
        if (!$conn) return false;
        if ($c_id) {
            $stmt = $conn->prepare("DELETE FROM cart WHERE c_id = ?");
            $stmt->bind_param('i', $c_id);
        } else {
            $stmt = $conn->prepare("DELETE FROM cart WHERE ip_add = ?");
            $stmt->bind_param('s', $ip);
        }
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /** Return all cart lines joined with products for display */
    public function get_items(?int $c_id, ?string $ip): array {
        $conn = $this->db_conn();
        if (!$conn) return [];

        $sql = "SELECT 
                    cart.cart_id, cart.p_id, cart.qty,
                    p.product_title, p.product_price, p.product_image
                FROM cart
                JOIN products p ON p.product_id = cart.p_id
                WHERE %s
                ORDER BY cart.cart_id DESC";

        if ($c_id) {
            $sql = sprintf($sql, 'cart.c_id = ?');
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $c_id);
        } else {
            $sql = sprintf($sql, 'cart.ip_add = ?');
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $ip);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data ?: [];
    }
}
