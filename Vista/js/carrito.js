document.addEventListener('DOMContentLoaded', () => {

    function parseMoneda(text) {
        return parseFloat(text.replace(/\./g, '').replace(',', '.').replace(/[^\d.]/g, ''));
    }

    async function actualizarCantidad(url, idProducto) {
        try {
            const respuesta = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'idproducto=' + encodeURIComponent(idProducto)
            });
            const texto = await respuesta.text();
            const data = JSON.parse(texto);

            if (data.ok) {
                const fila = document.querySelector(`tr[data-id-producto='${idProducto}']`);
                if (!fila) return;

                if (data.cantidad > 0) {
                    fila.querySelector('.cantidad').textContent = data.cantidad;
                    fila.querySelector('.subtotal').textContent = "$" + (data.cantidad * data.precio).toFixed(2).replace('.', ',');
                } else {
                    fila.remove();
                }

                // Actualizar total
                let total = 0;
                document.querySelectorAll('.subtotal').forEach(td => {
                    total += parseMoneda(td.textContent);
                });
                document.getElementById('total-carrito').textContent = "$" + total.toFixed(2).replace('.', ',');
            } else {
                alert("⚠ " + data.msg);
            }
        } catch (error) {
            alert("❌ Error de conexión:\n\n" + error);
        }
    }

    document.querySelectorAll('.reducir-cantidad').forEach(boton => {
        boton.addEventListener('click', () => {
            const idProducto = boton.getAttribute('data-id-producto');
            actualizarCantidad('../public/action/reducirProducto.php', idProducto);
        });
    });

    document.querySelectorAll('.aumentar-cantidad').forEach(boton => {
        boton.addEventListener('click', () => {
            const idProducto = boton.getAttribute('data-id-producto');
            actualizarCantidad('../public/action/aumentarProducto.php', idProducto);
        });
    });

    const botonFinalizar = document.getElementById('finalizar-compra');
    if (botonFinalizar) {
        botonFinalizar.addEventListener('click', () => {
            alert("Funcionalidad de finalizar compra aún no implementada.");
        });
    }
});
