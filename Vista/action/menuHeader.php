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
        $nombre = htmlspecialchars($menu->getMenombre());
        $url = $menu->getMeurl() ?: '#';

        //Asignar íconos según el nombre del menú
        $icono = '';
        switch (strtolower($nombre)) {
            case 'carrito':
                $icono = '
                <span class="position-relative menu-icono-carrito">
                    <i class="fa-solid fa-cart-shopping fa-lg"></i>
                    <span id="contador-carrito"
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size: 0.65rem; display: none;">
                    0
                    </span>
                </span>';
                break;

            case 'mi cuenta':
            case 'cuenta':
                $icono = '<i class="fa-solid fa-user-circle fa-lg"></i>';
                break;

            case 'mis compras':
                $icono = '<i class="fa-solid fa-receipt fa-lg"></i>';
                break;

            case 'inicio':
                $icono = '<i class="fa-solid fa-house fa-lg"></i>';
                break;

            default:
                $icono = $nombre;
        }

        // Enlaces del menú
        if ($tieneHijos) {
            echo "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown' title='$nombre'>" . $icono . "</a>";
            echo "<ul class='dropdown-menu'>";
            imprimirMenu($item['hijos']);
            echo "</ul>";
        } else {
            echo "<a class='nav-link' href='" . htmlspecialchars($url) . "' title='$nombre'>" . $icono . "</a>";
        }

        echo "</li>";
    }
}

ob_start();
imprimirMenu($menus);
echo ob_get_clean();

echo "<script>document.dispatchEvent(new Event('menuCargado'));</script>";
?>
