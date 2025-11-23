<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/usuarioRol.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/rol.php';

$session = new Session();
$control = new ControlUsuario();

$usuario = null;
$idUsuario = $session->getIdUsuario();

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">
</head>

<body class="bg-light">

<!-- Recibe un error (403, 401, 423) mediante GET desde authMiddleware.php en caso de acceder a una página con un rol incorrecto -->
  <?php if (isset($_GET['err']) && $_GET['err'] === '403'): ?>
    <div class="alert alert-danger text-center">
        Error 403: No tienes permisos para acceder a esa página.
    </div>
  
  <?php elseif (isset($_GET['err']) && $_GET['err'] === '401'): ?>
    <div class="alert alert-warning text-center">
        Error 401: Debes iniciar sesión para acceder a esa página.
    </div>

  <?php elseif (isset($_GET['err']) && $_GET['err'] === '423'): ?>
    <div class="alert alert-warning text-center">
        Error 423: Tu cuenta está deshabilitada.
    </div>
  <?php endif; ?>


<main class="d-flex align-items-center justify-content-center min-vh-100">
  <?php if (!$usuario): ?>
    <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
      <div class="text-center mb-4">
        <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
        <h2 class="fw-bold mt-2">Iniciar Sesión</h2>
        <p class="text-muted mb-0">Accede a tu cuenta</p>
      </div>

      <form method="POST" action="/PWD-TP-FINAL/Vista/action/accionLogin.php">
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

        <?php if (isset($_GET['errLogin'])): ?>
          <div class="alert alert-danger d-flex align-items-center py-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>Usuario o contraseña incorrectos.</div>
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
              <a href="/PWD-TP-FINAL/Vista/private/modificarCuenta.php" class="btn btn-outline-primary w-100">
                <i class="bi bi-pencil-square me-2"></i>Modificar mis datos
              </a>
            </div>
          </div>

          <div class="text-center mt-4">
            <a href="/PWD-TP-FINAL/Vista/action/logout.php" class="btn btn-outline-danger w-50">
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
