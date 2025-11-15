<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/configuracion.php";

function imprimirMenu($items) {
    echo "<ul>";
    
    foreach ($items as $item) {
        $menu = $item['obj'];
        echo "<li>";
        echo "<a href='#'>" . $menu->getMenombre() . "</a>";

        if (!empty($item['hijos'])) {
            imprimirMenu($item['hijos']); // recursividad
        }

        echo "</li>";
    }

    echo "</ul>";
}

$obj = new ControlMenu();
// Obtener menú disponible
$menuArbol = $obj->obtenerMenuParaHeader();

// Renderizar menú
imprimirMenu($menuArbol);
?>