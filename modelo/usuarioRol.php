<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

class UsuarioRol {
    private $idusuario;
    private $idrol;
    private $objUsuario;
    private $objRol;
    private $objPdo;

    public function __construct($objPdo = null) {
        $this->idusuario = null;
        $this->idrol = null;
        $this->objUsuario = null;
        $this->objRol = null;  
        $this->objPdo = $objPdo ?? new BDautenticacion();
    }

  // --- Getters ---
    public function getIdUsuario() { return $this->idusuario; }
    public function getIdRol() { return $this->idrol; }
    public function getObjUsuario() { return $this->objUsuario; }
    public function getObjRol() { return $this->objRol; }

    // --- Setters ---
    public function setIdUsuario($idusuario) { $this->idusuario = $idusuario; }
    public function setIdRol($idrol) { $this->idrol = $idrol; }
    public function setObjUsuario($objUsuario) { $this->objUsuario = $objUsuario; }
    public function setObjRol($objRol) { $this->objRol = $objRol; }

    // --- CRUD ---
    // Insertar
    public function insertar(){
    $rta = false;
    if($this->objPdo->Iniciar()){
        $idusuario = $this->idusuario ?? $this->getObjUsuario()->getIdusuario();
        $idrol = $this->idrol ?? $this->getObjRol()->getIdRol();

        $sql = "INSERT INTO usuariorol (idusuario, idrol)
                VALUES ($idusuario, $idrol)";
        $rta = $this->objPdo->Ejecutar($sql);
        }
       return $rta;
    }


    public function modificar(){
    $rta = false;
    if($this->objPdo->Iniciar()){
        $idusuario = $this->idusuario ?? $this->getObjUsuario()->getIdusuario();
        $idrol = $this->idrol ?? $this->getObjRol()->getIdRol();

        $sql = "UPDATE usuariorol SET 
                    idusuario = $idusuario,
                    idrol = $idrol
                WHERE idusuario = $idusuario
                AND idrol = $idrol";

        $rta = $this->objPdo->Ejecutar($sql);
        }
    return $rta;
    }

    public function eliminar(){
    $rta = false;
    if ($this->objPdo->Iniciar()){

        $idusuario = $this->idusuario ?? $this->getObjUsuario()?->getIdusuario();
        $idrol = $this->idrol ?? $this->getObjRol()?->getIdRol();

        $sql = "DELETE FROM usuariorol 
                WHERE idusuario = " . intval($idusuario) . " 
                AND idrol = " . intval($idrol);

        if($this->objPdo->Ejecutar($sql) >= 0) {
            $rta = true;
            }
        }
        return $rta;
    }   

    // Listar
    public function listar($condicion = ""){
        $arreglo = [];

        if($this->objPdo->Iniciar()){
            $sql = "SELECT * FROM usuariorol";
            if ($condicion !== "") {
                $sql .= " WHERE " . $condicion;
            }
            $this->objPdo->Ejecutar($sql);

            $filas = $this->objPdo->getFilas();
            if(!empty($filas)){

                foreach ($filas as $fila){
                    $objUR = new UsuarioRol($this->objPdo);

                    $objUsuario = new Usuario();
                    $res = $objUsuario->buscar($fila['idusuario']);
                    $objUR->setObjUsuario($objUsuario);

                    $objRol = new Rol();
                    $res = $objRol->buscar($fila['idrol']);
                    $objUR->setObjRol($objRol);

                    if ($res) $arreglo[] = $objUR;
                }
            }
        }
    return $arreglo;
    }

    public function cargar($objUsuario, $objRol){
        $this->setObjUsuario($objUsuario);
        $this->setObjRol($objRol);
    }

    // Si hace falta esta funcion, habria que corregirla
    // public function buscar($id) {
    //     $resultado = false;
    //     if ($this->objPdo->Iniciar()) {
    //         $this->objPdo->Ejecutar("SELECT * FROM compra WHERE idcompra = {$id}");
    //         $filas = $this->objPdo->getFilas();
    //         if (!empty($filas)) {
    //             $fila = $filas[0];
    //             $objUsuario = new Usuario();
    //             $objUsuario->buscar($fila['idusuario']);
    //             $this->cargar(
    //                 $fila['idcompra'],
    //                 $fila['cofecha'],
    //                 $objUsuario
    //             );
    //             $resultado = true;
    //         }
    //     }
    //     return $resultado;
    // }

    /**
     * Obtiene el rol asignado a un usuario.
     * Devuelve ID Rol
     */
    public function rolDeUsuario($idusuario){
        $rol = -1;
        $usuario = new Usuario();

        if($usuario->buscar($idusuario)){
            $sql = "SELECT r.*
                    FROM rol r
                    INNER JOIN usuariorol ur ON r.idrol = ur.idrol
                    INNER JOIN usuario u ON u.idusuario = ur.idusuario
                    WHERE u.idusuario = $idusuario";
    
            if ($this->objPdo->Iniciar()) {
                $res = $this->objPdo->Ejecutar($sql);
                if ($res) {
                    $filas = $this->objPdo->getFilas();
                    $objRol = new Rol();
                    $objRol->buscar($filas[0]['idrol']);
                    $rol = $objRol->getIdRol();
                }
            }
        }
    return $rol;
    }

}
?>
