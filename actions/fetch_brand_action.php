<?php
header('Content-Type: application/json');
session_start();
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';
require_once '../controllers/category_controller.php'; // to fetch categories

if (!isAdmin()) {
    echo json_encode(['status'=>'error','message'=>'Access denied']);
    exit;
}

/**
 * Return both:
 *  - brands (flat list with brand_cat & cat_name)
 *  - categories (id+name) to populate the dropdown
 */
$brands = get_all_brands_ctr();
$cats   = get_all_categories_ctr();

echo json_encode([
    'status' => 'success',
    'data'   => [
        'brands'     => $brands ?: [],
        'categories' => $cats   ?: []
    ]
]);
