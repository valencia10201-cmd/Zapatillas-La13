<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="dashboard-container">

    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Mi Dashboard</h2>
      <a href="#">Inicio</a>
      <a href="#">Usuarios</a>
      <a href="#">Productos</a>
      <a href="#">Pedidos</a>
      <a href="#">Inventario</a>
      <a href="#">Reportes</a>
      <a href="#">Clientes</a>
      <a href="#">Proveedores</a>
      <a href="#">Configuración</a>
      <a href="#">Cerrar sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
      <h1>Bienvenido al Dashboard</h1>

      <!-- Tablas (9) -->
      <div class="card">
        <h3>Usuarios</h3>
        <table>
          <thead>
            <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Juan</td><td>juan@example.com</td><td>Admin</td></tr>
            <tr><td>2</td><td>Maria</td><td>maria@example.com</td><td>Usuario</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3>Productos</h3>
        <table>
          <thead>
            <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Zapato A</td><td>50</td><td>20</td></tr>
            <tr><td>2</td><td>Zapato B</td><td>70</td><td>15</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3>Pedidos</h3>
        <table>
          <thead>
            <tr><th>ID</th><th>Cliente</th><th>Producto</th><th>Estado</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Juan</td><td>Zapato A</td><td>Enviado</td></tr>
            <tr><td>2</td><td>Maria</td><td>Zapato B</td><td>Pendiente</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3>Inventario</h3>
        <table>
          <thead>
            <tr><th>ID</th><th>Producto</th><th>Cantidad</th><th>Ubicación</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Zapato A</td><td>20</td><td>Bodega 1</td></tr>
            <tr><td>2</td><td>Zapato B</td><td>15</td><td>Bodega 2</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3>Clientes</h3>
        <table>
          <thead>
            <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Carlos</td><td>carlos@mail.com</td><td>555-1111</td></tr>
            <tr><td>2</td><td>Ana</td><td>ana@mail.com</td><td>555-2222</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3>Proveedores</h3>
        <table>
          <thead>
            <tr><th>ID</th><th>Nombre</th><th>Producto</th><th>Contacto</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Proveedor A</td><td>Zapatos</td><td>provea@mail.com</td></tr>
            <tr><td>2</td><td>Proveedor B</td><td>Accesorios</td><td>proveb@mail.com</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3>Reportes</h3>
        <table>
          <thead>
            <tr><th>ID</th><th>Tipo</th><th>Fecha</th><th>Estado</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Ventas</td><td>2025-11-14</td><td>Generado</td></tr>
            <tr><td>2</td><td>Stock</td><td>2025-11-14</td><td>Pendiente</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3>Configuración</h3>
        <table>
          <thead>
            <tr><th>ID</th><th>Opción</th><th>Valor</th><th>Estado</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Idioma</td><td>Español</td><td>Activo</td></tr>
            <tr><td>2</td><td>Moneda</td><td>COP</td><td>Activo</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h3>Usuarios con roles</h3>
        <table>
          <thead>
            <tr><th>ID</th><th>Nombre</th><th>Rol</th><th>Último login</th></tr>
          </thead>
          <tbody>
            <tr><td>1</td><td>Juan</td><td>Admin</td><td>2025-11-14</td></tr>
            <tr><td>2</td><td>Maria</td><td>Usuario</td><td>2025-11-13</td></tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</body>
</html>
