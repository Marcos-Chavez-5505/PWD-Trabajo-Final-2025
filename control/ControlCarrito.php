<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/bdCarritoCompras.php';

class controlCarrito {
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }

    // private function obtenerCompraIniciada($idUsuario) {
    //     $sql = "
    //         SELECT c.idcompra 
    //         FROM compra c
    //         INNER JOIN compraestado ce ON c.idcompra = ce.idcompra
    //         INNER JOIN compraestadotipo cet ON ce.idcompraestadotipo = cet.idcompraestadotipo
    //         WHERE c.idusuario = $idUsuario AND cet.cetdescripcion = 'Iniciada' AND ce.cefechafin IS NULL
    //         LIMIT 1
    //     ";
    //     $this->db->Ejecutar($sql);
    //     $compra = $this->db->Registro();

    //     //! esta parte deberia pertenecer a otra funcion
    //     if (!$compra) {
    //         $idCompra = $this->db->Ejecutar("INSERT INTO compra (idusuario) VALUES ($idUsuario)");
    //         $this->db->Ejecutar("
    //             INSERT INTO compraestado (idcompra, idcompraestadotipo, cefechaini)
    //             VALUES ($idCompra,
    //                 (SELECT idcompraestadotipo FROM compraestadotipo WHERE cetdescripcion = 'Iniciada' LIMIT 1),
    //                 NOW()
    //             )
    //         ");
    //     } else {
    //         $idCompra = $compra['idcompra'];
    //     }

    //     return $idCompra;
    // }


    // public function agregarAlCarrito($idUsuario, $idProducto, $cantidad = 1) {
    //     $idCompra = $this->obtenerCompraIniciada($idUsuario);
    //     $exito = true;

    //     $sql = "SELECT * FROM compraitem WHERE idcompra = $idCompra AND idproducto = $idProducto";
    //     $this->db->Ejecutar($sql);
    //     $item = $this->db->Registro();

    //     $sqlStock = "SELECT procantstock FROM producto WHERE idproducto = $idProducto";
    //     $this->db->Ejecutar($sqlStock);
    //     $stockData = $this->db->Registro();
    //     $stockDisponible = $stockData['procantstock'];

    //     if ($item) {
    //         $nuevaCantidad = $item['cicantidad'] + $cantidad;
    //         if ($nuevaCantidad > $stockDisponible){
    //             $exito = false;
    //         } else {
    //             $this->db->Ejecutar("UPDATE compraitem SET cicantidad = $nuevaCantidad WHERE idcompraitem = {$item['idcompraitem']}");
    //         }
    //     } else {
    //         if ($cantidad > $stockDisponible) {
    //             $exito = false;
    //         } else {
    //             $this->db->Ejecutar("INSERT INTO compraitem (idcompra, idproducto, cicantidad) VALUES ($idCompra, $idProducto, $cantidad)");
    //         }
    //     }

    //     return $exito;
    // }


    // function eliminarDelCarrito($idUsuario, $idProducto){
    //     $respuesta = false;

    //     $sqlCarrito = "
    //         SELECT c.idcompra 
    //         FROM compra c
    //         INNER JOIN compraestado ce ON c.idcompra = ce.idcompra
    //         WHERE c.idusuario = $idUsuario 
    //         AND ce.idcompraestadotipo = 1
    //         AND ce.cefechafin IS NULL
    //         LIMIT 1
    //     ";
    //     $this->db->Ejecutar($sqlCarrito);
    //     $compraActiva = $this->db->Registro();
        
    //     if ($compraActiva) {
    //         $idCompra = $compraActiva['idcompra'];
        
    //         $sqlEliminar = "
    //             DELETE FROM compraitem 
    //             WHERE idcompra = $idCompra 
    //             AND idproducto = $idProducto
    //         ";
            
    //         $respuesta =  $this->db->Ejecutar($sqlEliminar);
    //     }
        
    //     return $respuesta;
    // }

    // public function buscarCarritoAbierto($idUsuario) {
    //     $compra = new Compra();

    //     $compras = $compra->listar("idusuario = {$idUsuario}");
    //     $cant = count($compras);
    //     $i = 0;
    //     $rta = false;
    //     while (!$rta && $i<$cant){
    //         $idCompra = $compras[$i]->getIdcompra();

    //         $compraEst = new CompraEstado();

    //         $rta = $compraEst->buscarCompraAsociada($idCompra);
    //         $i++;
    //     }

    //     return null; // no existe carrito abierto
    // }

    // public function agregarProductoAlCarrito($idUsuario, $idProducto, $cantidad = 1) {
    //     $resultado = false;

    //     // 1) buscar carrito
    //     $idCompra = $this->buscarCarritoAbierto($idUsuario);

    //     // 2) si no existe compra, crearla mediante ORM
    //     if (!$idCompra) {
    //         $objCompra = new Compra();

    //         $objUsuario = new Usuario();
    //         $objUsuario->buscar($idUsuario);
    //         $objCompra->setObjUsuario($objUsuario);

    //         if ($objCompra->insertar()) {
    //             $idCompra = $this->db->lastInsertId();
    //         }
    //     }

    //     // Si no se pudo obtener el ID, no se continúa
    //     if ($idCompra) {

    //         // 3) verificar stock
    //         $sqlStock = "SELECT procantstock FROM producto WHERE idproducto = {$idProducto}";
    //         $this->db->Ejecutar($sqlStock);
    //         $filaStock = $this->db->Registro();

    //         if ($filaStock && $filaStock['procantstock'] >= $cantidad) {

    //             // 4) verificar si el item ya existe
    //             $sqlItem = "
    //                 SELECT cantidad FROM compraitem
    //                 WHERE idcompra = {$idCompra} AND idproducto = {$idProducto}
    //             ";
    //             $this->db->Ejecutar($sqlItem);
    //             $item = $this->db->Registro();

    //             if ($item) {
    //                 $nuevaCantidad = $item['cantidad'] + $cantidad;

    //                 if ($nuevaCantidad <= $filaStock['procantstock']) {
    //                     $sqlUpd = "
    //                         UPDATE compraitem
    //                         SET cantidad = {$nuevaCantidad}
    //                         WHERE idcompra = {$idCompra} AND idproducto = {$idProducto}
    //                     ";
    //                     $resultado = $this->db->Ejecutar($sqlUpd);
    //                 }

    //             } else {
    //                 // insertar nuevo producto
    //                 $sqlIns = "
    //                     INSERT INTO compraitem (idcompra, idproducto, cantidad)
    //                     VALUES ({$idCompra}, {$idProducto}, {$cantidad})
    //                 ";
    //                 $resultado = $this->db->Ejecutar($sqlIns);
    //             }
    //         }
    //     }

    //     return $resultado;
    // }


    /**
     * Busca la primer compra (carrito) asociado al usuario con $idUsuario y retorna idcompra.
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
     * Agrega un producto al carrito del usuario respetando stock.
     * Retorna true/false
     */
    public function agregarAlCarrito($idUsuario, $idProducto, $cantidad = 1) {
        $resultado = false;

        // 1) Obtener (o crear) carrito abierto
        $idCompra = $this->buscarCarritoAbierto($idUsuario);

        if ($idCompra !== null) {
            // 2) Verificar stock mediante ORM Producto
            $producto = new Producto();
            $prodOk = $producto->buscar($idProducto);

            if ($prodOk) {
                $stock = (int)$producto->getProcantstock();

                // Si la cantidad solicitada excede stock -> no continuar
                if ($cantidad <= 0 || $cantidad > $stock) {
                    $resultado = false;
                } else {
                    // 3) Obtener el item en el carrito (si existe) mediante CompraItem ORM
                    $compraItem = new CompraItem();
                    $itemExistente = $compraItem->buscarPorCompraYProducto($idCompra, $idProducto); // asumo este método

                    if ($itemExistente) {
                        // sumar cantidades y validar stock
                        $cantActual = (int)$itemExistente->getCicantidad();
                        $nuevaCant = $cantActual + $cantidad;

                        if ($nuevaCant <= $stock) {
                            $itemExistente->setCicantidad($nuevaCant);
                            $resultado = $itemExistente->modificar();
                        } else {
                            $resultado = false;
                        }
                    } else {
                        // crear nuevo item
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
                $resultado = false; // producto inexistente
            }
        } else {
            $resultado = false; // no se pudo obtener/crear carrito
        }

        return $resultado;
    }
}
?>
