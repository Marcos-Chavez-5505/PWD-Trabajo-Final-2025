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
}
?>