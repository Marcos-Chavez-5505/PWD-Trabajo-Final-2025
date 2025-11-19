<?php
// spl_autoload_register(function ($clase) {
//     // echo "🎯 PHP está buscando la clase: '" . $clase . "'\n";

//     $rutas = [
//         //* CONTROL - CASE SENSITIVE (exacto)
//         ROOT . 'control/' . $clase . '.php',      // Session.php
//         ROOT . 'modelo/' . $clase . '.php',       // Session.php en modelo
//         ROOT . 'modelo/conector/' . $clase . '.php',
        
//         //* CONTROL - CASE INSENSITIVE  
//         ROOT . 'control/' . strtolower($clase) . '.php', // session.php
//         ROOT . 'modelo/conector/' . strtolower($clase) . '.php',
//         ROOT . 'modelo/' . strtolower($clase) . '.php',

//         ROOT . 'util/' . strtolower($clase) . '.php',
//     ];
    
//     $encontrado = false;
    
//     foreach ($rutas as $ruta) {
//         if (!$encontrado) {
//             // echo "  🔍 Probando ruta: " . $ruta . "\n";
            
//             if (file_exists($ruta)) {
//                 // echo "  ✅ ENCONTRADO! Cargando: " . $ruta . "\n";
//                 require_once $ruta;
//                 $encontrado = true;
//             } else {
//                 // echo "  ❌ No existe\n";
//             }
//         }
//     }
    
//     if (!$encontrado) {
//         // echo "🚨 ERROR: No se encontró la clase '" . $clase . "'\n";
//     }
    
//     // echo "----------------------------------------\n";
// });
spl_autoload_register(function ($claseSolicitada) {
    error_log("🎯 AUTOLOADER INICIADO - Buscando clase: '" . $claseSolicitada . "'");
    
    $directorios = [
        ROOT . 'control/',
        ROOT . 'modelo/', 
        ROOT . 'modelo/conector/',
        ROOT . 'util/'
    ];

    // Log de directorios
    error_log("📁 Directorios a buscar:");
    foreach ($directorios as $dir) {
        error_log("   - " . $dir . " → " . (is_dir($dir) ? "✅ Existe" : "❌ No existe"));
    }
    error_log("----------------------------------------");

    $totalArchivosRevisados = 0;
    $archivosCargados = 0;

    foreach ($directorios as $directorio) {
        if (!is_dir($directorio)) {
            error_log("⏩ Saltando directorio (no existe): " . $directorio);
            continue;
        }
        
        error_log("🔍 Escaneando directorio: " . $directorio);
        $archivos = glob($directorio . '*.php');
        error_log("   📂 Archivos PHP encontrados: " . count($archivos));
        
        foreach ($archivos as $archivo) {
            $totalArchivosRevisados++;
            $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
            
            error_log("   📄 Revisando archivo: " . basename($archivo));
            error_log("      Nombre archivo: '" . $nombreArchivo . "'");
            error_log("      Clase solicitada: '" . $claseSolicitada . "'");
            error_log("      strcasecmp result: " . strcasecmp($nombreArchivo, $claseSolicitada));
            
            if (strcasecmp($nombreArchivo, $claseSolicitada) === 0) {
                error_log("      ✅ COINCIDENCIA ENCONTRADA! strcasecmp = 0");
                error_log("      📥 Cargando archivo: " . $archivo);
                
                require_once $archivo;
                $archivosCargados++;
                
                error_log("      🔍 Verificando si la clase existe...");
                
                if (class_exists($claseSolicitada, false)) {
                    error_log("      🎉 ✅ CLASE '" . $claseSolicitada . "' CARGADA EXITOSAMENTE!");
                    error_log("      📊 RESUMEN: " . $totalArchivosRevisados . " archivos revisados, " . $archivosCargados . " archivos cargados");
                    error_log("========================================");
                    return;
                } else {
                    error_log("      ⚠  Archivo cargado pero la clase '" . $claseSolicitada . "' no se encuentra en él");
                    error_log("      💡 Posibles causas:");
                    error_log("         - El archivo no contiene la clase '" . $claseSolicitada . "'");
                    error_log("         - La clase tiene un namespace diferente");
                    error_log("         - Error de sintaxis en el archivo");
                }
            } else {
                error_log("      ❌ No coincide (strcasecmp = " . strcasecmp($nombreArchivo, $claseSolicitada) . ")");
            }
        }
        error_log("   💤 Fin del directorio: " . $directorio);
    }

    error_log("🚨 ERROR: No se pudo cargar la clase '" . $claseSolicitada . "'");
    error_log("📊 RESUMEN FINAL: " . $totalArchivosRevisados . " archivos revisados, " . $archivosCargados . " archivos cargados");
    error_log("💡 Posibles soluciones:");
    error_log("   - Verificar que el archivo existe y tiene el nombre correcto");
    error_log("   - Verificar que la clase tiene el mismo nombre que el archivo"); 
    error_log("   - Revisar namespaces si los hay");
    error_log("   - Verificar permisos de archivo");
    error_log("========================================");
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