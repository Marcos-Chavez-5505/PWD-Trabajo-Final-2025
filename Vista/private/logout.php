<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/control/Session.php';

$session = new Session();

if ($session->activa()) {
    $session->cerrar();
}

header('Location: /PWD-TP-FINAL/Vista/public/index.php');
exit();
