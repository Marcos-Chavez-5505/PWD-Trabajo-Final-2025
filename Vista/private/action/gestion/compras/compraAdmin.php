<?php
//! Respuesta de obtenerComprasConEstadoYUsuario()
//* $compras = [
//*     0 => [
//*         'idcompra' => 2,
//*         'fecha' => '2025-11-17 16:17:57',
//*         'id_usuario' => 2,
//*         'nombre_usuario' => 'cliente1',
//*         'email_usuario' => 'cliente1@correo.com',
//*         'estado_actual' => 'Sin estado',
//*         'fecha_estado' => null,
//*         'tiene_estado' => false
//*     ],
//*     1 => [
//*         'idcompra' => 1,
//*         'fecha' => '2025-11-17 16:11:45',
//*         'id_usuario' => 1,
//*         'nombre_usuario' => 'admin',
//*         'email_usuario' => 'admin@tienda.com',
//*         'estado_actual' => 'iniciada',
//*         'fecha_estado' => null,
//*         'tiene_estado' => true
//*     ]
//* ];
//* despues lo filtro con array_filter para mantener solo los que tienen estado distinto de null

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