// js/cart.js
async function fetchJSON(url, opts) {
  const res = await fetch(url, opts);
  const text = await res.text();
  try { return JSON.parse(text); } catch { throw new Error(text); }
}

function formatMoney(n) {
  const num = Number(n || 0);
  return 'GHS ' + num.toFixed(2);
}

async function loadCart() {
  const body = document.getElementById('cart-body');
  const countEl = document.getElementById('cart-count');
  const totalEl = document.getElementById('cart-total');

  try {
    const data = await fetchJSON('../actions/cart_items_action.php');

    if (!data || data.status === 'error' || !Array.isArray(data.items) || data.items.length === 0) {
      body.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">Your cart is empty.</td></tr>';
      countEl.textContent = '';
      totalEl.textContent = formatMoney(0);
      return;
    }

    let rows = '';
    let total = 0;
    let count = 0;

    data.items.forEach(item => {
      // Try common field names safely
      const cart_id = item.cart_id ?? item.id ?? item.cartId;
      const p_id    = item.p_id ?? item.product_id ?? item.productId;
      const title   = item.product_title ?? item.title ?? 'Product';
      const price   = Number(item.product_price ?? item.price ?? 0);
      const qty     = Number(item.qty ?? item.quantity ?? 1);
      const sub     = price * qty;

      total += sub;
      count += qty;

      rows += `
        <tr data-cart-id="${cart_id}">
          <td>
            <div class="fw-semibold">${title}</div>
            <div class="text-muted small">Product ID: ${p_id}</div>
          </td>
          <td>${formatMoney(price)}</td>
          <td style="max-width:120px">
            <div class="input-group input-group-sm">
              <input type="number" class="form-control qty-input" min="1" value="${qty}">
              <button class="btn btn-outline-secondary btn-update" type="button">Update</button>
            </div>
          </td>
          <td class="line-subtotal">${formatMoney(sub)}</td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-danger btn-remove" type="button">Remove</button>
          </td>
        </tr>`;
    });

    body.innerHTML = rows;
    countEl.textContent = `${count} item(s)`;
    totalEl.textContent = formatMoney(total);

  } catch (err) {
    console.error(err);
    const body = document.getElementById('cart-body');
    body.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger">Error loading cart</td></tr>`;
  }
}

// Delegate clicks for update/remove
document.addEventListener('click', async (e) => {
  // Update qty
  if (e.target.classList.contains('btn-update')) {
    const row = e.target.closest('tr');
    const cart_id = row?.dataset?.cartId || row?.getAttribute('data-cart-id');
    const qtyInput = row.querySelector('.qty-input');
    const qty = Math.max(1, parseInt(qtyInput.value || '1', 10));

    try {
      const resp = await fetchJSON('../actions/cart_update_qty_action.php', {
        method: 'POST',
        body: new URLSearchParams({ cart_id, qty })
      });
      if (resp.status !== 'success') alert(resp.message || 'Could not update quantity');
      await loadCart();
    } catch (err) {
      console.error(err);
      alert('Server error updating quantity');
    }
  }

  // Remove line
  if (e.target.classList.contains('btn-remove')) {
    const row = e.target.closest('tr');
    const cart_id = row?.dataset?.cartId || row?.getAttribute('data-cart-id');

    try {
      const resp = await fetchJSON('../actions/cart_remove_action.php', {
        method: 'POST',
        body: new URLSearchParams({ cart_id })
      });
      if (resp.status !== 'success') alert(resp.message || 'Could not remove item');
      await loadCart();
    } catch (err) {
      console.error(err);
      alert('Server error removing item');
    }
  }
});

document.addEventListener('DOMContentLoaded', () => {
  loadCart();

  const emptyBtn = document.getElementById('btn-empty');
  if (emptyBtn) {
    emptyBtn.addEventListener('click', async () => {
      if (!confirm('Are you sure you want to empty your cart?')) return;

      try {
        const res = await fetch('../actions/empty_cart_action.php', {
          method: 'POST'
        });
        const data = await res.json();

        if (data.status === 'success') {
          await loadCart();
        } else {
          alert(data.message || 'Could not empty cart');
        }

      } catch (err) {
        console.error(err);
        alert('Server error emptying cart');
      }
    });
  }
});
