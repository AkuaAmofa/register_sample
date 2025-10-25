<?php
header('Content-Type: application/json');
session_start();

require_once '../controllers/brand_controller.php';

$response = [];

// Step 1: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Unauthorized access. Please log in.';
    echo json_encode($response);
    exit();
}

// Step 2: Fetch all brands
$brands = get_all_brands_ctr();

if ($brands) {
    $response['status'] = 'success';
    $response['data'] = $brands;
} else {
    $response['status'] = 'error';
    $response['message'] = 'No brands found or database error.';
}

// Step 3: Return JSON
echo json_encode($response);
exit();
?>
