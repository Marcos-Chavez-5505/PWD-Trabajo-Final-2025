<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$data = data_submitted();
$retorno = [];
$mensaje = null;

if (isset($data['menombre'])) {
    $objC = new AbmMenu();
    $resultado = $objC->alta($data);

    $respuesta = $resultado['respuesta'];
    $mensaje = $resultado['mensaje'];
}

$retorno['respuesta'] = $respuesta ?? false;

if (!empty($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}

echo json_encode($retorno);
?>