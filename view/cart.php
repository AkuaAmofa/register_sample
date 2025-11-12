<?php /* view/cart.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Your Cart</h3>
      <div>
        <a href="all_product.php" class="btn btn-outline-secondary">Continue Shopping</a>
        <a href="checkout.php" class="btn btn-primary ms-2" id="goCheckout">Proceed to Checkout</a>
        <button id="btn-empty" class="btn btn-outline-danger ms-2">Empty Cart</button>
      </div>
    </div>

    <!-- flash area -->
    <div id="flash" class="alert alert-success py-2 d-none" role="alert">Item added to cart.</div>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:55%">Product</th>
                <th style="width:10%">Price</th>
                <th style="width:15%">Qty</th>
                <th style="width:10%">Subtotal</th>
                <th style="width:10%"></th>
              </tr>
            </thead>
            <tbody id="cart-body">
              <tr><td colspan="5" class="text-center py-4 text-muted">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <div class="text-muted" id="cart-count"></div>
        <h5 class="mb-0">Total: <span id="cart-total">GHS 0.00</span></h5>
      </div>
    </div>
  </div>

  <script>
    // flash notice if redirected with ?added=1
    (function () {
      const params = new URLSearchParams(location.search);
      if (params.get('added') === '1') {
        const el = document.getElementById('flash');
        el.classList.remove('d-none');
        setTimeout(() => el.classList.add('d-none'), 1800);
      }
    })();

    // prevent checkout when empty
    document.getElementById('goCheckout').addEventListener('click', async (e) => {
      try {
        const res = await fetch('../actions/cart_items_action.php');
        const text = await res.text();
        const data = JSON.parse(text);
        if (!data.items || data.items.length === 0) {
          e.preventDefault();
          alert('Your cart is empty.');
        }
      } catch (_) {
        // if endpoint fails, allow default navigation so user can retry there
      }
    });
  </script>

  <script src="../js/cart.js"></script>
</body>
</html>
