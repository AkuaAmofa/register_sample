document.getElementById('simulatePay').addEventListener('click', async () => {
  try {
    const res  = await fetch('../actions/process_checkout_action.php', { method: 'POST' });
    const data = await res.json();

    const out = document.getElementById('result');

    if (data.status === 'success') {
      out.innerHTML = `✔ Order #${data.order_id} | Invoice ${data.invoice} | Amount ${data.amount}`;
    } else {
      out.innerHTML = `✖ ${data.message || 'Payment failed'}`;
    }
  } catch (err) {
    console.error(err);
    document.getElementById('result').innerHTML = '✖ Server error processing checkout';
  }
});

