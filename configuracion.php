<?php
  /**
   * /-------------- IMPORTANTE ---------------\
   * |  este archivo debe ser incluído antes   |
   * |  que cualquier otro output (HTML)       |
   * |  para que funcione header()             |
   * \-----------------------------------------/
   */
  session_start();
  header('Content-Type: text/html; charset=utf-8');
  header("Cache-Control: no-cache, must-revalidate ");
  
  $PROYECTO = 'PWD';
  
  define('ROOT', $_SERVER['DOCUMENT_ROOT'] . "/$PROYECTO/");
  define('BASE_URL', "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/");
  
  define('FRONT_PAGE', BASE_URL . '/home/html/index.html');
  define('LOGIN_PAGE', BASE_URL . 'vista/login/login.php'); // podemos usarlo para TP5
  
  include_once(ROOT . 'util/funciones.php');
?>