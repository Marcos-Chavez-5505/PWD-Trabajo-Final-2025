<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestión de Compras</title>
    <link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/themes/icon.css">
    <script type="text/javascript" src="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/jquery.min.js"></script>
    <script type="text/javascript" src="/PWD-TP-FINAL/Vista/js/jquery-easyui-1.6.6/jquery.easyui.min.js"></script>
</head>
<body>
    <!-- Tu header aquí -->
    
    <div style="margin:20px;">
        <h2>Gestión de Compras</h2>
        
        <table id="dgCompras" class="easyui-datagrid" 
               style="width:100%;height:500px"
               title="Lista de Compras"
               data-options="
                   url:'../action/gestion/compras/compraAdmin.php',
                   method:'GET',
                   pagination:true,
                   rownumbers:true,
                   fitColumns:true,
                   singleSelect:true">
            <thead>
                <tr>
                    <th data-options="field:'idcompra',width:50">ID</th>
                    <th data-options="field:'fecha',width:120">Fecha</th>
                    <th data-options="field:'nombre_usuario',width:100">Cliente</th>
                    <th data-options="field:'email_usuario',width:150">Email</th>
                    <th data-options="field:'estado_actual',width:100">Estado</th>
                    <th data-options="field:'fecha_estado',width:120">Fecha Estado</th>
                    <th data-options="field:'_acciones',width:150,align:'center',formatter:formatAcciones">Acciones</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Tu footer aquí -->

    <script>
    // Función para formatear los botones de acción
    function formatAcciones(value, row) {
        var html = '';
        
        if (row.estado_actual !== 'Cancelada' && row.estado_actual !== 'cancelada') {
            html += '<a href="javascript:void(0)" class="easyui-linkbutton" ' +
                   'iconCls="icon-arrow-right" plain="true" ' +
                   'onclick="avanzarEstado(' + row.idcompra + ')">Siguiente</a>&nbsp;';
        }
        
        // Botón "Cancelar" (si no está ya cancelada)
        if (row.estado_actual !== 'Cancelada' && row.estado_actual !== 'cancelada') {
            html += '<a href="javascript:void(0)" class="easyui-linkbutton" ' +
                   'iconCls="icon-cancel" plain="true" ' +
                   'onclick="cancelarCompra(' + row.idcompra + ')">Cancelar</a>';
        } else {
            html += '<span style="color:#999">Cancelada</span>';
        }
        
        return html;
    }

    // Función para avanzar al siguiente estado
    function avanzarEstado(idcompra) {
        $.messager.confirm('Confirmar', '¿Avanzar al siguiente estado?', function(r) {
            if (r) {
                $.post('../action/gestion/compras/cambiarEstado.php', {
                    idcompra: idcompra,
                    accion: 'siguienteEstado'
                }, function(response) {
                    if (response.success) {
                        $.messager.alert('Éxito', 'Estado actualizado correctamente', 'info');
                        $('#dgCompras').datagrid('reload');
                    } else {
                        $.messager.alert('Error', response.error, 'error');
                    }
                }, 'json');
            }
        });
    }

    // Función para cancelar compra
    function cancelarCompra(idcompra) {
        $.messager.confirm('Confirmar', '¿Cancelar esta compra?', function(r) {
            if (r) {
                $.post('../action/gestion/compras/cambiarEstado.php', {
                    idcompra: idcompra,
                    accion: 'cancelar'
                }, function(response) {
                    if (response.success) {
                        $.messager.alert('Éxito', 'Compra cancelada correctamente', 'info');
                        $('#dgCompras').datagrid('reload');
                    } else {
                        $.messager.alert('Error', response.error, 'error');
                        console.log(response.error);
                    }
                }, 'json');
            }
        });
    }

    $(function(){
        $('#dgCompras').datagrid({
            onLoadSuccess: function(data){
                if (!data.success) {
                    $.messager.alert('Error', data.error || 'Error al cargar compras', 'error');
                }
            }
        });
    });
    </script>
</body>
</html>


<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/footer.php";
?>