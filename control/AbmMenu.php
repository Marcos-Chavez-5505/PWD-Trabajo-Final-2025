<?php
class AbmMenu{
    //Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto

    
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Menu
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idmenu',$param) and array_key_exists('menombre',$param)){
            $obj = new Menu();
            $objmenu = null;
            if (isset($param['idpadre'])){
                $objmenu = new Menu();
                $objmenu->setIdmenu($param['idpadre']);
                $objmenu->cargar();
                
            }
            if(!isset($param['medeshabilitado'])){
                $param['medeshabilitado']=null;
            }else{
                $param['medeshabilitado']= date("Y-m-d H:i:s");
            }
            $obj->setear($param['idmenu'], $param['menombre'],$param['medescripcion'], $param['meurl'],$objmenu,$param['medeshabilitado']); 
        }
        return $obj;
    }
    
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Menu
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idmenu']) ){
            $obj = new Menu();
            $obj->setIdmenu($param['idmenu']);
        }
        return $obj;
    }
    
    
    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    
    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idmenu']))
            $resp = true;
        return $resp;
    }
    
    public function alta($param){
        $param['idmenu'] = null;
        $param['medeshabilitado'] = null;

        $obj = $this->cargarObjeto($param);

        if ($obj != null && $obj->insertar()) {
            return [
                'respuesta' => true,
                'mensaje'   => ''
            ];
        } else {
            return [
                'respuesta' => false,
                'mensaje'   => 'La acción ALTA no pudo concretarse'
            ];
        }
    }

    public function baja($param){
        $respuesta = false;
        $mensaje = 'La acción ELIMINACION no pudo concretarse';

        if ($this->seteadosCamposClaves($param)) {

            $obj = $this->cargarObjetoConClave($param);

            if ($obj != null && $obj->eliminar()) {
                $respuesta = true;
                $mensaje = '';
            }
        }

        return [
            'respuesta' => $respuesta,
            'mensaje'   => $mensaje
        ];
    }

    public function modificacion($param){
        $respuesta = false;
        $mensaje = 'La acción MODIFICACION no pudo concretarse';

        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);

            if ($obj != null && $obj->modificar()) {
                $respuesta = true;
                $mensaje = '';
            }
        }

        return [
            'respuesta' => $respuesta,
            'mensaje'   => $mensaje
        ];
    }

    /**
     * permite buscar un objeto
     * @param array $param
     * @return boolean
     */
    public function buscar($param){
        $where = " true ";
        /*if ($param<>NULL){
            if  (isset($param['id']))
                $where.=" and id =".$param['id'];
            if  (isset($param['descrip']))
                 $where.=" and descrip ='".$param['descrip']."'";
        }*/
        $arreglo = Menu::listar($where);  
        return $arreglo;
    }

    public function crearCombo(){
        $List_Menu = $this->buscar(null);

        $combo = '<select class="easyui-combobox" id="idpadre" name="idpadre" label="Submenú de?:" labelPosition="top" style="width:90%;">
                    <option></option>';

        foreach ($List_Menu as $objMenu) {
            $combo .= '<option value="' . $objMenu->getIdmenu() . '">' . $objMenu->getMenombre() . ': ' . $objMenu->getMedescripcion() . '</option>';
        }

        $combo .= '</select>';

        return $combo;
    }

    public function obtenerDatosMenu($filtros = []) {
        $lista = $this->buscar($filtros);
        $salida = [];

        foreach ($lista as $elem) {
            $salida[] = [
                'idmenu'         => $elem->getIdMenu(),
                'menombre'       => $elem->getMenombre(),
                'medescripcion'  => $elem->getMedescripcion(),
                'meurl'          => $elem->getMeurl(),
                'idpadre'        => $elem->getObjMenu() ? $elem->getObjMenu()->getMenombre() : null,
                'medeshabilitado'=> $elem->getMedeshabilitado()
            ];
        }

        return $salida;
    }

}
?>