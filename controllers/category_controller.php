<?php
// Ensure we can access the Category class properly in both local and server environments
require_once dirname(__DIR__) . '/classes/category_class.php';

/**
 * Add a new category
 * @param string $name
 * @return bool
 */
function add_category_ctr($name)
{
    $category = new Category();
    return $category->addCategory($name);
}

/**
 * Update an existing category
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
 * Delete a category
 * @param int $id
 * @return bool
 */
function delete_category_ctr($id)
{
    $category = new Category();
    return $category->deleteCategory($id);
}

/**
 * Retrieve a single category by ID
 * @param int $id
 * @return array|false
 */
function get_category_ctr($id)
{
    $category = new Category();
    return $category->getCategory($id);
}

/**
 * Retrieve all categories
 * @return array|false
 */
function get_all_categories_ctr()
{
    $category = new Category();
    return $category->getAllCategories();
}
?>
