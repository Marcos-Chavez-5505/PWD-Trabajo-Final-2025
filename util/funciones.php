<?php
// spl_autoload_register(function ($claseSolicitada) {
//     error_log("🎯 AUTOLOADER INICIADO - Buscando clase: '" . $claseSolicitada . "'");
    
//     $directorios = [
//         ROOT . 'control/',
//         ROOT . 'modelo/', 
//         ROOT . 'modelo/conector/',
//         ROOT . 'util/'
//     ];
//     $totalArchivosRevisados = 0;
//     $archivosCargados = 0;

//     foreach ($directorios as $directorio) {
//         if (!is_dir($directorio)) {
//             continue;
//         }
        
//         $archivos = glob($directorio . '*.php');
//         foreach ($archivos as $archivo) {
//             $totalArchivosRevisados++;
//             $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
//             if (strcasecmp($nombreArchivo, $claseSolicitada) === 0) {
//                 require_once $archivo;
//                 $archivosCargados++;
//             }
//         }
//     }
// });
spl_autoload_register(function ($claseSolicitada) {
    // Normalizar el nombre solicitado
    $claseNormalizada = strtolower(trim($claseSolicitada));

    // Directorios donde buscar
    $directorios = [
        ROOT . 'control/',
        ROOT . 'modelo/',
        ROOT . 'modelo/conector/',
        ROOT . 'util/'
    ];

    foreach ($directorios as $directorio) {

        if (!is_dir($directorio)) {
            continue;
        }

        // Obtener archivos PHP del directorio
        foreach (glob($directorio . '*.php') as $archivo) {

            $nombreArchivo = basename($archivo, '.php');

            // Comparación case-insensitive
            if (strtolower($nombreArchivo) === $claseNormalizada) {
                require_once $archivo;
                return; // Se encontró → finalizar
            }
        }
    }

    // Log opcional si no se encontró
    error_log("Autoloader: No se encontró archivo para clase '$claseSolicitada'");
});


function verEstructura($e){
  echo "<pre>";
  print_r($e);
  echo "</pre>";
}

function verSession(){
    echo "<pre>";
    var_dump($_SESSION);
    echo "</pre>";
}
// verSession();

//Esta funcion devuelve los datos en un array no importa si fue por $_POST o $_GET
function data_submitted() {
    
    $_AAux= array();
    if (!empty($_REQUEST))
        $_AAux =$_REQUEST;
     if (count($_AAux)){
            foreach ($_AAux as $indice => $valor) {
                if ($valor=="")
                    $_AAux[$indice] = 'null' ;
            }
        }
     return $_AAux;
        
}

?>