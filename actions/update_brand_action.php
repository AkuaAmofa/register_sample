<?php
// update_brand_action.php
header('Content-Type: application/json');
session_start();

require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

// Step 1: Access control (Admins only)
if (!isAdmin()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Access denied. Only admins can update brands.'
    ]);
    exit();
}

// Step 2: Collect input
$id   = isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : 0;
$name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';

// Step 3: Validate
if ($id <= 0 || $name === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid brand ID or brand name.'
    ]);
    exit();
}

// Step 4: Attempt update
$result = update_brand_ctr($id, $name);

// Step 5: Respond
if ($result) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Brand updated successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update brand. The name may already exist in this category.'
    ]);
}
exit();
?>
