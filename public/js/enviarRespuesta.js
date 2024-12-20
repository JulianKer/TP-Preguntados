let form = document.getElementById("form");
let labels = document.querySelectorAll(".option-button");

let btnNext = document.querySelector(".next-button");
let btnVolver = document.querySelector(".back-button");

labels.forEach((label)=>{
    label.addEventListener("click", (e)=>{
        if (!e.target.closest("label")) {
            e.stopPropagation();
            e.preventDefault();
        }else{
            if (btnNext || btnVolver){
                e.preventDefault();
            }else{
                form.submit();
            }
        }
    })
})



/*---------------- reloj ----------------

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

    if (tiempo < 0) {
        clearInterval(intervalo);
        contador.textContent = "¡Tiempo terminado!";
        contador.classList.remove("tiempo-rojo")
        contador.classList.add("animacion-tiempo-completo")

        labels.forEach(label => {
            label.classList.add("deshabilitado");
        });

        setTimeout(() => {
            form.submit()
        }, 4000);
    }
}, 1000);*/
