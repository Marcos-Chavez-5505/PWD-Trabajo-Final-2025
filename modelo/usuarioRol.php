<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

class UsuarioRol{
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

    //Getters
    public function getIdusuario(){ return $this->idusuario; }

    public function getIdrol(){ return $this->idrol; }

    public function getObjUsuario(){ return $this->objUsuario; }

    public function getObjRol(){ return $this->objRol; }

    public function getObjPdo() {
    return $this->objPdo;
}


    //setters
    public function setIdusuario($idusuario){ $this->idusuario = $idusuario; }

    public function setIdrol($idrol){ $this->idrol = $idrol; }

    public function setObjUsuario($objUsuario){ $this->objUsuario = $objUsuario; }

    public function setObjRol($objRol){ $this->objRol = $objRol; }

    //insertar
    public function insertar(){
        $rta = false;
        if ($this->objPdo->Iniciar()){
            $idusuario = $this->idusuario ?? $this->getObjUsuario()->getIdusuario();
            $idrol = $this->idrol ?? $this->getObjRol()->getIdrol();

            $sql = "INSERT INTO usuariorol (idusuario, idrol)
                    VALUES ($idusuario, $idrol)";

            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    //modificar
    public function modificar(){
        $rta = false;
        if ($this->objPdo->Iniciar()){
            $idusuario = $this->idusuario ?? $this->getObjUsuario()->getIdusuario();
            $idrol = $this->idrol ?? $this->getObjRol()->getIdrol();

            $sql = "UPDATE usuariorol SET
                        idusuario = $idusuario,
                        idrol = $idrol
                    WHERE idusuario = $idusuario
                    AND idrol = $idrol";

            $rta = $this->objPdo->Ejecutar($sql);
        }
        return $rta;
    }

    //eliminar
    public function eliminar(){
        $rta = false;

        if ($this->objPdo->Iniciar()){

            $idusuario = $this->idusuario ?? $this->getObjUsuario()?->getIdusuario();
            $idrol = $this->idrol ?? $this->getObjRol()?->getIdrol();

            $sql = "DELETE FROM usuariorol
                    WHERE idusuario = " . intval($idusuario) . "
                    AND idrol = " . intval($idrol);

            if ($this->objPdo->Ejecutar($sql) >= 0) {
                $rta = true;
            }
        }
        return $rta;
    }

    //listar
    public function listar($condicion = ""){
        $arreglo = [];

        if($this->objPdo->Iniciar()){

            $sql = "SELECT * FROM usuariorol";
            if ($condicion != "") {
                $sql .= " WHERE " . $condicion;
            }

            $this->objPdo->Ejecutar($sql);
            $filas = $this->objPdo->getFilas();

            if(!empty($filas)){
                foreach($filas as $fila){
                    $objUR = new UsuarioRol($this->objPdo);

                    $objUsuario = new Usuario();
                    $objUsuario->buscar($fila['idusuario']);
                    $objUR->setObjUsuario($objUsuario);

                    $objRol = new Rol();
                    $objRol->buscar($fila['idrol']);
                    $objUR->setObjRol($objRol);

                    $arreglo[] = $objUR;
                }
            }
        }
        return $arreglo;
    }

    public function cargar($objUsuario, $objRol){
        $this->setObjUsuario($objUsuario);
        $this->setObjRol($objRol);
    }

    //con este metodo obtenemos el rol del usuario
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
