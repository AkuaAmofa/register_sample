<?php
header('Content-Type: application/json');
session_start();

$response = [];

// 🔹 Prevent registration if already logged in
if (isset($_SESSION['user_id'])) {
    $response['status']  = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

require_once '../controllers/user_controller.php';

// 🔹 Collect form data safely
$name     = $_POST['name']     ?? '';
$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';
$country  = $_POST['country']  ?? '';
$city     = $_POST['city']     ?? '';
$contact  = $_POST['contact']  ?? '';
$role     = $_POST['role']     ?? 2;   // default 2 = Customer
$image    = null; // optional

// 🔹 Step 1: Validate required fields
if (!$name || !$email || !$password || !$country || !$city || !$contact) {
    $response['status']  = 'error';
    $response['message'] = 'All fields are required';
    echo json_encode($response);
    exit();
}

// 🔹 Step 2: check if email already exists
if (check_email_ctr($email)) {
    $response['status']  = 'error';
    $response['message'] = 'Email already exists';
    echo json_encode($response);
    exit();
}

// 🔹 Step 3: attempt registration
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
