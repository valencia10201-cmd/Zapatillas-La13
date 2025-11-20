const contenedor = document.getElementById("productos");
const API_URL = "http://localhost:3000";

let productoActual = null;

// LISTAR PRODUCTOS
async function cargarProductos() {
  try {
    const res = await fetch(`${API_URL}/productos`);
    if (!res.ok) throw new Error("Error al cargar productos");
    
    const datos = await res.json();
    contenedor.innerHTML = "";

    datos.forEach(p => {
      const card = document.createElement("div");
      card.className = "card";
      card.innerHTML = `
        <h3>${escapeHtml(p.nombre)}</h3>
        <p>${escapeHtml(p.descripcion)}</p>
        <p>Precio: $${parseFloat(p.precio).toFixed(2)}</p>
        <p>Stock: ${p.stock}</p>
        <p>Categoría: ${p.categoria_id}</p>
        <button class="editar" onclick="abrirModal(${p.id})">Editar</button>
        <button onclick="eliminarProducto(${p.id})">Eliminar</button>
      `;
      contenedor.appendChild(card);
    });
  } catch (error) {
    console.error(error);
    contenedor.innerHTML = "<p>Error al cargar productos</p>";
  }
}

cargarProductos();

// CREAR PRODUCTO
async function crearProducto() {
  const nombre = document.getElementById("nombre").value.trim();
  const descripcion = document.getElementById("descripcion").value.trim();
  const precio = parseFloat(document.getElementById("precio").value);
  const stock = parseInt(document.getElementById("stock").value);
  const categoria_id = document.getElementById("categoria").value;

  if (!nombre || !descripcion || !precio || !stock || !categoria_id) {
    alert("Completa todos los campos");
    return;
  }

  try {
    const res = await fetch(`${API_URL}/productos`, {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({nombre, descripcion, precio, stock, categoria_id})
    });
    if (!res.ok) throw new Error("Error al crear producto");
    alert("Producto creado");
    cargarProductos();
  } catch (error) {
    console.error(error);
    alert("Error al crear producto");
  }
}

// ELIMINAR
async function eliminarProducto(id) {
  if (!confirm("¿Eliminar producto?")) return;
  
  try {
    const res = await fetch(`${API_URL}/productos/${id}`, {method: "DELETE"});
    if (!res.ok) throw new Error("Error al eliminar");
    cargarProductos();
  } catch (error) {
    console.error(error);
    alert("Error al eliminar producto");
  }
}

// MODAL EDITAR
async function abrirModal(id) {
  try {
    const res = await fetch(`${API_URL}/productos/${id}`);
    const prod = await res.json();
    productoActual = prod;

    document.getElementById("editNombre").value = prod.nombre;
    document.getElementById("editDescripcion").value = prod.descripcion;
    document.getElementById("editPrecio").value = prod.precio;
    document.getElementById("editStock").value = prod.stock;
    document.getElementById("editCategoria").value = prod.categoria_id;

    document.getElementById("modal").style.display = "flex";
  } catch (error) {
    console.error(error);
    alert("Error al abrir modal");
  }
}

function cerrarModal() {
  document.getElementById("modal").style.display = "none";
}

// GUARDAR EDICIÓN
async function guardarEdicion() {
  const data = {
    nombre: document.getElementById("editNombre").value.trim(),
    descripcion: document.getElementById("editDescripcion").value.trim(),
    precio: parseFloat(document.getElementById("editPrecio").value),
    stock: parseInt(document.getElementById("editStock").value),
    categoria_id: document.getElementById("editCategoria").value
  };

  try {
    const res = await fetch(`${API_URL}/productos/${productoActual.id}`, {
      method: "PUT",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify(data)
    });
    if (!res.ok) throw new Error("Error al actualizar");
    cerrarModal();
    cargarProductos();
  } catch (error) {
    console.error(error);
    alert("Error al guardar");
  }
}

// Función para escapar HTML
function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}
