<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/Vista/action/accionRolesPermitidos.php";

include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';

require_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/action/crearCombo.php";


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ABM - Menú</title>

<link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/themes/color.css">
<link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/demo/demo.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">  

<link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/Vista/css/tpFinal.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
<div class="menu-admin-container">
  <div class="menu-admin-box">
    <h2>ABM - Menú</h2>
    <p>Seleccione la acción que desea realizar.</p>

    <table id="dg" title="Administrador de ítems del menú" class="easyui-datagrid"
        url="/PWD-TP-FINAL/Vista/action/listar_menu.php"
        toolbar="#toolbar" 
        pagination:true,
        rownumbers:true,
            fitColumns:true,
            nowrap:false,
            autoRowHeight:true,
            singleSelect:true,
            style="min-width: fit-content;">

        <thead>
            <tr>
                <th field="idmenu" width="fit-content">ID</th>
                <th field="menombre" width="fit-content">Nombre</th>
                <th field="medescripcion" width="fit-content">Descripción</th>
                <th field="meurl" width="fit-content">URL</th>
                <th field="idpadre" width="fit-content">Submenú de</th>
                <th field="medeshabilitado" width="fit-content">Deshabilitado</th>
            </tr>
        </thead>
    </table>

    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMenu()">Nuevo Menú</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMenu()">Editar Menú</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyMenu()">Baja Menú</a>
    </div>

    <div id="dlg" class="easyui-dialog" style="width:600px"
        data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">

        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
            <h3>Información del Menú</h3>
            <div style="margin-bottom:10px">
                <input name="menombre" id="menombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="medescripcion" id="medescripcion" class="easyui-textbox" required="true" label="Descripción:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="meurl" id="meurl" class="easyui-textbox" label="URL:" style="width:100%">
            </div>

            <?php echo $combo; ?>

            <div style="margin-bottom:10px">
                <input class="easyui-checkbox" name="medeshabilitado" value="medeshabilitado" label="Deshabilitar:">
            </div>
        </form>
    </div>

    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveMenu()" style="width:90px">Aceptar</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
    </div>
  </div>
</div>

<script type="text/javascript">
var url;

function newMenu(){
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','Nuevo Menú');
    $('#fm').form('clear');
    url = '/PWD-TP-FINAL/Vista/action/alta_menu.php';
}

function editMenu(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $('#dlg').dialog('open').dialog('center').dialog('setTitle','Editar Menú');
        $('#fm').form('load', row);
        url = '/PWD-TP-FINAL/Vista/action/edit_menu.php?action=mod&idmenu=' + row.idmenu;
    }
}

function saveMenu(){
    $('#fm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            var result = eval('(' + result + ')');
            if (!result.respuesta){
                $.messager.show({
                    title: 'Error',
                    msg: result.errorMsg
                });
            } else {
                $('#dlg').dialog('close');
                $('#dg').datagrid('reload');
            }
        }
    });
}

function destroyMenu(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $.messager.confirm('Confirmación','¿Seguro que desea eliminar el menú?', function(r){
            if (r){
                $.post('/PWD-TP-FINAL/Vista/action/eliminar_menu.php?idmenu=' + row.idmenu,
                {idmenu: row.id},
                function(result){
                    if (result.respuesta){
                        $('#dg').datagrid('reload');
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    }
                }, 'json');
            }
        });
    }
}
</script>

<!-- JS de EasyUI -->
<script type="text/javascript" src="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/jquery.min.js"></script>
<script type="text/javascript" src="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/jquery.easyui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php'; ?>
</body>
</html>
