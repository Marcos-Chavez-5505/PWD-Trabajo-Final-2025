<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$data = data_submitted();
$retorno = ['respuesta' => false];

if (!empty($data['idmenu'])) {
    $resultado = (new AbmMenu())->baja($data);
    $retorno = [
        'respuesta' => $resultado['respuesta'],
        'errorMsg' => $resultado['mensaje'] ?? null
    ];
}

echo json_encode($retorno);
?>
