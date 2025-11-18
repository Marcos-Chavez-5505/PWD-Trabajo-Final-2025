<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

// DEBUG: Ver qué está llegando
error_log("=== DEBUG cambiarEstado.php ===");
error_log("GET: " . print_r($_POST, true));
error_log("POST: " . print_r($_POST, true));


$response = ['success' => false];

if (isset($_POST['idcompra']) && isset($_POST['accion'])){
    $idCompra = $_POST['idcompra'];
    $accion = $_POST['accion'];
    $controlCompraEstado = new ControlCompraEstado();

    switch ($accion){
        case 'siguienteEstado':
            $idEstado = $controlCompraEstado->obtenerIdCompraEstadoTipo($idCompra);
            if (!is_null($idEstado)){
                $proximoEstado = intval($idEstado) + 1;
                
                if ($proximoEstado <= 4 && $proximoEstado > 0){
                    if($controlCompraEstado->cambiarEstado($idCompra,$proximoEstado)){
                        $response['success'] = true;
                        $response['message'] = "Estado cambiado a: " . $proximoEstado;

                        $mail = new MailerService();
                        $mail->generarMail($idCompra, $proximoEstado);

                    }else{
                        $response['success'] = false;
                        $response['error'] = "No se pudo cambiar al estado: " . $proximoEstado;
                    }
                }else{
                    $response['success'] = false;
                    $response['error'] = "El estado está fuera de los limites";
                }
            }else{
                $response['success'] = false;
                $response['error'] = "El estado es nulo";
            }
            break;
        case 'cancelar':
            if ($controlCompraEstado->cancelarCompra($idCompra)){
                $response['success'] = true;
                $response['message'] = "Compra cancelada exitosamente";
            } else {
                $estadoActual = $controlCompraEstado->obtenerIdCompraEstadoTipo($idCompra);
                if ($estadoActual == 4) {
                    $response['success'] = false;
                    $response['error'] = "La compra ya está cancelada";
                    
                    $mail = new MailerService();
                    $mail->generarMail($idCompra, $estadoActual);
                    
                } else {
                    $response['success'] = false;
                    $response['error'] = "No se pudo cancelar la compra";
                }
            }
            break;
        default:
            $response['error'] = "Acción no válida";
    }
} else {
    $response['error'] = 'Datos incompletos';
}

header('Content-Type: application/json');
echo json_encode($response);
?>