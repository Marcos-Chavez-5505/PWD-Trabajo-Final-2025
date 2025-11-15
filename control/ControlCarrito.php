<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/modelo/conector/bdCarritoCompras.php';

class controlCarrito {
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }

    private function obtenerCompraIniciada($idUsuario) {
        $sql = "
            SELECT c.idcompra 
            FROM compra c
            INNER JOIN compraestado ce ON c.idcompra = ce.idcompra
            INNER JOIN compraestadotipo cet ON ce.idcompraestadotipo = cet.idcompraestadotipo
            WHERE c.idusuario = $idUsuario AND cet.cetdescripcion = 'Iniciada' AND ce.cefechafin IS NULL
            LIMIT 1
        ";
        $this->db->Ejecutar($sql);
        $compra = $this->db->Registro();

        if (!$compra) {
            $idCompra = $this->db->Ejecutar("INSERT INTO compra (idusuario) VALUES ($idUsuario)");
            $this->db->Ejecutar("
                INSERT INTO compraestado (idcompra, idcompraestadotipo, cefechaini)
                VALUES ($idCompra,
                    (SELECT idcompraestadotipo FROM compraestadotipo WHERE cetdescripcion = 'Iniciada' LIMIT 1),
                    NOW()
                )
            ");
        } else {
            $idCompra = $compra['idcompra'];
        }

        return $idCompra;
    }

    public function agregarAlCarrito($idUsuario, $idProducto, $cantidad = 1) {
        $idCompra = $this->obtenerCompraIniciada($idUsuario);
        $exito = true;

        $sql = "SELECT * FROM compraitem WHERE idcompra = $idCompra AND idproducto = $idProducto";
        $this->db->Ejecutar($sql);
        $item = $this->db->Registro();

        $sqlStock = "SELECT procantstock FROM producto WHERE idproducto = $idProducto";
        $this->db->Ejecutar($sqlStock);
        $stockData = $this->db->Registro();
        $stockDisponible = $stockData['procantstock'];

        if ($item) {
            $nuevaCantidad = $item['cicantidad'] + $cantidad;
            if ($nuevaCantidad > $stockDisponible){
                $exito = false;
            } else {
                $this->db->Ejecutar("UPDATE compraitem SET cicantidad = $nuevaCantidad WHERE idcompraitem = {$item['idcompraitem']}");
            }
        } else {
            if ($cantidad > $stockDisponible) {
                $exito = false;
            } else {
                $this->db->Ejecutar("INSERT INTO compraitem (idcompra, idproducto, cicantidad) VALUES ($idCompra, $idProducto, $cantidad)");
            }
        }

        return $exito;
    }
}
?>
