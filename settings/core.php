<?php
// Settings/core.php

// Always start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// For header redirection
ob_start();

/**
 * Check if a user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if the logged-in user is an admin
 * @return bool
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] == 1;
}

/**
 * Get the logged-in user ID
 * @return int|null
 */
function getUserId() {
    return isLoggedIn() ? $_SESSION['user_id'] : null;
}

/**
 * Get the logged-in user name
 * @return string|null
 */
function getUserName() {
    return isLoggedIn() ? $_SESSION['name'] : null;
}
?>
