<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/bdCarritoCompras.php';

class ControlCarrito {
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }

    /** Busca o crea un carrito abierto (estado “Iniciada”) para el usuario */
    public function buscarCarritoAbierto($idUsuario) {
        $resultado = null;

        $compra = new compra();
        $compras = $compra->listar("idusuario = {$idUsuario} ORDER BY idcompra DESC");

        if (!empty($compras)) {
            foreach ($compras as $c) {
                $idCompra = $c->getIdcompra();
                $compraEstado = new compraEstado();
                $estado = $compraEstado->buscarCompraAsociada($idCompra);

                if ($estado) {
                    // buscarCompraAsociada() devuelve true si hay estado; el objeto queda cargado
                    $estadoTipo = $compraEstado->getObjCompraEstadoTipo()->getCETdescripcion();
                    if ($estadoTipo === 'Iniciada') {
                        $resultado = $idCompra;
                        break;
                    }
                } else {
                    // No tiene estado => está abierta
                    $resultado = $idCompra;
                    break;
                }
            }
        }

        // Si no encontró carrito existente, crear uno nuevo
        if ($resultado === null) {
            $usuario = new usuario();
            if (!$usuario->buscar($idUsuario)) {
                return null;
            }

            $nuevaCompra = new compra();
            $nuevaCompra->setObjUsuario($usuario);

            if ($nuevaCompra->insertar()) {
                $resultado = $nuevaCompra->getIdcompra();

                $estadoTipo = new compraEstadoTipo();
                $estadoTipo->buscarDescripcion("Iniciada");

                $nuevoEstado = new compraEstado();
                $nuevoEstado->setObjCompra($nuevaCompra);
                $nuevoEstado->setObjCompraEstadoTipo($estadoTipo);
                $nuevoEstado->insertar();
            }
        }

        return $resultado;
    }

    /** Agrega un producto al carrito del usuario */
    public function agregarAlCarrito($idUsuario, $idProducto, $cantidad = 1) {
        $resultado = false;
        $idCompra = $this->buscarCarritoAbierto($idUsuario);

        if ($idCompra !== null) {
            $producto = new producto();
            if ($producto->buscar($idProducto)) {
                $stock = (int)$producto->getProcantstock();

                if ($cantidad > 0 && $cantidad <= $stock) {
                    $compraItem = new compraItem();
                    $itemExistente = $compraItem->buscarPorCompraYProducto($idCompra, $idProducto);

                    if ($itemExistente) {
                        $cantActual = (int)$itemExistente->getCicantidad();
                        $nuevaCant = $cantActual + $cantidad;

                        if ($nuevaCant <= $stock) {
                            $itemExistente->setCicantidad($nuevaCant);
                            $resultado = $itemExistente->modificar();
                        }
                    } else {
                        $compraObj = new compra();
                        $compraObj->buscar($idCompra);

                        $nuevoItem = new compraItem();
                        $nuevoItem->setObjCompra($compraObj);
                        $nuevoItem->setObjProducto($producto);
                        $nuevoItem->setCicantidad($cantidad);

                        $resultado = $nuevoItem->insertar();
                    }
                }
            }
        }

        return $resultado;
    }

    /** Devuelve todos los items del carrito actual del usuario */
    public function obtenerItemsDelCarrito($idUsuario) {
        $resultado = [];
        $idCompra = $this->buscarCarritoAbierto($idUsuario);
        if ($idCompra !== null) {
            $compraItem = new compraItem();
            $items = $compraItem->listar("idcompra = {$idCompra}");
            if (!empty($items)) {
                foreach ($items as $item) {
                    $resultado[] = $item;
                }
            }
        }
        return $resultado;
    }

    /** Vacía el carrito del usuario */
    public function vaciarCarrito($idUsuario) {
        $resultado = true;
        $idCompra = $this->buscarCarritoAbierto($idUsuario);
        if ($idCompra !== null) {
            $compraItem = new compraItem();
            $items = $compraItem->listar("idcompra = {$idCompra}");
            if (!empty($items)) {
                foreach ($items as $item) {
                    if ($item->getObjProducto() !== null) {
                        $idProducto = $item->getObjProducto()->getIdproducto();
                        $ok = $compraItem->eliminarPorCompraYProducto($idCompra, $idProducto);
                        if (!$ok) $resultado = false;
                    } else {
                        $resultado = false;
                    }
                }
            }
        }
        return $resultado;
    }

    /** Calcula el total monetario del carrito */
    public function totalCarrito($idUsuario) {
        $total = 0.0;
        $idCompra = $this->buscarCarritoAbierto($idUsuario);
        if ($idCompra !== null) {
            $compraItem = new compraItem();
            $items = $compraItem->listar("idcompra = {$idCompra}");
            if (!empty($items)) {
                foreach ($items as $item) {
                    $cantidad = (int)$item->getCicantidad();
                    $precio = 0.0;
                    if ($item->getObjProducto() !== null) {
                        $precio = (float)$item->getObjProducto()->getProprecio();
                    }
                    $total += $cantidad * $precio;
                }
            }
        }
        return $total;
    }

    public function comprarCarrito($idUsuario) {
        $controlCompraEstado = new ControlCompraEstado();
        $controlCompra = new ControlCompra();
        $exito = false;

        $comprasSinEstado = $controlCompra->obtenerComprasPorUsuario($idUsuario);
        
        foreach ($comprasSinEstado as $compra) {
            $idCompra = $compra->getIdcompra();
            if (!$exito && $controlCompraEstado->añadirEstadoCarrito($idCompra)) {
                $exito = true;
            }
        }
        
        return $exito;
    }

    public function obtenerItemsSinEstado($idUsuario) {
    $resultado = array();
    $compra = new compra();
    $comprasSinEstado = $compra->listarComprasSinEstado("idusuario = {$idUsuario}");
    $exito = false;
    
    if (empty($comprasSinEstado)) {
        $nuevaCompra = new compra();
        $usuario = new usuario();
        $usuario->setIdusuario($idUsuario);
        $nuevaCompra->setObjUsuario($usuario);
        $exito = $nuevaCompra->insertar();
        
        if ($exito) {
            $idCompraNueva = $nuevaCompra->getIdcompra();
            if (!empty($idCompraNueva)) {
                $comprasSinEstado = array($nuevaCompra);
            }
        }
    }
    
    if (!empty($comprasSinEstado)) {
        $compraItem = new compraItem();
        
        foreach ($comprasSinEstado as $compraObj) {
            $idCompra = $compraObj->getIdcompra();
            if (!empty($idCompra)) {
                $items = $compraItem->listar("idcompra = {$idCompra}");
                $resultado = array_merge($resultado, $items);
            }
        }
    }
    
    return $resultado;
}
}

?>
