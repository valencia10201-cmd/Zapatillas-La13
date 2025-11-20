<?php
// frontend/login.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Tienda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title text-center mb-4">Iniciar Sesión</h3>
            <form id="loginForm">
              <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
            <div id="mensaje" class="mt-3 text-center text-danger"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const loginForm = document.getElementById('loginForm');
    const mensaje = document.getElementById('mensaje');

    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      try {
        const res = await fetch('/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password })
        });

        const data = await res.json();

        if(data.success) {
          mensaje.classList.remove('text-danger');
          mensaje.classList.add('text-success');
          mensaje.innerText = 'Login exitoso! Redirigiendo...';
          setTimeout(() => { window.location.href = '/dashboard'; }, 1000);
        } else {
          mensaje.classList.remove('text-success');
          mensaje.classList.add('text-danger');
          mensaje.innerText = 'Correo o contraseña incorrecta';
        }

      } catch(err) {
        mensaje.innerText = 'Error en el servidor';
      }
    });
  </script>

</body>
</html>