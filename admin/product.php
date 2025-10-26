<?php
// admin/product.php
include_once '../settings/core.php';
include_once '../controllers/category_controller.php';
include_once '../controllers/brand_controller.php';
include_once '../controllers/product_controller.php';

// Check login and admin authorization
if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}

// Retrieve existing categories, brands, and products
$categories = get_all_categories_ctr();
$brands     = get_all_brands_ctr();
$products   = get_all_products_ctr();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light p-4">

<div class="container">
  <h2 class="mb-4 text-center">Product Management</h2>

  <!-- Add / Edit Product Form -->
  <div class="card p-4 shadow-sm mb-5">
    <form id="productForm" enctype="multipart/form-data">
      <input type="hidden" name="product_id" id="product_id">

      <div class="row mb-3">
        <div class="col">
          <label for="product_cat" class="form-label">Category</label>
          <select id="product_cat" name="product_cat" class="form-select" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['cat_id']; ?>"><?= htmlspecialchars($cat['cat_name']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col">
          <label for="product_brand" class="form-label">Brand</label>
          <select id="product_brand" name="product_brand" class="form-select" required>
            <option value="">Select Brand</option>
            <?php foreach ($brands as $brand): ?>
              <option value="<?= $brand['brand_id']; ?>"><?= htmlspecialchars($brand['brand_name']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label for="product_title" class="form-label">Product Title</label>
        <input type="text" id="product_title" name="product_title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="product_price" class="form-label">Price (GHS)</label>
        <input type="number" step="0.01" id="product_price" name="product_price" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="product_desc" class="form-label">Description</label>
        <textarea id="product_desc" name="product_desc" rows="3" class="form-control"></textarea>
      </div>

      <div class="mb-3">
        <label for="product_keywords" class="form-label">Keywords</label>
        <input type="text" id="product_keywords" name="product_keywords" class="form-control" placeholder="e.g. organic, fertilizer, poultry feed">
      </div>

      <div class="mb-3">
        <label for="product_image" class="form-label">Product Image</label>
        <input type="file" id="product_image" name="product_image" class="form-control">
        <small class="text-muted">Allowed formats: JPG, PNG, GIF, WEBP.</small>
      </div>

      <button type="submit" class="btn btn-primary w-100" id="submitBtn">Add Product</button>
    </form>
  </div>

  <!-- Product List -->
  <h4>Existing Products</h4>
  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white align-middle">
      <thead class="table-primary text-center">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Category</th>
          <th>Brand</th>
          <th>Price (GHS)</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="productTableBody">
        <?php if (!empty($products)): ?>
          <?php foreach ($products as $p): ?>
            <tr>
              <td><?= $p['product_id']; ?></td>
              <td><?= htmlspecialchars($p['product_title']); ?></td>
              <td><?= htmlspecialchars($p['cat_name']); ?></td>
              <td><?= htmlspecialchars($p['brand_name']); ?></td>
              <td><?= number_format($p['product_price'], 2); ?></td>
              <td class="text-center">
                <?php if (!empty($p['product_image'])): ?>
                  <img src="../uploads/<?= htmlspecialchars($p['product_image']); ?>" alt="Product" style="width:60px;height:60px;object-fit:cover;">
                <?php else: ?>
                  <span class="text-muted">No image</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <button class="btn btn-sm btn-warning edit-btn"
                        data-id="<?= $p['product_id']; ?>"
                        data-title="<?= htmlspecialchars($p['product_title']); ?>"
                        data-price="<?= $p['product_price']; ?>"
                        data-desc="<?= htmlspecialchars($p['product_desc']); ?>"
                        data-keywords="<?= htmlspecialchars($p['product_keywords']); ?>"
                        data-cat="<?= $p['product_cat']; ?>"
                        data-brand="<?= $p['product_brand']; ?>">
                        Edit
                </button>

                <button class="btn btn-sm btn-danger delete-btn"
                        data-id="<?= $p['product_id']; ?>">
                        Delete
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center text-muted">No products found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="../js/product.js"></script>
</body>
</html>
