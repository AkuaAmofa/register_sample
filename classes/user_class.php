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

        if ($result && isset($result['customer_id'])) {
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
        try {
            // Hash password before saving
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

            if ($this->db === null) {
                $this->db_connect();
            }

            // Escape strings to prevent SQL injection
            $name    = mysqli_real_escape_string($this->db, $name);
            $email   = mysqli_real_escape_string($this->db, $email);
            $hashed_pass = mysqli_real_escape_string($this->db, $hashed_pass);
            $country = mysqli_real_escape_string($this->db, $country);
            $city    = mysqli_real_escape_string($this->db, $city);
            $contact = mysqli_real_escape_string($this->db, $contact);

            $sql = "INSERT INTO customer 
                    (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, customer_image, user_role)
                    VALUES 
                    ('$name', '$email', '$hashed_pass', '$country', '$city', '$contact', " .
                    (is_null($image) ? "NULL" : "'$image'") . ", '$role')";

            $result = $this->db_write_query($sql);

            if ($result) {
                error_log("User registered successfully: $email");
                return true;
            } else {
                error_log("Registration failed for: $email. MySQL error: " . $this->db->error);
                return false;
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if an email already exists
     */
    public function checkEmail($email)
    {
        try {
            if ($this->db === null) {
                $this->db_connect();
            }

            $email = mysqli_real_escape_string($this->db, $email);
            $sql = "SELECT * FROM customer WHERE customer_email = '$email' LIMIT 1";
            $result = $this->db_fetch_one($sql);

            // Debugging log
            error_log("checkEmail lookup for $email => " . print_r($result, true));

            if ($result && isset($result['customer_email'])) {
                return true; // exists
            }
            return false; // does not exist
        } catch (Exception $e) {
            error_log("Error checking email: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get user by ID
     */
    public function getUser($id)
    {
        if ($this->db === null) {
            $this->db_connect();
        }

        $id = mysqli_real_escape_string($this->db, $id);
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
        if ($this->db === null) {
            $this->db_connect();
        }

        $id = mysqli_real_escape_string($this->db, $id);
        $sql = "DELETE FROM customer WHERE customer_id = '$id'";
        return $this->db_write_query($sql);
    }

    /**
     * Get user by email (for login, but without password check)
     */
    public function getUserByEmail($email)
    {
        if ($this->db === null) {
            $this->db_connect();
        }

        $email = mysqli_real_escape_string($this->db, $email);
        $sql = "SELECT * FROM customer WHERE customer_email = '$email' LIMIT 1";
        return $this->db_fetch_one($sql);
    }

    /*
     verifying email and password to login a user
     */
    public function login($email, $password)
    {
        try {
            if ($this->db === null) {
                $this->db_connect();
            }

            $email = mysqli_real_escape_string($this->db, $email);
            $sql = "SELECT * FROM customer WHERE customer_email = '$email' LIMIT 1";
            $result = $this->db_fetch_one($sql);

            if ($result && isset($result['customer_pass'])) {
                if (password_verify($password, $result['customer_pass'])) {
                    return $result; // Successful login: return user data
                } else {
                    return false;  
                }
            }

            return false; 

        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
}
?>
