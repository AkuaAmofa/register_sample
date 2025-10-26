<?php
// delete_brand_action.php
header('Content-Type: application/json');
session_start();

require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

// Step 1: Access control (Admins only)
if (!isAdmin()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Access denied. Only admins can delete brands.'
    ]);
    exit();
}

// Step 2: Collect input
$id = isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : 0;

// Step 3: Validate input
if ($id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid brand ID.'
    ]);
    exit();
}

// Step 4: Delete operation
$result = delete_brand_ctr($id);

// Step 5: Respond
if ($result) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Brand deleted successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to delete brand. It may not exist or a server error occurred.'
    ]);
}
exit();
?>
