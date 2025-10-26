<?php
require_once '../settings/core.php';

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
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4 text-center">Brand Management</h2>

  <!-- Add Brand -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3">Add a New Brand</h5>
      <form id="addBrandForm" class="row g-3">
        <div class="col-md-6">
          <label for="brand_name" class="form-label">Brand Name</label>
          <input type="text" id="brand_name" name="brand_name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label for="brand_cat" class="form-label">Category</label>
          <select id="brand_cat" name="brand_cat" class="form-select" required>
            <!-- populated by JS -->
          </select>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary w-100">Add Brand</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Brands Table -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">Existing Brands</h5>
      <table class="table table-striped align-middle" id="brandTable">
        <thead>
          <tr>
            <th style="width: 90px">ID</th>
            <th>Brand</th>
            <th>Category</th>
            <th style="width: 130px">Actions</th>
          </tr>
        </thead>
        <tbody><!-- rows via JS --></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editBrandForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Brand</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit_brand_id" name="brand_id">
        <div class="mb-3">
          <label for="edit_brand_name" class="form-label">Brand Name</label>
          <input type="text" id="edit_brand_name" name="brand_name" class="form-control" required>
        </div>
        <p class="text-muted mb-0">Note: Category is not editable for this lab.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/brand.js"></script>
</body>
</html>
