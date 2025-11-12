<?php /* view/checkout.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Checkout</h3>
      <a href="cart.php" class="btn btn-outline-secondary">Back to Cart</a>
    </div>

    <div id="flash" class="alert d-none" role="alert"></div>

    <div class="row g-4">
      <!-- Order Summary -->
      <div class="col-lg-7">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <strong>Order Summary</strong>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table mb-0 align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Product</th>
                    <th class="text-end" style="width: 120px;">Price</th>
                    <th class="text-center" style="width: 80px;">Qty</th>
                    <th class="text-end" style="width: 140px;">Subtotal</th>
                  </tr>
                </thead>
                <tbody id="summary-body">
                  <tr><td colspan="4" class="text-center py-4 text-muted">Loading...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <div class="text-muted" id="summary-count"></div>
            <h5 class="mb-0">Total: <span id="summary-total">GHS 0.00</span></h5>
          </div>
        </div>
      </div>

      <!-- Customer Info + Pay -->
      <div class="col-lg-5">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <strong>Customer Details</strong>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input id="cust-name" type="text" class="form-control" placeholder="e.g., Akua Amofa">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input id="cust-email" type="email" class="form-control" placeholder="e.g., akua@example.com">
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input id="cust-phone" type="tel" class="form-control" placeholder="e.g., 024xxxxxxx">
            </div>
            <div class="mb-3">
              <label class="form-label">Address</label>
              <textarea id="cust-address" class="form-control" rows="3" placeholder="Delivery address (optional)"></textarea>
            </div>

            <button id="btn-pay" class="btn btn-primary w-100" disabled>Place Order (Simulate)</button>
            <div class="form-text mt-2">This is a simulation: it will clear your cart and show a confirmation.</div>
          </div>
        </div>
      </div>
    </div>

  </div>

<script>
async function fetchJSON(url, opts) {
  const res = await fetch(url, opts);
  const text = await res.text();
  try { return JSON.parse(text); } catch { throw new Error(text); }
}
function money(n){ const x=Number(n||0); return 'GHS ' + x.toFixed(2); }

async function loadSummary() {
  const body = document.getElementById('summary-body');
  const countEl = document.getElementById('summary-count');
  const totalEl = document.getElementById('summary-total');
  const btn = document.getElementById('btn-pay');

  try {
    const data = await fetchJSON('../actions/cart_items_action.php');
    if (!data || data.status === 'error' || !Array.isArray(data.items) || data.items.length === 0) {
      body.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">Your cart is empty.</td></tr>';
      countEl.textContent = '';
      totalEl.textContent = money(0);
      btn.disabled = true;
      return;
    }

    let rows = '';
    let total = 0, count = 0;
    data.items.forEach(it => {
      const title = it.product_title ?? 'Product';
      const price = Number(it.product_price ?? 0);
      const qty   = Number(it.qty ?? 1);
      const sub   = price * qty;
      total += sub; count += qty;

      rows += `
        <tr>
          <td>
            <div class="fw-semibold">${title}</div>
            <div class="text-muted small">Product ID: ${it.p_id}</div>
          </td>
          <td class="text-end">${money(price)}</td>
          <td class="text-center">${qty}</td>
          <td class="text-end">${money(sub)}</td>
        </tr>`;
    });

    body.innerHTML = rows;
    countEl.textContent = `${count} item(s)`;
    totalEl.textContent = money(total);
    btn.disabled = false;

  } catch (e) {
    body.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-danger">Error loading summary</td></tr>';
    document.getElementById('btn-pay').disabled = true;
  }
}

document.getElementById('btn-pay').addEventListener('click', async () => {
  const name  = document.getElementById('cust-name').value.trim();
  const email = document.getElementById('cust-email').value.trim();
  const phone = document.getElementById('cust-phone').value.trim();
  // Minimal validation for demo
  if (!name || !email) {
    showFlash('Please provide your name and email.', 'alert-warning');
    return;
  }

  try {
    const resp = await fetchJSON('../actions/process_checkout_action.php', {
      method: 'POST',
      body: new URLSearchParams({ name, email, phone, address: document.getElementById('cust-address').value })
    });
    if (resp.status === 'success') {
      showFlash('Order placed successfully. Cart cleared.', 'alert-success');
      await loadSummary();
      // optional: redirect to products after 1.5s
      setTimeout(() => { window.location.href = 'all_product.php'; }, 1500);
    } else {
      showFlash(resp.message || 'Could not place order.', 'alert-danger');
    }
  } catch (e) {
    showFlash('Server error placing order.', 'alert-danger');
  }
});

function showFlash(msg, cls) {
  const el = document.getElementById('flash');
  el.className = 'alert ' + cls;
  el.textContent = msg;
  el.classList.remove('d-none');
  setTimeout(() => el.classList.add('d-none'), 2000);
}

document.addEventListener('DOMContentLoaded', loadSummary);
</script>
</body>
</html>
