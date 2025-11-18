document.addEventListener('DOMContentLoaded', () => {
  const botones = document.querySelectorAll('.agregar-carrito');

  botones.forEach(boton => {
    boton.addEventListener('click', async () => {
      const id = boton.dataset.id;

      // ✅ Dar feedback visual inmediato
      boton.disabled = true;
      boton.classList.remove('btn-compra');
      boton.classList.add('btn-warning');
      boton.innerHTML = '<i class="bi bi-hourglass-split"></i> Agregando...';

      try {
        const respuesta = await fetch(BASE_URL + 'Vista/public/action/agregarProducto.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'idproducto=' + encodeURIComponent(id)
        });

        const texto = await respuesta.text();
        let data;
        try {
          data = JSON.parse(texto);
        } catch {
          alert("⚠ Error inesperado:\n\n" + texto);
          return;
        }

        if (data.ok) {
          boton.classList.remove('btn-warning');
          boton.classList.add('btn-success');
          boton.innerHTML = '<i class="bi bi-check-circle"></i> Agregado';
          setTimeout(() => {
            boton.classList.remove('btn-success');
            boton.classList.add('btn-compra');
            boton.innerHTML = '<i class="bi bi-cart-fill"></i> Agregar al carrito';
            boton.disabled = false;
          }, 1200);
        } else {
          boton.classList.remove('btn-warning');
          boton.classList.add('btn-danger');
          boton.innerHTML = '<i class="bi bi-x-circle"></i> Error';
          alert("⚠ " + data.msg);
          setTimeout(() => {
            boton.classList.remove('btn-danger');
            boton.classList.add('btn-compra');
            boton.innerHTML = '<i class="bi bi-cart-fill"></i> Agregar al carrito';
            boton.disabled = false;
          }, 1200);
        }

      } catch (error) {
        alert("❌ Error de conexión:\n\n" + error);
        boton.classList.remove('btn-warning');
        boton.classList.add('btn-compra');
        boton.innerHTML = '<i class="bi bi-cart-fill"></i> Agregar al carrito';
        boton.disabled = false;
      }
    });
  });
});
