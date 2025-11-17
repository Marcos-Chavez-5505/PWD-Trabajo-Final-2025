<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

class ControlMenu {

    /**
     * Construye un árbol a partir de una lista de objetos Menu.
     * Recibe array de objetos Menu.
     */
    private function construirMenuJerarquicoDesdeMenus($menus) {
        $arbol = [];

        // Indexar por idmenu
        $index = [];
        foreach ($menus as $menu) {
            $index[$menu->getIdmenu()] = [
                'obj' => $menu,
                'hijos' => []
            ];
        }

        // Armar relaciones padre -> hijos usando idpadre del Menu
        foreach ($index as $id => &$item) {
            $menuObj = $item['obj'];
            $idPadre = $menuObj->getIdpadre(); // puede ser null

            if ($idPadre !== null && $idPadre !== '' && isset($index[$idPadre])) {
                // el padre existe en la lista: anexar como hijo
                $index[$idPadre]['hijos'][] = &$item;
            } else {
                // no tiene padre (es raíz) -> agregar al árbol
                $arbol[] = &$item;
            }
        }
        // limpiar referencias
        unset($item);

        return $arbol;
    }

    /**
     * Devuelve la estructura de menú (array) para el header, solo para el rol del usuario en session.
     */
    public function obtenerMenuParaHeader() {
        $estructura = [];

        if (isset($_SESSION['idusuario'])) {
            $idUsuario = $_SESSION['idusuario'];

            // Obtener id de rol del usuario (asumo que rolDeUsuario devuelve int)
            $usuarioRolOrm = new UsuarioRol();
            $idRol = $usuarioRolOrm->rolDeUsuario($idUsuario);

            if ($idRol && $idRol > 0) {
                // Obtener relaciones menu-rol
                $menuRolOrm = new MenuRol();
                $menuRolList = $menuRolOrm->listar("idrol = {$idRol}");

                // Convertir MenuRol[] -> Menu[] (obtener los objetos Menu reales)
                $menus = [];
                if (!empty($menuRolList)) {
                    foreach ($menuRolList as $menuRol) {
                        // asumimos que MenuRol tiene getObjMenu() que devuelve un Menu o null
                        $menuObj = $menuRol->getObjMenu();
                        if ($menuObj !== null) {
                            $menus[] = $menuObj;
                        }
                    }
                }

                // Ahora construir árbol a partir de Menu[] usando idpadre
                if (!empty($menus)) {
                    $estructura = $this->construirMenuJerarquicoDesdeMenus($menus);
                }
            }
        }

        return $estructura;
    }
}
?>
