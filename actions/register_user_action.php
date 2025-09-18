<?php
header('Content-Type: application/json');
session_start();

$response = array();

// Check if already logged in
if (isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

require_once '../controllers/user_controller.php';

// Collect form data
$name     = $_POST['name'];
$email    = $_POST['email'];
$password = $_POST['password'];
$country  = $_POST['country'];
$city     = $_POST['city'];
$contact  = $_POST['contact'];
$role     = 2;       // default: customer
$image    = null;    // optional

// Step 1: check if email exists
if (check_email_ctr($email)) {
    $response['status'] = 'error';
    $response['message'] = 'Email already exists';
    echo json_encode($response);
    exit();
}

// Step 2: try registration
$result = register_user_ctr($name, $email, $password, $country, $city, $contact, $role, $image);

if ($result) {
    $response['status']  = 'success';
    $response['message'] = 'Registered successfully';
} else {
    $response['status']  = 'error';
    $response['message'] = 'Failed to register';
}

echo json_encode($response);
?>
