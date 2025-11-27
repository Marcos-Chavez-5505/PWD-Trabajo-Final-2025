  // Definición de la función que actualiza el contador
  async function actualizarContadorCarrito() {
    try {
      const res = await fetch('/PWD-TP-FINAL/Vista/action/cantidadCarrito.php');
      const data = await res.json();
      const badge = document.getElementById('contador-carrito');
      console.log("Badge encontrado:", badge);


      if (data.ok && badge) {
        if (data.cantidad > 0) {
          badge.textContent = data.cantidad;
          badge.style.display = 'inline';
        } else {
          badge.style.display = 'none';
        }
      }
    } catch (e) {
      console.error("Error al obtener cantidad del carrito:", e);
    }
  }
  window.actualizarContadorCarrito = actualizarContadorCarrito;


  // Carga del menú dinámico y actualización del contador cuando termine
  $(document).ready(function() {
    $.ajax({
      url: "/PWD-TP-FINAL/Vista/action/menuHeader.php",
      method: "GET",
      success: function(response) {
        $("#menu-dinamico").html(response);
        actualizarContadorCarrito(); 
        document.dispatchEvent(new CustomEvent('menuListo'));
      },
      error: function() {
        console.error("No se pudo cargar el menú.");
      }
    });
  });
