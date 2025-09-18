<?php
require_once '../classes/user_class.php';

/**
 * Handle user registration
 */
function register_user_ctr($name, $email, $password, $country, $city, $contact, $role = 2, $image = null)
{
    $user = new User();
    return $user->register($name, $email, $password, $country, $city, $contact, $role, $image);
}

/**
 * Check if email exists
 */
function check_email_ctr($email)
{
    $user = new User();
    return $user->checkEmail($email);
}

/**
 * Get one user by ID
 */
function get_user_ctr($id)
{
    $user = new User();
    return $user->getUser($id);
}

/**
 * Get all users
 */
function get_all_users_ctr()
{
    $user = new User();
    return $user->getAllUsers();
}

/**
 * Delete a user
 */
function delete_user_ctr($id)
{
    $user = new User();
    return $user->deleteUser($id);
}

/**
 * Login user by email (for login functionality)
 */
function login_user_ctr($email)
{
    $user = new User();
    return $user->getUserByEmail($email);
}
?>

