document.addEventListener('DOMContentLoaded', () => {

  function parseMoneda(text) {
    return parseFloat(text.replace(/\./g, '').replace(',', '.').replace(/[^\d.]/g, '')) || 0;
  }

  async function actualizarCantidad(accion, idProducto) {
    try {
      const url = '/PWD-TP-FINAL/Vista/action/' + accion + '.php';

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
        mostrarAlerta('danger', 'Error inesperado del servidor', 'Intenta nuevamente más tarde');
        return;
      }

      if (data.ok) {
        const fila = document.querySelector(`tr[data-id-producto='${idProducto}']`);
        if (!fila) return;

        if (data.cantidad > 0) {
          fila.querySelector('.cantidad').textContent = data.cantidad;
          fila.querySelector('.subtotal').textContent =
            "$" + (data.cantidad * data.precio).toFixed(2).replace('.', ',');
        } else {
          fila.remove();
        }

        let total = 0;
        document.querySelectorAll('.subtotal').forEach(td => {
          total += parseMoneda(td.textContent);
        });
        document.getElementById('total-carrito').textContent =
          "$" + total.toFixed(2).replace('.', ',');

      } else {
        mostrarAlerta('warning', 'Atención', data.msg || 'No se pudo actualizar la cantidad');
      }

    } catch (error) {
      console.error("Error conexión:", error);
      mostrarAlerta('danger', 'Error de conexión', 'No se pudo conectar con el servidor');
    }
  }

  // Botones de cantidad
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

  // Botón de finalizar compra
  const botonFinalizar = document.getElementById('finalizar-compra');
  if (botonFinalizar) {
    botonFinalizar.addEventListener('click', async () => {
      const idUsuario = botonFinalizar.getAttribute('data-usuario-id');
      if (idUsuario) {
        try {
          const response = await fetch('/PWD-TP-FINAL/Vista/action/realizarCompra.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idUsuario: parseInt(idUsuario) })
          });

          const texto = await response.text();
          let data;
          try {
            data = JSON.parse(texto);
          } catch (e) {
            console.error("Error JSON:", texto);
            mostrarAlerta('danger', 'Error inesperado del servidor', 'No se pudo procesar la compra');
            return;
          }

          if (data.ok) {
            // Recargar para mostrar mensaje de sesión
            window.location.reload();
          } else {
            mostrarAlerta('danger', 'Error', 'No se pudo completar la compra.');
          }

        } catch (error) {
          console.error("Error conexión:", error);
          mostrarAlerta('danger', 'Error de conexión', 'No se pudo conectar con el servidor');
        }
      } else {
        mostrarAlerta('warning', 'Usuario no identificado', 'No se recibió el ID del usuario');
      }
    });
  }

  // --- ALERTAS BOOTSTRAP TEMPORALES ---
  function mostrarAlerta(tipo, titulo, texto) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow`;
    alertDiv.style.zIndex = '2000';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
      <strong>${titulo}</strong> ${texto}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
      const alertInstance = bootstrap.Alert.getOrCreateInstance(alertDiv);
      alertInstance.close();
    }, 3000);
  }
});
