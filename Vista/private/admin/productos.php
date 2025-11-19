<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';

// Mostrar errores PHP (solo para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Productos</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- EasyUI -->
  <link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/vista/js/jquery-easyui-1.6.6/themes/default/easyui.css">
  <link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/vista/js/jquery-easyui-1.6.6/themes/icon.css">
  <link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/vista/js/jquery-easyui-1.6.6/themes/color.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- JS de EasyUI -->
  <script type="text/javascript" src="/PWD-TP-FINAL/vista/js/jquery-easyui-1.6.6/jquery.min.js"></script>
  <script type="text/javascript" src="/PWD-TP-FINAL/vista/js/jquery-easyui-1.6.6/jquery.easyui.min.js"></script>
</head>

<body>
  <div class="menu-admin-container">
    <div class="menu-admin-box">
      <h2>Gestión de Productos</h2>
      <p>Administra los productos del sistema.</p>

      <table id="dg" title="Mis productos" class="easyui-datagrid"
             style="width:100%;height:400px"
             url="/PWD-TP-FINAL/vista/private/action/gestion/productos/get_productos.php"
             toolbar="#toolbar" pagination="true"
             rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
          <tr>
            <th field="idproducto" width="50">ID</th>
            <th field="pronombre" width="80">Nombre</th>
            <th field="prodetalle" width="120">Detalle</th>
            <th field="procantstock" width="50">Stock</th>
            <th field="proprecio" width="50">Precio</th>
            <th field="proimagen" width="80">Imagen</th>
          </tr>
        </thead>
      </table>

      <div id="toolbar">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newProduct()">Nuevo Producto</a>
        <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editProduct()">Editar Producto</a>
        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyProduct()">Eliminar Producto</a>
        <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="uploadImage()">Subir Imagen</a>
      </div>

      <div id="dlg" class="easyui-dialog" style="width:500px"
           data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
          <h3>Información del Producto</h3>
          <input name="Id" type="hidden">

          <div style="margin-bottom:10px">
            <input name="pronombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
          </div>

          <div style="margin-bottom:10px">
            <input name="prodetalle" class="easyui-textbox" multiline="true" label="Detalle:" style="width:100%;height:60px">
          </div>

          <div style="margin-bottom:10px">
            <input name="procantstock" class="easyui-numberbox" required="true" label="Stock:" style="width:100%">
          </div>

          <div style="margin-bottom:10px">
            <input name="proprecio" class="easyui-numberbox" precision="2" required="true" label="Precio:" style="width:100%">
          </div>

          <div style="margin-bottom:10px">
            <input name="proimagen" class="easyui-textbox" label="Imagen:" style="width:100%">
          </div>
        </form>
      </div>

      <div id="dlg-img" class="easyui-dialog" style="width:400px"
          data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-img-buttons'">
          <form id="fm-img" method="post" enctype="multipart/form-data" style="margin:0;padding:20px 30px">
              <h3>Subir Imagen</h3>
              <input type="hidden" name="idproducto_img">
              <div style="margin-bottom:20px">
                  <input type="file" name="proimagen" accept="image/*" required>
              </div>
          </form>
      </div>
      <div id="dlg-img-buttons">
          <a class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveImage()" style="width:90px">Guardar</a>
          <a class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-img').dialog('close')" style="width:90px">Cancelar</a>
      </div>

      <div id="dlg-buttons">
        <a class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveProduct()" style="width:90px">Guardar</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
      </div>
    </div>
  </div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/PWD-TP-FINAL/Vista/js/productosAdmin.js"></script>

<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php';
?>
</body>
</html>
