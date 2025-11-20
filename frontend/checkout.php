<?php
// frontend/checkout.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pago - La 13</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    header { background: #333; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    header h1 { margin: 0; }
    .form-section { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
    .form-section h2 { margin-top: 0; border-bottom: 2px solid #333; padding-bottom: 10px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    input:focus, select:focus { outline: none; border-color: #007bff; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .form-row input { width: 100%; }
    .summary { background: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 20px; }
    .summary-row { display: flex; justify-content: space-between; margin: 8px 0; }
    .summary-row.total { font-weight: bold; font-size: 18px; border-top: 2px solid #333; padding-top: 10px; margin-top: 10px; }
    .pay-btn { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 20px; }
    .pay-btn:hover { background: #218838; }
    .back-btn { display: inline-block; margin-bottom: 20px; padding: 8px 12px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; }
    .back-btn:hover { background: #5a6268; }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Completar Pago - La 13</h1>
    </header>

    <a href="carrito.php" class="back-btn">← Volver al Carrito</a>

    <form id="checkoutForm">
      <!-- Datos Personales -->
      <div class="form-section">
        <h2>Datos Personales</h2>
        <div class="form-group">
          <label for="name">Nombre Completo *</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="email">Correo Electrónico *</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="phone">Teléfono *</label>
          <input type="tel" id="phone" name="phone" required>
        </div>
      </div>

      <!-- Dirección de Envío -->
      <div class="form-section">
        <h2>Dirección de Envío</h2>
        <div class="form-group">
          <label for="address">Dirección *</label>
          <input type="text" id="address" name="address" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="city">Ciudad *</label>
            <input type="text" id="city" name="city" required>
          </div>
          <div class="form-group">
            <label for="zip">Código Postal *</label>
            <input type="text" id="zip" name="zip" required>
          </div>
        </div>
      </div>

      <!-- Datos de Pago -->
      <div class="form-section">
        <h2>Información de Pago</h2>
        <div class="form-group">
          <label for="cardName">Titular de la Tarjeta *</label>
          <input type="text" id="cardName" name="cardName" required>
        </div>
        <div class="form-group">
          <label for="cardNumber">Número de Tarjeta *</label>
          <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="expiry">Vencimiento (MM/YY) *</label>
            <input type="text" id="expiry" name="expiry" placeholder="12/25" maxlength="5" required>
          </div>
          <div class="form-group">
            <label for="cvv">CVV *</label>
            <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" required>
          </div>
        </div>
      </div>

      <!-- Resumen -->
      <div class="form-section">
        <h2>Resumen de Compra</h2>
        <div class="summary">
          <div class="summary-row">
            <span>Subtotal:</span>
            <span id="subtotal">$0</span>
          </div>
          <div class="summary-row">
            <span>IVA (19%):</span>
            <span id="iva">$0</span>
          </div>
          <div class="summary-row total">
            <span>Total a Pagar:</span>
            <span id="total">$0</span>
          </div>
        </div>
      </div>

      <button type="submit" class="pay-btn">Confirmar Pago</button>
    </form>
  </div>

  <script>
    const totalAmount = parseFloat(localStorage.getItem('totalAmount')) || 0;
    const subtotal = totalAmount / 1.19;
    const iva = totalAmount - subtotal;

    document.getElementById('subtotal').innerText = subtotal.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
    document.getElementById('iva').innerText = iva.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
    document.getElementById('total').innerText = totalAmount.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });

    document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      // Validaciones básicas
      const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
      const expiry = document.getElementById('expiry').value;
      const cvv = document.getElementById('cvv').value;

      if (cardNumber.length !== 16) {
        alert('Número de tarjeta inválido');
        return;
      }

      if (!/^\d{2}\/\d{2}$/.test(expiry)) {
        alert('Formato de vencimiento inválido (MM/YY)');
        return;
      }

      if (cvv.length !== 3) {
        alert('CVV inválido');
        return;
      }

      // Datos del pedido
      const order = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        city: document.getElementById('city').value,
        zip: document.getElementById('zip').value,
        total: totalAmount,
        cart: JSON.parse(localStorage.getItem('cart')),
        date: new Date().toLocaleString('es-CO')
      };

      try {
        // Enviar al servidor
        const response = await fetch('../backend/procesar_pedido.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(order)
        });

        const result = await response.json();

        if (result.success) {
          console.log('Pedido confirmado:', result);
          
          // Limpiar carrito
          localStorage.removeItem('cart');
          localStorage.removeItem('totalAmount');

          alert(`¡Pago confirmado! Número de pedido: #${result.pedido_id}`);
          window.location.href = 'catalogo.php';
        } else {
          alert('Error: ' + result.error);
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar el pedido');
      }
    });

    // Formatear número de tarjeta
    document.getElementById('cardNumber').addEventListener('input', (e) => {
      let value = e.target.value.replace(/\s/g, '');
      let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
      e.target.value = formattedValue;
    });

    // Formatear expiry
    document.getElementById('expiry').addEventListener('input', (e) => {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
      }
      e.target.value = value;
    });
  </script>
</body>
</html>