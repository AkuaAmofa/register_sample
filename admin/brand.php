<?php
require_once '../settings/core.php';

// Step 1: Check login and admin access
if (!isLoggedIn()) {
    header("Location: ../login/login.php");
    exit();
}

if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brand Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Brand Management</h2>

    <!-- Add Brand Form -->
    <div class="card p-4 mb-4">
        <h5>Add a New Brand</h5>
        <form id="addBrandForm">
            <div class="mb-3">
                <label for="brand_name" class="form-label">Brand Name</label>
                <input type="text" class="form-control" id="brand_name" name="brand_name" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Brand</button>
        </form>
    </div>

    <!-- Brand Table -->
    <div class="card p-4">
        <h5>Existing Brands</h5>
        <table class="table table-striped" id="brandTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated dynamically via JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Brand Modal -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editBrandForm">
          <input type="hidden" id="edit_brand_id" name="brand_id">
          <div class="mb-3">
            <label for="edit_brand_name" class="form-label">Brand Name</label>
            <input type="text" class="form-control" id="edit_brand_name" name="brand_name" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/brand.js"></script>

</body>
</html>
