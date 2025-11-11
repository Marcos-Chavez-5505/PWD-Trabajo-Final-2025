<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Iniciar Sesión</title>

<!-- Bootstrap CSS (siempre primero) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Tu CSS personalizado (debe ir después de Bootstrap para sobrescribirlo) -->
<link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">

</head>

<body class="bg-light">

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $password = $_POST['password'];

    $control = new ControlUsuario();
    $usuario = $control->autenticar($nombreUsuario, $password);

    if ($usuario) {
        $session = new Session();
        $session->iniciar(
            $usuario->getIdusuario(),
            $usuario->getUsnombre(),
            $usuario->getUspass()
        );
        header('Location: /PWD-TP-FINAL/Vista/private/inicio.php');
        exit();
    } else {
        $mensaje = 'Usuario o contraseña incorrectos';
    }
}
?>

<!-- Login -->
<main class="d-flex align-items-center justify-content-center min-vh-100">
  <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
    <div class="text-center mb-4">
      <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
      <h2 class="fw-bold mt-2">Iniciar Sesión</h2>
      <p class="text-muted mb-0">Accede a tu cuenta</p>
    </div>

    <form method="POST">
      <div class="mb-3">
        <label for="nombreUsuario" class="form-label fw-semibold">Nombre de usuario</label>
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-person text-primary"></i>
          </span>
          <input type="text" class="form-control border-start-0" id="nombreUsuario" name="nombreUsuario" required placeholder="Ingresa tu usuario">
        </div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Contraseña</label>
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-lock text-primary"></i>
          </span>
          <input type="password" class="form-control border-start-0" id="password" name="password" required placeholder="Ingresa tu contraseña">
        </div>
      </div>

      <?php if ($mensaje): ?>
        <div class="alert alert-danger d-flex align-items-center py-2" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div><?= $mensaje ?></div>
        </div>
      <?php endif; ?>

      <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
      </button>

      <div class="text-center mt-3">
        <a href="#" class="text-decoration-none forgot-link small">¿Olvidaste tu contraseña?</a>
      </div>
    </form>
  </div>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
