<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/Vista/action/accionRolesPermitidos.php";

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

$session = new Session();
$session->validar();

$usuarioActual = $session->getUsuario() ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cambiar Contraseña</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">
</head>

<body class="bg-light">
<main class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-sm p-4 cambiar-card">
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock text-danger cambiar-icon"></i>
            <h2 class="fw-bold mt-2">Cambiar Contraseña</h2>
            <p class="text-muted">Actualiza tu contraseña de acceso</p>
        </div>

        <form method="POST" name="formCambiarContraseña" id="formCambiarContraseña">
            
            <input type="hidden" id="username" name="username" value="<?php echo htmlspecialchars($usuarioActual); ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">Usuario actual</label>
                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($usuarioActual); ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="currentPassword" class="form-label fw-semibold">Contraseña actual</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-lock text-primary"></i>
                    </span>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required placeholder="Ingresa tu contraseña actual">
                    <button class="btn btn-outline-secondary toggle-btn" type="button" id="toggleCurrentPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="newPassword" class="form-label fw-semibold">Nueva contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock text-primary"></i>
                    </span>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required placeholder="Ingresa tu nueva contraseña">
                    <button class="btn btn-outline-secondary toggle-btn" type="button" id="toggleNewPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="confirmPassword" class="form-label fw-semibold">Confirmar nueva contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock text-primary"></i>
                    </span>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required placeholder="Confirma tu nueva contraseña">
                    <button class="btn btn-outline-secondary toggle-btn" type="button" id="toggleConfirmPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="/PWD-TP-FINAL/Vista/public/index.php" class="btn btn-outline-primary w-50 py-2 cambiar-btn-volver">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
                <button type="submit" class="btn btn-primary w-50 py-2 cambiar-btn-actualizar">
                    <i class="bi bi-check-circle me-2"></i>Actualizar
                </button>
            </div>
        </form>
    </div>
</main>

<div class="modal fade" id="modalResultado" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="modalTitulo">Resultado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4" id="modalMensaje"></div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/PWD-TP-FINAL/Vista/js/cambiarContraseña.js"></script>
</body>
</html>

<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/footer.php";
?>
