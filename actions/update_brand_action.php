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
$brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';

// Step 3: Validate input
if ($brand_id <= 0 || empty($brand_name)) {
    $response['status'] = 'error';
    $response['message'] = 'Both brand ID and name are required.';
    echo json_encode($response);
    exit();
}

// Step 4: Update brand
$result = update_brand_ctr($brand_id, $brand_name);

if ($result) {
    $response['status'] = 'success';
    $response['message'] = 'Brand updated successfully.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to update brand. Please try again.';
}

// Step 5: Return JSON response
echo json_encode($response);
exit();
?>
