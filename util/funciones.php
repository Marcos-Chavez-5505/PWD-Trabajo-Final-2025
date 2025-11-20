<?php
spl_autoload_register(function ($claseSolicitada) {
    error_log("🎯 AUTOLOADER INICIADO - Buscando clase: '" . $claseSolicitada . "'");
    
    $directorios = [
        ROOT . 'control/',
        ROOT . 'modelo/', 
        ROOT . 'modelo/conector/',
        ROOT . 'util/'
    ];
    $totalArchivosRevisados = 0;
    $archivosCargados = 0;

    foreach ($directorios as $directorio) {
        if (!is_dir($directorio)) {
            continue;
        }
        
        $archivos = glob($directorio . '*.php');
        foreach ($archivos as $archivo) {
            $totalArchivosRevisados++;
            $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
            if (strcasecmp($nombreArchivo, $claseSolicitada) === 0) {
                require_once $archivo;
                $archivosCargados++;
            }
        }
    }
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