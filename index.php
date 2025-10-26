<?php
session_start();
require_once 'settings/core.php';
require_once 'controllers/category_controller.php';
require_once 'controllers/brand_controller.php';

$logged_in = isLoggedIn();
$is_admin  = isAdmin();
$categories = get_all_categories_ctr();
$brands     = get_all_brands_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home | MyShop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">MyShop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (!$logged_in): ?>
          <li class="nav-item"><a class="nav-link" href="login/register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login/login.php">Login</a></li>
        <?php else: ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?= htmlspecialchars($_SESSION['name']); ?>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item text-danger" href="login/logout.php">Logout</a></li>
              <?php if ($is_admin): ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="admin/category.php">Manage Categories</a></li>
                <li><a class="dropdown-item" href="admin/brand.php">Manage Brands</a></li>
                <li><a class="dropdown-item" href="admin/product.php">Manage Products</a></li>
              <?php endif; ?>
            </ul>
          </li>
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link" href="customer/all_product.php">All Products</a></li>
      </ul>

      <!-- Search form -->
      <form class="d-flex" role="search" action="customer/product_search_result.php" method="get">
        <input class="form-control me-2" type="search" name="q" placeholder="Search products..." aria-label="Search">
        <button class="btn btn-outline-primary" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<!-- Quick Filters -->
<div class="bg-white border-bottom">
  <div class="container py-2">
    <form class="row g-2" action="customer/product_search_result.php" method="get">
      <div class="col-md-5 col-sm-12">
        <select name="cat" class="form-select">
          <option value="">Filter by Category</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-5 col-sm-12">
        <select name="brand" class="form-select">
          <option value="">Filter by Brand</option>
          <?php foreach ($brands as $brand): ?>
            <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2 col-sm-12 d-grid">
        <button type="submit" class="btn btn-primary">Go</button>
      </div>
    </form>
  </div>
</div>

<!-- Main Content -->
<div class="container text-center py-5">
  <h1 class="mb-3">Welcome to MyShop</h1>

  <?php if ($logged_in): ?>
    <p class="text-muted">Logged in as <strong><?= htmlspecialchars($_SESSION['email']); ?></strong>.</p>
    <?php if ($is_admin): ?>
      <p><a href="admin/product.php" class="btn btn-success">Go to Admin Dashboard</a></p>
    <?php else: ?>
      <p><a href="view/all_product.php" class="btn btn-primary">Start Shopping</a></p>
    <?php endif; ?>
  <?php else: ?>
    <p class="text-muted">Please register or log in to start shopping.</p>
    <a href="login/register.php" class="btn btn-outline-primary me-2">Register</a>
    <a href="login/login.php" class="btn btn-outline-secondary">Login</a>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
