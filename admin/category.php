<?php
require_once '../settings/core.php';

// Restrict to logged-in admins only
if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/category.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Category Management</h2>

    <!-- Add Category Form -->
    <form id="addCategoryForm" class="mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" name="cat_name" id="cat_name" class="form-control" placeholder="Enter category name" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Add Category</button>
            </div>
        </div>
    </form>

    <!-- Categories Table -->
    <table class="table table-bordered" id="categoryTable">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th style="width: 25%">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows will be dynamically filled by category.js -->
        </tbody>
    </table>
</div>
</body>
</html>
