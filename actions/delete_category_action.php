<?php
header('Content-Type: application/json');
session_start();

require_once '../controllers/category_controller.php';

$response = [];

// Step 1: Check if logged in
if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You must be logged in';
    echo json_encode($response);
    exit();
}

// Step 2: Ensure admin access
if ($_SESSION['role'] != 1) {
    $response['status'] = 'error';
    $response['message'] = 'Access denied. Admins only.';
    echo json_encode($response);
    exit();
}

// Step 3: Collect category ID
$cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;

if ($cat_id <= 0) {
    $response['status'] = 'error';
    $response['message'] = 'Category ID is required';
    echo json_encode($response);
    exit();
}

// Step 4: Call controller
$result = delete_category_ctr($cat_id);

// Step 5: Return response
if ($result) {
    $response['status'] = 'success';
    $response['message'] = 'Category deleted successfully';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to delete category';
}

echo json_encode($response);
exit();
?>
