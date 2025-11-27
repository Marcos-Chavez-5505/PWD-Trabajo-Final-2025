<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$session = new Session();
$usuarioActivo = $session->activa();
$nombreUsuario = $usuarioActivo ? $session->getUsuario() : null;
$rolUsuario = $usuarioActivo ? $session->getRol() : null;

$inicioLink = ($usuarioActivo && $rolUsuario === "Administrador") 
              ? "/PWD-TP-FINAL/Vista/admin/usuarios.php" 
              : "/PWD-TP-FINAL/Vista/public/index.php";
?>