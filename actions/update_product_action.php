<?php
// actions/update_product_action.php
header('Content-Type: application/json');
session_start();

require_once '../controllers/product_controller.php';

// Response array
$response = [];

// Collect and sanitize inputs
$product_id   = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$product_cat  = isset($_POST['product_cat']) ? intval($_POST['product_cat']) : 0;
$product_brand= isset($_POST['product_brand']) ? intval($_POST['product_brand']) : 0;
$title        = isset($_POST['product_title']) ? trim($_POST['product_title']) : '';
$price        = isset($_POST['product_price']) ? floatval($_POST['product_price']) : 0.0;
$desc         = isset($_POST['product_desc']) ? trim($_POST['product_desc']) : '';
$keywords     = isset($_POST['product_keywords']) ? trim($_POST['product_keywords']) : '';
$image        = $_POST['product_image'] ?? ''; // from upload or existing filename

// Basic validation
if ($product_id <= 0 || empty($title) || $price <= 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Product ID, title, and price are required.'
    ]);
    exit();
}

// Handle direct image upload (optional)
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "/home/akua.amofa/public_html/uploads/";
    $originalName = basename($_FILES['product_image']['name']);
    $uniqueName = "prod_" . uniqid() . "_" . $originalName;
    $targetPath = $upload_dir . $uniqueName;

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetPath)) {
        $image = $uniqueName; // store only filename
    } else {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Image upload failed during update.'
        ]);
        exit();
    }
}

// Run update query via controller
$result = update_product_ctr($product_id, $product_cat, $product_brand, $title, $price, $desc, $image, $keywords);

if ($result) {
    echo json_encode([
        'status'  => 'success',
        'message' => 'Product updated successfully!'
    ]);
} else {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to update product. Please try again.'
    ]);
}
exit();
?>
