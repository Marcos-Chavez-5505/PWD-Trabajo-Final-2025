<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/control/ControlMenu.php";

$ctrl = new controlMenu();
$menus = $ctrl->obtenerMenuParaHeader();

function imprimirMenu($items) {
    foreach ($items as $item) {
        $menu = $item['obj'];
        echo "<li class='nav-item dropdown'>";

        $tieneHijos = !empty($item['hijos']);

        if ($tieneHijos) {
            echo "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown'>" 
                    . htmlspecialchars($menu->getMenombre()) . "</a>";
            echo "<ul class='dropdown-menu'>";
            imprimirMenu($item['hijos']);
            echo "</ul>";
        } else {
            $url = $menu->getMeurl() ?: '#';
            echo "<a class='nav-link' href='" . htmlspecialchars($url) . "'>"
                    . htmlspecialchars($menu->getMenombre()) . "</a>";
        }

        echo "</li>";
    }
}

ob_start();
imprimirMenu($menus);
echo ob_get_clean();
?>
