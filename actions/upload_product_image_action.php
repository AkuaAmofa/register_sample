<?php
// actions/upload_product_image_action.php
header('Content-Type: application/json');
session_start();

$response = [];

try {
    // Check for file presence
    if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No image uploaded or an upload error occurred.');
    }

    // Define the ONLY allowed upload directory
    $upload_dir = realpath(__DIR__ . '/../uploads/');

    if (!$upload_dir) {
        throw new Exception('Upload directory not found.');
    }

    // Validate the file type (basic security)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($_FILES['product_image']['tmp_name']);
    if (!in_array($file_type, $allowed_types)) {
        throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.');
    }

    // Generate a secure unique filename
    $filename = uniqid('prod_', true) . '_' . basename($_FILES['product_image']['name']);
    $target_path = $upload_dir . DIRECTORY_SEPARATOR . $filename;

    // Ensure the target path is still inside uploads/
    $resolved_path = realpath(dirname($target_path));
    if ($resolved_path !== $upload_dir) {
        throw new Exception('Invalid upload path. Uploads allowed only inside uploads/ folder.');
    }

    // Move file to the uploads directory
    if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path)) {
        throw new Exception('File upload failed. Could not move file.');
    }

    // ✅ Return ONLY the filename to store in the DB
    $response = [
        'status' => 'success',
        'message' => 'Image uploaded successfully.',
        'file_path' => $filename  // <-- Only filename
    ];
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
exit();
?>
