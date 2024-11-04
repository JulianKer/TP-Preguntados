let filas = document.querySelectorAll(".fila");

filas.forEach((fila)=>{
    fila.addEventListener("click", ()=>{
        const idUsuarioDeCadaFila = fila.id;
        window.location.href = `/perfil/usuario/${idUsuarioDeCadaFila}`;
    })
})