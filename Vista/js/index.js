let data = {};
let container = document.getElementById("container");

// Cargar datos de PHP
fetch("/PWD/home/php/getTPs.php")
  .then(res => res.json())
  .then(json => {
    data = json;
    mostrarTPs();
  });

// Mostrar tarjetas de TPs
function mostrarTPs() {
  container.innerHTML = "";
  Object.keys(data).forEach(tp => {
    let card = document.createElement("div");
    card.className = "card";
    card.innerText = "TP " + tp; // ej: "TP1", "TP2"
    card.onclick = () => mostrarEjercicios(tp);
    container.appendChild(card);
  });
}

// Mostrar tarjetas de Ejercicios dentro de un TP
function mostrarEjercicios(tp) {
  container.innerHTML = "";
  Object.keys(data[tp]).forEach((ejercicio) => {
    let card = document.createElement("div");
    card.className = "card";
    card.innerText = "Ejercicio " + ejercicio;
    card.onclick = () => mostrarArchivos(tp, ejercicio);
    container.appendChild(card);
  });

  // Botón volver a TPs
  let back = document.createElement("div");
  back.className = "card";
  back.innerHTML = `<i class="fa-solid fa-rotate-left">&nbsp&nbspVOLVER</i>`;
  back.onclick = mostrarTPs;
  container.appendChild(back);
}

// Mostrar archivos dentro de un ejercicio
// Mostrar archivos dentro de un ejercicio (solo el primero)
function mostrarArchivos(tp, ejercicio) {
  container.innerHTML = "";

  // aseguramos que sea array aunque venga como objeto
  let archivos = Object.values(data[tp][ejercicio]);

  let file = archivos[0]; // primer archivo
  if (file) {
    let card = document.createElement("div");
    card.className = "card";
    card.innerText = file;
    card.onclick = () => {
      window.location.href = "/PWD/vista/TP/" + tp + "/" + ejercicio + "/" + file;
    };
    container.appendChild(card);
  }



  // Botón volver a ejercicios
  let back = document.createElement("div");
  back.className = "card";
  back.innerHTML = `<i class="fa-solid fa-rotate-left">&nbsp&nbspVOLVER</i>`;
  back.onclick = () => mostrarEjercicios(tp);
  container.appendChild(back);
}
