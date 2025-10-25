<?php
header('Content-Type: application/json');
session_start();

require_once '../controllers/brand_controller.php';

$response = [];

// Step 1: Check login & admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    $response['status'] = 'error';
    $response['message'] = 'Unauthorized access.';
    echo json_encode($response);
    exit();
}

// Step 2: Collect input
$brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';

// Step 3: Validate
if (empty($brand_name)) {
    $response['status'] = 'error';
    $response['message'] = 'Brand name is required.';
    echo json_encode($response);
    exit();
}

// Step 4: Add brand using controller
$result = add_brand_ctr($brand_name);

if ($result === "exists") {
    $response['status'] = 'error';
    $response['message'] = 'Brand name already exists.';
} elseif ($result) {
    $response['status'] = 'success';
    $response['message'] = 'Brand added successfully.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to add brand. Try again.';
}

// Step 5: Send JSON response
echo json_encode($response);
exit();
?>
