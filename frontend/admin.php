<!-- frontend/admin.html -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Admin - Tienda La13</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Panel Admin</h1>
    <nav>
      <a href="catalogo.html">Ver catálogo</a>
    </nav>
  </header>

  <main>
    <section>
      <h2>Productos</h2>
      <div id="admin-productos"></div>

      <h3>Crear producto</h3>
      <input id="p-nombre" placeholder="Nombre">
      <input id="p-descripcion" placeholder="Descripción">
      <input id="p-precio" placeholder="Precio" type="number">
      <input id="p-stock" placeholder="Stock" type="number">
      <input id="p-cat" placeholder="ID categoría" type="number">
      <button id="crear-p-btn">Crear producto</button>
    </section>

    <section>
      <h2>Categorías</h2>
      <div id="admin-categorias"></div>

      <input id="c-nombre" placeholder="Nombre categoría">
      <button id="crear-c-btn">Crear categoría</button>
    </section>
  </main>

  <script>
    const API = "http://localhost:3000";

    async function cargarProductosAdmin() {
      const res = await fetch(API + "/productos");
      const data = await res.json();
      const cont = document.getElementById("admin-productos");
      cont.innerHTML = "";
      data.forEach(p => {
        const div = document.createElement("div");
        div.className = "card";
        div.innerHTML = `
          <h3>${p.nombre}</h3>
          <p>${p.descripcion || ""}</p>
          <p>Precio: $${p.precio}</p>
          <p>Stock: ${p.stock}</p>
          <p>Cat: ${p.categoria_id}</p>
          <button onclick='editar(${p.id})'>Editar</button>
          <button onclick='borrar(${p.id})'>Eliminar</button>
        `;
        cont.appendChild(div);
      });
    }

    async function cargarCategoriasAdmin() {
      const res = await fetch(API + "/categorias");
      const data = await res.json();
      const cont = document.getElementById("admin-categorias");
      cont.innerHTML = "";
      data.forEach(c => {
        const div = document.createElement("div");
        div.className = "card";
        div.innerHTML = `<strong>ID ${c.id}</strong> ${c.nombre} <button onclick='borrarCat(${c.id})'>Eliminar</button>`;
        cont.appendChild(div);
      });
    }

    document.getElementById("crear-p-btn").addEventListener("click", async () => {
      const nombre = document.getElementById("p-nombre").value;
      const descripcion = document.getElementById("p-descripcion").value;
      const precio = Number(document.getElementById("p-precio").value);
      const stock = Number(document.getElementById("p-stock").value);
      const categoria_id = Number(document.getElementById("p-cat").value);

      await fetch(API + "/productos", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({ nombre, descripcion, precio, stock, categoria_id })
      });
      cargarProductosAdmin();
    });

    document.getElementById("crear-c-btn").addEventListener("click", async () => {
      const nombre = document.getElementById("c-nombre").value;
      await fetch(API + "/categorias", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({ nombre })
      });
      cargarCategoriasAdmin();
    });

    async function borrar(id) {
      if (!confirm("Eliminar producto?")) return;
      await fetch(API + "/productos/" + id, { method: "DELETE" });
      cargarProductosAdmin();
    }
    async function borrarCat(id) {
      if (!confirm("Eliminar categoría?")) return;
      await fetch(API + "/categorias/" + id, { method: "DELETE" });
      cargarCategoriasAdmin();
    }

    // editar simple: prompt para cada campo
    async function editar(id) {
      const res = await fetch(API + "/productos/" + id);
      let prod = await res.json();
      if (!prod) return alert("No encontrado");
      const nombre = prompt("Nombre", prod.nombre) || prod.nombre;
      const descripcion = prompt("Descripción", prod.descripcion) || prod.descripcion;
      const precio = Number(prompt("Precio", prod.precio) || prod.precio);
      const stock = Number(prompt("Stock", prod.stock) || prod.stock);
      const categoria_id = Number(prompt("Categoria ID", prod.categoria_id) || prod.categoria_id);

      await fetch(API + "/productos/" + id, {
        method: "PUT",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({ nombre, descripcion, precio, stock, categoria_id })
      });
      cargarProductosAdmin();
    }

    // GET single product route (helper)
    window.onload = () => {
      cargarProductosAdmin();
      cargarCategoriasAdmin();
    };
  </script>
</body>
</html>
