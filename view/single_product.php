<?php // view/single_product.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body class="bg-light">

  <div class="container py-5">
    <!-- Updated path: now points to /view/all_product.php -->
    <a href="all_product.php" class="btn btn-secondary mb-4">← Back to Products</a>
    <div id="productDetails" class="row justify-content-center"></div>
  </div>

  <script>
  $(function(){
    const id = new URLSearchParams(location.search).get('id');
    if (!id) {
      $('#productDetails').html('<p class="text-danger">Invalid product ID.</p>');
      return;
    }

    $.getJSON('../actions/product_actions.php', { action: 'single', id }, function(p){
      if (!p || p.status === 'error') {
        $('#productDetails').html('<p class="text-muted">' + (p?.message || 'Product not found') + '</p>');
        return;
      }

      const html = `
        <div class="col-md-6 mb-3">
          <img src="../uploads/${p.product_image || ''}" class="img-fluid rounded shadow-sm" alt="${p.product_title}">
        </div>
        <div class="col-md-6">
          <div class="small text-muted mb-2">ID: ${p.product_id}</div>
          <h3 class="mb-1">${p.product_title}</h3>
          <p class="text-muted">${p.brand_name} • ${p.cat_name}</p>
          <h4 class="text-success mb-3">GHS ${parseFloat(p.product_price).toFixed(2)}</h4>
          <p>${p.product_desc || ''}</p>
          <p><strong>Keywords:</strong> ${p.product_keywords || 'N/A'}</p>
          <button class="btn btn-success">Add to Cart</button>
        </div>`;
      $('#productDetails').html(html);
    }).fail(function(){
      $('#productDetails').html('<p class="text-danger">Error loading product details.</p>');
    });
  });
  </script>

</body>
</html>
