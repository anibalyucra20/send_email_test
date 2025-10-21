<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - SIGI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box {
      background: white;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
    }
    .login-box h2 {
      margin-bottom: 1.5rem;
    }
    .logo {
      width: 80px;
      margin-bottom: 1rem;
    }
  </style>
  <script>
    const base_url = '<?= BASE_URL; ?>';
  </script>
</head>
<body>

<div class="login-box text-center">
  <img src="https://upload.wikimedia.org/wikipedia/commons/8/89/HD_transparent_picture.png" alt="Logo" class="logo">
  <h2>Iniciar Sesión</h2>
  <form id="frm_login">
    <div class="mb-3 text-start">
      <label for="correo" class="form-label">Correo</label>
      <input type="text" class="form-control" id="correo" name="correo" required>
    </div>
    <div class="mb-3 text-start">
      <label for="contrasena" class="form-label">Contraseña</label>
      <input type="password" class="form-control" id="contrasena" name="contrasena" required>
    </div>
    <button type="button" class="btn btn-primary w-100" onclick="iniciar_sesion();">Ingresar</button>
  </form>
  <p class="mt-3 mb-0 text-muted">© 2025 Sistema SIGI</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL; ?>view/function/user.js"></script>
</body>
</html>
