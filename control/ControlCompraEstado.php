<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
class ControlCompraEstado {
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }

    
    public function obtenerEstadoActual($idCompra) {
        $estadoActual = null;

        $objEstado = new compraEstado();
        $lista = $objEstado->listar("idcompra = {$idCompra} AND cefechafin IS NULL");

        if (!empty($lista)) {
            $estadoActual = $lista[0];
        }

        return $estadoActual;
    }

    public function tieneEstado($idCompra) {
        $tiene = false;

        $objEstado = new compraEstado();
        $lista = $objEstado->listar("idcompra = {$idCompra}");

        if (!empty($lista)) {
            $tiene = true;
        }

        return $tiene;
    }

    public function crearEstadoInicial($idCompra) {
        $exito = false;

        $objCompra = new compra();
        if ($objCompra->buscar($idCompra)) {

            $objTipo = new compraEstadoTipo();
            $objTipo->buscar(1);

            $objEstado = new compraEstado();
            $objEstado->setObjCompra($objCompra);
            $objEstado->setObjCompraEstadoTipo($objTipo);
            $objEstado->setCefechaini(date("Y-m-d H:i:s"));  
            $objEstado->setCefechafin(null);

            $exito = $objEstado->insertar();
        }

        return $exito;
    }

    public function cerrarEstadoActual($idCompra) {
        $exito = false;

        $actual = $this->obtenerEstadoActual($idCompra);

        if ($actual !== null) {
            $actual->setCefechafin(date("Y-m-d H:i:s"));
            $exito = $actual->modificar();
        }

        return $exito;
    }

    public function cambiarEstado($idCompra, $nuevoEstadoTipo) {
        $exito = false;

        $cerrado = $this->cerrarEstadoActual($idCompra);

        if ($cerrado) {
            $objCompra = new compra();
            $objCompra->buscar($idCompra);

            $objTipo = new compraEstadoTipo();
            $objTipo->buscar($nuevoEstadoTipo);

            $objEstado = new compraEstado();
            $objEstado->setObjCompra($objCompra);
            $objEstado->setObjCompraEstadoTipo($objTipo);
            $objEstado->setCefechaini(null);
            $objEstado->setCefechafin(null);

            $exito = $objEstado->insertar();
        }

        return $exito;
    }

    public function listarEstadosDeCompra($idCompra) {
        $listaEstados = [];

        $objEstado = new compraEstado();
        $lista = $objEstado->listar("idcompra = {$idCompra} ORDER BY cefechaini ASC");

        if (!empty($lista)) {
            $listaEstados = $lista;
        }

        return $listaEstados;
    }

    public function estadoActualEs($idCompra, $tipoEsperado) {
        $coincide = false;

        $actual = $this->obtenerEstadoActual($idCompra);

        if ($actual !== null) {
            if ($actual->getObjcompraEstadoTipo()->getIdcompraEstadoTipo() == $tipoEsperado) {
                $coincide = true;
            }
        }

        return $coincide;
    }

    public function obtenerIdCompraEstadoTipo($idCompra){
        $actual = $this->obtenerEstadoActual($idCompra);
        return ($actual !== null && $actual->getObjcompraEstadoTipo() !== null) ? $actual->getObjcompraEstadoTipo()->getIdcompraestadotipo() : null;
    }

    public function cancelarCompra($idCompra) {
        $estadoActual = $this->obtenerIdCompraEstadoTipo($idCompra);
        $resultado = false;
        
        if ($estadoActual != 4) {
            $resultado = $this->cambiarEstado($idCompra, 4);
        }
        
        return $resultado;
    }

    public function aceptarCompra($idCompra) {
        return $this->cambiarEstado($idCompra, 2);
    }

    public function iniciarCompra($idCompra) {
        $exito = true;
        $controlCompra = new ControlCompra();
        $objCompra = $controlCompra->buscarCompra($idCompra);
         
        if ($this->estadoActualEs($objCompra->getIdcompra(),1)){
            $exito = false;
        }else{
            $this->cambiarEstado($idCompra,1);
        }
        return $exito;
    }

   public function añadirEstadoCarrito($idCompra){
        $objCompraEstado = new compraEstado();
        $lista = $objCompraEstado->listar("idcompra = {$idCompra}");

        // Si no hay estado, crear estado inicial
        if (empty($lista)) {
            return $this->crearEstadoInicial($idCompra);
        }

        return false;
    }

    function procesarEstadoCompra($idCompra, $accion) {
    $response = ['success' => false];
    $controlCompraEstado = new ControlCompraEstado();

    switch ($accion) {
        case 'siguienteEstado':
            $idEstado = $controlCompraEstado->obtenerIdCompraEstadoTipo($idCompra);
            if (!is_null($idEstado)) {
                $proximoEstado = intval($idEstado) + 1;
                
                if ($proximoEstado <= 3 && $proximoEstado > 0) {
                    if ($controlCompraEstado->cambiarEstado($idCompra, $proximoEstado)) {
                        $response['success'] = true;
                        $response['message'] = "Estado cambiado a: " . $proximoEstado;

                        $mail = new MailerService();
                        $mail->generarMail($idCompra, $proximoEstado);
                    } else {
                        $response['error'] = "No se pudo cambiar al estado: " . $proximoEstado;
                    }
                } else {
                    $response['error'] = "El estado está fuera de los limites";
                }
            } else {
                $response['error'] = "El estado es nulo";
            }
            break;
            
        case 'cancelar':
            if ($controlCompraEstado->cancelarCompra($idCompra)) {
                $response['success'] = true;
                $response['message'] = "Compra cancelada exitosamente";

                $mail = new MailerService();
                $mail->generarMail($idCompra, 4);
            } else {
                $estadoActual = $controlCompraEstado->obtenerIdCompraEstadoTipo($idCompra);
                if ($estadoActual == 4) {
                    $response['error'] = "La compra ya está cancelada";                    
                } else {
                    $response['error'] = "No se pudo cancelar la compra";
                }
            }
            break;
            
        default:
            $response['error'] = "Acción no válida";
    }
    
    return $response;
}

}
?>