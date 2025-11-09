<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// También para mostrar notices y warnings
ini_set('error_reporting', E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//TODO if ($_SESSION['rol'] == "cliente"){
//TODO    $opciones = <li> <a href="/PWD-TP-FINAL/Vista/cliente/miCuenta.php" class="btn btn-outline-primary mb-3">Mi Cuenta</a> </li>
//TODO                <li> <a href="/PWD-TP-FINAL/Vista/publica/productos.php" class="btn btn-outline-primary mb-3">Ver Productos</a> </li>
//TODO                <li> <a href="/PWD-TP-FINAL/Vista/cliente/compras.php" class="btn btn-outline-primary mb-3">Mis Compras</a> </li>
//TODO }elseif($_SESSION['rol'] == "admin"){
//TODO    $opciones = <li> <a href="/PWD-TP-FINAL/Vista/admin/usuarios.php" class="btn btn-dark w-100 mb-3">Gestionar Usuarios</a> </li>
//TODO     <li> <a href="/PWD-TP-FINAL/Vista/admin/roles.php" class="btn btn-dark w-100 mb-3">Gestionar Roles</a> </li>
//TODO     <li> <a href="/PWD-TP-FINAL/Vista/admin/menu.php" class="btn btn-dark w-100 mb-3">Gestionar Menú</a> </li>
//TODO     <li> <a href="/PWD-TP-FINAL/Vista/admin/productos.php" class="btn btn-dark w-100 mb-3">Gestionar Productos</a> </li>
//TODO }

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
        <li class="nav-item"><a class="nav-link" href="/PWD-TP-FINAL/Vista/public/login.php">Login</a></li>
      </ul>
    </div>
  </div>
  <div class="dropdown-center">
    <button class="btn btn-secondary dropdown-toggle" 
            type="button" 
            data-bs-toggle="dropdown">
        <nav class="navbar-toggler-icon"></nav>
    </button>
    
    <ul class="dropdown-menu dropdown-menu-end">
      <!-- ACÁ IRIA LA VARIABLE $opciones -->
        <li><a class="dropdown-item" href="#">Opción 1</a></li>
    </ul>
</div>
</nav>

<div class="container mt-4">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

