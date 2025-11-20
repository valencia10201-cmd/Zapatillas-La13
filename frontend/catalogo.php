<?php
// frontend/catalogo.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Catálogo - La 13</title>

  <!-- Fuente (opcional, pero mejora la apariencia) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- CSS externo -->
  <link rel="stylesheet" href="catalogo.css">
</head>
<body>
  <div class="container">
    <header>
      <div class="header-inner">
        <h1>La 13</h1>
        <nav>
          <a href="index.php">Inicio</a>
          <a href="catalogo.php" class="active">Catálogo</a>
          <a href="dashboard.php">Dashboard</a>
        </nav>
      </div>
    </header>

    <main>
      <section class="catalog-top">
        <h2>Encuentra tu par</h2>
        <p class="lead">Calzado para todos los gustos. Selecciona color, talla y añade al carrito.</p>
      </section>

      <section class="catalog-container" id="catalogContainer"></section>
    </main>
  </div>

  <!-- Botón flotante -->
  <button class="cart-float-btn" id="cartFloatBtn" title="Ver carrito">
    🛒<span class="cart-float-badge" id="cartFloatBadge">0</span>
  </button>

  <!-- Panel deslizante -->
  <div class="cart-float-overlay" id="cartFloatOverlay"></div>
  <aside class="cart-float-panel" id="cartFloatPanel" aria-hidden="true">
    <button class="cart-float-close" id="cartFloatClose" aria-label="Cerrar carrito">×</button>
    <h3>Carrito</h3>
    <div class="cart-float-items" id="cartFloatItems"></div>
    <div class="cart-float-footer">
      <strong id="cartFloatTotal">Total: $0</strong>
      <a href="carrito.php" class="cart-float-link">Ir al carrito</a>
    </div>
  </aside>

  <script>
    /* Datos de ejemplo */
    const products = [
      { id: 1, name: "VANS", price: 125000, image: "https://static.dafiti.com.co/p/vans-8315-5174572-1-product.jpg", colors: ["black", "brown", "navy"], sizes: [38, 39, 40, 41, 42, 43] },
      { id: 2, name: "Nike air TN", price: 150000, image: "https://standshop.com.co/wp-content/uploads/2024/11/d75a5189-2a2e-4842-a707-0eade0130556.jpg", colors: ["red", "blue", "black", "gray"], sizes: [38, 39, 40, 41, 42, 43, 44, 45] },
      { id: 3, name: "Adidas Samba", price: 150000, image: "https://static.dafiti.com.co/p/adidas-originals-6590-653048-4-product.jpg", colors: ["black", "red", "nude", "silver"], sizes: [35, 36, 37, 38, 39, 40, 41, 42] },
      { id: 4, name: "Jordan Retro 1", price: 125000, image: "https://cdn-images.farfetch-contents.com/11/70/77/94/11707794_8097746_600.jpg", colors: ["white", "black", "blue", "green"], sizes: [38, 39, 40, 41, 42, 43, 44, 45] },
      { id: 5, name: "Converse All Star", price: 100000, image: "https://cdn.pixabay.com/photo/2013/07/12/18/20/shoes-153310_960_720.png", colors: ["brown", "tan", "navy", "black"], sizes: [40, 41, 42, 43, 44, 45] },
      { id: 6, name: "Air Force 1", price: 100000, image: "https://m.media-amazon.com/images/I/81uiWMk9dnL._AC_SX675_.jpg", colors: ["brown", "green", "black"], sizes: [39, 40, 41, 42, 43, 44, 45] },
      { id: 7, name: "Sandalias", price: 90000, image: "https://www.charleskeith.com/on/demandware.static/-/Library-Sites-CharlesKeith/default/dw7e760ed4/images/PeopleAlsoAsked/types-of-sandals/fall-sandals.jpg", colors: ["black", "white", "brown"], sizes: [35, 36, 37, 38, 39, 40, 41, 42, 43] },
      { id: 8, name: "New Balance 1906R", price: 150000, image: "https://images-cdn.ubuy.tn/67162b882fd19c2880667e5e-new-balance-1906r-nb-new-spruce-men.jpg", colors: ["black", "brown", "gray"], sizes: [37, 38, 39, 40, 41, 42, 43, 44] }
    ];

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function formatPrice(num) {
      return Number(num).toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
    }

    function renderCatalog() {
      const container = document.getElementById('catalogContainer');
      container.innerHTML = products.map(p => {
        const cartItem = cart.find(c => c.id === p.id);
        const isSelected = !!cartItem;
        const priceFormatted = formatPrice(p.price);
        return `
          <article class="product-card ${isSelected ? 'selected' : ''}" data-id="${p.id}">
            <div class="img-wrap">
              <img src="${p.image}" alt="${p.name}" class="product-image" loading="lazy">
            </div>
            <div class="product-info">
              <h4 class="product-name">${p.name}</h4>
              <div class="product-price">${priceFormatted}</div>

              <label class="sr-only">Color</label>
              <select class="color-select" data-id="${p.id}">
                <option value="">Color</option>
                ${p.colors.map(c => `<option value="${c}">${c}</option>`).join('')}
              </select>

              <label class="sr-only">Talla</label>
              <select class="size-select" data-id="${p.id}">
                <option value="">Talla</option>
                ${p.sizes.map(s => `<option value="${s}">${s}</option>`).join('')}
              </select>

              <div class="product-controls">
                <input type="number" class="qty-input" data-id="${p.id}" value="${cartItem ? cartItem.qty : 1}" min="1" max="10" aria-label="Cantidad">
                <button class="add-to-cart" data-id="${p.id}">${isSelected ? 'Actualizar' : 'Añadir'}</button>
              </div>
            </div>
          </article>
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
      const qty = parseInt(document.querySelector(`.qty-input[data-id="${productId}"]`).value) || 1;

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

    function toggleCart() {
      const open = panel.classList.toggle('open');
      overlay.classList.toggle('show', open);
      panel.setAttribute('aria-hidden', !open);
      if (open) updateFloatingCart();
    }

    btn.addEventListener('click', toggleCart);
    close.addEventListener('click', toggleCart);
    overlay.addEventListener('click', toggleCart);

    function updateFloatingCart() {
      itemsDiv.innerHTML = cart.length ? cart.map((item, i) => `
        <div class="cart-float-item" data-idx="${i}">
          <strong>${item.name}</strong>
          ${item.color} | ${item.size}<br>
          ${formatPrice(item.price)} x ${item.qty}
          <div class="cart-item-actions">
            <button class="remove-cart" data-idx="${i}" aria-label="Eliminar">Eliminar</button>
          </div>
        </div>
      `).join('') : '<p class="empty">El carrito está vacío</p>';

      const total = cart.reduce((s, i) => s + (i.price * i.qty), 0);
      totalSpan.innerText = 'Total: ' + formatPrice(total);
      badge.innerText = cart.reduce((s, i) => s + i.qty, 0);

      // agregar listeners para eliminar
      document.querySelectorAll('.remove-cart').forEach(btn => {
        btn.addEventListener('click', (e) => {
          removeFromCart(parseInt(e.target.dataset.idx, 10));
        });
      });
    }

    // Escuchar cambios en el carrito desde otras pestañas
    window.addEventListener('storage', () => {
      cart = JSON.parse(localStorage.getItem('cart')) || [];
      updateFloatingCart();
      renderCatalog();
    });

    // inicializar
    renderCatalog();
    updateFloatingCart();
  </script>
</body>
</html>
