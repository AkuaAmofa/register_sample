<?php
// view/product_search_result.php
include_once '../controllers/category_controller.php';
include_once '../controllers/brand_controller.php';
$categories = get_all_categories_ctr();
$brands = get_all_brands_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body class="bg-light">

<div class="container py-5">
  <!-- Updated: Back button now correctly points to view/all_product.php -->
  <a href="all_product.php" class="btn btn-secondary mb-4">← Back to Products</a>

  <div class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" id="searchBox" class="form-control" placeholder="Search products...">
    </div>
    <div class="col-md-3">
      <select id="filterCategory" class="form-select">
        <option value="">Category</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select id="filterBrand" class="form-select">
        <option value="">Brand</option>
        <?php foreach ($brands as $brand): ?>
          <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2 d-grid">
      <button id="applyFilters" class="btn btn-primary">Search</button>
    </div>
  </div>

  <h5 id="searchTitle" class="mb-3"></h5>
  <div id="searchResults" class="row g-4"></div>

  <div class="d-flex justify-content-between align-items-center mt-4">
    <button id="prevPage" class="btn btn-outline-secondary">Prev</button>
    <div id="pageInfo" class="small text-muted"></div>
    <button id="nextPage" class="btn btn-outline-secondary">Next</button>
  </div>
</div>

<script>
$(function(){
  const params = new URLSearchParams(location.search);
  $('#searchBox').val(params.get('q') || '');

  let page = 1, perPage = 10;

  function buildUrl(){
    const q = $('#searchBox').val().trim();
    const cat = $('#filterCategory').val();
    const brand = $('#filterBrand').val();

    const p = new URLSearchParams();
    p.set('page', page);
    p.set('per_page', perPage);

    if (q === '' && !cat && !brand) {
      p.set('action', 'all');
    } else {
      p.set('action', 'search_advanced');
      if (q) p.set('q', q);
      if (cat) p.set('cat', cat);
      if (brand) p.set('brand', brand);
    }
    return '../actions/product_actions.php?' + p.toString();
  }

  function load(){
    const q = $('#searchBox').val().trim();
    $('#searchTitle').text(q ? 'Search results for "' + q + '"' : 'All Products');

    $.getJSON(buildUrl(), function(payload){
      if (!payload || payload.status === 'error') {
        $('#searchResults').html('<p class="text-center text-muted mt-5">' + (payload?.message || 'No products') + '</p>');
        $('#pageInfo').text('');
        return;
      }
      render(payload.items || []);
      renderPager(payload);
    }).fail(function(){
      $('#searchResults').html('<p class="text-center text-danger mt-5">Error loading results.</p>');
      $('#pageInfo').text('');
    });
  }

  function render(items){
    if (!items.length) {
      $('#searchResults').html('<p class="text-center text-muted mt-5">No products found.</p>');
      return;
    }

    let html = '';
    items.forEach(p => {
      html += `
      <div class="col-md-3">
        <div class="card h-100 shadow-sm">
          <img src="../uploads/${p.product_image || ''}" class="card-img-top" style="height:200px;object-fit:cover;" alt="Product">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title mb-1">${p.product_title}</h6>
            <p class="text-muted small mb-2">${p.brand_name} • ${p.cat_name}</p>
            <p class="fw-bold mb-3">GHS ${parseFloat(p.product_price).toFixed(2)}</p>
            <div class="mt-auto">
              <a href="single_product.php?id=${p.product_id}" class="btn btn-sm btn-primary w-100 mb-2">View Details</a>
              <button class="btn btn-sm btn-outline-success w-100" data-id="${p.product_id}">Add to Cart</button>
            </div>
          </div>
        </div>
      </div>`;
    });
    $('#searchResults').html(html);
  }

  function renderPager(payload){
    const total = payload.total || 0;
    const pages = Math.max(1, Math.ceil(total / (payload.per_page || 10)));
    $('#pageInfo').text(`Page ${payload.page} of ${pages} • ${total} item(s)`);
    $('#prevPage').prop('disabled', payload.page <= 1);
    $('#nextPage').prop('disabled', payload.page >= pages);
  }

  $('#applyFilters').on('click', function(){ page = 1; load(); });
  $('#searchBox').on('keyup', function(e){ if (e.key==='Enter') { page = 1; load(); } });
  $('#filterCategory, #filterBrand').on('change', function(){ page = 1; load(); });
  $('#prevPage').on('click', function(){ if (page > 1){ page--; load(); } });
  $('#nextPage').on('click', function(){ page++; load(); });

  load();
});
</script>
</body>
</html>
