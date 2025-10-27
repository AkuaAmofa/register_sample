<?php
// classes/category_class.php
require_once dirname(__DIR__) . '/settings/db_class.php';


class Category extends db_connection
{
    /**
     * Add new category
     * @param string $name
     * @return bool
     */
    public function addCategory($name)
    {
        if ($this->db === null) {
            $this->db_connect();
        }

        $name = mysqli_real_escape_string($this->db, $name);

        // Ensure category name is unique
        $checkSql = "SELECT * FROM categories WHERE cat_name = '$name' LIMIT 1";
        $exists = $this->db_fetch_one($checkSql);

        if ($exists) {
            return false; // Category already exists
        }

        $sql = "INSERT INTO categories (cat_name) VALUES ('$name')";
        return $this->db_write_query($sql);
    }

    /**
     * Update category by ID
     * @param int $id
     * @param string $name
     * @return bool
     */
    public function updateCategory($id, $name)
    {
        if ($this->db === null) {
            $this->db_connect();
        }

        $id   = (int)$id;
        $name = mysqli_real_escape_string($this->db, $name);

        $sql = "UPDATE categories SET cat_name = '$name' WHERE cat_id = $id";
        return $this->db_write_query($sql);
    }

    /**
     * Delete category by ID
     * @param int $id
     * @return bool
     */
    public function deleteCategory($id)
    {
        if ($this->db === null) {
            $this->db_connect();
        }

        $id  = (int)$id;
        $sql = "DELETE FROM categories WHERE cat_id = $id";
        return $this->db_write_query($sql);
    }

    /**
     * Get single category by ID
     * @param int $id
     * @return array|false
     */
    public function getCategory($id)
    {
        if ($this->db === null) {
            $this->db_connect();
        }

        $id  = (int)$id;
        $sql = "SELECT * FROM categories WHERE cat_id = $id LIMIT 1";
        return $this->db_fetch_one($sql);
    }

    /**
     * Get all categories
     * @return array|false
     */
    public function getAllCategories()
    {
        if ($this->db === null) {
            $this->db_connect();
        }

        $sql = "SELECT * FROM categories ORDER BY cat_id DESC";
        return $this->db_fetch_all($sql);
    }
}
?>
