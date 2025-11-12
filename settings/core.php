<?php
// settings/core.php

// Show errors in dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Allow safe header() redirects after output
if (!headers_sent()) {
    ob_start();
}

/* -----------------------------
   Auth helpers
------------------------------*/

/** Is any user logged in? */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/** Is the logged-in user an admin? (role 1) */
function isAdmin(): bool {
    return isLoggedIn() && isset($_SESSION['role']) && (int)$_SESSION['role'] === 1;
}

/** Get logged-in user id or null */
function getUserId(): ?int {
    return isLoggedIn() ? (int)$_SESSION['user_id'] : null;
}

/** Get logged-in user's name or null */
function getUserName(): ?string {
    return isLoggedIn() ? (string)$_SESSION['name'] : null;
}

/* Compatibility aliases (older code) */
function is_logged_in() { return isLoggedIn(); }
function is_admin()     { return isAdmin(); }

/* -----------------------------
   NEW: helpers used by cart
------------------------------*/

/** Same as getUserId() but with a modern, explicit name for clarity */
function current_user_id(): ?int {
    return getUserId();
}

/** Basic client IP detection (sufficient for the lab) */
function get_client_ip(): string {
    foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (!empty($_SERVER[$key])) {
            // X-Forwarded-For can contain comma-separated list; we want the first hop
            $ip = explode(',', $_SERVER[$key])[0];
            return trim($ip);
        }
    }
    return '0.0.0.0';
}
