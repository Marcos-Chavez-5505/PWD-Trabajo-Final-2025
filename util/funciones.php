<?php
/**
 * La función spl_autoload_register es PHP, se usa para registrar funciones o métodos que
 * cargan automáticamente clases o interfaces cuando se intenta usarlas y aún no fueron
 * incluídas.
 * Ejemplo:
 * $obj = new Usuario();
 * esto usa automáticamente 'Usuario' como $clase
*/
spl_autoload_register(function ($clase){
  //echo "Se cargó la clase: ".$clase;
  $directories = array(
    ROOT . '/modelo/',
    ROOT . '/modelo/conector/',
    ROOT . '/control/',
    ROOT
  );
  $modelo = ['conector', 'tp4', 'tp5'];

  // //print_r($directories);
  // foreach($directories as $i=>$directory){
  //   if(file_exists($directory . $clase . '.php')) {
  //     //echo "se incluyó ".$directory. $class_name . '.php';
  //     require_once($directory . $clase . '.php');
  //     return;
  //   }
  //   if ($i == 2){
  //     for ($j=1; $j<10; $j++){
  //       if(file_exists($directory . $j . '/' . $clase . '.php')) {
  //         require_once($directory . $j . '/' . $clase . '.php');
  //         return;
  //       }
  //     }
  //   }
  //   if ($i == 0){
  //     $max = count($modelo);
  //     for ($j=0; $j<$max; $j++){
  //       if(file_exists($directory . $modelo[$j] . '/' . $clase . '.php')) {
  //         require_once($directory . $modelo[$j] . '/' . $clase . '.php');
  //         return;
  //       }
  //     }
  //   }
  // }
  
  //print_r($directories);
  $bandera = false;
  $i = 0;
  $max = count($directories);
  while (!$bandera && $i<$max){
    $directory = $directories[$i];

    if(file_exists($directory . $clase . '.php')) {
      //echo "se incluyó ".$directory. $class_name . '.php';
      require_once($directory . $clase . '.php');
      $bandera = true;
    }

    if ($i==2){  // /control
      $j = 1;
      $jmax = 10; // número mágico
      while (!$bandera && $j<$jmax){
        if(file_exists($directory . $j . '/' . $clase . '.php')) {
          require_once($directory . $j . '/' . $clase . '.php');
          $bandera = true;
        }
        $j++;
      }
    }

    if ($i==0){  // /modelo
      $j = 0;
      $maxModelos = count($modelo);
      while (!$bandera && $j<$maxModelos){
        if(file_exists($directory . $modelo[$j] . '/' . $clase . '.php')) {
          require_once($directory . $modelo[$j] . '/' . $clase . '.php');
          $bandera = true;
        }
        $j++;
      }
    }

    $i++;
  }
  return;
});

function verEstructura($e){
  echo "<pre>";
  print_r($e);
  echo "</pre>";
}
?>