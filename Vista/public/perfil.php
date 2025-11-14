<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();
$control = new ControlUsuario();
$mensaje = '';
$usuario = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $password = $_POST['password'];
    $usuario = $control->autenticar($nombreUsuario, $password);

    if ($usuario) {
        $session->iniciarSesion($usuario);
        header('Location: /PWD-TP-FINAL/Vista/public/index.php');
        exit();
    } else {
        $mensaje = 'Usuario o contraseña incorrectos';
    }
}

$idUsuario = $session->getIdUsuario();
$usuario = null;

if ($idUsuario) {
    $lista = $control->listarUsuarios("idusuario = $idUsuario");
    if (count($lista) > 0) {
        $usuario = $lista[0];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perfil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">
</head>

<body class="bg-light">

<main class="d-flex align-items-center justify-content-center min-vh-100">
  <?php if (!$usuario): ?>
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
      </form>
    </div>

  <?php else: ?>
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow-sm mb-4">
            <div class="card-body">
              <div class="d-flex align-items-center mb-3">
                <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
                <div class="ms-3">
                  <h3 class="fw-bold mb-0"><?= htmlspecialchars($usuario->getUsnombre()) ?></h3>
                  <p class="text-muted mb-0"><?= htmlspecialchars($usuario->getUsmail()) ?></p>
                </div>
              </div>
              <a href="/PWD-TP-FINAL/Vista/private/editarPerfil.php" class="btn btn-outline-primary w-100">
                <i class="bi bi-pencil-square me-2"></i>Modificar mis datos
              </a>
            </div>
          </div>

          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
              <i class="bi bi-bag-check me-2"></i> Mis compras
            </div>
            <div class="card-body">
              <p class="text-muted text-center mb-0">La visualización de compras estará disponible próximamente.</p>
            </div>
          </div>

          <div class="text-center mt-4">
            <a href="/PWD-TP-FINAL/Vista/private/logout.php" class="btn btn-outline-danger w-50">
              <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
            </a>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
