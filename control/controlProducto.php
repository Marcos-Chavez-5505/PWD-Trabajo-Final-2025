<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/modelo/Producto.php";

class ControlProducto {
    private $modelo;

    public function __construct() {
        $this->modelo = new Producto();
    }

    public function listarProductos() {
        $resultado = [];
        $productos = $this->modelo->listar();
        if (!empty($productos)) {
            $resultado = $productos;
        }
        return $resultado;
    }

    public function buscarProducto($idproducto) {
        $resultado = null;
        $producto = new Producto();
        if ($producto->buscar($idproducto)) {
            $resultado = $producto;
        }
        return $resultado;
    }

    public function agregarProducto($pronombre, $prodetalle, $procantstock, $proprecio, $proimagen) {
        $resultado = false;
        $producto = new Producto();
        $producto->cargar($pronombre, $prodetalle, $procantstock, $proprecio, $proimagen);
        $idGenerado = $producto->insertar();
        if ($idGenerado) {
            $resultado = $idGenerado;
        }
        return $resultado;
    }

    public function modificarProducto($idproducto, $pronombre, $prodetalle, $procantstock, $proprecio, $proimagen) {
        $resultado = false;
        $producto = new Producto();
        if ($producto->buscar($idproducto)) {
            $producto->cargar($pronombre, $prodetalle, $procantstock, $proprecio, $proimagen);
            $producto->setIdproducto($idproducto);
            if ($producto->modificar()) {
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function eliminarProducto($idproducto) {
        $resultado = false;
        $producto = new Producto();
        if ($producto->buscar($idproducto)) {
            if ($producto->eliminar()) {
                $resultado = true;
            }
        }
        return $resultado;
    }

    public function reducirStock($idproducto, $cantidad = 1) {
        $resultado = false;
        if ((int)$cantidad > 0) {
            $producto = new Producto();
            if ($producto->buscar($idproducto)) {
                $stockActual = (int)$producto->getProcantstock();
                if ($stockActual >= $cantidad) {
                    $producto->setProcantstock($stockActual - $cantidad);
                    if ($producto->modificar()) {
                        $resultado = true;
                    }
                }
            }
        }
        return $resultado;
    }
}
