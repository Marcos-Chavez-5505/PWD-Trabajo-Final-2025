<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/bdCarritoCompras.php';

class ControlCarrito {
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }

    
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

    /** Obtiene los items del carrito del usuario y calcula su total */
    public function obtenerCarritoUsuario($idUsuario) {

        $respuesta = [
            'items' => [],
            'total' => 0
        ];

        if ($idUsuario) {
            $items = $this->obtenerItemsSinEstado($idUsuario);
    
            if (!empty($items)) {
                $total = 0;
                $detalles = [];
        
                foreach ($items as $item) {
        
                    $producto = $item->getObjProducto();
                    if (!$producto) {
                        continue;
                    }
        
                    $precio = (float)$producto->getProprecio();
                    $cantidad = (int)$item->getCicantidad();
                    $subtotal = $precio * $cantidad;
        
                    $detalles[] = [
                        'producto' => [
                            'id'      => $producto->getIdproducto(),
                            'nombre'  => $producto->getPronombre(),
                            'detalle' => $producto->getProdetalle(),
                            'precio'  => $precio
                        ],
                        'item' => [
                            'cantidad' => $cantidad,
                            'idcompraitem' => $item->getIdcompraitem()
                        ],
                        'subtotal' => $subtotal
                    ];
        
                    $total += $subtotal;
                }
        
                $respuesta['items'] = $detalles;
                $respuesta['total'] = $total;
            }
        }
        return $respuesta;
    }

    /** Procesa la compra actual del usuario */
    public function comprarCarrito($idUsuario) {
        $controlCompraEstado = new ControlCompraEstado();
        $controlCompra = new ControlCompra();
        $compraItem = new CompraItem();
        $producto = new Producto();
        $mail = new MailerService();
        $exito = false;
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date('Y-m-d H:i:s');

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
                    $controlCompra->modificarFechaCompra($idCompra, $fecha);
                }
            }
            else {
                $exito = false;
            }
        }

        return $exito;
    }


    public function eliminarDelCarrito($idUsuario, $idProducto){

    $response = ['ok' => false, 'msg' => 'Error desconocido'];

    if (!$idUsuario || !$idProducto) {
        $response = ['ok' => false, 'msg' => 'Datos incompletos'];
    }
    else {
        $compra = new Compra();
        $idCompra = $compra->listarIDComprasSinEstadoNiFecha($idUsuario);

        if(!$idCompra){
            $response = ['ok' => false, 'msg' => 'Carrito no encontrado'];
        }
        else{
            $compraItem = new CompraItem();
            $item = $compraItem->obtenerDatosItem($idCompra, $idProducto);

            if (!$item) {
                $response = ['ok' => false, 'msg' => 'Producto no encontrado en el carrito'];
            }
            else {
                $ok = $compraItem->eliminarPorCompraYProducto($idCompra, $idProducto);

                if($ok){
                    $response = ['ok' => true, 'msg' => 'Producto eliminado correctamente'];
                }else{
                    $response = ['ok' => false, 'msg' => 'No se pudo eliminar el producto'];
                }
            }
        }
    }
        return $response;
    } 

    // public function procesarCompra($idUsuario) {
    //     if (!$idUsuario) {
    //         throw new Exception("ID de usuario no recibido.");
    //     }

    //     $exito = $this->comprarCarrito($idUsuario);

    //     return [
    //         'flash' => [
    //             'tipo' => $exito ? 'success' : 'danger',
    //             'texto' => $exito
    //                 ? '✅ La compra se realizó correctamente.'
    //                 : '❌ No hay compras pendientes o hubo un error al procesarlas.'
    //         ]
    //     ];
    // }
    public function procesarCompra($idUsuario) {
        if (!$idUsuario) {
            return [
                'ok' => false,
                'flash' => [
                    'tipo' => 'danger',
                    'texto' => 'ID de usuario inválido.'
                ]
            ];
        }

        $exito = $this->comprarCarrito($idUsuario);

        return [
            'ok' => $exito,
            'flash' => [
                'tipo' => $exito ? 'success' : 'danger',
                'texto' => $exito
                    ? 'La compra se realizó correctamente.'
                    : 'No hay compras pendientes o ocurrió un error.'
            ]
        ];
    }

    /** Se usa en agregarProducto.php */
    public function agregarProductoCarritoAction($idUsuario) {
        $valorEncapsulado = new ValorEncapsulado();
        $idProducto = $valorEncapsulado->obtenerValor('idproducto');

        $respuesta = [
            'ok' => false,
            'msg' => 'Error desconocido.'
        ];

        if ($idProducto > 0) {
            if ($this->agregarAlCarrito($idUsuario, $idProducto, 1)) {

                $items = $this->obtenerItemsSinEstado($idUsuario);
                $cantidadTotal = 0;

                foreach ($items as $item) {
                    $cantidadTotal += $item->getCicantidad();
                }

                $respuesta = [
                    'ok' => true,
                    'msg' => 'Producto añadido al carrito.',
                    'cantidad' => $cantidadTotal
                ];

            } else {
                $respuesta = [
                    'ok' => false,
                    'msg' => 'No hay suficiente stock.'
                ];
            }
        } else {
            $respuesta = [
                'ok' => false,
                'msg' => 'Producto no válido.'
            ];
        }

        return $respuesta;
    }

    /** Se usa en cantidadCarrito.php */
    public function obtenerCantidadTotalCarritoAction($idUsuario) {
        $items = $this->obtenerItemsSinEstado($idUsuario);

        $cantidad = 0;
        foreach ($items as $item) {
            $cantidad += (int)$item->getCicantidad();
        }

        return [
            'ok' => true,
            'cantidad' => $cantidad
        ];
    }

    /** Se usa en get_productos */
    public function listarProductosAction() {
        $controlAdmin = new controlAdmin();
        $productos = $controlAdmin->obtenerProductos();

        $respuesta = [
            'total' => 0,
            'rows' => []
        ];

        if ($productos !== false) {
            $respuesta['total'] = count($productos);
            $respuesta['rows'] = $productos;
        }

        return $respuesta;
    }

}
?>