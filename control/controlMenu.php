<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";
class ControlMenu{

    function construirMenuJerarquico($menus) {
        $arbol = [];

        // Indexar menús por idmenu
        $index = [];
        foreach ($menus as $menu) {
            $index[$menu->getIdmenu()] = [
                'obj' => $menu,
                'hijos' => []
            ];
        }

        // Crear árbol
        foreach ($index as $id => &$item) {
            $padre = $item['obj']->getObjMenu();
            if ($padre) {
                $padreId = $padre->getIdmenu();
                if (isset($index[$padreId])) {
                    $index[$padreId]['hijos'][] =& $item;
                }
            } else {
                $arbol[] =& $item;
            }
        }

        return $arbol;
    }

    public function obtenerMenuParaHeader() {
        $estructura = [];

        if (isset($_SESSION['idusuario'])) {
            $objMenu = new MenuRol();
            $objRol = new UsuarioRol();
            $idusuario = $_SESSION['idusuario'];

            $idRol = $objRol->rolDeUsuario($idusuario);
    
            if ($idRol > 0){
                $menus = $objMenu->listarMenuPorRol($idRol);
        
                $estructura = $this->construirMenuJerarquico($menus);
            }
        }
        return $estructura;
    }
}

?>