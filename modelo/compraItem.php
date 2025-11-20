<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

class CompraItem {
    private $idcompraitem;
    private $objProducto;
    private $objCompra;
    private $cicantidad;
    private $objPdo;
    private $colProductos; // 1:N

    public function getIdcompraitem() { return $this->idcompraitem; }
    public function setIdcompraitem($v) { $this->idcompraitem = $v; }

    public function getObjProducto() { return $this->objProducto; }
    public function setObjProducto($v) { $this->objProducto = $v; }

    public function getObjCompra() { return $this->objCompra; }
    public function setObjCompra($v) { $this->objCompra = $v; }

    public function getCicantidad() { return $this->cicantidad; }
    public function setCicantidad($v) { $this->cicantidad = $v; }

    public function getColProductos() { return $this->colProductos; }
    public function setColProductos($v) { $this->colProductos = $v; }

    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $idproducto = $this->getObjProducto()->getIdproducto() ?? NULL;
            $idcompra = $this->getObjCompra()->getIdcompra() ?? NULL;
            $cantidad = $this->getCicantidad();

            $sql = "INSERT INTO compraitem (idproducto, idcompra, cicantidad)
                    VALUES ('{$idproducto}', '{$idcompra}', '{$cantidad}')";

            $resultado = $this->objPdo->Ejecutar($sql);

            if ($resultado > 0 || $resultado === -1) {
                $rta = true;
            }
        }
        return $rta;
    }


    /** Modifica un item existente (solo cantidad o referencias) */
    public function modificar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $idproducto = $this->getObjProducto()->getIdproducto() ?? NULL;
            $idcompra = $this->getObjCompra()->getIdcompra() ?? NULL;

            $sql = "UPDATE compraitem SET 
                        idproducto = '{$idproducto}',
                        idcompra = '{$idcompra}',
                        cicantidad = '{$this->getCicantidad()}'
                    WHERE idcompraitem = {$this->getIdcompraitem()}";

            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    /** Lista todos los items que cumplan una condición */
    public function listar($condicion = "") {
        $arreglo = [];

        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM compraitem";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }

            $this->objPdo->Ejecutar($sql);
            $filas = $this->objPdo->getFilas();

            if (!empty($filas)) {
                foreach ($filas as $fila) {
                    $obj = new CompraItem();
                    $res = $obj->buscar($fila['idcompraitem']);
                    if ($res) $arreglo[] = $obj;
                }
            }
        }

        $this->setColProductos($arreglo);
        return $arreglo;
    }

    /** Carga datos en el objeto actual */
    public function cargar($idcompraitem, $objProducto, $objCompra, $cicantidad) {
        $this->setIdcompraitem($idcompraitem);
        $this->setObjProducto($objProducto);
        $this->setObjCompra($objCompra);
        $this->setCicantidad($cicantidad);
    }

    /** Busca un item por su ID */
    public function buscar($id) {
        $resultado = false;
        if ($this->objPdo->Iniciar()) {
            $this->objPdo->Ejecutar("SELECT * FROM compraitem WHERE idcompraitem = {$id}");
            $filas = $this->objPdo->getFilas();

            if (!empty($filas)) {
                $fila = $filas[0];

                $objCompra = new Compra();
                $objCompra->buscar($fila['idcompra']);

                $objProducto = new Producto();
                $objProducto->buscar($fila['idproducto']);

                $this->cargar(
                    $fila['idcompraitem'],
                    $objProducto,
                    $objCompra,
                    $fila['cicantidad']
                );

                $resultado = true;
            }
        }
        return $resultado;
    }

    /** Elimina un item por combinación de compra y producto */
    public function eliminarPorCompraYProducto($idCompra, $idProducto) {
        $resultado = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "DELETE FROM compraitem
                    WHERE idcompra = {$idCompra}
                    AND idproducto = {$idProducto}";
            $resultado = $this->objPdo->Ejecutar($sql);
        }
        return $resultado;
    }

    /** Busca un item por combinación de compra y producto */
    public function buscarPorCompraYProducto($idCompra, $idProducto) {
        $resultado = null;
        $cond = "idcompra = {$idCompra} AND idproducto = {$idProducto}";
        $items = $this->listar($cond);
        if (!empty($items)) {
            $resultado = $items[0];
        }
        return $resultado;
    }

    
    public function obtenerIdsYCantidadPorCompra($idCompra) {
        $resultado = [];
    
        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT idproducto, cicantidad 
                    FROM compraitem 
                    WHERE idcompra = {$idCompra}";
            $this->objPdo->Ejecutar($sql);
            $filas = $this->objPdo->getFilas();
    
            if (!empty($filas)) {
                foreach ($filas as $fila) {
                    $resultado[] = [
                        'idproducto' => (int)$fila['idproducto'],
                        'cicantidad' => (int)$fila['cicantidad']
                    ];
                }
            }
        }
    
        return $resultado;
    }


    public function obtenerDatosItem($idCompra, $idProducto) {
        $resultado = null;

        $sql = "
            SELECT idcompraitem, cicantidad, proprecio, procantstock
            FROM compraitem ci
            INNER JOIN producto p ON ci.idproducto = p.idproducto
            WHERE ci.idcompra = {$idCompra} 
            AND ci.idproducto = {$idProducto}
            LIMIT 1
        ";
        
        if ($this->objPdo->Iniciar()) {
            $this->objPdo->Ejecutar($sql);
            $filas = $this->objPdo->getFilas();

            if (!empty($filas)) {
                $resultado = [
                    'idcompraitem'   => (int)$filas[0]['idcompraitem'],
                    'cicantidad'   => (int)$filas[0]['cicantidad'],
                    'proprecio'    => (float)$filas[0]['proprecio'],
                    'procantstock' => (int)$filas[0]['procantstock']
                ];
                $this->buscar($filas[0]['idcompraitem']);
            }
        }
        return $resultado;
    }


    /** Retorna true si la compra tiene al menos 1 item */
    public function tieneItems($idCompra) {
        $rta = false;
        $sql = "SELECT 1 FROM compraitem WHERE idcompra = {$idCompra} LIMIT 1";

        if ($this->objPdo->Iniciar()) {
            if ($this->objPdo->Ejecutar($sql)) {
                if (!empty($this->objPdo->getFilas())){
                    $rta = true;
                }
            }
        }
        return $rta;
    }
}

?>
