<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/header.php';
// En tu código
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');

// Para MySQL específicamente
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Productos</title>
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/color.css">
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
</head>
    
    <div id="main-content">
        <h2>Gestión de Productos</h2>
        <p>Administra los productos del sistema.</p>
        <div id="easyui-content">
            
            <table id="dg" title="Mis productos" class="easyui-datagrid" style="width:100%;height:400px"
                url="../action/gestion/productos/get_productos.php"
                toolbar="#toolbar" pagination="true"
                rownumbers="true" fitColumns="true" singleSelect="true">
                <thead>
                    <tr>
                        <th field="idproducto" width="50">id</th>
                        <th field="pronombre" width="50">Nombre</th>
                        <th field="prodetalle" width="50">Detalle</th>
                        <th field="procantstock" width="50">Stock</th>
                        <th field="proprecio" width="50">Precio</th>
                        <th field="proimagen" width="50">Imagen</th>
                    </tr>
                </thead>
            </table>
            
            <div id="toolbar">
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newProduct()">Nuevo Producto</a>
                <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editProduct()">Editar Producto</a>
                <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyProduct()">Eliminar Producto</a>
            </div>
            
            <div id="dlg" class="easyui-dialog" style="width:500px" 
                data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
                <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
                    <h3>Información del Producto</h3>
                    <div style="margin-bottom:10px">
                        <input name="Id" type="hidden">
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
            
            <div id="dlg-buttons">
                <a class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveProduct()" style="width:90px">Guardar</a>
                <a class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
            </div>  
        </div>
    </div>

<script type="text/javascript">

    var url;
    var baseUrl = '/PWD-TP-FINAL/Vista/private/action/gestion/productos/';

    function newProduct(){
        $('#dlg').dialog('open').dialog('center').dialog('setTitle','Nuevo Producto');
        $('#fm').form('clear');
        url = baseUrl + 'save_producto.php?action=create';
    }

    function editProduct(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','Editar Producto');
            $('#fm').form('load',row);
            url = baseUrl + 'save_producto.php?action=update&id='+row.idproducto;
        } else {
            $.messager.alert('Advertencia','Por favor selecciona un producto.','warning');
        }
    }

    function saveProduct(){
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if (result.errorMsg){
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

    function destroyProduct(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirmar','¿Estás seguro de eliminar el producto: '+row.pronombre+'?',function(r){
                if (r){
                    $.post(baseUrl + 'save_producto.php',
                        {action: 'delete', id: row.idproducto},
                        function(result){
                            if (result.success){
                                $('#dg').datagrid('reload');
                            } else {
                                $.messager.show({
                                    title: 'Error',
                                    msg: result.errorMsg
                                });
                            }
                        },'json');
                }
            });
        } else {
            $.messager.alert('Advertencia','Por favor selecciona un producto.','warning');
        }
    }
</script>
<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/Vista/estructura/footer.php';
?>