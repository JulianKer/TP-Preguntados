let popup = document.querySelector(".popupCompleto");
let btnReanudar = document.getElementById("btnReanudar");

if (btnReanudar){
    btnReanudar.addEventListener("click", ()=>{
        popup.style.display = "none";
    })
}


// ----------- para perfil ----------------------
let qr = document.querySelector(".qr");
if (qr){
    qr.addEventListener("click", ()=>{
        popup.style.display = "flex";
    })
}
// --------------------------------------------



// ----------- para ranking ----------------------
let infoIcon = document.getElementById("infoIcon");
if (infoIcon){
    infoIcon.addEventListener("click", ()=>{
        popup.style.display = "flex";
    })
}
// --------------------------------------------

// ----------- para REPORTAR ----------------------
let flagIcon = document.querySelector(".report-button");

if (flagIcon){
    let btnIrAlFormDeReporte = document.getElementById("btnIrAlFormDeReporte");
    let formIrAlReporte = document.getElementById("reportarPreguntaForm");

    flagIcon.addEventListener("click", ()=>{
        popup.style.display = "flex";

        btnIrAlFormDeReporte.addEventListener("click", ()=>{
            formIrAlReporte.submit();
        })
    })
}
// --------------------------------------------
