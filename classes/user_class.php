<?php

require_once '../settings/db_class.php';

/**
 * User class handles customer-related operations
 */
class User extends db_connection
{
    private $user_id;
    private $name;
    private $email;
    private $password;
    private $role;
    private $date_created;
    private $phone_number;

    public function __construct($user_id = null)
    {
        parent::db_connect();
        if ($user_id) {
            $this->user_id = $user_id;
            $this->loadUser();
        }
    }

    private function loadUser($user_id = null)
    {
        if ($user_id) {
            $this->user_id = $user_id;
        }
        if (!$this->user_id) {
            return false;
        }

        $sql = "SELECT * FROM customer WHERE customer_id = '$this->user_id'";
        $result = $this->db_fetch_one($sql);

        if ($result) {
            $this->name = $result['customer_name'];
            $this->email = $result['customer_email'];
            $this->password = $result['customer_pass'];
            $this->role = $result['user_role'];
            $this->phone_number = $result['customer_contact'];
            return true;
        }
        return false;
    }

    /**
     * Register a new customer
     */
    public function register($name, $email, $password, $country, $city, $contact, $role = 2, $image = null)
    {
        // hash password before saving
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO customer 
                (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, customer_image, user_role)
                VALUES 
                ('$name', '$email', '$hashed_pass', '$country', '$city', '$contact', " . 
                (is_null($image) ? "NULL" : "'$image'") . ", '$role')";

        return $this->db_write_query($sql);
    }

    /**
     * Check if an email already exists
     */
    public function checkEmail($email)
    {
        $sql = "SELECT * FROM customer WHERE customer_email = '$email'";
        return $this->db_fetch_one($sql);  // returns record if found, false if not
    }

    /**
     * Get user by ID
     */
    public function getUser($id)
    {
        $sql = "SELECT * FROM customer WHERE customer_id = '$id'";
        return $this->db_fetch_one($sql);
    }

    /**
     * Get all users
     */
    public function getAllUsers()
    {
        $sql = "SELECT * FROM customer";
        return $this->db_fetch_all($sql);
    }

    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        $sql = "DELETE FROM customer WHERE customer_id = '$id'";
        return $this->db_write_query($sql);
    }

    /**
     *Get user by email (for login)
     */
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM customer WHERE customer_email = '$email'";
        return $this->db_fetch_one($sql);
    }
}
?>
