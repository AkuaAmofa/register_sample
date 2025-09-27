<?php
require_once '../classes/category_class.php';


/**
 * Add new category
 * @param string $name
 * @return bool
 */
function add_category_ctr($name)
{
    $category = new Category();
    return $category->addCategory($name);
}

/**
 * Update category
 * @param int $id
 * @param string $name
 * @return bool
 */
function update_category_ctr($id, $name)
{
    $category = new Category();
    return $category->updateCategory($id, $name);
}

/**
 * Delete category
 * @param int $id
 * @return bool
 */
function delete_category_ctr($id)
{
    $category = new Category();
    return $category->deleteCategory($id);
}

/**
 * Get single category
 * @param int $id
 * @return array|false
 */
function get_category_ctr($id)
{
    $category = new Category();
    return $category->getCategory($id);
}

/**
 * Get all categories
 * @return array|false
 */
function get_all_categories_ctr()
{
    $category = new Category();
    return $category->getAllCategories();
}
?>
