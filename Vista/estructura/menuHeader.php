<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/control/ControlMenu.php";
// Mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// O más específico
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

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
