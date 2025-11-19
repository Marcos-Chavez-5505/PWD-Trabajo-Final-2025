document.addEventListener('DOMContentLoaded', () => {

  function parseMoneda(text) {
    return parseFloat(text.replace(/\./g, '').replace(',', '.').replace(/[^\d.]/g, '')) || 0;
  }

  async function actualizarCantidad(accion, idProducto) {
    try {
      const url = '/PWD-TP-FINAL/Vista/public/action/' + accion + '.php';

      const respuesta = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'idproducto=' + encodeURIComponent(idProducto)
      });

      const texto = await respuesta.text();
      let data;
      try {
        data = JSON.parse(texto);
      } catch (e) {
        console.error("Error JSON:", texto);
        alert("⚠ Error inesperado del servidor.");
        return;
      }

      if (data.ok) {
        const fila = document.querySelector(`tr[data-id-producto='${idProducto}']`);
        if (!fila) return;

        if (data.cantidad > 0) {
          fila.querySelector('.cantidad').textContent = data.cantidad;
          fila.querySelector('.subtotal').textContent = "$" + (data.cantidad * data.precio).toFixed(2).replace('.', ',');
        } else {
          fila.remove();
        }

        let total = 0;
        document.querySelectorAll('.subtotal').forEach(td => {
          total += parseMoneda(td.textContent);
        });
        document.getElementById('total-carrito').textContent = "$" + total.toFixed(2).replace('.', ',');

      } else {
        alert("⚠ " + data.msg);
      }

    } catch (error) {
      console.error("Error conexión:", error);
      alert("❌ Error al conectar con el servidor.");
    }
  }

  // Botones
  document.querySelectorAll('.reducir-cantidad').forEach(boton => {
    boton.addEventListener('click', () => {
      const idProducto = boton.getAttribute('data-id-producto');
      actualizarCantidad('reducirProducto', idProducto);
    });
  });

  document.querySelectorAll('.aumentar-cantidad').forEach(boton => {
    boton.addEventListener('click', () => {
      const idProducto = boton.getAttribute('data-id-producto');
      actualizarCantidad('aumentarProducto', idProducto);
    });
  });


  const botonFinalizar = document.getElementById('finalizar-compra');
  if (botonFinalizar) {
    botonFinalizar.addEventListener('click', () => {
      const idUsuario = botonFinalizar.getAttribute('data-usuario-id');
      realizarCompra(idUsuario);
    });
  }

async function realizarCompra(idUsuario){
    if (idUsuario){
        const response = await fetch('/PWD-TP-FINAL/Vista/public/action/realizarCompra.php',{
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({idUsuario: parseInt(idUsuario) })
        });

        const texto = await response.text();
        let data;
        try{
            data = JSON.parse(texto);
        } catch (e) {
            console.error("Error JSON:", texto);
            alert("⚠ Error inesperado del servidor.");
            return;
        }

        if (data.ok){
            alert('la compra se realizó exitosamente');
            window.location.reload();
        } else {
            alert(data.error);
        }
    } else {
        alert('no llegó el id de usuario');
    }
  }
});
