CREATE TABLE menu (
    idmenu BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    menombre VARCHAR(50),
    medescripcion VARCHAR(124),
    idpadre BIGINT(20),
    medeshabilitado TIMESTAMP NULL
    );

<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";
class Menu {
    private $objPdo;
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $idpadre;
    private $medeshabilitado;

    // Getters y Setters
    public function getIdmenu() { return $this->idmenu; }
    public function setIdmenu($idmenu) { $this->idmenu = $idmenu; }
    
    public function getMenombre() { return $this->menombre; }
    public function setMenombre($menombre) { $this->menombre = $menombre; }
    
    public function getMedescripcion() { return $this->medescripcion; }
    public function setMedescripcion($medescripcion) { $this->medescripcion = $medescripcion; }
    
    public function getIdpadre() { return $this->idpadre; }
    public function setIdpadre($idpadre) { $this->idpadre = $idpadre; }
    
    public function getMedeshabilitado() { return $this->medeshabilitado; }
    public function setMedeshabilitado($medeshabilitado) { $this->medeshabilitado = $medeshabilitado; }

    public function __construct() {
        $this->objPdo = new bdCarritoCompras();
    }

    public function insertar() {
        $resultado = -1;
        if ($this->objPdo->Iniciar()) {
            // Para INSERT, si no se especifica valor, será NULL por defecto
            $deshabilitado = $this->getMedeshabilitado() ? "'{$this->getMedeshabilitado()}'" : "NULL";
            $idpadre = $this->getIdpadre() ? $this->getIdpadre() : "NULL";
            
            $sql = "INSERT INTO menu (menombre, medescripcion, idpadre, medeshabilitado)
                    VALUES ('{$this->getMenombre()}', '{$this->getMedescripcion()}', {$idpadre}, {$deshabilitado})";
            $resultado = $this->objPdo->Ejecutar($sql);
        }
        return $resultado;
    }

    public function modificar() {
        $resultado = -1;
        if ($this->objPdo->Iniciar()) {
            // Para UPDATE, manejar NULL correctamente
            $deshabilitado = $this->getMedeshabilitado() ? "'{$this->getMedeshabilitado()}'" : "NULL";
            $idpadre = $this->getIdpadre() ? $this->getIdpadre() : "NULL";
            
            $sql = "UPDATE menu SET 
                    menombre = '{$this->getMenombre()}',
                    medescripcion = '{$this->getMedescripcion()}', 
                    idpadre = {$idpadre},
                    medeshabilitado = {$deshabilitado}
                    WHERE idmenu = {$this->getIdmenu()}";
            $resultado = $this->objPdo->Ejecutar($sql);
        }
        return $resultado;
    }

    public function eliminar() {
        $resultado = -1;
        if ($this->objPdo->Iniciar()) {
            $sql = "DELETE FROM menu WHERE idmenu = {$this->getIdmenu()}";
            $resultado = $this->objPdo->Ejecutar($sql);
        }
        return $resultado;
    }

    public function listar() {
        $resultado = -1;
        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM menu";
            $resultado = $this->objPdo->Ejecutar($sql);
        }
        return $resultado;
    }

    public function obtenerPorId($idmenu) {
        $resultado = -1;
        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM menu WHERE idmenu = {$idmenu}";
            $resultado = $this->objPdo->Ejecutar($sql);
        }
        return $resultado;
    }

    /**
     * Deshabilitar menú (borrado lógico)
     * Establece medeshabilitado con la fecha/hora actual
     */
    public function deshabilitar() {
        $resultado = -1;
        if ($this->objPdo->Iniciar()) {
            $sql = "UPDATE menu SET medeshabilitado = CURRENT_TIMESTAMP WHERE idmenu = {$this->getIdmenu()}";
            $resultado = $this->objPdo->Ejecutar($sql);
        }
        return $resultado;
    }

    /**
     * Habilitar menú (quitar borrado lógico)
     * Establece medeshabilitado como NULL
     */
    public function habilitar() {
        $resultado = -1;
        if ($this->objPdo->Iniciar()) {
            $sql = "UPDATE menu SET medeshabilitado = NULL WHERE idmenu = {$this->getIdmenu()}";
            $resultado = $this->objPdo->Ejecutar($sql);
        }
        return $resultado;
    }

    /**
     * Listar solo menús activos (medeshabilitado IS NULL)
     */
    public function listarActivos() {
        $resultado = -1;
        if ($this->objPdo->Iniciar()) {
            $sql = "SELECT * FROM menu WHERE medeshabilitado IS NULL";
            $resultado = $this->objPdo->Ejecutar($sql);
        }
        return $resultado;
    }
}
?>