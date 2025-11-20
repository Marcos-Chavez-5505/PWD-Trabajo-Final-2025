<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

$controlCompra = new ControlCompra();
$response = ['success' => false];

try {
    $compras = $controlCompra->obtenerComprasConEstadoYUsuario();
    
    $comprasConEstado = array_filter($compras, function($compra) {
        return $compra['tiene_estado'];  
    });

    // Reindexar el array para que empiece desde 0
    $comprasConEstado = array_values($comprasConEstado);

    $response = [
        'success' => true,
        'total' => count($comprasConEstado),
        'rows' => $comprasConEstado
    ];

    if (count($comprasConEstado) === 0) {
        $response['message'] = 'No hay compras con estados asignados';
    }

} catch (Exception $e) {
    $response['error'] = 'Error al obtener compras: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>