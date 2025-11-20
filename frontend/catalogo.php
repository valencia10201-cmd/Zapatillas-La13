<?php
// frontend/catalogo.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo - La 13</title>
  <link rel="stylesheet" href="catalogo.css">
  <style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; }
    .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    header { background: #333; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    header h1 { margin: 0; }
    header nav { margin-top: 10px; }
    header nav a { color: white; text-decoration: none; margin-right: 20px; }
    header nav a:hover { text-decoration: underline; }
    .catalog-container { display: flex; flex-wrap: wrap; justify-content: center; }
    .product-card { border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin: 8px; width: 200px; text-align: center; cursor: pointer; transition: all 0.3s; background: white; }
    .product-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); transform: translateY(-2px); }
    .product-card.selected { border: 2px solid #1890ff; background: #e6f7ff; }
    .product-image { width: 100%; height: 180px; object-fit: cover; border-radius: 6px; margin-bottom: 8px; }
    .product-price { font-size: 18px; font-weight: bold; color: #d9534f; margin: 8px 0; }
    .product-controls { margin-top: 10px; display: flex; flex-direction: column; gap: 8px; }
    .product-controls select, .product-controls input { width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px; }
    .product-controls button { padding: 8px; cursor: pointer; background: #5cb85c; color: white; border: none; border-radius: 4px; font-weight: bold; }
    .product-controls button:hover { background: #4cae4c; }

    /* Estilos para el carrito flotante */
    .cart-float-btn {
      position: fixed;
      right: 20px;
      bottom: 20px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: #0078d4;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 22px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      z-index: 10000;
      transition: transform 0.2s;
    }
    .cart-float-btn:hover { transform: scale(1.1); }

    .cart-float-badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #ff4d4f;
      color: white;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: bold;
    }

    .cart-float-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s;
      z-index: 9998;
    }
    .cart-float-overlay.show { opacity: 1; visibility: visible; }

    .cart-float-panel {
      position: fixed;
      right: 0;
      top: 0;
      width: 350px;
      height: 100%;
      background: white;
      box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
      transform: translateX(110%);
      transition: transform 0.3s ease;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      padding: 16px;
    }
    .cart-float-panel.open { transform: translateX(0); }

    .cart-float-close {
      align-self: flex-end;
      background: transparent;
      border: none;
      font-size: 28px;
      cursor: pointer;
      color: #333;
    }

    .cart-float-panel h3 { margin: 0 0 16px 0; }

    .cart-float-items {
      flex: 1;
      overflow-y: auto;
      padding-right: 8px;
    }

    .cart-float-item {
      padding: 12px;
      background: #f5f5f5;
      border-radius: 4px;
      margin-bottom: 8px;
      font-size: 13px;
    }

    .cart-float-item strong { display: block; margin-bottom: 4px; }

    .cart-float-footer {
      border-top: 1px solid #ddd;
      padding-top: 12px;
      margin-top: 12px;
    }

    .cart-float-footer strong { display: block; margin-bottom: 8px; }

    .cart-float-link {
      display: block;
      text-align: center;
      background: #0078d4;
      color: white;
      padding: 10px;
      border-radius: 4px;
      text-decoration: none;
      font-weight: bold;
    }
    .cart-float-link:hover { background: #005a9e; }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Catálogo - La 13</h1>
      <nav>
        <a href="index.php">Inicio</a>
        <a href="catalogo.php">Catálogo</a>
        <a href="dashboard.php">Dashboard</a>
      </nav>
    </header>

    <div class="catalog-container" id="catalogContainer"></div>
  </div>

  <!-- Botón flotante -->
  <button class="cart-float-btn" id="cartFloatBtn" title="Ver carrito">
    🛒<span class="cart-float-badge" id="cartFloatBadge">0</span>
  </button>

  <!-- Panel deslizante -->
  <div class="cart-float-overlay" id="cartFloatOverlay"></div>
  <div class="cart-float-panel" id="cartFloatPanel">
    <button class="cart-float-close" id="cartFloatClose">×</button>
    <h3>Carrito</h3>
    <div class="cart-float-items" id="cartFloatItems"></div>
    <div class="cart-float-footer">
      <strong id="cartFloatTotal">Total: $0</strong>
      <a href="carrito.php" class="cart-float-link">Ir al carrito</a>
    </div>
  </div>

  <script>
    const products = [
      { id: 1, name: "VANS", price: 125000, image: "https://static.dafiti.com.co/p/vans-8315-5174572-1-product.jpg", colors: ["black", "brown", "navy"], sizes: [38, 39, 40, 41, 42, 43] },
      { id: 2, name: "Nike air TN", price: 150000, image: "https://standshop.com.co/wp-content/uploads/2024/11/d75a5189-2a2e-4842-a707-0eade0130556.jpg", colors: ["red", "blue", "black", "gray"], sizes: [38, 39, 40, 41, 42, 43, 44, 45] },
      { id: 3, name: "Adidas Samba", price: 150000, image: "https://static.dafiti.com.co/p/adidas-originals-6590-653048-4-product.jpg", colors: ["black", "red", "nude", "silver"], sizes: [35, 36, 37, 38, 39, 40, 41, 42] },
      { id: 4, name: "Jordan Retro 1", price: 125000, image: "https://cdn-images.farfetch-contents.com/11/70/77/94/11707794_8097746_600.jpg", colors: ["white", "black", "blue", "green"], sizes: [38, 39, 40, 41, 42, 43, 44, 45] },
      { id: 5, name: "Converse All Star", price: 100000, image: "https://cdn.pixabay.com/photo/2013/07/12/18/20/shoes-153310_960_720.png", colors: ["brown", "tan", "navy", "black"], sizes: [40, 41, 42, 43, 44, 45] },
      { id: 6, name: "Air Force 1", price: 100000, image: "https://m.media-amazon.com/images/I/81uiWMk9dnL._AC_SX675_.jpg", colors: ["brown", "green", "black"], sizes: [39, 40, 41, 42, 43, 44, 45] },
      { id: 7, name: "Sandalias", price: 90000, image: "https://www.charleskeith.com/on/demandware.static/-/Library-Sites-CharlesKeith/default/dw7e760ed4/images/PeopleAlsoAsked/types-of-sandals/fall-sandals.jpg", colors: ["black", "white", "brown"], sizes: [35, 36, 37, 38, 39, 40, 41, 42, 43] },
      { id: 8, name: "New Balance 1906R", price: 150000, image: "https://images-cdn.ubuy.tn/67162b882fd19c2880667e5e-new-balance-1906r-nb-new-spruce-men.jpg", colors: ["black", "brown", "gray"], sizes: [37, 38, 39, 40, 41, 42, 43, 44] },
    ];

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function renderCatalog() {
      const container = document.getElementById('catalogContainer');
      container.innerHTML = products.map(p => {
        const priceFormatted = Number(p.price).toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
        const cartItem = cart.find(c => c.id === p.id);
        const isSelected = !!cartItem;
        return `
          <div class="product-card ${isSelected ? 'selected' : ''}" data-id="${p.id}">
            <img src="${p.image}" alt="${p.name}" class="product-image">
            <h4>${p.name}</h4>
            <div class="product-price">${priceFormatted}</div>
            <select class="color-select" data-id="${p.id}">
              <option value="">Color</option>
              ${p.colors.map(c => `<option value="${c}">${c}</option>`).join('')}
            </select>
            <select class="size-select" data-id="${p.id}">
              <option value="">Talla</option>
              ${p.sizes.map(s => `<option value="${s}">${s}</option>`).join('')}
            </select>
            <div class="product-controls">
              <input type="number" class="qty-input" data-id="${p.id}" value="${cartItem ? cartItem.qty : 1}" min="1" max="10">
              <button class="add-to-cart" data-id="${p.id}">${isSelected ? 'Actualizar' : 'Añadir'}</button>
            </div>
          </div>
        `;
      }).join('');

      // event listeners
      document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', (e) => addToCart(parseInt(e.target.dataset.id)));
      });
    }

    function addToCart(productId) {
      const product = products.find(p => p.id === productId);
      const color = document.querySelector(`.color-select[data-id="${productId}"]`).value;
      const size = document.querySelector(`.size-select[data-id="${productId}"]`).value;
      const qty = parseInt(document.querySelector(`.qty-input[data-id="${productId}"]`).value);

      if (!color || !size) {
        alert('Por favor selecciona color y talla');
        return;
      }

      const existingItem = cart.find(c => c.id === productId && c.color === color && c.size === size);
      if (existingItem) {
        existingItem.qty = qty;
      } else {
        cart.push({ id: productId, name: product.name, price: product.price, color, size, qty, image: product.image });
      }

      localStorage.setItem('cart', JSON.stringify(cart));
      renderCatalog();
      updateFloatingCart();
    }

    function removeFromCart(idx) {
      cart.splice(idx, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCatalog();
      updateFloatingCart();
    }

    // Carrito flotante
    const btn = document.getElementById('cartFloatBtn');
    const close = document.getElementById('cartFloatClose');
    const panel = document.getElementById('cartFloatPanel');
    const overlay = document.getElementById('cartFloatOverlay');
    const badge = document.getElementById('cartFloatBadge');
    const itemsDiv = document.getElementById('cartFloatItems');
    const totalSpan = document.getElementById('cartFloatTotal');

    // Función para abrir/cerrar carrito
    function toggleCart() {
      if (panel.classList.contains('open')) {
        panel.classList.remove('open');
        overlay.classList.remove('show');
      } else {
        panel.classList.add('open');
        overlay.classList.add('show');
        updateFloatingCart();
      }
    }

    btn.addEventListener('click', toggleCart);
    close.addEventListener('click', toggleCart);
    overlay.addEventListener('click', toggleCart);

    function updateFloatingCart() {
      itemsDiv.innerHTML = cart.map(item => `
        <div class="cart-float-item">
          <strong>${item.name}</strong>
          ${item.color} | ${item.size}<br>
          $${item.price.toLocaleString()} x ${item.qty}
        </div>
      `).join('');

      const total = cart.reduce((s, i) => s + (i.price * i.qty), 0);
      totalSpan.innerText = 'Total: $' + total.toLocaleString();
      badge.innerText = cart.reduce((s, i) => s + i.qty, 0);
    }

    // Escuchar cambios en el carrito
    window.addEventListener('storage', () => {
      cart = JSON.parse(localStorage.getItem('cart')) || [];
      updateFloatingCart();
    });

    renderCatalog();
    updateFloatingCart();
  </script>
</body>
</html>