<?php
header('Content-Type: application/json');
session_start();

require_once '../controllers/brand_controller.php';

$response = [];

// Step 1: Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    $response['status'] = 'error';
    $response['message'] = 'Unauthorized access.';
    echo json_encode($response);
    exit();
}

// Step 2: Collect input
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;

// Step 3: Validate
if ($brand_id <= 0) {
    $response['status'] = 'error';
    $response['message'] = 'Invalid brand ID.';
    echo json_encode($response);
    exit();
}

// Step 4: Delete brand
$result = delete_brand_ctr($brand_id);

if ($result) {
    $response['status'] = 'success';
    $response['message'] = 'Brand deleted successfully.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to delete brand. Please try again.';
}

// Step 5: Return JSON response
echo json_encode($response);
exit();
?>
