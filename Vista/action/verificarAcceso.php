<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/control/Session.php";

$session = new Session();

function verificarAcceso($rolRequerido = null) {
    global $session;

    $rutaActual = $_SERVER['REQUEST_URI'];

    if (strpos($rutaActual, '/Vista/public/cuenta.php') !== false) {
        return;
    }

    if (!$session->activa()) {
        header("Location: /PWD-TP-FINAL/Vista/public/cuenta.php");
        exit();
    }

    if ($rolRequerido !== null) {
        $rolUsuario = $session->getRol();
        if ($rolUsuario !== $rolRequerido) {
            echo "<h1 style='text-align:center; margin-top:50px;'>No tenés permisos para acceder a esta página.</h1>";
            exit();
        }
    }
}
?>
