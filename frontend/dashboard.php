<?php
// frontend/index.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda La13 - Admin Productos</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
    <h1>Panel de Productos</h1>
  </header>

  <section id="formulario">
    <h2>Agregar Producto</h2>

    <input type="text" id="nombre" placeholder="Nombre">
    <input type="text" id="descripcion" placeholder="Descripción">
    <input type="number" id="precio" placeholder="Precio">
    <input type="number" id="stock" placeholder="Stock">
    <input type="number" id="categoria" placeholder="ID Categoría">

    <button onclick="crearProducto()">Guardar producto</button>
  </section>

  <section id="lista">
    <h2>Productos registrados</h2>
    <div id="productos"></div>
  </section>

  <!-- Modal editar -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <h2>Editar Producto</h2>

      <input type="text" id="editNombre">
      <input type="text" id="editDescripcion">
      <input type="number" id="editPrecio">
      <input type="number" id="editStock">
      <input type="number" id="editCategoria">

      <button onclick="guardarEdicion()">Guardar cambios</button>
      <button onclick="cerrarModal()">Cancelar</button>
    </div>
  </div>

  <script src="app.js"></script>
</body>
</html>