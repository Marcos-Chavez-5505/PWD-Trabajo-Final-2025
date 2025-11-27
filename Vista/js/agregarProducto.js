function inicializarBotonesCarrito() {
  const botones = document.querySelectorAll('.agregar-carrito');

  botones.forEach(boton => {
    const nuevoBoton = boton.cloneNode(true);
    boton.parentNode.replaceChild(nuevoBoton, boton);

    nuevoBoton.addEventListener('click', async () => {
      const id = nuevoBoton.dataset.id;

      nuevoBoton.disabled = true;
      nuevoBoton.classList.remove('btn-compra');
      nuevoBoton.classList.add('btn-warning');
      nuevoBoton.innerHTML = '<i class="bi bi-hourglass-split"></i> Agregando...';

      try {
        const respuesta = await fetch(BASE_URL + 'Vista/action/agregarProducto.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'idproducto=' + encodeURIComponent(id)
        });

        const data = await respuesta.text();
        let json;
        try { json = JSON.parse(data); } catch { alert("⚠ Error inesperado:\n\n" + data); return; }

        if (json.ok) {
          nuevoBoton.classList.remove('btn-warning');
          nuevoBoton.classList.add('btn-success');
          nuevoBoton.innerHTML = '<i class="bi bi-check-circle"></i> Agregado';
          if (typeof window.actualizarContadorCarrito === 'function') {
            window.actualizarContadorCarrito();
          }

          setTimeout(() => {
            nuevoBoton.disabled = false;
            nuevoBoton.classList.remove('btn-success');
            nuevoBoton.classList.add('btn-compra');
            nuevoBoton.innerHTML = '<i class="bi bi-cart-fill"></i> Agregar al carrito';
          }, 1200);

        } else {
          nuevoBoton.classList.remove('btn-warning');
          nuevoBoton.classList.add('btn-danger');
          nuevoBoton.innerHTML = '<i class="bi bi-x-circle"></i> Error';
          alert("⚠ " + json.msg);
          setTimeout(() => {
            nuevoBoton.classList.remove('btn-danger');
            nuevoBoton.classList.add('btn-compra');
            nuevoBoton.innerHTML = '<i class="bi bi-cart-fill"></i> Agregar al carrito';
            nuevoBoton.disabled = false;
          }, 1200);
        }

      } catch (error) {
        alert("❌ Error de conexión:\n\n" + error);
        nuevoBoton.classList.remove('btn-warning');
        nuevoBoton.classList.add('btn-compra');
        nuevoBoton.innerHTML = '<i class="bi bi-cart-fill"></i> Agregar al carrito';
        nuevoBoton.disabled = false;
      }
    });
  });
}

document.addEventListener("DOMContentLoaded", function(){
  document.querySelectorAll('.product-stock').forEach((stockElement) => {
      const stockText = stockElement.textContent || stockElement.innerText;
      const stockMatch = stockText.match(/\d+/);
      const stock = stockMatch ? parseInt(stockMatch[0]) : NaN;
      
      if (!isNaN(stock) && stock === 0) {
          const productCard = stockElement.closest(".card, .product-card, .product-item");
          if (productCard) {
              productCard.classList.add('border-danger','text-white');
              
              const addButton = productCard.querySelector('.agregar-carrito');
              if (addButton) {
                  addButton.disabled = true;
                  addButton.textContent = ' Sin stock';
                  addButton.classList.remove('btn-primary', 'btn-success', 'btn-compra');
                  addButton.classList.remove('btn-compra');
                  addButton.classList.add('btn-danger');
              }
          }
      }
  });
});


// Escuchar evento del menú cargado
document.addEventListener('menuListo', inicializarBotonesCarrito);
