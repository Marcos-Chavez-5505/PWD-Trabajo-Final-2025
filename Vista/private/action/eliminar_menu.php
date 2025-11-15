<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
$data = data_submitted();

if (isset($data['idmenu'])){
    $objC = new AbmMenu();
    $respuesta = $objC->baja($data);
    if (!$respuesta){
        $mensaje = " La accion  ELIMINACION No pudo concretarse";
    }
}

$retorno['respuesta'] = $respuesta;
if (isset($mensaje)){
   
    $retorno['errorMsg']=$mensaje;

}
    echo json_encode($retorno);
?>