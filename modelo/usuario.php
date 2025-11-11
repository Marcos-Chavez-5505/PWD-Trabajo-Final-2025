CREATE TABLE usuario (
    idusuario BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    usnombre VARCHAR(50),
    uspass INT(11),
    usmail VARCHAR(50),
    usdeshabilitado TIMESTAMP NULL
);

<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";
class Usuario{
    private $objPdo;
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;

    // === Getters & Setters ===
    public function getIdusuario() { return $this->idusuario; }
    public function setIdusuario($v) { $this->idusuario = $idusuario; }

    public function getUsnombre() { return $this->usnombre; }
    public function setUsnombre($v) { $this->usnombre = $usnombre; }

    public function getUspass() { return $this->uspass; }
    public function setUspass($v) { $this->uspass = $uspass; }

    public function getUsmail() { return $this->usmail; }
    public function setUsmail($v) { $this->usmail = $usmail; }

    public function getUsdeshabilitado() { return $this->usdeshabilitado; }
    public function setUsdeshabilitado($v) { $this->usdeshabilitado = $usdeshabilitado; }

    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    // Insertar Usuario
    public function insertar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $deshab = $this->getUsdeshabilitado() ? "'{$this->getUsdeshabilitado()}'" : "NULL";
            $sql = "INSERT INTO usuario (usnombre, uspass, usmail, usdeshabilitado)
                    VALUES ('{$this->getUsnombre()}', '{$this->getUspass()}', '{$this->getUsmail()}', $deshab)";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Modificar Usuario
    public function modificar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $deshab = $this->getUsdeshabilitado() ? "'{$this->getUsdeshabilitado()}'" : "NULL";
            $sql = "UPDATE usuario SET 
                        usnombre = '{$this->getUsnombre()}',
                        uspass = '{$this->getUspass()}',
                        usmail = '{$this->getUsmail()}',
                        usdeshabilitado = $deshab
                    WHERE idusuario = {$this->getIdusuario()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Borrado lógico 
    public function deshabilitar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "UPDATE usuario SET usdeshabilitado = CURRENT_TIMESTAMP 
                    WHERE idusuario = {$this->getIdusuario()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Habilitar
    public function habilitar() {
        $rta = false;
        if ($this->objPdo->Iniciar()) {
            $sql = "UPDATE usuario SET usdeshabilitado = NULL 
                    WHERE idusuario = {$this->getIdusuario()}";
            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    // Listar
    public function listar($condicion = "") {
        $arreglo = [];
        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM usuario";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $result = $this->objPdo->Ejecutar($sql);

            if ($result) {
                foreach ($result as $row) {
                    $obj = new Usuario();
                    $obj->cargarDesdeArray($row);
                    $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }

}
?>

