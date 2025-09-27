<?php
header('Content-Type: application/json');
session_start();

require_once '../controllers/category_controller.php';

$response = [];

//Step 1: Check if logged in
if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You must be logged in';
    echo json_encode($response);
    exit();
}

//Step 2: Admin only (adjust if customers should also view)
if ($_SESSION['role'] != 1) {
    $response['status'] = 'error';
    $response['message'] = 'Access denied. Admins only.';
    echo json_encode($response);
    exit();
}

//Step 3: Fetch all categories
$categories = get_all_categories_ctr();

if ($categories) {
    $response['status'] = 'success';
    $response['data']   = $categories;
} else {
    $response['status'] = 'error';
    $response['message'] = 'No categories found';
}

echo json_encode($response);
exit();
?>
