<?php
class abmGestionarRoles{

    //alta rol
    public function alta($rol){
        $rta = false;
        $objRol = new Rol();
        $objRol->setDescripcionRol($rol['rodescripcion']);

        if($objRol->insertar()){
            $rta = true;
        }
        return $rta;
    }
     
    //baja rol
    public function baja($rol){
        $rta = false;

        $objRol = new Rol();

        if ($objRol->buscar($rol)) {
            if ($objRol->eliminar()) {
                $rta = true;
            }
        }

        return $rta;
    }

    //modificar rol
    public function modificar($rol){
        $rta = false;
        $objRol = new Rol();

        if($objRol->buscar($rol['idrol'])){

            $objRol->setDescripcionRol($rol['rodescripcion']);

            if($objRol->modificar()){
                $rta = true;
            }
        }
        return $rta;
    }
}
?>