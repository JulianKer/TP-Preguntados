//---------------- reloj ----------------
let tiempo = 10;
let contador = document.getElementById("contador");

const intervalo = setInterval(() => {
    tiempo--;

    contador.textContent = `00:0${tiempo}`;

    if (tiempo === 6){
        contador.classList.add("tiempo-naranja");
    }

    if (tiempo === 3){
        contador.classList.remove("tiempo-naranja")
        contador.classList.add("tiempo-rojo")
    }

    if (tiempo <= 0) {
        clearInterval(intervalo);
        contador.textContent = "¡Tiempo terminado!";
        contador.classList.remove("tiempo-rojo")
        contador.classList.add("animacion-tiempo-completo")

        labels.forEach(label => {
            label.classList.add("deshabilitado");
        });

        setTimeout(() => {
            /*aca hacer el submit del form con el input hidden incorrecto*/
            form.submit()
        }, 2000);
    }
}, 1000);