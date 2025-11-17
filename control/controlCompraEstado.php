<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
class ControlCompraEstado {
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }

    
    public function obtenerEstadoActual($idCompra) {
        $estadoActual = null;

        $objEstado = new CompraEstado();
        $lista = $objEstado->listar("idcompra = {$idCompra} AND cefechafin IS NULL");

        if (!empty($lista)) {
            $estadoActual = $lista[0];
        }

        return $estadoActual;
    }

    public function tieneEstado($idCompra) {
        $tiene = false;

        $objEstado = new CompraEstado();
        $lista = $objEstado->listar("idcompra = {$idCompra}");

        if (!empty($lista)) {
            $tiene = true;
        }

        return $tiene;
    }

    public function crearEstadoInicial($idCompra) {
        $exito = false;

        $objCompra = new Compra();
        if ($objCompra->buscar($idCompra)) {

            $objTipo = new CompraEstadoTipo();
            $objTipo->buscar(1);

            $objEstado = new CompraEstado();
            $objEstado->setObjCompra($objCompra);
            $objEstado->setObjCompraEstadoTipo($objTipo);
            $objEstado->setCefechaini(null);
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
            $objCompra = new Compra();
            $objCompra->buscar($idCompra);

            $objTipo = new CompraEstadoTipo();
            $objTipo->buscar($nuevoEstadoTipo);

            $objEstado = new CompraEstado();
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

        $objEstado = new CompraEstado();
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
            if ($actual->getObjCompraEstadoTipo()->getIdcompraestadotipo() == $tipoEsperado) {
                $coincide = true;
            }
        }

        return $coincide;
    }


    public function cancelarCompra($idCompra) {
        return $this->cambiarEstado($idCompra, 4);
    }

    public function aceptarCompra($idCompra) {
        return $this->cambiarEstado($idCompra, 2);
    }

}
?>