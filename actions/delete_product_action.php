<?php
// actions/delete_product_action.php
header('Content-Type: application/json');
session_start();

require_once '../controllers/product_controller.php';

$response = [];

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($product_id <= 0) {
    $response = [
        'status' => 'error',
        'message' => 'Invalid product ID.'
    ];
    echo json_encode($response);
    exit();
}

// Perform delete
$result = delete_product_ctr($product_id);

if ($result) {
    $response = [
        'status' => 'success',
        'message' => 'Product deleted successfully.'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Failed to delete product. Please try again.'
    ];
}

echo json_encode($response);
exit();
?>

