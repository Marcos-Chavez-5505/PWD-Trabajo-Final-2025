<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/bdCarritoCompras.php';

class ControlCarrito {
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }

    // public function buscarCarritoAbierto($idUsuario) {
    //     $resultado = null;

    //     $compra = new compra();
    //     $compras = $compra->listar("idusuario = {$idUsuario} ORDER BY idcompra DESC");

    //     if (!empty($compras)) {
    //         foreach ($compras as $c) {
    //             if ($resultado === null) {
    //                 $idCompra = $c->getIdcompra();
    //                 $compraEstado = new compraEstado();
    //                 $estado = $compraEstado->buscarCompraAsociada($idCompra);

    //                 if ($estado) {
    //                     $estadoTipo = $compraEstado->getObjCompraEstadoTipo()->getCETdescripcion();
    //                     if ($estadoTipo === 'Iniciada') {
    //                         $resultado = $idCompra;
    //                     }
    //                 } else {
    //                     $resultado = $idCompra;
    //                 }
    //             }
    //         }
    //     }

    //     if ($resultado === null) {
    //         $usuario = new usuario();
    //         $usuarioExiste = $usuario->buscar($idUsuario);

    //         if ($usuarioExiste) {
    //             $nuevaCompra = new compra();
    //             $nuevaCompra->setObjUsuario($usuario);

    //             if ($nuevaCompra->insertar()) {
    //                 $resultado = $nuevaCompra->getIdcompra();

    //                 $estadoTipo = new compraEstadoTipo();
    //                 $estadoTipo->buscarDescripcion("Iniciada");

    //                 $nuevoEstado = new compraEstado();
    //                 $nuevoEstado->setObjCompra($nuevaCompra);
    //                 $nuevoEstado->setObjCompraEstadoTipo($estadoTipo);
    //                 $nuevoEstado->insertar();
    //             }
    //         }
    //     }

    //     return $resultado;
    // }
    
    /**
     * Busca la primer compra (carrito) asociado al usuario con $idUsuario y retorna idcompra.
     * En caso de no hallar compras (sin estado) asociadas al usuario, se crea una nueva compra
     */
    public function buscarCarritoAbierto($idUsuario) {
        $resultado = null;

        $compra = new Compra();
        $compras = $compra->listar("idusuario = {$idUsuario} ORDER BY idcompra DESC");

        if (!empty($compras)) {
            foreach ($compras as $c) {
                $idCompra = $c->getIdcompra();

                $compraEstado = new CompraEstado();
                $tieneEstado = $compraEstado->buscarCompraAsociada($idCompra);

                if (!$tieneEstado) {
                    $resultado = $idCompra;
                    break;
                }
            }
        }
        // si no encontro carrito
        if ($resultado === null) {
            $usuario = new Usuario();
            $usuario->buscar($idUsuario);

            $nuevaCompra = new Compra();
            $nuevaCompra->setObjUsuario($usuario);

            $insertOk = $nuevaCompra->insertar();
            if ($insertOk) {
                $ultimas = $compra->listar("idusuario = {$idUsuario} ORDER BY idcompra DESC LIMIT 1");
                if (!empty($ultimas)) {
                    $resultado = $ultimas[0]->getIdcompra();
                }
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

    /** Devuelve todos los items de la compra sin estado o “Iniciada” */
    public function obtenerItemsSinEstado($idUsuario) {
        $resultado = [];
        $compra = new compra();
        
        // Buscar compras sin estado o con estado "Iniciada"
        $comprasSinEstado = $compra->listarComprasSinEstado("idusuario = {$idUsuario}");
        
        // Solo crear una nueva compra si el usuario no tiene NINGUNA compra previa
        if (empty($comprasSinEstado)) {
            $controlCompra = new ControlCompra();
            $comprasPrevias = $controlCompra->obtenerComprasPorUsuario($idUsuario);

            // Si el usuario nunca compró nada, se crea una nueva compra
            if (empty($comprasPrevias)) {
                $usuario = new usuario();
                if ($usuario->buscar($idUsuario)) {
                    $nuevaCompra = new compra();
                    $nuevaCompra->setObjUsuario($usuario);
                    if ($nuevaCompra->insertar()) {
                        $comprasSinEstado = [$nuevaCompra];
                    }
                }
            }
        }


        // Si aún no hay compras activas, crear una nueva
        if (empty($comprasSinEstado)) {
            $usuario = new usuario();
            if ($usuario->buscar($idUsuario)) {
                $nuevaCompra = new compra();
                $nuevaCompra->setObjUsuario($usuario);
                if ($nuevaCompra->insertar()) {
                    $comprasSinEstado = [$nuevaCompra];
                }
            }
        }

        // Obtener items de esas compras
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

    /** Procesa la compra actual del usuario */
    public function comprarCarrito($idUsuario) {
        $controlCompraEstado = new ControlCompraEstado();
        $controlCompra = new ControlCompra();
        $compraItem = new CompraItem();
        $producto = new Producto();
        $mail = new MailerService();
        $exito = false;

        $compras = $controlCompra->obtenerComprasPorUsuario($idUsuario);

        foreach ($compras as $compra) {
            $idCompra = $compra->getIdcompra();
            if ($compraItem->tieneItems($idCompra)){
                if ($controlCompraEstado->añadirEstadoCarrito($idCompra)) {
                    $items = $compraItem->obtenerIdsYCantidadPorCompra($idCompra);
                    foreach ($items as $prod) {
                        $producto->reducirStock($prod['idproducto'], $prod['cicantidad']);
                    }
                    $mail->generarMail($idCompra, 1);
                    $exito = true;
                }
            }
            else {
                $exito = false;
            }
        }

        return $exito;
    }


    public function eliminarDelCarrito($idUsuario, $idProducto) {
        $resultado = false;

        $idCompra = $this->buscarCarritoAbierto($idUsuario);

        if ($idCompra !== null) {
            $compraItem = new compraItem();
            $resultado = $compraItem->eliminarPorCompraYProducto($idCompra, $idProducto);
        } else {
            $resultado = false;
        }

        return $resultado;
    }
}
?>