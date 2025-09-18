<?php
require_once '../classes/user_class.php';

/**
 * Handle user registration
 */
function register_user_ctr($name, $email, $password, $country, $city, $contact, $role = 2, $image = null)
{
    try {
        $user = new User();
        $result = $user->register($name, $email, $password, $country, $city, $contact, $role, $image);
        error_log("Controller: Registration result for $email: " . ($result ? 'success' : 'failed'));
        return $result;
    } catch (Exception $e) {
        error_log("Controller error in register_user_ctr: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if email exists
 */
function check_email_ctr($email)
{
    try {
        $user = new User();
        $result = $user->checkEmail($email);
        error_log("Controller: Email check for $email: " . ($result ? 'exists' : 'available'));
        return $result;
    } catch (Exception $e) {
        error_log("Controller error in check_email_ctr: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Get one user by ID
 */
function get_user_ctr($id)
{
    try {
        $user = new User();
        return $user->getUser($id);
    } catch (Exception $e) {
        error_log("Controller error in get_user_ctr: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all users
 */
function get_all_users_ctr()
{
    try {
        $user = new User();
        return $user->getAllUsers();
    } catch (Exception $e) {
        error_log("Controller error in get_all_users_ctr: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete a user
 */
function delete_user_ctr($id)
{
    try {
        $user = new User();
        return $user->deleteUser($id);
    } catch (Exception $e) {
        error_log("Controller error in delete_user_ctr: " . $e->getMessage());
        return false;
    }
}

/**
 * Login user by email (for login functionality)
 */
function login_user_ctr($email)
{
    try {
        $user = new User();
        return $user->getUserByEmail($email);
    } catch (Exception $e) {
        error_log("Controller error in login_user_ctr: " . $e->getMessage());
        return false;
    }
}
?>