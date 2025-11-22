<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
class ControlCompra{
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }

    /**
     * Devuelve un array con todas las compras como un arreglo asociativo donde las claves son las
     * columnas de la tabla
     */
    public function obtenerCompras(){
        $objCompra = new compra();

        return $objCompra->listar();
    }

    /**
     * Esta funcion busca una compra por id y retorna el objeto
     */
    public function buscarCompra($idCompra){
        $objCompra = new compra();
        $resultado = null;
        if ($objCompra->buscar($idCompra)){
            $resultado = $objCompra;
        }

        return $resultado;
    }

    public function obtenerComprasConEstadoYUsuario() {
        $arreglo = [];
    
        if ($this->db->Iniciar()) {
            $sql = "SELECT 
                        c.idcompra,
                        c.cofecha,
                        c.idusuario,
                        u.usnombre as nombre_usuario,
                        u.usmail as email_usuario,
                        cet.cetdescripcion as estado_actual,
                        ce.cefechaini,
                        ce.cefechafin
                    FROM compra c
                    INNER JOIN usuario u ON c.idusuario = u.idusuario
                    LEFT JOIN compraestado ce ON c.idcompra = ce.idcompra
                    LEFT JOIN compraestadotipo cet ON ce.idcompraestadotipo = cet.idcompraestadotipo
                    ORDER BY c.cofecha DESC";
            
            $this->db->Ejecutar($sql);
            $filas = $this->db->getFilas();
            
            if (!empty($filas)) {
                foreach ($filas as $fila) {
                    $arreglo[] = [
                        'idcompra' => $fila['idcompra'],
                        'fecha' => $fila['cofecha'],
                        'id_usuario' => $fila['idusuario'],
                        'nombre_usuario' => $fila['nombre_usuario'],
                        'email_usuario' => $fila['email_usuario'],
                        'estado_actual' => $fila['estado_actual'] ?? 'Sin estado',
                        'fecha_estado' => $fila['cefechaini'],
                        'tiene_estado' => ($fila['estado_actual'] !== null)
                    ];
                }
            }
        }
        
        return $arreglo;
    }

    public function obtenerComprasPorUsuario($idUsuario){
        $compra = new compra();
        return $compra->listar("idusuario = " . strval($idUsuario));
    }

    /**
     * Aumenta la cantidad de un producto en el carrito.
     * Códigos:
     *  1 = falta de datos
     *  2 = carrito no encontrado
     *  3 = producto no encontrado
     *  4 = sin stock disponible
     *  5 = error al modificar
     *  6 = ok
     */
    public function aumentarCantidadProducto($idUsuario, $idProducto) {

        $response = ['code' => 0];

        if (!$idUsuario || !$idProducto){
            $response = ['code' => 1];
        }
        else {
            $compra = new Compra();
            $idCompra = $compra->listarIDComprasSinEstadoNiFecha($idUsuario);

            if (!$idCompra) {
                $response = ['code' => 2];
            }
            else {
                $compraItem = new CompraItem();
                $item = $compraItem->obtenerDatosItem($idCompra, $idProducto);

                if (!$item){
                    $response = ['code' => 3];
                }
                else {
                    $nuevaCantidad = $item['cicantidad'] + 1;

                    if ($nuevaCantidad > $item['procantstock']){
                        $response = ['code' => 4];

                    }
                    else {

                        $compraItem->setCicantidad($nuevaCantidad);
                        $ok = $compraItem->modificar();

                        if (!$ok){
                            $response = ['code' => 5];
                        } else {
                            $response = [
                                'code' => 6,
                                'cantidad' => $nuevaCantidad,
                                'precio' => $item['proprecio']
                            ];
                        }
                    }
                }
            }
        }
        return $response;
    }


    /**
     * Disminuye la cantidad de un producto en el carrito.
     * Códigos de retorno:
     *  1 = falta de datos
     *  2 = carrito no encontrado
     *  3 = producto no encontrado en la compra
     *  4 = error al modificar item
     *  5 = ok, cantidad modificada o eliminado
     */
    public function disminuirCantidadProducto($idUsuario, $idProducto) {

        $response = ['code' => 0];

        if (!$idUsuario || !$idProducto){
            $response = ['code' => 1];
        }
        else {
            $compra = new Compra();
            $idCompra = $compra->listarIDComprasSinEstadoNiFecha($idUsuario);

            if (!$idCompra){
                $response = ['code' => 2];
            }
            else {
                $compraItem = new CompraItem();
                $item = $compraItem->obtenerDatosItem($idCompra, $idProducto);
                if (!$item){
                    $response = ['code' => 3];
                }
                else {
                    $nuevaCantidad = $item['cicantidad'] - 1;
                    $ok = false;
                    if ($nuevaCantidad > 0){
                        $compraItem->setCicantidad($nuevaCantidad);
                        $ok = $compraItem->modificar();
                    }
                    else {
                        $ok = $compraItem->eliminarPorCompraYProducto($idCompra, $idProducto);
                    }
                    if (!$ok){
                        $response = ['code' => 4];
                    }
                    else {
                        $response = [
                            'code' => 5,
                            'cantidad' => max(0, $nuevaCantidad),
                            'precio' => $item['proprecio']
                        ];
                    }
                }
            }
        }
        return $response;
    }

    /*
    *Esta funcion lo que hace es devolver todas las compras del usuario ya armadas
    */ 
    public function obtenerComprasDetalladasPorUsuario($idUsuario) {
    $comprasObj = $this->obtenerComprasPorUsuario($idUsuario);
    $controlEstado = new ControlCompraEstado();
    $detalladas = [];

    foreach ($comprasObj as $compraObj) {
        $idCompra = $compraObj->getIdcompra();

        $estadoObj = $controlEstado->obtenerEstadoActual($idCompra);
        $estado = $estadoObj ? $estadoObj->getObjCompraEstadoTipo()->getCETdescripcion() : "Sin estado";

        $itemObj = new compraItem();
        $itemsBD = $itemObj->listar("idcompra = {$idCompra}");

        $items = [];
        foreach ($itemsBD as $it) {
            $producto = $it->getObjProducto();

            $items[] = [
                'producto' => [
                    'nombre' => $producto->getPronombre(),
                    'precio' => $producto->getProprecio()
                ],
                'cantidad' => $it->getCicantidad(),
                'subtotal' => $it->getCicantidad() * $producto->getProprecio()
            ];
        }
        
        $detalladas[] = [
            'id'     => $idCompra,
            'fecha'  => $compraObj->getCofecha(),
            'estado' => $estado,
            'items'  => $items
        ];
    }
    return $detalladas;
}
}
?>