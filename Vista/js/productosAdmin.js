// productosAdmin.js
var url;
var baseUrl = '/PWD-TP-FINAL/vista/private/action/gestion/productos/';

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
      var result = typeof result === 'string' ? JSON.parse(result) : result;
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

function uploadImage(){
  var row = $('#dg').datagrid('getSelected');
  if (row) {
      $('#dlg-img').dialog('open').dialog('center').dialog('setTitle', 'Subir Imagen');
      $('#fm-img')[0].reset();
      $('[name=idproducto_img]').val(row.idproducto);
  } else {
      $.messager.alert('Advertencia', 'Por favor selecciona un producto.', 'warning');
  }
}

function saveImage() {
    var formData = new FormData($('#fm-img')[0]);

    $.ajax({
        url: baseUrl + 'upload_imagen.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(result) {
            var result = typeof result === 'string' ? JSON.parse(result) : result;
            
            if (result.success) {
                $('#dlg-img').dialog('close');
                $('#dg').datagrid('reload');
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.errorMsg || 'Error subiendo imagen.'
                });
            }
        }
    });
}
