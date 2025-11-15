<?php
spl_autoload_register(function ($clase) {
    // echo "🎯 PHP está buscando la clase: '" . $clase . "'\n";

    $rutas = [
        //*MODELO CASE SENSITIVE
        ROOT . 'modelo/' . $clase . '.php',
        ROOT . 'modelo/conector/' . $clase . '.php',
        //*MODELO CASE INSENSITIVE
        ROOT . 'modelo/conector/' . strtolower($clase) . '.php',
        ROOT . 'modelo/' . strtolower($clase) . '.php',

        //*CONTROL CASE SENSITIVE
        ROOT . 'control/' . $clase . '.php',
        //*CONTROL CASE INSENSITIVE
        ROOT . 'control/' . strtolower($clase) . '.php',

        ROOT . 'util/' . strtolower($clase) . '.php',
        
    ];
    
    $encontrado = false;
    
    foreach ($rutas as $ruta) {
        if (!$encontrado) {
            // echo "  🔍 Probando ruta: " . $ruta . "\n";
            
            if (file_exists($ruta)) {
                // echo "  ✅ ENCONTRADO! Cargando: " . $ruta . "\n";
                require_once $ruta;
                $encontrado = true;
            } else {
                // echo "  ❌ No existe\n";
            }
        }
    }
    
    if (!$encontrado) {
        // echo "🚨 ERROR: No se encontró la clase '" . $clase . "'\n";
    }
    
    // echo "----------------------------------------\n";
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