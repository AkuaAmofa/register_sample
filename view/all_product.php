<?php
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
  <title>All Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="text-center mb-4">All Products</h2>

    <!-- Filters -->
    <div class="row g-2 mb-3">
      <div class="col-md-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search products...">
      </div>
      <div class="col-md-2">
        <select id="filterCategory" class="form-select">
          <option value="">Category</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <select id="filterBrand" class="form-select">
          <option value="">Brand</option>
          <?php foreach ($brands as $brand): ?>
            <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <input type="number" min="0" step="0.01" id="minPrice" class="form-control" placeholder="Min Price">
      </div>
      <div class="col-md-2">
        <input type="number" min="0" step="0.01" id="maxPrice" class="form-control" placeholder="Max Price">
      </div>
      <div class="col-md-1 d-grid">
        <button id="applyFilters" class="btn btn-primary">Go</button>
      </div>
    </div>

    <!-- Products grid -->
    <div id="productGrid" class="row g-4"></div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
      <button id="prevPage" class="btn btn-outline-secondary">Prev</button>
      <div id="pageInfo" class="small text-muted"></div>
      <button id="nextPage" class="btn btn-outline-secondary">Next</button>
    </div>
  </div>

<script>
$(function(){
  let page = 1;
  const perPage = 10;

  function buildActionUrl() {
    const q      = $('#searchBox').val().trim();
    const cat    = $('#filterCategory').val();
    const brand  = $('#filterBrand').val();
    const minP   = $('#minPrice').val();
    const maxP   = $('#maxPrice').val();

    const params = new URLSearchParams();
    params.set('page', page);
    params.set('per_page', perPage);

    if (q || cat || brand || minP || maxP) {
      params.set('action', 'search_advanced');
      if (q) params.set('q', q);
      if (cat) params.set('cat', cat);
      if (brand) params.set('brand', brand);
      if (minP) params.set('min_price', minP);
      if (maxP) params.set('max_price', maxP);
    } else {
      params.set('action', 'all');
    }
    return '../actions/product_actions.php?' + params.toString();
  }

  function loadProducts() {
    $.getJSON(buildActionUrl(), function(payload){
      if (!payload || payload.status === 'error') {
        $('#productGrid').html('<p class="text-center text-muted mt-5">' + (payload?.message || 'No products found') + '</p>');
        $('#pageInfo').text('');
        return;
      }
      renderProducts(payload.items || []);
      renderPager(payload);
    }).fail(function(){
      $('#productGrid').html('<p class="text-center text-danger mt-5">Error loading products</p>');
      $('#pageInfo').text('');
    });
  }

  function renderProducts(items) {
    if (!items.length) {
      $('#productGrid').html('<p class="text-center text-muted mt-5">No products to show.</p>');
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
              <button class="btn btn-sm btn-outline-success w-100 addToCart" data-id="${p.product_id}">Add to Cart</button>
            </div>
          </div>
        </div>
      </div>`;
    });
    $('#productGrid').html(html);
  }

  function renderPager(payload) {
    const total = payload.total || 0;
    const pages = Math.max(1, Math.ceil(total / (payload.per_page || 10)));
    $('#pageInfo').text(`Page ${payload.page} of ${pages} • ${total} item(s)`);
    $('#prevPage').prop('disabled', payload.page <= 1);
    $('#nextPage').prop('disabled', payload.page >= pages);
  }

  // Filter and pagination controls
  $('#applyFilters').on('click', function(){ page = 1; loadProducts(); });
  $('#searchBox').on('keyup', function(e){
    if (e.key === 'Enter') { page = 1; loadProducts(); }
  });
  $('#filterCategory, #filterBrand').on('change', function(){ page = 1; loadProducts(); });
  $('#prevPage').on('click', function(){ if (page > 1) { page--; loadProducts(); } });
  $('#nextPage').on('click', function(){ page++; loadProducts(); });

  // Add to Cart event (redirect to cart on success)
  $(document).on('click', '.addToCart', async function() {
    const p_id = $(this).data('id');
    if (!p_id) return alert('Invalid product ID');

    try {
      const res  = await fetch('../actions/cart_add_action.php', {
        method: 'POST',
        body: new URLSearchParams({ p_id, qty: 1 })
      });

      const text = await res.text();
      let data;
      try { data = JSON.parse(text); }
      catch { return alert('Server returned non-JSON:\n' + text); }

      if (data.status === 'success') {
        // we are already in /view/, so relative link is fine
        window.location.href = 'cart.php?added=1';
      } else {
        alert(data.message || 'Could not add item.');
      }
    } catch (err) {
      console.error(err);
      alert('Server error. Try again.');
    }
  });

  // Initial load
  loadProducts();
});
</script>
</body>
</html>
