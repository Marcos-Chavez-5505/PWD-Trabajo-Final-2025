<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD-TP-FINAL/vista/css/tpFinal.css">
    
    <style>
        .login-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        
        .login-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(106, 17, 203, 0.4);
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #e1e5e9;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.25rem rgba(106, 17, 203, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .login-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

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

<div class="login-container my-5">
    <div class="login-card shadow-lg">
        <div class="login-header">
            <i class="bi bi-person-circle login-icon"></i>
            <h2 class="mb-0">Iniciar Sesión</h2>
            <p class="mb-0 mt-2 opacity-75">Accede a tu cuenta</p>
        </div>
        
        <div class="login-body">
            <form method="POST">
                <div class="mb-4">
                    <label for="nombreUsuario" class="form-label">Nombre de usuario</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-person text-primary"></i>
                        </span>
                        <input type="text" class="form-control" name="nombreUsuario" id="nombreUsuario" required placeholder="Ingresa tu usuario">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-lock text-primary"></i>
                        </span>
                        <input type="password" class="form-control" name="password" id="password" required placeholder="Ingresa tu contraseña">
                    </div>
                </div>
                
                <?php if ($mensaje): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div><?php echo $mensaje; ?></div>
                    </div>
                <?php endif; ?>
                
                <button type="submit" class="btn btn-login text-white w-100 py-3 mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar
                </button>
                
                <div class="text-center">
                    <a href="#" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/vista/estructura/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>