<?php
class controlAdmin{
    private $db;

    public function __construct() {
        $this->db = new bdCarritoCompras();
    }

    /**
     * Función que devuelve los productos que existen en la bd, lo devuelve en un 
     * array asociativo siendo las claves los nombres de las columnas 
     * @return array[]
     */
    public function obtenerProductos(){
        $productos = [];

        if ($this->db->Iniciar()) {
            $sql = "SELECT * FROM producto";
            if ($this->db->Ejecutar($sql) > 0) {
                while ($fila = $this->db->Registro()) {
                    array_push($productos, $fila);
                }
            }
        }

        return $productos;
    }

    /**
     * Función para eliminar un producto si eliminar() devuelve 0 filas se retorna falso
     * @param int $idProducto
     * @return bool
     */
    public function eliminarProducto($idProducto){
        $consultaExitosa = false;
        $producto = new producto;

        $producto->setIdproducto($idProducto);
        if ($producto->eliminar() > 0){
            $consultaExitosa = true;
        }

        return $consultaExitosa;
    }

    /**
    * Función para modificar un producto si modificar() devuelve <0 filas se retorna falso
    * @param int $idProducto
    * @return bool
    */
    public function actualizarProducto($idProducto, $datos){
        $producto = new Producto();
        $producto->buscar($idProducto);
        
        $producto->setIdproducto($idProducto);
        $producto->setProNombre($datos['pronombre']);
        $producto->setProDetalle($datos['prodetalle']);
        $producto->setProCantStock($datos['procantstock']);
        $producto->setProPrecio($datos['proprecio']);
        $producto->setProImagen($datos['proimagen']);
        
        return $producto->modificar() > 0;
    }

    /**
    * Función para añadir un producto
    */
    public function añadirProducto($datosProducto) {
        $resultado = false;
        
        if (!empty($datosProducto['pronombre']) && !empty($datosProducto['prodetalle'])) {
            $producto = new Producto();
            
            $producto->cargar(
                $datosProducto['pronombre'],
                $datosProducto['prodetalle'],
                $datosProducto['procantstock'] ?? 0,
                $datosProducto['proprecio'] ?? 0.00,
                $datosProducto['proimagen'] ?? ''
            );
            
            $idGenerado = $producto->insertar();
            
            if ($idGenerado !== -1) {
                $resultado = true;
            }
        }
        return $resultado;
    }
}
?>