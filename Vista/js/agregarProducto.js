document.addEventListener('DOMContentLoaded', () => {
  const botones = document.querySelectorAll('.agregar-carrito');

  botones.forEach(boton => {
    boton.addEventListener('click', async () => {
      const id = boton.dataset.id;

      try {
        const respuesta = await fetch(BASE_URL + 'vista/public/action/agregarProducto.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'idproducto=' + encodeURIComponent(id)
        });

        const texto = await respuesta.text();
        let data;
        try {
          data = JSON.parse(texto);
        } catch (e) {
          alert("⚠ Error inesperado:\n\n" + texto);
          return;
        }

        if (data.ok) {
          boton.classList.remove('btn-compra');
          boton.classList.add('btn-success');
          boton.innerHTML = '<i class="bi bi-check-circle"></i> Agregado';
          setTimeout(() => {
            boton.classList.remove('btn-success');
            boton.classList.add('btn-compra');
            boton.innerHTML = '<i class="bi bi-cart-fill"></i> Agregar al carrito';
          }, 1500);
        } else {
          alert("⚠ " + data.msg);
        }
      } catch (error) {
        alert("❌ Error de conexión:\n\n" + error);
      }
    });
  });
});
