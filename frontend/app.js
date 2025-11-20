const contenedor = document.getElementById("productos");

let productoActual = null;

// LISTAR PRODUCTOS
async function cargarProductos() {
  const res = await fetch("http://localhost:3000/productos");
  const datos = await res.json();

  contenedor.innerHTML = "";

  datos.forEach(p => {
    contenedor.innerHTML += `
      <div class="card">
        <h3>${p.nombre}</h3>
        <p>${p.descripcion}</p>
        <p>Precio: $${p.precio}</p>
        <p>Stock: ${p.stock}</p>
        <p>Categoría: ${p.categoria_id}</p>

        <button class="editar" onclick='abrirModal(${JSON.stringify(p)})'>Editar</button>
        <button onclick="eliminarProducto(${p.id})">Eliminar</button>
      </div>
    `;
  });
}

cargarProductos();

// CREAR PRODUCTO
async function crearProducto() {
  const data = {
    nombre: document.getElementById("nombre").value,
    descripcion: document.getElementById("descripcion").value,
    precio: document.getElementById("precio").value,
    stock: document.getElementById("stock").value,
    categoria_id: document.getElementById("categoria").value
  };

  await fetch("http://localhost:3000/productos", {
    method: "POST",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify(data)
  });

  cargarProductos();
}

// ELIMINAR
async function eliminarProducto(id) {
  await fetch(`http://localhost:3000/productos/${id}`, {
    method: "DELETE"
  });
  cargarProductos();
}

// MODAL EDITAR
function abrirModal(prod) {
  productoActual = prod;

  document.getElementById("editNombre").value = prod.nombre;
  document.getElementById("editDescripcion").value = prod.descripcion;
  document.getElementById("editPrecio").value = prod.precio;
  document.getElementById("editStock").value = prod.stock;
  document.getElementById("editCategoria").value = prod.categoria_id;

  document.getElementById("modal").style.display = "flex";
}

function cerrarModal() {
  document.getElementById("modal").style.display = "none";
}

// GUARDAR EDICIÓN
async function guardarEdicion() {
  const data = {
    nombre: document.getElementById("editNombre").value,
    descripcion: document.getElementById("editDescripcion").value,
    precio: document.getElementById("editPrecio").value,
    stock: document.getElementById("editStock").value,
    categoria_id: document.getElementById("editCategoria").value
  };

  await fetch(`http://localhost:3000/productos/${productoActual.id}`, {
    method: "PUT",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify(data)
  });

  cerrarModal();
  cargarProductos();
}
