<?php
require_once '../settings/db_class.php';

class Brand extends db_connection
{
    // ADD brand (CREATE)
    public function addBrand($brand_name)
    {
        if ($this->db === null) $this->db_connect();
        $brand_name = mysqli_real_escape_string($this->db, $brand_name);

        // Ensure unique brand name
        $check_sql = "SELECT * FROM brands WHERE brand_name = '$brand_name'";
        $exists = $this->db_fetch_one($check_sql);
        if ($exists) {
            return "exists";
        }

        $sql = "INSERT INTO brands (brand_name) VALUES ('$brand_name')";
        return $this->db_write_query($sql);
    }

    // GET all brands (READ)
    public function getAllBrands()
    {
        $sql = "SELECT * FROM brands ORDER BY brand_name ASC";
        return $this->db_fetch_all($sql);
    }

    // UPDATE brand
    public function updateBrand($brand_id, $brand_name)
    {
        if ($this->db === null) $this->db_connect();
        $brand_id = (int)$brand_id;
        $brand_name = mysqli_real_escape_string($this->db, $brand_name);

        $sql = "UPDATE brands SET brand_name='$brand_name' WHERE brand_id=$brand_id";
        return $this->db_write_query($sql);
    }

    // DELETE brand
    public function deleteBrand($brand_id)
    {
        if ($this->db === null) $this->db_connect();
        $brand_id = (int)$brand_id;

        $sql = "DELETE FROM brands WHERE brand_id=$brand_id";
        return $this->db_write_query($sql);
    }

    // GET single brand by ID
    public function getBrandById($brand_id)
    {
        $brand_id = (int)$brand_id;
        $sql = "SELECT * FROM brands WHERE brand_id=$brand_id";
        return $this->db_fetch_one($sql);
    }
}
?>
