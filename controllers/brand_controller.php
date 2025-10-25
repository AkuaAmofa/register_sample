<?php
require_once '../classes/brand_class.php';

/**
 * ADD a brand
 */
function add_brand_ctr($brand_name)
{
    try {
        $brand = new Brand();
        $result = $brand->addBrand($brand_name);
        return $result;
    } catch (Exception $e) {
        error_log("Controller error in add_brand_ctr: " . $e->getMessage());
        return false;
    }
}

/**
 * GET all brands
 */
function get_all_brands_ctr()
{
    try {
        $brand = new Brand();
        return $brand->getAllBrands();
    } catch (Exception $e) {
        error_log("Controller error in get_all_brands_ctr: " . $e->getMessage());
        return false;
    }
}

/**
 * UPDATE a brand
 */
function update_brand_ctr($brand_id, $brand_name)
{
    try {
        $brand = new Brand();
        return $brand->updateBrand($brand_id, $brand_name);
    } catch (Exception $e) {
        error_log("Controller error in update_brand_ctr: " . $e->getMessage());
        return false;
    }
}

/**
 * DELETE a brand
 */
function delete_brand_ctr($brand_id)
{
    try {
        $brand = new Brand();
        return $brand->deleteBrand($brand_id);
    } catch (Exception $e) {
        error_log("Controller error in delete_brand_ctr: " . $e->getMessage());
        return false;
    }
}

/**
 * GET single brand by ID
 */
function get_brand_ctr($brand_id)
{
    try {
        $brand = new Brand();
        return $brand->getBrandById($brand_id);
    } catch (Exception $e) {
        error_log("Controller error in get_brand_ctr: " . $e->getMessage());
        return false;
    }
}
?>
