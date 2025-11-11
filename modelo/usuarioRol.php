CREATE TABLE usuariorol (
    idusuario BIGINT(20),
    idrol BIGINT(20),
    PRIMARY KEY (idusuario, idrol),
    FOREIGN KEY (idusuario) REFERENCES usuario(idusuario),
    FOREIGN KEY (idrol) REFERENCES rol(idrol)
);

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";

class usuarioRol{
    private $objPdo;
    private $idusuario;
    private $idrol;

    // === Getters & Setters ===
    public function getIdusuario() { return $this->idusuario; }
    public function setIdusuario($v) { $this->idusuario = $idusuario; }

    public function getIdrol() { return $this->idrol; }
    public function setIdrol($v) { $this->idrol = $idrol; }

    //construc
    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Cargar datos desde la BD
    private function cargarDesdeArray($row) {
        $this->setIdusuario($row['idusuario']);
        $this->setIdrol($row['idrol']);
    }

    // Insertar relación
    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "INSERT INTO usuariorol (idusuario, idrol)
                    VALUES ({$this->getIdusuario()}, {$this->getIdrol()})";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Eliminar relación (eliminar el rol del usuario)
    public function eliminar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "DELETE FROM usuariorol
                    WHERE idusuario = {$this->getIdusuario()} 
                      AND idrol = {$this->getIdrol()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Listar relaciones
    public function listar($condicion = "") {
        $arreglo = [];
        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM usuariorol";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $result = $this->objPdo->Ejecutar($sql);

            foreach ($result as $row) {
                $obj = new UsuarioRol();
                $obj->cargarDesdeArray($row);
                $arreglo[] = $obj;
            }
        }
        return $arreglo;
    }

}
?>