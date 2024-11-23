//---------------- reloj ----------------------------------------------------------------------------------------------------------------------------------------------------------------
let letraS = document.getElementById("letra-s");

let spanSegundos = document.getElementById("segundos");
let segundosNumero = parseInt(spanSegundos.textContent);

// ------------------ para q la barra se ajuste segun el tiempo q viene del back ------------------------------------------------------------------------
const tiempoTotal = 10;
const barraProgreso = document.querySelector(".progreso");
let porcentajeRestante = (segundosNumero / tiempoTotal) * 100;
barraProgreso.style.width = `${porcentajeRestante}%`;

const tiempoTotalAnimation = 10;
let segundosRestantesAnimation = parseInt(spanSegundos.textContent);
const tiempoTranscurrido = tiempoTotalAnimation - segundosRestantesAnimation;

barraProgreso.style.animation = `carga ${tiempoTotal}s linear forwards`;
barraProgreso.style.animationDelay = `-${tiempoTranscurrido}s`;
//------------------------------------------------------------------------------------------------------------------------------------------------------

// y este es para que cuando se vayan descontando segundos, se cambie de color, se envie el form si se termino el tiempo, etc etc-----------------------
let contador = document.getElementById("contador");
const intervalo = setInterval(() => {
    segundosNumero--;

    spanSegundos.textContent = `${segundosNumero}`;

    //const nuevoPorcentaje = (segundosNumero / tiempoTotal) * 100;
    //barraProgreso.style.width = `${nuevoPorcentaje}%`;

    if (segundosNumero === 6){
        contador.classList.add("tiempo-naranja");
        letraS.classList.add("tiempo-naranja");
    }

    if (segundosNumero === 3){
        contador.classList.remove("tiempo-naranja")
        contador.classList.add("tiempo-rojo")

        letraS.classList.remove("tiempo-naranja")
        letraS.classList.add("tiempo-rojo")
    }

    if (segundosNumero <= 0) {
        clearInterval(intervalo);
        letraS.style.display = "none";
        contador.textContent = "¡Tiempo terminado!";
        contador.classList.remove("tiempo-rojo")
        contador.classList.add("animacion-tiempo-completo")

        labels.forEach(label => {
            label.classList.add("deshabilitado");
        });

        setTimeout(() => {
            form.submit()
        }, 2000);
    }
}, 1000);
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------