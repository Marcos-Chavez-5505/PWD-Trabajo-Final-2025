<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/bdCarritoCompras.php';

class controlCarrito {
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }


    /**
     * Busca la primer compra (carrito) asociado al usuario con $idUsuario y retorna idcompra
     * En caso de no hallar compras (sin estado) asociadas al usuario, se crea una nueva compra
     * @param int $idUsuario
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

    
    /**
     * Agrega un producto al carrito del usuario respetando stock
     * Retorna true/false
     * @param mixed $idUsuario
     * @param mixed $idProducto
     * @param mixed $cantidad
     * @return bool|int|string
     */
    public function agregarAlCarrito($idUsuario, $idProducto, $cantidad = 1) {
        $resultado = false;

        $idCompra = $this->buscarCarritoAbierto($idUsuario);

        if ($idCompra !== null) {
            $producto = new Producto();
            $prodOk = $producto->buscar($idProducto);

            if ($prodOk) {
                $stock = (int)$producto->getProcantstock();

                if ($cantidad <= 0 || $cantidad > $stock) {
                    $resultado = false;
                } else {
                    $compraItem = new CompraItem();
                    $itemExistente = $compraItem->buscarPorCompraYProducto($idCompra, $idProducto);

                    if ($itemExistente) {
                        $cantActual = (int)$itemExistente->getCicantidad();
                        $nuevaCant = $cantActual + $cantidad;

                        if ($nuevaCant <= $stock) {
                            $itemExistente->setCicantidad($nuevaCant);
                            $resultado = $itemExistente->modificar();
                        } else {
                            $resultado = false;
                        }
                    } else {
                        $compraObj = new Compra();
                        $compraObj->buscar($idCompra);

                        $productoObj = new Producto();
                        $productoObj->buscar($idProducto);

                        $nuevoItem = new CompraItem();
                        $nuevoItem->setObjCompra($compraObj);
                        $nuevoItem->setObjProducto($productoObj);
                        $nuevoItem->setCicantidad($cantidad);

                        $resultado = $nuevoItem->insertar();
                    }
                }
            } else {
                $resultado = false;
            }
        } else {
            $resultado = false;
        }

        return $resultado;
    }

    
    /**
     * Elimina un producto del carrito del usuario
     * @param int $idUsuario
     * @param int $idProducto
     * @return bool|int|string
     */
    public function eliminarDelCarrito($idUsuario, $idProducto) {
        $resultado = false;

        $idCompra = $this->buscarCarritoAbierto($idUsuario);

        if ($idCompra !== null) {
            $compraItem = new CompraItem();
            $resultado = $compraItem->eliminarPorCompraYProducto($idCompra, $idProducto);
        } else {
            $resultado = false;
        }

        return $resultado;
    }


    /**
     * Devuelve un array con los objetos CompraItem del carrito abierto del usuario
     * @param int $idUsuario
     * @return CompraItem[]
     */
    public function obtenerItemsDelCarrito($idUsuario) {
        $resultado = [];

        // Obtener idcompra del carrito abierto (puede crear la compra si no existe)
        $idCompra = $this->buscarCarritoAbierto($idUsuario);

        if ($idCompra !== null) {
            $compraItem = new CompraItem();
            // Uso de listar para obtener los objetos CompraItem asociados a la compra
            $items = $compraItem->listar("idcompra = {$idCompra}");

            if (!empty($items)) {
                // listar() debe devolver array de objetos CompraItem ya cargados
                foreach ($items as $item) {
                    $resultado[] = $item;
                }
            }
        }
        return $resultado;
    }

    
    /**
     * Vacía el carrito del usuario (elimina todos los items)
     * Devuelve true si todos los borrados (o no había items), false si algún borrado falla.
     * @param int $idUsuario
     * @return bool
     */
    public function vaciarCarrito($idUsuario) {
        $resultado = true;

        $idCompra = $this->buscarCarritoAbierto($idUsuario);

        if ($idCompra !== null) {
            $compraItem = new CompraItem();
            $items = $compraItem->listar("idcompra = {$idCompra}");

            if (!empty($items)) {
                foreach ($items as $item) {
                    if ($item->getObjProducto() !== null) {
                        $idProducto = $item->getObjProducto()->getIdproducto();
                    } else {
                        $resultado = false;
                        continue;
                    }

                    $ok = $compraItem->eliminarPorCompraYProducto($idCompra, $idProducto);
                    if (!$ok) {
                        $resultado = false;
                    }
                }
            }
        }
        return $resultado;
    }


    /**
     * Calcula el total monetario del carrito (sum(cantidad * precio)).
     * @param int $idUsuario
     * @return float|int
     */
    public function totalCarrito($idUsuario) {
        $total = 0.0;

        $idCompra = $this->buscarCarritoAbierto($idUsuario);

        if ($idCompra !== null) {
            $compraItem = new CompraItem();
            $items = $compraItem->listar("idcompra = {$idCompra}");

            if (!empty($items)) {
                foreach ($items as $item) {
                    if ((int)$item->getCicantidad()) {
                        $cantidad = (int)$item->getCicantidad();
                    } else {
                        $cantidad = 0;
                    }

                    $precio = 0.0;
                    if ($item->getObjProducto() !== null) {
                        $prodObj = $item->getObjProducto();
                        $precio = (float)$prodObj->getProprecio();
                    }
                    $total += $cantidad * $precio;
                }
            }
        }
        return $total;
    }



}
?>
