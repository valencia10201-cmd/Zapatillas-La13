<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrito - La 13</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; }
    .container { max-width: 900px; margin: 0 auto; padding: 20px; }
    header { background: #333; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    header h1 { margin: 0; }
    header nav { margin-top: 10px; }
    header nav a { color: white; text-decoration: none; margin-right: 20px; }
    header nav a:hover { text-decoration: underline; }
    .cart-content { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
    .cart-table { background: white; border-radius: 8px; padding: 20px; }
    .cart-table h2 { margin-top: 0; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #333; color: white; padding: 10px; text-align: left; }
    td { padding: 12px; border-bottom: 1px solid #ddd; }
    tr:hover { background: #f9f9f9; }
    .item-img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
    .qty-control { display: flex; gap: 4px; align-items: center; }
    .qty-control input { width: 50px; padding: 4px; text-align: center; }
    .qty-control button { padding: 4px 8px; cursor: pointer; background: #5cb85c; color: white; border: none; border-radius: 3px; }
    .qty-control button:hover { background: #4cae4c; }
    .remove-btn { background: #dc3545; color: white; border: none; padding: 6px 10px; cursor: pointer; border-radius: 3px; }
    .remove-btn:hover { background: #c82333; }
    .summary { background: white; border-radius: 8px; padding: 20px; height: fit-content; }
    .summary h3 { margin-top: 0; }
    .summary-row { display: flex; justify-content: space-between; margin: 10px 0; }
    .summary-row.total { font-weight: bold; font-size: 18px; border-top: 2px solid #333; padding-top: 10px; margin-top: 15px; }
    .checkout-btn { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 15px; font-weight: bold; font-size: 14px; }
    .checkout-btn:hover { background: #0056b3; }
    .back-btn { display: inline-block; margin-bottom: 10px; padding: 8px 12px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; }
    .back-btn:hover { background: #5a6268; }
    .empty-message { text-align: center; padding: 40px; background: white; border-radius: 8px; }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Carrito de Compras - La 13</h1>
      <nav>
        <a href="index.html">Inicio</a>
        <a href="catalogo.html">Catálogo</a>
        <a href="dashboard.html">Dashboard</a>
      </nav>
    </header>

    <a href="catalogo.html" class="back-btn">← Volver al Catálogo</a>

    <div id="cartEmpty" class="empty-message" style="display:none">
      <h2>Tu carrito está vacío</h2>
      <p>Vuelve al <a href="catalogo.html">catálogo</a> para añadir productos.</p>
    </div>

    <div id="cartContent" class="cart-content" style="display:none">
      <div class="cart-table">
        <h2>Artículos en el carrito</h2>
        <table id="cartTable">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Color</th>
              <th>Talla</th>
              <th>Precio</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="cartBody"></tbody>
        </table>
      </div>

      <div class="summary">
        <h3>Resumen de Compra</h3>
        <div class="summary-row">
          <span>Subtotal:</span>
          <span id="subtotal">$0</span>
        </div>
        <div class="summary-row">
          <span>IVA (19%):</span>
          <span id="iva">$0</span>
        </div>
        <div class="summary-row">
          <span>Envío:</span>
          <span id="shipping">Gratis</span>
        </div>
        <div class="summary-row total">
          <span>Total:</span>
          <span id="total">$0</span>
        </div>
        <button class="checkout-btn" id="proceedBtn">Proceder al Pago</button>
      </div>
    </div>
  </div>

  <script>
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function renderCart() {
      const tbody = document.getElementById('cartBody');
      const cartEmpty = document.getElementById('cartEmpty');
      const cartContent = document.getElementById('cartContent');

      if (cart.length === 0) {
        cartEmpty.style.display = 'block';
        cartContent.style.display = 'none';
        return;
      }

      cartEmpty.style.display = 'none';
      cartContent.style.display = 'grid';

      tbody.innerHTML = cart.map((item, idx) => {
        const subtotal = item.price * item.qty;
        const priceFormatted = item.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
        const subtotalFormatted = subtotal.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
        return `
          <tr>
            <td><strong>${item.name}</strong></td>
            <td>${item.color}</td>
            <td>${item.size}</td>
            <td>${priceFormatted}</td>
            <td>
              <div class="qty-control">
                <button onclick="updateQty(${idx}, ${item.qty - 1})">-</button>
                <input type="number" value="${item.qty}" min="1" onchange="updateQty(${idx}, this.value)">
                <button onclick="updateQty(${idx}, ${item.qty + 1})">+</button>
              </div>
            </td>
            <td>${subtotalFormatted}</td>
            <td><button class="remove-btn" onclick="removeItem(${idx})">Quitar</button></td>
          </tr>
        `;
      }).join('');

      updateSummary();
    }

    function updateQty(idx, newQty) {
      const qty = parseInt(newQty);
      if (qty < 1) return;
      cart[idx].qty = qty;
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
    }

    function removeItem(idx) {
      cart.splice(idx, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
    }

    function updateSummary() {
      const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
      const iva = subtotal * 0.19;
      const total = subtotal + iva;

      document.getElementById('subtotal').innerText = subtotal.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
      document.getElementById('iva').innerText = iva.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
      document.getElementById('total').innerText = total.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
    }

    document.getElementById('proceedBtn').addEventListener('click', () => {
      const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0) * 1.19;
      // Guarda el total en localStorage para el checkout
      localStorage.setItem('totalAmount', total);
      window.location.href = 'checkout.html';
    });

    renderCart();
  </script>
</body>
</html>