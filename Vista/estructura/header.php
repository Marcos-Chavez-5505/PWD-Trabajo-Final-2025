<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";


// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// $opciones = "";
// $mostrarDropdown = false;//menu desplegable

// if (isset($_SESSION['rol'])) {

//   //se habilitara el menu despleglable si el usuario tiene un rol
//   $mostrarDropdown = true;

//   if($_SESSION['rol'] === "cliente") {
//     $opciones .= '
//       <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/cliente/miCuenta.php">Mi Cuenta</a></li>
//       <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/publica/productos.php">Ver Productos</a></li>
//     ';
//   }

//   if($_SESSION['rol'] === "admin") {
//     $opciones .= '
//       <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/admin/usuario.php">Gestionar Usuarios</a></li>
//       <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/admin/menu.php">Gestionar Menú</a></li>
//       <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/admin/rol.php">Gestionar Roles</a></li>
//       <li><a class="dropdown-item" href="/PWD-TP-FINAL/Vista/privado/logout.php">Cerrar Sesión</a></li>
//     ';
//   }
// }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    
    <a class="navbar-brand" href="/PWD-TP-FINAL/Vista/public/index.php">El Guapo Gamer</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/PWD-TP-FINAL/Vista/public/index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="/PWD-TP-FINAL/Vista/public/contacto.php">Contacto</a></li>

        <!--si no esta logueado el usuario aparecera esta opcion-->
        <?php if (!$mostrarDropdown): ?>
            <li class="nav-item"><a class="nav-link" href="/PWD-TP-FINAL/Vista/public/perfil.php">Perfil</a></li>
        <?php endif; ?>
        
        <li class="nav-item"><a class="nav-link" href="/PWD-TP-FINAL/Vista/public/carritoCliente.php">Carrito</a></li>
      </ul>
    </div>

    <!--el menu desplegable aparecera solo si esta logueado-->
    <?php if ($mostrarDropdown): ?>
    <div class="dropdown ms-3">
        <button class="btn btn-secondary dropdown-toggle" 
                type="button" 
                data-bs-toggle="dropdown">
            <?= $_SESSION['rol'] === "admin" ? "Admin" : "Mi Cuenta" ?>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">
            <?= $opciones ?>
        </ul>
    </div>
    <?php endif; ?>

  </div>
</nav>

<div class="container mt-4">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>