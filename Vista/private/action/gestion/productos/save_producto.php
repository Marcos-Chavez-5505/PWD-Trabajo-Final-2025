<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$id = $_POST['id'] ?? $_GET['id'] ?? null;

try {
    $controlAdmin = new controlAdmin();
    
    switch($action) {
        case 'create':
            $datos = [
                'pronombre' => $_POST['pronombre'] ?? '',
                'prodetalle' => $_POST['prodetalle'] ?? '',
                'procantstock' => $_POST['procantstock'] ?? 0,
                'proprecio' => $_POST['proprecio'] ?? 0,
                'proimagen' => $_POST['proimagen'] ?? ''
            ];
            $resultado = $controlAdmin->añadirProducto($datos);
            echo json_encode(['success' => $resultado !== false]);
            break;
            
        case 'update':
            $datos = [
                'pronombre' => $_POST['pronombre'] ?? '',
                'prodetalle' => $_POST['prodetalle'] ?? '',
                'procantstock' => $_POST['procantstock'] ?? 0,
                'proprecio' => $_POST['proprecio'] ?? 0,
                'proimagen' => $_POST['proimagen'] ?? ''
            ];
            $resultado = $controlAdmin->actualizarProducto($id, $datos);
            echo json_encode(['success' => $resultado]);
            break;
            
        case 'delete':
            $resultado = $controlAdmin->eliminarProducto($id);
            echo json_encode(['success' => $resultado]);
            break;
            
        default:
            echo json_encode(['success' => false, 'errorMsg' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => $e->getMessage()]);
}
?>