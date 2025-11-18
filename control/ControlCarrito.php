<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/bdCarritoCompras.php';

class ControlCarrito {
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

        $compra = new compra();
        $compras = $compra->listar("idusuario = {$idUsuario} ORDER BY idcompra DESC");

        if (!empty($compras)) {
            foreach ($compras as $c) {
                $idCompra = $c->getIdcompra();

                $compraEstado = new compraEstado();
                $tieneEstado = $compraEstado->buscarCompraAsociada($idCompra);

                if (!$tieneEstado) {
                    $resultado = $idCompra;
                    break;
                }
            }
        }

        // si no encontró carrito existente
        if ($resultado === null) {
            $usuario = new usuario();
            $usuario->buscar($idUsuario);

            $nuevaCompra = new compra();
            $nuevaCompra->setObjUsuario($usuario);

            $insertOk = $nuevaCompra->insertar();
            if ($insertOk) {
                $ultimas = $compra->listar("idusuario = {$idUsuario} ORDER BY idcompra DESC LIMIT 1");
                if (!empty($ultimas)) {
                    $resultado = $ultimas[0]->getIdcompra();
                    
                    $estadoTipo = new compraEstadoTipo();
                    $estadoTipo->buscarDescripcion("Iniciada");

                    $nuevoEstado = new compraEstado();
                    $nuevoEstado->setObjCompra($ultimas[0]);
                    $nuevoEstado->setObjCompraEstadoTipo($estadoTipo);
                    $nuevoEstado->insertar();
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
    error_log("🎯 agregarAlCarrito INICIADO - Usuario: $idUsuario, Producto: $idProducto, Cantidad: $cantidad");

    $idCompra = $this->buscarCarritoAbierto($idUsuario);
    error_log("ID Compra obtenido: " . ($idCompra ?? 'NULL'));

    if ($idCompra !== null) {
        error_log("✅ Compra existe, buscando producto $idProducto...");
        $producto = new producto();
        $prodOk = $producto->buscar($idProducto);
        error_log("Producto encontrado: " . ($prodOk ? 'SÍ' : 'NO'));

        if ($prodOk) {
            $stock = (int)$producto->getProcantstock();
            error_log("Stock disponible: $stock, Cantidad solicitada: $cantidad");

            if ($cantidad <= 0 || $cantidad > $stock) {
                error_log("❌ Problema de stock - Cantidad fuera de rango");
                $resultado = false;
            } else {
                $compraItem = new compraItem();
                $itemExistente = $compraItem->buscarPorCompraYProducto($idCompra, $idProducto);
                error_log("Item existente en carrito: " . ($itemExistente ? 'SÍ' : 'NO'));

                if ($itemExistente) {
                    error_log("📦 Item existe, actualizando cantidad...");
                    $cantActual = (int)$itemExistente->getCicantidad();
                    $nuevaCant = $cantActual + $cantidad;
                    error_log("Cantidad actual: $cantActual, Nueva cantidad: $nuevaCant");

                    if ($nuevaCant <= $stock) {
                        $itemExistente->setCicantidad($nuevaCant);
                        $resultado = $itemExistente->modificar();
                        error_log("Modificar item: " . ($resultado ? 'ÉXITO' : 'FALLÓ'));
                    } else {
                        error_log("❌ Stock insuficiente para actualizar");
                        $resultado = false;
                    }
                } else {
                    error_log("🆕 Creando nuevo item en carrito...");
                    $compraObj = new compra();
                    $compraEncontrada = $compraObj->buscar($idCompra);
                    error_log("Compra buscada: " . ($compraEncontrada ? 'SÍ' : 'NO'));

                    $productoObj = new producto();
                    $productoEncontrado = $productoObj->buscar($idProducto);
                    error_log("Producto buscado: " . ($productoEncontrado ? 'SÍ' : 'NO'));

                    $nuevoItem = new compraItem();
                    $nuevoItem->setObjCompra($compraObj);
                    $nuevoItem->setObjProducto($productoObj);
                    $nuevoItem->setCicantidad($cantidad);

                    $resultado = $nuevoItem->insertar();
                    error_log("Insertar nuevo item: " . ($resultado ? 'ÉXITO' : 'FALLÓ'));
                    
                    if (!$resultado) {
                        error_log("❌ FALLÓ la inserción del item");
                    }
                }
            }
        } else {
            error_log("❌ Producto no encontrado");
        }
    } else {
        error_log("❌ No se pudo obtener/comprar ID de compra");
    }
    
    error_log("🔚 agregarAlCarrito RESULTADO FINAL: " . ($resultado ? 'TRUE' : 'FALSE'));
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
            $compraItem = new compraItem();
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
            $compraItem = new compraItem();
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
            $compraItem = new compraItem();
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
            $compraItem = new compraItem();
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
