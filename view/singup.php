<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro</title>
    <style>
        :root {
            font-family: system-ui, Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 24px;
            background: #f6f7f9;
        }

        .card {
            max-width: 420px;
            margin: auto;
            background: #fff;
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
        }

        .card h1 {
            font-size: 1.25rem;
            margin: 0 0 12px;
        }

        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            font-size: .95rem;
            margin-bottom: 6px;
        }

        .field input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d5d8de;
            border-radius: 10px;
            font-size: 1rem;
        }

        .row {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .hint {
            font-size: .85rem;
            color: #667085;
            margin-top: 6px;
        }

        .error {
            color: #b42318;
            font-size: .9rem;
            display: none;
            margin-top: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .primary {
            background: #1f6feb;
            color: white;
        }
    </style>
    <script>
    const base_url = '<?= BASE_URL; ?>';
  </script>
</head>

<body>
    <div class="container">
        <h4 class="mt-3 mb-3">Crear Cuenta</h4>

        <!-- REGISTRO -->
        <form id="frm_signup" autocomplete="off">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" minlength="2" maxlength="120" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo" maxlength="50" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" minlength="8" maxlength="120" required>
                    <small class="text-muted">Mínimo 8 caracteres.</small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Registrarme</button>
        </form>

        <hr class="my-4">

        <!-- VERIFICAR CÓDIGO -->
        <h6>Verificar código enviado a tu correo</h6>
        <form id="frm_verify" class="row gy-2 gx-2 align-items-center">
            <div class="col-md-4">
                <input type="email" class="form-control" id="correo_verify" placeholder="Tu correo" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="codigo_verify" placeholder="Código de 6 dígitos" maxlength="6" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Confirmar</button>
            </div>
        </form>

        <div class="mt-3">
            <button id="btn_resend" class="btn btn-secondary btn-sm">Reenviar código</button>
        </div>
    </div>
    <div class="container">
        <div class="mt-3">
            <a href="<?= BASE_URL ?>login" class="btn btn-primary">Iniciar Sesión</a>
        </div>
    </div>

    <script src="<?= BASE_URL ?>view/function/auth.js"></script>

</body>

</html>