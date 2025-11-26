<?php
$session = new Session();
$control = new ControlUsuario();

$usuario = null;
$idUsuario = $session->getIdUsuario();

if ($idUsuario) {
    $lista = $control->listarUsuarios("idusuario = $idUsuario");
    if (count($lista) > 0) {
        $usuario = $lista[0];
        $nombreUsuario = $usuario->getUsnombre();
        $mailUsuario = $usuario->getUsmail();
    }
}
?>